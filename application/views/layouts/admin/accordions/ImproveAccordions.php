<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

    function improveAccordion() {
        checkTrees();
        $("#title-menu").html("Continous Improvement");
        accordionItems.map(id => myTree.removeItem(id));
        accordionItems.push("a", "b");

        if(isHaveAcc("improvement_master")) {
            myTree.addItem("a", "Master Improvement", true);
            var masterImproveItems = [];
            var subMasterImproveItems = [];

            //@ITEMS
            if(isHaveTrees("master_improve_categories")) {
                subMasterImproveItems.push({id: "master_improve_categories", text: "Kategori Improvement", icons: {file: "menu_icon"}});
            }
            if(isHaveTrees("master_improve_levels")) {
                subMasterImproveItems.push({id: "master_improve_levels", text: "Tingkatan Improvement", icons: {file: "menu_icon"}});
            }

            //@MASTER TREE
            if(isHaveTrees('master_detective')) {
                masterImproveItems.push({id: "master_detective", text: "Mater Data", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: subMasterImproveItems})
            }

            var masterImproveTree = myTree.cells("a").attachTreeView({
                items: masterImproveItems
            });

            masterImproveTree.attachEvent("onClick", function(id) {
                if(id == "master_improve_categories") {
                    improveCatTab();
                } else if(id == "master_improve_levels") {
                    improveLevelTab();
                }
            });
        }

        if(isHaveAcc("improvement_process")) {
            myTree.addItem("b", "Pengajuan Improvement", true);
            var procImproveItems = [];
            var subProcImproveItems = [];

            //@ITEMS
            if(isHaveTrees("improve_form_detective")) {
                subProcImproveItems.push({id: "improve_form_detective", text: "Form Detektif", icons: {file: "menu_icon"}});
            }

            //@MASTER TREE
            if(isHaveTrees('improve_detective')) {
                procImproveItems.push({id: "improve_detective", text: "Detektif", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: subProcImproveItems})
            }

            var procImproveTree = myTree.cells("b").attachTreeView({
                items: procImproveItems
            });

            procImproveTree.attachEvent("onClick", function(id) {
                if(id == "improve_form_detective") {
                    improveFormDetTab();
                }
            });
        }
    }

JS;
echo $script;
