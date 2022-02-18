<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

	function showEmployee() {	
        var addEmpForm;
        var editEmpForm;

        var empGrid;
        var familyGrid;
        var rankGrid;
        var eduGrid;
        var trainingGrid;
        var nipSpv;

        var comboUrl = {
            department_id: {
                url: Emp("getDepartment"),
                reload: true
            },
            sub_department_id: {
                reload: false
            },
            division_id: {
                reload: false
            },
            rank_id: {
                url: Emp("getRank"),
                reload: true
            },
            location_id: {
                url: Emp("getLocation"),
                reload: true
            },
            training_id: {
                url: Emp("getTraining"),
                reload: true
            }
        }

        var empTabs = mainTab.cells("employee").attachTabbar({
            tabs: [
                {id: "data", text: "Data Karyawan", active: true}
            ]
        });

        empTabs.setArrowsMode("auto");
        empTabs.enableAutoReSize(true);

        var empToolbarItem = [
            {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
            {id: "add", text: "Tambah", type: "button", img: "add.png"},
            {id: "edit", text: "Ubah", type: "button", img: "edit.png", img_disabled: "edit_disabled.png"}
        ];

        if(userLogged.role === "admin" || userLogged.subId == 11) {
			empToolbarItem.push({id: "inactive", text: "Non Aktif", type: "button", img: "block.png"});
			empToolbarItem.push({id: "active", text: "Aktifkan", type: "button", img: "check.png"});
			empToolbarItem.push({id: "export", text: "Export To Excel", type: "button", img: "excel.png"});
			empToolbarItem.push({id: "searchtext", text: "Cari : ", type: "text"});
			empToolbarItem.push({id: "search", text: "", type: "buttonInput", width: 150});
		} else {
            empToolbarItem.push({id: "export", text: "Export To Excel", type: "button", img: "excel.png"});
			empToolbarItem.push({id: "searchtext", text: "Cari : ", type: "text"});
			empToolbarItem.push({id: "search", text: "", type: "buttonInput", width: 150});
		}

        var empToolbar = empTabs.cells("data").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: empToolbarItem
        });

        empToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    empToolbar.setValue("search","");
                    loadDataEmp();
                    break;
                case "add":
                    addEmpHandler();
                    break;
                case "inactive":
                    statusEmpHandler("INACTIVE");
                    break;
                case "active":
                    statusEmpHandler("ACTIVE");
                    break;
                case "export":
                    empGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    sAlert("Export Data Dimulai");
                    break;
                case "delete":
                    deleteEmpHandler();
                    break;
                case "edit":
                    editEmpHandler();
                    break;
            }
        });

        empToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    loadDataEmp();
                    empGrid.attachEvent("onGridReconstructed", empGridCount);
                    break;
            }
        });

        var empStatusBar = empTabs.cells("data").attachStatusBar();
        function empGridCount() {
            let empGridRows = empGrid.getRowsNum();
            empStatusBar.setText("Total baris: " + empGridRows);
        }

        function loadDataEmp() {
            isLogin();
            empTabs.cells("data").progressOn();
            empGrid = empTabs.cells("data").attachGrid();
            empGrid.setHeader("No,Nama Karyawan,NPP,ID SAP,No. KK,NIK,NPWP,Tempat Lahir,Tanggal Lahir,Jenis Kelamin,Agama,Umur,Status Karyawan,OS,Alamat,No.Telp,No.Hp,Email,No. SK Jabatan,Tanggal SK Jabatan,Tanggal Efektif SK,Tanggal Berakhir SK,Atasan Langsung,Sub Unit,Bagian,Sub Bagian,Jabatan,Status");
            empGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter")
            empGrid.setColSorting("str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
            empGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
            empGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
            empGrid.setInitWidthsP("5,20,15,15,15,15,15,15,20,15,15,10,15,20,35,15,15,20,15,15,15,15,15,15,20,30,15,15");
            empGrid.enableSmartRendering(true);
            empGrid.enableMultiselect(true);
            empGrid.attachEvent("onXLE", function() {
                empTabs.cells("data").progressOff();
            });
            empGrid.init();
            empGrid.clearAndLoad(Emp("empGrid", {search: empToolbar.getValue("search")}), empGridCount);
        }

        loadDataEmp();

        empDataFormLeft = [
            {type: "hidden", name: "id", label: "ID", labelWidth: 130, inputWidth: 250},
            {type: "input", name: "nip", label: "NPP", labelWidth: 130, inputWidth: 250, required: true},
            {type: "input", name: "sap_id", label: "ID SAP", labelWidth: 130, inputWidth: 250, required: true},
            {type: "input", name: "parent_nik", label: "No Kartu Keluarga", labelWidth: 130, inputWidth: 250, validate:"ValidNumeric"},
            {type: "input", name: "nik", label: "No. KTP", labelWidth: 130, inputWidth: 250, required: true},
            {type: "input", name: "npwp", label: "NPWP", labelWidth: 130, inputWidth: 250},
            {type: "input", name: "employee_name", label: "Nama Karyawan", labelWidth: 130, inputWidth: 250, required: true},	
            {type: "input", name: "birth_place", label: "Tempat Lahir", labelWidth: 130, inputWidth: 250, required: true},
            {type: "calendar", name: "birth_date", label: "Tanggal Lahir", labelWidth: 130, inputWidth: 250, required: true}
        ];

        empDataFormRight =  [
            {type: "combo", name: "gender", label: "Jenis Kelamin", readonly: true, labelWidth: 130, inputWidth: 250, required: true, 
                validate: "NotEmpty",
                comboType: "image", 
                comboImagePath: "./public/codebase/icons/", 
                options:[
                    {value: "", text: ""},
                    {value: "Laki-Laki", text: "Laki-Laki", img: "male.png"},
                    {value: "Perempuan", text: "Perempuan", img: "female.png"}
                ]
            },
            {type: "combo", name: "religion", label: "Agama", readonly: true, labelWidth: 130, inputWidth: 250, required: true,
                validate: "NotEmpty",
                comboType: "image", 
                comboImagePath: "./public/codebase/icons/", 
                options:[
                    {value: "", text: ""},
                    {value: "Islam", text: "Islam", img: "religion-moslem.png"},
                    {value: "Kristen", text: "Kristen", img: "religion-christian.png"},
                    {value: "Katholik", text: "Katholik", img: "religion-chatolic.png"},
                    {value: "Budha", text: "Budha", img: "religion-buddhism.png"},
                    {value: "Hindu", text: "Hindu", img: "religion-hinduism.png"},
                    {value: "Konghucu", text: "Konghucu", img: "religion-taoism.png"},
                    {value: "Penganut Kepercayaan", text: "Penganut Kepercayaan", img: "religion-others.png"},
                ]
            },
            {type: "input", name: "age", label: "Usia", labelWidth: 130, inputWidth: 250, required: true, validate:"ValidNumeric"},
            {type: "input", name: "address", label: "Alamat", labelWidth: 130, inputWidth: 250, rows: 3, required: true},
            {type: "input", name: "mobile", label: "No. Hp", labelWidth: 130, inputWidth: 250, required: true, validate:"ValidNumeric"},
            {type: "input", name: "phone", label: "No. Telpon", labelWidth: 130, inputWidth: 250, validate:"ValidNumeric"},
            {type: "input", name: "email", label: "Email", labelWidth: 130, inputWidth: 250, required: true, validate:"ValidEmail"},
        ];

        empRankFormLeft = [
            {type: "combo", name: "employee_status", label: "Status Karyawan", readonly: true, required: true, labelWidth: 130, inputWidth: 250,
                validate: "NotEmpty", 
                options:[
                    {value: "", text: ""},
                    {value: "PKWT", text: "PKWT"},
                    {value: "Kontrak OS", text: "Kontrak OS"},
                    {value: "Permanen", text: "Permanen"},
                ]
            },
            {type: "combo", name: "os_name", label: "OS", readonly: true, required: true, labelWidth: 130, inputWidth: 250,
                validate: "NotEmpty", 
                options:[
                    {value: "", text: ""},
                    {value: "-", text: "-"},
                    {value: "PT. BANGUN SINAR INDONESIA", text: "PT. BANGUN SINAR INDONESIA"},
                    {value: "PT. INDOPSIKO INDONESIA", text: "PT. INDOPSIKO INDONESIA"},
                    {value: "PT. KREASIBOGA PRIMATAMA", text: "PT. KREASIBOGA PRIMATAMA"},
                    {value: "PT. SINERGI INTEGRA SERVICES", text: "PT. SINERGI INTEGRA SERVICES"},
                    {value: "PT. ISS INDONESIA", text: "PT. ISS INDONESIA"},
                    {value: "PT. SINAR PRAPANCA", text: "PT. SINAR PRAPANCA"},
                ]
            },
            {type: "combo", name: "rank_id", label: "Jabatan", readonly: true, required: true, labelWidth: 130, inputWidth: 250},								
            {type: "combo", name: "department_id", label: "Sub Unit", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
            {type: "combo", name: "sub_department_id", label: "Bagian", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
            {type: "combo", name: "division_id", label: "Sub Bagian", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
            {type: "combo", name: "location_id", label: "Unit Kerja", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
        ];

        empRankFormRight = [
            {type: "input", name: "sk_number", label: "Nomor SK", labelWidth: 130, inputWidth: 250},
            {type: "calendar", name: "sk_date", label: "Tanggal SK", labelWidth: 130, inputWidth: 250},
            {type: "calendar", name: "sk_start_date", label: "Tanggal Mulai", labelWidth: 130, inputWidth: 250},
            {type: "calendar", name: "sk_end_date", label: "Tanggal Berakhir", labelWidth: 130, inputWidth: 250},
            {type: "input", name: "direct_spv", label: "NIP Atasan Langsung", labelWidth: 130, inputWidth: 250, readonly: true},
            {type: "combo", name: "overtime", label: "Jam Lembur", readonly: true, required: true, labelWidth: 130, inputWidth: 250,
                validate: "NotEmpty", 
                options:[
                    {value: "", text: ""},
                    {value: "0", text: "Jam Berjalan"},
                    {value: "1", text: "Jam Tetap"},
                ]
            },
        ];

        function addEmpHandler() {
            if (empTabs.tabs("edit")) {
                eAlert("Selesaikan terlebih dahulu proses 'Ubah Karyawan'!");
            } else {
                if (!empTabs.tabs("add")){
                    empTabs.addTab("add", "Tambah Karyawan", null, null, true, true);
                    addEmpForm = empTabs.tabs("add").attachForm([
                        {type: "fieldset", offsetLeft: 30, label: "Data Karyawan", list:[	
                            {type: "block", list: empDataFormLeft},	
                            {type: "newcolumn"},
                            {type: "block", list: empDataFormRight}
                        ]},
                        {type: "fieldset", offsetLeft: 30, label: "Data Jabatan", list:[
                            {type: "block", list: empRankFormLeft},
                            {type: "newcolumn"},
                            {type: "block", list: empRankFormRight}
                        ]},
                        {type: "block", offsetLeft: 30, offsetTop: 10, list: [
                            {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                            {type: "newcolumn"},
                            {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                            {type: "newcolumn"},
                            {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                        ]},
                        {type: "block", offsetLeft: 30, offsetTop: 10, list: []},
                    ]);

                    addEmpForm.attachEvent("onFocus", function(name, value) {
                        if(name === "direct_spv") {
                            loadSPV(addEmpForm);
                        }
                    });

                    var addDeptCombo = addEmpForm.getCombo("department_id");
                    var addSubDeptCombo = addEmpForm.getCombo("sub_department_id");
                    var addDivCombo = addEmpForm.getCombo("division_id");
                    var addRankCombo = addEmpForm.getCombo("rank_id");
                    var addLocationCombo = addEmpForm.getCombo("location_id");
                    addLocationCombo.load(AppMaster("getLocation"));

                    addDeptCombo.load(Emp("getDepartment"));
                    addDeptCombo.attachEvent("onChange", function(value, text){
                        if(addEmpForm.getItemValue("rank_id") > 2 || addEmpForm.getItemValue("rank_id") == "") {
                            clearComboReload(addEmpForm, "sub_department_id", Emp("getSubDepartment", {deptId: value}));
                        }
                    });
                    addSubDeptCombo.attachEvent("onChange", function(value, text){
                        if(addEmpForm.getItemValue("rank_id") > 4 || addEmpForm.getItemValue("rank_id") == "") {
                            clearComboReload(addEmpForm, "division_id", Emp("getDivision", {subDeptId: value}));
                        }
                    });

                    addRankCombo.load(Emp("getRank"));
                    addRankCombo.attachEvent("onChange", function(value, text){
                       if(value <= 2) {
                            addDivCombo.clearAll();
                            addDivCombo.addOption([[0, "-"]]);
                            addDivCombo.selectOption(0);
                            addSubDeptCombo.clearAll();
                            addSubDeptCombo.addOption([[0, "-"]]);
                            addSubDeptCombo.selectOption(0);
                       } else if(value <= 4) {
                            clearComboReload(addEmpForm, "sub_department_id", Emp("getSubDepartment", {deptId: addEmpForm.getCombo("department_id")}));
                            addDivCombo.clearAll();
                            addDivCombo.addOption([[0, "-"]]);
                            addDivCombo.selectOption(0);
                       } else {
                            addSubDeptCombo.load(Emp("getSubDepartment", {deptId: addEmpForm.getItemValue("department_id"), select: addEmpForm.getItemValue("sub_department_id")}));
                            addDivCombo.load(Emp("getDivision", {subDeptId: addEmpForm.getItemValue("sub_department_id"), select: addEmpForm.getItemValue("division_id")}));
                       }
                    });
                    isFormNumeric(addEmpForm, ["age", "phone", "mobile"]);

                    addEmpForm.attachEvent("onButtonClick", function (name) {
                        switch (name) {
                            case "add":
                                isLogin();
                                if (!addEmpForm.validate()) {
                                    return eAlert("Input error!");
                                }

                                setDisable(["add", "clear", "cancel"], addEmpForm, empTabs.tabs("add"));
                                let addEmpFormDP = new dataProcessor(Emp("empForm"));
                                addEmpFormDP.init(addEmpForm);
                                addEmpForm.save();

                                addEmpFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                                    let message = tag.getAttribute("message");
                                    switch (action) {
                                        case "inserted":
                                            sAlert("Berhasil Menambahkan Record <br>" + message);
                                            clearAllForm(addEmpForm, comboUrl);
                                            setEnable(["add", "clear", "cancel"], addEmpForm, empTabs.tabs("add"));
                                            loadDataEmp();
                                            nipSpv = null;
                                            break;
                                        case "error":
                                            eAlert("Gagal Menambahkan Record <br>" + message);
                                            setEnable(["add", "clear", "cancel"], addEmpForm, empTabs.tabs("add"));
                                            break;
                                    }
                                });
                                break;
                            case "clear":
                                clearAllForm(addEmpForm, comboUrl);
                                break;
                            case "cancel":
                                loadDataEmp();
                                empTabs.tabs("add").close();
                                break;
                        }
                    });
                } else {
                    empTabs.tabs("add").setActive();
                }
            }
        }

        function loadSPV(form) {          
            var spvWindow = createWindow("direct_spv", "Karyawan", 900, 400);
            myWins.window("direct_spv").skipMyCloseEvent = true;

            var spvToolbar = spvWindow.attachToolbar({
                icon_path: "./public/codebase/icons/",
                items: [
                    {id: "save", text: "Simpan", type: "button", img: "ok.png"}
                ]
            });

            spvToolbar.attachEvent("onClick", function(id) {
                switch (id) {
                    case "save":
                        form.setItemValue('direct_spv', nipSpv);
                        let name = spvGrid.cells(nipSpv, 3).getValue();
                        form.setItemValue('direct_spv_name', name);
                        closeWindow("direct_spv");
                        break;
                }
            });

            let spvStatusBar = spvWindow.attachStatusBar();
            function spvGridCount() {
                var spvGridRows = spvGrid.getRowsNum();
                spvStatusBar.setText("Total baris: " + spvGridRows);
                if(nipSpv) {
                    spvGrid.cells(nipSpv, 1).setValue(1);
                }
            }

            spvWindow.progressOn();
            spvGrid = spvWindow.attachGrid();
            spvGrid.setImagePath("./public/codebase/imgs/");
            spvGrid.setHeader("No,Check,NIP,Nama Karyawan,Bagian,Jabatan");
            spvGrid.attachHeader("#rspan,#rspan,#text_filter,#select_filter,#select_filter,#text_filter")
            spvGrid.setColSorting("int,na,str,str,str,str");
            spvGrid.setColAlign("center,left,left,left,left,left");
            spvGrid.setColTypes("rotxt,ch,rotxt,rotxt,rotxt,rotxt");
            spvGrid.setInitWidthsP("5,5,20,25,20,25");
            spvGrid.enableMultiselect(true);
            spvGrid.enableSmartRendering(true);
            spvGrid.attachEvent("onXLE", function() {
                spvWindow.progressOff();
            });
            spvGrid.init();
            spvGrid.attachEvent("onCheckbox", function(rId, cIdn, state) {
                if(state) {
                    if(!nipSpv) {
                        nipSpv = rId;
                    } else {
                        eAlert("Hanya bisa memilih 1 atasan!");
                        spvGrid.cells(rId, 1).setValue(0);
                    }                          
                } else {
                    nipSpv = null;
                }
            });
            if(form.getItemValue("nik")) {
                spvGrid.clearAndLoad(Emp("getEmployees", {notequal_nik: form.getItemValue("nik"), equal_status: "ACTIVE"}), spvGridCount);
            } else {
                spvGrid.clearAndLoad(Emp("getEmployees", {equal_status: "ACTIVE"}), spvGridCount);
            }
        }

        function editEmpHandler() {
            if (empTabs.tabs("add")) {
                eAlert("Selesaikan terlebih dahulu proses 'Tambah Karyawan'!");
            } else {
                if (empGrid.getSelectedRowId()) {
                    if (!empTabs.tabs("edit")) {
                        empTabs.addTab("edit", "Ubah Karyawan", null, null, true, true);
                        empTabs.cells("data").progressOn();
                        var editEmpToolbar = empTabs.tabs("edit").attachToolbar({
                            icon_path: "./public/codebase/icons/",
                            items: [
                                {id: "personal", text: "Pribadi", type: "button", img: "user.png"},
                                {id: "family", text: "Keluarga", type: "button", img: "family.png"},
                                {id: "history", text: "Riwayat", type: "buttonSelect", img: "history.png", options: [
                                    {id: "rank", text: "Jabatan", type: "obj", img: "medal.png"}
                                ]},
                                {id: "edu", text: "Pendidikan", type: "buttonSelect", img: "toga.png", options: [
                                    {id: "education", text: "Pendidikan Formal", type: "obj", img: "toga.png"},
                                    {id: "training", text: "Pelatihan", type: "obj", img: "certificate.png"}
                                ]}     
                            ]
                        });

                        editEmpToolbar.attachEvent("onClick", function(id) {
                            switch (id) {
                                case "personal":
                                    personalView();
                                    break;
                                case "family":
                                    familyView();
                                    break;
                                case "rank":
                                    rankView();
                                    break;
                                case "education" :
                                    eduView();
                                    break;
                                case "training":
                                    trainingView();
                                    break;
                            }
                        });

                        var editEmpLayout = empTabs.tabs("edit").attachLayout({
                            pattern: "1C",
                            cells: [
                                {id: "a", text: "Data Karyawan"}
                            ]
                        });

                        function personalView() {
                            editEmpLayout.cells("a").showView("personal");
                            var personalLayout = editEmpLayout.cells("a").attachLayout({
                                pattern: "1C",
                                cells: [
                                    {id: "a", text: "Data Karyawan"}
                                ]
                            })
                            
                            editEmpForm = personalLayout.cells("a").attachForm([
                                {type: "fieldset", offsetLeft: 30, label: "Data Karyawan", list:[	
                                    {type: "block", list: empDataFormLeft},	
                                    {type: "newcolumn"},
                                    {type: "block", list:empDataFormRight}
                                ]},
                                {type: "fieldset", offsetLeft: 30, label: "Data Jabatan", list:[
                                    {type: "block", list: empRankFormLeft},
                                    {type: "newcolumn"},
                                    {type: "block", list: empRankFormRight}
                                ]},
                                {type: "block", offsetLeft: 30, offsetTop: 10, list: [
                                    {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                                    {type: "newcolumn"},
                                    {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                                ]},
                                {type: "block", offsetLeft: 30, offsetTop: 10, list: []},
                            ]);

                            isFormNumeric(editEmpForm, ["age", "phone", "mobile"]);

                            var editDeptCombo = editEmpForm.getCombo("department_id");
                            var editSubDeptCombo = editEmpForm.getCombo("sub_department_id");
                            var editDivCombo = editEmpForm.getCombo("division_id");
                            var editRankCombo = editEmpForm.getCombo("rank_id");
                            var editLocationCombo = editEmpForm.getCombo("location_id");
                            fetchFormData(Emp("empForm", {id: empGrid.getSelectedRowId()}), editEmpForm, null, null, setCombo);

                            function setCombo() {
                                editDeptCombo.load(Emp("getDepartment", {select: editEmpForm.getItemValue("department_id")}));
                                editSubDeptCombo.load(Emp("getSubDepartment", {deptId: editEmpForm.getItemValue("department_id"), select: editEmpForm.getItemValue("sub_department_id")}));
                                
                                editDivCombo.load(Emp("getDivision", {subDeptId: editEmpForm.getItemValue("sub_department_id"), select: editEmpForm.getItemValue("division_id")}));
                                editLocationCombo.load(AppMaster("getLocation", {select: editEmpForm.getItemValue("location_id")}));
                                
                                editRankCombo.load(Emp("getRank", {select: editEmpForm.getItemValue("rank_id")}));

                                editDeptCombo.attachEvent("onChange", function(value, text){
                                    clearComboReload(editEmpForm, "sub_department_id", Emp("getSubDepartment", {deptId: value}));
                                });

                                editSubDeptCombo.attachEvent("onChange", function(value, text){
                                    clearComboReload(editEmpForm, "division_id", Emp("getDivision", {subDeptId: value}));
                                });

                                editRankCombo.attachEvent("onChange", function(value, text){
                                    if(value <= 2) {
                                            editDivCombo.clearAll();
                                            editDivCombo.addOption([[0, "-"]]);
                                            editDivCombo.selectOption(0);
                                            editSubDeptCombo.clearAll();
                                            editSubDeptCombo.addOption([[0, "-"]]);
                                            editSubDeptCombo.selectOption(0);
                                    } else if(value <= 4) {
                                            editDivCombo.clearAll();
                                            editDivCombo.addOption([[0, "-"]]);
                                            editDivCombo.selectOption(0);
                                    } else {
                                        editSubDeptCombo.load(Emp("getSubDepartment", {deptId: editEmpForm.getItemValue("department_id"), select: editEmpForm.getItemValue("sub_department_id")}));
                                        editDivCombo.load(Emp("getDivision", {subDeptId: editEmpForm.getItemValue("sub_department_id"), select: editEmpForm.getItemValue("division_id")}));
                                    }
                                });

                                nipSpv = editEmpForm.getItemValue("direct_spv");
                                editEmpForm.attachEvent("onFocus", function(name, value) {
                                    if(name === "direct_spv") {
                                        loadSPV(editEmpForm);
                                    }
                                });
                            }

                            editEmpForm.attachEvent("onButtonClick", function(name) {
                                switch (name) {
                                    case "update":
                                        isLogin();
                                        if (!editEmpForm.validate()) {
                                            return eAlert("Input error!");
                                        }

                                        setDisable(["update", "cancel"], editEmpForm, personalLayout.cells("a"));
                                        let editEmpFormDP = new dataProcessor(Emp("empForm"));
                                        editEmpFormDP.init(editEmpForm);
                                        editEmpForm.save();

                                        editEmpFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                                            let message = tag.getAttribute("message");
                                            switch (action) {
                                                case "updated":
                                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                                    setEnable(["update","cancel"], editEmpForm, personalLayout.cells("a"));
                                                    loadDataEmp();
                                                    empTabs.tabs("edit").skipMyCloseEvent = true;
                                                    empTabs.tabs("edit").close();
                                                    break;
                                                case "error":
                                                    eAlert("Gagal Mengubah Record <br>" + message);
                                                    setEnable(["update","cancel"], editEmpForm, personalLayout.cells("a"));
                                                    break;
                                            }
                                        });
                                        break;
                                    case "cancel":
                                        loadDataEmp();
                                        empTabs.tabs("edit").close();
                                        break;
                                }
                            });
                        }

                        personalView();

                        function familyView() {
                            editEmpLayout.cells("a").showView("family");
                            var familyLayout = editEmpLayout.cells("a").attachLayout({
                                pattern: "1C",
                                cells: [
                                    {id: "a", text: "Data Keluarga - " + editEmpForm.getItemValue("employee_name")}
                                ]
                            });

                            var familyToolbar = familyLayout.cells("a").attachToolbar({
                                icons_path: "./public/codebase/icons/",
                                items: [
                                    {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                                    {id: "add", text: "Tambah", type: "button", img: "add.png"},
                                    {id: "delete", text: "Hapus", type: "button", img: "delete.png"},
                                    {id: "edit", text: "Ubah", type: "button", img: "edit.png"},
                                    {id: "export", text: "Export Data", type: "button", img: "table.png"}
                                ]											
                            });

                            function loadDataFamily() {
                                isLogin();
                                familyLayout.cells("a").progressOn();
                                familyGrid = familyLayout.cells("a").attachGrid();
                                familyGrid.setHeader("No,Nama,Tanggal Lahir,Hubungan,Status Kawin,Tanggal Nikah,Pekerjaan");
                                familyGrid.setColSorting("int,str,str,str,str,str,str");
                                familyGrid.setColAlign("center,left,left,left,left,left,left");
                                familyGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
                                familyGrid.setInitWidthsP("5,25,15,10,15,15,15");
                                familyGrid.enableMultiselect(true);
                                familyGrid.enableSmartRendering(true);
                                familyGrid.attachEvent("onXLE", function() {
                                    familyLayout.cells("a").progressOff();
                                });
                                familyGrid.init();
                                familyGrid.clearAndLoad(Emp("familyGrid", {empId: editEmpForm.getItemValue("id")}));
                            }

                            loadDataFamily();

                            var familyForm = [
                                {type: "hidden", name: "id", label: "ID", inputWidth: 220, labelWidth: 120},
                                {type: "hidden", name: "emp_id", label: "Employee ID", inputWidth: 220, labelWidth: 120, value: editEmpForm.getItemValue("id")},
                                {type: "input", name: "family_name", label: "Nama", inputWidth: 220, labelWidth: 120, required: true},																
                                {type: "combo", name: "relation", label: "Hubungan", readonly: true, required: true, labelWidth: 120, inputWidth: 220, 
                                    validate: "NotEmpty",
                                    options:[
                                        {value: "", text: ""},
                                        {value: "Suami", text: "Suami"},
                                        {value: "Istri", text: "Istri"},
                                        {value: "Putra", text: "Putra"},
                                        {value: "Putri", text: "Putri"},
                                        {value: "Ayah", text: "Ayah"},
                                        {value: "Ibu", text: "Ibu"}
                                    ]
                                },
                                {type: "calendar", name: "birth_date", label: "Tanggal Lahir", required: true, inputWidth: 220, labelWidth: 120},
                                {type: "combo", name: "martial_status", label: "Status Kawin", readonly: true, required: true, labelWidth: 120, inputWidth: 220,
                                    validate: "NotEmpty",
                                    options:[
                                        {value: "", text: ""},
                                        {value: "Belum Kawin", text: "Belum Kawin"},
                                        {value: "Kawin", text: "Kawin"},
                                        {value: "Cerai Hidup", text: "Cerai Hidup"},
                                        {value: "Cerai Mati", text: "Cerai Mati"}
                                    ]
                                },
                                {type: "calendar", name: "wedding_date",label: "Tanggal Nikah", inputWidth: 220, labelWidth: 120},
                                {type: "input", name: "description",label: "Uraian", inputWidth: 220, labelWidth: 120, rows: 2},																	
                                {type: "calendar", name: "divorce_date", label: "Tanggal Cerai/Meninggal", inputWidth: 220, labelWidth: 120},
                                {type: "input", name: "profession", label: "Pekerjaan", inputWidth: 220, labelWidth: 120}														
                            ];

                            familyToolbar.attachEvent("onClick", function(id) {
                                switch (id) {
                                    case "refresh":
                                        loadDataFamily();
                                        break;
                                    case "add":
                                        editEmpLayout.cells("a").showView("add_family");
                                        var addFamilyForm = editEmpLayout.cells("a").attachForm([
                                            {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Tambah Data Keluarga - " + editEmpForm.getItemValue("employee_name"), list: [
                                                {type: "block", list: familyForm},
                                                {type: "block", offsetLeft: 10, offsetTop: 30, list:[
                                                    {type: "button", name: "add", value: "Tambah", className: "button_add"},
                                                    {type: "newcolumn"},
                                                    {type: "button", name: "clear", value: "Clear", className: "button_clear"},
                                                    {type: "newcolumn"},
                                                    {type: "button", name: "cancel", value: "Cancel", className: "button_no"}
                                                ]}
                                            ]}
                                        ]);

                                        addFamilyForm.attachEvent("onButtonClick", function(name) {
                                            switch (name) {
                                                case "add":
                                                    isLogin();
                                                    if (addFamilyForm.validate() == true) {
                                                        setDisable(["add", "clear", "cancel"], addFamilyForm, editEmpLayout.cells("a"));

                                                        let addFamilyFormDP = new dataProcessor(Emp("familyForm"));
                                                        addFamilyFormDP.init(addFamilyForm);
                                                        addFamilyForm.save();

                                                        addFamilyFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
                                                            let message = tag.getAttribute("message");
                                                            switch (action) {
                                                                case "inserted":
                                                                    sAlert("Berhasil Menambah Record <br>" + message);
                                                                    clearAllForm(addFamilyForm);
                                                                    setEnable(["add", "clear", "cancel"], addFamilyForm, editEmpLayout.cells("a"));
                                                                    break;
                                                                case "error":
                                                                    eAlert("Gagal Menambah Record<br>" + message);
                                                                    setEnable(["add", "clear", "cancel"], addFamilyForm, editEmpLayout.cells("a"));
                                                                    break;								
                                                            }
                                                        })
                                                    } else { 
                                                        eAlert("Input error!");
                                                    }
                                                    break;
                                                case "clear":
                                                    clearAllForm(addFamilyForm);
                                                    break;
                                                case "cancel":
                                                    editEmpLayout.cells("a").showView("family");
                                                    loadDataFamily();
                                                    break;
                                            }
                                        }); 
                                        break;
                                    case "delete":
                                        reqAction(familyGrid, Emp("familyDelete"), 1, (err, res) => {
                                            loadDataFamily();
                                            res.mSuccess && sAlert("Sukses Menghapus Record<br>" + res.mSuccess);
                                            res.mError && eAlert("Gagal Menghapus Record<br>" + res.mError);
                                        });
                                        break;
                                    case "edit":
                                        if(familyGrid.getSelectedRowId()) {
                                            editEmpLayout.cells("a").showView("edit_family");
                                            var editFamilyForm = editEmpLayout.cells("a").attachForm([
                                                {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Ubah Data Keluarga - " + editEmpForm.getItemValue("employee_name"), list: [
                                                    {type: "block", list: familyForm},
                                                    {type: "block", offsetLeft: 10, offsetTop: 30, list:[
                                                        {type: "button", name: "update", value: "Simpan", className: "button_update"},
                                                        {type: "newcolumn"},
                                                        {type: "button", name: "cancel", value: "Cancel", className: "button_no"}
                                                    ]}
                                                ]}
                                            ]);

                                            fetchFormData(Emp("familyForm", {id: familyGrid.getSelectedRowId()}), editFamilyForm);
                                            
                                            editFamilyForm.attachEvent("onButtonClick", function(name) {
                                                switch (name) {
                                                    case "update":
                                                        isLogin();
                                                        if (editFamilyForm.validate() == true) {
                                                            setDisable(["update", "cancel"], editFamilyForm, familyLayout.cells("a"));
                                                            
                                                            let editFamilyFormDP = new dataProcessor(Emp("familyForm"));
                                                            editFamilyFormDP.init(editFamilyForm);
                                                            editFamilyForm.save();
                                                            
                                                            editFamilyFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {	
                                                                let message = tag.getAttribute("message");	
                                                                switch (action) {
                                                                    case "updated":
                                                                        sAlert("Berhasil Mengubah Record <br>" + message);
                                                                        editEmpLayout.cells("a").showView("family");
                                                                        loadDataFamily();
                                                                        break;
                                                                    case "error":
                                                                        eAlert("Gagal Mengubah Record<br>"+message);
                                                                        setEnable(["update", "cancel"], editFamilyForm, familyLayout.cells("a"));
                                                                        break;				
                                                                }
                                                            });
                                                        } else {
                                                            eAlert("Input error!");
                                                        }
                                                        break;
                                                    case "cancel":
                                                        editEmpLayout.cells("a").showView("family");
                                                        break;
                                                }
                                            }); 
                                        } else {
                                            eAlert("Pilih baris yang akan diubah!");
                                        }
                                        break;
                                    case "export":
                                        familyGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                                        sAlert("Export Data Dimulai");
                                        break;
                                }
                            });
                        }

                        function rankView() {
                            editEmpLayout.cells("a").showView("rank");
                            var rankLayout = editEmpLayout.cells("a").attachLayout({
                                pattern: "1C",
                                cells: [
                                    {id: "a", text:"Riwayat Jabatan - " + editEmpForm.getItemValue("employee_name")}
                                ]
                            });

                            var rankToolbar = rankLayout.cells("a").attachToolbar({
                                icons_path: "./public/codebase/icons/",
                                items: [
                                    {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                                    {id: "add", text: "Tambah", type: "button", img: "add.png"},
                                    {id: "delete", text: "Hapus", type: "button", img: "delete.png"},
                                    {id: "edit", text: "Ubah", type: "button", img: "edit.png"},
                                    {id: "active", text: "Jadikan Jabatan Pelaksana", type: "button", img: "medal.png"},
                                    {id: "nonactive", text: "Nonaktifkan Jabatan", type: "button", img: "block.png"},
                                    {id: "export", text: "Export Data", type: "button", img: "table.png"}
                                ]											
                            });

                            function loadDataRank() {
                                isLogin();
                                rankLayout.cells("a").progressOn();
                                rankGrid = rankLayout.cells("a").attachGrid();
                                rankGrid.setHeader("No,Sub Unit,Sub Department,Sub Bagian,Jabatan,Status,Nomor SK,Tanggal SK,Tanggal Mulai,Tanggal Berakhir,Di Buat");
                                rankGrid.setInitWidthsP("5,15,15,15,15,15,15,15,15,15,20");
                                rankGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left");
                                rankGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str");
                                rankGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
                                rankGrid.enableMultiselect(true);
                                rankGrid.enableSmartRendering(true);
                                rankGrid.attachEvent("onXLE", function() {
                                    rankLayout.cells("a").progressOff();
                                });
                                rankGrid.init();
                                rankGrid.clearAndLoad(Emp("rankGrid", {empId: editEmpForm.getItemValue("id")}));
                            }
                            
                            loadDataRank();

                            var rankForm = [
                                {type: "hidden", name: "id", label: "ID", inputWidth: 220, labelWidth: 120},
                                {type: "hidden", name: "emp_id", label: "Employee ID", inputWidth: 220, labelWidth: 120, value: editEmpForm.getItemValue("id")},
                                {type: "combo", name: "rank_id", label: "Jabatan", readonly: true, required: true, labelWidth: 120, inputWidth: 220},																							
                                {type: "combo", name: "department_id", label: "Sub Unit", readonly: true, required: true, labelWidth: 120, inputWidth: 220},																							
                                {type: "combo", name: "sub_department_id", label: "Bagian", readonly: true, required: true, labelWidth: 120, inputWidth: 220},																							
                                {type: "combo", name: "division_id", label: "Sub Bagian", readonly: true, required: true, labelWidth: 120, inputWidth: 220},																							
                                {type: "input", name: "sk_number",label: "Nomor SK", inputWidth: 220, labelWidth: 120},
                                {type: "calendar", name: "sk_date",label: "Tanggal SK", inputWidth: 220, labelWidth: 120},												
                                {type: "calendar", name: "start_date",label: "Tanggal Mulai", inputWidth: 220, labelWidth: 120},												
                                {type: "calendar", name: "end_date",label: "Tanggal Berakhir", inputWidth: 220, labelWidth: 120},	
                                {type: "checkbox", name: "last_rank", label: "Jadikan Jabatan Terakhir", inputWidth: 220, labelWidth: 120},											
                            ];

                            rankToolbar.attachEvent("onClick", function(id) {
                                switch (id) {
                                    case "active":
                                        if(!rankGrid.getSelectedRowId()) {
                                            return eAlert("Pilih jabatan yang akan di aktifkan!");
                                        }
                                        dhtmlx.modalbox({
                                            type: "alert-warning",
                                            title: "Konfirmasi Aktivasi Jabatan Pelaksana",
                                            text: "Jadikan jabatan pelaksana?",
                                            buttons: ["Ya", "Tidak"],
                                            callback: function (index) {
                                                if (index == 0) {
                                                    reqJson(Emp("rankStatus"), "POST", {status: 'ACTIVE', id: rankGrid.getSelectedRowId(), empId: editEmpForm.getItemValue("id")}, (err, res) => {
                                                        if(res.status === "success") {
                                                            sAlert(res.message);
                                                            loadDataRank();
                                                        } else {
                                                            eAlert(res.message);
                                                        }
                                                    });
                                                }
                                            },
                                        });
                                        break;
                                    case "nonactive":
                                        if(!rankGrid.getSelectedRowId()) {
                                            return eAlert("Pilih jabatan yang akan di dinonaktifkan!");
                                        }
                                        dhtmlx.modalbox({
                                            type: "alert-warning",
                                            title: "Konfirmasi Aktivasi Jabatan Pelaksana",
                                            text: "Jadikan jabatan pelaksana?",
                                            buttons: ["Ya", "Tidak"],
                                            callback: function (index) {
                                                if (index == 0) {
                                                    reqJson(Emp("rankStatus"), "POST", {status: 'NONACTIVE', id: rankGrid.getSelectedRowId()}, (err, res) => {
                                                        if(res.status === "success") {
                                                            sAlert(res.message);
                                                            loadDataRank();
                                                        } else {
                                                            eAlert(res.message);
                                                        }
                                                    });
                                                }
                                            },
                                        });
                                        break;
                                    case "refresh":
                                        loadDataRank();
                                        break;
                                    case "add":
                                        editEmpLayout.cells("a").showView("add_rank");
                                        var addRankForm = editEmpLayout.cells("a").attachForm([
                                            {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Tambah Riwayat Jabatan - " + editEmpForm.getItemValue("employee_name"), 
                                                list: [
                                                    {type: "block", list: rankForm},
                                                    {type: "block", offsetLeft: 10, offsetTop: 30, list:[
                                                        {type: "button", name: "add", value: "Tambah", className: "button_add"},
                                                        {type: "newcolumn"},
                                                        {type: "button", name: "clear", value: "Clear", className: "button_clear"},
                                                        {type: "newcolumn"},
                                                        {type: "button", name: "cancel", value: "Cancel", className: "button_no"}
                                                    ]}
                                                ]
                                            }
                                        ]);

                                        var addDeptRankCombo = addRankForm.getCombo("department_id");
                                        var addSubDeptRankCombo = addRankForm.getCombo("sub_department_id");
                                        var addDivRankCombo = addRankForm.getCombo("division_id");
                                        var addRankRCombo = addRankForm.getCombo("rank_id");

                                        addDeptRankCombo.load(Emp("getDepartment"));
                                        addDeptRankCombo.attachEvent("onChange", function(value, text){
                                            if(addRankForm.getItemValue("rank_id") > 2 || addRankForm.getItemValue("rank_id") == "") {
                                                clearComboReload(addRankForm, "sub_department_id", Emp("getSubDepartment", {deptId: value}));
                                            }
                                        });
                                        addSubDeptRankCombo.attachEvent("onChange", function(value, text){
                                            if(addRankForm.getItemValue("rank_id") > 4 || addRankForm.getItemValue("rank_id") == "") {
                                                clearComboReload(addRankForm, "division_id", Emp("getDivision", {subDeptId: value}));
                                            }
                                        });

                                        addRankRCombo.load(Emp("getRank"));
                                        addRankRCombo.attachEvent("onChange", function(value, text){
                                        if(value <= 2) {
                                                addDivRankCombo.clearAll();
                                                addDivRankCombo.addOption([[0, "-"]]);
                                                addDivRankCombo.selectOption(0);
                                                addSubDeptRankCombo.clearAll();
                                                addSubDeptRankCombo.addOption([[0, "-"]]);
                                                addSubDeptRankCombo.selectOption(0);
                                        } else if(value <= 4) {
                                                clearComboReload(addRankForm, "sub_department_id", Emp("getSubDepartment", {deptId: addRankForm.getCombo("department_id")}));
                                                addDivRankCombo.clearAll();
                                                addDivRankCombo.addOption([[0, "-"]]);
                                                addDivRankCombo.selectOption(0);
                                        } else {
                                                addSubDeptRankCombo.load(Emp("getSubDepartment", {deptId: addRankForm.getItemValue("department_id"), select: addRankForm.getItemValue("sub_department_id")}));
                                                addDivRankCombo.load(Emp("getDivision", {subDeptId: addRankForm.getItemValue("sub_department_id"), select: addRankForm.getItemValue("division_id")}));
                                        }
                                        });

                                        addRankForm.attachEvent("onButtonClick", function(name) {
                                            switch (name) {
                                                case "add":
                                                    isLogin();
                                                    if (addRankForm.validate() == true) {
                                                        setDisable(["add", "clear", "cancel"], addRankForm, editEmpLayout.cells("a"));

                                                        let addRankFormDP = new dataProcessor(Emp("rankForm"));
                                                        addRankFormDP.init(addRankForm);
                                                        addRankForm.save();

                                                        addRankFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {	
                                                            let message = tag.getAttribute("message");
                                                            switch (action) {
                                                                case "inserted":
                                                                    sAlert("Berhasil Menambah Record <br>" + message);
                                                                    clearAllForm(addRankForm, comboUrl);
                                                                    setEnable(["add", "clear", "cancel"], addRankForm, editEmpLayout.cells("a"));
                                                                    break;
                                                                case "error":
                                                                    eAlert("Gagal Menambah Record<br>" + message);
                                                                    setEnable(["add", "clear", "cancel"], addRankForm, editEmpLayout.cells("a"));
                                                                    break;								
                                                            }
                                                        });
                                                    } else { 
                                                        eAlert("Input error!");
                                                    }
                                                    break;
                                                case "clear":
                                                    clearAllForm(addRankForm, comboUrl);
                                                    break;
                                                case "cancel":
                                                    loadDataRank();
                                                    editEmpLayout.cells("a").showView("rank");
                                                    break;
                                            }
                                        }); 
                                        break;
                                    case "delete":
                                        reqAction(rankGrid, Emp("rankDelete"), 3, (err, res) => {
                                            loadDataRank();
                                            res.mSuccess && sAlert("Sukses Menghapus Record<br>" + res.mSuccess);
                                            res.mError && eAlert("Gagal Menghapus Record<br>" + res.mError);
                                        });
                                        break;
                                    case "edit":
                                        if(rankGrid.getSelectedRowId()) {
                                            editEmpLayout.cells("a").showView("edit_rank");
                                            var editRankForm = editEmpLayout.cells("a").attachForm([
                                                {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Ubah Data Jabatan - " + editEmpForm.getItemValue("employee_name"), list: [
                                                    {type: "block", list: rankForm},
                                                    {type: "block", offsetLeft: 10, offsetTop: 30, list:[
                                                        {type: "button", name: "update", value: "Simpan", className: "button_update"},
                                                        {type: "newcolumn"},
                                                        {type: "button", name: "cancel", value: "Cancel", className: "button_no"}
                                                    ]}
                                                ]}
                                            ]);

                                            var editDeptRankCombo = editRankForm.getCombo("department_id");
                                            var editSubDeptRankCombo = editRankForm.getCombo("sub_department_id");
                                            var editDivRankCombo = editRankForm.getCombo("division_id");
                                            var editRankRCombo = editRankForm.getCombo("rank_id");
                                            fetchFormData(Emp("rankForm", {id: rankGrid.getSelectedRowId()}), editRankForm, null, null, setCombo);

                                            function setCombo() {
                                                editDeptRankCombo.load(Emp("getDepartment", {select: editRankForm.getItemValue("department_id")}));
                                                editSubDeptRankCombo.load(Emp("getSubDepartment", {deptId: editRankForm.getItemValue("department_id"), select: editEmpForm.getItemValue("sub_department_id")}));
                                                
                                                editDivRankCombo.load(Emp("getDivision", {subDeptId: editRankForm.getItemValue("sub_department_id"), select: editEmpForm.getItemValue("division_id")}));
                                                
                                                editRankRCombo.load(Emp("getRank", {select: editRankForm.getItemValue("rank_id")}));

                                                editDeptRankCombo.attachEvent("onChange", function(value, text){
                                                    clearComboReload(editRankForm, "sub_department_id", Emp("getSubDepartment", {deptId: value}));
                                                });

                                                editSubDeptRankCombo.attachEvent("onChange", function(value, text){
                                                    clearComboReload(editRankForm, "division_id", Emp("getDivision", {subDeptId: value}));
                                                });

                                                editRankRCombo.attachEvent("onChange", function(value, text){
                                                    if(value <= 2) {
                                                            editDivRankCombo.clearAll();
                                                            editDivRankCombo.addOption([[0, "-"]]);
                                                            editDivRankCombo.selectOption(0);
                                                            editSubDeptRankCombo.clearAll();
                                                            editSubDeptRankCombo.addOption([[0, "-"]]);
                                                            editSubDeptRankCombo.selectOption(0);
                                                    } else if(value <= 4) {
                                                            editDivRankCombo.clearAll();
                                                            editDivRankCombo.addOption([[0, "-"]]);
                                                            editDivRankCombo.selectOption(0);
                                                    } else {
                                                        editSubDeptRankCombo.load(Emp("getSubDepartment", {deptId: editRankForm.getItemValue("department_id"), select: editRankForm.getItemValue("sub_department_id")}));
                                                        editDivRankCombo.load(Emp("getDivision", {subDeptId: editRankForm.getItemValue("sub_department_id"), select: editRankForm.getItemValue("division_id")}));
                                                    }
                                                });
                                            }
                                            
                                            editRankForm.attachEvent("onButtonClick", function(name) {
                                                switch (name) {
                                                    case "update":
                                                        isLogin();
                                                        if (!editRankForm.validate()) {
                                                            return eAlert("Input error!");
                                                        }

                                                        setDisable(["update", "cancel"], editRankForm, rankLayout.cells("a"));
                                                        let editRankFormDP = new dataProcessor(Emp("rankForm"));
                                                        editRankFormDP.init(editRankForm);
                                                        editRankForm.save();

                                                        editRankFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {	
                                                            let message = tag.getAttribute("message");	
                                                            switch (action) {
                                                                case "updated":
                                                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                                                    setEnable(["update", "cancel"], editRankForm);
                                                                    editEmpLayout.cells("a").showView("rank");
                                                                    loadDataRank();
                                                                    break;
                                                                case "error":
                                                                    eAlert("Gagal Mengubah Record <br>" + message);
                                                                    setEnable(["update", "cancel"], editRankForm, rankLayout.cells("a"));
                                                                    break;				
                                                            }
                                                        });
                                                        break;
                                                    case "cancel":
                                                        editEmpLayout.cells("a").showView("rank");
                                                        break;
                                                }
                                            }); 
                                        } else {
                                            eAlert("Pilih baris yang akan diubah!");
                                        }
                                        break;
                                    case "export":
                                        rankGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                                        sAlert("Export Data Dimulai");
                                        break;
                                }
                            });
                        }

                        function eduView() {
                            editEmpLayout.cells("a").showView("education");
                            var eduLayout = editEmpLayout.cells("a").attachLayout({
                                pattern: "1C",
                                cells: [
                                    {id: "a", text:"Riwayat Pendidikan - " + editEmpForm.getItemValue("employee_name")}
                                ]
                            });

                            var eduToolbar = eduLayout.cells("a").attachToolbar({
                                icons_path: "./public/codebase/icons/",
                                items: [
                                    {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                                    {id: "add", text: "Tambah", type: "button", img: "add.png"},
                                    {id: "delete", text: "Hapus", type: "button", img: "delete.png"},
                                    {id: "edit", text: "Ubah", type: "button", img: "edit.png"},
                                    {id: "export", text: "Export Data", type: "button", img: "excel.png"}
                                ]											
                            });

                            function loadDataEdu() {
                                isLogin();
                                eduLayout.cells("a").progressOn();
                                eduGrid = eduLayout.cells("a").attachGrid();
                                eduGrid.setHeader("No,Tingkat Pendidikan,Jurusan,Uraian,Teknik/Non Teknik,Nama Sekolah/Universitas,Alamat,Nomor STTB,Tanggal STTB,Tahun Lulus,DiBuat");
                                eduGrid.setInitWidthsP("5,15,15,15,15,15,15,15,15,15,20");
                                eduGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left");
                                eduGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str");
                                eduGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
                                eduGrid.enableMultiselect(true);
                                eduGrid.enableSmartRendering(true);
                                eduGrid.attachEvent("onXLE", function() {
                                    eduLayout.cells("a").progressOff();
                                });
                                eduGrid.init();
                                eduGrid.clearAndLoad(Emp("eduGrid", {empId: editEmpForm.getItemValue("id")}));
                            }
                            
                            loadDataEdu();

                            var currentDate = new Date().getFullYear();
                            var optionYears = [{value: "", text: ""}];
                            for (let i = 1998; i <= parseInt(currentDate); i++) {
                               optionYears.push({value: i, text: i});
                            }

                            var eduForm = [
                                {type: "hidden", name: "id", label: "ID", inputWidth: 220, labelWidth: 120},
                                {type: "hidden", name: "emp_id", label: "Employee ID", inputWidth: 220, labelWidth: 120, value: editEmpForm.getItemValue("id")},
                                {type: "combo", name: "level", label: "Tingkat Pendidikan", readonly: true, required: true, labelWidth: 120, inputWidth: 220, 
                                    validate: "NotEmpty",
                                    options: [
                                        {value: "", text: ""},
                                        {value: "Sekolah Dasar", text: "Sekolah Dasar"},
                                        {value: "Sekolah Menengah Pertama", text: "Sekolah Menengah Pertama"},
                                        {value: "Sekolah Menengah Atas", text: "Sekolah Menengah Atas"},
                                        {value: "S1", text: "S1"},
                                        {value: "S2", text: "S2"},
                                        {value: "S3", text: "S3"},
                                    ]
                                },																							
                                {type: "input", name: "major", label: "Jurusan", inputWidth: 220, labelWidth: 120},
                                {type: "input", name: "description", label: "Uraian", inputWidth: 220, labelWidth: 120, rows: 2},
                                {type: "combo", name: "is_technique", label: "Teknik/Non Teknik", readonly: true, labelWidth: 120, inputWidth: 220, 
                                    options: [
                                        {value: "", text: ""},
                                        {value: "Teknik", text: "Teknik"},
                                        {value: "Non Teknik", text: "Non Teknik"},
                                    ]
                                },
                                {type: "input", name: "school_name", label: "Nama Sekolah/Universitas", required: true, inputWidth: 220, labelWidth: 120},
                                {type: "input", name: "address", label: "Alamat", required: true, inputWidth: 220, labelWidth: 120, rows: 2},
                                {type: "input", name: "sttb_number", label: "Nomor STTB", inputWidth: 220, labelWidth: 120},
                                {type: "calendar", name: "sttb_date", label: "Tanggal STTB", inputWidth: 220, labelWidth: 120},												
                                {type: "combo", name: "graduation_year", label: "Tahun", readonly: true, required: true, labelWidth: 120, inputWidth: 220, 
                                    validate: "NotEmpty",
                                    options: optionYears
                                },								
                            ];

                            eduToolbar.attachEvent("onClick", function(id) {
                                switch (id) {
                                    case "refresh":
                                        loadDataEdu();
                                        break;
                                    case "add":
                                        editEmpLayout.cells("a").showView("add_edu");
                                        var addEduForm = editEmpLayout.cells("a").attachForm([
                                            {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Tambah Riwayat Pendidikan - " + editEmpForm.getItemValue("employee_name"), 
                                                list: [
                                                    {type: "block", list: eduForm},
                                                    {type: "block", offsetLeft: 10, offsetTop: 30, list:[
                                                        {type: "button", name: "add", value: "Tambah", className: "button_add"},
                                                        {type: "newcolumn"},
                                                        {type: "button", name: "clear", value: "Clear", className: "button_clear"},
                                                        {type: "newcolumn"},
                                                        {type: "button", name: "cancel", value: "Cancel", className: "button_no"}
                                                    ]}
                                                ]
                                            }
                                        ]);

                                        addEduForm.attachEvent("onButtonClick", function(name) {
                                            switch (name) {
                                                case "add":
                                                    isLogin();
                                                    if (addEduForm.validate() == true) {
                                                        setDisable(["add", "clear", "cancel"], addEduForm, editEmpLayout.cells("a"));

                                                        let addEduFormDP = new dataProcessor(Emp("eduForm"));
                                                        addEduFormDP.init(addEduForm);
                                                        addEduForm.save();

                                                        addEduFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {	
                                                            let message = tag.getAttribute("message");
                                                            switch (action) {
                                                                case "inserted":
                                                                    sAlert("Berhasil Menambah Record <br>" + message);
                                                                    clearAllForm(addEduForm, comboUrl);
                                                                    setEnable(["add", "clear", "cancel"], addEduForm, editEmpLayout.cells("a"));
                                                                    break;
                                                                case "error":
                                                                    eAlert("Gagal Menambah Record<br>" + message);
                                                                    setEnable(["add", "clear", "cancel"], addEduForm, editEmpLayout.cells("a"));
                                                                    break;								
                                                            }
                                                        });
                                                    } else { 
                                                        eAlert("Input error!");
                                                    }
                                                    break;
                                                case "clear":
                                                    clearAllForm(addEduForm, comboUrl);
                                                    break;
                                                case "cancel":
                                                    loadDataEdu();
                                                    editEmpLayout.cells("a").showView("education");
                                                    break;
                                            }
                                        }); 
                                        break;
                                    case "delete":
                                        reqAction(eduGrid, Emp("eduDelete"), 5, (err, res) => {
                                            loadDataEdu();
                                            res.mSuccess && sAlert("Sukses Menghapus Record<br>" + res.mSuccess);
                                            res.mError && eAlert("Gagal Menghapus Record<br>" + res.mError);
                                        });
                                        break;
                                    case "edit":
                                        if(eduGrid.getSelectedRowId()) {
                                            editEmpLayout.cells("a").showView("edit_edu");
                                            var editEduForm = editEmpLayout.cells("a").attachForm([
                                                {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Ubah Data Pendidikan - " + editEmpForm.getItemValue("employee_name"), list: [
                                                    {type: "block", list: eduForm},
                                                    {type: "block", offsetLeft: 10, offsetTop: 30, list:[
                                                        {type: "button", name: "update", value: "Simpan", className: "button_update"},
                                                        {type: "newcolumn"},
                                                        {type: "button", name: "cancel", value: "Cancel", className: "button_no"}
                                                    ]}
                                                ]}
                                            ]);

                                            fetchFormData(Emp("eduForm", {id: eduGrid.getSelectedRowId()}), editEduForm);
                                            
                                            editEduForm.attachEvent("onButtonClick", function(name) {
                                                switch (name) {
                                                    case "update":
                                                        isLogin();
                                                        if (editEduForm.validate() == true) {
                                                            setDisable(["update", "cancel"], editEduForm, eduLayout.cells("a"));

                                                            let editEduFormDP = new dataProcessor(Emp("eduForm"));
                                                            editEduFormDP.init(editEduForm);
                                                            editEduForm.save();

                                                            editEduFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {	
                                                                let message = tag.getAttribute("message");	
                                                                switch (action) {
                                                                    case "updated":
                                                                        sAlert("Berhasil Mengubah Record <br>" + message);
                                                                        setEnable(["update", "cancel"], editEduForm);
                                                                        editEmpLayout.cells("a").showView("education");
                                                                        loadDataEdu();
                                                                        break;
                                                                    case "error":
                                                                        eAlert("Gagal Mengubah Record <br>" + message);
                                                                        setEnable(["update", "cancel"], editEduForm, eduLayout.cells("a"));
                                                                        break;				
                                                                }
                                                            });
                                                        } else {
                                                            eAlert("Input error!");
                                                        }
                                                        break;
                                                    case "cancel":
                                                        editEmpLayout.cells("a").showView("education");
                                                        break;
                                                }
                                            }); 
                                        } else {
                                            eAlert("Pilih baris yang akan diubah!");
                                        }
                                        break;
                                    case "export":
                                        eduGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                                        sAlert("Export Data Dimulai");
                                        break;
                                }
                            });
                        }

                        function trainingView() {
                            editEmpLayout.cells("a").showView("training");
                            var tryLayout = editEmpLayout.cells("a").attachLayout({
                                pattern: "1C",
                                cells: [
                                    {id: "a", text:"Riwayat Pelatihan - " + editEmpForm.getItemValue("employee_name")}
                                ]
                            });

                            var tryToolbar = tryLayout.cells("a").attachToolbar({
                                icons_path: "./public/codebase/icons/",
                                items: [
                                    {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                                    {id: "add", text: "Tambah", type: "button", img: "add.png"},
                                    {id: "delete", text: "Hapus", type: "button", img: "delete.png"},
                                    {id: "edit", text: "Ubah", type: "button", img: "edit.png"},
                                    {id: "export", text: "Export Data", type: "button", img: "table.png"}
                                ]											
                            });

                            function loadDataTry() {
                                isLogin();
                                tryLayout.cells("a").progressOn();
                                tryGrid = tryLayout.cells("a").attachGrid();
                                tryGrid.setHeader("No,Nama Pelatihan,Lokasi Pelatihan,Total Jam,Uraian,Tanggal Sertifikat/Pelaksanaan");
                                tryGrid.setInitWidthsP("5,25,25,10,15,20");
                                tryGrid.setColAlign("center,left,left,left,left,left");
                                tryGrid.setColSorting("int,str,str,str,str,str");
                                tryGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
                                tryGrid.enableMultiselect(true);
                                tryGrid.enableSmartRendering(true);
                                tryGrid.attachEvent("onXLE", function() {
                                    tryLayout.cells("a").progressOff();
                                });
                                tryGrid.init();
                                tryGrid.clearAndLoad(Emp("trainingGrid", {empId: editEmpForm.getItemValue("id")}));
                            }
                            
                            loadDataTry();

                            var tryForm = [
                                {type: "hidden", name: "id", label: "ID", inputWidth: 220, labelWidth: 120},
                                {type: "hidden", name: "emp_id", label: "Employee ID", inputWidth: 220, labelWidth: 120, value: editEmpForm.getItemValue("id")},
                                {type: "combo", name: "training_id", label: "Nama Training", readonly: true, required: true, labelWidth: 120, inputWidth: 220},
                                {type: "input", name: "location", label: "Lokasi Training", required: true, inputWidth: 220, labelWidth: 120, rows: 2},
                                {type: "input", name: "description", label: "Uraian", inputWidth: 220, labelWidth: 120, rows: 2},
                                {type: "input", name: "total_hour", label: "Total Jam Training", required: true, inputWidth: 220, labelWidth: 120, validate:"ValidNumeric"},												
                                {type: "calendar", name: "certificate_date", label: "Tanggal Sertifikat", required: true, inputWidth: 220, labelWidth: 120},												
                            ];


                            tryToolbar.attachEvent("onClick", function(id) {
                                switch (id) {
                                    case "refresh":
                                        loadDataTry();
                                        break;
                                    case "add":
                                        editEmpLayout.cells("a").showView("add_training");
                                        var addTryForm = editEmpLayout.cells("a").attachForm([
                                            {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Tambah Riwayat Pelatihan - " + editEmpForm.getItemValue("employee_name"), 
                                                list: [
                                                    {type: "block", list: tryForm},
                                                    {type: "block", offsetLeft: 10, offsetTop: 30, list:[
                                                        {type: "button", name: "add", value: "Tambah", className: "button_add"},
                                                        {type: "newcolumn"},
                                                        {type: "button", name: "clear", value: "Clear", className: "button_clear"},
                                                        {type: "newcolumn"},
                                                        {type: "button", name: "cancel", value: "Cancel", className: "button_no"}
                                                    ]}
                                                ]
                                            }
                                        ]);
                                        isFormNumeric(addTryForm, ["total_hour"]);

                                        var tryCombo = addTryForm.getCombo("training_id");
                                        tryCombo.load(Emp("getTraining"));

                                        addTryForm.attachEvent("onButtonClick", function(name) {
                                            switch (name) {
                                                case "add":
                                                    isLogin();
                                                    if (addTryForm.validate() == true) {
                                                        setDisable(["add", "clear", "cancel"], addTryForm, editEmpLayout.cells("a"));

                                                        let addTryFormDP = new dataProcessor(Emp("trainingForm"));
                                                        addTryFormDP.init(addTryForm);
                                                        addTryForm.save();

                                                        addTryFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {	
                                                            let message = tag.getAttribute("message");
                                                            switch (action) {
                                                                case "inserted":
                                                                    sAlert("Berhasil Menambah Record <br>" + message);
                                                                    clearAllForm(addTryForm, comboUrl);
                                                                    setEnable(["add", "clear", "cancel"], addTryForm, editEmpLayout.cells("a"));
                                                                    break;
                                                                case "error":
                                                                    eAlert("Gagal Menambah Record<br>" + message);
                                                                    setEnable(["add", "clear", "cancel"], addTryForm, editEmpLayout.cells("a"));
                                                                    break;								
                                                            }
                                                        });
                                                    } else { 
                                                        eAlert("Input error!");
                                                    }
                                                    break;
                                                case "clear":
                                                    clearAllForm(addTryForm, comboUrl);
                                                    break;
                                                case "cancel":
                                                    loadDataTry();
                                                    editEmpLayout.cells("a").showView("training");
                                                    break;
                                            }
                                        }); 
                                        break;
                                    case "delete":
                                        reqAction(tryGrid, Emp("trainingDelete"), 5, (err, res) => {
                                            loadDataTry();
                                            res.mSuccess && sAlert("Sukses Menghapus Record<br>" + res.mSuccess);
                                            res.mError && eAlert("Gagal Menghapus Record<br>" + res.mError);
                                        });
                                        break;
                                    case "edit":
                                        if(tryGrid.getSelectedRowId()) {
                                            editEmpLayout.cells("a").showView("edit_training");
                                            var editTryForm = editEmpLayout.cells("a").attachForm([
                                                {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Ubah Data Pelatihan - " + editEmpForm.getItemValue("employee_name"), list: [
                                                    {type: "block", list: tryForm},
                                                    {type: "block", offsetLeft: 10, offsetTop: 30, list:[
                                                        {type: "button", name: "update", value: "Simpan", className: "button_update"},
                                                        {type: "newcolumn"},
                                                        {type: "button", name: "cancel", value: "Cancel", className: "button_no"}
                                                    ]}
                                                ]}
                                            ]);
                                            isFormNumeric(editTryForm, ["total_hour"]);

                                            var editTryCombo = editTryForm.getCombo("training_id");
                                            fetchFormData(Emp("trainingForm", {id: tryGrid.getSelectedRowId()}), editTryForm, null, null, setTryCombo);

                                            function setTryCombo() {
                                                editTryCombo.load(Emp("getTraining", {select: editTryForm.getItemValue("training_id")}));
                                            }

                                            editTryForm.attachEvent("onButtonClick", function(name) {
                                                switch (name) {
                                                    case "update":
                                                        isLogin();
                                                        if (editTryForm.validate() == true) {
                                                            setDisable(["update", "cancel"], editTryForm, tryLayout.cells("a"));

                                                            let editTryFormDP = new dataProcessor(Emp("trainingForm"));
                                                            editTryFormDP.init(editTryForm);
                                                            editTryForm.save();

                                                            editTryFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {	
                                                                let message = tag.getAttribute("message");	
                                                                switch (action) {
                                                                    case "updated":
                                                                        sAlert("Berhasil Mengubah Record <br>" + message);
                                                                        setEnable(["update", "cancel"], editTryForm);
                                                                        editEmpLayout.cells("a").showView("training");
                                                                        loadDataTry();
                                                                        break;
                                                                    case "error":
                                                                        eAlert("Gagal Mengubah Record <br>" + message);
                                                                        setEnable(["update", "cancel"], editTryForm, tryLayout.cells("a"));
                                                                        break;				
                                                                }
                                                            });
                                                        } else {
                                                            eAlert("Input error!");
                                                        }
                                                        break;
                                                    case "cancel":
                                                        editEmpLayout.cells("a").showView("training");
                                                        break;
                                                }
                                            }); 
                                        } else {
                                            eAlert("Pilih baris yang akan diubah!");
                                        }
                                        break;
                                    case "export":
                                        tryGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                                        sAlert("Export Data Dimulai");
                                        break;
                                }
                            });
                        }

                    } else {
                        empTabs.tabs("edit").setActive();
                        fetchFormData(Emp("empForm", {id: empGrid.getSelectedRowId()}), editEmpForm);
                    }
                } else {
                    eAlert("Pilih baris yang akan diubah!");
                }
            }
		}

		function statusEmpHandler(status) {
            reqAction(empGrid, Emp("empStatus", { status }), 1, (err, res) => {
                loadDataEmp();
                res.mSuccess && sAlert("Sukses Non Aktifkan Record <br>" + res.mSuccess);
                res.mError && eAlert("Gagal Non Aktifkan Record <br>" + res.mError);
            });
		}

        empTabs.attachEvent("onTabClose", function(id) {
			switch (id) {
				case "edit":
					if (empTabs.tabs("edit").skipMyCloseEvent) {
						return true;
					} else {	
                        empTabs.tabs("edit").close();	
                        loadDataEmp();
					} 
					break;
				case "add":
					if (empTabs.tabs("add").skipMyCloseEvent) {
						return true;
					} else {
                        empTabs.tabs("add").skipMyCloseEvent = true;
                        empTabs.tabs("add").close();
                        loadDataEmp();
					} 
					break;
			}
		});
    }
JS;

header('Content-Type: application/javascript');
echo $script;

