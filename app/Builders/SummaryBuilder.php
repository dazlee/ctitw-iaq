<?php

namespace App\Builders;

use App\Builders\BaseBuilder;
use App\DeviceHistory;
use DB;

class SummaryBuilder extends BaseBuilder{
    private $key = 'summary';
    public $request;

    public function __construct($request, $deviceId=NULL) {
        parent::__construct($request, $deviceId);
        $this->request = $request;
    }

    public function run() {
        if ($this->request->query($this->key) != 1) {
            return [$this->key => []];
        }

        $deviceAccount = $this->request->query('deviceAccount');
        $client = DB::select('select * from clients where device_account = ?', [$deviceAccount]);
        if (count($client) > 0)  {
            $client_id = $client[0]->user_id;
            $devices = DB::select('select * from devices where client_id = ?', [$client_id]);
            $device_ids = [];
            foreach ($devices as $device) {
                array_push($device_ids, $deviceAccount . '-' . $device->index);
            }
            $query = $this->query->whereIn('device_id', $device_ids)->get();
        } else {
            $query = $this->query->get();
        }

        return [$this->key => [
            'co2' => ['max' => $query->max('co2'), 'avg' => $query->avg('co2'), 'min' => $query->min('co2')],
            'temp' => ['max' => $query->max('temp'), 'avg' => $query->avg('temp'), 'min' => $query->min('temp')],
            'rh' => ['max' => $query->max('rh'), 'avg' => $query->avg('rh'), 'min' => $query->min('rh')]
        ]];
    }
}
