<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
    		
    function showMasterProductType() {
        var addPTypeForm;
        var editPTypeForm;

        var mPTypeLayout = mainTab.cells("prod_master_spack_product_type").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form Golongan Produk",
                    header: true,
                    collapse: true
                }
            ]
        });

        var mPTypeToolbar = mainTab.cells("prod_master_spack_product_type").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "add", text: "Tambah", type: "button", img: "add.png"},
                {id: "delete", text: "Hapus", type: "button", img: "delete.png"},
                {id: "edit", text: "Ubah", type: "button", img: "edit.png"},
                {id: "export", text: "Export To Excel", type: "button", img: "excel.png"},
                {id: "searchtext", text: "Cari : ", type: "text"},
                {id: "search", text: "", type: "buttonInput", width: 150}
            ]
        });

        var mPTypeSB = mPTypeLayout.cells("a").attachStatusBar();
        function mPTypeGridCount() {
            mPTypeSB.setText("Total baris: " +  mPTypeGrid.getRowsNum());
        }

        var mPTypeGrid = mPTypeLayout.cells("a").attachGrid();
        mPTypeGrid.setHeader("No,Golongan Produk,Created By,Updated By,DiBuat");
        mPTypeGrid.attachHeader("#rspan,#text_filter,#select_filter,#select_filter,#text_filter")
        mPTypeGrid.setColSorting("int,str,str,str,str");
        mPTypeGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt");
        mPTypeGrid.setColAlign("center,left,left,left,left");
        mPTypeGrid.setInitWidthsP("5,33,20,20,22");
        mPTypeGrid.enableSmartRendering(true);
        mPTypeGrid.enableMultiselect(true);
        mPTypeGrid.attachEvent("onXLE", function() {
            mPTypeLayout.cells("a").progressOff();
        });
        mPTypeGrid.init();

        function rPTypeGrid() {
            mPTypeLayout.cells("a").progressOn();
            mPTypeGrid.clearAndLoad(Production("getMasterProductTypeGrid", {search: mPTypeToolbar.getValue("search")}), mPTypeGridCount);
        }

        rPTypeGrid();

        mPTypeToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    mPTypeToolbar.setValue("search","");
                    rPTypeGrid();
                    break;
                case "add":
                    addPTypeHandler();
                    break;
                case "delete":
                    reqAction(mPTypeGrid, Production("productTypeDelete"), 1, (err, res) => {
                        rPTypeGrid();
                        res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                        res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
                    });
                    break;
                case "edit":
                    editPTypeHandler();
                    break;
                case "export":
                    mPTypeGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    sAlert("Export Data Dimulai");
                    break;
            }
        });

        mPTypeToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    rPTypeGrid();
                    mPTypeGrid.attachEvent("onGridReconstructed", mPTypeGridCount);
                    break;
            }
        });

        function addPTypeHandler() {
            mPTypeLayout.cells("b").expand();
            mPTypeLayout.cells("b").showView("tambah_tipe_produk");

            addPTypeForm = mPTypeLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Golongan Produk", list: [
                    {type: "input", name: "name", label: "Golongan Produk", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                        {type: "newcolumn"},
                        {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            addPTypeForm.attachEvent("onButtonClick", function(id) {
                switch (id) {
                    case "add":
                        if(!addPTypeForm.validate()) {
                            return eAlert("Input error!");
                        }

                        setDisable(["add", "clear"], addPTypeForm, mPTypeLayout.cells("b"));
                        let addPTypeFormDP = new dataProcessor(Production("productTypeForm"));
                        addPTypeFormDP.init(addPTypeForm);
                        addPTypeForm.save();

                        addPTypeFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "inserted":
                                    sAlert("Berhasil Menambahkan Record <br>" + message);
                                    rPTypeGrid();
                                    clearAllForm(addPTypeForm);
                                    setEnable(["add", "clear"], addPTypeForm, mPTypeLayout.cells("b"));
                                    break;
                                case "error":
                                    eAlert("Gagal Menambahkan Record <br>" + message);
                                    setEnable(["add", "clear"], addPTypeForm, mPTypeLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "clear":
                        clearAllForm(addPTypeForm);
                        break;
                    case "cancel":
                        mPTypeLayout.cells("b").collapse();
                        break;
                }
            });
        }

        function editPTypeHandler() {
            if(!mPTypeGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diubah!");
            }

            mPTypeLayout.cells("b").expand();
            mPTypeLayout.cells("b").showView("edit_tipe_produk");

            editPTypeForm = mPTypeLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Edit Golongan Produk", list: [
                    {type: "hidden", name: "id", label: "ID", readonly: true},
                    {type: "input", name: "name", label: "Golongan Produk", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]},
            ]);

            fetchFormData(Production("productTypeForm", {id: mPTypeGrid.getSelectedRowId()}), editPTypeForm);
            editPTypeForm.attachEvent("onButtonClick", function(id) {
                switch (id) {
                    case "update":
                        setDisable(["update", "cancel"], editPTypeForm, mPTypeLayout.cells("b"));

                        let editPTypeFormDP = new dataProcessor(Production("productTypeForm"));
                        editPTypeFormDP.init(editPTypeForm);
                        editPTypeForm.save();

                        editPTypeFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                        let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rPTypeGrid();
                                    mPTypeLayout.cells("b").progressOff();
                                    mPTypeLayout.cells("b").showView("tambah_tipe_produk");
                                    mPTypeLayout.cells("b").collapse();
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record <br>" + message);
                                    setEnable(["update", "cancel"], editPTypeForm, mPTypeLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "cancel":
                        mPTypeLayout.cells("b").collapse();
                        mPTypeLayout.cells("b").showView("edit_tipe_produk");
                        break;
                }
            });
        }
    }

JS;

header('Content-Type: application/javascript');
echo $script;