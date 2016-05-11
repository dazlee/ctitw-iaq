define(["client/components/single-stats-table",
        "client/components/history-chart-board",
        "api-configs"], function (
            SingleStatsTable,
            HistoryChartBoard,
            apiConfigs) {

    return {
        initialize: function () {
            var endpoint = apiConfigs.endpoints.statsSummary;
            var queries = {
                summary: 1,
            };
            SingleStatsTable.initialize(endpoint, queries);
            HistoryChartBoard.initialize(endpoint);
        }
    };
});
