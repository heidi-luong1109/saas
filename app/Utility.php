<?php

namespace App;

use App\Mail\CommonEmailTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class Utility extends Model
{
    public static function settings()
    {
        $data = DB::table('settings');
        if(\Auth::check())
        {
            $userId = \Auth::user()->creatorId();
            $data   = $data->where('created_by', '=', $userId);
        }
        else
        {
            $data = $data->where('created_by', '=', 1);
        }
        $data     = $data->get();
        $settings = [
            "site_currency" => "USD",
            "site_currency_symbol" => "$",
            "site_currency_symbol_position" => "pre",
            "site_date_format" => "M j, Y",
            "site_time_format" => "g:i A",
            "employee_prefix" => "#EMP",
            "client_prefix" => "#CLT",
            "estimate_prefix" => "#EST",
            "invoice_prefix" => "#INV",
            "company_name" => "",
            "company_address" => "",
            "company_city" => "",
            "company_state" => "",
            "company_zipcode" => "",
            "company_country" => "",
            "company_telephone" => "",
            "company_email" => "",
            "company_email_from_name" => "",
            "footer_title" => "",
            "footer_notes" => "",
            "invoice_template" => "template1",
            "invoice_color" => "fffff",
            "estimate_template" => "template1",
            "estimate_color" => "fffff",
            "company_start_time" => "09:00",
            "company_end_time" => "18:00",
            "default_language" => "en",
            "registration_number" => "",
            "vat_number" => "",
        ];

        foreach($data as $row)
        {
            $settings[$row->name] = $row->value;
        }

        return $settings;
    }

    public static function languages()
    {
        $dir     = base_path() . '/resources/lang/';
        $glob    = glob($dir . "*", GLOB_ONLYDIR);
        $arrLang = array_map(
            function ($value) use ($dir){
                return str_replace($dir, '', $value);
            }, $glob
        );
        $arrLang = array_map(
            function ($value) use ($dir){
                return preg_replace('/[0-9]+/', '', $value);
            }, $arrLang
        );
        $arrLang = array_filter($arrLang);

        return $arrLang;
    }

    public static function getValByName($key)
    {
        $setting = Utility::settings();
        if(!isset($setting[$key]) || empty($setting[$key]))
        {
            $setting[$key] = '';
        }

        return $setting[$key];
    }

    public static function setEnvironmentValue(array $values)
    {
        $envFile = app()->environmentFilePath();
        $str     = file_get_contents($envFile);
        if(count($values) > 0)
        {
            foreach($values as $envKey => $envValue)
            {
                $keyPosition       = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine           = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                // If key does not exist, add it
                if(!$keyPosition || !$endOfLinePosition || !$oldLine)
                {
                    $str .= "{$envKey}='{$envValue}'\n";
                }
                else
                {
                    $str = str_replace($oldLine, "{$envKey}='{$envValue}'", $str);
                }
            }
        }
        $str = substr($str, 0, -1);
        $str .= "\n";
        if(!file_put_contents($envFile, $str))
        {
            return false;
        }

        return true;
    }

    public static function templateData()
    {
        $arr              = [];
        $arr['colors']    = [
            '003580',
            '666666',
            'f2f6fa',
            'f50102',
            'f9b034',
            'fbdd03',
            'c1d82f',
            '37a4e4',
            '8a7966',
            '6a737b',
            '050f2c',
            '0e3666',
            '3baeff',
            '3368e6',
            'b84592',
            'f64f81',
            'f66c5f',
            'fac168',
            '46de98',
            '40c7d0',
            'be0028',
            '2f9f45',
            '371676',
            '52325d',
            '511378',
            '0f3866',
            '48c0b6',
            '297cc0',
            'ffffff',
            '000',
        ];
        $arr['templates'] = [
            "template1" => "New York",
            "template2" => "Toronto",
            "template3" => "Rio",
            "template4" => "London",
            "template5" => "Istanbul",
            "template6" => "Mumbai",
            "template7" => "Hong Kong",
            "template8" => "Tokyo",
            "template9" => "Sydney",
            "template10" => "Paris",
        ];

        return $arr;
    }

    public static function priceFormat($settings, $price)
    {
        return (($settings['site_currency_symbol_position'] == "pre") ? $settings['site_currency_symbol'] : '') . number_format($price, 2) . (($settings['site_currency_symbol_position'] == "post") ? $settings['site_currency_symbol'] : '');
    }

    public static function currencySymbol($settings)
    {
        return $settings['site_currency_symbol'];
    }

    public static function dateFormat($settings, $date)
    {
        return date($settings['site_date_format'], strtotime($date));
    }

    public static function timeFormat($settings, $time)
    {
        return date($settings['site_time_format'], strtotime($time));
    }

    public static function invoiceNumberFormat($settings, $number)
    {
        return $settings["invoice_prefix"] . sprintf("%05d", $number);
    }

    public static function estimateNumberFormat($settings, $number)
    {
        return $settings["estimate_prefix"] . sprintf("%05d", $number);
    }

    public static function proposalNumberFormat($settings, $number)
    {
        return $settings["proposal_prefix"] . sprintf("%05d", $number);
    }

    public static function billNumberFormat($settings, $number)
    {
        return $settings["bill_prefix"] . sprintf("%05d", $number);
    }

    public static function tax($taxes)
    {
        $taxArr = explode(',', $taxes);
        $taxes  = [];
        foreach($taxArr as $tax)
        {
            $taxes[] = TaxRate::find($tax);
        }

        return $taxes;
    }

    public static function totalTaxRate($taxes)
    {
        $taxArr  = explode(',', $taxes);
        $taxRate = 0;
        foreach($taxArr as $tax)
        {
            $tax     = TaxRate::find($tax);
            $taxRate += !empty($tax->rate) ? $tax->rate : 0;
        }

        return $taxRate;
    }

    public static function taxRate($taxRate, $price, $quantity)
    {
        return ($taxRate / 100) * ($price * $quantity);
    }

    // get font-color code accourding to bg-color
    public static function hex2rgb($hex)
    {
        $hex = str_replace("#", "", $hex);

        if(strlen($hex) == 3)
        {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        }
        else
        {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $rgb = array(
            $r,
            $g,
            $b,
        );

        //return implode(",", $rgb); // returns the rgb values separated by commas
        return $rgb; // returns an array with the rgb values
    }


    public static function getFontColor($color_code)
    {
        $rgb = self::hex2rgb($color_code);
        $R   = $G = $B = $C = $L = $color = '';

        $R = (floor($rgb[0]));
        $G = (floor($rgb[1]));
        $B = (floor($rgb[2]));

        $C = [
            $R / 255,
            $G / 255,
            $B / 255,
        ];

        for($i = 0; $i < count($C); ++$i)
        {
            if($C[$i] <= 0.03928)
            {
                $C[$i] = $C[$i] / 12.92;
            }
            else
            {
                $C[$i] = pow(($C[$i] + 0.055) / 1.055, 2.4);
            }
        }

        $L = 0.2126 * $C[0] + 0.7152 * $C[1] + 0.0722 * $C[2];

        if($L > 0.179)
        {
            $color = 'black';
        }
        else
        {
            $color = 'white';
        }

        return $color;
    }

    public static function delete_directory($dir)
    {
        if(!file_exists($dir))
        {
            return true;
        }
        if(!is_dir($dir))
        {
            return unlink($dir);
        }
        foreach(scandir($dir) as $item)
        {
            if($item == '.' || $item == '..')
            {
                continue;
            }
            if(!self::delete_directory($dir . DIRECTORY_SEPARATOR . $item))
            {
                return false;
            }
        }

        return rmdir($dir);
    }

    public static function get_messenger_packages_migration()
    {
        $totalMigration = 0;
        $messengerPath  = glob(base_path() . '/vendor/munafio/chatify/database/migrations' . DIRECTORY_SEPARATOR . '*.php');
        if(!empty($messengerPath))
        {
            $messengerMigration = str_replace('.php', '', $messengerPath);
            $totalMigration     = count($messengerMigration);
        }

        return $totalMigration;

    }

    // Email Template Modules Function START
    // Common Function That used to send mail with check all cases
    public static function sendEmailTemplate($emailTemplate, $mailTo, $obj)
    {
        $usr = \Auth::user();

        //Remove Current Login user Email don't send mail to them
        unset($mailTo[$usr->id]);

        $mailTo = array_values($mailTo);

        if($usr->type != 'super admin')
        {
            // find template is exist or not in our record
            $template = EmailTemplate::where('slug', $emailTemplate)->first();

            if(isset($template) && !empty($template))
            {
                // check template is active or not by company
                $is_active = UserEmailTemplate::where('template_id', '=', $template->id)->where('user_id', '=', $usr->creatorId())->first();

                if($is_active->is_active == 1)
                {
                    $settings = self::settings();

                    // get email content language base
                    $content = EmailTemplateLang::where('parent_id', '=', $template->id)->where('lang', 'LIKE', $usr->lang)->first();

                    $content->from = $template->from;
                    if(!empty($content->content))
                    {

                        $content->content = self::replaceVariable($content->content, $obj);
                        // send email
                        try
                        {
                            Mail::to($mailTo)->send(new CommonEmailTemplate($content, $settings));
                        }
                        catch(\Exception $e)
                        {

                            $error = __('E-Mail has been not sent due to SMTP configuration');
                        }

                        if(isset($error))
                        {
                            $arReturn = [
                                'is_success' => false,
                                'error' => $error,
                            ];
                        }
                        else
                        {
                            $arReturn = [
                                'is_success' => true,
                                'error' => false,
                            ];
                        }
                    }
                    else
                    {
                        $arReturn = [
                            'is_success' => false,
                            'error' => __('Mail not send, email is empty'),
                        ];
                    }

                    return $arReturn;
                }
                else
                {
                    return [
                        'is_success' => true,
                        'error' => false,
                    ];
                }
            }
            else
            {
                return [
                    'is_success' => false,
                    'error' => __('Mail not send, email not found'),
                ];
            }
        }
    }

    // used for replace email variable (parameter 'template_name','id(get particular record by id for data)')
    public static function replaceVariable($content, $obj)
    {
        $arrVariable = [
            '{lead_name}',
            '{lead_email}',
            '{lead_subject}',
            '{lead_pipeline}',
            '{lead_stage}',
            '{deal_name}',
            '{deal_pipeline}',
            '{deal_stage}',
            '{deal_status}',
            '{deal_price}',
            '{estimation_id}',
            '{estimation_client}',
            '{estimation_category}',
            '{estimation_issue_date}',
            '{estimation_expiry_date}',
            '{estimation_status}',
            '{project_title}',
            '{project_category}',
            '{project_price}',
            '{project_client}',
            '{project_assign_user}',
            '{project_start_date}',
            '{project_due_date}',
            '{project_lead}',
            '{project}',
            '{task_title}',
            '{task_priority}',
            '{task_start_date}',
            '{task_due_date}',
            '{task_stage}',
            '{task_assign_user}',
            '{task_description}',
            '{invoice_id}',
            '{invoice_client}',
            '{invoice_issue_date}',
            '{invoice_due_date}',
            '{invoice_status}',
            '{invoice_total}',
            '{invoice_sub_total}',
            '{invoice_due_amount}',
            '{payment_total}',
            '{payment_date}',
            '{credit_note_date}',
            '{credit_amount}',
            '{credit_description}',
            '{support_title}',
            '{assign_user}',
            '{support_priority}',
            '{support_end_date}',
            '{support_description}',
            '{contract_subject}',
            '{contract_client}',
            '{contract_value}',
            '{contract_start_date}',
            '{contract_end_date}',
            '{contract_description}',
            '{app_name}',
            '{company_name}',
            '{app_url}',
            '{email}',
            '{password}',
        ];
        $arrValue    = [
            'lead_name' => '-',
            'lead_email' => '-',
            'lead_subject' => '-',
            'lead_pipeline' => '-',
            'lead_stage' => '-',
            'deal_name' => '-',
            'deal_pipeline' => '-',
            'deal_stage' => '-',
            'deal_status' => '-',
            'deal_price' => '-',
            'estimation_id' => '-',
            'estimation_client' => '-',
            'estimation_category' => '-',
            'estimation_issue_date' => '-',
            'estimation_expiry_date' => '-',
            'estimation_status' => '-',
            'project_title' => '-',
            'project_category' => '-',
            'project_price' => '-',
            'project_client' => '-',
            'project_assign_user' => '-',
            'project_start_date' => '-',
            'project_due_date' => '-',
            'project_lead' => '-',
            'project' => '-',
            'task_title' => '-',
            'task_priority' => '-',
            'task_start_date' => '-',
            'task_due_date' => '-',
            'task_stage' => '-',
            'task_assign_user' => '-',
            'task_description' => '-',
            'invoice_id' => '-',
            'invoice_client' => '-',
            'invoice_issue_date' => '-',
            'invoice_due_date' => '-',
            'invoice_status' => '-',
            'invoice_total' => '-',
            'invoice_sub_total' => '-',
            'invoice_due_amount' => '-',
            'payment_total' => '-',
            'payment_date' => '-',
            'credit_note_date' => '-',
            'credit_amount' => '-',
            'credit_description' => '-',
            'support_title' => '-',
            'assign_user' => '-',
            'support_priority' => '-',
            'support_end_date' => '-',
            'support_description' => '-',
            'contract_subject' => '-',
            'contract_client' => '-',
            'contract_value' => '-',
            'contract_start_date' => '-',
            'contract_end_date' => '-',
            'contract_description' => '-',
            'app_name' => '-',
            'company_name' => '-',
            'app_url' => '-',
            'email' => '-',
            'password' => '-',
        ];

        foreach($obj as $key => $val)
        {
            $arrValue[$key] = $val;
        }

        $arrValue['app_name']     = env('APP_NAME');
        $arrValue['company_name'] = self::settings()['company_name'];
        $arrValue['app_url']      = '<a href="' . env('APP_URL') . '" target="_blank">' . env('APP_URL') . '</a>';

        return str_replace($arrVariable, array_values($arrValue), $content);
    }

    // Make Entry in email_tempalte_lang table when create new language
    public static function makeEmailLang($lang)
    {
        $template = EmailTemplate::all();
        foreach($template as $t)
        {
            $default_lang                 = EmailTemplateLang::where('parent_id', '=', $t->id)->where('lang', 'LIKE', 'en')->first();
            $emailTemplateLang            = new EmailTemplateLang();
            $emailTemplateLang->parent_id = $t->id;
            $emailTemplateLang->lang      = $lang;
            $emailTemplateLang->subject   = $default_lang->subject;
            $emailTemplateLang->content   = $default_lang->content;
            $emailTemplateLang->save();
        }
    }
    // Email Template Modules Function END
}
