<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<<"JS"

    function docAccordion() {
        reqJson(Document("getDepartments"),"GET", null, (err, res) => {
            checkTrees();
            $("#title-menu").html("Dokumen Kontrol");
            accordionItems.map(id => myTree.removeItem(id));
            accordionItems.push("a");
            myTree.addItem("a","Manajemen Dokumen", true);
            var docTree = myTree.cells("a").attachTreeView({
                items: res.depts
            });

            docTree.attachEvent("onClick", function(id) {
                res.depts.map(dept => {
                    dept.items.map(sub => {
                        return id === sub.id && detailDocumentTab(sub)
                    });
                });
            });
        });
    }

JS;

echo $script;