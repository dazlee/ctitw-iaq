define(["chartConfigs",
        "lodash",
        "fetch-utils",
        "device-utils"], function (chartConfigs, _, fetchUtils, deviceUtils) {

    var _deviceId;
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
        fetchUtils.fetchJSON("/api/devices/" + _deviceId + "?row=-1", {
            Accept: "application/json"
        })
        .then(function (json) {
            refreshTableLayout(json.data);
        });
    }
    function initializeChart() {
        fetchUtils.fetchJSON("/api/devices/" + _deviceId, {
            Accept: "application/json"
        })
        .then(function (json) {
            drawChart(json.data, chartConfigs.outline);
        });

        // [TODO] should update every 10 mins
        setInterval(function () {
            refreshChart();
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

        _co2Number.innerHTML = currentData.co2;
        _tempNumber.innerHTML = currentData.temp;
        _rhNumber.innerHTML = currentData.rh;
    }
    function refreshChart() {
        fetchUtils.fetchJSON("/api/devices/" + _deviceId, {
            Accept: "application/json"
        })
        .then(function (json) {
            $('#realtimechart').highcharts().destroy();
            drawChart(json.data, chartConfigs.outline);
        });
    }
    function drawChart(data, chartOptions) {
        var deviceData = deviceUtils.parseData(data);

        var series = deviceUtils.generateChartSeries(deviceData);
        var options = {};
        _.extend(options, chartOptions, {
            series: series,
            title: {
                text: "即時資訊"
            }
        });
        $('#realtimechart').highcharts(options);
    }

    return {
        initialize: function (deviceId) {
            _deviceId = deviceId;

            initializeViews();
            initializeChart();
            initializePanels();
        }
    };
});
