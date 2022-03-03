<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

	function showVideoCategory() {	
        var catForm;
        var editCatForm;

        var catLayout = mainTab.cells("tutorial_video_categories").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form Kategori Video",
                    header: true,
                    collapse: true
                }
            ]
        });

        var catToolbar = mainTab.cells("tutorial_video_categories").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "add", text: "Tambah", type: "button", img: "add.png"},
                {id: "delete", text: "Hapus", type: "button", img: "delete.png"},
                {id: "edit", text: "Ubah", type: "button", img: "edit.png"},
                {id: "search", text: "", type: "buttonInput", width: 150}
            ]
        });

        if(userLogged.role !== "admin") {
            catToolbar.disableItem("add");
            catToolbar.disableItem("delete");
        }

        var catStatusBar = catLayout.cells("a").attachStatusBar();
        function catGridCount() {
            let catGridRows = catGrid.getRowsNum();
            catStatusBar.setText("Total baris: " + catGridRows);
        }

        var catGrid = catLayout.cells("a").attachGrid();
        catGrid.setHeader("No,Kategori,Created By,Updated By,DiBuat");
        catGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#text_filter")
        catGrid.setColSorting("str,str,str,str,str");
        catGrid.setColTypes("rotxt,rotxt,ron,rotxt,rotxt");
        catGrid.setColAlign("center,left,left,left,left");
        catGrid.setInitWidthsP("5,30,20,20,25");
        catGrid.enableSmartRendering(true);
        catGrid.enableMultiselect(true);
        catGrid.attachEvent("onXLE", function() {
            catLayout.cells("a").progressOff();
        });
        catGrid.init();

        function rCatGrid() {
            catLayout.cells("a").progressOn();
            catGrid.clearAndLoad(AppMaster2("getVideoCatGrid", {search: catToolbar.getValue("search")}), catGridCount);
        }

        rCatGrid();

        catToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    catToolbar.setValue("search","");
                    rCatGrid();
                    break;
                case "add":
                    addCatHandler();
                    break;
                case "delete":
                    deleteCatHandler();
                    break;
                case "edit":
                    editCatHandler();
                    break;
            }
        });

        catToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    rCatGrid();
                    catGrid.attachEvent("onGridReconstructed", catGridCount);
                    break;
            }
        });

        function deleteCatHandler() {
            reqAction(catGrid, AppMaster2("videoCatDelete"), 1, (err, res) => {
                rCatGrid();
                res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
            });
        }

        function addCatHandler() {
            catLayout.cells("b").expand();
            catLayout.cells("b").showView("add_categories");

            addCatForm = catLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Kategori Video", list: [
                    {type: "input", name: "name", label: "Kategori", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                        {type: "newcolumn"},
                        {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);
            
            addCatForm.attachEvent("onButtonClick", function (name) {
                switch (name) {
                    case "add":
                        if (!addCatForm.validate()) {
                            return eAlert("Input error!");
                        }

                        setDisable(["add", "clear"], addCatForm, catLayout.cells("b"));
                        let addCatFormDP = new dataProcessor(AppMaster2("catForm"));
                        addCatFormDP.init(addCatForm);
                        addCatForm.save();

                        addCatFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "inserted":
                                    sAlert("Berhasil Menambahkan Record <br>" + message);
                                    rCatGrid();
                                    clearAllForm(addCatForm);
                                    setEnable(["add", "clear"], addCatForm, catLayout.cells("b"));
                                    break;
                                case "error":
                                    eAlert("Gagal Menambahkan Record <br>" + message);
                                    setEnable(["add", "clear"], addCatForm, catLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "clear":
                        clearAllForm(addCatForm);
                        break;
                    case "cancel":
                        catLayout.cells("b").collapse();
                        break;
                }
            });
        }


        function editCatHandler() {
            if (!catGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diubah!");
            }

            catLayout.cells("b").expand();
            catLayout.cells("b").showView("edit_category");
            editCatForm = catLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Ubah Jenis Training", list: [
                    {type: "hidden", name: "id", label: "ID", readonly: true},
                    {type: "input", name: "name", label: "Kategori", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            fetchFormData(AppMaster2("catForm", {id: catGrid.getSelectedRowId()}), editCatForm);
            editCatForm.attachEvent("onButtonClick", function(name) {
                switch (name) {
                    case "update":
                        if (!editCatForm.validate()) {
                            return eAlert("Input error!");
                        }		

                        setDisable(["update", "cancel"], editCatForm, catLayout.cells("b"));
                        let editCatFormDP = new dataProcessor(AppMaster2("catForm"));
                        editCatFormDP.init(editCatForm);
                        editCatForm.save();

                        editCatFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rCatGrid();	
                                    catLayout.cells("b").progressOff();
                                    catLayout.cells("b").showView("add_category");
                                    catLayout.cells("b").collapse();
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record <br>" + message);
                                    setEnable(["update", "cancel"], editCatForm, catLayout.cells("b"));
                                    break;
                            }
                        });								
                        break;
                    case "cancel":
                        rCatGrid();
                        catLayout.cells("b").collapse();
                        catLayout.cells("b").showView("add_category");
                        break;
                }
            });
        }
    }

JS;

header('Content-Type: application/javascript');
echo $script;