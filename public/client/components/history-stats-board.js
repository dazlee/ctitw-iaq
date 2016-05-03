define(["underscore",
        "fetch-utils",
        "device-utils"], function (_, fetchUtils, deviceUtils) {

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
            console.log(_period);
        });
    }

    function initializeTable() {
        _deviceId = document.querySelector('#historytable').dataset.deviceId;
        if (typeof _deviceId === "undefined") return;

        fetchUtils.fetchJSON("/api/devices/" + _deviceId, {
            Accept: "application/json"
        })
        .then(function (json) {
            var deviceData = deviceUtils.parseData(json.data);
        });
    }

    return {
        initialize: function () {
            initializeDateRangePicker();
            initializeTable();
        }
    };
});
