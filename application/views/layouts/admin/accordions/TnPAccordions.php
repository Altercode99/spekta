<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

    function tnpAccordion() {
        checkTrees();
        $("#title-menu").html("Teknik & Pemeliharaan");
        accordionItems.map(id => myTree.removeItem(id));
        accordionItems.push("a");

        if(isHaveAcc("tnp_overtime_acc")) {
            myTree.addItem("a", "Lembur", true);
            var overtimeItems = [];
            var overtimeSubItems = [];

            if(isHaveTrees("tnp_input_lembur")) {
                overtimeSubItems.push({id: "tnp_input_lembur", text: "Input Lembur (B)", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("tnp_report_form_lembur")) {
                overtimeSubItems.push({id: "tnp_report_form_lembur", text: "Report Form Lembur", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("tnp_report_req_lembur")) {
                overtimeSubItems.push({id: "tnp_report_req_lembur", text: "Report Request Lembur", icons: {file: "menu_icon"}});
            }

            //TREES
            if(isHaveTrees("tnp_overtime")) {
                overtimeItems.push({id: "tnp_overtime", text: "Lembur", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: overtimeSubItems});
            }

            var overtimeTree = myTree.cells("a").attachTreeView({
                items: overtimeItems
            });

            overtimeTree.attachEvent("onClick", function(id) {
                if(id == "tnp_input_lembur") {
                    inputOvertimeTNPTab();
                } else if(id == "tnp_report_req_lembur") {
                    reportReqOvtTNPTab();
                } else if(id == "tnp_report_form_lembur") {
                    reportFormOvertimeTab();
                }
            });

        }
    }
JS;

echo $script;


