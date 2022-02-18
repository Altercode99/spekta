<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
    var currSnack;
	function showMeetingRev() {	
        var legend = legendGrid();
        var revMRoomToolbar = mainTab.cells("ga_meeting_rooms_reservation").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "approve", text: "Approve", type: "button", img: "ok.png"},
                {id: "reject", text: "Reject", type: "button", img: "messagebox_critical.png"},
                {id: "snack", text: "Pilih Snack", type: "button", img: "food.png"},
                {id: "searchtext", text: "Cari : ", type: "text"},
                {id: "search", text: "", type: "buttonInput", width: 150}
            ]
        });

        revMRoomToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    revMRoomToolbar.setValue("search","");
                    rRevGrid();
                    revMRoomToolbar.enableItem("approve");
                    revMRoomToolbar.enableItem("reject");
                    revMRoomToolbar.enableItem("snack");
                    revMRoomGridToolbar.enableItem("update");
                    revMRoomGridToolbar.enableItem("change_hour");
                    revMRoomGridToolbar.enableItem("closed");
                    break;
                case 'approve':
                case 'snack':
                    if(!revMRoomGrid.getSelectedRowId()) {
                        return eAlert("Pilih reservasi yang akan di approve");
                    }

                    if(revMRoomGrid.cells(revMRoomGrid.getSelectedRowId(), 9).getValue() != '-') {
                        var appvWin = createWindow("ga_appv_mroom", "Pilih Snack Meeting", 800, 400);
                        myWins.window("ga_appv_mroom").skipMyCloseEvent = true;

                        var appvWinToolbar = appvWin.attachToolbar({
                            icon_path: "./public/codebase/icons/",
                            items: [
                                {id: "approve", text: "Approve", type: "button", img: "ok.png"},
                                {id: "cancel", text: "Cancel", type: "button", img: "messagebox_critical.png"},
                                {id: "snack", text: "Ubah Snack", type: "button", img: "refresh.png"},
                            ]
                        });

                        if(revMRoomGrid.cells(revMRoomGrid.getSelectedRowId(), 17).getValue() == "CREATED") {
                            appvWinToolbar.enableItem("approve");
                            appvWinToolbar.disableItem("snack");
                        } else {
                            appvWinToolbar.disableItem("approve");
                            appvWinToolbar.enableItem("snack");
                        }

                        reqJson(GAOther("getSnacks"), "POST", {id: revMRoomGrid.getSelectedRowId()}, (err, res) => {
                            currSnack = res.snack_id;
                            if(res.status === "success") {
                                appvWin.attachHTMLString(res.template);
                            }
                        });

                        appvWinToolbar.attachEvent("onClick", function(id) {
                            switch (id) {
                                case "approve":
                                    if(!currSnack) {
                                        return eaAlert("Pilih Snack", "Silahkan pilih snack yang akan diberikan ke peserta meeting!");
                                    }

                                    reqJson(GAOther("appvReservation"), "POST", {id: revMRoomGrid.getSelectedRowId(), snackId: currSnack}, (err, res) => {
                                        if(res.status === "success") {
                                            rRevGrid();
                                            sAlert(res.message);
                                            closeWindow("ga_appv_mroom");
                                        } else {
                                            eaAlert("Error", res.message);
                                        }
                                    });
                                    break;
                                case "snack":
                                    reqJson(GAOther("changeRevSnack"), "POST", {id: revMRoomGrid.getSelectedRowId(), snackId: currSnack}, (err, res) => {
                                        if(res.status === "success") {
                                            rRevGrid();
                                            sAlert(res.message);
                                            closeWindow("ga_appv_mroom");
                                        } else {
                                            eaAlert("Error", res.message);
                                        }
                                    });
                                    break;
                                case "cancel":
                                    closeWindow("ga_appv_mroom");
                                    break;
                            }
                        });
                    } else {
                        dhtmlx.modalbox({
                            type: "alert-warning",
                            title: "Konfirmasi",
                            text: "Approve jadwal meeting "+ revMRoomGrid.cells(revMRoomGrid.getSelectedRowId(), 1).getValue() +"?",
                            buttons: ["Ya", "Tidak"],
                            callback: function (index) {
                                if (index == 0) {
                                    reqJson(GAOther("appvReservation"), "POST", {id: revMRoomGrid.getSelectedRowId()}, (err, res) => {
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
                    }
                    break;
                case "reject":
                    if(!revMRoomGrid.getSelectedRowId()) {
                        return eAlert("Pilih reservasi yang akan di tolak");
                    }

                    var rejectMRoomWin = createWindow("ga_reject_mroom", "Form Alasan Penolakan", 590, 320);
                    myWins.window("ga_reject_mroom").skipMyCloseEvent = true;

                    var rejectMRoomForm = rejectMRoomWin.attachForm([
                        {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Alasan Penolakan", list: [
                            {type: "hidden", name: "id", labelWidth: 130, inputWidth:350, required: true, value: revMRoomGrid.getSelectedRowId()},
                            {type: "input", name: "reason", label: "Komentas", labelWidth: 130, inputWidth:350, required: true, rows: 5},
                            {type: "block", offsetTop: 30, list: [
                                {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                                {type: "newcolumn"},
                                {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                            ]}
                        ]}
                    ]);

                    rejectMRoomForm.attachEvent("onButtonClick", function(name) {
                        switch (name) {
                            case "update":
                                if(!rejectMRoomForm.validate()) {
                                    return eAlert("Input error!");
                                }

                                setDisable(["update", "cancel"], rejectMRoomForm, rejectMRoomWin);
                                let rejectMRoomFormDP = new dataProcessor(GAOther("rejectReservation"));
                                rejectMRoomFormDP.init(rejectMRoomForm);
                                rejectMRoomForm.save();

                                rejectMRoomFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                                    let message = tag.getAttribute("message");
                                    switch (action) {
                                        case "updated":
                                            sAlert(message);
                                            rRevGrid();
                                            clearAllForm(rejectMRoomForm);
                                            setEnable(["update", "cancel"], rejectMRoomForm, rejectMRoomWin);
                                            closeWindow("ga_reject_mroom");
                                            break;
                                        case "error":
                                            eAlert(message);
                                            setEnable(["update", "cancel"], rejectMRoomForm, rejectMRoomWin);
                                            break;
                                    }
                                });
                                break;
                            case "cancel":
                                closeWindow("ga_reject_mroom");
                                break;
                        }
                    })
                    break;
            }
        });

        revMRoomToolbar.attachEvent("onEnter", function(id) {
            switch (id) {
                case "search":
                    rRevGrid();
                    revMRoomGrid.attachEvent("onGridReconstructed", revMRoomGridCount);
                    break;
            }
        });

        let currentDate = filterForMonth(new Date());
        var revtMenu =  mainTab.cells("ga_meeting_rooms_reservation").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "search", text: "<div style='width:100%'>Search: <input type='text' id='ga_start_mroom_rev' readonly value='"+currentDate.start+"' /> - <input type='text' id='ga_end_mroom_rev' readonly value='"+currentDate.end+"' /> <button id='ga_btn_mroom_rev'>Proses</button> | Status: <select id='ga_status_mroom_rev'><option>ALL</option><option>CREATED</option><option>APPROVED</option><option>REJECTED</option><option>CLOSED</option></select>"}
            ]
        });

        var filterCalendar = new dhtmlXCalendarObject(["ga_start_mroom_rev","ga_end_mroom_rev"]);

        $("#ga_btn_mroom_rev").on("click", function() {
            if(checkFilterDate($("#ga_start_mroom_rev").val(), $("#ga_end_mroom_rev").val())) {
                rRevGrid();
            }
        });

        $("#ga_status_mroom_rev").on("change", function() {
            rRevGrid();
        });

        var revMRoomLayout = mainTab.cells("ga_meeting_rooms_reservation").attachLayout({
            pattern: "1C",
            cells: [
                {id: "a", header: false}
            ]
        });

        var revMRoomGridToolbar = revMRoomLayout.cells("a").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "update", text: "Update Data kehadiran", type: "button", img: "update.png"},
                {id: "change_hour", text: "Ubah Waktu Reservasi", type: "button", img: "clock.png"},
                {id: "closed", text: "Tutup Meeting", type: "button", img: "ok.png"},
            ]
        });

        revMRoomGridToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "update":
                    if(!revMRoomGrid.getChangedRows()) {
                        return eAlert("Belum ada row yang di edit!");
                    }

                    revMRoomGridToolbar.disableItem("update");
                    revMRoomLayout.cells("a").progressOn();
                    revMRoomGridDP.sendData();
                    revMRoomGridDP.attachEvent('onAfterUpdate', function(id, action, tid, tag) {
                        let message = tag.getAttribute('message');
                        switch (action) {
                            case 'updated':
                                let mSplit = message.split(",");
                                mSplit.length >= 1 && mSplit[0] != "" && sAlert(mSplit[0]);
                                mSplit.length >= 2 && mSplit[1] != "" && eAlert(mSplit[1]);
                                rRevGrid();
                                revMRoomGridToolbar.enableItem("update");
                                revMRoomLayout.cells("a").progressOff();
                                setGridDP();
                                break;
                        }
                    });
                    break;
                case "change_hour":
                    if(!revMRoomGrid.getSelectedRowId()) {
                        return eAlert("Belum ada row yang di pilih!");
                    }

                    var chWin = createWindow("ch_win_mroom", "Ubah Waktu Reservasi", 500, 300);
                    myWins.window("ch_win_mroom").skipMyCloseEvent = true;

                    var times = createTime("full");
                    let revTime = getCurrentTime(revMRoomGrid, 6, 7);
                        
                    let labelStart = revTime.labelStart;
                    let labelEnd = revTime.labelEnd;
                    var chMRoomForm = chWin.attachForm([
                        {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Waktu Reservasi Ruang Meeting", list:[	
                            {type: "block", list: [
                                {type: "hidden", name: "id", label: "ID", labelWidth: 130, inputWidth: 250, value: revMRoomGrid.getSelectedRowId()},                               
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
                            {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Clear"}
                        ]},
                    ]);

                    var startCombo = chMRoomForm.getCombo("start_date");
                    var endCombo = chMRoomForm.getCombo("end_date");
                    let startIndex = times.filterStartTime.indexOf(revTime.start);
                    let endIndex = times.filterEndTime.indexOf(revTime.end);
                    startCombo.selectOption(startIndex);
                    endCombo.selectOption(endIndex);

                    chMRoomForm.attachEvent("onButtonClick", function(id) {
                        switch (id) {
                            case "update":
                                setDisable(["update", "cancel"], chMRoomForm, chWin);
                                let chMRoomFormDP = new dataProcessor(GAOther("changeRevTime"));
                                chMRoomFormDP.init(chMRoomForm);
                                chMRoomForm.save();

                                chMRoomFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                                    let message = tag.getAttribute("message");
                                    switch (action) {
                                        case "updated":
                                            rRevGrid();
                                            sAlert(message);
                                            setEnable(["update", "cancel"], chMRoomForm, chWin);
                                            closeWindow("ch_win_mroom");
                                            break;
                                        case "error":
                                            eaAlert("Kesalahan Waktu Reservasi", message);
                                            setEnable(["update", "cancel"], chMRoomForm, chWin);
                                            break;
                                    }
                                });
                                break;
                            case "cancel":
                                closeWindow("ch_win_mroom");
                                break;
                        }
                    });
                    break;
                case "closed":
                    if(!revMRoomGrid.getSelectedRowId()) {
                        return eAlert("Belum ada row yang di edit!");
                    }

                    dhtmlx.modalbox({
                        type: "alert-warning",
                        title: "Konfirmasi Tutup Meeting",
                        text: "Anda yakin ?",
                        buttons: ["Ya", "Tidak"],
                        callback: function (index) {
                            if (index == 0) {
                                revMRoomGridToolbar.disableItem("closed");
                                reqJson(GAOther("closeReservation"), "POST", {id: revMRoomGrid.getSelectedRowId()}, (err, res) => {
                                    if(res.status === "success") {
                                        rRevGrid();
                                        sAlert(res.message);
                                        revMRoomGridToolbar.enableItem("closed");
                                    }
                                });
                            }
                        },
                    });
                    
                    break;
            }
        })

        var revStatusBar = revMRoomLayout.cells("a").attachStatusBar();
        function revMRoomGridCount() {
            let revMRoomGridRows = revMRoomGrid.getRowsNum();
            revStatusBar.setText("Total baris: " + revMRoomGridRows  + " (" + legend.m_room_rev + ")");
            sumGridToElement(revMRoomGrid, 16, "ga_total_snack");
        }

        var revMRoomGrid = revMRoomLayout.cells("a").attachGrid();
        revMRoomGrid.setHeader("No,No. Tiket,No. Ref,Topik Meeting,Jenis Meeting,Ruang Meeting,Waktu Mulai,Waktu Selesai,Druasi,Snack,Total Peserta,Konfirmasi Hadir,Konfirmasi Tidak Hadir,Belum Konfirmasi,Snack,Harga Snack,Total,Status,Alasan Penolakan,Created By,Updated By,DiBuat");
        revMRoomGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        revMRoomGrid.setColSorting("str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        revMRoomGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,edtxt,rotxt,rotxt,rotxt,ron,ron,rotxt,rotxt,rotxt,rotxt,rotxt");
        revMRoomGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        revMRoomGrid.setInitWidthsP("5,15,15,25,15,15,20,20,10,10,10,10,10,10,15,15,15,15,30,15,15,25");
        revMRoomGrid.attachFooter(",Total Biaya Snack,,,,,,,,,,,,,,,<div id='ga_total_snack'>0</div>,,,,,,")
        revMRoomGrid.enableSmartRendering(true);
        revMRoomGrid.setEditable(true);
        revMRoomGrid.attachEvent("onXLE", function() {
            revMRoomLayout.cells("a").progressOff();
        });
        revMRoomGrid.setNumberFormat("0,000",15,".",",");
        revMRoomGrid.setNumberFormat("0,000",16,".",",");
        isGridNumeric(revMRoomGrid, [10,11,12,13,15,16]);
        revMRoomGrid.attachEvent("onRowSelect", function(rId, cidn) {
            if(revMRoomGrid.cells(rId, 17).getValue() == 'APPROVED') {
                revMRoomToolbar.disableItem("approve");
                revMRoomToolbar.disableItem("reject");
                if(revMRoomGrid.cells(rId, 9).getValue() == '-') {
                    revMRoomToolbar.disableItem("snack");
                } else {
                    revMRoomToolbar.enableItem("snack");
                }
                revMRoomGridToolbar.enableItem("update");
                revMRoomGridToolbar.enableItem("change_hour");
                revMRoomGridToolbar.enableItem("closed");
            } else if(revMRoomGrid.cells(rId, 17).getValue() == 'REJECTED' || revMRoomGrid.cells(rId, 17).getValue() == 'CLOSED') {
                revMRoomToolbar.disableItem("approve");
                revMRoomToolbar.disableItem("reject");
                revMRoomToolbar.disableItem("snack");
                revMRoomGridToolbar.disableItem("update");
                revMRoomGridToolbar.disableItem("change_hour");
                revMRoomGridToolbar.disableItem("closed");
            } else {
                if(userLogged.rankId >= 3 && userLogged.rankId <= 6) {
                    if(revMRoomGrid.cells(rId, 2).getValue() == '-') {
                        revMRoomToolbar.enableItem("approve");
                        revMRoomToolbar.enableItem("reject");
                        revMRoomToolbar.disableItem("snack");
                    } else {
                        revMRoomToolbar.disableItem("approve");
                        revMRoomToolbar.disableItem("reject");
                        revMRoomToolbar.disableItem("snack");
                    }
                } else {
                    revMRoomToolbar.disableItem("approve");
                    revMRoomToolbar.disableItem("reject");
                    revMRoomToolbar.disableItem("snack");
                }
                revMRoomGridToolbar.disableItem("update");
                revMRoomGridToolbar.disableItem("change_hour");
                revMRoomGridToolbar.disableItem("closed");
            }
        });

        function setGridDP() {
            revMRoomGridDP = new dataProcessor(GAOther('updateConfirmBatch'));
            revMRoomGridDP.setTransactionMode("POST", true);
            revMRoomGridDP.setUpdateMode("Off");
            revMRoomGridDP.init(revMRoomGrid);
        }

        setGridDP();
        revMRoomGrid.init();

        function rRevGrid() {
            revMRoomLayout.cells("a").progressOn();
            let start = $("#ga_start_mroom_rev").val();
            let end = $("#ga_end_mroom_rev").val();
            let status = $("#ga_status_mroom_rev").val();
            let params = {
                search: revMRoomToolbar.getValue("search"),
                betweendate_start_date: start+","+end
            };

            if(status != "ALL") {
                params.equal_status = status;
            }

            revMRoomGrid.clearAndLoad(GAOther("getMeetingRevGrid", params), revMRoomGridCount);
        }

        rRevGrid();
    }

    function selectSnack(snackId) {
        currSnack = snackId;
        $(".snack_selected").removeClass("snack_selected");
        $("#snack-" + snackId).addClass("snack_selected");
    }

JS;

header('Content-Type: application/javascript');
echo $script;
