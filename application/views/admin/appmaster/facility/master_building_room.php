<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	
	function showMasterBuildingRoom() {	
        var addRoomForm;
        var editRoomForm;

        var comboUrl = {
            building_id: {
                url: AppMaster("getBuilding"),
                reload: true
            }
        }

        var roomLayout = mainTab.cells("master_building_room").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form Ruangan",
                    header: true,
                    collapse: true
                }
            ]
        });

        var roomToolbar = mainTab.cells("master_building_room").attachToolbar({
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
            roomToolbar.disableItem("delete");
        }

        var roomStatusBar = roomLayout.cells("a").attachStatusBar();
        function roomGridCount() {
            let roomtGridRows = roomtGrid.getRowsNum();
            roomStatusBar.setText("Total baris: " + roomtGridRows);
        }

        var roomtGrid = roomLayout.cells("a").attachGrid();
        roomtGrid.setHeader("No,Nama Ruangan,Nama Gedung,Created By,Updated By,DiBuat");
        roomtGrid.attachHeader("#rspan,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter")
        roomtGrid.setColSorting("str,str,str,str,str,str");
        roomtGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        roomtGrid.setColAlign("center,left,left,left,left,left");
        roomtGrid.setInitWidthsP("5,20,20,15,15,25");
        roomtGrid.enableSmartRendering(true);
        roomtGrid.enableMultiselect(true);
        roomtGrid.attachEvent("onXLE", function() {
            roomLayout.cells("a").progressOff();
        });
        roomtGrid.init();

        function rRoomtGrid() {
            roomLayout.cells("a").progressOn();
            roomtGrid.clearAndLoad(AppMaster("buildRoomGrid", {search: roomToolbar.getValue("search")}), roomGridCount);
        }

        rRoomtGrid();

        roomToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    roomToolbar.setValue("search","");
                    rRoomtGrid();
                    break;
                case "add":
                    addRoomHandler();
                    break;
                case "delete":
                    deleteRoomHandler();
                    break;
                case "edit":
                    editRoomHandler();
                    break;
            }
        });

        roomToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    rRoomtGrid();
                    roomtGrid.attachEvent("onGridReconstructed", roomGridCount);
                    break;
            }
        });

        function deleteRoomHandler() {
            reqAction(roomtGrid, AppMaster("buildRoomDelete"), 1, (err, res) => {
                rRoomtGrid();
                res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
            });
        }

        function addRoomHandler() {
            roomLayout.cells("b").expand();
            roomLayout.cells("b").showView("tambah_building_room");

            addRoomForm = roomLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Ruangan", list: [
                    {type: "combo", name: "building_id", label: "Nama Gedung", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "input", name: "name", label: "Nama Ruangan", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                        {type: "newcolumn"},
                        {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            var addBuildCombo = addRoomForm.getCombo("building_id");
            addBuildCombo.load(AppMaster("getBuilding"));

            addRoomForm.attachEvent("onButtonClick", function (name) {
                switch (name) {
                    case "add":
                        if (!addRoomForm.validate()) {
                            return eAlert("Input error!");
                        }

                        setDisable(["add", "clear"], addRoomForm, roomLayout.cells("b"));
                        let addRoomFormDP = new dataProcessor(AppMaster("buildRoomForm"));
                        addRoomFormDP.init(addRoomForm);
                        addRoomForm.save();

                        addRoomFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "inserted":
                                    sAlert("Berhasil Menambahkan Record <br>" + message);
                                    rRoomtGrid();
                                    clearAllForm(addRoomForm, comboUrl);
                                    setEnable(["add", "clear"], addRoomForm, roomLayout.cells("b"));
                                    break;
                                case "error":
                                    eAlert("Gagal Menambahkan Record <br>" + message);
                                    setEnable(["add", "clear"], addRoomForm, roomLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "clear":
                        clearAllForm(addRoomForm, comboUrl);
                        break;
                    case "cancel":
                        roomLayout.cells("b").collapse();
                        break;
                }
            });
        }

        function editRoomHandler() {
            if (!roomtGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diubah!");
            }

            roomLayout.cells("b").expand();
            roomLayout.cells("b").showView("edit_building_room");
            editRoomForm = roomLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Ubah Ruangan", list: [
                    {type: "hidden", name: "id", label: "ID", readonly: true},
                    {type: "combo", name: "building_id", label: "Nama Gedung", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                    {type: "input", name: "name", label: "Nama Ruangan", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);
            isFormNumeric(editRoomForm, ["file_limit"]);

            var editBuildCombo = editRoomForm.getCombo("building_id");
            fetchFormData(AppMaster("buildRoomForm", {id: roomtGrid.getSelectedRowId()}), editRoomForm, null, null, setCombo);
            function setCombo() {
                editBuildCombo.load(AppMaster("getBuilding", {select: editRoomForm.getItemValue("building_id")}));
            }
            
            editRoomForm.attachEvent("onButtonClick", function(name) {
                switch (name) {
                    case "update":
                        if (!editRoomForm.validate()) {
                            return eAlert("Input error!");
                        }

                        setDisable(["update", "cancel"], editRoomForm, roomLayout.cells("b"));
                        let editRoomFormDP = new dataProcessor(AppMaster("buildRoomForm"));
                        editRoomFormDP.init(editRoomForm);
                        editRoomForm.save();

                        editRoomFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rRoomtGrid();
                                    roomLayout.cells("b").progressOff();
                                    roomLayout.cells("b").showView("tambah_building_room");
                                    roomLayout.cells("b").collapse();
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record <br>" + message);
                                    setEnable(["update", "cancel"], editRoomForm, roomLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "cancel":
                        rRoomtGrid();
                        roomLayout.cells("b").collapse();
                        roomLayout.cells("b").showView("tambah_building_room");
                        break;
                }
            });
        }
    }

JS;

header('Content-Type: application/javascript');
echo $script;
