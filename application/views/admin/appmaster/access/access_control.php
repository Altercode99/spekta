<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"

	function showAccessControl(subId) {
        var selectedRank;
        var selectedMenu;
        var selectedAcc;

        var treeRankView;
        var treeMenuView;
        var treeAccordionView;
        var treeAccTreeView;

        var mLayout = mainTab.cells("access_control_" + subId).attachLayout({
            pattern: "4T",
            cells: [{
                    id: "a",
                    text: "Jabatan",
                    header: true
                },
                {
                    id: "b",
                    text: "Menu Utama",
                    header: true
                },
                {
                    id: "c",
                    text: "Accordions",
                    header: true
                },
                {
                    id: "d",
                    text: "Trees",
                    header: true
                }
            ]
        });

        var treeToolbar = mLayout.cells("d").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "save", text: "Update Akses Tree", type: "button", img: "update.png"},
            ]
        });

        async function treesRank() {
            const treeRank = await reqJsonResponse(Access("getRanks"), "GET", null);
            treeRankView = mLayout.cells("a").attachTreeView({
                items: treeRank.items
            });

            treeRankView.attachEvent("onClick", function(id) {
                selectedRank = id.replace("rank-", "");
                treesMenu(selectedRank);
                treesAccordion(null);
                treesAccTree(null);
            });
        }

        async function treesMenu(rankId) {
            if(rankId) {
                const treeMenu = await reqJsonResponse(Access("getMenus", {subId, rankId: selectedRank}), "GET", null);
                treeMenuView = mLayout.cells("b").attachTreeView({
                    checkboxes: true,
                    items: treeMenu.items
                });

                treeMenuView.attachEvent("onClick", function(id){
                    let menuId = id.replace("menu-", "");
                    selectedMenu = menuId;
                    treesAccordion(menuId);
                    treesAccTree(null);
                });

                treeMenuView.attachEvent("onCheck", function(id, state){
                    let menuId = id.replace("menu-", "");
                    reqJson(Access("updateMenus"), "POST", {
                        subId,
                        rankId: selectedRank,
                        menuId,
                        status: state ? "ACTIVE" : "INACTIVE"
                    }, (err, res) => {
                        if (res.status === "success") {
                            sAlert(res.message);
                        } else {
                            eAlert("Update akses kontrol gagal!");
                        }
                    });
                });
            } else {
                treeMenuView = mLayout.cells("b").attachTreeView({
                    checkboxes: true,
                    items: []
                });
            }
            
        }
        
        var inactiveAcc = false;
        async function treesAccordion(menuId) {
            if(menuId) {
                const treeAccordion = await reqJsonResponse(Access("getAccordions", {subId, rankId: selectedRank, menuId}), "GET", null);
                treeAccordionView = mLayout.cells("c").attachTreeView({
                    checkboxes: true,
                    items: treeAccordion.items
                });

                treeAccordionView.attachEvent("onClick", function(code){
                    selectedAcc = code;
                    treesAccTree(code);
                });

                treeAccordionView.attachEvent("onCheck", function (code, state) {
                    if(!inactiveAcc) {
                        reqJson(Access("updateAccordions"), "POST", {
                            subId,
                            rankId: selectedRank,
                            menuId: selectedMenu,
                            accCode: code,
                            status: state ? "ACTIVE" : "INACTIVE"
                        }, (err, res) => {
                            if (res.status === "success") {
                                sAlert(res.message);
                            } else {
                                inactiveAcc = true;
                                setTimeout(() => {
                                    treeAccordionView.uncheckItem(code);
                                    eAlert(res.message);
                                }, 200);
                            }
                        });
                    } else {
                        inactiveAcc = false;
                    }
                });
            } else {
                treeAccordionView = mLayout.cells("c").attachTreeView({
                    checkboxes: true,
                    items: []
                });
            }
        }

        function treesAccTree(accCode) {
            if(accCode) {
                reqJson(Access("getTrees", {subId, rankId: selectedRank, menuId: selectedMenu, accCode}), "GET", null, (err, res) => {
                    var items;
                    if(res.status === "success") {
                        items = res.items;
                    } else {
                        items = [];
                        eAlert(res.message);
                    }

                    treeAccTreeView = mLayout.cells("d").attachTreeView({
                        checkboxes: true,
                        items: items
                    });
                });
               
            } else {
                treeAccTreeView = mLayout.cells("d").attachTreeView({
                    checkboxes: true,
                    items: []
                });
            }
        }

        treesRank();

        treeToolbar.attachEvent("onClick", function (id) {
            switch (id) {
                case "save":
                    if(selectedAcc) {
                        if(treeAccordionView.isItemChecked(selectedAcc)) {
                            reqJson(Access("updateTrees"), "POST", {
                                subId,
                                rankId: selectedRank,
                                menuId: selectedMenu,
                                accCode: selectedAcc,
                                trees: treeAccTreeView.getAllChecked()
                            }, (err, res) => {
                                if (res.status === "success") {
                                    sAlert(res.message);
                                } else {
                                    eAlert("Update akses control gagal!");
                                }
                            });
                        } else {
                            eAlert("Silahkan centang checkbox accordions!");
                        }
                    } else {
                        eAlert("Belum ada accordions yang dipilih!");
                    }
                    break;
            }
        });
    
    }

JS;

header('Content-Type: application/javascript');
echo $script;
