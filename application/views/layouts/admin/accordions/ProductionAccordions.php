<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

    function productionAccordion() {
        checkTrees();
        $("#title-menu").html("Produksi");
        accordionItems.map(id => myTree.removeItem(id));
        accordionItems.push("a");

        if(isHaveAcc("prod_master")) {
            myTree.addItem("a", "Master", true);
            var mProductItems = [];
            var mProductItemsDetail = [];

            //@PRODUCTS
            if(isHaveTrees("prod_master_products_product")) {
                mProductItemsDetail.push({id: "prod_master_products_product", text: "Produk", icons: {file: "menu_icon"}});
            }

            //@TREE
            if(isHaveTrees('prod_master_products')) {
                mProductItems.push({id: "prod_master_products", text: "Master Produk", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: mProductItemsDetail})
            }

            var mProductTree = myTree.cells("a").attachTreeView({
                items: mProductItems
            });

            mProductTree.attachEvent("onClick", function(id) {
                if(id == "prod_master_products_product") {
                    masterProductTab();
                }
            });
        }

    }
JS;

echo $script;


