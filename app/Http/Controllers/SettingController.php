<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use App\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SettingController extends Controller
{

    public function index()
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'super admin')
        {
            $settings  = Utility::settings();
            $timezones = config('timezones');

            return view('settings.index', compact('settings', 'timezones'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function saveBusinessSettings(Request $request)
    {
        $user = \Auth::user();
        if(\Auth::user()->type == 'super admin')
        {
            if($request->logo)
            {
                $request->validate(
                    [
                        'logo' => 'image|mimes:png|max:20480',
                    ]
                );

                $logoName = 'logo.png';
                $path     = $request->file('logo')->storeAs('uploads/logo/', $logoName);

                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                 $logoName,
                                                                                                                                                 'logo',
                                                                                                                                                 \Auth::user()->creatorId(),
                                                                                                                                             ]
                );
            }
            if($request->small_logo)
            {
                $request->validate(
                    [
                        'small_logo' => 'image|mimes:png|max:20480',
                    ]
                );
                $smallLogoName = 'small_logo.png';
                $path          = $request->file('small_logo')->storeAs('uploads/logo/', $smallLogoName);
                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                 $smallLogoName,
                                                                                                                                                 'small_logo',
                                                                                                                                                 \Auth::user()->creatorId(),
                                                                                                                                             ]
                );
            }
            if($request->favicon)
            {
                $request->validate(
                    [
                        'favicon' => 'image|mimes:png|max:20480',
                    ]
                );
                $favicon = 'favicon.png';
                $path    = $request->file('favicon')->storeAs('uploads/logo/', $favicon);
                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                 $favicon,
                                                                                                                                                 'favicon',
                                                                                                                                                 \Auth::user()->creatorId(),
                                                                                                                                             ]
                );
            }
            if(!empty($request->title_text) || !empty($request->footer_text) || !empty($request->default_language))
            {
                $post = $request->all();
                unset($post['_token'], $post['logo'], $post['small_logo'], $post['favicon']);
                foreach($post as $key => $data)
                {
                    \DB::insert(
                        'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                     $data,
                                                                                                                                                     $key,
                                                                                                                                                     \Auth::user()->creatorId(),
                                                                                                                                                 ]
                    );
                }
            }
        }
        else if(\Auth::user()->type == 'company')
        {
            if($request->company_logo)
            {

                $request->validate(
                    [
                        'company_logo' => 'image|mimes:png|max:20480',
                    ]
                );

                $logoName     = $user->id . '_logo.png';
                $path         = $request->file('company_logo')->storeAs('uploads/logo/', $logoName);
                $company_logo = !empty($request->company_logo) ? $logoName : 'logo.png';

                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                 $logoName,
                                                                                                                                                 'company_logo',
                                                                                                                                                 \Auth::user()->creatorId(),
                                                                                                                                             ]
                );
            }


            if($request->company_small_logo)
            {
                $request->validate(
                    [
                        'company_small_logo' => 'image|mimes:png|max:20480',
                    ]
                );
                $smallLogoName = $user->id . '_small_logo.png';
                $path          = $request->file('company_small_logo')->storeAs('uploads/logo/', $smallLogoName);

                $company_small_logo = !empty($request->company_small_logo) ? $smallLogoName : 'small_logo.png';

                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                 $smallLogoName,
                                                                                                                                                 'company_small_logo',
                                                                                                                                                 \Auth::user()->creatorId(),
                                                                                                                                             ]
                );
            }

            if($request->company_favicon)
            {
                $request->validate(
                    [
                        'company_favicon' => 'image|mimes:png|max:20480',
                    ]
                );
                $favicon = $user->id . '_favicon.png';
                $path    = $request->file('company_favicon')->storeAs('uploads/logo/', $favicon);

                $company_favicon = !empty($request->favicon) ? $favicon : 'favicon.png';

                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                 $favicon,
                                                                                                                                                 'company_favicon',
                                                                                                                                                 \Auth::user()->creatorId(),
                                                                                                                                             ]
                );
            }

            if(!empty($request->title_text))
            {
                $post = $request->all();

                unset($post['_token'], $post['company_logo'], $post['company_small_logo'], $post['company_favicon']);
                foreach($post as $key => $data)
                {
                    \DB::insert(
                        'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                     $data,
                                                                                                                                                     $key,
                                                                                                                                                     \Auth::user()->creatorId(),
                                                                                                                                                 ]
                    );
                }
            }

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        return redirect()->back()->with('success', 'Business setting succefully saved.');
    }

    public function saveCompanySettings(Request $request)
    {

        if(\Auth::user()->type == 'company')
        {
            $request->validate(
                [
                    'company_name' => 'required|string|max:50',
                    'company_email' => 'required',
                    'company_email_from_name' => 'required|string',
                    'registration_number' => 'required|string|max:50',
                    'vat_number' => 'required|string|max:50',
                ]
            );
            $post = $request->all();
            unset($post['_token']);

            foreach($post as $key => $data)
            {
                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                 $data,
                                                                                                                                                 $key,
                                                                                                                                                 \Auth::user()->creatorId(),
                                                                                                                                             ]
                );
            }

            $arrEnv = [
                'TIMEZONE' => $request->timezone,
            ];

            Utility::setEnvironmentValue($arrEnv);

            return redirect()->back()->with('success', __('Setting successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function saveEmailSettings(Request $request)
    {

        if(\Auth::user()->type == 'super admin')
        {
            $request->validate(
                [
                    'mail_driver' => 'required|string|max:50',
                    'mail_host' => 'required|string|max:50',
                    'mail_port' => 'required|string|max:50',
                    'mail_username' => 'required|string|max:50',
                    'mail_password' => 'required|string|max:50',
                    'mail_encryption' => 'required|string|max:50',
                    'mail_from_address' => 'required|string|max:50',
                    'mail_from_name' => 'required|string|max:50',
                ]
            );

            $arrEnv = [
                'MAIL_DRIVER' => $request->mail_driver,
                'MAIL_HOST' => $request->mail_host,
                'MAIL_PORT' => $request->mail_port,
                'MAIL_USERNAME' => $request->mail_username,
                'MAIL_PASSWORD' => $request->mail_password,
                'MAIL_ENCRYPTION' => $request->mail_encryption,
                'MAIL_FROM_NAME' => $request->mail_from_name,
                'MAIL_FROM_ADDRESS' => $request->mail_from_address,
            ];
            Utility::setEnvironmentValue($arrEnv);

            return redirect()->back()->with('success', __('Setting successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }


    }

    public function saveSystemSettings(Request $request)
    {

        if(\Auth::user()->type == 'company')
        {
            $request->validate(
                [
                    'site_currency' => 'required',
                ]
            );
            $post = $request->all();
            unset($post['_token']);

            foreach($post as $key => $data)
            {
                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                                                 $data,
                                                                                                                                                                                 $key,
                                                                                                                                                                                 \Auth::user()->creatorId(),
                                                                                                                                                                                 date('Y-m-d H:i:s'),
                                                                                                                                                                                 date('Y-m-d H:i:s'),
                                                                                                                                                                             ]
                );
            }

            return redirect()->back()->with('success', __('Setting successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function savePusherSettings(Request $request)
    {
        if(\Auth::user()->type == 'super admin')
        {
            $request->validate(
                [
                    'pusher_app_id' => 'required',
                    'pusher_app_key' => 'required',
                    'pusher_app_secret' => 'required',
                    'pusher_app_cluster' => 'required',
                ]
            );

            $arrEnvStripe = [
                'PUSHER_APP_ID' => $request->pusher_app_id,
                'PUSHER_APP_KEY' => $request->pusher_app_key,
                'PUSHER_APP_SECRET' => $request->pusher_app_secret,
                'PUSHER_APP_CLUSTER' => $request->pusher_app_cluster,
            ];

            $envStripe = Utility::setEnvironmentValue($arrEnvStripe);

            if($envStripe)
            {
                return redirect()->back()->with('success', __('Pusher successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', 'Something went wrong.');
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }


    }

    public function savePaymentSettings(Request $request)
    {
        if(\Auth::user()->type == 'super admin')
        {
            $request->validate(
                [
                    'currency' => 'required|string|max:255',
                    'currency_symbol' => 'required|string|max:255',
                ]
            );

            if(isset($request->enable_stripe) && $request->enable_stripe == 'on')
            {
                $request->validate(
                    [
                        'stripe_key' => 'required|string|max:255',
                        'stripe_secret' => 'required|string|max:255',
                    ]
                );
            }
            elseif(isset($request->enable_paypal) && $request->enable_paypal == 'on')
            {
                $request->validate(
                    [
                        'paypal_mode' => 'required|string',
                        'paypal_client_id' => 'required|string',
                        'paypal_secret_key' => 'required|string',
                    ]
                );
            }


            $arrEnv = [
                'CURRENCY_SYMBOL' => $request->currency_symbol,
                'CURRENCY' => $request->currency,
                'ENABLE_STRIPE' => $request->enable_stripe ?? 'off',
                'STRIPE_KEY' => $request->stripe_key,
                'STRIPE_SECRET' => $request->stripe_secret,
                'ENABLE_PAYPAL' => $request->enable_paypal ?? 'off',
                'PAYPAL_MODE' => $request->paypal_mode,
                'PAYPAL_CLIENT_ID' => $request->paypal_client_id,
                'PAYPAL_SECRET_KEY' => $request->paypal_secret_key,

            ];
            Utility::setEnvironmentValue($arrEnv);

            $post = $request->all();
            unset($post['_token'], $post['stripe_key'], $post['stripe_secret']);

            foreach($post as $key => $data)
            {
                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                                                 $data,
                                                                                                                                                                                 $key,
                                                                                                                                                                                 \Auth::user()->creatorId(),
                                                                                                                                                                                 date('Y-m-d H:i:s'),
                                                                                                                                                                                 date('Y-m-d H:i:s'),
                                                                                                                                                                             ]
                );
            }

            return redirect()->back()->with('success', __('Payment setting successfully saved.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function saveCompanyPaymentSettings(Request $request)
    {

        if(isset($request->enable_stripe) && $request->enable_stripe == 'on')
        {
            $request->validate(
                [
                    'stripe_key' => 'required|string',
                    'stripe_secret' => 'required|string',
                ]
            );
        }
        elseif(isset($request->enable_paypal) && $request->enable_paypal == 'on')
        {
            $request->validate(
                [
                    'paypal_mode' => 'required|string',
                    'paypal_client_id' => 'required|string',
                    'paypal_secret_key' => 'required|string',
                ]
            );
        }


        $post                  = $request->all();
        $post['enable_paypal'] = isset($request->enable_paypal) ? $request->enable_paypal : '';
        $post['enable_stripe'] = isset($request->enable_stripe) ? $request->enable_stripe : '';
        unset($post['_token']);

        foreach($post as $key => $data)
        {

            \DB::insert(
                'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                                             $data,
                                                                                                                                                                             $key,
                                                                                                                                                                             \Auth::user()->creatorId(),
                                                                                                                                                                             date('Y-m-d H:i:s'),
                                                                                                                                                                             date('Y-m-d H:i:s'),
                                                                                                                                                                         ]
            );
        }

        return redirect()->back()->with('success', __('Payment setting successfully updated.'));

    }

    public function testMail()
    {
        return view('settings.test_mail');
    }


    public function testSendMail(Request $request)
    {
        $validator = \Validator::make($request->all(), ['email' => 'required|email']);
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        try
        {
            Mail::to($request->email)->send(new TestMail());
        }
        catch(\Exception $e)
        {
            $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
        }

        return redirect()->back()->with('success', __('Email send Successfully.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));

    }
}
