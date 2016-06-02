define(["client/components/realtime-info-panel",
        "client/components/realtime-info-chart",
        "client/components/single-stats-table",
        "client/components/single-stats-chart",
        "api-configs"], function (
            RealtimeInfoPanel, RealtimeInfoChart,
            SingleStatsTable, SingleStatsChart,
            apiConfigs) {

    return {
        initialize: function (deviceId) {
            var endpoint = apiConfigs.endpoints.devices + deviceId;
            RealtimeInfoPanel.initialize(endpoint, {
                latest: 1,
                nodata: 1,
            }, "latest");
            RealtimeInfoChart.initialize(endpoint);
            SingleStatsTable.initialize(endpoint, {
                summary: 1,
                nodata: 1,
            }, "summary");
            SingleStatsChart.initialize(endpoint);
        }
    };
});
