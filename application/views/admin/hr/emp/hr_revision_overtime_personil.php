<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	function showHrRevisionOvertimePersonil() {	
        var revOvtPrsForm;
        let currentDate = filterForMonth(new Date());
        var legend = legendGrid();

        var revOvtPrsLayout = mainTab.cells("hr_revision_overtime_personil").attachLayout({
            pattern: "3J",
            cells: [
                {id: "a", text: "Daftar Permintaan Revisi Personil Lembur"},
                {id: "b", text: "Instruksi Revisi", collapse: true, width: 450},
                {id: "c", text: "Detail Permintaan Revisi", collapse: true},
            ]
        });

        var revOvtListMenu = revOvtPrsLayout.cells("a").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "search", text: "<div style='width:100%'>Search: <input type='text' id='hr_start_ovt_rev_personil' readonly value='"+currentDate.start+"' /> - <input type='text' id='hr_end_ovt_rev_personil' readonly value='"+currentDate.end+"' /> <button id='hr_btn_ftr_ovt_rev_personil'>Proses</button> | Status: <select id='hr_status_ovt_rev_personil'><option>ALL</option><option selected value='ACTIVE'>AKTIF (CREATED & PROCESS)</option><option>PROCESS</option><option>REJECTED</option><option>CLOSED</option></select></div>"}
            ]
        });

        $("#hr_btn_ftr_ovt_rev_personil").on("click", function() {
            if(checkFilterDate($("#hr_start_ovt_rev_personil").val(), $("#hr_end_ovt_rev_personil").val())) {
                rRevOvtPrsGrid();
                rRevOvtPrsDetailGrid();
            }
        });

        $("#hr_status_ovt_rev_personil").on("change", function() {
            rRevOvtPrsGrid();
            rRevOvtPrsDetailGrid();
        });

        var revOvtListToolbar = revOvtPrsLayout.cells("a").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "process", text: "Proses Permintaan Revisi", type: "button", img: "ok.png"},
                {id: "reject", text: "Tolak Permintaan Revisi", type: "button", img: "messagebox_critical.png"}
            ]
        });

        var revOvtDetailListToolbar = revOvtPrsLayout.cells("c").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "approve", text: "Approve", type: "button", img: "ok.png"},
                {id: "rollback", text: "Kembalikan Status Awal", type: "button", img: "refresh.png"},
                {id: "close", text: "Tutup Revisi", type: "button", img: "check.png"},
            ]
        });

        var revOvrPrsStatusBar = revOvtPrsLayout.cells("a").attachStatusBar();
        function revOvtPrsGridCount() {
            let revOvtPrsGridRows = revOvtPrsGrid.getRowsNum();
            revOvrPrsStatusBar.setText("Total baris: " + revOvtPrsGridRows + " (" + legend.revision_overtime + ")");
        }

        var revOvtPrsGrid = revOvtPrsLayout.cells("a").attachGrid();
        revOvtPrsGrid.setImagePath("./public/codebase/imgs/");
        revOvtPrsGrid.setHeader("No,Ref Task,Task ID,Sub Unit,Bagian,Sub Bagian,Kebutuhan Orang,Status Hari,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Catatan,Created By,Updated By,DiBuat,STATUS");
        revOvtPrsGrid.attachHeader("#rspan,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        revOvtPrsGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        revOvtPrsGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        revOvtPrsGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        revOvtPrsGrid.setInitWidthsP("5,20,20,20,20,20,10,10,20,20,20,20,20,20,25,20");
        revOvtPrsGrid.enableSmartRendering(true);
        revOvtPrsGrid.attachEvent("onRowSelect", function(rId, cIdn) {
            let status = revOvtPrsGrid.cells(rId, 15).getValue();
            if(status === 'CLOSED' || status === 'REJECTED') {
                revOvtListToolbar.disableItem("process");
                revOvtListToolbar.disableItem("reject");
            } else {
                if(status === "PROCESS") {
                    revOvtListToolbar.disableItem("process");
                    revOvtListToolbar.enableItem("reject");
                } else {
                    revOvtListToolbar.enableItem("process");
                    revOvtListToolbar.enableItem("reject");
                }
            }
        });
        revOvtPrsGrid.attachEvent("onRowDblClicked", function(rId, cIdn) {
            rRevOvtPrsDetailGrid(rId);
        });
        revOvtPrsGrid.attachEvent("onRowSelect", function(rId, cIdn) {
           let status = revOvtPrsGrid.cells(rId, 15).getValue();
           if(status == 'PROCESS') {
               enableOvtDetailToolbar();
           } else {
               disableOvtDetailToolbar();
           }
        });
        revOvtPrsGrid.attachEvent("onXLE", function() {
            revOvtPrsLayout.cells("a").progressOff();
        });
        revOvtPrsGrid.init();

        function rRevOvtPrsGrid() {
            revOvtPrsLayout.cells("a").progressOn();
            let start = $("#hr_start_ovt_rev_personil").val();
            let end = $("#hr_end_ovt_rev_personil").val();
            let status = $("#hr_status_ovt_rev_personil").val();
            let params = {
                betweendate_created_at: start+","+end
            };

            if(status != "ALL") {
                if(status == 'ACTIVE') {
                    params.status = "CREATED,PROCESS";
                } else {
                    params.status = status;
                }
            }
            revOvtPrsLayout.cells("b").attachHTMLString("<div style='width:100%;height:100%;display:flex;flex-direction:row;justify-content:center;align-items:center;'><p style='font-family:sans-serif'>Tidak ada revisi dipilih</p></div>");
            revOvtPrsGrid.clearAndLoad(Overtime("getRevOvtPersonil", params), revOvtPrsGridCount);
        }

        var revOvrPrsDetailStatusBar = revOvtPrsLayout.cells("c").attachStatusBar();
        function revOvtPrsDetailGridCount() {
            let revOvtPrsDetailGridRows = revOvtPrsDetailGrid.getRowsNum();
            revOvrPrsDetailStatusBar.setText("Total baris: " + revOvtPrsDetailGridRows + " (" + legend.revision_overtime_personil + ")");
            for (let i = 0; i < revOvtPrsDetailGridRows; i++) {
                let status = revOvtPrsDetailGrid.cells2(i, 4).getValue();
                if(status == 'NONE') {
                    revOvtPrsDetailGrid.cells2(i, 1).setDisabled(true);
                    revOvtPrsDetailGrid.cells2(i, 2).setDisabled(true);
                }
            }
        }

        var revOvtPrsDetailGrid = revOvtPrsLayout.cells("c").attachGrid();
        revOvtPrsDetailGrid.setImagePath("./public/codebase/imgs/");
        revOvtPrsDetailGrid.setHeader("No,Approve,Rollback,Status,Status Revisi,,Task ID,Nama Karyawan,Sub Unit,Bagian,Sub Bagian,Nama Mesin #1,Nama Mesin #2,Pelayanan Produksi,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Status Hari,Jam Efektif,Jam Istirahat,Jam Ril,Jam Hit,Premi,Nominal Overtime,Makan,Tugas,Status Overtime,Status Terakhir,Created By,Updated By,Di Buat");
        revOvtPrsDetailGrid.attachHeader("#rspan,#master_checkbox,#master_checkbox,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        revOvtPrsDetailGrid.setColSorting("int,na,na,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        revOvtPrsDetailGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        revOvtPrsDetailGrid.setColTypes("rotxt,ch,ch,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        revOvtPrsDetailGrid.setInitWidthsP("5,7,7,10,0,0,20,20,20,20,25,25,25,25,15,15,15,10,10,10,10,10,10,10,5,25,10,30,15,15,22");
        revOvtPrsDetailGrid.enableSmartRendering(true);
        revOvtPrsDetailGrid.setEditable(true);
        revOvtPrsDetailGrid.attachEvent("onXLE", function() {
            revOvtPrsLayout.cells("c").progressOff();
        });
        revOvtPrsDetailGrid.getCombo(1).put('-','Pilih Aksi');
        revOvtPrsDetailGrid.getCombo(1).put('approve','Approve');
	    revOvtPrsDetailGrid.getCombo(1).put('rollback','Rollback');
        revOvtPrsDetailGrid.attachEvent("onRowSelect", function(rId, cIdn) {
           let status = revOvtPrsGrid.cells(revOvtPrsGrid.getSelectedRowId(), 15).getValue();
           if(status == 'PROCESS') {
               enableOvtDetailToolbar();
           } else {
               disableOvtDetailToolbar();
           }
        });
        revOvtPrsDetailGrid.init();

        function setGridDP() {
            revOvtPrsDetailGridDP = new dataProcessor(Overtime('confirmRevisionPersonil'));
            revOvtPrsDetailGridDP.setTransactionMode("POST", true);
            revOvtPrsDetailGridDP.setUpdateMode("Off");
            revOvtPrsDetailGridDP.init(revOvtPrsDetailGrid);
        }
        function setGridDP2() {
            revOvtPrsDetailGridDP2 = new dataProcessor(Overtime('rollbackRevisionPersonil'));
            revOvtPrsDetailGridDP2.setTransactionMode("POST", true);
            revOvtPrsDetailGridDP2.setUpdateMode("Off");
            revOvtPrsDetailGridDP2.init(revOvtPrsDetailGrid);
        }

        setGridDP();
        setGridDP2();

        function rRevOvtPrsDetailGrid(rId = null) {
            if(rId) {
                let taskId = revOvtPrsGrid.cells(rId, 2).getValue();
                let status =  revOvtPrsGrid.cells(rId, 15).getValue();
                revOvtPrsLayout.cells("c").progressOn();
                revOvtPrsLayout.cells("c").expand();
                if(status == 'CREATED' || status == 'PROCESS') {
                    revOvtPrsDetailGrid.clearAndLoad(Overtime("getOvertimeDetailGridRev", {equal_task_id: taskId, check: true}), revOvtPrsDetailGridCount);
                } else {
                    revOvtPrsDetailGrid.clearAndLoad(Overtime("getOvertimeDetailGridRevHistory", {equal_task_id: taskId, check: true}), revOvtPrsDetailGridCount);
                }
                revOvtPrsLayout.cells("b").expand();
                reqJson(Overtime("getPersonilRevision"), "POST", {taskId: rId}, (err, res) => {
                    revOvtPrsForm = revOvtPrsLayout.cells("b").attachForm([
                        {type: "block", offsetLeft: 5, list: [
                            {type: "input", name: "task_id", label: "Task ID", labelWidth: 130, inputWidth: 385, readonly: true, required: true, value: res.revision.task_id},
                            {type: "editor", name: "description", label: "Keterangan", labelWidth: 130, inputWidth: 385, inputHeight: 200, required: true, value: res.revision.description},
                            {type: "editor", name: "response", label: "Tanggapan SDM", labelWidth: 130, inputWidth: 385, inputHeight: 210, required: true, value: res.revision.response},
                            {type: "block", offsetTop: 30, list: [
                                {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"}
                            ]}
                        ]}
                    ]);

                    revOvtPrsForm.attachEvent("onButtonClick", function (name) {
                        switch (name) {
                            case "update":
                                if(!revOvtPrsForm.validate()) {
                                    return eAlert("Input error!");
                                }

                                setDisable(["update"], revOvtPrsForm, revOvtPrsLayout.cells("b"));

                                let revOvtPrsFormDP = new dataProcessor(Overtime("updateRevOvtPersonilRes"));
                                revOvtPrsFormDP.init(revOvtPrsForm);
                                revOvtPrsForm.save();

                                revOvtPrsFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                                    let message = tag.getAttribute("message");
                                    switch (action) {
                                        case "updated":
                                            sAlert(message);
                                            setEnable(["update"], revOvtPrsForm, revOvtPrsLayout.cells("b"));
                                            break;
                                        case "error":
                                            eAlert(message);
                                            setEnable(["update"], revOvtPrsForm, revOvtPrsLayout.cells("b"));
                                            break;
                                    }
                                });
                                break;
                        }
                    })
                }); 
            } else {
                revOvtPrsLayout.cells("c").collapse();
                revOvtPrsLayout.cells("b").collapse();
                revOvtPrsDetailGrid.clearAll();
            }
        }

        revOvtListToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    rRevOvtPrsGrid();
                    rRevOvtPrsDetailGrid();
                    break;
                case "process":
                    reqConfirm(revOvtPrsGrid, Overtime("processRevisionPersonil"), 1, (err, res) => {
                        rRevOvtPrsGrid();
                        rRevOvtPrsDetailGrid();
                        res.mSuccess && sAlert("Sukses Memproses Permintaan Revisi<br>" + res.mSuccess);
                        res.mError && eAlert("Gagal Memproses Permintaan Revisi<br>" + res.mError);
                    });
                    break;
                case "reject":
                    reqAction(revOvtPrsGrid, Overtime("rejectRevisionPersonil"), 1, (err, res) => {
                        rRevOvtPrsGrid();
                        rRevOvtPrsDetailGrid();
                        res.mSuccess && sAlert("Sukses Menolak Permintaan Revisi<br>" + res.mSuccess);
                        res.mError && eAlert("Gagal Menolak Permintaan Revisi<br>" + res.mError);
                    });
                    break;
            }
        });

        revOvtDetailListToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "approve":
                    if(!revOvtPrsDetailGrid.getChangedRows()) {
                        return eAlert("Belum ada row yang di edit!");
                    }
                    dhtmlx.modalbox({
                        type: "alert-warning",
                        title: "Approve Perubahan Personil Lembur",
                        text: "Anda yakin akan melakukan approve revisi " + revOvtPrsGrid.cells(revOvtPrsGrid.getSelectedRowId(), 1).getValue() + "?",
                        buttons: ["Ya", "Tidak"],
                        callback: function (index) {
                            if (index == 0) {
                                disableOvtDetailToolbar();
                                revOvtPrsLayout.cells("c").progressOn();
                                revOvtPrsDetailGridDP.sendData();
                                revOvtPrsDetailGridDP.attachEvent('onAfterUpdate', function(id, action, tid, tag) {
                                    let message = tag.getAttribute('message');
                                    switch (action) {
                                        case 'updated':
                                            sAlert(message);
                                            rRevOvtPrsDetailGrid(revOvtPrsGrid.getSelectedRowId());
                                            enableOvtDetailToolbar();
                                            revOvtPrsLayout.cells("c").progressOff();
                                            setGridDP();
                                            break;
                                        case 'error':
                                            eAlert(message);
                                            enableOvtDetailToolbar();
                                            revOvtPrsLayout.cells("c").progressOff();
                                            setGridDP();
                                            break;
                                    }
                                });
                            }
                        },
                    });
                    break;
                case "rollback":
                    if(!revOvtPrsDetailGrid.getChangedRows()) {
                        return eAlert("Belum ada row yang di edit!");
                    }
                    dhtmlx.modalbox({
                        type: "alert-warning",
                        title: "Approve Perubahan Personil Lembur",
                        text: "Anda yakin akan melakukan rollback revisi " + revOvtPrsGrid.cells(revOvtPrsGrid.getSelectedRowId(), 1).getValue() + "?",
                        buttons: ["Ya", "Tidak"],
                        callback: function (index) {
                            if (index == 0) {
                                disableOvtDetailToolbar();
                                revOvtPrsLayout.cells("c").progressOn();
                                revOvtPrsDetailGridDP2.sendData();
                                revOvtPrsDetailGridDP2.attachEvent('onAfterUpdate', function(id, action, tid, tag) {
                                    let message = tag.getAttribute('message');
                                    switch (action) {
                                        case 'updated':
                                            sAlert(message);
                                            rRevOvtPrsDetailGrid(revOvtPrsGrid.getSelectedRowId());
                                            enableOvtDetailToolbar();
                                            revOvtPrsLayout.cells("c").progressOff();
                                            setGridDP2();
                                            break;
                                        case 'error':
                                            eAlert(message);
                                            enableOvtDetailToolbar();
                                            revOvtPrsLayout.cells("c").progressOff();
                                            setGridDP2();
                                            break;
                                    }
                                });
                            }
                        },
                    });
                    break;
                case "close":
                    if(!revOvtPrsGrid.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan di tutup!");
                    }
                    
                    dhtmlx.modalbox({
                        type: "alert-warning",
                        title: "Konfirmasi Tutup Revisi",
                        text: "Anda yakin akan menutup revisi lembur " + revOvtPrsGrid.getSelectedRowId() + "?",
                        buttons: ["Ya", "Tidak"],
                        callback: function (index) {
                            if (index == 0) {
                                reqJson(Overtime("closeRevisionPersonil"), "POST", {taskId: revOvtPrsGrid.getSelectedRowId()}, (err, res) => {
                                    if(res.status === "success") {
                                        rRevOvtPrsGrid();
                                        rRevOvtPrsDetailGrid();
                                        sAlert(res.message);
                                    } else {
                                        eAlert(res.message);
                                    }
                                });
                            }
                        },
                    });
                    break;
            }
        });

        function enableOvtDetailToolbar() {
            revOvtDetailListToolbar.enableItem("approve");
            revOvtDetailListToolbar.enableItem("rollback");
            revOvtDetailListToolbar.enableItem("close");
        }

        function disableOvtDetailToolbar() {
            revOvtDetailListToolbar.disableItem("approve");
            revOvtDetailListToolbar.disableItem("rollback");
            revOvtDetailListToolbar.disableItem("close");
        }

        rRevOvtPrsGrid();
        rRevOvtPrsDetailGrid();
    }

JS;

header('Content-Type: application/javascript');
echo $script;