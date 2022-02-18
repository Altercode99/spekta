<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	function showReqRevOvertime() {	
        var legend = legendGrid();
        var revForm;
        var fileError;
        var totalFile;
        var taskIds = [];
        var taskIdsEdit = [];

        var comboUrl = {
            department_id: {
                url: Overtime("getDepartment"),
                reload: true
            },
            sub_department_id: {
                url: Overtime("getSubDepartment"),
            },
            division_id: {
                url: Emp("getDivision"),
            }
        }

        var reqRevTabs = mainTab.cells("other_pengajuan_revisi_lembur").attachTabbar({
            pattern: "1C",
            tabs: [
                {id: "a", text: "Form Pengajuan Revisi Lembur", active: true},
                {id: "b", text: "Daftar Pengajuan Revisi Lembur"}
            ]
        });

        revForm = reqRevTabs.cells("a").attachForm([
            {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Form Revisi Lembur", list: [
                {type: "editor", name: "description", label: "Keterangan", labelWidth: 130, inputWidth:900, inputHeight:200, required: true},
                {type: "input", name: "task_ids", label: "Task ID Lembur", labelWidth: 130, inputWidth:350, required: true, rows: 3, readonly: true},
                {type: "combo", name: "department_id", label: "Sub Unit", labelWidth: 130, inputWidth:350, required: true},
                {type: "combo", name: "sub_department_id", label: "Bagian", labelWidth: 130, inputWidth:350, required: true},
                {type: "hidden", name: "filename", label: "Filename", readonly: true},
                {type: "upload", name: "file_uploader", inputWidth: 420,
                    url: AppMaster("fileUpload", {save: false, folder: "overtimes_revision_requests"}), 
                    swfPath: "./public/codebase/ext/uploader.swf", 
                    swfUrl: AppMaster("fileUpload")
                },
                {type: "block", offsetTop: 30, list: [
                    {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                    {type: "newcolumn"},
                    {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"}
                ]}
            ]}
        ]);

        var deptCombo = revForm.getCombo("department_id");
        var subCombo = revForm.getCombo("sub_department_id");

        deptCombo.load(Overtime("getDepartment", {equal_id: userLogged.deptId}));
        deptCombo.attachEvent("onChange", function(value, text) {
            clearComboReload(revForm, "sub_department_id", Overtime("getSubDepartment", {equal_id: userLogged.subId}));
        });

        revForm.attachEvent("onFocus", function(name, value) {
            if(name === "task_ids") {
                ovtListWin(false);
            }
        });

        revForm.attachEvent("onBeforeFileAdd", async function (filename, size) {
            beforeFileAdd(revForm, {filename, size});
        });

        revForm.attachEvent("onBeforeFileUpload", function(mode, loader, formData){
            if(fileError) {
                clearUploader(revForm, "file_uploader");
                eAlert("File error silahkan upload file sesuai ketentuan!");
                fileError = false;
            } else {
                return true;
            }
        });

        revForm.attachEvent("onButtonClick", function(id) {
            switch (id) {
                case "add":
                    const uploader = revForm.getUploader("file_uploader");
                    if(uploader.getStatus() === -1) {
                        if(!fileError) {
                            uploader.upload();
                        } else {
                            uploader.clear();
                            eAlert("File error silahkan upload file sesuai ketentuan!");
                            fileError = false;
                        }
                    } else {
                        addRevSubmit();
                    }
                    break;
                case "clear":
                    clearAllForm(revForm, comboUrl);
                    break;
            }
        });

        revForm.attachEvent("onUploadFile", function(filename, servername){
            revForm.setItemValue("filename", servername);
            addRevSubmit();
        });

        function addRevSubmit() {
            if(!revForm.validate()) {
                return eAlert("Input error!");
            }

            setDisable(["add", "clear"], revForm, reqRevTabs.cells("a"));

            let revFormDP = new dataProcessor(Overtime("addRevisionRequest"));
            revFormDP.init(revForm);
            revForm.save();

            revFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                let message = tag.getAttribute("message");
                switch (action) {
                    case "inserted":
                        sAlert(message);
                        clearAllForm(revForm, comboUrl);
                        clearUploader(revForm, "file_uploader");
                        setEnable(["add", "clear"], revForm, reqRevTabs.cells("a"));
                        rRevListGrid();
                        taskIds = [];
                        break;
                    case "error":
                        eAlert(message);
                        setEnable(["add", "clear"], revForm, reqRevTabs.cells("a"));
                        break;
                }
            });
        }

        async function beforeFileAdd(form, file) {
            if(form.validate()) {
                var ext = file.filename.split(".").pop();
                if (ext == "png" || ext == "jpg" || ext == "jpeg") {
                    if (file.size > 5000000) {
                        fileError = true;
                        eAlert("Tidak boleh melebihi 5 MB!");
                    } else {
                        if(totalFile > 0) {
                            eAlert("Maksimal 1 file");
                            fileError = true;
                        } else {
                            totalFile++;
                            return true;
                        }
                    }		    
                } else {
                    eAlert("Hanya png, jpg & jpeg saja yang bisa diupload!");
                    fileError = true;
                }
            } else {
                eAlert("Input error!");
            }	
        }

        var revListLayout = reqRevTabs.cells("b").attachLayout({
            pattern: "3J",
            cells: [
                {id: "a", text: "Daftar Pengajuan Revisi"},
                {id: "b", text: "Catatan Revisi", collapse: true, width: 450},
                {id: "c", text: "Detail Revisi", collapse: true},
            ]
        });

        var listStatusBar = revListLayout.cells("a").attachStatusBar();
        function listGridCount() {
            let listGridRows = revListGrid.getRowsNum();
            listStatusBar.setText("Total baris: " + listGridRows + " (" + legend.revision_overtime + ")");
        }

        var revListGrid = revListLayout.cells("a").attachGrid();
        revListGrid.setImagePath("./public/codebase/imgs/");
        revListGrid.setHeader("No,Task ID,Deskripsi,Bagian,Sub Bagian,Status,Created By,Updated By,DiBuat");
        revListGrid.attachHeader("#rspan,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter")
        revListGrid.setColSorting("int,str,str,str,str,str,str,str,str");
        revListGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        revListGrid.setColAlign("center,left,left,left,left,left,left,left,left");
        revListGrid.setInitWidthsP("5,20,35,20,20,20,20,20,25");
        revListGrid.enableSmartRendering(true);
        revListGrid.attachEvent("onXLE", function() {
            revListLayout.cells("a").progressOff();
        });
        revListGrid.attachEvent("onRowDblClicked", function(rId, cInd) {
            revListLayout.cells("a").expand();
            rRevListDetailGrid(rId);
        });
        revListGrid.attachEvent("onRowSelect", function(rId, cIdn) {
            if(revListGrid.cells(rId, 5).getValue() != "CREATED") {
                listRevToolbar.disableItem("desc_rev");
                listRevToolbar.disableItem("cancel");
                listDtlRevToolbar.disableItem("add");
                listDtlRevToolbar.disableItem("delete");
            } else {
                listRevToolbar.enableItem("desc_rev");
                listRevToolbar.enableItem("cancel");
                listDtlRevToolbar.enableItem("add");
                listDtlRevToolbar.enableItem("delete");
            }
        });
        revListGrid.init();

        function rRevListGrid() {
            revListLayout.cells("a").progressOn();
            let start = $("#other_start_ovt_rev").val();
            let end = $("#other_end_ovt_rev").val();
            let status = $("#other_status_ovt_rev").val();
            let params = {
                equal_sub_department_id: userLogged.subId, 
                betweendate_created_at: start+","+end
            };

            if(status != "ALL") {
                if(status == 'ACTIVE') {
                    params.in_status = "CREATED,PROCESS";
                } else {
                    params.equal_status = status;
                }
            }
            revListLayout.cells("b").attachHTMLString("<div style='width:100%;height:100%;display:flex;flex-direction:row;justify-content:center;align-items:center;'><p style='font-family:sans-serif'>Tidak ada revisi dipilih</p></div>");
            revListGrid.clearAndLoad(Overtime("getRevOvtGrid", params), listGridCount);
        }

        var revListDetailGrid = revListLayout.cells("c").attachGrid();
        revListDetailGrid.setImagePath("./public/codebase/imgs/");
        revListDetailGrid.setHeader("No,Task ID,,Created By,Updated By,DiBuat");
        revListDetailGrid.setHeader("No,Task ID,Nama Karyawan,Bagian,Sub Bagian,Nama Mesin #1,Nama Mesin #2,Pelayanan Produksi,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Status Hari,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Premi,Nominal Overtime,Makan,Biaya Makan,Tugas,Status Overtime,DiBuat");
        revListDetailGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        revListDetailGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        revListDetailGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        revListDetailGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        revListDetailGrid.setInitWidthsP("5,20,20,20,20,20,20,20,20,20,20,15,15,15,15,15,15,15,15,15,30,25");
        revListDetailGrid.enableSmartRendering(true);
        revListDetailGrid.enableMultiselect(true);
        revListDetailGrid.attachEvent("onXLE", function() {
            revListLayout.cells("c").progressOff();
        });
        revListDetailGrid.attachEvent("onRowSelect", function(rId, cInd) {
            if(revListGrid.cells(revListGrid.getSelectedRowId(), 5).getValue() != "CREATED") {
                listDtlRevToolbar.disableItem("add");
                listDtlRevToolbar.disableItem("delete");
            } else {
                listDtlRevToolbar.enableItem("add");
                listDtlRevToolbar.enableItem("delete");
            }
        });
        revListDetailGrid.init();

        function rRevListDetailGrid(taskId = null) {
            if(taskId) {
                revListLayout.cells("c").progressOn();
                revListLayout.cells("c").expand();
                revListDetailGrid.clearAndLoad(Overtime("getRevOvtDtlGrid", {taskId}));
                revListLayout.cells("b").expand();
                reqJson(Overtime("getRevision"), "POST", {taskId}, (err, res) => {
                    var revForm = revListLayout.cells("b").attachForm([
                        {type: "block", offsetLeft: 5, list: [
                            {type: "input", name: "task_id", label: "Task ID", labelWidth: 130, inputWidth: 385, readonly: true, required: true, value: res.revision.task_id},
                            {type: "editor", name: "description", label: "Keterangan", labelWidth: 130, inputWidth: 385, inputHeight: 200, required: true, value: res.revision.description},
                            {type: "editor", name: "response", label: "Tanggapan SDM", labelWidth: 130, inputWidth: 385, inputHeight: 210, required: true, value: res.revision.response},
                        ]}
                    ]);
                });
            } else {
                revListLayout.cells("c").collapse();
                revListLayout.cells("b").collapse();
                revListDetailGrid.clearAll();
            }
        }

        var listRevToolbar = revListLayout.cells("a").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "desc_rev", text: "Revisi Deskripsi", type: "button", img: "edit.png"},
                {id: "cancel", text: "Batalkan Revisi", type: "button", img: "messagebox_critical.png"},
                {id: "attachment", text: "Lihat Attachment", type: "button", img: "attachment.png"},
            ]
        });

        let currentDate = filterForMonth(new Date());
        var listRevMenu =  revListLayout.cells("a").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "search", text: "<div style='width:100%'>Search: <input type='text' id='other_start_ovt_rev' readonly value='"+currentDate.start+"' /> - <input type='text' id='other_end_ovt_rev' readonly value='"+currentDate.end+"' /> <button id='other_btn_ftr_ovt_rev'>Proses</button> | Status: <select id='other_status_ovt_rev'><option>ALL</option><option selected value='ACTIVE'>AKTIF (CREATED & PROCESS)</option><option>PROCESS</option><option>CANCELED</option><option>REJECTED</option><option>CLOSED</option></select></div>"}
            ]
        });

        var filterCalendar = new dhtmlXCalendarObject(["other_start_ovt_rev","other_end_ovt_rev"]);

        $("#other_btn_ftr_ovt_rev").on("click", function() {
            if(checkFilterDate($("#other_start_ovt_rev").val(), $("#other_end_ovt_rev").val())) {
                rRevListGrid();
            }
        });

        $("#other_status_ovt_rev").on("change", function() {
            rRevListGrid();
        });

        listRevToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    rRevListGrid();
                    rRevListDetailGrid();
                    break;
                case "desc_rev":
                    if(!revListGrid.getSelectedRowId()) {
                        return eAlert("Pilih permintaan revisi yang akan diupdate!");
                    }
                    
                    var descWin = createWindow("desc_win", "Update Deskripsi Revisi", 1100, 550);
                    myWins.window("desc_win").skipMyCloseEvent = true;

                    reqJson(Overtime("getDescription"), "POST", {taskId: revListGrid.getSelectedRowId()}, (err, res) => {
                        var descForm = descWin.attachForm([
                            {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Form Revisi", list:[	
                                {type: "block", list: [
                                    {type: "hidden", name: "task_id", value: res.task_id},
                                    {type: "editor", name: "description", label: "Deskripsi", labelWidth: 130, inputWidth: 800, inputHeight: 300, value: res.description},
                                ]},
                                {type: "block", offsetLeft: 30, offsetTop: 10, list: [
                                    {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Update"},
                                    {type: "newcolumn"},
                                    {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                                ]},
                            ]},
                        ]);

                        descForm.attachEvent("onButtonClick", function(name) {
                            switch (name) {
                                case "update":
                                    setDisable(["update", "cancel"], descForm, descWin);
                                    let descFormDP = new dataProcessor(Overtime("updateRevOvtDesc"));
                                    descFormDP.init(descForm);
                                    descForm.save();

                                    descFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                                        let message = tag.getAttribute("message");
                                        switch (action) {
                                            case "updated":
                                                rRevListGrid();
                                                rRevListDetailGrid(revListGrid.getSelectedRowId());
                                                sAlert(message);
                                                setEnable(["update", "cancel"], descForm, descWin);
                                                closeWindow("desc_win");
                                                break;
                                            case "error":
                                                eaAlert("Update Gagal" ,message);
                                                rRevListGrid();
                                                setEnable(["update", "cancel"], descForm, descWin);
                                                break;
                                        }
                                    });
                                    break;
                                case "cancel":
                                    closeWindow("desc_win");
                                    break;
                            }
                        });
                    });
                    break;
                case "cancel":
                    if(!revListGrid.getSelectedRowId()) {
                        return eAlert("Pilih permintaan revisi yang akan dibatalkan!");
                    }

                    dhtmlx.modalbox({
                        type: "alert-error",
                        title: "Pembatalan Revisi",
                        text: "Anda yakin akan membatalkan permintaan revisi " + revListGrid.getSelectedRowId() + "?",
                        buttons: ["Ya", "Tidak"],
                        callback: function (index) {
                            if (index == 0) {
                                reqJson(Overtime("cancelRevOvt"), "POST", {taskId: revListGrid.getSelectedRowId()}, (err, res) => {
                                    if(res.status === "success") {
                                        rRevListGrid();
                                        sAlert(res.message);
                                        rRevListDetailGrid(null);
                                    } else {
                                        rRevListGrid();
                                        eaAlert(res.message);
                                    }
                                });
                            }
                        },
                    });
                    
                    break;
                case "attachment":
                    if(!revListGrid.getSelectedRowId()) {
                        return eAlert("Pilih permintaan revisi!");
                    }
                    reqJson(Overtime("viewAttachment"), "POST", {taskId: revListGrid.getSelectedRowId()}, (err, res) => {
                        if(res.status === "success") {
                            var attachWin = createWindow("other_ovt_rev_attachment", "Attachment Revisi: " + revListGrid.getSelectedRowId(), 800, 500);
                            myWins.window("other_ovt_rev_attachment").skipMyCloseEvent = true;
                            attachWin.attachHTMLString(res.template);
                        }
                    });
                    break;
            }
        })

        var listDtlRevToolbar = revListLayout.cells("c").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "add", text: "Tambah", type: "button", img: "add.png"},
                {id: "delete", text: "Hapus", type: "button", img: "delete.png"}
            ]
        });

        listDtlRevToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "add":
                    if(!revListGrid.getSelectedRowId()) {
                        return eAlert("Pilih permintaan revisi yang akan ditambahkan!");
                    }

                    ovtListWin(true);
                    break;
                case "delete":
                    reqAction(revListDetailGrid, Overtime("cancelRevOvtDetail"), 1, (err, res) => {
                        rRevListDetailGrid(revListGrid.getSelectedRowId());
                        res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                        res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
                    });
                    break;
            }
        });

        function ovtListWin(edit = false) {
            var ovtWin = createWindow("rev_ovt_form_win", "Daftar Lembur (1 Minggu Terakhir)", 1100, 600);
            myWins.window("rev_ovt_form_win").skipMyCloseEvent = true;

            var ovtWinMenu = ovtWin.attachToolbar({
                icon_path: "./public/codebase/icons/",
                items: [
                    {id: "save", text: "Simpan", type: "button", img: "ok.png"}
                ]
            });

            ovtWinMenu.attachEvent("onClick", function(id) {
                switch (id) {
                    case "save":
                        if(!edit) {
                            taskIds = [];
                            for (let i = 0; i < ovtGrid.getRowsNum(); i++) {
                                let id = ovtGrid.getRowId(i);
                                if(ovtGrid.cells(id, 1).getValue() == 1) {
                                    taskIds.push(ovtGrid.cells(id, 2).getValue());
                                }
                            }
                            revForm.setItemValue("description", taskIds);
                            revForm.setItemValue("task_ids", taskIds);
                        } else {
                            taskIdsEdit = [];
                            for (let i = 0; i < ovtGrid.getRowsNum(); i++) {
                                let id = ovtGrid.getRowId(i);
                                if(ovtGrid.cells(id, 1).getValue() == 1) {
                                    taskIdsEdit.push(ovtGrid.cells(id, 2).getValue());
                                }
                            }
                            let revTaskId = revListGrid.cells(revListGrid.getSelectedRowId(), 1).getValue();
                            reqJson(Overtime("addPersonRevisionRequest"), "POST", {
                                taskId: taskIdsEdit, 
                                revTaskId
                            }, (err, res) => {
                                if(res.status === "success") {
                                    taskIdsEdit = [];
                                    rRevListDetailGrid(revTaskId);
                                    sAlert(res.message);
                                }
                            });
                        }
                        closeWindow("rev_ovt_form_win");
                        break;
                }
            })

            var winStatusBar = ovtWin.attachStatusBar();
            function winGridCount() {
                let winGridRows = ovtGrid.getRowsNum();
                winStatusBar.setText("Total baris: " + winGridRows);
                if(!edit) {
                    taskIds.length > 0 && taskIds.map(taskId => ovtGrid.cells(taskId, 1).setValue(1));
                } else {
                    taskIdsEdit.length > 0 && taskIdsEdit.map(taskId => ovtGrid.cells(taskId, 1).setValue(1));
                }
            }

            ovtWin.progressOn();
            var ovtGrid = ovtWin.attachGrid();
            ovtGrid.setImagePath("./public/codebase/imgs/");
            ovtGrid.setHeader("No,Check,Task ID,Nama Karyawan,Bagian,Sub Bagian,Nama Mesin #1,Nama Mesin #2,Pelayanan Produksi,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Status Hari,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Premi,Nominal Overtime,Makan,Biaya Makan,Tugas,Status Overtime,DiBuat");
            ovtGrid.attachHeader("#rspan,#master_checkbox,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
            ovtGrid.setColSorting("int,na,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
            ovtGrid.setColTypes("rotxt,ch,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
            ovtGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
            ovtGrid.setInitWidthsP("5,5,20,20,20,20,20,20,20,20,20,20,15,15,15,15,15,15,15,15,15,30,25");
            ovtGrid.enableSmartRendering(true);
            ovtGrid.setEditable(true);
            ovtGrid.attachEvent("onXLE", function() {
                ovtWin.progressOff();
            });
            ovtGrid.init();
            let date = new Date();
            ovtGrid.clearAndLoad(Overtime("getWindowOvertimeGrid", {
                equal_status: "CLOSED", 
                equal_sub_department_id: userLogged.subId,
                notequal_payment_status: "VERIFIED"
            }), winGridCount);
        }

        rRevListGrid();
        rRevListDetailGrid();
      
    }

JS;

header('Content-Type: application/javascript');
echo $script;