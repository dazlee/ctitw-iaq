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
        if ($this->request->query($this->key) == 1) {
            $query = $this->query->selectRaw('device_id,
                MIN(co2) as "co2-min", MIN(temp) as "temp-min", MIN(rh) as "rh-min",
                AVG(co2) as "co2-avg", AVG(temp) as "temp-avg", AVG(rh) as "rh-avg",
                MAX(co2) as "co2-max", MAX(temp) as "temp-max", MAX(rh) as "rh-max"
            ')->groupBy('device_id');

            return [$this->key => $query->get()];
        }

        return [$this->key => []];
    }
}
