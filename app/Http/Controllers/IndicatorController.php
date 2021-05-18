<?php

namespace App\Http\Controllers;

use App\Department;
use App\Employee;
use App\Indicator;
use App\User;
use Illuminate\Http\Request;

class IndicatorController extends Controller
{
    public function index()
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'employee')
        {
            $user = \Auth::user();
            if($user->type == 'employee')
            {
                $employee = Employee::where('user_id', $user->id)->first();
                $indicators = Indicator::where('created_by', '=', $user->creatorId())->where('department', $employee->department)->where('designation', $employee->designation)->get();
            }
            else
            {
                $indicators = Indicator::where('created_by', '=', $user->creatorId())->get();
            }

            return view('indicator.index', compact('indicators'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        $technical      = Indicator::$technical;
        $organizational = Indicator::$organizational;
        $departments    = Department::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $departments->prepend('Select Department', '');

        return view('indicator.create', compact('technical', 'organizational', 'departments'));
    }


    public function store(Request $request)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [

                                   'department' => 'required',
                                   'designation' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            $indicator                      = new Indicator();
            $indicator->department          = $request->department;
            $indicator->designation         = $request->designation;
            $indicator->customer_experience = $request->customer_experience;
            $indicator->marketing           = $request->marketing;
            $indicator->administration      = $request->administration;
            $indicator->professionalism     = $request->professionalism;
            $indicator->integrity           = $request->integrity;
            $indicator->attendance          = $request->attendance;
            if(\Auth::user()->type == 'company')
            {
                $indicator->created_user = \Auth::user()->creatorId();
            }
            else
            {
                $indicator->created_user = \Auth::user()->id;
            }

            $indicator->created_by = \Auth::user()->creatorId();
            $indicator->save();

            return redirect()->route('indicator.index')->with('success', __('Indicator successfully created.'));
        }
    }

    public function show(Indicator $indicator)
    {
        return view('indicator.show', compact('indicator'));
    }


    public function edit(Indicator $indicator)
    {
        $technical      = Indicator::$technical;
        $organizational = Indicator::$organizational;
        $departments    = Department::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $departments->prepend('Select Department', '');

        return view('indicator.edit', compact('technical', 'organizational', 'departments', 'indicator'));
    }


    public function update(Request $request, Indicator $indicator)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'department' => 'required',
                                   'designation' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            $indicator->department          = $request->department;
            $indicator->designation         = $request->designation;
            $indicator->customer_experience = $request->customer_experience;
            $indicator->marketing           = $request->marketing;
            $indicator->administration      = $request->administration;
            $indicator->professionalism     = $request->professionalism;
            $indicator->integrity           = $request->integrity;
            $indicator->attendance          = $request->attendance;
            $indicator->save();

            return redirect()->route('indicator.index')->with('success', __('Indicator successfully updated.'));
        }
    }


    public function destroy(Indicator $indicator)
    {
        if(\Auth::user()->type == 'company')
        {
            $indicator->delete();

            return redirect()->route('indicator.index')->with('success', __('Indicator successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
