<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}
$script = <<< "JS"

    function requestOvertimeTab() {
        if (!mainTab.tabs("tnp_overtime_request")){
            mainTab.addTab("tnp_overtime_request", tabsStyle("clock.png", "Request Lembur Produksi"), null, null, true, true);
            showRequestOvertime();
        } else {
            mainTab.tabs("tnp_overtime_request").setActive();
        }
    }

    function inputOvertimeTNPTab() {
        if (!mainTab.tabs("tnp_input_overtime")){
            mainTab.addTab("tnp_input_overtime", tabsStyle("clock.png", "Input Lembur Teknik"), null, null, true, true);
            showInputOvertimeTNP();
        } else {
            mainTab.tabs("tnp_input_overtime").setActive();
        }
    }

JS;

echo $script;