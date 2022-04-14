<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

    function detCatTab() {
        if (!mainTab.tabs("master_detective_categories")){
            mainTab.addTab("master_detective_categories", tabsStyle("detective.png", "Kategori Improvement"), null, null, true, true);
            showDetCat();
        } else {
            mainTab.tabs("master_detective_categories").setActive();
        }
    }
    
JS;
echo $script;