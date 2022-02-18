<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
    
    function detailDocument(subId, name) {	
        var docTree;
        var addFileForm;
        var totalFile = 0;
        var files;
        var main;
        var sub;
        var selectedFilename = null;
        var selectedDocId = null;
        var fileGrid;
        var fileError = false;

        var comboUrl = {
            parent_id: {
                url: Document("getMainFolders", {subId}),
                reload: true
            }
        }

        var docLayout = mainTab.cells("document_" + subId).attachLayout({
            pattern: "3J",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Preview File",
                    header: true,
                    width: 350
                },
                {
                    id: "c",
                    text: "Upload File Baru",
                    header: true,
                    height: 395
                }
            ]
        });

        var docToolbar = docLayout.cells("a").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "add_folder", text: "Tambah Folder", type: "button", img: "folder_closed.png"},
                {id: "rename_folder", text: "Rename Folder", type: "button", img: "folder_closed.png"},
                {id: "add_file", text: "Upload File Baru", type: "button", img: "app22.png"},
            ]
        });

        if((userLogged.rankId <= 6 &&  userLogged.subId == 27) || userLogged.role == "admin") {
            docToolbar.enableItem("add_folder");
            docToolbar.enableItem("rename_folder");
            docToolbar.enableItem("add_file");
        } else {
            docToolbar.disableItem("add_folder");
            docToolbar.disableItem("rename_folder");
            docToolbar.disableItem("add_file");
        }

        var treeLayout = docLayout.cells("a").attachLayout({
            pattern: "2U",
            cells: [
                {
                    id: "a",
                    text: "Daftar Folder",
                    header: true
                },
                {
                    id: "b",
                    text: "Informasi",
                    header: true,
                    width: 350
                }
            ]
        });

        var infoMenu = treeLayout.cells("b").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "revision", text: "Revisi Dokumen", img: "edit.png"},
                {id: "download", text: "Download", img: "download_16.png"},
                {id: "delete", text: "Hapus", img: "delete.png"},
            ]
        });

        var formToolbar = docLayout.cells("c").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "reset", text: "Reset Form", type: "button", img: "messagebox_critical.png"},
            ]
        });

        docToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    loadTree();
                    break;
                case "add_folder": 
                    if(myWins.isWindow("add_folder") === false) {
                        let openedWins = checkMaxOpenWins();
                        if(openedWins < 5) {
                            let folderWins = createWindow("add_folder", "Tambah Folder", 515, 330);

                            let folderTabbar = folderWins.attachTabbar({
                                tabs: [
                                    {id: "a", text: tabsStyle("folder_open.png", "Folder Utama"), active: true},
                                    {id: "b", text: tabsStyle("folder_open.png", "Sub Folder")},
                                ]
                            });

                            var addFolderForm = folderTabbar.cells("a").attachForm([
                                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Folder", list: [
                                    {type: "hidden", name: "sub_department_id", label: "Sub Department", value: subId },
                                    {type: "input", name: "name", label: "Nama Folder", labelWidth: 130, inputWidth:250, required: true},
                                    {type: "block", offsetTop: 30, list: [
                                        {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                                        {type: "newcolumn"},
                                        {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                                    ]}
                                ]}
                            ]);

                            addFolderForm.attachEvent("onButtonClick", function(name) {
                                switch (name) {
                                    case "add":
                                        
                                        if (!addFolderForm.validate()) {
                                            return eAlert("Input error!");
                                        }

                                        setDisable(["add", "clear"], addFolderForm, folderTabbar.cells("a"));
                                        let addFolderFormDP = new dataProcessor(Document("docForm"));
                                        addFolderFormDP.init(addFolderForm);
                                        addFolderForm.save();

                                        addFolderFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                                            let message = tag.getAttribute("message");
                                            switch (action) {
                                                case "inserted":
                                                    sAlert("Berhasil Menambahkan Folder <br>" + message);
                                                    loadTree();
                                                    clearAllForm(addFolderForm, comboUrl);
                                                    clearComboReload(addSubForm, "parent_id", comboUrl["parent_id"].url);
                                                    setEnable(["add", "clear"], addFolderForm, folderTabbar.cells("a"));
                                                    break;
                                                case "error":
                                                    eAlert("Gagal Menambahkan Folder <br>" + message);
                                                    setEnable(["add", "clear"], addFolderForm, folderTabbar.cells("a"));
                                                    break;
                                            }
                                        });
                                        break;
                                    case "clear":
                                        clearAllForm(addFolderForm, comboUrl);
                                        break;
                                }
                            });

                            var addSubForm = folderTabbar.cells("b").attachForm([
                                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Folder", list: [
                                    {type: "combo", name: "parent_id", label: "Folder Utama", readonly: true, required: true, labelWidth: 130, inputWidth: 250},
                                    {type: "input", name: "name", label: "Nama Sub Folder", labelWidth: 130, inputWidth:250, required: true},
                                    {type: "block", offsetTop: 30, list: [
                                        {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                                        {type: "newcolumn"},
                                        {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                                    ]}
                                ]}
                            ]);

                            var addParentCombo = addSubForm.getCombo("parent_id");
                            addParentCombo.load(Document("getMainFolders", {subId}));

                            addSubForm.attachEvent("onButtonClick", function(name) {
                                switch (name) {
                                    case "add":
                                        
                                        if (!addSubForm.validate()) {
                                            return eAlert("Input error!");
                                        }

                                        setDisable(["add", "clear"], addSubForm, folderTabbar.cells("b"));
                                        let addSubFormDP = new dataProcessor(Document("docSubForm"));
                                        addSubFormDP.init(addSubForm);
                                        addSubForm.save();

                                        addSubFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                                            let message = tag.getAttribute("message");
                                            switch (action) {
                                                case "inserted":
                                                    sAlert("Berhasil Menambahkan Sub Folder <br>" + message);
                                                    loadTree();
                                                    clearAllForm(addSubForm, comboUrl);
                                                    setEnable(["add", "clear"], addSubForm, folderTabbar.cells("b"));
                                                    break;
                                                case "error":
                                                    eAlert("Gagal Menambahkan Sub Folder <br>" + message);
                                                    setEnable(["add", "clear"], addSubForm, folderTabbar.cells("b"));
                                                    break;
                                            }
                                        });
                                        break;
                                    case "clear":
                                        clearAllForm(addSubForm, comboUrl);
                                        break;
                                }
                            });

                        } else {
                            aeAlert("Perhatian!", "Terlalu banyak Windows yang dibuka!");
                        }
                    } else {
                        myWins.window("add_folder").bringToTop();
			            myWins.window("add_folder").center();
                    }
                    break;
                case "rename_folder":
                    if(myWins.isWindow("rename_folder") === false) {
                        let openedWins = checkMaxOpenWins();
                        if(openedWins < 5) {
                            if(!selectedDocId) {
                                return eAlert("Pilih folder yang akan diubah!");
                            }

                            let isMain = selectedDocId.includes("main");
                            let isSub = selectedDocId.includes("sub");

                            if(isMain || isSub) {
                                let renameWins = createWindow("rename_folder", "Rename Folder", 510, 300);
                                var renameLayout = renameWins.attachLayout({
                                    pattern: "1C",
                                    cells: [
                                        {id: "a", header: false}
                                    ]
                                });

                                var currentFolder = isMain ? main[selectedDocId] : sub[selectedDocId];

                                var renameForm =  renameLayout.cells("a").attachForm([
                                    {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Rename Folder", list: [
                                        {type: "hidden", name: "id", label: "ID", value: selectedDocId},
                                        {type: "input", name: "name", label: "Nama Folder", labelWidth: 130, inputWidth:250, required: true, value: currentFolder.folder_name},
                                        {type: "block", offsetTop: 30, list: [
                                            {type: "button", name: "update", className: "button_update", offsetLeft: 15, value: "Simpan"},
                                            {type: "newcolumn"},
                                            {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"},
                                        ]}
                                    ]}
                                ]);

                                renameForm.attachEvent("onButtonClick", function(name) {
                                    switch (name) {
                                        case "update":
                                            
                                            if (!renameForm.validate()) {
                                                eAlert("Input error!");
                                            }

                                            setDisable(["update", "cancel"], renameForm, renameLayout.cells("a"));
                                            let renameFormDP = new dataProcessor(Document("renameFolder"));
                                            renameFormDP.init(renameForm);
                                            renameForm.save();

                                            renameFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                                                let message = tag.getAttribute("message");
                                                switch (action) {
                                                    case "updated":
                                                        sAlert("Berhasil Mengubah Record <br>" + message);
                                                        loadTree();
                                                        clearAllForm(renameForm);
                                                        setEnable(["update", "cancel"], renameForm, renameLayout.cells("a"));
                                                        closeWindow("rename_folder");
                                                        break;
                                                    case "error":
                                                        eAlert("Gagal Mengubah Record <br>" + message);
                                                        setEnable(["update", "cancel"], renameForm, renameLayout.cells("a"));
                                                        break;
                                                }
                                            });
                                            break;
                                        case "cancel":
                                            closeWindow("rename_folder");
                                            break;
                                    }
                                });
                            } else {
                                eAlert("Pilih folder yang akan diubah!");
                            }
                        } else {
                            aeAlert("Perhatian!", "Terlalu banyak Windows yang dibuka!");
                        }
                    } else {
                        myWins.window("rename_folder").bringToTop();
			            myWins.window("rename_folder").center();
                    }
                    break;
                case "add_file":
                    docLayout.cells("c").expand();
                    break;
            }
        });

        formToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "reset":
                    reqJson(Document("resetForm"), "GET", null, (err, res) => {
                        if(res.status == "success") {
                            loadTemp();
                        }
                    });
                    break;
            }
        })

        addFileForm = docLayout.cells("c").attachForm([
            {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Tambah Folder", list: [
                {type: "hidden", name: "folder_id", label: "Folder ID", labelWidth: 130, inputWidth:250 },
                {type: "hidden", name: "filename", label: "File Terupload", labelWidth: 130, inputWidth:250 },
                {type: "hidden", name: "subId", label: "File Terupload", labelWidth: 130, inputWidth:250, value: subId },
                {type: "input", name: "folder_name", label: "Target Folder", labelWidth: 130, inputWidth:250, required: true, readonly: true},
                {type: "input", name: "name", label: "Nama Dokumen", labelWidth: 130, inputWidth:250, required: true},
                {type: "calendar", name: "effective_date", label: "Tanggal Efektif", labelWidth: 130, inputWidth:250, required: true},
                {type: "input", name: "revision", label: "Revisi", labelWidth: 130, inputWidth:250, required: true, validate:"ValidNumeric"},
                {type: "upload", name: "file_uploader", inputWidth: 420,
                    url: Document("fileUpload"), 
                    swfPath: "./public/codebase/ext/uploader.swf", 
                    swfUrl: Document("fileUpload"),
                    autoStart: true
                },
                {type: "block", offsetTop: 30, list: [
                    {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                    {type: "newcolumn"},
                    {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"},
                ]}
            ]},
            {type: "newcolumn"},
            {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "File Terupload", list: [
                {type: "container", name : "file_display", label: "<img src='./public/img/no-doc.png' height='120' width='120'>"},
                {type: "container", name : "file_description", label: "<table><tr><td>Nama File</td><td>:</td><td>-</td></tr><tr><td>Tipe</td><td>:</td><td>-</td></tr><tr><td>Ukuran</td><td>:</td><td>-</td></tr></table>"},
            ]},
        ]);

        if(userLogged.role !== "admin") {
            setDisable(["add", "clear"], addFileForm);
        }

        isFormNumeric(addFileForm, ["revision"]);

        addFileForm.attachEvent("onBeforeFileAdd", function(filename, size){
            if(userLogged.role !== "admin") {
                return eAlert("Tidak ada privilage untuk upload!");
            }

            if(!addFileForm.validate()) {
               return eAlert("Input error!");
            }

			var ext = filename.split(".").pop();
			if (ext == "pdf" || ext == "doc" || ext == "docx") {
				if (size > 5000000) {
					eAlert("Tidak boleh melebihi 5 MB!");
				} else {
					if(totalFile > 0) {
						eAlert("Maksimal 1 file!");
					} else {
                        totalFile++;
						return true;
					}
				}					    	
			} else {
				eAlert("Hanya pdf, doc & docx saja yang bisa diupload!");	
			}		
		});

        addFileForm.attachEvent("onUploadFile", function(filename, servername){
            loadTemp();
            totalFile = 0;
		});
        
        function loadTemp() {
            reqJson(Document("tempFile", {action: "document_control"}), "GET", null, (err, res) => {
                if(res.status === "exist") {
                    addFileForm.setItemValue("filename", res.file.filename);
                    let type = res.file.type == "pdf" ? "pdf.png" : "word.png";
                    addFileForm.setItemLabel("file_display", "<img src='./public/img/"+type+"' height='120' width='120'>");
                    addFileForm.setItemLabel("file_description", "<table><tr><td>Nama File</td><td>:</td><td>"+res.file.doc_name+"</td></tr><tr><td>Tipe</td><td>:</td><td>"+res.file.type+"</td></tr><tr><td>Ukuran</td><td>:</td><td>"+res.file.size+"</td></tr></table>");
                    clearUploader(addFileForm, "file_uploader");
                    addFileForm.hideItem("file_uploader");
                } else {
                    addFileForm.setItemValue("filename", "");
                    addFileForm.setItemValue("folder_name", "");
                    addFileForm.setItemValue("name", "");
                    addFileForm.setItemLabel("file_display", "<img src='./public/img/no-doc.png' height='120' width='120'>");
                    addFileForm.setItemLabel("file_description", "<table><tr><td>Nama File</td><td>:</td><td>-</td></tr><tr><td>Tipe</td><td>:</td><td>-</td></tr><tr><td>Ukuran</td><td>:</td><td>-</td></tr></table>");
                    addFileForm.showItem("file_uploader");
                }
            });
        }

        var treeStatusBar = treeLayout.cells("a").attachStatusBar();
        
        function loadTree() {
            
            treeLayout.cells("a").progressOn();
            const tree = reqJsonResponse(Document("getTrees", {subId}), "GET", null);

            docTree = treeLayout.cells("a").attachTreeView({
                items: tree.folders
            });

            let status = tree.isFull ? "<span style='display:flex;justify-content:space-between;align-items:center;color:red'>Total Memory Terpakai: "+ tree.totalSize +" <img width='16' height='16' src='./public/codebase/icons/trash.png' /></span>" : "<span>Total Memory Terpakai: "+ tree.totalSize;
            treeStatusBar.setText(status);
            
            files = tree.files;
            main = tree.main;
            sub = tree.sub;

            docTree.attachEvent("onClick", function(id) {
                let name = docTree.getItemText(id);
                let mFile = id.includes("mfile");
                let sFile = id.includes("sfile");
                
                let isMain = id.includes("main");
                let isSub = id.includes("sub");

                if(mFile || sFile) {
                    let file = files[id];
                    if(file.type === "pdf") {
                        docLayout.cells("b").attachURL(fileUrl(file.filename));
                        showDetail("file", file);
                    } else {
                        noPreview();
                        showDetail("file", file);
                    }
                } else if(isMain || isSub) {
                    noPreview();
                    if(isMain) {
                        showDetail("folder", main[id]);
                    } else {
                        showDetail("folder", sub[id]);
                    }
                    disableMenu();
                    infoMenu.setItemEnabled("delete");
                    if(isMain) {
                        addFileForm.setItemValue("folder_id", id);
                        addFileForm.setItemValue("folder_name", name);
                    } else {
                        addFileForm.setItemValue("folder_id", id);
                        addFileForm.setItemValue("folder_name", name);
                    }
                }
            });
            treeLayout.cells("a").progressOff();
        }

        function showDetail(type, detail = null) {
            if(type === "file") {
                if(!detail) {
                    disableMenu();
                    treeLayout.cells("b").attachHTMLString("<div class='fwm_container'><div class='fwu_container'><div class='fw_img'><img width='70' height='70' src='./public/img/no-doc.png' /></div><div class='fw_desc'><table><tr><td>Nama</td><td>:</td><td>-</td></tr><tr><td>Tipe</td><td>:</td><td>-</td></tr><tr><td>Ukuran</td><td>:</td><td>-</td></tr><tr><td>Revisi</td><td>:</td><td>-</td></tr><tr><td>Efektif</td><td>:</td><td>-</td></tr><tr><td>Created By</td><td>:</td><td>-</td></tr><tr><td>Updated By</td><td>:</td><td>-</td></tr></table></div></div><div class='fwd_container'><div class='fwd_desc_2'><table><tr><td>Dibuat Tanggal</td><td>:</td><td>-</td></tr><tr><td>Diupdate Tanggal</td><td>:</td><td>-</td></tr></table></div></div></div>");
                } else {
                    enableMenu();
                    selectedFilename = detail.filename;
                    selectedDocId = detail.id;
                    let icon = detail.type === "pdf" ? "pdf.png" : "word.png";
                    treeLayout.cells("b").attachHTMLString("<div class='fwm_container'><div class='fwu_container'><div class='fw_img'><img width='70' height='70' src='./public/img/"+icon+"' /></div><div class='fw_desc'><table><tr><td>Nama</td><td>:</td><td>"+detail.name+"</td></tr><tr><td>Tipe</td><td>:</td><td>"+detail.type+"</td></tr><tr><td>Ukuran</td><td>:</td><td>"+detail.size+"</td></tr><tr><td>Revisi</td><td>:</td><td>"+detail.revision+"</td></tr><tr><td>Efektif</td><td>:</td><td>"+detail.effective_date+"</td></tr><tr><td>Created By</td><td>:</td><td>"+detail.created_by+"</td></tr><tr><td>Updated By</td><td>:</td><td>"+detail.updated_by+"</td></tr></table></div></div><div class='fwd_container'><div class='fwd_desc_2'><table><tr><td>Dibuat Tanggal</td><td>:</td><td>"+detail.created_at+"</td></tr><tr><td>Diupdate Tanggal</td><td>:</td><td>"+detail.updated_at+"</td></tr></table></div></div></div>");
                }
            } else {
                selectedFilename = null;
                disableMenu();
                if(!detail) {
                    treeLayout.cells("b").attachHTMLString("<div class='fwm_container'><div class='fwu_container'><div class='fw_img'><img width='70' height='70' src='./public/img/folder.png' /></div><div class='fw_desc'><table><tr><td>Nama</td><td>:</td><td>-</td></tr><tr><td>Sub Folder</td><td>:</td><td>-</td></tr><tr><td>Total File</td><td>:</td><td>-</td></tr><tr><td>Ukuran</td><td>:</td><td>-</td></tr><tr><td>Created By</td><td>:</td><td>-</td></tr><tr><td>Updated By</td><td>:</td><td>-</td></tr></table></div></div><div class='fwd_container'><div class='fwd_desc_2'><table><tr><td>Dibuat Tanggal</td><td>:</td><td>-</td></tr><tr><td>Diupdate Tanggal</td><td>:</td><td>-</td></tr></table></div></div></div>");
                } else {
                    selectedDocId = detail.id;
                    treeLayout.cells("b").attachHTMLString("<div class='fwm_container'><div class='fwu_container'><div class='fw_img'><img width='70' height='70' src='./public/img/folder.png' /></div><div class='fw_desc'><table><tr><td>Nama</td><td>:</td><td>"+detail.folder_name+"</td></tr><tr><td>Sub Folder</td><td>:</td><td>"+detail.sub_folder+"</td></tr><tr><td>Total File</td><td>:</td><td>"+detail.total_file+"</td></tr><tr><td>Ukuran</td><td>:</td><td>"+detail.total_size+"</td></tr><tr><td>Created By</td><td>:</td><td>"+detail.created_by+"</td></tr><tr><td>Updated By</td><td>:</td><td>"+detail.updated_by+"</td></tr></table></div></div><div class='fwd_container'><div class='fwd_desc_2'><table><tr><td>Dibuat Tanggal</td><td>:</td><td>"+detail.created_at+"</td></tr><tr><td>Diupdate Tanggal</td><td>:</td><td>"+detail.updated_at+"</td></tr></table></div></div></div>");
                }
            }
        }

        function noPreview() {
            docLayout.cells("b").attachHTMLString("<div class='preview_container'><img style='opacity: 0.1' src='./public/img/preview.png' /></div>");
        }

        function enableMenu() {
            if ((userLogged.rankId <= 6 && userLogged.subId == 27) || userLogged.role === "admin") {
                infoMenu.setItemEnabled("revision");
                infoMenu.setItemEnabled("delete");
            }
            infoMenu.setItemEnabled("download");
        }

        function disableMenu() {
            infoMenu.setItemDisabled("revision");
            infoMenu.setItemDisabled("download");
            infoMenu.setItemDisabled("delete");
        }

        infoMenu.attachEvent("onClick", function(id) {
            switch (id) {
                case "revision":
                    if(myWins.isWindow("revision") === false) {
                        let openedWins = checkMaxOpenWins();
                        if(openedWins < 5) {
                            let revisionWins = createWindow("revision", "Revisi Dokumen");
                            let revisionTabbar = revisionWins.attachTabbar({
                                tabs: [
                                    {id: "a", text: tabsStyle("edit.png", "Upload Dokumen Revisi"), active: true},
                                    {id: "b", text: tabsStyle("edit.png", "Riwayat Revisi")},
                                ]
                            });

                            let revHistToolbar = revisionTabbar.cells("b").attachToolbar({
                                icon_path: "./public/codebase/icons/",
                                items: [
                                    {id: "download", type: "button", text: "Download", img: "download_16.png"},
                                    {id: "delete", type: "button", text: "Hapus", img: "delete.png"},
                                ]
                            });

                            if(userLogged.role !== "admin") {
                                revHistToolbar.disableItem("delete");
                            }

                            revHistToolbar.attachEvent("onClick", function(id) {
                                switch (id) {
                                    case "download":
                                        if(!fileGrid.getSelectedRowId()) {
                                            eAlert("Belum ada file yang dipilih!");
                                        } else {
                                            toDownload(fileUrl(fileGrid.cells(fileGrid.getSelectedRowId(), 2).getValue()));
                                        }
                                        break;
                                    case "delete":
                                        if(!fileGrid.getSelectedRowId()) {
                                            eAlert("Belum ada file yang dipilih!");
                                        } else {
                                            dhtmlx.modalbox({
                                                type: "alert-error",
                                                title: "Konfirmasi",
                                                text: "Proses ini tidak dapat dibatalkan, apakah anda yakin ingin menghapus file & revisinya?",
                                                buttons: ["Ya", "Tidak"],
                                                callback: function (index) {
                                                    if (index == 0) {
                                                        reqJson(Document("deleteRevision"), "POST", {id: fileGrid.getSelectedRowId()}, (err, res) => {
                                                            if(!err) {
                                                                if(res.status === "success") {
                                                                    loadFileGrid();
                                                                    sAlert(res.message);
                                                                } else {
                                                                    eAlert(res.message);
                                                                }
                                                            } else {
                                                                eAlert("Hapus file gagal!");
                                                            }
                                                        });
                                                    }
                                                },
                                            });
                                        }
                                        break;
                                }
                            });

                            function loadFileGrid() {
                                
                                revisionTabbar.cells("b").progressOn();
                                fileGrid = revisionTabbar.cells("b").attachGrid();
                                fileGrid.setHeader("No,Nama Dokumen,Nama File,Tipe,Size,Remark,Revisi,DiRevisi Oleh,Tanggal Rrevisi");
                                fileGrid.setColSorting("int,str,str,str,str,str,str,str,str");
                                fileGrid.setColAlign("center,left,left,left,left,left,left,left,left");
                                fileGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
                                fileGrid.setInitWidthsP("5,25,25,10,15,35,15,15,15");
                                fileGrid.enableMultiselect(true);
                                fileGrid.enableSmartRendering(true);
                                fileGrid.attachEvent("onXLE", function() {
                                    revisionTabbar.cells("b").progressOff();
                                });
                                fileGrid.init();
                                fileGrid.clearAndLoad(Document("fileGrid", {fileId: selectedDocId}));
                            }

                            loadFileGrid();

                            var currentFile = files[selectedDocId];

                            var addRevisionForm = revisionTabbar.cells("a").attachForm([
                                {type: "fieldset", offsetTop: 30, offsetLeft: 30, label: "Upload Dokumen Baru", list: [
                                    {type: "hidden", name: "id", label: "ID", value: currentFile.id },
                                    {type: "hidden", name: "filename", label: "Filename" },
                                    {type: "input", name: "name", label: "Nama Dokumen", labelWidth: 130, inputWidth:250, required: true, value: currentFile.name},
                                    {type: "calendar", name: "effective_date", label: "Tanggal Efektif", labelWidth: 130, inputWidth:250, required: true, value: globalDate},
                                    {type: "input", name: "revision", label: "Revisi", labelWidth: 130, inputWidth:250, required: true, readonly: true, validate:"ValidNumeric", value: parseInt(currentFile.revision) + 1},
                                    {type: "input", name: "remark", label: "Remark", labelWidth: 130, inputWidth:250, required: true, rows: 3},
                                    {type: "upload", name: "file_uploader", inputWidth: 420,
                                        url: Document("fileUpload"), 
                                        swfPath: "./public/codebase/ext/uploader.swf", 
                                        swfUrl: Document("fileUpload")
                                    }
                                ]},
                                {type: "block", offsetTop: 30, list: [
                                    {type: "button", name: "save", className: "button_update", offsetLeft: 15, value: "Simpan"},
                                    {type: "newcolumn"},
                                    {type: "button", name: "cancel", className: "button_no", offsetLeft: 30, value: "Cancel"},
                                ]}
                            ]);

                            addRevisionForm.attachEvent("onBeforeFileAdd", async function (filename, size) {
                                if(addRevisionForm.validate()) {
                                    var ext = filename.split(".").pop();
                                    if (ext == "pdf" || ext == "doc" || ext == "docx") {
                                        if (size > 5000000) {
                                            fileError = true;
                                            eAlert("Tidak boleh melebihi 5 MB!");
                                        } else {
                                            if(totalFile > 0) {
                                                eAlert("Maksimal 1 file");
                                                fileError = true;
                                            } else {
                                                const checkName = await reqJsonResponse(Document("checkBeforeRevision"), "POST", {
                                                    id: addRevisionForm.getItemValue("id"),
                                                    name: addRevisionForm.getItemValue("name")
                                                });

                                                if(checkName) {
                                                    if(checkName.status === "success") {
                                                        totalFile++;
                                                        return true;
                                                    } else {
                                                        eAlert(checkName.message);
                                                        fileError = true;
                                                    }
                                                }
                                            }
                                        }		    
                                    } else {
                                        eAlert("Hanya pdf, doc & docx saja yang bisa diupload!");
                                        fileError = true;
                                    }
                                } else {
                                    eAlert("Input error!");
                                    setTimeout(() => {
                                        clearUploader(addRevisionForm, "file_uploader");
                                    }, 200);
                                }		
                            });

                            addRevisionForm.attachEvent("onBeforeFileUpload", function(mode, loader, formData){
                                
                                if(fileError) {
                                    clearUploader(addRevisionForm, "file_uploader");
                                    eAlert("File error silahkan upload file sesuai ketentuan!");
                                    fileError = false;
                                } else {
                                    return true;
                                }
                            });

                            addRevisionForm.attachEvent("onButtonClick", function(id) {
                                switch (id) {
                                    case "save":
                                        
                                        const uploader = addRevisionForm.getUploader("file_uploader");
                                        if(uploader.getStatus() === -1) {
                                            if(!fileError) {
                                                uploader.upload();
                                            } else {
                                                uploader.clear();
                                                eAlert("File error silahkan upload file sesuai ketentuan!");
                                                fileError = false;
                                            }
                                        } else {
                                            eAlert("Silahkah pilih file terlebih dahulu!");
                                        }
                                        break;
                                    case "cancel":
                                        closeWindow("revision");
                                        break;
                                }
                            });

                            addRevisionForm.attachEvent("onUploadFile", function(filename, servername){
                                addRevisionForm.setItemValue("filename", servername);
                                setDisable(["save", "cancel"], addRevisionForm, revisionTabbar.cells("a"));

                                let addRevisionFormDP = new dataProcessor(Document("revisionFile"));
                                addRevisionFormDP.init(addRevisionForm);
                                addRevisionForm.save();

                                addRevisionFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                                    let message = tag.getAttribute("message");
                                    switch (action) {
                                        case "inserted":
                                            sAlert("Berhasil Menambahkan File Revisi <br>" + message);
                                            loadTree();
                                            showDetail("file");
                                            noPreview();
                                            disableMenu();
                                            loadFileGrid();
                                            addRevisionForm.setItemValue("remark", "");
                                            addRevisionForm.setItemValue("filename", "");
                                            addRevisionForm.setItemValue("revision", parseInt(addRevisionForm.getItemValue("revision")) + 1);
                                            clearUploader(addRevisionForm, "file_uploader");
                                            setEnable(["save", "cancel"], addRevisionForm, revisionTabbar.cells("a"));
                                            totalFile = 0;
                                            fileError = false;
                                            break;
                                        case "full":
                                        case "error":
                                            eAlert("Gagal Menambahkan File Revisi <br>" + message);
                                            setEnable(["save", "cancel"], addRevisionForm, revisionTabbar.cells("a"));
                                            break;
                                    }
                                });
                            });
                        } else {
                            aeAlert("Perhatian!", "Terlalu banyak Windows yang dibuka!");
                        }
                    } else {
                        myWins.window("revision").bringToTop();
			            myWins.window("revision").center();
                    }
                    break;
                case "download":
                    if(selectedFilename) {
                        toDownload(fileUrl(selectedFilename));
                    } else {
                        eAlert("Belum ada file yang dipilih!");
                    }
                    break;
                case "delete":
                    
                    if(selectedDocId) {
                        dhtmlx.modalbox({
                            type: "alert-error",
                            title: "Konfirmasi",
                            text: "Proses ini tidak dapat dibatalkan, apakah anda yakin ingin menghapus folder tersebut?",
                            buttons: ["Ya", "Tidak"],
                            callback: function (index) {
                                if (index == 0) {
                                    reqJson(Document("deleteDoc"), "POST", {id: selectedDocId}, (err, res) => {
                                        if(!err) {
                                            if(res.status === "success") {
                                                loadTree();
                                                noPreview();
                                                showDetail("file");
                                                selectedDocId = null;
                                                sAlert(res.message);
                                            } else {
                                                eAlert(res.message);
                                            }
                                        } else {
                                            eAlert("Hapus file gagal!");
                                        }
                                    });
                                }
                            },
                        });
                    } else {
                        eAlert("Belum ada file yang dipilih!");
                    }
                    break;
            }
        });
        
        addFileForm.attachEvent("onButtonClick", function(id) {
            switch (id) {
                case "add":
                    
                    if (!addFileForm.validate()) {
                        return eAlert("Input error!");
                    }

                    if(addFileForm.getItemValue("filename") === "") {
                        return eAlert("Belum ada file yang diupload!");
                    }

                    setDisable(["add", "clear"], addFileForm, docLayout.cells("c"));
                    let addFileFormDP = new dataProcessor(Document("createFile"));
                    addFileFormDP.init(addFileForm);
                    addFileForm.save();

                    addFileFormDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                        let message = tag.getAttribute("message");
                        switch (action) {
                            case "inserted":
                                sAlert("Berhasil Menambahkan File <br>" + message);
                                loadTemp();
                                loadTree();
                                clearAllForm(addFileForm);
                                setEnable(["add", "clear"], addFileForm, docLayout.cells("c"));
                                totalFile = 0;
                                break;
                            case "full":
                                reqJson(Document("resetForm"), "GET", null, (err, res) => {
                                    if(res.status == "success") {
                                        loadTemp();
                                    }
                                });
                                clearAllForm(addFileForm);
                            case "error":
                                eAlert("Gagal Menambahkan File <br>" + message);
                                setEnable(["add", "clear"], addFileForm, docLayout.cells("c"));
                        }
                    });
                    break;
                case "clear":
                    addFileForm.setItemValue("folder_name", "");
                    addFileForm.setItemValue("name", "");
                    break;
            }
        });

        loadTree();
        showDetail("file");
        noPreview();
        loadTemp();
        disableMenu();

        setTimeout(() => {
            docLayout.cells("c").collapse();
        }, 1000)
    }

JS;

header('Content-Type: application/javascript');
echo $script;
