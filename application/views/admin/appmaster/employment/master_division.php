<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	
	function showMasterDivision() {	
        var addDivForm;
        var editDivForm;

        var comboUrl = {
            department_id: {
                url: Emp("getDepartment"),
                reload: true
            },
            sub_department_id: {
                url: Emp("getSubDepartment"),
                reload: false
            }
        }

        var divLayout = mainTab.cells("master_division").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form Sub Bagian",
                    header: true,
                    collapse: true
                }
            ]
        });

        var divToolbar = mainTab.cells("master_division").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "add", text: "Tambah", type: "button", img: "add.png"},
                {id: "delete", text: "Hapus", type: "button", img: "delete.png"},
                {id: "edit", text: "Ubah", type: "button", img: "edit.png", img_disabled: "edit_disabled.png"},
                {id: "searchtext", text: "Cari : ", type: "text"},
                {id: "search", text: "", type: "buttonInput", width: 150}
            ]
        });

        if(userLogged.role !== "admin") {
            divToolbar.disableItem("add");
            divToolbar.disableItem("delete");
        }

        var divStatusBar = divLayout.cells("a").attachStatusBar();
        function divGridCount() {
            let divGridRows = divGrid.getRowsNum();
            divStatusBar.setText("Total baris: " + divGridRows);
        }

        var divGrid = divLayout.cells("a").attachGrid();
        divGrid.setHeader("No,Nama Sub Bagian,Bagian,Sub Unit,Created By,Updated By,DiBuat");
        divGrid.attachHeader("#rspan,#text_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter")
        divGrid.setColSorting("str,str,str,str,str,str,str");
        divGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        divGrid.setColAlign("center,left,left,left,left,left,left");
        divGrid.setInitWidthsP("5,25,25,25,15,15,25");
        divGrid.enableSmartRendering(true);
        divGrid.enableMultiselect(true);
        divGrid.attachEvent("onXLE", function() {
            divLayout.cells("a").progressOff();
        });
        divGrid.init();
        
        function rDivGrid() {
            divLayout.cells("a").progressOn();
            divGrid.clearAndLoad(AppMaster("divGrid", {search: divToolbar.getValue("search")}), divGridCount);
        };

        rDivGrid();

        divToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    divToolbar.setValue("search","");
                    rDivGrid();
                    break;
                case "add":
                    addDivHandler();
                    break;
                case "delete":
                    deleteDivHandler();
                    break;
                case "edit":
                    editDivHandler();
                    break;
            }
        });

        divToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    rDivGrid();
                    divGrid.attachEvent("onGridReconstructed", divGridCount);
                    break;
            }
        });

        function deleteDivHandler() {
            reqAction(divGrid, AppMaster("divDelete"), 1, (err, res) => {
                rDivGrid();
                res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
            });
        }

        function addDivHandler() {
            divLayout.cells("b").expand();
            divLayout.cells("b").showView("tambah_division");

            addDivForm = divLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Sub Bagian", list: [
                    {type: "combo", name: "department_id", label: "Sub Unit", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "combo", name: "sub_department_id", label: "Bagian", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "input", name: "name", label: "Nama Sub Bagian", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                        {type: "newcolumn"},
                        {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            var addDeptCombo = addDivForm.getCombo("department_id");
            addDeptCombo.load(Emp("getDepartment"));
            addDeptCombo.attachEvent("onChange", function(value, text){
                clearComboReload(addDivForm, "sub_department_id", Emp("getSubDepartment", {deptId: value}));
            });

            addDivForm.attachEvent("onButtonClick", function (name) {
                switch (name) {
                    case "add":
                        if (!addDivForm.validate()) {
                            return eAlert("Input error!");
                        }

                        setDisable(["add", "clear"], addDivForm, divLayout.cells("b"));
                        let addDivFormDP = new dataProcessor(AppMaster("divForm"));
                        addDivFormDP.init(addDivForm);
                        addDivForm.save();

                        addDivFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "inserted":
                                    sAlert("Berhasil Menambahkan Record <br>" + message);
                                    rDivGrid();
                                    clearAllForm(addDivForm, comboUrl);
                                    setEnable(["add", "clear"], addDivForm, divLayout.cells("b"));
                                    break;
                                case "error":
                                    eAlert("Gagal Menambahkan Record <br>" + message);
                                    setEnable(["add", "clear"], addDivForm, divLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "clear":
                        clearAllForm(addDivForm, comboUrl);
                        break;
                    case "cancel":
                        rDivGrid();
                        divLayout.cells("b").collapse();
                        break;
                }
            });
        }

        function editDivHandler() {
            if (!divGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diubah!");
            }

            divLayout.cells("a").progressOff();
            divLayout.cells("b").expand();
            divLayout.cells("b").showView("edit_division");
            editDivForm = divLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Ubah Sub Bagian", list: [
                    {type: "hidden", name: "id", label: "ID", readonly: true},
                    {type: "combo", name: "department_id", label: "Sub Unit", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "combo", name: "sub_department_id", label: "Bagian", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "input", name: "name", label: "Nama Sub Bagian", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);
            
            var editDeptCombo = editDivForm.getCombo("department_id");
            var editSubDeptCombo = editDivForm.getCombo("sub_department_id");
 
            fetchFormData(AppMaster("divForm", {id: divGrid.getSelectedRowId()}), editDivForm, null, null, setCombo);

            function setCombo() {
                editDeptCombo.load(Emp("getDepartment", {select: editDivForm.getItemValue("department_id")}));
                editSubDeptCombo.load(Emp("getSubDepartment", {deptId: editDivForm.getItemValue("department_id"), select: editDivForm.getItemValue("sub_department_id")}));
                
                editDeptCombo.attachEvent("onChange", function(value, text){
                    clearComboReload(editDivForm, "sub_department_id", Emp("getSubDepartment", {deptId: value}));
                });
            }
            
            editDivForm.attachEvent("onButtonClick", function(name) {
                switch (name) {
                    case "update":
                        if (!editDivForm.validate()) {
                            return eAlert("Input error!");
                        }	
                        
                        setDisable(["update", "cancel"], editDivForm, divLayout.cells("b"));
                        let editDivFormDP = new dataProcessor(AppMaster("divForm"));
                        editDivFormDP.init(editDivForm);
                        editDivForm.save();

                        editDivFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rDivGrid();
                                    divLayout.cells("b").progressOff();
                                    divLayout.cells("b").showView("tambah_division");
                                    divLayout.cells("b").collapse();
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record <br>" + message);
                                    setEnable(["update", "cancel"], editDivForm, divLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "cancel":
                        divLayout.cells("b").collapse();
                        divLayout.cells("b").showView("tambah_division");
                        break;
                }
            });
        }
    }
JS;

header('Content-Type: application/javascript');
echo $script;

