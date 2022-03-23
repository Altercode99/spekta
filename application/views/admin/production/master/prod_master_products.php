<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
    		
    function showMasterProduct() {

        var mProductLayout = mainTab.cells("prod_master_products_product").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Form Master Produk",
                    header: true,
                    collapse: true
                }
            ]
        });

        var maProductToolbar = mainTab.cells("prod_master_products_product").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "add", text: "Tambah", type: "button", img: "add.png"},
                {id: "delete", text: "Hapus", type: "button", img: "delete.png"},
                {id: "edit", text: "Ubah", type: "button", img: "edit.png", img_disabled: "edit_disabled.png"},
                {id: "export", text: "Export To Excel", type: "button", img: "excel.png"},
                {id: "searchtext", text: "Cari : ", type: "text"},
                {id: "search", text: "", type: "buttonInput", width: 150}
            ]
        });

        if(userLogged.role !== "admin") {
            maProductToolbar.disableItem("delete");
        }

        var mProductSB = machineLayout.cells("a").attachStatusBar();
        function mProductGridCount() {
            mProductSB.setText("Total baris: " +  machineGrid.getRowsNum());
        }

        var mProductGrid = mProductLayout.cells("a").attachGrid();
        mProductGrid.setHeader("No,Nama Produk,Created By,Updated By,DiBuat");
        mProductGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#text_filter")
        mProductGrid.setColSorting("int,str,str,str,str");
        mProductGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt");
        mProductGrid.setColAlign("center,left,left,left,left");
        mProductGrid.setInitWidthsP("5,25,20,20,15,20,20,15,15,15,15,25");
        mProductGrid.enableSmartRendering(true);
        mProductGrid.enableMultiselect(true);
        mProductGrid.attachEvent("onXLE", function() {
            mProductLayout.cells("a").progressOff();
        });
        mProductGrid.init();
    }

JS;

header('Content-Type: application/javascript');
echo $script;