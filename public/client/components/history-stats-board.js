define(["underscore",
        "fetch-utils",
        "device-utils",
        "date-utils",
        "utils"], function (_, fetchUtils, deviceUtils, dateUtils, utils) {

    var _tableElement;
    var _deviceId;
    var _period = {};

    function initializeDateRangePicker() {
        // initialize date range checker
        _period.from = new Date();
        _period.to = new Date();
        _period.from.setDate(_period.from.getDate() - 30);
        $("#average-daterange").datepicker({
            endDate: new Date(),
        })
        .on("changeDate", function (e) {
            _period[e.target.name] = e.date;
        });
    }

    function initializeActions() {
        $("#refreshTable").click(function (e) {
            e.preventDefault();
            refreshTable();
        });
    }

    function initializeTable() {
        _tableElement = document.querySelector('#historytable');
        _deviceId = _tableElement.dataset.deviceId;
        if (typeof _deviceId === "undefined") return;

        fetchUtils.fetchJSON("/api/devices/" + _deviceId, {
            Accept: "application/json"
        })
        .then(function (json) {
            var deviceData = deviceUtils.parseData(json.data);
            var deviceDataStats = deviceUtils.getDeviceDataStatistics(deviceData);
            drawTable(deviceDataStats);
        });
    }
    function refreshTable() {
        if (typeof _deviceId === "undefined") return;

        var query = {
            fromDate: dateUtils.formatYMD(_period.from),
            toDate: dateUtils.formatYMD(_period.to),
        };
        var queryString = fetchUtils.queryStringify(query);
        fetchUtils.fetchJSON("/api/devices/" + _deviceId + "?" + queryString, {
            Accept: "application/json"
        })
        .then(function (json) {
            var deviceData = deviceUtils.parseData(json.data);
            var deviceDataStats = deviceUtils.getDeviceDataStatistics(deviceData);
            drawTable(deviceDataStats);
        });
    }
    function drawTable (deviceDataStats) {
        var body = _tableElement.querySelector("tbody");
        utils.mapObject(deviceDataStats, function (data, key) {
            // class name is restricted to
            // '.co2-max', 'co2-min', 'co2-avg'
            // '.temp-max', 'temp-min', 'temp-avg'
            // '.rh-max', 'rh-min', 'rh-avg'
            var className = "." + key;
            utils.mapObject(data, function (value, key_inner) {
                var className_inner = className + '-' + key_inner;
                body.querySelector(className_inner).innerHTML = value.toFixed(2);
            });
        });
    }

    return {
        initialize: function () {
            initializeDateRangePicker();
            initializeTable();
            initializeActions();
        }
    };
});
