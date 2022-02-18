<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
    
    function showDashMRoomSumData(tabs, year, month) {	

        var ga_rm_report_total_hour = "ga_rm_report_total_hour"+year+month;
        var ga_rm_report_total_person = "ga_rm_report_total_person"+year+month;
        var ga_rm_report_total_snack = "ga_rm_report_total_snack"+year+month;
        var ga_rm_report_total_person_grand = "ga_rm_report_total_person_grand"+year+month;
        var ga_rm_report_total_hour_grand = "ga_rm_report_total_hour_grand"+year+month;
        var ga_rm_report_total_snack_grand = "ga_rm_report_total_snack_grand"+year+month;

        var reportTabs =  mainTab.cells(tabs).attachTabbar({
            tabs: [
                {id: "a", text: "Report Global", active: true},
                {id: "b", text: "Report Ruangan"},
            ]
        });

        var reportToolbar = reportTabs.cells("a").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "export", text: "Export To Excel", type: "button", img: "excel.png"},
            ]
        });

        reportToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "export":
                    reportGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    sAlert("Export Data Dimulai");
                    break;
            }
        });

        var reportStatusBar = reportTabs.cells("a").attachStatusBar();
        function reportGridCount() {
            let reportGridRows = reportGrid.getRowsNum();
            reportStatusBar.setText("Total baris: " + reportGridRows);
            sumGridToElement(reportGrid, 16, ga_rm_report_total_snack, ga_rm_report_total_snack_grand, "money");
            sumGridToElement(reportGrid, 8, ga_rm_report_total_hour, ga_rm_report_total_hour_grand, "float");
            sumGridToElement(reportGrid, 11, ga_rm_report_total_person, ga_rm_report_total_person_grand, "int");
        }

        var reportGrid = reportTabs.cells("a").attachGrid();
        reportGrid.setHeader("No,No. Tiket,No. Ref,Topik Meeting,Jenis Meeting,Ruang Meeting,Waktu Mulai,Waktu Selesai,Druasi,Snack,Total Peserta,Konfirmasi Hadir,Konfirmasi Tidak Hadir,Belum Konfirmasi,Snack,Harga Snack,Total,Status,Alasan Penolakan,Created By,Updated By,DiBuat");
        reportGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        reportGrid.setColSorting("str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        reportGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,edtxt,rotxt,rotxt,rotxt,ron,ron,rotxt,rotxt,rotxt,rotxt,rotxt");
        reportGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        reportGrid.setInitWidthsP("5,15,15,25,15,15,20,20,10,10,10,10,10,10,15,15,15,15,30,15,15,25");
        reportGrid.attachFooter(",Total,,,,,,<span id='"+ga_rm_report_total_hour+"'>0</span> Jam,,,<span id='"+ga_rm_report_total_person+"'>0</span> Orang,,,,,<div id='"+ga_rm_report_total_snack+"'>0</div>,,,,,,");
        reportGrid.attachFooter(",Total Peserta,<span id='"+ga_rm_report_total_person_grand+"'>0</span> Orang");
        reportGrid.attachFooter(",Total Jam Reservasi,<span id='"+ga_rm_report_total_hour_grand+"'>0</span> Jam");
        reportGrid.attachFooter(",Total Biaya Snack,<div id='"+ga_rm_report_total_snack_grand+"'>0</div>");
        reportGrid.enableSmartRendering(true);
        reportGrid.attachEvent("onXLE", function() {
            reportTabs.cells("a").progressOff();
        });
        reportGrid.setNumberFormat("0,000",15,".",",");
        reportGrid.setNumberFormat("0,000",16,".",",");
        
        reportGrid.init();

        function rReportGrid() {
            reportTabs.cells("a").progressOn();
            let params = {
                month_start_date: month,
                year_start_date: year,
                equal_status: "CLOSED",
                report: true
            };
            reportGrid.clearAndLoad(GAOther("getMeetingRevGrid", params), reportGridCount);
        }

        rReportGrid();

        var reportGroubToolbar = reportTabs.cells("b").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "export", text: "Export To Excel", type: "button", img: "excel.png"},
            ]
        });

        reportGroubToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "export":
                    reportGroupGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    sAlert("Export Data Dimulai");
                    break;
            }
        });

        var ga_rmr_total_rev = "ga_rmr_total_rev_"+year+month;
        var ga_rmr_total_rev_grand = "ga_rmr_total_rev_grand_"+year+month;
        var ga_rmr_total_person = "ga_rmr_total_person_"+year+month;
        var ga_rmr_total_person_grand = "ga_rmr_total_person_grand_"+year+month;
        var ga_rmr_total_hour = "ga_rmr_total_hour_"+year+month;
        var ga_rmr_total_hour_grand = "ga_rmr_total_hour_grand_"+year+month;
        var ga_rmr_total_snack = "ga_rmr_total_snack_"+year+month;
        var ga_rmr_total_snack_grand = "ga_rmr_total_snack_grand_"+year+month;

        var groupStatusBar = reportTabs.cells("b").attachStatusBar();
        function groupGridCount() {
            let reportGridRows = reportGroupGrid.getRowsNum();
            groupStatusBar.setText("Total baris: " + reportGridRows);
            sumGridToElement(reportGroupGrid, 2, ga_rmr_total_rev, ga_rmr_total_rev_grand, "int");
            sumGridToElement(reportGroupGrid, 3, ga_rmr_total_person, ga_rmr_total_person_grand, "int");
            sumGridToElement(reportGroupGrid, 4, ga_rmr_total_hour, ga_rmr_total_hour_grand, "float");
            sumGridToElement(reportGroupGrid, 5, ga_rmr_total_snack, ga_rmr_total_snack_grand, "money");
        }

        var reportGroupGrid = reportTabs.cells("b").attachGrid();
        reportGroupGrid.setHeader("No,Nama Ruang Meeting,Total Reservasi,Total Peserta,Total Jam,Total Biaya Snack");
        reportGroupGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        reportGroupGrid.setColSorting("str,str,str,str,str,str");
        reportGroupGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        reportGroupGrid.setColAlign("center,left,left,left,left,left");
        reportGroupGrid.setInitWidthsP("5,30,15,15,15,20");
        reportGroupGrid.attachFooter(",Total,<span id='"+ga_rmr_total_rev+"'>0</span>,<span id='"+ga_rmr_total_person+"'>0</span> Orang,<span id='"+ga_rmr_total_hour+"'>0</span> Jam,Rp. <span id='"+ga_rmr_total_snack+"'>0</span>");
        reportGroupGrid.attachFooter(",Total Reservasi (Meeting),<span id='"+ga_rmr_total_rev_grand+"'>0</span>");
        reportGroupGrid.attachFooter(",Total Peserta,<span id='"+ga_rmr_total_person_grand+"'>0</span> Orang");
        reportGroupGrid.attachFooter(",Total Jam,<span id='"+ga_rmr_total_hour_grand+"'>0</span> Jam");
        reportGroupGrid.attachFooter(",Total Biaya Snack,<span id='"+ga_rmr_total_snack_grand+"'>0</span>");
        reportGroupGrid.enableSmartRendering(true);
        reportGroupGrid.attachEvent("onXLE", function() {
            reportTabs.cells("b").progressOff();
        });
        
        reportGroupGrid.init();

        function rReportGroupGrid() {
            reportTabs.cells("b").progressOn();
            let params = {
                month_start_date: month,
                year_start_date: year,
                equal_status: "CLOSED"
            };

            reportGroupGrid.clearAndLoad(GAOther("getMeetingRevGroupGrid", params), groupGridCount);
        }

        rReportGroupGrid();
    }

JS;

header('Content-Type: application/javascript');
echo $script;