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

    var _endpoint;
    var _deviceData = {};
    var _filter = "hr";
    var _period = {};

    function initializeData() {
        _period.from = new Date();
        _period.to = new Date();
        _period.from.setDate(_period.from.getDate() - 30);
    }

    function initializeDateRangePicker() {
        $("#history-daterange").datepicker({
            endDate: new Date(),
        })
        .on("changeDate", function (e) {
            _period[e.target.name] = e.date;
        });
    }
    function initializeUnitSelector() {
        $('#unit-selector a').click(function (e) {
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
        $("#refreshHistory").click(function (e) {
            e.preventDefault();
            refreshChart();
        });

        $("#download").click(function (e) {
            e.preventDefault();
            console.log("should download");
        });
    }

    function initializeChart() {
        fetchUtils.fetchJSON(_endpoint, {
            Accept: "application/json"
        })
        .then(parseAndSaveDeviceData)
        .then(drawChart);
    }
    function refreshChart() {
        var query = {
            fromDate: dateUtils.formatYMD(_period.from),
            toDate: dateUtils.formatYMD(_period.to),
        };
        var queryString = fetchUtils.queryStringify(query);
        fetchUtils.fetchJSON(_endpoint + "?" + queryString, {
            Accept: "application/json"
        })
        .then(function (json) {
            $('#historychart').highcharts().destroy();
            return json;
        })
        .then(parseAndSaveDeviceData)
        .then(drawChart);
    }
    function parseAndSaveDeviceData (json) {
        var deviceData = deviceUtils.parseData(json.data);
        _deviceData = deviceUtils.filterDeviceData(deviceData, _filter);
    }
    function drawChart() {
        var deviceData = deviceUtils.filterDeviceData(_deviceData, _filter);
        var chartOptions = chartConfigs.outline;

        var series = deviceUtils.generateChartSeries(deviceData);
        var options = {};
        _.extend(options, chartOptions, {
            series: series,
        });
        $('#historychart').highcharts(options);
    }

    return {
        initialize: function (endpoint) {
            _endpoint = endpoint;

            initializeData();
            initializeDateRangePicker();
            initializeUnitSelector();
            initializeActions();
            initializeChart();
        }
    };
});
