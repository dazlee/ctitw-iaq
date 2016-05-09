define(["client/components/realtime-info-board",
        "api-configs"], function (
            RealtimeInfoBoard, apiConfigs) {

    return {
        initialize: function () {
            var endpoint = apiConfigs.endpoints.devices;
            var queries = {
                action: "avg",
                row: -1,
            };
            RealtimeInfoBoard.initialize(endpoint, queries);
        }
    };
});
