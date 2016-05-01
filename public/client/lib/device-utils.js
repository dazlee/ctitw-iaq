define(["client/constants/chart.js"], function (chartOptions) {
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
    };
});
