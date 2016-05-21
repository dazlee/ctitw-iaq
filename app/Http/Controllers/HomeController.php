<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Client;
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
        $filterId = NULL;
        $user = Auth::user();
        

        if ($user && $user->hasRole('admin')) {
            if ($request->get('agent_id')) {
                $list = '客戶名單';
                $type = 'client';
                $filterId = $request->get('agent_id');
            } else if ($request->get('client_id')) {
                $list = '部門名單';
                $type = 'department';
                 $filterId = $request->get('client_id');
            }
        }

        return view('home', [
            'type' => $type,
            'list' => $list,
            'filter_id' => $filterId
        ]);
    }
}
