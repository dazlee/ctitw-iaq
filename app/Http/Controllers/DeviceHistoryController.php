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

        if (isset($row) && $row == -1) {
            return DeviceHistory::orderBy('record_at', 'desc')
                ->take(Device::count())
                ->get();
        }

        $date = new \DateTime();
        $datetime = $date->modify('-6 day')->format('Y-m-d');
        return DeviceHistory::whereDate('record_at', '>', $datetime)->orderBy('record_at', 'asc')->get();
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
        $fromDate = $request->query('fromDate');
        $toDate = $request->query('toDate');
        $row = $request->query('row');

        if (isset($fromDate) && isset($toDate)) {
            $fromDate = date_create($fromDate)->setTime(00, 00, 00);
            $toDate = date_create($toDate)->setTime(23, 59, 59);
            return DeviceHistory::where('device_id', $deivceId)
                        ->whereBetween('record_at', array($fromDate, $toDate))
                        ->orderBy('record_at', 'asc')
                        ->paginate($this->limit);
        }
        
        if (isset($row) && $row == -1) {
            return DeviceHistory::where('device_id', $deivceId)
                        ->orderBy('record_at', 'desc')
                        ->first();
                            
        } 

        $date = new \DateTime();
        $datetime = $date->modify('-6 day')->format('Y-m-d');
        return DeviceHistory::where('device_id', $deivceId)
                    ->whereDate('record_at', '>', $datetime)
                    ->orderBy('record_at', 'asc')
                    ->get();
    }
}
