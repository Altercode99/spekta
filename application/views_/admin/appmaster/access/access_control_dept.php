<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

	function showAccessControlDept(e){var t,s,c,a,n,l,o=mainTab.cells("access_control_dept_"+e).attachLayout({pattern:"4T",cells:[{id:"a",text:"Jabatan",header:!0},{id:"b",text:"Menu Utama",header:!0},{id:"c",text:"Accordions",header:!0},{id:"d",text:"Trees",header:!0}]}),i=o.cells("d").attachToolbar({icon_path:"./public/codebase/icons/",items:[{id:"save",text:"Update Akses Tree",type:"button",img:"update.png"}]});var d=!1;async function r(a){if(a){const l=await reqJsonResponse(AccessDept("getAccordions",{deptId:e,rankId:t,menuId:a}),"GET",null);(n=o.cells("c").attachTreeView({checkboxes:!0,items:l.items})).attachEvent("onClick",(function(e){c=e,u(e)})),n.attachEvent("onCheck",(function(c,a){d?d=!1:reqJson(AccessDept("updateAccordions"),"POST",{deptId:e,rankId:t,menuId:s,accCode:c,status:a?"ACTIVE":"INACTIVE"},((e,t)=>{"success"===t.status?sAlert(t.message):(d=!0,setTimeout((()=>{n.uncheckItem(c),eAlert(t.message)}),200))}))}))}else n=o.cells("c").attachTreeView({checkboxes:!0,items:[]})}function u(c){c?reqJson(AccessDept("getTrees",{deptId:e,rankId:t,menuId:s,accCode:c}),"GET",null,((e,t)=>{var s;"success"===t.status?s=t.items:(s=[],eAlert(t.message)),l=o.cells("d").attachTreeView({checkboxes:!0,items:s})})):l=o.cells("d").attachTreeView({checkboxes:!0,items:[]})}!async function(){const c=await reqJsonResponse(AccessDept("getRanks"),"GET",null);o.cells("a").attachTreeView({items:c.items}).attachEvent("onClick",(function(c){(async function(c){if(c){const c=await reqJsonResponse(AccessDept("getMenus",{deptId:e,rankId:t}),"GET",null);(a=o.cells("b").attachTreeView({checkboxes:!0,items:c.items})).attachEvent("onClick",(function(e){let t=e.replace("menu-","");s=t,r(t),u(null)})),a.attachEvent("onCheck",(function(s,c){let a=s.replace("menu-","");reqJson(AccessDept("updateMenus"),"POST",{deptId:e,rankId:t,menuId:a,status:c?"ACTIVE":"INACTIVE"},((e,t)=>{"success"===t.status?sAlert(t.message):eAlert("Update akses kontrol gagal!")}))}))}else a=o.cells("b").attachTreeView({checkboxes:!0,items:[]})})(t=c.replace("rank-","")),r(null),u(null)}))}(),i.attachEvent("onClick",(function(a){switch(a){case"save":c?n.isItemChecked(c)?reqJson(AccessDept("updateTrees"),"POST",{deptId:e,rankId:t,menuId:s,accCode:c,trees:l.getAllChecked()},((e,t)=>{"success"===t.status?sAlert(t.message):eAlert("Update akses control gagal!")})):eAlert("Silahkan centang checkbox accordions!"):eAlert("Belum ada accordions yang dipilih!")}}))}

JS;

header('Content-Type: application/javascript');
echo $script;
