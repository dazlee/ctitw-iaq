<?php

namespace App\Builders;

use App\DeviceHistory;

class BaseBuilder {

    protected $query = NULL;

    public function __construct($request, $deviceId) {
        $this->query = ($deviceId === NULL) ? $this->createQuery($request) : $this->createQueryOfDevice($request, $deviceId);
    }

    protected function createQuery($request) {
        $fromDate = $request->query('fromDate');
        $toDate = $request->query('toDate');
        $deviceAccount = $request->query('deviceAccount');
        $query = DeviceHistory::sortRecord('asc');

        if (isset($fromDate) && isset($toDate)) {
            $query = $query->betweenDates($fromDate, $toDate);
        } else {
            $query = $query->ofDays(6);
        }

        if (!empty($deviceAccount)) {
            $query = $query->like($deviceAccount);
        }

        return $query;
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
