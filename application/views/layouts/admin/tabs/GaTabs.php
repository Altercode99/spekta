<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}
$script = <<< "JS"

    function showCatheringTab() {
        if (!mainTab.tabs("ga_cathering_vendor")){
            mainTab.addTab("ga_cathering_vendor", tabsStyle("food.png", "Vendor Katering"), null, null, true, true);
            showCathering();
        } else {
            mainTab.tabs("ga_cathering_vendor").setActive();
        }
    }

    function showMeetingSnackTab() {
        if (!mainTab.tabs("ga_meeting_snack")){
            mainTab.addTab("ga_meeting_snack", tabsStyle("food.png", "Snack Meeting"), null, null, true, true);
            showMeetingSnack();
        } else {
            mainTab.tabs("ga_meeting_snack").setActive();
        }
    }

    function showMeetingRevTab() {
        if (!mainTab.tabs("ga_meeting_rooms_reservation")){
            mainTab.addTab("ga_meeting_rooms_reservation", tabsStyle("calendar.png", "Reservasi R. Meeting", "background-size: 16px 16px"), null, null, true, true);
            showMeetingRev();
        } else {
            mainTab.tabs("ga_meeting_rooms_reservation").setActive();
        }
    }

    function showMeetingReportTab() {
        if (!mainTab.tabs("ga_meeting_rooms_report")){
            mainTab.addTab("ga_meeting_rooms_report", tabsStyle("app18.png", "Report R. Meeting", "background-size: 16px 16px"), null, null, true, true);
            showMeetingReport();
        } else {
            mainTab.tabs("ga_meeting_rooms_report").setActive();
        }
    }

    function showVehicleRevTab() {
        if (!mainTab.tabs("ga_vehicles_reservation")){
            mainTab.addTab("ga_vehicles_reservation", tabsStyle("calendar.png", "Reservasi Kendaraan", "background-size: 16px 16px"), null, null, true, true);
            showVehicleRev();
        } else {
            mainTab.tabs("ga_vehicles_reservation").setActive();
        }
    }

    function showVehicleReportTab() {
        if (!mainTab.tabs("ga_vehicles_report")){
            mainTab.addTab("ga_vehicles_report", tabsStyle("app18.png", "Report Kendaraan", "background-size: 16px 16px"), null, null, true, true);
            showVehicleReport();
        } else {
            mainTab.tabs("ga_vehicles_report").setActive();
        }
    }

JS;

echo $script;