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
}
