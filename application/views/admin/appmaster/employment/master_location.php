<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	
	function showMasterLocation() {	
        var addLocForm;
        var editLocForm;

        var locLayout = mainTab.cells("master_location").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form Lokasi",
                    header: true,
                    collapse: true
                }
            ]
        });

        var locToolbar = mainTab.cells("master_location").attachToolbar({
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
            locToolbar.disableItem("add");
            locToolbar.disableItem("delete");
        }

        var locStatusBar = locLayout.cells("a").attachStatusBar();
        function locGridCount() {
            let locGridRows = locGrid.getRowsNum();
            locStatusBar.setText("Total baris: " + locGridRows);
        }

        var locGrid = locLayout.cells("a").attachGrid();
        locGrid.setHeader("No,Kode,Lokasi,Created By,Updated By,DiBuat");
        locGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        locGrid.setColSorting("str,str,str,str,str,str");
        locGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        locGrid.setColAlign("center,left,left,left,left,left");
        locGrid.setInitWidthsP("5,20,20,15,15,25");
        locGrid.enableSmartRendering(true);
        locGrid.enableMultiselect(true);
        locGrid.attachEvent("onXLE", function() {
            locLayout.cells("a").progressOff();
        });
        locGrid.init();
        
        function rLocGrid() {
            locLayout.cells("a").progressOn();
            locGrid.clearAndLoad(AppMaster("locGrid", {search: locToolbar.getValue("search")}), locGridCount);
        }

        rLocGrid();

        locToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    locToolbar.setValue("search","");
                    rLocGrid();
                    break;
                case "add":
                    addLocHandler();
                    break;
                case "delete":
                    deleteLocHandler();
                    break;
                case "edit":
                    editLocHandler();
                    break;
            }
        });

        locToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    rLocGrid();
                    locGrid.attachEvent("onGridReconstructed", locGridCount);
                    break;
            }
        });

        function deleteLocHandler() {
            reqAction(locGrid, AppMaster("locDelete"), 1, (err, res) => {
                rLocGrid();
                res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
            });
        }

        function addLocHandler() {
            locLayout.cells("b").expand();
            locLayout.cells("b").showView("tambah_location");

            addLocForm = locLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Lokasi", list: [
                    {type: "input", name: "code", label: "Kode Lokasi", labelWidth: 130, inputWidth:250, required: true},
                    {type: "input", name: "name", label: "Nama Lokasi", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                        {type: "newcolumn"},
                        {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            addLocForm.attachEvent("onButtonClick", function (name) {
                switch (name) {
                    case "add":
                        if (!addLocForm.validate()) {
                            return eAlert("Input error!");
                        }

                        setDisable(["add", "clear"], addLocForm, locLayout.cells("b"));
                        let addLocFormDP = new dataProcessor(AppMaster("locForm"));
                        addLocFormDP.init(addLocForm);
                        addLocForm.save();

                        addLocFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "inserted":
                                    sAlert("Berhasil Menambahkan Record <br>" + message);
                                    rLocGrid();
                                    clearAllForm(addLocForm);
                                    setEnable(["add", "clear"], addLocForm, locLayout.cells("b"));
                                    break;
                                case "error":
                                    eAlert("Gagal Menambahkan Record <br>" + message);
                                    setEnable(["add", "clear"], addLocForm, locLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "clear":
                        clearAllForm(addLocForm);
                        break;
                    case "cancel":
                        rLocGrid();
                        locLayout.cells("b").collapse();
                        break;
                }
            });
        }

        function editLocHandler() {
            if (!locGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diubah!");
            }

            locLayout.cells("b").expand();
            locLayout.cells("b").showView("edit_loc");
            editLocForm = locLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Ubah Lokasi", list: [
                    {type: "hidden", name: "id", label: "ID", readonly: true},
                    {type: "input", name: "code", label: "Kode Lokasi", labelWidth: 130, inputWidth:250, required: true, readonly: true},
                    {type: "input", name: "name", label: "Nama Lokasi", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            fetchFormData(AppMaster("locForm", {id: locGrid.getSelectedRowId()}), editLocForm);
            editLocForm.attachEvent("onButtonClick", function(name) {
                switch (name) {
                    case "update":
                        if (!editLocForm.validate()) {
                            return eAlert("Input error!");
                        }	

                        setDisable(["update", "cancel"], editLocForm, locLayout.cells("b"));
                        let editLocFormDP = new dataProcessor(AppMaster("locForm"));
                        editLocFormDP.init(editLocForm);
                        editLocForm.save();

                        editLocFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rLocGrid();	
                                    locLayout.cells("b").progressOff();
                                    locLayout.cells("b").showView("tambah_location");
                                    locLayout.cells("b").collapse();								
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record<br>" + message);
                                    setEnable(["update", "cancel"], editLocForm, locLayout.cells("b"));
                                    break;
                            }
                        });									
                        break;
                    case "cancel":
                        locLayout.cells("b").collapse();
                        locLayout.cells("b").showView("tambah_location");
                        break;
                }
            });
        }

    }
JS;

header('Content-Type: application/javascript');
echo $script;

