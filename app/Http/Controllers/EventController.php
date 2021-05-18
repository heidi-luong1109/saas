<?php

namespace App\Http\Controllers;

use App\Department;
use App\Employee;
use App\event;
use App\User;
use Illuminate\Http\Request;

class EventController extends Controller
{

    public function index()
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'employee')
        {
            $events    = Event::where('created_by',\Auth::user()->creatorId())->get();
            $arrEvents = [];

            foreach($events as $event)
            {

                $arr['id']        = $event['id'];
                $arr['title']     = $event['name'];
                $arr['start']     = $event['start_date'];
                $arr['end']       = $event['end_date'];
                $arr['className'] = $event['color'];
                $arr['url']       = route('event.show', $event['id']);

                $arrEvents[] = $arr;
            }
            $arrEvents = str_replace('"[', '[', str_replace(']"', ']', json_encode($arrEvents)));

            return view('event.index', compact('arrEvents'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        $departments = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $departments->prepend('All', 0);

        return view('event.create', compact('departments'));
    }


    public function store(Request $request)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'department' => 'required',
                                   'employee' => 'required',
                                   'start_date' => 'required',
                                   'start_time' => 'required',
                                   'end_date' => 'required',
                                   'end_time' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $event              = new event();
            $event->name        = $request->name;
            $event->where       = $request->where;
            $event->department  = implode(',', $request->department);
            $event->employee    = implode(',', $request->employee);
            $event->start_date  = $request->start_date;
            $event->start_time  = $request->start_time;
            $event->end_date    = $request->end_date;
            $event->end_time    = $request->end_time;
            $event->color       = $request->color;
            $event->description = $request->description;
            $event->created_by  = \Auth::user()->creatorId();

            $event->save();

            return redirect()->route('event.index')->with('success', __('Event successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function show(event $event)
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'employee')
        {
            $departments = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $departments->prepend('All', 0);
            $event->department = explode(',', $event->department);
            $event->employee   = explode(',', $event->employee);

            $dep = [];
            foreach($event->department as $department)
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

            $emp = [];
            foreach($event->employee as $employee)
            {
                if($employee == 0)
                {
                    $emp[] = 'All Employee';
                }
                else
                {
                    $employees = User::find($employee);
                    $emp[]     = $employees->name;
                }
            }


            return view('event.show', compact('event', 'dep', 'emp'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }


    }


    public function edit(event $event)
    {
        $departments = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $departments->prepend('All', 0);

        return view('event.edit', compact('departments', 'event'));
    }


    public function update(Request $request, event $event)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'start_date' => 'required',
                                   'start_time' => 'required',
                                   'end_date' => 'required',
                                   'end_time' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            $event->name        = $request->name;
            $event->where       = $request->where;
            $event->start_date  = $request->start_date;
            $event->start_time  = $request->start_time;
            $event->end_date    = $request->end_date;
            $event->end_time    = $request->end_time;
            $event->color       = $request->color;
            $event->description = $request->description;

            $event->save();

            return redirect()->route('event.index')->with('success', __('Event successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function destroy(event $event)
    {
        //
    }

    public function getEmployee(Request $request)
    {

        if(in_array('0', $request->department))
        {
            $employees = Employee::get();

        }
        else
        {
            $employees = Employee::whereIn('department', $request->department)->get();

        }
        $users = [];
        foreach($employees as $employee)
        {
            if(!empty($employee->users))
            {
                $users[$employee->users->id] = $employee->users->name;
            }

        }

        return response()->json($users);
    }
}
