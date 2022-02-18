<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}
$script = <<< "JS"

    function userTab() {
        if (!mainTab.tabs("user")){
            mainTab.addTab("user", tabsStyle("userinfo.png", "Data User", "background-size: 16px 16px"), null, null, true, true);
            showUser();
        } else {
            mainTab.tabs("user").setActive();
        }
    }

    function sendEmailTab() {
        if (!mainTab.tabs("am_send_email_tab")){
            mainTab.addTab("am_send_email_tab", tabsStyle("email.png", "Pengiriman Email", "background-size: 16px 16px"), null, null, true, true);
            showEmailSend();
        } else {
            mainTab.tabs("am_send_email_tab").setActive();
        }
    }

    function accControlTab(sub) {
        let id = sub.id.replace("sub-", "");
        let text = sub.text;
        if (!mainTab.tabs("access_control_" + id)){
            mainTab.addTab("access_control_" + id, tabsStyle("puzzle_16.png", "Akses Kontrol " + text, "background-size: 16px 16px"), null, null, true, true);
            showAccessControl(id);
        } else {
            mainTab.tabs("access_control_" + id).setActive();
        }
    }

    function accControlDeptTab(dept) {
        let id = dept.id.replace("dept-", "");
        let text = dept.text;
        if (!mainTab.tabs("access_control_dept_" + id)){
            mainTab.addTab("access_control_dept_" + id, tabsStyle("puzzle_16.png", "Akses Kontrol " + text, "background-size: 16px 16px"), null, null, true, true);
            showAccessControlDept(id);
        } else {
            mainTab.tabs("access_control_dept_" + id).setActive();
        }
    }

    function masterLocTab() {
        if (!mainTab.tabs("master_location")){
            mainTab.addTab("master_location", tabsStyle("map_16.png", "Data Lokasi"), null, null, true, true);
            showMasterLocation();
        } else {
            mainTab.tabs("master_location").setActive();
        }
    }

    function masterDeptTab() {
        if (!mainTab.tabs("master_department")){
            mainTab.addTab("master_department", tabsStyle("puzzle_16.png", "Data Departemen"), null, null, true, true);
            showMasterDept();
        } else {
            mainTab.tabs("master_department").setActive();
        }
    }

    function masterSubDeptTab() {
        if (!mainTab.tabs("master_sub_department")){
            mainTab.addTab("master_sub_department", tabsStyle("puzzle_16.png", "Data Sub Departemen"), null, null, true, true);
            showMasterSubDept();
        } else {
            mainTab.tabs("master_sub_department").setActive();
        }
    }

    function masterRankTab() {
        if (!mainTab.tabs("master_rank")){
            mainTab.addTab("master_rank", tabsStyle("medal.png", "Data Jabatan"), null, null, true, true);
            showMasterRank();
        } else {
            mainTab.tabs("master_rank").setActive();
        }
    }

    function masterDivisionTab() {
        if (!mainTab.tabs("master_division")){
            mainTab.addTab("master_division", tabsStyle("group.png", "Data Divisi"), null, null, true, true);
            showMasterDivision();
        } else {
            mainTab.tabs("master_division").setActive();
        }
    }

    function masterTrainingTab() {
        if (!mainTab.tabs("master_training")){
            mainTab.addTab("master_training", tabsStyle("certificate.png", "Data Jenis Training", "background-size: 16px 16px"), null, null, true, true);
            showMasterTraining();
        } else {
            mainTab.tabs("master_training").setActive();
        }
    }

    function masterBuildingTab() {
        if (!mainTab.tabs("master_building")){
            mainTab.addTab("master_building", tabsStyle("building.png", "Data Gedung", "background-size: 16px 16px"), null, null, true, true);
            showMasterBuilding();
        } else {
            mainTab.tabs("master_building").setActive();
        }
    }

    function masterBuildingRoomTab() {
        if (!mainTab.tabs("master_building_room")){
            mainTab.addTab("master_building_room", tabsStyle("room.png", "Data Ruang Gedung", "background-size: 16px 16px"), null, null, true, true);
            showMasterBuildingRoom();
        } else {
            mainTab.tabs("master_building_room").setActive();
        }
    }

    function masterMeetingRoomTab() {
        if (!mainTab.tabs("master_meeting_room")){
            mainTab.addTab("master_meeting_room", tabsStyle("meeting_group.png", "Ruang Meeting", "background-size: 16px 16px"), null, null, true, true);
            showMasterMeetingRoom();
        } else {
            mainTab.tabs("master_meeting_room").setActive();
        }
    }

    function masterVehicleTab() {
        if (!mainTab.tabs("master_vehicle")){
            mainTab.addTab("master_vehicle", tabsStyle("car_16.png", "Kendaraan Dinas", "background-size: 16px 16px"), null, null, true, true);
            showVehicle();
        } else {
            mainTab.tabs("master_vehicle").setActive();
        }
    }

    function masterPMachineTab() {
        if (!mainTab.tabs("master_production_machine")){
            mainTab.addTab("master_production_machine", tabsStyle("building_16.png", "Mesin Produksi", "background-size: 16px 16px"), null, null, true, true);
            showPMachine();
        } else {
            mainTab.tabs("master_production_machine").setActive();
        }
    }

    function reqOvertimeTab() {
        if (!mainTab.tabs("master_overtime_requirement")){
            mainTab.addTab("master_overtime_requirement", tabsStyle("clock.png", "Lembur", "background-size: 16px 16px"), null, null, true, true);
            showReqOvertime();
        } else {
            mainTab.tabs("master_overtime_requirement").setActive();
        }
    }

    function masterPICTab() {
        if (!mainTab.tabs("master_pic")){
            mainTab.addTab("master_pic", tabsStyle("person_16.png", "Data PIC", "background-size: 16px 16px"), null, null, true, true);
            showMasterPIC();
        } else {
            mainTab.tabs("master_pic").setActive();
        }
    }

JS;

echo $script;
