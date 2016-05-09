<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\DeviceHistory;
use App\Device;

class DeviceHistoryController extends Controller
{

    private $formDataKey = 'file';
    private $limit = 1008;
    private $rules = [
        'device_id'     => 'required',
        'co2'           => 'required|numeric',
        'temp'          => 'required|numeric',
        'rh'            => 'required|numeric',
        'record_at'    => 'required|date_format:Y-m-d h:i:s',
        'created_at'    => 'date_format:Y-m-d h:i:s',
        'updated_at'    => 'date_format:Y-m-d h:i:s',
    ];

    private function validator(array $data) {
        return Validator::make($data, $this->rules);
    }

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
        $row = $request->query('row');
        $action = $request->query('action');

        if (isset($row) && $row == -1) {
            $query = DeviceHistory::latest()->take(Device::count());

            if (isset($action) && $action == 'avg') {
                return [
                    'avg' => ['co2' => $query->avg('co2'), 'temp' => $query->avg('temp'), 'rh' => $query->avg('rh')],
                    'data' => $query->get()
                ];
            }

            return ['data' => $query->get()];
        }
        
        $query = DeviceHistory::oneWeek();

        if (isset($action) && $action == 'avg') {
            return [
                'avg' => ['co2' => $query->avg('co2'), 'temp' => $query->avg('temp'), 'rh' => $query->avg('rh')],
                'data' => $query->get()
            ];
        }

        return ['data' => $query->get()];
    }

    public function show(Request $request, $deviceId) {
        $fromDate = $request->query('fromDate');
        $toDate = $request->query('toDate');
        $row = $request->query('row');
        $action = $request->query('action');       

        if (isset($fromDate) && isset($toDate)) {
            $query = DeviceHistory::betweenDatesByDeviceId($deviceId, $fromDate, $toDate);

            if (isset($action) && $action == 'summary') {
                return [
                    'summary' => [
                        'co2' => ['max' => $query->max('co2'), 'avg' => $query->avg('co2'), 'min' => $query->min('co2')],
                        'temp' => ['max' => $query->max('temp'), 'avg' => $query->avg('temp'), 'min' => $query->min('temp')],
                        'rh' => ['max' => $query->max('rh'), 'avg' => $query->avg('rh'), 'min' => $query->min('rh')]
                    ],
                    'data' => $query->get()
                ];
            }

            return ['data' => $query->get()];
        }
        
        if (isset($row) && $row == -1) {
            return ['data' => DeviceHistory::latestByDeviceId($deviceId)->first()];
        } 

        return ['data' => DeviceHistory::oneWeekByDeviceId($deviceId)->get()];
    }
}
