define(["client/components/realtime-info-board",
        "client/components/history-stats-board",
        "client/components/history-chart-board",
        "api-configs"], function (
            RealtimeInfoBoard,
            HistoryStatsBoard,
            HistoryChartBoard,
            apiConfigs) {

    return {
        initialize: function (deviceId) {
            var endpoint = apiConfigs.endpoints.devices + deviceId;
            var queries = {
                latest: 1,
            };
            RealtimeInfoBoard.initialize(endpoint, queries);

            queries = {
                summary: 1,
            };
            HistoryStatsBoard.initialize(endpoint, queries);
            HistoryChartBoard.initialize(endpoint);
        }
    };
});
