<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	function showReportOvertime() {	
        var legend = legendGrid();
        var reportTabs =  mainTab.cells("other_report_overtime").attachTabbar({
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
                {id: "review", text: "Ulasan Pencapaian Lembur", type: "button", img: "certificate.png"},
            ]
        });

        if(userLogged.rankId != 3 && userLogged.rankId != 4) {
            reportToolbar.disableItem("review");
        }

        let currentDate = filterForMonth(new Date());
        var reportMenu =  reportTabs.cells("a").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "search", text: "<div style='width:100%'>Search: <input type='text' id='other_start_ovt_report' readonly value='"+currentDate.start+"' /> - <input type='text' id='other_end_ovt_report' readonly value='"+currentDate.end+"' /> <button id='other_btn_ftr_ovt_report'>Proses</button>"}
            ]
        });

        var filterCalendar = new dhtmlXCalendarObject(["other_start_ovt_report","other_end_ovt_report"]);

        $("#other_btn_ftr_ovt_report").on("click", function() {
            if(checkFilterDate($("#other_start_ovt_report").val(), $("#other_end_ovt_report").val())) {
                rReportOvtGrid();
                rReportOvtDeptGrid();
                rReportOvtSubGrid();
                rReportOvtEmpGrid();
            }
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
                case "review":
                    if(!reportOvtGrid.getSelectedRowId()) {
                        return eAlert("Pilih lemburan yang akan di ulas");
                    }

                    let taskId = reportOvtGrid.cells(reportOvtGrid.getSelectedRowId(), 2).getValue();
                    var reviewWin = createWindow("review_overtime_win", "Ulasan Pencapaian Lembur " + taskId, 1100, 700);
                    myWins.window("review_overtime_win").skipMyCloseEvent = true;

                    var revLayout = reviewWin.attachLayout({
                        pattern: "3U",
                        cells: [
                            {id: "a", header: 270},
                            {id: "b", header: false},
                            {id: "c", header: false},
                        ]
                    });

                    reqJson(Overtime("getOvertimeDetailViewRev"), "POST", {taskId}, (err, res) => {
                        if(res.status === "success") {
                            revLayout.cells("a").attachHTMLString(res.template);
                        }
                    });

                    revLayout.cells("c").progressOn();
                    var personGrid = revLayout.cells("c").attachGrid();
                    personGrid.setImagePath("./public/codebase/imgs/");
                    personGrid.setImagePath("./public/codebase/imgs/");
                    personGrid.setHeader("No,Nama Karyawan,Sub Bagian,Bagian,Sub Unit,Tugas Lembur,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Nominal Overtime,Biaya Makan");
                    personGrid.attachHeader("#rspan,#text_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
                    personGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str");
                    personGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left");
                    personGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
                    personGrid.setInitWidthsP("5,20,20,20,20,30,10,10,10,10,15,15");
                    personGrid.attachFooter(",Total Summary,#cspan,#cspan,#cspan,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,<div id='other_total_ovt_rev'>0</div>,<div id='other_total_meal_ovt_rev'>0</div>");
                    personGrid.enableSmartRendering(true);
                    personGrid.attachEvent("onXLE", function() {
                        revLayout.cells("c").progressOff();
                    });
                    personGrid.init();
                    personGrid.clearAndLoad(Overtime("getReportOvertimeEmpGridRev", {equal_task_id: taskId, equal_status: "CLOSED"}), countTotalOvertimeRev);

                    function countTotalOvertimeRev() {
                        sumGridToElement(personGrid, 10, "other_total_ovt_rev");
                        sumGridToElement(personGrid, 11, "other_total_meal_ovt_rev");
                    }

                    var revForm = revLayout.cells("b").attachForm([
                        {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Ulasan ASMAN Terkait", list: [
                            {type: "hidden", name: "task_id", labelWidth: 130, inputWidth:250, required: true, value: taskId},
                            {type: "input", name: "overtime_review", label: "Komentar", labelWidth: 130, inputWidth:250, required: true, rows: 4},
                            {type: "block", offsetTop: 30, list: [
                                {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                                {type: "newcolumn"},
                                {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                            ]}
                        ]}
                    ]);

                    revForm.attachEvent("onButtonClick", function(name) {
                        switch (name) {
                            case "update":
                                if (!revForm.validate()) {
                                    return eAlert("Input error!");
                                }

                                setDisable(["update", "clear"], revForm, revLayout.cells("b"));
                                let revFormDP = new dataProcessor(Overtime("updateOvertimeReview"));
                                revFormDP.init(revForm);
                                revForm.save();

                                revFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                                    let message = tag.getAttribute("message");
                                    switch (action) {
                                        case "updated":
                                            sAlert(message);
                                            rReportOvtGrid();
                                            clearAllForm(revForm);
                                            setEnable(["update", "clear"], revForm, revLayout.cells("b"));
                                            closeWindow("review_overtime_win");
                                            break;
                                        case "error":
                                            eAlert(message);
                                            setEnable(["update", "clear"], revForm, revLayout.cells("b"));
                                            break;
                                    }
                                });
                                break;
                            case "cancel":
                                closeWindow("review_overtime_win");
                                break;
                        }
                    })
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
            reportOvtStatusBar.setText("Total baris: " + reportOvtGridRows + " (" + legend.report_overtime + ")");
            let totalOvertime = sumGridToElement(reportOvtGrid, 20, "other_total_ovt_report", "other_grand_total_ovt_report");
            let totalMeal = sumGridToElement(reportOvtGrid, 22, "other_total_meal_ovt_report", "other_grand_total_meal_ovt_report");
            $("#other_grand_total_all_ovt_report").html("Rp. " + numberFormat(totalOvertime+totalMeal));
        }

        var reportOvtGrid = reportTabs.cells("a").attachGrid();
        reportOvtGrid.setImagePath("./public/codebase/imgs/");
        reportOvtGrid.setHeader("No,Task ID,No. Memo Lembur,Nama Karyawan,Bagian Personil,Sub Bagian Personil,Bagian Penyelenggara,Sub Bagian Penyelenggara,Nama Mesin #1,Nama Mesin #2,Pelayanan,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Status Hari,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Premi,Nominal Overtime,Makan,Biaya Makan,Status Overtime,Ulasan Pencapaian Lembur,Created At");
        reportOvtGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        reportOvtGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        reportOvtGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        reportOvtGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        reportOvtGrid.setInitWidthsP("5,20,20,20,20,20,20,20,15,15,15,15,15,15,10,10,10,10,10,10,15,5,15,10,30,25");
        reportOvtGrid.attachFooter(",Total Summary,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,,<div id='other_total_ovt_report'>0</div>,,<div id='other_total_meal_ovt_report'>0</div>,,,");
        reportOvtGrid.attachFooter(",Total Biaya Lembur,<div id='other_grand_total_ovt_report'>0</div>");
        reportOvtGrid.attachFooter(",Total Biaya Makan,<div id='other_grand_total_meal_ovt_report'>0</div>");
        reportOvtGrid.attachFooter(",Grand Total,<div id='other_grand_total_all_ovt_report'>0</div>");
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
        
        function rReportOvtGrid() {
            reportTabs.cells("a").progressOn();
            let start = $("#other_start_ovt_report").val();
            let end = $("#other_end_ovt_report").val();
            let params = {
                equal_status: "CLOSED",
                betweendate_overtime_date: start+","+end
            };
            reportOvtGrid.clearAndLoad(Overtime("getReportOvertimeGrid", params), reportOvtGridCount);
        }

        rReportOvtGrid();

        let reportOvtSumStatusBar = reportTabs.cells("b").attachStatusBar();
        function reportOvtSubGridCount() {
            var reportOvtSubGridRows = reportOvtSubGrid.getRowsNum();
            reportOvtSumStatusBar.setText("Total baris: " + reportOvtSubGridRows);
            let totalOvertimeSub = sumGridToElement(reportOvtSubGrid, 7, "other_total_ovt_report_sub", "other_grand_total_ovt_report_sub");
            let totalMealSub = sumGridToElement(reportOvtSubGrid, 8, "other_total_meal_ovt_report_sub", "other_grand_total_meal_ovt_report_sub");
            $("#other_grand_total_all_ovt_report_sub").html("Rp. " + numberFormat(totalOvertimeSub+totalMealSub));
        }

        var reportOvtSubGrid = reportTabs.cells("b").attachGrid();
        reportOvtSubGrid.setImagePath("./public/codebase/imgs/");
        reportOvtSubGrid.setHeader("No,Bagian,Sub Unit,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Nominal Overtime,Biaya Makan");
        reportOvtSubGrid.attachHeader("#rspan,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        reportOvtSubGrid.setColSorting("int,str,str,str,str,str,str,str,str");
        reportOvtSubGrid.setColAlign("center,left,left,left,left,left,left,left,left");
        reportOvtSubGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        reportOvtSubGrid.setInitWidthsP("5,20,20,10,10,10,10,13,13");
        reportOvtSubGrid.attachFooter(",Total Summary,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,<div id='other_total_ovt_report_sub'>0</div>,<div id='other_total_meal_ovt_report_sub'>0</div>");
        reportOvtSubGrid.attachFooter(",Total Biaya Lembur,<div id='other_grand_total_ovt_report_sub'>0</div>");
        reportOvtSubGrid.attachFooter(",Total Biaya Makan,<div id='other_grand_total_meal_ovt_report_sub'>0</div>");
        reportOvtSubGrid.attachFooter(",Grand Total,<div id='other_grand_total_all_ovt_report_sub'>0</div>");
        reportOvtSubGrid.enableSmartRendering(true);
        reportOvtSubGrid.attachEvent("onXLE", function() {
            reportTabs.cells("b").progressOff();
        });
        reportOvtSubGrid.init();
        
        function rReportOvtDeptGrid() {
            reportTabs.cells("b").progressOn();
            let start = $("#other_start_ovt_report").val();
            let end = $("#other_end_ovt_report").val();
            let params = {
                equal_status: "CLOSED",
                betweendate_overtime_date: start+","+end,
                groupby_sub_department_id: true
            };
            reportOvtSubGrid.clearAndLoad(Overtime("getReportOvertimeSubGrid", params), reportOvtSubGridCount);
        }

        rReportOvtDeptGrid();

        let reportOvtDivStatusBar = reportTabs.cells("c").attachStatusBar();
        function reportOvtDivGridCount() {
            var reportOvtDivGridRows = reportOvtDivGrid.getRowsNum();
            reportOvtDivStatusBar.setText("Total baris: " + reportOvtDivGridRows);
            let totalOvertimeDiv = sumGridToElement(reportOvtDivGrid, 7, "other_total_ovt_report_div", "other_grand_total_ovt_report_div");
            let totalMealDiv = sumGridToElement(reportOvtDivGrid, 8, "other_total_meal_ovt_report_div", "other_grand_total_meal_ovt_report_div");
            $("#other_grand_total_all_ovt_report_div").html("Rp. " + numberFormat(totalOvertimeDiv+totalMealDiv));
        }

        var reportOvtDivGrid = reportTabs.cells("c").attachGrid();
        reportOvtDivGrid.setImagePath("./public/codebase/imgs/");
        reportOvtDivGrid.setHeader("No,Sub Bagian,Bagian,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Nominal Overtime,Biaya Makan");
        reportOvtDivGrid.attachHeader("#rspan,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        reportOvtDivGrid.setColSorting("int,str,str,str,str,str,str,str,str");
        reportOvtDivGrid.setColAlign("center,left,left,left,left,left,left,left,left");
        reportOvtDivGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        reportOvtDivGrid.setInitWidthsP("5,20,20,10,10,10,10,15,15");
        reportOvtDivGrid.attachFooter(",Total Summary,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,<div id='other_total_ovt_report_div'>0</div>,<div id='other_total_meal_ovt_report_div'>0</div>");
        reportOvtDivGrid.attachFooter(",Total Biaya Lembur,<div id='other_grand_total_ovt_report_div'>0</div>");
        reportOvtDivGrid.attachFooter(",Total Biaya Makan,<div id='other_grand_total_meal_ovt_report_div'>0</div>");
        reportOvtDivGrid.attachFooter(",Grand Total,<div id='other_grand_total_all_ovt_report_div'>0</div>");
        reportOvtDivGrid.enableSmartRendering(true);
        reportOvtDivGrid.attachEvent("onXLE", function() {
            reportTabs.cells("c").progressOff();
        });
        reportOvtDivGrid.init();
        
        function rReportOvtSubGrid() {
            reportTabs.cells("c").progressOn();
            let start = $("#other_start_ovt_report").val();
            let end = $("#other_end_ovt_report").val();
            let params = {
                equal_status: "CLOSED",
                betweendate_overtime_date: start+","+end,
                groupby_division_id: true
            };
            reportOvtDivGrid.clearAndLoad(Overtime("getReportOvertimeDivGrid", params), reportOvtDivGridCount);
        }

        rReportOvtSubGrid();

        let reportOvtEmpStatusBar = reportTabs.cells("d").attachStatusBar();
        function reportOvtEmpGridCount() {
            var reportOvtDivGridRows = reportOvtEmpGrid.getRowsNum();
            reportOvtEmpStatusBar.setText("Total baris: " + reportOvtDivGridRows);
            let totalOvertimeEmp = sumGridToElement(reportOvtEmpGrid, 9, "other_total_ovt_report_emp", "other_grand_total_ovt_report_emp");
            let totalMealEmp = sumGridToElement(reportOvtEmpGrid, 10, "other_total_meal_ovt_report_emp", "other_grand_total_meal_ovt_report_emp");
            $("#other_grand_total_all_ovt_report_emp").html("Rp. " + numberFormat(totalOvertimeEmp+totalMealEmp));
        }

        var reportOvtEmpGrid = reportTabs.cells("d").attachGrid();
        reportOvtEmpGrid.setImagePath("./public/codebase/imgs/");
        reportOvtEmpGrid.setHeader("No,Nama Karyawan,Sub Bagian,Bagian,Sub Unit,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Nominal Overtime,Biaya Makan");
        reportOvtEmpGrid.attachHeader("#rspan,#text_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        reportOvtEmpGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str");
        reportOvtEmpGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left");
        reportOvtEmpGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        reportOvtEmpGrid.setInitWidthsP("5,20,20,20,20,10,10,10,10,15,15");
        reportOvtEmpGrid.attachFooter(",Total Summary,#cspan,#cspan,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,<div id='other_total_ovt_report_emp'>0</div>,<div id='other_total_meal_ovt_report_emp'>0</div>");
        reportOvtEmpGrid.attachFooter(",Total Biaya Lembur,<div id='other_grand_total_ovt_report_emp'>0</div>");
        reportOvtEmpGrid.attachFooter(",Total Biaya Makan,<div id='other_grand_total_meal_ovt_report_emp'>0</div>");
        reportOvtEmpGrid.attachFooter(",Grand Total,<div id='other_grand_total_all_ovt_report_emp'>0</div>");
        reportOvtEmpGrid.enableSmartRendering(true);
        reportOvtEmpGrid.attachEvent("onXLE", function() {
            reportTabs.cells("d").progressOff();
        });
        reportOvtEmpGrid.init();
        
        function rReportOvtEmpGrid() {
            reportTabs.cells("d").progressOn();
            let start = $("#other_start_ovt_report").val();
            let end = $("#other_end_ovt_report").val();
            let params = {
                equal_status: "CLOSED",
                betweendate_overtime_date: start+","+end,
                groupby_emp_id: true
            };
            reportOvtEmpGrid.clearAndLoad(Overtime("getReportOvertimeEmpGrid", params), reportOvtEmpGridCount);
        }

        rReportOvtEmpGrid();
    }

JS;

header('Content-Type: application/javascript');
echo $script;