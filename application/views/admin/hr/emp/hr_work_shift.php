<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

	function showWorkShift() {	
        var addShiftForm;
        var editShiftForm;

        var times = createTime();

        var comboUrl = {
            department_id: {
                url: Emp("getDepartment"),
                reload: true
            },
            sub_department_id: {
                url: Emp("getSubDepartment"),
                reload: false
            },
            division_id: {
                url: Emp("getDivision"),
                reload: false
            }
        }

        var shiftLayout = mainTab.cells("hr_work_shift").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form Shift Kerja",
                    header: true,
                    collapse: true
                }
            ]
        });

        var shiftToolbar = mainTab.cells("hr_work_shift").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "add", text: "Tambah", type: "button", img: "add.png"},
                {id: "delete", text: "Hapus", type: "button", img: "delete.png"},
                {id: "edit", text: "Ubah", type: "button", img: "edit.png"},
                {id: "searchtext", text: "Cari : ", type: "text"},
                {id: "search", text: "", type: "buttonInput", width: 150}
            ]
        });

        var shiftStatusBar = shiftLayout.cells("a").attachStatusBar();
        function shiftGridCount() {
            shiftStatusBar.setText("Total baris: " + shiftGrid.getRowsNum());
        }

        var shiftGrid = shiftLayout.cells("a").attachGrid();
        shiftGrid.setHeader("No,Sub Unit,Bagian,Sub Bagian,Shift Kerja,Waktu Kerja,Created By,Updated By,DiBuat");
        shiftGrid.attachHeader("#rspan,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#select_filter,#select_filter,#text_filter")
        shiftGrid.setColSorting("int,str,str,str,str,str,str,str,str");
        shiftGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        shiftGrid.setColAlign("center,left,left,left,left,left,left,left,left");
        shiftGrid.setInitWidthsP("5,25,25,25,10,10,15,15,25");
        shiftGrid.enableSmartRendering(true);
        shiftGrid.enableMultiselect(true);
        shiftGrid.attachEvent("onXLE", function() {
            shiftLayout.cells("a").progressOff();
        });
        shiftGrid.init();
        
        function rShiftGrid() {
            shiftLayout.cells("a").progressOn();
            shiftGrid.clearAndLoad(Absen("getShiftGrid", {search: shiftToolbar.getValue("search")}), shiftGridCount);
        }

        rShiftGrid();

        shiftToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    shiftToolbar.setValue("search","");
                    rShiftGrid();
                    break;
                case "add":
                    addShiftHandler();
                    break;
                case "delete":
                    reqAction(shiftGrid, Absen("shiftDelete"), 1, (err, res) => {
                        rShiftGrid();
                        res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                        res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
                    });
                    break;
                case "edit":
                    editShiftHandler();
                    break;
            }
        });

        function addShiftHandler() {
            shiftLayout.cells("b").expand();
            shiftLayout.cells("b").showView("tambah_shift");

            addShiftForm = shiftLayout.cells("b").attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Shift", list: [
                    {type: "combo", name: "department_id", label: "Sub Unit", readonly: true, labelWidth: 130, inputWidth: 250},
                    {type: "combo", name: "sub_department_id", label: "Bagian", readonly: true, labelWidth: 130, inputWidth: 250},
                    {type: "combo", name: "division_id", label: "Sub Bagian", readonly: true, labelWidth: 130, inputWidth: 250},
                    {type: "combo", name: "name", label: "Shift", readonly: true, labelWidth: 130, inputWidth: 250, required: true,
                        validate: "NotEmpty",
                        options:[
                            {value: "", text: ""},
                            {value: "Shift 1", text: "Shift 1"},
                            {value: "Shift 2", text: "Shift 2"},
                            {value: "Shift 3", text: "Shift 3"},
                            {value: "Custom", text: "Custom"},
                        ]
                    },
                    {type: "combo", name: "work_start", label: "Waktu Mulai", labelWidth: 130, inputWidth: 250, required: true, readonly: true,
                        validate: "NotEmpty", 
                        options: times.startTimes
                    },
                    {type: "combo", name: "work_end", label: "Waktu Selesai", labelWidth: 130, inputWidth: 250, required: true, readonly: true,
                        validate: "NotEmpty", 
                        options: times.endTimes,
                    },
                    {type: "checkbox", name: "is_reguler", label: "Waktu Reguler", labelWidth: 130, inputWidth: 250},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                        {type: "newcolumn"},
                        {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);

            var addDeptCombo = addShiftForm.getCombo("department_id");
            var addSubDeptCombo = addShiftForm.getCombo("sub_department_id");
            var addDivCombo = addShiftForm.getCombo("division_id");

            addDeptCombo.load(Emp("getDepartment"));
            addDeptCombo.attachEvent("onChange", function(value, text){
                clearComboReload(addShiftForm, "sub_department_id", Emp("getSubDepartment", {deptId: value}));
            });
            addSubDeptCombo.attachEvent("onChange", function(value, text){
                clearComboReload(addShiftForm, "division_id", Emp("getDivision", {subDeptId: value}));
            });

            var startCombo = addShiftForm.getCombo("work_start");
            var endCombo = addShiftForm.getCombo("work_end");
            endCombo.selectOption(times.endTimes.length - 1);

            addShiftForm.attachEvent("onChange", function(name, value) {
                if(name === 'work_start' || name === 'work_end') {
                    checkTimeShift(startCombo, endCombo, ['add', 'clear'], addShiftForm);
                } else if(name === "is_reguler") {
                    if(addShiftForm.isItemChecked("is_reguler")) {
                        addShiftForm.hideItem("department_id");
                        addShiftForm.hideItem("sub_department_id");
                        addShiftForm.hideItem("division_id");
                    } else {
                        addShiftForm.showItem("department_id");
                        addShiftForm.showItem("sub_department_id");
                        addShiftForm.showItem("division_id");
                    }
                }
            });

            addShiftForm.attachEvent("onButtonClick", function (name) {
                switch (name) {
                    case "add":
                        if (!addShiftForm.validate()) {
                            return eAlert("Input error!");
                        }

                        setDisable(["add", "clear"], addShiftForm, shiftLayout.cells("b"));
                        let addShiftFormDP = new dataProcessor(Absen("shiftForm"));
                        addShiftFormDP.init(addShiftForm);
                        addShiftForm.save();

                        addShiftFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "inserted":
                                    sAlert("Berhasil Menambahkan Record <br>" + message);
                                    rShiftGrid();
                                    clearForm(addShiftForm);
                                    setEnable(["add", "clear"], addShiftForm, shiftLayout.cells("b"));
                                    break;
                                case "error":
                                    eAlert("Gagal Menambahkan Record <br>" + message);
                                    setEnable(["add", "clear"], addShiftForm, shiftLayout.cells("b"));
                                    break;
                            }
                        });
                        break;
                    case "clear":
                        clearForm(addShiftForm);
                        break;
                    case "cancel":
                        rShiftGrid();
                        shiftLayout.cells("b").collapse();
                        break;
                }
            });
        }

        function clearForm(form) {
            clearComboReload(form, 'department_id', comboUrl['department_id'].url);
            clearComboReload(form, 'sub_department_id', comboUrl['sub_department_id'].url);
            clearComboReload(form, 'division_id', comboUrl['division_id'].url);
            clearComboOptions(form, 'name');
        }

    }

JS;

header('Content-Type: application/javascript');
echo $script;
        