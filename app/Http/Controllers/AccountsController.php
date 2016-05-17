<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests;
use DB;
use Auth;
use App\User;
use App\Role;
use App\Department;
use App\Device;

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
            "devices"   => Device::all(),
        ));
    }

    public function createDepartment(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
            'device_id' => 'required|unique:departments,device_id'
        ]);

        DB::transaction(function($request) use ($request) {
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => bcrypt($request->get('password'))
            ]);

            $role = Role::where('name', '=', 'department')->first();
            $user->attachRole($role);

            $department = new Department();       
            $department->client_id = Auth::id();
            $department->device_id = $request->get('device_id');
            $department->phone = $request->get('phone');
            $user->department()->save($department);
        });

        return Redirect::back();
    }

    public function device () {
        return view('accounts', array(
            "name"      => "儀器",
            "type"      => "device",
        ));
    }
}
