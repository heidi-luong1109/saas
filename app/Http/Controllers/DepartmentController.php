<?php

namespace App\Http\Controllers;

use App\Department;
use App\Employee;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        if(\Auth::user()->type == 'company')
        {
            $departments = Department::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('department.index', compact('departments'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function create()
    {
        return view('department.create');
    }

    public function store(Request $request)
    {
        if(\Auth::user()->type == 'company')
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

            $department             = new Department();
            $department->name       = $request->name;
            $department->created_by = \Auth::user()->creatorId();
            $department->save();

            return redirect()->route('department.index')->with(
                'success', 'Department successfully created.'
            );
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function show(Department $department)
    {

    }


    public function edit(Department $department)
    {
        return view('department.edit', compact('department'));
    }


    public function update(Request $request, Department $department)
    {
        if(\Auth::user()->type == 'company')
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

            $department->name = $request->name;
            $department->save();

            return redirect()->route('department.index')->with(
                'success', 'Department successfully updated.'
            );
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }


    }


    public function destroy(Department $department)
    {
        if(\Auth::user()->type == 'company')
        {

            $data = Employee::where('department', $department->id)->first();
            if(!empty($data))
            {
                return redirect()->back()->with('error', __('this department is already use so please transfer or delete this department related data.'));
            }

            $department->delete();

            return redirect()->route('department.index')->with(
                'success', 'Department successfully deleted.'
            );

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }


    }
}
