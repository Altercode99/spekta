<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

    function improveCatTab() {
        if (!mainTab.tabs("master_improve_categories")){
            mainTab.addTab("master_improve_categories", tabsStyle("detective.png", "Kategori Improvement"), null, null, true, true);
            showImproveCat();
        } else {
            mainTab.tabs("master_improve_categories").setActive();
        }
    }

    function improveLevelTab() {
        if (!mainTab.tabs("master_improve_levels")){
            mainTab.addTab("master_improve_levels", tabsStyle("detective.png", "Tingkatan Improvement"), null, null, true, true);
            showImproveLevel();
        } else {
            mainTab.tabs("master_improve_levels").setActive();
        }
    }

    function improveFormDetTab() {
        if (!mainTab.tabs("improve_form_detective")){
            mainTab.addTab("improve_form_detective", tabsStyle("detective.png", "Form Detektif"), null, null, true, true);
            showFormDet();
        } else {
            mainTab.tabs("improve_form_detective").setActive();
        }
    }
    
JS;
echo $script;