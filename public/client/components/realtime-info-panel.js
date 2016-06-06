define(["chartConfigs",
        "lodash",
        "fetch-utils",
        "device-utils"], function (chartConfigs, _, fetchUtils, deviceUtils) {

    var _api, _endpoint, _queries, _responseKey;
    var _co2Threshold, _co2Value, _co2Number;
    var _tempThreshold, _tempValue, _tempNumber;
    var _rhThreshold, _rhValue, _rhNumber;

    function initializeViews() {
        var co2Panel = document.querySelector("#realtime-info-co2-panel");
        _co2Threshold = parseFloat(co2Panel.dataset.co2Threshold);
        _co2Value = co2Panel.querySelector(".value");
        _co2Number = _co2Value.querySelector(".number");

        var tempPanel = document.querySelector("#realtime-info-temp-panel");
        _tempThreshold = parseFloat(tempPanel.dataset.tempThreshold);
        _tempValue = tempPanel.querySelector(".value");
        _tempNumber = _tempValue.querySelector(".number");

        var rhPanel = document.querySelector("#realtime-info-rh-panel");
        _rhThreshold = parseFloat(rhPanel.dataset.rhThreshold);
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

        if (currentData.co2 > _co2Threshold) {
            _co2Value.classList.add('fg-red');
        } else {
            _co2Value.classList.remove('fg-red');
        }
        if (currentData.temp > _tempThreshold) {
            _tempValue.classList.add('fg-red');
        } else {
            _tempValue.classList.remove('fg-red');
        }
        if (currentData.rh > _rhThreshold) {
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
