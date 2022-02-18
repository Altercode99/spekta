<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

    function affairAccordion() {
        checkTrees();
        $("#title-menu").html("General Affair");
        accordionItems.map(id => myTree.removeItem(id));
        accordionItems.push("a");

        if(isHaveAcc("ga_other")) {
            myTree.addItem("a", "Umum", true);
            var otherItems = [];
            var otherSubItems = [];
            var roomSubItems = [];
            var vehicleItems = [];

            //KATERING
            if(isHaveTrees("ga_vendor_katering")) {
                otherSubItems.push({id: "ga_vendor_katering", text: "Vendor Katering", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("ga_snack_meeting")) {
                otherSubItems.push({id: "ga_snack_meeting", text: "Snack Meeting", icons: {file: "menu_icon"}});
            }

            //RUANG MEETING
            if(isHaveTrees("ga_meeting_room_reservation")) {
                roomSubItems.push({id: "ga_meeting_room_reservation", text: "Reservasi R. Meeting", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("ga_meeting_room_report")) {
                roomSubItems.push({id: "ga_meeting_room_report", text: "Report R. Meeting", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("ga_meeting_room_monitoring")) {
                roomSubItems.push({id: "ga_meeting_room_monitoring", text: "Monitoring Jadwal", icons: {file: "menu_icon"}});
            }

            //KENDARAAN DINAS
            if(isHaveTrees("ga_vehicle_reservation")) {
                vehicleItems.push({id: "ga_vehicle_reservation", text: "Reservasi Kendaraan", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("ga_vehicle_report")) {
                vehicleItems.push({id: "ga_vehicle_report", text: "Report Kendaraan", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("ga_vehicle_monitoring")) {
                vehicleItems.push({id: "ga_vehicle_monitoring", text: "Monitoring Jadwal", icons: {file: "menu_icon"}});
            }

            //TREES
            if(isHaveTrees("ga_katering")) {
                otherItems.push({id: "ga_katering", text: "Katering", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: otherSubItems});
            }

            if(isHaveTrees("ga_meeting_rooms")) {
                otherItems.push({id: "ga_meeting_rooms", text: "Ruang Meeting", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: roomSubItems});
            }

            if(isHaveTrees("ga_vehicles")) {
                otherItems.push({id: "ga_vehicles", text: "Kendaraan Dinas", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: vehicleItems});
            }

            var otherTree = myTree.cells("a").attachTreeView({
                items: otherItems
            });

            otherTree.attachEvent("onClick", function(id) {
                if(id == "ga_vendor_katering") {
                    showCatheringTab();
                } else if(id == "ga_snack_meeting") {
                    showMeetingSnackTab();
                } else if(id == "ga_meeting_room_reservation") {
                    showMeetingRevTab();
                } else if(id == "ga_meeting_room_report") {
                    showMeetingReportTab();
                } else if(id == "ga_meeting_room_monitoring") {
                    mRoomListTab();
                } else if(id == "ga_vehicle_reservation") {
                    showVehicleRevTab();
                } else if(id == "ga_vehicle_report") {
                    showVehicleReportTab();
                } else if(id == "ga_vehicle_monitoring") {
                    vehicleListTab();
                } 
            });
        }
    }
JS;

echo $script;


