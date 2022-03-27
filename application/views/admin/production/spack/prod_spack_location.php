<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
    		
    function showSpackLocation() {
        var addSpLocForm;
        var editSpLocForm;

        var spLocLayout = mainTab.cells("prod_spack_location").attachLayout({
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

        var spLocToolbar = mainTab.cells("prod_spack_location").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "add", text: "Tambah", type: "button", img: "add.png"},
                {id: "delete", text: "Hapus", type: "button", img: "delete.png"},
                {id: "edit", text: "Ubah", type: "button", img: "edit.png"},
                {id: "searchtext", text: "Cari : ", type: "text"},
                {id: "search", text: "", type: "buttonInput", width: 150}
            ]
        });

        if(userLogged.role !== "admin") {
            spLocToolbar.disableItem("add");
            spLocToolbar.disableItem("delete");
        }


        var spLocStatusBar = spLocLayout.cells("a").attachStatusBar();
        function spLocGridCount() {
            spLocStatusBar.setText("Total baris: " + spLocGrid.getRowsNum());
        }

        var spLocGrid = spLocLayout.cells("a").attachGrid();
        spLocGrid.setHeader("No,Lokasi,Created By,Updated By,DiBuat");
        spLocGrid.attachHeader("#rspan,#text_filter,#select_filter,#select_filter,#text_filter")
        spLocGrid.setColSorting("int,str,str,str,str");
        spLocGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt");
        spLocGrid.setColAlign("center,left,left,left,left");
        spLocGrid.setInitWidthsP("5,33,20,20,22");
        spLocGrid.enableSmartRendering(true);
        spLocGrid.enableMultiselect(true);
        spLocGrid.attachEvent("onXLE", function() {
            spLocLayout.cells("a").progressOff();
        });
        spLocGrid.init();
        
        function rSpLocGrid() {
            spLocLayout.cells("a").progressOn();
            spLocGrid.clearAndLoad(Production("getSpLocGrid", {search: spLocToolbar.getValue("search")}), spLocGridCount);
        }

        rSpLocGrid();

        spLocToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    spLocToolbar.setValue("search","");
                    rSpLocGrid();
                    break;
                case "add":
                    addSpLocHandler();
                    break;
                case "delete":
                    deleteSpLocHandler();
                    break;
                case "edit":
                    editSpLocHandler();
                    break;
            }
        });

        spLocToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    rSpLocGrid();
                    spLocGrid.attachEvent("onGridReconstructed", spLocGridCount);
                    break;
            }
        });

        function deleteSpLocHandler() {
            reqAction(spLocGrid, Production("spLocDelete"), 1, (err, res) => {
                rSpLocGrid();
                res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
            });
        }

        function addSpLocHandler() {
            spLocLayout.cells("b").expand();
            spLocLayout.cells("b").showView("tambah_spack_location");

            addSpLocForm = spLocLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Lokasi", list: [
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

            addSpLocForm.attachEvent("onButtonClick", function (name) {
                switch (name) {
                    case "add":
                        if (!addSpLocForm.validate()) {
                            return eAlert("Input error!");
                        }

                        setDisable(["add", "clear"], addSpLocForm, spLocLayout.cells("b"));
                        let addSpLocFormDP = new dataProcessor(Production("spLocForm"));
                        addSpLocFormDP.init(addSpLocForm);
                        addSpLocForm.save();

                        addSpLocFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "inserted":
                                    sAlert("Berhasil Menambahkan Record <br>" + message);
                                    rSpLocGrid();
                                    clearAllForm(addSpLocForm);
                                    setEnable(["add", "clear"], addSpLocForm, spLocLayout.cells("b"));
                                    break;
                                case "error":
                                    eAlert("Gagal Menambahkan Record <br>" + message);
                                    setEnable(["add", "clear"], addSpLocForm, spLocLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "clear":
                        clearAllForm(addSpLocForm);
                        break;
                    case "cancel":
                        rSpLocGrid();
                        spLocLayout.cells("b").collapse();
                        break;
                }
            });
        }

        function editSpLocHandler() {
            if (!spLocGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diubah!");
            }

            spLocLayout.cells("b").expand();
            spLocLayout.cells("b").showView("edit_spack_location");
            editSpLocForm = spLocLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Ubah Lokasi", list: [
                    {type: "hidden", name: "id", label: "ID", readonly: true},
                    {type: "input", name: "name", label: "Nama Lokasi", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            fetchFormData(Production("spLocForm", {id: spLocGrid.getSelectedRowId()}), editSpLocForm);
            editSpLocForm.attachEvent("onButtonClick", function(name) {
                switch (name) {
                    case "update":
                        if (!editSpLocForm.validate()) {
                            return eAlert("Input error!");
                        }	

                        setDisable(["update", "cancel"], editSpLocForm, spLocLayout.cells("b"));
                        let editSpLocFormDP = new dataProcessor(Production("spLocForm"));
                        editSpLocFormDP.init(editSpLocForm);
                        editSpLocForm.save();

                        editSpLocFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rSpLocGrid();	
                                    spLocLayout.cells("b").progressOff();
                                    spLocLayout.cells("b").showView("edit_spack_location");
                                    spLocLayout.cells("b").collapse();								
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record<br>" + message);
                                    setEnable(["update", "cancel"], editSpLocForm, spLocLayout.cells("b"));
                                    break;
                            }
                        });									
                        break;
                    case "cancel":
                        spLocLayout.cells("b").collapse();
                        spLocLayout.cells("b").showView("edit_spack_location");
                        break;
                }
            });
        }
    }
JS;

header('Content-Type: application/javascript');
echo $script;