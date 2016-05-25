<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Client;
use App\User;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = [];
        $list = '經銷商名單';
        $type = 'agent';
        $user = Auth::user();

        if ($user && $user->hasRole('admin')) {
            if ($request->get('agent_id')) {
                $username = User::find($request->get('agent_id'))->name;
                $list = "{$username}的客戶名單";
                $type = 'client';
            } else if ($request->get('client_id')) {
                $username = User::find($request->get('client_id'))->name;
                $list = "{$username}的帳號清單";
                $type = 'department';
            }
        }

        return view('home', [
            'type' => $type,
            'list' => $list,
        ]);
    }
}
