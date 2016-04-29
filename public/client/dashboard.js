define(function () {
    return {
        initialize: function () {
            $('#historychart').highcharts({
                chart: {
                    spacingBottom: 50,
                    zoomType: "x",
                },
                exporting: { enabled: false },
                title: {
                    text: '歷史紀錄'
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    crosshair: true,
                    type: 'datetime',
                    labels: {
                        formatter: function() {
                            var datetimeStr = Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.value);
                            return datetimeStr;
                        }
                    }
                },
                yAxis: [{ // Primary yAxis
                    labels: {
                        format: '{value} ppm',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    title: {
                        text: '二氧化碳',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    opposite: true

                }, { // Secondary yAxis
                    gridLineWidth: 0,
                    title: {
                        text: '溫度',
                        style: {
                            color: Highcharts.getOptions().colors[3]
                        }
                    },
                    labels: {
                        format: '{value} °C',
                        style: {
                            color: Highcharts.getOptions().colors[3]
                        }
                    }

                }, { // Tertiary yAxis
                    gridLineWidth: 0,
                    title: {
                        text: '濕度',
                        style: {
                            color: Highcharts.getOptions().colors[2]
                        }
                    },
                    labels: {
                        format: '{value} %',
                        style: {
                            color: Highcharts.getOptions().colors[2]
                        }
                    },
                    opposite: true
                }],
                tooltip: {
                    xDateFormat: "%Y-%m-%d %H:%M:%S",
                    shared: true
                },
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    x: 0,
                    verticalAlign: 'bottom',
                    y: 30,
                    floating: true,
                    backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
                },
                // tooltip: {
                //     formatter: function() {
                //         // var datetimeStr = Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x);
                //         // return datetimeStr;
                //         return 'The value for <b>' + this.x + '</b> is <b>' + this.y + '</b>, in series '+ this.series.name;
                //     },
                // },
                series: [{
                    name: '二氧化碳',
                    yAxis: 0,
                    data: [
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
                    ],
                    tooltip: {
                        valueDecimals: 2,
                        valueSuffix: ' ppm'
                    },
                    color: Highcharts.getOptions().colors[0]
                },
                {
                    name: '溫度',
                    yAxis: 1,
                    data: [
                        [1167692400000, 36.2],
                        [1167778800000, 33.2],
                        [1167865200000, 34.2],
                        [1167951600000, 35.2],
                        [1168210800000, 36.2],
                        [1168297200000, 32.2],
                        [1168383600000, 26.2],
                        [1168470000000, 25.2],
                        [1168556400000, 24.2],
                        [1168815600000, 23.2],
                        [1168902000000, 22.2],
                    ],
                    tooltip: {
                        valueDecimals: 2,
                        valueSuffix: ' °C'
                    },
                    color: Highcharts.getOptions().colors[3]

                },
                {
                    name: '濕度',
                    yAxis: 2,
                    data: [
                        [1167692400000, 60.2],
                        [1167778800000, 56.2],
                        [1167865200000, 76.2],
                        [1167951600000, 88.2],
                        [1168210800000, 78.2],
                        [1168297200000, 76.2],
                        [1168383600000, 86.2],
                        [1168470000000, 94.2],
                        [1168556400000, 56.2],
                        [1168815600000, 78.2],
                        [1168902000000, 67.2],
                    ],
                    tooltip: {
                        valueDecimals: 2,
                        valueSuffix: ' %'
                    },
                    color: Highcharts.getOptions().colors[2]

                }]
            });
        }
    };
});
