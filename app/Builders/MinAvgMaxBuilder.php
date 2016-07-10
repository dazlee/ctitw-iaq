<?php

namespace App\Builders;

use App\Builders\BaseBuilder;
use App\DeviceHistory;
use DB;

class MinAvgMaxBuilder extends BaseBuilder{
    private $key = 'min_avg_max';
    public $request;

    public function __construct($request, $deviceId=NULL) {
        parent::__construct($request, $deviceId);
        $this->request = $request;
    }

    public function run() {
        if ($this->request->query($this->key) == 1) {

            $deviceAccount = $this->request->query('deviceAccount');
            $client = DB::select('select * from clients where device_account = ?', [$deviceAccount]);
            if (count($client) > 0)  {
                $client_id = $client[0]->user_id;
                $devices = DB::select('select * from devices where client_id = ?', [$client_id]);
                $device_ids = [];
                foreach ($devices as $device) {
                    array_push($device_ids, $deviceAccount . '-' . $device->index);
                }
                $query = $this->query->whereIn('device_id', $device_ids);
            } else {
                $query = $this->query;
            }

            $query = $query->selectRaw('device_id,
                MIN(co2) as "co2-min", MIN(temp) as "temp-min", MIN(rh) as "rh-min",
                AVG(co2) as "co2-avg", AVG(temp) as "temp-avg", AVG(rh) as "rh-avg",
                MAX(co2) as "co2-max", MAX(temp) as "temp-max", MAX(rh) as "rh-max"
            ')->groupBy('device_id');

            return [$this->key => $query->get()];
        }

        return [$this->key => []];
    }
}
