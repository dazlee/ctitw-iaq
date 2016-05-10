define(["client/components/realtime-info-board",
        "client/components/realtime-info-panel",
        "api-configs"], function (
            RealtimeInfoBoard, RealtimeInfoPanel, apiConfigs) {

    return {
        initialize: function () {
            var endpoint = apiConfigs.endpoints.devices;
            var queries = {
                avg: 1,
            };
            RealtimeInfoPanel.initialize(endpoint, queries);
            queries = {
                avg: 1,
                timestamp: 1,
            };
            // RealtimeInfoBoard.initialize(endpoint, queries);
        }
    };
});
