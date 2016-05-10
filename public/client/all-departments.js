define(["client/components/multiple-stats-board",
        "api-configs"], function (
            MultipleStatsBoard, apiConfigs) {

    return {
        initialize: function () {
            var endpoint = apiConfigs.endpoints.devices;
            MultipleStatsBoard.initialize(endpoint, {
                min_max_avg: 1,
                device_level: 1,
            });
        }
    };
});
