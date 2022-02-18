<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

    async function accessAccordion() {
        checkTrees();
        $("#title-menu").html("Akses & Master");
        accordionItems.map(id => myTree.removeItem(id));
        accordionItems.push("a", "b");

        if(isHaveAcc("am_manajemen_user")) {
            myTree.addItem("a", "Manajemen Akses", true);
            var accessItems = [];
            var subUserItems = [];
            var emailItems = [];

            //User
            if(isHaveTrees("am_data_user")) {
                subUserItems.push({id: "am_data_user", text: "Data User", icons: {file: "menu_icon"}});
            }

            //Email
            if(isHaveTrees("am_email_send")) {
                emailItems.push({id: "am_email_send", text: "Pengiriman Email", icons: {file: "menu_icon"}});
            }

            //Trees
            if(isHaveTrees("am_user")) {
                accessItems.push({id: "am_user", text: "User", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: subUserItems})
            }

            if(isHaveTrees('am_email')) {
                accessItems.push({id: "am_email", text: "Email", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: emailItems})
            }

            if(userLogged.role === "admin") {
                var depts = await reqJsonResponse(Access("getDepartments"), "GET", null);
                if(depts.items.length > 0) {
                    accessItems.push({id: "access_control", text: "Akses Kontrol", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: depts.items})
                }
            }

            var accessTree = myTree.cells("a").attachTreeView({
                items: accessItems
            });

            accessTree.attachEvent("onClick", function(id) {
                if(id == "am_data_user") {
                    userTab();
                } else if(id == "am_email_send") {
                    sendEmailTab();
                }

                if(userLogged.role === 'admin' && depts.items.length > 0) {
                    depts.items.map(dept => {
                        if(dept.id == id) {
                            accControlDeptTab(dept);
                        }
                        dept.items.map(sub => {
                            if(sub.id == id) {
                                accControlTab(sub);
                            }
                        })
                    });
                }
            });
            
        }

        if(isHaveAcc('am_master_aplikasi')) {
            myTree.addItem("b", "Master Aplikasi");
            var masterItems = [];
            var masterSubItems = [];
            var masterBuildingSubItems = [];
            var masterFacilitySubItems = [];
            var masterPMachineSubItems = [];
            var masterOvertimeSubItems = [];
            var masterPICSubItems = [];

            //@KEPEGAWAIAN
            if(isHaveTrees("am_data_lokasi")) {
                masterSubItems.push({id: "am_data_lokasi", text: "Data Lokasi", icons: {file: "menu_icon"}});
            }
            if(isHaveTrees("am_data_departemen")) {
                masterSubItems.push({id: "am_data_departemen", text: "Data Sub Unit", icons: {file: "menu_icon"}});
            }
            if(isHaveTrees("am_data_sub_departemen")) {
                masterSubItems.push({id: "am_data_sub_departemen", text: "Data Bagian", icons: {file: "menu_icon"}});
            }
            if(isHaveTrees("am_data_divisi")) {
                masterSubItems.push({id: "am_data_divisi", text: "Data Sub Bagian", icons: {file: "menu_icon"}});
            }
            if(isHaveTrees("am_data_jabatan")) {
                masterSubItems.push({id: "am_data_jabatan", text: "Data Jabatan", icons: {file: "menu_icon"}});
            }
            if(isHaveTrees("am_data_training")) {
                masterSubItems.push({id: "am_data_training", text: "Data Training", icons: {file: "menu_icon"}});
            }
            
            //@SIPIL
            if(isHaveTrees("am_gedung")) {
                masterBuildingSubItems.push({id: "am_gedung", text: "Data Gedung", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("am_ruangan")) {
                masterBuildingSubItems.push({id: "am_ruangan", text: "Data Ruangan", icons: {file: "menu_icon"}});
            }

            //@FASILITAS
            if(isHaveTrees("am_ruang_meeting")) {
                masterFacilitySubItems.push({id: "am_ruang_meeting", text: "Ruang Meeting", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("am_kendaraan_inventaris")) {
                masterFacilitySubItems.push({id: "am_kendaraan_inventaris", text: "Kendaraan Dinas", icons: {file: "menu_icon"}});
            }

             //@MESIN PRODUKSI
            if(isHaveTrees("am_mesin_produksi")) {
                masterPMachineSubItems.push({id: "am_mesin_produksi", text: "Mesin Produksi", icons: {file: "menu_icon"}});
            }

            //@OVERTIME
            if(isHaveTrees("am_kebutuhan_lembur")) {
                masterOvertimeSubItems.push({id: "am_kebutuhan_lembur", text: "Kebutuhan Lembur", icons: {file: "menu_icon"}});
            }

            //@PIC
            if(isHaveTrees("am_data_pic")) {
                masterPICSubItems.push({id: "am_data_pic", text: "Data PIC", icons: {file: "menu_icon"}});
            }

            //@ROOT TREES
            if(isHaveTrees("am_kepegawaian")) {
                masterItems.push({id: "am_kepegawaian", text: "Kepegawaian", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: masterSubItems})
            }

            if(isHaveTrees("am_sipil")) {
                masterItems.push({id: "am_sipil", text: "Sipil", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: masterBuildingSubItems})
            }
            
            if(isHaveTrees("am_fasilitas")) {
                masterItems.push({id: "am_fasilitas", text: "Fasilitas", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: masterFacilitySubItems})
            }

            if(isHaveTrees("am_mesin")) {
                masterItems.push({id: "am_mesin", text: "Mesin", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: masterPMachineSubItems})
            }

            if(isHaveTrees("am_lembur")) {
                masterItems.push({id: "am_lembur", text: "Lembur", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: masterOvertimeSubItems})
            }

            if(isHaveTrees("am_pic")) {
                masterItems.push({id: "am_pic", text: "PIC", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: masterPICSubItems})
            }

            var appMasterTree = myTree.cells("b").attachTreeView({
                items: masterItems
            });

            appMasterTree.attachEvent("onClick", function(id) {
                if(id == "am_data_lokasi") {
                    masterLocTab();
                } else if(id == "am_data_departemen") {
                    masterDeptTab();
                } else if(id == "am_data_sub_departemen") {
                    masterSubDeptTab();
                } else if(id == "am_data_divisi") {
                    masterDivisionTab();
                } else if(id == "am_data_jabatan") {
                    masterRankTab();
                } else if(id == "am_data_training") {
                    masterTrainingTab();
                } else if(id == "am_gedung") {
                    masterBuildingTab();
                } else if(id == "am_ruangan") {
                    masterBuildingRoomTab();
                } else if(id === "am_ruang_meeting") {
                    masterMeetingRoomTab();
                } else if(id === "am_kendaraan_inventaris") {
                    masterVehicleTab();
                } else if(id === "am_mesin_produksi") {
                    masterPMachineTab();
                } else if(id === "am_kebutuhan_lembur") {
                    reqOvertimeTab();
                } else if(id === "am_data_pic") {
                    masterPICTab();
                } 
            });
        }
    }

JS;

echo $script;


