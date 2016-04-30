define(function () {
    return {
        initialize: function (deviceData) {
            // initialize date range checker
            $('.input-daterange input').each(function() {
                $(this).datepicker();
            });

            $('#unit-selector a').click(function (e) {
                e.preventDefault();
                $(this).tab('show');
            });

            // initialize chart
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
                series: [{
                    name: '二氧化碳',
                    yAxis: 0,
                    data: deviceData.co2,
                    tooltip: {
                        valueDecimals: 2,
                        valueSuffix: ' ppm'
                    },
                    color: Highcharts.getOptions().colors[0]
                },
                {
                    name: '溫度',
                    yAxis: 1,
                    data: deviceData.temp,
                    tooltip: {
                        valueDecimals: 2,
                        valueSuffix: ' °C'
                    },
                    color: Highcharts.getOptions().colors[3]

                },
                {
                    name: '濕度',
                    yAxis: 2,
                    data: deviceData.rh,
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
