<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	
	function showMasterBuilding() {	
        var addBuildingForm;
        var editBuildingForm;

        var buildingLayout = mainTab.cells("master_building").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form Gedung",
                    header: true,
                    collapse: true
                }
            ]
        });

        var buildingToolbar = mainTab.cells("master_building").attachToolbar({
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
            buildingToolbar.disableItem("delete");
        }

        var buildingStatusBar = buildingLayout.cells("a").attachStatusBar();
        function buildingGridCount() {
            let buildingGridRows = buildingGrid.getRowsNum();
            buildingStatusBar.setText("Total baris: " + buildingGridRows);
        }

        var buildingGrid = buildingLayout.cells("a").attachGrid();
        buildingGrid.setHeader("No,Nama Gedung,Created By,Updated By,DiBuat");
        buildingGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#text_filter")
        buildingGrid.setColSorting("str,str,str,str,str");
        buildingGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt");
        buildingGrid.setColAlign("center,left,left,left,left");
        buildingGrid.setInitWidthsP("5,25,22,23,25");
        buildingGrid.enableSmartRendering(true);
        buildingGrid.enableMultiselect(true);
        buildingGrid.attachEvent("onXLE", function() {
            buildingLayout.cells("a").progressOff();
        });
        buildingGrid.init();
        
        function rBuildingGrid() {
            buildingLayout.cells("a").progressOn();
            buildingGrid.clearAndLoad(AppMaster("buildingGrid", {search: buildingToolbar.getValue("search")}), buildingGridCount);
        }

        rBuildingGrid();

        buildingToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    buildingToolbar.setValue("search","");
                    rBuildingGrid();
                    break;
                case "add":
                    addBuildingHandler();
                    break;
                case "delete":
                    deleteBuildingHandler();
                    break;
                case "edit":
                    editBuildingHandler();
                    break;
            }
        });

        buildingToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    rBuildingGrid();
                    buildingGrid.attachEvent("onGridReconstructed", buildingGridCount);
                    break;
            }
        });

        function deleteBuildingHandler() {
            reqAction(buildingGrid, AppMaster("buildingDelete"), 1, (err, res) => {
                rBuildingGrid();
                res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
            });
        }

        function addBuildingHandler() {
            buildingLayout.cells("b").expand();
            buildingLayout.cells("b").showView("tambah_building");

            addBuildingForm = buildingLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Gedung", list: [
                    {type: "input", name: "name", label: "Nama Gedung", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                        {type: "newcolumn"},
                        {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);
            
            addBuildingForm.attachEvent("onButtonClick", function (name) {
                switch (name) {
                    case "add":
                        if (!addBuildingForm.validate()) {
                            return eAlert("Input error!");
                        }

                        setDisable(["add", "clear"], addBuildingForm, buildingLayout.cells("b"));
                        let addBuildingFormDP = new dataProcessor(AppMaster("buildingForm"));
                        addBuildingFormDP.init(addBuildingForm);
                        addBuildingForm.save();

                        addBuildingFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "inserted":
                                    sAlert("Berhasil Menambahkan Record <br>" + message);
                                    rBuildingGrid();
                                    clearAllForm(addBuildingForm);
                                    setEnable(["add", "clear"], addBuildingForm, buildingLayout.cells("b"));
                                    break;
                                case "error":
                                    eAlert("Gagal Menambahkan Record <br>" + message);
                                    setEnable(["add", "clear"], addBuildingForm, buildingLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "clear":
                        clearAllForm(addBuildingForm);
                        break;
                    case "cancel":
                        buildingLayout.cells("b").collapse();
                        break;
                }
            });
        }

        function editBuildingHandler() {
            if (!buildingGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diubah!");
            }

            buildingLayout.cells("b").expand();
            buildingLayout.cells("b").showView("edit_building");
            editBuildingForm = buildingLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Ubah Gedung", list: [
                    {type: "hidden", name: "id", label: "ID", readonly: true},
                    {type: "input", name: "name", label: "Nama Gedung", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            fetchFormData(AppMaster("buildingForm", {id: buildingGrid.getSelectedRowId()}), editBuildingForm);
            editBuildingForm.attachEvent("onButtonClick", function(name) {
                switch (name) {
                    case "update":
                        if (!editBuildingForm.validate()) {
                            return eAlert("Input error!");
                        }		

                        setDisable(["update", "cancel"], editBuildingForm, buildingLayout.cells("b"));
                        let editBuildingFormDP = new dataProcessor(AppMaster("buildingForm"));
                        editBuildingFormDP.init(editBuildingForm);
                        editBuildingForm.save();

                        editBuildingFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rBuildingGrid();	
                                    buildingLayout.cells("b").progressOff();
                                    buildingLayout.cells("b").showView("tambah_building");
                                    buildingLayout.cells("b").collapse();
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record <br>" + message);
                                    setEnable(["update", "cancel"], editBuildingForm, buildingLayout.cells("b"));
                                    break;
                            }
                        });								
                        break;
                    case "cancel":
                        rBuildingGrid();
                        buildingLayout.cells("b").collapse();
                        buildingLayout.cells("b").showView("tambah_building");
                        break;
                }
            });
        }
    }

JS;

header('Content-Type: application/javascript');
echo $script;
