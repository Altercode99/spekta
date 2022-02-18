<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
    
   function detailDocument(e,t){var a,l,d,i,r,n,s=0,o=null,c=null,u=!1,f={parent_id:{url:Document("getMainFolders",{subId:e}),reload:!0}},m=mainTab.cells("document_"+e).attachLayout({pattern:"3J",cells:[{id:"a",header:!1},{id:"b",text:"Preview File",header:!0,width:350},{id:"c",text:"Upload File Baru",header:!0,height:395}]}),p=m.cells("a").attachToolbar({icon_path:"./public/codebase/icons/",items:[{id:"refresh",text:"Refresh",type:"button",img:"refresh.png"},{id:"add_folder",text:"Tambah Folder",type:"button",img:"folder_closed.png"},{id:"rename_folder",text:"Rename Folder",type:"button",img:"folder_closed.png"},{id:"add_file",text:"Upload File Baru",type:"button",img:"app22.png"}]});userLogged.rankId<=6&&27==userLogged.subId||"admin"==userLogged.role?(p.enableItem("add_folder"),p.enableItem("rename_folder"),p.enableItem("add_file")):(p.disableItem("add_folder"),p.disableItem("rename_folder"),p.disableItem("add_file"));var b=m.cells("a").attachLayout({pattern:"2U",cells:[{id:"a",text:"Daftar Folder",header:!0},{id:"b",text:"Informasi",header:!0,width:350}]}),h=b.cells("b").attachMenu({icon_path:"./public/codebase/icons/",items:[{id:"revision",text:"Revisi Dokumen",img:"edit.png"},{id:"download",text:"Download",img:"download_16.png"},{id:"delete",text:"Hapus",img:"delete.png"}]}),g=m.cells("c").attachToolbar({icon_path:"./public/codebase/icons/",items:[{id:"reset",text:"Reset Form",type:"button",img:"messagebox_critical.png"}]});function v(){reqJson(Document("tempFile",{action:"document_control"}),"GET",null,((e,t)=>{if("exist"===t.status){l.setItemValue("filename",t.file.filename);let e="pdf"==t.file.type?"pdf.png":"word.png";l.setItemLabel("file_display","<img src='./public/img/"+e+"' height='120' width='120'>"),l.setItemLabel("file_description","<table><tr><td>Nama File</td><td>:</td><td>"+t.file.doc_name+"</td></tr><tr><td>Tipe</td><td>:</td><td>"+t.file.type+"</td></tr><tr><td>Ukuran</td><td>:</td><td>"+t.file.size+"</td></tr></table>"),clearUploader(l,"file_uploader"),l.hideItem("file_uploader")}else l.setItemValue("filename",""),l.setItemValue("folder_name",""),l.setItemValue("name",""),l.setItemLabel("file_display","<img src='./public/img/no-doc.png' height='120' width='120'>"),l.setItemLabel("file_description","<table><tr><td>Nama File</td><td>:</td><td>-</td></tr><tr><td>Tipe</td><td>:</td><td>-</td></tr><tr><td>Ukuran</td><td>:</td><td>-</td></tr></table>"),l.showItem("file_uploader")}))}p.attachEvent("onClick",(function(t){switch(t){case"refresh":_();break;case"add_folder":if(!1===myWins.isWindow("add_folder")){if(checkMaxOpenWins()<5){let t=createWindow("add_folder","Tambah Folder",515,330).attachTabbar({tabs:[{id:"a",text:tabsStyle("folder_open.png","Folder Utama"),active:!0},{id:"b",text:tabsStyle("folder_open.png","Sub Folder")}]});var a=t.cells("a").attachForm([{type:"fieldset",offsetTop:30,offsetLeft:30,label:"Tambah Folder",list:[{type:"hidden",name:"sub_department_id",label:"Sub Department",value:e},{type:"input",name:"name",label:"Nama Folder",labelWidth:130,inputWidth:250,required:!0},{type:"block",offsetTop:30,list:[{type:"button",name:"add",className:"button_add",offsetLeft:15,value:"Tambah"},{type:"newcolumn"},{type:"button",name:"clear",className:"button_clear",offsetLeft:30,value:"Clear"}]}]}]);a.attachEvent("onButtonClick",(function(e){switch(e){case"add":if(!a.validate())return eAlert("Input error!");setDisable(["add","clear"],a,t.cells("a"));let e=new dataProcessor(Document("docForm"));e.init(a),a.save(),e.attachEvent("onAfterUpdate",(function(e,d,i,r){let n=r.getAttribute("message");switch(d){case"inserted":sAlert("Berhasil Menambahkan Folder <br>"+n),_(),clearAllForm(a,f),clearComboReload(l,"parent_id",f.parent_id.url),setEnable(["add","clear"],a,t.cells("a"));break;case"error":eAlert("Gagal Menambahkan Folder <br>"+n),setEnable(["add","clear"],a,t.cells("a"))}}));break;case"clear":clearAllForm(a,f)}}));var l=t.cells("b").attachForm([{type:"fieldset",offsetTop:30,offsetLeft:30,label:"Tambah Folder",list:[{type:"combo",name:"parent_id",label:"Folder Utama",readonly:!0,required:!0,labelWidth:130,inputWidth:250},{type:"input",name:"name",label:"Nama Sub Folder",labelWidth:130,inputWidth:250,required:!0},{type:"block",offsetTop:30,list:[{type:"button",name:"add",className:"button_add",offsetLeft:15,value:"Tambah"},{type:"newcolumn"},{type:"button",name:"clear",className:"button_clear",offsetLeft:30,value:"Clear"}]}]}]);l.getCombo("parent_id").load(Document("getMainFolders",{subId:e})),l.attachEvent("onButtonClick",(function(e){switch(e){case"add":if(!l.validate())return eAlert("Input error!");setDisable(["add","clear"],l,t.cells("b"));let e=new dataProcessor(Document("docSubForm"));e.init(l),l.save(),e.attachEvent("onAfterUpdate",(function(e,a,d,i){let r=i.getAttribute("message");switch(a){case"inserted":sAlert("Berhasil Menambahkan Sub Folder <br>"+r),_(),clearAllForm(l,f),setEnable(["add","clear"],l,t.cells("b"));break;case"error":eAlert("Gagal Menambahkan Sub Folder <br>"+r),setEnable(["add","clear"],l,t.cells("b"))}}));break;case"clear":clearAllForm(l,f)}}))}else aeAlert("Perhatian!","Terlalu banyak Windows yang dibuka!")}else myWins.window("add_folder").bringToTop(),myWins.window("add_folder").center();break;case"rename_folder":if(!1===myWins.isWindow("rename_folder")){if(checkMaxOpenWins()<5){if(!c)return eAlert("Pilih folder yang akan diubah!");let e=c.includes("main"),t=c.includes("sub");if(e||t){var d=createWindow("rename_folder","Rename Folder",510,300).attachLayout({pattern:"1C",cells:[{id:"a",header:!1}]}),n=e?i[c]:r[c],s=d.cells("a").attachForm([{type:"fieldset",offsetTop:30,offsetLeft:30,label:"Rename Folder",list:[{type:"hidden",name:"id",label:"ID",value:c},{type:"input",name:"name",label:"Nama Folder",labelWidth:130,inputWidth:250,required:!0,value:n.folder_name},{type:"block",offsetTop:30,list:[{type:"button",name:"update",className:"button_update",offsetLeft:15,value:"Simpan"},{type:"newcolumn"},{type:"button",name:"cancel",className:"button_no",offsetLeft:30,value:"Cancel"}]}]}]);s.attachEvent("onButtonClick",(function(e){switch(e){case"update":s.validate()||eAlert("Input error!"),setDisable(["update","cancel"],s,d.cells("a"));let e=new dataProcessor(Document("renameFolder"));e.init(s),s.save(),e.attachEvent("onAfterUpdate",(function(e,t,a,l){let i=l.getAttribute("message");switch(t){case"updated":sAlert("Berhasil Mengubah Record <br>"+i),_(),clearAllForm(s),setEnable(["update","cancel"],s,d.cells("a")),closeWindow("rename_folder");break;case"error":eAlert("Gagal Mengubah Record <br>"+i),setEnable(["update","cancel"],s,d.cells("a"))}}));break;case"cancel":closeWindow("rename_folder")}}))}else eAlert("Pilih folder yang akan diubah!")}else aeAlert("Perhatian!","Terlalu banyak Windows yang dibuka!")}else myWins.window("rename_folder").bringToTop(),myWins.window("rename_folder").center();break;case"add_file":m.cells("c").expand()}})),g.attachEvent("onClick",(function(e){switch(e){case"reset":reqJson(Document("resetForm"),"GET",null,((e,t)=>{"success"==t.status&&v()}))}})),l=m.cells("c").attachForm([{type:"fieldset",offsetTop:30,offsetLeft:30,label:"Tambah Folder",list:[{type:"hidden",name:"folder_id",label:"Folder ID",labelWidth:130,inputWidth:250},{type:"hidden",name:"filename",label:"File Terupload",labelWidth:130,inputWidth:250},{type:"hidden",name:"subId",label:"File Terupload",labelWidth:130,inputWidth:250,value:e},{type:"input",name:"folder_name",label:"Target Folder",labelWidth:130,inputWidth:250,required:!0,readonly:!0},{type:"input",name:"name",label:"Nama Dokumen",labelWidth:130,inputWidth:250,required:!0},{type:"calendar",name:"effective_date",label:"Tanggal Efektif",labelWidth:130,inputWidth:250,required:!0},{type:"input",name:"revision",label:"Revisi",labelWidth:130,inputWidth:250,required:!0,validate:"ValidNumeric"},{type:"upload",name:"file_uploader",inputWidth:420,url:Document("fileUpload"),swfPath:"./public/codebase/ext/uploader.swf",swfUrl:Document("fileUpload"),autoStart:!0},{type:"block",offsetTop:30,list:[{type:"button",name:"add",className:"button_add",offsetLeft:15,value:"Tambah"},{type:"newcolumn"},{type:"button",name:"clear",className:"button_clear",offsetLeft:30,value:"Clear"}]}]},{type:"newcolumn"},{type:"fieldset",offsetTop:30,offsetLeft:30,label:"File Terupload",list:[{type:"container",name:"file_display",label:"<img src='./public/img/no-doc.png' height='120' width='120'>"},{type:"container",name:"file_description",label:"<table><tr><td>Nama File</td><td>:</td><td>-</td></tr><tr><td>Tipe</td><td>:</td><td>-</td></tr><tr><td>Ukuran</td><td>:</td><td>-</td></tr></table>"}]}]),"admin"!==userLogged.role&&setDisable(["add","clear"],l),isFormNumeric(l,["revision"]),l.attachEvent("onBeforeFileAdd",(function(e,t){if("admin"!==userLogged.role)return eAlert("Tidak ada privilage untuk upload!");if(!l.validate())return eAlert("Input error!");var a=e.split(".").pop();if("pdf"==a||"doc"==a||"docx"==a)if(t>5e6)eAlert("Tidak boleh melebihi 5 MB!");else{if(!(s>0))return s++,!0;eAlert("Maksimal 1 file!")}else eAlert("Hanya pdf, doc & docx saja yang bisa diupload!")})),l.attachEvent("onUploadFile",(function(e,t){v(),s=0}));var y=b.cells("a").attachStatusBar();function _(){b.cells("a").progressOn();const t=reqJsonResponse(Document("getTrees",{subId:e}),"GET",null);a=b.cells("a").attachTreeView({items:t.folders});let n=t.isFull?"<span style='display:flex;justify-content:space-between;align-items:center;color:red'>Total Memory Terpakai: "+t.totalSize+" <img width='16' height='16' src='./public/codebase/icons/trash.png' /></span>":"<span>Total Memory Terpakai: "+t.totalSize;y.setText(n),d=t.files,i=t.main,r=t.sub,a.attachEvent("onClick",(function(e){let t=a.getItemText(e),n=e.includes("mfile"),s=e.includes("sfile"),o=e.includes("main"),c=e.includes("sub");if(n||s){let t=d[e];"pdf"===t.type?(m.cells("b").attachURL(fileUrl(t.filename)),w("file",t)):(k(),w("file",t))}else(o||c)&&(k(),w("folder",o?i[e]:r[e]),T(),h.setItemEnabled("delete"),l.setItemValue("folder_id",e),l.setItemValue("folder_name",t))})),b.cells("a").progressOff()}function w(e,t=null){if("file"===e)if(t){!function(){(userLogged.rankId<=6&&27==userLogged.subId||"admin"===userLogged.role)&&(h.setItemEnabled("revision"),h.setItemEnabled("delete"));h.setItemEnabled("download")}(),o=t.filename,c=t.id;let e="pdf"===t.type?"pdf.png":"word.png";b.cells("b").attachHTMLString("<div class='fwm_container'><div class='fwu_container'><div class='fw_img'><img width='70' height='70' src='./public/img/"+e+"' /></div><div class='fw_desc'><table><tr><td>Nama</td><td>:</td><td>"+t.name+"</td></tr><tr><td>Tipe</td><td>:</td><td>"+t.type+"</td></tr><tr><td>Ukuran</td><td>:</td><td>"+t.size+"</td></tr><tr><td>Revisi</td><td>:</td><td>"+t.revision+"</td></tr><tr><td>Efektif</td><td>:</td><td>"+t.effective_date+"</td></tr><tr><td>Created By</td><td>:</td><td>"+t.created_by+"</td></tr><tr><td>Updated By</td><td>:</td><td>"+t.updated_by+"</td></tr></table></div></div><div class='fwd_container'><div class='fwd_desc_2'><table><tr><td>Dibuat Tanggal</td><td>:</td><td>"+t.created_at+"</td></tr><tr><td>Diupdate Tanggal</td><td>:</td><td>"+t.updated_at+"</td></tr></table></div></div></div>")}else T(),b.cells("b").attachHTMLString("<div class='fwm_container'><div class='fwu_container'><div class='fw_img'><img width='70' height='70' src='./public/img/no-doc.png' /></div><div class='fw_desc'><table><tr><td>Nama</td><td>:</td><td>-</td></tr><tr><td>Tipe</td><td>:</td><td>-</td></tr><tr><td>Ukuran</td><td>:</td><td>-</td></tr><tr><td>Revisi</td><td>:</td><td>-</td></tr><tr><td>Efektif</td><td>:</td><td>-</td></tr><tr><td>Created By</td><td>:</td><td>-</td></tr><tr><td>Updated By</td><td>:</td><td>-</td></tr></table></div></div><div class='fwd_container'><div class='fwd_desc_2'><table><tr><td>Dibuat Tanggal</td><td>:</td><td>-</td></tr><tr><td>Diupdate Tanggal</td><td>:</td><td>-</td></tr></table></div></div></div>");else o=null,T(),t?(c=t.id,b.cells("b").attachHTMLString("<div class='fwm_container'><div class='fwu_container'><div class='fw_img'><img width='70' height='70' src='./public/img/folder.png' /></div><div class='fw_desc'><table><tr><td>Nama</td><td>:</td><td>"+t.folder_name+"</td></tr><tr><td>Sub Folder</td><td>:</td><td>"+t.sub_folder+"</td></tr><tr><td>Total File</td><td>:</td><td>"+t.total_file+"</td></tr><tr><td>Ukuran</td><td>:</td><td>"+t.total_size+"</td></tr><tr><td>Created By</td><td>:</td><td>"+t.created_by+"</td></tr><tr><td>Updated By</td><td>:</td><td>"+t.updated_by+"</td></tr></table></div></div><div class='fwd_container'><div class='fwd_desc_2'><table><tr><td>Dibuat Tanggal</td><td>:</td><td>"+t.created_at+"</td></tr><tr><td>Diupdate Tanggal</td><td>:</td><td>"+t.updated_at+"</td></tr></table></div></div></div>")):b.cells("b").attachHTMLString("<div class='fwm_container'><div class='fwu_container'><div class='fw_img'><img width='70' height='70' src='./public/img/folder.png' /></div><div class='fw_desc'><table><tr><td>Nama</td><td>:</td><td>-</td></tr><tr><td>Sub Folder</td><td>:</td><td>-</td></tr><tr><td>Total File</td><td>:</td><td>-</td></tr><tr><td>Ukuran</td><td>:</td><td>-</td></tr><tr><td>Created By</td><td>:</td><td>-</td></tr><tr><td>Updated By</td><td>:</td><td>-</td></tr></table></div></div><div class='fwd_container'><div class='fwd_desc_2'><table><tr><td>Dibuat Tanggal</td><td>:</td><td>-</td></tr><tr><td>Diupdate Tanggal</td><td>:</td><td>-</td></tr></table></div></div></div>")}function k(){m.cells("b").attachHTMLString("<div class='preview_container'><img style='opacity: 0.1' src='./public/img/preview.png' /></div>")}function T(){h.setItemDisabled("revision"),h.setItemDisabled("download"),h.setItemDisabled("delete")}h.attachEvent("onClick",(function(e){switch(e){case"revision":if(!1===myWins.isWindow("revision")){if(checkMaxOpenWins()<5){let e=createWindow("revision","Revisi Dokumen").attachTabbar({tabs:[{id:"a",text:tabsStyle("edit.png","Upload Dokumen Revisi"),active:!0},{id:"b",text:tabsStyle("edit.png","Riwayat Revisi")}]}),i=e.cells("b").attachToolbar({icon_path:"./public/codebase/icons/",items:[{id:"download",type:"button",text:"Download",img:"download_16.png"},{id:"delete",type:"button",text:"Hapus",img:"delete.png"}]});function t(){e.cells("b").progressOn(),(n=e.cells("b").attachGrid()).setHeader("No,Nama Dokumen,Nama File,Tipe,Size,Remark,Revisi,DiRevisi Oleh,Tanggal Rrevisi"),n.setColSorting("int,str,str,str,str,str,str,str,str"),n.setColAlign("center,left,left,left,left,left,left,left,left"),n.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt"),n.setInitWidthsP("5,25,25,10,15,35,15,15,15"),n.enableMultiselect(!0),n.enableSmartRendering(!0),n.attachEvent("onXLE",(function(){e.cells("b").progressOff()})),n.init(),n.clearAndLoad(Document("fileGrid",{fileId:c}))}"admin"!==userLogged.role&&i.disableItem("delete"),i.attachEvent("onClick",(function(e){switch(e){case"download":n.getSelectedRowId()?toDownload(fileUrl(n.cells(n.getSelectedRowId(),2).getValue())):eAlert("Belum ada file yang dipilih!");break;case"delete":n.getSelectedRowId()?dhtmlx.modalbox({type:"alert-error",title:"Konfirmasi",text:"Proses ini tidak dapat dibatalkan, apakah anda yakin ingin menghapus file & revisinya?",buttons:["Ya","Tidak"],callback:function(e){0==e&&reqJson(Document("deleteRevision"),"POST",{id:n.getSelectedRowId()},((e,a)=>{e?eAlert("Hapus file gagal!"):"success"===a.status?(t(),sAlert(a.message)):eAlert(a.message)}))}}):eAlert("Belum ada file yang dipilih!")}})),t();var a=d[c],l=e.cells("a").attachForm([{type:"fieldset",offsetTop:30,offsetLeft:30,label:"Upload Dokumen Baru",list:[{type:"hidden",name:"id",label:"ID",value:a.id},{type:"hidden",name:"filename",label:"Filename"},{type:"input",name:"name",label:"Nama Dokumen",labelWidth:130,inputWidth:250,required:!0,value:a.name},{type:"calendar",name:"effective_date",label:"Tanggal Efektif",labelWidth:130,inputWidth:250,required:!0,value:globalDate},{type:"input",name:"revision",label:"Revisi",labelWidth:130,inputWidth:250,required:!0,readonly:!0,validate:"ValidNumeric",value:parseInt(a.revision)+1},{type:"input",name:"remark",label:"Remark",labelWidth:130,inputWidth:250,required:!0,rows:3},{type:"upload",name:"file_uploader",inputWidth:420,url:Document("fileUpload"),swfPath:"./public/codebase/ext/uploader.swf",swfUrl:Document("fileUpload")}]},{type:"block",offsetTop:30,list:[{type:"button",name:"save",className:"button_update",offsetLeft:15,value:"Simpan"},{type:"newcolumn"},{type:"button",name:"cancel",className:"button_no",offsetLeft:30,value:"Cancel"}]}]);l.attachEvent("onBeforeFileAdd",(async function(e,t){if(l.validate()){var a=e.split(".").pop();if("pdf"==a||"doc"==a||"docx"==a)if(t>5e6)u=!0,eAlert("Tidak boleh melebihi 5 MB!");else if(s>0)eAlert("Maksimal 1 file"),u=!0;else{const e=await reqJsonResponse(Document("checkBeforeRevision"),"POST",{id:l.getItemValue("id"),name:l.getItemValue("name")});if(e){if("success"===e.status)return s++,!0;eAlert(e.message),u=!0}}else eAlert("Hanya pdf, doc & docx saja yang bisa diupload!"),u=!0}else eAlert("Input error!"),setTimeout((()=>{clearUploader(l,"file_uploader")}),200)})),l.attachEvent("onBeforeFileUpload",(function(e,t,a){if(!u)return!0;clearUploader(l,"file_uploader"),eAlert("File error silahkan upload file sesuai ketentuan!"),u=!1})),l.attachEvent("onButtonClick",(function(e){switch(e){case"save":const e=l.getUploader("file_uploader");-1===e.getStatus()?u?(e.clear(),eAlert("File error silahkan upload file sesuai ketentuan!"),u=!1):e.upload():eAlert("Silahkah pilih file terlebih dahulu!");break;case"cancel":closeWindow("revision")}})),l.attachEvent("onUploadFile",(function(a,d){l.setItemValue("filename",d),setDisable(["save","cancel"],l,e.cells("a"));let i=new dataProcessor(Document("revisionFile"));i.init(l),l.save(),i.attachEvent("onAfterUpdate",(function(a,d,i,r){let n=r.getAttribute("message");switch(d){case"inserted":sAlert("Berhasil Menambahkan File Revisi <br>"+n),_(),w("file"),k(),T(),t(),l.setItemValue("remark",""),l.setItemValue("filename",""),l.setItemValue("revision",parseInt(l.getItemValue("revision"))+1),clearUploader(l,"file_uploader"),setEnable(["save","cancel"],l,e.cells("a")),s=0,u=!1;break;case"full":case"error":eAlert("Gagal Menambahkan File Revisi <br>"+n),setEnable(["save","cancel"],l,e.cells("a"))}}))}))}else aeAlert("Perhatian!","Terlalu banyak Windows yang dibuka!")}else myWins.window("revision").bringToTop(),myWins.window("revision").center();break;case"download":o?toDownload(fileUrl(o)):eAlert("Belum ada file yang dipilih!");break;case"delete":c?dhtmlx.modalbox({type:"alert-error",title:"Konfirmasi",text:"Proses ini tidak dapat dibatalkan, apakah anda yakin ingin menghapus folder tersebut?",buttons:["Ya","Tidak"],callback:function(e){0==e&&reqJson(Document("deleteDoc"),"POST",{id:c},((e,t)=>{e?eAlert("Hapus file gagal!"):"success"===t.status?(_(),k(),w("file"),c=null,sAlert(t.message)):eAlert(t.message)}))}}):eAlert("Belum ada file yang dipilih!")}})),l.attachEvent("onButtonClick",(function(e){switch(e){case"add":if(!l.validate())return eAlert("Input error!");if(""===l.getItemValue("filename"))return eAlert("Belum ada file yang diupload!");setDisable(["add","clear"],l,m.cells("c"));let e=new dataProcessor(Document("createFile"));e.init(l),l.save(),e.attachEvent("onAfterUpdate",(function(e,t,a,d){let i=d.getAttribute("message");switch(t){case"inserted":sAlert("Berhasil Menambahkan File <br>"+i),v(),_(),clearAllForm(l),setEnable(["add","clear"],l,m.cells("c")),s=0;break;case"full":reqJson(Document("resetForm"),"GET",null,((e,t)=>{"success"==t.status&&v()})),clearAllForm(l);case"error":eAlert("Gagal Menambahkan File <br>"+i),setEnable(["add","clear"],l,m.cells("c"))}}));break;case"clear":l.setItemValue("folder_name",""),l.setItemValue("name","")}})),_(),w("file"),k(),v(),T(),setTimeout((()=>{m.cells("c").collapse()}),1e3)}

JS;

header('Content-Type: application/javascript');
echo $script;
