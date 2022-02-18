<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	function showRequestOvertime() {	
        var reqOvtLayout = mainTab.cells("tnp_overtime_request").attachLayout({
            pattern: "1C",
            cells: [
                {id: "a", text: "Daftar Request Lembur Dari Produksi"}
            ]
        });

        var reqMenu = reqOvtLayout.cells("a").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "process", text: "Process", img: "undo.gif"}
            ]
        });

        reqMenu.attachEvent("onClick", function(id) {
            switch (id) {
                case "process":
                    inputOvertimeTNPTab(process = true);
                    break;
            }
        })

        let reqStatusBar = mainTab.cells("tnp_overtime_request").attachStatusBar();
        function reqGridCount() {
            var reqGridRows = reqOvtGrid.getRowsNum();
            reqStatusBar.setText("Total baris: " + reqGridRows);
        }

        reqOvtLayout.cells("a").progressOn();
        var reqOvtGrid = reqOvtLayout.cells("a").attachGrid();
        reqOvtGrid.setImagePath("./public/codebase/imgs/");
        reqOvtGrid.setHeader("No,Task ID Teknik,Ref Lembur Produksi,Sub Unit,Bagian,Disivi,Kebutuhan Orang,Status Hari,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Catatan,Makan,Steam,AHU,Compressor,PW,Jemputan,Dust Collector,Mekanik,Listrik,H&N,Created By,Updated By,Created At");
        reqOvtGrid.attachHeader("#rspan,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter")
        reqOvtGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        reqOvtGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        reqOvtGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        reqOvtGrid.setInitWidthsP("5,20,20,20,20,20,15,15,15,20,20,20,7,7,7,7,7,7,7,7,7,7,15,15,25");
        reqOvtGrid.enableSmartRendering(true);
        reqOvtGrid.attachEvent("onXLE", function() {
            reqOvtLayout.cells("a").progressOff();
        });
        reqOvtGrid.init();
        
        function rReqOvtGrid() {
            reqOvtLayout.cells("a").progressOn();
            let params = {in_status: "CREATED", notequal_ref: ""};
            reqOvtGrid.clearAndLoad(Overtime("getRequestOvertimeGrid", params), reqGridCount);
        }

        rReqOvtGrid();
    }
JS;

header('Content-Type: application/javascript');
echo $script;