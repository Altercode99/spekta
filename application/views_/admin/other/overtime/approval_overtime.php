<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

	function showAppvOvertime(){var e=legendGrid(),t=createTime(),a=mainTab.cells("other_approval_overtime").attachLayout({pattern:"2E",cells:[{id:"a",text:"Daftar Lembur",active:!0},{id:"b",text:"Detail Lembur",collapse:!0}]}),l=a.cells("a").attachToolbar({icon_path:"./public/codebase/icons/",items:[{id:"refresh",text:"Refresh",type:"button",img:"refresh.png"},{id:"approve",text:"Approve Lembur",type:"button",img:"ok.png"},{id:"reject",text:"Reject Lembur",type:"button",img:"messagebox_critical.png"},{id:"revision",text:"Revisi Lembur (Back To Admin)",type:"button",img:"refresh.png"},{id:"hour_revision",text:"Revisi Waktu Lembur",type:"button",img:"clock.png"}]});"admin"!==userLogged.role&&userLogged.rankId>4&&!userLogged.picOvertime&&(l.disableItem("approve"),l.disableItem("reject"),l.disableItem("revision"),l.disableItem("hour_revision"));let r=filterForMonth(new Date);a.cells("a").attachMenu({icon_path:"./public/codebase/icons/",items:[{id:"search",text:"<div style='width:100%'>Search: <input type='text' id='other_start_ovt_appv' readonly value='"+r.start+"' /> - <input type='text' id='other_end_ovt_appv' readonly value='"+r.end+"' /> <button id='other_btn_ftr_ovt_appv'>Proses</button> | Status: <select id='other_status_ovt_appv'><option>ALL</option><option>PROCESS</option><option>REJECTED</option></select></div>"}]});$("#other_btn_ftr_ovt_appv").on("click",(function(){checkFilterDate($("#other_start_ovt_appv").val(),$("#other_end_ovt_appv").val())&&c()})),$("#other_status_ovt_appv").on("change",(function(){checkFilterDate($("#other_start_ovt_appv").val(),$("#other_end_ovt_appv").val())&&(c(),f(null))}));new dhtmlXCalendarObject(["other_start_ovt_appv","other_end_ovt_appv"]);l.attachEvent("onClick",(function(e){switch(e){case"refresh":c(),f(null);break;case"approve":if(!o.getSelectedRowId())return eAlert("Pilih baris yang akan revisi!");var l=o.cells(o.getSelectedRowId(),1).getValue();dhtmlx.modalbox({type:"alert-warning",title:"Approve Lemburan",text:"Anda yakin akan approve lembur "+l+"?",buttons:["Ya","Tidak"],callback:function(e){0==e&&reqJson(Overtime("approveOvertime"),"POST",{taskId:l},((e,t)=>{"success"===t.status?(c(),f(o.getSelectedRowId()),a.cells("b").setText("Detail Lembur"),a.cells("b").collapse(),sAlert(t.message)):eAlert(t.message)}))}});break;case"reject":if(!o.getSelectedRowId())return eAlert("Pilih baris yang akan direject!");l=o.cells(o.getSelectedRowId(),1).getValue();dhtmlx.modalbox({type:"alert-error",title:"Reject Lemburan",text:"Anda yakin akan menolak lembur "+l+"?",buttons:["Ya","Tidak"],callback:function(e){if(0==e){var t=createWindow("reject_overtime","Reject Lembur",500,300);myWins.window("reject_overtime").skipMyCloseEvent=!0;var r=t.attachForm([{type:"fieldset",offsetLeft:30,offsetTop:30,label:"Form Reject",list:[{type:"block",list:[{type:"input",name:"rejection_note",label:"Alasan Reject",labelWidth:130,inputWidth:250,required:!0,rows:3}]}]},{type:"block",offsetLeft:30,offsetTop:10,list:[{type:"button",name:"submit",className:"button_update",offsetLeft:15,value:"Submit"},{type:"newcolumn"},{type:"button",name:"cancel",className:"button_no",offsetLeft:30,value:"Cancel"}]}]);r.attachEvent("onButtonClick",(function(e){switch(e){case"submit":reqJson(Overtime("rejectOvertime"),"POST",{taskId:l,rejectionNote:r.getItemValue("rejection_note")},((e,t)=>{"success"===t.status&&(c(),f(null),a.cells("b").setText("Detail Lembur"),a.cells("b").collapse(),closeWindow("reject_overtime")),sAlert(t.message)}));break;case"cancel":closeWindow("reject_overtime")}}))}}});break;case"revision":if(!o.getSelectedRowId())return eAlert("Pilih baris yang akan revisi!");l=o.cells(o.getSelectedRowId(),1).getValue();dhtmlx.modalbox({type:"alert-warning",title:"Revisi Lemburan",text:"Anda yakin akan mengembalikan lembur "+l+" ke Admin Lembur?",buttons:["Ya","Tidak"],callback:function(e){if(0==e){var t=createWindow("back_to_admin","Revisi Lembur",500,300);myWins.window("back_to_admin").skipMyCloseEvent=!0;var r=t.attachForm([{type:"fieldset",offsetLeft:30,offsetTop:30,label:"Form Revisi",list:[{type:"block",list:[{type:"input",name:"revision_note",label:"Alasan Revisi",labelWidth:130,inputWidth:250,required:!0,rows:3}]}]},{type:"block",offsetLeft:30,offsetTop:10,list:[{type:"button",name:"submit",className:"button_update",offsetLeft:15,value:"Submit"},{type:"newcolumn"},{type:"button",name:"cancel",className:"button_no",offsetLeft:30,value:"Cancel"}]}]);r.attachEvent("onButtonClick",(function(e){switch(e){case"submit":reqJson(Overtime("revisionOvertime"),"POST",{taskId:l,revisionNote:r.getItemValue("revision_note")},((e,t)=>{"success"===t.status&&(c(),f(null),a.cells("b").setText("Detail Lembur"),a.cells("b").collapse(),closeWindow("back_to_admin")),sAlert(t.message)}));break;case"cancel":closeWindow("back_to_admin")}}))}}});break;case"hour_revision":if(!o.getSelectedRowId())return eAlert("Pilih baris yang akan di revisi!");var r=createWindow("hour_revision","Revisi Waktu Lembur",510,280);myWins.window("hour_revision").skipMyCloseEvent=!0;let e=getCurrentTime(o,8,9),d=e.labelStart,u=e.labelEnd;var s=r.attachForm([{type:"fieldset",offsetLeft:30,offsetTop:30,label:"Jam Lembur",list:[{type:"block",list:[{type:"hidden",name:"id",label:"ID",labelWidth:130,inputWidth:250,value:o.getSelectedRowId()},{type:"combo",name:"start_date",label:d,labelWidth:130,inputWidth:250,required:!0,validate:"NotEmpty",options:t.startTimes},{type:"combo",name:"end_date",label:u,labelWidth:130,inputWidth:250,required:!0,validate:"NotEmpty",options:t.endTimes}]}]},{type:"newcolumn"},{type:"block",offsetLeft:30,offsetTop:10,list:[{type:"button",name:"update",className:"button_update",offsetLeft:15,value:"Update"},{type:"newcolumn"},{type:"button",name:"cancel",className:"button_no",offsetLeft:30,value:"Clear"}]}]),i=s.getCombo("start_date"),n=s.getCombo("end_date");let g=t.filterStartTime.indexOf(e.start),p=t.filterEndTime.indexOf(e.end);i.selectOption(g),n.selectOption(p),s.attachEvent("onButtonClick",(function(e){switch(e){case"update":setDisable(["update","cancel"],s,r);let e=new dataProcessor(Overtime("updateOvertimeHour"));e.init(s),s.save(),e.attachEvent("onAfterUpdate",(function(e,t,a,l){let i=l.getAttribute("message");switch(t){case"updated":c(),f(null),sAlert(i),setEnable(["update","cancel"],s,r),closeWindow("hour_revision");break;case"error":eaAlert("Kesalahan Waktu Lembur",i),setEnable(["update","cancel"],s,r)}}));break;case"cancel":closeWindow("hour_revision")}}))}}));let s=a.cells("a").attachStatusBar();function i(){var t=o.getRowsNum();s.setText("Total baris: "+t+" ("+e.approval_overtime+")")}a.cells("a").progressOn();var o=a.cells("a").attachGrid();function n(){l.disableItem("approve"),l.disableItem("reject"),l.disableItem("revision"),l.disableItem("hour_revision")}function d(){l.enableItem("approve"),l.enableItem("reject"),l.enableItem("revision"),l.enableItem("hour_revision")}function c(){a.cells("a").progressOn(),d();let e=$("#other_start_ovt_appv").val(),t=$("#other_end_ovt_appv").val(),l=$("#other_status_ovt_appv").val(),r={notin_status:"CANCELED,CREATED,CLOSED",betweendate_overtime_date:e+","+t};"ALL"!==l&&(r.equal_status=l),"admin"!==userLogged.role&&1!=userLogged.rankId&&1!=userLogged.pltRankId&&(2==userLogged.rankId||2==userLogged.pltRankId?r.in_department_id=userLogged.deptId+","+userLogged.pltDeptId:(userLogged.rankId>2||userLogged.pltRankId>2)&&(r.in_sub_department_id=userLogged.subId+","+userLogged.pltSubId)),o.clearAndLoad(Overtime("getAppvOvertimeGrid",r),i)}o.setImagePath("./public/codebase/imgs/"),o.setHeader("No,Task ID,Sub Unit,Bagian,Disivi,Kebutuhan Orang,Status Hari,Tanggal Overtime,Waktu Mulai, Waktu Selesai,Catatan,Makan,Steam,AHU,Compressor,PW,Jemputan,Dust Collector,Mekanik,Listrik,H&N,Status Overtime,SPV Approval,ASMAN Approval,MANAGER Approval,HEAD Approval,Revisi Jam Lembur,Rejection User Approval,Created By,Updated By,Created At,NIPSPV,NIPASMAN,NIPMGR"),o.attachHeader("#rspan,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter"),o.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str"),o.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left"),o.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt"),o.setInitWidthsP("5,20,20,20,20,15,15,15,20,20,20,7,7,7,7,7,7,7,7,7,7,10,35,35,35,35,30,30,15,15,25,0,0,0"),o.enableSmartRendering(!0),o.attachEvent("onXLE",(function(){a.cells("a").progressOff()})),o.attachEvent("onRowDblClicked",(function(e,t){a.cells("b").setText("Detail Lembur : "+o.cells(e,1).getValue()),a.cells("b").expand(),f(e)})),o.attachEvent("onRowSelect",(function(e,t){if("REJECTED"===o.cells(e,24).getValue())l.disableItem("approve"),l.disableItem("reject"),l.disableItem("hour_revision"),"-"!==o.cells(e,25).getValue()?1==userLogged.rankId||1==userLogged.pltRankId||"admin"===userLogged.role?l.enableItem("revision"):l.disableItem("revision"):"-"!==o.cells(e,24).getValue()?o.cells(e,33).getValue()==userLogged.empNip||"admin"===userLogged.role?l.enableItem("revision"):l.disableItem("revision"):"-"!==o.cells(e,23).getValue()?o.cells(e,32).getValue()==userLogged.empNip||"admin"===userLogged.role?l.enableItem("revision"):l.disableItem("revision"):"-"!==o.cells(e,22).getValue()?o.cells(e,31).getValue()==userLogged.empNip||"admin"===userLogged.role?l.enableItem("revision"):l.disableItem("revision"):5!=userLogged.rankId&&6!=userLogged.rankId||l.enableItem("revision");else{let t=cleanSC(o.cells(e,2).getValue()),a=cleanSC(o.cells(e,3).getValue()),l=cleanSC(o.cells(e,4).getValue());userLogged.picOvertime&&userLogged.rankId<=6||userLogged.pltRankId<=6?"-"!==o.cells(e,24).getValue()||"-"===o.cells(e,33).getValue()?"-"===o.cells(e,33).getValue()?"-"===o.cells(e,22).getValue()?5==userLogged.rankId&&userLogged.division==l||6==userLogged.rankId&&userLogged.division==l||5==userLogged.pltRankId&&userLogged.pltDivision==l||6==userLogged.pltRankId&&userLogged.pltDivision==l?d():n():"-"===o.cells(e,23).getValue()&&(3==userLogged.rankId&&userLogged.subDepartment==a||4==userLogged.rankId&&userLogged.subDepartment==a||3==userLogged.pltRankId&&userLogged.pltSubDepartment==a||4==userLogged.pltRankId&&userLogged.pltSubDepartment==a?d():n()):1==userLogged.rankId||1==userLogged.pltRankId?d():n():"-"!==o.cells(e,23).getValue()||"-"===o.cells(e,32).getValue()?"-"===o.cells(e,32).getValue()?"-"===o.cells(e,22).getValue()&&(5==userLogged.rankId&&userLogged.division==l||6==userLogged.rankId&&userLogged.division==l||5==userLogged.pltRankId&&userLogged.pltDivision==l||6==userLogged.pltRankId&&userLogged.pltDivision==l?d():n()):1==userLogged.rankId||1==userLogged.pltRankId||2==userLogged.rankId&&userLogged.department==t||2==userLogged.pltRankId&&userLogged.pltDepartment==t?d():n():"-"!==o.cells(e,22).getValue()||"-"===o.cells(e,31).getValue()?3==userLogged.rankId&&userLogged.subDepartment==a||4==userLogged.rankId&&userLogged.subDepartment==a||3==userLogged.pltRankId&&userLogged.pltSubDepartment==a||4==userLogged.pltRankId&&userLogged.pltSubDepartment==a?d():n():5==userLogged.rankId&&userLogged.division==l||6==userLogged.rankId&&userLogged.division==l||5==userLogged.pltRankId&&userLogged.pltDivision==l||6==userLogged.pltRankId&&userLogged.pltDivision==l?d():n():n()}})),o.init(),c();var u=a.cells("b").attachToolbar({icon_path:"./public/codebase/icons/",items:[{id:"reject",text:"Batalkan Lembur Personil",type:"button",img:"messagebox_critical.png"},{id:"rollback",text:"Kembalikan Ke Lemburan",type:"button",img:"refresh.png"},{id:"hour_revision",text:"Revisi Waktu Lemburan",type:"button",img:"clock.png"}]});u.attachEvent("onClick",(function(e){if(!g.getSelectedRowId())return eAlert("Pilih baris yang akan batalkan!");let a=g.cells(g.getSelectedRowId(),2).getValue(),l=g.cells(g.getSelectedRowId(),1).getValue();switch(e){case"reject":dhtmlx.modalbox({type:"alert-error",title:"Batalkan Lembur Personil",text:"Anda yakin akan membatalkan lembur "+a+"?",buttons:["Ya","Tidak"],callback:function(e){0==e&&reqJson(Overtime("rejectPersonilOvertime"),"POST",{empTaskId:l},((e,t)=>{"success"===t.status&&f(o.getSelectedRowId()),sAlert(t.message)}))}});break;case"rollback":dhtmlx.modalbox({type:"alert-warning",title:"Rollback Lembur Personil",text:"Anda yakin akan mengembalikan "+a+" ke daftar lemburan?",buttons:["Ya","Tidak"],callback:function(e){0==e&&reqJson(Overtime("rollbackPersonilOvertime"),"POST",{empTaskId:l},((e,t)=>{"success"===t.status?(f(o.getSelectedRowId()),sAlert(t.message)):eaAlert("Kesalahan Waktu Lembur",t.message)}))}});break;case"hour_revision":if(!g.getSelectedRowId())return eAlert("Pilih baris yang akan di revisi!");var r=createWindow("hour_revision_detail","Revisi Waktu Lembur",510,280);myWins.window("hour_revision_detail").skipMyCloseEvent=!0;let e=getCurrentTime(o,8,9),d=t.filterTime.indexOf(e.start),c=t.filterTime.indexOf(e.end),u=t.filterTime.indexOf(e.start),p=t.filterTime.indexOf(e.end);var s=genWorkTime(t.times,d,c),i=genWorkTime(t.times,u,p);let m=e.labelStart,b=e.labelEnd;var n=r.attachForm([{type:"fieldset",offsetLeft:30,offsetTop:30,label:"Jam Lembur",list:[{type:"block",list:[{type:"hidden",name:"id",label:"ID",labelWidth:130,inputWidth:250,value:g.getSelectedRowId()},{type:"combo",name:"start_date",label:m,labelWidth:130,inputWidth:250,required:!0,validate:"NotEmpty",options:s.newStartTime},{type:"combo",name:"end_date",label:b,labelWidth:130,inputWidth:250,required:!0,validate:"NotEmpty",options:i.newEndTime}]}]},{type:"newcolumn"},{type:"block",offsetLeft:30,offsetTop:10,list:[{type:"button",name:"update",className:"button_update",offsetLeft:15,value:"Update"},{type:"newcolumn"},{type:"button",name:"cancel",className:"button_no",offsetLeft:30,value:"Clear"}]}]);let v=n.getCombo("start_date"),_=n.getCombo("end_date"),k=getCurrentTime(g,10,11),x=s.filterStart.indexOf(k.start),L=i.filterEnd.indexOf(k.end);v.selectOption(x),_.selectOption(L),n.attachEvent("onButtonClick",(function(e){switch(e){case"update":setDisable(["update","cancel"],n,r);let e=new dataProcessor(Overtime("updateOvertimeDetailHour"));e.init(n),n.save(),e.attachEvent("onAfterUpdate",(function(e,t,a,l){let s=l.getAttribute("message");switch(t){case"updated":f(o.getSelectedRowId()),sAlert(s),setEnable(["update","cancel"],n,r),closeWindow("hour_revision_detail");break;case"error":eaAlert("Kesalahan Waktu Lembur",s),setEnable(["update","cancel"],n,r)}}));break;case"cancel":closeWindow("hour_revision_detail")}}))}}));var g=a.cells("b").attachGrid();function p(){u.disableItem("reject"),u.disableItem("rollback"),u.disableItem("hour_revision")}function m(){u.enableItem("reject"),u.disableItem("rollback"),u.enableItem("hour_revision")}function f(e){if(e){a.cells("b").progressOn(),u.disableItem("reject"),u.disableItem("rollback"),u.disableItem("hour_revision");let t=o.cells(e,1).getValue();g.clearAndLoad(Overtime("getOvertimeDetailGrid",{equal_task_id:t,notequal_status:"CANCELED"}),b)}else g.clearAll(),g.callEvent("onGridReconstructed",[]),$("#other_total_ovt_appv").html("0")}function b(){sumGridToElement(g,18,"other_total_ovt_appv")}g.setImagePath("./public/codebase/imgs/"),g.setHeader("No,Task ID,Nama Karyawan,Sub Unit,Bagian,Disivi,Nama Mesin #1,Nama Mesin #2,Pelayanan Produksi,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Status Hari,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Premi,Nominal Overtime,Makan,Tugas,Status Overtime,Status Terakhir,Created By,Updated By,Created At,Nik Rejector"),g.attachHeader("#rspan,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter"),g.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str"),g.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left"),g.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt"),g.setInitWidthsP("5,20,20,20,20,20,15,15,20,15,15,15,10,10,10,10,10,10,10,5,25,10,30,15,15,22,0"),g.attachFooter("Total,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,,<div id='other_total_ovt_appv'></div>,,,,,,,,"),g.enableSmartRendering(!0),g.attachEvent("onXLE",(function(){a.cells("b").progressOff()})),g.attachEvent("onRowSelect",(function(e,t){"REJECTED"===g.cells(e,21).getValue()?g.cells(e,26).getValue()==userLogged.empNip?"REJECTED"===o.cells(o.getSelectedRowId(),21).getValue()?p():(u.disableItem("reject"),u.enableItem("rollback"),u.disableItem("hour_revision")):p():"-"!==o.cells(o.getSelectedRowId(),24).getValue()?1==userLogged.rankId||1==userLogged.pltRankId?m():p():"-"!==o.cells(o.getSelectedRowId(),23).getValue()?userLogged.rankId<=2||userLogged.pltRankId<=2?m():p():"-"!==o.cells(o.getSelectedRowId(),22).getValue()?userLogged.rankId<=4||userLogged.pltRankId<=4?m():p():5==userLogged.rankId||6==userLogged.rankId||5==userLogged.pltRankId||6==userLogged.pltRankId?m():p()})),g.init()}

JS;

header('Content-Type: application/javascript');
echo $script;