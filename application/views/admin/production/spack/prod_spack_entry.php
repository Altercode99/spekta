<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
    		
    function showSpackEntry() {
        var addEntryForm;
        var editEntryForm;
        
        var comboUrl = {
            product_id: {
                url: Production("getProduct"),
                reload: true
            },
        }

        var comboUrl2 = {
            location_id: {
                url: Production("getLocation"),
                reload: true
            },
            makloon: {
                url: Production("getMakloon"),
                reload: true
            },
        }

        var spEntryTabs = mainTab.cells("prod_spack_entry").attachTabbar({
            tabs: [
                {id: "a", text: "Entry Surat Pack", active: true}
            ]
        });

        var spEntryLayout = spEntryTabs.cells("a").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    text: "Form Surat Pack",
                },
                {
                    id: "b",
                    text: "Daftar Surat Pack",
                }
            ]
        });

        addEntryForm = spEntryLayout.cells("a").attachForm([
            {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Surat Pack", list: [
                {type: "combo", name: "product_id", label: "Produk", labelWidth: 130, inputWidth: 250, required: true},
                {type: "input", name: "no_batch", label: "No. Batch", labelWidth: 130, inputWidth:250, required: true},
                {type: "block", offsetTop: 30, list: [
                    {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                    {type: "newcolumn"},
                    {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                ]}
            ]}
        ]);

        var addProductCombo = addEntryForm.getCombo("product_id");
        addProductCombo.enableFilteringMode(true, 'product_id');
        addProductCombo.attachEvent("onDynXLS", productComboFilter);

        function productComboFilter(text){
            addProductCombo.clearAll();
            if(text.length > 3) {
                dhx.ajax.get(Production('getProduct', {name: text}), function(xml){
                    if(xml.xmlDoc.responseText) {
                        addProductCombo.load(xml.xmlDoc.responseText);
                        addProductCombo.openSelect();
                    }
                });
            }
        };

        addEntryForm.attachEvent("onButtonClick", function (name) {
            switch (name) {
                case "add":
                    if (!addEntryForm.validate()) {
                        return eAlert("Input error!");
                    }

                    setDisable(["add", "clear"], addEntryForm, spEntryLayout.cells("b"));
                    let addEntryFormDP = new dataProcessor(Production("spEntryForm"));
                    addEntryFormDP.init(addEntryForm);
                    addEntryForm.save();

                    addEntryFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                        let message = tag.getAttribute("message");
                        switch (action) {
                            case "inserted":
                                rSpEntryGrid();
                                sAlert("Berhasil Menambahkan Record <br>" + message);
                                clearAllForm(addEntryForm, comboUrl);
                                setEnable(["add", "clear"], addEntryForm, spEntryLayout.cells("b"));
                                break;
                            case "error":
                                eAlert("Gagal Menambahkan Record <br>" + message);
                                setEnable(["add", "clear"], addEntryForm, spEntryLayout.cells("b"));
                                break;
                        }
                    });
                    break;
                case "clear":
                    clearAllForm(addEntryForm, comboUrl);
                    break;
            }
        });

        var spEntryListToolbar = spEntryLayout.cells("b").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "delete", text: "Hapus", type: "button", img: "delete.png"},
                {id: "edit", text: "Ubah", type: "button", img: "edit.png"},
                {id: "print", text: "Cetak Surat Pack", type: "button", img: "printer.png"},
            ]
        });

        spEntryListToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    rSpEntryGrid();
                    break;
                case "delete":
                    reqAction(spEntryGrid, Production("spEntryDelete"), 1, (err, res) => {
                        rSpEntryGrid();
                        res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                        res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
                    });
                    break;
                case "edit":
                    editSpEntryHandler();
                    break;
                case "print":
                    if(!spEntryGrid.getSelectedRowId()) {
                        return eAlert("Pilih baris yang akan diubah!");
                    }

                    let tabName = "pros_spack_entry_" + spEntryGrid.getSelectedRowId();
                    let noBatch = spEntryGrid.cells(spEntryGrid.getSelectedRowId(), 1).getValue();
                    let productName = spEntryGrid.cells(spEntryGrid.getSelectedRowId(), 2).getValue();
                    if (!spEntryTabs.tabs(tabName)){
                        spEntryTabs.addTab(tabName, "Cetak " + noBatch, null, null, true, true);
                    } else {
                        spEntryTabs.tabs(tabName).setActive();
                    }

                    var printLayout = spEntryTabs.cells(tabName).attachLayout({
                        pattern: "2E",
                        cells: [
                            {id: "a", text: "Form Cetak No. Batch: " + noBatch, height: 350},
                            {id: "b", header : false},
                        ]
                    });

                    var formToolbar = printLayout.cells("a").attachToolbar({
                        icon_path: "./public/codebase/icons/",
                        items: [
                            {id: "save", text: "Simpan", type: "button", img: "update.png"},
                            {id: "clear", text: "Clear Form", type: "button", img: "clear.png"},
                        ]
                    });

                    const years = [{
                        value: 0, text: "-Pilih Tahun-",
                    }];
                    const newDate = new Date();
                    for (let i = 2022; i <= newDate.getFullYear() + 5; i++) {
                        years.push({value: i, text: i});
                    }
                    var printForm = printLayout.cells("a").attachForm([
                        {type: "block", offsetTop: 10, list: [
                            {type: "calendar", name: "letter_date", label: "Tanggal", labelWidth: 130, inputWidth:250, readonly:true},
                            {type: "input", name: "no_batch", label: "No. Batch", labelWidth: 130, inputWidth:250, required: true, readonly:true, value: noBatch},
                            {type: "input", name: "product_name", label: "Produk", labelWidth: 130, inputWidth:250, required: true, readonly:true, value: productName},
                            {type: "combo", name: "makloon", label: "Maklook", labelWidth: 130, inputWidth: 250, readonly: true},
                            {type: "combo", name: "location_id", label: "Lokasi", labelWidth: 130, inputWidth: 250, readonly: true, required: true},
                            {type: "combo", name: "packing_by", label: "Dikemas Oleh", labelWidth: 130, inputWidth: 250},
                            {type: "combo", name: "spv_by", label: "Supervisor", labelWidth: 130, inputWidth: 250},
                        ]}, 
                        {type: "newcolumn"},
                        {type: "block", offsetTop: 10, list: [
                            {type: "combo", name: "mfg_month", label: "Mfg. Bulan", readonly: true, required: true, labelWidth: 130, inputWidth: 250,
                                validate: "NotEmpty", 
                                options:[
                                    {value: 0, text: "-Pilih Bulan-"},
                                    {value: 1, text: "Januari"},
                                    {value: 2, text: "Februari"},
                                    {value: 3, text: "Maret"},
                                    {value: 4, text: "April"},
                                    {value: 5, text: "Mei"},
                                    {value: 6, text: "Juni"},
                                    {value: 7, text: "Juli"},
                                    {value: 8, text: "Agustus"},
                                    {value: 9, text: "September"},
                                    {value: 10, text: "Oktober"},
                                    {value: 11, text: "November"},
                                    {value: 12, text: "Desember"},
                                ]
                            },
                            {type: "combo", name: "mfg_year", label: "Mfg. Tahun", readonly: true, required: true, labelWidth: 130, inputWidth: 250,
                                validate: "NotEmpty", 
                                options: years
                            },
                            {type: "combo", name: "exp_month", label: "Exp. Bulan", readonly: true, required: true, labelWidth: 130, inputWidth: 250,
                                validate: "NotEmpty", 
                                options:[
                                    {value: 0, text: "-Pilih Bulan-"},
                                    {value: 1, text: "Januari"},
                                    {value: 2, text: "Februari"},
                                    {value: 3, text: "Maret"},
                                    {value: 4, text: "April"},
                                    {value: 5, text: "Mei"},
                                    {value: 6, text: "Juni"},
                                    {value: 7, text: "Juli"},
                                    {value: 8, text: "Agustus"},
                                    {value: 9, text: "September"},
                                    {value: 10, text: "Oktober"},
                                    {value: 11, text: "November"},
                                    {value: 12, text: "Desember"},
                                ]
                            },
                            {type: "combo", name: "exp_year", label: "Exp. Tahun", readonly: true, required: true, labelWidth: 130, inputWidth: 250,
                                validate: "NotEmpty", 
                                options: years
                            },
                        ]},
                    ]);

                    var packingCombo = printForm.getCombo("packing_by");
                    packingCombo.enableFilteringMode(true, 'packing_by');
                    packingCombo.attachEvent("onDynXLS", packingComboFilter);

                    var locCombo = printForm.getCombo("location_id");
                    locCombo.load(Production("getLocation"));

                    var makloonCombo = printForm.getCombo("makloon");
                    makloonCombo.load(Production("getMakloon"));

                    function packingComboFilter(text){
                        packingCombo.clearAll();
                        if(text.length > 3) {
                            dhx.ajax.get(User('getEmps', {name: text}), function(xml){
                                if(xml.xmlDoc.responseText) {
                                    packingCombo.load(xml.xmlDoc.responseText);
                                    packingCombo.openSelect();
                                }
                            });
                        }
                    };

                    var spvCombo = printForm.getCombo("spv_by");
                    spvCombo.enableFilteringMode(true, 'spv_by');
                    spvCombo.attachEvent("onDynXLS", spvComboFilter);

                    function spvComboFilter(text){
                        spvCombo.clearAll();
                        if(text.length > 3) {
                            dhx.ajax.get(User('getEmps', {name: text}), function(xml){
                                if(xml.xmlDoc.responseText) {
                                    spvCombo.load(xml.xmlDoc.responseText);
                                    spvCombo.openSelect();
                                }
                            });
                        }
                    };

                    formToolbar.attachEvent("onClick", function(id) {
                        switch (id) {
                            case "save":
                                if(!printForm.validate()) {
                                    return eAlert("Input error!");
                                }

                                printLayout.cells("a").progressOn();
                                formToolbar.disableItem("save");
                                let printFormDP = new dataProcessor(Production("createSpPrint"));
                                printFormDP.init(printForm);
                                printForm.save();

                                printFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                                    let message = tag.getAttribute("message");
                                    switch (action) {
                                        case "inserted":
                                            sAlert("Berhasil Menambahkan Record <br>" + message);
                                            formToolbar.enableItem("save");
                                            printLayout.cells("a").progressOff();
                                            clearPrintForm();
                                            rSpPrintGrid();
                                            break;
                                        case "error":
                                            eAlert("Gagal Menambahkan Record <br>" + message);
                                            formToolbar.enableItem("save");
                                            printLayout.cells("a").progressOff();
                                            break;
                                    }
                                });
                                break;
                            case "clear":
                                clearPrintForm();
                                break;
                        }
                    });

                    function clearPrintForm() {
                        printForm.setItemValue("letter_date", "");
                        printForm.setItemValue("makloon", "");
                        clearComboReload(printForm, "location_id", Production("getLocation"));
                        clearComboOptions(printForm, "mfg_month");
                        clearComboOptions(printForm, "mfg_year");
                        clearComboOptions(printForm, "exp_month");
                        clearComboOptions(printForm, "exp_year");
                        clearComboReload(printForm, "packing_by", User("getEmps"));
                        clearComboReload(printForm, "spv_by", User("getEmps"));
                    }

                    var gridToolbar = printLayout.cells("b").attachToolbar({
                        icon_path: "./public/codebase/icons/",
                        items: [
                            {id: "print", text: "Cetak Surat Pack", type: "button", img: "print.png"},
                            {id: "delete", text: "Hapus", type: "button", img: "delete.png"},
                        ]
                    });

                    var spPrintGrid = printLayout.cells("b").attachGrid();
                    spPrintGrid.setHeader("No,Nomor Batch,Produk,Golongan Produk,Kemasan,Makloon,Tanggal Surat,Dikemas Oleh,Supervisor,Mfg Date,Exp Date,Created By");
                    spPrintGrid.attachHeader("#rspan,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter")
                    spPrintGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str");
                    spPrintGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
                    spPrintGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left");
                    spPrintGrid.setInitWidthsP("5,15,25,20,20,20,20,20,20,20,20,20,20");
                    spPrintGrid.enableSmartRendering(true);
                    spPrintGrid.enableMultiselect(true);
                    spPrintGrid.attachEvent("onXLE", function() {
                        printLayout.cells("b").progressOff();
                    });
                    spPrintGrid.init();

                    function rSpPrintGrid() {
                        printLayout.cells("b").progressOn();
                        spPrintGrid.clearAndLoad(Production("getSpPrint", {equal_no_batch: noBatch}));
                    }

                    rSpPrintGrid();

                    gridToolbar.attachEvent("onClick", function(id) {
                        switch (id) {
                            case "print":
                                if(!spPrintGrid.getSelectedRowId()) {
                                    return eAlert("Pilih baris yang akan cetak!");
                                }

                                var qtySpWin = createWindow("qty_sp_win", "Jumlah Cetakan", 500, 350);
                                myWins.window("qty_sp_win").skipMyCloseEvent = true;
                                
                                qtySpForm = qtySpWin.attachForm([
                                    {type: "fieldset", offsetTop: 30, offsetLeft: 30, list: [
                                        {type: "input", name: "no_batch", label: "No. Batch", labelWidth: 130, inputWidth:250, required: true, readonly: true, value: spPrintGrid.cells(spPrintGrid.getSelectedRowId(), 1).getValue()},
                                        {type: "input", name: "package_desc", label: "Kemasan", labelWidth: 130, inputWidth:250, required: true, value: spPrintGrid.cells(spPrintGrid.getSelectedRowId(), 4).getValue()},
                                        {type: "input", name: "total_print", label: "Jumlah Cetakan", labelWidth: 130, inputWidth:250, required: true, validate:"ValidNumeric"},
                                        {type: "input", name: "start_from", label: "Mulai Dari", labelWidth: 130, inputWidth:250, required: true, validate:"ValidNumeric"},
                                        {type: "block", offsetTop: 30, list: [
                                            {type: "button", name: "print", className: "button_print", offsetLeft: 30, value: "Cetak"},
                                        ]}
                                    ]}
                                ]);
                                isFormNumeric(qtySpForm, ['total_print']);

                                qtySpForm.attachEvent("onButtonClick", function(name) {
                                    switch (name) {
                                        case "print":
                                            if(!qtySpForm.validate()) {
                                                return eAlert("Input error!");
                                            }

                                            let totalPrint = qtySpForm.getItemValue("total_print");
                                            let startFrom = qtySpForm.getItemValue("start_from");

                                            if(totalPrint <= 0) {
                                                return eAlert("Jumlah Cetakan harus lebih besar dari 0");
                                            }

                                            if(startFrom <= 0) {
                                                return eAlert("Mulai Dari harus lebih besar dari 0");
                                            }

                                            let dataPrint = {
                                                id: spPrintGrid.getSelectedRowId(),
                                                no_batch: qtySpForm.getItemValue("no_batch"),
                                                package_desc: qtySpForm.getItemValue("package_desc"),
                                                total_print: totalPrint,
                                                start_from: startFrom,
                                            };
                                            
                                            window.open(Production("doSpPrint", dataPrint), '_blank', 'location=yes,height=650,width=450,scrollbars=yes,status=yes');
                                            break;
                                    }
                                });
                                break;
                            case "delete":
                                reqAction(spPrintGrid, Production("spPrintDelete"), 1, (err, res) => {
                                    rSpPrintGrid();
                                    res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                                    res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
                                });
                                break;
                        }
                    });
                    break;
            }
        });

        var spEntryStatusBar = spEntryLayout.cells("b").attachStatusBar();
        function spEntryGridCount() {
            spEntryStatusBar.setText("Total baris: " + spEntryGrid.getRowsNum());
        }

        var spEntryGrid = spEntryLayout.cells("b").attachGrid();
        spEntryGrid.setHeader("No,Nomor Batch,Produk");
        spEntryGrid.attachHeader("#rspan,#text_filter,#select_filter")
        spEntryGrid.setColSorting("int,str,str");
        spEntryGrid.setColTypes("rotxt,rotxt,rotxt");
        spEntryGrid.setColAlign("center,left,left");
        spEntryGrid.setInitWidthsP("5,25,70");
        spEntryGrid.enableSmartRendering(true);
        spEntryGrid.enableMultiselect(true);
        spEntryGrid.attachEvent("onXLE", function() {
            spEntryLayout.cells("b").progressOff();
        });
        spEntryGrid.init();
        
        function rSpEntryGrid() {
            spEntryLayout.cells("b").progressOn();
            spEntryGrid.clearAndLoad(Production("getSpEntryGrid", {equal_sub_department_id: userLogged.subId}), spEntryGridCount);
        }

        rSpEntryGrid();

        function editSpEntryHandler() {
            if(!spEntryGrid.getSelectedRowId()) {
                return eAlert("Pilih baris yang akan diubah!");
            }
            var spEntryWin = createWindow("sp_entry_win", "Edit Nomor Batch", 500, 300);
            myWins.window("sp_entry_win").skipMyCloseEvent = true;

            editEntryForm = spEntryWin.attachForm([
                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Surat Pack", list: [
                    {type: "hidden", name: "id", label: "ID", labelWidth: 130, inputWidth: 250, readonly: true},
                    {type: "combo", name: "product_id", label: "Produk", labelWidth: 130, inputWidth: 250, readonly: true, required: true},
                    {type: "input", name: "no_batch", label: "No. Batch", labelWidth: 130, inputWidth:250, required: true},
                    {type: "block", offsetTop: 30, list: [
                        {type: "button", name: "update", className: "button_update", offsetLeft: 30, value: "Update"},
                        {type: "newcolumn"},
                        {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                    ]}
                ]}
            ]);
            fetchFormData(Production("spEntryForm", {id: spEntryGrid.getSelectedRowId()}), editEntryForm, null, null, setCombo);
            var editProductCombo = editEntryForm.getCombo("product_id");

            function setCombo() {
                editProductCombo.load(Production("getProduct2", {select: editEntryForm.getItemValue("product_id")}));
            }

            editEntryForm.attachEvent("onButtonClick", function(name) {
                switch (name) {
                    case "update":
                        if (!editEntryForm.validate()) {
                            return eAlert("Input error!");
                        }	

                        setDisable(["update", "cancel"], editEntryForm, spEntryWin);
                        let editEntryFormDP = new dataProcessor(Production("spEntryForm"));
                        editEntryFormDP.init(editEntryForm);
                        editEntryForm.save();

                        editEntryFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
                            let message = tag.getAttribute("message");
                            switch (action) {
                                case "updated":
                                    sAlert("Berhasil Mengubah Record <br>" + message);
                                    rSpEntryGrid();
                                    setEnable(["update", "cancel"], editEntryForm, spEntryWin);
                                    closeWindow("sp_entry_win");                               							
                                    break;
                                case "error":
                                    eAlert("Gagal Mengubah Record<br>" + message);
                                    setEnable(["update", "cancel"], editEntryForm, spEntryWin);
                                    closeWindow("sp_entry_win");
                                    break;
                            }
                        });									
                        break;
                    case "cancel":
                        closeWindow("sp_entry_win");
                        break;
                }
            });
        }
    }
JS;

header('Content-Type: application/javascript');
echo $script;