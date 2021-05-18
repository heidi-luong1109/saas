<?php

namespace App\Http\Controllers;

use App\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{

    public function index(Request $request)
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'employee')
        {
            $holidays = Holiday::where('created_by', '=', \Auth::user()->creatorId());

            if(!empty($request->start_date))
            {
                $holidays->where('date', '>=', $request->start_date);
            }
            if(!empty($request->end_date))
            {
                $holidays->where('date', '<=', $request->end_date);
            }
            $holidays = $holidays->get();

            return view('holiday.index', compact('holidays'));
        }

    }


    public function create()
    {
        if(\Auth::user()->type == 'company')
        {
            return view('holiday.create');
        }
    }


    public function store(Request $request)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required',
                                   'occasion' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $holiday             = new Holiday();
            $holiday->date       = $request->date;
            $holiday->occasion   = $request->occasion;
            $holiday->created_by = \Auth::user()->creatorId();
            $holiday->save();

            return redirect()->route('holiday.index')->with(
                'success', 'Holiday successfully created.'
            );
        }

    }


    public function show(Holiday $holiday)
    {

    }


    public function edit(Holiday $holiday)
    {
        if(\Auth::user()->type == 'company')
        {
            return view('holiday.edit', compact('holiday'));
        }
    }


    public function update(Request $request, Holiday $holiday)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required',
                                   'occasion' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $holiday->date     = $request->date;
            $holiday->occasion = $request->occasion;
            $holiday->save();

            return redirect()->route('holiday.index')->with(
                'success', 'Holiday successfully updated.'
            );
        }

    }

    public function destroy(Holiday $holiday)
    {
        if(\Auth::user()->type == 'company')
        {
            $holiday->delete();

            return redirect()->route('holiday.index')->with(
                'success', 'Holiday successfully deleted.'
            );
        }

    }
}
