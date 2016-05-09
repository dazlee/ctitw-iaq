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
            HistoryStatsBoard.initialize(endpoint);
            HistoryChartBoard.initialize(endpoint);
        }
    };
});
