define(["chartConfigs",
        "lodash",
        "fetch-utils",
        "device-utils"], function (chartConfigs, _, fetchUtils, deviceUtils) {

    var _api, _endpoint, _queries, _responseKey;

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
            $('#realtime-info-chart').highcharts().destroy();
            return json;
        })
        .then(drawChart);
    }
    function drawChart(json) {
        var deviceData = deviceUtils.parseData(json[_responseKey]);
        var chartOptions = chartConfigs.outline;

        deviceData = deviceUtils.filterDeviceDataByPeriod("8hrs", deviceData);
        var series = deviceUtils.generateChartSeriesList(deviceData);
        var options = {};
        _.extend(options, chartOptions, {
            series: series,
            title: {
                text: "即時資訊"
            }
        });
        $('#realtime-info-chart').highcharts(options);
    }

    return {
        initialize: function (endpoint, queries, responseKey) {
            _endpoint = endpoint;
            _queries = queries || {};
            _responseKey = responseKey || "data";
            _api = fetchUtils.formUrl(_endpoint, _queries);

            initializeChart();
            // [TODO] should update every 10 mins
            setInterval(function () {
                refreshChart();
            }, 10000);
        }
    };
});
