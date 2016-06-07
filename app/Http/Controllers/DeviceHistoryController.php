<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\DeviceHistory;
use App\Device;
use App\Builders\QueryBuilder;

class DeviceHistoryController extends Controller
{

    private $formDataKey = 'file';

    public function upload(Request $request) {
        if (!$request->hasFile($this->formDataKey)) {
            return response()->json(['err' => 'invalid form-data key'], 406);
        }

        $file = $request->file($this->formDataKey);
        $fileName = $file->getClientOriginalName();
        $deviceAccount = explode("-", $fileName)[0];

        if (stripos($fileName, 'hour') !== False) {
            $path =  public_path() . '/files';
            $file->move($path, $fileName);
            return response()->json(['msg' => 'Success to upload file to webserver'], 201);
        }

        $content = file_get_contents($file);
        $device_history_list = DeviceHistory::parseContent($deviceAccount, $content);

        if (empty($device_history_list)) {
            return response()->json(['err' => 'The format of file is uncorrect'], 406);
        }

        /*foreach ($device_history_list as $row) {
            Device::firstOrCreate(array('id' => $row['device_id']));
        }
        */

        try {
            DeviceHistory::sendMail($deviceAccount, $device_history_list);
        } catch (\Exception $e) {
            return response()->json(['err' => 'SMTP Error: ' . $e->getMessage()], 406);
        }

        DeviceHistory::insert($device_history_list);
        return response()->json(['msg' => $device_history_list], 201);
    }

    public function index(Request $request) {
        return response()->json(QueryBuilder::run($request))->setEncodingOptions(JSON_NUMERIC_CHECK);
    }

    public function show(Request $request, $deviceId) {
        return response()->json(QueryBuilder::run($request, $deviceId))->setEncodingOptions(JSON_NUMERIC_CHECK);
    }
}
