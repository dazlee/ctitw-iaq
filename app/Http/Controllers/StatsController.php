<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class StatsController extends Controller
{
    public function index() {
        return view("summary");
    }

    public function history() {
        $to = date_create();
        $from = date_create();
        $from = date_sub($from, date_interval_create_from_date_string("30 days"));

        return view("history", array(
            'from'  => $from,
            'to'    => $to,
        ));
    }

    public function all() {
        $to = date_create();
        $from = date_create();
        $from = date_sub($from, date_interval_create_from_date_string("30 days"));

        return view("all", array(
            'from'  => $from,
            'to'    => $to,
        ));
    }
}
