<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

	function showVehicleReport(){var t=mainTab.cells("ga_vehicles_report").attachTabbar({tabs:[{id:"a",text:"Report Global",active:!0},{id:"b",text:"Report Ruangan"}]}),e=t.cells("a").attachToolbar({icon_path:"./public/codebase/icons/",items:[{id:"refresh",text:"Refresh",type:"button",img:"refresh.png"},{id:"export",text:"Export To Excel",type:"button",img:"excel.png"}]});let a=filterForMonth(new Date);t.cells("a").attachMenu({icon_path:"./public/codebase/icons/",items:[{id:"search",text:"<div style='width:100%'>Search: <input type='text' id='ga_start_vehicle_report' readonly value='"+a.start+"' /> - <input type='text' id='ga_end_vehicle_report' readonly value='"+a.end+"' /> <button id='ga_btn_ftr_vehicle_report'>Proses</button>"}]}),new dhtmlXCalendarObject(["ga_start_vehicle_report","ga_end_vehicle_report"]);$("#ga_btn_ftr_vehicle_report").on("click",(function(){checkFilterDate($("#ga_start_vehicle_report").val(),$("#ga_end_vehicle_report").val())&&(i(),c())})),e.attachEvent("onClick",(function(t){switch(t){case"refresh":i();break;case"export":o.toExcel("./public/codebase/grid-to-excel-php/generate.php"),sAlert("Export Data Dimulai")}}));var r=t.cells("a").attachStatusBar();function l(){let t=o.getRowsNum();r.setText("Total baris: "+t),sumGridToElement(o,12,"ga_vehice_total_hour","ga_vehice_total_hour_grand","float"),sumGridToElement(o,9,"ga_vehice_total_km","ga_vehice_total_km_grand","float")}var o=t.cells("a").attachGrid();function i(){t.cells("a").progressOn();let e={betweendate_start_date:$("#ga_start_vehicle_report").val()+","+$("#ga_end_vehicle_report").val(),equal_status:"CLOSED",report:!0};o.clearAndLoad(GAOther("getVehicleRevGrid",e),l)}o.setHeader("No,No. Tiket,Tujuan,Jenis Perjalanan,Kendaraan,Driver,Konfirmasi Driver,Kilometer Awal,Kilometer Akhir,Jarak Tempuh,Waktu Mulai,Waktu Selesai,Druasi,Jumlah Penumpang,Status,Alasan Penolakan,Created By,Updated By,DiBuat"),o.attachHeader("#rspan,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter"),o.setColSorting("str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str"),o.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,ron,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt"),o.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left"),o.setInitWidthsP("5,15,25,20,15,15,15,15,15,15,20,20,10,10,15,30,15,15,25"),o.attachFooter(",Total,,,,,,,,<span id='ga_vehice_total_km'>0</span> KM,,,<span id='ga_vehice_total_hour'>0</span> Jam,,,,,,"),o.attachFooter(",Total Jam Reservasi,<span id='ga_vehice_total_hour_grand'>0</span> Jam,"),o.attachFooter(",Total Jarak Tempuh,<span id='ga_vehice_total_km_grand'>0</span> KM,"),o.enableSmartRendering(!0),o.attachEvent("onXLE",(function(){t.cells("a").progressOff()})),o.init(),i(),t.cells("b").attachToolbar({icon_path:"./public/codebase/icons/",items:[{id:"export",text:"Export To Excel",type:"button",img:"excel.png"}]}).attachEvent("onClick",(function(t){switch(t){case"export":n.toExcel("./public/codebase/grid-to-excel-php/generate.php"),sAlert("Export Data Dimulai")}}));var s=t.cells("b").attachStatusBar();function _(){let t=n.getRowsNum();s.setText("Total baris: "+t),sumGridToElement(n,3,"ga_vehice_group_total_hour","ga_vehice_group_total_hour_grand","float"),sumGridToElement(n,4,"ga_vehice_group_total_km","ga_vehice_group_total_km_grand","float")}var n=t.cells("b").attachGrid();function c(){t.cells("b").progressOn();let e={betweendate_start_date:$("#ga_start_vehicle_report").val()+","+$("#ga_end_vehicle_report").val(),equal_status:"CLOSED"};n.clearAndLoad(GAOther("getVehicleRevGroupGrid",e),_)}n.setHeader("No,Nama Kendaraan Dinas,Total Reservasi,Total Jam,Total Jarak Tempuh"),n.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#text_filter"),n.setColSorting("str,str,str,str,str"),n.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt"),n.setColAlign("center,left,left,left,left"),n.setInitWidthsP("5,35,20,20,20"),n.attachFooter(",Total,,<span id='ga_vehice_group_total_hour'>0</span> Jam,<span id='ga_vehice_group_total_km'>0</span> KM"),n.attachFooter(",Total Jam Reservasi,<span id='ga_vehice_group_total_hour_grand'>0</span> Jam,"),n.attachFooter(",Total Jarak Tempuh,<span id='ga_vehice_group_total_km_grand'>0</span> Jam,"),n.enableSmartRendering(!0),n.attachEvent("onXLE",(function(){t.cells("b").progressOff()})),n.init(),c()}

JS;

header('Content-Type: application/javascript');
echo $script;