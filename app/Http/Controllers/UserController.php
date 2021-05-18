<?php

namespace App\Http\Controllers;

use App\Client;
use App\CustomField;
use App\Order;
use App\Plan;
use App\User;
use App\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        $user = \Auth::user();
        if(\Auth::user()->type == 'super admin')
        {
            if(\Auth::user()->type == 'super admin')
            {
                $users = User::where('created_by', '=', $user->creatorId())->where('type', '=', 'company')->get();
            }

            return view('user.index', compact('users'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }


    }


    public function create()
    {
        return view('user.create');
    }


    public function store(Request $request)
    {
        $default_language = Utility::getValByName('default_language');

        if(\Auth::user()->type == 'super admin')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'email' => 'required|email|unique:users',
                                   'password' => 'required|min:6',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $user               = new User();
            $user['name']       = $request->name;
            $user['email']      = $request->email;
            $user['password']   = Hash::make($request->password);
            $user['type']       = 'company';
            $user['lang']       = !empty($default_language) ? $default_language : 'en';
            $user['created_by'] = \Auth::user()->creatorId();
            $user['plan']       = Plan::first()->id;
            $user['avatar']     = 'avatar.png';
            $user->save();

            $user->defaultEmail();
            $user->userDefaultData();

            return redirect()->back()->with('success', __('User successfully created.'));
        }

    }


    public function show($id)
    {

    }


    public function edit($id)
    {
        $user = User::find($id);

        return view('user.edit', compact('user'));
    }


    public function update(Request $request, $id)
    {

        if(\Auth::user()->type == 'super admin')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'email' => 'required|email|unique:users,email,' . $id,
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $user          = User::find($id);
            $user['name']  = $request->name;
            $user['email'] = $request->email;

            $user->save();

            return redirect()->back()->with('success', __('User successfully updated.'));
        }
    }


    public function destroy($id)
    {
        if(\Auth::user()->type == 'super admin')
        {
            $user = User::find($id);
            $user->delete();

            return redirect()->route('transfer.index')->with('success', __('User successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function profile()
    {
        $userDetail = \Auth::user();

        return view('user.profile', compact('userDetail'));
    }

    public function editprofile(Request $request)
    {

        $userDetail = \Auth::user();

        $user = User::findOrFail($userDetail['id']);
        $this->validate(
            $request, [
                        'name' => 'required|max:120',
                        'email' => 'required|email|unique:users,email,' . $userDetail['id'],
                    ]
        );

        if($user->type == 'client')
        {
            $this->validate(
                $request, [
                            'mobile' => 'required',
                            'address_1' => 'required',
                            'city' => 'required',
                            'state' => 'required',
                            'country' => 'required',
                            'zip_code' => 'required',
                        ]
            );
            $client            = Client::where('user_id', $user->id)->first();
            $client->mobile    = $request->mobile;
            $client->address_1 = $request->address_1;
            $client->address_2 = $request->address_2;
            $client->city      = $request->city;
            $client->state     = $request->state;
            $client->country   = $request->country;
            $client->zip_code  = $request->zip_code;
            $client->save();
        }

        if($request->hasFile('profile'))
        {
            $filenameWithExt = $request->file('profile')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('profile')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            $dir        = storage_path('uploads/avatar/');
            $image_path = $dir . $userDetail['avatar'];

            if(\File::exists($image_path))
            {
                \File::delete($image_path);
            }

            if(!file_exists($dir))
            {
                mkdir($dir, 0777, true);
            }

            $path = $request->file('profile')->storeAs('uploads/avatar/', $fileNameToStore);

        }

        if(!empty($request->profile))
        {
            $user['avatar'] = $fileNameToStore;
        }
        $user['name']  = $request['name'];
        $user['email'] = $request['email'];
        $user->save();


        return redirect()->back()->with('success', 'Profile successfully updated.');
    }

    public function updatePassword(Request $request)
    {
        if(\Auth::Check())
        {
            $request->validate(
                [
                    'current_password' => 'required',
                    'new_password' => 'required|min:6',
                    'confirm_password' => 'required|same:new_password',
                ]
            );
            $objUser          = \Auth::user();
            $request_data     = $request->All();
            $current_password = $objUser->password;
            if(Hash::check($request_data['current_password'], $current_password))
            {
                $user_id            = \Auth::User()->id;
                $obj_user           = User::find($user_id);
                $obj_user->password = Hash::make($request_data['new_password']);;
                $obj_user->save();

                return redirect()->route('profile', $objUser->id)->with('success', __('Password successfully updated.'));
            }
            else
            {
                return redirect()->route('profile', $objUser->id)->with('error', __('Please enter correct current password.'));
            }
        }
        else
        {
            return redirect()->route('profile', \Auth::user()->id)->with('error', __('Something is wrong.'));
        }
    }

    public function upgradePlan($user_id)
    {
        $user = User::find($user_id);

        $plans = Plan::get();

        return view('user.plan', compact('user', 'plans'));
    }

    public function activePlan($user_id, $plan_id)
    {

        $user       = User::find($user_id);
        $assignPlan = $user->assignPlan($plan_id);
        $plan       = Plan::find($plan_id);
        if($assignPlan['is_success'] == true && !empty($plan))
        {
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            Order::create(
                [
                    'order_id' => $orderID,
                    'name' => null,
                    'card_number' => null,
                    'card_exp_month' => null,
                    'card_exp_year' => null,
                    'plan_name' => $plan->name,
                    'plan_id' => $plan->id,
                    'price' => $plan->price,
                    'price_currency' => Utility::getValByName('site_currency'),
                    'txn_id' => '',
                    'payment_status' => 'succeeded',
                    'receipt' => null,
                    'payment_type' => __('Manually'),
                    'user_id' => $user->id,
                ]
            );

            return redirect()->back()->with('success', 'Plan successfully upgraded.');
        }
        else
        {
            return redirect()->back()->with('error', 'Plan fail to upgrade.');
        }

    }

    public function clientCompanyEdit(Request $request)
    {
        $user = \Auth::user();
        if($user->type == 'client')
        {
            $this->validate(
                $request, [
                            'company_name' => 'required',
                            'website' => 'required',
                            'tax_number' => 'required',
                        ]
            );
            $client               = Client::where('user_id', $user->id)->first();
            $client->company_name = $request->company_name;
            $client->website      = $request->website;
            $client->tax_number   = $request->tax_number;
            $client->notes        = $request->notes;
            $client->save();
        }

        return redirect()->back()->with('success', 'Profile successfully updated.');
    }

}
