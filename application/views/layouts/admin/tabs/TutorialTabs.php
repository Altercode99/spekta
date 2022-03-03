<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}
$script = <<< "JS"

  function videoCategoryTabs() {
        if (!mainTab.tabs("tutorial_video_categories")){
            mainTab.addTab("tutorial_video_categories", tabsStyle("play.png", "Kategori Video", "background-size: 16px 16px"), null, null, true, true);
            showVideoCategory();
        } else {
            mainTab.tabs("tutorial_video_categories").setActive();
        }
    }

JS;

echo $script;
