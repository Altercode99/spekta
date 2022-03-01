<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	
function showHrVerifiedOvertime(){var t=legendGrid(),e=mainTab.cells("hr_verified_overtime").attachTabbar({tabs:[{id:"a",text:"Report Lembur",active:!0},{id:"b",text:"Report Lembur Bagian"},{id:"c",text:"Report Lembur Sub Bagian"},{id:"d",text:"Report Lembur Karyawan"}]}),a=e.cells("a").attachToolbar({icon_path:"./public/codebase/icons/",items:[{id:"refresh",text:"Refresh",type:"button",img:"refresh.png"},{id:"export",text:"Export To Excel",type:"button",img:"excel.png"}]});let r=filterForMonth(new Date);e.cells("a").attachMenu({icon_path:"./public/codebase/icons/",items:[{id:"search",text:"<div style='width:100%'>Search: <input type='text' id='hr_start_ovt_verified' readonly value='"+r.start+"' /> - <input type='text' id='hr_end_ovt_verified' readonly value='"+r.end+"' /> <button id='hr_btn_ftr_ovt_vfd'>Proses</button>"}]}),new dhtmlXCalendarObject(["hr_start_ovt_verified","hr_end_ovt_verified"]);$("#hr_btn_ftr_ovt_vfd").on("click",(function(){checkFilterDate($("#hr_start_ovt_verified").val(),$("#hr_end_ovt_verified").val())&&(_(),v(),h(),g())})),a.attachEvent("onClick",(function(t){switch(t){case"refresh":_(),v(),h(),g();break;case"export":i.toExcel("./public/codebase/grid-to-excel-php/generate.php"),sAlert("Export Data Dimulai")}})),e.cells("b").attachMenu({icon_path:"./public/codebase/icons/",items:[{id:"export",text:"Export To Excel",img:"excel.png"}]}).attachEvent("onClick",(function(t){switch(t){case"export":n.toExcel("./public/codebase/grid-to-excel-php/generate.php"),sAlert("Export Data Dimulai")}})),e.cells("c").attachMenu({icon_path:"./public/codebase/icons/",items:[{id:"export",text:"Export To Excel",img:"excel.png"}]}).attachEvent("onClick",(function(t){switch(t){case"export":m.toExcel("./public/codebase/grid-to-excel-php/generate.php"),sAlert("Export Data Dimulai")}})),e.cells("d").attachMenu({icon_path:"./public/codebase/icons/",items:[{id:"export",text:"Export To Excel",img:"excel.png"}]}).attachEvent("onClick",(function(t){switch(t){case"export":u.toExcel("./public/codebase/grid-to-excel-php/generate.php"),sAlert("Export Data Dimulai")}}));let l=e.cells("a").attachStatusBar();function o(){var e=i.getRowsNum();l.setText("Total baris: "+e+" ("+t.hr_verified_overtime+")");let a=sumGridToElement(i,21,"hr_total_ovt_vfd","hr_grand_total_ovt_vfd"),r=sumGridToElement(i,23,"hr_total_meal_ovt_vfd","hr_grand_total_meal_ovt_vfd");$("#hr_grand_total_all_ovt_vfd").html("Rp. "+numberFormat(a+r))}var i=e.cells("a").attachGrid();function _(){e.cells("a").progressOn();let t={equal_status:"CLOSED",equal_payment_status:"VERIFIED",betweendate_overtime_date:$("#hr_start_ovt_verified").val()+","+$("#hr_end_ovt_verified").val(),check:!0};i.clearAndLoad(Overtime("getReportOvertimeGrid",t),o)}i.setImagePath("./public/codebase/imgs/"),i.setHeader("No,Check,Task ID,No. Memo Lembur,Nama Karyawan,Bagian Personil,Sub Bagian Personil,Bagian Penyelenggara,Sub Bagian Penyelenggara,Nama Mesin #1,Nama Mesin #2,Pelayanan,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Status Hari,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Premi,Nominal Overtime,Makan,Biaya Makan,Status Overtime,Ulasan Pencapaian Lembur,Created At"),i.attachHeader("#rspan,#master_checkbox,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter"),i.setColSorting("int,na,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str"),i.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left"),i.setColTypes("rotxt,ch,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt"),i.setInitWidthsP("5,0,20,20,20,20,20,20,20,15,15,15,15,15,15,10,10,10,10,10,10,15,5,15,10,30,25"),i.attachFooter(",,Total Summary,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,,<div id='hr_total_ovt_vfd'>0</div>,,<div id='hr_total_meal_ovt_vfd'>0</div>,,,"),i.attachFooter(",,Total Biaya Lembur,<div id='hr_grand_total_ovt_vfd'>0</div>"),i.attachFooter(",,Total Biaya Makan,<div id='hr_grand_total_meal_ovt_vfd'>0</div>"),i.attachFooter(",,Grand Total,<div id='hr_grand_total_all_ovt_vfd'>0</div>"),i.enableSmartRendering(!0),i.attachEvent("onXLE",(function(){e.cells("a").progressOff()})),i.init(),_();let s=e.cells("b").attachStatusBar();function d(){var t=n.getRowsNum();s.setText("Total baris: "+t);let e=sumGridToElement(n,7,"hr_total_ovt_vfd_sub","hr_grand_total_ovt_vfd_sub"),a=sumGridToElement(n,8,"hr_total_meal_ovt_vfd_sub","hr_grand_total_meal_ovt_vfd_sub");$("#hr_grand_total_all_ovt_vfd_sub").html("Rp. "+numberFormat(e+a))}var n=e.cells("b").attachGrid();function v(){e.cells("b").progressOn();let t={equal_status:"CLOSED",equal_payment_status:"VERIFIED",betweendate_overtime_date:$("#hr_start_ovt_verified").val()+","+$("#hr_end_ovt_verified").val(),groupby_sub_department_id:!0};n.clearAndLoad(Overtime("getReportOvertimeSubGrid",t),d)}n.setImagePath("./public/codebase/imgs/"),n.setHeader("No,Bagian,Sub Unit,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Nominal Overtime,Biaya Makan"),n.attachHeader("#rspan,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter"),n.setColSorting("int,str,str,str,str,str,str,str,str"),n.setColAlign("center,left,left,left,left,left,left,left,left"),n.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt"),n.setInitWidthsP("5,20,20,10,10,10,10,13,13"),n.attachFooter(",Total Summary,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,<div id='hr_total_ovt_vfd_sub'>0</div>,<div id='hr_total_meal_ovt_vfd_sub'>0</div>"),n.attachFooter(",Total Biaya Lembur,<div id='hr_grand_total_ovt_vfd_sub'>0</div>"),n.attachFooter(",Total Biaya Makan,<div id='hr_grand_total_meal_ovt_vfd_sub'>0</div>"),n.attachFooter(",Grand Total,<div id='hr_grand_total_all_ovt_vfd_sub'>0</div>"),n.enableSmartRendering(!0),n.attachEvent("onXLE",(function(){e.cells("b").progressOff()})),n.init(),v();let c=e.cells("c").attachStatusBar();function f(){var t=m.getRowsNum();c.setText("Total baris: "+t);let e=sumGridToElement(m,7,"hr_total_ovt_vfd_div","hr_grand_total_ovt_vfd_div"),a=sumGridToElement(m,8,"hr_total_meal_ovt_vfd_div","hr_grand_total_meal_ovt_vfd_div");$("#hr_grand_total_all_ovt_vfd_div").html("Rp. "+numberFormat(e+a))}var m=e.cells("c").attachGrid();function h(){e.cells("c").progressOn();let t={equal_status:"CLOSED",equal_payment_status:"VERIFIED",betweendate_overtime_date:$("#hr_start_ovt_verified").val()+","+$("#hr_end_ovt_verified").val(),groupby_division_id:!0};m.clearAndLoad(Overtime("getReportOvertimeDivGrid",t),f)}m.setImagePath("./public/codebase/imgs/"),m.setHeader("No,Sub Bagian,Bagian,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Nominal Overtime,Biaya Makan"),m.attachHeader("#rspan,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter"),m.setColSorting("int,str,str,str,str,str,str,str,str"),m.setColAlign("center,left,left,left,left,left,left,left,left"),m.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt"),m.setInitWidthsP("5,20,20,10,10,10,10,15,15"),m.attachFooter(",Total Summary,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,<div id='hr_total_ovt_vfd_div'>0</div>,<div id='hr_total_meal_ovt_vfd_div'>0</div>"),m.attachFooter(",Total Biaya Lembur,<div id='hr_grand_total_ovt_vfd_div'>0</div>"),m.attachFooter(",Total Biaya Makan,<div id='hr_grand_total_meal_ovt_vfd_div'>0</div>"),m.attachFooter(",Grand Total,<div id='hr_grand_total_all_ovt_vfd_div'>0</div>"),m.enableSmartRendering(!0),m.attachEvent("onXLE",(function(){e.cells("c").progressOff()})),m.init(),h();let x=e.cells("d").attachStatusBar();function p(){var t=u.getRowsNum();x.setText("Total baris: "+t);let e=sumGridToElement(u,9,"hr_total_ovt_vfd_emp","hr_grand_total_ovt_vfd_emp"),a=sumGridToElement(u,10,"hr_total_meal_ovt_vfd_emp","hr_grand_total_meal_ovt_vfd_emp");$("#hr_grand_total_all_ovt_vfd_emp").html("Rp. "+numberFormat(e+a))}var u=e.cells("d").attachGrid();function g(){e.cells("d").progressOn();let t={equal_status:"CLOSED",equal_payment_status:"VERIFIED",betweendate_overtime_date:$("#hr_start_ovt_verified").val()+","+$("#hr_end_ovt_verified").val(),groupby_emp_id:!0};u.clearAndLoad(Overtime("getReportOvertimeEmpGrid",t),p)}u.setImagePath("./public/codebase/imgs/"),u.setHeader("No,Nama Karyawan,Sub Bagian,Bagian,Sub Unit,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Nominal Overtime,Biaya Makan"),u.attachHeader("#rspan,#text_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter"),u.setColSorting("int,str,str,str,str,str,str,str,str,str,str"),u.setColAlign("center,left,left,left,left,left,left,left,left,left,left"),u.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt"),u.setInitWidthsP("5,20,20,20,20,10,10,10,10,15,15"),u.attachFooter(",Total Summary,#cspan,#cspan,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,<div id='hr_total_ovt_vfd_emp'>0</div>,<div id='hr_total_meal_ovt_vfd_emp'>0</div>"),u.attachFooter(",Total Biaya Lembur,<div id='hr_grand_total_ovt_vfd_emp'>0</div>"),u.attachFooter(",Total Biaya Makan,<div id='hr_grand_total_meal_ovt_vfd_emp'>0</div>"),u.attachFooter(",Grand Total,<div id='hr_grand_total_all_ovt_vfd_emp'>0</div>"),u.enableSmartRendering(!0),u.attachEvent("onXLE",(function(){e.cells("d").progressOff()})),u.init(),g()}

JS;

header('Content-Type: application/javascript');
echo $script;