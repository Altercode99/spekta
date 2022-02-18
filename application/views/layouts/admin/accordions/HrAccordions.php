<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

    function hrAccordion() {
        checkTrees();
        $("#title-menu").html("Human Resource");
        accordionItems.map(id => myTree.removeItem(id));
        accordionItems.push("a");

        if(isHaveAcc("hr_manajemen_karyawan")) {
            myTree.addItem("a", "Manajemen Karyawan", true);
            var empItems = [];
            var empSubItems_1 = [];
            var empSubItems_2 = [];
            var empSubItems_3 = [];
            var empSubItems_4 = [];

            //@KARYAWAN
            if(isHaveTrees("hr_data_karyawan")) {
                empSubItems_1.push({id: "hr_data_karyawan", text: "Data Karyawan", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("hr_data_gaji")) {
                empSubItems_1.push({id: "hr_data_gaji", text: "Data Gaji Karyawan", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("hr_data_gaji_atasan")) {
                empSubItems_1.push({id: "hr_data_gaji_atasan", text: "Data Gaji Atasan", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("hr_data_pin_karyawan")) {
                empSubItems_1.push({id: "hr_data_pin_karyawan", text: "Data PIN Karyawan", icons: {file: "menu_icon"}});
            }

            //@LEMBUR

            if(isHaveTrees("hr_report_lembur")) {
                empSubItems_4.push({id: "hr_report_lembur", text: "Report Lembur", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("hr_verified_lembur")) {
                empSubItems_4.push({id: "hr_verified_lembur", text: "Verified Lembur", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("hr_revisi_lembur")) {
                empSubItems_4.push({id: "hr_revisi_lembur", text: "Request Revisi Lembur", icons: {file: "menu_icon"}});
            }

            //@MASTER KEPEGAWAIAN
            if(isHaveTrees("hr_data_departemen")) {
                empSubItems_2.push({id: "hr_data_departemen", text: "Data Sub Unit", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("hr_data_sub_departemen")) {
                empSubItems_2.push({id: "hr_data_sub_departemen", text: "Data Bagian", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("hr_data_divisi")) {
                empSubItems_2.push({id: "hr_data_divisi", text: "Data Sub Bagian", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("hr_data_jabatan")) {
                empSubItems_2.push({id: "hr_data_jabatan", text: "Data Jabatan", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("hr_data_training")) {
                empSubItems_2.push({id: "hr_data_training", text: "Data Training", icons: {file: "menu_icon"}});
            }

             //@MASTER LIBUR NASIONAL
             if(isHaveTrees("hr_data_libur_nasional")) {
                empSubItems_3.push({id: "hr_data_libur_nasional", text: "Libur Nasional", icons: {file: "menu_icon"}});
            }

            //@TREES
            if(isHaveTrees("hr_karyawan")) {
                empItems.push({id: "hr_karyawan", text: "Karyawan", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: empSubItems_1});
            }

            if(isHaveTrees("hr_lembur")) {
                empItems.push({id: "hr_lembur", text: "Lembur", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: empSubItems_4});
            }

            if(isHaveTrees("hr_master_kepegawaian")) {
                empItems.push({id: "hr_master_kepegawaian", text: "Master Kepegawaian", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: empSubItems_2});
            }

            if(isHaveTrees("hr_libur_nasional")) {
                empItems.push({id: "hr_libur_nasional", text: "Master Libur Nasional", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: empSubItems_3});
            }

            var employeeTree = myTree.cells("a").attachTreeView({
                items: empItems
            });

            employeeTree.attachEvent("onClick", function(id) {
                if(id == "hr_data_karyawan") {
                    employeeTab();
                } else if(id == "hr_data_gaji") {
                    basicSallaryTab();
                } else if(id == "hr_data_gaji_atasan") {
                    superiorSallaryTab();
                } else if(id == "hr_data_pin_karyawan") {
                    empPinTab();
                } else if(id == "hr_report_lembur") {
                    hrReportOvtTab();
                } else if(id == "hr_verified_lembur") {
                    hrVerifiedOvtTab();
                } else if(id == "hr_revisi_lembur") {
                    hrRevisionOvtTab();
                } else if(id == "hr_data_departemen") {
                    masterDeptTab();
                } else if(id == "hr_data_sub_departemen") {
                    masterSubDeptTab();
                } else if(id == "hr_data_divisi") {
                    masterDivisionTab();
                } else if(id == "hr_data_jabatan") {
                    masterRankTab();
                } else if(id == "hr_data_training") {
                    masterTrainingTab();
                } else if(id == "hr_data_libur_nasional") {
                    masterNasionalFreeTab();
                }
            });
        }
    }

JS;

echo $script;


