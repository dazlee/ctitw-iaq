require.config({
    paths: {
        "client": "../client",
        "lib": "../client/lib",
        "underscore": "../client/lib/underscore/underscore.min",
        "fetch-utils": "../client/lib/fetch-utils",
        "device-utils": "../client/lib/device-utils",
        "utils": "../client/lib/utils",
    }
});

require(["client/dashboard"], function (Dashboard) {
    Dashboard.initialize();
});
