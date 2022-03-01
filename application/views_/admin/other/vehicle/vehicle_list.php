<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	
    function showVehicleList(){var e=mainTab.cells("vehicle_list").attachLayout({pattern:"2U",cells:[{id:"a",text:"Kendaraan"},{id:"b",header:null}]}),t=e.cells("b").attachLayout({pattern:"2E",cells:[{id:"a",header:!1},{id:"b",text:"Detail Reservasi"}]}),a=t.cells("a").attachLayout({pattern:"2U",cells:[{id:"a",text:"Pilih Tanggal",width:285},{id:"b",text:"Total Reservasi"}]}),i=reqJsonResponse(VehicleRev("getVehiclesView"),"GET",null);vehicleView=e.cells("a").attachDataView({container:"vehicle_container",type:{template:i.template,height:160},autowidth:1}),i.data.length>0&&i.data.map((e=>vehicleView.add(e))),vehicleView.attachEvent("onAfterSelect",(function(e){i.data.length>0&&i.data.map((i=>e===i.id&&function(e){t.cells("b").setText("Detail Reservasi"),t.cells("b").attachHTMLString("<div></div>"),a.cells("b").attachHTMLString("<div style='width:100%;height:100%;display:flex;flex-direction:column;justify-content:center;align-items: center;'><p style='font-size:128px;font-family:sans-serif'>0</p></div>"),e;var i=a.cells("a").attachForm([{type:"container",name:"calendar",id:"calendar-info"}]),n=new dhtmlXCalendarObject(i.getContainer("calendar"));function l(){let t=n.getDate();reqJson(RoomRev("getEventCalendar"),"POST",{table:"vehicles_reservation",column:"vehicle_id",id:e,date:t.toISOString()},((a,i)=>{a?eAlert("Get Event Calendar gagal!"):"success"===i.status&&""!==i.dates&&(n.setHolidays(i.dates),s(e,t))}))}function s(e,i){t.cells("b").setText("Detail Reservasi: "+indoDate(i)),reqJson(VehicleRev("getEventDate"),"POST",{vehicleId:e,date:i.toISOString()},((e,i)=>{e?eAlert("Get Event Calendar gagal!"):"success"===i.status&&(t.cells("b").attachHTMLString(i.template),a.cells("b").attachHTMLString("<div style='width:100%;height:100%;display:flex;flex-direction:column;justify-content:center;align-items: center;'><p style='font-size:128px;font-family:sans-serif'>"+i.total+"</p></div>"))}))}n.hideTime(),n.show(),n.setPosition(15,5),l(),n.attachEvent("onClick",(function(a){t.cells("b").setText("Detail Reservasi: "+indoDate(a)),s(e,a)}))}(e)))}))}function detailVehicleEventDate(e){var t=createWindow("event-vehicle-detail","Detail Reservasi Kendaraan",800,500);myWins.window("event-vehicle-detail").skipMyCloseEvent=!0,t.progressOn(),eventGrid=t.attachGrid(),eventGrid.setHeader("Detail Reservasi Kendaraan,#cspan"),eventGrid.setColSorting("str,str"),eventGrid.setColAlign("left,left"),eventGrid.setColTypes("rotxt,rotxt"),eventGrid.setInitWidthsP("25,75"),eventGrid.enableSmartRendering(!0),eventGrid.attachEvent("onXLE",(function(){t.progressOff()})),eventGrid.init(),eventGrid.clearAndLoad(VehicleRev("getEventDetail",{eventId:e}))}

JS;

header('Content-Type: application/javascript');
echo $script;
    