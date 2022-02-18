<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
    
    function showDashMRoomSum() {	
        var yearName = "dmr_summary_year";
        var monthName = "dmr_summary_month";

        var mainLayout = mainTab.cells("dashboard_meeting_room_summary").attachLayout({
            pattern: "2U",
            cells: [
                {id: "a", header : false},
                {id: "b", header : false},
            ]
        });

        var leftLayout = mainLayout.cells("a").attachLayout({
            pattern: "2E",
            cells: [
                {id: "a", header : false},
                {id: "b", header : false},
            ]
        });

        var rightLayout = mainLayout.cells("b").attachLayout({
            pattern: "2E",
            cells: [
                {id: "a", header : false},
                {id: "b", header : false},
            ]
        });

        var summaryMenu = mainTab.cells("dashboard_meeting_room_summary").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "month", text: genSelectMonth(yearName, monthName)},
                {id: "refresh", text: "Resize", img: "resize.png"},
                {id: "mr_data", text: "Data Reservasi R.Meeting", img: "app18.png"}
            ]
        });

        $("#"+yearName).on("change", function() {
            loadChart();
        });

        $("#"+monthName).on("change", function() {
            loadChart();
        });

        summaryMenu.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    loadChart();
                    break;
                case "mr_data":
                    var year = $("#"+yearName).val();
                    var month = $("#"+monthName).val();
                    if(!mainTab.tabs("dashboard_meeting_room_summary_data_tab_" + nameOfMonth(month))){
                        mainTab.addTab("dashboard_meeting_room_summary_data_tab_" + nameOfMonth(month), tabsStyle("app18.png", "Data Ruang Meeting " + nameOfMonth(month) + " " + year, "background-size: 16px 16px"), null, null, true, true);
                        showDashMRoomSumData("dashboard_meeting_room_summary_data_tab_" + nameOfMonth(month), year, month);
                    } else {
                        mainTab.tabs("dashboard_meeting_room_summary_data_tab_" + nameOfMonth(month)).setActive();
                    }
                    break;
            }
        });

        leftLayout.cells("a").attachHTMLString("<div class='hc_graph' style='width:100%;height:100%' id='dmr_top_left'></div>");
        leftLayout.cells("b").attachHTMLString("<div class='hc_graph' style='width:100%;height:100%' id='dmr_bottom_left'></div>");
        rightLayout.cells("a").attachHTMLString("<div class='hc_graph' style='width:100%;height:100%' id='dmr_top_right'></div>");
        rightLayout.cells("b").attachHTMLString("<div class='hc_graph' style='width:100%;height:100%' id='dmr_bottom_right'></div>");

        function loadChart() {
        
            let year = $("#"+yearName).val();
            let month = $("#"+monthName).val();

            leftLayout.cells("a").progressOn();
            reqJson(DashMRoom("getTotalByColumn", {
                month_start_date: month, 
                year_start_date: year,
                equal_status: "CLOSED",
                column: "total_rev"
            }), "POST", null, (err, res) => {
                if(res.status === "success") {
                    dashPieChart("dmr_top_left", "Total Reservasi " + nameOfMonth(month), "Total Reservasi", res.series, res.color);
                    leftLayout.cells("a").progressOff();
                }
            });

            rightLayout.cells("a").progressOn();
            reqJson(DashMRoom("getTotalByColumn", {
                month_start_date: month, 
                year_start_date: year,
                equal_status: "CLOSED",
                column: "total_hour"
            }), "POST", null, (err, res) => {
                if(res.status === "success") {
                    dashPieChart("dmr_top_right", "Total Jam " + nameOfMonth(month), "Total Jam", res.series, res.color);
                    rightLayout.cells("a").progressOff();
                }
            });

            rightLayout.cells("b").progressOn();
            reqJson(DashMRoom("getTotalBySnack", {
                month_start_date: month, 
                year_start_date: year,
                equal_status: "CLOSED",
                column: "total_hour"
            }), "POST", null, (err, res) => {
                if(res.status === "success") {
                    Highcharts.chart('dmr_bottom_right', {
                        chart: {
                            type: "line"
                        },
                        title: {
                            text: "Total Biaya Snack " + nameOfMonth(month)
                        },
                        xAxis: {
                            categories: res.categories
                        },
                        yAxis: {
                            title: {
                                text: 'Tota Biaya Snack'
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
                    rightLayout.cells("b").progressOff();
                }
            });

            function roomCount() {
                sumGridToElement(roomGrid, 2, "dmr_sum_rmr_total_rev", null, "int");
                sumGridToElement(roomGrid, 3, "dmr_sum_rmr_total_person", null, "int");
                sumGridToElement(roomGrid, 4, "dmr_sum_rmr_total_hour", null, "float");
                sumGridToElement(roomGrid, 5, "dmr_sum_rmr_total_snack", null, "money");
            }

            leftLayout.cells("b").progressOn();
            var roomGrid =  leftLayout.cells("b").attachGrid();
            roomGrid.setHeader("No,Nama Ruang Meeting,Total Reservasi,Total Peserta,Total Jam,Biaya Snack");
            roomGrid.setColSorting("str,str,str,str,str,str");
            roomGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
            roomGrid.setColAlign("center,left,left,left,left,left");
            roomGrid.setInitWidthsP("5,25,15,15,15,25");
            roomGrid.enableSmartRendering(true);
            roomGrid.attachEvent("onXLE", function() {
                leftLayout.cells("b").progressOff();
            });
            roomGrid.attachFooter(",Total,<span id='dmr_sum_rmr_total_rev'>0</span>,<span id='dmr_sum_rmr_total_person'>0</span> Orang,<span id='dmr_sum_rmr_total_hour'>0</span> Jam,Rp. <span id='dmr_sum_rmr_total_snack'>0</span>");
            roomGrid.init();

            roomGrid.clearAndLoad(GAOther("getMeetingRevGroupGrid", {
                month_start_date: month, 
                year_start_date: year, 
                equal_status: "CLOSED"
            }), roomCount);
        }

        function dashPieChart(element, title, legend, series, customColor) {
            Highcharts.chart(element, {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: title
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
                        colors: customColor,
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    name: legend,
                    colorByPoint: true,
                    data: series
                }]
            });
        }

        loadChart();
    }

JS;
header('Content-Type: application/javascript');
echo $script;