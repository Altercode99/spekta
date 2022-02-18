<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
    
    function showDashVehicleSum() {	
        var yearName = "vhc_summary_year";
        var monthName = "vhc_summary_month";

        var mainLayout = mainTab.cells("dashboard_vehicle_summary").attachLayout({
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

        var summaryMenu = mainTab.cells("dashboard_vehicle_summary").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "month", text: genSelectMonth(yearName, monthName)},
                {id: "refresh", text: "Resize", img: "resize.png"},
                {id: "vehicle_data", text: "Data Reservasi Kendaraan", img: "app18.png"}
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
                case "vehicle_data":
                    var year = $("#"+yearName).val();
                    var month = $("#"+monthName).val();
                    if(!mainTab.tabs("dashboard_vehicle_summary_data_tab_" + nameOfMonth(month))){
                        mainTab.addTab("dashboard_vehicle_summary_data_tab_" + nameOfMonth(month), tabsStyle("app18.png", "Data Kendaraan Dinas " + nameOfMonth(month) + " " + year, "background-size: 16px 16px"), null, null, true, true);
                        showDashVehicleSumData("dashboard_vehicle_summary_data_tab_" + nameOfMonth(month), year, month);
                    } else {
                        mainTab.tabs("dashboard_vehicle_summary_data_tab_" + nameOfMonth(month)).setActive();
                    }
                    break;
            }
        });

        leftLayout.cells("a").attachHTMLString("<div class='hc_graph' style='width:100%;height:100%' id='vhc_top_left'></div>");
        leftLayout.cells("b").attachHTMLString("<div class='hc_graph' style='width:100%;height:100%' id='vhc_bottom_left'></div>");
        rightLayout.cells("a").attachHTMLString("<div class='hc_graph' style='width:100%;height:100%' id='vhc_top_right'></div>");
        rightLayout.cells("b").attachHTMLString("<div class='hc_graph' style='width:100%;height:100%' id='vhc_bottom_right'></div>");

        function loadChart() {
            let year = $("#"+yearName).val();
            let month = $("#"+monthName).val();

            leftLayout.cells("a").progressOn();
            reqJson(DashVehicle("getTotalByColumn", {
                month_start_date: month, 
                year_start_date: year,
                equal_status: "CLOSED",
                column: "total_rev"
            }), "POST", null, (err, res) => {
                if(res.status === "success") {
                    dashPieChart("vhc_top_left", "Total Reservasi " + nameOfMonth(month), "Total Reservasi", res.series, res.color);
                    leftLayout.cells("a").progressOff();
                }
            });

            rightLayout.cells("a").progressOn();
            reqJson(DashVehicle("getTotalByColumn", {
                month_start_date: month, 
                year_start_date: year,
                equal_status: "CLOSED",
                column: "total_hour"
            }), "POST", null, (err, res) => {
                if(res.status === "success") {
                    dashPieChart("vhc_top_right", "Total Jam " + nameOfMonth(month), "Total Jam", res.series, res.color);
                    rightLayout.cells("a").progressOff();
                }
            });

            rightLayout.cells("b").progressOn();
            reqJson(DashVehicle("getTotalByColumn", {
                month_start_date: month, 
                year_start_date: year,
                equal_status: "CLOSED",
                column: "total_km"
            }), "POST", null, (err, res) => {
                if(res.status === "success") {
                    if(res.status === "success") {
                        dashPieChart("vhc_bottom_right", "Total Jarak " + nameOfMonth(month), "Total Jarak", res.series, res.color);
                        rightLayout.cells("b").progressOff();
                    }
                }
            });


            function vhcCount() {
                sumGridToElement(vhcDashGrid, 2, "vhc_dash_sum_total_rev", null, "int");
                sumGridToElement(vhcDashGrid, 3, "vhc_dash_sum_total_hour", null, "float");
                sumGridToElement(vhcDashGrid, 4, "vhc_dash_sum_total_km", null, "float");
            }

            leftLayout.cells("b").progressOn();
            var vhcDashGrid =  leftLayout.cells("b").attachGrid();
            vhcDashGrid.setHeader("No,Nama Kendaraan Dinas,Total Reservasi,Total Jam,Total Kilometer");
            vhcDashGrid.setColSorting("str,str,str,str,str");
            vhcDashGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt");
            vhcDashGrid.setColAlign("center,left,left,left,left");
            vhcDashGrid.setInitWidthsP("5,25,20,25,25");
            vhcDashGrid.enableSmartRendering(true);
            vhcDashGrid.attachEvent("onXLE", function() {
                leftLayout.cells("b").progressOff();
            });
            vhcDashGrid.attachFooter(",Total,<span id='vhc_dash_sum_total_rev'>0</span>,<span id='vhc_dash_sum_total_hour'>0</span> Jam,<span id='vhc_dash_sum_total_km'>0</span> KM");
            vhcDashGrid.init();

            vhcDashGrid.clearAndLoad(GAOther("getVehicleRevGroupGrid", {
                month_start_date: month, 
                year_start_date: year, 
                equal_status: "CLOSED"
            }), vhcCount);
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