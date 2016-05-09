define(["client/components/realtime-info-board",
        "api-configs"], function (
            RealtimeInfoBoard, apiConfigs) {

    return {
        initialize: function () {
            var endpoint = apiConfigs.endpoints.statsSummary;
            var queries = {
                avg: 1,
            };
            RealtimeInfoBoard.initialize(endpoint, queries);
        }
    };
});
