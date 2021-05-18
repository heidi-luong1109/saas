<?php

namespace App\Http\Controllers;

use App\Appraisal;
use App\User;
use Illuminate\Http\Request;

class AppraisalController extends Controller
{
    public function index()
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'employee')
        {
            $user = \Auth::user();
            if($user->type == 'employee')
            {
                $appraisals = Appraisal::where('created_by', '=', \Auth::user()->creatorId())->where('employee', $user->id)->get();
            }
            else
            {
                $appraisals = Appraisal::where('created_by', '=', \Auth::user()->creatorId())->get();
            }

            return view('appraisal.index', compact('appraisals'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        $technical      = Appraisal::$technical;
        $organizational = Appraisal::$organizational;
        $employees      = User::where('created_by', \Auth::user()->creatorId())->where('type', '=', 'employee')->get()->pluck('name', 'id');
        $employees->prepend('Select Employee', '');

        return view('appraisal.create', compact('technical', 'organizational', 'employees'));
    }


    public function store(Request $request)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [

                                   'employee' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $appraisal                      = new Appraisal();
            $appraisal->employee            = $request->employee;
            $appraisal->appraisal_date      = $request->appraisal_date;
            $appraisal->customer_experience = $request->customer_experience;
            $appraisal->marketing           = $request->marketing;
            $appraisal->administration      = $request->administration;
            $appraisal->professionalism     = $request->professionalism;
            $appraisal->integrity           = $request->integrity;
            $appraisal->attendance          = $request->attendance;
            $appraisal->remark              = $request->remark;
            $appraisal->created_by          = \Auth::user()->creatorId();
            $appraisal->save();

            return redirect()->route('appraisal.index')->with('success', __('Appraisal successfully created.'));
        }
    }

    public function show(Appraisal $appraisal)
    {
        return view('appraisal.show', compact('appraisal'));
    }


    public function edit(Appraisal $appraisal)
    {
        $technical      = Appraisal::$technical;
        $organizational = Appraisal::$organizational;
        $employees      = User::where('created_by', \Auth::user()->creatorId())->where('type', '=', 'employee')->get()->pluck('name', 'id');
        $employees->prepend('Select Employee', '');

        return view('appraisal.edit', compact('technical', 'organizational', 'appraisal', 'employees'));
    }


    public function update(Request $request, Appraisal $appraisal)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [

                                   'employee' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $appraisal->employee            = $request->employee;
            $appraisal->appraisal_date      = $request->appraisal_date;
            $appraisal->customer_experience = $request->customer_experience;
            $appraisal->marketing           = $request->marketing;
            $appraisal->administration      = $request->administration;
            $appraisal->professionalism     = $request->professionalism;
            $appraisal->integrity           = $request->integrity;
            $appraisal->attendance          = $request->attendance;
            $appraisal->remark              = $request->remark;
            $appraisal->save();

            return redirect()->route('appraisal.index')->with('success', __('Appraisal successfully updated.'));
        }
    }


    public function destroy(Appraisal $appraisal)
    {
        if(\Auth::user()->type == 'company')
        {
            $appraisal->delete();

            return redirect()->route('appraisal.index')->with('success', __('Appraisal successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
