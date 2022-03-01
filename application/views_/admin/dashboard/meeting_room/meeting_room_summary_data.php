<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
    
    function showDashMRoomSumData(t,e,a){var r="ga_rm_report_total_hour"+e+a,o="ga_rm_report_total_person"+e+a,l="ga_rm_report_total_snack"+e+a,s="ga_rm_report_total_person_grand"+e+a,n="ga_rm_report_total_hour_grand"+e+a,i="ga_rm_report_total_snack_grand"+e+a,_=mainTab.cells(t).attachTabbar({tabs:[{id:"a",text:"Report Global",active:!0},{id:"b",text:"Report Ruangan"}]});_.cells("a").attachToolbar({icon_path:"./public/codebase/icons/",items:[{id:"export",text:"Export To Excel",type:"button",img:"excel.png"}]}).attachEvent("onClick",(function(t){switch(t){case"export":f.toExcel("./public/codebase/grid-to-excel-php/generate.php"),sAlert("Export Data Dimulai")}}));var c=_.cells("a").attachStatusBar();function p(){let t=f.getRowsNum();c.setText("Total baris: "+t),sumGridToElement(f,16,l,i,"money"),sumGridToElement(f,8,r,n,"float"),sumGridToElement(f,11,o,s,"int")}var f=_.cells("a").attachGrid();f.setHeader("No,No. Tiket,No. Ref,Topik Meeting,Jenis Meeting,Ruang Meeting,Waktu Mulai,Waktu Selesai,Druasi,Snack,Total Peserta,Konfirmasi Hadir,Konfirmasi Tidak Hadir,Belum Konfirmasi,Snack,Harga Snack,Total,Status,Alasan Penolakan,Created By,Updated By,DiBuat"),f.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter"),f.setColSorting("str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str"),f.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,edtxt,rotxt,rotxt,rotxt,ron,ron,rotxt,rotxt,rotxt,rotxt,rotxt"),f.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left"),f.setInitWidthsP("5,15,15,25,15,15,20,20,10,10,10,10,10,10,15,15,15,15,30,15,15,25"),f.attachFooter(",Total,,,,,,<span id='"+r+"'>0</span> Jam,,,<span id='"+o+"'>0</span> Orang,,,,,<div id='"+l+"'>0</div>,,,,,,"),f.attachFooter(",Total Peserta,<span id='"+s+"'>0</span> Orang"),f.attachFooter(",Total Jam Reservasi,<span id='"+n+"'>0</span> Jam"),f.attachFooter(",Total Biaya Snack,<div id='"+i+"'>0</div>"),f.enableSmartRendering(!0),f.attachEvent("onXLE",(function(){_.cells("a").progressOff()})),f.setNumberFormat("0,000",15,".",","),f.setNumberFormat("0,000",16,".",","),f.init(),function(){_.cells("a").progressOn();let t={month_start_date:a,year_start_date:e,equal_status:"CLOSED",report:!0};f.clearAndLoad(GAOther("getMeetingRevGrid",t),p)}(),_.cells("b").attachToolbar({icon_path:"./public/codebase/icons/",items:[{id:"export",text:"Export To Excel",type:"button",img:"excel.png"}]}).attachEvent("onClick",(function(t){switch(t){case"export":k.toExcel("./public/codebase/grid-to-excel-php/generate.php"),sAlert("Export Data Dimulai")}}));var x="ga_rmr_total_rev_"+e+a,d="ga_rmr_total_rev_grand_"+e+a,m="ga_rmr_total_person_"+e+a,g="ga_rmr_total_person_grand_"+e+a,u="ga_rmr_total_hour_"+e+a,h="ga_rmr_total_hour_grand_"+e+a,T="ga_rmr_total_snack_"+e+a,b="ga_rmr_total_snack_grand_"+e+a,E=_.cells("b").attachStatusBar();function v(){let t=k.getRowsNum();E.setText("Total baris: "+t),sumGridToElement(k,2,x,d,"int"),sumGridToElement(k,3,m,g,"int"),sumGridToElement(k,4,u,h,"float"),sumGridToElement(k,5,T,b,"money")}var k=_.cells("b").attachGrid();k.setHeader("No,Nama Ruang Meeting,Total Reservasi,Total Peserta,Total Jam,Total Biaya Snack"),k.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter"),k.setColSorting("str,str,str,str,str,str"),k.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt"),k.setColAlign("center,left,left,left,left,left"),k.setInitWidthsP("5,30,15,15,15,20"),k.attachFooter(",Total,<span id='"+x+"'>0</span>,<span id='"+m+"'>0</span> Orang,<span id='"+u+"'>0</span> Jam,Rp. <span id='"+T+"'>0</span>"),k.attachFooter(",Total Reservasi (Meeting),<span id='"+d+"'>0</span>"),k.attachFooter(",Total Peserta,<span id='"+g+"'>0</span> Orang"),k.attachFooter(",Total Jam,<span id='"+h+"'>0</span> Jam"),k.attachFooter(",Total Biaya Snack,<span id='"+b+"'>0</span>"),k.enableSmartRendering(!0),k.attachEvent("onXLE",(function(){_.cells("b").progressOff()})),k.init(),function(){_.cells("b").progressOn();let t={month_start_date:a,year_start_date:e,equal_status:"CLOSED"};k.clearAndLoad(GAOther("getMeetingRevGroupGrid",t),v)}()}

JS;

header('Content-Type: application/javascript');
echo $script;