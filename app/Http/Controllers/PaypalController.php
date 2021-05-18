<?php

namespace App\Http\Controllers;

use App\Coupon;
use App\Invoice;
use App\InvoicePayment;
use App\Order;
use App\Plan;
use App\UserCoupon;
use App\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PaypalController extends Controller
{
    private $_api_context;

    public function setApiContext()
    {
        $user = Auth::user();

        $paypal_conf = config('paypal');

        $paypal_conf['settings']['mode'] = env('PAYPAL_MODE');

        $this->_api_context = new ApiContext(
            new OAuthTokenCredential(
                $paypal_conf['client_id'], $paypal_conf['secret_key']
            )
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }


    public function planPayWithPaypal(Request $request)
    {
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan   = Plan::find($planID);
        if($plan)
        {
            try
            {
                $coupon_id = null;
                $price     = $plan->price;
                if(!empty($request->coupon))
                {
                    $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                    if(!empty($coupons))
                    {
                        $usedCoupun     = $coupons->used_coupon();
                        $discount_value = ($plan->price / 100) * $coupons->discount;
                        $price          = $plan->price - $discount_value;
                        if($coupons->limit == $usedCoupun)
                        {
                            return redirect()->back()->with('error', __('This coupon code has expired.'));
                        }
                        $coupon_id = $coupons->id;
                    }
                    else
                    {
                        return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                    }
                }
                $this->setApiContext();
                $name  = $plan->name;
                $payer = new Payer();
                $payer->setPaymentMethod('paypal');
                $item_1 = new Item();
                $item_1->setName($name)->setCurrency(env('CURRENCY'))->setQuantity(1)->setPrice($price);
                $item_list = new ItemList();
                $item_list->setItems([$item_1]);
                $amount = new Amount();
                $amount->setCurrency(env('CURRENCY'))->setTotal($price);
                $transaction = new Transaction();
                $transaction->setAmount($amount)->setItemList($item_list)->setDescription($name);
                $redirect_urls = new RedirectUrls();
                $redirect_urls->setReturnUrl(
                    route(
                        'plan.get.payment.status', [
                                                     $plan->id,
                                                     'coupon_id' => $coupon_id,
                                                 ]
                    )
                )->setCancelUrl(
                    route(
                        'plan.get.payment.status', [
                                                     $plan->id,
                                                     'coupon_id' => $coupon_id,
                                                 ]
                    )
                );
                $payment = new Payment();
                $payment->setIntent('Sale')->setPayer($payer)->setRedirectUrls($redirect_urls)->setTransactions([$transaction]);
                try
                {
                    $payment->create($this->_api_context);
                }
                catch(\PayPal\Exception\PayPalConnectionException $ex) //PPConnectionException
                {
                    if(config('app.debug'))
                    {
                        return redirect()->route('stripe', \Illuminate\Support\Facades\Crypt::encrypt($plan->id))->with('error', __('Connection timeout'));
                    }
                    else
                    {
                        return redirect()->route('stripe', \Illuminate\Support\Facades\Crypt::encrypt($plan->id))->with('error', __('Some error occur, sorry for inconvenient'));
                    }
                }
                foreach($payment->getLinks() as $link)
                {
                    if($link->getRel() == 'approval_url')
                    {
                        $redirect_url = $link->getHref();
                        break;
                    }
                }
                Session::put('paypal_payment_id', $payment->getId());
                if(isset($redirect_url))
                {
                    return Redirect::away($redirect_url);
                }

                return redirect()->route('payment', \Illuminate\Support\Facades\Crypt::encrypt($plan->id))->with('error', __('Unknown error occurred'));
            }
            catch(\Exception $e)
            {
                return redirect()->route('plan.index')->with('error', __($e->getMessage()));
            }
        }
        else
        {
            return redirect()->route('plan.index')->with('error', __('Plan is deleted.'));
        }
    }

    public function planGetPaymentStatus(Request $request, $plan_id)
    {
        $user = Auth::user();
        $plan = Plan::find($plan_id);
        if($plan)
        {
            $this->setApiContext();
            $payment_id = Session::get('paypal_payment_id');
            Session::forget('paypal_payment_id');
            if(empty($request->PayerID || empty($request->token)))
            {
                return redirect()->route('payment', \Illuminate\Support\Facades\Crypt::encrypt($plan->id))->with('error', __('Payment failed'));
            }
            $payment   = Payment::get($payment_id, $this->_api_context);
            $execution = new PaymentExecution();
            $execution->setPayerId($request->PayerID);
            try
            {
                $result  = $payment->execute($execution, $this->_api_context)->toArray();
                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                $status  = ucwords(str_replace('_', ' ', $result['state']));
                if($request->has('coupon_id') && $request->coupon_id != '')
                {
                    $coupons = Coupon::find($request->coupon_id);
                    if(!empty($coupons))
                    {
                        $userCoupon         = new UserCoupon();
                        $userCoupon->user   = $user->id;
                        $userCoupon->coupon = $coupons->id;
                        $userCoupon->order  = $orderID;
                        $userCoupon->save();
                        $usedCoupun = $coupons->used_coupon();
                        if($coupons->limit <= $usedCoupun)
                        {
                            $coupons->is_active = 0;
                            $coupons->save();
                        }
                    }
                }
                if($result['state'] == 'approved')
                {

                    $order                 = new Order();
                    $order->order_id       = $orderID;
                    $order->name           = $user->name;
                    $order->card_number    = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year  = '';
                    $order->plan_name      = $plan->name;
                    $order->plan_id        = $plan->id;
                    $order->price          = $result['transactions'][0]['amount']['total'];
                    $order->price_currency = env('CURRENCY');
                    $order->txn_id         = $payment_id;
                    $order->payment_type   = __('PAYPAL');
                    $order->payment_status = $result['state'];
                    $order->receipt        = '';
                    $order->user_id        = $user->id;
                    $order->save();
                    $assignPlan = $user->assignPlan($plan->id);
                    if($assignPlan['is_success'])
                    {
                        return redirect()->route('plan.index')->with('success', __('Plan activated Successfully.'));
                    }
                    else
                    {
                        return redirect()->route('plan.index')->with('error', __($assignPlan['error']));
                    }
                }
                else
                {
                    return redirect()->route('plan.index')->with('error', __('Transaction has been ' . __($status)));
                }
            }
            catch(\Exception $e)
            {
                return redirect()->route('plan.index')->with('error', __('Transaction has been failed.'));
            }
        }
        else
        {
            return redirect()->route('plan.index')->with('error', __('Plan is deleted.'));
        }
    }

    public function clientPayWithPaypal(Request $request, $invoice_id)
    {
        $settings = DB::table('settings')->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('value', 'name');
        $user     = Auth::user();

        $get_amount = $request->amount;

        $request->validate(['amount' => 'required|numeric|min:0']);


        $invoice = Invoice::find($invoice_id);

        if($invoice)
        {
            if($get_amount > $invoice->getDue())
            {
                return redirect()->back()->with('error', __('Invalid amount.'));
            }
            else
            {
                $this->setApiContext();

                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                $name = Utility::invoiceNumberFormat($settings, $invoice->invoice_id);

                $payer = new Payer();
                $payer->setPaymentMethod('paypal');

                $item_1 = new Item();
                $item_1->setName($name)->setCurrency(Utility::getValByName('site_currency'))->setQuantity(1)->setPrice($get_amount);

                $item_list = new ItemList();
                $item_list->setItems([$item_1]);

                $amount = new Amount();
                $amount->setCurrency(Utility::getValByName('site_currency'))->setTotal($get_amount);

                $transaction = new Transaction();
                $transaction->setAmount($amount)->setItemList($item_list)->setDescription($name)->setInvoiceNumber($orderID);

                $redirect_urls = new RedirectUrls();
                $redirect_urls->setReturnUrl(
                    route(
                        'client.get.payment.status', $invoice->id
                    )
                )->setCancelUrl(
                    route(
                        'client.get.payment.status', $invoice->id
                    )
                );

                $payment = new Payment();
                $payment->setIntent('Sale')->setPayer($payer)->setRedirectUrls($redirect_urls)->setTransactions([$transaction]);

                try
                {

                    $payment->create($this->_api_context);
                }
                catch(\PayPal\Exception\PayPalConnectionException $ex) //PPConnectionException
                {
                    if(\Config::get('app.debug'))
                    {
                        return redirect()->route(
                            'invoices.show', \Crypt::encrypt($invoice_id)
                        )->with('error', __('Connection timeout'));
                    }
                    else
                    {
                        return redirect()->route(
                            'invoices.show', \Crypt::encrypt($invoice_id)
                        )->with('error', __('Some error occur, sorry for inconvenient'));
                    }
                }
                foreach($payment->getLinks() as $link)
                {
                    if($link->getRel() == 'approval_url')
                    {
                        $redirect_url = $link->getHref();
                        break;
                    }
                }
                Session::put('paypal_payment_id', $payment->getId());
                if(isset($redirect_url))
                {
                    return Redirect::away($redirect_url);
                }

                return redirect()->route(
                    'invoices.show', \Crypt::encrypt($invoice_id)
                )->with('error', __('Unknown error occurred'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function clientGetPaymentStatus(Request $request, $invoice_id)
    {
        $settings = DB::table('settings')->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('value', 'name');
        $user     = Auth::user();

        $invoice = Invoice::find($invoice_id);

        $this->setApiContext();

        $payment_id = Session::get('paypal_payment_id');

        Session::forget('paypal_payment_id');

        if(empty($request->PayerID || empty($request->token)))
        {
            return redirect()->route(
                'invoice.show', $invoice_id
            )->with('error', __('Payment failed'));
        }

        $payment = Payment::get($payment_id, $this->_api_context);

        $execution = new PaymentExecution();
        $execution->setPayerId($request->PayerID);

        try
        {
            $result   = $payment->execute($execution, $this->_api_context)->toArray();
            $order_id = strtoupper(str_replace('.', '', uniqid('', true)));
            $status   = ucwords(str_replace('_', ' ', $result['state']));
            if($result['state'] == 'approved')
            {
                $amount = $result['transactions'][0]['amount']['total'];
            }
            else
            {
                $amount = isset($result['transactions'][0]['amount']['total']) ? $result['transactions'][0]['amount']['total'] : '0.00';
            }


            if($result['state'] == 'approved')
            {
                $payments = InvoicePayment::create(
                    [
                        'invoice' => $invoice->id,
                        'date' => date('Y-m-d'),
                        'amount' => $amount,
                        'payment_method' => 1,
                        'transaction' => $order_id,
                        'payment_type' => __('PAYPAL'),
                        'receipt' => '',
                        'notes' => __('Invoice') . ' ' . Utility::invoiceNumberFormat($settings, $invoice->invoice_id),
                    ]
                );

                $invoice = Invoice::find($invoice->id);
                if($invoice->getDue() <= 0.0)
                {
                    Invoice::change_status($invoice->id, 5);
                }
                elseif($invoice->getDue() > 0)
                {
                    Invoice::change_status($invoice->id, 4);
                }
                else
                {
                    Invoice::change_status($invoice->id, 3);
                }


                return redirect()->route(
                    'invoice.show', \Crypt::encrypt($invoice_id)
                )->with('success', __('Payment successfully added'));
            }
            else
            {
                return redirect()->route(
                    'invoice.show', \Crypt::encrypt($invoice_id)
                )->with('error', __('Transaction has been ' . $status));
            }

        }
        catch(\Exception $e)
        {

            return redirect()->route(
                'invoice.show', \Crypt::encrypt($invoice_id)
            )->with('error', __('Transaction has been failed.'));
        }

    }
}
