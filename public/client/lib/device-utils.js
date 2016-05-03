define(["chartConfigs",
        "utils",
        "moment",
        "curry"], function (chartConfigs, utils, moment, curry) {

    function gteDays (ms, days) {
        return moment.duration(ms).asDays() >= days;
    }
    function gteMonths (ms, months) {
        return moment.duration(ms).asMonths() >= months;
    }
    function gteHours (ms, hours) {
        return moment.duration(ms).asHours() >= hours;
    }

    var filterDeviceData = curry(function (checker, benchmark, data) {
        var previousTimestamp = 0;
        return data.reduce(function (reduced, v) {
            if (checker(v[0] - previousTimestamp, benchmark)) {
                previousTimestamp = v[0];
                reduced.push(v);
            }
            return reduced;
        }, []);
    });

    var filterDeviceDataByDays = filterDeviceData(gteDays);
    var filterDeviceDataByMonths = filterDeviceData(gteMonths);
    var filterDeviceDataByHours = filterDeviceData(gteHours);

    var getDeviceDataStatistics = function (data) {
        var result = {max: -Infinity, min: Infinity, avg: 0};
        result = data.reduce(function (reduced, v) {
            var value = v[1];
            reduced.max = (value > reduced.max) ? value : reduced.max;
            reduced.min = (value < reduced.min) ? value : reduced.min;
            reduced.avg += value;
            return reduced;
        }, result);
        result.avg /= data.length;
        return result;
    };

    return {
        parseData: function (dataList) {
            var parsedData = {
                co2: [],
                temp: [],
                rh: [],
            };
            return dataList.reduce(function (reduced, data) {
                // [TODO] should change to record_at
                var timestamp = new Date(data.record_at).getTime();
                reduced.co2.push([timestamp, data.co2]);
                reduced.temp.push([timestamp, data.temp]);
                reduced.rh.push([timestamp, data.rh]);
                return reduced;
            }, parsedData);
        },
        generateChartSeries: function (deviceData) {
            return [
                _.extend({}, chartConfigs.series0, {
                    name: '二氧化碳',
                    data: deviceData.co2,
                    tooltip: {
                        valueDecimals: 2,
                        valueSuffix: ' ppm'
                    },
                }),
                _.extend({}, chartConfigs.series1, {
                    name: '溫度',
                    data: deviceData.temp,
                    tooltip: {
                        valueDecimals: 2,
                        valueSuffix: ' °C'
                    },
                }),
                _.extend({}, chartConfigs.series2, {
                    name: '濕度',
                    data: deviceData.rh,
                    tooltip: {
                        valueDecimals: 2,
                        valueSuffix: ' %'
                    },
                })
            ];
        },
        filterDeviceData: function (deviceData, filter) {
            var previousTimestamp = 0;
            switch (filter) {
                case "hr":
                    return utils.mapObject(deviceData, filterDeviceDataByHours(1));
                case "8hrs":
                    return utils.mapObject(deviceData, filterDeviceDataByHours(8));
                case "day":
                    return utils.mapObject(deviceData, filterDeviceDataByDays(1));
                case "week":
                    return utils.mapObject(deviceData, filterDeviceDataByDays(7));
                case "month":
                    return utils.mapObject(deviceData, filterDeviceDataByMonths(1));
                default:
                    return deviceData;
            }
        },
        getDeviceDataStatistics: function (deviceData) {
            return utils.mapObject(deviceData, getDeviceDataStatistics);
        },
    };
});
