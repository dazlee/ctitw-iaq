define(["chartConfigs",
        "lodash",
        "fetch-utils",
        "device-utils",
        "date-utils",
        "ramda",
        "utils"], function (chartConfigs, _, fetchUtils, deviceUtils, dateUtils, R, utils) {

    var _api;
    var _multipleDeviceData = {};
    var _filter = "hr";
    var _deviceChartSwitcher = {};
    var _period = {};
    var _deviceSelector;

    var _parseAndFillMinMaxAvgs;

    function initializeFunctions() {

    }


    function initializeViews () {
        _deviceSelector = document.querySelector("#device-selector");
        _deviceSelector.addEventListener("click", function (e) {
            var deviceId = e.target.dataset.deviceId;
            _deviceChartSwitcher[deviceId] = !_deviceChartSwitcher[deviceId];
            drawChart();
        });
    }
    function initializeData () {
        _period.from = new Date();
        _period.to = new Date();
        _period.from.setDate(_period.from.getDate() - 30);
    }
    function initializeDateRangePicker() {
        $("#average-daterange").datepicker({
            endDate: new Date(),
        })
        .on("changeDate", function (e) {
            _period[e.target.name] = e.date;
        });
    }
    function updateDeviceSelector() {

        var buttons = R.map(function (key) {
            var button = document.createElement("button");
            button.className = "btn btn-success btn-sm";
            button.dataset.deviceId = key;
            button.innerHTML = key;
            return button;
        }, R.keys(_deviceChartSwitcher));

        _deviceSelector.innerHTML = "";
        R.map(function (button) {
            _deviceSelector.appendChild(button);
        }, buttons);

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

    function parseAndSaveDeviceData (json) {
        _multipleDeviceData = deviceUtils.parseDataForMultipleDevice(json.data);
    }
    function updateDeviceChartSwitcher() {
        R.forEach(function (key) {_deviceChartSwitcher[key] = false;}, R.keys(_multipleDeviceData));
    }
    function initializeChart() {
        fetchUtils.fetchJSON(_api, {
            Accept: "application/json"
        })
        .then(parseAndSaveDeviceData)
        .then(updateDeviceChartSwitcher)
        .then(updateDeviceSelector)
        .then(drawChart);
    }
    function filterDeviceSeries(multipleDeviceSeries) {

    }
    function drawChart() {
        var multipleDeviceData = deviceUtils.filterMultipleDeviceData(_filter, _multipleDeviceData);
        var chartOptions = chartConfigs.outline;

        var multipleDeviceSeries = deviceUtils.generateChartSeriesForMultipleDevice(multipleDeviceData);
        var series = R.reduce(function (reduced, key) {
            if (_deviceChartSwitcher[key]) {
                reduced = R.concat(reduced, multipleDeviceSeries[key]);
            }
            return reduced;
        }, [], R.keys(_deviceChartSwitcher));

        var options = {};
        _.extend(options, chartOptions, {
            series: series,
        });
        $('#departmentchart').highcharts(options);
    }

    return {
        initialize: function (endpoint, queries) {
            _api = fetchUtils.formUrl(endpoint, queries);

            initializeFunctions();
            initializeViews();
            initializeData();
            initializeDateRangePicker();
            initializeActions();
            initializeChart();
        }
    };
});
