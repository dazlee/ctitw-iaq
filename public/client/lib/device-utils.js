define(["chartConfigs",
        "utils",
        "moment",
        "ramda"], function (chartConfigs, utils, moment, R) {

    function gteMinutes (ms, minutes) {
        return moment.duration(ms).asMinutes() >= minutes;
    }
    function gteHours (ms, hours) {
        return moment.duration(ms).asHours() >= hours;
    }
    function gteDays (ms, days) {
        return moment.duration(ms).asDays() >= days;
    }
    function gteMonths (ms, months) {
        return moment.duration(ms).asMonths() >= months;
    }

    var filterData = R.curry(function (checker, benchmark, data) {
        var previousTimestamp = 0;
        var result = data.reduceRight(function (reduced, v) {
            var diff = Math.abs(v[0] - previousTimestamp);
            if (checker(diff, benchmark)) {
                previousTimestamp = v[0];
                reduced.push(v);
            }
            return reduced;
        }, []);
        result.reverse();
        return result;
    });

    var filterDataByMinutes = filterData(gteMinutes);
    var filterDataByHours = filterData(gteHours);
    var filterDataByDays = filterData(gteDays);
    var filterDataByMonths = filterData(gteMonths);

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

    var filterDeviceDataByPeriod = R.curry(function (filter, deviceData) {
        switch (filter) {
            case "10mins":
                return utils.mapObject(deviceData, filterDataByMinutes(10));
            case "hr":
                return utils.mapObject(deviceData, filterDataByHours(1));
            case "8hrs":
                return utils.mapObject(deviceData, filterDataByHours(8));
            case "day":
                return utils.mapObject(deviceData, filterDataByDays(1));
            case "week":
                return utils.mapObject(deviceData, filterDataByDays(7));
            case "month":
                return utils.mapObject(deviceData, filterDataByMonths(1));
            default:
                return deviceData;
        }
    });
    var filterDeviceDataForMultiDeviceByPeriod = function (filter, multipleDeviceData) {
        return R.map(filterDeviceDataByPeriod(filter), multipleDeviceData);
    };

    var generateChartSeries = R.curry(function (yAxis, color, unit, name, data) {
        return _.extend({}, chartConfigs.seriesX, {
            name: name,
            data: data,
            yAxis: yAxis,
            tooltip: {
                valueDecimals: 2,
                valueSuffix: unit
            },
            color: color,
        });
    });
    var generateChartSeriesForCo2 = generateChartSeries(0, Highcharts.getOptions().colors[0], " ppm");
    var generateChartSeriesForTemp = generateChartSeries(1, Highcharts.getOptions().colors[3], " °C");
    var generateChartSeriesForRh = generateChartSeries(2, Highcharts.getOptions().colors[2], " %");
    var generateChartSeriesList = function (deviceData) {
        return [
            generateChartSeriesForCo2("二氧化碳", deviceData.co2),
            generateChartSeriesForTemp("溫度", deviceData.temp),
            generateChartSeriesForRh("濕度", deviceData.rh),
        ];
    };

    var generateChartSeriesForCo2WithKey = function (data, key, obj) {
        return generateChartSeriesForCo2(key + " 二氧化碳", data.co2);
    };
    var generateChartSeriesForTempWithKey = function (data, key, obj) {
        return generateChartSeriesForTemp(key + " 溫度", data.temp);
    };
    var generateChartSeriesForRhWithKey = function (data, key, obj) {
        return generateChartSeriesForRh(key + " 濕度", data.rh);
    };
    var generateChartSeriesListForMultiDeviceWithDataTypeFilter = function (dataTypeFilter, multipleDeviceData) {
        var keys = R.keys(multipleDeviceData);
        var _generateChartSeriesForCo2 = function (value, key, obj) {
            return generateChartSeriesForCo2(key + " 二氧化碳", value.co2);
        };
        switch (dataTypeFilter) {
            case "co2":
                return R.mapObjIndexed(generateChartSeriesForCo2WithKey, multipleDeviceData);
            case "temp":
                return R.mapObjIndexed(generateChartSeriesForTempWithKey, multipleDeviceData);
            case "rh":
                return R.mapObjIndexed(generateChartSeriesForRhWithKey, multipleDeviceData);
            default:
                return [];
        }
    };

    return {
        parseData: function (dataList) {
            var parsedData = {
                co2: [],
                temp: [],
                rh: [],
            };
            return dataList.reduce(function (reduced, data) {
		var date = new Date(data.record_at);
                var timestamp = date.getTime() - (date.getTimezoneOffset() * 60000);
                reduced.co2.push([timestamp, data.co2]);
                reduced.temp.push([timestamp, data.temp]);
                reduced.rh.push([timestamp, data.rh]);
                return reduced;
            }, parsedData);
        },
        parseDataForMultipleDevice: function (dataList) {
            var parsedData = {};

            return dataList.reduce(function (reduced, data) {
                var key = data.device_id;
                if (typeof reduced[key] === "undefined") {
                    reduced[key] = {
                        co2: [],
                        temp: [],
                        rh: [],
                    };
                }
                var tempData = {};
		var date = new Date(data.record_at);
                var timestamp = date.getTime() - (date.getTimezoneOffset() * 60000);
                reduced[key].co2.push([timestamp, data.co2]);
                reduced[key].temp.push([timestamp, data.temp]);
                reduced[key].rh.push([timestamp, data.rh]);
                return reduced;
            }, parsedData);
        },

        generateChartSeriesList: generateChartSeriesList,
        generateChartSeriesListForMultiDeviceWithDataTypeFilter: generateChartSeriesListForMultiDeviceWithDataTypeFilter,

        filterDeviceDataByPeriod: filterDeviceDataByPeriod,
        filterDeviceDataForMultiDeviceByPeriod: filterDeviceDataForMultiDeviceByPeriod,

        getDeviceDataStatistics: function (deviceData) {
            return utils.mapObject(deviceData, getDeviceDataStatistics);
        },
    };
});
