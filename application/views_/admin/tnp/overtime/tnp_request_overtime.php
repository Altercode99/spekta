<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

	function showRequestOvertime(){var t=mainTab.cells("tnp_overtime_request").attachLayout({pattern:"1C",cells:[{id:"a",text:"Daftar Request Lembur Dari Produksi"}]});t.cells("a").attachMenu({icon_path:"./public/codebase/icons/",items:[{id:"process",text:"Process",img:"undo.gif"}]}).attachEvent("onClick",(function(t){switch(t){case"process":inputOvertimeTNPTab(process=!0)}}));let e=mainTab.cells("tnp_overtime_request").attachStatusBar();function r(){var t=s.getRowsNum();e.setText("Total baris: "+t)}t.cells("a").progressOn();var s=t.cells("a").attachGrid();s.setImagePath("./public/codebase/imgs/"),s.setHeader("No,Task ID Teknik,Ref Lembur Produksi,Sub Unit,Bagian,Disivi,Kebutuhan Orang,Status Hari,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Catatan,Makan,Steam,AHU,Compressor,PW,Jemputan,Dust Collector,Mekanik,Listrik,H&N,Created By,Updated By,Created At"),s.attachHeader("#rspan,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter"),s.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str"),s.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left"),s.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt"),s.setInitWidthsP("5,20,20,20,20,20,15,15,15,20,20,20,7,7,7,7,7,7,7,7,7,7,15,15,25"),s.enableSmartRendering(!0),s.attachEvent("onXLE",(function(){t.cells("a").progressOff()})),s.init(),t.cells("a").progressOn(),s.clearAndLoad(Overtime("getRequestOvertimeGrid",{in_status:"CREATED",notequal_ref:""}),r)}

JS;

header('Content-Type: application/javascript');
echo $script;