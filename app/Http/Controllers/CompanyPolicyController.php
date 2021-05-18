<?php

namespace App\Http\Controllers;

use App\CompanyPolicy;
use Illuminate\Http\Request;

class CompanyPolicyController extends Controller
{
    public function index()
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'employee' || \Auth::user()->type == 'client')
        {
            $companyPolicy = CompanyPolicy::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('companyPolicy.index', compact('companyPolicy'));
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

            return view('companyPolicy.create');
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
                                   'title' => 'required',
                                   'attachment' => 'mimes:jpeg,png,jpg,gif,pdf,doc|max:20480',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            if(!empty($request->attachment))
            {
                $filenameWithExt = $request->file('attachment')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('attachment')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $dir             = storage_path('uploads/companyPolicy/');

                if(!file_exists($dir))
                {
                    mkdir($dir, 0777, true);
                }
                $path = $request->file('attachment')->storeAs('uploads/companyPolicy/', $fileNameToStore);
            }

            $policy              = new CompanyPolicy();
            $policy->title       = $request->title;
            $policy->description = $request->description;
            $policy->attachment  = !empty($request->attachment) ? $fileNameToStore : '';
            $policy->created_by  = \Auth::user()->creatorId();
            $policy->save();

            return redirect()->route('company-policy.index')->with('success', __('Company policy successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(CompanyPolicy $companyPolicy)
    {
        //
    }


    public function edit(CompanyPolicy $companyPolicy)
    {

        if(\Auth::user()->type == 'company')
        {
            return view('companyPolicy.edit', compact('companyPolicy'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    public function update(Request $request, CompanyPolicy $companyPolicy)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [

                                   'title' => 'required',
                                   'attachment' => 'mimes:jpeg,png,jpg,gif,pdf,doc|max:20480',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            if(isset($request->attachment))
            {
                $filenameWithExt = $request->file('attachment')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('attachment')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $dir             = storage_path('uploads/companyPolicy/');

                if(!file_exists($dir))
                {
                    mkdir($dir, 0777, true);
                }
                $path = $request->file('attachment')->storeAs('uploads/companyPolicy/', $fileNameToStore);
            }

            $companyPolicy->title       = $request->title;
            $companyPolicy->description = $request->description;
            if(isset($request->attachment))
            {
                $companyPolicy->attachment = $fileNameToStore;
            }
            $companyPolicy->created_by = \Auth::user()->creatorId();
            $companyPolicy->save();

            return redirect()->route('company-policy.index')->with('success', __('Company policy successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(CompanyPolicy $companyPolicy)
    {

        if(\Auth::user()->type == 'company')
        {
            $companyPolicy->delete();

            $dir = storage_path('uploads/companyPolicy/');
            if(!empty($companyPolicy->attachment))
            {
                unlink($dir . $companyPolicy->attachment);
            }

            return redirect()->route('company-policy.index')->with('success', __('Company policy successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
