<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Department;
use App\Employee;
use App\User;
use App\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{

    public function index(Request $request)
    {
        if(\Auth::user()->type == 'company')
        {
            $employees = User::where('type', 'employee')->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $employees->prepend('Select Employee', '');

            $attendances = Attendance::where('created_by', \Auth::user()->creatorId());
            if(!empty($request->date))
            {
                $attendances->where('date', $request->date);
            }
            if(!empty($request->employee))
            {
                $attendances->where('employee_id', $request->employee);
            }
            $attendances = $attendances->get();
        }
        elseif(\Auth::user()->type == 'employee')
        {
            $employees   = [];
            $attendances = Attendance::where('employee_id', \Auth::user()->id);
            if(!empty($request->date))
            {
                $attendances->where('date', $request->date);
            }
            $attendances = $attendances->get();
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        return view('attendance.index', compact('attendances', 'employees'));

    }


    public function create()
    {
        if(\Auth::user()->type == 'company')
        {

            $employees = User::where('type', 'employee')->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $employees->prepend('Select Employee', '');

            return view('attendance.create', compact('employees'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function store(Request $request)
    {

        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'employee_id' => 'required',
                                   'date' => 'required',
                                   'clock_in' => 'required',
                                   'clock_out' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $startTime  = Utility::getValByName('company_start_time');
            $endTime    = Utility::getValByName('company_end_time');
            $attendance = Attendance::where('employee_id', '=', $request->employee_id)->where('date', '=', $request->date)->where('clock_out', '=', '00:00:00')->get()->toArray();
            if($attendance)
            {
                return redirect()->route('attendance.index')->with('error', __('Employee attendance already created.'));
            }
            else
            {
                $date             = date("Y-m-d");
                $totalLateSeconds = strtotime($request->clock_in) - strtotime($date . $startTime);

                $hours = floor($totalLateSeconds / 3600);
                $mins  = floor($totalLateSeconds / 60 % 60);
                $secs  = floor($totalLateSeconds % 60);
                $late  = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                //early Leaving
                $totalEarlyLeavingSeconds = strtotime($date . $endTime) - strtotime($request->clock_out);
                $hours                    = floor($totalEarlyLeavingSeconds / 3600);
                $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
                $secs                     = floor($totalEarlyLeavingSeconds % 60);
                $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);


                if(strtotime($request->clock_out) > strtotime($date . $endTime))
                {
                    //Overtime
                    $totalOvertimeSeconds = strtotime($request->clock_out) - strtotime($date . $endTime);
                    $hours                = floor($totalOvertimeSeconds / 3600);
                    $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                    $secs                 = floor($totalOvertimeSeconds % 60);
                    $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                }
                else
                {
                    $overtime = '00:00:00';
                }
                $attendance                = new Attendance();
                $attendance->employee_id   = $request->employee_id;
                $attendance->date          = $request->date;
                $attendance->status        = 'Present';
                $attendance->clock_in      = $request->clock_in . ':00';
                $attendance->clock_out     = $request->clock_out . ':00';
                $attendance->late          = $late;
                $attendance->early_leaving = $earlyLeaving;
                $attendance->overtime      = $overtime;
                $attendance->total_rest    = '00:00:00';
                $attendance->created_by    = Auth::user()->creatorId();
                $attendance->save();

                return redirect()->route('attendance.index')->with('success', __('Employee attendance successfully created.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }


    }

    public function show(Attendance $attendance)
    {
        //
    }

    public function edit(Attendance $attendance)
    {
        if(\Auth::user()->type == 'company')
        {
            $employees = User::where('type', 'employee')->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $employees->prepend('Select Employee', '');

            return view('attendance.edit', compact('employees', 'attendance'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }


    }


    public function update(Request $request, Attendance $attendance)
    {
        $todayAttendance = Attendance::where('employee_id', '=', \Auth::user()->id)->where('date', date('Y-m-d'))->first();
        if(!empty($todayAttendance) && $todayAttendance->clock_out == '00:00:00')
        {
            if(\Auth::user()->type == 'company' || \Auth::user()->type == 'employee')
            {
                $startTime = Utility::getValByName('company_start_time');
                $endTime   = Utility::getValByName('company_end_time');

                if(Auth::user()->type == 'employee')
                {

                    $date = date("Y-m-d");
                    $time = date("H:i:s");

                    //early Leaving
                    $totalEarlyLeavingSeconds = strtotime($date . $endTime) - time();
                    $hours                    = floor($totalEarlyLeavingSeconds / 3600);
                    $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
                    $secs                     = floor($totalEarlyLeavingSeconds % 60);
                    $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                    if(time() > strtotime($date . $endTime))
                    {
                        //Overtime
                        $totalOvertimeSeconds = time() - strtotime($date . $endTime);
                        $hours                = floor($totalOvertimeSeconds / 3600);
                        $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                        $secs                 = floor($totalOvertimeSeconds % 60);
                        $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                    }
                    else
                    {
                        $overtime = '00:00:00';
                    }


                    $attendance->clock_out     = $time;
                    $attendance->early_leaving = $earlyLeaving;
                    $attendance->overtime      = $overtime;
                    $attendance->save();

                    return redirect()->back()->with('success', __('Employee successfully clock Out.'));
                }
                else
                {
                    $date = date("Y-m-d");
                    //late
                    $totalLateSeconds = strtotime($request->clock_in) - strtotime($date . $startTime);

                    $hours = floor($totalLateSeconds / 3600);
                    $mins  = floor($totalLateSeconds / 60 % 60);
                    $secs  = floor($totalLateSeconds % 60);
                    $late  = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                    //early Leaving
                    $totalEarlyLeavingSeconds = strtotime($date . $endTime) - strtotime($request->clock_out);
                    $hours                    = floor($totalEarlyLeavingSeconds / 3600);
                    $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
                    $secs                     = floor($totalEarlyLeavingSeconds % 60);
                    $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);


                    if(strtotime($request->clock_out) > strtotime($date . $endTime))
                    {
                        //Overtime
                        $totalOvertimeSeconds = strtotime($request->clock_out) - strtotime($date . $endTime);
                        $hours                = floor($totalOvertimeSeconds / 3600);
                        $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                        $secs                 = floor($totalOvertimeSeconds % 60);
                        $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                    }
                    else
                    {
                        $overtime = '00:00:00';
                    }

                    $attendance->employee_id   = $request->employee_id;
                    $attendance->date          = $request->date;
                    $attendance->clock_in      = $request->clock_in;
                    $attendance->clock_out     = $request->clock_out;
                    $attendance->late          = $late;
                    $attendance->early_leaving = $earlyLeaving;
                    $attendance->overtime      = $overtime;
                    $attendance->total_rest    = '00:00:00';

                    $attendance->save();

                    return redirect()->back()->with('success', __('Employee attendance successfully updated.'));
                }
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Employee are not allow Multiple time clock in & clock for every day.'));
        }

    }


    public function destroy(Attendance $attendance)
    {
        if(\Auth::user()->type == 'company')
        {
            $attendance->delete();

            return redirect()->route('attendance.index')->with('success', __('Attendance successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }


    }

    public function attendance(Request $request)
    {

        $startTime = Utility::getValByName('company_start_time');
        $endTime   = Utility::getValByName('company_end_time');

        $todayAttendance = Attendance::where('employee_id', '=', \Auth::user()->id)->where('date', date('Y-m-d'))->first();
        if(empty($todayAttendance))
        {
            $attendance = Attendance::orderBy('id', 'desc')->where('employee_id', '=', \Auth::user()->id)->where('clock_out', '=', '00:00:00')->first();

            if($attendance != null)
            {
                $attendance            = Attendance::find($attendance->id);
                $attendance->clock_out = $endTime;
                $attendance->save();
            }

            $date = date("Y-m-d");
            $time = date("H:i:s");

            //late
            $totalLateSeconds = time() - strtotime($date . $startTime);
            $hours            = floor($totalLateSeconds / 3600);
            $mins             = floor($totalLateSeconds / 60 % 60);
            $secs             = floor($totalLateSeconds % 60);
            $late             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

            $checkDb = Attendance::where('employee_id', '=', \Auth::user()->id)->get()->toArray();


            if(empty($checkDb))
            {
                $employeeAttendance                = new Attendance();
                $employeeAttendance->employee_id   = Auth::user()->id;
                $employeeAttendance->date          = $date;
                $employeeAttendance->status        = 'Present';
                $employeeAttendance->clock_in      = $time;
                $employeeAttendance->clock_out     = '00:00:00';
                $employeeAttendance->late          = $late;
                $employeeAttendance->early_leaving = '00:00:00';
                $employeeAttendance->overtime      = '00:00:00';
                $employeeAttendance->total_rest    = '00:00:00';
                $employeeAttendance->created_by    = \Auth::user()->creatorId();
                $employeeAttendance->save();

                return redirect()->back()->with('success', __('Employee Successfully Clock In.'));
            }
            foreach($checkDb as $check)
            {


                $employeeAttendance                = new Attendance();
                $employeeAttendance->employee_id   = \Auth::user()->id;
                $employeeAttendance->date          = $date;
                $employeeAttendance->status        = 'Present';
                $employeeAttendance->clock_in      = $time;
                $employeeAttendance->clock_out     = '00:00:00';
                $employeeAttendance->late          = $late;
                $employeeAttendance->early_leaving = '00:00:00';
                $employeeAttendance->overtime      = '00:00:00';
                $employeeAttendance->total_rest    = '00:00:00';
                $employeeAttendance->created_by    = \Auth::user()->creatorId();
                $employeeAttendance->save();

                //            dd($employeeAttendance);
                return redirect()->back()->with('success', __('Employee Successfully Clock In.'));

            }
        }
        else
        {
            return redirect()->back()->with('error', __('Employee are not allow Multiple time clock in & clock for every day.'));
        }
    }

    public function bulkAttendance(Request $request)
    {
        if(\Auth::user()->type == 'company')
        {

            $department = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $department->prepend('Select Department', '');

            $employees = [];
            if(!empty($request->department))
            {
                $employees = Employee::where('created_by', \Auth::user()->creatorId())->where('department', $request->department)->get();

            }

            return view('attendance.bulk', compact('employees', 'department'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function bulkAttendanceData(Request $request)
    {

        if(\Auth::user()->type == 'company')
        {
            if(!empty($request->department))
            {
                $startTime = Utility::getValByName('company_start_time');
                $endTime   = Utility::getValByName('company_end_time');
                $date      = $request->date;

                $employees = $request->employee_id;
                $atte      = [];
                foreach($employees as $employee)
                {

                    $present = 'present-' . $employee;
                    $in      = 'in-' . $employee;
                    $out     = 'out-' . $employee;
                    $atte[]  = $present;
                    if($request->$present == 'on')
                    {

                        $in  = date("H:i:s", strtotime($request->$in));
                        $out = date("H:i:s", strtotime($request->$out));

                        $totalLateSeconds = strtotime($in) - strtotime($startTime);

                        $hours = floor($totalLateSeconds / 3600);
                        $mins  = floor($totalLateSeconds / 60 % 60);
                        $secs  = floor($totalLateSeconds % 60);
                        $late  = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                        //early Leaving
                        $totalEarlyLeavingSeconds = strtotime($endTime) - strtotime($out);
                        $hours                    = floor($totalEarlyLeavingSeconds / 3600);
                        $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
                        $secs                     = floor($totalEarlyLeavingSeconds % 60);
                        $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);


                        if(strtotime($out) > strtotime($endTime))
                        {
                            //Overtime
                            $totalOvertimeSeconds = strtotime($out) - strtotime($endTime);
                            $hours                = floor($totalOvertimeSeconds / 3600);
                            $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                            $secs                 = floor($totalOvertimeSeconds % 60);
                            $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                        }
                        else
                        {
                            $overtime = '00:00:00';
                        }


                        $attendance = Attendance::where('employee_id', '=', $employee)->where('date', '=', $request->date)->first();

                        if(!empty($attendance))
                        {
                            $employeeAttendance = $attendance;
                        }
                        else
                        {
                            $employeeAttendance              = new Attendance();
                            $employeeAttendance->employee_id = $employee;
                            $employeeAttendance->created_by  = \Auth::user()->creatorId();
                        }


                        $employeeAttendance->date          = $request->date;
                        $employeeAttendance->status        = 'Present';
                        $employeeAttendance->clock_in      = $in;
                        $employeeAttendance->clock_out     = $out;
                        $employeeAttendance->late          = $late;
                        $employeeAttendance->early_leaving = ($earlyLeaving > 0) ? $earlyLeaving : '00:00:00';
                        $employeeAttendance->overtime      = $overtime;
                        $employeeAttendance->total_rest    = '00:00:00';
                        $employeeAttendance->save();

                    }
                    else
                    {
                        $attendance = Attendance::where('employee_id', '=', $employee)->where('date', '=', $request->date)->first();

                        if(!empty($attendance))
                        {
                            $employeeAttendance = $attendance;
                        }
                        else
                        {
                            $employeeAttendance              = new Attendance();
                            $employeeAttendance->employee_id = $employee;
                            $employeeAttendance->created_by  = \Auth::user()->creatorId();
                        }

                        $employeeAttendance->status        = 'Leave';
                        $employeeAttendance->date          = $request->date;
                        $employeeAttendance->clock_in      = '00:00:00';
                        $employeeAttendance->clock_out     = '00:00:00';
                        $employeeAttendance->late          = '00:00:00';
                        $employeeAttendance->early_leaving = '00:00:00';
                        $employeeAttendance->overtime      = '00:00:00';
                        $employeeAttendance->total_rest    = '00:00:00';
                        $employeeAttendance->save();
                    }
                }

                return redirect()->back()->with('success', __('Employee attendance successfully created.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Department field required.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
