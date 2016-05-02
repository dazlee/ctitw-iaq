define(["client/constants/chart.js",
        "utils",
        "moment",
        "curry"], function (chartOptions, utils, moment, curry) {

    function gteDays (ms, days) {
        return moment.duration(ms).asDays() >= days;
    }
    function gteMonths (ms, months) {
        return moment.duration(ms).asMonths() >= months;
    }
    function gteHours (ms, hours) {
        return moment.duration(ms).asHours() >= hours;
    }

    var filterDeviceData = curry(function (checker, days, data) {
        var previousTimestamp = 0;
        return data.reduce(function (reduced, v) {
            if (checker(v[0] - previousTimestamp, days)) {
                previousTimestamp = v[0];
                reduced.push(v);
            }
            return reduced;
        }, []);
    });

    var filterDeviceDataByDays = filterDeviceData(gteDays);
    var filterDeviceDataByMonths = filterDeviceData(gteMonths);
    var filterDeviceDataByHours = filterDeviceData(gteHours);

    return {
        parseData: function (dataList) {
            var parsedData = {
                co2: [],
                temp: [],
                rh: [],
            };
            return dataList.reduce(function (reduced, data) {
                // [TODO] should change to record_at
                var timestamp = new Date(data.created_at).getTime();
                reduced.co2.push([timestamp, data.co2]);
                reduced.temp.push([timestamp, data.temp]);
                reduced.rh.push([timestamp, data.rh]);
                return reduced;
            }, parsedData);
        },
        generateChartSeries: function (deviceData) {
            return [
                _.extend({}, chartOptions.series0, {
                    name: '二氧化碳',
                    data: deviceData.co2,
                    tooltip: {
                        valueDecimals: 2,
                        valueSuffix: ' ppm'
                    },
                }),
                _.extend({}, chartOptions.series1, {
                    name: '溫度',
                    data: deviceData.temp,
                    tooltip: {
                        valueDecimals: 2,
                        valueSuffix: ' °C'
                    },
                }),
                _.extend({}, chartOptions.series2, {
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
                    return utils.reduceObject(deviceData, filterDeviceDataByHours(1));
                case "8hrs":
                    return utils.reduceObject(deviceData, filterDeviceDataByHours(8));
                case "day":
                    return utils.reduceObject(deviceData, filterDeviceDataByDays(1));
                case "week":
                    return utils.reduceObject(deviceData, filterDeviceDataByDays(7));
                case "month":
                    return utils.reduceObject(deviceData, filterDeviceDataByMonths(1));
                default:

            }
        },
    };
});
