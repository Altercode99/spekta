<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	
	function showPMachine() {	
        var addMacineForm;
        var editMachineForm;
        var fileError;
        var totalFile;

        var comboUrl = {
            building_id: {
                url: AppMaster("getBuilding"),
                reload: true
            },
            room_id: {
                url: AppMaster("getBuildingRoom"),
                reload: false
            },
            department_id: {
                url: Emp("getDepartment"),
                reload: true
            },
            sub_department_id: {
                url: Emp("getSubDepartment"),
                reload: false
            },
            division_id: {
                url: Emp("getDivision"),
                reload: false
            }
        }

        var machineLayout = mainTab.cells("master_production_machine").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form Mesin Produksi",
                    header: true,
                    collapse: true
                }
            ]
        });

        var machineToolbar = mainTab.cells("master_production_machine").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "add", text: "Tambah", type: "button", img: "add.png"},
                {id: "delete", text: "Hapus", type: "button", img: "delete.png"},
                {id: "edit", text: "Ubah", type: "button", img: "edit.png", img_disabled: "edit_disabled.png"},
                {id: "export", text: "Export To Excel", type: "button", img: "excel.png"},
                {id: "searchtext", text: "Cari : ", type: "text"},
                {id: "search", text: "", type: "buttonInput", width: 150}
            ]
        });

        if(userLogged.role !== "admin") {
            machineToolbar.disableItem("delete");
        }

        var machineMenu =  mainTab.cells("master_production_machine").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "update", text: "Update Grid", img: "update.png"}
            ]
        });

        var machineStatusBar = machineLayout.cells("a").attachStatusBar();
        function machineGridCount() {
            let machineGridRows = machineGrid.getRowsNum();
            machineStatusBar.setText("Total baris: " + machineGridRows);
        }

        var machineGrid = machineLayout.cells("a").attachGrid();
        machineGrid.setHeader("No,Nama Mesin,Gedung,Ruangan,Sub Unit,Bagian,Sub Bagian,Dimensi,Created By,Updated By,DiBuat");
        machineGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        machineGrid.setColSorting("str,str,str,str,str,str,str,str,str,str,str");
        machineGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        machineGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left");
        machineGrid.setInitWidthsP("5,25,20,20,15,20,20,15,15,15,25");
        machineGrid.enableSmartRendering(true);
        machineGrid.enableMultiselect(true);
        machineGrid.setEditable(true);
        machineGrid.attachEvent("onXLE", function() {
            machineLayout.cells("a").progressOff();
        });
        machineGrid.init();

        function setGridDP() {
            machineGridDP = new dataProcessor(AppMaster('updateProdMachineBatch'));
            machineGridDP.setTransactionMode("POST", true);
            machineGridDP.setUpdateMode("Off");
            machineGridDP.init(machineGrid);
        }

        setGridDP();

        function rMachineGrid() {
            machineLayout.cells("a").progressOn();
            machineGrid.clearAndLoad(AppMaster("prodMachineGrid", {search: machineToolbar.getValue("search")}), machineGridCount);
        }

        rMachineGrid();

        machineToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    machineToolbar.setValue("search","");
                    rMachineGrid();
                    break;
                case "add":
                    addMachineHandler();
                    break;
                case "delete":
                    deleteMachineHandler();
                    break;
                case "edit":
                    editMachineHandler();
                    break;
                case "export":
                    machineGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    sAlert("Export Data Dimulai");
                    break;
            }
        });


        machineToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    rMachineGrid();
                    machineGrid.attachEvent("onGridReconstructed", machineGridCount);
                    break;
            }
        });

        machineMenu.attachEvent("onClick", function(id) {
            switch (id) {
                case "update":
                    if(!machineGrid.getChangedRows()) {
                        return eAlert("Belum ada row yang di edit!");
                    }

                    machineMenu.setItemDisabled("update");
                    machineLayout.cells("a").progressOn();
                    machineGridDP.sendData();
                    machineGridDP.attachEvent('onAfterUpdate', function(id, action, tid, tag) {
                        let message = tag.getAttribute('message');
                        switch (action) {
                            case 'updated':
                                sAlert(message);
                                rMachineGrid();
                                machineMenu.setItemEnabled("update");
                                machineLayout.cells("a").progressOff();
                                setGridDP();
                                break;
                            case 'error':
                                eAlert(message);
                                machineMenu.setItemEnabled("update");
                                machineLayout.cells("a").progressOff();
                                break;
                        }
                    });
                    break;
            }
        });

        function deleteMachineHandler() {
            reqAction(machineGrid, AppMaster("prodMachineDelete"), 1, (err, res) => {
                rMachineGrid();
                res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
            });
        }

        function addMachineHandler() {
            machineLayout.cells("b").expand();
            machineLayout.cells("b").showView("tambah_mesin_produksi");

            addMachineForm = machineLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Mesin Produksi", list: [
                    {type: "input", name: "name", label: "Nama Mesin", labelWidth: 130, inputWidth:250, required: true},
                    {type: "combo", name: "building_id", label: "Nama Gedung", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "combo", name: "room_id", label: "Nama Ruangan", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "combo", name: "department_id", label: "Sub Unit", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "combo", name: "sub_department_id", label: "Bagian", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "combo", name: "division_id", label: "Sub Bagian", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "input", name: "dimension", label: "Dimensi", labelWidth: 130, inputWidth: 250},
                    {type: "hidden", name: "filename", label: "Filename", readonly: true},
                    {type: "upload", name: "file_uploader", inputWidth: 420,
                        url: AppMaster("fileUpload", {save: false, folder: "production_machines"}), 
                        swfPath: "./public/codebase/ext/uploader.swf", 
                        swfUrl: AppMaster("fileUpload")
                    },
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                        {type: "newcolumn"},
                        {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            var addBuildCombo = addMachineForm.getCombo("building_id");
            addBuildCombo.load(AppMaster("getBuilding"));
            addBuildCombo.attachEvent("onChange", function(value, text){
                clearComboReload(addMachineForm, "room_id", AppMaster("getBuildingRoom", {buildId: value}));
            });

            var addDeptCombo = addMachineForm.getCombo("department_id");
            var addSubCombo = addMachineForm.getCombo("sub_department_id");

            addDeptCombo.load(Emp("getDepartment"));
            addDeptCombo.attachEvent("onChange", function(value, text){
                clearComboReload(addMachineForm, "sub_department_id", Emp("getSubDepartment", {deptId: value}));
            });

            addSubCombo.attachEvent("onChange", function(value, text){
                clearComboReload(addMachineForm, "division_id", Emp("getDivision", {subDeptId: value}));
            });

            addMachineForm.attachEvent("onBeforeFileAdd", async function (filename, size) {
                beforeFileAdd(addMachineForm, {filename, size});
            });

            addMachineForm.attachEvent("onBeforeFileUpload", function(mode, loader, formData){
                if(fileError) {
                    clearUploader(addMachineForm, "file_uploader");
                    eAlert("File error silahkan upload file sesuai ketentuan!");
                    fileError = false;
                } else {
                    return true;
                }
            });

            addMachineForm.attachEvent("onButtonClick", function(id) {
                switch (id) {
                    case "add":
                        const uploader = addMachineForm.getUploader("file_uploader");
                        if(uploader.getStatus() === -1) {
                            if(!fileError) {
                                uploader.upload();
                            } else {
                                uploader.clear();
                                eAlert("File error silahkan upload file sesuai ketentuan!");
                                fileError = false;
                            }
                        } else {
                            addMachineFormSubmit();
                        }
                        break;
                    case "clear":
                        clearAllForm(addMachineForm, comboUrl);
                        break;
                    case "cancel":
                        machineLayout.cells("b").collapse();
                        break;
                }
            });

            addMachineForm.attachEvent("onUploadFile", function(filename, servername){
                addMachineForm.setItemValue("filename", servername);
                addMachineFormSubmit();
            });

            function addMachineFormSubmit() {
                if(!addMachineForm.validate()) {
                    return eAlert("Input error!");
                }

                setDisable(["add", "clear"], addMachineForm, machineLayout.cells("b"));

                let addMachineFormDP = new dataProcessor(AppMaster("prodMachineForm"));
                addMachineFormDP.init(addMachineForm);
                addMachineForm.save();

                addMachineFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                    let message = tag.getAttribute("message");
                    switch (action) {
                        case "inserted":
                            sAlert("Berhasil Menambahkan Record <br>" + message);
                            rMachineGrid();
                            clearAllForm(addMachineForm, comboUrl);
                            clearUploader(addMachineForm, "file_uploader");
                            setEnable(["add", "clear"], addMachineForm, machineLayout.cells("b"));
                            break;
                        case "error":
                            eAlert("Gagal Menambahkan Record <br>" + message);
                            setEnable(["add", "clear"], addMachineForm, machineLayout.cells("b"));
                            break;
                    }
                });
            }
        }

        function editMachineHandler() {
            if(!machineGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diubah!");
            }

            machineLayout.cells("b").expand();
            machineLayout.cells("b").showView("edit_mesin_produksi");

            editMachineForm = machineLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Edit Ruang Meeting", list: [
                    {type: "hidden", name: "id", label: "ID", readonly: true},
                    {type: "input", name: "name", label: "Nama Mesin", labelWidth: 130, inputWidth:250, required: true},
                    {type: "combo", name: "building_id", label: "Nama Gedung", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "combo", name: "room_id", label: "Nama Ruangan", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "combo", name: "department_id", label: "Sub Unit", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "combo", name: "sub_department_id", label: "Bagian", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "combo", name: "division_id", label: "Sub Bagian", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "input", name: "dimension", label: "Dimensi", labelWidth: 130, inputWidth: 250},
                    {type: "hidden", name: "filename", label: "Filename", readonly: true},
                    {type: "upload", name: "file_uploader", inputWidth: 420,
                        url: AppMaster("fileUpload", {save: false, folder: "production_machines"}), 
                        swfPath: "./public/codebase/ext/uploader.swf", 
                        swfUrl: AppMaster("fileUpload"),
                        autoStart: true
                    },
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]},
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Foto Mesin Produksi", list: [
                    {type: "container", name : "file_display", label: "<img src='./public/img/no-image.png' height='120' width='120'>"}
                ]},
            ]);

            const loadTemp = (filename) => {
                if (filename.length === 0) {
                    editMachineForm.setItemLabel("file_display", "<img src='./public/img/no-image.png' height='120' width='120'>");
                } else {
                    filename.map(file => {
                        if(file === '') {
                            editMachineForm.setItemLabel("file_display", "<img src='./public/img/no-image.png' height='120' width='120'>");
                        } else {
                            var fotoDisplay = "<img src='./assets/images/production_machines/"+file+"' height='120' width='120'>"
                            editMachineForm.setItemLabel("file_display", fotoDisplay);
                        }
                    });
                }	
            }
            
            var editBuildCombo = editMachineForm.getCombo("building_id");
            var editBuildRoomCombo = editMachineForm.getCombo("room_id");
            var editDeptCombo = editMachineForm.getCombo("department_id");
            var editSubDeptCombo = editMachineForm.getCombo("sub_department_id");
            var editDivCombo = editMachineForm.getCombo("division_id");
            fetchFormData(AppMaster("prodMachineForm", {id: machineGrid.getSelectedRowId()}), editMachineForm, ["filename"], loadTemp, afterFetchForm);

            function afterFetchForm() {
                editBuildCombo.load(AppMaster("getBuilding", {select: editMachineForm.getItemValue("building_id")}));
                editBuildRoomCombo.load(AppMaster("getBuildingRoom", {buildId: editMachineForm.getItemValue("building_id"), select: editMachineForm.getItemValue("room_id")}));
                editDeptCombo.load(Emp("getDepartment", {select: editMachineForm.getItemValue("department_id")}));
                editSubDeptCombo.load(Emp("getSubDepartment", {deptId: editMachineForm.getItemValue("department_id"), select: editMachineForm.getItemValue("sub_department_id")}));
                editDivCombo.load(Emp("getDivision", {subDeptId: editMachineForm.getItemValue("sub_department_id"), select: editMachineForm.getItemValue("division_id")}));

                editBuildCombo.attachEvent("onChange", function(value, text){
                    clearComboReload(editMachineForm, "room_id", AppMaster("getBuildingRoom", {buildId: value, select: editMachineForm.getItemValue("room_id")}));
                });
            
                editDeptCombo.attachEvent("onChange", function(value, text){
                    clearComboReload(editMachineForm, "sub_department_id", Emp("getSubDepartment", {deptId: value, select: editMachineForm.getItemValue("sub_department_id")}));
                });

                editSubDeptCombo.attachEvent("onChange", function(value, text){
                    clearComboReload(editMachineForm, "division_id", Emp("getDivision", {subDeptId: value, select: editMachineForm.getItemValue("division_id")}));
                });
            }

            function loadDivision() {
                clearComboSet(editMachineForm, "division_id", Emp("getDivision", {subDeptId: editMachineForm.getItemValue("sub_department_id")}), editMachineForm.getItemValue("division_id"));
                second++;
            }


            editMachineForm.attachEvent("onBeforeFileAdd", async function (filename, size) {
                beforeFileAdd(editMachineForm, {filename, size}, editMachineForm.getItemValue("id"));
            });

            editMachineForm.attachEvent("onBeforeFileUpload", function(mode, loader, formData){
                if(fileError) {
                    clearUploader(editMachineForm, "file_uploader");
                    eAlert("File error silahkan upload file sesuai ketentuan!");
                    fileError = false;
                } else {
                    return true;
                }
            });

            editMachineForm.attachEvent("onUploadFile", function(filename, servername){
                reqJson(AppMaster("updateAfterUpload"), "POST", {
                    id: editMachineForm.getItemValue("id"),
                    oldFile: editMachineForm.getItemValue("filename"),
                    filename: servername,
                    folder: "kf_maintenance.production_machines"
                }, (err, res) => {
                    if(res.status === "success") {
                        editMachineForm.setItemValue("filename", servername);
                        clearUploader(editMachineForm, "file_uploader");
                        editMachineForm.setItemLabel("file_display", "<img src='./assets/images/production_machines/"+servername+"' height='120' width='120'>");
                        sAlert(res.message);
                    } else {
                        eAlert(res.message);
                    }
                });
            });     

            editMachineForm.attachEvent("onButtonClick", function(id) {
                switch (id) {
                    case "update":
                        setDisable(["update", "cancel"], editMachineForm, machineLayout.cells("b"));

                        let editMachineFormDP = new dataProcessor(AppMaster("prodMachineForm"));
                        editMachineFormDP.init(editMachineForm);
                        editMachineForm.save();

                        editMachineFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                        let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rMachineGrid();
                                    machineLayout.cells("b").progressOff();
                                    machineLayout.cells("b").showView("tambah_mesin_produksi");
                                    machineLayout.cells("b").collapse();
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record <br>" + message);
                                    setEnable(["update", "cancel"], editMachineForm, machineLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "cancel":
                        machineLayout.cells("b").collapse();
                        machineLayout.cells("b").showView("tambah_mesin_produksi");
                        break;
                }
            });
        }

        async function beforeFileAdd(form, file, id = null) {
            if(form.validate()) {
                var ext = file.filename.split(".").pop();
                if (ext == "png" || ext == "jpg" || ext == "jpeg") {
                    if (file.size > 1000000) {
                        fileError = true;
                        eAlert("Tidak boleh melebihi 1 MB!");
                    } else {
                        if(totalFile > 0) {
                            eAlert("Maksimal 1 file");
                            fileError = true;
                        } else {
                            const data = {
                                id,
                                name: form.getItemValue("name"),
                                building_id: form.getItemValue("building_id"),
                                room_id: form.getItemValue("room_id"),
                                department_id: form.getItemValue("department_id"),
                                sub_department_id: form.getItemValue("sub_department_id"),
                                division_id: form.getItemValue("division_id"),
                            }

                            const checkMachine = await reqJsonResponse(AppMaster("checkBeforeAddFile3"), "POST", data);

                            if(checkMachine) {
                                if(checkMachine.status === "success") {
                                    totalFile++;
                                    return true;
                                } else if(checkMachine.status === "deleted") {
                                    fileError = false;
                                    totalFile= 0;
                                    machineLayout.cells("b").collapse();
                                    machineLayout.cells("b").showView("tambah_mesin_produksi");
                                } else {
                                    eAlert(checkMachine.message);
                                    fileError = true;
                                }
                            }
                        }
                    }		    
                } else {
                    eAlert("Hanya png, jpg & jpeg saja yang bisa diupload!");
                    fileError = true;
                }
            } else {
                eAlert("Input error!");
            }	
        }
    }

JS;

header('Content-Type: application/javascript');
echo $script;
