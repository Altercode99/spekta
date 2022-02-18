<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

	function showEmpPin() {	
        var addPinForm;
        var editPinForm;

        var pinLayout = mainTab.cells("hr_data_pin_karyawan").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form PIN Karyawan",
                    header: true,
                    collapse: true
                }
            ]
        });

        var pinToolbar = mainTab.cells("hr_data_pin_karyawan").attachToolbar({
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
            pinToolbar.disableItem("add");
            pinToolbar.disableItem("delete");
        }

        var pinStatusBar = pinLayout.cells("a").attachStatusBar();
        function pinGridCount() {
            let pinGridRows = pinGrid.getRowsNum();
            pinStatusBar.setText("Total baris: " + pinGridRows);
        }

        var pinGrid = pinLayout.cells("a").attachGrid();
        pinGrid.setHeader("No,Nama Karyawan,NIP,PIN,Jabatan,Sub Unit,Bagian,Sub Bagian,Created By,Updated By,DiBuat");
        pinGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter")
        pinGrid.setColSorting("str,str,str,str,str,str,str,str,str,str,str");
        pinGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        pinGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left");
        pinGrid.setInitWidthsP("5,20,15,20,20,20,20,20,20,20,25");
        pinGrid.enableSmartRendering(true);
        pinGrid.enableMultiselect(true);
        pinGrid.attachEvent("onXLE", function() {
            pinLayout.cells("a").progressOff();
        });
        pinGrid.init();
        
        function rPinGrid() {
            pinLayout.cells("a").progressOn();
            pinGrid.clearAndLoad(Emp("pinGrid", {search: pinToolbar.getValue("search")}), pinGridCount);
        }

        rPinGrid();

        pinToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    pinToolbar.setValue("search","");
                    rPinGrid();
                    break;
                case "add":
                    addPinHandler();
                    break;
                case "delete":
                    deletePinHandler();
                    break;
                case "edit":
                    editPinHandler();
                    break;
            }
        });

        pinToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    rPinGrid();
                    pinGrid.attachEvent("onGridReconstructed", pinGridCount);
                    break;
            }
        });

        function deletePinHandler() {
            reqAction(pinGrid, Emp("pinDelete"), 1, (err, res) => {
                rPinGrid();
                res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
            });
        }

        function addPinHandler() {
            pinLayout.cells("b").expand();
            pinLayout.cells("b").showView("tambah_pin");

            addPinForm = pinLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Jenis Training", list: [
                    {type: "combo", name: "nip", label: "Nama Karyawan", labelWidth: 130, inputWidth: 250, 
                        validate: "NotEmpty", 
                        required: true
                    },
                    {type: "input", name: "pin", label: "PIN", labelWidth: 130, inputWidth: 250, required: true, validate:"ValidNumeric"},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                        {type: "newcolumn"},
                        {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            isFormNumeric(addPinForm, ['pin'])

            var nipCombo = addPinForm.getCombo("nip");
            nipCombo.enableFilteringMode(true, 'nip');
            nipCombo.attachEvent("onDynXLS", nipComboFilter);

            function nipComboFilter(text){
                nipCombo.clearAll();
                if(text.length > 3) {
                    dhx.ajax.get(User('getEmps', {name: text}), function(xml){
                        if(xml.xmlDoc.responseText) {
                            nipCombo.load(xml.xmlDoc.responseText);
                            nipCombo.openSelect();
                        }
                    });
                }
            };

            addPinForm.attachEvent("onButtonClick", function (name) {
                switch (name) {
                    case "add":
                        if (!addPinForm.validate()) {
                            return eAlert("Input error!");
                        }

                        if(addPinForm.getItemValue("pin").length > 6) {
                            return eAlert("Maksimum 6 digit!");
                        }

                        setDisable(["add", "clear"], addPinForm, pinLayout.cells("b"));
                        let addPinFormDP = new dataProcessor(Emp("pinForm"));
                        addPinFormDP.init(addPinForm);
                        addPinForm.save();

                        addPinFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "inserted":
                                    sAlert("Berhasil Menambahkan Record <br>" + message);
                                    rPinGrid();
                                    clearAllForm(addPinForm);
                                    clearComboNoReload(addPinForm, "nip");
                                    setEnable(["add", "clear"], addPinForm, pinLayout.cells("b"));
                                    break;
                                case "error":
                                    eAlert("Gagal Menambahkan Record <br>" + message);
                                    setEnable(["add", "clear"], addPinForm, pinLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "clear":
                        clearAllForm(addPinForm);
                        break;
                    case "cancel":
                        pinLayout.cells("b").collapse();
                        break;
                }
            });
        }

        function editPinHandler() {
            if (!pinGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diubah!");
            }

            pinLayout.cells("b").expand();
            pinLayout.cells("b").showView("edit_pin");
            editPinForm = pinLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Ubah Jenis Training", list: [
                    {type: "hidden", name: "id", label: "ID", readonly: true},
                    {type: "input", name: "employee_name", label: "Nama Karyawan", labelWidth: 130, inputWidth:250, required: true},
                    {type: "input", name: "pin", label: "PIN", labelWidth: 130, inputWidth: 250, required: true, validate:"ValidNumeric"},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            fetchFormData(Emp("pinForm", {id: pinGrid.getSelectedRowId()}), editPinForm);
            editPinForm.attachEvent("onButtonClick", function(name) {
                switch (name) {
                    case "update":
                        if (!editPinForm.validate()) {
                            return eAlert("Input error!");
                        }		

                        setDisable(["update", "cancel"], editPinForm, pinLayout.cells("b"));
                        let editPinFormDP = new dataProcessor(Emp("pinForm"));
                        editPinFormDP.init(editPinForm);
                        editPinForm.save();

                        editPinFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rPinGrid();	
                                    pinLayout.cells("b").progressOff();
                                    pinLayout.cells("b").showView("tambah_pin");
                                    pinLayout.cells("b").collapse();
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record <br>" + message);
                                    setEnable(["update", "cancel"], editPinForm, pinLayout.cells("b"));
                                    break;
                            }
                        });								
                        break;
                    case "cancel":
                        rPinGrid();
                        pinLayout.cells("b").collapse();
                        pinLayout.cells("b").showView("tambah_pin");
                        break;
                }
            });
        }
    }

JS;

header('Content-Type: application/javascript');
echo $script;
    