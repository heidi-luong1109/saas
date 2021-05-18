<?php

namespace App\Http\Controllers;

use App\Support;
use App\SupportReply;
use App\User;
use App\Utility;
use Illuminate\Http\Request;

class SupportController extends Controller
{

    public function index()
    {
        if(\Auth::user()->type == 'company')
        {
            $supports = Support::where('created_by', \Auth::user()->creatorId())->get();;

            return view('support.index', compact('supports'));
        }
        elseif(\Auth::user()->type == 'client')
        {
            $supports = Support::where('user', \Auth::user()->id)->get();;

            return view('support.index', compact('supports'));
        }
        elseif(\Auth::user()->type == 'employee')
        {

            $supports = Support::where('user', \Auth::user()->id)->get();;

            return view('support.index', compact('supports'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        $priority = Support::$priority;

        $users = User::whereIn(
            'type', [
                      'employee',
                      'client',
                  ]
        )->get()->pluck('name', 'id');

        return view('support.create', compact('priority', 'users'));
    }


    public function store(Request $request)
    {

        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'subject' => 'required',
                                   'priority' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $support              = new Support();
            $support->subject     = $request->subject;
            $support->user        = $request->user;
            $support->priority    = $request->priority;
            $support->end_date    = $request->end_date;
            $support->ticket_code = date('hms');
            $support->status      = 'Open';

            if(!empty($request->attachment))
            {
                $fileName = time() . "_" . $request->attachment->getClientOriginalName();
                $request->attachment->storeAs('uploads/supports', $fileName);
                $support->attachment = $fileName;
            }
            $support->description = $request->description;
            $support->created_by  = \Auth::user()->creatorId();
            $support->save();

            $client        = User::find($request->user);
            $supportArr = [
                'support_title' => $request->subject,
                'assign_user' => $client->name,
                'support_end_date' => \Auth::user()->dateFormat($request->end_date),
                'support_description' => $request->description,
                'support_priority' => Support::$priority[$request->priority],
            ];

            // Send Email
            $resp = Utility::sendEmailTemplate('create_support', [$client->id => $client->email], $supportArr);


            return redirect()->route('support.index')->with('success', __('Support successfully created.') . (($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(Support $support)
    {
        //
    }


    public function edit(Support $support)
    {
        $priority = Support::$priority;

        $users = User::whereIn(
            'type', [
                      'employee',
                      'client',
                  ]
        )->get()->pluck('name', 'id');

        return view('support.edit', compact('priority', 'users', 'support'));
    }


    public function update(Request $request, Support $support)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'subject' => 'required',
                                   'priority' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $support->subject  = $request->subject;
            $support->user     = $request->user;
            $support->priority = $request->priority;
            $support->end_date = $request->end_date;
            if(!empty($request->attachment))
            {
                $fileName = time() . "_" . $request->attachment->getClientOriginalName();
                $request->attachment->storeAs('uploads/supports', $fileName);
                $support->attachment = $fileName;
            }
            $support->description = $request->description;

            $support->save();

            return redirect()->route('support.index')->with('success', __('Support successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(Support $support)
    {
        if(\Auth::user()->type == 'company')
        {
            $support->delete();
            if($support->attachment)
            {
                \File::delete(storage_path('uploads/supports/' . $support->attachment));
            }

            return redirect()->route('support.index')->with('success', __('Support successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function reply($ids)
    {
        $id      = \Crypt::decrypt($ids);
        $replyes = SupportReply::where('support_id', $id)->get();
        $support = Support::find($id);

        foreach($replyes as $reply)
        {
            $supportReply          = SupportReply::find($reply->id);
            $supportReply->is_read = 1;
            $supportReply->save();
        }

        return view('support.reply', compact('support', 'replyes'));
    }

    public function replyAnswer(Request $request, $id)
    {
        $supportReply              = new SupportReply();
        $supportReply->support_id  = $id;
        $supportReply->user        = \Auth::user()->id;
        $supportReply->description = $request->description;
        $supportReply->created_by  = \Auth::user()->creatorId();
        $supportReply->save();

        return redirect()->back()->with('success', __('Support reply successfully send.'));
    }

}
