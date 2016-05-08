<?php

namespace App\Builders;

use App\Builders\BaseBuilder;

class DataBuilder extends BaseBuilder{
    private $key = 'data';
    public $request;

    public function __construct($request, $deviceId=NULL) {
        parent::__construct($request, $deviceId);
        $this->request = $request;
    }

    public function run() {
        if ($this->request->query('nodata') == 1) {
            return [$this->key => []];
        }
 
        return [$this->key => $this->query->get()];
    }
}
