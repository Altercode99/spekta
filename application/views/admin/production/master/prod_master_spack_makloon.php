<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
    		
    function showMasterMakloon() {
        var addMakloonForm;
        var editMakloonForm;

        var makloonLayout = mainTab.cells("prod_master_spack_makloon").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form Makloon",
                    header: true,
                    collapse: true
                }
            ]
        });

        var makloonToolbar = mainTab.cells("prod_master_spack_makloon").attachToolbar({
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

        var makloonSB = makloonLayout.cells("a").attachStatusBar();
        function makloonGridCount() {
            makloonSB.setText("Total baris: " +  makloonGrid.getRowsNum());
        }

        var makloonGrid = makloonLayout.cells("a").attachGrid();
        makloonGrid.setHeader("No,Nama Makloon,Created By,Updated By,DiBuat");
        makloonGrid.attachHeader("#rspan,#text_filter,#select_filter,#select_filter,#text_filter")
        makloonGrid.setColSorting("int,str,str,str,str");
        makloonGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt");
        makloonGrid.setColAlign("center,left,left,left,left");
        makloonGrid.setInitWidthsP("5,33,20,20,22");
        makloonGrid.enableSmartRendering(true);
        makloonGrid.enableMultiselect(true);
        makloonGrid.attachEvent("onXLE", function() {
            makloonLayout.cells("a").progressOff();
        });
        makloonGrid.init();

        function rMakloonGrid() {
            makloonLayout.cells("a").progressOn();
            makloonGrid.clearAndLoad(Production("getMasterMakloonGrid", {search: makloonToolbar.getValue("search")}), makloonGridCount);
        }

        rMakloonGrid();

        makloonToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    makloonToolbar.setValue("search","");
                    rMakloonGrid();
                    break;
                case "add":
                    addMakloonHandler();
                    break;
                case "delete":
                    reqAction(makloonGrid, Production("makloonDelete"), 1, (err, res) => {
                        rMakloonGrid();
                        res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                        res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
                    });
                    break;
                case "edit":
                    editMakloonHandler();
                    break;
                case "export":
                    makloonGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    sAlert("Export Data Dimulai");
                    break;
            }
        });

        makloonToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    rMakloonGrid();
                    makloonGrid.attachEvent("onGridReconstructed", makloonGridCount);
                    break;
            }
        });

        function addMakloonHandler() {
            makloonLayout.cells("b").expand();
            makloonLayout.cells("b").showView("tambah_makloon");

            addMakloonForm = makloonLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Makloon", list: [
                    {type: "input", name: "name", label: "Nama Makloon", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                        {type: "newcolumn"},
                        {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            addMakloonForm.attachEvent("onButtonClick", function(id) {
                switch (id) {
                    case "add":
                        if(!addMakloonForm.validate()) {
                            return eAlert("Input error!");
                        }

                        setDisable(["add", "clear"], addMakloonForm, makloonLayout.cells("b"));
                        let addMakloonFormDP = new dataProcessor(Production("makloonForm"));
                        addMakloonFormDP.init(addMakloonForm);
                        addMakloonForm.save();

                        addMakloonFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "inserted":
                                    sAlert("Berhasil Menambahkan Record <br>" + message);
                                    rMakloonGrid();
                                    clearAllForm(addMakloonForm);
                                    setEnable(["add", "clear"], addMakloonForm, makloonLayout.cells("b"));
                                    break;
                                case "error":
                                    eAlert("Gagal Menambahkan Record <br>" + message);
                                    setEnable(["add", "clear"], addMakloonForm, makloonLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "clear":
                        clearAllForm(addMakloonForm);
                        break;
                    case "cancel":
                        makloonLayout.cells("b").collapse();
                        break;
                }
            });
        }

        function editMakloonHandler() {
            if(!makloonGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diubah!");
            }

            makloonLayout.cells("b").expand();
            makloonLayout.cells("b").showView("edit_tipe_produk");

            editMakloonForm = makloonLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Edit Tipe Produk", list: [
                    {type: "hidden", name: "id", label: "ID", readonly: true},
                    {type: "input", name: "name", label: "Nama Makloon", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]},
            ]);

            fetchFormData(Production("makloonForm", {id: makloonGrid.getSelectedRowId()}), editMakloonForm);
            editMakloonForm.attachEvent("onButtonClick", function(id) {
                switch (id) {
                    case "update":
                        setDisable(["update", "cancel"], editMakloonForm, makloonLayout.cells("b"));

                        let editMakloonFormDP = new dataProcessor(Production("makloomForm"));
                        editMakloonFormDP.init(editMakloonForm);
                        editMakloonForm.save();

                        editMakloonFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                        let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rMakloonGrid();
                                    makloonLayout.cells("b").progressOff();
                                    makloonLayout.cells("b").showView("tambah_makloon");
                                    makloonLayout.cells("b").collapse();
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record <br>" + message);
                                    setEnable(["update", "cancel"], editMakloonForm, makloonLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "cancel":
                        makloonLayout.cells("b").collapse();
                        makloonLayout.cells("b").showView("edit_makloon");
                        break;
                }
            });
        }
    }

JS;

header('Content-Type: application/javascript');
echo $script;