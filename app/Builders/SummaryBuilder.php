<?php

namespace App\Builders;

use App\Builders\BaseBuilder;
use App\DeviceHistory;

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
 
        $query = $this->query->get();
        return [$this->key => [
            'co2' => ['max' => $query->max('co2'), 'avg' => $query->avg('co2'), 'min' => $query->min('co2')],
            'temp' => ['max' => $query->max('temp'), 'avg' => $query->avg('temp'), 'min' => $query->min('temp')],
            'rh' => ['max' => $query->max('rh'), 'avg' => $query->avg('rh'), 'min' => $query->min('rh')]
        ]];
    }
}
