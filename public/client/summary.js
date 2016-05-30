define(["client/components/realtime-info-panel",
        "client/components/realtime-info-chart",
        "api-configs"], function (
            RealtimeInfoPanel, RealtimeInfoChart, apiConfigs) {

    return {
        initialize: function (deviceAccount) {
            var endpoint = apiConfigs.endpoints.devices;
            RealtimeInfoPanel.initialize(endpoint, {
                avg: 1,
                nodata: 1,
                deviceAccount: deviceAccount,
            }, "avg");
            RealtimeInfoChart.initialize(endpoint, {
                avg: 1,
                timestamp: 1,
                nodata: 1,
                deviceAccount: deviceAccount,
            }, "avg");
        }
    };
});
