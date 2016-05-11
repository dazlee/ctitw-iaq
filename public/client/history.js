define(["client/components/single-stats-table",
        "client/components/single-stats-chart",
        "api-configs"], function (
            SingleStatsTable,
            SingleStatsChart,
            apiConfigs) {

    return {
        initialize: function () {
            var endpoint = apiConfigs.endpoints.statsSummary;
            var queries = {
                summary: 1,
            };
            SingleStatsTable.initialize(endpoint, queries);
            SingleStatsChart.initialize(endpoint);
        }
    };
});
