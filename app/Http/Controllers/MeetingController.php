<?php

namespace App\Http\Controllers;

use App\Department;
use App\Designation;
use App\Meeting;
use Illuminate\Http\Request;

class MeetingController extends Controller
{

    public function index(Request $request)
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'employee')
        {
            $departments = Department::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $departments->prepend('All', 0);
            $designations = Designation::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $designations->prepend('All', 0);

            $meetings = Meeting::where('created_by', '=', \Auth::user()->creatorId());

            if(!empty($request->department))
            {
                $meetings->where('department', $request->department);
            }
            if(!empty($request->designation))
            {
                $meetings->where('designation', $request->designation);
            }
            if(!empty($request->start_date))
            {
                $meetings->where('date', '>=', $request->start_date);
            }
            if(!empty($request->end_date))
            {
                $meetings->where('date', '<=', $request->end_date);
            }


            $meetings = $meetings->get();

            $arrMeeting = [];
            foreach($meetings as $meeting)
            {

                $arr['id']        = $meeting['id'];
                $arr['title']     = $meeting['title'];
                $arr['start']     = $meeting['date'];
                $arr['className'] = 'bg-red';
                $arr['url']       = route('meeting.show', $meeting['id']);

                $arrMeeting[] = $arr;
            }
            $arrMeeting = str_replace('"[', '[', str_replace(']"', ']', json_encode($arrMeeting)));

            return view('meeting.index', compact('meetings', 'departments', 'designations', 'arrMeeting'));
        }

    }


    public function create()
    {
        $departments = Department::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $departments->prepend('All', 0);
        $designations = Designation::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $designations->prepend('All', 0);

        return view('meeting.create', compact('departments', 'designations'));
    }


    public function store(Request $request)
    {

        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [

                                   'title' => 'required',
                                   'date' => 'required',
                                   'time' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $meeting              = new Meeting();
            $meeting->department  = $request->department;
            $meeting->designation = $request->designation;
            $meeting->title       = $request->title;
            $meeting->date        = $request->date;
            $meeting->time        = $request->time;
            $meeting->notes       = $request->notes;
            $meeting->created_by  = \Auth::user()->creatorId();
            $meeting->save();

            return redirect()->route('meeting.index')->with('success', __('Meeting  successfully created.'));
        }
    }


    public function show(Meeting $meeting)
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'employee')
        {
            $departments = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $departments->prepend('All', 0);
            $meeting->department = explode(',', $meeting->department);
            $meeting->designation   = explode(',', $meeting->designation);

            $dep = [];
            foreach($meeting->department as $department)
            {

                if($department == 0)
                {
                    $dep[] = 'All Department';
                }
                else
                {
                    $departments = Department::find($department);
                    $dep[]       = $departments->name;
                }
            }
            $des = [];
            foreach($meeting->designation as $designation)
            {

                if($designation == 0)
                {
                    $des[] = 'All Designation';
                }
                else
                {
                    $designations = Designation::find($designation);
                    $des[]        = $designations->name;
                }
            }

            return view('meeting.show', compact('meeting', 'dep', 'des'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function edit(Meeting $meeting)
    {
        $departments = Department::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $departments->prepend('All', 0);
        $designations = Designation::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $designations->prepend('All', 0);

        return view('meeting.edit', compact('departments', 'designations', 'meeting'));
    }


    public function update(Request $request, Meeting $meeting)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [

                                   'title' => 'required',
                                   'date' => 'required',
                                   'time' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            $meeting->department  = $request->department;
            $meeting->designation = $request->designation;
            $meeting->title       = $request->title;
            $meeting->date        = $request->date;
            $meeting->time        = $request->time;
            $meeting->notes       = $request->notes;
            $meeting->save();

            return redirect()->route('meeting.index')->with('success', __('Meeting  successfully updated.'));
        }

    }


    public function destroy(Meeting $meeting)
    {
        if(\Auth::user()->type == 'company')
        {
            $meeting->delete();

            return redirect()->route('meeting.index')->with('success', __('Meeting successfully deleted.'));
        }

    }
}
