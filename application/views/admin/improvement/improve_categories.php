<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	
    function showImproveCat() {	
        var addImproveCatForm;
        var editImproveCatForm;

        var improveCatLayout = mainTab.cells("master_improve_categories").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form Kategori",
                    header: true,
                    collapse: true
                }
            ]
        });

        var improveCatToolbar = mainTab.cells("master_improve_categories").attachToolbar({
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

        var improveCatStatusBar = improveCatLayout.cells("a").attachStatusBar();
        function improveCatGridCount() {
            improveCatStatusBar.setText("Total baris: " + improveCatGrid.getRowsNum());
        }

        var improveCatGrid = improveCatLayout.cells("a").attachGrid();
        improveCatGrid.setHeader("No,Nama Kategori,Created By,Updated By,DiBuat");
        improveCatGrid.attachHeader("#rspan,#text_filter,#select_filter,#select_filter,#text_filter")
        improveCatGrid.setColSorting("int,str,str,str,str");
        improveCatGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt");
        improveCatGrid.setColAlign("center,left,left,left,left");
        improveCatGrid.setInitWidthsP("5,30,20,20,25");
        improveCatGrid.enableSmartRendering(true);
        improveCatGrid.enableMultiselect(true);
        improveCatGrid.attachEvent("onXLE", function() {
            improveCatLayout.cells("a").progressOff();
        });
        improveCatGrid.init();
        
        function rImproveCatGrid() {
            improveCatLayout.cells("a").progressOn();
            improveCatGrid.clearAndLoad(Improve("getImproveCategories", {search: improveCatToolbar.getValue("search")}), improveCatGridCount);
        };

        rImproveCatGrid();

        improveCatToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    improveCatToolbar.setValue("search","");
                    rImproveCatGrid();
                    break;
                case "add":
                    addImproveCatHandler();
                    break;
                case "delete":
                    reqAction(improveCatGrid, Improve("improveCatDelete"), 1, (err, res) => {
                        rImproveCatGrid();
                        res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                        res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
                    });
                    break;
                case "edit":
                    editImproveCatHandler();
                    break;
            }
        });

        function addImproveCatHandler() {
            improveCatLayout.cells("b").expand();
            improveCatLayout.cells("b").showView("tambah_improve_cat");

            addImproveCatForm = improveCatLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Kategori", list: [
                    {type: "input", name: "name", label: "Nama Kategori", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                        {type: "newcolumn"},
                        {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            addImproveCatForm.attachEvent("onButtonClick", function (name) {
                switch (name) {
                    case "add":
                        if (!addImproveCatForm.validate()) {
                            return eAlert("Input error!");
                        }

                        setDisable(["add", "clear"], addImproveCatForm, improveCatLayout.cells("b"));
                        let addImproveCatFormDP = new dataProcessor(Improve("improveCatForm"));
                        addImproveCatFormDP.init(addImproveCatForm);
                        addImproveCatForm.save();

                        addImproveCatFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "inserted":
                                    sAlert("Berhasil Menambahkan Record <br>" + message);
                                    rImproveCatGrid();
                                    clearAllForm(addImproveCatForm);
                                    setEnable(["add", "clear"], addImproveCatForm, improveCatLayout.cells("b"));
                                    break;
                                case "error":
                                    eAlert("Gagal Menambahkan Record <br>" + message);
                                    setEnable(["add", "clear"], addImproveCatForm, improveCatLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "clear":
                        clearAllForm(addImproveCatForm);
                        break;
                    case "cancel":
                        rImproveCatGrid();
                        improveCatLayout.cells("b").collapse();
                        break;
                }
            });
        }

        function editImproveCatHandler() {
            if (!improveCatGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diubah!");
            }

            improveCatLayout.cells("a").progressOff();
            improveCatLayout.cells("b").expand();
            improveCatLayout.cells("b").showView("edit_improve_cat");
            editImproveCatForm = improveCatLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Ubah Kategori", list: [
                    {type: "hidden", name: "id", label: "ID", readonly: true},
                    {type: "input", name: "name", label: "Nama Kategori", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            fetchFormData(Improve("improveCatForm", {id: improveCatGrid.getSelectedRowId()}), editImproveCatForm);

            editImproveCatForm.attachEvent("onButtonClick", function(name) {
                switch (name) {
                    case "update":
                        if (!editImproveCatForm.validate()) {
                            return eAlert("Input error!");
                        }	
                        
                        setDisable(["update", "cancel"], editImproveCatForm, improveCatLayout.cells("b"));
                        let editImproveCatFormDP = new dataProcessor(Improve("improveCatForm"));
                        editImproveCatFormDP.init(editImproveCatForm);
                        editImproveCatForm.save();

                        editImproveCatFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rImproveCatGrid();
                                    improveCatLayout.cells("b").progressOff();
                                    improveCatLayout.cells("b").showView("tambah_improve_cat");
                                    improveCatLayout.cells("b").collapse();
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record <br>" + message);
                                    setEnable(["update", "cancel"], editImproveCatForm, improveCatLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "cancel":
                        improveCatLayout.cells("b").collapse();
                        improveCatLayout.cells("b").showView("tambah_improve_cat");
                        break;
                }
            });
        }
    }

JS;
header('Content-Type: application/javascript');
echo $script;