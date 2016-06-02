define(["chartConfigs",
        "lodash",
        "fetch-utils",
        "device-utils",
        "date-utils"], function (
                chartConfigs,
                _,
                fetchUtils,
                deviceUtils,
                dateUtils) {

    var _api, _endpoint, _queries, _responseKey;
    var _deviceData = {};
    var _filter = "hr";
    var _period = {};

    function initializeData() {
        _period.from = new Date();
        _period.to = new Date();
        _period.from.setDate(_period.from.getDate() - 30);
    }

    function initializeDateRangePicker() {
        $("#daterange-single-stats-chart").datepicker({
            endDate: new Date(),
        })
        .on("changeDate", function (e) {
            _period[e.target.name] = e.date;
        });
    }
    function initializeUnitSelector() {
        $('#unit-selector-single-stats-chart a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');

            var filter = e.target.dataset.filter;
            if (filter !== _filter) {
                _filter = filter;
                drawChart();
            }
        });
    }
    function initializeActions() {
        $("#refresh-single-stats-chart").click(function (e) {
            e.preventDefault();
            refreshChart();
        });

        $("#download-single-stats-chart").click(function (e) {
            e.preventDefault();
            console.log("should download");
        });
    }

    function initializeChart() {
        fetchUtils.fetchJSON(_api, {
            Accept: "application/json"
        })
        .then(parseAndSaveDeviceData)
        .then(drawChart);
    }
    function refreshChart() {
        var api = fetchUtils.formUrl(_endpoint, _.extend({}, _queries, {
            fromDate: dateUtils.formatYMD(_period.from),
            toDate: dateUtils.formatYMD(_period.to),
        }));
        fetchUtils.fetchJSON(api, {
            Accept: "application/json"
        })
        .then(function (json) {
            $('#single-stats-chart').highcharts().destroy();
            return json;
        })
        .then(parseAndSaveDeviceData)
        .then(drawChart);
    }
    function parseAndSaveDeviceData (json) {
        _deviceData = deviceUtils.parseData(json[_responseKey]);
    }
    function drawChart() {
        var deviceData = deviceUtils.filterDeviceDataByPeriod(_filter, _deviceData);
        var chartOptions = chartConfigs.outline;

        var series = deviceUtils.generateChartSeriesList(deviceData);
        var options = {};
        _.extend(options, chartOptions, {
            series: series,
        });
        $('#single-stats-chart').highcharts(options);
    }

    return {
        initialize: function (endpoint, queries, responseKey) {
            _endpoint = endpoint;
            _queries = queries || {};
            _responseKey = responseKey || "data";
            _api = fetchUtils.formUrl(_endpoint, _queries);

            initializeData();
            initializeDateRangePicker();
            initializeUnitSelector();
            initializeActions();
            initializeChart();
        }
    };
});
