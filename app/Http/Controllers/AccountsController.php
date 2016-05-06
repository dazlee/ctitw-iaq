<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

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
        ));
    }

    public function device () {
        return view('accounts', array(
            "name"      => "儀器",
            "type"      => "device",
        ));
    }
}
