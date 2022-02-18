<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

    function dashboardAccordion() {
        checkTrees();
        $("#title-menu").html("Dashboard");
        accordionItems.map(id => myTree.removeItem(id));
        accordionItems.push("a");

        if(isHaveAcc("dashboard_performance")) {
            myTree.addItem("a", "Performa", true);
            var dashItems = [];
            var dashOvtSubItems = [];
            var dashMRoomSubItems = [];
            var dashVehicleSubItems = [];

            //SUMMARY OVERTIME
            if(isHaveTrees("dashboard_overtime_summary")) {
                dashOvtSubItems.push({id: "dashboard_overtime_summary", text: "Summary By Personil", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("dashboard_overtime_summary_provider")) {
                dashOvtSubItems.push({id: "dashboard_overtime_summary_provider", text: "Summary By Penyelenggara", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("dashboard_overtime_comparation")) {
                dashOvtSubItems.push({id: "dashboard_overtime_comparation", text: "Komparasi Lembur", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("dashboard_overtime_machine")) {
                dashOvtSubItems.push({id: "dashboard_overtime_machine", text: "Waktu Pengunaan Mesin", icons: {file: "menu_icon"}});
            }

            //SUMMARY MEETING ROOM
            if(isHaveTrees("dashboard_meeting_room_summary")) {
                dashMRoomSubItems.push({id: "dashboard_meeting_room_summary", text: "Summary By Reservasi", icons: {file: "menu_icon"}});
            }

            //SUMMARY VEHICLE
            if(isHaveTrees("dashboard_vehicle_summary")) {
                dashVehicleSubItems.push({id: "dashboard_vehicle_summary", text: "Summary By Reservasi", icons: {file: "menu_icon"}});
            }

            //TREES
            if(isHaveTrees("dashboard_overtime_tree")) {
                dashItems.push({id: "dashboard_overtime_tree", text: "Lembur", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: dashOvtSubItems});
            }

            if(isHaveTrees("dashboard_meeting_room")) {
                dashItems.push({id: "dashboard_meeting_room", text: "Ruang Meeting", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: dashMRoomSubItems});
            }

            if(isHaveTrees("dashboard_vehicle")) {
                dashItems.push({id: "dashboard_vehicle", text: "Kendaraan Dinas", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: dashVehicleSubItems});
            }

            var dashOvtTree = myTree.cells("a").attachTreeView({
                items: dashItems
            });

            dashOvtTree.attachEvent("onClick", function(id) {
                if(id == "dashboard_overtime_summary") {
                    showDashboardOvertimeTab();
                } else if(id == "dashboard_overtime_summary_provider") {
                    showDashboardOvertimeProviderTab();
                } else if(id == "dashboard_overtime_comparation") {
                    showDashOvertimeCompareTab();
                } else if(id == "dashboard_overtime_machine") {
                    showDashOvertimeMachineTab();
                } else if(id == "dashboard_meeting_room_summary") {
                    showDashMRoomSumTab();
                } else if(id == "dashboard_vehicle_summary") {
                    showDashVehicleSumTab();
                } 
            });
        }
    }

JS;

echo $script;
