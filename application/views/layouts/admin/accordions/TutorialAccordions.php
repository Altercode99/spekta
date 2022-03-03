<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

    function tutorialAccordion() {
        checkTrees();
        $("#title-menu").html("Panduan Penggunaan");
        accordionItems.map(id => myTree.removeItem(id));
        accordionItems.push("a");

        if(isHaveAcc("tutorial")) {
            myTree.addItem("a", "Tutorial", true);
            var tutorials = [];
            var videosItems = [];

            if(isHaveTrees("tutorial_video_categories")) {
                videosItems.push({id: "tutorial_video_categories", text: "Kategori Video", icons: {file: "menu_icon"}});
            }

            if(isHaveTrees("tutorial_video")) {
                tutorials.push({id: "tutorial_video", text: "Video Tutorial", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: videosItems});
            }

            var videoTree = myTree.cells("a").attachTreeView({
                items: tutorials
            });

            videoTree.attachEvent("onClick", function(id) {
                if(id == "tutorial_video_categories") {
                   videoCategoryTabs();
                }
            });
        }
    }

JS;

echo $script;