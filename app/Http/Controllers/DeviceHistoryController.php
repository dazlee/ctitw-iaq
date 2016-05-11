<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\DeviceHistory;
use App\Device;

define('ENUM_BETWEEN_DATES', 0);
define('ENUM_BETWEEN_DATES_AND_SUMMARY', 1);
define('ENUM_LATEST', 2);
define('ENUM_LATEST_AND_AVG', 3);
define('ENUM_ONEWEEK', 4);
define('ENUM_ONEWEEK_AND_SUMMARY', 5);
define('ENUM_ONEWEEK_AND_AVG', 6);
define('ENUM_ONEWEEK_AVG_AND_TIMESTAMP', 7);
define('ENUM_ONEWEEK_AVG_AND_DEVICELEVEL', 8);
define('ENUM_ONEWEEK_MIN_MAX_AVG_AND_DEVICELEVEL', 9);
define('ENUM_BETWEEN_DATES_MIN_MAX_AVG_AND_DEVICELEVEL', 10);

class DeviceHistoryController extends Controller
{

    private $formDataKey = 'file';
    private $rulesOfIndex = [
        ENUM_LATEST_AND_AVG => ['latest' => 'required|accepted', 'avg' => 'required|accepted'],
        ENUM_LATEST => ['latest' => 'required|accepted'],
        ENUM_BETWEEN_DATES_MIN_MAX_AVG_AND_DEVICELEVEL => ['fromDate' => 'required', 'toDate' => 'required', 'min_max_avg' => 'required|accepted', 'device_level' => 'required|accepted'],
        ENUM_BETWEEN_DATES => ['fromDate' => 'required', 'toDate' => 'required'],
        ENUM_ONEWEEK_AVG_AND_TIMESTAMP => ['avg' => 'required|accepted', 'timestamp' => 'required|accepted'],
        ENUM_ONEWEEK_AVG_AND_DEVICELEVEL => ['avg' => 'required|accepted', 'device_level' => 'required|accepted'],
        ENUM_ONEWEEK_MIN_MAX_AVG_AND_DEVICELEVEL => ['min_max_avg' => 'required|accepted', 'device_level' => 'required|accepted'],
        ENUM_ONEWEEK_AND_AVG => ['avg' => 'required|accepted'],
        ENUM_ONEWEEK => []
    ];
    private $rulesOfShow = [
        ENUM_BETWEEN_DATES_AND_SUMMARY => ['fromDate' => 'required', 'toDate' => 'required', 'summary' => 'required|accepted'],
        ENUM_BETWEEN_DATES => ['fromDate' => 'required', 'toDate' => 'required'],
        ENUM_LATEST => ['latest' => 'required|accepted'],
        ENUM_ONEWEEK_AND_SUMMARY => ['summary' => 'required|accepted'],
        ENUM_ONEWEEK => []
    ];

    public function upload(Request $request) {
        if (!$request->hasFile($this->formDataKey)) {
            return response()->json(['err' => 'invalid form-data key'], 406);
        }

        $file = $request->file($this->formDataKey);
        $content = file_get_contents($file);
        $device_history_list = DeviceHistory::parseContent($content);
        $device_list = [];

        if (empty($device_history_list)) {
            return response()->json(['err' => 'The format of file is uncorrect'], 406);
        }

        foreach ($device_history_list as $row) {
            Device::firstOrCreate(array('id' => $row['device_id']));
        }

        DeviceHistory::insert($device_history_list);
        return response()->json(['msg' => $device_history_list], 201);
    }

    private function getRule($data, $rules) {
        foreach ($rules as $key => $rule) {
            if (Validator::make($data, $rule)->passes()) {
                break;
            }
        }

        return $key;
    }

    public function index(Request $request) {
        $result = ['avg' => [], 'data' => []];
        $rule = $this->getRule($request->query(), $this->rulesOfIndex);

        switch($rule) {
            case ENUM_LATEST_AND_AVG:
                $query = DeviceHistory::descOrder()->take(Device::count());
                $result['avg'] = DeviceHistory::doAvg($query);
                $result['data'] = $query->get();
                break;
            case ENUM_LATEST:
                $result['data'] = DeviceHistory::descOrder()->take(Device::count())->get();
                break;
            case ENUM_ONEWEEK_AND_AVG:
                $query = DeviceHistory::oneWeek();
                $result['avg'] = DeviceHistory::doAvg($query);
                $result['data'] = $query->get();
                break;
            case ENUM_ONEWEEK_AVG_AND_TIMESTAMP:
                $result['data'] = DeviceHistory::oneWeek()
                    ->selectRaw('record_at, AVG(co2) as co2, AVG(temp) as temp, AVG(rh) as rh')
                    ->groupBy('record_at')
                    ->get();
                break;
            case ENUM_ONEWEEK_AVG_AND_DEVICELEVEL:
                $result['data'] = DeviceHistory::oneWeek()
                    ->selectRaw('device_id, avg(co2) as co2, avg(temp) as temp, avg(rh) as rh')
                    ->groupBy('device_id')
                    ->get();
                break;
            case ENUM_BETWEEN_DATES_MIN_MAX_AVG_AND_DEVICELEVEL:
                $result['data'] = DeviceHistory::betweenDates($request->query('fromDate'), $request->query('toDate'))
                    ->selectRaw('device_id, MIN(co2) as "co2-min", MAX(co2) as "co2-max", AVG(co2) as "co2-avg",
                                            MIN(temp) as "temp-min", MAX(temp) as "temp-max", AVG(temp) as "temp-avg",
                                            MIN(rh) as "rh-min", MAX(rh) as "rh-max", AVG(rh) as "rh-avg"')
                    ->groupBy('device_id')
                    ->orderBy('device_id', 'asc')
                    ->get();
                break;
            case ENUM_ONEWEEK_MIN_MAX_AVG_AND_DEVICELEVEL:
                $result['data'] = DeviceHistory::oneWeek()
                    ->selectRaw('device_id, MIN(co2) as "co2-min", MAX(co2) as "co2-max", AVG(co2) as "co2-avg",
                                            MIN(temp) as "temp-min", MAX(temp) as "temp-max", AVG(temp) as "temp-avg",
                                            MIN(rh) as "rh-min", MAX(rh) as "rh-max", AVG(rh) as "rh-avg"')
                    ->groupBy('device_id')
                    ->orderBy('device_id', 'asc')
                    ->get();
                break;
            case ENUM_BETWEEN_DATES:
                $result['data'] = DeviceHistory::betweenDates($request->query('fromDate'), $request->query('toDate'))->get();
                break;
            default:
                $result['data'] = DeviceHistory::oneWeek()->get();
                break;
        }

        return response()->json($result)->setEncodingOptions(JSON_NUMERIC_CHECK);
    }

    public function show(Request $request, $deviceId) {
        $result = ['summary' => [], 'data' => []];
        $rule = $this->getRule($request->query(), $this->rulesOfShow);

        switch($rule) {
            case ENUM_BETWEEN_DATES_AND_SUMMARY:
                $query = DeviceHistory::betweenDatesOfDeviceId($deviceId, $request->query('fromDate'), $request->query('toDate'));
                $result['summary'] = DeviceHistory::doSummary($query);
                $result['data'] = $query->get();
                break;
            case ENUM_BETWEEN_DATES:
                $result['data'] = DeviceHistory::betweenDatesOfDeviceId($deviceId, $request->query('fromDate'), $request->query('toDate'))->get();
                break;
            case ENUM_LATEST:
                $result['data'] = DeviceHistory::descOrderOfDeviceId($deviceId)->first();
                break;
            case ENUM_ONEWEEK_AND_SUMMARY:
                $query = DeviceHistory::oneWeekOfDeviceId($deviceId);
                $result['summary'] = DeviceHistory::doSummary($query);
                $result['data'] = $query->get();
                break;
            default:
                $result['data'] = DeviceHistory::oneWeekOfDeviceId($deviceId)->get();
                break;
        }

        return response()->json($result)->setEncodingOptions(JSON_NUMERIC_CHECK);
    }
}
