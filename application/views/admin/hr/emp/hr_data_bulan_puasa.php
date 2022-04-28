<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

	function showBulanPuasa() {	
        var addPuasaForm;
        var editPuasaForm;

        var puasaLayout = mainTab.cells("hr_data_bulan_puasa").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form Bulan Puasa",
                    header: true,
                    collapse: true
                }
            ]
        });

        var puasaToolbar = mainTab.cells("hr_data_bulan_puasa").attachToolbar({
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

        var puasaStatusBar = puasaLayout.cells("a").attachStatusBar();
        function puasaGridCount() {
            puasaStatusBar.setText("Total baris: " + puasaGrid.getRowsNum());
        }

        var puasaGrid = puasaLayout.cells("a").attachGrid();
        puasaGrid.setHeader("No,Tahun,Tanggal,Created By,Updated By,DiBuat");
        puasaGrid.attachHeader("#rspan,#text_filter,#text_filter,#select_filter,#select_filter,#text_filter")
        puasaGrid.setColSorting("int,str,str,str,str,str");
        puasaGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        puasaGrid.setColAlign("center,left,left,left,left,left");
        puasaGrid.setInitWidthsP("5,15,25,15,15,25");
        puasaGrid.enableSmartRendering(true);
        puasaGrid.enableMultiselect(true);
        puasaGrid.attachEvent("onXLE", function() {
            puasaLayout.cells("a").progressOff();
        });
        puasaGrid.init();
        
        function rPuasaGrid() {
            puasaLayout.cells("a").progressOn();
            puasaGrid.clearAndLoad(AppMaster2("getPuasaGrid", {search: puasaToolbar.getValue("search")}), puasaGridCount);
        };

        rPuasaGrid();

        puasaToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    puasaToolbar.setValue("search","");
                    rPuasaGrid();
                    break;
                case "add":
                    addPuasaHandler();
                    break;
                case "delete":
                    reqAction(puasaGrid, AppMaster2("puasaDelete"), 1, (err, res) => {
                        rPuasaGrid();
                        res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                        res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
                    });
                    break;
                case "edit":
                    editPuasaHandler();
                    break;
            }
        });

        puasaToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    rPuasaGrid();
                    puasaGrid.attachEvent("onGridReconstructed", puasaGridCount);
                    break;
            }
        });

        let years = [];
            let date = new Date();
            for (let i = 2021;i <= date.getFullYear();i++) {
                years.push({value: i, text: i});
            }

        function addPuasaHandler() {
            puasaLayout.cells("b").expand();
            puasaLayout.cells("b").showView("tambah_puasa");
            addPuasaForm = puasaLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Hari Bulan Puasa", list: [
                    {type: "combo", name: "year", label: "Tahun", readonly: true, labelWidth: 130, inputWidth: 250, required: true,
                        validate: "NotEmpty",
                        options: years
                    },
                    {type: "calendar", name: "start_date", label: "Tanggal Mulai Puasa", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "calendar", name: "end_date", label: "Tanggal Akhir Puasa", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                        {type: "newcolumn"},
                        {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            addPuasaForm.attachEvent("onButtonClick", function (name) {
                switch (name) {
                    case "add":
                        if (!addPuasaForm.validate()) {
                            return eAlert("Input error!");
                        }

                        setDisable(["add", "clear"], addPuasaForm, puasaLayout.cells("b"));
                        let addPuasaFormDP = new dataProcessor(AppMaster2("puasaForm"));
                        addPuasaFormDP.init(addPuasaForm);
                        addPuasaForm.save();

                        addPuasaFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "inserted":
                                    sAlert("Berhasil Menambahkan Record <br>" + message);
                                    rPuasaGrid();
                                    clearAllForm(addPuasaForm);
                                    setEnable(["add", "clear"], addPuasaForm, puasaLayout.cells("b"));
                                    break;
                                case "error":
                                    eAlert("Gagal Menambahkan Record <br>" + message);
                                    setEnable(["add", "clear"], addPuasaForm, puasaLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "clear":
                        clearAllForm(addPuasaForm);
                        break;
                    case "cancel":
                        rPuasaGrid();
                        puasaLayout.cells("b").collapse();
                        break;
                }
            });
        }

        function editPuasaHandler() {
            if (!puasaGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diubah!");
            }

            puasaLayout.cells("a").progressOff();
            puasaLayout.cells("b").expand();
            puasaLayout.cells("b").showView("edit_puasa");
            editPuasaForm = puasaLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Ubah Hari Bulan Puasa", list: [
                    {type: "hidden", name: "id", label: "ID", readonly: true},
                    {type: "input", name: "year", label: "Tahun", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "calendar", name: "start_date", label: "Tanggal Mulai Puasa", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "calendar", name: "end_date", label: "Tanggal Akhir Puasa", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);
            
            fetchFormData(AppMaster2("puasaForm", {id: puasaGrid.getSelectedRowId()}), editPuasaForm);
            editPuasaForm.attachEvent("onButtonClick", function(name) {
                switch (name) {
                    case "update":
                        if (!editPuasaForm.validate()) {
                            return eAlert("Input error!");
                        }	
                        
                        setDisable(["update", "cancel"], editPuasaForm, puasaLayout.cells("b"));
                        let editPuasaFormDP = new dataProcessor(AppMaster2("puasaForm"));
                        editPuasaFormDP.init(editPuasaForm);
                        editPuasaForm.save();

                        editPuasaFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rPuasaGrid();
                                    puasaLayout.cells("b").progressOff();
                                    puasaLayout.cells("b").showView("tambah_puasa");
                                    puasaLayout.cells("b").collapse();
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record <br>" + message);
                                    setEnable(["update", "cancel"], editPuasaForm, puasaLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "cancel":
                        puasaLayout.cells("b").collapse();
                        puasaLayout.cells("b").showView("tambah_puasa");
                        break;
                }
            });
        }
    }

JS;

header('Content-Type: application/javascript');
echo $script;
        