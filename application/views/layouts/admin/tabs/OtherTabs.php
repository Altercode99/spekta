<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}
$script = <<< "JS"

    function mRoomScheduleTab() {
        if (!mainTab.tabs("meeting_room_schedule")){
            mainTab.addTab("meeting_room_schedule", tabsStyle("clock.png", "Jadwal Ruang Meeting", "background-size: 16px 16px"), null, null, true, true);
            showRoomSchedule();
        } else {
            mainTab.tabs("meeting_room_schedule").setActive();
        }
    }

    function mRoomListTab() {
        if (!mainTab.tabs("meeting_room_list")){
            mainTab.addTab("meeting_room_list", tabsStyle("meeting_group.png", "Daftar Ruang Meeting", "background-size: 16px 16px"), null, null, true, true);
            showRoomList();
        } else {
            mainTab.tabs("meeting_room_list").setActive();
        }
    }

    function vehicleScheduleTab() {
        if (!mainTab.tabs("vehicle_schedule")){
            mainTab.addTab("vehicle_schedule", tabsStyle("clock.png", "Jadwal Kendaraan", "background-size: 16px 16px"), null, null, true, true);
            showVehicleSchedule();
        } else {
            mainTab.tabs("vehicle_schedule").setActive();
        }
    }

    function vehicleListTab() {
        if (!mainTab.tabs("vehicle_list")){
            mainTab.addTab("vehicle_list", tabsStyle("car_16.png", "Daftar Ruang Meeting", "background-size: 16px 16px"), null, null, true, true);
            showVehicleList();
        } else {
            mainTab.tabs("vehicle_list").setActive();
        }
    }

    function inputOvertimeTab() {
        if (!mainTab.tabs("other_input_lembur")){
            if(!userLogged.picOvertime) {
                return eaAlert("Kesalahan Hak Akses", "Anda tidak memiliki hak akses sebagai Admin lemburan!");
            }
            mainTab.addTab("other_input_lembur", tabsStyle("clock.png", "Input Lembur", "background-size: 16px 16px"), null, null, true, true);
            showInputOvertime();
        } else {
            mainTab.tabs("other_input_lembur").setActive();
        }
    }

    function inputOvertimeAsmanTab() {
        if (!mainTab.tabs("other_input_lembur")){
            if(userLogged.pltRankId <= 4 || userLogged.rankId <= 4) {
                mainTab.addTab("other_input_lembur", tabsStyle("clock.png", "Input Lembur", "background-size: 16px 16px"), null, null, true, true);
                showInputOvertime();
            } else {
                return eaAlert("Kesalahan Hak Akses", "Anda tidak memiliki hak akses sebagai Admin lemburan!");
            }
        } else {
            mainTab.tabs("other_input_lembur").setActive();
        }
    }

    function appvOvertimeTab() {
        if (!mainTab.tabs("other_approval_overtime")){
            if(!userLogged.picOvertime) {
                return eaAlert("Kesalahan Hak Akses", "Anda tidak memiliki hak akses sebagai Admin lemburan!");
            }
            mainTab.addTab("other_approval_overtime", tabsStyle("ok.png", "Approval Lembur", "background-size: 16px 16px"), null, null, true, true);
            showAppvOvertime();
        } else {
            mainTab.tabs("other_approval_overtime").setActive();
        }
    }

    function reportOvertimeTab() {
        if (!mainTab.tabs("other_report_overtime")){
            if(!userLogged.picOvertime) {
                return eaAlert("Kesalahan Hak Akses", "Anda tidak memiliki hak akses sebagai Admin lemburan!");
            }
            mainTab.addTab("other_report_overtime", tabsStyle("app18.png", "Report Lembur", "background-size: 16px 16px"), null, null, true, true);
            showReportOvertime();
        } else {
            mainTab.tabs("other_report_overtime").setActive();
        }
    }

    function reportFormOvertimeTab() {
        if (!mainTab.tabs("other_report_form_lembur")){
            if(!userLogged.picOvertime) {
                return eaAlert("Kesalahan Hak Akses", "Anda tidak memiliki hak akses sebagai Admin lemburan!");
            }
            mainTab.addTab("other_report_form_lembur", tabsStyle("app18.png", "Rekap Form Lembur", "background-size: 16px 16px"), null, null, true, true);
            showFormOvertime();
        } else {
            mainTab.tabs("other_report_form_lembur").setActive();
        }
    }

    function reqRevOvertimeTab() {
        if (!mainTab.tabs("other_pengajuan_revisi_lembur")){
            if(!userLogged.picOvertime) {
                return eaAlert("Kesalahan Hak Akses", "Anda tidak memiliki hak akses sebagai Admin lemburan!");
            }
            mainTab.addTab("other_pengajuan_revisi_lembur", tabsStyle("clock.png", "Pengajuan Revisi Lembur", "background-size: 16px 16px"), null, null, true, true);
            showReqRevOvertime();
        } else {
            mainTab.tabs("other_pengajuan_revisi_lembur").setActive();
        }
    }

    function shiftManagementTab() {
        if (!mainTab.tabs("other_manajemen_shift")){
            mainTab.addTab("other_manajemen_shift", tabsStyle("clock.png", "Manajemen Shift", "background-size: 16px 16px"), null, null, true, true);
            showShiftManagement();
        } else {
            mainTab.tabs("other_manajemen_shift").setActive();
        }
    }

JS;

echo $script;