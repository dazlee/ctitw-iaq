define(["client/components/multiple-stats-table",
        "client/components/multiple-stats-chart",
        "api-configs"], function (
            MultipleStatsTable, MultipleStatsChart, apiConfigs) {

    return {
        initialize: function () {
            var endpoint = apiConfigs.endpoints.devices;
            MultipleStatsTable.initialize(endpoint, {
                min_max_avg: 1,
                device_level: 1,
            });
            MultipleStatsChart.initialize(endpoint, {});
        }
    };
});
