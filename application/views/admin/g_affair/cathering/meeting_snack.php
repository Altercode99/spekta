<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	function showMeetingSnack() {	
        var addSnackForm;
        var editSnackForm;
        var fileError;
        var totalFile;
        
        var snackLayout = mainTab.cells("ga_meeting_snack").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form Snack Meeting",
                    header: true,
                    collapse: true
                }
            ]
        });

        var snackToolbar = mainTab.cells("ga_meeting_snack").attachToolbar({
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
            snackToolbar.disableItem("delete");
        }

        var snackStatusBar = snackLayout.cells("a").attachStatusBar();
        function snackGridCount() {
            let snackGridRows = snackGrid.getRowsNum();
            snackStatusBar.setText("Total baris: " + snackGridRows);
        }

        var snackGrid = snackLayout.cells("a").attachGrid();
        snackGrid.setHeader("No,Nama Snack,Harga,Created By,Updated By,DiBuat");
        snackGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        snackGrid.setColSorting("str,str,str,str,str,str");
        snackGrid.setColTypes("rotxt,rotxt,ron,rotxt,rotxt,rotxt");
        snackGrid.setColAlign("center,left,left,left,left,left");
        snackGrid.setInitWidthsP("5,20,20,15,15,25");
        snackGrid.enableSmartRendering(true);
        snackGrid.attachEvent("onXLE", function() {
            snackLayout.cells("a").progressOff();
        });
        snackGrid.setNumberFormat("0,000",2,".",",");
        snackGrid.init();

        function setGridDP() {
            snackGridDP = new dataProcessor(GAOther('getSnackGrid'));
            snackGridDP.setTransactionMode("POST", true);
            snackGridDP.setUpdateMode("Off");
            snackGridDP.init(snackGrid);
        }

        setGridDP();

        function rSnackGrid() {
            snackLayout.cells("a").progressOn();
            snackGrid.clearAndLoad(GAOther("getSnackGrid", {search: snackToolbar.getValue("search")}), snackGridCount);
        }

        rSnackGrid();

        snackToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    snackToolbar.setValue("search","");
                    rSnackGrid();
                    break;
                case "add":
                    addSnackHandler();
                    break;
                case "delete":
                    deleteSnackHandler();
                    break;
                case "edit":
                    editSnackHandler();
                    break;
            }
        });


        snackToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    rSnackGrid();
                    snackGrid.attachEvent("onGridReconstructed", snackGridCount);
                    break;
            }
        });

        function deleteSnackHandler() {
            reqAction(snackGrid, GAOther("snackDelete"), 1, (err, res) => {
                rSnackGrid();
                res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
            });
        }

        function addSnackHandler() {
            snackLayout.cells("b").expand();
            snackLayout.cells("b").showView("tambah_snack");

            addSnackForm = snackLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Mesin Produksi", list: [
                    {type: "input", name: "name", label: "Nama Snack", labelWidth: 130, inputWidth:250, required: true},
                    {type: "input", name: "price", label: "Harga", required: true, labelWidth: 130, inputWidth: 250, validate:"ValidNumeric"},
                    {type: "hidden", name: "filename", label: "Filename", readonly: true},
                    {type: "upload", name: "file_uploader", inputWidth: 420,
                        url: AppMaster("fileUpload", {save: false, folder: "meeting_snacks"}), 
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
            isFormNumeric(addSnackForm, ['price']);

            addSnackForm.attachEvent("onBeforeFileAdd", async function (filename, size) {
                beforeFileAdd(addSnackForm, {filename, size});
            });

            addSnackForm.attachEvent("onBeforeFileUpload", function(mode, loader, formData){
                if(fileError) {
                    clearUploader(addSnackForm, "file_uploader");
                    eAlert("File error silahkan upload file sesuai ketentuan!");
                    fileError = false;
                } else {
                    return true;
                }
            });

            addSnackForm.attachEvent("onButtonClick", function(id) {
                switch (id) {
                    case "add":
                        const uploader = addSnackForm.getUploader("file_uploader");
                        if(uploader.getStatus() === -1) {
                            if(!fileError) {
                                uploader.upload();
                            } else {
                                uploader.clear();
                                eAlert("File error silahkan upload file sesuai ketentuan!");
                                fileError = false;
                            }
                        } else {
                            addSnackFormSubmit();
                        }
                        break;
                    case "clear":
                        clearAllForm(addSnackForm);
                        break;
                    case "cancel":
                        snackLayout.cells("b").collapse();
                        break;
                }
            });

            addSnackForm.attachEvent("onUploadFile", function(filename, servername){
                addSnackForm.setItemValue("filename", servername);
                addSnackFormSubmit();
            });

            function addSnackFormSubmit() {
                if(!addSnackForm.validate()) {
                    return eAlert("Input error!");
                }

                setDisable(["add", "clear"], addSnackForm, snackLayout.cells("b"));

                let addSnackFormDP = new dataProcessor(GAOther("snackForm"));
                addSnackFormDP.init(addSnackForm);
                addSnackForm.save();

                addSnackFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                    let message = tag.getAttribute("message");
                    switch (action) {
                        case "inserted":
                            sAlert("Berhasil Menambahkan Record <br>" + message);
                            rSnackGrid();
                            clearAllForm(addSnackForm);
                            clearUploader(addSnackForm, "file_uploader");
                            setEnable(["add", "clear"], addSnackForm, snackLayout.cells("b"));
                            break;
                        case "error":
                            eAlert("Gagal Menambahkan Record <br>" + message);
                            setEnable(["add", "clear"], addSnackForm, snackLayout.cells("b"));
                            break;
                    }
                });
            }
        }

        function editSnackHandler() {
            if(!snackGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diubah!");
            }

            snackLayout.cells("b").expand();
            snackLayout.cells("b").showView("edit_mesin_snack");

            editSnackForm = snackLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Edit Ruang Meeting", list: [
                    {type: "hidden", name: "id", label: "ID", readonly: true},
                    {type: "input", name: "name", label: "Nama Mesin", labelWidth: 130, inputWidth:250, required: true},
                    {type: "input", name: "price", label: "Harga", required: true, labelWidth: 130, inputWidth: 250, validate:"ValidNumeric"},
                    {type: "hidden", name: "filename", label: "Filename", readonly: true},
                    {type: "upload", name: "file_uploader", inputWidth: 420,
                        url: AppMaster("fileUpload", {save: false, folder: "meeting_snacks"}), 
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
                    editSnackForm.setItemLabel("file_display", "<img src='./public/img/no-image.png' height='120' width='120'>");
                } else {
                    filename.map(file => {
                        if(file === '') {
                            editSnackForm.setItemLabel("file_display", "<img src='./public/img/no-image.png' height='120' width='120'>");
                        } else {
                            var fotoDisplay = "<img src='./assets/images/meeting_snacks/"+file+"' height='120' width='120'>"
                            editSnackForm.setItemLabel("file_display", fotoDisplay);
                        }
                    });
                }	
            }
        
            fetchFormData(GAOther("snackForm", {id: snackGrid.getSelectedRowId()}), editSnackForm, ["filename"], loadTemp);

            editSnackForm.attachEvent("onBeforeFileAdd", async function (filename, size) {
                beforeFileAdd(editSnackForm, {filename, size}, editSnackForm.getItemValue("id"));
            });

            editSnackForm.attachEvent("onBeforeFileUpload", function(mode, loader, formData){
                if(fileError) {
                    clearUploader(editSnackForm, "file_uploader");
                    eAlert("File error silahkan upload file sesuai ketentuan!");
                    fileError = false;
                } else {
                    return true;
                }
            });

            editSnackForm.attachEvent("onUploadFile", function(filename, servername){
                reqJson(AppMaster("updateAfterUpload"), "POST", {
                    id: editSnackForm.getItemValue("id"),
                    oldFile: editSnackForm.getItemValue("filename"),
                    filename: servername,
                    folder: "kf_general.snacks"
                }, (err, res) => {
                    if(res.status === "success") {
                        editSnackForm.setItemValue("filename", servername);
                        clearUploader(editSnackForm, "file_uploader");
                        editSnackForm.setItemLabel("file_display", "<img src='./assets/images/meeting_snacks/"+servername+"' height='120' width='120'>");
                        sAlert(res.message);
                    } else {
                        eAlert(res.message);
                    }
                });
            });     

            editSnackForm.attachEvent("onButtonClick", function(id) {
                switch (id) {
                    case "update":
                        setDisable(["update", "cancel"], editSnackForm, snackLayout.cells("b"));

                        let editSnackFormDP = new dataProcessor(GAOther("snackForm"));
                        editSnackFormDP.init(editSnackForm);
                        editSnackForm.save();

                        editSnackFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                        let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rSnackGrid();
                                    snackLayout.cells("b").progressOff();
                                    snackLayout.cells("b").showView("tambah_snack");
                                    snackLayout.cells("b").collapse();
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record <br>" + message);
                                    setEnable(["update", "cancel"], editSnackForm, snackLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "cancel":
                        snackLayout.cells("b").collapse();
                        snackLayout.cells("b").showView("tambah_snack");
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
                                name: form.getItemValue("name")
                            }

                            const checkSnack = await reqJsonResponse(GAOther("checkBeforeAddFile"), "POST", data);

                            if(checkSnack) {
                                if(checkSnack.status === "success") {
                                    totalFile++;
                                    return true;
                                } else if(checkSnack.status === "deleted") {
                                    fileError = false;
                                    totalFile= 0;
                                    snackLayout.cells("b").collapse();
                                    snackLayout.cells("b").showView("tambah_snack");
                                } else {
                                    eAlert(checkSnack.message);
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
