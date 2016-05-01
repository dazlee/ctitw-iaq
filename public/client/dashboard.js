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
            initializeChart();
        });

        $("#download").click(function (e) {
            e.preventDefault();

            console.log("should download");
        });
    }

    var historyChart;
    function initializeChart() {
        fetchUtils.fetchJSON("/api/devices/1", {
            Accept: "application/json"
        })
        .then(function (json) {
            var deviceData = deviceUtils.parseData(json.data);
            var series = deviceUtils.generateChartSeries(deviceData);
            var options = {};
            _.extend(options, chartOptions.outline, {
                series: series,
            });
            historyChart = $('#historychart').highcharts(options);
        });
    }
    function refreshChart() {
        fetchUtils.fetchJSON("/api/devices/2", {
            Accept: "application/json"
        })
        .then(function (json) {
            var deviceData = deviceUtils.parseData(json.data);
            var series = deviceUtils.generateChartSeries(deviceData);
            historyChart.setData(series);
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
