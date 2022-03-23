<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
    		
    function showMasterProduct() {
        var addProductForm;
        var editProductForm;
        var fileError;
        var totalFile;

        var mProductLayout = mainTab.cells("prod_master_products_product").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form Master Produk",
                    header: true,
                    collapse: true
                }
            ]
        });

        var mProductToolbar = mainTab.cells("prod_master_products_product").attachToolbar({
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
            mProductToolbar.disableItem("delete");
        }

        var mProductSB = mProductLayout.cells("a").attachStatusBar();
        function mProductGridCount() {
            mProductSB.setText("Total baris: " +  mProductGrid.getRowsNum());
        }

        var mProductGrid = mProductLayout.cells("a").attachGrid();
        mProductGrid.setHeader("No,Nama Produk,Kode Produk,Created By,Updated By,DiBuat");
        mProductGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        mProductGrid.setColSorting("int,str,str,str,str,str");
        mProductGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        mProductGrid.setColAlign("center,left,left,left,left,left");
        mProductGrid.setInitWidthsP("5,30,20,20,20,22");
        mProductGrid.enableSmartRendering(true);
        mProductGrid.enableMultiselect(true);
        mProductGrid.attachEvent("onXLE", function() {
            mProductLayout.cells("a").progressOff();
        });
        mProductGrid.init();

        function rProductGrid() {
            mProductLayout.cells("a").progressOn();
            mProductGrid.clearAndLoad(Production("getMasterProductGrid", {search: mProductToolbar.getValue("search")}), mProductGridCount);
        }

        rProductGrid();

        mProductToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    mProductToolbar.setValue("search","");
                    rProductGrid();
                    break;
                case "add":
                    addProductHandler();
                    break;
                case "delete":
                    deleteProductHandler();
                    break;
                case "edit":
                    editProductHandler();
                    break;
                case "export":
                    mProductGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    sAlert("Export Data Dimulai");
                    break;
            }
        });

        mProductToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    rProductGrid();
                    mProductGrid.attachEvent("onGridReconstructed", mProductGridCount);
                    break;
            }
        });

        function deleteProductHandler() {
            reqAction(mProductGrid, Production("productDelete"), 1, (err, res) => {
                rProductGrid();
                res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
            });
        }

        function addProductHandler() {
            mProductLayout.cells("b").expand();
            mProductLayout.cells("b").showView("tambah_produk");

            addProductForm = mProductLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Produk", list: [
                    {type: "input", name: "name", label: "Nama Produk", labelWidth: 130, inputWidth:250, required: true},
                    {type: "input", name: "code", label: "Kode Produk", labelWidth: 130, inputWidth:250, required: true},
                    {type: "hidden", name: "filename", label: "Filename", readonly: true},
                    {type: "upload", name: "file_uploader", inputWidth: 420,
                        url: AppMaster("fileUpload", {save: false, folder: "products"}), 
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

            addProductForm.attachEvent("onBeforeFileAdd", async function (filename, size) {
                beforeFileAdd(addProductForm, {filename, size});
            });

            addProductForm.attachEvent("onBeforeFileUpload", function(mode, loader, formData){
                if(fileError) {
                    clearUploader(addProductForm, "file_uploader");
                    eAlert("File error silahkan upload file sesuai ketentuan!");
                    fileError = false;
                } else {
                    return true;
                }
            });

            addProductForm.attachEvent("onButtonClick", function(id) {
                switch (id) {
                    case "add":
                        const uploader = addProductForm.getUploader("file_uploader");
                        if(uploader.getStatus() === -1) {
                            if(!fileError) {
                                uploader.upload();
                            } else {
                                uploader.clear();
                                eAlert("File error silahkan upload file sesuai ketentuan!");
                                fileError = false;
                            }
                        } else {
                            addProductFormSubmit();
                        }
                        break;
                    case "clear":
                        clearAllForm(addProductForm);
                        break;
                    case "cancel":
                        mProductLayout.cells("b").collapse();
                        break;
                }
            });

            addProductForm.attachEvent("onUploadFile", function(filename, servername){
                addProductForm.setItemValue("filename", servername);
                addProductFormSubmit();
            });

            function addProductFormSubmit() {
                if(!addProductForm.validate()) {
                    return eAlert("Input error!");
                }

                setDisable(["add", "clear"], addProductForm, mProductLayout.cells("b"));
                let addProductFormDP = new dataProcessor(Production("productForm"));
                addProductFormDP.init(addProductForm);
                addProductForm.save();

                addProductFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                    let message = tag.getAttribute("message");
                    switch (action) {
                        case "inserted":
                            sAlert("Berhasil Menambahkan Record <br>" + message);
                            rProductGrid();
                            clearAllForm(addProductForm);
                            clearUploader(addProductForm, "file_uploader");
                            setEnable(["add", "clear"], addProductForm, mProductLayout.cells("b"));
                            break;
                        case "error":
                            eAlert("Gagal Menambahkan Record <br>" + message);
                            setEnable(["add", "clear"], addProductForm, mProductLayout.cells("b"));
                            break;
                    }
                });
            }
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
                            }

                            const checkProduct = await reqJsonResponse(AppMaster("checkBeforeAddFile4"), "POST", data);

                            if(checkProduct) {
                                if(checkProduct.status === "success") {
                                    totalFile++;
                                    return true;
                                } else if(checkProduct.status === "deleted") {
                                    fileError = false;
                                    totalFile= 0;
                                    mProductLayout.cells("b").collapse();
                                    mProductLayout.cells("b").showView("tambah_produk");
                                } else {
                                    eAlert(checkProduct.message);
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