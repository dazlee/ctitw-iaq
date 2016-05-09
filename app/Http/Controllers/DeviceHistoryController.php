<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\DeviceHistory;
use App\Device;

define('ENUM_DATES_WITH_SUMMARY', '00');
define('ENUM_DATES', '01');
define('ENUM_LATEST', '10');
define('ENUM_LATEST_WITH_AVG', '11');
define('ENUM_ONEWEEK_WITH_SUMMARY', '20');
define('ENUM_ONEWEEK', '21');
define('ENUM_ONEWEEK_WITH_AVG', '22');

class DeviceHistoryController extends Controller
{

    private $formDataKey = 'file';
    private $queryRulesOfIndex = [
        ENUM_LATEST_WITH_AVG => ['latest' => 'required|accepted', 'avg' => 'required|accepted'],
        ENUM_LATEST => ['latest' => 'required|accepted'],
        ENUM_ONEWEEK_WITH_AVG => ['avg' => 'required|accepted'],
        ENUM_ONEWEEK => []
    ];
    private $queryRulesOfShow = [
        ENUM_DATES_WITH_SUMMARY => ['fromDate' => 'required', 'toDate' => 'required', 'summary' => 'required|accepted'],
        ENUM_DATES => ['fromDate' => 'required', 'toDate' => 'required'],
        ENUM_LATEST => ['latest' => 'required|accepted'],
        ENUM_ONEWEEK_WITH_SUMMARY => ['summary' => 'required|accepted'],
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


    public function index(Request $request) {
        $result = ['avg' => [], 'data' => []];
        $data = $request->query();

        foreach ($this->queryRulesOfIndex as $key => $queryRule) {
            if (Validator::make($data, $queryRule)->passes()) {
                break;
            }
        }

        switch($key) {
            case ENUM_LATEST_WITH_AVG:
                $query = DeviceHistory::descOrder()->take(Device::count());
                $result['avg'] = DeviceHistory::doAvg($query);
                $result['data'] = $query->get();
                break;
            case ENUM_LATEST:
                $result['data'] = DeviceHistory::descOrder()->take(Device::count())->get();
                break;
            case ENUM_ONEWEEK_WITH_AVG:
                $query = DeviceHistory::oneWeek();
                $result['avg'] = DeviceHistory::doAvg($query);
                $result['data'] = $query->get();
                break;
            default:
                $result['data'] = DeviceHistory::oneWeek()->get();
                break;        
        }

        return $result;
    }

    public function show(Request $request, $deviceId) {
        $result = ['summary' => [], 'data' => []];
        $data = $request->query();

        foreach ($this->queryRulesOfShow as $key => $queryRule) {
            if (Validator::make($data, $queryRule)->passes()) { 
                break;
            }
        }

        switch($key) {
            case ENUM_DATES_WITH_SUMMARY:
                $query = DeviceHistory::betweenDatesOfDeviceId($deviceId, $request->query('fromDate'), $request->query('toDate'));
                $result['summary'] = DeviceHistory::doSummary($query);
                $result['data'] = $query->get();
                break;
            case ENUM_DATES:
                $result['data'] = DeviceHistory::betweenDatesOfDeviceId($deviceId, $request->query('fromDate'), $request->query('toDate'))->get();
                break;
            case ENUM_LATEST:
                $result['data'] = DeviceHistory::descOrderOfDeviceId($deviceId)->first();
                break;
            case ENUM_ONEWEEK_WITH_SUMMARY:
                $query = DeviceHistory::oneWeekOfDeviceId($deviceId);
                $result['summary'] = DeviceHistory::doSummary($query);
                $result['data'] = $query->get();
                break;
            default:
                $result['data'] = DeviceHistory::oneWeekOfDeviceId($deviceId)->get();
                break;
        }

        return $result;
    }
}
