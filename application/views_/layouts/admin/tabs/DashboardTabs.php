<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}
$script = <<< "JS"

    function showDashboardOvertimeTab() {
        if (!mainTab.tabs("dashboard_overtime_summary_tab")){
            mainTab.addTab("dashboard_overtime_summary_tab", tabsStyle("bar_chart.png", "Summary By Personil", "background-size: 16px 16px"), null, null, true, true);
            showDashboardOvertimeSummary();
        } else {
            mainTab.tabs("dashboard_overtime_summary_tab").setActive();
        }
    }

    function showDashboardOvertimeProviderTab() {
        if (!mainTab.tabs("dashboard_overtime_summary_provider_tab")){
            mainTab.addTab("dashboard_overtime_summary_provider_tab", tabsStyle("bar_chart.png", "Summary By Penyelenggara", "background-size: 16px 16px"), null, null, true, true);
            showDashboardOvertimeSummaryProvider();
        } else {
            mainTab.tabs("dashboard_overtime_summary_provider_tab").setActive();
        }
    }

    function showDashOvertimeCompareTab() {
        if (!mainTab.tabs("dashboard_overtime_comparasion_tab")){
            mainTab.addTab("dashboard_overtime_comparasion_tab", tabsStyle("double_chart.png", "Komparasi Lemburan", "background-size: 16px 16px"), null, null, true, true);
            showDashOvertimeCompare();
        } else {
            mainTab.tabs("dashboard_overtime_comparasion_tab").setActive();
        }
    }

    function showDashOvertimeMachineTab() {
        if (!mainTab.tabs("dashboard_overtime_machine_tab")){
            mainTab.addTab("dashboard_overtime_machine_tab", tabsStyle("bar_chart.png", "Waktu Penggunaan Mesin", "background-size: 16px 16px"), null, null, true, true);
            showDashOvertimeMachine();
        } else {
            mainTab.tabs("dashboard_overtime_machine_tab").setActive();
        }
    }

    function showDashMRoomSumTab() {
        if (!mainTab.tabs("dashboard_meeting_room_summary")){
            mainTab.addTab("dashboard_meeting_room_summary", tabsStyle("bar_chart.png", "Summary By Reservasi", "background-size: 16px 16px"), null, null, true, true);
            showDashMRoomSum();
        } else {
            mainTab.tabs("dashboard_meeting_room_summary").setActive();
        }
    }

    function showDashVehicleSumTab() {
        if (!mainTab.tabs("dashboard_vehicle_summary")){
            mainTab.addTab("dashboard_vehicle_summary", tabsStyle("bar_chart.png", "Summary By Reservasi", "background-size: 16px 16px"), null, null, true, true);
            showDashVehicleSum();
        } else {
            mainTab.tabs("dashboard_vehicle_summary").setActive();
        }
    }

JS;

echo $script;