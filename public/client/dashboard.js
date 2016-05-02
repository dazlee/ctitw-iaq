define(["./constants/chart.js",
        "underscore",
        "fetch-utils",
        "device-utils",
        "utils"], function (chartConfigs, _, fetchUtils, deviceUtils, utils) {

    var _deviceData = {};
    var _filter = "hr";

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

            var filter = e.target.dataset.filter;
            if (filter !== _filter) {
                var deviceData = deviceUtils.filterDeviceData(_deviceData, filter);
                _filter = filter;
                drawChart(deviceData, chartConfigs.outline);
            }
        });
    }

    function initializeActions() {
        $("#refresh").click(function (e) {
            e.preventDefault();
            refreshChart();
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
            drawChart(deviceData, chartConfigs.outline);

            _deviceData = deviceData;
        });
    }
    function refreshChart() {
        fetchUtils.fetchJSON("/api/devices/2", {
            Accept: "application/json"
        })
        .then(function (json) {
            $('#historychart').highcharts().destroy();

            var deviceData = deviceUtils.parseData(json.data);
            drawChart(deviceData, chartConfigs.outline);

            _deviceData = deviceData;
        });
    }
    function drawChart(deviceData, chartOptions) {
        var series = deviceUtils.generateChartSeries(deviceData);
        var options = {};
        _.extend(options, chartOptions, {
            series: series,
        });
        $('#historychart').highcharts(options);
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
