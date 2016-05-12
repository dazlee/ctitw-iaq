define(["client/components/multiple-stats-table",
        "client/components/multiple-stats-chart",
        "api-configs"], function (
            MultipleStatsTable, MultipleStatsChart, apiConfigs) {

    return {
        initialize: function () {
            var endpoint = apiConfigs.endpoints.devices;
            MultipleStatsTable.initialize(endpoint, {
                min_avg_max: 1,
                device_level: 1,
                nodata: 1,
            }, "min_avg_max");
            MultipleStatsChart.initialize(endpoint);
        }
    };
});
