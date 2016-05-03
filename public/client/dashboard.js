define(["client/components/realtime-info-board",
        "client/components/history-stats-board",
        "client/components/history-chart-board"], function (
            RealtimeInfoBoard,
            HistoryStatsBoard,
            HistoryChartBoard) {

    return {
        initialize: function () {
            RealtimeInfoBoard.initialize();
            HistoryStatsBoard.initialize();
            HistoryChartBoard.initialize();
        }
    };
});
