<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	
    function showFormDet() {	
        var formDet;
        var fileError;
        var totalFile;

        var formDetTabs = mainTab.cells("improve_form_detective").attachTabbar({
            tabs: [
                {id: "a", text: "Form Pengajuan Ide", active: true},
                {id: "b", text: "Daftar Form Yang Sudah Di Ajukan"},
            ]
        });

        reqJson(Emp('getSuperior'), "POST", null, (err, res) => {
            formDet = formDetTabs.cells("a").attachForm([
                {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Form Ide", list:[	
                    {type: "block", list: [
                        {type: "input", name: "npp", label: "NPP", labelWidth: 130, inputWidth: 400, required: true, readonly: true, value: userLogged.empNip},
                        {type: "input", name: "employee_name", label: "Nama Karyawan", labelWidth: 130, inputWidth: 400, required: true, readonly: true, value: userLogged.empName},
                        {type: "input", name: "sub_department", label: "Bagian Yang Di Improve", labelWidth: 130, inputWidth: 400, required: true, readonly: true, value: userLogged.subDepartment},
                        {type: "hidden", name: "superior_nip", label: "Atasan NIP", labelWidth: 130, inputWidth: 400, required: true, readonly: true, value: res.superior_nip},
                        {type: "input", name: "superior_name", label: "Atasan Langsung", labelWidth: 130, inputWidth: 400, required: true, readonly: true, value: res.superior_name},
                        {type: "input", name: "title", label: "Judul Improvement", labelWidth: 130, inputWidth: 400, required: true},
                        {type: "input", name: "current_condition", label: "Kondisi Existing", labelWidth: 130, inputWidth: 400, required: true, rows: 5},
                        {type: "input", name: "expected_condition", label: "Kondisi Harapan (Tujuan)", labelWidth: 130, inputWidth: 400, required: true, rows: 5},
                        {type: "input", name: "planning", label: "Rencana/Langkah Awal Improvement", labelWidth: 130, inputWidth: 400, required: true, rows: 5},
                    ]},
                ]},
                {type: "newcolumn"},
                {type: "fieldset", offsetLeft: 30, offsetTop: 30, label: "Upload Kondisi Existing", list:[	
                    {type: "block", list: [
                        {type: "hidden", name: "before_filename", label: "Filename", readonly: true},
                        {type: "upload", name: "file_uploader", inputWidth: 420,
                            url: AppMaster("fileUpload", {save: false, folder: "improvements"}), 
                            swfPath: "./public/codebase/ext/uploader.swf", 
                            swfUrl: AppMaster("fileUpload")
                        },
                    ]},
                ]},
                {type: "block", offsetLeft: 30, offsetTop: 10, list: [
                    {type: "button", name: "add", className: "button_add", offsetLeft: 15, value: "Tambah"},
                    {type: "newcolumn"},
                    {type: "button", name: "clear", className: "button_clear", offsetLeft: 30, value: "Clear"}
                ]},
            ]);

            formDet.attachEvent("onBeforeFileAdd", async function (filename, size) {
                beforeFileAdd(formDet, {filename, size});
            });

            formDet.attachEvent("onBeforeFileUpload", function(mode, loader, formData){
                if(fileError) {
                    clearUploader(formDet, "file_uploader");
                    eAlert("File error silahkan upload file sesuai ketentuan!");
                    fileError = false;
                } else {
                    return true;
                }
            });

            formDet.attachEvent("onButtonClick", function(id) {
                switch (id) {
                    case "add":
                        const uploader = formDet.getUploader("file_uploader");
                        if(uploader.getStatus() === -1) {
                            if(!fileError) {
                                uploader.upload();
                            } else {
                                uploader.clear();
                                eAlert("File error silahkan upload file sesuai ketentuan!");
                                fileError = false;
                            }
                        } else {
                            addDetFormSubmit();
                        }
                        break;
                    case "clear":
                        clearAllForm(formDet);
                        break;
                }
            });

            formDet.attachEvent("onUploadFile", function(filename, servername){
                formDet.setItemValue("before_filename", servername);
                addDetFormSubmit();
            });

            function addDetFormSubmit() {
                if(!formDet.validate()) {
                    return eAlert("Input error!");
                }

                setDisable(["add", "clear"], formDet, formDetTabs.cells("a"));

                let formDetDP = new dataProcessor(Improve("detectiveForm"));
                formDetDP.init(formDet);
                formDet.save();

                formDetDP.attachEvent("onAfterUpdate", function (id, action, tid, tag) {
                    let message = tag.getAttribute("message");
                    switch (action) {
                        case "inserted":
                            sAlert("Berhasil Menambahkan Record <br>" + message);
                            formDet.setItemValue("title", "");
                            formDet.setItemValue("current_condition", "");
                            formDet.setItemValue("expected_condition", "");
                            formDet.setItemValue("planning", "");
                            clearUploader(formDet, "file_uploader");
                            setEnable(["add", "clear"], formDet, formDetTabs.cells("a"));
                            break;
                        case "error":
                            eAlert("Gagal Menambahkan Record <br>" + message);
                            setEnable(["add", "clear"], formDet, formDetTabs.cells("a"));
                            break;
                    }
                });
            }
        });

        var formListMenu = formDetTabs.cells("b").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "year", text: genSelectYear("det_form_year")},
                {id: "export", text: "Export To Excel", img:"excel.png"},
            ]
        });

        var detGrid =  formDetTabs.cells("b").attachGrid();
        detGrid.setHeader("No,Pemilik Ide,Kategori,Bagian Yang Di Improve,Judul,Kondisi Existing,Kondisi Harapan (Tujuan),Perencanaan,Atasan Langsung,Approval Atasan,Approval Tim Detektif,Status Ide,Created By,Updated By,Dibuat");
        detGrid.attachHeader("#rspan,#text_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter")
        detGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str");
        detGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        detGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        detGrid.setInitWidthsP("5,20,20,20,30,30,30,30,20,20,20,20,20,20,25");
        detGrid.enableSmartRendering(true);
        detGrid.attachEvent("onXLE", function() {
            formDetTabs.cells("b").progressOff();
        });
        detGrid.init();

        function rDetGrid() {
            let year = $("#det_form_year").val();
            formDetTabs.cells("b").progressOn();
            detGrid.clearAndLoad(Improve("getDetIdeas", {
                equal_emp_id: userLogged.empId, 
                year_created_at: year
            }));
        };

        rDetGrid();

        async function beforeFileAdd(form, file, id = null) {
            if(form.validate()) {
                var ext = file.filename.split(".").pop();
                if (ext == "png" || ext == "jpg" || ext == "jpeg") {
                    if (file.size > 1000000) {
                        fileError = true;
                        eAlert("Tidak boleh melebihi 1 MB!");
                    } else {
                        if(totalFile > 0) {
                            eAlert("Maksimal 1 file");
                            fileError = true;
                        } else {
                            const data = id ? {id, title: form.getItemValue("title")} : {title: form.getItemValue("title")}
                            const check = await reqJsonResponse(Improve("checkBeforeAddFileDetForm"), "POST", data);

                            if(check) {
                                if(check.status === "success") {
                                    totalFile++;
                                    return true;
                                } else if(check.status === "deleted") {
                                    fileError = false;
                                    totalFile= 0;
                                } else {
                                    eAlert(check.message);
                                    fileError = true;
                                }
                            }
                        }
                    }		    
                } else {
                    eAlert("Hanya png, jpg & jpeg saja yang bisa diupload!");
                    fileError = true;
                }
            } else {
                eAlert("Input error!");
            }	
        }
    }

JS;
header('Content-Type: application/javascript');
echo $script;