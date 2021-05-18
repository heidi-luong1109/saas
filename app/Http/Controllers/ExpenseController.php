<?php

namespace App\Http\Controllers;

use App\Expense;
use App\Project;
use App\User;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{

    public function index()
    {
        if(\Auth::user()->type == 'company')
        {
            $expenses = Expense::where('created_by', \Auth::user()->creatorId())->get();

            return view('expense.index', compact('expenses'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        $users = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'employee')->get()->pluck('name', 'id');
        $users->prepend('--', 0);

        $projects = Project::where('created_by', \Auth::user()->creatorId())->get()->pluck('title', 'id');
        $projects->prepend('--', 0);

        return view('expense.create', compact('users', 'projects'));
    }


    public function store(Request $request)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required',
                                   'amount' => 'required',
                                   'attachment' => 'mimes:jpeg,jpg,png,gif,pdf,doc|max:10000',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $expense          = new Expense();
            $expense->date    = $request->date;
            $expense->amount  = $request->amount;
            $expense->user    = $request->user;
            $expense->project = $request->project;

            if($request->attachment)
            {
                $imageName = 'expense_' . time() . "_" . $request->attachment->getClientOriginalName();
                $request->attachment->storeAs('uploads/attachment', $imageName);
                $expense->attachment = $imageName;
            }
            $expense->description = $request->description;
            $expense->created_by  = \Auth::user()->creatorId();
            $expense->save();

            return redirect()->route('expense.index')->with('success', __('Expense successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(Expense $expense)
    {
        //
    }


    public function edit(Expense $expense)
    {
        $users = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'employee')->get()->pluck('name', 'id');
        $users->prepend('--', 0);

        $projects = Project::where('created_by', \Auth::user()->creatorId())->get()->pluck('title', 'id');
        $projects->prepend('--', 0);

        return view('expense.edit', compact('users', 'projects', 'expense'));
    }


    public function update(Request $request, Expense $expense)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required',
                                   'amount' => 'required',
                                   'attachment' => 'mimes:jpeg,jpg,png,gif,pdf,doc|max:10000',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $expense->date    = $request->date;
            $expense->amount  = $request->amount;
            $expense->user    = $request->user;
            $expense->project = $request->project;

            if($request->attachment)
            {
                if($expense->attachment)
                {
                    \File::delete(storage_path('uploads/attachment/' . $expense->attachment));
                }
                $imageName = 'expense_' . time() . "_" . $request->attachment->getClientOriginalName();
                $request->attachment->storeAs('uploads/attachment', $imageName);
                $expense->attachment = $imageName;
            }
            $expense->description = $request->description;
            $expense->save();

            return redirect()->route('expense.index')->with('success', __('Expense successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function destroy(Expense $expense)
    {
        if(\Auth::user()->type == 'company')
        {
            if($expense->attachment)
            {
                \File::delete(storage_path('uploads/attachment/' . $expense->attachment));
            }
            $expense->delete();

            return redirect()->route('expense.index')->with('success', __('Expense successfully deleted.'));

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
