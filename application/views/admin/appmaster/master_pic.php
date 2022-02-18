<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	
	function showMasterPIC() {	
        var addPicForm;
        var editPicForm;
        var persons = [];

        var comboUrl = {
            department_id: {
                url: Emp("getDepartment"),
                reload: true
            },
            sub_department_id: {
                reload: false
            }
        }

        var picLayout = mainTab.cells("master_pic").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form PIC",
                    header: true,
                    collapse: true
                }
            ]
        });

        var masterPicToolbar = mainTab.cells("master_pic").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "add", text: "Tambah", type: "button", img: "add.png"},
                {id: "edit", text: "Ubah", type: "button", img: "edit.png", img_disabled: "edit_disabled.png"},
                {id: "searchtext", text: "Cari : ", type: "text"},
                {id: "search", text: "", type: "buttonInput", width: 150}
            ]
        });

        var picStatusBar = picLayout.cells("a").attachStatusBar();
        function picGridCount() {
            let picGridRows = picGrid.getRowsNum();
            picStatusBar.setText("Total baris: " + picGridRows);
        }

        var picGrid = picLayout.cells("a").attachGrid();
        picGrid.setHeader("No,Kode,Nama Kegiatan,Sub Unit,Bagian,Created By,Updated By,DiBuat");
        picGrid.attachHeader("#rspan,#text_filter,#text_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter")
        picGrid.setColSorting("str,str,str,str,str,str,str,str");
        picGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        picGrid.setColAlign("center,left,left,left,left,left,left,left");
        picGrid.setInitWidthsP("5,20,20,20,20,15,15,25");
        picGrid.enableSmartRendering(true);
        picGrid.enableMultiselect(true);
        picGrid.attachEvent("onXLE", function() {
            picLayout.cells("a").progressOff();
        });
        picGrid.init();
        
        function rPicGrid() {
            picLayout.cells("a").progressOn();
            picGrid.clearAndLoad(AppMaster2("picGrid", {search: masterPicToolbar.getValue("search")}), picGridCount);
        }

        rPicGrid();

        masterPicToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    masterPicToolbar.setValue("search","");
                    rPicGrid();
                    break;
                case "add":
                    addPicHandler();
                    break;
                case "edit":
                    editPicHandler();
                    break;
            }
        });

        masterPicToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    rPicGrid();
                    picGrid.attachEvent("onGridReconstructed", picGridCount);
                    break;
            }
        });

        function addPicHandler() {
            persons = [];
            picLayout.cells("b").expand();
            picLayout.cells("b").showView("tambah_pic");

            addPicForm = picLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah PIC", list: [
                    {type: "combo", name: "code", label: "Kode", labelWidth: 130, inputWidth:250, required: true, validate: "NotEmpty",
                        options: [
                            {value: "vehicles", text: "Reservasi Kendaraan Dinas"},
                            {value: "meeting_rooms", text: "Reservasi Ruang Meeting"},
                            {value: "overtime", text: "Pembuatan Lemburan"}
                        ]
                    },
                    {type: "input", name: "name", label: "Nama Kegiatan", labelWidth: 130, inputWidth:250, required: true},
                    {type: "combo", name: "department_id", label: "Sub Unit", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "combo", name: "sub_department_id", label: "Bagian", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "input", name: "pic_emails", label: "PIC Email", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                        {type: "newcolumn"},
                        {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            var addDeptCombo = addPicForm.getCombo("department_id");
            addDeptCombo.load(Emp("getDepartment"));
            addDeptCombo.attachEvent("onChange", function(value, text){
                clearComboReload(addPicForm, "sub_department_id", Emp("getSubDepartment", {deptId: value}));
            });

            addPicForm.attachEvent("onFocus", function(name, value){
                if(name === "pic_emails") {
                    loadPIC(addPicForm);
                }
            });

            addPicForm.attachEvent("onButtonClick", function (name) {
                switch (name) {
                    case "add":
                        if (!addPicForm.validate()) {
                            return eAlert("Input error!");
                        }

                        setDisable(["add", "clear"], addPicForm, picLayout.cells("b"));
                        let addPicFormDP = new dataProcessor(AppMaster2("picForm"));
                        addPicFormDP.init(addPicForm);
                        addPicForm.save();

                        addPicFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "inserted":
                                    sAlert("Berhasil Menambahkan Record <br>" + message);
                                    rPicGrid();
                                    clearAllForm(addPicForm, comboUrl);
                                    setEnable(["add", "clear"], addPicForm, picLayout.cells("b"));
                                    persons = [];
                                    break;
                                case "error":
                                    eAlert("Gagal Menambahkan Record <br>" + message);
                                    setEnable(["add", "clear"], addPicForm, picLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "clear":
                        clearAllForm(addPicForm, comboUrl);
                        break;
                    case "cancel":
                        rPicGrid();
                        picLayout.cells("b").collapse();
                        break;
                }
            });
        }

        function editPicHandler() {
            if (!picGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diubah!");
            }

            picLayout.cells("b").expand();
            picLayout.cells("b").showView("edit_pic");
            editPicForm = picLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Ubah PIC", list: [
                    {type: "hidden", name: "id", label: "ID", readonly: true},
                    {type: "hidden", name: "code", label: "Kode", labelWidth: 130, inputWidth:250, required: true},
                    {type: "input", name: "name", label: "Nama Kegiatan", labelWidth: 130, inputWidth:250, required: true},
                    {type: "combo", name: "department_id", label: "Sub Unit", readonly: true, required: true, labelWidth: 130, inputWidth: 250, connector: comboUrl["department_id"]},
                    {type: "combo", name: "sub_department_id", label: "Bagian", readonly: true, required: true, labelWidth: 130, inputWidth: 250, connector: comboUrl["sub_department_id"]},
                    {type: "input", name: "pic_emails", label: "PIC Email", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);
            
            var editDeptCombo = editPicForm.getCombo("department_id");
            var editSubDeptCombo = editPicForm.getCombo("sub_department_id");
            fetchFormData(AppMaster2("picForm", {id: picGrid.getSelectedRowId()}), editPicForm, null, null, setCombo);

            function setCombo() {
                editDeptCombo.load(Emp("getDepartment", {select: editPicForm.getItemValue("department_id")}));
                editSubDeptCombo.load(Emp("getSubDepartment", {deptId: editPicForm.getItemValue("department_id"), select: editPicForm.getItemValue("sub_department_id")}));
            
                editDeptCombo.attachEvent("onChange", function(value, text){
                    clearComboReload(editPicForm, "sub_department_id", Emp("getSubDepartment", {deptId: value}));
                });

                persons = editPicForm.getItemValue("pic_emails").split(",")
            }

            editPicForm.attachEvent("onFocus", function(name, value){
                if(name === "pic_emails") {
                    loadPIC(editPicForm);
                }
            });

            editPicForm.attachEvent("onButtonClick", function(name) {
                switch (name) {
                    case "update":
                        if (!editPicForm.validate()) {
                            return eAlert("Input error!");
                        }	

                        setDisable(["update", "cancel"], editPicForm, picLayout.cells("b"));
                        let editPicFormDP = new dataProcessor(AppMaster2("picForm"));
                        editPicFormDP.init(editPicForm);
                        editPicForm.save();

                        editPicFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rPicGrid();	
                                    picLayout.cells("b").progressOff();
                                    picLayout.cells("b").showView("tambah_pic");
                                    picLayout.cells("b").collapse();	
                                    persons = [];						
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record<br>" + message);
                                    setEnable(["update", "cancel"], editPicForm, picLayout.cells("b"));
                                    break;
                            }
                        });									
                        break;
                    case "cancel":
                        picLayout.cells("b").collapse();
                        picLayout.cells("b").showView("tambah_pic");
                        break;
                }
            });
        }

        function loadPIC(form) {            
            if(form.getItemValue("sub_department_id") === "") {
                return eAlert("Bagian belum di pilih!");
            }

            var masterPicWindow = createWindow("master_pic_window", "Karyawan", 900, 400);
            myWins.window("master_pic_window").skipMyCloseEvent = true;

            var masterPicToolbar = masterPicWindow.attachToolbar({
                icon_path: "./public/codebase/icons/",
                items: [
                    {id: "save", text: "Simpan", type: "button", img: "ok.png"}
                ]
            });

            masterPicToolbar.attachEvent("onClick", function(id) {
                switch (id) {
                    case "save":
                        persons = [];
                        for (let i = 0; i < wPicGrid.getRowsNum(); i++) {
                           let id = wPicGrid.getRowId(i);
                           if(wPicGrid.cells(id, 1).getValue() == 1) {
                               persons.push(id);
                           } 
                        }
                        form.setItemValue('pic_emails', persons);
                        closeWindow("master_pic_window");
                        break;
                }
            });

            let mPicStatusBar = masterPicWindow.attachStatusBar();
            function mPicGridCount() {
                var mPicGridRows = picGrid.getRowsNum();
                mPicStatusBar.setText("Total baris: " + mPicGridRows);
                persons.length > 0 && persons.map(id => {
                    if(wPicGrid.doesRowExist(id)) {
                        wPicGrid.cells(id, 1).setValue(1);
                    }
                });
            }

            masterPicWindow.progressOn();
            wPicGrid = masterPicWindow.attachGrid();
            wPicGrid.setImagePath("./public/codebase/imgs/");
            wPicGrid.setHeader("No,Check,Nama Karyawan,Bagian,Jabatan,Email");
            wPicGrid.attachHeader("#rspan,#master_checkbox,#text_filter,#select_filter,#select_filter,#text_filter")
            wPicGrid.setColSorting("int,na,str,str,str,str");
            wPicGrid.setColAlign("center,left,left,left,left,left");
            wPicGrid.setColTypes("rotxt,ch,rotxt,rotxt,rotxt,rotxt");
            wPicGrid.setInitWidthsP("5,5,20,25,20,25");
            wPicGrid.enableMultiselect(true);
            wPicGrid.enableSmartRendering(true);
            wPicGrid.attachEvent("onXLE", function() {
                masterPicWindow.progressOff();
            });
            wPicGrid.init();
            wPicGrid.attachEvent("onCheckbox", function(rId, cIdn, state) {
                if(state) {
                    persons.push(rId);                            
                } else {
                    persons.splice(persons.indexOf(rId), 1);
                }
            });
            wPicGrid.clearAndLoad(RoomRev("getEmployees", {equal_sub_department_id: form.getItemValue("sub_department_id"), equal_status: "ACTIVE"}), mPicGridCount);
        }
    }

JS;

header('Content-Type: application/javascript');
echo $script;