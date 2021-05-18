<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Plan;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if(!file_exists(storage_path() . "/installed"))
        {
            header('location:install');
            die;
        }
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {

        if($user->is_active == 0)
        {
            auth()->logout();
        }

        if($user->type == 'company')
        {
            $free_plan = Plan::where('price', '=', '0.0')->first();
            $plan      = Plan::find($user->plan);

            if($user->plan != $free_plan->id)
            {
                if(date('Y-m-d') > $user->plan_expire_date && $plan->duration != 'unlimited')
                {
                    $user->plan             = $free_plan->id;
                    $user->plan_expire_date = null;
                    $user->save();

                    $clients   = User::where('type', 'client')->where('created_by', '=', \Auth::user()->creatorId())->get();
                    $employees = User::where('type', 'employee')->where('created_by', '=', \Auth::user()->creatorId())->get();

                    if($free_plan->max_client == -1)
                    {
                        foreach($clients as $client)
                        {
                            $client->is_active = 1;
                            $client->save();
                        }
                    }
                    else
                    {
                        $clientCount = 0;
                        foreach($clients as $client)
                        {
                            $clientCount++;
                            if($clientCount <= $free_plan->max_client)
                            {
                                $client->is_active = 1;
                                $client->save();
                            }
                            else
                            {
                                $client->is_active = 0;
                                $client->save();
                            }
                        }

                    }


                    if($free_plan->max_employee == -1)
                    {
                        foreach($employees as $employee)
                        {
                            $employee->is_active = 1;
                            $employee->save();
                        }
                    }
                    else
                    {
                        $employeeCount = 0;
                        foreach($employees as $employee)
                        {
                            $employeeCount++;
                            if($employeeCount <= $free_plan->max_employee)
                            {
                                $employee->is_active = 1;
                                $employee->save();
                            }
                            else
                            {
                                $employee->is_active = 0;
                                $employee->save();
                            }
                        }
                    }

                    return redirect()->route('dashboard')->with('error', 'Your plan expired limit is over, please upgrade your plan');
                }
            }

        }

    }

    public function showLoginForm($lang = '')
    {
        if($lang == '')
        {
            $lang = \App\Utility::getValByName('default_language');
        }
        \App::setLocale($lang);

        return view('auth.login', compact('lang'));
    }

    public function showLinkRequestForm($lang = '')
    {

        if($lang == '')
        {
            $lang = \App\Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.passwords.email', compact('lang'));
    }


}
