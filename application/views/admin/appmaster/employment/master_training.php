<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	
	function showMasterTraining() {	
        var addTrainingForm;
        var editTrainingForm;

        var trainingLayout = mainTab.cells("master_training").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form Jenis Training",
                    header: true,
                    collapse: true
                }
            ]
        });

        var trainingToolbar = mainTab.cells("master_training").attachToolbar({
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
            trainingToolbar.disableItem("add");
            trainingToolbar.disableItem("delete");
        }

        var trainingStatusBar = trainingLayout.cells("a").attachStatusBar();
        function trainingGridCount() {
            let trainingGridRows = trainingGrid.getRowsNum();
            trainingStatusBar.setText("Total baris: " + trainingGridRows);
        }

        var trainingGrid = trainingLayout.cells("a").attachGrid();
        trainingGrid.setHeader("No,Nama Training,Created By,Updated By,DiBuat");
        trainingGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#text_filter")
        trainingGrid.setColSorting("str,str,str,str,str");
        trainingGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt");
        trainingGrid.setColAlign("center,left,left,left,left");
        trainingGrid.setInitWidthsP("5,25,22,23,25");
        trainingGrid.enableSmartRendering(true);
        trainingGrid.enableMultiselect(true);
        trainingGrid.attachEvent("onXLE", function() {
            trainingLayout.cells("a").progressOff();
        });
        trainingGrid.init();
        
        function rTrainingGrid() {
            trainingLayout.cells("a").progressOn();
            trainingGrid.clearAndLoad(AppMaster("trainingGrid", {search: trainingToolbar.getValue("search")}), trainingGridCount);
        }

        rTrainingGrid();

        trainingToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    trainingToolbar.setValue("search","");
                    rTrainingGrid();
                    break;
                case "add":
                    addTrainingHandler();
                    break;
                case "delete":
                    deleteTrainingHandler();
                    break;
                case "edit":
                    editTrainingHandler();
                    break;
            }
        });

        trainingToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    rTrainingGrid();
                    trainingGrid.attachEvent("onGridReconstructed", trainingGridCount);
                    break;
            }
        });

        function deleteTrainingHandler() {
            reqAction(trainingGrid, AppMaster("trainingDelete"), 1, (err, res) => {
                rTrainingGrid();
                res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
            });
        }

        function addTrainingHandler() {
            trainingLayout.cells("b").expand();
            trainingLayout.cells("b").showView("tambah_training");

            addTrainingForm = trainingLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Jenis Training", list: [
                    {type: "input", name: "name", label: "Nama Training", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                        {type: "newcolumn"},
                        {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);
            
            addTrainingForm.attachEvent("onButtonClick", function (name) {
                switch (name) {
                    case "add":
                        if (!addTrainingForm.validate()) {
                            return eAlert("Input error!");
                        }

                        setDisable(["add", "clear"], addTrainingForm, trainingLayout.cells("b"));
                        let addTrainingFormDP = new dataProcessor(AppMaster("trainingForm"));
                        addTrainingFormDP.init(addTrainingForm);
                        addTrainingForm.save();

                        addTrainingFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "inserted":
                                    sAlert("Berhasil Menambahkan Record <br>" + message);
                                    rTrainingGrid();
                                    clearAllForm(addTrainingForm);
                                    setEnable(["add", "clear"], addTrainingForm, trainingLayout.cells("b"));
                                    break;
                                case "error":
                                    eAlert("Gagal Menambahkan Record <br>" + message);
                                    setEnable(["add", "clear"], addTrainingForm, trainingLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "clear":
                        clearAllForm(addTrainingForm);
                        break;
                    case "cancel":
                        trainingLayout.cells("b").collapse();
                        break;
                }
            });
        }

        function editTrainingHandler() {
            if (!trainingGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diubah!");
            }

            trainingLayout.cells("b").expand();
            trainingLayout.cells("b").showView("edit_training");
            editTrainingForm = trainingLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Ubah Jenis Training", list: [
                    {type: "hidden", name: "id", label: "ID", readonly: true},
                    {type: "input", name: "name", label: "Nama Training", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            fetchFormData(AppMaster("trainingForm", {id: trainingGrid.getSelectedRowId()}), editTrainingForm);
            editTrainingForm.attachEvent("onButtonClick", function(name) {
                switch (name) {
                    case "update":
                        if (!editTrainingForm.validate()) {
                            return eAlert("Input error!");
                        }		

                        setDisable(["update", "cancel"], editTrainingForm, trainingLayout.cells("b"));
                        let editTrainingFormDP = new dataProcessor(AppMaster("trainingForm"));
                        editTrainingFormDP.init(editTrainingForm);
                        editTrainingForm.save();

                        editTrainingFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rTrainingGrid();	
                                    trainingLayout.cells("b").progressOff();
                                    trainingLayout.cells("b").showView("tambah_training");
                                    trainingLayout.cells("b").collapse();
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record <br>" + message);
                                    setEnable(["update", "cancel"], editTrainingForm, trainingLayout.cells("b"));
                                    break;
                            }
                        });								
                        break;
                    case "cancel":
                        rTrainingGrid();
                        trainingLayout.cells("b").collapse();
                        trainingLayout.cells("b").showView("tambah_training");
                        break;
                }
            });
        }

    }
JS;

header('Content-Type: application/javascript');
echo $script;

