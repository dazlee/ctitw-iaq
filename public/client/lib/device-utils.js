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
        return data.reduce(function (reduced, v) {
            if (checker(v[0] - previousTimestamp, benchmark)) {
                previousTimestamp = v[0];
                reduced.push(v);
            }
            return reduced;
        }, []);
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

    var generateChartSeries = R.curry(function (name, unit, yAxis, color, data) {
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
    var generateChartSeriesForCo2 = generateChartSeries("二氧化碳", " ppm", 0, Highcharts.getOptions().colors[0]);
    var generateChartSeriesForTemp = generateChartSeries("溫度", " °C", 1, Highcharts.getOptions().colors[3]);
    var generateChartSeriesForRh = generateChartSeries("濕度", " %", 2, Highcharts.getOptions().colors[2]);
    var generateChartSeriesList = function (deviceData) {
        return [
            generateChartSeriesForCo2(deviceData.co2),
            generateChartSeriesForTemp(deviceData.temp),
            generateChartSeriesForRh(deviceData.rh),
        ];
    };
    var generateChartSeriesListForMultiDeviceWithDataTypeFilter = function (dataTypeFilter, multipleDeviceData) {
        switch (dataTypeFilter) {
            case "co2":
                return R.map(R.compose(generateChartSeriesForCo2, R.prop("co2")), multipleDeviceData);
            case "temp":
                return R.map(R.compose(generateChartSeriesForTemp, R.prop("temp")), multipleDeviceData);
            case "rh":
                return R.map(R.compose(generateChartSeriesForRh, R.prop("rh")), multipleDeviceData);
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
                var timestamp = new Date(data.record_at).getTime();
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
                var timestamp = new Date(data.record_at).getTime();
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
