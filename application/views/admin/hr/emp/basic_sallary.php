<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

	function showBasicSallary() {	
        var editSallaryForm;

        var sallaryLayout = mainTab.cells("basic_sallary").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form Gaji",
                    header: true,
                    collapse: true
                }
            ]
        });

        var sallaryToolbar = mainTab.cells("basic_sallary").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "edit", text: "Ubah", type: "button", img: "edit.png", img_disabled: "edit_disabled.png"},
                {id: "searchtext", text: "Cari : ", type: "text"},
                {id: "search", text: "", type: "buttonInput", width: 150}
            ]
        });

        var sallaryMenu =  mainTab.cells("basic_sallary").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "update", text: "Update Grid", img: "update.png"}
            ]
        });

        var sallaryStatusBar = sallaryLayout.cells("a").attachStatusBar();
        function sallaryGridCount() {
            let sallaryGridRows = sallaryGrid.getRowsNum();
            sallaryStatusBar.setText("Total baris: " + sallaryGridRows);
        }

        var sallaryGrid = sallaryLayout.cells("a").attachGrid();
        sallaryGrid.setHeader("No,Nama Karyawan,Gaji Pokok,Total Gaji,Premi Lembur,Jam Lembur,Jabatan,Sub Unit,Bagian,Sub Bagian,Created By,Updated By,DiBuat");
        sallaryGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter")
        sallaryGrid.setColSorting("str,str,str,str,str,str,str,str,str,str,str,str,str");
        sallaryGrid.setColTypes("rotxt,rotxt,edn,ron,ron,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        sallaryGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left");
        sallaryGrid.setInitWidthsP("5,25,20,20,20,20,20,20,20,35,20,20,25");
        sallaryGrid.enableSmartRendering(true);
        sallaryGrid.setEditable(true);
        sallaryGrid.attachEvent("onXLE", function() {
            sallaryLayout.cells("a").progressOff();
        });
        sallaryGrid.setNumberFormat("0,000",2,".",",");
        sallaryGrid.setNumberFormat("0,000",3,".",",");
        sallaryGrid.setNumberFormat("0,000",4,".",",");
        sallaryGrid.init();
        isGridNumeric(sallaryGrid, [2]);

        function setGridDP() {
            sallaryGridDP = new dataProcessor(Sallary('updateSallaryBatch'));
            sallaryGridDP.setTransactionMode("POST", true);
            sallaryGridDP.setUpdateMode("Off");
            sallaryGridDP.init(sallaryGrid);
        }

        setGridDP();

        function rSallaryGrid() {
            isLogin();
            sallaryLayout.cells("a").progressOn();
            sallaryGrid.clearAndLoad(Sallary("sallaryGrid", {search: sallaryToolbar.getValue("search"), gt_rank_id: 6}), sallaryGridCount);
        }

        rSallaryGrid();

        sallaryToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    sallaryToolbar.setValue("search","");
                    rSallaryGrid();
                    break;
                case "edit":
                    editSallaryHandler();
                    break;
            }
        });

        sallaryToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    rSallaryGrid();
                    sallaryGrid.attachEvent("onGridReconstructed", sallaryGridCount);
                    break;
            }
        });

        sallaryMenu.attachEvent("onClick", function(id) {
            switch (id) {
                case "update":
                    if(!sallaryGrid.getChangedRows()) {
                        return eAlert("Belum ada row yang di edit!");
                    }

                    sallaryMenu.setItemDisabled("update");
                    sallaryLayout.cells("a").progressOn();
                    sallaryGridDP.sendData();
                    sallaryGridDP.attachEvent('onAfterUpdate', function(id, action, tid, tag) {
                        let message = tag.getAttribute('message');
                        switch (action) {
                            case 'updated':
                                sAlert(message);
                                rSallaryGrid();
                                sallaryMenu.setItemEnabled("update");
                                sallaryLayout.cells("a").progressOff();
                                setGridDP();
                                break;
                            case 'error':
                                eAlert(message);
                                sallaryMenu.setItemEnabled("update");
                                sallaryLayout.cells("a").progressOff();
                                break;
                        }
                    });
                    break;
            }
        });

        function editSallaryHandler() {
            if (!sallaryGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diupdate!");
            }

            sallaryLayout.cells("b").expand();
            sallaryLayout.cells("b").showView("edit_sallary");
            editSallaryForm = sallaryLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Update Data Gaji", list: [
                    {type: "hidden", name: "id", label: "Emp ID", readonly: true},
                    {type: "input", name: "employee_name", label: "Nama Karyawan", labelWidth: 130, inputWidth:250, readonly: true},
                    {type: "input", name: "basic_sallary", label: "Gaji Pokok", labelWidth: 130, inputWidth:250, required: true, validate:"ValidNumeric"},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);
            isFormNumeric(editSallaryForm, ['basic_sallary']);

            fetchFormData(Sallary("sallaryForm", {equal_id: sallaryGrid.getSelectedRowId()}), editSallaryForm);
            editSallaryForm.attachEvent("onButtonClick", function(name) {
                switch (name) {
                    case "update":
                        isLogin();
                        if (!editSallaryForm.validate()) {
                            return eAlert("Input error!");
                        }		

                        setDisable(["update", "cancel"], editSallaryForm, sallaryLayout.cells("b"));
                        let editSallaryFormDP = new dataProcessor(Sallary("updateSallary"));
                        editSallaryFormDP.init(editSallaryForm);
                        editSallaryForm.save();

                        editSallaryFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Memperbarui Record <br>" + message);
                                    rSallaryGrid();	
                                    sallaryLayout.cells("b").progressOff();
                                    sallaryLayout.cells("b").collapse();
                                    clearAllForm(editSallaryForm);
                                    break;
                                case "error":
                                    eAlert("Gagal Memperbarui Record <br>" + message);
                                    setEnable(["update", "cancel"], editSallaryForm, sallaryLayout.cells("b"));
                                    break;
                            }
                        });								
                        break;
                    case "cancel":
                        rSallaryGrid();
                        clearAllForm(editSallaryForm);
                        sallaryLayout.cells("b").collapse();
                        break;
                }
            });
        }

    }

JS;

header('Content-Type: application/javascript');
echo $script;
    