require(["../client/dashboard",
         "lib/fetch-utils",
         "lib/device-utils",
         "lib/utils"], function (Dashboard, fetchUtils, deviceUtils, utils) {

    var statusCheckerJSONParser = utils.compose([fetchUtils.checkStatus, fetchUtils.parseJSON]);

    fetch("/api/devices/1", {
        method: "GET",
        headers: {
            Accept: "application/json"
        },
    })
    .then(statusCheckerJSONParser)
    .then(function(json) {
        var initData = deviceUtils.parseData(json.data);
        Dashboard.initialize(initData);
    });

});
