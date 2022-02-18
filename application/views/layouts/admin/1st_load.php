<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
$script = <<<"JS"
	var containerLayout;
	var myWins;
	var mainTab;
	var mainMenu;
	var fotoFromGrid;
	var currDate;
	
	menuWidth = 265;
	contentWidth = screen.width - 265;
	windowHeight = window.innerHeight;
	accordionItems = [];
	
	window.dhx4.attachEvent("onLoadXMLError", function (request,obj){
		dhtmlx.message({
			type: "error",
			text: "Error "+ request +"<br />Please Check If your login Session expired<br />Click <a href='index.php'>HERE</a> to reload",
			expire: -1,
		})
	});

	function afterLogin() {
		containerLayout = new dhtmlXLayoutObject({
			parent: document.body,
			pattern: "1C",
			cells: [
				{
					id: "a",
					header: false
				}
			]
		})

		leftRightLayout = containerLayout.cells("a").attachLayout({
			pattern : "2U",
			cells: [
				{
					id: "a",
					text: "<span id='title-menu'>"+ toDay +"</span>",
					header: true,
					width: menuWidth
				},
				{
					id: "b"
				}
			]
		});

		leftRightLayout.cells("a").fixSize(true, true);

		leftRightLayout.attachEvent("onCollapse", function(id) {
			if(id == "a") {
				contentWidth += 227;
				showHome();
				showHolidays(currDate);
			}
		});

		leftRightLayout.attachEvent("onExpand", function(id) {
			if(id == "a") {
				contentWidth -= 227;
				showHome();
				showHolidays(currDate);
			}
		});

		let infoText = "<span style='font-size: 12px'> <img src='public/codebase/icons/hrd.png' width='16' height='16' /> "+userLogged.empName+'</span>';
		infoText = infoText + "<span style='font-size: 12px'> <img src='public/codebase/icons/puzzle_16.png' width='16' height='16' /> "+userLogged.subDepartment+"</span>";
		infoText = infoText + "<span style='font-size: 12px'> <img src='public/codebase/icons/medal.png' width='16' height='16' /> "+userLogged.rank+"</span>";
		infoText = infoText + "<span style='font-size: 12px'> <img src='public/codebase/icons/map_16.png' width='16' height='16' /> "+userLogged.empLoc+"</span>";
		var userInfo = infoText;

		sidebar = leftRightLayout.cells("a").attachForm([
			{
				type: "container", 
				name: "info", 
				id: "calendar-info",
				inputWidth: 120, 
				inputHeight: 200
			},
			{type: "block", name: "user", offsetTop: 85, list: [
				{type: "label", labelWidth: menuWidth * 0.77, label: "<div class='info-container'><div class='info-text'>"+userInfo+"</div></div>"},
				{type: "label", labelWidth: menuWidth * 0.72, label: "<div class='info-version'><div><p>E-KFPJ v.1.0.0</p><ul><li>Under Development</li></ul</div></div>"}
			]},
			{
				type: "container", 
				name: "trees", 
				id: "trees", 
				inputWidth: menuWidth * 0.965, 
				inputHeight: windowHeight * 0.90
			},
		]);

		var myCalendar = new dhtmlXCalendarObject(sidebar.getContainer("info"));
		myCalendar.hideTime();
		myCalendar.show();
		myCalendar.setPosition(5, 5);
		
		reqJson(App("getHolidays"), "GET", null, (err, res) => {
			if(res.status === "success") {
				myCalendar.setHolidays(res.holidays); 
			}
		});
		
		myCalendar.showToday();

		myCalendar.attachEvent("onClick", function(date){
			currDate = date;
			showHolidays(date);
		});

		sidebar.setItemValue("username", userLogged.username);
		sidebar.setItemValue("role", userLogged.role);

		myTree = new dhtmlXAccordion(sidebar.getContainer("trees"));

		sidebar.hideItem("trees");

		mainTab = leftRightLayout.cells("b").attachTabbar({
			tabs: [
				{
					id: "home",
					text: tabsStyle("home.png", "Home"),
					active: true
				}
			],
		});

		mainTab.enableAutoReSize(true);
		mainTab.setArrowsMode("auto");

		mainTab.attachEvent("onTabClose", function(id){
			if (mainTab.tabs(id).skipMyCloseEvent) {
				return true;
			} else {
				dhtmlx.confirm({
					type: "confirm-error",
					title: "Konfirmasi",
					ok: "Ya", cancel: "Tidak",
					text: "Anda yakin ingin menutup Tab ini ?",
					callback: function(result){
						if (result == true) {
							mainTab.tabs(id).skipMyCloseEvent = true
							mainTab.tabs(id).close();
						} else
							return false;
					}
				})
			}
		});

		myWins = new dhtmlXWindows();
		myWins.attachViewportTo(document.body);

		myWins.attachEvent("onClose", function(myWins,id){
			if (myWins.skipMyCloseEvent) {
				return true;
			} else {
				dhtmlx.confirm({
					type: "confirm-error",
					title: "Konfirmasi",
					ok: "Ya", cancel: "Tidak",
					text: "Anda yakin ingin menutup Window ini ?",
					callback: function(result){
						if (result == true) {
							myWins.skipMyCloseEvent = true;
							myWins.close();
						} else
							return false;
					}
				})
			}
		});
	}

	function selectTheme(title){
		totalLink = 0;
		var links = document.getElementsByTagName("link");
		for (var i = 0;i < links.length; i++){
			var linkTitle = links[i].getAttribute("title");
			if(linkTitle === "skyblue" || linkTitle === "rose" || linkTitle === "salad" || linkTitle === "cloud") {
				totalLink++;
				links[i].disabled = (links[i].getAttribute("title") != title);
			}
		}
		localStorage.setItem("themes", title);
	}

	function checkMaxOpenWins(){
		var winsCount = 0;
		myWins.forEachWindow(function(){winsCount++;});
		return winsCount;
	};

	function checkMaxOpenTabs(){
		var tabsCount = 0;
		mainTab.forEachTab(function(){tabsCount++;});
		return tabsCount;
	};

afterLogin();

JS;

if ($this->auth->isLogin()) {
    header('Content-Type: application/javascript');
    echo $script;
}
