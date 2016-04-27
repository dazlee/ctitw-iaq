<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\DeviceHistory;

class DeviceHistoryController extends Controller
{

    private $formDataKey = 'file';
    private $rules = [
	'device_id' => 'required',
        'co2' => 'required|numeric',
        'temp' => 'required|numeric',
        'rh' => 'required|numeric',
        'created_at' => 'required|date_format:Y-m-d h:i:s',
	'updated_at' => 'date_format:Y-m-d h:i:s',
    ];

    private function validator(array $data) {
	return Validator::make($data, $this->rules);
    }

    public function upload(Request $request) {
	if (!$request->hasFile($this->formDataKey)) {
	    return response()->json(['err' => 'invalid form-data key'], 401);
	}

	$file = $request->file($this->formDataKey);
	$content = file_get_contents($file);
	$rows = DeviceHistory::parseContent($content);
	
	DeviceHistory::insert($rows);
        return response()->json(['msg' => 'Success to insert ' . count($rows) . ' rows'], 201);
    }

    public function index() {
	return DeviceHistory::all();
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

    public function show($deivceId) {
	return DeviceHistory::where('device_id', $deivceId)->get();
    }
}
