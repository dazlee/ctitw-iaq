//Flot Multiple Axes Line Chart
$(function() {
    var oilprices = [
        [1167692400000, 61.05],
        [1167778800000, 58.32],
        [1167865200000, 57.35],
        [1167951600000, 56.31],
        [1168210800000, 55.55],
        [1168297200000, 55.64],
        [1168383600000, 54.02],
        [1168470000000, 51.88],
        [1168556400000, 52.99],
        [1168815600000, 52.99],
        [1168902000000, 51.21],
    ];
    var exchangerates = [
        [1167606000000, 0.7580],
        [1167692400000, 0.7580],
        [1167778800000, 0.75470],
        [1167865200000, 0.75490],
        [1167951600000, 0.76130],
        [1168038000000, 0.76550],
        [1168124400000, 0.76930],
        [1168210800000, 0.76940],
        [1168297200000, 0.76880],
        [1168383600000, 0.76780],
        [1168470000000, 0.77080],
    ];
    var goldprices = [
        [1167692400000, 36.05],
        [1167778800000, 45.32],
        [1167865200000, 35.35],
        [1167951600000, 25.31],
        [1168210800000, 15.55],
        [1168297200000, 25.64],
        [1168383600000, 35.02],
        [1168470000000, 45.88],
        [1168556400000, 35.99],
        [1168815600000, 25.99],
        [1168902000000, 5.21],
    ];

    function euroFormatter(v, axis) {
        return v.toFixed(axis.tickDecimals) + "€";
    }

    function doPlot(position) {
        $.plot($("#flot-line-chart-multi"), [{
            data: oilprices,
            label: "Oil price ($)"
        }, {
            data: exchangerates,
            label: "USD/EUR exchange rate",
            yaxis: 2
        }, {
            data: goldprices,
            label: "gold rate",
            yaxis: 3
        }], {
            xaxes: [{
                mode: 'time'
            }],
            yaxes: [{
                min: 0
            }, {
                // align if we are to the right
                alignTicksWithAxis: position == "right" ? 1 : null,
                position: position,
                tickFormatter: euroFormatter
            }],
            legend: {
                position: 'sw'
            },
            grid: {
                hoverable: true //IMPORTANT! this is needed for tooltip to work
            },
            tooltip: true,
            tooltipOpts: {
                content: "%s for %x was %y",
                xDateFormat: "%y-%0m-%0d",

                onHover: function(flotItem, $tooltipEl) {
                    // console.log(flotItem, $tooltipEl);
                }
            }

        });
    }

    doPlot("right");

    $("button").click(function() {
        doPlot($(this).text());
    });
});
