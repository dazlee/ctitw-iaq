define(["client/components/history-stats-board",
        "client/components/history-chart-board"], function (
            HistoryStatsBoard,
            HistoryChartBoard) {

    return {
        initialize: function () {
            HistoryStatsBoard.initialize();
            HistoryChartBoard.initialize();
        }
    };
});
