require.config({
    paths: {
        "client": "/client",
        "lib": "/client/lib",
        "chartConfigs": "/client/constants/chart",
        "lodash": "/client/lib/lodash/lodash.min",
        "ramda": "/client/lib/ramda/ramda.min",
        "moment": "/client/lib/moment/moment.min",
        "date-utils": "/client/lib/date-utils",
        "fetch-utils": "/client/lib/fetch-utils",
        "device-utils": "/client/lib/device-utils",
        "utils": "/client/lib/utils",
    }
});

require(["client/history"], function (History) {
    History.initialize();
});
