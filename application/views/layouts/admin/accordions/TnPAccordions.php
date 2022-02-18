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

            //OVERTIME
            if(isHaveTrees("tnp_request_lembur")) {
                overtimeSubItems.push({id: "tnp_request_lembur", text: "Request Lembur Produksi", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("tnp_input_lembur")) {
                overtimeSubItems.push({id: "tnp_input_lembur", text: "Input Lembur", icons: {file: "menu_icon"}});
            }

            //TREES
            if(isHaveTrees("tnp_overtime")) {
                overtimeItems.push({id: "tnp_overtime", text: "Lembur", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: overtimeSubItems});
            }

            var overtimeTree = myTree.cells("a").attachTreeView({
                items: overtimeItems
            });

            overtimeTree.attachEvent("onClick", function(id) {
                if(id == "tnp_request_lembur") {
                    requestOvertimeTab();
                } else if(id == "tnp_input_lembur") {
                    inputOvertimeTNPTab();
                }
            });

        }
    }
JS;

echo $script;


