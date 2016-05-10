define(["chartConfigs",
        "lodash",
        "fetch-utils",
        "device-utils"], function (chartConfigs, _, fetchUtils, deviceUtils) {

    var _api;

    function initializeChart() {
        fetchUtils.fetchJSON(_api, {
            Accept: "application/json"
        })
        .then(drawChart);
    }
    function refreshChart() {
        fetchUtils.fetchJSON(_api, {
            Accept: "application/json"
        })
        .then(function (json) {
            $('#realtimechart').highcharts().destroy();
            return json;
        })
        .then(drawChart);
    }
    function drawChart(json) {
        var deviceData = deviceUtils.parseData(json.data);
        var chartOptions = chartConfigs.outline;

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
        initialize: function (endpoint, queries) {
            _api = fetchUtils.formUrl(endpoint, queries);

            initializeChart();
            // [TODO] should update every 10 mins
            setInterval(function () {
                refreshChart();
            }, 10000);
        }
    };
});
