define(["chartConfigs",
        "lodash",
        "fetch-utils",
        "device-utils"], function (chartConfigs, _, fetchUtils, deviceUtils) {

    var _api;
    var _co2Value, _co2Number;
    var _tempValue, _tempNumber;
    var _rhValue, _rhNumber;

    function initializeViews() {
        _co2Value = document.querySelector("#co2-panel .value");
        _co2Number = _co2Value.querySelector(".number");

        _tempValue = document.querySelector("#temp-panel .value");
        _tempNumber = _tempValue.querySelector(".number");

        _rhValue = document.querySelector("#rh-panel .value");
        _rhNumber = _rhValue.querySelector(".number");
    }
    function initializePanels() {
        fetchUtils.fetchJSON(_api, {
            Accept: "application/json"
        })
        .then(function (json) {
            refreshTableLayout(json.avg || json.data);
        });

        setInterval(function () {
            initializePanels();
        }, 2000);
    }
    function refreshTableLayout (currentData) {
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
        initialize: function (endpoint, queries) {
            _api = fetchUtils.formUrl(endpoint, queries);

            initializeViews();
            initializePanels();
        }
    };
});
