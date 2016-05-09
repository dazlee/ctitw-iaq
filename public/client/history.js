define(["client/components/history-stats-board",
        "client/components/history-chart-board",
        "api-configs"], function (
            HistoryStatsBoard,
            HistoryChartBoard,
            apiConfigs) {

    return {
        initialize: function () {
            var endpoint = apiConfigs.endpoints.statsSummary;
            var queries = {
                summary: 1,
            };
            HistoryStatsBoard.initialize(endpoint, queries);
            HistoryChartBoard.initialize(endpoint);
        }
    };
});
