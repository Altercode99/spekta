<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	var homeLayout;
	
	function showHome() {
		homeLayout = mainTab.cells("home").attachLayout({
			pattern : "3T",		
			cells : [
				{id : "a", header : false, height: 275},
				{id : "b", header : true, text: "Event " + userLogged.locName},
				{id : "c", header : true, text: "Info"},
			]	
		});

		dataView = homeLayout.cells("a").attachDataView({
			container: "data_container",
			type:{
				template:"<div style='text-align:center;padding-top:20px;cursor:pointer;'><img src='#path#' height='50' width='50' style='cursor:pointer' /><p>#title#</p></div>",
				width: contentWidth * 0.1717,
				height: 120
			}
		});

		homeLayout.cells("c").attachHTMLString("<div id='news' style='width:100%;height:100%'></div>");

		reqJson(Home("getNews"), "POST", null, (err, res) => {
			if(res.status === "success") {
				$("#news").html(res.template);
			}
		});

		dataView.add({
			path:"./public/codebase/icons/info.png",
			title: "Info",
			id:"info"
		});
		
		if(isHaveMenu("Dashboard")) {
			dataView.add({
				path:"./public/codebase/icons/dashboard.png",
				title: "Dashboard",
				id:"dashboard"
			});
		}

		if(isHaveMenu("Akses & Master")) {
			dataView.add({
				path:"./public/codebase/icons/key.png",
				title: "Akses & Master",
				id:"access"
			});
		}

		if(isHaveMenu("Human Resource")) {
			dataView.add({
				path:"./public/codebase/icons/hrd.png",
				title: "SDM & Akutansi",
				id:"hr"
			});
		}

		if(isHaveMenu("General Affair")) {
			dataView.add({
				path:"./public/codebase/icons/building.png",
				title: "Umum & K3L",
				id:"ga"
			});
		}

		// if(isHaveMenu("Production")) {
		// 	dataView.add({
		// 		path:"./public/codebase/icons/production.png",
		// 		title: "Produksi",
		// 		id:"prod"
		// 	});
		// }

		// if(isHaveMenu("Warehouse")) {
		// 	dataView.add({
		// 		path:"./public/codebase/icons/warehouse.png",
		// 		title: "Penyimpanan",
		// 		id:"whs"
		// 	});
		// }

		if(isHaveMenu("Teknik & Pemeliharaan")) {
			dataView.add({
				path:"./public/codebase/icons/tools.png",
				title: "Teknik & Pemeliharaan",
				id:"technique"
			});
		}

		if(isHaveMenu("Dokumen Kontrol")) {
			dataView.add({
				path:"./public/codebase/icons/document_48.png",
				title: "Dokumen Kontrol",
				id:"doc"
			});
		}

		if(isHaveMenu("Others")) {
			dataView.add({
				path:"./public/codebase/icons/others.png",
				title: "Others",
				id:"other"
			});
		}

		if(isHaveMenu("Proyek Manajemen")) {
			dataView.add({
				path:"./public/codebase/icons/timeline.png",
				title: "Proyek Manajemen",
				id:"project"
			});
		}

		dataView.select("info");

		dataView.attachEvent("onAfterSelect", function(id) {
			if(id == "info") {
				sidebar.hideItem("trees");
				sidebar.showItem("info");
				sidebar.showItem("user");
				$("#title-menu").html(toDay);
			} else if(id == "access") {
				leftRightLayout.cells("a").expand();
				setTimeout(() => {
					if(typeof accessAccordion === "function"){
						accessAccordion();
					}
				}, 100);
			} else if(id == "dashboard") {
				leftRightLayout.cells("a").expand();
				setTimeout(() => {
					if(typeof dashboardAccordion === "function"){
						dashboardAccordion();
					}
				}, 100);
			} else if(id == "hr") {
				leftRightLayout.cells("a").expand();
				setTimeout(() => {
					if(typeof hrAccordion === "function"){
						hrAccordion();
					}
				}, 100);
			} else if(id == "ga") {
				leftRightLayout.cells("a").expand();
				setTimeout(() => {
					if(typeof affairAccordion === "function"){
						affairAccordion();
					}
				}, 100);
			} else if(id == "prod") {
				leftRightLayout.cells("a").expand();
				setTimeout(() => {
					if(typeof productionAccordion === "function"){
						productionAccordion();
					}
				}, 100);
			} else if(id == "whs") {
				leftRightLayout.cells("a").expand();
				setTimeout(() => {
					if(typeof whsAccordion === "function"){
						whsAccordion();
					}
				}, 100);
			} else if(id == "doc") {
				leftRightLayout.cells("a").expand();
				setTimeout(() => {
					if(typeof docAccordion === "function"){
						docAccordion();
					}
				}, 100);
			} else if(id == "technique") {
				leftRightLayout.cells("a").expand();
				setTimeout(() => {
					if(typeof tnpAccordion === "function"){
						tnpAccordion();
					}
				}, 100);
			} else if(id == "other") {
				leftRightLayout.cells("a").expand();
				setTimeout(() => {
					if(typeof otherAccordion === "function"){
						otherAccordion();
					}
				}, 100);
			} else if(id == "project") {
				leftRightLayout.cells("a").expand();
				setTimeout(() => {
					if(typeof projectAccordion === "function"){
						projectAccordion();
						projectManagerTab();
					}
				}, 100);
			}
		});
	}

	function showHolidays(date) {
		let newDate = indoDate(date);
		let spilDate = newDate.split(" ");
		homeLayout.cells("b").setText("Daftar Hari Libur Nasional: " + spilDate[1] + " " + spilDate[2]);

		freeView = homeLayout.cells("b").attachDataView({
			container: "free_container",
			type:{
				template:"<div style='width:100%;display:flex;flex-direction:column;justify-content:space-between,align-items:center'><span style='padding:5px;font-family:sans-serif'><img src='#path#' /> <b>#date#</b></span><div style='padding:5px;font-family:sans-serif'>#description#</div></div>",
				height: 50
			},
			autowidth: 1
		});

		reqJson(App("getHolidaysView"), "POST", {date}, (err, res) => {
			if(res.status === "success") {
				if(res.data.length > 0) {
					res.data.map(data => freeView.add(data));
				}
			}
		});
	}

	setTimeout(() => {
		showHome();
		currDate = new Date();
		showHolidays(currDate);
	}, 500);
JS;

header('Content-Type: application/javascript');
echo $script;

