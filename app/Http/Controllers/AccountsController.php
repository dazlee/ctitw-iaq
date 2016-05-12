<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\Role;

class AccountsController extends Controller
{
    //
    public function index () {
        return view('accounts');
    }

    public function agent () {
        return view('accounts', array(
            "name"      => "經銷商",
            "type"      => "agent",
        ));
    }

    public function createAgent (Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'username' => 'required|max:20|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')),
        ]);

        $agent = Role::where('name', '=', "agent")->first();
        $user->attachRole($agent);

        return view('accounts', array(
            "name"      => "經銷商",
            "type"      => "agent",
        ));
    }

    public function client () {
        return view('accounts', array(
            "name"      => "客戶",
            "type"      => "client",
        ));
    }

    public function department () {
        return view('accounts', array(
            "name"      => "部門",
            "type"      => "department",
        ));
    }

    public function device () {
        return view('accounts', array(
            "name"      => "儀器",
            "type"      => "device",
        ));
    }
}
