define(["client/constants/chart.js",
        "moment"], function (chartOptions, moment) {
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
                    console.log("should filter by hr");
                    return deviceData;
                case "8hrs":
                    console.log("should filter by 8hrs");
                    break;
                case "day":
                    console.log("should filter by day");
                    break;
                case "week":
                    previousTimestamp = 0;
                    var filteredCo2 = deviceData.co2.reduce(function (reduced, v) {
                        var diff = moment.duration(v[0] - previousTimestamp);
                        if (diff.asDays() > 6) {
                            previousTimestamp = v[0];
                            reduced.push(v);
                        }
                        return reduced;
                    }, []);
                    previousTimestamp = 0;
                    var filteredTemp = deviceData.temp.reduce(function (reduced, v) {
                        var diff = moment.duration(v[0] - previousTimestamp);
                        if (diff.asDays() > 6) {
                            previousTimestamp = v[0];
                            reduced.push(v);
                        }
                        return reduced;
                    }, []);
                    previousTimestamp = 0;
                    var filteredRh = deviceData.rh.reduce(function (reduced, v) {
                        var diff = moment.duration(v[0] - previousTimestamp);
                        if (diff.asDays() > 6) {
                            previousTimestamp = v[0];
                            reduced.push(v);
                        }
                        return reduced;
                    }, []);
                    return {
                        co2: filteredCo2,
                        temp: filteredTemp,
                        rh: filteredRh,
                    };
                case "month":
                    console.log("should filter by month");
                    break;
                default:

            }
        },
    };
});
