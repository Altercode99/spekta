<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

    function employeeTab() {
        if (!mainTab.tabs("employee")){
            mainTab.addTab("employee", tabsStyle("person_16.png", "Data Karyawan"), null, null, true, true);
            showEmployee();
        } else {
            mainTab.tabs("employee").setActive();
        }
    }

    function basicSallaryTab() {
        if (!mainTab.tabs("basic_sallary")){
            mainTab.addTab("basic_sallary", tabsStyle("money.png", "Data Gaji Karyawan"), null, null, true, true);
            showBasicSallary();
        } else {
            mainTab.tabs("basic_sallary").setActive();
        }
    }

    function masterNasionalFreeTab() {
        if (!mainTab.tabs("national_freeday")){
            mainTab.addTab("national_freeday", tabsStyle("calendar.png", "Data Libur Nasional"), null, null, true, true);
            showNationalFree();
        } else {
            mainTab.tabs("national_freeday").setActive();
        }
    }

    function superiorSallaryTab() {
        if (!mainTab.tabs("superior_sallary")){
            mainTab.addTab("superior_sallary", tabsStyle("money.png", "Data Gaji Atasan"), null, null, true, true);
            showSuperiorSallary();
        } else {
            mainTab.tabs("superior_sallary").setActive();
        }
    }

    function empPinTab() {
        if (!mainTab.tabs("hr_data_pin_karyawan")){
            mainTab.addTab("hr_data_pin_karyawan", tabsStyle("key.png", "Data PIN Karyawan", "background-size: 16px 16px"), null, null, true, true);
            showEmpPin();
        } else {
            mainTab.tabs("hr_data_pin_karyawan").setActive();
        }
    }

    function hrReportOvtTab() {
        if (!mainTab.tabs("hr_report_overtime")){
            mainTab.addTab("hr_report_overtime", tabsStyle("app18.png", "Report Lembur", "background-size: 16px 16px"), null, null, true, true);
            showHrReportOvertime();
        } else {
            mainTab.tabs("hr_report_overtime").setActive();
        }
    }
    
    function hrVerifiedOvtTab() {
        if (!mainTab.tabs("hr_verified_overtime")){
            mainTab.addTab("hr_verified_overtime", tabsStyle("ok.png", "Verified Lembur", "background-size: 16px 16px"), null, null, true, true);
            showHrVerifiedOvertime();
        } else {
            mainTab.tabs("hr_verified_overtime").setActive();
        }
    }

    function hrRevisionOvtTab() {
        if (!mainTab.tabs("hr_revision_overtime")){
            mainTab.addTab("hr_revision_overtime", tabsStyle("clock.png", "Request Revisi Lembur", "background-size: 16px 16px"), null, null, true, true);
            showHrRevisionOvertime();
        } else {
            mainTab.tabs("hr_revision_overtime").setActive();
        }
    }
    
JS;

echo $script;