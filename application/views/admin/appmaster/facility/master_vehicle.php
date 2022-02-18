<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	
	function showVehicle() {	
        var addVehicleForm;
        var editVehicleForm;
        var fileError;
        var totalFile;

        var vehicleLayout = mainTab.cells("master_vehicle").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form Kendaraan Inventaris",
                    header: true,
                    collapse: true
                }
            ]
        });

        var vehicleToolbar = mainTab.cells("master_vehicle").attachToolbar({
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
            vehicleToolbar.disableItem("delete");
        }

        var vehicleMenu =  mainTab.cells("master_vehicle").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "update", text: "Update Grid", img: "update.png"}
            ]
        });

        var vehicleStatusBar = vehicleLayout.cells("a").attachStatusBar();
        function vehicleGridCount() {
            let vehicleGridRows = vehicleGrid.getRowsNum();
            vehicleStatusBar.setText("Total baris: " + vehicleGridRows);
        }

        var vehicleGrid = vehicleLayout.cells("a").attachGrid();
        vehicleGrid.setHeader("No,Nama Kendaraan,Merk/Brand,Tipe,Kode Warna,Colopicker,Nomor Polisi,Kapasitas Penumpang (Orang),Nomor BPKB,Nomor STNK,Nomor Mesin,Kapasitas Mesin (CC),Kilometer,Tanggal Service,Created By,Updated By,DiBuat");
        vehicleGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#rspan,#rspan,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        vehicleGrid.setColSorting("str,str,str,str,str,str,str,str,str,str,str,str,str,date,str,str,str");
        vehicleGrid.setColTypes("rotxt,rotxt,edtxt,edtxt,edtxt,cp,rotxt,edtxt,rotxt,rotxt,rotxt,edtxt,edtxt,dhxCalendar,rotxt,rotxt,rotxt");
        vehicleGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        vehicleGrid.setInitWidthsP("5,15,15,15,10,10,15,20,15,15,20,15,15,15,15,15,25");
        vehicleGrid.enableSmartRendering(true);
        vehicleGrid.enableMultiselect(true);
        vehicleGrid.setEditable(true);
        vehicleGrid.attachEvent("onEditCell", changeCellHandler);
        vehicleGrid.attachEvent("onXLE", function() {
            vehicleLayout.cells("a").progressOff();
        });
        vehicleGrid.init();

        function setGridDP() {
            vehicleGridDP = new dataProcessor(AppMaster('updateVehicleBatch'));
            vehicleGridDP.setTransactionMode("POST", true);
            vehicleGridDP.setUpdateMode("Off");
            vehicleGridDP.init(vehicleGrid);
        }

        setGridDP();

        function changeCellHandler(stage, rId, cIn) {
            if(stage === 2) {
				if(cIn === 4){
					vehicleGrid.cells(rId, 5).setValue(vehicleGrid.cells(rId,4).getValue());
				}else if(cIn === 5){
					vehicleGrid.cells(rId, 4).setValue(vehicleGrid.cells(rId,5).getValue());
				}
			} else if(stage === 1 && (cIn === 7 || cIn === 11 || cIn === 12)) {
                this.editor.obj.onkeypress = function(e) {  
                    var ValidChars = "0123456789";
                    if (ValidChars.indexOf((String.fromCharCode((e||event).keyCode))) == -1) {
                        return false;
                    } else {
                        return true;
                    }
                }
            }
			return true;
        }

        function rVehicleGrid() {
            vehicleLayout.cells("a").progressOn();
            vehicleGrid.clearAndLoad(AppMaster("vehicleGrid", {search: vehicleToolbar.getValue("search")}), vehicleGridCount);
        }

        rVehicleGrid();

        vehicleToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    vehicleToolbar.setValue("search","");
                    rVehicleGrid();
                    break;
                case "add":
                    addVehicleHandler();
                    break;
                case "delete":
                    deleteVehicleHandler();
                    break;
                case "edit":
                    editVehicleHandler();
                    break;
            }
        });

        vehicleToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    rVehicleGrid();
                    vehicleGrid.attachEvent("onGridReconstructed", vehicleGridCount);
                    break;
            }
        });

        vehicleMenu.attachEvent("onClick", function(id) {
            switch (id) {
                case "update":
                    if(!vehicleGrid.getChangedRows()) {
                        return eAlert("Belum ada row yang di edit!");
                    }

                    vehicleMenu.setItemDisabled("update");
                    vehicleLayout.cells("a").progressOn();
                    vehicleGridDP.sendData();
                    vehicleGridDP.attachEvent('onAfterUpdate', function(id, action, tid, tag) {
                        let message = tag.getAttribute('message');
                        switch (action) {
                            case 'updated':
                                sAlert(message);
                                rVehicleGrid();
                                vehicleMenu.setItemEnabled("update");
                                vehicleLayout.cells("a").progressOff();
                                setGridDP();
                                break;
                            case 'error':
                                eAlert(message);
                                vehicleMenu.setItemEnabled("update");
                                vehicleLayout.cells("a").progressOff();
                                break;
                        }
                    });
                    break;
            }
        });

        function deleteVehicleHandler() {
            reqAction(vehicleGrid, AppMaster("vehicleDelete"), 1, (err, res) => {
                rVehicleGrid();
                res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
            });
        }

        function addVehicleHandler() {
            vehicleLayout.cells("b").expand();
            vehicleLayout.cells("b").showView("tambah_kendaraan");

            addVehicleForm = vehicleLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Kendaraan", list: [
                    {type: "input", name: "name", label: "Nama Kendaraan", labelWidth: 130, inputWidth:250, required: true},
                    {type: "input", name: "brand", label: "Brand/Merk", labelWidth: 130, inputWidth:250, required: true},
                    {type: "input", name: "type", label: "Tipe", labelWidth: 130, inputWidth:250, required: true},
                    {type: "input", name: "police_no", label: "Nomor Polisi", labelWidth: 130, inputWidth:250, required: true},
                    {type: "input", name: "bpkb_no", label: "Nomor BPKB", labelWidth: 130, inputWidth:250},
                    {type: "input", name: "stnk_no", label: "Nomor STNK", labelWidth: 130, inputWidth:250},
                    {type: "input", name: "machine_no", label: "Nomor Mesin", labelWidth: 130, inputWidth:250},
                    {type: "input", name: "machine_capacity", label: "Kapasitas Mesin", labelWidth: 130, inputWidth:250, validate:"ValidNumeric"},
                    {type: "input", name: "passenger_capacity", label: "Kapasitas Penumpang", labelWidth: 130, inputWidth:250, required: true, validate:"ValidNumeric"},
                    {type: "input", name: "last_km", label: "Kilometer Akhir", labelWidth: 130, inputWidth:250, validate:"ValidNumeric"},
                    {type: "calendar", name: "last_service_date", label: "Tanggal Terakhir Service", labelWidth: 130, inputWidth:250},
                    {type: "colorpicker", name: "color", label: "Indikasi Warna", labelWidth: 130, inputWidth:250, readonly: true, required: true},
                    {type: "hidden", name: "filename", label: "Filename", readonly: true},
                    {type: "upload", name: "file_uploader", inputWidth: 420,
                        url: AppMaster("fileUpload", {save: false, folder: "vehicles"}), 
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
            isFormNumeric(addVehicleForm, ['machine_capacity', 'passenger_capacity', 'last_km']);

            addVehicleForm.attachEvent("onBeforeFileAdd", async function (filename, size) {
                beforeFileAdd(addVehicleForm, {filename, size});
            });

            addVehicleForm.attachEvent("onBeforeFileUpload", function(mode, loader, formData){
                if(fileError) {
                    clearUploader(addVehicleForm, "file_uploader");
                    eAlert("File error silahkan upload file sesuai ketentuan!");
                    fileError = false;
                } else {
                    return true;
                }
            });

            addVehicleForm.attachEvent("onButtonClick", function(id) {
                switch (id) {
                    case "add":
                        const uploader = addVehicleForm.getUploader("file_uploader");
                        if(uploader.getStatus() === -1) {
                            if(!fileError) {
                                uploader.upload();
                            } else {
                                uploader.clear();
                                eAlert("File error silahkan upload file sesuai ketentuan!");
                                fileError = false;
                            }
                        } else {
                            addVehicleFormSubmit();
                        }
                        break;
                    case "clear":
                        clearAllForm(addVehicleForm);
                        break;
                    case "cancel":
                        vehicleLayout.cells("b").collapse();
                        break;
                }
            });

            addVehicleForm.attachEvent("onUploadFile", function(filename, servername){
                addVehicleForm.setItemValue("filename", servername);
                addVehicleFormSubmit();
            });

            function addVehicleFormSubmit() {
                if(!addVehicleForm.validate()) {
                    return eAlert("Input error!");
                }

                setDisable(["add", "clear"], addVehicleForm, vehicleLayout.cells("b"));

                let addVehicleFormDP = new dataProcessor(AppMaster("vehicleForm"));
                addVehicleFormDP.init(addVehicleForm);
                addVehicleForm.save();

                addVehicleFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                    let message = tag.getAttribute("message");
                    switch (action) {
                        case "inserted":
                            sAlert("Berhasil Menambahkan Record <br>" + message);
                            rVehicleGrid();
                            clearAllForm(addVehicleForm);
                            clearUploader(addVehicleForm, "file_uploader");
                            setEnable(["add", "clear"], addVehicleForm, vehicleLayout.cells("b"));
                            break;
                        case "error":
                            eAlert("Gagal Menambahkan Record <br>" + message);
                            setEnable(["add", "clear"], addVehicleForm, vehicleLayout.cells("b"));
                            break;
                    }
                });
            }
        }

        function editVehicleHandler() {
            if(!vehicleGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diubah!");
            }

            vehicleLayout.cells("b").expand();
            vehicleLayout.cells("b").showView("edit_ruang_meeting");

            editVehicleForm = vehicleLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Edit Ruang Meeting", list: [
                    {type: "hidden", name: "id", label: "ID", readonly: true},
                    {type: "input", name: "name", label: "Nama Kendaraan", labelWidth: 130, inputWidth:250, required: true},
                    {type: "input", name: "brand", label: "Brand/Merk", labelWidth: 130, inputWidth:250, required: true},
                    {type: "input", name: "type", label: "Tipe", labelWidth: 130, inputWidth:250, required: true},
                    {type: "input", name: "police_no", label: "Nomor Polisi", labelWidth: 130, inputWidth:250, required: true},
                    {type: "input", name: "bpkb_no", label: "Nomor BPKB", labelWidth: 130, inputWidth:250},
                    {type: "input", name: "stnk_no", label: "Nomor STNK", labelWidth: 130, inputWidth:250},
                    {type: "input", name: "machine_no", label: "Nomor Mesin", labelWidth: 130, inputWidth:250},
                    {type: "input", name: "machine_capacity", label: "Kapasitas Mesin", labelWidth: 130, inputWidth:250, validate:"ValidNumeric"},
                    {type: "input", name: "passenger_capacity", label: "Kapasitas Penumpang", labelWidth: 130, inputWidth:250, required: true, validate:"ValidNumeric"},
                    {type: "input", name: "last_km", label: "Kilometer Akhir", labelWidth: 130, inputWidth:250, validate:"ValidNumeric"},
                    {type: "calendar", name: "last_service_date", label: "Tanggal Terakhir Service", labelWidth: 130, inputWidth:250},
                    {type: "colorpicker", name: "color", label: "Indikasi Warna", labelWidth: 130, inputWidth:250, readonly: true, required: true},
                    {type: "hidden", name: "filename", label: "Filename", readonly: true},
                    {type: "upload", name: "file_uploader", inputWidth: 420,
                        url: AppMaster("fileUpload", {save: false, folder: "vehicles"}), 
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
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Foto Kedaraan", list: [
                    {type: "container", name : "file_display", label: "<img src='./public/img/no-image.png' height='120' width='120'>"}
                ]},
            ]);

            isFormNumeric(editVehicleForm, ['machine_capacity', 'passenger_capacity', 'last_km']);    
            
            const loadTemp = (filename) => {
                if (filename.length === 0) {
                    editVehicleForm.setItemLabel("file_display", "<img src='./public/img/no-image.png' height='120' width='120'>");
                } else {
                    filename.map(file => {
                        if(file === '') {
                            editVehicleForm.setItemLabel("file_display", "<img src='./public/img/no-image.png' height='120' width='120'>");
                        } else {
                            var fotoDisplay = "<img src='./assets/images/vehicles/"+file+"' height='120' width='120'>"
                            editVehicleForm.setItemLabel("file_display", fotoDisplay);
                        }
                    });
                }	
            }

            fetchFormData(AppMaster("vehicleForm", {id: vehicleGrid.getSelectedRowId()}), editVehicleForm, ["filename"], loadTemp);

            editVehicleForm.attachEvent("onBeforeFileAdd", async function (filename, size) {
                beforeFileAdd(editVehicleForm, {filename, size}, editVehicleForm.getItemValue("id"));
            });

            editVehicleForm.attachEvent("onBeforeFileUpload", function(mode, loader, formData){
                if(fileError) {
                    clearUploader(editVehicleForm, "file_uploader");
                    eAlert("File error silahkan upload file sesuai ketentuan!");
                    fileError = false;
                } else {
                    return true;
                }
            });

            editVehicleForm.attachEvent("onUploadFile", function(filename, servername){
                reqJson(AppMaster("updateAfterUpload"), "POST", {
                    id: editVehicleForm.getItemValue("id"),
                    oldFile: editVehicleForm.getItemValue("filename"),
                    filename: servername,
                    folder: "kf_general.vehicles"
                }, (err, res) => {
                    if(res.status === "success") {
                        editVehicleForm.setItemValue("filename", servername);
                        clearUploader(editVehicleForm, "file_uploader");
                        editVehicleForm.setItemLabel("file_display", "<img src='./assets/images/vehicles/"+servername+"' height='120' width='120'>");
                        sAlert(res.message);
                    } else {
                        eAlert(res.message);
                    }
                });
            });     

            editVehicleForm.attachEvent("onButtonClick", function(id) {
                switch (id) {
                    case "update":
                        setDisable(["update", "cancel"], editVehicleForm, vehicleLayout.cells("b"));

                        let editVehicleFormDP = new dataProcessor(AppMaster("vehicleForm"));
                        editVehicleFormDP.init(editVehicleForm);
                        editVehicleForm.save();

                        editVehicleFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                        let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rVehicleGrid();
                                    vehicleLayout.cells("b").progressOff();
                                    vehicleLayout.cells("b").showView("tambah_kendaraans");
                                    vehicleLayout.cells("b").collapse();
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record <br>" + message);
                                    setEnable(["update", "cancel"], editVehicleForm, vehicleLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "cancel":
                        vehicleLayout.cells("b").collapse();
                        vehicleLayout.cells("b").showView("tambah_kendaraan");
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
                            const data = id ? {id, police_no: form.getItemValue("police_no")} : {police_no: form.getItemValue("police_no")}
                            const checkVehicle = await reqJsonResponse(AppMaster("checkBeforeAddFile2"), "POST", data);

                            if(checkVehicle) {
                                if(checkVehicle.status === "success") {
                                    totalFile++;
                                    return true;
                                } else if(checkVehicle.status === "deleted") {
                                    fileError = false;
                                    totalFile= 0;
                                    vehicleLayout.cells("b").collapse();
                                    vehicleLayout.cells("b").showView("tambah_kendaraan");
                                } else {
                                    eAlert(checkVehicle.message);
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
