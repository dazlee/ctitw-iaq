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
    private $limit = 30;
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


    public function index() {
        $device_count = Device::count();
        return DeviceHistory::orderBy('record_at', 'asc')->paginate($this->limit*$device_count);
    }
    /*
    public function store(Request $request) {
    $validator = $this->validator($request->all());

    if ($validator->fails()) {
        return response()->json($validator->messages(), 401);
    }

    return DeviceHistory::create($request->all());
    }
    */

    public function show(Request $request, $deivceId) {
        $fromDate = date_create($request->query('fromDate'));
        $toDate = date_create($request->query('toDate'));

        if (isset($fromDate) && isset($toDate)) {
            $fromDate->setTime(00, 00, 00);
            $toDate->setTime(23, 59, 59);
            return DeviceHistory::where('device_id', $deivceId)
                        ->whereBetween('record_at', array($fromDate, $toDate))
                        ->orderBy('record_at', 'asc')
                        ->paginate($this->limit);
        } else {
            return DeviceHistory::where('device_id', $deivceId)
                        ->orderBy('record_at', 'asc')
                        ->paginate($this->limit);
        }
    }
}
