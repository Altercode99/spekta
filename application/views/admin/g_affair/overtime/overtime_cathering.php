<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	function showOvertimeCathering() {
        var ovtCathLayout =  mainTab.cells("ga_lembur_katering").attachLayout({
           pattern: "1C",
           cells: [
               {id: "a", text: "Kebutuhan Katering Lembur"}
           ]
        });

        ovtCathLayout.cells("a").attachHTMLString("<div style='width:100%;height:100%;align-items:center;justify-content:center;display:flex;'>Dalam Proses Pengembangan</div>")
    }

JS;

header('Content-Type: application/javascript');
echo $script;
