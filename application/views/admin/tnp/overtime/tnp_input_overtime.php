<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	function showInputOvertimeTNP(process = null) {	
        var legend = legendGrid();
        var personils = [];
        var personilNames = [];
        var requireName = [];
        var bookedPersonil = [];
        var formOvtGridTnp;

        var comboUrl = {
            department_id: {
                url: Overtime("getDepartment"),
                reload: true
            },
            sub_department_id: {
                reload: false
            },
            division_id: {
                reload: false
            },
        }
        
        if(process) {
            var inputTabItems = [
                {id: "a", text: "Form Lembur", active: true},
                {id: "b", text: "Proses Personil"},
            ];
        } else {
            var inputTabItems = [
                {id: "a", text: "Form Lembur"},
                {id: "b", text: "Proses Personil" , active: true},
            ];
        }

        var inputTabs = mainTab.cells("tnp_input_overtime").attachTabbar({
            tabs: inputTabItems
        });

        var times = createTime();

        var initialLeft = [
            {type: "hidden", name: "ref", value: "-", readonly: true, required: true},
            {type: "combo", name: "department_id", label: "Sub Unit", labelWidth: 130, inputWidth: 250, readonly: true, required: true},
            {type: "combo", name: "sub_department_id", label: "Bagian", labelWidth: 130, inputWidth: 250, readonly: true, required: true},
            {type: "combo", name: "division_id", label: "Sub Bagian", labelWidth: 130, inputWidth: 250, readonly: true,required: true},
            {type: "input", name: "personil", label: "Kebutuhan Orang", labelWidth: 130, inputWidth: 250, required: true, validate:"ValidNumeric"},
            {type: "calendar", name: "overtime_date", label: "Tanggal Lembur", labelWidth: 130, inputWidth: 250, readonly: true, required: true},
            {type: "combo", name: "start_date", label: "Waktu Mulai", labelWidth: 130, inputWidth: 250, required: true,
                validate: "NotEmpty", 
                options: times.startTimes
            },
            {type: "combo", name: "end_date", label: "Waktu Selesai", labelWidth: 130, inputWidth: 250, required: true, 
                validate: "NotEmpty", 
                options: times.endTimes,
            },
            {type: "input", name: "notes", label: "Catatan", labelWidth: 130, inputWidth: 250, rows: 3},
        ];

        const reqs = reqJsonResponse(Overtime("getOTRequirement", {mtn: true}), "GET", null);

        var initialRight = reqs.data;

        var initialForm = inputTabs.cells("a").attachForm([
            {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Data Lembur", list:[	
                {type: "block", list: initialLeft},
                {type: "newcolumn"},
                {type: "fieldset", offsetLeft: 30, label: "Kebutuhan Lembur", list: initialRight}
            ]},
            {type: "block", offsetLeft: 30, offsetTop: 10, list: [
                {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                {type: "newcolumn"},
                {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"}
            ]},
        ]);

        var addDeptCombo = initialForm.getCombo("department_id");
        var addSubCombo = initialForm.getCombo("sub_department_id");

        addDeptCombo.load(Overtime("getDepartment", {equal_id: 1}));
        addDeptCombo.attachEvent("onChange", function(value, text){
            clearComboReload(initialForm, "sub_department_id", Overtime("getSubDepartment", {equal_id: 5}));
        });

        addSubCombo.attachEvent("onChange", function(value, text){
            clearComboReload(initialForm, "division_id", Emp("getDivision", {subDeptId: value}));
        });

        isFormNumeric(initialForm, ['personil']);

        var startCombo = initialForm.getCombo("start_date");
        var endCombo = initialForm.getCombo("end_date");
        endCombo.selectOption(times.endTimes.length - 1);

        initialForm.attachEvent("onChange", function(name, value) {
            if(name === 'start_date' || name === 'end_date') {
                checkTime(startCombo, endCombo, ['add', 'clear'], initialForm, "makan");
            }
        });

        checkTime(startCombo, endCombo, ['add', 'clear'], initialForm, "makan");

        initialForm.attachEvent("onButtonClick", function (name) {
            switch (name) {
                case "add":
                    if (!initialForm.validate()) {
                        return eAlert("Input error!");
                    }

                    setDisable(["add", "clear"], initialForm, inputTabs.cells("a"));
                    let initialFormDP = new dataProcessor(Overtime("createInitialOvertime", {ref: "-"}));
                    initialFormDP.init(initialForm);
                    initialForm.save();

                    initialFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                        let message = tag.getAttribute("message");
                        switch (action) {
                            case "inserted":
                                sAlert("Berhasil Menambahkan Record <br>" + message);
                                requireName = [];
                                clearAllForm(initialForm, comboUrl, null, ['start_date', 'end_date']);
                                rProcGrid();
                                setEnable(["add", "clear"], initialForm, inputTabs.cells("a"));
                                break;
                            case "error":
                                eaAlert("Kesalahan Waktu Lembur", message);
                                setEnable(["add", "clear"], initialForm, inputTabs.cells("a"));
                                break;
                        }
                    });
                    break;
                case "clear":
                    clearAllForm(initialForm, comboUrl, null, ['start_date', 'end_date']);
                    break;
            }
        });

        var processlayoutTnp = inputTabs.cells("b").attachLayout({
            pattern: "2E",
            cells: [
                {id: "a", text: "Daftar Form Lembur"},
                {id: "b", text: "Proses Personil", collapse: true}
            ]
        });

        var procToolbar = processlayoutTnp.cells("a").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "cancel", text: "Batalkan", type: "button", img: "messagebox_critical.png"},
                {id: "personil", text: "Update Kebutuhan Personil", type: "button", img: "person_16.png"},
                {id: "hour_revision", text: "Update Waktu Lembur", type: "button", img: "clock.png"},
                {id: "production_detail", text: "Detail Lembur Produksi", type: "button", img: "edit.png"},
            ]
        });

        procToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    rProcGrid();
                    formOvtDetailGridTnp.clearAll();
                    break;
                case "cancel":
                    if(!formOvtGridTnp.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan dibatalkan!");
                    }

                    let taskId = formOvtGridTnp.cells(formOvtGridTnp.getSelectedRowId(), 1).getValue();
                    dhtmlx.modalbox({
                        type: "alert-warning",
                        title: "Konfirmasi Form Lembur",
                        text: "Anda yakin akan membatalkan lembur " + taskId + "?",
                        buttons: ["Ya", "Tidak"],
                        callback: function (index) {
                            if (index == 0) {
                                reqJson(Overtime("cancelOvertimeMtn"), "POST", {taskId}, (err, res) => {
                                    if(res.status === "success") {
                                        rProcGrid();
                                        formOvtDetailGridTnp.clearAll();
                                        processlayoutTnp.cells("b").setText("Proses Personil");
                                        processlayoutTnp.cells("b").collapse();
                                        personils = [];
                                        personilNames = [];
                                        bookedPersonil = [];
                                    }
                                    sAlert(res.message);
                                });
                            }
                        },
                    });
                    break;
                case "personil":
                    if(!formOvtGridTnp.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan dibatalkan!");
                    }
                    let currPersonil = formOvtGridTnp.cells(formOvtGridTnp.getSelectedRowId(), 5).getValue().replace(" Orang", "");
                    let cpWindow = createWindow("change_personil", "Update Kebutuhan Orang", 500, 250);
                    myWins.window("change_personil").skipMyCloseEvent = true;

                    var cpForm = cpWindow.attachForm([
                        {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Jumlah Kebutuhan Orang", list:[	
                            {type: "block", list: [
                                {type: "hidden", name: "task_id", label: "ID", readonly: true, value: formOvtGridTnp.cells(formOvtGridTnp.getSelectedRowId(), 1).getValue()},
                                {type: "input", name: "personil", label: "Jumlah Orang", labelWidth: 130, inputWidth: 250, value: currPersonil, validate:"ValidNumeric"},
                            ]},
                        ]},
                        {type: "block", offsetLeft: 30, offsetTop: 10, list: [
                            {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Update"},
                            {type: "newcolumn"},
                            {type: "button", name: "cancel", className: "button_clear", offsetLeft: 30, value: "Cancel"}
                        ]},
                    ]);
                    isFormNumeric(cpForm, ['personil']);

                    cpForm.attachEvent("onButtonClick", function(id) {
                        switch (id) {
                            case "update":
                                if(!cpForm.validate()) {
                                    return eAlert("Input error!");
                                }
                                setDisable(["update", "cancel"], cpForm, cpWindow);
                                let cpFormDP = new dataProcessor(Overtime("updatePersonilNeeded"));
                                cpFormDP.init(cpForm);
                                cpForm.save();

                                cpFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                                    let message = tag.getAttribute("message");
                                    switch (action) {
                                        case "updated":
                                            sAlert(message);
                                            setEnable(["update", "cancel"], cpForm, cpWindow);
                                            formOvtGridTnp.cells(formOvtGridTnp.getSelectedRowId(), 5).setValue(cpForm.getItemValue("personil") + " Orang");
                                            closeWindow("change_personil");
                                            break;
                                        case "error":
                                            eaAlert(message);
                                            setEnable(["update", "cancel"], cpForm, cpWindow);
                                            break;
                                    }
                                });
                                break;
                            case "cancel":
                                closeWindow("change_personil");
                                break;
                        }
                    });
                    break;
                case "hour_revision":
                    if(!formOvtGridTnp.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan di revisi!");
                    }

                    var hourRevWin = createWindow("hour_revision_parent_tnp", "Revisi Waktu Lembur", 510, 280);
                    myWins.window("hour_revision_parent_tnp").skipMyCloseEvent = true;

                    let ovtTime = getCurrentTime(formOvtGridTnp, 8, 9);
                        
                    let labelStart = ovtTime.labelStart;
                    let labelEnd = ovtTime.labelEnd;
                    var hourRevForm = hourRevWin.attachForm([
                        {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Jam Lembur", list:[	
                            {type: "block", list: [
                                {type: "hidden", name: "id", label: "ID", labelWidth: 130, inputWidth: 250, value: formOvtGridTnp.getSelectedRowId()},                               
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
                                let hourRevFormDP = new dataProcessor(Overtime("updateOvertimeHour"));
                                hourRevFormDP.init(hourRevForm);
                                hourRevForm.save();

                                hourRevFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                                    let message = tag.getAttribute("message");
                                    switch (action) {
                                        case "updated":
                                            rProcGrid();
                                            rProcPersonGrid(null);
                                            sAlert(message);
                                            setEnable(["update", "cancel"], hourRevForm, hourRevWin);
                                            closeWindow("hour_revision_parent_tnp");
                                            break;
                                        case "error":
                                            eaAlert("Kesalahan Waktu Lembur", message);
                                            setEnable(["update", "cancel"], hourRevForm, hourRevWin);
                                            break;
                                    }
                                });
                                break;
                            case "cancel":
                                closeWindow("hour_revision_parent_tnp");
                                break;

                        }
                    });
                    break;
                case "production_detail":
                    if(!formOvtGridTnp.getSelectedRowId()) {
                        return eAlert("Silahkan pilih lemburan!");
                    } else {
                        let tabName = "tnp_production_detail_" + formOvtGridTnp.getSelectedRowId();
                        if(!inputTabs.tabs(tabName)) {
                            inputTabs.addTab(tabName, "Detail Lembur Produksi " + formOvtGridTnp.cells(formOvtGridTnp.getSelectedRowId(), 1).getValue(), null, null, true, true);
                        } else {
                            inputTabs.tabs(tabName).setActive();
                        }

                        var detailLayout = inputTabs.tabs(tabName).attachLayout({
                            pattern: "2E",
                            cells: [
                                {id: "a", text: "Detail Lembur Produksi", height: 260},
                                {id: "b", text: "Daftar Personil Lembur"}
                            ]
                        });

                        detailLayout.cells("b").progressOn();
                        detailGrid = detailLayout.cells("b").attachGrid();
                        detailGrid.setImagePath("./public/codebase/imgs/");
                        detailGrid.setHeader("No,Task ID,Nama Karyawan,Sub Unit,Bagian,Disivi,Nama Mesin #1,Nama Mesin #2,Pelayanan Produksi,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Status Hari,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Premi,Nominal Overtime,Makan,Tugas,Status Overtime,Status Terakhir,Created By,Updated By,Created At,Nik Rejector,EmpID");
                        detailGrid.attachHeader("#rspan,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
                        detailGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
                        detailGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
                        detailGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
                        detailGrid.setInitWidthsP("5,20,20,20,20,20,0,0,15,15,15,15,10,10,10,10,10,10,10,5,25,10,30,15,15,22,0,0");
                        detailGrid.attachFooter("Total,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,,<div id='tnp_total_ovt_prod_detail_"+formOvtGridTnp.getSelectedRowId()+"'></div>,,,,,,,,,");
                        detailGrid.enableSmartRendering(true);
                        detailGrid.attachEvent("onXLE", function() {
                            detailLayout.cells("b").progressOff();
                        });
                        detailGrid.init();
                        
                        function rDetailGrid(taskId) {
                            detailLayout.cells("b").progressOn();
                            detailGrid.clearAndLoad(Overtime("getOvertimeDetailGrid", {notin_status: "CANCELED,REJECTED", equal_task_id: taskId}), countDetailOvertime);
                        }

                        function countDetailOvertime() {
                            sumGridToElement(detailGrid, 18, "tnp_total_ovt_prod_detail_" + formOvtGridTnp.getSelectedRowId());
                        }

                        reqJson(Overtime("getOvertimeDetailView"), "POST", {id: formOvtGridTnp.getSelectedRowId()}, (err, res) => {
                            if(res.status === "success") {
                                detailLayout.cells("a").attachHTMLString(res.template);
                                rDetailGrid(res.ref);
                            }
                        });
                    }
                    break
            }
        });

        let procStatusBar = processlayoutTnp.cells("a").attachStatusBar();
        function procGridCount() {
            var procGridRows = formOvtGridTnp.getRowsNum();
            procStatusBar.setText("Total baris: " + procGridRows + " (" + legend.input_overtime + ")");
        }

        processlayoutTnp.cells("a").progressOn();
        formOvtGridTnp = processlayoutTnp.cells("a").attachGrid();
        formOvtGridTnp.setImagePath("./public/codebase/imgs/");
        formOvtGridTnp.setHeader("No,Task ID,Sub Unit,Bagian,Disivi,Kebutuhan Orang,Status Hari,Tanggal Overtime,Waktu Mulai, Waktu Selesai,Catatan,Makan,Steam,AHU,Compressor,PW,Jemputan,Dust Collector,Mekanik,Listrik,H&N,Status Overtime, Revisi Jam Lembur,Revisi User Approval,Rejection User Approval,Created By,Updated By,Created At");
        formOvtGridTnp.attachHeader("#rspan,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        formOvtGridTnp.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        formOvtGridTnp.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        formOvtGridTnp.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        formOvtGridTnp.setInitWidthsP("5,20,20,20,20,15,15,15,20,20,20,7,7,7,7,7,7,7,7,7,7,10,30,30,30,15,15,25");
        formOvtGridTnp.enableSmartRendering(true);
        formOvtGridTnp.attachEvent("onXLE", function() {
            processlayoutTnp.cells("a").progressOff();
        });
        formOvtGridTnp.attachEvent("onRowDblClicked", function(rId,cInd){
            rProcPersonGrid(rId);
            processlayoutTnp.cells("b").setText("Proses Personil Lembur : " + formOvtGridTnp.cells(rId, 1).getValue());
            processlayoutTnp.cells("b").expand();
        });
        formOvtGridTnp.init();
        
        function rProcGrid() {
            processlayoutTnp.cells("a").progressOn();
            let params = {in_status: "CREATED", notequal_ref: ""};
            formOvtGridTnp.clearAndLoad(Overtime("getOvertimeGrid", params), procGridCount);
        }

        rProcGrid();

        var detailToolbar = processlayoutTnp.cells("b").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "add_person", text: "Manage Personil", type: "button", img: "person_16.png"},
                {id: "delete", text: "Hapus Personil", type: "button", img: "delete.png"},
                {id: "final", text: "Final Submit", type: "button", img: "update.png"},
                {id: "hour_revision", text: "Revisi Waktu Lembur", type: "button", img: "clock.png"},
            ]
        });

        formOvtDetailGridTnp = processlayoutTnp.cells("b").attachGrid();
        formOvtDetailGridTnp.setImagePath("./public/codebase/imgs/");
        formOvtDetailGridTnp.setHeader("No,Task ID,Nama Karyawan,Sub Unit,Bagian,Disivi,Nama Mesin #1,Nama Mesin #2,Pelayanan Produksi,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Status Hari,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Premi,Nominal Overtime,Makan,Tugas,Status Overtime,Status Terakhir,Created By,Updated By,Created At,Nik Rejector,EmpID");
        formOvtDetailGridTnp.attachHeader("#rspan,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        formOvtDetailGridTnp.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        formOvtDetailGridTnp.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        formOvtDetailGridTnp.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        formOvtDetailGridTnp.setInitWidthsP("5,20,20,20,20,20,0,0,15,15,15,15,10,10,10,10,10,10,10,5,25,10,30,15,15,22,0,0");
        formOvtDetailGridTnp.attachFooter("Total,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,,<div id='tnp_total_ovt_input'></div>,,,,,,,,,");
        formOvtDetailGridTnp.enableMultiselect(true);
        formOvtDetailGridTnp.enableSmartRendering(true);
        formOvtDetailGridTnp.attachEvent("onXLE", function() {
            processlayoutTnp.cells("b").progressOff();
        });
        formOvtDetailGridTnp.init();
        
        function rProcPersonGrid(rId) {
            if(rId) {
                processlayoutTnp.cells("b").progressOn();
                let tsakId = formOvtGridTnp.cells(rId, 1).getValue();
                formOvtDetailGridTnp.clearAndLoad(Overtime("getOvertimeDetailGrid", {in_status: "CREATED,REJECTED", equal_task_id: tsakId}), setBookedPersonil);
            } else {
                formOvtDetailGridTnp.clearAll();
                formOvtDetailGridTnp.callEvent("onGridReconstructed",[]);
                $("#tnp_total_ovt_input").html("0");
            }
        }

        function setBookedPersonil() {
            bookedPersonil = [];
            sumGridToElement(formOvtDetailGridTnp, 18, "tnp_total_ovt_input");
            for (let i = 0; i < formOvtDetailGridTnp.getRowsNum(); i++) {
                bookedPersonil.push(formOvtDetailGridTnp.cells2(i, 27).getValue());
            }
        }

        detailToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "add_person":
                    if(!formOvtGridTnp.getSelectedRowId()) {
                        return eAlert("Belum ada lemburan yang di pilih!");
                    }

                    if(formOvtDetailGridTnp.getRowsNum() >= formOvtGridTnp.cells(formOvtGridTnp.getSelectedRowId(), 5).getValue().replace(" Orang", "")) {
                        return eaWarning("Warning Kebutuhan Orang!", "Jumlah personil sudah cukup!");
                    }

                    var addPersonWin = createWindow("add_person", "Detail Overtime", 1100, 700);
                    myWins.window("add_person").skipMyCloseEvent = true;

                    const detailOvertime = reqJsonResponse(Overtime("getDetailOvertime"), "POST", {id: formOvtGridTnp.getSelectedRowId()}, null);

                    var personLayout = addPersonWin.attachLayout({
                        pattern: "3U",
                        cells: [
                            {id: "a", text: "Detail", height: 260},
                            {id: "b", text: "Klik Pelayanan Untuk Memilih", height: 260},
                            {id: "c", text: "Tambah Personil"}
                        ]
                    });

                    personLayout.cells("a").attachHTMLString(detailOvertime.template);
                    let ovtPersonTime = getCurrentTime(formOvtGridTnp, 8, 9);
                    let startIndex1 = times.filterTime.indexOf(ovtPersonTime.start);
                    let endIndex1 = times.filterTime.indexOf(ovtPersonTime.end);
                    let startIndex2 = times.filterTime.indexOf(ovtPersonTime.start);
                    let endIndex2 = times.filterTime.indexOf(ovtPersonTime.end);
                        
                    var workTime1 = genWorkTime(times.times, startIndex1, endIndex1);
                    var workTime2 = genWorkTime(times.times, startIndex2, endIndex2);

                    var personilForm = personLayout.cells("c").attachForm([
                        {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Data Lembur", list:[	
                            {type: "block", list: [
                                {type: "hidden", name: "overtime_id", label: "Overtime ID", labelWidth: 130, inputWidth: 250, value: formOvtGridTnp.getSelectedRowId()},                               
                                {type: "combo", name: "start_date", label: "Waktu Mulai", labelWidth: 130, inputWidth: 250, required: true,
                                    validate: "NotEmpty", 
                                    options: workTime1.newStartTime
                                },
                                {type: "combo", name: "end_date", label: "Waktu Selesai", labelWidth: 130, inputWidth: 250, required: true, 
                                    validate: "NotEmpty", 
                                    options: workTime2.newEndTime,
                                },
                                {type: "input", name: "requirements", label: "Nama Pelayanan", labelWidth: 130, inputWidth: 250, readonly: true},
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
                    endPCombo.selectOption(workTime2.newEndTime.length - 1);
                   
                    personilForm.attachEvent("onChange", function(name, value) {
                        if(name === 'start_date' || name === 'end_date') {
                            checkTime(startPCombo, endPCombo, ['add', 'clear'], personilForm);
                        }
                    });

                    var isReqGrid = detailOvertime.overtime.steam == 0 && detailOvertime.overtime.ahu == 0 && detailOvertime.overtime.compressor == 0 &&
                                    detailOvertime.overtime.pw == 0 && detailOvertime.overtime.dust_collector == 0 && detailOvertime.overtime.wfi == 0 && 
                                    detailOvertime.overtime.hnn == 0;
                    if(isReqGrid) {
                        personLayout.cells("b").attachHTMLString("<div style='width:100%;height:100%;display:flex;flex-direction:center;justify-content:center;align-items:center;font-family:sans-serif'>No Support</div>");
                        personLayout.cells("b").setText("Lembur Umum");
                        personLayout.cells("b").collapse();
                        personilForm.hideItem("requirements");
                    } else {
                        var reqMenu = personLayout.cells("b").attachMenu({
                            icon_path: "./public/codebase/icons/",
                            items: [
                                {id: "clear", text: "Clear", img: "refresh.png"}
                            ]
                        });

                        reqMenu.attachEvent("onClick", function(id) {
                            switch (id) {
                                case "clear":
                                    reqGrid.clearSelection();
                                    personilForm.setItemValue("requirements", "");
                                    break;
                            }
                        });

                        personLayout.cells("b").progressOn();
                        var reqGrid = personLayout.cells("b").attachGrid();
                        reqGrid.setImagePath("./public/codebase/imgs/");
                        reqGrid.setHeader("No,Nama Pelayanan,Sub Bagian,Division ID");
                        reqGrid.setColSorting("int,str,str,str");
                        reqGrid.setColAlign("center,left,left,left");
                        reqGrid.setColTypes("rotxt,rotxt,rotxt,rotxt");
                        reqGrid.setInitWidthsP("5,45,50,0");
                        reqGrid.enableMultiselect(true);
                        reqGrid.enableSmartRendering(true);
                        reqGrid.attachEvent("onXLE", function() {
                            personLayout.cells("b").progressOff();
                        });
                        reqGrid.attachEvent("onRowSelect", function(rId, cIdn) {
                            let splitId = reqGrid.getSelectedRowId().split(",");
                            let name = [];
                            splitId.map(id => name.push(reqGrid.cells(id, 1).getValue()));
                            personilForm.setItemValue("requirements", name);
                        });
                        reqGrid.init();
                        reqGrid.clearAndLoad(Overtime("getOvertimeRequirement", {task_id: detailOvertime.overtime.task_id}));
                    }

                    personilForm.attachEvent("onFocus", function(name, value) {
                        if(name === 'personil_name') {
                            if(!isReqGrid) {
                                if(reqGrid.getSelectedRowId()) {
                                    let splitId = reqGrid.getSelectedRowId().split(",");
                                    let divIds = [];
                                    splitId.map(id => divIds.push(reqGrid.cells(id, 3).getValue()));
                                    let stringDiv = divIds.join(",");
                                    loadPersonil(stringDiv);
                                } else {
                                    loadPersonil();
                                }
                            } else {
                                loadPersonil();
                            }
                        }
                    });

                    personilForm.attachEvent("onButtonClick", function(id) {
                        switch (id) {
                            case "add":
                                if (!personilForm.validate()) {
                                    return eAlert("Input error!");
                                }

                                if(formOvtDetailGridTnp.getRowsNum() >= formOvtGridTnp.cells(formOvtGridTnp.getSelectedRowId(), 5).getValue().replace(" Orang", "")) {
                                    return eaWarning("Warning Kebutuhan Orang!", "Jumlah personil sudah cukup!");
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
                                            if(reqGrid && reqGrid.getSelectedRowId()) {
                                                reqGrid.clearSelection();
                                            }
                                            clearAllForm(personilForm, null, null, ['start_date', 'end_date']);
                                            rProcPersonGrid(formOvtGridTnp.getSelectedRowId());
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

                    function loadPersonil(divIds = null) {
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
                        addPersonilGrid.setHeader("No,Check,Nama Personil,Sub Unit,Bagian,Sub Bagian");
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

                        var params;
                        if(divIds) {
                            params = {in_division_id: divIds};
                        } else {
                            params = {equal_sub_department_id: detailOvertime.overtime.sub_department_id};
                        }
                        addPersonilGrid.clearAndLoad(Overtime("getEmployees", params), disabledBookedPersonil);

                        function disabledBookedPersonil() {
                            bookedPersonil.map(empId => addPersonilGrid.cells(empId, 1).setDisabled(true));
                        }
                    }
                    break;
                case "delete":
                    if(!formOvtDetailGridTnp.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan hapus!");
                    }
                    
                    reqAction(formOvtDetailGridTnp, Overtime("personilOvertimeDelete"), 1, (err, res) => {
                        rProcPersonGrid(formOvtGridTnp.getSelectedRowId());
                        res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                        res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
                    });
                    break;
                case "final":
                    if(!formOvtGridTnp.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan di final submit!");
                    }

                    if(formOvtDetailGridTnp.getRowsNum() === 0) {
                        return eaAlert("Peringatan", "Data personil lembur belum ada!");
                    }

                    dhtmlx.modalbox({
                        type: "alert-warning",
                        title: "Konfirmasi Form Lembur",
                        text: "Anda yakin akan melakukan Final Submit, pastikan data lembur sudah sesuai?",
                        buttons: ["Ya", "Tidak"],
                        callback: function (index) {
                            if (index == 0) {
                                
                                let taskId = formOvtGridTnp.cells(formOvtGridTnp.getSelectedRowId(), 1).getValue();
                                reqJson(Overtime("processOvertime"), 'POST', {taskId}, (err, res) => {
                                    if(!err) {
                                        if(res.status === "success") {
                                            rProcGrid();
                                            formOvtDetailGridTnp.clearAll();
                                            processlayoutTnp.cells("b").setText("Proses Personil");
                                            processlayoutTnp.cells("b").collapse();
                                            personils = [];
                                            personilNames = [];
                                            bookedPersonil = [];
                                        }
                                        sAlert(res.message);
                                    } else {
                                        eAlert("Gagal melakukan final submit!");
                                    }
                                });
                            }
                        },
                    });
                    break;
                case "hour_revision":
                    if(!formOvtDetailGridTnp.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan di revisi!");
                    }

                    var hourDetailRevWin = createWindow("hour_revision_detail_input_tnp", "Revisi Waktu Lembur", 510, 280);
                    myWins.window("hour_revision_detail_input_tnp").skipMyCloseEvent = true;

                    let ovtTime = getCurrentTime(formOvtGridTnp, 8, 9);
                    let startWinIndex1 = times.filterTime.indexOf(ovtTime.start);
                    let endWinIndex1 = times.filterTime.indexOf(ovtTime.end);
                    let startWinIndex2 = times.filterTime.indexOf(ovtTime.start);
                    let endWinIndex2 = times.filterTime.indexOf(ovtTime.end);
                        
                    var workTime1 = genWorkTime(times.times, startWinIndex1, endWinIndex1);
                    var workTime2 = genWorkTime(times.times, startWinIndex2, endWinIndex2);

                    let labelStart = ovtTime.labelStart;
                    let labelEnd = ovtTime.labelEnd;
                    var hourDetailRevForm = hourDetailRevWin.attachForm([
                        {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Jam Lembur", list:[	
                            {type: "block", list: [
                                {type: "hidden", name: "id", label: "ID", labelWidth: 130, inputWidth: 250, value: formOvtDetailGridTnp.getSelectedRowId()},                               
                                {type: "combo", name: "start_date", label: labelStart, labelWidth: 130, inputWidth: 250, required: true,
                                    validate: "NotEmpty", 
                                    options: workTime1.newStartTime
                                },
                                {type: "combo", name: "end_date", label: labelEnd, labelWidth: 130, inputWidth: 250, required: true, 
                                    validate: "NotEmpty", 
                                    options: workTime2.newEndTime
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

                    let startDetailCombo = hourDetailRevForm.getCombo("start_date");
                    let endDetailCombo = hourDetailRevForm.getCombo("end_date");
                    let ovtDetailTime = getCurrentTime(formOvtDetailGridTnp, 10, 11);
                    let startCurrWinIndex = workTime1.filterStart.indexOf(ovtDetailTime.start);
                    let endCurrWinIndex = workTime2.filterEnd.indexOf(ovtDetailTime.end);
                    startDetailCombo.selectOption(startCurrWinIndex);
                    endDetailCombo.selectOption(endCurrWinIndex);

                    hourDetailRevForm.attachEvent("onChange", function(name, value) {
                        if(name === "start_date" || name === "end_date") {
                            checkRevisionTime(times.filterTime, startDetailCombo.getSelectedValue(), endDetailCombo.getSelectedValue(), ['update'], hourDetailRevForm);
                        }
                    });

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
                                            rProcPersonGrid(formOvtGridTnp.getSelectedRowId());
                                            sAlert(message);
                                            setEnable(["update", "cancel"], hourDetailRevForm, hourDetailRevWin);
                                            closeWindow("hour_revision_detail_input_tnp");
                                            break;
                                        case "error":
                                            eaAlert("Kesalahan Waktu Lembur", message);
                                            setEnable(["update", "cancel"], hourDetailRevForm, hourDetailRevWin);
                                            break;
                                    }
                                });
                                break;
                            case "cancel":
                                closeWindow("hour_revision_detail_input_tnp");
                                break;
                        }
                    })
                    break;
            }
        })
    }
JS;

header('Content-Type: application/javascript');
echo $script;