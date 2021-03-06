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
        accordionItems.push("a", "b");

        if(isHaveAcc("prod_master")) {
            myTree.addItem("a", "Master");
            var mProductItems = [];
            var mProductItemsDetail = [];

            //@PRODUCTS
            if(isHaveTrees("prod_master_spack_product")) {
                mProductItemsDetail.push({id: "prod_master_spack_product", text: "Produk", icons: {file: "menu_icon"}});
            }
            if(isHaveTrees("prod_master_spack_product_type")) {
                mProductItemsDetail.push({id: "prod_master_spack_product_type", text: "Golongan Produk", icons: {file: "menu_icon"}});
            }
            if(isHaveTrees("prod_master_spack_makloon")) {
                mProductItemsDetail.push({id: "prod_master_spack_makloon", text: "Daftar Makloon", icons: {file: "menu_icon"}});
            }
            if(isHaveTrees("prod_spack_location")) {
                mProductItemsDetail.push({id: "prod_spack_location", text: "Lokasi", icons: {file: "menu_icon"}});
            }

            //@TREE
            if(isHaveTrees('prod_master_spack')) {
                mProductItems.push({id: "prod_master_spack", text: "Master Surat Pack", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: mProductItemsDetail})
            }

            var mProductTree = myTree.cells("a").attachTreeView({
                items: mProductItems
            });

            mProductTree.attachEvent("onClick", function(id) {
                if(id == "prod_master_spack_product") {
                    masterProductTab();
                } else if(id == "prod_master_spack_product_type") {
                    masterProductTypeTab();
                } else if(id == "prod_master_spack_makloon") {
                    masterMakloonTab();
                } else if(id == "prod_spack_location") {
                    spackLocationTab();
                }
            });
        }

        if(isHaveAcc("prod_process")) {
            myTree.addItem("b", "Proses Produksi", true);
            var spackItems = [];
            var spackItemDetail = [];

            //@SPACK
            if(isHaveTrees("prod_spack_entry")) {
                spackItemDetail.push({id: "prod_spack_entry", text: "Entry Surat Pack", icons: {file: "menu_icon"}});
            }

            //@TREE
            if(isHaveTrees('prod_spack')) {
                spackItems.push({id: "prod_spack", text: "Surat Pack", open: 1, icons: {folder_opened: "arrow_down", folder_closed: "arrow_right"}, items: spackItemDetail})
            }

            var spackTree = myTree.cells("b").attachTreeView({
                items: spackItems
            });

            spackTree.attachEvent("onClick", function(id) {
                if(id == "prod_spack_entry") {
                    spackEntryTab();
                }
            });
        }

    }
JS;

echo $script;


