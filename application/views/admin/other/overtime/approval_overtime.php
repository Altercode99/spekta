<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	
    function showAppvOvertime() {	
        var legend = legendGrid();
        var times = createTime();

        var appvTabs = mainTab.cells("other_approval_overtime").attachLayout({
            pattern: "2E",
            cells: [
                {id: "a", text: "Daftar Lembur", active: true},
                {id: "b", text: "Detail Lembur", collapse: true},
            ]
        });

        var appvToolbar = appvTabs.cells("a").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "approve", text: "Approve Lembur", type: "button", img: "ok.png"},
                {id: "reject", text: "Reject Lembur", type: "button", img: "messagebox_critical.png"},
                {id: "revision", text: "Revisi Lembur (Back To Admin)", type: "button", img: "refresh.png"},
                {id: "hour_revision", text: "Revisi Waktu Lembur", type: "button", img: "clock.png"},
                {id: "print_overtime", text: "Cetak Lemburan", type: "button", img: "printer.png"},
                {id: "send_wsap", text: "Kirim WhatsApp", type: "button", img: "wa.png"},
            ]
        });

        if(userLogged.role !== "admin" && userLogged.rankId > 4 && !userLogged.picOvertime) {
            appvToolbar.disableItem("approve");
            appvToolbar.disableItem("reject");
            appvToolbar.disableItem("revision");
            appvToolbar.disableItem("hour_revision");
        }

        let currentDate = filterForMonth(new Date());
        var appvMenu =  appvTabs.cells("a").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "search", text: "<div style='width:100%'>Search: <input type='text' id='other_start_ovt_appv' readonly value='"+currentDate.start+"' /> - <input type='text' id='other_end_ovt_appv' readonly value='"+currentDate.end+"' /> <button id='other_btn_ftr_ovt_appv'>Proses</button> | Status: <select id='other_status_ovt_appv'><option>PROCESS</option><option>REJECTED</option><option>ALL</option></select></div>"}
            ]
        });

        $("#other_btn_ftr_ovt_appv").on("click", function() {
            if(checkFilterDate($("#other_start_ovt_appv").val(), $("#other_end_ovt_appv").val())) {
                rOvtGrid();
            } 
        });

        $("#other_status_ovt_appv").on("change", function() {
            if(checkFilterDate($("#other_start_ovt_appv").val(), $("#other_end_ovt_appv").val())) {
                rOvtGrid();
                rOvtDetailGrid(null);
            }
        });

        var filterCalendar = new dhtmlXCalendarObject(["other_start_ovt_appv","other_end_ovt_appv"]);

        appvToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    rOvtGrid();
                    rOvtDetailGrid(null);
                    break;
                case "approve":
                    if(!ovtGrid.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan revisi!");
                    }
                    var taskId = ovtGrid.cells(ovtGrid.getSelectedRowId(), 1).getValue();
                    dhtmlx.modalbox({
                        type: "alert-warning",
                        title: "Approve Lemburan",
                        text: "Anda yakin akan approve lembur " + taskId + "?",
                        buttons: ["Ya", "Tidak"],
                        callback: function (index) {
                            if (index == 0) {
                                reqJson(Overtime("approveOvertime"), "POST", {taskId}, (err, res) => {
                                    if(res.status === "success") {
                                        rOvtGrid();
                                        rOvtDetailGrid(ovtGrid.getSelectedRowId());
                                        appvTabs.cells("b").setText("Detail Lembur");
                                        appvTabs.cells("b").collapse();
                                        sAlert(res.message);
                                    } else {
                                        eAlert(res.message);
                                    }
                                });
                            }
                        },
                    });
                    break;
                case "reject":
                    if(!ovtGrid.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan direject!");
                    }
                    var taskId = ovtGrid.cells(ovtGrid.getSelectedRowId(), 1).getValue();
                    dhtmlx.modalbox({
                        type: "alert-error",
                        title: "Reject Lemburan",
                        text: "Anda yakin akan menolak lembur " + taskId + "?",
                        buttons: ["Ya", "Tidak"],
                        callback: function (index) {
                            if (index == 0) {
                                var rejNoteWin = createWindow("reject_overtime", "Reject Lembur", 500, 300);
                                myWins.window("reject_overtime").skipMyCloseEvent = true;

                                var rejNoteForm = rejNoteWin.attachForm([
                                    {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Form Reject", list:[	
                                        {type: "block", list: [
                                            {type: "input", name: "rejection_note", label: "Alasan Reject", labelWidth: 130, inputWidth: 250, required: true, rows: 3},                               
                                        ]},
                                    ]},
                                    {type: "block", offsetLeft: 30, offsetTop: 10, list: [
                                        {type: "button", name: "submit", className: "button_update", offsetLeft: 15, value: "Submit"},
                                        {type: "newcolumn"},
                                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                                    ]},
                                ]);
                                
                                rejNoteForm.attachEvent("onButtonClick", function(name) {
                                    switch (name) {
                                        case "submit":
                                            reqJson(Overtime("rejectOvertime"), "POST", {
                                                taskId,
                                                rejectionNote: rejNoteForm.getItemValue("rejection_note")
                                            }, (err, res) => {
                                                if(res.status === "success") {
                                                    rOvtGrid();
                                                    rOvtDetailGrid(null);
                                                    appvTabs.cells("b").setText("Detail Lembur");
                                                    appvTabs.cells("b").collapse();
                                                    closeWindow("reject_overtime");
                                                }
                                                sAlert(res.message);
                                            });
                                            break;
                                        case "cancel":
                                            closeWindow("reject_overtime");
                                            break;
                                    }
                                });
                            }
                        },
                    });
                    break;
                case "revision":
                    if(!ovtGrid.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan revisi!");
                    }
                    var taskId = ovtGrid.cells(ovtGrid.getSelectedRowId(), 1).getValue();
                    dhtmlx.modalbox({
                        type: "alert-warning",
                        title: "Revisi Lemburan",
                        text: "Anda yakin akan mengembalikan lembur " + taskId + " ke Admin Lembur?",
                        buttons: ["Ya", "Tidak"],
                        callback: function (index) {
                            if (index == 0) {
                                var revNoteWin = createWindow("back_to_admin", "Revisi Lembur", 500, 300);
                                myWins.window("back_to_admin").skipMyCloseEvent = true;

                                var revNoteForm = revNoteWin.attachForm([
                                    {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Form Revisi", list:[	
                                        {type: "block", list: [
                                            {type: "input", name: "revision_note", label: "Alasan Revisi", labelWidth: 130, inputWidth: 250, required: true, rows: 3},                               
                                        ]},
                                    ]},
                                    {type: "block", offsetLeft: 30, offsetTop: 10, list: [
                                        {type: "button", name: "submit", className: "button_update", offsetLeft: 15, value: "Submit"},
                                        {type: "newcolumn"},
                                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                                    ]},
                                ]);
                                
                                revNoteForm.attachEvent("onButtonClick", function(name) {
                                    switch (name) {
                                        case "submit":
                                            reqJson(Overtime("revisionOvertime"), "POST", {
                                                taskId,
                                                revisionNote: revNoteForm.getItemValue("revision_note")
                                            }, (err, res) => {
                                                if(res.status === "success") {
                                                    rOvtGrid();
                                                    rOvtDetailGrid(null);
                                                    appvTabs.cells("b").setText("Detail Lembur");
                                                    appvTabs.cells("b").collapse();
                                                    closeWindow("back_to_admin");
                                                }
                                                sAlert(res.message);
                                            });
                                            break;
                                        case "cancel":
                                            closeWindow("back_to_admin");
                                            break;
                                    }
                                });
                            }
                        },
                    });
                    break;
                case "hour_revision":
                    if(!ovtGrid.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan di revisi!");
                    }

                    var hourRevWin = createWindow("hour_revision", "Revisi Waktu Lembur", 510, 280);
                    myWins.window("hour_revision").skipMyCloseEvent = true;

                    let ovtTime = getCurrentTime(ovtGrid, 8, 9);
                        
                    let labelStart = ovtTime.labelStart;
                    let labelEnd = ovtTime.labelEnd;
                    var hourRevForm = hourRevWin.attachForm([
                        {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Jam Lembur", list:[	
                            {type: "block", list: [
                                {type: "hidden", name: "id", label: "ID", labelWidth: 130, inputWidth: 250, value: ovtGrid.getSelectedRowId()},                               
                                {type: "combo", name: "start_date", label: labelStart, labelWidth: 130, inputWidth: 250, required: true, readonly: true,
                                    validate: "NotEmpty", 
                                    options: times.startTimes
                                },
                                {type: "combo", name: "end_date", label: labelEnd, labelWidth: 130, inputWidth: 250, required: true, readonly: true,
                                    validate: "NotEmpty", 
                                    options: times.endTimes,
                                }
                            ]},
                        ]},
                        {type: "newcolumn"},
                        {type: "block", offsetLeft: 30, offsetTop: 10, list: [
                            {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Update"},
                            {type: "newcolumn"},
                            {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                        ]},
                    ]);

                    var startCombo = hourRevForm.getCombo("start_date");
                    var endCombo = hourRevForm.getCombo("end_date");
                    let startIndex = times.filterStartTime.indexOf(ovtTime.start);
                    let endIndex = times.filterEndTime.indexOf(ovtTime.end);
                    startCombo.selectOption(startIndex);
                    endCombo.selectOption(endIndex);

                    hourRevForm.attachEvent("onChange", function(name, value) {
                        if(name === "start_date" || name === "end_date") {
                            checkTime(startCombo, endCombo, ['update', 'cancel'], hourRevForm);
                        }
                    });

                    hourRevForm.attachEvent("onButtonClick", function(id) {
                        switch (id) {
                            case "update":
                                setDisable(["update", "cancel"], hourRevForm, hourRevWin);
                                let hourRevFormDP = new dataProcessor(Overtime("updateOvertimeHour"));
                                hourRevFormDP.init(hourRevForm);
                                hourRevForm.save();

                                hourRevFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                                    let message = tag.getAttribute("message");
                                    switch (action) {
                                        case "updated":
                                            rOvtGrid();
                                            rOvtDetailGrid(null);
                                            sAlert(message);
                                            setEnable(["update", "cancel"], hourRevForm, hourRevWin);
                                            closeWindow("hour_revision");
                                            break;
                                        case "error":
                                            eaAlert("Kesalahan Waktu Lembur", message);
                                            setEnable(["update", "cancel"], hourRevForm, hourRevWin);
                                            break;
                                    }
                                });
                                break;
                            case "cancel":
                                closeWindow("hour_revision");
                                break;
                        }
                    });
                    break;
                case "print_overtime":
                    if(!ovtGrid.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan di print!");
                    }
                    reqJson(Pc("createLink", {action: 'web'}), "POST", {
                        waTaskId: ovtGrid.cells(ovtGrid.getSelectedRowId(), 1).getValue(),
                    }, (err, res) => {
                        window.open(res.url, '_blank');
                    });
                    break;
                case "send_wsap":
                    if(!ovtGrid.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan di bagikan ke WhatsApp!");
                    }
                    reqJson(Pc("createLink", {action: 'wa'}), "POST", {
                        waOvtDate: ovtGrid.cells(ovtGrid.getSelectedRowId(), 7).getValue(),
                        waTaskId: ovtGrid.cells(ovtGrid.getSelectedRowId(), 1).getValue(),
                        waStartDate: ovtGrid.cells(ovtGrid.getSelectedRowId(), 8).getValue(),
                        waEndDate: ovtGrid.cells(ovtGrid.getSelectedRowId(), 9).getValue(),
                        waTotalPersonel: ovtGrid.cells(ovtGrid.getSelectedRowId(), 5).getValue()
                    }, (err, res) => {
                        window.open(res.message);
                    });
                    break;
            }
        });

        let ovtStatusBar = appvTabs.cells("a").attachStatusBar();
        function ovtGridCount() {
            var ovtGridRows = ovtGrid.getRowsNum();
            ovtStatusBar.setText("Total baris: " + ovtGridRows + " (" + legend.approval_overtime + ")");
        }

        appvTabs.cells("a").progressOn();
        var ovtGrid = appvTabs.cells("a").attachGrid();
        ovtGrid.setImagePath("./public/codebase/imgs/");
        ovtGrid.setHeader("No,Task ID,Sub Unit,Bagian,,Kebutuhan Orang,Status Hari,Tanggal Overtime,Waktu Mulai, Waktu Selesai,Catatan,Makan,Steam,AHU,Compressor,PW,Jemputan,Dust Collector,WFI,Mekanik,Listrik,H&N,QC,QA,Penandaan,GBK,GBB,Status Overtime,,Approval ASMAN,Approval PPIC,Approval MANAGER,Approval PLANT MANAGER,Revisi Jam Lembur,Rejection User Approval,Created By,Updated By,Created At,NIPSPV,NIPASMAN,NIPPPIC,NIPMGR");
        ovtGrid.attachHeader("#rspan,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        ovtGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        ovtGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        ovtGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        ovtGrid.setInitWidthsP("5,20,20,20,0,10,10,15,15,15,25,7,7,7,7,7,7,10,7,7,7,7,7,7,7,7,7,10,0,30,30,30,30,30,30,15,15,22,0,0,0,0");
        ovtGrid.enableSmartRendering(true);
        ovtGrid.attachEvent("onXLE", function() {
            appvTabs.cells("a").progressOff();
        });
        ovtGrid.attachEvent("onRowDblClicked", function(rId, cInd){
            appvTabs.cells("b").setText("Detail Lembur : " + ovtGrid.cells(rId, 1).getValue());
            appvTabs.cells("b").expand();
            rOvtDetailGrid(rId);
        });
        ovtGrid.attachEvent("onRowSelect", function(rId, cIdn) {
            if(ovtGrid.cells(rId, 22).getValue() === 'REJECTED') {
                disableAppvToolbar();
            } else {
                let ovtDepartment = cleanSC(ovtGrid.cells(rId, 2).getValue());
                let ovtSubDepartment = cleanSC(ovtGrid.cells(rId, 3).getValue());
                let ovtDivision = cleanSC(ovtGrid.cells(rId, 4).getValue());
                if((userLogged.picOvertime && userLogged.rankId <= 6) || userLogged.pltRankId <= 6) {
                    if((ovtGrid.cells(rId, 31).getValue() !== "-" || ovtGrid.cells(rId, 41).getValue() === "-") && ovtGrid.cells(rId, 30).getValue() !== "-") {  //Approval MGR
                        if(ovtGrid.cells(rId, 41).getValue() === "-") {
                            if(ovtGrid.cells(rId, 28).getValue() === "-") {
                                //Approval SPV
                                if(userLogged.rankId == 5 && userLogged.division == ovtDivision || userLogged.rankId == 6 && userLogged.division == ovtDivision ||
                                  userLogged.pltRankId == 5 && userLogged.pltDivision == ovtDivision || userLogged.pltRankId == 6 && userLogged.pltDivision == ovtDivision) {
                                    enableAppvToolbar();
                                } else {
                                    disableAppvToolbar();
                                }
                            } else if(ovtGrid.cells(rId, 29).getValue() === "-" || ovtGrid.cells(rId, 30).getValue() === "-") {
                                //Approval ASMAN / PPIC
                                if(userLogged.rankId == 3 && userLogged.subDepartment == ovtSubDepartment || userLogged.rankId == 4 && userLogged.subDepartment == ovtSubDepartment ||
                                   userLogged.pltRankId == 3 && userLogged.pltSubDepartment == ovtSubDepartment || userLogged.pltRankId == 4 && userLogged.pltSubDepartment == ovtSubDepartment){
                                    enableAppvToolbar();
                                } else {
                                    disableAppvToolbar();
                                }
                            } else {
                                if(userLogged.rankId == 1 || userLogged.pltRankId == 1) {
                                    enableAppvToolbar();
                                } else {
                                    disableAppvToolbar();
                                }
                            }
                        } else {
                            if(userLogged.rankId == 1 || userLogged.pltRankId == 1) {
                                enableAppvToolbar();
                            } else {
                                disableAppvToolbar();
                            }
                        }
                    } else if(ovtGrid.cells(rId, 30).getValue() !== "-" || ovtGrid.cells(rId, 40).getValue() === "-") {  //Approval PPIC
                        if(ovtGrid.cells(rId, 40).getValue() === "-") {
                            if(ovtGrid.cells(rId, 28).getValue() === "-") {
                                //Approval SPV
                                if(userLogged.rankId == 5 && userLogged.division == ovtDivision || userLogged.rankId == 6 && userLogged.division == ovtDivision ||
                                   userLogged.pltRankId == 5 && userLogged.pltDivision == ovtDivision || userLogged.pltRankId == 6 && userLogged.pltDivision == ovtDivision) {
                                    enableAppvToolbar();
                                } else {
                                    disableAppvToolbar();
                                }
                            } else if(ovtGrid.cells(rId, 29).getValue() === "-") {
                                //Approval ASMAN
                                if(userLogged.rankId == 3 && userLogged.subDepartment == ovtSubDepartment || userLogged.rankId == 4 && userLogged.subDepartment == ovtSubDepartment ||
                                   userLogged.pltRankId == 3 && userLogged.pltSubDepartment == ovtSubDepartment || userLogged.pltRankId == 4 && userLogged.pltSubDepartment == ovtSubDepartment){
                                    enableAppvToolbar();
                                } else {
                                    disableAppvToolbar();
                                }
                            } else if(ovtGrid.cells(rId, 31).getValue() === "-") {
                                //Approval Manager
                                if((userLogged.rankId == 2 && userLogged.department == ovtDepartment) || (userLogged.pltRankId == 2 && userLogged.pltDepartment == ovtDepartment)){
                                    enableAppvToolbar();
                                } else {
                                    disableAppvToolbar();
                                }
                            } else {
                                disableAppvToolbar();
                            }
                        } else {
                            if((userLogged.rankId == 2 && userLogged.department == ovtDepartment) || (userLogged.pltRankId == 2 && userLogged.pltDepartment == ovtDepartment)) {
                                enableAppvToolbar();
                            } else {
                                disableAppvToolbar();
                            }
                        }
                    }else if(ovtGrid.cells(rId, 29).getValue() !== "-" || ovtGrid.cells(rId, 39).getValue() === "-") {  //Approval ASMAN
                        if(ovtGrid.cells(rId, 39).getValue() === "-") {
                            if(ovtGrid.cells(rId, 28).getValue() === "-") {
                                //Approval SPV
                                if(userLogged.rankId == 5 && userLogged.division == ovtDivision || userLogged.rankId == 6 && userLogged.division == ovtDivision ||
                                   userLogged.pltRankId == 5 && userLogged.pltDivision == ovtDivision || userLogged.pltRankId == 6 && userLogged.pltDivision == ovtDivision) {
                                    enableAppvToolbar();
                                } else {
                                    disableAppvToolbar();
                                }
                            } else {
                                disableAppvToolbar();
                            }
                        } else {
                            if((userLogged.rankId == 2 && userLogged.department == ovtDepartment) || (userLogged.pltRankId == 2 && userLogged.pltDepartment == ovtDepartment)) {
                                if(ovtGrid.cells(rId, 40).getValue() === "-") {
                                    enableAppvToolbar();
                                } else {
                                    disableAppvToolbar();
                                }
                            } else if((userLogged.rankId == 3 || userLogged.rankId == 4) && userLogged.subId == 9 && ovtGrid.cells(rId, 30).getValue() === "-"){
                                enableLimitAppvToolbar();
                            } else {
                                disableAppvToolbar();
                            }
                        }
                    } else if(ovtGrid.cells(rId, 28).getValue() !== "-" || ovtGrid.cells(rId, 38).getValue() === "-") { //Approval SPV
                        if(userLogged.rankId == 3 && userLogged.subDepartment == ovtSubDepartment || userLogged.rankId == 4 && userLogged.subDepartment == ovtSubDepartment ||
                           userLogged.pltRankId == 3 && userLogged.pltSubDepartment == ovtSubDepartment || userLogged.pltRankId == 4 && userLogged.pltSubDepartment == ovtSubDepartment){
                            enableAppvToolbar();
                        } else {
                            disableAppvToolbar();
                        }
                    } else {
                        if(userLogged.rankId == 5 && userLogged.division == ovtDivision || userLogged.rankId == 6 && userLogged.division == ovtDivision ||
                           userLogged.pltRankId == 5 && userLogged.pltDivision == ovtDivision || userLogged.pltRankId == 6 && userLogged.pltDivision == ovtDivision) {
                            enableAppvToolbar();
                        } else {
                            disableAppvToolbar();
                        }
                    }
                } else {
                    disableAppvToolbar();
                }
            }
        });

        ovtGrid.init();

        function disableAppvToolbar() {
            appvToolbar.disableItem("approve");
            appvToolbar.disableItem("reject");
            appvToolbar.disableItem("revision");
            appvToolbar.disableItem("hour_revision");
        }
        
        function enableAppvToolbar() {
            appvToolbar.enableItem("approve");
            appvToolbar.enableItem("reject");
            appvToolbar.enableItem("revision");
            appvToolbar.enableItem("hour_revision");
        }

        function enableLimitAppvToolbar() {
            appvToolbar.enableItem("approve");
            appvToolbar.enableItem("reject");
            appvToolbar.disableItem("revision");
            appvToolbar.disableItem("hour_revision");
        }

        function rOvtGrid() {
            appvTabs.cells("a").progressOn();
            enableAppvToolbar();
            let start = $("#other_start_ovt_appv").val();
            let end = $("#other_end_ovt_appv").val();
            let status = $("#other_status_ovt_appv").val();
            let params = {
                notin_status: "CANCELED,CREATED,CLOSED,ADD", 
                betweendate_overtime_date: start+","+end
            };

            if(status !== "ALL") {
                params.equal_status = status;
            }

            if(userLogged.role !== "admin" && userLogged.rankId != 1 && userLogged.pltRankId != 1) {
                if(userLogged.rankId == 2 || userLogged.pltRankId == 2) {
                    params.in_department_id = userLogged.deptId+","+userLogged.pltDeptId;
                }else if(userLogged.rankId > 2 || userLogged.pltRankId > 2) {
                    if(userLogged.rankId == 3 || userLogged.pltRankId == 3 || userLogged.rankId == 4 || userLogged.pltRankId == 4) {
                        if(userLogged.subId == 9) {
                            params.in_sub_department_id = userLogged.subId+","+userLogged.pltSubId+",1,2,3,4,13";
                        } else {
                            params.in_sub_department_id = userLogged.subId+","+userLogged.pltSubId;
                        }
                    } else {
                        params.in_sub_department_id = userLogged.subId+","+userLogged.pltSubId;
                    }
                }
            }
            ovtGrid.clearAndLoad(Overtime("getAppvOvertimeGrid", params), ovtGridCount);
        }

        rOvtGrid();

        var dtlToolbar;
        if(userLogged.rankId == 5 || userLogged.rankId == 6 || userLogged.pltRankId == 5 || userLogged.pltRankId == 6) {
            dtlToolbar = [
                {id: "approve_detail", text: "Approve (SPV)", type: "button", img: "ok.png"},
                {id: "reject", text: "Batalkan Lembur Personil", type: "button", img: "messagebox_critical.png"},
                {id: "rollback", text: "Kembalikan Ke Lemburan", type: "button", img: "refresh.png"},
                {id: "hour_revision", text: "Revisi Waktu Lemburan", type: "button", img: "clock.png"},
            ];
        } else {
            dtlToolbar = [
                {id: "reject", text: "Batalkan Lembur Personil", type: "button", img: "messagebox_critical.png"},
                {id: "rollback", text: "Kembalikan Ke Lemburan", type: "button", img: "refresh.png"},
                {id: "hour_revision", text: "Revisi Waktu Lemburan", type: "button", img: "clock.png"},
            ]
        }
        var appvDetailToolbar = appvTabs.cells("b").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: dtlToolbar
        });
 
        appvDetailToolbar.attachEvent("onClick", function(id) {
            if(!ovtDetailGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan batalkan!");
            }
            let empName = ovtDetailGrid.cells(ovtDetailGrid.getSelectedRowId(), 2).getValue();
            let empTaskId = ovtDetailGrid.cells(ovtDetailGrid.getSelectedRowId(), 1).getValue();
            switch (id) {
                case "approve_detail":
                    dhtmlx.modalbox({
                        type: "alert-warning",
                        title: "Approve Lembur Personil",
                        text: "Anda yakin akan approve lembur " + empName + "?",
                        buttons: ["Ya", "Tidak"],
                        callback: function (index) {
                            if (index == 0) {
                                reqJson(Overtime("approvePersonilOvertime"), "POST", {empTaskId}, (err, res) => {
                                    if(res.status === "success") {
                                        rOvtDetailGrid(ovtGrid.getSelectedRowId());
                                    }
                                    sAlert(res.message);
                                });
                            }
                        },
                    });
                    break;
                case "reject":
                    dhtmlx.modalbox({
                        type: "alert-error",
                        title: "Batalkan Lembur Personil",
                        text: "Anda yakin akan membatalkan lembur " + empName + "?",
                        buttons: ["Ya", "Tidak"],
                        callback: function (index) {
                            if (index == 0) {
                                reqJson(Overtime("rejectPersonilOvertime"), "POST", {empTaskId}, (err, res) => {
                                    if(res.status === "success") {
                                        rOvtDetailGrid(ovtGrid.getSelectedRowId());
                                    }
                                    sAlert(res.message);
                                });
                            }
                        },
                    });
                    break;
                case "rollback":
                    dhtmlx.modalbox({
                        type: "alert-warning",
                        title: "Rollback Lembur Personil",
                        text: "Anda yakin akan mengembalikan " + empName + " ke daftar lemburan?",
                        buttons: ["Ya", "Tidak"],
                        callback: function (index) {
                            if (index == 0) {
                                reqJson(Overtime("rollbackPersonilOvertime"), "POST", {empTaskId}, (err, res) => {
                                    if(res.status === "success") {
                                        rOvtDetailGrid(ovtGrid.getSelectedRowId());
                                        sAlert(res.message);
                                    } else {
                                        eaAlert("Kesalahan Waktu Lembur", res.message);
                                    }
                                });
                            }
                        },
                    });
                    break;
                case "hour_revision":
                    if(!ovtDetailGrid.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan di revisi!");
                    }

                    var hourDetailRevWin = createWindow("hour_revision_detail", "Revisi Waktu Lembur", 510, 280);
                    myWins.window("hour_revision_detail").skipMyCloseEvent = true;

                    let ovtTime = getCurrentTime(ovtGrid, 8, 9);
                    let startWinIndex = times.filterTime.indexOf(ovtTime.start);
                    let endWinIndex = times.filterTime.indexOf(ovtTime.end);
                        
                    var workTime = genWorkTime(times.times, startWinIndex, endWinIndex);

                    var labelStartDetail = ovtTime.labelStart;
                    var labelEndDetail = ovtTime.labelEnd;
                    var hourDetailRevForm = hourDetailRevWin.attachForm([
                        {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Jam Lembur", list:[	
                            {type: "block", list: [
                                {type: "hidden", name: "id", label: "ID", labelWidth: 130, inputWidth: 250, value: ovtDetailGrid.getSelectedRowId()},                               
                                {type: "hidden", name: "labelStartDetail", label: "Start Date", labelWidth: 130, inputWidth: 250, value: labelStartDetail},                               
                                {type: "combo", name: "start_date", label: "<span id='labelStartDetail'>"+labelStartDetail+"</span>", labelWidth: 130, inputWidth: 250, required: true, readonly: true,
                                    validate: "NotEmpty", 
                                    options: workTime.newStartTime
                                },
                                {type: "hidden", name: "labelEndDetail", label: "End Date", labelWidth: 130, inputWidth: 250, value: labelEndDetail},                               
                                {type: "combo", name: "end_date", label: "<span id='labelEndDetail'>"+labelEndDetail+"</span>", labelWidth: 130, inputWidth: 250, required: true, readonly: true,
                                    validate: "NotEmpty", 
                                    options: workTime.newEndTime
                                }
                            ]},
                        ]},
                        {type: "newcolumn"},
                        {type: "block", offsetLeft: 30, offsetTop: 10, list: [
                            {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Update"},
                            {type: "newcolumn"},
                            {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                        ]},
                    ]);

                    let startDetailCombo = hourDetailRevForm.getCombo("start_date");
                    let endDetailCombo = hourDetailRevForm.getCombo("end_date");
                    let ovtDetailTime = getCurrentTime(ovtDetailGrid, 11, 12);
                    let startCurrWinIndex = workTime.filterStart.indexOf(ovtDetailTime.start);
                    let endCurrWinIndex = workTime.filterEnd.indexOf(ovtDetailTime.end);
                    startDetailCombo.selectOption(startCurrWinIndex);
                    endDetailCombo.selectOption(endCurrWinIndex);

                    hourDetailRevForm.attachEvent("onChange", function(name, value) {
                        if(name === "start_date" || name === "end_date") {
                            dateChangeDetail(workTime.filterStart.indexOf(startDetailCombo.getSelectedValue()), workTime.filterEnd.indexOf(endDetailCombo.getSelectedValue()));
                            checkRevisionTime(times.filterTime, startDetailCombo.getSelectedValue(), endDetailCombo.getSelectedValue(), ['update'], hourDetailRevForm);
                        }
                    });

                    function dateChangeDetail(start, end) {
                        let startMiddle = workTime.filterStart.indexOf("23:30");
                        let endMiddle = workTime.filterEnd.indexOf("00:00");
                        if(start > startMiddle) {
                            hourDetailRevForm.setItemValue("labelStartDetail", labelEndDetail);
                            $("#labelStartDetail").html(labelEndDetail);
                        } else {
                            hourDetailRevForm.setItemValue("labelStartDetail", labelStartDetail);
                            $("#labelStartDetail").html(labelStartDetail);
                        }
                        if(end >= endMiddle) {
                            hourDetailRevForm.setItemValue("labelEndDetail", labelEndDetail);
                            $("#labelEndDetail").html(labelEndDetail);
                        } else {
                            hourDetailRevForm.setItemValue("labelEndDetail", labelStartDetail);
                            $("#labelEndDetail").html(labelStartDetail);
                        }
                    }

                    dateChangeDetail(workTime.filterStart.indexOf(startDetailCombo.getSelectedValue()), workTime.filterEnd.indexOf(endDetailCombo.getSelectedValue()));
                    checkRevisionTime(times.filterTime, startDetailCombo.getSelectedValue(), endDetailCombo.getSelectedValue(), ['update'], hourDetailRevForm);

                    hourDetailRevForm.attachEvent("onButtonClick", function(id) {
                        switch (id) {
                            case "update":
                                setDisable(["update", "cancel"], hourDetailRevForm, hourDetailRevWin);
                                let hourDetailRevFormDP = new dataProcessor(Overtime("updateOvertimeDetailHour"));
                                hourDetailRevFormDP.init(hourDetailRevForm);
                                hourDetailRevForm.save();

                                hourDetailRevFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                                    let message = tag.getAttribute("message");
                                    switch (action) {
                                        case "updated":
                                            rOvtDetailGrid(ovtGrid.getSelectedRowId());
                                            sAlert(message);
                                            setEnable(["update", "cancel"], hourDetailRevForm, hourDetailRevWin);
                                            closeWindow("hour_revision_detail");
                                            break;
                                        case "error":
                                            eaAlert("Kesalahan Waktu Lembur", message);
                                            setEnable(["update", "cancel"], hourDetailRevForm, hourDetailRevWin);
                                            break;
                                    }
                                });
                                break;
                            case "cancel":
                                closeWindow("hour_revision_detail");
                                break;
                        }
                    })
                    break;
            }
        });

        var ovtDetailGrid = appvTabs.cells("b").attachGrid();
        ovtDetailGrid.setImagePath("./public/codebase/imgs/");
        ovtDetailGrid.setHeader("No,Task ID,Nama Karyawan,Tugas,Sub Unit,Bagian,Sub Bagian,Nama Mesin,,Pelayanan Produksi,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Status Hari,Jam Efektif,Jam Istirahat,Jam Ril,Jam Hit,Premi,Nominal Overtime,Makan,Status Overtime,Status Terakhir,Spv Approval,Created By,Updated By,Created At,");
        ovtDetailGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#select_filter,#select_filter,#text_filter,#text_filter")
        ovtDetailGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        ovtDetailGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        ovtDetailGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        ovtDetailGrid.setInitWidthsP("5,20,20,25,20,20,20,20,0,20,15,15,15,10,10,10,10,10,10,10,5,10,25,25,15,15,22,0");
        ovtDetailGrid.attachFooter(legend.approval_overtime_spv+",#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,,<div id='other_total_ovt_appv'></div>,,,,,,,,");
        ovtDetailGrid.enableSmartRendering(true);
        ovtDetailGrid.attachEvent("onXLE", function() {
            appvTabs.cells("b").progressOff();
        });
        ovtDetailGrid.attachEvent("onRowSelect", function(rId, cIdn){
            if(ovtDetailGrid.cells(rId, 21).getValue() === "REJECTED") {
                if(ovtDetailGrid.cells(rId, 27).getValue() == userLogged.empNip) {
                    if(ovtGrid.cells(ovtGrid.getSelectedRowId(), 27).getValue() === "REJECTED") {
                        disableAppvDetailToolbar();
                    } else {
                        if(userLogged.rankId == 5 || userLogged.rankId == 6 || userLogged.pltRankId == 5 || userLogged.pltRankId == 6) {
                            appvDetailToolbar.disableItem("approve_detail");
                        }
                        appvDetailToolbar.disableItem("reject");
                        appvDetailToolbar.enableItem("rollback");
                        appvDetailToolbar.disableItem("hour_revision");
                    }
                } else {
                    disableAppvDetailToolbar();
                }
            } else {
                if((userLogged.picOvertime && userLogged.rankId <= 6) || userLogged.pltRankId <= 6) {
                    if(ovtGrid.cells(ovtGrid.getSelectedRowId(), 31).getValue() !== "-" || ovtGrid.cells(ovtGrid.getSelectedRowId(), 41).getValue() === "-") { //Approval MGR
                        if(ovtGrid.cells(ovtGrid.getSelectedRowId(), 41).getValue() === "-") {
                            if(ovtGrid.cells(ovtGrid.getSelectedRowId(), 28).getValue() === "-") {
                                if(userLogged.rankId == 5 || userLogged.rankId == 6 || userLogged.pltRankId == 5 || userLogged.pltRankId == 6) {
                                    enableAppvDetailToolbar();
                                } else {
                                    disableAppvDetailToolbar();
                                }
                            } else if(ovtGrid.cells(ovtGrid.getSelectedRowId(), 29).getValue() === "-" || ovtGrid.cells(ovtGrid.getSelectedRowId(), 30).getValue() === "-") {
                                if(userLogged.rankId == 3 || userLogged.rankId == 4 || userLogged.pltRankId == 3 || userLogged.pltRankId == 4){
                                    enableAppvDetailToolbar();
                                } else if(userLogged.rankId == 5 || userLogged.rankId == 6 || userLogged.pltRankId == 5 || userLogged.pltRankId == 6){
                                    if(ovtDetailGrid.cells(ovtDetailGrid.getSelectedRowId(), 6).getValue() != userLogged.division) {
                                        disableAppvDetailToolbar();
                                    } else {
                                        enableAppvDetailToolbar();
                                    }
                                } else {
                                    disableAppvDetailToolbar();
                                }
                            } else {
                                if(userLogged.rankId == 1 || userLogged.pltRankId == 1) {
                                    enableAppvDetailToolbar();
                                } else {
                                    disableAppvDetailToolbar();
                                }
                            }
                        } else {
                            if(userLogged.rankId == 1 || userLogged.pltRankId == 1) {
                                enableAppvDetailToolbar();
                            } else {
                                disableAppvDetailToolbar();
                            }
                        }
                    } else if(ovtGrid.cells(ovtGrid.getSelectedRowId(), 30).getValue() !== "-" || ovtGrid.cells(ovtGrid.getSelectedRowId(), 40).getValue() === "-") { //Approval PPIC
                        if(ovtGrid.cells(ovtGrid.getSelectedRowId(), 40).getValue() === "-") {
                            if(ovtGrid.cells(ovtGrid.getSelectedRowId(), 28).getValue() === "-") {
                                if(userLogged.rankId == 5 || userLogged.rankId == 6 || userLogged.pltRankId == 5 || userLogged.pltRankId == 6) {
                                    enableAppvDetailToolbar();
                                } else {
                                    disableAppvDetailToolbar();
                                }
                            } else if(ovtGrid.cells(ovtGrid.getSelectedRowId(), 29).getValue() === "-") {
                                if(userLogged.rankId == 3 || userLogged.rankId == 4 || userLogged.pltRankId == 3 || userLogged.pltRankId == 4){
                                    enableAppvDetailToolbar();
                                } else if(userLogged.rankId == 5 || userLogged.rankId == 6 || userLogged.pltRankId == 5 || userLogged.pltRankId == 6){
                                    if(ovtDetailGrid.cells(ovtDetailGrid.getSelectedRowId(), 6).getValue() != userLogged.division) {
                                        disableAppvDetailToolbar();
                                    } else {
                                        enableAppvDetailToolbar();
                                    }
                                } else {
                                    disableAppvDetailToolbar();
                                }
                            } else if(ovtGrid.cells(ovtGrid.getSelectedRowId(), 31).getValue() === "-") {
                                if(userLogged.rankId == 2 || userLogged.pltRankId == 2){
                                    enableAppvDetailToolbar();
                                } else {
                                    disableAppvDetailToolbar();
                                }
                            }
                        } else {
                            if(userLogged.rankId == 2 || userLogged.pltRankId == 2) {
                                enableAppvDetailToolbar();
                            } else {
                                disableAppvDetailToolbar();
                            }
                        }
                    } else if(ovtGrid.cells(ovtGrid.getSelectedRowId(), 29).getValue() !== "-" || ovtGrid.cells(ovtGrid.getSelectedRowId(), 39).getValue() === "-") { //Approval ASMAN
                        if(ovtGrid.cells(ovtGrid.getSelectedRowId(), 39).getValue() === "-") {
                            if(ovtGrid.cells(ovtGrid.getSelectedRowId(), 28).getValue() === "-") {
                                if(userLogged.rankId == 5 || userLogged.rankId == 6 || userLogged.pltRankId == 5 || userLogged.pltRankId == 6) {
                                    enableAppvDetailToolbar();
                                } else {
                                    disableAppvDetailToolbar();
                                }
                            }
                        } else {
                            if(userLogged.rankId == 2 || userLogged.pltRankId == 2) {
                                if(ovtGrid.cells(ovtGrid.getSelectedRowId(), 40).getValue() === "-") {
                                    enableAppvDetailToolbar();
                                } else {
                                    disableAppvDetailToolbar();
                                }
                            }
                        }
                    } else if(ovtGrid.cells(ovtGrid.getSelectedRowId(), 28).getValue() !== "-" || ovtGrid.cells(ovtGrid.getSelectedRowId(), 38).getValue() === "-") { //Approval SPV
                        if(userLogged.rankId == 3 || userLogged.rankId == 4 || userLogged.pltRankId == 3 || userLogged.pltRankId == 4){
                            enableAppvDetailToolbar();
                        } else if(userLogged.rankId == 5 || userLogged.rankId == 6 || userLogged.pltRankId == 5 || userLogged.pltRankId == 6){
                            if(ovtDetailGrid.cells(ovtDetailGrid.getSelectedRowId(), 6).getValue() != userLogged.division) {
                                disableAppvDetailToolbar();
                            } else {
                                enableAppvDetailToolbar();
                            }
                        } else {
                            disableAppvDetailToolbar();
                        }
                    } else {
                        if(userLogged.rankId == 5 || userLogged.rankId == 6 || userLogged.pltRankId == 5 || userLogged.pltRankId == 6) {
                            enableAppvDetailToolbar();
                        } else {
                            disableAppvDetailToolbar();
                        }
                    }
                }
            }
        });
        ovtDetailGrid.init();

        function disableAppvDetailToolbar() {
            if(userLogged.rankId == 5 || userLogged.rankId == 6 || userLogged.pltRankId == 5 || userLogged.pltRankId == 6) {
                appvDetailToolbar.disableItem("approve_detail");
            }
            appvDetailToolbar.disableItem("reject");
            appvDetailToolbar.disableItem("rollback");
            appvDetailToolbar.disableItem("hour_revision");
        }

        function enableAppvDetailToolbar() {
            if(userLogged.rankId == 5 || userLogged.rankId == 6 || userLogged.pltRankId == 5 || userLogged.pltRankId == 6) {
                appvDetailToolbar.enableItem("approve_detail");
            }
            appvDetailToolbar.enableItem("reject");
            appvDetailToolbar.disableItem("rollback");
            appvDetailToolbar.enableItem("hour_revision");
        }
        
        function rOvtDetailGrid(rId) {
            if(rId) {
                appvTabs.cells("b").progressOn();
                disableAppvDetailToolbar();
                let taskId = ovtGrid.cells(rId, 1).getValue();
                ovtDetailGrid.clearAndLoad(Overtime("getOvertimeDetailGrid", {equal_task_id: taskId, notin_status: "CANCELED,ADD", order_by: 'id:asc', apv: true}), countTotalOvertime);
            } else {
                ovtDetailGrid.clearAll();
                ovtDetailGrid.callEvent("onGridReconstructed",[]);
                $("#other_total_ovt_appv").html("0");
            }
        }

        function countTotalOvertime() {
            sumGridToElement(ovtDetailGrid, 19, "other_total_ovt_appv");
        }
    }

JS;

header('Content-Type: application/javascript');
echo $script;