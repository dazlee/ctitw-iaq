define(["lodash",
        "fetch-utils",
        "device-utils",
        "date-utils",
        "utils"], function (_, fetchUtils, deviceUtils, dateUtils, utils) {

    var _endpoint;
    var _queries;
    var _tableElement;
    var _tableBodyElement;
    var _period = {};

    function initializeViews () {
        _tableElement = document.querySelector('#historytable');
        _tableBodyElement = _tableElement.querySelector("tbody");
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
        fetchUtils.fetchJSON(fetchUtils.formUrl(_endpoint, _queries), {
            Accept: "application/json"
        })
        .then(drawTable);
    }
    function refreshTable() {
        var query = _.extend({
            fromDate: dateUtils.formatYMD(_period.from),
            toDate: dateUtils.formatYMD(_period.to),
        }, _queries);
        fetchUtils.fetchJSON(fetchUtils.formUrl(_endpoint, query), {
            Accept: "application/json"
        })
        .then(drawTable);
    }


    function drawTable (json) {
        // drawTable(json.summary);
        // TODO should put summary in summary
        var data = deviceUtils.parseData(json.data);
        var deviceDataStats = deviceUtils.getDeviceDataStatistics(data);

        var body = _tableBodyElement;
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
        initialize: function (endpoint, queries) {
            _endpoint = endpoint;
            _queries = queries;

            initializeViews();
            initializeData();
            initializeDateRangePicker();
            initializeTable();
            initializeActions();
        }
    };
});
