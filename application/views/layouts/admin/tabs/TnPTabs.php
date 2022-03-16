<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}
$script = <<< "JS"

    function inputOvertimeTNPTab(process = null) {
        if (!mainTab.tabs("tnp_input_overtime")){
            if(!userLogged.picOvertime) {
                return eaAlert("Kesalahan Hak Akses", "Anda tidak memiliki hak akses sebagai Admin lemburan!");
            }
            mainTab.addTab("tnp_input_overtime", tabsStyle("clock.png", "Input Lembur (Support)"), null, null, true, true);
            showInputOvertimeTNP(process);
        } else {
            mainTab.tabs("tnp_input_overtime").setActive();
        }
    }

JS;

echo $script;