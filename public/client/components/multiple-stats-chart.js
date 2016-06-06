define(["chartConfigs",
        "lodash",
        "fetch-utils",
        "device-utils",
        "date-utils",
        "ramda",
        "utils"], function (chartConfigs, _, fetchUtils, deviceUtils, dateUtils, R, utils) {

    var _api, _endpoint, _queries, _responseKey;
    var _multipleDeviceData = {};
    var _filter = "hr";
    var _dataTypeFilter = "co2";
    var _isDrawDeviceChart = {};
    var _period = {};
    var _deviceSelector;

    var _parseAndFillMinMaxAvgs;

    var appendChild = R.curry(function(parent, child) {
        parent.appendChild(child);
    });
    var _appendToDeviceSelector;

    function initializeViews () {
        _deviceSelector = document.querySelector("#device-selector-multiple-stats-chart");
        _deviceSelector.addEventListener("click", function (e) {
            var deviceId = e.target.dataset.deviceId;
            if (typeof deviceId === "undefined") return;

            _isDrawDeviceChart[deviceId] = !_isDrawDeviceChart[deviceId];

            if (_isDrawDeviceChart[deviceId]) {
                $(e.target).addClass('active');
            } else {
                $(e.target).removeClass('active');
            }

            drawChart();
        });
    }
    function initializeData () {
        _period.from = new Date();
        _period.to = new Date();
        _period.from.setDate(_period.from.getDate() - 30);
    }
    function initializeDateRangePicker() {
        $("#daterange-multiple-stats-chart").datepicker({
            endDate: new Date(),
        })
        .on("changeDate", function (e) {
            _period[e.target.name] = e.date;
        });
    }

    function generateDeviceSwitchButton(isOn, key) {
        var button = document.createElement("button");
        var className = isOn ? "btn btn-device btn-sm mr-5 active" : "btn btn-device btn-sm mr-5";
        button.className = className;
        button.dataset.deviceId = key;
        button.innerHTML = key;
        return button;
    }
    function initializeUnitSelector() {
        $('#unit-selector-multiple-stats-chart a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');

            var filter = e.target.dataset.filter;
            if (filter !== _filter) {
                _filter = filter;
                drawChart();
            }
        });
    }
    function initializeDatatypeSelector() {
        $('#datatype-selector-multiple-stats-chart a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');

            var filter = e.target.dataset.filter;
            if (filter !== _dataTypeFilter) {
                _dataTypeFilter = filter;
                drawChart();
            }
        });
    }
    function initializeActions() {
        $("#refresh-multiple-stats-chart").click(function (e) {
            e.preventDefault();
            refreshChart();
        });

        _appendToDeviceSelector = appendChild(_deviceSelector);
    }

    function parseAndSaveDeviceData (json) {
        _multipleDeviceData = deviceUtils.parseDataForMultipleDevice(json[_responseKey]);
    }
    function updateIsDrawDeviceChart() {
        R.forEach(function (key) {
            _isDrawDeviceChart[key] = false;
        }, R.keys(_multipleDeviceData));
    }
    function initializeChart() {
        fetchUtils.fetchJSON(_api, {
            Accept: "application/json"
        })
        .then(parseAndSaveDeviceData)
        .then(updateIsDrawDeviceChart)
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
        .then(parseAndSaveDeviceData)
        .then(function (json) {
            $('#multiple-stats-chart').highcharts().destroy();
            return json;
        })
        .then(drawChart);
    }
    function drawChart() {
        var multipleDeviceData = deviceUtils.filterDeviceDataForMultiDeviceByPeriod(_filter, _multipleDeviceData);
        var chartOptions = chartConfigs.outline;

        var multipleDeviceSeries = deviceUtils.generateChartSeriesListForMultiDeviceWithDataTypeFilter(_dataTypeFilter, multipleDeviceData);
        var series = R.reduce(function (reduced, key) {
            if (_isDrawDeviceChart[key] && multipleDeviceSeries[key]) {
                reduced.push(multipleDeviceSeries[key]);
            }
            return reduced;
        }, [], R.keys(_isDrawDeviceChart));

        var options = {};
        _.extend(options, chartOptions, {
            series: series,
        });
        $('#multiple-stats-chart').highcharts(options);
    }

    return {
        initialize: function (endpoint, queries, responseKey) {
            _endpoint = endpoint;
            _queries = queries || {};
            _responseKey = responseKey || "data";
            _api = fetchUtils.formUrl(_endpoint, _queries);

            initializeViews();
            initializeData();
            initializeDateRangePicker();
            initializeDatatypeSelector();
            initializeActions();
            initializeChart();
        }
    };
});
