<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

function showEmailSend() {
        var legend = legendGrid();

        var emailLayout = mainTab.cells("am_send_email_tab").attachLayout({
            pattern: "1C",
            cells: [
                {id: "a", header : false}
            ]
        });

        var emailMenu = mainTab.cells("am_send_email_tab").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", img: "refresh.png"},
                {id: "email_send", text: "<span>Kirim Email: <select id='email_send' style='height:22px;margin-top:3px'><option value='enable'>Enable</option><option value='disable'>Disable</option></select></span>"},
                {id: "month", text: genSelectMonth("am_email_year", "am_email_month")},
                {id: "message", text: "Lihat Isi Pesan", img: "email.png"},
                {id: "send", text: "Kirim Ulang <span id='email_loading'></span>", img: "send.png"},
                {id: "delete", text: "Hapus", img: "delete.png"},
            ]
        });

        reqJson(AppMaster2('setEmailStatus'), "GET", null, (err, res) => {
            $("#email_send").val(res.status);
        });

        $("#email_send").on("change", function() {
            reqJson(AppMaster2('enableEmail'), "POST", {status: $("#email_send").val()}, (err, res) => {
                if(res.status === "success") {
                    sAlert(res.message);
                } else {
                    eAlert(res.message);
                }
            });
        });

        emailMenu.attachEvent("onClick", function(id) {
            switch (id) {
                case "send":
                    if(!emailGrid.getSelectedRowId()) {
                        return eAlert("Pilih email terlebih dahulu!");
                    }

                    emailMenu.setItemDisabled("send");
                    $("#email_loading").html(", Mengirim...");
                    reqJson(AppMaster2("emailSend"), "POST", {id: emailGrid.getSelectedRowId()}, (err, res) => {
                        if(res.status === "success") {
                            sAlert(res.message);
                            rEmailGrid();
                        } else {
                            eAlert(res.message);
                        }
                        $("#email_loading").html("");
                        emailMenu.setItemEnabled("send");
                    });
                    break;
                case "refresh":
                    rEmailGrid();
                    break;
                case "message": 
                    if(!emailGrid.getSelectedRowId()) {
                        return eAlert("Pilih email terlebih dahulu!");
                    }
                    let title = emailGrid.cells(emailGrid.getSelectedRowId(), 2).getValue();
                    var emailWin = createWindow("email_msg_win", title, 900,600);
                    myWins.window("email_msg_win").skipMyCloseEvent = true;
                    reqJson(AppMaster2("emailMessage"), "POST", {id: emailGrid.getSelectedRowId()}, (err, res) => {
                        if(res.status === "success") {
                            emailWin.attachHTMLString("<div style='width:100%;height:100%;overflow:auto;overflow-y:scroll;padding:10px;'>" + res.template + "</div>");
                        } else {
                            eAlert("Terjadi Kesalahan");
                            closeWindow("email_msg_win");
                        }
                    });
                    break;
                case "delete":
                    reqAction(emailGrid, AppMaster2("emailDelete"), 2, (err, res) => {
                        rEmailGrid();
                        res.mSuccess && sAlert("Sukses Menghapus Record <br>" + res.mSuccess);
                        res.mError && eAlert("Gagal Menghapus Record <br>" + res.mError);
                    });
                    break;
            }
        });

        $("#am_email_year").on("change", function() {
            rEmailGrid();
        });

        $("#am_email_month").on("change", function() {
            rEmailGrid();
        });

        var emailStatusBar = emailLayout.cells("a").attachStatusBar();
        function emailGridCount() {
            let emailGridRows = emailGrid.getRowsNum();
            emailStatusBar.setText("Total baris: " + emailGridRows + " ( " + legend.email_send + " )");
        }

        var emailGrid =  emailLayout.cells("a").attachGrid();
        emailGrid.setHeader("No,Tipe Email,Subjek,Nama Subjek,Penerima,CC Penerima,Dibuat,Status,Dikirim");
        emailGrid.attachHeader("#rspan,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        emailGrid.setColSorting("str,str,str,str,str,str,str,str,str");
        emailGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        emailGrid.setColAlign("center,left,left,left,left,left,left,left,left");
        emailGrid.setInitWidthsP("5,25,35,35,30,20,25,20,25");
        emailGrid.enableSmartRendering(true);
        emailGrid.enableMultiselect(true);
        emailGrid.attachEvent("onXLE", function() {
            emailLayout.cells("a").progressOff();
        });
        emailGrid.init();

        function rEmailGrid() {
            let month = $("#am_email_month").val();
            let year = $("#am_email_year").val();
            emailGrid.clearAndLoad(AppMaster2("getEmailGrid", {
                month, 
                year}
            ), emailGridCount);
        }

        rEmailGrid();
    }

JS;

header('Content-Type: application/javascript');
echo $script;