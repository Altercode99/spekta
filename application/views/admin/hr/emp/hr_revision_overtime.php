<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	function showHrRevisionOvertime() {	
        var legend = legendGrid();
        var revLayout = mainTab.cells("hr_revision_overtime").attachLayout({
            pattern: "3J",
            cells: [
                {id: "a", text: "Daftar Permintaan Revisi Lembur"},
                {id: "b", text: "Instruksi Revisi", collapse: true, width: 450},
                {id: "c", text: "Detail Permintaan Revisi", collapse: true},
            ]
        });

        let currentDate = filterForMonth(new Date());
        var revMenu = revLayout.cells("a").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "search", text: "<div style='width:100%'>Search: <input type='text' id='hr_start_ovt_rev' readonly value='"+currentDate.start+"' /> - <input type='text' id='hr_end_ovt_rev' readonly value='"+currentDate.end+"' /> <button id='hr_btn_ftr_ovt_rev'>Proses</button> | Status: <select id='hr_status_ovt_rev'><option>ALL</option><option selected value='ACTIVE'>AKTIF (CREATED & PROCESS)</option><option>PROCESS</option><option>REJECTED</option><option>CLOSED</option></select></div>"}
            ]
        });

        $("#hr_btn_ftr_ovt_rev").on("click", function() {
            if(checkFilterDate($("#hr_start_ovt_rev").val(), $("#hr_end_ovt_rev").val())) {
                rRevGrid();
            }
        });

        $("#hr_status_ovt_rev").on("change", function() {
            rRevGrid();
        });

        var revToolbar = revLayout.cells("a").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "process", text: "Proses Permintaan Revisi", type: "button", img: "ok.png"},
                {id: "reject", text: "Tolak Permintaan Revisi", type: "button", img: "messagebox_critical.png"},
                {id: "attachment", text: "Lihat Attachment", type: "button", img: "attachment.png"},
            ]
        });

        revToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    rRevGrid();
                    rRevDetailGrid();
                    break;
                case "process":
                    reqConfirm(revGrid, Overtime("processRevision"), 1, (err, res) => {
                        rRevGrid();
                        rRevDetailGrid();
                        res.mSuccess && sAlert("Sukses Memproses Permintaan Revisi<br>" + res.mSuccess);
                        res.mError && eAlert("Gagal Memproses Permintaan Revisi<br>" + res.mError);
                    });
                    break;
                case "reject":
                    reqAction(revGrid, Overtime("rejectRevision"), 1, (err, res) => {
                        rRevGrid();
                        rRevDetailGrid();
                        res.mSuccess && sAlert("Sukses Menolak Permintaan Revisi<br>" + res.mSuccess);
                        res.mError && eAlert("Gagal Menolak Permintaan Revisi<br>" + res.mError);
                    });
                    break;
                case "attachment":
                    if(!revGrid.getSelectedRowId()) {
                        return eAlert("Pilih permintaan revisi!");
                    }
                    reqJson(Overtime("viewAttachment"), "POST", {taskId: revGrid.getSelectedRowId()}, (err, res) => {
                        if(res.status === "success") {
                            var attachWin = createWindow("hr_ovt_rev_attachment", "Attachment Revisi: " + revGrid.getSelectedRowId(), 800, 500);
                            myWins.window("hr_ovt_rev_attachment").skipMyCloseEvent = true;
                            attachWin.attachHTMLString(res.template);
                        }
                    });
                    break;
            }
        })

        var revStatusBar = revLayout.cells("a").attachStatusBar();
        function revGridCount() {
            let revGridRows = revGrid.getRowsNum();
            revStatusBar.setText("Total baris: " + revGridRows + " (" + legend.revision_overtime + ")");
        }

        var revGrid = revLayout.cells("a").attachGrid();
        revGrid.setImagePath("./public/codebase/imgs/");
        revGrid.setHeader("No,Task ID,Deskripsi,Bagian,Sub Bagian,Status,Created By,Updated By,DiBuat");
        revGrid.attachHeader("#rspan,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter")
        revGrid.setColSorting("int,str,str,str,str,str,str,str,str");
        revGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        revGrid.setColAlign("center,left,left,left,left,left,left,left,left");
        revGrid.setInitWidthsP("5,20,35,20,20,20,20,20,25");
        revGrid.enableMultiselect(true);
        revGrid.enableSmartRendering(true);
        revGrid.attachEvent("onRowSelect", function(rId, cIdn) {
            let status = revGrid.cells(rId, 5).getValue();
            if(status === 'CLOSED' || status === 'REJECTED') {
                revToolbar.disableItem("process");
                revToolbar.disableItem("reject");
                revDetailToolbar.disableItem("revision");
                revDetailToolbar.disableItem("closed");
            } else {
                if(status === "PROCESS") {
                    revToolbar.disableItem("process");
                    revToolbar.enableItem("reject");
                } else {
                    revToolbar.enableItem("process");
                    revToolbar.enableItem("reject");
                }
            }
        });
        revGrid.attachEvent("onRowDblClicked", function(rId, cIdn) {
            rRevDetailGrid(rId);
        });
        revGrid.attachEvent("onXLE", function() {
            revLayout.cells("a").progressOff();
        });
        revGrid.init();

        function rRevGrid() {
            revLayout.cells("a").progressOn();
            let start = $("#hr_start_ovt_rev").val();
            let end = $("#hr_end_ovt_rev").val();
            let status = $("#hr_status_ovt_rev").val();
            let params = {
                betweendate_created_at: start+","+end
            };

            if(status != "ALL") {
                if(status == 'ACTIVE') {
                    params.in_status = "CREATED,PROCESS";
                } else {
                    params.equal_status = status;
                }
            }
            revLayout.cells("b").attachHTMLString("<div style='width:100%;height:100%;display:flex;flex-direction:row;justify-content:center;align-items:center;'><p style='font-family:sans-serif'>Tidak ada revisi dipilih</p></div>");
            revGrid.clearAndLoad(Overtime("getRevOvtGrid", params), revGridCount);
        }

        var revDetailGrid = revLayout.cells("c").attachGrid();
        revDetailGrid.setImagePath("./public/codebase/imgs/");
        revDetailGrid.setHeader("No,Task ID,,Created By,Updated By,DiBuat");
        revDetailGrid.setHeader("No,Task ID,Nama Karyawan,Bagian,Sub Bagian,Nama Mesin #1,Nama Mesin #2,Pelayanan Produksi,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Status Hari,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Premi,Nominal Overtime,Makan,Biaya Makan,Tugas,Status Overtime,DiBuat");
        revDetailGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        revDetailGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        revDetailGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        revDetailGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        revDetailGrid.setInitWidthsP("5,20,20,20,20,20,20,20,20,20,20,15,15,15,15,15,15,15,15,15,30,25");
        revDetailGrid.enableSmartRendering(true);
        revDetailGrid.enableMultiselect(true);
        revDetailGrid.attachEvent("onXLE", function() {
            revLayout.cells("c").progressOff();
        });
        revDetailGrid.attachEvent("onRowSelect", function(rId, cInd) {
            if(revGrid.cells(revGrid.getSelectedRowId(), 5).getValue() != "PROCESS") {
                revDetailToolbar.disableItem("revision");
                revDetailToolbar.disableItem("closed");
            } else {
                revDetailToolbar.enableItem("revision");
                revDetailToolbar.enableItem("closed");
            }
        });
        revDetailGrid.init();

        function rRevDetailGrid(taskId = null) {
            if(taskId) {
                revLayout.cells("c").progressOn();
                revLayout.cells("c").expand();
                revDetailGrid.clearAndLoad(Overtime("getRevOvtDtlGrid", {taskId}));
                revLayout.cells("b").expand();
                reqJson(Overtime("getRevision"), "POST", {taskId}, (err, res) => {
                    var revForm = revLayout.cells("b").attachForm([
                        {type: "block", offsetLeft: 5, list: [
                            {type: "input", name: "task_id", label: "Task ID", labelWidth: 130, inputWidth: 385, readonly: true, required: true, value: res.revision.task_id},
                            {type: "editor", name: "description", label: "Keterangan", labelWidth: 130, inputWidth: 385, inputHeight: 200, required: true, value: res.revision.description},
                            {type: "editor", name: "response", label: "Tanggapan SDM", labelWidth: 130, inputWidth: 385, inputHeight: 210, required: true, value: res.revision.response},
                            {type: "block", offsetTop: 30, list: [
                                {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"}
                            ]}
                        ]}
                    ]);

                    if(revGrid.cells(revGrid.getSelectedRowId(), 5).getValue() == 'PROCESS' || revGrid.cells(revGrid.getSelectedRowId(), 5).getValue() == 'CREATED') {
                        setEnable(["update"], revForm);
                    } else {
                        setDisable(["update"], revForm);
                    }

                    revForm.attachEvent("onButtonClick", function (name) {
                        switch (name) {
                            case "update":
                                if(!revForm.validate()) {
                                    return eAlert("Input error!");
                                }

                                setDisable(["update"], revForm, revLayout.cells("b"));

                                let revFormDP = new dataProcessor(Overtime("updateRevOvtRes"));
                                revFormDP.init(revForm);
                                revForm.save();

                                revFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                                    let message = tag.getAttribute("message");
                                    switch (action) {
                                        case "updated":
                                            sAlert(message);
                                            setEnable(["update"], revForm, revLayout.cells("b"));
                                            break;
                                        case "error":
                                            eAlert(message);
                                            setEnable(["update"], revForm, revLayout.cells("b"));
                                            break;
                                    }
                                });
                                break;
                        }
                    })
                });
            } else {
                revLayout.cells("c").collapse();
                revLayout.cells("b").collapse();
                revDetailGrid.clearAll();
            }
        }

        var revDetailToolbar = revLayout.cells("c").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "revision", text: "Revisi Jam Lembur", type: "button", img: "clock.png"},
                {id: "closed", text: "Tutup Revisi", type: "button", img: "check.png"},
            ]
        });

        var times = createTime();

        revDetailToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "revision":
                    if(!revDetailGrid.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan di revisi!");
                    }

                    var hourRevWin = createWindow("hr_hour_revision", "Revisi Waktu Lembur", 510, 300);
                    myWins.window("hr_hour_revision").skipMyCloseEvent = true;

                    let ovtTime = getCurrentTime(revDetailGrid, 9, 10);
                        
                    let labelStart = ovtTime.labelStart;
                    let labelEnd = ovtTime.labelEnd;
                    var hourRevForm = hourRevWin.attachForm([
                        {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Jam Lembur", list:[	
                            {type: "block", list: [
                                {type: "input", name: "task_id", label: "Task ID", labelWidth: 130, inputWidth: 250, readonly: true, value: revDetailGrid.getSelectedRowId()},                               
                                {type: "combo", name: "start_date", label: labelStart, labelWidth: 130, inputWidth: 250, required: true,
                                    validate: "NotEmpty", 
                                    options: times.startTimes
                                },
                                {type: "combo", name: "end_date", label: labelEnd, labelWidth: 130, inputWidth: 250, required: true, 
                                    validate: "NotEmpty", 
                                    options: times.endTimes,
                                }
                            ]},
                        ]},
                        {type: "newcolumn"},
                        {type: "block", offsetLeft: 30, offsetTop: 10, list: [
                            {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Update"},
                            {type: "newcolumn"},
                            {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Clear"}
                        ]},
                    ]);

                    var startCombo = hourRevForm.getCombo("start_date");
                    var endCombo = hourRevForm.getCombo("end_date");
                    let startIndex = times.filterStartTime.indexOf(ovtTime.start);
                    let endIndex = times.filterEndTime.indexOf(ovtTime.end);
                    startCombo.selectOption(startIndex);
                    endCombo.selectOption(endIndex);

                    hourRevForm.attachEvent("onButtonClick", function(id) {
                        switch (id) {
                            case "update":
                                setDisable(["update", "cancel"], hourRevForm, hourRevWin);
                                let hourRevFormDP = new dataProcessor(Overtime("updateRevisionHour"));
                                hourRevFormDP.init(hourRevForm);
                                hourRevForm.save();

                                hourRevFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                                    let message = tag.getAttribute("message");
                                    switch (action) {
                                        case "updated":
                                            rRevDetailGrid(revGrid.getSelectedRowId());
                                            sAlert(message);
                                            setEnable(["update", "cancel"], hourRevForm, hourRevWin);
                                            closeWindow("hr_hour_revision");
                                            break;
                                        case "error":
                                            eaAlert("Kesalahan Waktu Lembur", message);
                                            setEnable(["update", "cancel"], hourRevForm, hourRevWin);
                                            break;
                                    }
                                });
                                break;
                            case "cancel":
                                closeWindow("hr_hour_revision");
                                break;
                        }
                    });
                    break;
                case "closed":
                    if(!revGrid.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan di tutup!");
                    }

                    dhtmlx.modalbox({
                        type: "alert-warning",
                        title: "Konfirmasi Tutup Revisi",
                        text: "Anda yakin akan menutup revisi lembur " + revGrid.getSelectedRowId() + "?",
                        buttons: ["Ya", "Tidak"],
                        callback: function (index) {
                            if (index == 0) {
                                reqJson(Overtime("closeRevision"), "POST", {taskId: revGrid.getSelectedRowId()}, (err, res) => {
                                    if(res.status === "success") {
                                        rRevGrid();
                                        rRevDetailGrid();
                                        sAlert(res.message);
                                    }
                                });
                            }
                        },
                    });
                    break;
            }
        });

        rRevGrid();
        rRevDetailGrid();
    }

JS;

header('Content-Type: application/javascript');
echo $script;