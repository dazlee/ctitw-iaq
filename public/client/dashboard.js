define(["client/components/realtime-info-board",
        "chartConfigs",
        "underscore",
        "fetch-utils",
        "device-utils",
        "utils"], function (RealtimeInfoBoard, chartConfigs, _, fetchUtils, deviceUtils, utils) {

    var _deviceId;
    var _deviceData = {};
    var _filter = "hr";
    var _period = {};

    function initializeDateRangePicker() {
        // initialize date range checker
        _period.from = new Date();
        _period.to = new Date();
        _period.from.setDate(_period.from.getDate() - 30);
        $(".input-daterange").datepicker({
            endDate: new Date(),
        })
        .on("changeDate", function (e) {
            _period[e.target.name] = e.date;
            console.log(_period);
        });
    }

    function initializeUnitSelector() {
        $('#unit-selector a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');

            if (typeof _deviceId === "undefined") return;

            var filter = e.target.dataset.filter;
            if (filter !== _filter) {
                var deviceData = deviceUtils.filterDeviceData(_deviceData, filter);
                _filter = filter;
                drawChart(deviceData, chartConfigs.outline);
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
        _deviceId = document.querySelector('#historychart').dataset.deviceId;
        if (typeof _deviceId === "undefined") return;

        fetchUtils.fetchJSON("/api/devices/" + _deviceId, {
            Accept: "application/json"
        })
        .then(function (json) {
            var deviceData = deviceUtils.parseData(json.data);
            drawChart(deviceData, chartConfigs.outline);

            _deviceData = deviceData;
        });
    }
    function refreshChart() {
        if (typeof _deviceId === "undefined") return;

        fetchUtils.fetchJSON("/api/devices/" + _deviceId, {
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
            RealtimeInfoBoard.initialize();

            initializeDateRangePicker();
            initializeUnitSelector();
            initializeActions();
            initializeChart();
        }
    };
});
