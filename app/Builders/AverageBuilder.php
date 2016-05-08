<?php

namespace App\Builders;

use App\Builders\BaseBuilder;
use App\DeviceHistory;

class AverageBuilder extends BaseBuilder{
    private $key = 'avg';
    public $request;

    public function __construct($request, $deviceId=NULL) {
        parent::__construct($request, $deviceId);
        $this->request = $request;
    }

    public function run() {
        if ($this->request->query($this->key) != 1) {
            return [$this->key => []];
        }
 
        if ($this->request->query('timestamp') == 1) {
            return [$this->key => DeviceHistory::selectRaw('record_at, AVG(co2) as co2, AVG(temp) as temp, AVG(rh) as rh')->ofDays(6)->sortRecord('asc')->groupBy('record_at')->get()
            ];
        }

        if ($this->request->query('device_level') == 1) {
            return [$this->key => DeviceHistory::selectRaw('device_id, AVG(co2) as co2, AVG(temp) as temp, AVG(rh) as rh')->ofDays(6)->groupBy('device_id')->get()];
        }

        $query = $this->query->get();
        return [$this->key => ['co2' => $query->avg('co2'), 'temp' => $query->avg('temp'), 'rh' => $query->avg('rh')]];
    }
}
