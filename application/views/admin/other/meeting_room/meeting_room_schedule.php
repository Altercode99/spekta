<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	
	function showRoomSchedule() {	
        var roomData = [];
        var persons = [];
        var guests = [];
        var timeLightbox;

        var scheduleLayout = mainTab.cells("meeting_room_schedule").attachLayout({
            pattern: "1C",
            cells: [{
                    id: "a",
                    header: false
                }
            ]
        });
        
        scheduler1 = Scheduler.getSchedulerInstance();
        scheduler1.clearAll();
        scheduleLayout.cells("a").attachScheduler(null, "month", null, scheduler1);

        var rooms = reqJsonResponse(RoomRev("getRooms"), "GET", {});
        roomData = rooms.detail;

        scheduler1.locale.labels.section_name = "Judul Kegiatan";
        scheduler1.locale.labels.section_meeting_type = "Jenis Kegiatan";
        scheduler1.locale.labels.section_description = "Deskripsi Kegiatan";
        scheduler1.locale.labels.section_type = "Jenis Meeting";
        scheduler1.locale.labels.section_room = "Ruang Meeting";
        scheduler1.locale.labels.section_auto = "Waktu Reservasi";
        scheduler1.locale.labels.section_repeat = "Repeat Meeting";
        scheduler1.locale.labels.section_meal = "Snack";
        scheduler1.locale.labels.section_participant = "Peserta Meeting";
        scheduler1.locale.labels.section_guest = "Tamu";

        scheduler1.config.lightbox.sections = [
            {name:"name", height:32, map_to:"name", type:"textarea" , focus:true},
            {name:"meeting_type", height:40, map_to:"meeting_type", type:"select" , options: [
                {key: 'internal', label: 'Meeting Internal'},
                {key: 'external', label: 'Meeting Eksternal'}
            ]},
            {name:"description", height:50, map_to:"description", type:"textarea"},
            {name:"type", height:40, map_to:"type", type:"select" , options: [
                {key: 0, label: 'Offline'},
                {key: 1, label: 'Online'}
            ]},
            {name:"time", height:70, type:"time", map_to:"auto"},
            {name:"room", height:40, map_to:"room", type:"select", options: rooms.data},
            {name:"meal", height:40, map_to:"meal", type:"select" , options: [
                {key: 0, label: 'Tanpa Snack'},
                {key: 1, label: 'Dengan Snack'}
            ]},
            {name:"repeat", height:40, map_to:"repeat", type:"select" , options: [
                {key: 1, label: '1x'},
                {key: 2, label: '2x'},
                {key: 3, label: '3x'},
                {key: 4, label: '4x'},
                {key: 5, label: '5x'},
                {key: 6, label: '6x'},
                {key: 7, label: '7x'},
            ]},
            {name:"participant", height:32, map_to:"participant", type:"textarea"},
            {name:"guest", height:32, map_to:"guest", type:"textarea"},
        ];

        scheduler1.config.buttons_right = ["dhx_save_btn", "dhx_cancel_btn"];
        scheduler1.config.buttons_left = ["dhx_delete_btn", "participant_button", "guest_button"];
        scheduler1.config.drag_resize = false;
        scheduler1.config.drag_move = false;
        scheduler1.config.drag_create = false;
        scheduler1.config.time_step = 30;
        scheduler1.config.first_hour = 8;
        scheduler1.config.last_hour = 16;

        scheduler1.locale.labels["participant_button"] = "Peserta";
        scheduler1.locale.labels["guest_button"] = "Tamu";

        scheduler1.attachEvent("onLightbox", function(){
            var section = scheduler1.formSection("participant");
            var section1 = scheduler1.formSection("guest");
            section.control.disabled = true;
            section1.control.disabled = true;

            typeLightbox = scheduler1.formSection("type");
            onTypeChange(typeLightbox);

            timeLightbox = scheduler1.formSection("time");
            onTimeChange(timeLightbox);

            typeLightbox.control.onchange = function(e) {
                onTypeChange(typeLightbox);
            }

            if(typeLightbox.getValue() === "Offline") {
                timeLightbox.control[0].onchange = function(e) {
                    onTimeChange(timeLightbox);
                }
                timeLightbox.control[1].onchange = function(e) {
                    onTimeChange(timeLightbox);
                }
                timeLightbox.control[2].onchange = function(e) {
                    onTimeChange(timeLightbox);
                }
                timeLightbox.control[3].onchange = function(e) {
                    onTimeChange(timeLightbox);
                }
                timeLightbox.control[4].onchange = function(e) {
                    onTimeChange(timeLightbox);
                }
                timeLightbox.control[5].onchange = function(e) {
                    onTimeChange(timeLightbox);
                }
                timeLightbox.control[6].onchange = function(e) {
                    onTimeChange(timeLightbox);
                }
                timeLightbox.control[7].onchange = function(e) {
                    onTimeChange(timeLightbox);
                }
            }
        });

        function onTypeChange(type) { 
            let typeMeeting  = type.getValue();
            if(typeMeeting == 1) {
                scheduler1.formSection('meal').setValue("0");
                scheduler1.formSection('meal').control.disabled = true;
                scheduler1.formSection('room').setValue("0");
                scheduler1.formSection('room').control.disabled = true;
                scheduler1.formSection('repeat').setValue("1");
                scheduler1.formSection('repeat').control.disabled = true;
            } else {
                scheduler1.formSection('meal').control.disabled = false;
                scheduler1.formSection('room').control.disabled = false;
                scheduler1.formSection('repeat').control.disabled = false;
            }
        }

        function onTimeChange(time) { 
            let startDate = time.getValue().start_date;
            let endDate = time.getValue().end_date;

            let timeDif = timeDiffCalc(startDate, endDate);

            if(timeDif.days > 0) {
                return eAlert("Meeting harus dimulai dan selesai di hari yang sama");
            }

            if(timeDif.hours >= 2) {
                scheduler1.formSection('meal').setValue("1");
                scheduler1.formSection('meal').control.disabled = true;
            } else {
                scheduler1.formSection('meal').setValue("0");
                scheduler1.formSection('meal').control.disabled = false;
            }
        }

        scheduler1.attachEvent("onLightboxButton", function(button_id, node, e){
            if(button_id == "participant_button"){
                persons = [];
                var participantWindow = createWindow("rm_participant", "Peserta Meeting", 900, 400);
                myWins.window("rm_participant").skipMyCloseEvent = true;

                if(scheduler1.formSection('participant').getValue() !== "") {
                    let person = scheduler1.formSection('participant').getValue().split(",");
                    person.map(email => email !== "" && persons.push(email));
                } 

                var participantToolbar = participantWindow.attachToolbar({
                    icon_path: "./public/codebase/icons/",
                    items: [
                        {id: "save", text: "Simpan", type: "button", img: "ok.png"}
                    ]
                });

                participantToolbar.attachEvent("onClick", function(id) {
                    switch (id) {
                        case "save":
                            participantGrid.filterBy(0,"");
                            setTimeout(() => {
                                if(participantGrid.getRowsNum() == 0) {
                                    eaAlert("Bersihkan Filter", "Data grid kosong, silahkan tutup window, klik X dibagian kanan atas!");
                                } else {
                                    scheduler1.formSection('participant').setValue(persons);
                                    closeWindow("rm_participant");
                                }
                            }, 250);
                            break;
                    }
                });

                let partStatusBar = participantWindow.attachStatusBar();

                function partGridCount() {
                    var partGridRows = participantGrid.getRowsNum();
                    partStatusBar.setText("Total baris: " + partGridRows);
                    persons.length > 0 && persons.map(id => id !== '' && participantGrid.cells(id, 1).setValue(1));
                }

                var max = roomData[scheduler1.formSection("room").getValue()].capacity;

                participantGrid = participantWindow.attachGrid();
                participantGrid.setImagePath("./public/codebase/imgs/");
                participantGrid.setHeader("No,Check,Nama Karyawan,Bagian,Jabatan,Email");
                participantGrid.attachHeader("#rspan,#rspan,#text_filter,#select_filter,#select_filter,#text_filter")
                participantGrid.setColSorting("int,na,str,str,str,str");
                participantGrid.setColAlign("center,left,left,left,left,left");
                participantGrid.setColTypes("rotxt,ch,rotxt,rotxt,rotxt,rotxt");
                participantGrid.setInitWidthsP("5,5,20,25,20,25");
                participantGrid.enableSmartRendering(true);
                participantGrid.attachEvent("onXLE", function() {
                    participantWindow.progressOff();
                });
                participantGrid.init();
                participantGrid.attachEvent("onCheckbox", function(rId, cIdn, state) {
                    if(state) {
                        if((persons.length + guests.length) >= max) {
                            eAlert("Melebihi kapasitas peserta!");
                            participantGrid.cells(rId, 1).setValue(0);
                        } else {
                            persons.push(rId);
                        }
                    } else {
                        persons.splice(persons.indexOf(rId), 1);
                    }
                });

                function loadParticipant() {
                    participantWindow.progressOn();
                    participantGrid.clearAndLoad(RoomRev("getEmployees", {equal_status: "ACTIVE", notequal_email: ""}), partGridCount);
                }

                loadParticipant();
            } else if(button_id == "guest_button") {
                guests = [];
                var guestWindow = createWindow("rm_guest", "Daftar Tamu", 1100, 400);
                myWins.window("rm_guest").skipMyCloseEvent = true;

                if(scheduler1.formSection('guest').getValue() !== "") {
                    let guest = scheduler1.formSection('guest').getValue().split(",");
                    guest.map(data => { 
                        if(data !== "") {
                            let newData = data.split(":");
                            guests.push({email: newData[0], total: newData[1], rId: newData[2]});
                        }
                    });
                } 

                var guestToolbar = guestWindow.attachToolbar({
                    icon_path: "./public/codebase/icons/",
                    items: [
                        {id: "add", text: "Tambah", type: "button", img: "add.png"},
                        {id: "update", text: "Simpan & Update Tamu", type: "button", img: "update.png"},
                        {id: "save", text: "Simpan", type: "button", img: "ok.png"},
                    ]
                });

                var guestMenu = guestWindow.attachMenu({
                    icon_path: "./public/codebase/icons/",
                    items: [
                        {id: "add", text: "<span style='color:red'>Update Jumlah Peserta Undangan sebelum anda <b>Checklist</b> buku tamu!</span>"}
                    ]
                });

                guestToolbar.attachEvent("onClick", function(id) {
                    switch (id) {
                        case "save":
                            guestGrid.filterBy(0,"");
                            setTimeout(() => {
                                if(guestGrid.getRowsNum() == 0) {
                                    eaAlert("Bersihkan Filter", "Data grid kosong, silahkan tutup window, klik X dibagian kanan atas!");
                                } else {
                                    let newGuest = "";
                                    guests.map(data => {
                                        if(newGuest== "") {
                                            newGuest =  data.email + ":" + data.total + ":" + data.rId;
                                        } else {
                                            newGuest = newGuest + "," + data.email + ":" + data.total + ":" + data.rId;
                                        }
                                    })
                                    scheduler1.formSection('guest').setValue(newGuest);
                                    closeWindow("rm_guest");
                                }
                            }, 250);
                            break;
                        case "add":
                            let newId = (new Date()).valueOf();
                            guestGrid.addRow(newId, ["", 0, "Nama Tamu", "Nama Perusahaan", "Email", "Jumlah Peserta Meeting"]);
                            break;
                        case "update":
                            if(!guestGrid.getChangedRows()) {
                                eAlert("Belum ada row yang di edit!");
                            } else {
                                guestToolbar.disableItem("update");
                                guestWindow.progressOn();
                                guestGridDP.sendData();
                                guestGridDP.attachEvent('onAfterUpdate', function(id, action, tid, tag) {
                                    let message = tag.getAttribute('message');
                                    switch (action) {
                                        case 'updated':
                                            let mSplit = message.split(",");
                                            mSplit.length >= 1 && mSplit[0] != "" && sAlert(mSplit[0]);
                                            mSplit.length >= 2 && mSplit[1] != "" && eAlert(mSplit[1]);
                                            loadGuest();
                                            guestToolbar.enableItem("update");
                                            guestWindow.progressOff();
                                            setGridDP();
                                            break;
                                    }
                                });
                                break;
                            }
                    }
                });

                let guestStatusBar = guestWindow.attachStatusBar();

                function guestGridCount() {
                    var guestGridRows = guestGrid.getRowsNum();
                    guestStatusBar.setText("Total baris: " + guestGridRows);
                    guests.length > 0 && guests.map(data => {
                        if(data !== '') {
                            guestGrid.cells(data.rId, 1).setValue(1);
                            guestGrid.cells(data.rId, 5).setValue(data.total);
                            guestGrid.cells(data.rId, 5).setDisabled(true);
                            guestGrid.cells(data.rId, 2).setDisabled(true);
                            guestGrid.cells(data.rId, 3).setDisabled(true);
                            guestGrid.cells(data.rId, 4).setDisabled(true);
                        }
                    });
                }

                var max = roomData[scheduler1.formSection("room").getValue()].capacity;

                guestGrid = guestWindow.attachGrid();
                guestGrid.setImagePath("./public/codebase/imgs/");
                guestGrid.setHeader("No,Check,Nama Tamu,Perusahaan,Email,Jumlah Peserta Meeting (Klik 2X)");
                guestGrid.attachHeader("#rspan,#rspan,#text_filter,#select_filter,#text_filter,#text_filter")
                guestGrid.setColSorting("int,na,str,str,str,str");
                guestGrid.setColAlign("center,left,left,left,left,left");
                guestGrid.setColTypes("rotxt,ch,ed,ed,ed,ed");
                guestGrid.setInitWidthsP("5,5,20,25,25,20");
                guestGrid.enableSmartRendering(true);
                guestGrid.setEditable(true);
                guestGrid.attachEvent("onXLE", function() {
                    guestWindow.progressOff();
                });
                guestGrid.init();
                guestGrid.attachEvent("onCheckbox", function(rId, cIdn, state) {
                    if(state) {
                        if((persons.length + guests.length) >= max) {
                            eAlert("Melebihi kapasitas penumpang!");
                            guestGrid.cells(rId, 1).setValue(0);
                        } else {
                            guests.push({email: guestGrid.cells(rId, 4).getValue(), total: guestGrid.cells(rId, 5).getValue(), rId});
                            guestGrid.cells(rId, 5).setDisabled(true);
                            guestGrid.cells(rId, 2).setDisabled(true);
                            guestGrid.cells(rId, 3).setDisabled(true);
                            guestGrid.cells(rId, 4).setDisabled(true);
                        }
                    } else {
                        guests = guests.filter(guest => rId != guest.rId);
                        guestGrid.cells(rId, 5).setDisabled(false);
                        guestGrid.cells(rId, 2).setDisabled(false);
                        guestGrid.cells(rId, 3).setDisabled(false);
                        guestGrid.cells(rId, 4).setDisabled(false);
                    }
                });
                function setGridDP() {
                    guestGridDP = new dataProcessor(RoomRev('addGuest'));
                    guestGridDP.setTransactionMode("POST", true);
                    guestGridDP.setUpdateMode("Off");
                    guestGridDP.init(guestGrid);
                }

                setGridDP();

                function loadGuest() {
                    guestWindow.progressOn();
                    guestGrid.clearAndLoad(RoomRev("getGuests"), guestGridCount);
                }
                isGridNumeric(guestGrid, [5]);
                loadGuest();
            }
        });

        function loadEvent() {
            scheduleLayout.cells("a").progressOn();
            scheduler1.clearAll();
            const state = scheduler1.getState();
            const data = {
                mode: state.mode,
                date: state.date,
                min_date: state.min_date.toISOString(),
                max_date: state.max_date.toISOString(),
            }
            scheduler1.load(RoomRev('getEvents', data));
            scheduleLayout.cells("a").progressOff();
        }

        scheduler1.attachEvent("onViewChange", function (new_mode , new_date){
            loadEvent();
        });

        $(".dhx_cal_tab").attr("style", "dispay:none");

        scheduler1.attachEvent("onClick", function(event_id, e){
            scheduler1._on_dbl_click(e||event);
            return false;
        });

        var dp = new dataProcessor(RoomRev('eventHandler')); 
        dp.init(scheduler1);
        dp.setTransactionMode("JSON");

        dp.attachEvent("onAfterUpdate", function(id, action, tid, response){
            let message = response.getAttribute("message");
            if(action === 'inserted' || action === 'updated') {
                sAlert(message);
                loadEvent();
            } else if(action === 'deleted'){
                eAlert(message);
            } else {
                eAlert(message);
            }
            loadEvent();
        });

        loadEvent();

    }

JS;

header('Content-Type: application/javascript');
echo $script;
    