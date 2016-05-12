define(["lodash",
        "fetch-utils",
        "device-utils",
        "date-utils",
        "ramda",
        "utils"], function (_, fetchUtils, deviceUtils, dateUtils, R, utils) {

    var _api, _endpoint, _queries, _responseKey;
    var _tableElement;
    var _tableBodyElement;
    var _period = {};

    var _parseAndFillMinMaxAvgs;

    function initializeFunctions() {
        var CellKeys = ["device_id", "co2-max", "co2-min", "co2-avg",
                                    "temp-max", "temp-min", "temp-avg",
                                    "rh-max", "rh-min", "rh-avg"];
        var appendChild = R.curry(function (parent, child) {
            parent.appendChild(child);
            return parent;
        });
        var generateCell = function (data) {
            var td = document.createElement("td");
            td.innerHTML = data;
            return td;
        };
        var generateCellArray = R.map(generateCell);
        var generateRow = function (tds) {
            var tr = document.createElement("tr");
            var appendChildToTr = appendChild(tr);
            R.forEach(appendChildToTr, tds);
            return tr;
        };
        var mapToDataArray = function (data) {
            return CellKeys.map(function (key) {
                return data[key];
            });
        };
        var appendToTBody = appendChild(_tableBodyElement);
        var generateRows = R.map(R.compose(generateRow, generateCellArray, mapToDataArray));
        var appendRowsToTBody = R.forEach(appendToTBody);
        _parseAndFillMinMaxAvgs = R.compose(appendRowsToTBody, generateRows);
    }


    function initializeViews () {
        _tableElement = document.querySelector("#multiple-stats-table");
        _tableBodyElement = _tableElement.querySelector("tbody");
    }
    function initializeData () {
        _period.from = new Date();
        _period.to = new Date();
        _period.from.setDate(_period.from.getDate() - 30);
    }
    function initializeDateRangePicker() {
        $("#daterange-multiple-stats-table").datepicker({
            endDate: new Date(),
        })
        .on("changeDate", function (e) {
            _period[e.target.name] = e.date;
        });
    }
    function initializeActions() {
        $("#refresh-multiple-stats-table").click(function (e) {
            e.preventDefault();
            refreshTable();
        });
    }
    function initializeTable() {
        fetchUtils.fetchJSON(_api, {
            Accept: "application/json"
        })
        .then(function (json) {
            drawTable(json);
        });
    }
    function refreshTable() {
        var api = fetchUtils.formUrl(_endpoint, _.extend({}, _queries, {
            fromDate: dateUtils.formatYMD(_period.from),
            toDate: dateUtils.formatYMD(_period.to),
        }));
        fetchUtils.fetchJSON(api, {
            Accept: "application/json"
        })
        .then(drawTable);
    }
    function drawTable (json) {
        var min_avg_max = json[_responseKey];
        _tableBodyElement.innerHTML = "";
        _parseAndFillMinMaxAvgs(min_avg_max);
    }

    return {
        initialize: function (endpoint, queries, responseKey) {
            _endpoint = endpoint;
            _queries = queries || {};
            _responseKey = responseKey || "data";
            _api = fetchUtils.formUrl(_endpoint, _queries);

            initializeViews();
            initializeFunctions();
            initializeData();
            initializeDateRangePicker();
            initializeActions();
            initializeTable();
        }
    };
});
