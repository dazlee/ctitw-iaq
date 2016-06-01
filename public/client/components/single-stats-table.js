define(["lodash",
        "fetch-utils",
        "device-utils",
        "date-utils",
        "utils"], function (_, fetchUtils, deviceUtils, dateUtils, utils) {

    var _api, _endpoint, _queries, _responseKey;
    var _tableElement;
    var _tableBodyElement;
    var _period = {};

    function initializeViews () {
        _tableElement = document.querySelector('#single-stats-table');
        _tableBodyElement = _tableElement.querySelector("tbody");
    }
    function initializeData () {
        _period.from = new Date();
        _period.to = new Date();
        _period.from.setDate(_period.from.getDate() - 30);
    }

    function initializeDateRangePicker() {
        $("#average-daterange-single-stats-table").datepicker({
            endDate: new Date(),
        })
        .on("changeDate", function (e) {
            _period[e.target.name] = e.date;
        });
    }
    function initializeActions() {
        $("#refresh-single-stats-table").click(function (e) {
            e.preventDefault();
            refreshTable();
        });
    }
    function initializeTable() {
        fetchUtils.fetchJSON(_api, {
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
        var deviceDataStats = json[_responseKey];

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
        initialize: function (endpoint, queries, responseKey) {
            _endpoint = endpoint;
            _queries = queries || {};
            _responseKey = responseKey || "data";
            _api = fetchUtils.formUrl(_endpoint, _queries);

            initializeViews();
            initializeData();
            initializeDateRangePicker();
            initializeTable();
            initializeActions();
        }
    };
});
