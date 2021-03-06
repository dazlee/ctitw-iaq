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
        $avg = $request->query('avg');
        $fromDate = $request->query('fromDate');
        $toDate = $request->query('toDate');
        $query;
        $avgData;
        $data;
        $result = [];

        if (isset($fromDate) && isset($toDate)) {
            $query = DeviceHistory::betweenDatesSummary($request->query('fromDate'), $request->query('toDate'));
        } else {
            $query = DeviceHistory::oneWeekSummary();
        }

        if (isset($avg) && $avg == "1") {
            $avgData = ['co2' => $query->avg('co2'), 'temp' => $query->avg('temp'), 'rh' => $query->avg('rh')];
            return [
                'avg' => ['co2' => $query->avg('co2'), 'temp' => $query->avg('temp'), 'rh' => $query->avg('rh')],
                'data' => $query->groupBy('record_at')->get(),
            ];
        }
        $result = ['data' => $query->groupBy('record_at')->get()];
        return response()->json($result)->setEncodingOptions(JSON_NUMERIC_CHECK);
    }
}
