<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	function showInputOvertime() {	
        var legend = legendGrid();
        var machineId = [];
        var machineName = [];
        var personils = [];
        var personilNames = [];
        var bookedPersonil = [];
        var formOvtGrid;
        //@Modal Variabel
        var countMachine;
        var countPerson;

        var comboUrl = {
            department_id: {
                url: Overtime("getDepartment"),
                reload: true
            },
            sub_department_id: {
                reload: false
            },
        }
        
        var inputTabs = mainTab.cells("other_input_overtime").attachTabbar({
            tabs: [
                {id: "a", text: "Form Lembur", active: true},
                {id: "b", text: "Proses Personil"},
            ]
        });

        var times = createTime();

        var initialLeft = [
            {type: "combo", name: "department_id", label: "Sub Unit", labelWidth: 130, inputWidth: 250, readonly: true, required: true},
            {type: "combo", name: "sub_department_id", label: "Bagian", labelWidth: 130, inputWidth: 250, readonly: true, required: true},
            {type: "hidden", name: "division_id", label: "Sub Bagian", labelWidth: 130, inputWidth: 250, readonly: true, value: 0},
            {type: "input", name: "personil", label: "Kebutuhan Orang", labelWidth: 130, inputWidth: 250, required: true, validate:"ValidNumeric"},
            {type: "calendar", name: "overtime_date", label: "Tanggal Lembur", labelWidth: 130, inputWidth: 250, readonly: true, required: true},
            {type: "combo", name: "start_date", label: "Waktu Mulai", labelWidth: 130, inputWidth: 250, required: true, readonly: true,
                validate: "NotEmpty", 
                options: times.startTimes
            },
            {type: "combo", name: "end_date", label: "Waktu Selesai", labelWidth: 130, inputWidth: 250, required: true, readonly: true,
                validate: "NotEmpty", 
                options: times.endTimes,
            },
            {type: "input", name: "notes", label: "Catatan", labelWidth: 130, inputWidth: 250, rows: 3},
            {type: "checkbox", name: "is_production", label: "Lembur Produksi", labelWidth: 130, inputWidth: 250},
            {type: "hidden", name: "machine_id", label: "ID Mesin", labelWidth: 130, inputWidth: 250, readonly: true},
            {type: "input", name: "machine_name", label: "Nama Mesin", labelWidth: 130, inputWidth: 250, readonly: true},
        ];

        const reqs = reqJsonResponse(Overtime("getOTRequirement", {split: 'teknik'}), "GET", null);
        const reqs2 = reqJsonResponse(Overtime("getOTRequirement", {split: 'support'}), "GET", null);

        var initialRight = reqs.data;
        var initialRight2 = reqs2.data;

        var initialForm = inputTabs.cells("a").attachForm([
            {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Data Lembur", list:[	
                {type: "block", list: initialLeft},
                {type: "newcolumn"},
                {type: "fieldset", offsetLeft: 30, label: "Kebutuhan Teknik", list: initialRight},
                {type: "newcolumn"},
                {type: "fieldset", offsetLeft: 30, label: "Kebutuhan Support", list: initialRight2}
            ]},
            {type: "block", offsetLeft: 30, offsetTop: 10, list: [
                {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                {type: "newcolumn"},
                {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"}
            ]},
        ]);
        
        let currentDate = new Date();
        let date = currentDate.toISOString().split('T')[0];
        let overtime_date = initialForm.getCalendar("overtime_date");
        overtime_date.setSensitiveRange(date, null);

        isFormNumeric(initialForm, ['personil']);

        initialForm.hideItem("machine_name");

        var addDeptCombo = initialForm.getCombo("department_id");
        var addSubDeptCombo = initialForm.getCombo("sub_department_id");
        addDeptCombo.load(Overtime("getDepartment", {equal_id: userLogged.deptId}));
        addDeptCombo.attachEvent("onChange", function(value, text){
            clearComboReload(initialForm, "sub_department_id", Overtime("getSubDepartment", {equal_department_id: value, equal_id: userLogged.subId, notequal_id: 5}));
        });

        var startCombo = initialForm.getCombo("start_date");
        var endCombo = initialForm.getCombo("end_date");
        endCombo.selectOption(times.endTimes.length - 1);

        initialForm.attachEvent("onChange", function(name, value) {
            if(name === 'start_date' || name === 'end_date') {
                checkTime(startCombo, endCombo, ['add', 'clear'], initialForm);
            } else if(name === "is_production") {
                if(initialForm.isItemChecked("is_production")) {
                    initialForm.showItem("machine_name");
                } else {
                    initialForm.hideItem("machine_name");
                    initialForm.setItemValue("machine_id", "");
                    initialForm.setItemValue("machine_name", "");
                }
            }
        });

        checkTime(startCombo, endCombo, ['add', 'clear'], initialForm);

        initialForm.attachEvent("onFocus", function(name, value) {
            if(name === 'machine_name') {
                if(initialForm.getItemValue('sub_department_id') === "") {
                    return eAlert("Silahkan pilih Sub Department terlebih dahulu!");
                }

                var macineWindow = createWindow("machine_window", "Daftar Mesin", 900, 500);
                myWins.window("machine_window").skipMyCloseEvent = true;

                var machineToolbar = macineWindow.attachToolbar({
                    icon_path: "./public/codebase/icons/",
                    items: [
                        {id: "save", text: "Simpan", type: "button", img: "ok.png"}
                    ]
                });

                machineToolbar.attachEvent("onClick", function(id) {
                    switch (id) {
                        case "save":
                            machineGrid.filterBy(0,"");
                            machineId = [];
                            machineName = [];
                            setTimeout(() => {
                                let total = 0;
                                for (let i = 0; i < machineGrid.getRowsNum(); i++) {
                                    let id = machineGrid.getRowId(i);
                                    if(machineGrid.cells(id, 1).getValue() == 1) {
                                        machineId.push(id);
                                        machineName.push(machineGrid.cells(id, 2).getValue());
                                    }
                                    total++;
                                }
                                if(countMachine != total) {
                                    eaWarning("Bersihkan Filter", "Silahkan bersihkan filter sebelum klik Simpan!");
                                } else {
                                    initialForm.setItemValue('machine_id', machineId);
                                    initialForm.setItemValue('machine_name', machineName);
                                    closeWindow("machine_window");
                                }
                            }, 200)
                            break;
                    }
                });

                let mStatusBar = macineWindow.attachStatusBar();
                function machineDetailGridCount() {
                    var machineDetailGridRows = machineGrid.getRowsNum();
                    countMachine = machineDetailGridRows;
                    mStatusBar.setText("Total baris: " + machineDetailGridRows);
                    machineId.length > 0 && machineId.map(id => machineGrid.cells(id, 1).setValue(1));
                    
                }

                macineWindow.progressOn();
                machineGrid = macineWindow.attachGrid();
                machineGrid.setImagePath("./public/codebase/imgs/");
                machineGrid.setHeader("No,Check,Nama Mesin,Sub Unit,Bagian,Sub Bagian,Lokasi");
                machineGrid.attachHeader("#rspan,#rspan,#text_filter,#select_filter,#select_filter,#select_filter,#text_filter")
                machineGrid.setColSorting("int,na,str,str,str,str,str");
                machineGrid.setColAlign("center,left,left,left,left,left,left");
                machineGrid.setColTypes("rotxt,ch,rotxt,rotxt,rotxt,rotxt,rotxt");
                machineGrid.setInitWidthsP("5,5,20,20,20,20,25");
                machineGrid.enableSmartRendering(true);
                machineGrid.attachEvent("onXLE", function() {
                    macineWindow.progressOff();
                });
                machineGrid.init();
                machineGrid.clearAndLoad(Overtime("getMachineGrid", {subId: initialForm.getItemValue("sub_department_id")}), machineDetailGridCount);
            }
        })

        initialForm.attachEvent("onButtonClick", function (name) {
            switch (name) {
                case "add":
                    if (!initialForm.validate()) {
                        return eAlert("Input error!");
                    }

                    setDisable(["add", "clear"], initialForm, inputTabs.cells("a"));
                    let initialFormDP = new dataProcessor(Overtime("createInitialOvertime"));
                    initialFormDP.init(initialForm);
                    initialForm.save();

                    initialFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                        let message = tag.getAttribute("message");
                        switch (action) {
                            case "inserted":
                                sAlert("Berhasil Menambahkan Record <br>" + message);
                                machineId = [];
                                machineName = [];
                                clearAllForm(initialForm, comboUrl, null, ['start_date', 'end_date']);
                                initialForm.hideItem("machine_name");
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

        var processlayout = inputTabs.cells("b").attachLayout({
            pattern: "2E",
            cells: [
                {id: "a", text: "Daftar Form Lembur"},
                {id: "b", text: "Proses Personil", collapse: true}
            ]
        });

        var procToolbar = processlayout.cells("a").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "cancel", text: "Batalkan", type: "button", img: "messagebox_critical.png"},
                {id: "personil", text: "Update Kebutuhan Personil", type: "button", img: "person_16.png"},
                {id: "hour_revision", text: "Revisi Waktu Lembur", type: "button", img: "clock.png"},
            ]
        });

        procToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    rProcGrid();
                    formOvtDetailGrid.clearAll();
                    break;
                case "cancel":
                    if(!formOvtGrid.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan dibatalkan!");
                    }

                    let taskId = formOvtGrid.cells(formOvtGrid.getSelectedRowId(), 1).getValue();
                    dhtmlx.modalbox({
                        type: "alert-warning",
                        title: "Konfirmasi Form Lembur",
                        text: "Anda yakin akan membatalkan lembur " + taskId + "?",
                        buttons: ["Ya", "Tidak"],
                        callback: function (index) {
                            if (index == 0) {
                                reqJson(Overtime("cancelOvertime"), "POST", {taskId}, (err, res) => {
                                    if(res.status === "success") {
                                        rProcGrid();
                                        formOvtDetailGrid.clearAll();
                                        processlayout.cells("b").setText("Proses Personil");
                                        processlayout.cells("b").collapse();
                                        machineId = [];
                                        machineName = [];
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
                    if(!formOvtGrid.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan dibatalkan!");
                    }
                    let currPersonil = formOvtGrid.cells(formOvtGrid.getSelectedRowId(), 5).getValue().replace(" Orang", "");
                    let cpWindow = createWindow("input_change_personil", "Update Kebutuhan Orang", 500, 250);
                    myWins.window("input_change_personil").skipMyCloseEvent = true;

                    var cpForm = cpWindow.attachForm([
                        {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Jumlah Kebutuhan Orang", list:[	
                            {type: "block", list: [
                                {type: "hidden", name: "task_id", label: "ID", readonly: true, value: formOvtGrid.cells(formOvtGrid.getSelectedRowId(), 1).getValue()},
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
                                            formOvtGrid.cells(formOvtGrid.getSelectedRowId(), 5).setValue(cpForm.getItemValue("personil") + " Orang");
                                            closeWindow("input_change_personil");
                                            break;
                                        case "error":
                                            eaAlert("Kesalahan Jumlah Personil", message);
                                            setEnable(["update", "cancel"], cpForm, cpWindow);
                                            break;
                                    }
                                });
                                break;
                            case "cancel":
                                closeWindow("input_change_personil");
                                break;
                        }
                    });
                    break;
                case "hour_revision":
                    if(!formOvtGrid.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan di revisi!");
                    }

                    var hourRevWin = createWindow("hour_revision", "Revisi Waktu Lembur", 510, 280);
                    myWins.window("hour_revision").skipMyCloseEvent = true;

                    let ovtTime = getCurrentTime(formOvtGrid, 8, 9);
                        
                    let labelStart = ovtTime.labelStart;
                    let labelEnd = ovtTime.labelEnd;
                    var hourRevForm = hourRevWin.attachForm([
                        {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Jam Lembur", list:[	
                            {type: "block", list: [
                                {type: "hidden", name: "id", label: "ID", labelWidth: 130, inputWidth: 250, value: formOvtGrid.getSelectedRowId()},                               
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
                    })
                   
                    break;
            }
        });

        let procStatusBar = processlayout.cells("a").attachStatusBar();
        function procGridCount() {
            var procGridRows = formOvtGrid.getRowsNum();
            procStatusBar.setText("Total baris: " + procGridRows + " ("+ legend.input_overtime + ")");
        }

        processlayout.cells("a").progressOn();
        formOvtGrid = processlayout.cells("a").attachGrid();
        formOvtGrid.setImagePath("./public/codebase/imgs/");
        formOvtGrid.setHeader("No,Task ID,Sub Unit,Bagian,,Kebutuhan Orang,Status Hari,Tanggal Overtime,Waktu Mulai, Waktu Selesai,Catatan,Makan,Steam,AHU,Compressor,PW,Jemputan,Dust Collector,WFI,Mekanik,Listrik,H&N,QC,QA,Penandaan,GBK,GBB,Status Overtime, Revisi Jam Lembur,Revisi User Approval,Rejection User Approval,Created By,Updated By,Created At");
        formOvtGrid.attachHeader("#rspan,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        formOvtGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        formOvtGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        formOvtGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        formOvtGrid.setInitWidthsP("5,20,20,20,0,15,15,15,20,20,20,7,7,7,7,7,7,10,7,7,7,7,7,7,7,7,7,10,30,30,30,15,15,25");
        formOvtGrid.enableSmartRendering(true);
        formOvtGrid.attachEvent("onXLE", function() {
            processlayout.cells("a").progressOff();
        });
        formOvtGrid.attachEvent("onRowDblClicked", function(rId,cInd){
            rProcPersonGrid(rId);
            processlayout.cells("b").setText("Proses Personil Lembur : " + formOvtGrid.cells(rId, 1).getValue());
            processlayout.cells("b").expand();
        });
        formOvtGrid.init();
        
        function rProcGrid() {
            processlayout.cells("a").progressOn();
            let params = {equal_status: "CREATED"};
            if(userLogged.rankId >= 3 || userLogged.pltRankId >= 3) {
                if(userLogged.rankId >= 6 || userLogged.pltRankId >= 6) {
                    params.in_sub_department_id = userLogged.subId+","+userLogged.pltSubId;
                    params.equal_created_by = userLogged.empId;
                } else {
                    params.in_sub_department_id = userLogged.subId+","+userLogged.pltSubId;
                }
            } else if(userLogged.rankId == 2 || userLogged.pltRankId == 2) {
                params.in_department_id = userLogged.deptId+","+userLogged.pltDeptId;
            }
            formOvtGrid.clearAndLoad(Overtime("getOvertimeGrid", params), procGridCount);
        }

        rProcGrid();

        var detailToolbar = processlayout.cells("b").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "add_person", text: "Manage Personil", type: "button", img: "person_16.png"},
                {id: "delete", text: "Hapus Personil", type: "button", img: "delete.png"},
                {id: "final", text: "Final Submit", type: "button", img: "update.png"},
                {id: "hour_revision", text: "Revisi Waktu Lembur", type: "button", img: "clock.png"},
                {id: "task_revision", text: "Revisi Tugas Lembur", type: "button", img: "edit.png"},
                {id: "change_machine", text: "Revisi Mesin Lembur", type: "button", img: "building_16.png"},
            ]
        });

        formOvtDetailGrid = processlayout.cells("b").attachGrid();
        formOvtDetailGrid.setImagePath("./public/codebase/imgs/");
        formOvtDetailGrid.setHeader("No,Task ID,Nama Karyawan,Sub Unit,Bagian,Disivi,Nama Mesin #1,Nama Mesin #2,,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Status Hari,Jam Efektif,Jam Istirahat,Jam Ril,Jam Hit,Premi,Nominal Overtime,Makan,Tugas,Status Overtime,Status Terakhir,Created By,Updated By,Created At,,");
        formOvtDetailGrid.attachHeader("#rspan,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter");
        formOvtDetailGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        formOvtDetailGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        formOvtDetailGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        formOvtDetailGrid.setInitWidthsP("5,20,20,20,20,20,25,25,0,15,15,15,10,10,10,10,10,10,10,5,25,10,30,15,15,22,0,0");
        formOvtDetailGrid.attachFooter("Total,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,,<div id='other_total_ovt_input'></div>,,,,,,,,,");
        formOvtDetailGrid.enableMultiselect(true);
        formOvtDetailGrid.enableSmartRendering(true);
        formOvtDetailGrid.attachEvent("onXLE", function() {
            processlayout.cells("b").progressOff();
        });
        formOvtDetailGrid.init();
        
        function rProcPersonGrid(rId) {
            if(rId) {
                processlayout.cells("b").progressOn();
                let tsakId = formOvtGrid.cells(rId, 1).getValue();
                formOvtDetailGrid.clearAndLoad(Overtime("getOvertimeDetailGrid", {in_status: "CREATED,REJECTED", equal_task_id: tsakId}), setBookedPersonil);
            } else {
                formOvtDetailGrid.clearAll();
                formOvtDetailGrid.callEvent("onGridReconstructed",[]);
                $("#other_total_ovt_input").html("0");
            }
        }

        function setBookedPersonil() {
            bookedPersonil = [];
            sumGridToElement(formOvtDetailGrid, 18, "other_total_ovt_input");
            for (let i = 0; i < formOvtDetailGrid.getRowsNum(); i++) {
                bookedPersonil.push(formOvtDetailGrid.cells2(i, 27).getValue());
            }
        }

        detailToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "add_person":
                    if(!formOvtGrid.getSelectedRowId()) {
                        return eAlert("Belum ada lemburan yang di pilih!");
                    }

                    if(formOvtDetailGrid.getRowsNum() >= formOvtGrid.cells(formOvtGrid.getSelectedRowId(), 5).getValue().replace(" Orang", "")) {
                        return eaWarning("Warning Kebutuhan Orang!", "Jumlah personil sudah cukup!");
                    }

                    var addPersonWin = createWindow("add_person", "Detail Overtime", 1100, 700);
                    myWins.window("add_person").skipMyCloseEvent = true;

                    const detailOvertime = reqJsonResponse(Overtime("getDetailOvertime"), "POST", {id: formOvtGrid.getSelectedRowId()}, null);

                    var personLayout = addPersonWin.attachLayout({
                        pattern: "3U",
                        cells: [
                            {id: "a", text: "Detail", height: 260},
                            {id: "b", text: "Klik Mesin Untuk Memilih", height: 260},
                            {id: "c", text: "Tambah Personil"}
                        ]
                    });

                    personLayout.cells("a").attachHTMLString(detailOvertime.template);

                    let ovtPersonTime = getCurrentTime(formOvtGrid, 8, 9);
                    let startIndex = times.filterTime.indexOf(ovtPersonTime.start);
                    let endIndex = times.filterTime.indexOf(ovtPersonTime.end);
                        
                    var workTime = genWorkTime(times.times, startIndex, endIndex);

                    var personilForm = personLayout.cells("c").attachForm([
                        {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Data Lembur", list:[	
                            {type: "block", list: [
                                {type: "hidden", name: "overtime_id", label: "Overtime ID", labelWidth: 130, inputWidth: 250, value: formOvtGrid.getSelectedRowId()},                               
                                {type: "combo", name: "start_date", label: "Waktu Mulai", labelWidth: 130, inputWidth: 250, required: true, readonly: true,
                                    validate: "NotEmpty", 
                                    options: workTime.newStartTime
                                },
                                {type: "combo", name: "end_date", label: "Waktu Selesai", labelWidth: 130, inputWidth: 250, required: true, readonly: true,
                                    validate: "NotEmpty", 
                                    options: workTime.newEndTime,
                                },
                                {type: "hidden", name: "machine_id", label: "ID Mesin", labelWidth: 130, inputWidth: 250, readonly: true},
                                {type: "input", name: "machine_name", label: "Nama Mesin", labelWidth: 130, inputWidth: 250, readonly: true},
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

                                if(formOvtDetailGrid.getRowsNum() >= formOvtGrid.cells(formOvtGrid.getSelectedRowId(), 5).getValue().replace(" Orang", "")) {
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
                                            clearAllForm(personilForm, null, null, ['start_date', 'end_date']);
                                            rProcPersonGrid(formOvtGrid.getSelectedRowId());
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
                                    addPersonilGrid.filterBy(0,"");
                                    personils = [];
                                    personilNames = [];
                                    setTimeout(() => {
                                        let total = 0;
                                        for (let i = 0; i < addPersonilGrid.getRowsNum(); i++) {
                                            let id = addPersonilGrid.getRowId(i);
                                            if(addPersonilGrid.cells(id, 1).getValue() == 1) {
                                                personils.push(id);
                                                personilNames.push(addPersonilGrid.cells(id, 2).getValue());
                                            }
                                            total++;
                                        }
                                        if(countPerson != total) {
                                            eaWarning("Bersihkan Filter", "Silahkan bersihkan filter sebelum klik Simpan!");
                                        } else {
                                            personilForm.setItemValue('personil_id', personils);
                                            personilForm.setItemValue('personil_name', personilNames);
                                            closeWindow("add_personil_win");
                                        }
                                    }, 200)
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
                        addPersonilGrid.clearAndLoad(Overtime("getEmployees"), disabledBookedPersonil);

                        function disabledBookedPersonil() {
                            bookedPersonil.map(empId => addPersonilGrid.setRowColor(empId, "#f7ed74"));
                            countPerson = addPersonilGrid.getRowsNum();
                        }
                    }
                    break;
                case "delete":
                    if(!formOvtDetailGrid.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan hapus!");
                    }
                    
                    reqAction(formOvtDetailGrid, Overtime("personilOvertimeDelete"), 1, (err, res) => {
                        rProcPersonGrid(formOvtGrid.getSelectedRowId());
                        res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                        res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
                    });
                    break;
                case "final":
                    if(!formOvtGrid.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan di final submit!");
                    }

                    if(formOvtDetailGrid.getRowsNum() === 0) {
                        return eaAlert("Peringatan", "Data personil lembur belum ada!");
                    }

                    dhtmlx.modalbox({
                        type: "alert-warning",
                        title: "Konfirmasi Form Lembur",
                        text: "Anda yakin akan melakukan Final Submit, pastikan data lembur sudah sesuai?",
                        buttons: ["Ya", "Tidak"],
                        callback: function (index) {
                            if (index == 0) {
                                
                                let taskId = formOvtGrid.cells(formOvtGrid.getSelectedRowId(), 1).getValue();
                                reqJson(Overtime("processOvertime"), 'POST', {taskId}, (err, res) => {
                                    if(!err) {
                                        if(res.status === "success") {
                                            rProcGrid();
                                            formOvtDetailGrid.clearAll();
                                            processlayout.cells("b").setText("Proses Personil");
                                            processlayout.cells("b").collapse();
                                            machineId = [];
                                            machineName = [];
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
                    if(!formOvtDetailGrid.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan di revisi!");
                    }

                    var hourDetailRevWin = createWindow("hour_revision_detail_input", "Revisi Waktu Lembur", 510, 280);
                    myWins.window("hour_revision_detail_input").skipMyCloseEvent = true;

                    let ovtTime = getCurrentTime(formOvtGrid, 8, 9);
                    let startWinIndex = times.filterTime.indexOf(ovtTime.start);
                    let endWinIndex = times.filterTime.indexOf(ovtTime.end);
                        
                    var workTime = genWorkTime(times.times, startWinIndex, endWinIndex);

                    var labelStartDetail = ovtTime.labelStart;
                    var labelEndDetail = ovtTime.labelEnd;
                    var hourDetailRevForm = hourDetailRevWin.attachForm([
                        {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Jam Lembur", list:[	
                            {type: "block", list: [
                                {type: "hidden", name: "id", label: "ID", labelWidth: 130, inputWidth: 250, value: formOvtDetailGrid.getSelectedRowId()},                               
                                {type: "hidden", name: "labelStartDetail", label: "Start Date", labelWidth: 130, inputWidth: 250, value: labelStartDetail},                               
                                {type: "combo", name: "start_date", label: "<span id='labelStartDetail'>"+labelStartDetail+"</span>", labelWidth: 130, inputWidth: 250, required: true, readonly: true,
                                    validate: "NotEmpty", 
                                    options: workTime.newStartTime
                                },
                                {type: "hidden", name: "labelEndDetail", label: "End Date", labelWidth: 130, inputWidth: 250, value: labelEndDetail},                               
                                {type: "combo", name: "end_date", label: "<span id='labelEndDetail'>"+labelEndDetail+"</span>", labelWidth: 130, inputWidth: 250, required: true, readonly: true,
                                    validate: "NotEmpty", 
                                    options: workTime.newEndTime,
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
                    let ovtDetailTime = getCurrentTime(formOvtDetailGrid, 10, 11);
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
                                            rProcPersonGrid(formOvtGrid.getSelectedRowId());
                                            sAlert(message);
                                            setEnable(["update", "cancel"], hourDetailRevForm, hourDetailRevWin);
                                            closeWindow("hour_revision_detail_input");
                                            break;
                                        case "error":
                                            eaAlert("Kesalahan Waktu Lembur", message);
                                            setEnable(["update", "cancel"], hourDetailRevForm, hourDetailRevWin);
                                            break;
                                    }
                                });
                                break;
                            case "cancel":
                                closeWindow("hour_revision_detail_input");
                                break;

                        }
                    })
                    break;
                case "task_revision":
                    if(!formOvtDetailGrid.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan di revisi!");
                    }
                    var taskDetailRevWin = createWindow("task_revision_detail_input", "Revisi Tugas Lembur", 510, 320);
                    myWins.window("task_revision_detail_input").skipMyCloseEvent = true;

                    var taskDetailRevForm = taskDetailRevWin.attachForm([
                        {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Jam Lembur", list:[	
                            {type: "block", list: [
                                {type: "hidden", name: "id", label: "ID", labelWidth: 130, inputWidth: 250, value: formOvtDetailGrid.getSelectedRowId()},                               
                                {type: "input", name: "empTask", label: "Task ID", labelWidth: 130, inputWidth: 250, readonly: true, value: formOvtDetailGrid.cells(formOvtDetailGrid.getSelectedRowId(), 1).getValue()},                               
                                {type: "input", name: "notes", label: "Tugas Lembur", labelWidth: 130, inputWidth: 250, rows: 3},                               
                            ]},
                        ]},
                        {type: "newcolumn"},
                        {type: "block", offsetLeft: 30, offsetTop: 10, list: [
                            {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Update"},
                            {type: "newcolumn"},
                            {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Clear"}
                        ]},
                    ]);

                    taskDetailRevForm.attachEvent("onButtonClick", function(id) {
                        switch (id) {
                            case "update":
                                setDisable(["update", "cancel"], taskDetailRevForm, taskDetailRevWin);
                                let taskDetailRevFormDP = new dataProcessor(Overtime("updateOvertimeDetailNotes"));
                                taskDetailRevFormDP.init(taskDetailRevForm);
                                taskDetailRevForm.save();

                                taskDetailRevFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                                    let message = tag.getAttribute("message");
                                    switch (action) {
                                        case "updated":
                                            rProcPersonGrid(formOvtGrid.getSelectedRowId());
                                            sAlert(message);
                                            setEnable(["update", "cancel"], taskDetailRevForm, taskDetailRevWin);
                                            closeWindow("task_revision_detail_input");
                                            break;
                                        case "error":
                                            eaAlert("Kesalahan Waktu Lembur", message);
                                            setEnable(["update", "cancel"], taskDetailRevForm, taskDetailRevWin);
                                            break;
                                    }
                                });
                                break;
                            case "cancel":
                                closeWindow("task_revision_detail_input");
                                break;
                        }
                    });
                    break;
                case "change_machine":
                    if(!formOvtDetailGrid.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan di revisi!");
                    }

                    var revMachineWin = createWindow("rev_machine_win", "Revisi Mesin", 710, 320);
                    myWins.window("rev_machine_win").skipMyCloseEvent = true;

                    var mWinToolbar = revMachineWin.attachToolbar({
                        icon_path: "./public/codebase/icons/",
                        items: [
                            {id: "update", text: "Simpan", type: "button", img: "update.png"},
                        ]
                    })

                    var mWinMenu = revMachineWin.attachMenu({
                        icon_path: "./public/codebase/icons/",
                        items: [
                            {id: "a", text: "Tahan CTRL untuk memilih mesin lebih dari 1"}
                        ]
                    })

                    const detailOvertimeWin = reqJsonResponse(Overtime("getDetailOvertime"), "POST", {id: formOvtGrid.getSelectedRowId()}, null);

                    if(detailOvertimeWin.overtime.machine_ids) {
                        revMachineWin.progressOn();
                        var machineWinGrid = revMachineWin.attachGrid();
                        machineWinGrid.setImagePath("./public/codebase/imgs/");
                        machineWinGrid.setHeader("No,Nama Mesin,Lokasi");
                        machineWinGrid.setColSorting("int,str,str");
                        machineWinGrid.setColAlign("center,left,left");
                        machineWinGrid.setColTypes("rotxt,rotxt,rotxt");
                        machineWinGrid.setInitWidthsP("5,45,50");
                        machineWinGrid.enableMultiselect(true);
                        machineWinGrid.enableSmartRendering(true);
                        machineWinGrid.attachEvent("onXLE", function() {
                            revMachineWin.progressOff();
                        });
                        machineWinGrid.init();
                        machineWinGrid.clearAndLoad(Overtime("getOvertimeMachine", {equal_sub_department_id: userLogged.subId}));
                    } else {
                        revMachineWin.attachHTMLString("<div style='width:100%;height:100%;display:flex;flex-direction:center;justify-content:center;align-items:center;font-family:sans-serif'>No Machine</div>");
                    }

                    mWinToolbar.attachEvent("onClick", function(id) {
                        switch (id) {
                            case "update":
                                let ids = machineWinGrid.getSelectedRowId();
                                reqJson(Overtime("updatePersonilMachine"), "POST", {ids, id: formOvtDetailGrid.getSelectedRowId()}, (err, res) => {
                                    if(res.status === "success") {
                                        rProcPersonGrid(formOvtGrid.getSelectedRowId());
                                        closeWindow("rev_machine_win");
                                        sAlert(res.message);
                                    } else {
                                        eAlert(res.message);
                                    }
                                });
                                break;
                        }
                    });
                    break;
            }
        })
    }
JS;

header('Content-Type: application/javascript');
echo $script;