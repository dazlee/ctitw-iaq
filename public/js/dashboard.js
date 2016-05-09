require.config({
    paths: {
        "client": "../client",
        "lib": "../client/lib",
        "chartConfigs": "../client/constants/chart",
        "underscore": "../client/lib/underscore/underscore.min",
        "moment": "../client/lib/moment/moment.min",
        "curry": "../client/lib/curry/curry.min",
        "fetch-utils": "../client/lib/fetch-utils",
        "date-utils": "../client/lib/date-utils",
        "device-utils": "../client/lib/device-utils",
        "utils": "../client/lib/utils",
    }
});

require(["client/dashboard"], function (Dashboard) {
    var deviceId = document.querySelector(".dashboard").dataset.deviceId;
    Dashboard.initialize(deviceId);
});
