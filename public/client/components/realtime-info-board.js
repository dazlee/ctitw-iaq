define(["chartConfigs",
        "underscore",
        "fetch-utils",
        "device-utils"], function (chartConfigs, _, fetchUtils, deviceUtils) {

    var _deviceId;

    function initializePanels() {
        _deviceId = document.querySelector('#realtimechart').dataset.deviceId;
        if (typeof _deviceId === "undefined") return;

        var co2Panel = document.querySelector('#co2-panel');
        var co2Value = co2Panel.querySelector(".value");
        var co2Number = co2Value.querySelector(".number");

        var tempPanel = document.querySelector('#temp-panel');
        var tempValue = tempPanel.querySelector(".value");
        var tempNumber = tempValue.querySelector(".number");

        var rhPanel = document.querySelector('#rh-panel');
        var rhValue = rhPanel.querySelector(".value");
        var rhNumber = rhValue.querySelector(".number");
        fetchUtils.fetchJSON("/api/devices/" + _deviceId, {
            Accept: "application/json"
        })
        .then(function (json) {
            var currentData = json.data[json.data.length - 1];
            if (currentData.co2 > 50) {
                co2Value.classList.add('fg-red');
            } else {
                co2Value.classList.remove('fg-red');
            }
            if (currentData.temp > 20) {
                tempValue.classList.add('fg-red');
            } else {
                tempValue.classList.remove('fg-red');
            }
            if (currentData.rh > 20) {
                rhValue.classList.add('fg-red');
            } else {
                rhValue.classList.remove('fg-red');
            }

            co2Number.innerHTML = currentData.co2;
            tempNumber.innerHTML = currentData.temp;
            rhNumber.innerHTML = currentData.rh;
        });
    }
    function initializeChart() {
        _deviceId = document.querySelector('#realtimechart').dataset.deviceId;
        if (typeof _deviceId === "undefined") return;

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
    function refreshChart() {
        if (typeof _deviceId === "undefined") return;

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
        initialize: function () {
            initializeChart();
            initializePanels();
        }
    };
});
