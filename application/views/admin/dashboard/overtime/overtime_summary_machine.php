<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
    
    function showDashOvertimeMachine() {	
        var gridData;

        var type = "line";
        var summaryLayout = mainTab.cells("dashboard_overtime_machine_tab").attachLayout({
            pattern: "2E",
            cells: [
                {id: "a", header : false},
                {id: "b", header : false},
            ]
        });

        var summaryMenu = mainTab.cells("dashboard_overtime_machine_tab").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "month", text: genSelectMonth("dash_year_summary_machine", "dash_month_summary_machine")},
                {id: "refresh", text: "Resize", img: "resize.png"},
                {id: "export", text: "Export To Excel", img: "excel.png"},
            ]
        });

        $("#dash_year_summary_machine").on("change", function() {
            loadMainChart();
        });

        $("#dash_month_summary_machine").on("change", function() {
            loadMainChart();
        });

        summaryMenu.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    loadMainChart();
                    break;
                case "export":
                    subOvtMachineHourGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    sAlert("Export Data Dimulai");
                    break;
            }
        });

        summaryLayout.cells("a").attachHTMLString("<div class='hc_graph' id='monthly_summary_machine' style='height:100%;width:100%;'></div>");

        function subGridCount() {
            sumGridToElement(subOvtMachineHourGrid, 4, "total_dash_machine_hour_sub", null, "float");
        }

        subOvtMachineHourGrid =  summaryLayout.cells("b").attachGrid();
        subOvtMachineHourGrid.setHeader("No,Nama Mesin,Bagian,Sub Bagian,Jam Operasional");
        subOvtMachineHourGrid.setColSorting("str,str,str,str,str");
        subOvtMachineHourGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt");
        subOvtMachineHourGrid.setColAlign("center,left,left,left,left");
        subOvtMachineHourGrid.setInitWidthsP("10,30,25,25,10");
        subOvtMachineHourGrid.enableSmartRendering(true);
        subOvtMachineHourGrid.attachEvent("onXLE", function() {
            summaryLayout.cells("b").progressOff();
        });
        subOvtMachineHourGrid.attachFooter(",Total Jam Operasional,<span id='total_dash_machine_hour_sub'>0</span> Jam");
        subOvtMachineHourGrid.init();

        function loadMainChart() {
            let year = $("#dash_year_summary_machine").val();
            let month = $("#dash_month_summary_machine").val();
            summaryLayout.cells("a").progressOn();
            summaryLayout.cells("b").progressOn();
            reqJson(Dashboard("getSummaryMachine", {
                    month_overtime_date: month, 
                    year_overtime_date: year, 
                    equal_status: "CLOSED"
                }), "POST", {year, month}, (err, res) => {
                if(res.grid.length > 0) {
                    subOvtMachineHourGrid.parse(res.grid, subGridCount, "json");
                } else {
                    summaryLayout.cells("b").progressOff();
                }
                summaryLayout.cells("a").progressOff();
                Highcharts.chart('monthly_summary_machine', {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                    },
                    title: {
                        text: 'Waktu Penggunaan Mesin ' + nameOfMonth(month) + ' (Top 10)'
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
                        name: 'Persentase Jam',
                        colorByPoint: true,
                        data: res.series
                    }]
                });
            });
        }

        loadMainChart();
    }

JS;
header('Content-Type: application/javascript');
echo $script;