<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
    
    function showDashVehicleSumData(tabs, year, month) {	
        var ga_vhc_report_total_hour = "ga_vhc_report_total_hour"+year+month;
        var ga_vhc_report_total_km = "ga_vhc_report_total_km"+year+month;
        var ga_vhc_report_total_hour_grand = "ga_vhc_report_total_hour_grand"+year+month;
        var ga_vhc_report_total_km_grand = "ga_vhc_report_total_km_grand"+year+month;

        var reportDashVhcTabs =  mainTab.cells(tabs).attachTabbar({
            tabs: [
                {id: "a", text: "Report Global", active: true},
                {id: "b", text: "Report Ruangan"},
            ]
        });

        var reportDashToolbar = reportDashVhcTabs.cells("a").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "export", text: "Export To Excel", type: "button", img: "excel.png"},
            ]
        });

        reportDashToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "export":
                    reportGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    sAlert("Export Data Dimulai");
                    break;
            }
        });

        var reportStatusBar = reportDashVhcTabs.cells("a").attachStatusBar();
        function reportDashVhcGridCount() {
            let reportDashVhcGridRows = reportDashVhcGrid.getRowsNum();
            reportStatusBar.setText("Total baris: " + reportDashVhcGridRows);
            sumGridToElement(reportDashVhcGrid, 12, ga_vhc_report_total_hour, ga_vhc_report_total_hour_grand, "float");
            sumGridToElement(reportDashVhcGrid, 9, ga_vhc_report_total_km, ga_vhc_report_total_km_grand, "float");
        }

        var reportDashVhcGrid = reportDashVhcTabs.cells("a").attachGrid();
        reportDashVhcGrid.setHeader("No,No. Tiket,Tujuan,Jenis Perjalanan,Kendaraan,Driver,Konfirmasi Driver,Kilometer Awal,Kilometer Akhir,Jarak Tempuh,Waktu Mulai,Waktu Selesai,Druasi,Jumlah Penumpang,Status,Alasan Penolakan,Created By,Updated By,DiBuat");
        reportDashVhcGrid.attachHeader("#rspan,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        reportDashVhcGrid.setColSorting("str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        reportDashVhcGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,ron,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        reportDashVhcGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        reportDashVhcGrid.setInitWidthsP("5,15,25,20,15,15,15,15,15,15,20,20,10,10,15,30,15,15,25");
        reportDashVhcGrid.attachFooter(",Total,,,,,,,,<span id="+ga_vhc_report_total_km+">0</span> KM,,,<span id="+ga_vhc_report_total_hour+">0</span> Jam,,,,,,");
        reportDashVhcGrid.attachFooter(",Total Jam Reservasi,<span id="+ga_vhc_report_total_hour_grand+">0</span> Jam,");
        reportDashVhcGrid.attachFooter(",Total Jarak Tempuh,<span id="+ga_vhc_report_total_km_grand+">0</span> KM,");
        reportDashVhcGrid.enableSmartRendering(true);
        reportDashVhcGrid.attachEvent("onXLE", function() {
            reportDashVhcTabs.cells("a").progressOff();
        });
        
        reportDashVhcGrid.init();

        function rReportGrid() {
            reportDashVhcTabs.cells("a").progressOn();
            let params = {
                month_start_date: month,
                year_start_date: year,
                equal_status: "CLOSED",
                report: true
            };

            reportDashVhcGrid.clearAndLoad(GAOther("getVehicleRevGrid", params), reportDashVhcGridCount);
        }

        rReportGrid();

        var reportDashVhcGroupToolbar = reportDashVhcTabs.cells("b").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "export", text: "Export To Excel", type: "button", img: "excel.png"},
            ]
        });

        reportDashVhcGroupToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "export":
                    reportDashVhcGroupGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    sAlert("Export Data Dimulai");
                    break;
            }
        });

        var ga_vhc_report_total_group_hour = "ga_vhc_report_total_group_hour"+year+month;
        var ga_vhc_report_total_group_km = "ga_vhc_report_total_group_km"+year+month;
        var ga_vhc_report_total_group_hour_grand = "ga_vhc_report_total_group_hour_grand"+year+month;
        var ga_vhc_report_total_group_km_grand = "ga_vhc_report_total_group_km_grand"+year+month;

        var groupStatusBar = reportDashVhcTabs.cells("b").attachStatusBar();
        function groupGridCount() {
            let reportDashVhcGridRows = reportDashVhcGroupGrid.getRowsNum();
            groupStatusBar.setText("Total baris: " + reportDashVhcGridRows);
            sumGridToElement(reportDashVhcGroupGrid, 3, ga_vhc_report_total_group_hour, ga_vhc_report_total_group_hour_grand, "float");
            sumGridToElement(reportDashVhcGroupGrid, 4, ga_vhc_report_total_group_km, ga_vhc_report_total_group_km_grand, "float");
        }

        var reportDashVhcGroupGrid = reportDashVhcTabs.cells("b").attachGrid();
        reportDashVhcGroupGrid.setHeader("No,Nama Kendaraan Dinas,Total Reservasi,Total Jam,Total Jarak Tempuh");
        reportDashVhcGroupGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#text_filter")
        reportDashVhcGroupGrid.setColSorting("str,str,str,str,str");
        reportDashVhcGroupGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt");
        reportDashVhcGroupGrid.setColAlign("center,left,left,left,left");
        reportDashVhcGroupGrid.setInitWidthsP("5,35,20,20,20");
        reportDashVhcGroupGrid.attachFooter(",Total,,<span id="+ga_vhc_report_total_group_hour+">0</span> Jam,<span id="+ga_vhc_report_total_group_km+">0</span> KM");
        reportDashVhcGroupGrid.attachFooter(",Total Jam Reservasi,<span id="+ga_vhc_report_total_group_hour_grand+">0</span> Jam,");
        reportDashVhcGroupGrid.attachFooter(",Total Jarak Tempuh,<span id="+ga_vhc_report_total_group_km_grand+">0</span> Jam,");
        reportDashVhcGroupGrid.enableSmartRendering(true);
        reportDashVhcGroupGrid.attachEvent("onXLE", function() {
            reportDashVhcTabs.cells("b").progressOff();
        });
        reportDashVhcGroupGrid.init();
        function rReportGroupGrid() {
            reportDashVhcTabs.cells("b").progressOn();
            let params = {
                month_start_date: month,
                year_start_date: year,
                equal_status: "CLOSED"
            };
            reportDashVhcGroupGrid.clearAndLoad(GAOther("getVehicleRevGroupGrid", params), groupGridCount);
        }

        rReportGroupGrid();
    }

JS;

header('Content-Type: application/javascript');
echo $script;