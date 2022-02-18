<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

	function showNationalFree() {	
        var freeYear = "free_year";
        var freeMonth = "free_month";
        var addFreeForm;
        var editFreeForm;

        var freeLayout = mainTab.cells("national_freeday").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form Libur Nasional",
                    header: true,
                    collapse: true
                }
            ]
        });

        var freeToolbar = mainTab.cells("national_freeday").attachToolbar({
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

        var freeMenu =  mainTab.cells("national_freeday").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "filter", text: genSelectMonth(freeYear, freeMonth)}
            ]
        });


        $("#"+freeYear).on("change", function() {
            rFreeGrid();
        });

        $("#"+freeMonth).on("change", function() {
            rFreeGrid();
        });

        var freeStatusBar = freeLayout.cells("a").attachStatusBar();
        function freeGridCount() {
            let freeGridRows = freeGrid.getRowsNum();
            freeStatusBar.setText("Total baris: " + freeGridRows);
        }

        var freeGrid = freeLayout.cells("a").attachGrid();
        freeGrid.setHeader("No,Tanggal Libur Nasional,Keterangan,Created By,Updated By,DiBuat");
        freeGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        freeGrid.setColSorting("str,str,str,str,str,str");
        freeGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        freeGrid.setColAlign("center,left,left,left,left,left");
        freeGrid.setInitWidthsP("5,20,25,15,15,25");
        freeGrid.enableSmartRendering(true);
        freeGrid.enableMultiselect(true);
        freeGrid.attachEvent("onXLE", function() {
            freeLayout.cells("a").progressOff();
        });
        freeGrid.init();
        
        function rFreeGrid() {
            freeLayout.cells("a").progressOn();
            let year = $("#"+freeYear).val();
            let month = $("#"+freeMonth).val();
            freeGrid.clearAndLoad(AppMaster2("freeGrid", {search: freeToolbar.getValue("search"), year_date: year, month_date: month}), freeGridCount);
        };

        rFreeGrid();

        freeToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    freeToolbar.setValue("search","");
                    rFreeGrid();
                    break;
                case "add":
                    addFreeHandler();
                    break;
                case "delete":
                    deleteFreeHandler();
                    break;
                case "edit":
                    editFreeHandler();
                    break;
            }
        });

        freeToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    rFreeGrid();
                    freeGrid.attachEvent("onGridReconstructed", freeGridCount);
                    break;
            }
        });

        function deleteFreeHandler() {
            reqAction(freeGrid, AppMaster2("freeDelete"), 1, (err, res) => {
                rFreeGrid();
                res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
            });
        }

        function addFreeHandler() {
            freeLayout.cells("b").expand();
            freeLayout.cells("b").showView("tambah_national");

            addFreeForm = freeLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Hari Libur", list: [
                    {type: "calendar", name: "date", label: "Tanggal", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "input", name: "description", label: "Keterangan", required: true, labelWidth: 130, inputWidth: 250, rows: 3},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                        {type: "newcolumn"},
                        {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            addFreeForm.attachEvent("onButtonClick", function (name) {
                switch (name) {
                    case "add":
                        if (!addFreeForm.validate()) {
                            return eAlert("Input error!");
                        }

                        setDisable(["add", "clear"], addFreeForm, freeLayout.cells("b"));
                        let addFreeFormDP = new dataProcessor(AppMaster2("freeForm"));
                        addFreeFormDP.init(addFreeForm);
                        addFreeForm.save();

                        addFreeFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "inserted":
                                    sAlert("Berhasil Menambahkan Record <br>" + message);
                                    rFreeGrid();
                                    clearAllForm(addFreeForm);
                                    setEnable(["add", "clear"], addFreeForm, freeLayout.cells("b"));
                                    break;
                                case "error":
                                    eAlert("Gagal Menambahkan Record <br>" + message);
                                    setEnable(["add", "clear"], addFreeForm, freeLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "clear":
                        clearAllForm(addFreeForm);
                        break;
                    case "cancel":
                        rFreeGrid();
                        freeLayout.cells("b").collapse();
                        break;
                }
            });
        }

        function editFreeHandler() {
            if (!freeGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diubah!");
            }

            freeLayout.cells("a").progressOff();
            freeLayout.cells("b").expand();
            freeLayout.cells("b").showView("edit_national");
            editFreeForm = freeLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Ubah Hari Libur", list: [
                    {type: "hidden", name: "id", label: "ID", readonly: true},
                    {type: "calendar", name: "date", label: "Tanggal", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "input", name: "description", label: "Keterangan", required: true, labelWidth: 130, inputWidth: 250, rows: 3},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);
            
            fetchFormData(AppMaster2("freeForm", {id: freeGrid.getSelectedRowId()}), editFreeForm);
            editFreeForm.attachEvent("onButtonClick", function(name) {
                switch (name) {
                    case "update":
                        if (!editFreeForm.validate()) {
                            return eAlert("Input error!");
                        }	
                        
                        setDisable(["update", "cancel"], editFreeForm, freeLayout.cells("b"));
                        let editFreeFormDP = new dataProcessor(AppMaster2("freeForm"));
                        editFreeFormDP.init(editFreeForm);
                        editFreeForm.save();

                        editFreeFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rFreeGrid();
                                    freeLayout.cells("b").progressOff();
                                    freeLayout.cells("b").showView("tambah_national");
                                    freeLayout.cells("b").collapse();
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record <br>" + message);
                                    setEnable(["update", "cancel"], editFreeForm, freeLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "cancel":
                        freeLayout.cells("b").collapse();
                        freeLayout.cells("b").showView("tambah_national");
                        break;
                }
            });
        }
    }

JS;

header('Content-Type: application/javascript');
echo $script;
        