<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	
	function showUser() {	
		var addUserForm;
		var editUserForm;

		var comboUrl = {
            nip: {
				url: User("getEmps"),
				reload: true
			},
            role_id: {
				url: User("getRoles"),
				reload: true
			}
        }

		var userTabs = mainTab.cells("user").attachTabbar({
			tabs: [
				{
					id: "data",
					text: "Daftar User",
					active: true
				}
			]
		});

		userTabs.setArrowsMode("auto");
		userTabs.enableAutoReSize(true);

		var userToolbarItem = [
			{id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
			{id: "add", text: "Tambah", type: "button", img: "add.png"},
			{id: "edit", text: "Ubah", type: "button", img: "edit.png", img_disabled: "edit_disabled.png"},
			{id: "inactive", text: "Non Aktif", type: "button", img: "block.png"},
		];

		if(userLogged.role === "admin") {
			userToolbarItem.push({id: "delete", text: "Hapus", type: "button", img: "delete.png"});
			userToolbarItem.push({id: "searchtext", text: "Cari : ", type: "text"});
			userToolbarItem.push({id: "search", text: "", type: "buttonInput", width: 150});
		} else {
			userToolbarItem.push({id: "searchtext", text: "Cari : ", type: "text"});
			userToolbarItem.push({id: "search", text: "", type: "buttonInput", width: 150});
		}

		var userToolbar = userTabs.cells("data").attachToolbar({
			icon_path: "./public/codebase/icons/",
			items: userToolbarItem
		});

		userToolbar.attachEvent("onClick", function(id) {
			switch (id) {
				case "refresh":
					userToolbar.setValue("search","");
					loadData();
					break;
				case "add":
					addUserHandler();
					break;
				case "inactive":
					inactiveUserHandler();
					break;
				case "delete":
					deleteUserHandler();
					break;
				case "edit":
					editUserHandler();
					break;
			}
		});

		userToolbar.attachEvent("onEnter", function(id) {
			switch (id) {
				case "search":	
					userGrid.clearAndLoad(User("userGrid", {search: userToolbar.getValue("search")}), userGridCount);
					userGrid.attachEvent("onGridReconstructed", userGridCount);
					break;
			}
		});

		var userStatusBar = userTabs.cells("data").attachStatusBar();
		function userGridCount() {		
			var userGridRows = userGrid.getRowsNum();
			userStatusBar.setText("Total baris: " + userGridRows);
		}

		function loadData() {
			userTabs.cells("data").progressOn();
			userGrid = userTabs.cells("data").attachGrid();
			userGrid.setHeader("No,Nama Karyawan,Department,Jabatan,Username,Privilage,Status,Update Password,Di Buat");
			userGrid.attachHeader("#rspan,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
			userGrid.setColSorting("str,str,str,str,str,str,str,str,str");
			userGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
			userGrid.setColAlign("center,left,left,left,left,left,left,left,left");
			userGrid.setInitWidthsP("5,15,15,15,15,15,15,20,20");
			userGrid.enableSmartRendering(true);
			userGrid.enableMultiselect(true);
			userGrid.init();	
			userGrid.attachEvent("onXLE", function() {
				userTabs.cells("data").progressOff();
			});
			userGrid.load(User("userGrid", {search: userToolbar.getValue("search")}), userGridCount);
		}

		loadData();

		function inactiveUserHandler() {
			if (!userGrid.getSelectedRowId()) {
				eAlert("Pilih baris yang akan dihapus!");
			} else {
				reqAction(userGrid, User("userStatus"), 1, (err, res) => {
					loadData();
					res.mSuccess && sAlert("Sukses Non Aktifkan Record <br>" + res.mSuccess);
					res.mError && eAlert("Gagal Non Aktifkan Record <br>" + res.mError);
				});
			}
		}

		function deleteUserHandler() {
            reqAction(userGrid, User("userDelete"), 1, (err, res) => {
                loadData();
                res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
            });
        }

		function addUserHandler() {
			if (userTabs.tabs("add") === null){
				userTabs.addTab("add", "Tambah User", null, null, true, true);
				addUserForm = userTabs.tabs("add").attachForm([
					{type: "fieldset", offsetLeft: 30, label: "Data User", list:[	
						{type: "block", list: [
							{type: "combo", name: "nip", label: "Nama Karyawan", labelWidth: 130, inputWidth: 250, 
								validate: "NotEmpty", 
								required: true
							},
							{type: "input", name: "username", label: "Username", labelWidth: 130, inputWidth: 250, required: true},
							{type: "password", name: "password", label: "Password", labelWidth: 130, inputWidth: 250, required: true},
							{type: "password", name: "confirm_password", label: "Konfirmasi Password", labelWidth: 130, inputWidth: 250, required: true},
							{type: "combo", name: "role_id", label: "Privilage", labelWidth: 130, inputWidth: 250, 
								readonly: true,
								validate: "NotEmpty", 
								required: true
							},
						]},
						{type: "block", offsetLeft: 30, offsetTop: 10, list: [
							{type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
							{type: "newcolumn"},
							{type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
							{type: "newcolumn"},
							{type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
						]}
					]}
				]);

				var nipCombo = addUserForm.getCombo("nip");
				nipCombo.enableFilteringMode(true, 'nip');
        		nipCombo.attachEvent("onDynXLS", nipComboFilter);

				function nipComboFilter(text){
					nipCombo.clearAll();
					if(text.length > 3) {
						dhx.ajax.get(User('getEmps', {name: text}), function(xml){
							if(xml.xmlDoc.responseText) {
								nipCombo.load(xml.xmlDoc.responseText);
								nipCombo.openSelect();
							}
						});
					}
				};

				var roleCombo = addUserForm.getCombo("role_id");
				roleCombo.load(User("getRoles"));

				addUserForm.attachEvent("onButtonClick", function(name) {
					switch (name) {
						case "add":
							addUserSubmit();
							break;
						case "clear":
							clearAllForm(addUserForm, comboUrl);
							break;
						case "cancel":
							userTabs.tabs("add").close();
							break;
					}
				});
			} else {
				userTabs.tabs("add").setActive();
			}

			function addUserSubmit() {
				if(!addUserForm.validate()) {
					return eAlert("Input error!");
				}

				setDisable(["add", "clear"], addUserForm);
				var addUserFormDP = new dataProcessor(User("userForm"));
				addUserFormDP.init(addUserForm);

				var username = setEscape(addUserForm.getItemValue("username"));
				var password = setEscape(addUserForm.getItemValue("password"));
				var confirm = setEscape(addUserForm.getItemValue("confirm_password"));

				if(password !== confirm) {
					setEnable(["update", "clear"], addUserForm);
					return eAlert("Password tidak sama!");
				}

				addUserForm.save();

				addUserFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
					var message = tag.getAttribute("message");
					switch (action) {
						case "inserted":
							sAlert("Berhasil Menambahkan Record <br>" + message);
							loadData();
							clearAllForm(addUserForm, comboUrl);
							setEnable(["add", "clear"], addUserForm);
							break;
						case "error":
							eAlert("Gagal Menambahkan Record <br>" + message);
							addUserForm.setItemValue("password", "");
							addUserForm.setItemValue("confirm_password", "");
							setEnable(["add", "clear"], addUserForm);
							break;
					}
				});
			}
		}

		function editUserHandler() {
			if (userGrid.getSelectedRowId()) {
				if (userTabs.tabs("edit") == null) {
					userTabs.addTab("edit", "Ubah User", null, null, true, true);
					editEmpForm = userTabs.tabs("edit").attachForm([
						{type: "fieldset", offsetLeft: 30, label: "Data User", list:[	
							{type: "block", list: [
								{type: "hidden", name: "id", required: true, readonly: true},
								{type: "input", name: "nip", label: "Nama Karyawan", labelWidth: 130, inputWidth: 250, required: true, readonly: true},
								{type: "input", name: "username", label: "Username", labelWidth: 130, inputWidth: 250, required: true, readonly: true},
								{type: "combo", name: "role_id", label: "Privilage", labelWidth: 130, inputWidth: 250, 
									readonly: true,
									validate: "NotEmpty", 
									required: true
								},
								{type: "combo", name: "status", label: "Status User", readonly: true, required: true, labelWidth: 130, inputWidth: 250,
									validate: "NotEmpty", 
									options:[
										{value: "", text: ""},
										{value: "ACTIVE", text: "ACTIVE"},
										{value: "INACTIVE", text: "INACTIVE"},
									]
								},
							]}
						]},
						{type: "fieldset", offsetLeft: 30, label: "Ganti Password (Kosongkan jika tidak diubah)", list:[	
							{
								type: "block", list: [
									{type: "password", name: "new_password", label: "Password", labelWidth: 130, inputWidth: 250},
									{type: "password", name: "new_password_confirm", label: "Konfirmasi Password", labelWidth: 130, inputWidth: 250},
								]
							},
							{type: "block", offsetTop: 30, list: [
								{type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
								{type: "newcolumn"},
								{type: "button", name: "cancel", className: "button_no", offsetLeft: 15, value: "Cancel"}
							]}
						]}
					]);

					var editRoleCombo = editEmpForm.getCombo("role_id");
					fetchFormData(User("userForm", {id: userGrid.getSelectedRowId()}), editEmpForm, null, null, setCombo);

					function setCombo() {
						editRoleCombo.load(User("getRoles", {select: editEmpForm.getItemValue("role_id")}));
					}

					var editEmpFormDP = new dataProcessor(User("userForm"));
					editEmpFormDP.init(editEmpForm);

					editEmpForm.attachEvent("onButtonClick", function(name) {
						switch (name) {
							case "update":
								editUserSubmit();
								break;
							case "cancel":
								userTabs.tabs("edit").close();
								break;
						}
					});

					editEmpFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
						var message = tag.getAttribute("message");
						switch (action) {
							case "updated":
								sAlert("Berhasil Mengubah Record <br>" + message);
								loadData();
								editEmpForm.setItemValue("new_password", "");
								editEmpForm.setItemValue("new_password_confirm", "");
								setEnable(["update", "clear"], editEmpForm);
								break;
							case "error":
								eAlert("Gagal Mengubah Record <br>" + message);
								editEmpForm.setItemValue("new_password", "");
								editEmpForm.setItemValue("new_password_confirm", "");
								setEnable(["update", "clear"], editEmpForm);
								break;
						}												
					});

				} else {
					userTabs.tabs("edit").setActive();
					fetchFormData(User("userForm", {id: userGrid.getSelectedRowId()}), editEmpForm);
				}
			} else {
				eAlert("Pilih baris yang akan diubah!");
			}

			function editUserSubmit() {
				if(!editEmpForm.validate()) {
					return eAlert("Input error!");
				}

				setDisable(["update", "clear"], editEmpForm);

				var username = setEscape(editEmpForm.getItemValue("username"));
				var password = setEscape(editEmpForm.getItemValue("new_password"));
				var confirm = setEscape(editEmpForm.getItemValue("new_password_confirm"));

				if(password !== "" && confirm === "" || password === "" && confirm !== "") {
					setEnable(["update", "clear"], editEmpForm);
					return eAlert("Password & konfirmasi password tidak boleh kosong!");
				}

				if(password && confirm) {
					if(password !== confirm) {
						setEnable(["update", "clear"], editEmpForm);
						return eAlert("Password tidak sama!");
					}
				}

				editEmpForm.save();
			}
		}

		userTabs.attachEvent("onTabClose", function(id) {
			switch (id) {
				case "edit":
					if (userTabs.tabs("edit").skipMyCloseEvent) {
						return true;
					} else {	
						addUserForm = null;
						userGrid.clearAndLoad(User("userGrid"), userGridCount);
						userTabs.tabs("edit").close();	
					} 
					break;
				case "add":
					if (userTabs.tabs("add").skipMyCloseEvent) {
						return true;
					} else {
						userTabs.tabs("add").skipMyCloseEvent = true;
						userTabs.tabs("add").close();
					} 
					break;
			}
		});
	}

JS;

header('Content-Type: application/javascript');
echo $script;

