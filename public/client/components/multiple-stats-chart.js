define(["chartConfigs",
        "lodash",
        "fetch-utils",
        "device-utils",
        "date-utils",
        "ramda",
        "utils"], function (chartConfigs, _, fetchUtils, deviceUtils, dateUtils, R, utils) {

    var _api, _endpoint, _queries;
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
        _deviceSelector = document.querySelector("#device-selector");
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
        $("#average-daterange-multiple-stats-chart").datepicker({
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
    function updateDeviceSelector() {
        _deviceSelector.innerHTML = "";
        R.map(_appendToDeviceSelector,
            R.mapObjIndexed(generateDeviceSwitchButton, _isDrawDeviceChart)
        );
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
    function initializeDatatypeSelector() {
        $('#datatype-selector a').click(function (e) {
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
        _multipleDeviceData = deviceUtils.parseDataForMultipleDevice(json.data);
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
        .then(updateDeviceSelector)
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
        .then(drawChart);
    }
    function drawChart() {
        var multipleDeviceData = deviceUtils.filterDeviceDataForMultiDeviceByPeriod(_filter, _multipleDeviceData);
        var chartOptions = chartConfigs.outline;

        var multipleDeviceSeries = deviceUtils.generateChartSeriesListForMultiDeviceWithDataTypeFilter(_dataTypeFilter, multipleDeviceData);
        var series = R.reduce(function (reduced, key) {
            if (_isDrawDeviceChart[key]) {
                reduced.push(multipleDeviceSeries[key]);
            }
            return reduced;
        }, [], R.keys(_isDrawDeviceChart));

        var options = {};
        _.extend(options, chartOptions, {
            series: series,
        });
        $('#departmentchart').highcharts(options);
    }

    return {
        initialize: function (endpoint, queries) {
            _api = fetchUtils.formUrl(endpoint, queries);
            _endpoint = endpoint;
            _queries = queries;

            initializeViews();
            initializeData();
            initializeDateRangePicker();
            initializeDatatypeSelector();
            initializeActions();
            initializeChart();
        }
    };
});
