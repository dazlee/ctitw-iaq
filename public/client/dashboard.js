define(["client/components/realtime-info-board",
        "client/components/realtime-info-panel",
        "client/components/realtime-info-chart",
        "client/components/history-stats-board",
        "client/components/history-chart-board",
        "api-configs"], function (
            RealtimeInfoBoard,
            RealtimeInfoPanel, RealtimeInfoChart,
            HistoryStatsBoard, HistoryChartBoard,
            apiConfigs) {

    return {
        initialize: function (deviceId) {
            var endpoint = apiConfigs.endpoints.devices + deviceId;
            RealtimeInfoPanel.initialize(endpoint, {
                latest: 1,
            });
            RealtimeInfoChart.initialize(endpoint, {});
            HistoryStatsBoard.initialize(endpoint, {
                summary: 1,
            });
            HistoryChartBoard.initialize(endpoint);
        }
    };
});
