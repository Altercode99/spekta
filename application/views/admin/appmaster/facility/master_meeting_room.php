<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	
	function showMasterMeetingRoom() {	
        var addRoomForm;
        var editRoomForm;
        var fileError;
        var totalFile;

        var roomLayout = mainTab.cells("master_meeting_room").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form Ruang Meeting",
                    header: true,
                    collapse: true
                }
            ]
        });

        var roomToolbar = mainTab.cells("master_meeting_room").attachToolbar({
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
            roomToolbar.disableItem("delete");
        }

        var roomMenu =  mainTab.cells("master_meeting_room").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "update", text: "Update Grid", img: "update.png"}
            ]
        });

        var roomStatusBar = roomLayout.cells("a").attachStatusBar();
        function roomGridCount() {
            let roomGridRows = roomGrid.getRowsNum();
            roomStatusBar.setText("Total baris: " + roomGridRows);
        }

        var roomGrid = roomLayout.cells("a").attachGrid();
        roomGrid.setHeader("No,Nama Ruangan,Kode Warna,Colopicker,Kapasitas,Gedung,Lantai,Created By,Updated By,DiBuat");
        roomGrid.attachHeader("#rspan,#text_filter,#rspan,#rspan,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        roomGrid.setColSorting("str,str,str,str,str,str,str,str,str,str");
        roomGrid.setColTypes("rotxt,rotxt,edtxt,cp,edtxt,edtxt,edtxt,rotxt,rotxt,rotxt");
        roomGrid.setColAlign("center,left,left,left,left,left,left,left,left,left");
        roomGrid.setInitWidthsP("5,15,10,10,15,15,15,15,15,25");
        roomGrid.enableSmartRendering(true);
        roomGrid.enableMultiselect(true);
        roomGrid.setEditable(true);
        roomGrid.attachEvent("onEditCell", changeCellHandler);
        roomGrid.attachEvent("onXLE", function() {
            roomLayout.cells("a").progressOff();
        });
        roomGrid.init();

        function setGridDP() {
            roomGridDP = new dataProcessor(AppMaster('updateRoomBatch'));
            roomGridDP.setTransactionMode("POST", true);
            roomGridDP.setUpdateMode("Off");
            roomGridDP.init(roomGrid);
        }
    
        setGridDP();

        function changeCellHandler(stage, rId, cIn) {
            if(stage === 2) {
				if(cIn === 2){
					roomGrid.cells(rId, 3).setValue(roomGrid.cells(rId,2).getValue());
				}else if(cIn === 3){
					roomGrid.cells(rId, 2).setValue(roomGrid.cells(rId,3).getValue());
				}
			} else if(stage === 1 && (cIn === 4 || cIn === 6)) {
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

        function rRoomGrid() {
            roomLayout.cells("a").progressOn();
            roomGrid.clearAndLoad(AppMaster("roomGrid", {search: roomToolbar.getValue("search")}), roomGridCount);
        }

        rRoomGrid();

        roomToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    roomToolbar.setValue("search","");
                    rRoomGrid();
                    break;
                case "add":
                    addRoomHandler();
                    break;
                case "delete":
                    deleteRoomHandler();
                    break;
                case "edit":
                    editRoomHandler();
                    break;
            }
        });

        roomToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    rRoomGrid();
                    roomGrid.attachEvent("onGridReconstructed", roomGridCount);
                    break;
            }
        });
        
        roomMenu.attachEvent("onClick", function(id) {
            switch (id) {
                case "update":
                    if(!roomGrid.getChangedRows()) {
                        return eAlert("Belum ada row yang di edit!");
                    }

                    roomMenu.setItemDisabled("update");
                    roomLayout.cells("a").progressOn();
                    roomGridDP.sendData();
                    roomGridDP.attachEvent('onAfterUpdate', function(id, action, tid, tag) {
                        let message = tag.getAttribute('message');
                        switch (action) {
                            case 'updated':
                                sAlert(message);
                                rRoomGrid();
                                roomMenu.setItemEnabled("update");
                                roomLayout.cells("a").progressOff();
                                setGridDP();
                                break;
                            case 'error':
                                eAlert(message);
                                roomMenu.setItemEnabled("update");
                                roomLayout.cells("a").progressOff();
                                break;
                        }
                    });
                    break;
            }
        });

        function deleteRoomHandler() {
            reqAction(roomGrid, AppMaster("roomDelete"), 1, (err, res) => {
                rRoomGrid();
                res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
            });
        }

        function addRoomHandler() {
            roomLayout.cells("b").expand();
            roomLayout.cells("b").showView("tambah_ruang_meeting");

            addRoomForm = roomLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Ruang Meeting", list: [
                    {type: "input", name: "name", label: "Nama Ruangan", labelWidth: 130, inputWidth:250, required: true},
                    {type: "input", name: "capacity", label: "Kapasitas", labelWidth: 130, inputWidth:250, required: true, validate:"ValidNumeric"},
                    {type: "input", name: "building", label: "Lokasi Gedung", labelWidth: 130, inputWidth:250, required: true},
                    {type: "input", name: "on_floor", label: "Lokasi Lantai", labelWidth: 130, inputWidth:250, required: true, validate:"ValidNumeric"},
                    {type: "input", name: "facility", label: "Fasilitas", labelWidth: 130, inputWidth:250, required: true, rows: 5},
                    {type: "colorpicker", name: "color", label: "Indikasi Warna", labelWidth: 130, inputWidth:250, readonly: true, required: true},
                    {type: "hidden", name: "filename", label: "Filename", readonly: true},
                    {type: "upload", name: "file_uploader", inputWidth: 420,
                        url: AppMaster("fileUpload", {save: false, folder: "meeting_rooms"}), 
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
            isFormNumeric(addRoomForm, ['capacity', 'on_floor']);

            addRoomForm.attachEvent("onBeforeFileAdd", async function (filename, size) {
                beforeFileAdd(addRoomForm, {filename, size});
            });

            addRoomForm.attachEvent("onBeforeFileUpload", function(mode, loader, formData){
                if(fileError) {
                    clearUploader(addRoomForm, "file_uploader");
                    eAlert("File error silahkan upload file sesuai ketentuan!");
                    fileError = false;
                } else {
                    return true;
                }
            });

            addRoomForm.attachEvent("onButtonClick", function(id) {
                switch (id) {
                    case "add":
                        const uploader = addRoomForm.getUploader("file_uploader");
                        if(uploader.getStatus() === -1) {
                            if(!fileError) {
                                uploader.upload();
                            } else {
                                uploader.clear();
                                eAlert("File error silahkan upload file sesuai ketentuan!");
                                fileError = false;
                            }
                        } else {
                            addRoomFormSubmit();
                        }
                        break;
                    case "clear":
                        clearAllForm(addRoomForm);
                        break;
                    case "cancel":
                        roomLayout.cells("b").collapse();
                        break;
                }
            });

            addRoomForm.attachEvent("onUploadFile", function(filename, servername){
                addRoomForm.setItemValue("filename", servername);
                addRoomFormSubmit();
            });

            function addRoomFormSubmit() {
                if(!addRoomForm.validate()) {
                    return eAlert("Input error!");
                }

                setDisable(["add", "clear"], addRoomForm, roomLayout.cells("b"));

                let addRoomFormDP = new dataProcessor(AppMaster("roomForm"));
                addRoomFormDP.init(addRoomForm);
                addRoomForm.save();

                addRoomFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                    let message = tag.getAttribute("message");
                    switch (action) {
                        case "inserted":
                            sAlert("Berhasil Menambahkan Record <br>" + message);
                            rRoomGrid();
                            clearAllForm(addRoomForm);
                            clearUploader(addRoomForm, "file_uploader");
                            setEnable(["add", "clear"], addRoomForm, roomLayout.cells("b"));
                            break;
                        case "error":
                            eAlert("Gagal Menambahkan Record <br>" + message);
                            setEnable(["add", "clear"], addRoomForm, roomLayout.cells("b"));
                            break;
                    }
                });
            }
        }

        function editRoomHandler() {
            if(!roomGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diubah!");
            }

            roomLayout.cells("b").expand();
            roomLayout.cells("b").showView("edit_ruang_meeting");

            editRoomForm = roomLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Edit Ruang Meeting", list: [
                    {type: "hidden", name: "id", label: "ID", readonly: true},
                    {type: "input", name: "name", label: "Nama Ruangan", labelWidth: 130, inputWidth:250, required: true},
                    {type: "input", name: "capacity", label: "Kapasitas", labelWidth: 130, inputWidth:250, required: true, validate:"ValidNumeric"},
                    {type: "input", name: "building", label: "Lokasi Gedung", labelWidth: 130, inputWidth:250, required: true},
                    {type: "input", name: "on_floor", label: "Lokasi Lantai", labelWidth: 130, inputWidth:250, required: true, validate:"ValidNumeric"},
                    {type: "input", name: "facility", label: "Fasilitas", labelWidth: 130, inputWidth:250, required: true, rows: 5},
                    {type: "colorpicker", name: "color", label: "Indikasi Warna", labelWidth: 130, inputWidth:250, readonly: true, required: true},
                    {type: "hidden", name: "filename", label: "Filename", readonly: true},
                    {type: "upload", name: "file_uploader", inputWidth: 420,
                        url: AppMaster("fileUpload", {save: false, folder: "meeting_rooms"}), 
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
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Foto Ruangan", list: [
                    {type: "container", name : "file_display", label: "<img src='./public/img/no-image.png' height='120' width='120'>"}
                ]},
            ]);

            isFormNumeric(editRoomForm, ['capacity', 'on_floor']);    
            
            const loadTemp = (filename) => {
                if (filename.length === 0) {
                    editRoomForm.setItemLabel("file_display", "<img src='./public/img/no-image.png' height='120' width='120'>");
                } else {
                    filename.map(file => {
                        if(file === '') {
                            editRoomForm.setItemLabel("file_display", "<img src='./public/img/no-image.png' height='120' width='120'>");
                        } else {
                            var fotoDisplay = "<img src='./assets/images/meeting_rooms/"+file+"' height='120' width='120'>"
                            editRoomForm.setItemLabel("file_display", fotoDisplay);
                        }
                    });
                }	
            }

            fetchFormData(AppMaster("roomForm", {id: roomGrid.getSelectedRowId()}), editRoomForm, ["filename"], loadTemp);

            editRoomForm.attachEvent("onBeforeFileAdd", async function (filename, size) {
                beforeFileAdd(editRoomForm, {filename, size}, editRoomForm.getItemValue("id"));
            });

            editRoomForm.attachEvent("onBeforeFileUpload", function(mode, loader, formData){
                if(fileError) {
                    clearUploader(editRoomForm, "file_uploader");
                    eAlert("File error silahkan upload file sesuai ketentuan!");
                    fileError = false;
                } else {
                    return true;
                }
            });

            editRoomForm.attachEvent("onUploadFile", function(filename, servername){
                reqJson(AppMaster("updateAfterUpload"), "POST", {
                    id: editRoomForm.getItemValue("id"),
                    oldFile: editRoomForm.getItemValue("filename"),
                    filename: servername,
                    folder: "kf_general.meeting_rooms"
                }, (err, res) => {
                    if(res.status === "success") {
                        editRoomForm.setItemValue("filename", servername);
                        clearUploader(editRoomForm, "file_uploader");
                        editRoomForm.setItemLabel("file_display", "<img src='./assets/images/meeting_rooms/"+servername+"' height='120' width='120'>");
                        sAlert(res.message);
                    } else {
                        eAlert(res.message);
                    }
                });
            });     

            editRoomForm.attachEvent("onButtonClick", function(id) {
                switch (id) {
                    case "update":
                        setDisable(["update", "cancel"], editRoomForm, roomLayout.cells("b"));

                        let editRoomFormDP = new dataProcessor(AppMaster("roomForm"));
                        editRoomFormDP.init(editRoomForm);
                        editRoomForm.save();

                        editRoomFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                        let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rRoomGrid();
                                    roomLayout.cells("b").progressOff();
                                    roomLayout.cells("b").showView("tambah_ruang_meeting");
                                    roomLayout.cells("b").collapse();
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record <br>" + message);
                                    setEnable(["update", "cancel"], editRoomForm, roomLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "cancel":
                        roomLayout.cells("b").collapse();
                        roomLayout.cells("b").showView("tambah_ruang_meeting");
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
                            const data = id ? {id, name: form.getItemValue("name")} : {name: form.getItemValue("name")}
                            const checkRoom = await reqJsonResponse(AppMaster("checkBeforeAddFile"), "POST", data);

                            if(checkRoom) {
                                if(checkRoom.status === "success") {
                                    totalFile++;
                                    return true;
                                } else if(checkRoom.status === "deleted") {
                                    fileError = false;
                                    totalFile= 0;
                                    roomLayout.cells("b").collapse();
                                    roomLayout.cells("b").showView("tambah_ruang_meeting");
                                } else {
                                    eAlert(checkRoom.message);
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
