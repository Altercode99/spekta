<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	
    function showDetCat() {	
        var addDetCatForm;
        var editDetCatForm;

        var detCatLayout = mainTab.cells("master_detective_categories").attachLayout({
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

        var detCatToolbar = mainTab.cells("master_detective_categories").attachToolbar({
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

        var detCatStatusBar = detCatLayout.cells("a").attachStatusBar();
        function detCatGridCount() {
            detCatStatusBar.setText("Total baris: " + detCatGrid.getRowsNum());
        }

        var detCatGrid = detCatLayout.cells("a").attachGrid();
        detCatGrid.setHeader("No,Nama Kategori,Created By,Updated By,DiBuat");
        detCatGrid.attachHeader("#rspan,#text_filter,#select_filter,#select_filter,#text_filter")
        detCatGrid.setColSorting("int,str,str,str,str");
        detCatGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt");
        detCatGrid.setColAlign("center,left,left,left,left");
        detCatGrid.setInitWidthsP("5,30,20,20,25");
        detCatGrid.enableSmartRendering(true);
        detCatGrid.enableMultiselect(true);
        detCatGrid.attachEvent("onXLE", function() {
            detCatLayout.cells("a").progressOff();
        });
        detCatGrid.init();
        
        function rDetCatGrid() {
            detCatLayout.cells("a").progressOn();
            detCatGrid.clearAndLoad(Improve("getDetCategories", {search: detCatToolbar.getValue("search")}), detCatGridCount);
        };

        rDetCatGrid();

        detCatToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    detCatToolbar.setValue("search","");
                    rDetCatGrid();
                    break;
                case "add":
                    addDetCatHandler();
                    break;
                case "delete":
                    reqAction(detCatGrid, Improve("detCatDelete"), 1, (err, res) => {
                        rDetCatGrid();
                        res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                        res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
                    });
                    break;
                case "edit":
                    editDetCatHandler();
                    break;
            }
        });

        function addDetCatHandler() {
            detCatLayout.cells("b").expand();
            detCatLayout.cells("b").showView("tambah_det_cat");

            addDetCatForm = detCatLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Sub Bagian", list: [
                    {type: "input", name: "name", label: "Kategori Ide", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                        {type: "newcolumn"},
                        {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            addDetCatForm.attachEvent("onButtonClick", function (name) {
                switch (name) {
                    case "add":
                        if (!addDetCatForm.validate()) {
                            return eAlert("Input error!");
                        }

                        setDisable(["add", "clear"], addDetCatForm, detCatLayout.cells("b"));
                        let addDetCatFormDP = new dataProcessor(Improve("detCatForm"));
                        addDetCatFormDP.init(addDetCatForm);
                        addDetCatForm.save();

                        addDetCatFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "inserted":
                                    sAlert("Berhasil Menambahkan Record <br>" + message);
                                    rDetCatGrid();
                                    clearAllForm(addDetCatForm);
                                    setEnable(["add", "clear"], addDetCatForm, detCatLayout.cells("b"));
                                    break;
                                case "error":
                                    eAlert("Gagal Menambahkan Record <br>" + message);
                                    setEnable(["add", "clear"], addDetCatForm, detCatLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "clear":
                        clearAllForm(addDetCatForm);
                        break;
                    case "cancel":
                        rDetCatGrid();
                        detCatLayout.cells("b").collapse();
                        break;
                }
            });
        }

        function editDetCatHandler() {
            if (!detCatGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diubah!");
            }

            detCatLayout.cells("a").progressOff();
            detCatLayout.cells("b").expand();
            detCatLayout.cells("b").showView("edit_det_cat");
            editDetCatForm = detCatLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Ubah Sub Bagian", list: [
                    {type: "hidden", name: "id", label: "ID", readonly: true},
                    {type: "input", name: "name", label: "Nama Sub Bagian", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            fetchFormData(Improve("detCatForm", {id: detCatGrid.getSelectedRowId()}), editDetCatForm);

            editDetCatForm.attachEvent("onButtonClick", function(name) {
                switch (name) {
                    case "update":
                        if (!editDetCatForm.validate()) {
                            return eAlert("Input error!");
                        }	
                        
                        setDisable(["update", "cancel"], editDetCatForm, detCatLayout.cells("b"));
                        let editDetCatFormDP = new dataProcessor(Improve("detCatForm"));
                        editDetCatFormDP.init(editDetCatForm);
                        editDetCatForm.save();

                        editDetCatFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rDetCatGrid();
                                    detCatLayout.cells("b").progressOff();
                                    detCatLayout.cells("b").showView("tambah_det_cat");
                                    detCatLayout.cells("b").collapse();
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record <br>" + message);
                                    setEnable(["update", "cancel"], editDetCatForm, detCatLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "cancel":
                        detCatLayout.cells("b").collapse();
                        detCatLayout.cells("b").showView("tambah_det_cat");
                        break;
                }
            });
        }
    }

JS;
header('Content-Type: application/javascript');
echo $script;