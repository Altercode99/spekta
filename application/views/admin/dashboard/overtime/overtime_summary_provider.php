<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
    
    function showDashboardOvertimeSummaryProvider() {	
        var type = "line";
        var yearName = "dash_year_summary_provider";
        var monthName = "dash_month_summary_provider";

        var summaryLayout = mainTab.cells("dashboard_overtime_summary_provider_tab").attachLayout({
            pattern: "3T",
            cells: [
                {id: "a", header: false},
                {id: "b", header: false},
                {id: "c", header: false},
            ]
        });

        var summaryMenu =  mainTab.cells("dashboard_overtime_summary_provider_tab").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "month", text: genSelectMonth(yearName, monthName)},
                {id: "line", text: "Line Chart", img: "double_chart.png"},
                {id: "column", text: "Bar Chart", img: "bar_chart.png"},
                {id: "refresh", text: "Refresh", img: "resize.png"},
                {id: "data_overtime", text: "Data Lemburan", img: "app18.png"}
            ]
        });

        summaryMenu.attachEvent("onClick", function(id) {
            switch (id) {
                case "data_overtime":
                    let year = $("#"+yearName).val();
                    let month = $("#"+monthName).val();
                    if(!mainTab.tabs("dashboard_overtime_summary_provider_data_tab_" + nameOfMonth(month))){
                        mainTab.addTab("dashboard_overtime_summary_provider_data_tab_" + nameOfMonth(month), tabsStyle("app18.png", "Data Penyelenggara Lembur " + nameOfMonth(month), "background-size: 16px 16px"), null, null, true, true);
                        showSummaryOvertimeProviderData("dashboard_overtime_summary_provider_data_tab_" + nameOfMonth(month), year, month);
                    } else {
                        mainTab.tabs("dashboard_overtime_summary_provider_data_tab_" + nameOfMonth(month)).setActive();
                    }
                    break;
                case "refresh":
                    loadMainChart();
                    rSubOvtGrid();
                    loadTop5Sub();
                    break;
                case "line":
                    type = "line";
                    loadMainChart();
                    break;
                case "column":
                    type = "column";
                    loadMainChart();
                    break;
            }
        });

        summaryLayout.cells("a").attachHTMLString("<div class='hc_graph' id='monthly_summary_provider' style='height:100%;width:100%;'></div>");
        summaryLayout.cells("c").attachHTMLString("<div class='hc_graph' id='top_5_summary_provider' style='height:100%;width:100%;'></div>");

        $("#"+yearName).on("change", function() {
            loadMainChart();
            rSubOvtGrid();
            loadTop5Sub();
        });

        $("#"+monthName).on("change", function() {
            loadMainChart();
            rSubOvtGrid();
            loadTop5Sub();
        });

        function loadMainChart() {
            let year = $("#"+yearName).val();
            let month = $("#"+monthName).val();
            summaryLayout.cells("a").progressOn();
            reqJson(Dashboard("getSummaryPersonil", {
                    month_overtime_date: month, 
                    year_overtime_date: year, 
                    equal_status: "CLOSED"
                }), "POST", {year, month}, (err, res) => {
                summaryLayout.cells("a").progressOff();
                Highcharts.chart('monthly_summary_provider', {
                    chart: {
                        type: 'line'
                    },
                    title: {
                        text: "Lembur Bulan " + nameOfMonth(month)
                    },
                    xAxis: {
                        categories: res.categories
                    },
                    yAxis: {
                        title: {
                            text: 'Tota Biaya Lembur'
                        }
                    },
                    plotOptions: {
                        line: {
                            dataLabels: {
                                enabled: true
                            },
                            enableMouseTracking: true
                        }
                    },
                    series: res.series
                });
            });
        }

        loadMainChart();

        function subGridCount() {
            sumGridToElement(subOvtGrid, 3, "total_dash_sub_provider");
        }

        var subOvtGrid =  summaryLayout.cells("b").attachGrid();
        subOvtGrid.setHeader("No,Nama Bagian Penyelenggara Lembur,Jam Lembur,Nominal");
        subOvtGrid.setColSorting("str,str,str,str");
        subOvtGrid.setColTypes("rotxt,rotxt,rotxt,rotxt");
        subOvtGrid.setColAlign("center,left,left,left");
        subOvtGrid.setInitWidthsP("5,50,20,25");
        subOvtGrid.enableSmartRendering(true);
        subOvtGrid.attachEvent("onXLE", function() {
            summaryLayout.cells("b").progressOff();
        });
        subOvtGrid.attachFooter(",Total Biaya Lembur,#stat_total,<div id='total_dash_sub_provider'>0</div>");
        subOvtGrid.init();

        function rSubOvtGrid() {
            let year = $("#"+yearName).val();
            let month = $("#"+monthName).val();
            summaryLayout.cells("b").progressOn();
            subOvtGrid.clearAndLoad(Dashboard("getSubOvtGrid", {
                wherejoin_ovt_sub_department: 'b.id',
                groupby_ovt_sub_department: true, 
                month_overtime_date: month, 
                year_overtime_date: year, 
                equal_status: "CLOSED"}), subGridCount);
        };

        rSubOvtGrid();

        function loadTop5Sub() {
            var year = $("#"+yearName).val();
            var month = $("#"+monthName).val();
            summaryLayout.cells("c").progressOn();
            reqJson(Dashboard("getTop5Sub", {
                    wherejoin_ovt_sub_department: 'b.id',
                    groupby_ovt_sub_department: true, 
                    month_overtime_date: month, 
                    year_overtime_date: year, 
                    equal_status: "CLOSED"}), "POST", {}, (err, res) => {
                summaryLayout.cells("c").progressOff();
                Highcharts.chart('top_5_summary_provider', {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                    },
                    title: {
                        text: 'TOP 5 Bagian Penyelenggara Lembur ' + nameOfMonth(month)
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                    },
                    accessibility: {
                        point: {
                            valueSuffix: '%'
                        }
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                            }
                        }
                    },
                    series: [{
                        name: 'Lembur',
                        colorByPoint: true,
                        data: res.series
                    }]
                });
            });
        }

        loadTop5Sub();
    }

JS;
header('Content-Type: application/javascript');
echo $script;