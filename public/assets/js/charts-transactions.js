/**
 * Charts Apex
 */

'use strict';

(function () {
    let cardColor, headingColor, labelColor, borderColor, legendColor;

    if (isDarkStyle) {
        cardColor = config.colors_dark.cardColor;
        headingColor = config.colors_dark.headingColor;
        labelColor = config.colors_dark.textMuted;
        legendColor = config.colors_dark.bodyColor;
        borderColor = config.colors_dark.borderColor;
    } else {
        cardColor = config.colors.cardColor;
        headingColor = config.colors.headingColor;
        labelColor = config.colors.textMuted;
        legendColor = config.colors.bodyColor;
        borderColor = config.colors.borderColor;
    }

    // Line Area Chart
    // --------------------------------------------------------------------
    const areaChartEl = document.querySelector('#lineAreaChartTransaction'),
        areaChartConfig = {
            chart: {
                height: 400,
                type: 'area',
                parentHeightOffset: 0,
                toolbar: {
                    show: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: false,
                curve: 'straight'
            },
            legend: {
                show: true,
                position: 'top',
                horizontalAlign: 'start',
                labels: {
                    colors: legendColor,
                    useSeriesColors: false
                }
            },
            grid: {
                borderColor: borderColor,
                xaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            colors: ['#4bc081', '#ea5455', '#ffad5f'],
            series: [
                {
                    name: 'Berhasil',
                    data: [100, 120, 90, 170, 130, 160, 140, 240, 220, 180, 270, 280, 375]
                },
                {
                    name: 'Gagal',
                    data: [60, 80, 70, 110, 80, 100, 90, 180, 160, 140, 200, 220, 275]
                },
                {
                    name: 'Pending',
                    data: [20, 40, 30, 70, 40, 60, 50, 140, 120, 100, 140, 180, 220]
                }
            ],
            xaxis: {
                categories: [
                    '7/12',
                    '8/12',
                    '9/12',
                    '10/12',
                    '11/12',
                    '12/12',
                    '13/12',
                    '14/12',
                    '15/12',
                    '16/12',
                    '17/12',
                    '18/12',
                    '19/12',
                    '20/12'
                ],
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    style: {
                        colors: labelColor,
                        fontSize: '13px'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: labelColor,
                        fontSize: '13px'
                    }
                }
            },
            fill: {
                opacity: 1,
                type: 'solid'
            },
            tooltip: {
                shared: false
            }
        };
    if (typeof areaChartEl !== undefined && areaChartEl !== null) {
        const areaChart = new ApexCharts(areaChartEl, areaChartConfig);
        areaChart.render();
    }
})();
