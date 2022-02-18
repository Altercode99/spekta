<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	
	function showVehicleSchedule() {	
        var driverGrid;
        var empGrid;
        var vehicleData = [];

        var scheduleLayout = mainTab.cells("vehicle_schedule").attachLayout({
            pattern: "1C",
            cells: [{
                    id: "a",
                    header: false
                }
            ]
        });

        scheduler2 = Scheduler.getSchedulerInstance();
        scheduler2.clearAll();
        scheduleLayout.cells("a").attachScheduler(null, "month", null, scheduler2);

        var vehicles = reqJsonResponse(VehicleRev("getVehicles"), "GET", {});
        vehicleData = vehicles.detail;

        scheduler2.locale.labels.section_destination = "Tujuan";
        scheduler2.locale.labels.section_trip_type = "Tipe Perjalanan";
        scheduler2.locale.labels.section_description = "Deskripsi Kegiatan";
        scheduler2.locale.labels.section_vehicle = "Kendaraan";
        scheduler2.locale.labels.section_auto = "Waktu Reservasi";
        scheduler2.locale.labels.section_driver = "Driver";
        scheduler2.locale.labels.section_passenger = "Penumpang";

        scheduler2.config.lightbox.sections = [
            {name:"destination", height:32, map_to:"destination", type:"textarea", focus:true},
            {name:"trip_type", height:40, map_to:"trip_type", type:"select", options: [
                {key: 'drop', label: "Pergi Saja (Drop)"},
                {key: 'pp', label: "Pulang Pergi"},
            ]},
            {name:"description", height:150, map_to:"description", type:"textarea"},
            {name:"vehicle", height:40, map_to:"vehicle", type:"select", options: vehicles.data},
            {name:"time", height:72, type:"time", map_to:"auto"},
            {name:"driver", height:32, map_to:"driver", type:"textarea"},
            {name:"passenger", height:32, map_to:"passenger", type:"textarea"}
        ];
 
        scheduler2.config.buttons_right = ["dhx_save_btn", "dhx_cancel_btn"];
        scheduler2.config.buttons_left = ["dhx_delete_btn", "driver_button", "emp_button"];
        scheduler2.config.drag_resize = false;
        scheduler2.config.drag_move = false;
        scheduler2.config.drag_create = false;
        scheduler2.config.time_step = 30;
        scheduler2.config.first_hour = 8;
        scheduler2.config.last_hour = 16;

        scheduler2.locale.labels["driver_button"] = "Driver";
        scheduler2.locale.labels["emp_button"] = "Penumpang";

        scheduler2.attachEvent("onLightbox", function(){
            var section = scheduler2.formSection("driver");
            section.control.disabled = true;
        });

        scheduler2.attachEvent("onLightboxButton", function(button_id, node, e){
            if(button_id == "driver_button"){
                var driverWindow = createWindow("driver", "Daftar Driver", 900, 400);
                myWins.window("driver").skipMyCloseEvent = true;

                var driver = scheduler2.formSection('driver').getValue().split(",");

                var driverToolbar = driverWindow.attachToolbar({
                    icon_path: "./public/codebase/icons/",
                    items: [
                        {id: "save", text: "Simpan", type: "button", img: "ok.png"}
                    ]
                });

                driverToolbar.attachEvent("onClick", function(id) {
                    switch (id) {
                        case "save":
                            const newDriver = driver.filter(id => id !== "");
                            scheduler2.formSection('driver').setValue(newDriver);
                            closeWindow("driver");
                            break;
                    }
                });

                let driverStatusBar = driverWindow.attachStatusBar();

                function driverGridCount() {
                    var driverGridRows = driverGrid.getRowsNum();
                    driverStatusBar.setText("Total baris: " + driverGridRows);
                    driver.length > 0 && driver.map(id => id !== '' && driverGrid.cells(id, 1).setValue(1));
                }

                function loadVehicle() {
                    
                    driverWindow.progressOn();
                    driverGrid = driverWindow.attachGrid();
                    driverGrid.setImagePath("./public/codebase/imgs/");
                    driverGrid.setHeader("No,Check,Nama Karyawan,Sub Unit,Jabatan,Email");
                    driverGrid.attachHeader("#rspan,#rspan,#text_filter,#select_filter,#select_filter,#text_filter")
                    driverGrid.setColSorting("int,na,str,str,str,str");
                    driverGrid.setColAlign("center,left,left,left,left,left");
                    driverGrid.setColTypes("rotxt,ch,rotxt,rotxt,rotxt,rotxt");
                    driverGrid.setInitWidthsP("5,5,20,25,20,25");
                    driverGrid.enableSmartRendering(true);
                    driverGrid.attachEvent("onXLE", function() {
                        driverWindow.progressOff();
                    });
                    driverGrid.init();
                    driverGrid.attachEvent("onCheckbox", function(rId,cIdn, state) {
                        if(state) {
                            if(driver.length > 1) {
                                eAlert("Hanya bisa memilih 1 Driver");
                                driverGrid.cells(rId, 1).setValue(0);
                            } else {
                                driver.push(rId);
                            }
                        } else {
                            driver.splice(driver.indexOf(rId), 1);
                        }
                    });
                    driverGrid.clearAndLoad(RoomRev("getEmployees", {equal_rank_id: "10", equal_status: "ACTIVE", notequal_email: ""}), driverGridCount);
                }

                loadVehicle();
            } else if(button_id === 'emp_button'){
                var empWindow = createWindow("emp_vehicle", "Penumpang", 900, 400);
                myWins.window("emp_vehicle").skipMyCloseEvent = true;

                var persons = [];
                if(scheduler2.formSection('passenger').getValue() !== "") {
                    let person = scheduler2.formSection('passenger').getValue().split(",");
                    person.map(email => email !== "" && persons.push(email));
                }

                var empToolbar = empWindow.attachToolbar({
                    icon_path: "./public/codebase/icons/",
                    items: [
                        {id: "save", text: "Simpan", type: "button", img: "ok.png"}
                    ]
                });

                empToolbar.attachEvent("onClick", function(id) {
                    switch (id) {
                        case "save":
                            scheduler2.formSection('passenger').setValue(persons);
                            closeWindow("emp_vehicle");
                            break;
                    }
                });

                let empStatusBar = empWindow.attachStatusBar();

                function empGridCount() {
                    var empGridRows = empGrid.getRowsNum();
                    empStatusBar.setText("Total baris: " + empGridRows);
                    persons.length > 0 && persons.map(id => empGrid.cells(id, 1).setValue(1));
                }

                var max = vehicleData[scheduler2.formSection("vehicle").getValue()].passenger_capacity;

                function loadPassenger() {
                    
                    empWindow.progressOn();
                    empGrid = empWindow.attachGrid();
                    empGrid.setImagePath("./public/codebase/imgs/");
                    empGrid.setHeader("No,Check,Nama Karyawan,Bagian,Jabatan,Email");
                    empGrid.attachHeader("#rspan,#rspan,#text_filter,#select_filter,#select_filter,#text_filter")
                    empGrid.setColSorting("int,na,str,str,str,str");
                    empGrid.setColAlign("center,left,left,left,left,left");
                    empGrid.setColTypes("rotxt,ch,rotxt,rotxt,rotxt,rotxt");
                    empGrid.setInitWidthsP("5,5,20,25,20,25");
                    empGrid.enableSmartRendering(true);
                    empGrid.attachEvent("onXLE", function() {
                        empWindow.progressOff();
                    });
                    empGrid.init();
                    empGrid.attachEvent("onCheckbox", function(rId, cIdn, state) {
                        if(state) {
                            if(persons.length >= max) {
                                eAlert("Melebihi kapasitas penumpang!");
                                empGrid.cells(rId, 1).setValue(0);
                            } else {
                                persons.push(rId);
                            }
                        } else {
                            persons.splice(persons.indexOf(rId), 1);
                        }
                    });
                    empGrid.clearAndLoad(RoomRev("getEmployees", {notequal_rank_id: "10", equal_status: "ACTIVE", notequal_email: ""}), empGridCount);
                }

                loadPassenger();
            }
        });

        function loadEvent() {
            
            scheduleLayout.cells("a").progressOn();
            scheduler2.clearAll();
            const state = scheduler2.getState();
            const data = {
                mode: state.mode,
                date: state.date,
                min_date: state.min_date.toISOString(),
                max_date: state.max_date.toISOString(),
            }
            scheduler2.load(VehicleRev('getEvents', data));
            scheduleLayout.cells("a").progressOff();
        }

        scheduler2.attachEvent("onViewChange", function (new_mode , new_date){
            loadEvent();
        });

        $(".dhx_cal_tab").attr("style", "dispay:none");

        scheduler2.attachEvent("onClick", function(event_id, e){
            scheduler2._on_dbl_click(e||event);
            return false;
        });

        var dp2 = new dataProcessor(VehicleRev('eventHandler')); 
        dp2.init(scheduler2);
        dp2.setTransactionMode("JSON");

        dp2.attachEvent("onBeforeUpdate", function(id, state, data){
            return isLogin();
        });

        dp2.attachEvent("onAfterUpdate", function(id, action, tid, response){
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
    