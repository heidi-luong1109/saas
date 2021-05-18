<?php

namespace App\Http\Controllers;

use App\EmailTemplate;
use App\EmailTemplateLang;
use App\UserEmailTemplate;
use App\Utility;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{

    public function index()
    {
        $usr = \Auth::user();

        if($usr->type == 'super admin' || $usr->type == 'company')
        {
            $EmailTemplates = EmailTemplate::all();

            return view('email_templates.index', compact('EmailTemplates'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        if(\Auth::user()->type == 'super admin')
        {
            return view('email_templates.create');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function store(Request $request)
    {
        $usr = \Auth::user();

        if(\Auth::user()->type == 'super admin')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $EmailTemplate             = new EmailTemplate();
            $EmailTemplate->name       = $request->name;
            $EmailTemplate->slug       = strtolower(str_replace(' ', '_', $request->name));
            $EmailTemplate->from       = env('APP_NAME');
            $EmailTemplate->created_by = $usr->id;
            $EmailTemplate->save();

            return redirect()->route('email_template.index')->with('success', __('Email Template successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(EmailTemplate $emailTemplate)
    {
        //
    }


    public function edit(EmailTemplate $emailTemplate)
    {
        //
    }


    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        if(\Auth::user()->type == 'super admin')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'from' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $emailTemplate       = EmailTemplate::find($emailTemplate->id);
            $emailTemplate->from = $request->from;
            $emailTemplate->save();

            return redirect()->route(
                'manage.email.language', [
                                           $emailTemplate->id,
                                           $request->lang,
                                       ]
            )->with('success', __('Email Template successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(EmailTemplate $emailTemplate)
    {
        //
    }

    // Used For View Email Template Language Wise
    public function manageEmailLang($id, $lang = 'en')
    {
        if(\Auth::user()->type == 'super admin')
        {
            $languages         = Utility::languages();
            $emailTemplate     = EmailTemplate::where('id', '=', $id)->first();
            $currEmailTempLang = EmailTemplateLang::where('parent_id', '=', $id)->where('lang', $lang)->first();

            if(!isset($currEmailTempLang) || empty($currEmailTempLang))
            {
                $currEmailTempLang       = EmailTemplateLang::where('parent_id', '=', $id)->where('lang', 'en')->first();
                $currEmailTempLang->lang = $lang;
            }

            return view('email_templates.show', compact('emailTemplate', 'languages', 'currEmailTempLang'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    // Used For Store Email Template Language Wise
    public function storeEmailLang(Request $request, $id)
    {
//        dd($request);
        if(\Auth::user()->type == 'super admin')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'subject' => 'required',
                                   'content' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $emailLangTemplate = EmailTemplateLang::where('parent_id', '=', $id)->where('lang', '=', $request->lang)->first();

            // if record not found then create new record else update it.
            if(empty($emailLangTemplate))
            {
                $emailLangTemplate            = new EmailTemplateLang();
                $emailLangTemplate->parent_id = $id;
                $emailLangTemplate->lang      = $request['lang'];
                $emailLangTemplate->subject   = $request['subject'];
                $emailLangTemplate->content   = $request['content'];
                $emailLangTemplate->save();
            }
            else
            {
                $emailLangTemplate->subject = $request['subject'];
                $emailLangTemplate->content = $request['content'];
                $emailLangTemplate->save();
            }

            return redirect()->route(
                'manage.email.language', [
                                           $id,
                                           $request->lang,
                                       ]
            )->with('success', __('Email Template Detail successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    // Used For Update Status Company Wise.
    public function updateStatus(Request $request, $id)
    {
        $usr = \Auth::user();

        if($usr->type == 'super admin' || $usr->type == 'company')
        {
            $user_email = UserEmailTemplate::where('id', '=', $id)->where('user_id', '=', $usr->id)->first();
            if(!empty($user_email))
            {
                if($request->status == 1)
                {
                    $user_email->is_active = 0;
                }
                else
                {
                    $user_email->is_active = 1;
                }

                $user_email->save();

                return response()->json(
                    [
                        'is_success' => true,
                        'success' => __('Status successfully updated!'),
                    ], 200
                );
            }
            else
            {
                return response()->json(
                    [
                        'is_success' => false,
                        'error' => __('Permission Denied.'),
                    ], 401
                );
            }
        }
    }
}
