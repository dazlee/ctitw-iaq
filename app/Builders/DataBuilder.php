<?php

namespace App\Builders;

use App\Builders\BaseBuilder;
use DB;

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

        if ($this->request->query('d') == 1) {
            return [$this->key => $this->query->selectRaw('record_at, AVG(co2) as co2, AVG(temp) as temp, AVG(rh) as rh')
                ->groupBy(DB::raw('DAY(record_at)'))->get()
            ];
        }

        return [$this->key => $this->query->get()];
    }
}
