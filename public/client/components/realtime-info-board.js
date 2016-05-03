define(["chartConfigs",
        "underscore",
        "fetch-utils",
        "device-utils"], function (chartConfigs, _, fetchUtils, deviceUtils) {

    var _deviceId;

    function initializeChart() {
        _deviceId = document.querySelector('#realtimechart').dataset.deviceId;
        if (typeof _deviceId === "undefined") return;

        fetchUtils.fetchJSON("/api/devices/" + _deviceId, {
            Accept: "application/json"
        })
        .then(function (json) {
            drawChart(json.data, chartConfigs.outline);
        });


        // [TODO] should set interval to fetch updated data continuously
    }
    function refreshChart() {
        if (typeof _deviceId === "undefined") return;

        fetchUtils.fetchJSON("/api/devices/" + _deviceId, {
            Accept: "application/json"
        })
        .then(function (json) {
            $('#realtimechart').highcharts().destroy();

            drawChart(json.data, chartConfigs.outline);
        });
    }
    function drawChart(data, chartOptions) {
        var deviceData = deviceUtils.parseData(data);
        deviceData = deviceUtils.filterDeviceData(deviceData, "hr");

        var series = deviceUtils.generateChartSeries(deviceData);
        var options = {};
        _.extend(options, chartOptions, {
            series: series,
            title: {
                text: "即時資訊"
            }
        });
        $('#realtimechart').highcharts(options);
    }

    return {
        initialize: function () {
            initializeChart();
        }
    };
});
