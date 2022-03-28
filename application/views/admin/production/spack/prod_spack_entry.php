<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
    		
    function showSpackEntry() {
        var addEntryForm;
        var editEntryForm;

        var spEntryTabs = mainTab.cells("prod_spack_entry").attachTabbar({
            tabs: [
                {id: "a", text: "Entry Surat Pack", active: true}
            ]
        });

        var spEntryLayout = spEntryTabs.cells("a").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    text: "Form Surat Pack",
                },
                {
                    id: "b",
                    text: "Daftar Surat Pack",
                }
            ]
        });

        addEntryForm = spEntryLayout.cells("a").attachForm([
            {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Surat Pack", list: [
                {type: "combo", name: "product_id", label: "Produk", labelWidth: 130, inputWidth: 250, readonly: true, required: true},
                {type: "input", name: "no_batch", label: "No. Batch", labelWidth: 130, inputWidth:250, required: true},
                {type: "block", offsetTop: 30, list: [
                    {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                    {type: "newcolumn"},
                    {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                    {type: "newcolumn"},
                    {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                ]}
            ]}
        ]);

        var addProductCombo = addEntryForm.getCombo("product_id");
        addProductCombo.load(Production("getProduct"));

        addEntryForm.attachEvent("onButtonClick", function (name) {
            switch (name) {
                case "add":
                    if (!addEntryForm.validate()) {
                        return eAlert("Input error!");
                    }

                    setDisable(["add", "clear"], addEntryForm, spEntryLayout.cells("b"));
                    let addEntryFormDP = new dataProcessor(Production("spEntryForm"));
                    addEntryFormDP.init(addEntryForm);
                    addEntryForm.save();

                    addEntryFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                        let message = tag.getAttribute("message");
                        switch (action) {
                            case "inserted":
                                rSpEntryGrid();
                                sAlert("Berhasil Menambahkan Record <br>" + message);
                                clearAllForm(addEntryForm);
                                setEnable(["add", "clear"], addEntryForm, spEntryLayout.cells("b"));
                                break;
                            case "error":
                                eAlert("Gagal Menambahkan Record <br>" + message);
                                setEnable(["add", "clear"], addEntryForm, spEntryLayout.cells("b"));
                                break;
                        }
                    });
                    break;
                case "clear":
                    clearAllForm(addEntryForm);
                    break;
                case "cancel":
                    rSpEntryGrid();
                    spEntryLayout.cells("b").collapse();
                    break;
            }
        });

        var spEntryListToolbar = spEntryLayout.cells("b").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "delete", text: "Hapus", type: "button", img: "delete.png"},
                {id: "edit", text: "Ubah", type: "button", img: "edit.png"},
                {id: "print", text: "Cetak Surat Pack", type: "button", img: "printer.png"},
            ]
        });

        spEntryListToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    rSpEntryGrid();
                    break;
                case "delete":
                    reqAction(spEntryGrid, Production("spEntryDelete"), 1, (err, res) => {
                        rSpEntryGrid();
                        res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                        res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
                    });
                    break;
                case "edit":
                    editSpEntryHandler();
                    break;
                case "print":
                    if(!spEntryGrid.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan diubah!");
                    }

                    let tabName = "pros_spack_entry_" + spEntryGrid.getSelectedRowId();
                    let noBatch = spEntryGrid.cells(spEntryGrid.getSelectedRowId(), 1).getValue();
                    if (!spEntryTabs.tabs(tabName)){
                        spEntryTabs.addTab(tabName, "Cetak " + noBatch, null, null, true, true);
                    } else {
                        spEntryTabs.tabs(tabName).setActive();
                    }

                    var printLayout = spEntryTabs.cells(tabName).attachLayout({
                        pattern: "2E",
                        cells: [
                            {id: "a", text: "Form Cetak No. Batch: " + noBatch},
                            {id: "b", text: "History Print No. Batch: " + noBatch},
                        ]
                    });

                    var printForm = printLayout.cells("a").attachForm([
                        {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Data Untuk Di Cetak", list: [
                            {type: "combo", name: "product_id", label: "Produk", labelWidth: 130, inputWidth: 250, readonly: true, required: true},
                            {type: "input", name: "no_batch", label: "No. Batch", labelWidth: 130, inputWidth:250, required: true},
                            {type: "block", offsetTop: 30, list: [
                                {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                                {type: "newcolumn"},
                                {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                                {type: "newcolumn"},
                                {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                            ]}
                        ]}
                    ]);
                    break;
            }
        });

        var spEntryStatusBar = spEntryLayout.cells("b").attachStatusBar();
        function spEntryGridCount() {
            spEntryStatusBar.setText("Total baris: " + spEntryGrid.getRowsNum());
        }

        var spEntryGrid = spEntryLayout.cells("b").attachGrid();
        spEntryGrid.setHeader("No,Nomor Batch,Produk");
        spEntryGrid.attachHeader("#rspan,#text_filter,#select_filter")
        spEntryGrid.setColSorting("int,str,str");
        spEntryGrid.setColTypes("rotxt,rotxt,rotxt");
        spEntryGrid.setColAlign("center,left,left");
        spEntryGrid.setInitWidthsP("5,25,70");
        spEntryGrid.enableSmartRendering(true);
        spEntryGrid.enableMultiselect(true);
        spEntryGrid.attachEvent("onXLE", function() {
            spEntryLayout.cells("b").progressOff();
        });
        spEntryGrid.init();
        
        function rSpEntryGrid() {
            spEntryLayout.cells("b").progressOn();
            spEntryGrid.clearAndLoad(Production("getSpEntryGrid"), spEntryGridCount);
        }

        rSpEntryGrid();

        function editSpEntryHandler() {
            if(!spEntryGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diubah!");
            }
            var spEntryWin = createWindow("sp_entry_win", "Edit Nomor Batch", 500, 300);
            myWins.window("sp_entry_win").skipMyCloseEvent = true;

            editEntryForm = spEntryWin.attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Surat Pack", list: [
                    {type: "hidden", name: "id", label: "ID", labelWidth: 130, inputWidth: 250, readonly: true},
                    {type: "combo", name: "product_id", label: "Produk", labelWidth: 130, inputWidth: 250, readonly: true, required: true},
                    {type: "input", name: "no_batch", label: "No. Batch", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "update", className: "button_update", offsetLeft: 30, value: "Update"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);
            fetchFormData(Production("spEntryForm", {id: spEntryGrid.getSelectedRowId()}), editEntryForm, null, null, setCombo);
            var editProductCombo = editEntryForm.getCombo("product_id");

            function setCombo() {
                editProductCombo.load(Production("getProduct", {select: editEntryForm.getItemValue("product_id")}));
            }

            editEntryForm.attachEvent("onButtonClick", function(name) {
                switch (name) {
                    case "update":
                        if (!editEntryForm.validate()) {
                            return eAlert("Input error!");
                        }	

                        setDisable(["update", "cancel"], editEntryForm, spEntryWin);
                        let editEntryFormDP = new dataProcessor(Production("spEntryForm"));
                        editEntryFormDP.init(editEntryForm);
                        editEntryForm.save();

                        editEntryFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rSpEntryGrid();
                                    setEnable(["update", "cancel"], editEntryForm, spEntryWin);
                                    closeWindow("sp_entry_win");                               							
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record<br>" + message);
                                    setEnable(["update", "cancel"], editEntryForm, spEntryWin);
                                    closeWindow("sp_entry_win");
                                    break;
                            }
                        });									
                        break;
                    case "cancel":
                        closeWindow("sp_entry_win");
                        break;
                }
            });
        }
    }
JS;

header('Content-Type: application/javascript');
echo $script;