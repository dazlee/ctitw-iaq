<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($deviceId)
    {
        // [TODO] should pull data from DB and combine with view first here?
        $to = date_create();
        $from = date_create();
        $from = date_sub($from, date_interval_create_from_date_string("30 days"));

        return view('dashboard', array(
            'deviceId'    => $deviceId,
            'from'  => $from,
            'to'    => $to,
        ));
    }

    public function indexForAdmin($id, $deviceId)
    {
        // [TODO] should pull data from DB and combine with view first here?
        $to = date_create();
        $from = date_create();
        $from = date_sub($from, date_interval_create_from_date_string("30 days"));

        // second device id is for admin to check device stats
        return view('dashboard', array(
            'deviceId'    => $deviceId,
            'from'  => $from,
            'to'    => $to,
        ));
    }
}
