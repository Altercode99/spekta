<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	function showVehicleReport() {
        var reportVehicleTabs =  mainTab.cells("ga_vehicles_report").attachTabbar({
            tabs: [
                {id: "a", text: "Report Global", active: true},
                {id: "b", text: "Report Ruangan"},
            ]
        });

        var reportVehicleToolbar = reportVehicleTabs.cells("a").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "export", text: "Export To Excel", type: "button", img: "excel.png"},
            ]
        });

        let currentDate = filterForMonth(new Date());
        var reportVehicleMenu =  reportVehicleTabs.cells("a").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "search", text: "<div style='width:100%'>Search: <input type='text' id='ga_start_vehicle_report' readonly value='"+currentDate.start+"' /> - <input type='text' id='ga_end_vehicle_report' readonly value='"+currentDate.end+"' /> <button id='ga_btn_ftr_vehicle_report'>Proses</button>"}
            ]
        });

        var filterCalendar = new dhtmlXCalendarObject(["ga_start_vehicle_report","ga_end_vehicle_report"]);

        $("#ga_btn_ftr_vehicle_report").on("click", function() {
            if(checkFilterDate($("#ga_start_vehicle_report").val(), $("#ga_end_vehicle_report").val())) {
                rReportGrid();
                rReportGroupGrid();
            }
        });

        reportVehicleToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    rReportGrid();
                    break;
                case "export":
                    reportVehicleGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    sAlert("Export Data Dimulai");
                    break;
            }
        });

        var reportStatusBar = reportVehicleTabs.cells("a").attachStatusBar();
        function reportVehicleGridCount() {
            let reportVehicleGridRows = reportVehicleGrid.getRowsNum();
            reportStatusBar.setText("Total baris: " + reportVehicleGridRows);
            sumGridToElement(reportVehicleGrid, 12, "ga_vehice_total_hour", 'ga_vehice_total_hour_grand', "float");
            sumGridToElement(reportVehicleGrid, 9, "ga_vehice_total_km", 'ga_vehice_total_km_grand', "float");
        }

        var reportVehicleGrid = reportVehicleTabs.cells("a").attachGrid();
        reportVehicleGrid.setHeader("No,No. Tiket,Tujuan,Jenis Perjalanan,Kendaraan,Driver,Konfirmasi Driver,Kilometer Awal,Kilometer Akhir,Jarak Tempuh,Waktu Mulai,Waktu Selesai,Druasi,Jumlah Penumpang,Status,Alasan Penolakan,Created By,Updated By,DiBuat");
        reportVehicleGrid.attachHeader("#rspan,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        reportVehicleGrid.setColSorting("str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        reportVehicleGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,ron,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        reportVehicleGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        reportVehicleGrid.setInitWidthsP("5,15,25,20,15,15,15,15,15,15,20,20,10,10,15,30,15,15,25");
        reportVehicleGrid.attachFooter(",Total,,,,,,,,<span id='ga_vehice_total_km'>0</span> KM,,,<span id='ga_vehice_total_hour'>0</span> Jam,,,,,,");
        reportVehicleGrid.attachFooter(",Total Jam Reservasi,<span id='ga_vehice_total_hour_grand'>0</span> Jam,");
        reportVehicleGrid.attachFooter(",Total Jarak Tempuh,<span id='ga_vehice_total_km_grand'>0</span> KM,");
        reportVehicleGrid.enableSmartRendering(true);
        reportVehicleGrid.attachEvent("onXLE", function() {
            reportVehicleTabs.cells("a").progressOff();
        });
        
        reportVehicleGrid.init();

        function rReportGrid() {
            reportVehicleTabs.cells("a").progressOn();
            let start = $("#ga_start_vehicle_report").val();
            let end = $("#ga_end_vehicle_report").val();
            let params = {
                betweendate_start_date: start+","+end,
                equal_status: "CLOSED",
                report: true
            };

            reportVehicleGrid.clearAndLoad(GAOther("getVehicleRevGrid", params), reportVehicleGridCount);
        }

        rReportGrid();

        var reportVehicleGroupToolbar = reportVehicleTabs.cells("b").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "export", text: "Export To Excel", type: "button", img: "excel.png"},
            ]
        });

        reportVehicleGroupToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "export":
                    reportVehicleGroupGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    sAlert("Export Data Dimulai");
                    break;
            }
        });

        var groupStatusBar = reportVehicleTabs.cells("b").attachStatusBar();
        function groupGridCount() {
            let reportVehicleGridRows = reportVehicleGroupGrid.getRowsNum();
            groupStatusBar.setText("Total baris: " + reportVehicleGridRows);
            sumGridToElement(reportVehicleGroupGrid, 3, "ga_vehice_group_total_hour", 'ga_vehice_group_total_hour_grand', "float");
            sumGridToElement(reportVehicleGroupGrid, 4, "ga_vehice_group_total_km", 'ga_vehice_group_total_km_grand', "float");
        }

        var reportVehicleGroupGrid = reportVehicleTabs.cells("b").attachGrid();
        reportVehicleGroupGrid.setHeader("No,Nama Kendaraan Dinas,Total Reservasi,Total Jam,Total Jarak Tempuh");
        reportVehicleGroupGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#text_filter")
        reportVehicleGroupGrid.setColSorting("str,str,str,str,str");
        reportVehicleGroupGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt");
        reportVehicleGroupGrid.setColAlign("center,left,left,left,left");
        reportVehicleGroupGrid.setInitWidthsP("5,35,20,20,20");
        reportVehicleGroupGrid.attachFooter(",Total,,<span id='ga_vehice_group_total_hour'>0</span> Jam,<span id='ga_vehice_group_total_km'>0</span> KM");
        reportVehicleGroupGrid.attachFooter(",Total Jam Reservasi,<span id='ga_vehice_group_total_hour_grand'>0</span> Jam,");
        reportVehicleGroupGrid.attachFooter(",Total Jarak Tempuh,<span id='ga_vehice_group_total_km_grand'>0</span> Jam,");
        reportVehicleGroupGrid.enableSmartRendering(true);
        reportVehicleGroupGrid.attachEvent("onXLE", function() {
            reportVehicleTabs.cells("b").progressOff();
        });
        
        reportVehicleGroupGrid.init();

        function rReportGroupGrid() {
            reportVehicleTabs.cells("b").progressOn();
            let start = $("#ga_start_vehicle_report").val();
            let end = $("#ga_end_vehicle_report").val();
            let params = {
                betweendate_start_date: start+","+end,
                equal_status: "CLOSED"
            };

            reportVehicleGroupGrid.clearAndLoad(GAOther("getVehicleRevGroupGrid", params), groupGridCount);
        }

        rReportGroupGrid();
    }

JS;

header('Content-Type: application/javascript');
echo $script;
