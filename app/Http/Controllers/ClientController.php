<?php

namespace App\Http\Controllers;

use App\Client;
use App\User;
use App\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{

    public function index()
    {
        if(\Auth::user()->type == 'company')
        {
            $status  = Client::$statues;
            $clients = User::where('type', 'client')->where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('client.index', compact('status', 'clients'));
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
            return view('client.create');
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
                                   'name' => 'required|max:120',
                                   'email' => 'required|email|unique:users',
                                   'password' => 'required|min:6',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $user             = new User();
            $user->name       = $request->name;
            $user->email      = $request->email;
            $user->password   = Hash::make($request->password);
            $user->type       = 'client';
            $user->lang       = 'en';
            $user->created_by = \Auth::user()->creatorId();
            $user->avatar     = 'avatar.png';
            $user->save();

            if(!empty($user))
            {
                $client             = new Client();
                $client->user_id    = $user->id;
                $client->client_id  = $this->clientNumber();
                $client->created_by = \Auth::user()->creatorId();
                $client->save();
            }

            $uArr = [
                'email' => $user->email,
                'password' => $request->password,
            ];

            $resp = Utility::sendEmailTemplate('create_user', [$user->id => $user->email], $uArr);


            return redirect()->route('client.index')->with('success', __('Client successfully created.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function show($id)
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'client')
        {
            $cId  = \Crypt::decrypt($id);
            $user = User::find($cId);

            $client = Client::where('user_id', $cId)->first();

            return view('client.view', compact('user', 'client'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function edit($id)
    {
        if(\Auth::user()->type == 'company')
        {
            $user   = User::find($id);
            $client = Client::where('user_id', $id)->first();

            return view('client.edit', compact('user', 'client'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function update(Request $request, $id)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'mobile' => 'required',
                                   'company_name' => 'required',
                                   'address_1' => 'required',
                                   'city' => 'required',
                                   'state' => 'required',
                                   'country' => 'required',
                                   'zip_code' => 'required',

                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            if(!empty($request->name))
            {
                $user       = User::find($id);
                $user->name = $request->name;
                $user->save();
            }

            $client               = Client::where('user_id', $user->id)->first();
            $client->company_name = $request->company_name;
            $client->mobile       = $request->mobile;
            $client->address_1    = $request->address_1;
            $client->address_2    = !empty($request->address_2) ? $request->address_2 : '';
            $client->tax_number   = !empty($request->tax_number) ? $request->tax_number : '';
            $client->website      = !empty($request->website) ? $request->website : '';
            $client->city         = $request->city;
            $client->state        = $request->state;
            $client->country      = $request->country;
            $client->zip_code     = $request->zip_code;
            $client->notes         = !empty($request->notes) ? $request->notes : '';
            $client->save();

            return redirect()->route('client.index')->with(
                'success', 'Client successfully updated.'
            );
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function destroy($id)
    {
        if(\Auth::user()->type == 'company')
        {
            $user = User::find($id);
            $user->delete();

            $client = Client::where('user_id', $id)->first();
            $client->delete();

            return redirect()->route('client.index')->with('success', __('Client successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    function clientNumber()
    {
        $latest = Client::where('created_by', '=', \Auth::user()->creatorId())->latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->client_id + 1;
    }
}
