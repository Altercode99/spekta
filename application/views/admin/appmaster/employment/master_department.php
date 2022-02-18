<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

	function showMasterDept() {
        var addDeptForm;
        var editDeptForm;

        var comboUrl = {
            location: AppMaster("getLocation")
        }

        var deptLayout = mainTab.cells("master_department").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form Sub Unit",
                    header: true,
                    collapse: true
                }
            ]
        });

        var deptToolbar = mainTab.cells("master_department").attachToolbar({
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
            deptToolbar.disableItem("add");
            deptToolbar.disableItem("delete");
        }

        var deptStatusBar = deptLayout.cells("a").attachStatusBar();
        function deptGridCount() {
            let deptGridRows = deptGrid.getRowsNum();
            deptStatusBar.setText("Total baris: " + deptGridRows);
        }

        var deptGrid = deptLayout.cells("a").attachGrid();
        deptGrid.setHeader("No,Nama Sub Unit,Created By,Updated By,DiBuat");
        deptGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#text_filter")
        deptGrid.setColSorting("str,str,str,str,str");
        deptGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt");
        deptGrid.setColAlign("center,left,left,left,left");
        deptGrid.setInitWidthsP("5,30,20,20,25");
        deptGrid.enableSmartRendering(true);
        deptGrid.enableMultiselect(true);
        deptGrid.attachEvent("onXLE", function() {
            deptLayout.cells("a").progressOff();
        });
        deptGrid.init();

        function rDeptGrid() {
            deptLayout.cells("a").progressOn();
            deptGrid.clearAndLoad(AppMaster("deptGrid", {search: deptToolbar.getValue("search")}), deptGridCount);
        }

        rDeptGrid();

        deptToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    deptToolbar.setValue("search","");
                    rDeptGrid();
                    break;
                case "add":
                    addDeptHandler();
                    break;
                case "delete":
                    deleteDeptHandler();
                    break;
                case "edit":
                    editDeptHandler();
                    break;
            }
        });

        deptToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    rDeptGrid();
                    deptGrid.attachEvent("onGridReconstructed", deptGridCount);
                    break;
            }
        });

        function deleteDeptHandler() {
            reqAction(deptGrid, AppMaster("deptDelete"), 1, (err, res) => {
                rDeptGrid();
                res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
            });
        }

        function addDeptHandler() {
            deptLayout.cells("b").expand();
            deptLayout.cells("b").showView("tambah_departemen");

            addDeptForm = deptLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Sub Unit", list: [
                    {type: "input", name: "name", label: "Nama Sub Unit", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                        {type: "newcolumn"},
                        {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            addDeptForm.attachEvent("onButtonClick", function (name) {
                switch (name) {
                    case "add":
                        if (!addDeptForm.validate()) {
                            return eAlert("Input error!");
                        }

                        setDisable(["add", "clear"], addDeptForm, deptLayout.cells("b"));
                        let addDeptFormDP = new dataProcessor(AppMaster("deptForm"));
                        addDeptFormDP.init(addDeptForm);
                        addDeptForm.save();

                        addDeptFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "inserted":
                                    sAlert("Berhasil Menambahkan Record <br>" + message);
                                    rDeptGrid();
                                    clearAllForm(addDeptForm, comboUrl);
                                    setEnable(["add", "clear"], addDeptForm, deptLayout.cells("b"));
                                    break;
                                case "error":
                                    eAlert("Gagal Menambahkan Record <br>" + message);
                                    setEnable(["add", "clear"], addDeptForm, deptLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "clear":
                        clearAllForm(addDeptForm, comboUrl);
                        break;
                    case "cancel":
                        deptLayout.cells("b").collapse();
                        break;
                }
            });
        }

        function editDeptHandler() {
            if (!deptGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diubah!");
            }

            deptLayout.cells("b").expand();
            deptLayout.cells("b").showView("edit_departemen");
            editDeptForm = deptLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Ubah Sub Unit", list: [
                    {type: "hidden", name: "id", label: "ID", readonly: true},
                    {type: "input", name: "name", label: "Nama Sub Unit", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            fetchFormData(AppMaster("deptForm", {id: deptGrid.getSelectedRowId()}), editDeptForm);
            editDeptForm.attachEvent("onButtonClick", function(name) {
                switch (name) {
                    case "update":
                        if (!editDeptForm.validate()) {
                            return eAlert("Input error!");
                        }

                        setDisable(["update", "cancel"], editDeptForm, deptLayout.cells("b"));
                        let editDeptFormDP = new dataProcessor(AppMaster("deptForm"));
                        editDeptFormDP.init(editDeptForm);
                        editDeptForm.save();

                        editDeptFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rDeptGrid();
                                    deptLayout.cells("b").progressOff();
                                    deptLayout.cells("b").showView("tambah_departemen");
                                    deptLayout.cells("b").collapse();
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record <br>" + message);
                                    setEnable(["update", "cancel"], editDeptForm, deptLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "cancel":
                        rDeptGrid();
                        deptLayout.cells("b").collapse();
                        deptLayout.cells("b").showView("tambah_departemen");
                        break;
                }
            });
        }

    }
JS;

header('Content-Type: application/javascript');
echo $script;
