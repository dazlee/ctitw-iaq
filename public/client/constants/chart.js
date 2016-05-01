define(function() {
    return {
        outline: {
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
        },
        series0: {
            name: 'series0',
            yAxis: 0,
            data: [],
            tooltip: {
                valueDecimals: 2,
                valueSuffix: ''
            },
            color: Highcharts.getOptions().colors[0]
        },
        series1: {
            name: 'series1',
            yAxis: 1,
            data: [],
            tooltip: {
                valueDecimals: 2,
                valueSuffix: ''
            },
            color: Highcharts.getOptions().colors[3]
        },
        series2: {
            name: 'series2',
            yAxis: 2,
            data: [],
            tooltip: {
                valueDecimals: 2,
                valueSuffix: ''
            },
            color: Highcharts.getOptions().colors[2]
        },
    };
});
