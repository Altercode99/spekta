<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	
	function showMasterRank() {	
        var addRankForm;
        var editRankForm;

        var rankLayout = mainTab.cells("master_rank").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form Jabatan",
                    header: true,
                    collapse: true
                }
            ]
        });

        var rankToolbar = mainTab.cells("master_rank").attachToolbar({
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
            rankToolbar.disableItem("add");
            rankToolbar.disableItem("delete");
        }

        var rankStatusBar = rankLayout.cells("a").attachStatusBar();
        function rankGridCount() {
            let rankGridRows = rankGrid.getRowsNum();
            rankStatusBar.setText("Total baris: " + rankGridRows);
        }

        var rankGrid = rankLayout.cells("a").attachGrid();
        rankGrid.setHeader("No,Nama Jabatan,Created By,Updated By,DiBuat");
        rankGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#text_filter")
        rankGrid.setColSorting("str,str,str,str,str");
        rankGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt");
        rankGrid.setColAlign("center,left,left,left,left");
        rankGrid.setInitWidthsP("5,25,22,23,25");
        rankGrid.enableSmartRendering(true);
        rankGrid.enableMultiselect(true);
        rankGrid.attachEvent("onXLE", function() {
            rankLayout.cells("a").progressOff();
        });
        rankGrid.init();
        
        function rRankGrid() {
            rankLayout.cells("a").progressOn();
            rankGrid.clearAndLoad(AppMaster("rankGrid", {search: rankToolbar.getValue("search")}), rankGridCount);
        }

        rRankGrid();

        rankToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    rankToolbar.setValue("search","");
                    rRankGrid();
                    break;
                case "add":
                    addRankHandler();
                    break;
                case "delete":
                    deleteRankHandler();
                    break;
                case "edit":
                    editRankHandler();
                    break;
            }
        });

        rankToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    rRankGrid();
                    rankGrid.attachEvent("onGridReconstructed", rankGridCount);
                    break;
            }
        });

        function deleteRankHandler() {
            reqAction(rankGrid, AppMaster("rankDelete"), 1, (err, res) => {
                rRankGrid();
                res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
            });
        }

        function addRankHandler() {
            rankLayout.cells("b").expand();
            rankLayout.cells("b").showView("tambah_rank");

            addRankForm = rankLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Jabatan", list: [
                    {type: "input", name: "name", label: "Nama Jabatan", labelWidth: 130, inputWidth:250, required: true},
                    {type: "input", name: "grade", label: "Grade", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                        {type: "newcolumn"},
                        {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            addRankForm.attachEvent("onButtonClick", function (name) {
                switch (name) {
                    case "add":
                        if (!addRankForm.validate()) {
                            return eAlert("Input error!");
                        }

                        setDisable(["add", "clear"], addRankForm, rankLayout.cells("b"));
                        let addRankFormDP = new dataProcessor(AppMaster("rankForm"));
                        addRankFormDP.init(addRankForm);
                        addRankForm.save();

                        addRankFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "inserted":
                                    sAlert("Berhasil Menambahkan Record <br>" + message);
                                    rRankGrid();
                                    clearAllForm(addRankForm);
                                    setEnable(["add", "clear"], addRankForm, rankLayout.cells("b"));
                                    break;
                                case "error":
                                    eAlert("Gagal Menambahkan Record <br>" + message);
                                    setEnable(["add", "clear"], addRankForm, rankLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "clear":
                        clearAllForm(addRankForm);
                        break;
                    case "cancel":
                        rRankGrid();
                        rankLayout.cells("b").collapse();
                        break;
                }
            });
        }

        function editRankHandler() {
            if (!rankGrid.getSelectedRowId()) {
                eAlert("Pilih baris yang akan diubah!");
            }

            rankLayout.cells("b").expand();
            rankLayout.cells("b").showView("edit_rank");
            editRankForm = rankLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Ubah Jabatan", list: [
                    {type: "hidden", name: "id", label: "ID", readonly: true},
                    {type: "input", name: "name", label: "Nama Jabatan", labelWidth: 130, inputWidth:250, required: true},
                    {type: "input", name: "grade", label: "Grade", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            fetchFormData(AppMaster("rankForm", {id: rankGrid.getSelectedRowId()}), editRankForm);
            editRankForm.attachEvent("onButtonClick", function(name) {
                switch (name) {
                    case "update":
                        if (!editRankForm.validate()) {
                            return eAlert("Input error!");
                        }		

                        setDisable(["update", "cancel"], editRankForm, rankLayout.cells("b"));
                        var editRankFormDP = new dataProcessor(AppMaster("rankForm"));
                        editRankFormDP.init(editRankForm);
                        editRankForm.save();

                        editRankFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
                            var message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rRankGrid();	
                                    rankLayout.cells("b").progressOff();
                                    rankLayout.cells("b").showView("tambah_rank");
                                    rankLayout.cells("b").collapse();
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record <br>" + message);
                                    setEnable(["update", "cancel"], editRankForm, rankLayout.cells("b"));
                                    break;
                            }
                        });								
                        break;
                    case "cancel":
                        rankLayout.cells("b").collapse();
                        rankLayout.cells("b").showView("tambah_rank");
                        break;
                }
            });
        }

    }
JS;

header('Content-Type: application/javascript');
echo $script;

