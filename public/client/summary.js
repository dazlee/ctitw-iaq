define(["client/components/realtime-info-panel",
        "client/components/realtime-info-chart",
        "api-configs"], function (
            RealtimeInfoPanel, RealtimeInfoChart, apiConfigs) {

    return {
        initialize: function () {
            var endpoint = apiConfigs.endpoints.devices;
            RealtimeInfoPanel.initialize(endpoint, {
                avg: 1,
                nodata: 1,
            });
            RealtimeInfoChart.initialize(endpoint, {
                avg: 1,
                timestamp: 1,
            });
        }
    };
});
