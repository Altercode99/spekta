<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}
$script = <<< "JS"

    function masterProductTab() {
        if (!mainTab.tabs("prod_master_products_product")){
            mainTab.addTab("prod_master_products_product", tabsStyle("clock.png", "Master Produk"), null, null, true, true);
            showMasterProduct();
        } else {
            mainTab.tabs("prod_master_products_product").setActive();
        }
    }

    function spackLocationTab() {
        if (!mainTab.tabs("prod_spack_location")){
            mainTab.addTab("prod_spack_location", tabsStyle("clock.png", "Lokasi"), null, null, true, true);
            showSpackLocation();
        } else {
            mainTab.tabs("prod_spack_location").setActive();
        }
    }

JS;

echo $script;