<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	
    function showImproveLevel() {	
        var addImproveLevelForm;
        var editImproveLevelForm;

        var improveLevelLayout = mainTab.cells("master_improve_levels").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form Tingkatan",
                    header: true,
                    collapse: true
                }
            ]
        });

        var improveLevelToolbar = mainTab.cells("master_improve_levels").attachToolbar({
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

        var improveLevelStatusBar = improveLevelLayout.cells("a").attachStatusBar();
        function improveLevelGridCount() {
            improveLevelStatusBar.setText("Total baris: " + improveLevelGrid.getRowsNum());
        }

        var improveLevelGrid = improveLevelLayout.cells("a").attachGrid();
        improveLevelGrid.setHeader("No,Nama Tingkatan,Nama Pendek,Created By,Updated By,DiBuat");
        improveLevelGrid.attachHeader("#rspan,#text_filter,#text_filter,#select_filter,#select_filter,#text_filter")
        improveLevelGrid.setColSorting("int,str,str,str,str,str");
        improveLevelGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        improveLevelGrid.setColAlign("center,left,left,left,left,left");
        improveLevelGrid.setInitWidthsP("5,30,20,20,20,25");
        improveLevelGrid.enableSmartRendering(true);
        improveLevelGrid.enableMultiselect(true);
        improveLevelGrid.attachEvent("onXLE", function() {
            improveLevelLayout.cells("a").progressOff();
        });
        improveLevelGrid.init();
        
        function rImproveLevelGrid() {
            improveLevelLayout.cells("a").progressOn();
            improveLevelGrid.clearAndLoad(Improve("getImproveLevels", {search: improveLevelToolbar.getValue("search")}), improveLevelGridCount);
        };

        rImproveLevelGrid();

        improveLevelToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    improveLevelToolbar.setValue("search","");
                    rImproveLevelGrid();
                    break;
                case "add":
                    addImproveLevelHandler();
                    break;
                case "delete":
                    reqAction(improveLevelGrid, Improve("improveLevelDelete"), 1, (err, res) => {
                        rImproveLevelGrid();
                        res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                        res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
                    });
                    break;
                case "edit":
                    editImproveLevelHandler();
                    break;
            }
        });

        function addImproveLevelHandler() {
            improveLevelLayout.cells("b").expand();
            improveLevelLayout.cells("b").showView("tambah_improve_level");

            addImproveLevelForm = improveLevelLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Tingkatan", list: [
                    {type: "input", name: "stand_for", label: "Nama Pendek", labelWidth: 130, inputWidth:250, required: true},
                    {type: "input", name: "name", label: "Nama Tingkatan", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                        {type: "newcolumn"},
                        {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            addImproveLevelForm.attachEvent("onButtonClick", function (name) {
                switch (name) {
                    case "add":
                        if (!addImproveLevelForm.validate()) {
                            return eAlert("Input error!");
                        }

                        setDisable(["add", "clear"], addImproveLevelForm, improveLevelLayout.cells("b"));
                        let addImproveLevelFormDP = new dataProcessor(Improve("improveLevelForm"));
                        addImproveLevelFormDP.init(addImproveLevelForm);
                        addImproveLevelForm.save();

                        addImproveLevelFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "inserted":
                                    sAlert("Berhasil Menambahkan Record <br>" + message);
                                    rImproveLevelGrid();
                                    clearAllForm(addImproveLevelForm);
                                    setEnable(["add", "clear"], addImproveLevelForm, improveLevelLayout.cells("b"));
                                    break;
                                case "error":
                                    eAlert("Gagal Menambahkan Record <br>" + message);
                                    setEnable(["add", "clear"], addImproveLevelForm, improveLevelLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "clear":
                        clearAllForm(addImproveLevelForm);
                        break;
                    case "cancel":
                        rImproveLevelGrid();
                        improveLevelLayout.cells("b").collapse();
                        break;
                }
            });
        }

        function editImproveLevelHandler() {
            if (!improveLevelGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diubah!");
            }

            improveLevelLayout.cells("a").progressOff();
            improveLevelLayout.cells("b").expand();
            improveLevelLayout.cells("b").showView("edit_improve_level");
            editImproveLevelForm = improveLevelLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Ubah Tingkatan", list: [
                    {type: "hidden", name: "id", label: "ID", readonly: true},
                    {type: "input", name: "stand_for", label: "Nama Pendek", labelWidth: 130, inputWidth:250, required: true},
                    {type: "input", name: "name", label: "Nama Tingkatan", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            fetchFormData(Improve("improveLevelForm", {id: improveLevelGrid.getSelectedRowId()}), editImproveLevelForm);

            editImproveLevelForm.attachEvent("onButtonClick", function(name) {
                switch (name) {
                    case "update":
                        if (!editImproveLevelForm.validate()) {
                            return eAlert("Input error!");
                        }	
                        
                        setDisable(["update", "cancel"], editImproveLevelForm, improveLevelLayout.cells("b"));
                        let editImproveLevelFormDP = new dataProcessor(Improve("improveLevelForm"));
                        editImproveLevelFormDP.init(editImproveLevelForm);
                        editImproveLevelForm.save();

                        editImproveLevelFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rImproveLevelGrid();
                                    improveLevelLayout.cells("b").progressOff();
                                    improveLevelLayout.cells("b").showView("tambah_improve_level");
                                    improveLevelLayout.cells("b").collapse();
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record <br>" + message);
                                    setEnable(["update", "cancel"], editImproveLevelForm, improveLevelLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "cancel":
                        improveLevelLayout.cells("b").collapse();
                        improveLevelLayout.cells("b").showView("tambah_improve_level");
                        break;
                }
            });
        }
    }

JS;
header('Content-Type: application/javascript');
echo $script;