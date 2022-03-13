<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	function showReqRevOvertime() {	
        var legend = legendGrid();
        var revForm;
        var revSubForm;
        var fileError;
        var totalFile;
        var taskIds = [];
        var taskIdsEdit = [];
        var personils = [];
        var bookedPersonil = [];
        let currentDate = filterForMonth(new Date());
        var times = createTime();

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
                {id: "a", text: "Form Revisi Jam Lembur", active: true},
                {id: "b", text: "Daftar Revisi Jam Lembur"},
                {id: "c", text: "Form Revisi Personil Lembur"},
                {id: "d", text: "Daftar Revisi Personil Lembur"},
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
                        revForm.setItemValue("description", "");
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

        var revSubLayout = reqRevTabs.cells("c").attachLayout({
            pattern: "3J",
            cells: [
                {id: "a", text: "Daftar Lemburan (1 Minggu Terakhir)"},
                {id: "b", text: "Form Revisi Lembur", width: 450},
                {id: "c", text: "Detail Revisi Lembur", collapse: true},
            ]
        });

        var ovtListToolbar = revSubLayout.cells("a").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"}
            ]
        });

        var ovtListDetailToolbar = revSubLayout.cells("c").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "cancel", text: "Batalkan Lembur", type: "button", img: "messagebox_critical.png"},
                {id: "rollback", text: "Kembalikan Status Awal", type: "button", img: "refresh.png"},
                {id: "add", text: "Tambah Personil", type: "button", img: "add.png"},
                {id: "hour_revision", text: "Revisi Jam Lembur", type: "button", img: "calendar.png"},
                {id: "final", text: "Submit Form Revisi", type: "button", img: "update.png"},
            ]
        });

        var revSubGrid = revSubLayout.cells("a").attachGrid();
        revSubLayout.cells("a").progressOn();
        revSubGrid.setImagePath("./public/codebase/imgs/");
        revSubGrid.setHeader("No,Task ID,Sub Unit,Bagian,Sub Bagian,Kebutuhan Orang,Status Hari,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Catatan,Created By,Updated By,DiBuat");
        revSubGrid.attachHeader("#rspan,#text_filter,#select_filter,#select_filter,#select_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        revSubGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str");
        revSubGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        revSubGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left");
        revSubGrid.setInitWidthsP("5,20,20,20,20,10,10,20,20,20,20,20,20,25");
        revSubGrid.enableSmartRendering(true);
        revSubGrid.attachEvent("onXLE", function() {
            revSubLayout.cells("a").progressOff();
        });
        revSubGrid.init();
        revSubGrid.attachEvent("onRowDblClicked", function(rId, cInd){
            revSubForm.setItemValue("description", rId);
            revSubForm.setItemValue("task_id", rId);
            rSubDetailGrid(rId);
        });

        function rRevSubGrid() {
            revSubLayout.cells("a").progressOn();
            revSubGrid.clearAndLoad(Overtime("getOvt7Day", {in_status: "CLOSED", equal_sub_department_id: userLogged.subId}));
            ovtListDetailToolbar.enableItem("cancel");
            ovtListDetailToolbar.enableItem("rollback");
            ovtListDetailToolbar.enableItem("hour_revision");
        }

        rRevSubGrid();
        setTimeout(() => {
            revSubLayout.cells("b").collapse(); 
        }, 1000);

        var revSubDetailBar = revSubLayout.cells("c").attachStatusBar();
        function setBookedPersonil() {
            let revSubDetailRows = revSubDetailGrid.getRowsNum();
            revSubDetailBar.setText("Total baris: " + revSubDetailRows + " (" + legend.revision_overtime_personil + ")");
            bookedPersonil = [];
            for (let i = 0; i < revSubDetailGrid.getRowsNum(); i++) {
                bookedPersonil.push(revSubDetailGrid.cells2(i, 29).getValue());
            }
        }

        var revSubDetailGrid = revSubLayout.cells("c").attachGrid();
        revSubDetailGrid.setImagePath("./public/codebase/imgs/");
        revSubDetailGrid.setHeader("No,Status,,,Task ID,Nama Karyawan,Sub Unit,Bagian,Sub Bagian,Nama Mesin #1,Nama Mesin #2,Pelayanan Produksi,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Status Hari,Jam Efektif,Jam Istirahat,Jam Ril,Jam Hit,Premi,Nominal Overtime,Makan,Tugas,Status Overtime,Status Terakhir,Created By,Updated By,Di Buat,");
        revSubDetailGrid.attachHeader("#rspan,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        revSubDetailGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        revSubDetailGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        revSubDetailGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        revSubDetailGrid.setInitWidthsP("5,10,0,0,20,20,20,20,25,25,25,25,15,15,15,10,10,10,10,10,10,10,5,25,10,30,15,30,22,0");
        revSubDetailGrid.enableSmartRendering(true);
        revSubDetailGrid.attachEvent("onXLE", function() {
            revSubLayout.cells("c").progressOff();
        });
        revSubDetailGrid.attachEvent("onRowSelect", function(rId, cIdn) {
            let revStatus = revSubDetailGrid.cells(rId, 2).getValue();
            console.log(revStatus);
            if(revStatus == 'NONE' || revStatus == 'CLOSED') {
                ovtListDetailToolbar.enableItem("cancel");
                ovtListDetailToolbar.disableItem("rollback");
            } else {
                ovtListDetailToolbar.disableItem("cancel");
                ovtListDetailToolbar.enableItem("rollback");
            }

            if(revStatus == "ADD") {
                ovtListDetailToolbar.enableItem("hour_revision");
            } else {
                ovtListDetailToolbar.disableItem("hour_revision");
            }
        });
        revSubDetailGrid.init();

        function rSubDetailGrid(rId = null) {
            if(rId) {
                revSubLayout.cells("c").progressOn();
                revSubLayout.cells("c").expand();
                revSubDetailGrid.clearAndLoad(Overtime("getOvertimeDetailGridRev", {equal_task_id: rId, in_status: "CLOSED,ADD"}), setBookedPersonil);
            } else {
                revSubLayout.cells("c").collapse();
                revSubLayout.cells("b").collapse();
                revSubDetailGrid.clearAll();
            }
        }

        ovtListToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    rRevSubGrid();
                    rSubDetailGrid();
                    break;
            }
        });

        ovtListDetailToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "final":
                    if(!revSubGrid.getSelectedRowId()) {
                        return eAlert("Belum ada lemburan yang di pilih!");
                    }
                    revSubLayout.cells("b").expand();
                    break;
                case "cancel":
                    if(!revSubGrid.getSelectedRowId()) {
                        return eAlert("Belum ada lemburan yang di pilih!");
                    }
                    dhtmlx.modalbox({
                        type: "alert-error",
                        title: "Pembatalan Personil Lembur",
                        text: "Anda yakin akan membatalkan lemburan revisi " + revSubDetailGrid.cells(revSubDetailGrid.getSelectedRowId(), 4).getValue() + "?",
                        buttons: ["Ya", "Tidak"],
                        callback: function (index) {
                            if (index == 0) {
                                let taskId = revSubDetailGrid.cells(revSubDetailGrid.getSelectedRowId(), 4).getValue();
                                reqJson(Overtime("cancelOvtRev"), "POST", {taskId}, (err, res) => {
                                    if(res.status === "success") {
                                        sAlert(res.message);
                                        rSubDetailGrid(revSubGrid.getSelectedRowId());
                                    } else {
                                        eAlert(res.message);
                                    }
                                });
                            }
                        },
                    });
                    break;
                case "rollback":
                    dhtmlx.modalbox({
                        type: "alert-error",
                        title: "Rollback Revisi Lembur",
                        text: "Anda yakin akan mengembalikan status revisi Task ID: " + revSubDetailGrid.cells(revSubDetailGrid.getSelectedRowId(), 4).getValue() + "?",
                        buttons: ["Ya", "Tidak"],
                        callback: function (index) {
                            if (index == 0) {
                                reqJson(Overtime("rollbackOvtRev"), "POST", {taskId: revSubDetailGrid.cells(revSubDetailGrid.getSelectedRowId(), 4).getValue()}, (err, res) => {
                                    if(res.status === "success") {
                                        sAlert(res.message);
                                        rSubDetailGrid(revSubGrid.getSelectedRowId());
                                    } else {
                                        eAlert(res.message);
                                    }
                                });
                            }
                        },
                    });
                    break;
                case "add":
                    if(!revSubGrid.getSelectedRowId()) {
                        return eAlert("Belum ada lemburan yang di pilih!");
                    }

                    var addPersonWin = createWindow("add_person", "Detail Overtime", 1100, 700);
                    myWins.window("add_person").skipMyCloseEvent = true;

                    const detailOvertime = reqJsonResponse(Overtime("getDetailOvertime"), "POST", {id: revSubGrid.getSelectedRowId()}, null);

                    var personLayout = addPersonWin.attachLayout({
                        pattern: "3U",
                        cells: [
                            {id: "a", text: "Detail", height: 260},
                            {id: "b", text: "Klik Mesin Untuk Memilih", height: 260},
                            {id: "c", text: "Tambah Personil"}
                        ]
                    });

                    personLayout.cells("a").attachHTMLString(detailOvertime.template);

                    let ovtPersonTime = getCurrentTime(revSubGrid, 8, 9);
                    let startIndex = times.filterTime.indexOf(ovtPersonTime.start);
                    let endIndex = times.filterTime.indexOf(ovtPersonTime.end);
                        
                    var workTime = genWorkTime(times.times, startIndex, endIndex);

                    var personilForm = personLayout.cells("c").attachForm([
                        {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Data Lembur", list:[	
                            {type: "block", list: [
                                {type: "hidden", name: "overtime_id", label: "Overtime ID", labelWidth: 130, inputWidth: 250, value: revSubGrid.getSelectedRowId()},                               
                                {type: "combo", name: "start_date", label: "Waktu Mulai", labelWidth: 130, inputWidth: 250, required: true,
                                    validate: "NotEmpty", 
                                    options: workTime.newStartTime
                                },
                                {type: "combo", name: "end_date", label: "Waktu Selesai", labelWidth: 130, inputWidth: 250, required: true, 
                                    validate: "NotEmpty", 
                                    options: workTime.newEndTime,
                                },
                                {type: "hidden", name: "machine_id", label: "ID Mesin", labelWidth: 130, inputWidth: 250, readonly: true},
                                {type: "input", name: "machine_name", label: "Nama Mesin", labelWidth: 130, inputWidth: 250, readonly: true},
                                {type: "hidden", name: "status", label: "status", labelWidth: 130, inputWidth: 250, readonly: true, value: "ADD"},
                                {type: "hidden", name: "revision_status", label: "revision_status", labelWidth: 130, inputWidth: 250, readonly: true, value: "CLOSED"},
                                {type: "hidden", name: "status_before", label: "status_before", labelWidth: 130, inputWidth: 250, readonly: true, value: "ADD"},
                            ]},
                        ]},
                        {type: "newcolumn"},
                        {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Personil Dan Tugas Lembur", list:[	
                            {type: "block", list: [
                                {type: "hidden", name: "personil_id", label: "Daftar Personil", labelWidth: 130, inputWidth: 250, required: true, readonly: true},
                                {type: "input", name: "personil_name", label: "Daftar Personil", labelWidth: 130, inputWidth: 250, required: true, readonly: true},
                                {type: "input", name: "notes", label: "Tugas Lembur", labelWidth: 130, inputWidth: 250, required: true, rows: 3},
                            ]},
                        ]},
                        {type: "newcolumn"},
                        {type: "block", offsetLeft: 30, offsetTop: 10, list: [
                            {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                            {type: "newcolumn"},
                            {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"}
                        ]},
                    ]);

                    var startPCombo = personilForm.getCombo("start_date");
                    var endPCombo = personilForm.getCombo("end_date");
                    endPCombo.selectOption(workTime.newEndTime.length - 1);

                    personilForm.attachEvent("onFocus", function(name, value) {
                        if(name === 'personil_name') {
                            loadPersonil();
                        }
                    });

                    if(detailOvertime.overtime.machine_ids) {
                        personLayout.cells("b").progressOn();
                        var machineDetailGrid = personLayout.cells("b").attachGrid();
                        machineDetailGrid.setImagePath("./public/codebase/imgs/");
                        machineDetailGrid.setHeader("No,Nama Mesin,Lokasi");
                        machineDetailGrid.setColSorting("int,str,str");
                        machineDetailGrid.setColAlign("center,left,left");
                        machineDetailGrid.setColTypes("rotxt,rotxt,rotxt");
                        machineDetailGrid.setInitWidthsP("5,45,50");
                        machineDetailGrid.enableMultiselect(true);
                        machineDetailGrid.enableSmartRendering(true);
                        machineDetailGrid.attachEvent("onXLE", function() {
                            personLayout.cells("b").progressOff();
                        });
                        machineDetailGrid.attachEvent("onRowSelect", function(rId, cIdn) {
                            let splitId = machineDetailGrid.getSelectedRowId().split(",");
                            if(splitId.length <= 2) {
                                let name = [];
                                splitId.map(id => {
                                    name.push(machineDetailGrid.cells(id, 1).getValue());
                                });

                                personilForm.setItemValue("machine_id", machineDetailGrid.getSelectedRowId());
                                personilForm.setItemValue("machine_name", name);
                            } else {
                                machineDetailGrid.clearSelection();
                                eaAlert("Oops..", "Maksimal 1 operator menjalankan 2 mesin!");
                                personilForm.setItemValue("machine_id", "");
                                personilForm.setItemValue("machine_name", "");
                            }
                        });
                        machineDetailGrid.init();
                        machineDetailGrid.clearAndLoad(Overtime("getOvertimeMachine", {ids: detailOvertime.overtime.machine_ids}));
                    } else {
                        personLayout.cells("b").attachHTMLString("<div style='width:100%;height:100%;display:flex;flex-direction:center;justify-content:center;align-items:center;font-family:sans-serif'>No Machine</div>");
                        personLayout.cells("b").setText("Lembur Umum");
                        personLayout.cells("b").collapse();
                        personilForm.hideItem("machine_name");
                    }
                    
                    personilForm.attachEvent("onButtonClick", function(id) {
                        switch (id) {
                            case "add":
                                if (!personilForm.validate()) {
                                    return eAlert("Input error!");
                                }

                                setDisable(["add", "clear"], personilForm, personLayout.cells("c"));
                                let personilFormDP = new dataProcessor(Overtime("createPersonilOvertime"));
                                personilFormDP.init(personilForm);
                                personilForm.save();

                                personilFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                                    let message = tag.getAttribute("message");
                                    switch (action) {
                                        case "inserted":
                                            sAlert("Berhasil Menambahkan Record <br>" + message);
                                            personils= [];
                                            personilNames= [];
                                            clearAllForm(personilForm, null, null, ['start_date', 'end_date']);
                                            rSubDetailGrid(revSubGrid.getSelectedRowId());
                                            if(detailOvertime.overtime.machine_ids) {
                                                machineDetailGrid.clearSelection();
                                            }
                                            setEnable(["add", "clear"], personilForm, personLayout.cells("c"));
                                            break;
                                        case "error":
                                            eAlert("Gagal Menambahkan Record <br>" + message);
                                            setEnable(["add", "clear"], personilForm, personLayout.cells("c"));
                                            break;
                                        case "invalid":
                                            eaAlert('Terjadi Kesalahan', message);
                                            setEnable(["add", "clear"], personilForm, personLayout.cells("c"));
                                            break;
                                    }
                                });
                                break;
                            case "cancel":
                                clearAllForm(personilForm, null, null, ['start_date', 'end_date']);
                                break;
                        }
                    });

                    function loadPersonil() {
                        var addPersonilWin = createWindow("add_personil_win", "Daftar Personil", 900, 500);
                        myWins.window("add_personil_win").skipMyCloseEvent = true;

                        var personilToolbar = addPersonilWin.attachToolbar({
                            icon_path: "./public/codebase/icons/",
                            items: [
                                {id: "save", text: "Simpan", type: "button", img: "ok.png"}
                            ]
                        });

                        personilToolbar.attachEvent("onClick", function(id) {
                            switch (id) {
                                case "save":
                                    personils = [];
                                    personilNames = [];
                                    for (let i = 0; i < addPersonilGrid.getRowsNum(); i++) {
                                        let id = addPersonilGrid.getRowId(i);
                                        if(addPersonilGrid.cells(id, 1).getValue() == 1) {
                                            personils.push(id);
                                            personilNames.push(addPersonilGrid.cells(id, 2).getValue());
                                        }
                                    }
                                    personilForm.setItemValue('personil_id', personils);
                                    personilForm.setItemValue('personil_name', personilNames);
                                    closeWindow("add_personil_win");
                                    break;
                            }
                        });

                        var addPersonilGrid = addPersonilWin.attachGrid();
                        addPersonilGrid.setImagePath("./public/codebase/imgs/");
                        addPersonilGrid.setHeader("No,Check,Nama Personil,Sub Unit,Bagian,Divisi");
                        addPersonilGrid.attachHeader("#rspan,#master_checkbox,#text_filter,#select_filter,#select_filter,#select_filter")
                        addPersonilGrid.setColAlign("center,left,left,left,left,left");
                        addPersonilGrid.setColSorting("str,na,str,str,str,str");
                        addPersonilGrid.setColTypes("rotxt,ch,rotxt,rotxt,rotxt,rotxt");
                        addPersonilGrid.setInitWidthsP("5,5,20,20,25,25");
                        addPersonilGrid.enableSmartRendering(true);
                        addPersonilGrid.attachEvent("onXLE", function() {
                            personils.length > 0 && personils.map(id => id !== '' && addPersonilGrid.cells(id, 1).setValue(1));
                            addPersonilWin.progressOff();
                        });
                        addPersonilGrid.init();
                        addPersonilGrid.clearAndLoad(Overtime("getEmployees", {equal_department_id: detailOvertime.overtime.department_id, notequal_sub_department_id: 5}), disabledBookedPersonil);

                        function disabledBookedPersonil() {
                            bookedPersonil.map(empId => addPersonilGrid.cells(empId, 1).setDisabled(true));
                        }
                    }
                    break;
                case "hour_revision":
                    if(!revSubDetailGrid.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan di revisi!");
                    }

                    var hourRevWin = createWindow("other_hour_revision_personil", "Revisi Waktu Lembur", 510, 300);
                    myWins.window("other_hour_revision_personil").skipMyCloseEvent = true;

                    let ovtTime = getCurrentTime(revSubGrid, 8, 9);
                    let startIndex1 = times.filterTime.indexOf(ovtTime.start);
                    let endIndex1 = times.filterTime.indexOf(ovtTime.end);

                    var workTime1 = genWorkTime(times.times, startIndex1, endIndex1, true);
                        
                    var labelStartDetail = ovtTime.labelStart;
                    var labelEndDetail = ovtTime.labelEnd;
                    var hourRevForm = hourRevWin.attachForm([
                        {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Jam Lembur", list:[	
                            {type: "block", list: [
                                {type: "input", name: "task_id", label: "Task ID", labelWidth: 130, inputWidth: 250, readonly: true, value: revSubDetailGrid.cells(revSubDetailGrid.getSelectedRowId(), 4).getValue()},                               
                                {type: "hidden", name: "labelStartDetail", label: "Start Date", labelWidth: 130, inputWidth: 250, value: labelStartDetail},                               
                                {type: "combo", name: "start_date", label: "<span id='labelStartDetail'>"+labelStartDetail+"</span>", labelWidth: 130, inputWidth: 250, required: true,
                                    validate: "NotEmpty", 
                                    options: workTime1.newStartTime
                                },
                                {type: "hidden", name: "labelEndDetail", label: "End Date", labelWidth: 130, inputWidth: 250, value: labelEndDetail},                               
                                {type: "combo", name: "end_date", label: "<span id='labelEndDetail'>"+labelEndDetail+"</span>", labelWidth: 130, inputWidth: 250, required: true, 
                                    validate: "NotEmpty", 
                                    options: workTime1.newEndTime,
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
                    let ovtDetailTime = getCurrentTime(revSubDetailGrid, 13, 14);
                    let startCurrWinIndex = workTime1.filterStart.indexOf(ovtDetailTime.start);
                    let endCurrWinIndex = workTime1.filterEnd.indexOf(ovtDetailTime.end);
                    startCombo.selectOption(startCurrWinIndex);
                    endCombo.selectOption(endCurrWinIndex);
                    dateChangeDetail(workTime1.filterStart.indexOf(startCombo.getSelectedValue()), workTime1.filterEnd.indexOf(endCombo.getSelectedValue()));

                    hourRevForm.attachEvent("onChange", function(name, value) {
                        if(name === "start_date" || name === "end_date") {
                            dateChangeDetail(workTime1.filterStart.indexOf(startCombo.getSelectedValue()), workTime1.filterEnd.indexOf(endCombo.getSelectedValue()));
                            checkRevisionTime(times.filterTime, startCombo.getSelectedValue(), endCombo.getSelectedValue(), ['update'], hourRevForm);
                        }
                    });

                    dateChangeDetail(workTime1.filterStart.indexOf(startCombo.getSelectedValue()), workTime1.filterEnd.indexOf(endCombo.getSelectedValue()));
                    checkRevisionTime(times.filterTime, startCombo.getSelectedValue(), endCombo.getSelectedValue(), ['update'], hourRevForm);

                    function dateChangeDetail(start, end) {
                        let startMiddle = workTime1.filterStart.indexOf("23:30");
                        let endMiddle = workTime1.filterEnd.indexOf("00:00");
                        if(start > startMiddle) {
                            hourRevForm.setItemValue("labelStartDetail", labelEndDetail);
                            $("#labelStartDetail").html(labelEndDetail);
                        } else {
                            hourRevForm.setItemValue("labelStartDetail", labelStartDetail);
                            $("#labelStartDetail").html(labelStartDetail);
                        }
                        if(end >= endMiddle) {
                            hourRevForm.setItemValue("labelEndDetail", labelEndDetail);
                            $("#labelEndDetail").html(labelEndDetail);
                        } else {
                            hourRevForm.setItemValue("labelEndDetail", labelStartDetail);
                            $("#labelEndDetail").html(labelStartDetail);
                        }
                    }

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
                                            rSubDetailGrid(revSubGrid.getSelectedRowId());
                                            sAlert(message);
                                            setEnable(["update", "cancel"], hourRevForm, hourRevWin);
                                            closeWindow("other_hour_revision_personil");
                                            break;
                                        case "error":
                                            eaAlert("Kesalahan Waktu Lembur", message);
                                            setEnable(["update", "cancel"], hourRevForm, hourRevWin);
                                            break;
                                    }
                                });
                                break;
                            case "cancel":
                                closeWindow("other_hour_revision_personil");
                                break;
                        }
                    });
                    break;
            }
        });

        revSubForm = revSubLayout.cells("b").attachForm([
            {type: "block", offsetLeft: 5, list: [
                {type: "editor", name: "description", label: "Keterangan", labelWidth: 130, inputWidth:385, inputHeight:350, required: true},
                {type: "input", name: "task_id", label: "Task ID Lembur", labelWidth: 130, inputWidth:385, required: true, readonly: true},
                {type: "block", offsetTop: 30, list: [
                    {type: "button", name: "submit", className: "button_update", offsetLeft: 15, value: "Submit"},
                    {type: "newcolumn"},
                    {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                ]}
            ]}
        ]);

        revSubForm.attachEvent("onButtonClick", function(id) {
            switch (id) {
                case "cancel":
                    revSubForm.setItemValue("description", "");
                    revSubForm.setItemValue("task_id", "");
                    revSubLayout.cells("b").collapse();
                    break;
                case "submit":
                    console.log("revSubFormSubmit");
                    if (!revSubForm.validate()) {
                        return eAlert("Input error!");
                    }

                    setDisable(["add", "cancel"], revSubForm, revSubLayout.cells("b"));
                    let revSubFormDP = new dataProcessor(Overtime("createRevisionPersonil"));
                    revSubFormDP.init(revSubForm);
                    revSubForm.save();

                    revSubFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                        let message = tag.getAttribute("message");
                        switch (action) {
                            case "inserted":
                                sAlert("Berhasil Menambahkan Record <br>" + message);
                                revSubForm.setItemValue("description", "");
                                revSubForm.setItemValue("task_id", "");
                                revSubLayout.cells("b").collapse();
                                personils= [];
                                personilNames= [];
                                rRevSubGrid();
                                rSubDetailGrid(revSubGrid.getSelectedRowId());
                                setEnable(["add", "cancel"], revSubForm, revSubLayout.cells("b"));
                                break;
                            case "error":
                                eAlert("Gagal Menambahkan Record <br>" + message);
                                setEnable(["add", "cancel"], revSubForm, revSubLayout.cells("b"));
                                break;
                            case "invalid":
                                eaAlert('Terjadi Kesalahan', message);
                                setEnable(["add", "cancel"], revSubForm, revSubLayout.cells("b"));
                                break;
                        }
                    });
                    break;
            }
        });

        var revSubListLayout = reqRevTabs.cells("d").attachLayout({
            pattern: "3J",
            cells: [
                {id: "a", text: "Daftar Pengajuan Revisi Personil"},
                {id: "b", text: "Catatan Revisi Lembur", collapse: true, width: 450},
                {id: "c", text: "Detail Revisi Lembur", collapse: true},
            ]
        });

        setTimeout(() => {
            revSubListLayout.cells("b").collapse();
        }, 1000);

        var listSubRevMenu =  revSubListLayout.cells("a").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "search", text: "<div style='width:100%'>Search: <input type='text' id='other_start_ovt_rev_personil' readonly value='"+currentDate.start+"' /> - <input type='text' id='other_end_ovt_rev_personil' readonly value='"+currentDate.end+"' /> <button id='other_btn_ftr_ovt_rev_personil'>Proses</button> | Status: <select id='other_status_ovt_rev_personil'><option>ALL</option><option selected value='ACTIVE'>AKTIF (CREATED & PROCESS)</option><option>PROCESS</option><option>CANCELED</option><option>REJECTED</option><option>CLOSED</option></select></div>"}
            ]
        });

        var filterCalendarPersonil = new dhtmlXCalendarObject(["other_start_ovt_rev_personil","other_end_ovt_rev_personil"]);

        $("#other_btn_ftr_ovt_rev_personil").on("click", function() {
            if(checkFilterDate($("#other_start_ovt_rev_personil").val(), $("#other_end_ovt_rev_personil").val())) {
                rSubRevListGrid();
            }
        });

        $("#other_status_ovt_rev_personil").on("change", function() {
            rSubRevListGrid();
        });

        var listSubRevTolbar = revSubListLayout.cells("a").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "desc_revision", text: "Revisi Deskripsi", type: "button", img: "edit.png"},
                {id: "cancel", text: "Batalkan Revisi", type: "button", img: "messagebox_critical.png"},
            ]
        });

        var subListStatusBar = revSubListLayout.cells("a").attachStatusBar();
        function subListGridCount() {
            let subListGridRows = revSubListbGrid.getRowsNum();
            subListStatusBar.setText("Total baris: " + subListGridRows + " (" + legend.revision_overtime + ")");
        }

        var revSubListbGrid = revSubListLayout.cells("a").attachGrid();
        revSubListLayout.cells("a").progressOn();
        revSubListbGrid.setImagePath("./public/codebase/imgs/");
        revSubListbGrid.setHeader("No,Ref Task,Task ID,Sub Unit,Bagian,Sub Bagian,Kebutuhan Orang,Status Hari,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Catatan,Created By,Updated By,DiBuat,STATUS");
        revSubListbGrid.attachHeader("#rspan,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        revSubListbGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        revSubListbGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        revSubListbGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        revSubListbGrid.setInitWidthsP("5,20,20,20,20,20,10,10,20,20,20,20,20,20,25,0");
        revSubListbGrid.enableSmartRendering(true);
        revSubListbGrid.attachEvent("onXLE", function() {
            revSubListLayout.cells("a").progressOff();
        });
        revSubListbGrid.init();
        revSubListbGrid.attachEvent("onRowDblClicked", function(rId, cInd){
            rSubListDetailGrid(rId);
        });
        revSubListbGrid.attachEvent("onRowSelect", function(rId, cInd) {
            let status = revSubListbGrid.cells(rId, 15).getValue();
            if(status == "CREATED") {
                listSubRevTolbar.enableItem("desc_revision");
                listSubRevTolbar.enableItem("cancel");
            } else {
                listSubRevTolbar.disableItem("desc_revision");
                listSubRevTolbar.disableItem("cancel");
            }
        });

        function rSubRevListGrid() {
            revSubListLayout.cells("a").progressOn();
            let start = $("#other_start_ovt_rev_personil").val();
            let end = $("#other_end_ovt_rev_personil").val();
            let status = $("#other_status_ovt_rev_personil").val();

            let params = {
                equal_sub_department_id: userLogged.subId, 
                betweendate_created_at: start+","+end,
            };

            if(status != "ALL") {
                if(status == 'ACTIVE') {
                    params.status = "CREATED,PROCESS";
                } else {
                    params.status = status;
                }
            }
            revSubListLayout.cells("b").attachHTMLString("<div style='width:100%;height:100%;display:flex;flex-direction:row;justify-content:center;align-items:center;'><p style='font-family:sans-serif'>Tidak ada revisi dipilih</p></div>");
            revSubListbGrid.clearAndLoad(Overtime("getRevOvtPersonil", params), subListGridCount);
        }

        rSubRevListGrid();

        var revSubListDetailBar = revSubListLayout.cells("c").attachStatusBar();
        function revSubListDetailCount() {
            let revSubListDetailRows = revSubListDetailGrid.getRowsNum();
            revSubListDetailBar.setText("Total baris: " + revSubListDetailRows + " (" + legend.revision_overtime_personil + ")");
        }

        var revSubListDetailGrid = revSubListLayout.cells("c").attachGrid();
        revSubListDetailGrid.setImagePath("./public/codebase/imgs/");
        revSubListDetailGrid.setHeader("No,Status,,,Task ID,Nama Karyawan,Sub Unit,Bagian,Sub Bagian,Nama Mesin #1,Nama Mesin #2,Pelayanan Produksi,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Status Hari,Jam Efektif,Jam Istirahat,Jam Ril,Jam Hit,Premi,Nominal Overtime,Makan,Tugas,Status Overtime,Status Terakhir,Created By,Updated By,Di Buat");
        revSubListDetailGrid.attachHeader("#rspan,#select_filter,#select_filter,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        revSubListDetailGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        revSubListDetailGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        revSubListDetailGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        revSubListDetailGrid.setInitWidthsP("5,10,0,0,20,20,20,20,25,25,25,25,15,15,15,10,10,10,10,10,10,10,5,25,10,30,15,30,22");
        revSubListDetailGrid.enableSmartRendering(true);
        revSubListDetailGrid.attachEvent("onXLE", function() {
            revSubListLayout.cells("c").progressOff();
        });
        revSubListDetailGrid.attachEvent("onRowSelect", function(rId, cIdn) {
            let revStatus = revSubListDetailGrid.cells(rId, 2).getValue();
        });
        revSubListDetailGrid.init();

        function rSubListDetailGrid(rId = null) {
            if(rId) {
                let taskId = revSubListbGrid.cells(rId, 2).getValue();
                let status = revSubListbGrid.cells(rId, 15).getValue();
                revSubListLayout.cells("c").progressOn();
                revSubListLayout.cells("c").expand();

                if(status == 'CREATED' || status == 'PROCESS') {
                    revSubListDetailGrid.clearAndLoad(Overtime("getOvertimeDetailGridRev", {equal_task_id: taskId}), revSubListDetailCount);
                } else {
                    revSubListDetailGrid.clearAndLoad(Overtime("getOvertimeDetailGridRevHistory", {equal_task_id: taskId}), revSubListDetailCount);
                }
                
                revSubListLayout.cells("b").expand();
                reqJson(Overtime("getPersonilRevision"), "POST", {taskId: rId}, (err, res) => {
                    var revForm = revSubListLayout.cells("b").attachForm([
                        {type: "block", offsetLeft: 5, list: [
                            {type: "input", name: "rev_task_id", label: "Task ID", labelWidth: 130, inputWidth: 385, readonly: true, required: true, value: res.revision.rev_task_id},
                            {type: "editor", name: "description", label: "Keterangan", labelWidth: 130, inputWidth: 385, inputHeight: 200, required: true, value: res.revision.description},
                            {type: "editor", name: "response", label: "Tanggapan SDM", labelWidth: 130, inputWidth: 385, inputHeight: 210, required: true, value: res.revision.response},
                        ]}
                    ]);
                }); 
            } else {
                revSubListLayout.cells("c").collapse();
                revSubListLayout.cells("b").collapse();
                revSubListDetailGrid.clearAll();
            }
        }

        listSubRevTolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    rSubRevListGrid();
                    rSubListDetailGrid();
                    break;
                case "desc_revision":
                    if(!revSubListbGrid.getSelectedRowId()) {
                        return eAlert("Pilih permintaan revisi yang akan diupdate!");
                    }
                    
                    var descPrsWin = createWindow("desc_prs_win", "Update Deskripsi Revisi", 1100, 550);
                    myWins.window("desc_prs_win").skipMyCloseEvent = true;

                    reqJson(Overtime("getPersonilDescription"), "POST", {taskId: revSubListbGrid.cells(revSubListbGrid.getSelectedRowId(), 1).getValue()}, (err, res) => {
                        var descPrsForm = descPrsWin.attachForm([
                            {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Form Revisi", list:[	
                                {type: "block", list: [
                                    {type: "hidden", name: "rev_task_id", value: res.rev_task_id},
                                    {type: "editor", name: "description", label: "Deskripsi", labelWidth: 130, inputWidth: 800, inputHeight: 300, value: res.description},
                                ]},
                                {type: "block", offsetLeft: 30, offsetTop: 10, list: [
                                    {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Update"},
                                    {type: "newcolumn"},
                                    {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                                ]},
                            ]},
                        ]);

                        descPrsForm.attachEvent("onButtonClick", function(name) {
                            switch (name) {
                                case "update":
                                    setDisable(["update", "cancel"], descPrsForm, descPrsWin);
                                    let descPrsFormDP = new dataProcessor(Overtime("updatePrsRevOvtDesc"));
                                    descPrsFormDP.init(descPrsForm);
                                    descPrsForm.save();

                                    descPrsFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                                        let message = tag.getAttribute("message");
                                        switch (action) {
                                            case "updated":
                                                rSubRevListGrid();
                                                rSubListDetailGrid(revSubListbGrid.getSelectedRowId());
                                                sAlert(message);
                                                setEnable(["update", "cancel"], descPrsForm, descPrsWin);
                                                closeWindow("desc_prs_win");
                                                break;
                                            case "error":
                                                eaAlert("Update Gagal" ,message);
                                                rSubRevListGrid();
                                                setEnable(["update", "cancel"], descPrsForm, descPrsWin);
                                                break;
                                        }
                                    });
                                    break;
                                case "cancel":
                                    closeWindow("desc_prs_win");
                                    break;
                            }
                        });
                    });
                    break;
                case "cancel":
                    if(!revSubListbGrid.getSelectedRowId()) {
                        return eAlert("Pilih permintaan revisi yang akan diupdate!");
                    }

                    dhtmlx.modalbox({
                        type: "alert-error",
                        title: "Pembatalan Revisi",
                        text: "Anda yakin akan membatalkan permintaan revisi " + revSubListbGrid.getSelectedRowId() + "?",
                        buttons: ["Ya", "Tidak"],
                        callback: function (index) {
                            if (index == 0) {
                                reqJson(Overtime("cancelRevOvtSub"), "POST", {taskId: revSubListbGrid.getSelectedRowId()}, (err, res) => {
                                    if(res.status === "success") {
                                        rSubRevListGrid();
                                        sAlert(res.message);
                                        rSubListDetailGrid(null);
                                    } else {
                                        rSubRevListGrid();
                                        eaAlert(res.message);
                                    }
                                });
                            }
                        },
                    });
                    break;
            }
        });

        function rSubRevListDetailGrid(taskId = null) {
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

        var revListLayout = reqRevTabs.cells("b").attachLayout({
            pattern: "3J",
            cells: [
                {id: "a", text: "Daftar Pengajuan Revisi Jam Lembur"},
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
        revListDetailGrid.setHeader("No,Task ID,Nama Karyawan,Bagian,Sub Bagian,Nama Mesin #1,Nama Mesin #2,Pelayanan Produksi,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Status Hari,Jam Efektif,Jam Istirahat,Jam Ril,Jam Hit,Premi,Nominal Overtime,Makan,Biaya Makan,Tugas,Status Overtime,DiBuat");
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
            ovtGrid.setHeader("No,Check,Task ID,Nama Karyawan,Bagian,Sub Bagian,Nama Mesin #1,Nama Mesin #2,Pelayanan Produksi,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Status Hari,Jam Efektif,Jam Istirahat,Jam Ril,Jam Hit,Premi,Nominal Overtime,Makan,Biaya Makan,Tugas,Status Overtime,DiBuat");
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
                equal_revision_status: "NONE", 
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