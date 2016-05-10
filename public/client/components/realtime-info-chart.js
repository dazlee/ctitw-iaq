define(["chartConfigs",
        "lodash",
        "fetch-utils",
        "device-utils"], function (chartConfigs, _, fetchUtils, deviceUtils) {

    var _api;

    function initializeChart() {
        fetchUtils.fetchJSON(_api, {
            Accept: "application/json"
        })
        .then(function (json) {
            drawChart(json.data, chartConfigs.outline);
        });

        // [TODO] should update every 10 mins
        setInterval(function () {
            refreshChart();
        }, 2000);
    }
    function refreshChart() {
        fetchUtils.fetchJSON(_api, {
            Accept: "application/json"
        })
        .then(function (json) {
            $('#realtimechart').highcharts().destroy();
            drawChart(json.data, chartConfigs.outline);
        });
    }
    function drawChart(data, chartOptions) {
        var deviceData = deviceUtils.parseData(data);

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
        }
    };
});
