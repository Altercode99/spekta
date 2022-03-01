<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	
    function showUser(){var e,t={nip:{url:User("getEmps"),reload:!0},role_id:{url:User("getRoles"),reload:!0}},a=mainTab.cells("user").attachTabbar({tabs:[{id:"data",text:"Daftar User",active:!0}]});a.setArrowsMode("auto"),a.enableAutoReSize(!0);var r=[{id:"refresh",text:"Refresh",type:"button",img:"refresh.png"},{id:"add",text:"Tambah",type:"button",img:"add.png"},{id:"edit",text:"Ubah",type:"button",img:"edit.png",img_disabled:"edit_disabled.png"},{id:"inactive",text:"Non Aktif",type:"button",img:"block.png"}];"admin"===userLogged.role?(r.push({id:"delete",text:"Hapus",type:"button",img:"delete.png"}),r.push({id:"searchtext",text:"Cari : ",type:"text"}),r.push({id:"search",text:"",type:"buttonInput",width:150})):(r.push({id:"searchtext",text:"Cari : ",type:"text"}),r.push({id:"search",text:"",type:"buttonInput",width:150}));var s=a.cells("data").attachToolbar({icon_path:"./public/codebase/icons/",items:r});s.attachEvent("onClick",(function(r){switch(r){case"refresh":s.setValue("search",""),d();break;case"add":!function(){if(null===a.tabs("add")){a.addTab("add","Tambah User",null,null,!0,!0);var r=(e=a.tabs("add").attachForm([{type:"fieldset",offsetLeft:30,label:"Data User",list:[{type:"block",list:[{type:"combo",name:"nip",label:"Nama Karyawan",labelWidth:130,inputWidth:250,validate:"NotEmpty",required:!0},{type:"input",name:"username",label:"Username",labelWidth:130,inputWidth:250,required:!0},{type:"password",name:"password",label:"Password",labelWidth:130,inputWidth:250,required:!0},{type:"password",name:"confirm_password",label:"Konfirmasi Password",labelWidth:130,inputWidth:250,required:!0},{type:"combo",name:"role_id",label:"Privilage",labelWidth:130,inputWidth:250,readonly:!0,validate:"NotEmpty",required:!0},{type:"combo",name:"access",label:"Level Akses",readonly:!0,required:!0,labelWidth:130,inputWidth:250,validate:"NotEmpty",options:[{value:"",text:""},{value:"BOTH",text:"Web & Mobile"},{value:"WEB",text:"Web"},{value:"MOBILE",text:"Mobile"}]}]},{type:"block",offsetLeft:30,offsetTop:10,list:[{type:"button",name:"add",className:"button_add",offsetLeft:15,value:"Tambah"},{type:"newcolumn"},{type:"button",name:"clear",className:"button_clear",offsetLeft:30,value:"Clear"},{type:"newcolumn"},{type:"button",name:"cancel",className:"button_no",offsetLeft:30,value:"Cancel"}]}]}])).getCombo("nip");function s(e){r.clearAll(),e.length>3&&dhx.ajax.get(User("getEmps",{name:e}),(function(e){e.xmlDoc.responseText&&(r.load(e.xmlDoc.responseText),r.openSelect())}))}r.enableFilteringMode(!0,"nip"),r.attachEvent("onDynXLS",s),e.getCombo("role_id").load(User("getRoles")),e.attachEvent("onButtonClick",(function(r){switch(r){case"add":l();break;case"clear":clearAllForm(e,t);break;case"cancel":a.tabs("add").close()}}))}else a.tabs("add").setActive();function l(){if(!e.validate())return eAlert("Input error!");setDisable(["add","clear"],e);var a=new dataProcessor(User("userForm"));a.init(e);setEscape(e.getItemValue("username"));if(setEscape(e.getItemValue("password"))!==setEscape(e.getItemValue("confirm_password")))return setEnable(["update","clear"],e),eAlert("Password tidak sama!");e.save(),a.attachEvent("onAfterUpdate",(function(a,r,s,l){var i=l.getAttribute("message");switch(r){case"inserted":sAlert("Berhasil Menambahkan Record <br>"+i),d(),clearAllForm(e,t),setEnable(["add","clear"],e);break;case"error":eAlert("Gagal Menambahkan Record <br>"+i),e.setItemValue("password",""),e.setItemValue("confirm_password",""),setEnable(["add","clear"],e)}}))}}();break;case"inactive":userGrid.getSelectedRowId()?reqAction(userGrid,User("userStatus"),1,((e,t)=>{d(),t.mSuccess&&sAlert("Sukses Non Aktifkan Record <br>"+t.mSuccess),t.mError&&eAlert("Gagal Non Aktifkan Record <br>"+t.mError)})):eAlert("Pilih baris yang akan dihapus!");break;case"delete":reqAction(userGrid,User("userDelete"),1,((e,t)=>{d(),t.mSuccess&&sAlert("Sukses Menghapus Record <br>"+t.mSuccess),t.mError&&eAlert("Gagal Menghapus Record <br>"+t.mError)}));break;case"edit":!function(){if(userGrid.getSelectedRowId())if(null==a.tabs("edit")){a.addTab("edit","Ubah User",null,null,!0,!0),editEmpForm=a.tabs("edit").attachForm([{type:"fieldset",offsetLeft:30,label:"Data User",list:[{type:"block",list:[{type:"hidden",name:"id",required:!0,readonly:!0},{type:"input",name:"nip",label:"Nama Karyawan",labelWidth:130,inputWidth:250,required:!0,readonly:!0},{type:"input",name:"username",label:"Username",labelWidth:130,inputWidth:250,required:!0,readonly:!0},{type:"combo",name:"role_id",label:"Privilage",labelWidth:130,inputWidth:250,readonly:!0,validate:"NotEmpty",required:!0},{type:"combo",name:"access",label:"Level Akses",readonly:!0,required:!0,labelWidth:130,inputWidth:250,validate:"NotEmpty",options:[{value:"",text:""},{value:"BOTH",text:"Web & Mobile"},{value:"WEB",text:"Web"},{value:"MOBILE",text:"Mobile"}]},{type:"combo",name:"status",label:"Status User",readonly:!0,required:!0,labelWidth:130,inputWidth:250,validate:"NotEmpty",options:[{value:"",text:""},{value:"ACTIVE",text:"ACTIVE"},{value:"INACTIVE",text:"INACTIVE"}]}]}]},{type:"fieldset",offsetLeft:30,label:"Ganti Password (Kosongkan jika tidak diubah)",list:[{type:"block",list:[{type:"password",name:"new_password",label:"Password",labelWidth:130,inputWidth:250},{type:"password",name:"new_password_confirm",label:"Konfirmasi Password",labelWidth:130,inputWidth:250}]},{type:"block",offsetTop:30,list:[{type:"button",name:"update",className:"button_update",offsetLeft:15,value:"Simpan"},{type:"newcolumn"},{type:"button",name:"cancel",className:"button_no",offsetLeft:15,value:"Cancel"}]}]}]);var e=editEmpForm.getCombo("role_id");function t(){e.load(User("getRoles",{select:editEmpForm.getItemValue("role_id")}))}fetchFormData(User("userForm",{id:userGrid.getSelectedRowId()}),editEmpForm,null,null,t);var r=new dataProcessor(User("userForm"));r.init(editEmpForm),editEmpForm.attachEvent("onButtonClick",(function(e){switch(e){case"update":s();break;case"cancel":a.tabs("edit").close()}})),r.attachEvent("onAfterUpdate",(function(e,t,a,r){var s=r.getAttribute("message");switch(t){case"updated":sAlert("Berhasil Mengubah Record <br>"+s),d(),editEmpForm.setItemValue("new_password",""),editEmpForm.setItemValue("new_password_confirm",""),setEnable(["update","clear"],editEmpForm);break;case"error":eAlert("Gagal Mengubah Record <br>"+s),editEmpForm.setItemValue("new_password",""),editEmpForm.setItemValue("new_password_confirm",""),setEnable(["update","clear"],editEmpForm)}}))}else a.tabs("edit").setActive(),fetchFormData(User("userForm",{id:userGrid.getSelectedRowId()}),editEmpForm);else eAlert("Pilih baris yang akan diubah!");function s(){if(!editEmpForm.validate())return eAlert("Input error!");setDisable(["update","clear"],editEmpForm);setEscape(editEmpForm.getItemValue("username"));var e=setEscape(editEmpForm.getItemValue("new_password")),t=setEscape(editEmpForm.getItemValue("new_password_confirm"));return""!==e&&""===t||""===e&&""!==t?(setEnable(["update","clear"],editEmpForm),eAlert("Password & konfirmasi password tidak boleh kosong!")):e&&t&&e!==t?(setEnable(["update","clear"],editEmpForm),eAlert("Password tidak sama!")):void editEmpForm.save()}}()}})),s.attachEvent("onEnter",(function(e){if("search"===e)userGrid.clearAndLoad(User("userGrid",{search:s.getValue("search")}),i),userGrid.attachEvent("onGridReconstructed",i)}));var l=a.cells("data").attachStatusBar();function i(){var e=userGrid.getRowsNum();l.setText("Total baris: "+e)}function d(){a.cells("data").progressOn(),userGrid=a.cells("data").attachGrid(),userGrid.setHeader("No,Nama Karyawan,Department,Jabatan,Username,Privilage,Status,Update Password,Di Buat"),userGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter"),userGrid.setColSorting("str,str,str,str,str,str,str,str,str"),userGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt"),userGrid.setColAlign("center,left,left,left,left,left,left,left,left"),userGrid.setInitWidthsP("5,15,15,15,15,15,15,20,20"),userGrid.enableSmartRendering(!0),userGrid.enableMultiselect(!0),userGrid.init(),userGrid.attachEvent("onXLE",(function(){a.cells("data").progressOff()})),userGrid.load(User("userGrid",{search:s.getValue("search")}),i)}d(),a.attachEvent("onTabClose",(function(t){switch(t){case"edit":if(a.tabs("edit").skipMyCloseEvent)return!0;e=null,userGrid.clearAndLoad(User("userGrid"),i),a.tabs("edit").close();break;case"add":if(a.tabs("add").skipMyCloseEvent)return!0;a.tabs("add").skipMyCloseEvent=!0,a.tabs("add").close()}}))}

JS;

header('Content-Type: application/javascript');
echo $script;
