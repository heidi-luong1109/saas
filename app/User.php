<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use Notifiable;


    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'avatar',
        'lang',
        'delete_status',
        'plan',
        'plan_expire_date',
        'created_by',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function employeeDetail()
    {
        return $this->hasOne('App\Employee', 'user_id', 'id');
    }

    public function clientDetail()
    {
        return $this->hasOne('App\Client', 'user_id', 'id');
    }

    public function authId()
    {
        return $this->id;
    }

    public function creatorId()
    {
        if($this->type == 'company' || $this->type == 'super admin')
        {
            return $this->id;
        }
        else
        {
            return $this->created_by;
        }
    }

    public function currentLanguage()
    {
        return $this->lang;
    }

    public function employeeIdFormat($number)
    {
        $settings = Utility::settings();

        return $settings["employee_prefix"] . sprintf("%05d", $number);
    }

    public function clientIdFormat($number)
    {
        $settings = Utility::settings();

        return $settings["client_prefix"] . sprintf("%05d", $number);
    }

    public function priceFormat($price)
    {
        $settings = Utility::settings();

        return (($settings['site_currency_symbol_position'] == "pre") ? $settings['site_currency_symbol'] : '') . number_format($price, 2) . (($settings['site_currency_symbol_position'] == "post") ? $settings['site_currency_symbol'] : '');
    }

    public function currencySymbol()
    {
        $settings = Utility::settings();

        return $settings['site_currency_symbol'];
    }

    public function dateFormat($date)
    {
        $settings = Utility::settings();

        return date($settings['site_date_format'], strtotime($date));
    }

    public function timeFormat($time)
    {
        $settings = Utility::settings();

        return date($settings['site_time_format'], strtotime($time));
    }

    public static function estimateNumberFormat($number)
    {
        $settings = Utility::settings();

        return $settings["estimate_prefix"] . sprintf("%05d", $number);
    }

    public function invoiceNumberFormat($number)
    {
        $settings = Utility::settings();

        return $settings["invoice_prefix"] . sprintf("%05d", $number);
    }

    public function unread()
    {
        return Messages::where('from', '=', $this->id)->where('is_read', '=', 0)->count();
    }

    public function assignPlan($planID)
    {
        $plan = Plan::find($planID);
        if($plan)
        {
            $this->plan = $plan->id;
            if($plan->duration == 'month')
            {
                $this->plan_expire_date = Carbon::now()->addMonths(1)->isoFormat('YYYY-MM-DD');
            }
            elseif($plan->duration == 'year')
            {
                $this->plan_expire_date = Carbon::now()->addYears(1)->isoFormat('YYYY-MM-DD');
            }
            $this->save();

            $employees = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'employee')->get();
            $clients   = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'client')->get();


            if($plan->max_employee == -1)
            {
                foreach($employees as $employee)
                {
                    $employee->is_active = 1;
                    $employee->save();
                }
            }
            else
            {
                $employeeCount = 0;
                foreach($employees as $employee)
                {
                    $employeeCount++;
                    if($employeeCount <= $plan->max_employee)
                    {
                        $employee->is_active = 1;
                        $employee->save();
                    }
                    else
                    {
                        $employee->is_active = 0;
                        $employee->save();
                    }
                }
            }

            if($plan->max_client == -1)
            {
                foreach($clients as $client)
                {
                    $client->is_active = 1;
                    $client->save();
                }
            }
            else
            {
                $clientCount = 0;
                foreach($clients as $client)
                {
                    $clientCount++;
                    if($clientCount <= $plan->max_client)
                    {
                        $client->is_active = 1;
                        $client->save();
                    }
                    else
                    {
                        $client->is_active = 0;
                        $client->save();
                    }
                }
            }

            return ['is_success' => true];
        }
        else
        {
            return [
                'is_success' => false,
                'error' => 'Plan is deleted.',
            ];
        }
    }

    public function countCompany()
    {
        return User::where('type', '=', 'company')->where('created_by', '=', $this->creatorId())->count();
    }

    public function countPaidCompany()
    {
        return User::where('type', '=', 'company')->whereNotIn(
            'plan', [
                      0,
                      1,
                  ]
        )->where('created_by', '=', \Auth::user()->id)->count();
    }

    public function invoices()
    {
        return $this->hasMany('App\Invoice', 'client', 'id');
    }

    public function countEmployees($id)
    {
        return Employee::where('created_by', $id)->count();
    }

    public function countClients($id)
    {
        return Client::where('created_by', $id)->count();
    }

    public function currentPlan()
    {
        return $this->hasOne('App\Plan', 'id', 'plan');
    }

    public function userDefaultData()
    {
        $id       = $this->id;
        $pipeline = Pipeline::create(
            [
                'name' => 'Sales',
                'created_by' => $id,
            ]
        );


        // Default Lead Stages
        $lead_stages = [
            'Draft',
            'Sent',
            'Declined',
            'Accepted',
        ];
        foreach($lead_stages as $k => $lead_stage)
        {
            LeadStage::create(
                [
                    'name' => $lead_stage,
                    'pipeline_id' => $pipeline->id,
                    'order' => $k,
                    'created_by' => $id,
                ]
            );
        }

        // Default Deal Stages
        $stages = [
            'Initial Contact',
            'Qualification',
            'Meeting',
            'Close',
        ];
        foreach($stages as $k => $stage)
        {
            DealStage::create(
                [
                    'name' => $stage,
                    'pipeline_id' => $pipeline->id,
                    'order' => $k,
                    'created_by' => $id,
                ]
            );
        }


        // End Default Lead Stages

        // Label
        $labels = [
            'New Deal' => 'danger',
            'Idea' => 'warning',
            'Appointment' => 'primary',
        ];
        foreach($labels as $label => $color)
        {
            Label::create(
                [
                    'name' => $label,
                    'color' => $color,
                    'pipeline_id' => $pipeline->id,
                    'created_by' => $id,
                ]
            );
        }

        // Source
        $sources = [
            'Website',
            'Organic',
            'Call',
            'Social Media',
            'Email Campaign',
        ];
        foreach($sources as $source)
        {
            Source::create(
                [
                    'name' => $source,
                    'created_by' => $id,
                ]
            );
        }

        // Payment
        $payments = [
            'Cash',
            'Bank',
        ];
        foreach($payments as $payment)
        {
            PaymentMethod::create(
                [
                    'name' => $payment,
                    'created_by' => $id,
                ]
            );
        }

        // Salary Type
        $salaryTypes = [
            'Monthly',
        ];
        foreach($salaryTypes as $salaryType)
        {
            SalaryType::create(
                [
                    'name' => $salaryType,
                    'created_by' => $id,
                ]
            );
        }

        // Leave Type
        $leaveTypes = [
            'Casual Leave',
            'Medical Leave',
        ];
        foreach($leaveTypes as $leaveType)
        {
            LeaveType::create(
                [
                    'title' => $leaveType,
                    'days' => 12,
                    'created_by' => $id,
                ]
            );
        }

        // Project Stages
        $projectStages = [
            'To Do',
            'In Progress',
            'Bug',
            'Done',
        ];
        $colors        = [
            '#4a7123',
            '#698f24',
            '#bb7c7c',
            '#c8b53c',
        ];
        foreach($projectStages as $k => $projectStage)
        {
            ProjectStage::create(
                [
                    'name' => $projectStage,
                    'order' => $k,
                    'color' => $colors[$k],
                    'created_by' => $id,
                ]
            );
        }


        // Make Entry In User_Email_Template
        $allEmail = EmailTemplate::all();
        foreach($allEmail as $email)
        {
            UserEmailTemplate::create(
                [
                    'template_id' => $email->id,
                    'user_id' => $id,
                    'is_active' => 1,
                ]
            );
        }
    }

    // For Email template Module
    public function defaultEmail()
    {
        // Email Template
        $emailTemplate = [
            'Create User',
            'Lead Assign',
            'Deal Assign',
            'Send Estimation',
            'Create Project',
            'Project Assign',
            'Project Finished',
            'Task Assign',
            'Send Invoice',
            'Invoice Payment Recored',
            'Credit Note',
            'Create Support',
            'Create Contract',
        ];

        foreach($emailTemplate as $eTemp)
        {

            EmailTemplate::create(
                [
                    'name' => $eTemp,
                    'from' => env('APP_NAME'),
                    'slug' => strtolower(str_replace(' ', '_', $eTemp)),
                    'created_by' => $this->id,
                ]
            );

        }

        $defaultTemplate = [
            'create_user' => [
                'subject' => 'Login Detail',
                'lang' => [
                    'ar' => '<p>????????????&nbsp;<br>?????????? ???? ???? {app_name}.</p><p><b>???????????? ???????????????????? </b>: {email}<br><b>???????? ????????</b> : {password}</p><p>{app_url}</p><p>????????<br>{app_name}</p>',
                    'da' => '<p>Hej,&nbsp;<br>Velkommen til {app_name}.</p><p><b>E-mail </b>: {email}<br><b>Adgangskode</b> : {password}</p><p>{app_url}</p><p>Tak,<br>{app_name}</p>',
                    'de' => '<p>Hallo,&nbsp;<br>Willkommen zu {app_name}.</p><p><b>Email </b>: {email}<br><b>Passwort</b> : {password}</p><p>{app_url}</p><p>Vielen Dank,<br>{app_name}</p>',
                    'en' => '<p>Hello,&nbsp;<br>Welcome to {app_name}.</p><p><b>Email </b>: {email}<br><b>Password</b> : {password}</p><p>{app_url}</p><p>Thanks,<br>{app_name}</p>',
                    'es' => '<p>Hola,&nbsp;<br>Bienvenido a {app_name}.</p><p><b>Correo electr??nico </b>: {email}<br><b>Contrase??a</b> : {password}</p><p>{app_url}</p><p>Gracias,<br>{app_name}</p>',
                    'fr' => '<p>Bonjour,&nbsp;<br>Bienvenue ?? {app_name}.</p><p><b>Email </b>: {email}<br><b>Mot de passe</b> : {password}</p><p>{app_url}</p><p>Merci,<br>{app_name}</p>',
                    'it' => '<p>Ciao,&nbsp;<br>Benvenuto a {app_name}.</p><p><b>E-mail </b>: {email}<br><b>Parola d\'ordine</b> : {password}</p><p>{app_url}</p><p>Grazie,<br>{app_name}</p>',
                    'ja' => '<p>??????????????????&nbsp;<br>??????????????? {app_name}.</p><p><b>E????????? </b>: {email}<br><b>???????????????</b> : {password}</p><p>{app_url}</p><p>???????????????<br>{app_name}</p>',
                    'nl' => '<p>Hallo,&nbsp;<br>Welkom bij {app_name}.</p><p><b>E-mail </b>: {email}<br><b>Wachtwoord</b> : {password}</p><p>{app_url}</p><p>Bedankt,<br>{app_name}</p>',
                    'pl' => '<p>Witaj,&nbsp;<br>Witamy w {app_name}.</p><p><b>E-mail </b>: {email}<br><b>Has??o</b> : {password}</p><p>{app_url}</p><p>Dzi??ki,<br>{app_name}</p>',
                    'ru' => '<p>????????????,&nbsp;<br>?????????? ???????????????????? ?? {app_name}.</p><p><b>?????????????????????? ???????????? </b>: {email}<br><b>????????????</b> : {password}</p><p>{app_url}</p><p>??????????????,<br>{app_name}</p>',
                ],
            ],
            'lead_assign' => [
                'subject' => 'Lead Assign',
                'lang' => [
                    'ar' => '<p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-family: " open="" sans";"="">???</span><span style="font-family: " open="" sans";"="">??????????,</span><br style="font-family: sans-serif;"></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="" open="" sans";"="">???? ?????????? ???????? ?????????? ???????? ????.</span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="" open="" sans";"="">?????? ???????????? ??????????????&nbsp;: {lead_name}</span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span open="" sans";"="" style="">???????????? ???????????? ????????????????????<span style="font-size: 1rem;">&nbsp;: {lead_email}</span></span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="" open="" sans";"="">???? ???????????? ????????????&nbsp;: {lead_pipeline}</span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="" open="" sans";"="">?????????? ????????????&nbsp;: {lead_stage}</span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="" open="" sans";"="">?????????????? ??????????????: {lead_subject}</span></p><p></p>',
                    'da' => '<p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-family: " open="" sans";"="">Hej,</span><br style="font-family: sans-serif;"></p><p><span style="font-family: " open="" sans";"="">Ny bly er blevet tildelt dig.</span></p><p><span style="font-size: 1rem; font-weight: bolder; font-family: " open="" sans";"="">Lead-e-mail</span><span style="font-size: 1rem; font-family: " open="" sans";"="">&nbsp;</span><span style="font-size: 1rem; font-family: " open="" sans";"="">: {lead_email}</span></p><p><span style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: " open="" sans";"="">Blyr??rledning</span><span style="font-family: " open="" sans";"="">&nbsp;</span><span style="font-family: " open="" sans";"="">: {lead_pipeline}</span></span></p><p><span style="font-size: 1rem; font-weight: bolder; font-family: " open="" sans";"="">Lead scenen</span><span style="font-size: 1rem; font-family: " open="" sans";"="">&nbsp;</span><span style="font-size: 1rem; font-family: " open="" sans";"="">: {lead_stage}</span></p><p></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: " open="" sans";"="">Blynavn</span><span style="font-family: " open="" sans";"="">&nbsp;</span><span style="font-family: " open="" sans";"="">: {lead_name}</span></span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span open="" sans";"=""><b>Lead Emne</b>: {lead_subject}</span><span style="font-family: sans-serif;"><span style="font-family: " open="" sans";"=""><br></span><br></span></p><p></p>',
                    'de' => '<p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-family: sans-serif;">Hallo,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Neuer Lead wurde Ihnen zugewiesen.</span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-family: sans-serif; font-weight: bolder;" open="" sans";"="">Lead Name</span><span style="font-family: sans-serif;" open="" sans";"="">&nbsp;</span><span style="" open="" sans";"=""><font face="sans-serif">:</font> {lead_name}</span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-family: sans-serif; font-weight: bolder;" open="" sans";"="">Lead-E-Mail</span><span style="font-family: sans-serif;" open="" sans";"="">&nbsp;</span><span style="" open="" sans";"=""><font face="sans-serif">: </font>{lead_email}</span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-family: sans-serif; font-weight: bolder;" open="" sans";"="">Lead Pipeline</span><span style="font-family: sans-serif;" open="" sans";"="">&nbsp;</span><span style="" open="" sans";"=""><font face="sans-serif">:</font> {lead_pipeline}</span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-family: sans-serif; font-weight: bolder;" open="" sans";"="">Lead Stage</span><span style="font-family: sans-serif;" open="" sans";"="">&nbsp;</span><span style="" open="" sans";"=""><font face="sans-serif">: </font>{lead_stage}</span></p><p style="line-height: 28px;"><span style="font-family: " open="" sans";"=""><b>Lead Emne</b>: {lead_subject}</span></p><p></p>',
                    'en' => '<p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-family: " open="" sans";"="">???</span><span style="font-family: " open="" sans";"="">Hello,</span><br style="font-family: sans-serif;"><span style="font-family: " open="" sans";"="">New Lead has been Assign to you.</span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="" open="" sans";"=""><b>Lead Name</b></span><span style="" open="" sans";"="">&nbsp;: {lead_name}</span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span open="" sans";"="" style="font-size: 1rem;"><b>Lead Email</b></span><span open="" sans";"="" style="font-size: 1rem;">&nbsp;: {lead_email}</span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="" open="" sans";"=""><b>Lead Pipeline</b></span><span style="" open="" sans";"="">&nbsp;: {lead_pipeline}</span></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="" open="" sans";"=""><b>Lead Stage</b></span><span style="" open="" sans";"="">&nbsp;: {lead_stage}</span></p><p style="line-height: 28px;"><span style="" open="" sans";"=""><b>Lead Subject</b>: {lead_subject}</span></p><p></p>',
                    'es' => '<p style="line-height: 28px;">Hola,<br style=""></p><p>Se le ha asignado un nuevo plomo.</p><p></p><p style="line-height: 28px;"><b>Nombre principal</b>&nbsp;: {lead_name}</p><p style="line-height: 28px;"><b>Correo electr??nico</b> principal&nbsp;: {lead_email}</p><p style="line-height: 28px;"><b>Tuber??a de plomo</b>&nbsp;: {lead_pipeline}</p><p style="line-height: 28px;"><b>Etapa de plomo</b>&nbsp;: {lead_stage}</p><p style="line-height: 28px;"><span open="" sans";"=""><b>Hauptthema</b>: {lead_subject}</span><br></p><p></p>',
                    'fr' => '<p style="line-height: 28px;">Bonjour,<br style=""></p><p style="">Un nouveau prospect vous a ??t?? attribu??.</p><p></p><p style="line-height: 28px;"><b>Nom du responsable</b>&nbsp;: {lead_name}</p><p style="line-height: 28px;"><b>Courriel principal</b>&nbsp;: {lead_email}</p><p style="line-height: 28px;"><b>Pipeline de plomb</b>&nbsp;: {lead_pipeline}</p><p style="line-height: 28px;"><b>??tape principale</b>&nbsp;: {lead_stage}</p><p style="line-height: 28px;"><span style="" open="" sans";"=""><b>Sujet principal</b>: {lead_subject}</span></p><p></p>',
                    'it' => '<p style="line-height: 28px;">Ciao,<br style=""></p><p>New Lead ?? stato assegnato a te.</p><p><b>Lead Email</b>&nbsp;: {lead_email}</p><p><b>Conduttura di piombo&nbsp;: {lead_pipeline}</b></p><p><b>Lead Stage</b>&nbsp;: {lead_stage}</p><p></p><p style="line-height: 28px;"><b>Nome del lead</b>&nbsp;: {lead_name}<br></p><p style="line-height: 28px;"><span style="" open="" sans";"=""><b>Soggetto principale</b>: {lead_subject}</span></p><p></p>',
                    'ja' => '<p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-family: " open="" sans";"="">??????????????????</span><br style="font-family: sans-serif;"></p><p><span style="font-family: " open="" sans";"="">???????????????????????????????????????????????????</span><br><span style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: " open="" sans";"="">??????????????????</span><span style="font-family: " open="" sans";"="">&nbsp;</span><span style="font-family: " open="" sans";"="">: {lead_email}</span></span><br><span style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: " open="" sans";"="">???????????????????????????</span><span style="font-family: " open="" sans";"="">&nbsp;</span><span style="font-family: " open="" sans";"="">: {lead_pipeline}</span></span><br><span style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: " open="" sans";"="">?????????????????????</span><span style="font-family: " open="" sans";"="">&nbsp;: {lead_stage}</span></span></p><p></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-family: sans-serif;"><span style="font-weight: bolder; font-family: " open="" sans";"="">????????????</span><span style="font-family: " open="" sans";"="">&nbsp;</span><span style="font-family: " open="" sans";"="">: {lead_name}</span><br></span></p><p style="line-height: 28px;"><span open="" sans";"="" style=""><span style="font-family: " open="" sans";"="">???????????????????????????</span><span style="font-size: 1rem; font-family: " open="" sans";"="">: {lead_subject}</span></span></p><p></p>',
                    'nl' => '<p style="line-height: 28px;">Hallo,<br style=""></p><p style="">Nieuwe lead is aan u toegewezen.<br><b>E-mail leiden</b>&nbsp;: {lead_email}<br><b>Lead Pipeline</b>&nbsp;: {lead_pipeline}<br><b>Hoofdfase</b>&nbsp;: {lead_stage}</p><p></p><p style="line-height: 28px;"><b>Lead naam</b>&nbsp;: {lead_name}<br></p><p style="line-height: 28px;"><span style="" open="" sans";"=""><b>Hoofdonderwerp</b>: {lead_subject}</span></p><p></p>',
                    'pl' => '<p style="line-height: 28px;">Witaj,<br style="">Nowy potencjalny klient zosta?? do ciebie przypisany.</p><p style="line-height: 28px;"><b>Imi?? i nazwisko</b>&nbsp;: {lead_name}<br><b>G????wny adres e-mail</b>&nbsp;: {lead_email}<br><b>O????w ruroci??gu</b>&nbsp;: {lead_pipeline}<br><b>Etap prowadz??cy</b>&nbsp;: {lead_stage}</p><p style="line-height: 28px;"><span style="" open="" sans";"=""><b>G????wny temat</b>: {lead_subject}</span></p><p></p>',
                    'ru' => '<p style="line-height: 28px;">????????????,<br style="">?????????? ?????? ?????? ???????????????? ??????.</p><p style="line-height: 28px;"><b>?????? ????????????</b>&nbsp;: {lead_name}<br><b>?????????????? Email</b>&nbsp;: {lead_email}<br><b>?????????????? ??????????????????????</b>&nbsp;: {lead_pipeline}<br><b>?????????????? ????????</b>&nbsp;: {lead_stage}</p><p style="line-height: 28px;"><span style="" open="" sans";"=""><b>?????????????? ????????</b>: {lead_subject}</span></p><p></p>',
                ],
            ],
            'deal_assign' => [
                'subject' => 'Deal Assign',
                'lang' => [
                    'ar' => '<p style="line-height: 28px; font-family: Nunito, &quot;Segoe UI&quot;, arial; font-size: 14px;"><span style="font-family: sans-serif;">????????????</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">???? ?????????? ???????? ?????????? ????.</span></p><p style="line-height: 28px; font-family: Nunito, &quot;Segoe UI&quot;, arial; font-size: 14px;"><span style="font-family: sans-serif;"><span style="font-weight: bolder;">?????? ????????????</span>&nbsp;: {deal_name}<br><span style="font-weight: bolder;">???? ???????????? ????????????</span>&nbsp;: {deal_pipeline}<br><span style="font-weight: bolder;">?????????? ????????????</span>&nbsp;: {deal_stage}<br><span style="font-weight: bolder;">???????? ????????????</span>&nbsp;: {deal_status}<br><span style="font-weight: bolder;">?????? ????????????</span>&nbsp;: {deal_price}</span></p><p></p>',
                    'da' => '<p style="line-height: 28px; font-family: Nunito, &quot;Segoe UI&quot;, arial; font-size: 14px;"><span style="font-family: sans-serif;">Hej,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">New Deal er blevet tildelt til dig.</span></p><p style="line-height: 28px; font-family: Nunito, &quot;Segoe UI&quot;, arial; font-size: 14px;"><span style="font-family: sans-serif;"><span style="font-weight: bolder;">Deal Navn</span>&nbsp;: {deal_name}<br><span style="font-weight: bolder;">Deal Pipeline</span>&nbsp;: {deal_pipeline}<br><span style="font-weight: bolder;">Deal Fase</span>&nbsp;: {deal_stage}<br><span style="font-weight: bolder;">Deal status</span>&nbsp;: {deal_status}<br><span style="font-weight: bolder;">Deal pris</span>&nbsp;: {deal_price}</span></p><p></p>',
                    'de' => '<p style="line-height: 28px; font-family: Nunito, &quot;Segoe UI&quot;, arial; font-size: 14px;"><span style="font-family: sans-serif;">Hallo,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">New Deal wurde Ihnen zugewiesen.</span></p><p style="line-height: 28px; font-family: Nunito, &quot;Segoe UI&quot;, arial; font-size: 14px;"><span style="font-family: sans-serif;"><span style="font-weight: bolder;">Gesch??ftsname</span>&nbsp;: {deal_name}<br><span style="font-weight: bolder;">Deal Pipeline</span>&nbsp;: {deal_pipeline}<br><span style="font-weight: bolder;">Deal Stage</span>&nbsp;: {deal_stage}<br><span style="font-weight: bolder;">Deal Status</span>&nbsp;: {deal_status}<br><span style="font-weight: bolder;">Ausgehandelter Preis</span>&nbsp;: {deal_price}</span></p><p></p>',
                    'en' => '<p style="line-height: 28px; font-family: Nunito, &quot;Segoe UI&quot;, arial; font-size: 14px;"><span style="font-family: sans-serif;">Hello,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">New Deal has been Assign to you.</span></p><p style="line-height: 28px; font-family: Nunito, &quot;Segoe UI&quot;, arial; font-size: 14px;"><span style="font-family: sans-serif;"><span style="font-weight: bolder;">Deal Name</span>&nbsp;: {deal_name}<br><span style="font-weight: bolder;">Deal Pipeline</span>&nbsp;: {deal_pipeline}<br><span style="font-weight: bolder;">Deal Stage</span>&nbsp;: {deal_stage}<br><span style="font-weight: bolder;">Deal Status</span>&nbsp;: {deal_status}<br><span style="font-weight: bolder;">Deal Price</span>&nbsp;: {deal_price}</span></p><p></p>',
                    'es' => '<p style="line-height: 28px; font-family: Nunito, &quot;Segoe UI&quot;, arial; font-size: 14px;"><span style="font-family: sans-serif;">Hola,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">New Deal ha sido asignado a usted.</span></p><p style="line-height: 28px; font-family: Nunito, &quot;Segoe UI&quot;, arial; font-size: 14px;"><span style="font-family: sans-serif;"><span style="font-weight: bolder;">Nombre del trato</span>&nbsp;: {deal_name}<br><span style="font-weight: bolder;">Tuber??a de reparto</span>&nbsp;: {deal_pipeline}<br><span style="font-weight: bolder;">Etapa de reparto</span>&nbsp;: {deal_stage}<br><span style="font-weight: bolder;">Estado del acuerdo</span>&nbsp;: {deal_status}<br><span style="font-weight: bolder;">Precio de oferta</span>&nbsp;: {deal_price}</span></p><p></p>',
                    'fr' => '<p style="line-height: 28px; font-family: Nunito, &quot;Segoe UI&quot;, arial; font-size: 14px;"><span style="font-family: sans-serif;">Bonjour,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Le New Deal vous a ??t?? attribu??.</span></p><p style="line-height: 28px; font-family: Nunito, &quot;Segoe UI&quot;, arial; font-size: 14px;"><span style="font-family: sans-serif;"><span style="font-weight: bolder;">Nom de l\'accord</span>&nbsp;: {deal_name}<br><span style="font-weight: bolder;">Pipeline de transactions</span>&nbsp;: {deal_pipeline}<br><span style="font-weight: bolder;">??tape de l\'op??ration</span>&nbsp;: {deal_stage}<br><span style="font-weight: bolder;">Statut de l\'accord</span>&nbsp;: {deal_status}<br><span style="font-weight: bolder;">Prix ??????de l\'offre</span>&nbsp;: {deal_price}</span></p><p></p>',
                    'it' => '<p style="line-height: 28px; font-family: Nunito, &quot;Segoe UI&quot;, arial; font-size: 14px;"><span style="font-family: sans-serif;">Ciao,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">New Deal ?? stato assegnato a te.</span></p><p style="line-height: 28px; font-family: Nunito, &quot;Segoe UI&quot;, arial; font-size: 14px;"><span style="font-family: sans-serif;"><span style="font-weight: bolder;">Nome dell\'affare</span>&nbsp;: {deal_name}<br><span style="font-weight: bolder;">Pipeline di offerte</span>&nbsp;: {deal_pipeline}<br><span style="font-weight: bolder;">Stage Deal</span>&nbsp;: {deal_stage}<br><span style="font-weight: bolder;">Stato dell\'affare</span>&nbsp;: {deal_status}<br><span style="font-weight: bolder;">Prezzo dell\'offerta</span>&nbsp;: {deal_price}</span></p><p></p>',
                    'ja' => '<p style="line-height: 28px; font-family: Nunito, &quot;Segoe UI&quot;, arial; font-size: 14px;"><span style="font-family: sans-serif;">??????????????????</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">????????????????????????????????????????????????</span></p><p style="line-height: 28px; font-family: Nunito, &quot;Segoe UI&quot;, arial; font-size: 14px;"><span style="font-family: sans-serif;"><span style="font-weight: bolder;">?????????</span>&nbsp;: {deal_name}<br><span style="font-weight: bolder;">????????????????????????</span>&nbsp;: {deal_pipeline}<br><span style="font-weight: bolder;">??????????????????</span>&nbsp;: {deal_stage}<br><span style="font-weight: bolder;">????????????</span>&nbsp;: {deal_status}<br><span style="font-weight: bolder;">????????????</span>&nbsp;: {deal_price}</span></p><p></p>',
                    'nl' => '<p style="line-height: 28px; font-family: Nunito, &quot;Segoe UI&quot;, arial; font-size: 14px;"><span style="font-family: sans-serif;">Hallo,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">New Deal is aan u toegewezen.</span></p><p style="line-height: 28px; font-family: Nunito, &quot;Segoe UI&quot;, arial; font-size: 14px;"><span style="font-family: sans-serif;"><span style="font-weight: bolder;">Dealnaam</span>&nbsp;: {deal_name}<br><span style="font-weight: bolder;">Deal Pipeline</span>&nbsp;: {deal_pipeline}<br><span style="font-weight: bolder;">Deal Stage</span>&nbsp;: {deal_stage}<br><span style="font-weight: bolder;">Dealstatus</span>&nbsp;: {deal_status}<br><span style="font-weight: bolder;">Deal prijs</span>&nbsp;: {deal_price}</span></p><p></p>',
                    'pl' => '<p style="line-height: 28px; font-family: Nunito, &quot;Segoe UI&quot;, arial; font-size: 14px;"><span style="font-family: sans-serif;">Witaj,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">Umowa zosta??a przeniesiona {deal_old_stage} do&nbsp; {deal_new_stage}.</span></p><p style="line-height: 28px; font-family: Nunito, &quot;Segoe UI&quot;, arial; font-size: 14px;"><span style="font-family: sans-serif;"><span style="font-weight: bolder;">Nazwa oferty</span>&nbsp;: {deal_name}<br><span style="font-weight: bolder;">Deal Pipeline</span>&nbsp;: {deal_pipeline}<br><span style="font-weight: bolder;">Etap transakcji</span>&nbsp;: {deal_stage}<br><span style="font-weight: bolder;">Status oferty</span>&nbsp;: {deal_status}<br><span style="font-weight: bolder;">Cena oferty</span>&nbsp;: {deal_price}</span></p><p></p>',
                    'ru' => '<p style="line-height: 28px; font-family: Nunito, &quot;Segoe UI&quot;, arial; font-size: 14px;"><span style="font-family: sans-serif;">????????????,</span><br style="font-family: sans-serif;"><span style="font-family: sans-serif;">???????????? ???????? ???????????????????? ???? {deal_old_stage} ??&nbsp; {deal_new_stage}.</span></p><p style="line-height: 28px; font-family: Nunito, &quot;Segoe UI&quot;, arial; font-size: 14px;"><span style="font-family: sans-serif;"><span style="font-weight: bolder;">???????????????? ????????????</span>&nbsp;: {deal_name}<br><span style="font-weight: bolder;">?????????????????????? ????????????</span>&nbsp;: {deal_pipeline}<br><span style="font-weight: bolder;">???????? ????????????</span>&nbsp;: {deal_stage}<br><span style="font-weight: bolder;">???????????? ????????????</span>&nbsp;: {deal_status}<br><span style="font-weight: bolder;">???????? ????????????</span>&nbsp;: {deal_price}</span></p><p></p>',
                ],
            ],
            'send_estimation' => [
                'subject' => 'Send Estimation',
                'lang' => [
                    'ar' => '<p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-size: 14px; font-family: sans-serif;">????????????</span><br style="font-size: 14px; font-family: sans-serif;"><span style="font-size: 14px; font-family: sans-serif;">???? ?????????? ?????????? ???????? ????.</span><br></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"="">?????? ??????????????: {estimation_id}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-family: sans-serif; font-size: 14px; font-weight: bolder;">???????? ??????????</span>&nbsp;: {estimation_client}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"="">?????? ??????????????&nbsp;: {estimation_category}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"="">?????????? ?????????? ??????????????&nbsp;: {estimation_issue_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"="">?????????? ???????????? ???????????????? ????????????????&nbsp;: {estimation_expiry_date}<br><span style="font-weight: bolder; font-family: sans-serif; font-size: 14px;">???????? ??????????????</span>&nbsp;: {estimation_status}</p><p></p>',
                    'da' => '<p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-size: 14px; font-family: sans-serif;">Hej,</span><br style="font-size: 14px; font-family: sans-serif;"><span style="font-size: 14px; font-family: sans-serif;">Ny estimering er blevet tildelt til dig.</span><br></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Ansl??et navn</b>: {estimation_id}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Kundeoverslag</b>&nbsp;: {estimation_client}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Estimeringskategori</b>&nbsp;: {estimation_category}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Estimationsudstedelsesdato</b>&nbsp;: {estimation_issue_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Ansl??et udl??bsdato</b>&nbsp;: {estimation_expiry_date}<br><b>Sk??n Status</b>&nbsp;: {estimation_status}</p><p></p>',
                    'de' => '<p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-size: 14px; font-family: sans-serif;">Hallo,</span><br style="font-size: 14px; font-family: sans-serif;"><span style="font-size: 14px; font-family: sans-serif;">Neue Sch??tzung wurde Ihnen zugewiesen.</span><br></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Sch??tzungsname</b>: {estimation_id}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Kundensch??tzung</b>&nbsp;: {estimation_client}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Sch??tzungskategorie</b>&nbsp;: {estimation_category}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Ausstellungsdatum der Sch??tzung</b>&nbsp;: {estimation_issue_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Ablaufdatum der Sch??tzung</b>&nbsp;: {estimation_expiry_date}<br><b>Sch??tzungsstatus</b>&nbsp;: {estimation_status}</p><p></p>',
                    'en' => '<p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"="">Hello,<br style="">New Estimation has been Assign to you.</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Estimation Name</b>: {estimation_id}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Estimation Client</b>&nbsp;: {estimation_client}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Estimation Category</b>&nbsp;: {estimation_category}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Estimation Issue Date</b>&nbsp;: {estimation_issue_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Estimation Expiry Date</b>&nbsp;: {estimation_expiry_date}<br><b>Estimation Status</b>&nbsp;: {estimation_status}</p><p></p>',
                    'es' => '<p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-size: 14px; font-family: sans-serif;">Hola,</span><br style="font-size: 14px; font-family: sans-serif;"><span style="font-size: 14px; font-family: sans-serif;">Se le ha asignado una nueva estimaci??n.</span><br></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Nombre de la estimaci??n</b>: {estimation_id}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Estimaci??n del cliente</b>&nbsp;: {estimation_client}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Categor??a de estimaci??n</b>&nbsp;: {estimation_category}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Fecha de emisi??n de la estimaci??n</b>&nbsp;: {estimation_issue_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Fecha de vencimiento de la estimaci??n</b>&nbsp;: {estimation_expiry_date}<br><b>Estado de estimaci??n</b>&nbsp;: {estimation_status}</p><p></p>',
                    'fr' => '<p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-size: 14px; font-family: sans-serif;">Bonjour,</span><br style="font-size: 14px; font-family: sans-serif;"><span style="font-size: 14px; font-family: sans-serif;">Une nouvelle estimation vous a ??t?? attribu??e.</span><br></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"="">Nom de l\'estimation: {estimation_id}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"="">Estimation Client&nbsp;: {estimation_client}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"="">Cat??gorie d\'estimation&nbsp;: {estimation_category}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"="">Date d\'??mission de l\'estimation&nbsp;: {estimation_issue_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"="">Date d\'expiration de l\'estimation&nbsp;: {estimation_expiry_date}<br>Statut d\'estimation&nbsp;: {estimation_status}</p><p></p>',
                    'it' => '<p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-size: 14px; font-family: sans-serif;">Ciao,</span><br style="font-size: 14px; font-family: sans-serif;"><span style="font-size: 14px; font-family: sans-serif;">La nuova stima ?? stata assegnata a te.</span><br></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Nome stima</b>: {estimation_id}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Stima del cliente</b>&nbsp;: {estimation_client}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Categoria di stima</b>&nbsp;: {estimation_category}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Data di emissione della stima</b>&nbsp;: {estimation_issue_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Data di scadenza della stima</b>&nbsp;: {estimation_expiry_date}<br><b>Stato della stima</b>&nbsp;: {estimation_status}</p><p></p>',
                    'ja' => '<p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-size: 14px; font-family: sans-serif;">??????????????????</span><br style="font-size: 14px; font-family: sans-serif;"><span style="font-size: 14px; font-family: sans-serif;">??????????????????????????????????????????????????????</span><br></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"="">???????????????: {estimation_id}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"="">?????????????????????&nbsp;: {estimation_client}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"="">?????????????????????&nbsp;: {estimation_category}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"="">??????????????????&nbsp;: {estimation_issue_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"="">?????????????????????&nbsp;: {estimation_expiry_date}<br>????????????&nbsp;: {estimation_status}</p><p></p>',
                    'nl' => '<p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-size: 14px; font-family: sans-serif;">Hallo,</span><br style="font-size: 14px; font-family: sans-serif;"><span style="font-size: 14px; font-family: sans-serif;">Nieuwe schatting is aan u toegewezen.</span><br></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Naam schatting</b>: {estimation_id}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Klant schatting</b>&nbsp;: {estimation_client}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Geschatte categorie</b>&nbsp;: {estimation_category}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Schatting uitgiftedatum</b>&nbsp;: {estimation_issue_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Geschatte vervaldatum</b>&nbsp;: {estimation_expiry_date}<br><b>Geschatte status</b>&nbsp;: {estimation_status}</p><p></p>',
                    'pl' => '<p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-size: 14px; font-family: sans-serif;">Witaj,</span><br style="font-size: 14px; font-family: sans-serif;"><span style="font-size: 14px; font-family: sans-serif;">Nowe oszacowanie zosta??o Ci przypisane.</span><br></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Nazwa szacunku</b>: {estimation_id}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Oszacowanie klienta</b>&nbsp;: {estimation_client}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Kategoria szacowania</b>&nbsp;: {estimation_category}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Szacunkowa data wystawienia</b>&nbsp;: {estimation_issue_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>Szacunkowa data wa??no??ci</b>&nbsp;: {estimation_expiry_date}<br><b>Status oszacowania</b>&nbsp;: {estimation_status}</p><p></p>',
                    'ru' => '<p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><span style="font-size: 14px; font-family: sans-serif;">????????????,</span><br style="font-size: 14px; font-family: sans-serif;"><span style="font-size: 14px; font-family: sans-serif;">?????????? ???????????? ???????? ?????????????????? ??????.</span><br></p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>???????????????? ????????????</b>: {estimation_id}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>???????????? ??????????????</b>&nbsp;: {estimation_client}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>?????????????????? ????????????</b>&nbsp;: {estimation_category}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>???????? ???????????? ????????????</b>&nbsp;: {estimation_issue_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""><b>???????? ???????????????? ????????????</b>&nbsp;: {estimation_expiry_date}<br><b>???????????? ????????????</b>&nbsp;: {estimation_status}</p><p></p>',
                ],
            ],
            'create_project' => [
                'subject' => 'Create Project',
                'lang' => [
                    'ar' => '<p>??????????&nbsp;{project_client},</p><p>???? ?????????? ?????????? ???????? ????.</p><p>?????????? ??????????????<strong>:</strong>&nbsp;{project_title}</p><p>?????????? ?????? ??????????????<strong>:</strong>&nbsp;{project_start_date}</p><p>?????????? ?????????????? ??????????????:&nbsp;{project_due_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""></p><p>?????? ?????????? ?????? ???????????????? ??????.<br><br>???????? ??????????????,<br>{app_name}</p><p></p>',
                    'da' => '<p>Hej&nbsp;{project_client},</p><p>Nyt projekt er tildelt dig.<br><br><b>Projekt titel:</b>&nbsp;{project_title}</p><p><b>Projektets startdato</b><strong>:</strong>&nbsp;{project_start_date}</p><p><b>Projektets forfaldsdato</b>:&nbsp;{project_due_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""></p><p>Vi gl??der os til at h??re fra dig.<br><br><b>Med venlig hilsen</b>,<br>{app_name}</p><p></p>',
                    'de' => '<p>Hallo&nbsp;{project_client},</p><p>Ihnen wird ein neues Projekt zugewiesen.<br><br><b>Projekttitel:</b>&nbsp;{project_title}</p><p><b>Projektstartdatum</b><strong>:</strong>&nbsp;{project_start_date}</p><p><b>Projektf??lligkeit</b>:&nbsp;{project_due_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""></p><p>Wir freuen uns von Ihnen zu h??ren.<br><br><b>Mit freundlichen Gr????en</b>,<br>{app_name}</p><p></p>',
                    'en' => '<p>Hello&nbsp;{project_client},</p><p>New project is assigned to you.<br><br><strong>Project Title:</strong>&nbsp;{project_title}</p><p><strong>Project Start Date:</strong>&nbsp;{project_start_date}</p><p><b>Project Due Date</b>:&nbsp;{project_due_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""></p><p>We are looking forward hearing from you.<br><br>Kind Regards,<br>{app_name}</p><p></p>',
                    'es' => '<p>Hola&nbsp;{project_client},</p><p>Se te ha asignado un nuevo proyecto.<br><br><b>T??tulo del Proyecto:</b>&nbsp;{project_title}</p><p><b>Fecha de inicio del proyecto</b><strong>:</strong>&nbsp;{project_start_date}</p><p><b>Fecha de vencimiento del proyecto</b>:&nbsp;{project_due_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""></p><p>Esperamos tener noticias tuyas.<br><br><b>Saludos cordiales</b>,<br>{app_name}</p><p></p>',
                    'fr' => '<p>Bonjour&nbsp;{project_client},</p><p>Un nouveau projet vous est attribu??.<br><br><b>Titre du projet:</b>&nbsp;{project_title}</p><p><b>Date de d??but du projet</b><strong>:</strong>&nbsp;{project_start_date}</p><p><b>Date d\'??ch??ance du projet</b>:&nbsp;{project_due_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""></p><p>Nous sommes impatients de vous entendre.<br><br><b>Sinc??res amiti??s</b>,<br>{app_name}</p><p></p>',
                    'it' => '<p>Ciao&nbsp;{project_client},</p><p>Ti viene assegnato un nuovo progetto.<br><br><b>titolo del progetto:</b>&nbsp;{project_title}</p><p><b>Data di inizio del progetto</b><strong>:</strong>&nbsp;{project_start_date}</p><p><b>Data di scadenza del progetto</b>:&nbsp;{project_due_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""></p><p>Non vediamo l\'ora di sentirti.<br><br><b>Cordiali saluti</b>,<br>{app_name}</p><p></p>',
                    'ja' => '<p>???????????????&nbsp;{project_client},</p><p>??????????????????????????????????????????????????????????????????.<br><br>?????????????????????<b>:</b>&nbsp;{project_title}</p><p>???????????????????????????<strong>:</strong>&nbsp;{project_start_date}</p><p>???????????????????????????:&nbsp;{project_due_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""></p><p>??????????????????????????????????????????.<br><br>??????,<br>{app_name}</p><p></p>',
                    'nl' => '<p>Hallo&nbsp;{project_client},</p><p>Er is een nieuw project aan u toegewezen.<br><br><b>project titel:</b>&nbsp;{project_title}</p><p><b>Startdatum van het project</b><strong>:</strong>&nbsp;{project_start_date}</p><p><b>Project vervaldatum</b>:&nbsp;{project_due_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""></p><p>We horen graag van je.<br><br><b>Vriendelijke groeten</b>,<br>{app_name}</p><p></p>',
                    'pl' => '<p>cze????&nbsp;{project_client},</p><p>Nowy projekt zosta?? Ci przypisany.<br><br><b>Tytu?? Projektu:</b>&nbsp;{project_title}</p><p><b>Data rozpocz??cia projektu</b><strong>:</strong>&nbsp;{project_start_date}</p><p><b>Termin realizacji projektu</b>:&nbsp;{project_due_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""></p><p>Czekamy na wiadomo???? od Ciebie.<br><br><b>Z powa??aniem</b>,<br>{app_name}</p><p></p>',
                    'ru' => '<p>????????????????????????&nbsp;{project_client},</p><p>?????? ???????????????? ?????????? ????????????.<br><br>???????????????? ??????????????<b>:</b>&nbsp;{project_title}</p><p>???????? ???????????? ??????????????<strong>:</strong>&nbsp;{project_start_date}</p><p>???????? ???????????????????? ??????????????:&nbsp;{project_due_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""></p><p>???? ?? ?????????????????????? ???????? ???????????? ????????????.<br><br>?? ??????????????????,<br>{app_name}</p><p></p>',
                ],
            ],
            'project_assign' => [
                'subject' => 'Assign Project',
                'lang' => [
                    'ar' => '<p>??????????&nbsp;{project_assign_user},</p><p>???? ?????????? ?????????? ???????? ????.</p><p>?????????? ??????????????<strong>:</strong>&nbsp;{project_title}</p><p>?????????? ?????? ??????????????<strong>:</strong>&nbsp;{project_start_date}</p><p>?????????? ?????????????? ??????????????:&nbsp;{project_due_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""></p><p>?????? ?????????? ?????? ???????????????? ??????.<br><br>???????? ??????????????,<br>{app_name}</p><p></p>',
                    'da' => '<p>Hej&nbsp;{project_assign_user},</p><p>Nyt projekt er tildelt dig.<br><br><b>Projekt titel:</b>&nbsp;{project_title}</p><p><b>Projektets startdato</b><strong>:</strong>&nbsp;{project_start_date}</p><p><b>Projektets forfaldsdato</b>:&nbsp;{project_due_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""></p><p>Vi gl??der os til at h??re fra dig.<br><br><b>Med venlig hilsen</b>,<br>{app_name}</p><p></p>',
                    'de' => '<p>Hallo&nbsp;{project_assign_user},</p><p>Ihnen wird ein neues Projekt zugewiesen.<br><br><b>Projekttitel:</b>&nbsp;{project_title}</p><p><b>Projektstartdatum</b><strong>:</strong>&nbsp;{project_start_date}</p><p><b>Projektf??lligkeit</b>:&nbsp;{project_due_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""></p><p>Wir freuen uns von Ihnen zu h??ren.<br><br><b>Mit freundlichen Gr????en</b>,<br>{app_name}</p><p></p>',
                    'en' => '<p>Hello&nbsp;{project_assign_user},</p><p>New project is assigned to you.<br><br><strong>Project Title:</strong>&nbsp;{project_title}</p><p><strong>Project Start Date:</strong>&nbsp;{project_start_date}</p><p><b>Project Due Date</b>:&nbsp;{project_due_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""></p><p>We are looking forward hearing from you.<br><br>Kind Regards,<br>{app_name}</p><p></p>',
                    'es' => '<p>Hola&nbsp;{project_assign_user},</p><p>Se te ha asignado un nuevo proyecto.<br><br><b>T??tulo del Proyecto:</b>&nbsp;{project_title}</p><p><b>Fecha de inicio del proyecto</b><strong>:</strong>&nbsp;{project_start_date}</p><p><b>Fecha de vencimiento del proyecto</b>:&nbsp;{project_due_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""></p><p>Esperamos tener noticias tuyas.<br><br><b>Saludos cordiales</b>,<br>{app_name}</p><p></p>',
                    'fr' => '<p>Bonjour&nbsp;{project_assign_user},</p><p>Un nouveau projet vous est attribu??.<br><br><b>Titre du projet:</b>&nbsp;{project_title}</p><p><b>Date de d??but du projet</b><strong>:</strong>&nbsp;{project_start_date}</p><p><b>Date d\'??ch??ance du projet</b>:&nbsp;{project_due_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""></p><p>Nous sommes impatients de vous entendre.<br><br><b>Sinc??res amiti??s</b>,<br>{app_name}</p><p></p>',
                    'it' => '<p>Ciao&nbsp;{project_assign_user},</p><p>Ti viene assegnato un nuovo progetto.<br><br><b>titolo del progetto:</b>&nbsp;{project_title}</p><p><b>Data di inizio del progetto</b><strong>:</strong>&nbsp;{project_start_date}</p><p><b>Data di scadenza del progetto</b>:&nbsp;{project_due_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""></p><p>Non vediamo l\'ora di sentirti.<br><br><b>Cordiali saluti</b>,<br>{app_name}</p><p></p>',
                    'ja' => '<p>???????????????&nbsp;{project_assign_user},</p><p>??????????????????????????????????????????????????????????????????.<br><br>?????????????????????<b>:</b>&nbsp;{project_title}</p><p>???????????????????????????<strong>:</strong>&nbsp;{project_start_date}</p><p>???????????????????????????:&nbsp;{project_due_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""></p><p>??????????????????????????????????????????.<br><br>??????,<br>{app_name}</p><p></p>',
                    'nl' => '<p>Hallo&nbsp;{project_assign_user},</p><p>Er is een nieuw project aan u toegewezen.<br><br><b>project titel:</b>&nbsp;{project_title}</p><p><b>Startdatum van het project</b><strong>:</strong>&nbsp;{project_start_date}</p><p><b>Project vervaldatum</b>:&nbsp;{project_due_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""></p><p>We horen graag van je.<br><br><b>Vriendelijke groeten</b>,<br>{app_name}</p><p></p>',
                    'pl' => '<p>cze????&nbsp;{project_assign_user},</p><p>Nowy projekt zosta?? Ci przypisany.<br><br><b>Tytu?? Projektu:</b>&nbsp;{project_title}</p><p><b>Data rozpocz??cia projektu</b><strong>:</strong>&nbsp;{project_start_date}</p><p><b>Termin realizacji projektu</b>:&nbsp;{project_due_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""></p><p>Czekamy na wiadomo???? od Ciebie.<br><br><b>Z powa??aniem</b>,<br>{app_name}</p><p></p>',
                    'ru' => '<p>????????????????????????&nbsp;{project_assign_user},</p><p>?????? ???????????????? ?????????? ????????????.<br><br>???????????????? ??????????????<b>:</b>&nbsp;{project_title}</p><p>???????? ???????????? ??????????????<strong>:</strong>&nbsp;{project_start_date}</p><p>???????? ???????????????????? ??????????????:&nbsp;{project_due_date}</p><p style="line-height: 28px; font-family: Nunito, " segoe="" ui",="" arial;="" font-size:="" 14px;"=""></p><p>???? ?? ?????????????????????? ???????? ???????????? ????????????.<br><br>?? ??????????????????,<br>{app_name}</p><p></p>',
                ],
            ],
            'project_finished' => [
                'subject' => 'Project mark as finished',
                'lang' => [
                    'ar' => '<p>??????????&nbsp;{project_client},</p><p>?????? ?????????? ?????? ???????????? ???????????????????? ???????? ??????????????&nbsp;<strong>{project}</strong> ???? ???????????? ?????? ?????? ??????????. ???? ?????????? ?????? ?????????????? ?????? ?????????? ???????????? ?????? ???? ?????????? ?????? ?????????? ????????.<br></p><p>?????? ?????? ???????? ???? ?????????? ?? ?????? ?????????? ???? ?????????????? ??????.<br><br>???????? ??????????????,<br>{app_name}</p>',
                    'da' => '<p>Hej&nbsp;{project_client},</p><p>Du modtager denne e-mail, fordi projektet&nbsp;<strong>{project}</strong>er markeret som f??rdig. Dette projekt er tildelt under din virksomhed, og vi ville bare holde dig opdateret.<br></p><p>Hvis du har sp??rgsm??l, s?? t??v ikke med at kontakte os.<br><br>Med venlig hilsen,<br>{app_name}</p>',
                    'de' => '<p>Hallo&nbsp;{project_client},</p><p>Sie erhalten diese E-Mail wegen Projekt&nbsp;<strong>{project}</strong> wurde als fertig markiert. Dieses Projekt ist Ihrem Unternehmen zugeordnet und wir wollten Sie nur auf dem Laufenden halten.<br></p><p>Wenn Sie Fragen haben, z??gern Sie nicht, uns zu kontaktieren.<br><br>Mit freundlichen Gr????en,<br>{app_name}</p>',
                    'en' => '<p><b>Hello</b>&nbsp;{project_client},</p><p>You are receiving this email because project&nbsp;<strong>{project}</strong> has been marked as finished. This project is assigned under your company and we just wanted to keep you up to date.<br></p><p>If you have any questions don\'t hesitate to contact us.<br><br>Kind Regards,<br>{app_name}</p>',
                    'es' => '<p>Hola&nbsp;{project_client},</p><p>Est??s recibiendo este correo electr??nico porque proyecto&nbsp;<strong>{project}</strong> ha sido marcado como terminado. Este proyecto est?? asignado a su empresa y solo quer??amos mantenerlo actualizado.<br></p><p>Si tiene alguna pregunta no dude en contactarnos..<br><br>Saludos cordiales,<br>{app_name}</p>',
                    'fr' => '<p>Hola&nbsp;{project_client},</p><p>Est??s recibiendo este correo electr??nico porque proyecto&nbsp;<strong>{project}</strong> ha sido marcado como terminado. Este proyecto est?? asignado a su empresa y solo quer??amos mantenerlo actualizado.<br></p><p>Si tiene alguna pregunta no dude en contactarnos..<br><br>Saludos cordiales,<br>{app_name}</p>',
                    'it' => '<p>Ciao&nbsp;{project_client},</p><p>Hai ricevuto questa email perch?? project&nbsp;<strong>{project}</strong>?? stato contrassegnato come finito. Questo progetto ?? assegnato dalla tua azienda e volevamo solo tenerti aggiornato.<br></p>    <p>Se hai domande non esitare a contattarci.<br><br>Cordiali saluti,<br>{app_name}</p>',
                    'ja' => '<p>???????????????&nbsp;{project_client},</p><p>???????????????????????????????????????????????????????????????????????????&nbsp;<strong>{project}</strong> ???????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????.<br></p><p>????????????????????????????????????????????????????????????????????????????????????.<br><br>??????,<br>{app_name}</p>',
                    'nl' => '<p>Hallo&nbsp;{project_client},</p><p>U ontvangt deze e-mail omdat project&nbsp;<strong>{project}</strong> is gemarkeerd als voltooid. Dit project is toegewezen onder uw bedrijf en we wilden u gewoon op de hoogte houden.<br></p><p>Mocht u nog vragen hebben, neem dan gerust contact met ons op.<br><br>Vriendelijke groeten,<br>{app_name}</p>',
                    'pl' => '<p>cze????&nbsp;{project_client},</p><p>Otrzymujesz t?? wiadomo???? e-mail, poniewa?? project&nbsp;<strong>{project}</strong>zosta?? oznaczony jako zako??czony. Ten projekt jest przypisany do Twojej firmy i chcieli??my tylko, aby?? by?? na bie????co.<br></p><p>Je??li masz jakie?? pytania, nie wahaj si?? z nami skontaktowa??.<br><br>Z powa??aniem,<br>{app_name}</p>',
                    'ru' => '<p>????????????????????????&nbsp;{project_client},</p><p>???? ???????????????? ?????? ????????????, ???????????? ?????? ????????????&nbsp;<strong>{project}</strong> ?????? ?????????????? ?????? ??????????????????????. ???????? ???????????? ?????????????????? ???? ?????????? ??????????????????, ?? ???? ???????????? ???????????? ?????????????? ?????? ?? ??????????..<br></p><p>???????? ?? ?????? ???????? ??????????????, ???? ?????????????????????? ???????????????????? ?? ??????.<br><br>?? ??????????????????,<br>{app_name}</p>',
                ],
            ],
            'task_assign' => [
                'subject' => 'Project task assign',
                'lang' => [
                    'ar' => '<p>???????????? {task_assign_user}</p><p>?????? ???? ???????????? ?????????? ??????????:</p><p>??????: {task_title}<br>?????????? ??????????: {task_start_date}<br>?????????? ??????????????????: {task_due_date}<br>????????????: {task_priority}<br><br>???????? ??????????????,<br>{app_name}</p>',
                    'da' => '<p>K??re {task_assign_user}</p><p>Du er blevet tildelt en ny opgave:</p><p><b>Navn</b>: {task_title}<br><b>Start dato</b>: {task_start_date}<br><b>Afleveringsdato</b>: {task_due_date}<br><b>Prioritet</b>: {task_priority}<br><br><b>Kind Regards</b>,<br>{app_name}</p>',
                    'de' => '<p>sehr geehrter {task_assign_user}</p><p>Sie wurden einer neuen Aufgabe zugewiesen:</p><p><b>Name</b>: {task_title}<br><b>Anfangsdatum</b>: {task_start_date}<br><b>Geburtstermin</b>: {task_due_date}<br><b>Priorit??t</b>: {task_priority}<br><br><b>Mit freundlichen Gr????en</b>,<br>{app_name}</p>',
                    'en' => '<p>Dear {task_assign_user}</p><p>You have been assigned to a new task:</p><p><b>Name</b>: {task_title}<br><b>Start Date</b>: {task_start_date}<br><b>Due date</b>: {task_due_date}<br><b>Priority</b>: {task_priority}<br><br>Kind Regards,<br>{app_name}</p>',
                    'es' => '<p>sehr geehrter {task_assign_user}</p><p>Sie wurden einer neuen Aufgabe zugewiesen:</p><p><b>Name</b>: {task_title}<br><b>Anfangsdatum</b>: {task_start_date}<br><b>Geburtstermin</b>: {task_due_date}<br><b>Priorit??t</b>: {task_priority}<br><br><b>Mit freundlichen Gr????en</b>,<br>{app_name}</p>',
                    'fr' => '<p>Ch??re {task_assign_user}</p><p>Vous avez ??t?? affect?? ?? une nouvelle t??che:</p><p><b>Nom</b>: {task_title}<br><b>Date de d??but</b>: {task_start_date}<br><b>Date d\'??ch??ance</b>: {task_due_date}<br><b>Priorit??</b>: {task_priority}<br><br><b>Sinc??res amiti??s</b>,<br>{app_name}</p>',
                    'it' => '<p>Cara {task_assign_user}</p><p>Sei stato assegnato a una nuova attivit??:</p><p><b>Nome</b>: {task_title}<br><b>Data d\'inizio</b>: {task_start_date}<br><b>Scadenza</b>: {task_due_date}<br><b>Priorit??</b>: {task_priority}<br><br><b>Cordiali saluti</b>,<br>{app_name}</p>',
                    'ja' => '<p>????????? {task_assign_user}</p><p>????????????????????????????????????????????????:</p><p>??????: {task_title}<br>?????????: {task_start_date}<br>??????: {task_due_date}<br>??????: {task_priority}<br><br>??????,<br>{app_name}</p>',
                    'nl' => '<p>Lieve {task_assign_user}</p><p>U bent aan een nieuwe taak toegewezen:</p><p><b>Naam</b>: {task_title}<br><b>Begin datum</b>: {task_start_date}<br><b>Opleveringsdatum</b>: {task_due_date}<br><b>Prioriteit</b>: {task_priority}<br><br><b>Vriendelijke groeten</b>,<br>{app_name}</p>',
                    'pl' => '<p>Drogi {task_assign_user}</p><p>Zosta??e?? przydzielony do nowego zadania:</p><p><b>Nazwa</b>: {task_title}<br><b>Data rozpocz??cia</b>: {task_start_date}<br><b>Termin</b>: {task_due_date}<br><b>Priorytet</b>: {task_priority}<br><br>Z powa??aniem,<br>{app_name}</p>',
                    'ru' => '<p>?????????????? {task_assign_user}</p><p>?????? ???????????????? ?????????? ????????????:</p><p><b>??????</b>: {task_title}<br><b>???????? ????????????</b>: {task_start_date}<br><b>????????</b>: {task_due_date}<br><b>??????????????????</b>: {task_priority}<br><br><b>?? ??????????????????</b>,<br>{app_name}</p>',
                ],
            ],
            'send_invoice' => [
                'subject' => 'Invoice Send',
                'lang' => [
                    'ar' => '????????????<span style="font-size: 12pt;">&nbsp;{invoice_client}</span><span style="font-size: 12pt;">,</span><br><br>?????? ???????? ???????????? ???????????????? ?????????????? ???? ????????<span style="font-size: 12pt;">: </span><strong style="font-size: 12pt;">&nbsp;{invoice_id}</strong><br><br>???????? ????????????????<span style="font-size: 12pt;">: {invoice_status}</span><br><br><br>???????? ?????????????? ?????? ???????????? ?????? ???????? ???? ??????????????????<span style="font-size: 12pt;">.</span><br><br>???????? ??????????????<span style="font-size: 12pt;">,</span><br>{app_name}',
                    'da' => 'K??re<span style="font-size: 12pt;">&nbsp;{invoice_client}</span><span style="font-size: 12pt;">,</span><br><br>Vi har udarbejdet f??lgende faktura til dig<span style="font-size: 12pt;">:&nbsp;&nbsp;{invoice_id}</span><br><br>Fakturastatus: {invoice_status}<br><br>Kontakt os for mere information<span style="font-size: 12pt;">.</span><br><br>Med venlig hilsen<span style="font-size: 12pt;">,</span><br>{app_name}',
                    'de' => '<p><b>sehr geehrter</b><span style="font-size: 12pt;">&nbsp;{invoice_client}</span><br><br>Wir haben die folgende Rechnung f??r Sie vorbereitet<span style="font-size: 12pt;">: {invoice_id}</span><br><br><b>Rechnungsstatus</b><span style="font-size: 12pt;">: {invoice_status}</span></p><p>Bitte kontaktieren Sie uns f??r weitere Informationen<span style="font-size: 12pt;">.</span><br><br><b>Mit freundlichen Gr????en</b><span style="font-size: 12pt;">,</span><br>{app_name}</p>',
                    'en' => '<p><span style="font-size: 12pt;"><b>Dear</b>??{invoice_client}</span><span style="font-size: 12pt;">,</span></p><p><span style="font-size: 12pt;">We have prepared the following invoice for you :#{invoice_id}</span></p><p><span style="font-size: 12pt;"><b>Invoice Status</b> : {invoice_status}</span></p><p>Please Contact us for more information.</p><p><br><b>Kind Regards</b>,<br><span style="font-size: 12pt;">{app_name}</span><br></p>',
                    'es' => '<p><b>Querida</b><span style="font-size: 12pt;">&nbsp;{invoice_client}</span><span style="font-size: 12pt;">,</span></p><p>Hemos preparado la siguiente factura para ti<span style="font-size: 12pt;">:&nbsp;&nbsp;{invoice_id}</span></p><p><b>Estado de la factura</b><span style="font-size: 12pt;">: {invoice_status}</span></p><p>Por favor cont??ctenos para m??s informaci??n<span style="font-size: 12pt;">.</span></p><p><b>Saludos cordiales</b><span style="font-size: 12pt;">,<br></span>{app_name}</p>',
                    'fr' => '<p><b>Cher</b><span style="font-size: 12pt;">&nbsp;{invoice_client}</span><span style="font-size: 12pt;">,</span></p><p>Nous avons pr??par?? la facture suivante pour vous<span style="font-size: 12pt;">: {invoice_id}</span></p><p><b>??tat de la facture</b><span style="font-size: 12pt;">: {invoice_status}</span></p><p>Veuillez nous contacter pour plus d\'informations<span style="font-size: 12pt;">.</span></p><p><b>Sinc??res amiti??s</b><span style="font-size: 12pt;">,<br></span>{app_name}</p>',
                    'it' => '<p><b>Caro</b><span style="font-size: 12pt;">&nbsp;{invoice_client}</span><span style="font-size: 12pt;">,</span></p><p>Abbiamo preparato per te la seguente fattura<span style="font-size: 12pt;">:&nbsp;&nbsp;{invoice_id}</span></p><p><b>Stato della fattura</b><span style="font-size: 12pt;">: {invoice_status}</span></p><p>Vi preghiamo di contattarci per ulteriori informazioni<span style="font-size: 12pt;">.</span></p><p><b>Cordiali saluti</b><span style="font-size: 12pt;">,<br></span>{app_name}</p>',
                    'ja' => '?????????<span style="font-size: 12pt;">&nbsp;{invoice_client}</span><span style="font-size: 12pt;">,</span><br><br>???????????????????????????????????????????????????<span style="font-size: 12pt;">: {invoice_client}</span><br><br>???????????????????????????<span style="font-size: 12pt;">: {invoice_status}</span><br><br>??????????????????????????????????????????<span style="font-size: 12pt;">.</span><br><br>??????<span style="font-size: 12pt;">,</span><br>{app_name}',
                    'nl' => '<p><b>Lieve</b><span style="font-size: 12pt;">&nbsp;{invoice_client}</span><span style="font-size: 12pt;">,</span></p><p>We hebben de volgende factuur voor u opgesteld<span style="font-size: 12pt;">: {invoice_id}</span></p><p><b>Factuurstatus</b><span style="font-size: 12pt;">: {invoice_status}</span></p><p>Voor meer informatie kunt u contact met ons opnemen<span style="font-size: 12pt;">.</span></p><p><b>Vriendelijke groeten</b><span style="font-size: 12pt;">,<br></span>{app_name}</p>',
                    'pl' => '<p><b>Drogi</b><span style="font-size: 12pt;">&nbsp;{invoice_client}</span><span style="font-size: 12pt;">,</span></p><p>Przygotowali??my dla Ciebie nast??puj??c?? faktur??<span style="font-size: 12pt;">: {invoice_id}</span></p><p><b>Status faktury</b><span style="font-size: 12pt;">: {invoice_status}</span></p><p>Skontaktuj si?? z nami, aby uzyska?? wi??cej informacji<span style="font-size: 12pt;">.</span></p><p><b>Z powa??aniem</b><span style="font-size: 12pt;"><b>,</b><br></span>{app_name}</p>',
                    'ru' => '<p><b>??????????????</b><span style="font-size: 12pt;">&nbsp;{invoice_client}</span><span style="font-size: 12pt;">,</span></p><p>???? ?????????????????????? ?????? ?????? ?????????????????? ????????<span style="font-size: 12pt;">: {invoice_id}</span></p><p><b>???????????? ??????????</b><span style="font-size: 12pt;">: {invoice_status}</span></p><p>????????????????????, ?????????????????? ?? ???????? ?????? ?????????????????? ???????????????????????????? ????????????????????<span style="font-size: 12pt;">.</span></p><p><b>?? ??????????????????</b><span style="font-size: 12pt;">,<br></span>{app_name}</p>',
                ],
            ],


            'invoice_payment_recored' => [
                'subject' => 'Invoice Payment Recored',
                'lang' => [
                    'ar' => '<p>??????????<span style="font-size: 12pt;">&nbsp;{invoice_client}</span><span style="font-size: 12pt;"><br><br></span><span style="font-size: 12pt;"><br></span>???????? ?????? ??????????. ???????? ???? ???????????? ?????????? ??????????:<br>-------------------------------------------------<br>????????: {payment_total}<strong><br></strong>??????????:&nbsp; {payment_date<strong><br></strong>?????? ????????????????: {invoice_id}<span style="font-size: 12pt;"><strong><br><br></strong></span><span style="font-size: 12pt;"><strong><br></strong></span>-------------------------------------------------<br>?????? ?????????? ?????? ?????????? ??????.<br>???????? ??????????????<span style="font-size: 12pt;">,</span><br>{app_name}</p>',
                    'da' => '<p></p><p></p><h4><blockquote class="blockquote"><p><b>Hej</b><span style="font-size: 12pt;">&nbsp;{invoice_client},</span><span style="font-size: 12pt;"><br></span></p></blockquote></h4><p></p><p></p><p>Tak for betalingen. Find betalingsoplysningerne nedenfor:<br>-------------------------------------------------<br></p><p><b>Bel??b</b>: {payment_total}<strong><br></strong></p><p><b>Dato</b>: {payment_date}<strong><br></strong></p><p><b>Faktura nummer</b>: {invoice_id}<span style="font-size: 12pt;"><strong><br><br></strong></span><span style="font-size: 12pt;"><strong><br></strong></span>-------------------------------------------------<br></p><p>Vi ser frem til at arbejde sammen med dig.<br></p><p><b>Med venlig hilsen</b><span style="font-size: 12pt;">,<br></span>{app_name}</p>',
                    'de' => '<p><b>Hallo</b>&nbsp;{invoice_client}<br><br><br></p><p>Vielen Dank f??r die Zahlung. Hier finden Sie die Zahlungsdetails:<br>-------------------------------------------------<br></p><p><b>Menge</b>: {payment_total}<br></p><p><b>Datum</b>: {payment_date}<br></p><p><b>Rechnungsnummer</b>: {invoice_id}<br><br><br>-------------------------------------------------<br></p><p>Wir freuen uns auf die Zusammenarbeit mit Ihnen.<br></p><p><b>Mit freundlichen Gr????en</b>,<br>{app_name}</p>',
                    'en' => '<p><span style="font-size: 12pt;"><b>Hello</b>&nbsp;{invoice_client}</span><span style="font-size: 12pt;"><br><br></span><span style="font-size: 12pt;"><br></span>Thank you for the payment. Find the payment details below:<br>-------------------------------------------------<br><b>Amount</b>: {payment_total}<strong><br></strong><b>Date</b>: {payment_date}<strong><br></strong><b>Invoice number</b>: {invoice_id}<span style="font-size: 12pt;"><strong><br></strong></span><span style="font-size: 12pt;"><strong><br></strong></span>-------------------------------------------------<br>We are looking forward working with you.<br><span style="font-size: 12pt;"><b>Kind Regards</b>,<br></span>{app_name}</p>',
                    'es' => '<p><b>Hola</b>&nbsp;{invoice_client}<br><br><br></p><p>Gracias por el pago. Encuentre los detalles de pago a continuaci??n:<br>-------------------------------------------------<br></p><p><b>Cantidad</b>:&nbsp; {payment_total}<br></p><p><b>Fecha</b>: {payment_date}<br></p><p><b>N??mero de factura</b>: {invoice_id}<br><br><br>-------------------------------------------------<br></p><p>Esperamos trabajar con usted.<br></p><p><b>Saludos cordiales</b>,<br>{invoice_id}</p>',
                    'fr' => '<p><b>Bonjour</b>&nbsp;{invoice_client},<br><br><br></p><p>Merci pour le paiement. Trouvez les d??tails de paiement ci-dessous:<br>-------------------------------------------------<br></p><p><b>Montant</b>: {payment_total}<br></p><p><b>Date</b>: {payment_date}<br></p><p><b>Num??ro de facture</b>: {invoice_id}<br><br><br>-------------------------------------------------<br></p><p>Nous sommes impatients de travailler avec vous.<br></p><p><b>Sinc??res amiti??s</b>,<br>{app_name}</p>',
                    'it' => '<p><b>Ciao</b>&nbsp;{invoice_client},<br><br><br></p><p>Grazie per il pagamento. Trova i dettagli di pagamento di seguito:<br>-------------------------------------------------<br></p><p><b>Quantit??</b>: {payment_total}<br></p><p><b>Data</b>: {payment_date}<br></p><p><b>Numero di fattura</b>: {invoice_id}<br><br><br>-------------------------------------------------<br></p><p>Non vediamo l\'ora di lavorare con te.<br></p><p><b>Cordiali saluti</b>,</p><p>{app_name}</p>',
                    'ja' => '<p>??????????????? {invoice_client}<br><br><br></p><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Translation" id="tw-target-text" dir="ltr" style="unicode-bidi: isolate; line-height: 32px; border: none; padding: 2px 0.14em 2px 0px; position: relative; margin-top: -2px; margin-bottom: -2px; resize: none; overflow: hidden; width: 277px; overflow-wrap: break-word;"><span lang="ja" style="">??????????????????????????????????????????????????????????????????????????????????????????????????????????????????</span></pre><p>-------------------------------------------------<br></p><p>???: {payment_total}<br></p><p>??????: {payment_date}<br></p><p>???????????????: {invoice_id}<br><br><br>-------------------------------------------------<br></p><p>?????????????????????????????????????????????.<br></p><p>??????,<br>{app_name}</p>',
                    'nl' => '<p><b>Hallo</b>&nbsp;{invoice_client},<br><br><br></p><p>Bedankt voor de betaling. Vind de betalingsgegevens hieronder:<br>-------------------------------------------------<br></p><p><b>Bedrag</b>: {payment_total}<br></p><p><b>Datum</b>: {payment_date}<br></p><p><b>Factuurnummer</b>: {invoice_id}<br><br><br>-------------------------------------------------<br></p><p>We kijken er naar uit om met u samen te werken.<br></p><p><b>Vriendelijke groeten</b>,<br>{app_name}</p>',
                    'pl' => '<p><b>cze????</b>&nbsp; {invoice_client},<br><br><br></p><p>Dzi??kuj?? za wp??at??. Znajd?? szczeg????y p??atno??ci poni??ej:<br>-------------------------------------------------<br></p><p><b>Ilo????</b>: {payment_total}<br></p><p><b>Data</b>: {payment_date}<br></p><p><b>Numer faktury</b>: {invoice_id}<br><br><br>-------------------------------------------------<br></p><p>Cieszymy si?? na wsp????prac?? z Tob??.<br></p><p><b>Z powa??aniem</b>,<br>{app_name}</p>',
                    'ru' => '<p><b>????????????????????????</b>&nbsp;{invoice_client},<br></p><p>?????????????? ???? ????????????. ?????????????? ???????????????????? ?? ?????????????? ????????:<br>-------------------------------------------------<br></p><p><b>????????????????????</b>: {payment_total}<br></p><p><b>????????</b>: {payment_date}<br></p><p><b>?????????? ??????????</b>: {invoice_id}<br><br><br>-------------------------------------------------<br></p><p>?????????? ???????? ???????????????????????????? ?? ????????.<br></p><p><b>?? ??????????????????</b>,<br>{app_name}</p>',
                ],
            ],
            'credit_note' => [
                'subject' => 'Credit Note',
                'lang' => [
                    'ar' => '<p><span style="background-color: rgb(248, 249, 250); color: rgb(34, 34, 34); font-family: inherit; font-size: 24px; text-align: right; white-space: pre-wrap;">????????????</span>&nbsp;{invoice_client}</p><p><span style="background-color: rgb(248, 249, 250); color: rgb(34, 34, 34); font-family: inherit; font-size: 24px; text-align: right; white-space: pre-wrap;">?????? ???????????? ?????????? ???????????????? </span><span style="background-color: rgb(248, 249, 250); color: rgb(34, 34, 34); font-family: inherit; font-size: 24px; text-align: right; white-space: pre-wrap;">???????????? ????????</span><span style="font-size: 1rem;">&nbsp; #{invoice_id}&nbsp;</span><span style="background-color: rgb(248, 249, 250); color: rgb(34, 34, 34); font-family: inherit; font-size: 24px; text-align: right; white-space: pre-wrap;">????????????</span><span style="font-size: 1rem;">.</span></p><p><span style="background-color: rgb(248, 249, 250); color: rgb(34, 34, 34); font-family: inherit; font-size: 24px; text-align: right; white-space: pre-wrap;">??????????:</span>&nbsp;{credit_note_date}</p><p><span style="background-color: rgb(248, 249, 250); color: rgb(34, 34, 34); font-family: inherit; font-size: 24px; text-align: right; white-space: pre-wrap;">???????????? ????????????????</span>:&nbsp;{credit_amount}</p><p><span style="background-color: rgb(248, 249, 250); color: rgb(34, 34, 34); font-family: inherit; font-size: 24px; text-align: right; white-space: pre-wrap;">???????? ?????????????? ?????? ???????????? </span><span style="background-color: rgb(248, 249, 250); color: rgb(34, 34, 34); font-family: inherit; font-size: 24px; text-align: right; white-space: pre-wrap;">?????? ???????? ???? ??????????????????.</span><span style="font-size: 1rem;">.</span></p><p><span style="background-color: rgb(248, 249, 250); color: rgb(34, 34, 34); font-family: inherit; font-size: 24px; text-align: right; white-space: pre-wrap;">???????? ????????????????</span>,</p>{app_name}',
                    'da' => '<p><b>K??re</b>&nbsp;{invoice_client}</p><p>Vi har vedh??ftet kreditnotaen med nummer&nbsp;#{invoice_id}&nbsp;til din reference.</p><p><b>Dato</b>:&nbsp;{credit_note_date}</p><p><b>Total bel??b</b>:&nbsp;{credit_amount}</p><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Translation" id="tw-target-text" dir="ltr" style="unicode-bidi: isolate; line-height: 36px; border: none; padding: 2px 0.14em 2px 0px; position: relative; margin-top: -2px; margin-bottom: -2px; resize: none; overflow: hidden; width: 277px; overflow-wrap: break-word;"><span lang="da">Kontakt os for mere information</span></pre><p><b>Med venlig hilsen</b>,</p>{app_name}',
                    'de' => '<p><b>sehr geehrter</b>&nbsp;{invoice_client}</p><p>Wir haben die Gutschrift mit der Nummer&nbsp;#{invoice_id}&nbsp;als Referenz beigef??gt.</p><p><b>Datum</b>:&nbsp;{credit_note_date}</p><p><b>Gesamtsumme</b>:&nbsp;{credit_amount}</p><p>Bitte kontaktieren Sie uns f??r weitere Informationen.</p><p><b>Mit freundlichen</b>,</p>{app_name}',
                    'en' => '<p><b>Dear</b>&nbsp;{invoice_client}</p><p>We have attached the credit note with number #{invoice_id} for your reference.</p><p><b>Date</b>:&nbsp;{credit_note_date}</p><p><b>Total Amount</b>:&nbsp;{credit_amount}</p><p>Please contact us for more information.</p><p><b>Kind Regards</b>,</p>{app_name}',
                    'es' => '<p><b>querido</b>&nbsp;{invoice_client}</p><p>Hemos adjuntado la nota de cr??dito con el n??mero &nbsp;#{invoice_id}&nbsp;para su referencia.</p><p><b>Date</b>:&nbsp;{credit_note_date}</p><p><b>Cantidad total</b>:&nbsp;{credit_amount}</p><p>Por favor cont??ctenos para m??s informaci??n.</p><p><b>Saludos cordiales</b>,</p>{app_name}',
                    'fr' => '<p><b>ch??re</b>&nbsp;{invoice_client}</p><p>Nous avons joint la note de cr??dit avec le num??ro #{invoice_id}&nbsp;pour votre r??f??rence.</p><p><b>Date</b>:&nbsp;{credit_note_date}</p><p><b>Montant total</b>:&nbsp;{credit_amount}</p><p>Veuillez nous contacter pour plus d\'informations.</p><p><b>Sinc??res amiti??s</b>,</p>{app_name}',
                    'it' => '<p><b>caro</b>&nbsp;{invoice_client}</p><p>Abbiamo allegato la nota di credito con il numero&nbsp;#{invoice_id}&nbsp;come riferimento.</p><p><b>Data</b>:&nbsp;{credit_note_date}</p><p><b>Importo totale</b>:&nbsp;{credit_amount}</p><p>Vi preghiamo di contattarci per ulteriori informazioni.</p><p><b>Cordiali saluti</b>,</p>{app_name}',
                    'ja' => '<p><span style="background-color: rgb(248, 249, 250); color: rgb(34, 34, 34); font-family: inherit; font-size: 24px; white-space: pre-wrap;">?????????</span>&nbsp;{invoice_client}</p><p><span style="background-color: rgb(248, 249, 250); color: rgb(34, 34, 34); font-family: inherit; font-size: 24px; white-space: pre-wrap;">?????????????????????????????????????????????????????????????????????????????????</span>.</p><p><span style="background-color: rgb(248, 249, 250); color: rgb(34, 34, 34); font-family: inherit; font-size: 24px; white-space: pre-wrap;">??????</span>:&nbsp;{credit_note_date}</p><p><span style="background-color: rgb(248, 249, 250); color: rgb(34, 34, 34); font-family: inherit; font-size: 24px; white-space: pre-wrap;">????????????</span>:&nbsp;{credit_amount}</p><p><span style="background-color: rgb(248, 249, 250); color: rgb(34, 34, 34); font-family: inherit; font-size: 24px; white-space: pre-wrap;">??????????????????????????????????????????</span></p><p><span style="background-color: rgb(248, 249, 250); color: rgb(34, 34, 34); font-family: inherit; font-size: 24px; white-space: pre-wrap;"><br></span></p><p><span style="background-color: rgb(248, 249, 250); color: rgb(34, 34, 34); font-family: inherit; font-size: 24px; white-space: pre-wrap;">?????????</span><span style="font-size: 1rem;">,</span><br></p>{app_name}',
                    'nl' => '<p><b>Lieve</b>&nbsp;{invoice_client}</p><p>Ter referentie hebben we de creditnota met nummer # bijgevoegd.</p><p><b>Datum</b>:&nbsp;{credit_note_date}</p><p><b>Totaalbedrag</b>:&nbsp;{credit_amount}</p><p>Voor meer informatie kunt u contact met ons opnemen.</p><p><b>Vriendelijke groeten</b>,</p>{app_name}',
                    'pl' => '<p><b>Drogi</b>&nbsp;{invoice_client}</p><p>W celach informacyjnych za????czyli??my not?? ??????kredytow?? z numerem # invoice_id.</p><p><b>Data</b>:&nbsp;{credit_note_date}</p><p><b>Ca??kowita kwota</b>:&nbsp;{credit_amount}</p><p>Skontaktuj si?? z nami, aby uzyska?? wi??cej informacji.</p><p><b>Z powa??aniem</b>,</p>{app_name}',
                    'ru' => '<p><b>??????????????</b>&nbsp;{invoice_client}</p><p>???? ?????????????????? ????????????-???????? ?????? ??????????????&nbsp;#{invoice_id}&nbsp;?????? ?????????? ??????????????.</p><p><b>????????</b>:&nbsp;{credit_note_date}</p><p><b>?????????? ????????????????????</b>:&nbsp;{credit_amount}</p><p>????????????????????, ?????????????????? ?? ???????? ?????? ?????????????????? ???????????????????????????? ????????????????????.</p><p><b>?? ??????????????????</b>,</p>{app_name}',
                ],
            ],
            'create_support' => [
                'subject' => 'Create Support',
                'lang' => [
                    'ar' => '<p><span style="background-color: rgb(248, 249, 250); color: rgb(34, 34, 34); font-family: inherit; font-size: 24px; text-align: right; white-space: pre-wrap;">??????????</span><span style="font-size: 12pt;">&nbsp;{assign_user}</span><br><br></p><p><span style="background-color: rgb(248, 249, 250); color: rgb(34, 34, 34); font-family: inherit; font-size: 24px; text-align: right; white-space: pre-wrap;">???? ?????? ?????????? ?????? ??????????.</span><span style="font-size: 12pt;">.</span><br><br></p><p><span style="background-color: rgb(248, 249, 250); color: rgb(34, 34, 34); font-family: inherit; font-size: 24px; text-align: right; white-space: pre-wrap;">??????????</span><span style="font-size: 12pt;"><strong>:</strong>&nbsp;{support_title}</span><br></p><p><span style="background-color: rgb(248, 249, 250); color: rgb(34, 34, 34); font-family: inherit; font-size: 24px; text-align: right; white-space: pre-wrap;">????????????</span><span style="font-size: 12pt;"><strong>:</strong>&nbsp;{support_priority}</span><span style="font-size: 12pt;"><br></span></p><p><span style="background-color: rgb(248, 249, 250); color: rgb(34, 34, 34); font-family: inherit; font-size: 24px; text-align: right; white-space: pre-wrap;">?????????? ????????????????</span><span style="font-size: 12pt;">: {support_end_date}</span></p><p><span style="background-color: rgb(248, 249, 250); color: rgb(34, 34, 34); font-family: inherit; font-size: 24px; text-align: right; white-space: pre-wrap;">?????????? ??????</span><span style="font-size: 12pt;"><strong>:</strong></span><br><span style="font-size: 12pt;">{support_description}</span><span style="font-size: 12pt;"><br><br></span></p><p><span style="background-color: rgb(248, 249, 250); color: rgb(34, 34, 34); font-family: inherit; font-size: 24px; text-align: right; white-space: pre-wrap;">???????? ????????????????</span><span style="font-size: 12pt;">,</span><br>{app_name}</p>',
                    'da' => '<p><b>Hej</b>&nbsp;{assign_user}<br><br></p><p>Ny supportbillet er blevet ??bnet.<br><br></p><p><b>Titel</b>: {support_title}<br></p><p><b>Prioritet</b>: {support_priority}<br></p><p><b>Slutdato</b>: {support_end_date}</p><p><br></p><p><b>Supportmeddelelse</b>:<br>{support_description}<br><br></p><p><b>Med venlig hilsen</b>,<br>{app_name}</p>',
                    'de' => '<p><b>Hallo</b>&nbsp;{assign_user}<br><br></p><p>Neues Support-Ticket wurde er??ffnet.<br><br></p><p><b>Titel</b>: {support_title}<br></p><p><b>Priorit??t</b>: {support_priority}<br></p><p><b>Endtermin</b>: {support_end_date}</p><p><br></p><p><b>Support-Nachricht</b>:<br>{support_description}<br><br></p><p><b>Mit freundlichen Gr????en</b>,<br>{app_name}</p>',
                    'en' => '<p><span style="font-size: 12pt;"><b>Hi</b>&nbsp;{assign_user}</span><br><br><span style="font-size: 12pt;">New support ticket has been opened.</span><br><br><span style="font-size: 12pt;"><strong>Title:</strong>&nbsp;{support_title}</span><br><span style="font-size: 12pt;"><strong>Priority:</strong>&nbsp;{support_priority}</span><span style="font-size: 12pt;"><br></span><span style="font-size: 12pt;"><b>End Date</b>: {support_end_date}</span></p><p><br><span style="font-size: 12pt;"><strong>Support message:</strong></span><br><span style="font-size: 12pt;">{support_description}</span><span style="font-size: 12pt;"><br><br><b>Kind Regards</b>,</span><br>{app_name}</p>',
                    'es' => '<p><b>Hola</b>&nbsp;{assign_user}<br><br></p><p>Se ha abierto un nuevo ticket de soporte.<br><br></p><p><b>T??tulo</b>: {support_title}<br></p><p><b>Prioridad</b>: {support_priority}<br></p><p><b>Fecha final</b>: {support_end_date}</p><p><br></p><p><b>Mensaje de apoyo</b>:<br>{support_description}<br><br></p><p><b>Saludos cordiales</b>,<br>{app_name}</p>',
                    'fr' => '<p><b>salut</b>&nbsp;{assign_user}<br><br></p><p>Un nouveau ticket d\'assistance a ??t?? ouvert.<br><br></p><p><b>Titre</b>: {support_title}<br></p><p><b>Priorit??</b>: {support_priority}<br></p><p><b>Date de fin</b>: {support_end_date}</p><p><b>Message d\'assistance</b>:<br>{support_description}<br><br></p><p><b>Sinc??res amiti??s</b>,<br>{app_name}</p>',
                    'it' => '<p><b>Ciao</b>&nbsp;{assign_user},<br><br></p><p>?? stato aperto un nuovo ticket di supporto.<br><br></p><p><b>Titolo</b>: {support_title}<br></p><p><b>Priorit??</b>: {support_priority}<br></p><p><b>Data di fine</b>: {support_end_date}</p><p><br></p><p><b>Messaggio di supporto</b>:<br>{support_description}</p><p><b>Cordiali saluti</b>,<br>{app_name}</p>',
                    'ja' => '<p>??????????????? {assign_user}<br><br></p><p>???????????????????????????????????????????????????????????????.<br><br></p><p>??????: {support_title}<br></p><p>??????: {support_priority}<br></p><p>?????????: {support_end_date}</p><p><br></p><p>???????????????????????????:<br>{support_description}<br><br></p><div class="tw-ta-container hide-focus-ring tw-lfl focus-visible" id="tw-target-text-container" tabindex="0" data-focus-visible-added="" style="overflow: hidden; position: relative; outline: 0px;"><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Translation" id="tw-target-text" dir="ltr" style="unicode-bidi: isolate; line-height: 32px; border: none; padding: 2px 0.14em 2px 0px; position: relative; margin-top: -2px; margin-bottom: -2px; resize: none; overflow: hidden; width: 277px; overflow-wrap: break-word;"><span lang="ja">?????????</span>,</pre></div><p>{app_name}</p>',
                    'nl' => '<p><b>Hoi</b>&nbsp;{assign_user}<br><br></p><p>Er is een nieuw supportticket geopend.<br><br></p><p><b>Titel</b>: {support_title}<br></p><p><b>Prioriteit</b>: {support_priority}<br></p><p><b>Einddatum</b>: {support_end_date}</p><p><br></p><p><b>Ondersteuningsbericht</b>:<br>{support_description}<br><br></p><p><b>Vriendelijke groeten</b>,<br>{app_name}</p>',
                    'pl' => '<p><b>cze????</b>&nbsp;{assign_user}<br><br></p><p>Nowe zg??oszenie do pomocy technicznej zosta??o otwarte.<br><br></p><p><b>Tytu??</b>: {support_title}<br></p><p><b>Priorytet</b>: {support_priority}<br></p><p><b>Data ko??cowa</b>: {support_end_date}</p><p><br></p><p><b>Wiadomo???? pomocy</b>:<br>{support_description}<br><br></p><p><b>Z powa??aniem</b>,<br>{app_name}</p>',
                    'ru' => '<p><b>????????????????????</b>&nbsp;{assign_user}<br><br></p><p>?????????????? ?????????? ???????????? ?? ???????????? ??????????????????.<br><br></p><p><b>????????????????</b>: {support_title}<br></p><p><b>??????????????????</b>: {support_priority}<br></p><p><b>???????? ??????????????????</b>: {support_end_date}</p><p><br></p><p><b>?????????????????? ??????????????????</b>:<br>{support_description}<br><br></p><p><b>?? ??????????????????</b>,<br>{app_name}</p>',
                ],
            ],
            'create_contract' => [
                'subject' => 'Create Contract',
                'lang' => [
                    'ar' => '<p><span style="text-align: right;">??????????</span>&nbsp;{contract_client}</p><p><span style="text-align: right;">???????? ???????????? ?????? {contract_subject}</span><span style="text-align: right;">&nbsp;????????????.</span></p><p><span style="text-align: right;"><br></span></p><p><span style="text-align: right;">??????</span>: {contract_description}<br></p><p><span style="text-align: right;">?????????? ??????.</span>.<br></p><p><span style="text-align: right;">???????? ????????????????</span>,<br>{app_name}</p>',
                    'da' => '<p><b>Hej</b>&nbsp;{contract_client}</p><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Translation" id="tw-target-text" dir="ltr" style="unicode-bidi: isolate; line-height: 36px; border: none; padding: 2px 0.14em 2px 0px; position: relative; margin-top: -2px; margin-bottom: -2px; resize: none; overflow: hidden; width: 277px; overflow-wrap: break-word;"><span lang="da">Se {contract_subject} vedh??ftet</span></pre><p><b>Beskrivelse</b>: {contract_description}</p><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Translation" id="tw-target-text" dir="ltr" style="unicode-bidi: isolate; line-height: 36px; border: none; padding: 2px 0.14em 2px 0px; position: relative; margin-top: -2px; margin-bottom: -2px; resize: none; overflow: hidden; width: 277px; overflow-wrap: break-word;"><span lang="da">Ser frem til at h??re fra dig.</span></pre><p><b>Med venlig hilsen</b>,<br>{app_name}</p>',
                    'de' => '<p><b>Hallo</b>&nbsp;{contract_client}</p><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Translation" id="tw-target-text" dir="ltr" style="unicode-bidi: isolate; line-height: 36px; border: none; padding: 2px 0.14em 2px 0px; position: relative; margin-top: -2px; margin-bottom: -2px; resize: none; overflow: hidden; width: 277px; overflow-wrap: break-word;"><span lang="de">Das angeh??ngte {contract_-subject} finden Sie im Anhang.</span></pre><p><b>Beschreibung</b>: {contract_description}</p><p>Freue mich von Dir zu h??ren..<br></p><p><b>Mit freundlichen Gr????en</b>,<br>{app_name}</p>',
                    'en' => '<p><span style="font-size: 12pt;"><b>Hi</b>&nbsp;{contract_client}</span></p><p><span style="font-size: 12pt;">Please find the {contract_subject}</span><span style="font-size: 12pt;">&nbsp;attached.</span></p><p><span style="font-size: 12pt;"><b>Description</b>: {contract_description}</span></p><p><span style="font-size: 12pt;">Looking forward to hear from you.</span><br><span style="font-size: 12pt;"><b>Kind Regards</b>,<br></span>{app_name}</p>',
                    'es' => '<p><b>Hola</b>&nbsp;{contract_client}</p><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Translation" id="tw-target-text" dir="ltr" style="unicode-bidi: isolate; line-height: 36px; border: none; padding: 2px 0.14em 2px 0px; position: relative; margin-top: -2px; margin-bottom: -2px; resize: none; overflow: hidden; width: 277px; overflow-wrap: break-word;"><span lang="es">Encuentre el {contract_subject} adjunto.</span></pre><p><b>Descripci??n</b>: {contract_descriotion}</p><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Translation" id="tw-target-text" dir="ltr" style="unicode-bidi: isolate; line-height: 36px; border: none; padding: 2px 0.14em 2px 0px; position: relative; margin-top: -2px; margin-bottom: -2px; resize: none; overflow: hidden; width: 277px; overflow-wrap: break-word;"><span lang="es">Esperando poder escuchar de ti.</span></pre><p><b>Saludos cordiales</b>,<br>{app_name}</p>',
                    'fr' => '<p><b>salut</b>&nbsp;{contract_client}</p><p>Veuillez trouver le {contract_subject} ci-joint.</p><p><b>La description</b>: {contract_description}<br></p><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Translation" id="tw-target-text" dir="ltr" style="unicode-bidi: isolate; line-height: 36px; border: none; padding: 2px 0.14em 2px 0px; position: relative; margin-top: -2px; margin-bottom: -2px; resize: none; overflow: hidden; width: 277px; overflow-wrap: break-word;"><span lang="fr">J\'ai h??te d\'avoir de vos nouvelles.</span></pre><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Translation" id="tw-target-text" dir="ltr" style="unicode-bidi: isolate; line-height: 36px; border: none; padding: 2px 0.14em 2px 0px; position: relative; margin-top: -2px; margin-bottom: -2px; resize: none; overflow: hidden; width: 277px; overflow-wrap: break-word;"><span lang="fr"><br></span></pre><p><b>Sinc??res amiti??s</b>,<br>{app_name}</p>',
                    'it' => '<p><b>Ciao</b>&nbsp;{contract_client}</p><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Translation" id="tw-target-text" dir="ltr" style="unicode-bidi: isolate; line-height: 36px; border: none; padding: 2px 0.14em 2px 0px; position: relative; margin-top: -2px; margin-bottom: -2px; resize: none; overflow: hidden; width: 277px; overflow-wrap: break-word;"><span lang="it">Si prega di trovare il {contract_sbject} allegato.</span></pre><p><b>Descrizione</b>: {contract_description}</p><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Translation" id="tw-target-text" dir="ltr" style="unicode-bidi: isolate; line-height: 36px; border: none; padding: 2px 0.14em 2px 0px; position: relative; margin-top: -2px; margin-bottom: -2px; resize: none; overflow: hidden; width: 277px; overflow-wrap: break-word;"><span lang="it">Non vedo l\'ora di sentire la tua opinione.</span></pre><p><b>Cordiali saluti</b>,<br>{app_name}</p>',
                    'ja' => '<p>??????????????? {contract_client}</p><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Translation" id="tw-target-text" dir="ltr" style="unicode-bidi: isolate; line-height: 32px; border: none; padding: 2px 0.14em 2px 0px; position: relative; margin-top: -2px; margin-bottom: -2px; resize: none; overflow: hidden; width: 277px; overflow-wrap: break-word;"><span lang="ja">?????????{contract_subject}??????????????????????????????</span></pre><p>??????: {contract_description}</p><p>?????????????????????????????????????????????.<br></p><p>?????????,<br>{app_name}</p>',
                    'nl' => '<p><b>Hoi</b>&nbsp;{contract_client}</p><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Translation" id="tw-target-text" dir="ltr" style="unicode-bidi: isolate; line-height: 36px; border: none; padding: 2px 0.14em 2px 0px; position: relative; margin-top: -2px; margin-bottom: -2px; resize: none; overflow: hidden; width: 277px; overflow-wrap: break-word;"><span lang="nl">Bijgevoegd vindt u het {contract_subject}.</span></pre><p><b>Omschrijving</b>: {contract_description}</p><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Translation" id="tw-target-text" dir="ltr" style="unicode-bidi: isolate; line-height: 36px; border: none; padding: 2px 0.14em 2px 0px; position: relative; margin-top: -2px; margin-bottom: -2px; resize: none; overflow: hidden; width: 277px; overflow-wrap: break-word;"><span lang="nl">Hoop van je te horen.</span></pre><p><b>Vriendelijke groeten</b>,<br>{app_name}</p>',
                    'pl' => '<p><b>cze????</b>&nbsp;{contract_client}</p><p>W za????czeniu {contract_subject}.</p><p><b>Opis</b>: {contract_description}<br></p><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Translation" id="tw-target-text" dir="ltr" style="unicode-bidi: isolate; line-height: 36px; border: none; padding: 2px 0.14em 2px 0px; position: relative; margin-top: -2px; margin-bottom: -2px; resize: none; overflow: hidden; width: 277px; overflow-wrap: break-word;"><span lang="pl">Czekam na wiadomo???? od ciebie.</span></pre><p><b>Z powa??aniem</b>,<br>{app_name}</p>',
                    'ru' => '<p><b>????????????????????</b>&nbsp;{contract_client}</p><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Translation" id="tw-target-text" dir="ltr" style="unicode-bidi: isolate; line-height: 36px; border: none; padding: 2px 0.14em 2px 0px; position: relative; margin-top: -2px; margin-bottom: -2px; resize: none; overflow: hidden; width: 277px; overflow-wrap: break-word;"><span lang="ru">????????????????????, ?????????????? ??????????????????????????  {contract_subject}.</span></pre><p><b>????????????????</b>: {contract_description}</p><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Translation" id="tw-target-text" dir="ltr" style="unicode-bidi: isolate; line-height: 36px; border: none; padding: 2px 0.14em 2px 0px; position: relative; margin-top: -2px; margin-bottom: -2px; resize: none; overflow: hidden; width: 277px; overflow-wrap: break-word;"><span lang="ru">?????????? ???????? ???????????????? ???? ??????.</span></pre><p><b>?? ??????????????????</b>,,<br>{app_name}</p>',
                ],
            ],
        ];

        $email = EmailTemplate::all();

        foreach($email as $e)
        {

            foreach($defaultTemplate[$e->slug]['lang'] as $lang => $content)
            {
                EmailTemplateLang::create(
                    [
                        'parent_id' => $e->id,
                        'lang' => $lang,
                        'subject' => $defaultTemplate[$e->slug]['subject'],
                        'content' => $content,
                    ]
                );
            }
        }
    }

}
