<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	function showVehicleRev() {
        var legend = legendGrid();
        var revVehicleToolbar = mainTab.cells("ga_vehicles_reservation").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "approve", text: "Approve", type: "button", img: "ok.png"},
                {id: "reject", text: "Reject", type: "button", img: "messagebox_critical.png"},
                {id: "driver", text: "Pilih Driver", type: "button", img: "person_16.png"},
                {id: "vehicle", text: "Pilih Kendaraan", type: "button", img: "car_16.png"},
                {id: "searchtext", text: "Cari : ", type: "text"},
                {id: "search", text: "", type: "buttonInput", width: 150}
            ]
        });

        revVehicleToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    revVehicleToolbar.setValue("search","");
                    rRevGrid();
                    revVehicleToolbar.enableItem("approve");
                    revVehicleToolbar.enableItem("reject");
                    revVehicleToolbar.enableItem("driver");
                    revVehicleToolbar.enableItem("vehicle");
                    revVehicleGridToolbar.enableItem("update");
                    revVehicleGridToolbar.enableItem("change_hour");
                    revVehicleGridToolbar.enableItem("closed");
                    break;
                case 'approve':
                    if(!revVehicleGrid.getSelectedRowId()) {
                        return eAlert("Pilih reservasi yang akan di approve");
                    }

                    dhtmlx.modalbox({
                        type: "alert-warning",
                        title: "Konfirmasi",
                        text: "Approve jadwal perjalanan "+ revVehicleGrid.cells(revVehicleGrid.getSelectedRowId(), 1).getValue() +"?",
                        buttons: ["Ya", "Tidak"],
                        callback: function (index) {
                            if (index == 0) {
                                reqJson(GAOther("appvVehicleRev"), "POST", {id: revVehicleGrid.getSelectedRowId()}, (err, res) => {
                                    if(res.status === "success") {
                                        rRevGrid();
                                        sAlert(res.message);
                                    } else {
                                        eAlert(res.message);
                                    }
                                });
                            }
                        }
                    });
                    break;
                case 'reject':
                    if(!revVehicleGrid.getSelectedRowId()) {
                        return eAlert("Pilih reservasi yang akan di tolak");
                    }

                    var rejectVehicleWin = createWindow("ga_reject_vehicle", "Form Alasan Penolakan", 590, 320);
                    myWins.window("ga_reject_vehicle").skipMyCloseEvent = true;

                    var rejectVehicleForm = rejectVehicleWin.attachForm([
                        {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Alasan Penolakan", list: [
                            {type: "hidden", name: "id", labelWidth: 130, inputWidth:350, required: true, value: revVehicleGrid.getSelectedRowId()},
                            {type: "input", name: "reason", label: "Komentas", labelWidth: 130, inputWidth:350, required: true, rows: 5},
                            {type: "block", offsetTop: 30, list: [
                                {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                                {type: "newcolumn"},
                                {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                            ]}
                        ]}
                    ]);

                    rejectVehicleForm.attachEvent("onButtonClick", function(name) {
                        switch (name) {
                            case "update":
                                if(!rejectVehicleForm.validate()) {
                                    return eAlert("Input error!");
                                }

                                setDisable(["update", "cancel"], rejectVehicleForm, rejectVehicleWin);
                                let rejectVehicleFormDP = new dataProcessor(GAOther("rejectVehicleRev"));
                                rejectVehicleFormDP.init(rejectVehicleForm);
                                rejectVehicleForm.save();

                                rejectVehicleFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                                    let message = tag.getAttribute("message");
                                    switch (action) {
                                        case "updated":
                                            sAlert(message);
                                            rRevGrid();
                                            clearAllForm(rejectVehicleForm);
                                            setEnable(["update", "cancel"], rejectVehicleForm, rejectVehicleWin);
                                            closeWindow("ga_reject_vehicle");
                                            break;
                                        case "error":
                                            eAlert(message);
                                            setEnable(["update", "cancel"], rejectVehicleForm, rejectVehicleWin);
                                            break;
                                    }
                                });
                                break;
                            case "cancel":
                                closeWindow("ga_reject_vehicle");
                                break;
                        }
                    })
                    break;
                case 'driver':
                    if(!revVehicleGrid.getSelectedRowId()) {
                        return eAlert("Pilih reservasi yang akan di update drivernya");
                    }

                    var changeDriverWin = createWindow("ga_change_driver", "Form Driver", 590, 320);
                    myWins.window("ga_change_driver").skipMyCloseEvent = true;

                    var changeDriverForm = changeDriverWin.attachForm([
                        {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Pilih Driver Yang Tersedia", list: [
                            {type: "hidden", name: "id", labelWidth: 130, inputWidth:350, required: true, value: revVehicleGrid.getSelectedRowId()},
                            {type: "combo", name: "driver", label: "Driver", labelWidth: 130, inputWidth:350, readonly: true, required: true},
                            {type: "block", offsetTop: 30, list: [
                                {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                                {type: "newcolumn"},
                                {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                            ]}
                        ]}
                    ]);

                    var changeComboDriver = changeDriverForm.getCombo("driver");
                    changeComboDriver.load(GAOther("changeDriverList", {id: revVehicleGrid.getSelectedRowId()}));

                    changeDriverForm.attachEvent("onButtonClick", function(name) {
                        switch (name) {
                            case "update":
                                if(!changeDriverForm.validate()) {
                                    return eAlert("Input error!");
                                }

                                setDisable(["update", "cancel"], changeDriverForm, changeDriverWin);
                                let changeDriverFormDP = new dataProcessor(GAOther("changeDriverRev"));
                                changeDriverFormDP.init(changeDriverForm);
                                changeDriverForm.save();

                                changeDriverFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                                    let message = tag.getAttribute("message");
                                    switch (action) {
                                        case "updated":
                                            sAlert(message);
                                            rRevGrid();
                                            clearAllForm(changeDriverForm);
                                            setEnable(["update", "cancel"], changeDriverForm, changeDriverWin);
                                            closeWindow("ga_change_driver");
                                            break;
                                        case "error":
                                            eAlert(message);
                                            setEnable(["update", "cancel"], changeDriverForm, changeDriverWin);
                                            break;
                                    }
                                });
                                break;
                            case "cancel":
                                closeWindow("ga_change_driver");
                                break;
                        }
                    });
                    break;
                case "vehicle":
                    if(!revVehicleGrid.getSelectedRowId()) {
                        return eAlert("Pilih reservasi yang akan di update drivernya");
                    }

                    var changeVhcWin = createWindow("ga_change_vehicle", "Form Kendaraan", 590, 320);
                    myWins.window("ga_change_vehicle").skipMyCloseEvent = true;

                    var changeVhcForm = changeVhcWin.attachForm([
                        {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Pilih Kendaraan Yang Tersedia", list: [
                            {type: "hidden", name: "id", labelWidth: 130, inputWidth:350, required: true, value: revVehicleGrid.getSelectedRowId()},
                            {type: "combo", name: "vehicle", label: "Kendaraan Dinas", labelWidth: 130, inputWidth:350, readonly: true, required: true},
                            {type: "block", offsetTop: 30, list: [
                                {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                                {type: "newcolumn"},
                                {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                            ]}
                        ]}
                    ]);

                    var changeComboVehicle = changeVhcForm.getCombo("vehicle");
                    changeComboVehicle.load(GAOther("changeVehicleList", {id: revVehicleGrid.getSelectedRowId()}));

                    changeVhcForm.attachEvent("onButtonClick", function(name) {
                        switch (name) {
                            case "update":
                                if(!changeVhcForm.validate()) {
                                    return eAlert("Input error!");
                                }

                                setDisable(["update", "cancel"], changeVhcForm, changeVhcWin);
                                let changeVhcFormDP = new dataProcessor(GAOther("changeVehicleRev"));
                                changeVhcFormDP.init(changeVhcForm);
                                changeVhcForm.save();

                                changeVhcFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                                    let message = tag.getAttribute("message");
                                    switch (action) {
                                        case "updated":
                                            sAlert(message);
                                            rRevGrid();
                                            clearAllForm(changeVhcForm);
                                            setEnable(["update", "cancel"], changeVhcForm, changeVhcWin);
                                            closeWindow("ga_change_vehicle");
                                            break;
                                        case "error":
                                            eAlert(message);
                                            setEnable(["update", "cancel"], changeVhcForm, changeVhcWin);
                                            break;
                                    }
                                });
                                break;
                            case "cancel":
                                closeWindow("ga_change_vehicle");
                                break;
                        }
                    });
                    break;
            }
        });

        revVehicleToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    rRevGrid();
                    revVehicleGrid.attachEvent("onGridReconstructed", revVehicleGridCount);
                    break;
            }
        });

        let currentDate = filterForMonth(new Date());
        var revtVehicleMenu =  mainTab.cells("ga_vehicles_reservation").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "search", text: "<div style='width:100%'>Search: <input type='text' id='ga_start_vehicle_rev' readonly value='"+currentDate.start+"' /> - <input type='text' id='ga_end_vehicle_rev' readonly value='"+currentDate.end+"' /> <button id='ga_btn_vehicle_rev'>Proses</button> | Status: <select id='ga_status_vehicle_rev'><option>ALL</option><option>CREATED</option><option>APPROVED</option><option>REJECTED</option><option>CLOSED</option></select>"}
            ]
        });

        var filterCalendar = new dhtmlXCalendarObject(["ga_start_vehicle_rev","ga_end_vehicle_rev"]);

        $("#ga_btn_vehicle_rev").on("click", function() {
            if(checkFilterDate($("#ga_start_vehicle_rev").val(), $("#ga_end_vehicle_rev").val())) {
                rRevGrid();
            }
        });

        $("#ga_status_vehicle_rev").on("change", function() {
            rRevGrid();
        });

        var revVehicleLayout = mainTab.cells("ga_vehicles_reservation").attachLayout({
            pattern: "1C",
            cells: [
                {id: "a", header: false}
            ]
        });

        var revVehicleGridToolbar = revVehicleLayout.cells("a").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "update", text: "Update Jumlah Penumpang & Kilomter", type: "button", img: "update.png"},
                {id: "change_hour", text: "Ubah Waktu Reservasi", type: "button", img: "clock.png"},
                {id: "closed", text: "Tutup Meeting", type: "button", img: "ok.png"},
            ]
        });

        revVehicleGridToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "update":
                    if(!revVehicleGrid.getChangedRows()) {
                        return eAlert("Belum ada row yang di edit!");
                    }

                    revVehicleGridToolbar.disableItem("update");
                    revVehicleLayout.cells("a").progressOn();
                    revVehicleGridDP.sendData();
                    revVehicleGridDP.attachEvent('onAfterUpdate', function(id, action, tid, tag) {
                        let message = tag.getAttribute('message');
                        switch (action) {
                            case 'updated':
                                let mSplit = message.split(",");
                                mSplit.length >= 1 && mSplit[0] != "" && sAlert(mSplit[0]);
                                mSplit.length >= 2 && mSplit[1] != "" && eAlert(mSplit[1]);
                                rRevGrid();
                                revVehicleGridToolbar.enableItem("update");
                                revVehicleLayout.cells("a").progressOff();
                                setGridDP();
                                break;
                        }
                    });
                    break;
                case "change_hour":
                    if(!revVehicleGrid.getSelectedRowId()) {
                        return eAlert("Belum ada row yang di pilih!");
                    }

                    var chWin = createWindow("ch_win_vehicle", "Ubah Waktu Reservasi", 500, 300);
                    myWins.window("ch_win_vehicle").skipMyCloseEvent = true;

                    var times = createTime("full");
                    let revTime = getCurrentTime(revVehicleGrid, 10, 11);
                        
                    let labelStart = revTime.labelStart;
                    let labelEnd = revTime.labelEnd;
                    var chVehicleForm = chWin.attachForm([
                        {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Waktu Reservasi Kendaraan", list:[	
                            {type: "block", list: [
                                {type: "hidden", name: "id", label: "ID", labelWidth: 130, inputWidth: 250, value: revVehicleGrid.getSelectedRowId()},                               
                                {type: "combo", name: "start_date", label: labelStart, labelWidth: 130, inputWidth: 250, readonly: true, required: true,
                                    validate: "NotEmpty", 
                                    options: times.startTimes
                                },
                                {type: "combo", name: "end_date", label: labelEnd, labelWidth: 130, inputWidth: 250, readonly: true, required: true, 
                                    validate: "NotEmpty", 
                                    options: times.endTimes,
                                }
                            ]},
                        ]},
                        {type: "newcolumn"},
                        {type: "block", offsetLeft: 30, offsetTop: 10, list: [
                            {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Update"},
                            {type: "newcolumn"},
                            {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                        ]},
                    ]);

                    var startCombo = chVehicleForm.getCombo("start_date");
                    var endCombo = chVehicleForm.getCombo("end_date");
                    let startIndex = times.filterStartTime.indexOf(revTime.start);
                    let endIndex = times.filterEndTime.indexOf(revTime.end);
                    startCombo.selectOption(startIndex);
                    endCombo.selectOption(endIndex);

                    chVehicleForm.attachEvent("onButtonClick", function(id) {
                        switch (id) {
                            case "update":
                                setDisable(["update", "cancel"], chVehicleForm, chWin);
                                let chVehicleFormDP = new dataProcessor(GAOther("changeVehicleRevTime"));
                                chVehicleFormDP.init(chVehicleForm);
                                chVehicleForm.save();

                                chVehicleFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                                    let message = tag.getAttribute("message");
                                    switch (action) {
                                        case "updated":
                                            rRevGrid();
                                            sAlert(message);
                                            setEnable(["update", "cancel"], chVehicleForm, chWin);
                                            closeWindow("ch_win_vehicle");
                                            break;
                                        case "error":
                                            eaAlert("Kesalahan Waktu Reservasi", message);
                                            setEnable(["update", "cancel"], chVehicleForm, chWin);
                                            break;
                                    }
                                });
                                break;
                            case "cancel":
                                closeWindow("ch_win_vehicle");
                                break;
                        }
                    });
                    break;
                case "closed":
                    if(!revVehicleGrid.getSelectedRowId()) {
                        return eAlert("Belum ada row yang di edit!");
                    }

                    dhtmlx.modalbox({
                        type: "alert-warning",
                        title: "Konfirmasi Tutup Perjalanan",
                        text: "Anda yakin ?",
                        buttons: ["Ya", "Tidak"],
                        callback: function (index) {
                            if (index == 0) {
                                revVehicleGridToolbar.disableItem("closed");
                                reqJson(GAOther("closeVehicleRev"), "POST", {id: revVehicleGrid.getSelectedRowId()}, (err, res) => {
                                    if(res.status === "success") {
                                        rRevGrid();
                                        sAlert(res.message);
                                        revVehicleGridToolbar.enableItem("closed");
                                    }
                                });
                            }
                        },
                    });
                    break;
            }
        });

        var revStatusBar = revVehicleLayout.cells("a").attachStatusBar();
        function revVehicleGridCount() {
            let revVehicleGridRows = revVehicleGrid.getRowsNum();
            revStatusBar.setText("Total baris: " + revVehicleGridRows  + " (" + legend.m_room_rev + ")");
        }

        var revVehicleGrid = revVehicleLayout.cells("a").attachGrid();
        revVehicleGrid.setHeader("No,No. Tiket,Tujuan,Jenis Perjalanan,Kendaraan,Driver,Konfirmasi Driver,Kilometer Awal,Kilometer Akhir,Jarak Tempuh,Waktu Mulai,Waktu Selesai,Druasi,Jumlah Penumpang,Status,Alasan Penolakan,Created By,Updated By,DiBuat");
        revVehicleGrid.attachHeader("#rspan,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        revVehicleGrid.setColSorting("str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        revVehicleGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,edtxt,edtxt,rotxt,rotxt,rotxt,ron,edtxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        revVehicleGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        revVehicleGrid.setInitWidthsP("5,15,25,20,15,15,15,15,15,15,20,20,10,10,15,30,15,15,25");
        revVehicleGrid.attachFooter(",Total,,,,,,,,#stat_total,,,#stat_total,,,,,,");
        revVehicleGrid.enableSmartRendering(true);
        revVehicleGrid.setEditable(true);
        revVehicleGrid.attachEvent("onXLE", function() {
            revVehicleLayout.cells("a").progressOff();
        });
        isGridNumeric(revVehicleGrid, [7,8,13]);
        revVehicleGrid.attachEvent("onRowSelect", function(rId, cidn) {
            if(revVehicleGrid.cells(rId, 14).getValue() == 'APPROVED') {
                revVehicleToolbar.disableItem("approve");
                revVehicleToolbar.disableItem("reject");
                revVehicleToolbar.enableItem("driver");
                revVehicleToolbar.enableItem("vehicle");
                revVehicleGridToolbar.enableItem("update");
                revVehicleGridToolbar.enableItem("change_hour");
                revVehicleGridToolbar.enableItem("closed");
            } else if(revVehicleGrid.cells(rId, 14).getValue() == 'REJECTED' || revVehicleGrid.cells(rId, 14).getValue() == 'CLOSED') {
                revVehicleToolbar.disableItem("approve");
                revVehicleToolbar.disableItem("reject");
                revVehicleToolbar.disableItem("driver");
                revVehicleToolbar.disableItem("vehicle");
                revVehicleGridToolbar.disableItem("update");
                revVehicleGridToolbar.disableItem("change_hour");
                revVehicleGridToolbar.disableItem("closed");
            } else {
                if(userLogged.rankId >= 3 && userLogged.rankId <= 6) {
                    revVehicleToolbar.enableItem("approve");
                    revVehicleToolbar.enableItem("reject");
                    revVehicleToolbar.disableItem("driver");
                    revVehicleToolbar.disableItem("vehicle");
                } else {
                    revVehicleToolbar.disableItem("approve");
                    revVehicleToolbar.disableItem("reject");
                    revVehicleToolbar.disableItem("driver");
                    revVehicleToolbar.disableItem("vehicle");
                }
                revVehicleGridToolbar.disableItem("update");
                revVehicleGridToolbar.disableItem("change_hour");
                revVehicleGridToolbar.disableItem("closed");
            }
        });

        function setGridDP() {
            revVehicleGridDP = new dataProcessor(GAOther('updateVehicleRevBatch'));
            revVehicleGridDP.setTransactionMode("POST", true);
            revVehicleGridDP.setUpdateMode("Off");
            revVehicleGridDP.init(revVehicleGrid);
        }

        setGridDP();

        revVehicleGrid.init();

        function rRevGrid() {
            revVehicleLayout.cells("a").progressOn();
            let start = $("#ga_start_vehicle_rev").val();
            let end = $("#ga_end_vehicle_rev").val();
            let status = $("#ga_status_vehicle_rev").val();
            let params = {
                search: revVehicleToolbar.getValue("search"),
                betweendate_start_date: start+","+end
            };

            if(status != "ALL") {
                params.equal_status = status;
            }
            revVehicleGrid.clearAndLoad(GAOther("getVehicleRevGrid", params), revVehicleGridCount);
        }
        rRevGrid();
    }

JS;

header('Content-Type: application/javascript');
echo $script;
