<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	function showHrReportOvertime() {	
        var legend = legendGrid();
        var reportTabs =  mainTab.cells("hr_report_overtime").attachTabbar({
            tabs: [
                {id: "a", text: "Report Lembur", active: true},
                {id: "b", text: "Report Lembur Bagian"},
                {id: "c", text: "Report Lembur Sub Bagian"},
                {id: "d", text: "Report Lembur Karyawan"},
            ]
        });

        var reportToolbar = reportTabs.cells("a").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "export", text: "Export To Excel", type: "button", img: "excel.png"},
                {id: "verification", text: "Verifikasi Lembur", type: "button", img: "ok.png"},
            ]
        });

        let currentDate = filterForMonth(new Date());
        var reportMenu =  reportTabs.cells("a").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "search", text: "<div style='width:100%'>Search: <input type='text' id='hr_start_ovt_report' readonly value='"+currentDate.start+"' /> - <input type='text' id='hr_end_ovt_report' readonly value='"+currentDate.end+"' /> <button id='hr_btn_ftr_ovt_report'>Proses</button> | Status: <select id='hr_status_ovt_report'><option>ALL</option><option>SUDAH DIULAS</option><option>VERIFIED</option><option>PENDING</option></select></div>"}
            ]
        });

        var filterCalendar = new dhtmlXCalendarObject(["hr_start_ovt_report","hr_end_ovt_report"]);

        $("#hr_btn_ftr_ovt_report").on("click", function() {
            if(checkFilterDate($("#hr_start_ovt_report").val(), $("#hr_end_ovt_report").val())) {
                rReportOvtGrid();
                rReportOvtDeptGrid();
                rReportOvtSubGrid();
                rReportOvtEmpGrid();
            }
        });

        $("#hr_status_ovt_report").on("change", function() {
            rReportOvtGrid();
            rReportOvtDeptGrid();
            rReportOvtSubGrid();
            rReportOvtEmpGrid();
        });

        reportToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    rReportOvtGrid();
                    rReportOvtDeptGrid();
                    rReportOvtSubGrid();
                    rReportOvtEmpGrid();
                    break;
                case "export":
                    reportOvtGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    sAlert("Export Data Dimulai");
                    break;
                case "verification":
                    if(!reportOvtGrid.getChangedRows()) {
                        return eAlert("Belum ada lemburan yang di checklist!");
                    }

                    dhtmlx.modalbox({
                        type: "alert-warning",
                        title: "Verifikasi Lemburan",
                        text: "Lemburan yang di verifikasi akan masuk ke daftar lemburan yang siap di bayarkan!",
                        buttons: ["Ya", "Tidak"],
                        callback: function (index) {
                            if (index == 0) {
                                reportToolbar.disableItem("verification");
                                reportTabs.cells("a").progressOn();
                                reportOvtGridDP.sendData();
                                reportOvtGridDP.attachEvent('onAfterUpdate', function(id, action, tid, tag) {
                                    let message = tag.getAttribute('message');
                                    switch (action) {
                                        case 'updated':
                                            sAlert(message);
                                            rReportOvtGrid();
                                            rReportOvtDeptGrid();
                                            rReportOvtSubGrid();
                                            rReportOvtEmpGrid();
                                            reportToolbar.enableItem("verification");
                                            reportTabs.cells("a").progressOff();
                                            setGridDP();
                                            break;
                                        case 'error':
                                            eAlert(message);
                                            eportToolbar.setItemEnabled("verification");
                                            reportTabs.cells("a").progressOff();
                                            break;
                                    }
                                });
                            }
                        },
                    });
                    break;
            }
        });

        var reportOvtSubMenu = reportTabs.cells("b").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "export", text: "Export To Excel", img: "excel.png"},
            ]
        });

        reportOvtSubMenu.attachEvent("onClick", function(id) {
            switch (id) {
                case "export":
                    reportOvtSubGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    sAlert("Export Data Dimulai");
                    break;
            }
        });

        var reportOvtDivMenu = reportTabs.cells("c").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "export", text: "Export To Excel", img: "excel.png"},
            ]
        });

        reportOvtDivMenu.attachEvent("onClick", function(id) {
            switch (id) {
                case "export":
                    reportOvtDivGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    sAlert("Export Data Dimulai");
                    break;
            }
        });

        var reportOvtEmpMenu = reportTabs.cells("d").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "export", text: "Export To Excel", img: "excel.png"},
            ]
        });

        reportOvtEmpMenu.attachEvent("onClick", function(id) {
            switch (id) {
                case "export":
                    reportOvtEmpGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    sAlert("Export Data Dimulai");
                    break;
            }
        });

        let reportOvtStatusBar = reportTabs.cells("a").attachStatusBar();
        function reportOvtGridCount() {
            var reportOvtGridRows = reportOvtGrid.getRowsNum();
            reportOvtStatusBar.setText("Total baris: " + reportOvtGridRows + " (" + legend.hr_report_overtime + ")");
            let totalOvertime = sumGridToElement(reportOvtGrid, 21, "hr_total_ovt_report", "hr_grand_total_ovt_report");
            let totalMeal = sumGridToElement(reportOvtGrid, 23, "hr_total_meal_ovt_report", "hr_grand_total_meal_ovt_report");
            $("#hr_grand_total_all_ovt_report").html("Rp. " + numberFormat(totalOvertime+totalMeal));
        }

        var reportOvtGrid = reportTabs.cells("a").attachGrid();
        reportOvtGrid.setImagePath("./public/codebase/imgs/");
        reportOvtGrid.setHeader("No,Check,Task ID,No. Memo Lembur,Nama Karyawan,Bagian Personil,Sub Bagian Personil,Bagian Penyelenggara,Sub Bagian Penyelenggara,Nama Mesin #1,Nama Mesin #2,Pelayanan,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Status Hari,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Premi,Nominal Overtime,Makan,Biaya Makan,Status Overtime,Ulasan Pencapaian Lembur,Created At");
        reportOvtGrid.attachHeader("#rspan,#master_checkbox,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        reportOvtGrid.setColSorting("int,na,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        reportOvtGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        reportOvtGrid.setColTypes("rotxt,ch,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        reportOvtGrid.setInitWidthsP("5,5,20,20,20,20,20,20,20,15,15,15,15,15,15,10,10,10,10,10,10,15,5,15,10,30,25");
        reportOvtGrid.attachFooter(",,Total Summary,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,,<div id='hr_total_ovt_report'>0</div>,,<div id='hr_total_meal_ovt_report'>0</div>,,,");
        reportOvtGrid.attachFooter(",,Total Biaya Lembur,<div id='hr_grand_total_ovt_report'>0</div>");
        reportOvtGrid.attachFooter(",,Total Biaya Makan,<div id='hr_grand_total_meal_ovt_report'>0</div>");
        reportOvtGrid.attachFooter(",,Grand Total,<div id='hr_grand_total_all_ovt_report'>0</div>");
        reportOvtGrid.enableSmartRendering(true);
        reportOvtGrid.attachEvent("onXLE", function() {
            reportTabs.cells("a").progressOff();
        });
        reportOvtGrid.attachEvent("onRowSelect", function(rId, cIdn) {
            if((userLogged.rankId == 3 || userLogged.rankId == 4) && userLogged.subDepartment == reportOvtGrid.cells(rId, 4).getValue()) {
                reportToolbar.enableItem("review");
            } else {
                reportToolbar.disableItem("review");
            }
        });
        reportOvtGrid.init();
        
        function setGridDP() {
            reportOvtGridDP = new dataProcessor(Overtime('ovtVerificationBatch'));
            reportOvtGridDP.setTransactionMode("POST", true);
            reportOvtGridDP.setUpdateMode("Off");
            reportOvtGridDP.init(reportOvtGrid);
        }
        setGridDP();
        
        function rReportOvtGrid() {
            reportTabs.cells("a").progressOn();
            let start = $("#hr_start_ovt_report").val();
            let end = $("#hr_end_ovt_report").val();
            let status = $("#hr_status_ovt_report").val();
            let params = {
                equal_status: "CLOSED",
                betweendate_overtime_date: start+","+end,
                check: true
            };

            if(status == 'SUDAH DIULAS'){
                params.join_g_notequal_overtime_review = "";
            } else if(status == 'VERIFIED') {
                params.equal_payment_status = 'VERIFIED';
            } else if(status == 'PENDING') {
                params.equal_payment_status = 'PENDING';
            }

            reportOvtGrid.clearAndLoad(Overtime("getReportOvertimeGrid", params), reportOvtGridCount);
        }

        rReportOvtGrid();

        let reportOvtSumStatusBar = reportTabs.cells("b").attachStatusBar();
        function reportOvtSubGridCount() {
            var reportOvtSubGridRows = reportOvtSubGrid.getRowsNum();
            reportOvtSumStatusBar.setText("Total baris: " + reportOvtSubGridRows);
            let totalOvertimeSub = sumGridToElement(reportOvtSubGrid, 7, "hr_total_ovt_report_sub", "hr_grand_total_ovt_report_sub");
            let totalMealSub = sumGridToElement(reportOvtSubGrid, 8, "hr_total_meal_ovt_report_sub", "hr_grand_total_meal_ovt_report_sub");
            $("#hr_grand_total_all_ovt_report_sub").html("Rp. " + numberFormat(totalOvertimeSub+totalMealSub));
        }

        var reportOvtSubGrid = reportTabs.cells("b").attachGrid();
        reportOvtSubGrid.setImagePath("./public/codebase/imgs/");
        reportOvtSubGrid.setHeader("No,Bagian,Sub Unit,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Nominal Overtime,Biaya Makan");
        reportOvtSubGrid.attachHeader("#rspan,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        reportOvtSubGrid.setColSorting("int,str,str,str,str,str,str,str,str");
        reportOvtSubGrid.setColAlign("center,left,left,left,left,left,left,left,left");
        reportOvtSubGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        reportOvtSubGrid.setInitWidthsP("5,20,20,10,10,10,10,13,13");
        reportOvtSubGrid.attachFooter(",Total Summary,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,<div id='hr_total_ovt_report_sub'>0</div>,<div id='hr_total_meal_ovt_report_sub'>0</div>");
        reportOvtSubGrid.attachFooter(",Total Biaya Lembur,<div id='hr_grand_total_ovt_report_sub'>0</div>");
        reportOvtSubGrid.attachFooter(",Total Biaya Makan,<div id='hr_grand_total_meal_ovt_report_sub'>0</div>");
        reportOvtSubGrid.attachFooter(",Grand Total,<div id='hr_grand_total_all_ovt_report_sub'>0</div>");
        reportOvtSubGrid.enableSmartRendering(true);
        reportOvtSubGrid.attachEvent("onXLE", function() {
            reportTabs.cells("b").progressOff();
        });
        reportOvtSubGrid.init();
        
        function rReportOvtDeptGrid() {
            reportTabs.cells("b").progressOn();
            let start = $("#hr_start_ovt_report").val();
            let end = $("#hr_end_ovt_report").val();
            let status = $("#hr_status_ovt_report").val();
            let params = {
                equal_status: "CLOSED",
                betweendate_overtime_date: start+","+end,
                groupby_sub_department_id: true
            };

            if(status == 'VERIFIED') {
                params.equal_payment_status = 'VERIFIED';
            } else if(status == 'PENDING') {
                params.equal_payment_status = 'PENDING';
            }
            reportOvtSubGrid.clearAndLoad(Overtime("getReportOvertimeSubGrid", params), reportOvtSubGridCount);
        }

        rReportOvtDeptGrid();

        let reportOvtDivStatusBar = reportTabs.cells("c").attachStatusBar();
        function reportOvtDivGridCount() {
            var reportOvtDivGridRows = reportOvtDivGrid.getRowsNum();
            reportOvtDivStatusBar.setText("Total baris: " + reportOvtDivGridRows);
            let totalOvertimeDiv = sumGridToElement(reportOvtDivGrid, 7, "hr_total_ovt_report_div", "hr_grand_total_ovt_report_div");
            let totalMealDiv = sumGridToElement(reportOvtDivGrid, 8, "hr_total_meal_ovt_report_div", "hr_grand_total_meal_ovt_report_div");
            $("#hr_grand_total_all_ovt_report_div").html("Rp. " + numberFormat(totalOvertimeDiv+totalMealDiv));
        }

        var reportOvtDivGrid = reportTabs.cells("c").attachGrid();
        reportOvtDivGrid.setImagePath("./public/codebase/imgs/");
        reportOvtDivGrid.setHeader("No,Sub Bagian,Bagian,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Nominal Overtime,Biaya Makan");
        reportOvtDivGrid.attachHeader("#rspan,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        reportOvtDivGrid.setColSorting("int,str,str,str,str,str,str,str,str");
        reportOvtDivGrid.setColAlign("center,left,left,left,left,left,left,left,left");
        reportOvtDivGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        reportOvtDivGrid.setInitWidthsP("5,20,20,10,10,10,10,15,15");
        reportOvtDivGrid.attachFooter(",Total Summary,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,<div id='hr_total_ovt_report_div'>0</div>,<div id='hr_total_meal_ovt_report_div'>0</div>");
        reportOvtDivGrid.attachFooter(",Total Biaya Lembur,<div id='hr_grand_total_ovt_report_div'>0</div>");
        reportOvtDivGrid.attachFooter(",Total Biaya Makan,<div id='hr_grand_total_meal_ovt_report_div'>0</div>");
        reportOvtDivGrid.attachFooter(",Grand Total,<div id='hr_grand_total_all_ovt_report_div'>0</div>");
        reportOvtDivGrid.enableSmartRendering(true);
        reportOvtDivGrid.attachEvent("onXLE", function() {
            reportTabs.cells("c").progressOff();
        });
        reportOvtDivGrid.init();
        
        function rReportOvtSubGrid() {
            reportTabs.cells("c").progressOn();
            let start = $("#hr_start_ovt_report").val();
            let end = $("#hr_end_ovt_report").val();
            let status = $("#hr_status_ovt_report").val();
            let params = {
                equal_status: "CLOSED",
                betweendate_overtime_date: start+","+end,
                groupby_division_id: true
            };

            if(status == 'VERIFIED') {
                params.equal_payment_status = 'VERIFIED';
            } else if(status == 'PENDING') {
                params.equal_payment_status = 'PENDING';
            }
            reportOvtDivGrid.clearAndLoad(Overtime("getReportOvertimeDivGrid", params), reportOvtDivGridCount);
        }

        rReportOvtSubGrid();

        let reportOvtEmpStatusBar = reportTabs.cells("d").attachStatusBar();
        function reportOvtEmpGridCount() {
            var reportOvtDivGridRows = reportOvtEmpGrid.getRowsNum();
            reportOvtEmpStatusBar.setText("Total baris: " + reportOvtDivGridRows);
            let totalOvertimeEmp = sumGridToElement(reportOvtEmpGrid, 9, "hr_total_ovt_report_emp", "hr_grand_total_ovt_report_emp");
            let totalMealEmp = sumGridToElement(reportOvtEmpGrid, 10, "hr_total_meal_ovt_report_emp", "hr_grand_total_meal_ovt_report_emp");
            $("#hr_grand_total_all_ovt_report_emp").html("Rp. " + numberFormat(totalOvertimeEmp+totalMealEmp));
        }

        var reportOvtEmpGrid = reportTabs.cells("d").attachGrid();
        reportOvtEmpGrid.setImagePath("./public/codebase/imgs/");
        reportOvtEmpGrid.setHeader("No,Nama Karyawan,Sub Bagian,Bagian,Sub Unit,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Nominal Overtime,Biaya Makan");
        reportOvtEmpGrid.attachHeader("#rspan,#text_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        reportOvtEmpGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str");
        reportOvtEmpGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left");
        reportOvtEmpGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        reportOvtEmpGrid.setInitWidthsP("5,20,20,20,20,10,10,10,10,15,15");
        reportOvtEmpGrid.attachFooter(",Total Summary,#cspan,#cspan,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,<div id='hr_total_ovt_report_emp'>0</div>,<div id='hr_total_meal_ovt_report_emp'>0</div>");
        reportOvtEmpGrid.attachFooter(",Total Biaya Lembur,<div id='hr_grand_total_ovt_report_emp'>0</div>");
        reportOvtEmpGrid.attachFooter(",Total Biaya Makan,<div id='hr_grand_total_meal_ovt_report_emp'>0</div>");
        reportOvtEmpGrid.attachFooter(",Grand Total,<div id='hr_grand_total_all_ovt_report_emp'>0</div>");
        reportOvtEmpGrid.enableSmartRendering(true);
        reportOvtEmpGrid.attachEvent("onXLE", function() {
            reportTabs.cells("d").progressOff();
        });
        reportOvtEmpGrid.init();
        
        function rReportOvtEmpGrid() {
            reportTabs.cells("d").progressOn();
            let start = $("#hr_start_ovt_report").val();
            let end = $("#hr_end_ovt_report").val();
            let status = $("#hr_status_ovt_report").val();
            let params = {
                equal_status: "CLOSED",
                betweendate_overtime_date: start+","+end,
                groupby_emp_id: true
            };

            if(status == 'VERIFIED') {
                params.equal_payment_status = 'VERIFIED';
            } else if(status == 'PENDING') {
                params.equal_payment_status = 'PENDING';
            }
            reportOvtEmpGrid.clearAndLoad(Overtime("getReportOvertimeEmpGrid", params), reportOvtEmpGridCount);
        }

        rReportOvtEmpGrid();
    }

JS;

header('Content-Type: application/javascript');
echo $script;