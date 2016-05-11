define(["client/components/realtime-info-panel",
        "client/components/realtime-info-chart",
        "client/components/single-stats-table",
        "client/components/history-chart-board",
        "api-configs"], function (
            RealtimeInfoPanel, RealtimeInfoChart,
            SingleStatsTable, HistoryChartBoard,
            apiConfigs) {

    return {
        initialize: function (deviceId) {
            var endpoint = apiConfigs.endpoints.devices + deviceId;
            RealtimeInfoPanel.initialize(endpoint, {
                latest: 1,
            });
            RealtimeInfoChart.initialize(endpoint, {});
            SingleStatsTable.initialize(endpoint, {
                summary: 1,
            });
            HistoryChartBoard.initialize(endpoint);
        }
    };
});
