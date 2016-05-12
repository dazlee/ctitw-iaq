<?php

namespace App\Builders;

use App\Builders\BaseBuilder;
use App\DeviceHistory;
use App\Device;

class LatestBuilder extends BaseBuilder{
    private $key = 'latest';
    public $deviceId;
    public $request;

    public function __construct($request, $deviceId=NULL) {
        $this->request = $request;
        $this->deviceId = $deviceId;
    }

    public function run() {
        if ($this->request->query($this->key) != 1) {
            return [$this->key => []];
        }
        
        if ($this->deviceId === NULL) {
            $count = Device::count();
            return [$this->key => DeviceHistory::sortRecord('desc')->take($count)->get()];
        } else {
            return [$this->key => DeviceHistory::ofDevice($this->deviceId)->sortRecord('desc')->first()];
        }
    }
}
