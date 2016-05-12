define(["chartConfigs",
        "lodash",
        "fetch-utils",
        "device-utils"], function (chartConfigs, _, fetchUtils, deviceUtils) {

    var _api, _endpoint, _queries, _responseKey;
    var _co2Value, _co2Number;
    var _tempValue, _tempNumber;
    var _rhValue, _rhNumber;

    function initializeViews() {
        _co2Value = document.querySelector("#realtime-info-co2-panel .value");
        _co2Number = _co2Value.querySelector(".number");

        _tempValue = document.querySelector("#realtime-info-temp-panel .value");
        _tempNumber = _tempValue.querySelector(".number");

        _rhValue = document.querySelector("#realtime-info-rh-panel .value");
        _rhNumber = _rhValue.querySelector(".number");
    }
    function initializePanels() {
        fetchUtils.fetchJSON(_api, {
            Accept: "application/json"
        })
        .then(refreshTableLayout);
    }
    function refreshTableLayout (json) {
        var currentData = json[_responseKey];

        if (currentData.co2 > 50) {
            _co2Value.classList.add('fg-red');
        } else {
            _co2Value.classList.remove('fg-red');
        }
        if (currentData.temp > 20) {
            _tempValue.classList.add('fg-red');
        } else {
            _tempValue.classList.remove('fg-red');
        }
        if (currentData.rh > 20) {
            _rhValue.classList.add('fg-red');
        } else {
            _rhValue.classList.remove('fg-red');
        }

        _co2Number.innerHTML = currentData.co2.toFixed(2);
        _tempNumber.innerHTML = currentData.temp.toFixed(2);
        _rhNumber.innerHTML = currentData.rh.toFixed(2);
    }

    return {
        initialize: function (endpoint, queries, responseKey) {
            _endpoint = endpoint;
            _queries = queries || {};
            _responseKey = responseKey || "data";
            _api = fetchUtils.formUrl(_endpoint, _queries);

            initializeViews();
            initializePanels();
            setInterval(function () {
                initializePanels();
            }, 10000);
        }
    };
});
