<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

    function showRoomSchedule(){var e,t,a=[],n=[],i=mainTab.cells("meeting_room_schedule").attachLayout({pattern:"1C",cells:[{id:"a",header:!1}]});scheduler1=Scheduler.getSchedulerInstance(),scheduler1.clearAll(),i.cells("a").attachScheduler(null,"month",null,scheduler1);var r=reqJsonResponse(RoomRev("getRooms"),"GET",{});function s(e){let t=e.getValue().start_date,a=e.getValue().end_date,n=timeDiffCalc(t,a);if(n.days>0)return eAlert("Meeting harus dimulai dan selesai di hari yang sama");n.hours>=2?(scheduler1.formSection("meal").setValue("1"),scheduler1.formSection("meal").control.disabled=!0):(scheduler1.formSection("meal").setValue("0"),scheduler1.formSection("meal").control.disabled=!1)}function l(){i.cells("a").progressOn(),scheduler1.clearAll();const e=scheduler1.getState(),t={mode:e.mode,date:e.date,min_date:e.min_date.toISOString(),max_date:e.max_date.toISOString()};scheduler1.load(RoomRev("getEvents",t)),i.cells("a").progressOff()}e=r.detail,scheduler1.locale.labels.section_name="Judul Kegiatan",scheduler1.locale.labels.section_meeting_type="Jenis Kegiatan",scheduler1.locale.labels.section_description="Deskripsi Kegiatan",scheduler1.locale.labels.section_room="Ruang Meeting",scheduler1.locale.labels.section_auto="Waktu Reservasi",scheduler1.locale.labels.section_repeat="Repeat Meeting",scheduler1.locale.labels.section_meal="Snack",scheduler1.locale.labels.section_participant="Peserta Meeting",scheduler1.locale.labels.section_guest="Tamu",scheduler1.config.lightbox.sections=[{name:"name",height:32,map_to:"name",type:"textarea",focus:!0},{name:"meeting_type",height:40,map_to:"meeting_type",type:"select",options:[{key:"internal",label:"Meeting Internal"},{key:"external",label:"Meeting Eksternal"}]},{name:"description",height:75,map_to:"description",type:"textarea"},{name:"room",height:40,map_to:"room",type:"select",options:r.data},{name:"time",height:72,type:"time",map_to:"auto"},{name:"meal",height:40,map_to:"meal",type:"select",options:[{key:0,label:"Tanpa Snack"},{key:1,label:"Dengan Snack"}]},{name:"repeat",height:40,map_to:"repeat",type:"select",options:[{key:1,label:"1x"},{key:2,label:"2x"},{key:3,label:"3x"},{key:4,label:"4x"},{key:5,label:"5x"},{key:6,label:"6x"},{key:7,label:"7x"}]},{name:"participant",height:32,map_to:"participant",type:"textarea"},{name:"guest",height:32,map_to:"guest",type:"textarea"}],scheduler1.config.buttons_right=["dhx_save_btn","dhx_cancel_btn"],scheduler1.config.buttons_left=["dhx_delete_btn","participant_button","guest_button"],scheduler1.config.drag_resize=!1,scheduler1.config.drag_move=!1,scheduler1.config.drag_create=!1,scheduler1.config.time_step=30,scheduler1.config.first_hour=8,scheduler1.config.last_hour=16,scheduler1.locale.labels.participant_button="Peserta",scheduler1.locale.labels.guest_button="Tamu",scheduler1.attachEvent("onLightbox",(function(){var e=scheduler1.formSection("participant"),a=scheduler1.formSection("guest");e.control.disabled=!0,a.control.disabled=!0,s(t=scheduler1.formSection("time")),t.control[0].onchange=function(e){s(t)},t.control[1].onchange=function(e){s(t)},t.control[2].onchange=function(e){s(t)},t.control[3].onchange=function(e){s(t)},t.control[4].onchange=function(e){s(t)},t.control[5].onchange=function(e){s(t)},t.control[6].onchange=function(e){s(t)},t.control[7].onchange=function(e){s(t)}})),scheduler1.attachEvent("onLightboxButton",(function(t,i,r){if("participant_button"==t){var s=createWindow("rm_participant","Peserta Meeting",900,400);if(myWins.window("rm_participant").skipMyCloseEvent=!0,""!==scheduler1.formSection("participant").getValue()){scheduler1.formSection("participant").getValue().split(",").map((e=>""!==e&&a.push(e)))}s.attachToolbar({icon_path:"./public/codebase/icons/",items:[{id:"save",text:"Simpan",type:"button",img:"ok.png"}]}).attachEvent("onClick",(function(e){switch(e){case"save":scheduler1.formSection("participant").setValue(a),closeWindow("rm_participant"),a=[]}}));let t=s.attachStatusBar();function l(){var e=participantGrid.getRowsNum();t.setText("Total baris: "+e),a.length>0&&a.map((e=>""!==e&&participantGrid.cells(e,1).setValue(1)))}var o=e[scheduler1.formSection("room").getValue()].capacity;participantGrid=s.attachGrid(),participantGrid.setImagePath("./public/codebase/imgs/"),participantGrid.setHeader("No,Check,Nama Karyawan,Bagian,Jabatan,Email"),participantGrid.attachHeader("#rspan,#rspan,#text_filter,#select_filter,#select_filter,#text_filter"),participantGrid.setColSorting("int,na,str,str,str,str"),participantGrid.setColAlign("center,left,left,left,left,left"),participantGrid.setColTypes("rotxt,ch,rotxt,rotxt,rotxt,rotxt"),participantGrid.setInitWidthsP("5,5,20,25,20,25"),participantGrid.enableSmartRendering(!0),participantGrid.attachEvent("onXLE",(function(){s.progressOff()})),participantGrid.init(),participantGrid.attachEvent("onCheckbox",(function(e,t,i){i?a.length+n.length>=o?(eAlert("Melebihi kapasitas penumpang!"),participantGrid.cells(e,1).setValue(0)):a.push(e):a.splice(a.indexOf(e),1)})),s.progressOn(),participantGrid.clearAndLoad(RoomRev("getEmployees",{equal_status:"ACTIVE",notequal_email:""}),l)}else if("guest_button"==t){var c=createWindow("rm_guest","Daftar Tamu",900,400);if(myWins.window("rm_guest").skipMyCloseEvent=!0,""!==scheduler1.formSection("guest").getValue()){scheduler1.formSection("guest").getValue().split(",").map((e=>""!==e&&n.push(e)))}var d=c.attachToolbar({icon_path:"./public/codebase/icons/",items:[{id:"add",text:"Tambah",type:"button",img:"add.png"},{id:"update",text:"Simpan Tamu Baru",type:"button",img:"update.png"},{id:"save",text:"Simpan",type:"button",img:"ok.png"}]});d.attachEvent("onClick",(function(e){switch(e){case"save":scheduler1.formSection("guest").setValue(n),closeWindow("rm_guest"),n=[];break;case"add":let e=(new Date).valueOf();guestGrid.addRow(e,["",0,"Nama Tamu","Nama Perusahaan","Email"]);break;case"update":if(guestGrid.getChangedRows()){d.disableItem("update"),c.progressOn(),guestGridDP.sendData(),guestGridDP.attachEvent("onAfterUpdate",(function(e,t,a,n){let i=n.getAttribute("message");switch(t){case"updated":let e=i.split(",");e.length>=1&&""!=e[0]&&sAlert(e[0]),e.length>=2&&""!=e[1]&&eAlert(e[1]),g(),d.enableItem("update"),c.progressOff(),p()}}));break}eAlert("Belum ada row yang di edit!")}}));let t=c.attachStatusBar();function u(){var e=guestGrid.getRowsNum();t.setText("Total baris: "+e),n.length>0&&n.map((e=>""!==e&&guestGrid.cells(e,1).setValue(1)))}o=e[scheduler1.formSection("room").getValue()].capacity;function p(){guestGridDP=new dataProcessor(RoomRev("addGuest")),guestGridDP.setTransactionMode("POST",!0),guestGridDP.setUpdateMode("Off"),guestGridDP.init(guestGrid)}function g(){c.progressOn(),guestGrid.clearAndLoad(RoomRev("getGuests"),u)}guestGrid=c.attachGrid(),guestGrid.setImagePath("./public/codebase/imgs/"),guestGrid.setHeader("No,Check,Nama Tamu,Perusahaan,Email"),guestGrid.attachHeader("#rspan,#rspan,#text_filter,#select_filter,#text_filter"),guestGrid.setColSorting("int,na,str,str,str"),guestGrid.setColAlign("center,left,left,left,left"),guestGrid.setColTypes("rotxt,ch,ed,ed,ed"),guestGrid.setInitWidthsP("5,5,30,35,25"),guestGrid.enableSmartRendering(!0),guestGrid.setEditable(!0),guestGrid.attachEvent("onXLE",(function(){c.progressOff()})),guestGrid.init(),guestGrid.attachEvent("onCheckbox",(function(e,t,i){i?a.length+n.length>=o?(eAlert("Melebihi kapasitas penumpang!"),guestGrid.cells(e,1).setValue(0)):n.push(e):n.splice(n.indexOf(e),1)})),p(),g()}})),scheduler1.attachEvent("onViewChange",(function(e,t){l()})),$(".dhx_cal_tab").attr("style","dispay:none"),scheduler1.attachEvent("onClick",(function(e,t){return scheduler1._on_dbl_click(t||event),!1}));var o=new dataProcessor(RoomRev("eventHandler"));o.init(scheduler1),o.setTransactionMode("JSON"),o.attachEvent("onAfterUpdate",(function(e,t,a,n){let i=n.getAttribute("message");"inserted"===t||"updated"===t?(sAlert(i),l()):eAlert(i),l()})),l()}	

JS;

header('Content-Type: application/javascript');
echo $script;
    