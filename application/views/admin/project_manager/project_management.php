<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
    		
    function projectManagement(subId, name) {
		var sendSpekta = [];
		var sendEmail = [];
		gantt.clearAll(); 
		if(userLogged.subId != subId) {
			return eaAlert("Akses Error", "Anda bukan karyawan bagian " + name);
		}

		$("#gantt_sub_name").html(name);

		projectToolbar.attachEvent("onClick", function(id) {
			switch (id) {
				case "refresh":
					loadData();
					break;
				case "share":
					sendSpekta = [];
					sendEmail = [];
					var shareWin = createWindow("share_gantt_win", "Bagikan Jadwal Pekerajan", 900, 500);
					myWins.window("share_gantt_win").skipMyCloseEvent = true;

					var shareTabs = shareWin.attachTabbar({
						tabs: [
							{id: "a", text: "S.P.E.K.T.A Chat", active: true},
							{id: "b", text: "Email"},
						]
					});

					var spektaToolbar = shareTabs.cells("a").attachToolbar({
						icon_path: "./public/codebase/icons/",
						items: [
							{id: "send", text: "Kirim", type: "button", img: "send.png"},
						]
					});

					var emailToolbar = shareTabs.cells("b").attachToolbar({
						icon_path: "./public/codebase/icons/",
						items: [
							{id: "send", text: "Kirim", type: "button", img: "send.png"},
						]
					});

					spektaToolbar.attachEvent("onClick", function(id) {
						switch (id) {
							case "send":
								let tasks = {
									subId,
									subName: name,
									divId: $("#gantt_division_id").val(),
									taskId: $("#gantt_task_id").val(),
									month: $("#gantt_month").val(),
									year: $("#gantt_year").val()
								};
								spektaToolbar.disableItem("send");
								reqJson(Project("shareSpekta"), "POST", {ids: sendSpekta, tasks}, (err, res) => {
									if(res.status === "success") {
										sAlert(res.message);
									} else {
										eAlert(res.message);
									}

									spektaGrid.uncheckAll();
									sendSpekta = [];
									spektaToolbar.enableItem("send");
								});
								break;
						}
					});

					emailToolbar.attachEvent("onClick", function(id) {
						switch (id) {
							case "send":
								let tasks = {
									subId,
									subName: name,
									divId: $("#gantt_division_id").val(),
									taskId: $("#gantt_task_id").val(),
									month: $("#gantt_month").val(),
									year: $("#gantt_year").val()
								};
								spektaToolbar.disableItem("send");
								reqJson(Project("shareEmail"), "POST", {ids: sendEmail, tasks}, (err, res) => {
									if(res.status === "success") {
										sAlert(res.message);
									} else {
										eAlert(res.message);
									}

									emailGrid.uncheckAll();
									sendEmail = [];
									spektaToolbar.enableItem("send");
								});
								break;
						}
					});

					let statusSpektaGrid = shareTabs.cells("a").attachStatusBar();
					function countSpektaGrid() {
						statusSpektaGrid.setText("Total baris: " + spektaGrid.getRowsNum());
					}

					shareTabs.cells("a").progressOn();
					var spektaGrid = shareTabs.cells("a").attachGrid();
					spektaGrid.setImagePath("./public/codebase/imgs/");
					spektaGrid.setHeader("No,Check,Nama,Email");
					spektaGrid.attachHeader("#rspan,#rspan,#text_filter,#text_filter")
					spektaGrid.setColAlign("center,left,left,left");
					spektaGrid.setColTypes("rotxt,ch,rotxt,rotxt");
					spektaGrid.setColSorting("int,na,str,str");
					spektaGrid.setInitWidthsP("5,5,45,45");
					spektaGrid.enableSmartRendering(true);
					spektaGrid.attachEvent("onXLE", function() {
						shareTabs.cells("a").progressOff();
					});
					spektaGrid.attachEvent("onCheckbox", function(rId, cIdn, state) {
						if(state) {
							sendSpekta.push(rId);
						} else {
							sendSpekta.splice(sendSpekta.indexOf(rId), 1);
						}
					});
					spektaGrid.init();
					spektaGrid.clearAndLoad(Project("getSpektaChatUser"), countSpektaGrid);

					let statusEmailaGrid = shareTabs.cells("b").attachStatusBar();
					function countEmailGrid() {
						statusEmailaGrid.setText("Total baris: " + emailGrid.getRowsNum());
					}

					shareTabs.cells("b").progressOn();
					var emailGrid = shareTabs.cells("b").attachGrid();
					emailGrid.setImagePath("./public/codebase/imgs/");
					emailGrid.setHeader("No,Check,Nama,Email");
					emailGrid.attachHeader("#rspan,#rspan,#text_filter,#text_filter")
					emailGrid.setColAlign("center,left,left,left");
					emailGrid.setColTypes("rotxt,ch,rotxt,rotxt");
					emailGrid.setColSorting("int,na,str,str");
					emailGrid.setInitWidthsP("5,5,45,45");
					emailGrid.enableSmartRendering(true);
					emailGrid.attachEvent("onXLE", function() {
						shareTabs.cells("b").progressOff();
					});
					emailGrid.attachEvent("onCheckbox", function(rId, cIdn, state) {
						if(state) {
							sendEmail.push(rId);
						} else {
							sendEmail.splice(sendEmail.indexOf(rId), 1);
						}
					});
					emailGrid.init();
					emailGrid.clearAndLoad(Project("getEmpEmail"), countEmailGrid);
					break;
			}
		});

		function loadData() {
			gantt.clearAll(); 
			let divId = $("#gantt_division_id").val();
			let taskId = $("#gantt_task_id").val();
			let month = $("#gantt_month").val();
			let year = $("#gantt_year").val();
			reqJson(Project("loadData"), "POST", {subId, divId, taskId, month, year}, (err, res) => {
				if(res.status === "success") {
					if(res.tasks.data.length > 0) {
						gantt.parse({data: res.tasks.data, links: res.tasks.links});
					}
					$("#gantt_division_id").html(res.division);
					$("#gantt_task_id").html(res.tasksList);
				}
			});
		}

		loadData();

		$("#gantt_division_id").on("change", function() {
			loadData();
		});

		$("#gantt_task_id").on("change", function() {
			loadData();
		});

		$("#gantt_month").on("change", function() {
			loadData();
		});

		$("#gantt_year").on("change", function() {
			loadData();
		});

		var dp = new gantt.dataProcessor(Project("lightboxAction"));
		dp.init(gantt);
		dp.setTransactionMode("JSON");
		
        gantt.attachEvent("onLightboxSave", function(id, task, is_new) {
			dp.sendData;
			return true;
        });

		gantt.attachEvent("onAfterTaskUpdate", function(id,item){
			dp.sendData;
		});
    }
    
JS;

header('Content-Type: application/javascript');
echo $script;
