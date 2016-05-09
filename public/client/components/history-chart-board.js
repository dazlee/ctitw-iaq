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

    var _deviceId = -1;
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
                drawChart(deviceUtils.filterDeviceData(_deviceData, filter), chartConfigs.outline);
                _filter = filter;
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
        fetchUtils.fetchJSON("/api/devices/" + _deviceId, {
            Accept: "application/json"
        })
        .then(function (json) {
            _deviceData = deviceUtils.parseData(json.data);
            drawChart(deviceUtils.filterDeviceData(_deviceData, _filter), chartConfigs.outline);
        });
    }
    function refreshChart() {
        var query = {
            fromDate: dateUtils.formatYMD(_period.from),
            toDate: dateUtils.formatYMD(_period.to),
        };
        var queryString = fetchUtils.queryStringify(query);
        fetchUtils.fetchJSON("/api/devices/" + _deviceId + "?" + queryString, {
            Accept: "application/json"
        })
        .then(function (json) {
            $('#historychart').highcharts().destroy();
            _deviceData = deviceUtils.parseData(json.data);
            drawChart(deviceUtils.filterDeviceData(deviceData, _filter), chartConfigs.outline);
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
        initialize: function (deviceId) {
            _deviceId = deviceId;

            initializeData();
            initializeDateRangePicker();
            initializeUnitSelector();
            initializeActions();
            initializeChart();
        }
    };
});
