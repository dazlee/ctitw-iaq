<?php

namespace App\Builders;

use App\Builders\BaseBuilder;
use App\DeviceHistory;

class MinAvgMaxBuilder extends BaseBuilder{
    private $key = 'min_avg_max';
    public $request;

    public function __construct($request, $deviceId=NULL) {
        parent::__construct($request, $deviceId);
        $this->request = $request;
    }

    public function run() {
        if ($this->request->query($this->key) != 1) {
            return [$this->key => []];
        }
 
        if ($this->request->query('device_level') == 1) {
            return [$this->key => DeviceHistory::selectRaw('device_id, 
                MIN(co2) as min_co2, MIN(temp) as min_temp, MIN(rh) as min_rh, 
                AVG(co2) as avg_co2, AVG(temp) as avg_temp, AVG(rh) as avg_rh,
                MAX(co2) as max_co2, MAX(temp) as max_temp, MAX(rh) as max_rh
                ')->ofDays(6)->groupBy('device_id')->get()
            ];

        }
    }
}
