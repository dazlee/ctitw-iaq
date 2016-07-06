<?php

namespace App\Builders;

use App\Builders\BaseBuilder;
use App\DeviceHistory;
use DB;

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
            $deviceAccount = $this->request->query('deviceAccount');
            $client = DB::select('select * from clients where device_account = ?', [$deviceAccount]);
            if (count($client) > 0)  {
                $client_id = $client[0]->user_id;
                $devices = DB::select('select * from devices where client_id = ?', [$client_id]);
                $device_ids = [];
                foreach ($devices as $device) {
                    array_push($device_ids, $deviceAccount . '-' . $device->index);
                }
                return [$this->key => $this->query->selectRaw('record_at, AVG(co2) as co2, AVG(temp) as temp, AVG(rh) as rh')->whereIn('device_id', $device_ids)->groupBy('record_at')->get()];
            } else {
                return [$this->key => $this->query->selectRaw('record_at, AVG(co2) as co2, AVG(temp) as temp, AVG(rh) as rh')->groupBy('record_at')->get()];
            }
        }

        if ($this->request->query('device_level') == 1) {
            return [$this->key => $this->query->selectRaw('device_id, AVG(co2) as co2, AVG(temp) as temp, AVG(rh) as rh')->groupBy('device_id')->get()];
        }

        $query = $this->query->get();
        return [$this->key => ['co2' => $query->avg('co2'), 'temp' => $query->avg('temp'), 'rh' => $query->avg('rh')]];
    }
}
