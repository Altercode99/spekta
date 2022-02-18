<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

	function showAccessControl(e){var s,t,c,a,n,l,o=mainTab.cells("access_control_"+e).attachLayout({pattern:"4T",cells:[{id:"a",text:"Jabatan",header:!0},{id:"b",text:"Menu Utama",header:!0},{id:"c",text:"Accordions",header:!0},{id:"d",text:"Trees",header:!0}]}),i=o.cells("d").attachToolbar({icon_path:"./public/codebase/icons/",items:[{id:"save",text:"Update Akses Tree",type:"button",img:"update.png"}]});var r=!1;async function u(a){if(a){const l=await reqJsonResponse(Access("getAccordions",{subId:e,rankId:s,menuId:a}),"GET",null);(n=o.cells("c").attachTreeView({checkboxes:!0,items:l.items})).attachEvent("onClick",(function(e){c=e,d(e)})),n.attachEvent("onCheck",(function(c,a){r?r=!1:reqJson(Access("updateAccordions"),"POST",{subId:e,rankId:s,menuId:t,accCode:c,status:a?"ACTIVE":"INACTIVE"},((e,s)=>{"success"===s.status?sAlert(s.message):(r=!0,setTimeout((()=>{n.uncheckItem(c),eAlert(s.message)}),200))}))}))}else n=o.cells("c").attachTreeView({checkboxes:!0,items:[]})}function d(c){c?reqJson(Access("getTrees",{subId:e,rankId:s,menuId:t,accCode:c}),"GET",null,((e,s)=>{var t;"success"===s.status?t=s.items:(t=[],eAlert(s.message)),l=o.cells("d").attachTreeView({checkboxes:!0,items:t})})):l=o.cells("d").attachTreeView({checkboxes:!0,items:[]})}!async function(){const c=await reqJsonResponse(Access("getRanks"),"GET",null);o.cells("a").attachTreeView({items:c.items}).attachEvent("onClick",(function(c){(async function(c){if(c){const c=await reqJsonResponse(Access("getMenus",{subId:e,rankId:s}),"GET",null);(a=o.cells("b").attachTreeView({checkboxes:!0,items:c.items})).attachEvent("onClick",(function(e){let s=e.replace("menu-","");t=s,u(s),d(null)})),a.attachEvent("onCheck",(function(t,c){let a=t.replace("menu-","");reqJson(Access("updateMenus"),"POST",{subId:e,rankId:s,menuId:a,status:c?"ACTIVE":"INACTIVE"},((e,s)=>{"success"===s.status?sAlert(s.message):eAlert("Update akses kontrol gagal!")}))}))}else a=o.cells("b").attachTreeView({checkboxes:!0,items:[]})})(s=c.replace("rank-","")),u(null),d(null)}))}(),i.attachEvent("onClick",(function(a){switch(a){case"save":c?n.isItemChecked(c)?reqJson(Access("updateTrees"),"POST",{subId:e,rankId:s,menuId:t,accCode:c,trees:l.getAllChecked()},((e,s)=>{"success"===s.status?sAlert(s.message):eAlert("Update akses control gagal!")})):eAlert("Silahkan centang checkbox accordions!"):eAlert("Belum ada accordions yang dipilih!")}}))}

JS;

header('Content-Type: application/javascript');
echo $script;
