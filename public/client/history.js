define(["client/components/single-stats-table",
        "client/components/single-stats-chart",
        "api-configs"], function (
            SingleStatsTable,
            SingleStatsChart,
            apiConfigs) {

    return {
        initialize: function (deviceAccount) {
            var endpoint = apiConfigs.endpoints.devices;
            SingleStatsTable.initialize(endpoint, {
                summary: 1,
                nodata: 1,
                deviceAccount: deviceAccount,
            }, "summary");
            SingleStatsChart.initialize(endpoint, {
                deviceAccount: deviceAccount,
            });
        }
    };
});
