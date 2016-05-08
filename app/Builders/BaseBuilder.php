<?php

namespace App\Builders;

use App\DeviceHistory;
use App\Device;

class BaseBuilder {
    
    protected $query = NULL;

    public function __construct($request, $deviceId) {
        $this->query = ($deviceId === NULL) ? $this->createQuery($request) : $this->createQueryOfDevice($request, $deviceId);
    }

    protected function createQuery($request) {
        if ($request->query('latest') == 1) {
            $count = Device::count();
            return DeviceHistory::sortRecord('desc')->take($count);
        }

        return DeviceHistory::ofDays(6)->sortRecord('asc');
    }

    protected function createQueryOfDevice($request, $deviceId) {
        $fromDate = $request->query('fromDate');
        $toDate = $request->query('toDate');

        if (isset($fromDate) && isset($toDate)) {
            return DeviceHistory::ofDevice($deviceId)->betweenDates($fromDate, $toDate)->sortRecord('asc');
        }

        if ($request->query('latest') == 1) {
            return DeviceHistory::ofDevice($deviceId)->sortRecord('desc')->take(1);
        }

        return DeviceHistory::ofDevice($deviceId)->ofDays(6)->sortRecord('asc');
    }
}
