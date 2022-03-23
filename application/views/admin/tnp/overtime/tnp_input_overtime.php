<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	function showInputOvertimeTNP(process) {	
        var legend = legendGrid();
        var personils = [];
        var personilNames = [];
        var requireName = [];
        var bookedPersonil = [];
        var formOvtGridTnp;
        //@Modal Variabel
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
        
        if(process) {
            var inputTabItems = [
                {id: "a", text: "Form Lembur"},
                {id: "b", text: "Proses Personil"},
                {id: "c", text: "Referensi Lembur", active: true},
            ];
        } else {
            var inputTabItems = [
                {id: "a", text: "Form Lembur", active: true},
                {id: "b", text: "Proses Personil"},
                {id: "c", text: "Referensi Lembur"},
            ];
        }

        var inputTabs = mainTab.cells("tnp_input_overtime").attachTabbar({
            tabs: inputTabItems
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
            {type: "input", name: "taskIds", label: "Referensi Lembur", labelWidth: 130, inputWidth: 250, readonly: true, rows: 5},
        ];

        const reqs = reqJsonResponse(Overtime("getOTRequirement", {split: 'teknik'}), "GET", null);
        const reqs2 = reqJsonResponse(Overtime("getOTRequirement", {split: 'support'}), "GET", null);

        var initialRight = reqs.data;
        var initialRight2 = reqs2.data;

        var initialForm = inputTabs.cells("a").attachForm([
            {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "<span id='data_lembur'>Data Lembur (Normal)</span>", list:[	
                {type: "block", list: initialLeft},
                {type: "newcolumn"},
                {type: "fieldset", offsetLeft: 30, label: "<span id='kebutuhan_teknik'>Kebutuhan Teknik</span>", list: initialRight},
                {type: "newcolumn"},
                {type: "fieldset", offsetLeft: 30, label: "<span id='kebutuhan_support'>Kebutuhan Support</span>", list: initialRight2},
            ]},
            {type: "block", offsetLeft: 30, offsetTop: 10, list: [
                {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "<span id='tnp_init_btn'>Tambah</span>"},
                {type: "newcolumn"},
                {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"}
            ]},
        ]);

        let currentDate = new Date();
        let date = currentDate.toISOString().split('T')[0];
        let overtime_date = initialForm.getCalendar("overtime_date");
        overtime_date.setSensitiveRange(date, null);

        var addDeptCombo = initialForm.getCombo("department_id");
        var addSubCombo = initialForm.getCombo("sub_department_id");

        addDeptCombo.load(Overtime("getDepartment", {equal_id: userLogged.deptId}));
        addDeptCombo.attachEvent("onChange", function(value, text){
            clearComboReload(initialForm, "sub_department_id", Overtime("getSubDepartment", {equal_id: userLogged.subId}));
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
                    let initialFormDP = new dataProcessor(Overtime("createInitialOvertime"));
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
                                rReqOvtGrid();
                                break;
                            case "error":
                                eaAlert("Kesalahan Waktu Lembur", message);
                                setEnable(["add", "clear"], initialForm, inputTabs.cells("a"));
                                break;
                        }
                    });
                    break;
                case "clear":
                    $("#tnp_init_btn").html("Tambah");
                    clearAllForm(initialForm, comboUrl, null, ['start_date', 'end_date']);
                    initialForm.uncheckItem("jemputan");
                    initialForm.uncheckItem("ahu");
                    initialForm.uncheckItem("compressor");
                    initialForm.uncheckItem("pw");
                    initialForm.uncheckItem("steam");
                    initialForm.uncheckItem("wfi");
                    initialForm.uncheckItem("mechanic");
                    initialForm.uncheckItem("electric");
                    initialForm.uncheckItem("hnn");
                    initialForm.uncheckItem("qc");
                    initialForm.uncheckItem("qa");
                    initialForm.uncheckItem("penandaan");
                    initialForm.uncheckItem("gbb");
                    initialForm.uncheckItem("gbk");
                    $("#data_lembur").html("Data Lembur (Umum)");
                    $("#kebutuhan_teknik").html("Kebutuhan Teknik");
                    $("#kebutuhan_support").html("Kebutuhan Support");
                    initialForm.hideItem("taskIds");
                    disableRequest();
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
                                        rReqOvtGrid();
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
                                            eaAlert("Kesalahan Jumlah Personil", message);
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
        formOvtGridTnp.setHeader("No,Task ID,Sub Unit,Bagian,,Kebutuhan Orang,Status Hari,Tanggal Overtime,Waktu Mulai, Waktu Selesai,Catatan,Makan,Steam,AHU,Compressor,PW,Jemputan,Dust Collector,WFI,Mekanik,Listrik,H&N,QC,QA,Penandaan,GBK,GBB,Status Overtime, Revisi Jam Lembur,Revisi User Approval,Rejection User Approval,Created By,Updated By,Created At,");
        formOvtGridTnp.attachHeader("#rspan,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#text_filter,#text_filter");
        formOvtGridTnp.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        formOvtGridTnp.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        formOvtGridTnp.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        formOvtGridTnp.setInitWidthsP("5,20,20,20,0,10,10,15,17,17,22,7,7,7,7,7,7,10,7,7,7,7,7,7,7,7,7,10,25,25,25,15,15,22,0");
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
            let params = {in_status: "CREATED"};
            if(userLogged.rankId >= 3 || userLogged.pltRankId >= 3) {
                if(userLogged.rankId >= 6 || userLogged.pltRankId >= 6) {
                    params.in_sub_department_id = userLogged.subId+","+userLogged.pltSubId;
                } else {
                    params.equal_created_by = userLogged.empId;;
                }
            } else if(userLogged.rankId == 2 || userLogged.pltRankId == 2) {
                params.in_department_id = userLogged.deptId+","+userLogged.pltDeptId;
            }
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
                {id: "task_revision", text: "Revisi Tugas Lembur", type: "button", img: "edit.png"},
                {id: "change_req", text: "Revisi Kebutuhan Support", type: "button", img: "tools.png"},
            ]
        });

        formOvtDetailGridTnp = processlayoutTnp.cells("b").attachGrid();
        formOvtDetailGridTnp.setImagePath("./public/codebase/imgs/");
        formOvtDetailGridTnp.setHeader("No,Task ID,Nama Karyawan,Sub Unit,Bagian,,,,Pelayanan Produksi,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Status Hari,Jam Efektif,Jam Istirahat,Jam Ril,Jam Hit,Premi,Nominal Overtime,Makan,Tugas,Status Overtime,Status Terakhir,,Created By,Updated By,Created At,,");
        formOvtDetailGridTnp.attachHeader("#rspan,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        formOvtDetailGridTnp.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        formOvtDetailGridTnp.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        formOvtDetailGridTnp.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        formOvtDetailGridTnp.setInitWidthsP("5,20,20,20,0,20,0,0,25,15,15,15,10,10,10,10,10,10,10,5,25,10,30,0,15,15,22,0,0");
        formOvtDetailGridTnp.attachFooter("Total,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,,<div id='tnp_total_ovt_input'></div>,,,,,,,,,,");
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
                    let startIndex = times.filterTime.indexOf(ovtPersonTime.start);
                    let endIndex = times.filterTime.indexOf(ovtPersonTime.end);
                        
                    var workTime = genWorkTime(times.times, startIndex, endIndex);

                    var personilForm = personLayout.cells("c").attachForm([
                        {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Data Lembur", list:[	
                            {type: "block", list: [
                                {type: "hidden", name: "overtime_id", label: "Overtime ID", labelWidth: 130, inputWidth: 250, value: formOvtGridTnp.getSelectedRowId()},                               
                                {type: "combo", name: "start_date", label: "Waktu Mulai", labelWidth: 130, inputWidth: 250, required: true, readonly: true,
                                    validate: "NotEmpty", 
                                    options: workTime.newStartTime
                                },
                                {type: "combo", name: "end_date", label: "Waktu Selesai", labelWidth: 130, inputWidth: 250, required: true, readonly: true,
                                    validate: "NotEmpty", 
                                    options: workTime.newEndTime,
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
                    endPCombo.selectOption(workTime.newEndTime.length - 1);
                   
                    personilForm.attachEvent("onChange", function(name, value) {
                        if(name === 'start_date' || name === 'end_date') {
                            checkTime(startPCombo, endPCombo, ['add', 'clear'], personilForm);
                        }
                    });

                    if(userLogged.subId == 5) {
                        var isReqGrid = detailOvertime.overtime.steam == 0 && detailOvertime.overtime.ahu == 0 && detailOvertime.overtime.compressor == 0 &&
                                    detailOvertime.overtime.pw == 0 && detailOvertime.overtime.dust_collector == 0 && detailOvertime.overtime.wfi == 0 && 
                                    detailOvertime.overtime.hnn == 0;
                    } else if(userLogged.subId == 7) {
                        var isReqGrid = detailOvertime.overtime.qa == 0;
                    } else if(userLogged.subId == 8) {
                        var isReqGrid = detailOvertime.overtime.qc == 0;
                    } else if(userLogged.subId == 13) {
                        var isReqGrid = detailOvertime.overtime.penandaan == 0 && detailOvertime.overtime.gbk == 0 && detailOvertime.overtime.gbb == 0;
                    }
                   
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
                            personilForm.setItemFocus("overtime_id");
                            loadPersonil();
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
                        addPersonilGrid.setColSorting("int,na,str,str,str,str");
                        addPersonilGrid.setColTypes("rotxt,ch,rotxt,rotxt,rotxt,rotxt");
                        addPersonilGrid.setInitWidthsP("5,5,20,20,25,25");
                        addPersonilGrid.enableSmartRendering(true);
                        addPersonilGrid.attachEvent("onXLE", function() {
                            personils.length > 0 && personils.map(id => id !== '' && addPersonilGrid.cells(id, 1).setValue(1));
                            addPersonilWin.progressOff();
                        });
                        addPersonilGrid.init();
                        let params;
                        if(userLogged.subId == 5) {
                            params = {equal_sub_department_id: userLogged.subId};
                        }
                        addPersonilGrid.clearAndLoad(Overtime("getEmployees", params), disabledBookedPersonil);
                        
                        function disabledBookedPersonil() {
                            bookedPersonil.map(empId => addPersonilGrid.setRowColor(empId, "#f7ed74"));
                            countPerson = addPersonilGrid.getRowsNum();
                            console.log(countPerson);
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
                    let startWinIndex = times.filterTime.indexOf(ovtTime.start);
                    let endWinIndex = times.filterTime.indexOf(ovtTime.end);
                        
                    var workTime = genWorkTime(times.times, startWinIndex, endWinIndex);

                    var labelStartDetail = ovtTime.labelStart;
                    var labelEndDetail = ovtTime.labelEnd;
                    var hourDetailRevForm = hourDetailRevWin.attachForm([
                        {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Jam Lembur", list:[	
                            {type: "block", list: [
                                {type: "hidden", name: "id", label: "ID", labelWidth: 130, inputWidth: 250, value: formOvtDetailGridTnp.getSelectedRowId()},                               
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
                            {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Clear"}
                        ]},
                    ]);

                    let startDetailCombo = hourDetailRevForm.getCombo("start_date");
                    let endDetailCombo = hourDetailRevForm.getCombo("end_date");
                    let ovtDetailTime = getCurrentTime(formOvtDetailGridTnp, 10, 11);
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
                    });
                    break;
                case "task_revision":
                    if(!formOvtDetailGridTnp.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan di revisi!");
                    }
                    var taskDetailRevWin = createWindow("task_revision_detail_input_tnp", "Revisi Tugas Lembur", 510, 320);
                    myWins.window("task_revision_detail_input_tnp").skipMyCloseEvent = true;

                    var taskDetailRevForm = taskDetailRevWin.attachForm([
                        {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Jam Lembur", list:[	
                            {type: "block", list: [
                                {type: "hidden", name: "id", label: "ID", labelWidth: 130, inputWidth: 250, value: formOvtDetailGridTnp.getSelectedRowId()},                               
                                {type: "input", name: "empTask", label: "Task ID", labelWidth: 130, inputWidth: 250, readonly: true, value: formOvtDetailGridTnp.cells(formOvtDetailGridTnp.getSelectedRowId(), 1).getValue()},                               
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
                                            rProcPersonGrid(formOvtGridTnp.getSelectedRowId());
                                            sAlert(message);
                                            setEnable(["update", "cancel"], taskDetailRevForm, taskDetailRevWin);
                                            closeWindow("task_revision_detail_input_tnp");
                                            break;
                                        case "error":
                                            eaAlert("Kesalahan Waktu Lembur", message);
                                            setEnable(["update", "cancel"], taskDetailRevForm, taskDetailRevWin);
                                            break;
                                    }
                                });
                                break;
                            case "cancel":
                                closeWindow("task_revision_detail_input_tnp");
                                break;
                        }
                    });
                    break;
                case "change_req":
                    if(!formOvtDetailGridTnp.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan di revisi!");
                    }
                    var reqDetailRevWin = createWindow("req_revision_detail_input_tnp", "Revisi Kebutuhan Support", 700, 320);
                    myWins.window("req_revision_detail_input_tnp").skipMyCloseEvent = true;

                    var rWinToolbar = reqDetailRevWin.attachToolbar({
                        icon_path: "./public/codebase/icons/",
                        items: [
                            {id: "update", text: "Simpan", type: "button", img: "update.png"},
                        ]
                    })

                    var rWinMenu = reqDetailRevWin.attachMenu({
                        icon_path: "./public/codebase/icons/",
                        items: [
                            {id: "a", text: "Tahan CTRL untuk memilih mesin lebih dari 1"}
                        ]
                    })

                    reqDetailRevWin.progressOn();
                    var reqWinGrid = reqDetailRevWin.attachGrid();
                    reqWinGrid.setImagePath("./public/codebase/imgs/");
                    reqWinGrid.setHeader("No,Nama Pelayanan,Sub Bagian,Division ID");
                    reqWinGrid.setColSorting("int,str,str,str");
                    reqWinGrid.setColAlign("center,left,left,left");
                    reqWinGrid.setColTypes("rotxt,rotxt,rotxt,rotxt");
                    reqWinGrid.setInitWidthsP("5,45,50,0");
                    reqWinGrid.enableMultiselect(true);
                    reqWinGrid.enableSmartRendering(true);
                    reqWinGrid.attachEvent("onXLE", function() {
                        reqDetailRevWin.progressOff();
                    });
                    reqWinGrid.init();
                    reqWinGrid.clearAndLoad(Overtime("getOvertimeRequirement"));

                    rWinToolbar.attachEvent("onClick", function(id) {
                        switch (id) {
                            case "update":
                                let ids = reqWinGrid.getSelectedRowId();
                                reqJson(Overtime("updatePersonilRequest"), "POST", {ids, id: formOvtDetailGridTnp.getSelectedRowId()}, (err, res) => {
                                    if(res.status === "success") {
                                        rProcPersonGrid(formOvtGridTnp.getSelectedRowId());
                                        closeWindow("req_revision_detail_input_tnp");
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
        });

        var refProdLayout = inputTabs.cells("c").attachLayout({
            pattern: "1C",
            cells: [
                {id: "a", text: "Daftar Referensi Lembur"}
            ]
        });

        var refProdToolbar = refProdLayout.cells("a").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "del_ref", text: "Hapus Referensi", type: "button", img: "delete.png"},
                {id: "production_detail", text: "Detail Referensi Lembur", type: "button", img: "edit.png"},
                {id: "process_support", text: "Proses Support", type: "button", img: "undo.gif"},
                {id: "asign", text: "Assign To", type: "button", img: "update.png"},
            ]
        });

        let currentDateRef = filterForMonth(new Date());
        var refProdMenu =  refProdLayout.cells("a").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "search", text: "<div style='width:100%'>Search: <input type='text' id='tnp_ref_start_date' readonly value='"+currentDateRef.start+"' /> - <input type='text' id='tnp_ref_end_date' readonly value='"+currentDateRef.end+"' /> <button id='tnp_ref_process'>Proses</button>"}
            ]
        });

        var filterCalendar = new dhtmlXCalendarObject(["tnp_ref_start_date","tnp_ref_end_date"]);
        $("#tnp_ref_process").on("click", function() {
            if(checkFilterDate($("#tnp_ref_start_date").val(), $("#tnp_ref_end_date").val())) {
                rReqOvtGrid();
            }
        });

        let refStatusBar = refProdLayout.cells("a").attachStatusBar();
        function refGridCount() {
            var refGridRows = refProdGrid.getRowsNum();
            refStatusBar.setText("Total baris: " + refGridRows);
            for (let i = 0; i < refGridRows; i++) {
                if(refProdGrid.cells2(i, 3).getValue() != "-") {
                    refProdGrid.cells2(i, 1).setDisabled(true);
                }
            }
        }

        refProdLayout.cells("a").progressOn();
        var refProdGrid = refProdLayout.cells("a").attachGrid();
        refProdGrid.setImagePath("./public/codebase/imgs/");
        refProdGrid.setHeader("No,Check,Task ID Produksi,Task ID Suport,Sub Unit,Bagian,,Kebutuhan Orang,Status Hari,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Catatan,Makan,Steam,AHU,Compressor,PW,Jemputan,Dust Collector,WFI,Mekanik,Listrik,H&N,QC,QA,Penandaan,GBK,GBB,Created By,Created At");
        refProdGrid.attachHeader("#rspan,#rspan,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter")
        refProdGrid.setColSorting("int,na,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        refProdGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        refProdGrid.setColTypes("rotxt,ch,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        refProdGrid.setInitWidthsP("5,5,20,20,20,20,0,15,15,15,20,20,20,7,7,7,7,7,7,10,7,7,7,7,7,7,7,7,7,15,25");
        refProdGrid.enableSmartRendering(true);
        refProdGrid.attachEvent("onXLE", function() {
            refProdLayout.cells("a").progressOff();
        });
        refProdGrid.attachEvent("onRowSelect", function(rId, cIdn) {
            if(refProdGrid.cells(rId, 3).getValue() != "-") {
                refProdToolbar.disableItem("del_ref");
                refProdToolbar.disableItem("process_support");
                refProdToolbar.disableItem("asign");
            } else {
                refProdToolbar.enableItem("del_ref");
                refProdToolbar.enableItem("process_support");
                refProdToolbar.enableItem("asign");
            }
        });
        refProdGrid.init();
        
        function rReqOvtGrid() {
            refProdLayout.cells("a").progressOn();
            let start = $("#tnp_ref_start_date").val();
            let end = $("#tnp_ref_end_date").val();
            let params = {betweendate_created_at: start+","+end};
            if(userLogged.subId) {
                params.equal_sub_department_id = userLogged.subId;
            }
            refProdToolbar.enableItem("del_ref");
            refProdToolbar.enableItem("process_support");
            refProdGrid.clearAndLoad(Overtime("getRequestOvertimeGrid", params), refGridCount);
        }

        rReqOvtGrid();

        refProdToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    rReqOvtGrid();
                    break;
                case "production_detail":
                    if(!refProdGrid.getSelectedRowId()) {
                        return eAlert("Silahkan pilih lemburan!");
                    } else {
                        let tabName = "tnp_production_detail_" + refProdGrid.getSelectedRowId();
                        if(!inputTabs.tabs(tabName)) {
                            inputTabs.addTab(tabName, "Detail Lembur Produksi " + refProdGrid.cells(refProdGrid.getSelectedRowId(), 1).getValue(), null, null, true, true);
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
                        detailGrid.setHeader("No,Task ID,Nama Karyawan,Sub Unit,Bagian,,,,Pelayanan Produksi,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Status Hari,Jam Efektif,Jam Istirahat,Jam Ril,Jam Hit,Premi,Nominal Overtime,Makan,Tugas,Status Overtime,Status Terakhir,Spv Approval,Created By,Updated By,Created At,,");
                        detailGrid.attachHeader("#rspan,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
                        detailGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
                        detailGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
                        detailGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
                        detailGrid.setInitWidthsP("5,20,20,20,20,0,0,0,25,15,15,15,10,10,10,10,10,10,10,5,25,10,30,25,15,15,22,0,0");
                        detailGrid.attachFooter("Total,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,,<div id='tnp_total_ovt_prod_detail_"+refProdGrid.getSelectedRowId()+"'></div>,,,,,,,,,,");
                        detailGrid.enableSmartRendering(true);
                        detailGrid.attachEvent("onXLE", function() {
                            detailLayout.cells("b").progressOff();
                        });
                        detailGrid.init();
                        
                        function rDetailGrid(taskId) {
                            detailLayout.cells("b").progressOn();
                            detailGrid.clearAndLoad(Overtime("getOvertimeDetailGrid", {notin_status: "CANCELED,REJECTED,ADD", equal_task_id: taskId}), countDetailOvertime);
                        }

                        function countDetailOvertime() {
                            sumGridToElement(detailGrid, 18, "tnp_total_ovt_prod_detail_" + refProdGrid.getSelectedRowId());
                        }

                        reqJson(Overtime("getOvertimeDetailView"), "POST", {taskId: refProdGrid.getSelectedRowId()}, (err, res) => {
                            if(res.status === "success") {
                                detailLayout.cells("a").attachHTMLString(res.template);
                                rDetailGrid(refProdGrid.getSelectedRowId());
                            }
                        });
                    }
                    break;
                case "process_support":
                    let taskId = [];
                    for (let i = 0; i < refProdGrid.getRowsNum(); i++) {
                        let id = refProdGrid.getRowId(i);
                        if(refProdGrid.cells(id, 1).getValue() == 1) {
                            taskId.push(id);
                        }
                    }

                    if(taskId.length > 0) {
                        $("#tnp_init_btn").html("Tambah Lembur Support");
                        enableRequest();
                        initialForm.uncheckItem("jemputan");
                        initialForm.uncheckItem("ahu");
                        initialForm.uncheckItem("compressor");
                        initialForm.uncheckItem("pw");
                        initialForm.uncheckItem("steam");
                        initialForm.uncheckItem("wfi");
                        initialForm.uncheckItem("mechanic");
                        initialForm.uncheckItem("electric");
                        initialForm.uncheckItem("hnn");
                        initialForm.uncheckItem("qc");
                        initialForm.uncheckItem("qa");
                        initialForm.uncheckItem("penandaan");
                        initialForm.uncheckItem("gbb");
                        initialForm.uncheckItem("gbk");
                        $("#data_lembur").html("Data Lembur <b style='color:green'>(Support)</b>");
                        $("#kebutuhan_teknik").html("Pelayanan Teknik");
                        $("#kebutuhan_support").html("Pelayanan Support");
                        setTimeout(() => {
                            initialForm.showItem("taskIds");
                            initialForm.setItemValue("taskIds", taskId.join(","));
                            reqJson(Overtime("getOvtReqByTaskId"), "POST", {taskId}, (err, res) => {
                                if(res.status === "success") {
                                    if(res.reqs.length > 0) {
                                        res.reqs.map(id => {
                                            if(userLogged.subId == 5) {
                                                if(id == "ahu" || id == "compressor" || id == "pw" || id == "steam" ||
                                                id == "dust_collector" || id == "wfi" || id == "mechanic" || id == "electric" || id == "hnn") 
                                                {
                                                    initialForm.checkItem(id);
                                                }
                                            } else if(userLogged.subId == 7) {
                                                if(id == "qa") {
                                                    initialForm.checkItem(id);
                                                }
                                            } else if(userLogged.subId == 8) {
                                                if(id == "qc") {
                                                    initialForm.checkItem(id);
                                                }
                                            } else if(userLogged.subId == 13) {
                                                if(id == "penandaan" || id == "gbb" || id == "gbk") {
                                                    initialForm.checkItem(id);
                                                }
                                            }
                                        });
                                    }
                                }
                            });
                            inputTabs.tabs("a").setActive();
                        }, 200);
                    } else {
                        eAlert("Silahkan pilih referensi lembur terlebih dahulu!");
                    }
                    break;
                case "del_ref":
                    if(!refProdGrid.getSelectedRowId()) {
                        return eAlert("Silahkan pilih referensi lembur terlebih dahulu!");
                    }
                    dhtmlx.modalbox({
                        type: "alert-error",
                        title: "Konfirmasi Hapus Referensi",
                        text: "Anda yakin akan menghapus Referensi Lembur?",
                        buttons: ["Ya", "Tidak"],
                        callback: function (index) {
                            if (index == 0) {
                                reqJson(Overtime("deleteRef"), "POST", {taskId: refProdGrid.cells(refProdGrid.getSelectedRowId(), 2).getValue()}, (err, res) => {
                                    if(res.status === "success") {
                                        rReqOvtGrid();
                                        sAlert(res.message);
                                    } else {
                                        eAlert(res.message);
                                    }
                                });
                            }
                        },
                    });
                    break;
                case "asign":
                    if(!refProdGrid.getSelectedRowId()) {
                        return eAlert("Silahkan pilih referensi lembur terlebih dahulu!");
                    }
                    
                    var ovtTaskWin = createWindow("ovt_task_win", "Daftar Lembur Support", 900, 400);
                    myWins.window("ovt_task_win").skipMyCloseEvent = true;

                    var ovtTaskToolbar = ovtTaskWin.attachToolbar({
                        icon_path: "./public/codebase/icons/",
                        items: [
                            {id: "asign_to", text: "Simpan", type: "button", img: "ok.png"},
                        ]
                    });

                    var ovtTaskGrid = ovtTaskWin.attachGrid();
                    ovtTaskGrid.setImagePath("./public/codebase/imgs/");
                    ovtTaskGrid.setImagePath("./public/codebase/imgs/");
                    ovtTaskGrid.setHeader("No,Task ID,Sub Unit,Bagian,,Kebutuhan Orang,Status Hari,Tanggal Overtime,Waktu Mulai, Waktu Selesai,Catatan,Makan,Steam,AHU,Compressor,PW,Jemputan,Dust Collector,WFI,Mekanik,Listrik,H&N,QC,QA,Penandaan,GBK,GBB,Status Overtime, Revisi Jam Lembur,Revisi User Approval,Rejection User Approval,Created By,Updated By,Created At,");
                    ovtTaskGrid.attachHeader("#rspan,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter");
                    ovtTaskGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
                    ovtTaskGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
                    ovtTaskGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
                    ovtTaskGrid.setInitWidthsP("5,20,20,20,0,15,15,15,20,20,20,7,7,7,7,7,7,10,7,7,7,7,7,7,7,7,7,10,30,30,30,15,15,25,0");
                    ovtTaskGrid.enableSmartRendering(true);
                    ovtTaskGrid.attachEvent("onXLE", function() {
                        ovtTaskWin.progressOff();
                    });
                    ovtTaskGrid.init();

                    function rOvtTaskGrid() {
                        ovtTaskWin.progressOn();
                        let params = {in_status: "CREATED,PROCESS"};
                        if(userLogged.rankId >= 3 || userLogged.pltRankId >= 3) {
                            if(userLogged.rankId >= 6 || userLogged.pltRankId >= 6) {
                                params.in_sub_department_id = userLogged.subId+","+userLogged.pltSubId;
                            } else {
                                params.equal_created_by = userLogged.empId;;
                            }
                        } else if(userLogged.rankId == 2 || userLogged.pltRankId == 2) {
                            params.in_department_id = userLogged.deptId+","+userLogged.pltDeptId;
                        }
                        ovtTaskGrid.clearAndLoad(Overtime("getOvertimeGrid", params));
                    }

                    rOvtTaskGrid();

                    ovtTaskToolbar.attachEvent("onClick", function(id) {
                        switch (id) {
                            case "asign_to":
                                if(!ovtTaskGrid.getSelectedRowId()) {
                                    return eAlert("Belum ada lemburan yang di pilih!");
                                }

                                reqJson(Overtime("asignToOvertime"), "POST", {
                                    taskId: refProdGrid.getSelectedRowId(), 
                                    taskIdSupport: ovtTaskGrid.cells(ovtTaskGrid.getSelectedRowId(), 1).getValue()
                                }, (err, res) => {
                                    if(res.status === "success") {
                                        sAlert(res.message);
                                        rProcGrid();
                                        rReqOvtGrid();
                                        closeWindow("ovt_task_win");
                                    } else {
                                        eAlert(res.message);
                                    }
                                });
                                break;
                        }
                    })
                    break;
            }
        })

        initialForm.hideItem("taskIds");

        function disableRequest() {
            let subId = userLogged.subId;
            if(subId == 5) {
                reqTeknik("disable");
                
                reqWhs("enable");
                initialForm.enableItem("qa");
                initialForm.enableItem("qc");
            } else if(subId == 7) {
                initialForm.disableItem("qa");

                reqTeknik("enable");
                reqWhs("enable");
                initialForm.enableItem("qc");
            } else if(subId == 8) {
                initialForm.disableItem("qc");

                reqTeknik("enable");
                reqWhs("enable");
                initialForm.enableItem("qa");
            } else if(subId == 13) {
                reqWhs("disable");

                reqTeknik("enable");
                initialForm.enableItem("qc");
                initialForm.enableItem("qa");
            }
        } 

        function enableRequest() {
            let subId = userLogged.subId;
            if(subId == 5) {
                reqTeknik("enable");

                initialForm.disableItem("qa");
                initialForm.disableItem("qc");
                initialForm.disableItem("penandaan");
                initialForm.disableItem("gbb");
                initialForm.disableItem("gbk");
            } else if(subId == 7) {
                initialForm.enableItem("qa");

                reqTeknik("disable");
                reqWhs("disable");
                initialForm.disableItem("qc");
            } else if(subId == 8) {
                initialForm.enableItem("qc");

                reqTeknik("disable");
                reqWhs("disable");
                initialForm.disableItem("qa");
            } else if(subId == 13) {
                reqWhs("enable");

                reqTeknik("disable");
                initialForm.disableItem("qc");
                initialForm.disableItem("qa");
            }
        }

        function reqTeknik(type) {
            if(type == "disable") {
                initialForm.disableItem("ahu");
                initialForm.disableItem("compressor");
                initialForm.disableItem("pw");
                initialForm.disableItem("steam");
                initialForm.disableItem("dust_collector");
                initialForm.disableItem("wfi");
                initialForm.disableItem("mechanic");
                initialForm.disableItem("electric");
                initialForm.disableItem("hnn");
            } else {
                initialForm.enableItem("ahu");
                initialForm.enableItem("compressor");
                initialForm.enableItem("pw");
                initialForm.enableItem("steam");
                initialForm.enableItem("dust_collector");
                initialForm.enableItem("wfi");
                initialForm.enableItem("mechanic");
                initialForm.enableItem("electric");
                initialForm.enableItem("hnn");
            }
        }

        function reqWhs(type) {
            if(type == "disable") {
                initialForm.disableItem("penandaan");
                initialForm.disableItem("gbb");
                initialForm.disableItem("gbk");
            } else {
                initialForm.enableItem("penandaan");
                initialForm.enableItem("gbb");
                initialForm.enableItem("gbk");
            }
        }

        disableRequest();
    }
JS;

header('Content-Type: application/javascript');
echo $script;