require.config({
    paths: {
        "client": "/client",
        "lib": "/client/lib",
        "chartConfigs": "/client/constants/chart",
        "underscore": "/client/lib/underscore/underscore.min",
        "moment": "/client/lib/moment/moment.min",
        "curry": "/client/lib/curry/curry.min",
        "date-utils": "/client/lib/date-utils",
        "fetch-utils": "/client/lib/fetch-utils",
        "device-utils": "/client/lib/device-utils",
        "utils": "/client/lib/utils",
    }
});

require(["client/current-summary"], function (CurrentSummary) {
    CurrentSummary.initialize();
});
