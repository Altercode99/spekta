<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

	function showMasterSubDept() {
        var addSubDeptForm;
        var editSubDeptForm;

        var comboUrl = {
            department_id: {
                url: Emp("getDepartment"),
                reload: true
            }
        }

        var subDeptLayout = mainTab.cells("master_sub_department").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form Bagian",
                    header: true,
                    collapse: true
                }
            ]
        });

        var subDeptToolbar = mainTab.cells("master_sub_department").attachToolbar({
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
            subDeptToolbar.disableItem("add");
            subDeptToolbar.disableItem("delete");
        }

        var subDeptStatusBar = subDeptLayout.cells("a").attachStatusBar();
        function subDeptGridCount() {
            let subDeptGridRows = subDeptGrid.getRowsNum();
            subDeptStatusBar.setText("Total baris: " + subDeptGridRows);
        }

        var subDeptGrid = subDeptLayout.cells("a").attachGrid();
        subDeptGrid.setHeader("No,Nama Bagian,Sub Unit,Created By,Updated By,DiBuat");
        subDeptGrid.attachHeader("#rspan,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter")
        subDeptGrid.setColSorting("str,str,str,str,str,str");
        subDeptGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        subDeptGrid.setColAlign("center,left,left,left,left,left");
        subDeptGrid.setInitWidthsP("5,25,25,20,20,25");
        subDeptGrid.enableSmartRendering(true);
        subDeptGrid.enableMultiselect(true);
        subDeptGrid.attachEvent("onXLE", function() {
            subDeptLayout.cells("a").progressOff();
        });
        subDeptGrid.init();

        function rSubDeptGrid() {
            subDeptLayout.cells("a").progressOn();
            subDeptGrid.clearAndLoad(AppMaster("subDeptGrid", {search: subDeptToolbar.getValue("search")}), subDeptGridCount);
        }

        rSubDeptGrid();

        subDeptToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    subDeptToolbar.setValue("search","");
                    rSubDeptGrid();
                    break;
                case "add":
                    addSubDeptHandler();
                    break;
                case "delete":
                    deleteSubDeptHandler();
                    break;
                case "edit":
                    editSubDeptHandler();
                    break;
            }
        });

        subDeptToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    rSubDeptGrid();
                    subDeptGrid.attachEvent("onGridReconstructed", subDeptGridCount);
                    break;
            }
        });

        function deleteSubDeptHandler() {
            reqAction(subDeptGrid, AppMaster("subDeptDelete"), 1, (err, res) => {
                rSubDeptGrid();
                res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
            });
        }

        function addSubDeptHandler() {
            subDeptLayout.cells("b").expand();
            subDeptLayout.cells("b").showView("tambah_sub_departemen");

            addSubDeptForm = subDeptLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Bagian", list: [
                    {type: "combo", name: "department_id", label: "Sub Unit", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "input", name: "name", label: "Nama Bagian", labelWidth: 130, inputWidth:250, required: true},
                    {type: userLogged.role === "admin" ? "input" : "hidden", name: "file_limit", label: "Limit File Dokumen Kontrol (MB)", labelWidth: 130, inputWidth:250, required: true, validate:"ValidNumeric"},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                        {type: "newcolumn"},
                        {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);
            isFormNumeric(addSubDeptForm, ["file_limit"]);

            var addDeptCombo = addSubDeptForm.getCombo("department_id");
            addDeptCombo.load(Emp("getDepartment"));

            addSubDeptForm.attachEvent("onButtonClick", function (name) {
                switch (name) {
                    case "add":
                        if (!addSubDeptForm.validate()) {
                            return eAlert("Input error!");
                        }

                        setDisable(["add", "clear"], addSubDeptForm, subDeptLayout.cells("b"));
                        let addSubDeptFormDP = new dataProcessor(AppMaster("subDeptForm"));
                        addSubDeptFormDP.init(addSubDeptForm);
                        addSubDeptForm.save();

                        addSubDeptFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "inserted":
                                    sAlert("Berhasil Menambahkan Record <br>" + message);
                                    rSubDeptGrid();
                                    clearAllForm(addSubDeptForm, comboUrl);
                                    setEnable(["add", "clear"], addSubDeptForm, subDeptLayout.cells("b"));
                                    break;
                                case "error":
                                    eAlert("Gagal Menambahkan Record <br>" + message);
                                    setEnable(["add", "clear"], addSubDeptForm, subDeptLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "clear":
                        clearAllForm(addSubDeptForm, comboUrl);
                        break;
                    case "cancel":
                        subDeptLayout.cells("b").collapse();
                        break;
                }
            });
        }

        function editSubDeptHandler() {
            if (!subDeptGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diubah!");
            }

            subDeptLayout.cells("b").expand();
            subDeptLayout.cells("b").showView("edit_departemen");
            editSubDeptForm = subDeptLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Ubah Bagian", list: [
                    {type: "hidden", name: "id", label: "ID", readonly: true},
                    {type: "combo", name: "department_id", label: "Sub Unit", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "input", name: "name", label: "Nama Bagian", labelWidth: 130, inputWidth:250, required: true},
                    {type: userLogged.role === "admin" ? "input" : "hidden", name: "file_limit", label: "Limit File Dokumen Kontrol (MB)", labelWidth: 130, inputWidth:250, required: true, validate:"ValidNumeric"},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);
            isFormNumeric(editSubDeptForm, ["file_limit"]);
            
            var editDeptCombo = editSubDeptForm.getCombo("department_id");
            fetchFormData(AppMaster("subDeptForm", {id: subDeptGrid.getSelectedRowId()}), editSubDeptForm, null, null, setCombo);

            function setCombo() {
                editDeptCombo.load(Emp("getDepartment", {select: editSubDeptForm.getItemValue("department_id")}));
            }
            
            editSubDeptForm.attachEvent("onButtonClick", function(name) {
                switch (name) {
                    case "update":
                        if (!editSubDeptForm.validate()) {
                            return eAlert("Input error!");
                        }

                        setDisable(["update", "cancel"], editSubDeptForm, subDeptLayout.cells("b"));
                        let editSubDeptFormDP = new dataProcessor(AppMaster("subDeptForm"));
                        editSubDeptFormDP.init(editSubDeptForm);
                        editSubDeptForm.save();

                        editSubDeptFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rSubDeptGrid();
                                    subDeptLayout.cells("b").progressOff();
                                    subDeptLayout.cells("b").showView("tambah_sub_departemen");
                                    subDeptLayout.cells("b").collapse();
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record <br>" + message);
                                    setEnable(["update", "cancel"], editSubDeptForm, subDeptLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "cancel":
                        rSubDeptGrid();
                        subDeptLayout.cells("b").collapse();
                        subDeptLayout.cells("b").showView("tambah_sub_departemen");
                        break;
                }
            });
        }

    }
JS;

header('Content-Type: application/javascript');
echo $script;
