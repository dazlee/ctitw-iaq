<?php

namespace App\Builders;

use App\Builders\DataBuilder;
use App\Builders\SummaryBuilder;
use App\Builders\AverageBuilder;
use App\Builders\MinAvgMaxBuilder;
use App\DeviceHistory;
use App\Device;

class QueryBuilder {
    public static function run($request, $deviceId=NULL) {
        $results = [];
        $builders = [
            new DataBuilder($request, $deviceId),
            new SummaryBuilder($request, $deviceId),
            new AverageBuilder($request, $deviceId),
            new MinAvgMaxBuilder($request, $deviceId)
        ];

        foreach ($builders as $builder) {
            $results += $builder->run();
        }

        return $results;
    }
}
