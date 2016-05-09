<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\DeviceHistory;
use App\Device;

class StatsController extends Controller
{
    public function index() {
        return view("stats", array(
            'name'  => "即時資訊",
            'type'  => "summary",
        ));
    }

    public function history() {
        $to = date_create();
        $from = date_create();
        $from = date_sub($from, date_interval_create_from_date_string("30 days"));

        return view("stats", array(
            'from'  => $from,
            'to'    => $to,
            'name'  => "歷史資訊",
            'type'  => "history",
        ));
    }

    public function all() {
        $to = date_create();
        $from = date_create();
        $from = date_sub($from, date_interval_create_from_date_string("30 days"));

        return view("stats", array(
            'from'  => $from,
            'to'    => $to,
            'name'  => "各部門資訊",
            'type'  => "all-departments",
        ));
    }

    public function summary(Request $request) {
        $row = $request->query('row');
        $avg = $request->query('avg');
        $query = DeviceHistory::oneWeekSummary();

        if (isset($avg)  && $avg == "1") {
            return [
                'avg' => ['co2' => $query->avg('co2'), 'temp' => $query->avg('temp'), 'rh' => $query->avg('rh')],
                'data' => $query->groupBy('record_at')->get(),
            ];
        }

        return ['data' => $query->groupBy('record_at')->get()];
    }
}
