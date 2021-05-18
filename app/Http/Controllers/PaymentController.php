<?php

namespace App\Http\Controllers;

use App\Payment;
use App\PaymentMethod;
use App\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function index()
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'client')
        {
            if(\Auth::user()->type == 'company')
            {
                $payments = Payment::where('created_by', \Auth::user()->creatorId())->get();
            }
            else
            {
                $payments = Payment::where('client', \Auth::user()->id)->get();
            }


            return view('payment.index', compact('payments'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function create()
    {
        $clients = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'client')->get()->pluck('name', 'id');
        $clients->prepend('--', 0);
        $paymentMethod = PaymentMethod::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

        return view('payment.create', compact('clients', 'paymentMethod'));
    }


    public function store(Request $request)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required',
                                   'amount' => 'required',
                                   'payment_method' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $payment                 = new Payment();
            $payment->date           = $request->date;
            $payment->amount         = $request->amount;
            $payment->payment_method = $request->payment_method;
            $payment->client         = $request->client;
            $payment->reference      = $request->reference;
            $payment->description    = $request->description;
            $payment->created_by     = \Auth::user()->creatorId();
            $payment->save();

            //        $category            = ProductServiceCategory::where('id', $request->category_id)->first();
            //        $revenue->payment_id = $revenue->id;
            //        $revenue->type       = 'Payment';
            //        $revenue->category   = $category->name;
            //        $revenue->user_id    = $revenue->customer_id;
            //        $revenue->user_type  = 'Customer';
            //
            //        Transaction::addTransaction($revenue);

            //        $customer         = Customer::where('id', $request->customer_id)->first();
            //        $payment          = new InvoicePayment();
            //        $payment->name    = !empty($customer) ? $customer['name'] : '';
            //        $payment->date    = \Auth::user()->dateFormat($request->date);
            //        $payment->amount  = \Auth::user()->priceFormat($request->amount);
            //        $payment->invoice = '';

            //        if(!empty($customer))
            //        {
            //            Utility::userBalance('customer', $customer->id, $revenue->amount, 'credit');
            //        }
            //
            //        Utility::bankAccountBalance($request->account_id, $revenue->amount, 'credit');
            //
            //        try
            //        {
            //            Mail::to($customer['email'])->send(new InvoicePaymentCreate($payment));
            //        }
            //        catch(\Exception $e)
            //        {
            //            $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
            //        }

            return redirect()->route('payment.index')->with('success', __('Payment successfully created.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(Payment $payment)
    {
        //
    }


    public function edit(Payment $payment)
    {
        $clients = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'client')->get()->pluck('name', 'id');
        $clients->prepend('--', 0);
        $paymentMethod = PaymentMethod::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

        return view('payment.edit', compact('clients', 'paymentMethod', 'payment'));
    }


    public function update(Request $request, Payment $payment)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required',
                                   'amount' => 'required',
                                   'payment_method' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $payment->date           = $request->date;
            $payment->amount         = $request->amount;
            $payment->payment_method = $request->payment_method;
            $payment->client         = $request->client;
            $payment->reference      = $request->reference;
            $payment->description    = $request->description;
            $payment->save();

            return redirect()->route('payment.index')->with('success', __('Payment successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function destroy(Payment $payment)
    {
        if(\Auth::user()->type == 'company')
        {
            $payment->delete();

            return redirect()->route('payment.index')->with('success', __('Payment successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
