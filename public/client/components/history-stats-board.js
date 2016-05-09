define(["underscore",
        "fetch-utils",
        "device-utils",
        "date-utils",
        "utils"], function (_, fetchUtils, deviceUtils, dateUtils, utils) {

    var _deviceId = -1;
    var _tableElement;
    var _period = {};

    function initializeViews () {
        _tableElement = document.querySelector('#historytable');
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
    function initializeActions() {
        $("#refreshTable").click(function (e) {
            e.preventDefault();
            refreshTable();
        });
    }
    function initializeTable() {
        fetchUtils.fetchJSON("/api/devices/" + _deviceId + "?action=summary", {
            Accept: "application/json"
        })
        .then(function (json) {
            drawTable(json.summary);
        });
    }
    function refreshTable() {
        var query = {
            fromDate: dateUtils.formatYMD(_period.from),
            toDate: dateUtils.formatYMD(_period.to),
            action: "summary",
        };
        var queryString = fetchUtils.queryStringify(query);
        fetchUtils.fetchJSON("/api/devices/" + _deviceId + "?" + queryString, {
            Accept: "application/json"
        })
        .then(function (json) {
            drawTable(json.summary);
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
        initialize: function (deviceId) {
            _deviceId = deviceId;

            initializeViews();
            initializeData();
            initializeDateRangePicker();
            initializeTable();
            initializeActions();
        }
    };
});
