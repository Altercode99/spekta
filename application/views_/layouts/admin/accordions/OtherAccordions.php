<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

    async function otherAccordion() {
        checkTrees();
        $("#title-menu").html("Others");
        accordionItems.map(id => myTree.removeItem(id));
        accordionItems.push("a", "b");
        if(isHaveAcc("other_umum")) {
            myTree.addItem("a", "Umum", true);
            var generalItems = [];
            var subRoomItems = [];
            var subVehicleItems = [];

            //@RUANG MEETING
            if(isHaveTrees("other_reservasi_ruang_meeting")) {
                subRoomItems.push({id: "other_reservasi_ruang_meeting", text: "Reservasi Ruang Meeting", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("other_daftar_ruang_meeting")) {
                subRoomItems.push({id: "other_daftar_ruang_meeting", text: "Daftar Ruang Meeting", icons: {file: "menu_icon"}});
            }

            //@KENDARAAN INVENTARIS
            if(isHaveTrees("other_reservasi_kendaraan")) {
                subVehicleItems.push({id: "other_reservasi_kendaraan", text: "Reservasi Kendaraan", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("other_daftar_kendaraan")) {
                subVehicleItems.push({id: "other_daftar_kendaraan", text: "Daftar Kendaraan", icons: {file: "menu_icon"}});
            }
            
            //@TREES
            if(isHaveTrees('other_ruang_meeting')) {
                generalItems.push({id: "other_ruang_meeting", text: "Ruang Meeting", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: subRoomItems})
            }

            if(isHaveTrees('other_kendaraan_inventaris')) {
                generalItems.push({id: "other_kendaraan_inventaris", text: "Kendaraan Inventaris", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: subVehicleItems})
            }

            var generalTree = myTree.cells("a").attachTreeView({
                items: generalItems
            });

            generalTree.attachEvent("onClick", function(id) {
                if(id == "other_reservasi_ruang_meeting") {
                    mRoomScheduleTab();
                } else if(id == "other_daftar_ruang_meeting") {
                    mRoomListTab();
                } else if(id == "other_reservasi_kendaraan") {
                    vehicleScheduleTab();
                } else if(id == "other_daftar_kendaraan") {
                    vehicleListTab();
                } 
            });
        }

        if(isHaveAcc("other_lembur")) {
            myTree.addItem("b", "Lembur");
            var overtimeItems = [];
            var subOvertimeItems = [];

            //@LEMBURAN
            if(isHaveTrees("other_input_lembur")) {
                subOvertimeItems.push({id: "other_input_lembur", text: "Input Lembur", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("other_approval_lembur")) {
                subOvertimeItems.push({id: "other_approval_lembur", text: "Approval Lembur", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("other_report_lembur")) {
                subOvertimeItems.push({id: "other_report_lembur", text: "Report Lembur", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("other_pengajuan_revisi_lembur")) {
                subOvertimeItems.push({id: "other_pengajuan_revisi_lembur", text: "Pengajuan Revisi Lembur", icons: {file: "menu_icon"}});
            }

            //@TREES
            if(isHaveTrees('other_lembur')) {
                overtimeItems.push({id: "other_lembur", text: "Lembur", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: subOvertimeItems})
            }

            var overtimeTree = myTree.cells("b").attachTreeView({
                items: overtimeItems
            });

            overtimeTree.attachEvent("onClick", function(id) {
                if(id == "other_input_lembur") {
                    inputOvertimeTab();
                } else if(id == "other_approval_lembur") {
                    appvOvertimeTab();
                } else if(id == "other_report_lembur") {
                    reportOvertimeTab();
                } else if(id == "other_pengajuan_revisi_lembur") {
                    reqRevOvertimeTab();
                }
            });
        }
    }

JS;

echo $script;