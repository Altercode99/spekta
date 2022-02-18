<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	function showMeetingReport() {	
        var reportMRoomTabs =  mainTab.cells("ga_meeting_rooms_report").attachTabbar({
            tabs: [
                {id: "a", text: "Report Global", active: true},
                {id: "b", text: "Report Ruangan"},
            ]
        });

        var reportMRoomToolbar = reportMRoomTabs.cells("a").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "export", text: "Export To Excel", type: "button", img: "excel.png"},
            ]
        });

        let currentDate = filterForMonth(new Date());
        var reportMRoomMenu =  reportMRoomTabs.cells("a").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "search", text: "<div style='width:100%'>Search: <input type='text' id='ga_start_mroom_report' readonly value='"+currentDate.start+"' /> - <input type='text' id='ga_end_mroom_report' readonly value='"+currentDate.end+"' /> <button id='ga_btn_ftr_mroom_report'>Proses</button>"}
            ]
        });

        var filterCalendar = new dhtmlXCalendarObject(["ga_start_mroom_report","ga_end_mroom_report"]);

        $("#ga_btn_ftr_mroom_report").on("click", function() {
            if(checkFilterDate($("#ga_start_mroom_report").val(), $("#ga_end_mroom_report").val())) {
                rReportGrid();
                rReportGroupGrid();
            }
        });

        reportMRoomToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    rReportGrid();
                    break;
                case "export":
                    reportMRoomGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    sAlert("Export Data Dimulai");
                    break;
            }
        });
        
        var reportStatusBar = reportMRoomTabs.cells("a").attachStatusBar();
        function reportMRoomGridCount() {
            let reportMRoomGridRows = reportMRoomGrid.getRowsNum();
            reportStatusBar.setText("Total baris: " + reportMRoomGridRows);
            sumGridToElement(reportMRoomGrid, 16, "ga_rm_report_total_snack", "ga_rm_report_total_snack_grand", "money");
            sumGridToElement(reportMRoomGrid, 8, "ga_rm_report_total_hour", "ga_rm_report_total_hour_grand", "float");
            sumGridToElement(reportMRoomGrid, 11, "ga_rm_report_total_person", "ga_rm_report_total_person_grand", "int");
        }

        var reportMRoomGrid = reportMRoomTabs.cells("a").attachGrid();
        reportMRoomGrid.setHeader("No,No. Tiket,No. Ref,Topik Meeting,Jenis Meeting,Ruang Meeting,Waktu Mulai,Waktu Selesai,Druasi,Snack,Total Peserta,Konfirmasi Hadir,Konfirmasi Tidak Hadir,Belum Konfirmasi,Snack,Harga Snack,Total,Status,Alasan Penolakan,Created By,Updated By,DiBuat");
        reportMRoomGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        reportMRoomGrid.setColSorting("str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        reportMRoomGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,edtxt,rotxt,rotxt,rotxt,ron,ron,rotxt,rotxt,rotxt,rotxt,rotxt");
        reportMRoomGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        reportMRoomGrid.setInitWidthsP("5,15,15,25,15,15,20,20,10,10,10,10,10,10,15,15,15,15,30,15,15,25");
        reportMRoomGrid.attachFooter(",Total,,,,,,<span id='ga_rm_report_total_hour'>0</span> Jam,,,<span id='ga_rm_report_total_person'>0</span> Orang,,,,,<div id='ga_rm_report_total_snack'>0</div>,,,,,,");
        reportMRoomGrid.attachFooter(",Total Peserta,<span id='ga_rm_report_total_person_grand'>0</span> Orang");
        reportMRoomGrid.attachFooter(",Total Jam Reservasi,<span id='ga_rm_report_total_hour_grand'>0</span> Jam");
        reportMRoomGrid.attachFooter(",Total Biaya Snack,<div id='ga_rm_report_total_snack_grand'>0</div>");
        reportMRoomGrid.enableSmartRendering(true);
        reportMRoomGrid.attachEvent("onXLE", function() {
            reportMRoomTabs.cells("a").progressOff();
        });
        reportMRoomGrid.setNumberFormat("0,000",15,".",",");
        reportMRoomGrid.setNumberFormat("0,000",16,".",",");
        
        reportMRoomGrid.init();

        function rReportGrid() {
            reportMRoomTabs.cells("a").progressOn();
            let start = $("#ga_start_mroom_report").val();
            let end = $("#ga_end_mroom_report").val();
            let params = {
                betweendate_start_date: start+","+end,
                equal_status: "CLOSED",
                report: true
            };

            reportMRoomGrid.clearAndLoad(GAOther("getMeetingRevGrid", params), reportMRoomGridCount);
        }

        rReportGrid();

        var reportGroupToolbar = reportMRoomTabs.cells("b").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "export", text: "Export To Excel", type: "button", img: "excel.png"},
            ]
        });

        reportGroupToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "export":
                    reportMRoomGroupGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    sAlert("Export Data Dimulai");
                    break;
            }
        });

        var groupStatusBar = reportMRoomTabs.cells("b").attachStatusBar();
        function groupGridCount() {
            let reportMRoomGridRows = reportMRoomGroupGrid.getRowsNum();
            groupStatusBar.setText("Total baris: " + reportMRoomGridRows);
            sumGridToElement(reportMRoomGroupGrid, 2, "ga_rmr_total_rev", 'ga_rmr_total_rev_grand', "int");
            sumGridToElement(reportMRoomGroupGrid, 3, "ga_rmr_total_person", 'ga_rmr_total_person_grand', "int");
            sumGridToElement(reportMRoomGroupGrid, 4, "ga_rmr_total_hour", 'ga_rmr_total_hour_grand', "float");
            sumGridToElement(reportMRoomGroupGrid, 5, "ga_rmr_total_snack", 'ga_rmr_total_snack_grand', "money");
        }

        var reportMRoomGroupGrid = reportMRoomTabs.cells("b").attachGrid();
        reportMRoomGroupGrid.setHeader("No,Nama Ruang Meeting,Total Reservasi,Total Peserta,Total Jam,Total Biaya Snack");
        reportMRoomGroupGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        reportMRoomGroupGrid.setColSorting("str,str,str,str,str,str");
        reportMRoomGroupGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        reportMRoomGroupGrid.setColAlign("center,left,left,left,left,left");
        reportMRoomGroupGrid.setInitWidthsP("5,20,20,20,20,20");
        reportMRoomGroupGrid.attachFooter(",Total,<span id='ga_rmr_total_rev'>0</span>,<span id='ga_rmr_total_person'>0</span> Orang,<span id='ga_rmr_total_hour'>0</span> Jam,Rp. <span id='ga_rmr_total_snack'>0</span>");
        reportMRoomGroupGrid.attachFooter(",Total Reservasi (Meeting),<span id='ga_rmr_total_rev_grand'>0</span>");
        reportMRoomGroupGrid.attachFooter(",Total Peserta,<span id='ga_rmr_total_person_grand'>0</span> Orang");
        reportMRoomGroupGrid.attachFooter(",Total Jam,<span id='ga_rmr_total_hour_grand'>0</span> Jam");
        reportMRoomGroupGrid.attachFooter(",Total Biaya Snack,<span id='ga_rmr_total_snack_grand'>0</span>");
        reportMRoomGroupGrid.enableSmartRendering(true);
        reportMRoomGroupGrid.attachEvent("onXLE", function() {
            reportMRoomTabs.cells("b").progressOff();
        });
        
        reportMRoomGroupGrid.init();

        function rReportGroupGrid() {
            reportMRoomTabs.cells("b").progressOn();
            let start = $("#ga_start_mroom_report").val();
            let end = $("#ga_end_mroom_report").val();
            let params = {
                betweendate_start_date: start+","+end,
                equal_status: "CLOSED"
            };

            reportMRoomGroupGrid.clearAndLoad(GAOther("getMeetingRevGroupGrid", params), groupGridCount);
        }

        rReportGroupGrid();
    }

JS;

header('Content-Type: application/javascript');
echo $script;
