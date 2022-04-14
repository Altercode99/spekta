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
        accordionItems.push("a");

        if(isHaveAcc("improvement_detective")) {
            myTree.addItem("a", "Detective", true);
            var masterDetItems = [];
            var subMasterDetItems = [];

            //@ITEMS
            if(isHaveTrees("master_detective_categories")) {
                subMasterDetItems.push({id: "master_detective_categories", text: "Kategori Improvement", icons: {file: "menu_icon"}});
            }

            //@MASTER TREE
            if(isHaveTrees('master_detective')) {
                masterDetItems.push({id: "master_detective", text: "Mater Data", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: subMasterDetItems})
            }

            var masterDetTree = myTree.cells("a").attachTreeView({
                items: masterDetItems
            });

            masterDetTree.attachEvent("onClick", function(id) {
                if(id == "master_detective_categories") {
                    detCatTab();
                }
            });
        }
    }

JS;
echo $script;
