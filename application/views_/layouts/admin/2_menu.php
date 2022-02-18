<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

require_once APPPATH . "libraries/Common.php";

$script = <<<"JS"

    var mAccountItems = [
        {id: "profile", text: "Profil", img: "personal.png"},
        {id: "change_password", text: "Ganti Password", img: "key.png"}
    ];

    if(userLogged.role === "admin" || userLogged.empNip == "9999") {
        mAccountItems.push({id: "change_location", text: "Ganti Privilage Akun", img: "map_16.png"});
        mAccountItems.push({id: "exit", text: "Keluar", img: "exit.png"});
    } else {
        mAccountItems.push({id: "exit", text: "Keluar", img: "exit.png"});
    }
    
    var myMenu = containerLayout.cells("a").attachMenu({
        icons_path: "./public/codebase/icons/",
        items:[
            {id: "file", text: "Akun " + userLogged.empLoc + " - <b>" + userLogged.rank + " (" + userLogged.subDepartment + ")</b>", img: "userinfo.png", items: mAccountItems},
        ]
    });

    myMenu.setOpenMode("win");

    if(userLogged.role === "admin" || userLogged.empNip == "9999") {
        myMenu.addNewSeparator("change_location", "line_1");
    } else {
        myMenu.addNewSeparator("change_password", "line_1");
    }

    myMenu.attachEvent("onClick", function(id) {
        switch (id) {
            case "exit":
                dhtmlx.modalbox({
                    type: "alert-error",
                    title: "Keluar",
                    text: "Anda yakin ingin keluar ?",
                    buttons: ["Ya", "Tidak"],
                    callback: function(index){
                        if (index == 0) {
                            reqJsonResponse("index.php?c=AuthController&m=exitapp", "GET", null);
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        }
                    }
                });
                break;
            case "profile":
                let winProfile = createWindow('profile', 'Profile', 900, 420);
                myWins.window("profile").skipMyCloseEvent = true;
                winProfile.progressOn();
                reqJson(App("getProfile"), "GET", null, (err, res) => {
                    if(res.status === "success") {
                        winProfile.progressOff();
                        winProfile.attachHTMLString(res.profile);
                    }
                });
                break;
            case "change_password":
                let winChangePassword = createWindow('change_password', 'Ganti Password', 495, 330);
                var cpForm = winChangePassword.attachForm([
                    {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Ganti Password", list: [
                        {type: "hidden", name: "username", label: "Username", readonly: true, value: userLogged.username},
                        {type: "password", name: "old_password", label: "Password Lama", required: true, labelWidth: 130, inputWidth: 250},
                        {type: "password", name: "password", label: "Password Baru", required: true, labelWidth: 130, inputWidth: 250},
                        {type: "password", name: "confirm", label: "Konfirmasi Password Baru", required: true, labelWidth: 130, inputWidth: 250},
                        {type: "block", offsetTop: 30, list: [
                            {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                            {type: "newcolumn"},
                            {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                        ]}
                    ]}
                ]);

                cpForm.attachEvent("onButtonClick", function(name) {
                    switch (name) {
                        case "update":
                            if(!cpForm.validate()) {
                                return eAlert("Input error!");
                            }

                            setDisable(["update", "cancel"], cpForm);

                            var oldPassword = setEscape(cpForm.getItemValue("old_password"));
                            var password = setEscape(cpForm.getItemValue("password"));
                            var confirm = setEscape(cpForm.getItemValue("confirm"));
                           
                            var oldPasswordHash = genPasswordHash(oldPassword);
                            var newPasswordHash = genPasswordHash(password);
                            var confirmPasswordHash = genPasswordHash(confirm);

                            if(newPasswordHash !== confirmPasswordHash) {
                                setEnable(["update", "clear"], cpForm);
                                return eAlert("Password konfirmasi tidak sama!");
                            }

                            cpForm.setItemValue("old_password", oldPasswordHash);
                            cpForm.setItemValue("password", newPasswordHash);
                            cpForm.setItemValue("confirm", confirmPasswordHash);

                            let cpFormDP = new dataProcessor(User("changePassword"));
					        cpFormDP.init(cpForm);
                            cpForm.save();

                            cpFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
                                var message = tag.getAttribute("message");
                                switch (action) {
                                    case "updated":
                                        sAlert(message);
                                        clearAllForm(cpForm);
                                        setEnable(["update", "cancel"], cpForm);
                                        closeWindow("change_password");
                                        break;
                                    case "error":
                                        eAlert(message);
                                        cpForm.setItemValue("old_password", oldPassword);
                                        cpForm.setItemValue("password", password);
                                        cpForm.setItemValue("confirm", confirm);
                                        setEnable(["update", "cancel"], cpForm);
                                        break;
                                }												
                            });
                            break;
                        case "cancel":
                            closeWindow("change_password");
                            break;
                    }
                })
                break;
            case "change_location":
                let winChangeLocation = createWindow('change_location', 'Ganti Privilage Akun',495, 420);
                var clForm = winChangeLocation.attachForm([
                    {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Ganti Privilage Akun", list: [
                        {type: "combo", name: "location", label: "Lokasi Akun", readonly: true, labelWidth: 130, inputWidth: 250, required: true},
                        {type: "combo", name: "rank_id", label: "Jabatan", readonly: true, labelWidth: 130, inputWidth: 250, required: true},
                        {type: "combo", name: "department_id", label: "Sub Unit", readonly: true, labelWidth: 130, inputWidth: 250, required: true},
                        {type: "combo", name: "sub_department_id", label: "Bagian", readonly: true, labelWidth: 130, inputWidth: 250, required: true},
                        {type: "combo", name: "division_id", label: "Divisi", readonly: true, labelWidth: 130, inputWidth: 250, required: true},
                        {type: "combo", name: "role_id", label: "Privilage", readonly: true, labelWidth: 130, inputWidth: 250, required: true},
                        {type: "block", offsetTop: 30, list: [
                            {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                            {type: "newcolumn"},
                            {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"}
                        ]}
                    ]}
                ]);


				var mRoleCombo = clForm.getCombo("role_id");
				mRoleCombo.load(User("getRoles"));

                var clDeptCombo = clForm.getCombo("department_id");
                var clSubDeptCombo = clForm.getCombo("sub_department_id");
                var clDivCombo = clForm.getCombo("division_id");
                var clRankCombo = clForm.getCombo("rank_id");
                var clLocationCombo = clForm.getCombo("location");
                clLocationCombo.load(App("getLocation"));

                clDeptCombo.load(Emp("getDepartment"));
                clDeptCombo.attachEvent("onChange", function(value, text){
                    if(clForm.getItemValue("rank_id") > 2 || clForm.getItemValue("rank_id") == "") {
                        clearComboReload(clForm, "sub_department_id", Emp("getSubDepartment", {deptId: value}));
                    }
                });
                clSubDeptCombo.attachEvent("onChange", function(value, text){
                    if(clForm.getItemValue("rank_id") > 4 || clForm.getItemValue("rank_id") == "") {
                        clearComboReload(clForm, "division_id", Emp("getDivision", {subDeptId: value}));
                    }
                });

                clRankCombo.load(Emp("getRank"));
                clRankCombo.attachEvent("onChange", function(value, text){
                    if(value <= 2) {
                        clDivCombo.clearAll();
                        clDivCombo.addOption([[0, "-"]]);
                        clDivCombo.selectOption(0);
                        clSubDeptCombo.clearAll();
                        clSubDeptCombo.addOption([[0, "-"]]);
                        clSubDeptCombo.selectOption(0);
                    } else if(value <= 4) {
                        clearComboReload(clForm, "sub_department_id", Emp("getSubDepartment", {deptId: clForm.getCombo("department_id")}));
                        clDivCombo.clearAll();
                        clDivCombo.addOption([[0, "-"]]);
                        clDivCombo.selectOption(0);
                    } else {
                        clSubDeptCombo.load(Emp("getSubDepartment", {deptId: clForm.getItemValue("department_id"), select: clForm.getItemValue("sub_department_id")}));
                        clDivCombo.load(Emp("getDivision", {subDeptId: clForm.getItemValue("sub_department_id"), select: clForm.getItemValue("division_id")}));
                    }
                });

                clForm.attachEvent("onButtonClick", function(name) {
                    switch (name) {
                        case "update":
                            if(!clForm.validate()) {
                                return eAlert("Input error!");
                            }

                            setDisable(["update", "cancel"], clForm);
                            let clFormDP = new dataProcessor(User("changePrivilage"));
                            clFormDP.init(clForm);
                            clForm.save();

                            clFormDP.attachEvent("onAfterUpdate", function(id,action,tid,tag) {
                                var message = tag.getAttribute("message");
                                switch (action) {
                                    case "updated":
                                        sAlert(message);
                                        clearAllForm(clForm);
                                        setEnable(["update", "cancel"], clForm);
                                        closeWindow("change_location");
                                        setTimeout(() => {
                                            window.location.reload();
                                        }, 1000);
                                        break;
                                    case "error":
                                        eAlert(message);
                                        setEnable(["update", "cancel"], cpForm);
                                        break;
                                }												
                            });
                            break;
                        case "cancel":
                            closeWindow("change_location");
                            break;
                    }
                })
                break;	
        }
    });

JS;

if ($this->auth->isLogin()) {
    header('Content-Type: application/javascript');
    echo $script;
}