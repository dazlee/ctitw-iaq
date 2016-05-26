<?php

namespace App\Builders;

use App\DeviceHistory;

class BaseBuilder {

    protected $query = NULL;

    public function __construct($request, $deviceId) {
        $this->query = ($deviceId === NULL) ? $this->createQuery($request) : $this->createQueryOfDevice($request, $deviceId);
    }

    protected function createQuery($request) {
        return DeviceHistory::ofDays(6)->sortRecord('asc');
    }

    protected function createQueryOfDevice($request, $deviceId) {
        $fromDate = $request->query('fromDate');
        $toDate = $request->query('toDate');

        if (isset($fromDate) && isset($toDate)) {
            return DeviceHistory::ofDevice($deviceId)->betweenDates($fromDate, $toDate)->sortRecord('asc');
        }

        return DeviceHistory::ofDevice($deviceId)->ofDays(6)->sortRecord('asc');
    }
}