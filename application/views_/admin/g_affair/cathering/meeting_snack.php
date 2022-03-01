<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

	function showMeetingSnack(){var e,t,a,l,s=mainTab.cells("ga_meeting_snack").attachLayout({pattern:"2U",cells:[{id:"a",header:!1},{id:"b",text:"Form Snack Meeting",header:!0,collapse:!0}]}),i=mainTab.cells("ga_meeting_snack").attachToolbar({icon_path:"./public/codebase/icons/",items:[{id:"refresh",text:"Refresh",type:"button",img:"refresh.png"},{id:"add",text:"Tambah",type:"button",img:"add.png"},{id:"delete",text:"Hapus",type:"button",img:"delete.png"},{id:"edit",text:"Ubah",type:"button",img:"edit.png",img_disabled:"edit_disabled.png"},{id:"searchtext",text:"Cari : ",type:"text"},{id:"search",text:"",type:"buttonInput",width:150}]});"admin"!==userLogged.role&&i.disableItem("delete");var n=s.cells("a").attachStatusBar();function r(){let e=c.getRowsNum();n.setText("Total baris: "+e)}var c=s.cells("a").attachGrid();function o(){s.cells("a").progressOn(),c.clearAndLoad(GAOther("getSnackGrid",{search:i.getValue("search")}),r)}async function d(e,t,i=null){if(e.validate()){var n=t.filename.split(".").pop();if("png"==n||"jpg"==n||"jpeg"==n)if(t.size>1e6)a=!0,eAlert("Tidak boleh melebihi 1 MB!");else if(l>0)eAlert("Maksimal 1 file"),a=!0;else{const t={id:i,name:e.getItemValue("name")},n=await reqJsonResponse(GAOther("checkBeforeAddFile"),"POST",t);if(n){if("success"===n.status)return l++,!0;"deleted"===n.status?(a=!1,l=0,s.cells("b").collapse(),s.cells("b").showView("tambah_snack")):(eAlert(n.message),a=!0)}}else eAlert("Hanya png, jpg & jpeg saja yang bisa diupload!"),a=!0}else eAlert("Input error!")}c.setHeader("No,Nama Snack,Harga,Created By,Updated By,DiBuat"),c.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter"),c.setColSorting("str,str,str,str,str,str"),c.setColTypes("rotxt,rotxt,ron,rotxt,rotxt,rotxt"),c.setColAlign("center,left,left,left,left,left"),c.setInitWidthsP("5,20,20,15,15,25"),c.enableSmartRendering(!0),c.attachEvent("onXLE",(function(){s.cells("a").progressOff()})),c.setNumberFormat("0,000",2,".",","),c.init(),snackGridDP=new dataProcessor(GAOther("getSnackGrid")),snackGridDP.setTransactionMode("POST",!0),snackGridDP.setUpdateMode("Off"),snackGridDP.init(c),o(),i.attachEvent("onClick",(function(l){switch(l){case"refresh":i.setValue("search",""),o();break;case"add":!function(){function t(){if(!e.validate())return eAlert("Input error!");setDisable(["add","clear"],e,s.cells("b"));let t=new dataProcessor(GAOther("snackForm"));t.init(e),e.save(),t.attachEvent("onAfterUpdate",(function(t,a,l,i){let n=i.getAttribute("message");switch(a){case"inserted":sAlert("Berhasil Menambahkan Record <br>"+n),o(),clearAllForm(e),clearUploader(e,"file_uploader"),setEnable(["add","clear"],e,s.cells("b"));break;case"error":eAlert("Gagal Menambahkan Record <br>"+n),setEnable(["add","clear"],e,s.cells("b"))}}))}s.cells("b").expand(),s.cells("b").showView("tambah_snack"),e=s.cells("b").attachForm([{type:"fieldset",offsetTop:30,offsetLeft:30,label:"Tambah Mesin Produksi",list:[{type:"input",name:"name",label:"Nama Snack",labelWidth:130,inputWidth:250,required:!0},{type:"input",name:"price",label:"Harga",required:!0,labelWidth:130,inputWidth:250,validate:"ValidNumeric"},{type:"hidden",name:"filename",label:"Filename",readonly:!0},{type:"upload",name:"file_uploader",inputWidth:420,url:AppMaster("fileUpload",{save:!1,folder:"meeting_snacks"}),swfPath:"./public/codebase/ext/uploader.swf",swfUrl:AppMaster("fileUpload")},{type:"block",offsetTop:30,list:[{type:"button",name:"add",className:"button_add",offsetLeft:15,value:"Tambah"},{type:"newcolumn"},{type:"button",name:"clear",className:"button_clear",offsetLeft:30,value:"Clear"},{type:"newcolumn"},{type:"button",name:"cancel",className:"button_no",offsetLeft:30,value:"Cancel"}]}]}]),isFormNumeric(e,["price"]),e.attachEvent("onBeforeFileAdd",(async function(t,a){d(e,{filename:t,size:a})})),e.attachEvent("onBeforeFileUpload",(function(t,l,s){if(!a)return!0;clearUploader(e,"file_uploader"),eAlert("File error silahkan upload file sesuai ketentuan!"),a=!1})),e.attachEvent("onButtonClick",(function(l){switch(l){case"add":const l=e.getUploader("file_uploader");-1===l.getStatus()?a?(l.clear(),eAlert("File error silahkan upload file sesuai ketentuan!"),a=!1):l.upload():t();break;case"clear":clearAllForm(e);break;case"cancel":s.cells("b").collapse()}})),e.attachEvent("onUploadFile",(function(a,l){e.setItemValue("filename",l),t()}))}();break;case"delete":reqAction(c,GAOther("snackDelete"),1,((e,t)=>{o(),t.mSuccess&&sAlert("Sukses Menghapus Record <br>"+t.mSuccess),t.mError&&eAlert("Gagal Menghapus Record <br>"+t.mError)}));break;case"edit":!function(){if(!c.getSelectedRowId())return eAlert("Pilih baris yang akan diubah!");s.cells("b").expand(),s.cells("b").showView("edit_mesin_snack"),t=s.cells("b").attachForm([{type:"fieldset",offsetTop:30,offsetLeft:30,label:"Edit Ruang Meeting",list:[{type:"hidden",name:"id",label:"ID",readonly:!0},{type:"input",name:"name",label:"Nama Mesin",labelWidth:130,inputWidth:250,required:!0},{type:"input",name:"price",label:"Harga",required:!0,labelWidth:130,inputWidth:250,validate:"ValidNumeric"},{type:"hidden",name:"filename",label:"Filename",readonly:!0},{type:"upload",name:"file_uploader",inputWidth:420,url:AppMaster("fileUpload",{save:!1,folder:"meeting_snacks"}),swfPath:"./public/codebase/ext/uploader.swf",swfUrl:AppMaster("fileUpload"),autoStart:!0},{type:"block",offsetTop:30,list:[{type:"button",name:"update",className:"button_update",offsetLeft:15,value:"Simpan"},{type:"newcolumn"},{type:"button",name:"cancel",className:"button_no",offsetLeft:30,value:"Cancel"}]}]},{type:"fieldset",offsetTop:30,offsetLeft:30,label:"Foto Mesin Produksi",list:[{type:"container",name:"file_display",label:"<img src='./public/img/no-image.png' height='120' width='120'>"}]}]);const e=e=>{0===e.length?t.setItemLabel("file_display","<img src='./public/img/no-image.png' height='120' width='120'>"):e.map((e=>{if(""===e)t.setItemLabel("file_display","<img src='./public/img/no-image.png' height='120' width='120'>");else{var a="<img src='./assets/images/meeting_snacks/"+e+"' height='120' width='120'>";t.setItemLabel("file_display",a)}}))};fetchFormData(GAOther("snackForm",{id:c.getSelectedRowId()}),t,["filename"],e),t.attachEvent("onBeforeFileAdd",(async function(e,a){d(t,{filename:e,size:a},t.getItemValue("id"))})),t.attachEvent("onBeforeFileUpload",(function(e,l,s){if(!a)return!0;clearUploader(t,"file_uploader"),eAlert("File error silahkan upload file sesuai ketentuan!"),a=!1})),t.attachEvent("onUploadFile",(function(e,a){reqJson(AppMaster("updateAfterUpload"),"POST",{id:t.getItemValue("id"),oldFile:t.getItemValue("filename"),filename:a,folder:"kf_general.snacks"},((e,l)=>{"success"===l.status?(t.setItemValue("filename",a),clearUploader(t,"file_uploader"),t.setItemLabel("file_display","<img src='./assets/images/meeting_snacks/"+a+"' height='120' width='120'>"),sAlert(l.message)):eAlert(l.message)}))})),t.attachEvent("onButtonClick",(function(e){switch(e){case"update":setDisable(["update","cancel"],t,s.cells("b"));let e=new dataProcessor(GAOther("snackForm"));e.init(t),t.save(),e.attachEvent("onAfterUpdate",(function(e,a,l,i){let n=i.getAttribute("message");switch(a){case"updated":sAlert("Berhasil Mengubah Record <br>"+n),o(),s.cells("b").progressOff(),s.cells("b").showView("tambah_snack"),s.cells("b").collapse();break;case"error":eAlert("Gagal Mengubah Record <br>"+n),setEnable(["update","cancel"],t,s.cells("b"))}}));break;case"cancel":s.cells("b").collapse(),s.cells("b").showView("tambah_snack")}}))}()}})),i.attachEvent("onEnter",(function(e){switch(e){case"search":o(),c.attachEvent("onGridReconstructed",r)}}))}

JS;

header('Content-Type: application/javascript');
echo $script;