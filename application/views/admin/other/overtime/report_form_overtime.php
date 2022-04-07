<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	function showFormOvertime() {	
        var legend = legendGrid();
        var reportFormLayout =  mainTab.cells("other_report_form_lembur").attachLayout({
            pattern: "2E",
            cells: [
                {id: "a", text: "Rekap Form Lembur"},
                {id: "b", text: "Detail Lembur", collapse: true},
            ]
        });

        var reportFormToolbar = reportFormLayout.cells("a").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "export", text: "Export To Excel", type: "button", img: "excel.png"},
                {id: "print_overtime", text: "Cetak Lemburan", type: "button", img: "printer.png"},
            ]
        });

        var reportFormDtlToolbar = reportFormLayout.cells("b").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "export", text: "Export To Excel", type: "button", img: "excel.png"},
            ]
        });

        let currentDate = filterForMonth(new Date());
        var reportFormMenu =  reportFormLayout.cells("a").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "search", text: "<div style='width:100%'>Search: <input type='text' id='other_start_ovt_form_report' readonly value='"+currentDate.start+"' /> - <input type='text' id='other_end_ovt_form_report' readonly value='"+currentDate.end+"' /> <button id='other_btn_ftr_ovt_form_report'>Proses</button> | Status: <select id='other_status_form_report'><option>PROCESS</option><option selected>CLOSED</option><option>REJECTED</option><option>ALL</option></select></div>"}
            ]
        });

        var filterCalendar = new dhtmlXCalendarObject(["other_start_ovt_form_report","other_end_ovt_form_report"]);
        $("#other_btn_ftr_ovt_form_report").on("click", function() {
            if(checkFilterDate($("#other_start_ovt_form_report").val(), $("#other_end_ovt_form_report").val())) {
                rOvtFormGrid();
            }
        });

        $("#other_status_form_report").on("change", function() {
            if(checkFilterDate($("#other_start_ovt_form_report").val(), $("#other_end_ovt_form_report").val())) {
                rOvtFormGrid();
            }
        });

        reportFormToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    rOvtFormGrid();
                    break;
                case "export":
                    ovtFormGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    break;
                case "print_overtime":
                    if(!ovtFormGrid.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan di print!");
                    }
                    reqJson(Pc("createLink", {action: 'web'}), "POST", {
                        waTaskId: ovtFormGrid.cells(ovtFormGrid.getSelectedRowId(), 1).getValue(),
                    }, (err, res) => {
                        window.open(res.url, '_blank');
                    });
                    break;
            }
        })

        reportFormDtlToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "export":
                    ovtFormDtlGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    break;
            }
        })

        let ovtFormStatusBar = reportFormLayout.cells("a").attachStatusBar();
        function ovtFormGridCount() {
            ovtFormStatusBar.setText("Total baris: " + ovtFormGrid.getRowsNum() + " (" + legend.approval_overtime + ")");
        }

        reportFormLayout.cells("a").progressOn();
        var ovtFormGrid = reportFormLayout.cells("a").attachGrid();
        ovtFormGrid.setImagePath("./public/codebase/imgs/");
        ovtFormGrid.setHeader("No,Task ID,Sub Unit,Bagian,,Kebutuhan Orang,Status Hari,Tanggal Overtime,Waktu Mulai, Waktu Selesai,Catatan,Makan,Steam,AHU,Compressor,PW,Jemputan,Dust Collector,WFI,Mekanik,Listrik,H&N,QC,QA,Penandaan,GBK,GBB,Status Overtime,,Approval ASMAN,Approval PPIC,Approval MANAGER,Approval PLANT MANAGER,Revisi Jam Lembur,Rejection User Approval,Created By,Updated By,Created At,NIPSPV,NIPASMAN,NIPPPIC,NIPMGR");
        ovtFormGrid.attachHeader("#rspan,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        ovtFormGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        ovtFormGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        ovtFormGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        ovtFormGrid.setInitWidthsP("5,20,20,20,0,10,10,15,15,15,25,7,7,7,7,7,7,10,7,7,7,7,7,7,7,7,7,10,0,30,30,30,30,30,30,15,15,22,0,0,0,0");
        ovtFormGrid.enableSmartRendering(true);
        ovtFormGrid.attachEvent("onXLE", function() {
            reportFormLayout.cells("a").progressOff();
        });
        ovtFormGrid.attachEvent("onRowDblClicked", function(rId, cInd){
            reportFormLayout.cells("b").setText("Detail Lembur : " + ovtFormGrid.cells(rId, 1).getValue());
            reportFormLayout.cells("b").expand();
            rFormOvtDtlGrid(rId);
        });
        ovtFormGrid.init();
        function rOvtFormGrid() {
            let start = $("#other_start_ovt_form_report").val();
            let end = $("#other_end_ovt_form_report").val();
            let status = $("#other_status_form_report").val();
            let params = {
                notin_status: "CANCELED,CREATED,ADD", 
                betweendate_overtime_date: start+","+end,
                equal_sub_department_id: userLogged.subId,
            };

            if(status !== "ALL") {
                params.equal_status = status;
            }
            reportFormLayout.cells("a").progressOn();
            ovtFormGrid.clearAndLoad(Overtime("getAppvOvertimeGrid", params), ovtFormGridCount);
        }

        rOvtFormGrid();

        function countTotalOvertime() {
            sumGridToElement(ovtFormDtlGrid, 19, "other_total_form_report");
        }

        var ovtFormDtlGrid = reportFormLayout.cells("b").attachGrid();
        ovtFormDtlGrid.setImagePath("./public/codebase/imgs/");
        ovtFormDtlGrid.setHeader("No,Task ID,Nama Karyawan,Tugas,Sub Unit,Bagian,Sub Bagian,Nama Mesin,,Pelayanan Produksi,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Status Hari,Jam Efektif,Jam Istirahat,Jam Ril,Jam Hit,Premi,Nominal Overtime,Makan,Status Overtime,Status Terakhir,Spv Approval,Created By,Updated By,Created At,");
        ovtFormDtlGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#select_filter,#select_filter,#text_filter,#text_filter")
        ovtFormDtlGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        ovtFormDtlGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        ovtFormDtlGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        ovtFormDtlGrid.setInitWidthsP("5,20,20,25,20,20,20,20,0,20,15,15,15,10,10,10,10,10,10,10,5,10,25,25,15,15,22,0");
        ovtFormDtlGrid.attachFooter(legend.approval_overtime_spv+",#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,,<div id='other_total_form_report'></div>,,,,,,,,");
        ovtFormDtlGrid.enableSmartRendering(true);
        ovtFormDtlGrid.attachEvent("onXLE", function() {
            reportFormLayout.cells("b").progressOff();
        });
        ovtFormDtlGrid.init();
        function rFormOvtDtlGrid(rId) {
            if(rId) {
                reportFormLayout.cells("b").progressOn();
                let taskId = ovtFormGrid.cells(rId, 1).getValue();
                ovtFormDtlGrid.clearAndLoad(Overtime("getOvertimeDetailGrid", {equal_task_id: taskId, notin_status: "CANCELED,ADD", order_by: 'id:asc', apv: true}), countTotalOvertime);
            } else {
                ovtFormDtlGrid.clearAll();
                ovtFormDtlGrid.callEvent("onGridReconstructed",[]);
                $("#other_total_form_report").html("0");
            }
        }
    }

JS;

header('Content-Type: application/javascript');
echo $script;