<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	
	function showReqOvertime() {	
        var editReqForm;
        var persons = [];

        var comboUrl = {
            department_id: {
                url: Emp("getDepartment"),
                reload: true
            },
            sub_department_id: {
                url: Emp("getSubDepartment"),
                reload: false
            },
            division_id: {
                url: Emp("getDivision"),
                reload: false
            }
        }

        var reqLayout = mainTab.cells("master_overtime_requirement").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form Kebutuhan Lembur",
                    header: true,
                    collapse: true
                }
            ]
        });

        var reqToolbar = mainTab.cells("master_overtime_requirement").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "edit", text: "Ubah", type: "button", img: "edit.png", img_disabled: "edit_disabled.png"},
                {id: "searchtext", text: "Cari : ", type: "text"},
                {id: "search", text: "", type: "buttonInput", width: 150}
            ]
        });

        var reqStatusBar = reqLayout.cells("a").attachStatusBar();
        function reqGridCount() {
            let reqGridRows = reqGrid.getRowsNum();
            reqStatusBar.setText("Total baris: " + reqGridRows);
        }

        var reqGrid = reqLayout.cells("a").attachGrid();
        reqGrid.setHeader("No,Nama Kebutuhan,Kategori,Sub Unit (Ref),Bagian (Ref),Sub Bagian (Ref),Created By,Updated By,DiBuat");
        reqGrid.attachHeader("#rspan,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter")
        reqGrid.setColSorting("str,str,str,str,str,str,str,str,str");
        reqGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        reqGrid.setColAlign("center,left,left,left,left,left,left,left,left");
        reqGrid.setInitWidthsP("5,20,20,20,20,15,15,15,25");
        reqGrid.enableSmartRendering(true);
        reqGrid.enableMultiselect(true);
        reqGrid.attachEvent("onXLE", function() {
            reqLayout.cells("a").progressOff();
        });
        reqGrid.init();
        
        function rReqGrid() {
            reqLayout.cells("a").progressOn();
            reqGrid.clearAndLoad(AppMaster2("reqOvertimeGrid", {search: reqToolbar.getValue("search")}), reqGridCount);
        }

        rReqGrid();

        reqToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    reqToolbar.setValue("search","");
                    rReqGrid();
                    break;
                case "edit":
                    editOvertimeHandler();
                    break;
            }
        });

        reqToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    rReqGrid();
                    reqGrid.attachEvent("onGridReconstructed", reqGridCount);
                    break;
            }
        });
       
        function editOvertimeHandler() {
            if (!reqGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diubah!");
            }

            reqLayout.cells("b").expand();
            reqLayout.cells("b").showView("edit_loc");
            editReqForm = reqLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Ubah Kebutuhan Lembur", list: [
                    {type: "hidden", name: "id", label: "ID", readonly: true},
                    {type: "input", name: "name", label: "Nama Kebutuhan", labelWidth: 130, inputWidth:250, required: true},
                    {type: "combo", name: "category", label: "Kategori", readonly: true, required: true, labelWidth: 130, inputWidth: 250,
                        validate: "NotEmpty", 
                        options:[
                            {value: "", text: ""},
                            {value: "Fasilitas", text: "Fasilitas"},
                            {value: "Tenaga Kerja", text: "Tenaga Kerja"},
                        ]
                    },
                    {type: "combo", name: "department_id", label: "Sub Unit", readonly: true, required: true, labelWidth: 130, inputWidth: 250, connector: comboUrl["department_id"]},
                    {type: "combo", name: "sub_department_id", label: "Bagian", readonly: true, required: true, labelWidth: 130, inputWidth: 250, connector: comboUrl["sub_department_id"]},
                    {type: "combo", name: "division_id", label: "Sub Bagian", readonly: true, required: true, labelWidth: 130, inputWidth: 250, connector: comboUrl["division_id"]},
                    {type: "input", name: "pic_emails", label: "PIC Email", required: true, labelWidth: 130, inputWidth: 250},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            var editDeptCombo = editReqForm.getCombo("department_id");
            var editSubDeptCombo = editReqForm.getCombo("sub_department_id");
            var editDivCombo = editReqForm.getCombo("division_id");
            fetchFormData(AppMaster2("reqOvertimeForm", {id: reqGrid.getSelectedRowId()}), editReqForm, null, null, setCombo);

            function setCombo() {
                editDeptCombo.load(Emp("getDepartment", {select: editReqForm.getItemValue("department_id")}));
                editSubDeptCombo.load(Emp("getSubDepartment", {deptId: editReqForm.getItemValue("department_id"), select: editReqForm.getItemValue("sub_department_id")}));
                editDivCombo.load(Emp("getDivision", {subDeptId: editReqForm.getItemValue("sub_department_id"), select: editReqForm.getItemValue("division_id")}));

                editDeptCombo.attachEvent("onChange", function(value, text){
                    clearComboReload(editReqForm, "sub_department_id", Emp("getSubDepartment", {deptId: value}));
                });

                editSubDeptCombo.attachEvent("onChange", function(value, text){
                    clearComboReload(editReqForm, "division_id", Emp("getDivision", {subDeptId: value}));
                });

                persons = editReqForm.getItemValue("pic_emails").split(",");
            }

            editReqForm.attachEvent("onFocus", function(name, value){
                if(name === "pic_emails") {
                    loadPIC(editReqForm);
                }
            });

            editReqForm.attachEvent("onButtonClick", function(name) {
                switch (name) {
                    case "update":
                        if (!editReqForm.validate()) {
                            return eAlert("Input error!");
                        }	

                        setDisable(["update", "cancel"], editReqForm, reqLayout.cells("b"));
                        let editReqFormDP = new dataProcessor(AppMaster2("reqOvertimeForm"));
                        editReqFormDP.init(editReqForm);
                        editReqForm.save();

                        editReqFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rReqGrid();	
                                    reqLayout.cells("b").progressOff();
                                    reqLayout.cells("b").showView("tambah_req_overtime");
                                    reqLayout.cells("b").collapse();	
                                    persons = [];						
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record<br>" + message);
                                    setEnable(["update", "cancel"], editReqForm, reqLayout.cells("b"));
                                    break;
                            }
                        });									
                        break;
                    case "cancel":
                        reqLayout.cells("b").collapse();
                        reqLayout.cells("b").showView("tambah_req_overtime");
                        break;
                }
            });
        }

        function loadPIC(form) {            
            if(form.getItemValue("sub_department_id") === "") {
                return eAlert("Bagian belum di pilih!");
            }

            var picWindow = createWindow("pic_window", "Karyawan", 900, 400);
            myWins.window("pic_window").skipMyCloseEvent = true;

            var picToolbar = picWindow.attachToolbar({
                icon_path: "./public/codebase/icons/",
                items: [
                    {id: "save", text: "Simpan", type: "button", img: "ok.png"}
                ]
            });

            picToolbar.attachEvent("onClick", function(id) {
                switch (id) {
                    case "save":
                        persons = [];
                        for (let i = 0; i < picGrid.getRowsNum(); i++) {
                           let id = picGrid.getRowId(i);
                           if(picGrid.cells(id, 1).getValue() == 1) {
                               persons.push(id);
                           } 
                        }
                        form.setItemValue('pic_emails', persons);
                        closeWindow("pic_window");
                        break;
                }
            });


            let picStatusBar = picWindow.attachStatusBar();
            function picGridCount() {
                var picGridRows = picGrid.getRowsNum();
                picStatusBar.setText("Total baris: " + picGridRows);
                persons.length > 0 && persons.map(id => {
                    if(picGrid.doesRowExist(id)) {
                        picGrid.cells(id, 1).setValue(1);
                    }
                });
            }

            picWindow.progressOn();
            picGrid = picWindow.attachGrid();
            picGrid.setImagePath("./public/codebase/imgs/");
            picGrid.setHeader("No,Check,Nama Karyawan,Bagian,Jabatan,Email");
            picGrid.attachHeader("#rspan,#rspan,#text_filter,#select_filter,#select_filter,#text_filter")
            picGrid.setColSorting("int,na,str,str,str,str");
            picGrid.setColAlign("center,left,left,left,left,left");
            picGrid.setColTypes("rotxt,ch,rotxt,rotxt,rotxt,rotxt");
            picGrid.setInitWidthsP("5,5,20,25,20,25");
            picGrid.enableMultiselect(true);
            picGrid.enableSmartRendering(true);
            picGrid.attachEvent("onXLE", function() {
                picWindow.progressOff();
            });
            picGrid.init();
            picGrid.attachEvent("onCheckbox", function(rId, cIdn, state) {
                if(state) {
                    persons.push(rId);                            
                } else {
                    persons.splice(persons.indexOf(rId), 1);
                }
            });
            picGrid.clearAndLoad(RoomRev("getEmployees", {equal_sub_department_id: form.getItemValue("sub_department_id"), equal_status: "ACTIVE"}), picGridCount);
        }

    }
JS;

header('Content-Type: application/javascript');
echo $script;