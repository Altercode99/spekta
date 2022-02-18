<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	function showCathering() {	
        var addCathForm;
        var editCathForm;

        var cathLayout = mainTab.cells("ga_cathering_vendor").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form Katering",
                    header: true,
                    collapse: true
                }
            ]
        });

        var cathToolbar = mainTab.cells("ga_cathering_vendor").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "add", text: "Tambah", type: "button", img: "add.png"},
                {id: "delete", text: "Hapus", type: "button", img: "delete.png"},
                {id: "edit", text: "Ubah", type: "button", img: "edit.png", img_disabled: "edit_disabled.png"},
                {id: "active", text: "Aktifkan", type: "button", img: "check.png"},
                {id: "searchtext", text: "Cari : ", type: "text"},
                {id: "search", text: "", type: "buttonInput", width: 150}
            ]
        });

        if(userLogged.role !== "admin") {
            cathToolbar.disableItem("add");
            cathToolbar.disableItem("delete");
        }

        var cathStatusBar = cathLayout.cells("a").attachStatusBar();
        function cathGridCount() {
            let cathGridRows = cathGrid.getRowsNum();
            cathStatusBar.setText("Total baris: " + cathGridRows);
        }

        var cathGrid = cathLayout.cells("a").attachGrid();
        cathGrid.setHeader("No,Nama Vendor,Harga,Status,Masa Berakhir Kontrak,Created By,Updated By,DiBuat");
        cathGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        cathGrid.setColSorting("str,str,str,str,str,str,str,str");
        cathGrid.setColTypes("rotxt,rotxt,ron,rotxt,rotxt,rotxt,rotxt,rotxt");
        cathGrid.setColAlign("center,left,left,left,left,left,left,left");
        cathGrid.setInitWidthsP("5,25,15,15,20,15,15,25");
        cathGrid.enableSmartRendering(true);
        cathGrid.enableMultiselect(true);
        cathGrid.attachEvent("onXLE", function() {
            cathLayout.cells("a").progressOff();
        });
        cathGrid.setNumberFormat("0,000",2,".",",");
        cathGrid.init();

        function rCathGrid() {
            cathLayout.cells("a").progressOn();
            cathGrid.clearAndLoad(GAOther("catheringPriceGrid", {search: cathToolbar.getValue("search")}), cathGridCount);
        }

        rCathGrid();

        cathToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    cathToolbar.setValue("search","");
                    rCathGrid();
                    break;
                case "add":
                    addCathHandler();
                    break;
                case "delete":
                    deleteCathHandler();
                    break;
                case "edit":
                    editCathHandler();
                    break;
                case "active":
                    activeCathering();
                    break;
            }
        });

        function activeCathering() {
            if(!cathGrid.getSelectedRowId()) {
                return eAlert("Pilih vendor yang akan diakrifkan!");
            }

            let namaVendor = cathGrid.cells(cathGrid.getSelectedRowId(), 1).getValue();
            dhtmlx.modalbox({
                type: "alert-warning",
                title: "Konfirmasi Aktifasi Vendor",
                text: "Anda yakin akan mengaktifkan vendor " + namaVendor +"?",
                buttons: ["Ya", "Tidak"],
                callback: function (index) {
                    if (index == 0) {
                        reqJson(GAOther("setCathActive"), "POST", {id: cathGrid.getSelectedRowId()}, (err, res) => {
                            if(res.status === "success") {
                                rCathGrid();
                                sAlert(res.message);
                            } else {
                                eAlert(res.message);
                            }
                        });
                    }
                },
            });
		}

        cathToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    rCathGrid();
                    cathGrid.attachEvent("onGridReconstructed", cathGridCount);
                    break;
            }
        });

        function deleteCathHandler() {
            reqAction(cathGrid, GAOther("catheringDelete"), 1, (err, res) => {
                rCathGrid();
                res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
            });
        }

        function addCathHandler() {
            cathLayout.cells("b").expand();
            cathLayout.cells("b").showView("tambah_cathering");

            addCathForm = cathLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Jenis Training", list: [
                    {type: "input", name: "vendor_name", label: "Nama Vendor", labelWidth: 130, inputWidth:250, required: true},
                    {type: "input", name: "price", label: "Harga", labelWidth: 130, inputWidth:250, required: true, validate:"ValidNumeric"},
                    {type: "calendar", name: "expired", label: "Masa Berakhir Kontrak", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                        {type: "newcolumn"},
                        {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);
            isFormNumeric(addCathForm, ['price']);
            
            addCathForm.attachEvent("onButtonClick", function (name) {
                switch (name) {
                    case "add":
                        if (!addCathForm.validate()) {
                            return eAlert("Input error!");
                        }

                        setDisable(["add", "clear"], addCathForm, cathLayout.cells("b"));
                        let addCathFormDP = new dataProcessor(GAOther("catheringForm"));
                        addCathFormDP.init(addCathForm);
                        addCathForm.save();

                        addCathFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "inserted":
                                    sAlert("Berhasil Menambahkan Record <br>" + message);
                                    rCathGrid();
                                    clearAllForm(addCathForm);
                                    setEnable(["add", "clear"], addCathForm, cathLayout.cells("b"));
                                    break;
                                case "error":
                                    eAlert("Gagal Menambahkan Record <br>" + message);
                                    setEnable(["add", "clear"], addCathForm, cathLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "clear":
                        clearAllForm(addCathForm);
                        break;
                    case "cancel":
                        cathLayout.cells("b").collapse();
                        break;
                }
            });
        }

        function editCathHandler() {
            if (!cathGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diubah!");
            }

            cathLayout.cells("b").expand();
            cathLayout.cells("b").showView("edit_cathering");
            editCathForm = cathLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Ubah Jenis Training", list: [
                    {type: "hidden", name: "id", label: "ID", readonly: true},
                    {type: "input", name: "vendor_name", label: "Nama Vendor", labelWidth: 130, inputWidth:250, required: true},
                    {type: "input", name: "price", label: "Harga", labelWidth: 130, inputWidth:250, required: true, validate:"ValidNumeric"},
                    {type: "calendar", name: "expired", label: "Masa Berakhir Kontrak", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);
            isFormNumeric(editCathForm, ['price']);

            fetchFormData(GAOther("catheringForm", {id: cathGrid.getSelectedRowId()}), editCathForm);
            editCathForm.attachEvent("onButtonClick", function(name) {
                switch (name) {
                    case "update":
                        if (!editCathForm.validate()) {
                            return eAlert("Input error!");
                        }		

                        setDisable(["update", "cancel"], editCathForm, cathLayout.cells("b"));
                        let editCathFormDP = new dataProcessor(GAOther("catheringForm"));
                        editCathFormDP.init(editCathForm);
                        editCathForm.save();

                        editCathFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rCathGrid();	
                                    cathLayout.cells("b").progressOff();
                                    cathLayout.cells("b").showView("tambah_cathering");
                                    cathLayout.cells("b").collapse();
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record <br>" + message);
                                    setEnable(["update", "cancel"], editCathForm, cathLayout.cells("b"));
                                    break;
                            }
                        });								
                        break;
                    case "cancel":
                        rCathGrid();
                        cathLayout.cells("b").collapse();
                        cathLayout.cells("b").showView("tambah_cathering");
                        break;
                }
            });
        }

    }

JS;

header('Content-Type: application/javascript');
echo $script;
