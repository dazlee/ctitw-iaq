define(["client/components/single-stats-table",
        "client/components/single-stats-chart",
        "api-configs"], function (
            SingleStatsTable,
            SingleStatsChart,
            apiConfigs) {

    return {
        initialize: function () {
            var endpoint = apiConfigs.endpoints.devices;
            SingleStatsTable.initialize(endpoint, {
                summary: 1,
                nodata: 1,
            }, "summary");
            SingleStatsChart.initialize(endpoint);
        }
    };
});
