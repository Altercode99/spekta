<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

    function productionAccordion() {
        checkTrees();
        $("#title-menu").html("Production");
        accordionItems.map(id => myTree.removeItem(id));
        accordionItems.push("a");

    }
JS;

echo $script;


