<?php

namespace App\Http\Controllers;

use App\Award;
use App\AwardType;
use App\Employee;
use App\Mail\AwardSend;
use App\User;
use Illuminate\Http\Request;

class AwardController extends Controller
{
    public function index()
    {
        $usr = \Auth::user();
        if($usr->type == 'company' || $usr->type == 'employee')
        {
            $employees  = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'employee')->get();
            $awardtypes = AwardType::where('created_by', '=', \Auth::user()->creatorId())->get();

            if(\Auth::user()->type == 'employee')
            {
                $emp    = User::where('id', '=', \Auth::user()->id)->first();
                $awards = Award::where('employee_id', '=', $emp->id)->get();
            }
            else
            {
                $awards = Award::where('created_by', '=', \Auth::user()->creatorId())->get();
            }

            return view('award.index', compact('awards', 'employees', 'awardtypes'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if(\Auth::user()->type == 'company')
        {
            $employees  = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'employee')->get()->pluck('name', 'id');
            $awardtypes = AwardType::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('award.create', compact('employees', 'awardtypes'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {

        if(\Auth::user()->type == 'company')
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'employee_id' => 'required',
                                   'award_type' => 'required',
                                   'date' => 'required',
                                   'gift' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $award              = new Award();
            $award->employee_id = $request->employee_id;
            $award->award_type  = $request->award_type;
            $award->date        = $request->date;
            $award->gift        = $request->gift;
            $award->description = $request->description;
            $award->created_by  = \Auth::user()->creatorId();
            $award->save();


            return redirect()->route('award.index')->with('success', __('Award  successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function edit(Award $award)
    {
        if(\Auth::user()->type == 'company')
        {
            $employees  = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'employee')->get()->pluck('name', 'id');
            $awardtypes = AwardType::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('award.edit', compact('award', 'awardtypes', 'employees'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Award $award)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'employee_id' => 'required',
                                   'award_type' => 'required',
                                   'date' => 'required',
                                   'gift' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $award->employee_id = $request->employee_id;
            $award->award_type  = $request->award_type;
            $award->date        = $request->date;
            $award->gift        = $request->gift;
            $award->description = $request->description;
            $award->save();

            return redirect()->route('award.index')->with('success', __('Award successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Award $award)
    {
        if(\Auth::user()->type == 'company')
        {
            if($award->created_by == \Auth::user()->creatorId())
            {
                $award->delete();

                return redirect()->route('award.index')->with('success', __('Award successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
