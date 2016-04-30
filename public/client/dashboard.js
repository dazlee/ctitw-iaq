define(["./constants/chart.js",
        "underscore",
        "fetch-utils",
        "device-utils",
        "utils"], function (chartOptions, _, fetchUtils, deviceUtils, utils) {
    function initializeDateRangePicker() {
        // initialize date range checker
        $('.input-daterange input').each(function() {
            $(this).datepicker();
        });
    }

    function initializeUnitSelector() {
        $('#unit-selector a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
    }

    function initializeActions() {
        $("#refresh").click(function (e) {
            e.preventDefault();

            console.log("should refresh");
        });

        $("#download").click(function (e) {
            e.preventDefault();

            console.log("should download");
        });
    }

    function initializeChart() {
        fetchUtils.fetchJSON("/api/devices/1", {
            Accept: "application/json"
        })
        .then(function (json) {
            var deviceData = deviceUtils.parseData(json.data);
            var options = {};
            _.extend(options, chartOptions, {
                series: [{
                    name: '二氧化碳',
                    yAxis: 0,
                    data: deviceData.co2,
                    tooltip: {
                        valueDecimals: 2,
                        valueSuffix: ' ppm'
                    },
                    color: Highcharts.getOptions().colors[0]
                },
                {
                    name: '溫度',
                    yAxis: 1,
                    data: deviceData.temp,
                    tooltip: {
                        valueDecimals: 2,
                        valueSuffix: ' °C'
                    },
                    color: Highcharts.getOptions().colors[3]

                },
                {
                    name: '濕度',
                    yAxis: 2,
                    data: deviceData.rh,
                    tooltip: {
                        valueDecimals: 2,
                        valueSuffix: ' %'
                    },
                    color: Highcharts.getOptions().colors[2]

                }]
            });
            $('#historychart').highcharts(options);
        });
    }

    return {
        initialize: function () {
            initializeDateRangePicker();
            initializeUnitSelector();
            initializeActions();
            initializeChart();
        }
    };
});
