<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

    function detailDocumentTab(sub) {
        if (!mainTab.tabs("document_" + sub.id)){
            mainTab.addTab("document_" + sub.id, tabsStyle("document_48.png", "Dokumen " + sub.text, "background-size: 16px 16px"), null, null, true, true);
            detailDocument(sub.id, sub.text);
        } else {
            mainTab.tabs("document_" + sub.id).setActive();
        }
    }
    
JS;

echo $script;