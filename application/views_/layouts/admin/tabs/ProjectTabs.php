<?php
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}
$script = <<< "JS"
    var gantt;
    var projectMenu;
    var projectToolbar;

    function projectManagerTab() {
        if (!mainTab.tabs("pm")){
            mainTab.addTab("pm", tabsStyle("timeline.png", "Project Managemen", "background-size: 16px 16px"), null, null, true, true);
            mainTab.cells("pm").attachHTMLString("<div class='pm_gantt' id='pm' style='width:100%;height:100%'></div>");
            gantt.init("pm");
            
            projectMenu = mainTab.cells("pm").attachMenu({
                icon_path: "./public/codebase/icons/",
                items: [
                    {id: "name", text: "<span id='gantt_sub_name'>Nama Bagian</span> | <span><select id='gantt_division_id' style='height:21px'><option value='-'>ALL</option></select></span> | <span><select id='gantt_task_id' style='height:21px'><option value='-'>ALL</option></select></span> | " +  genSelectMonth("gantt_year", "gantt_month")},
                ]
            });

            projectToolbar = mainTab.cells("pm").attachToolbar({
                icon_path: "./public/codebase/icons/",
                items: [
                    {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                    {id: "share", text: "Bagikan", type: "button", img: "share.png"},
                ]
            });
        } else {
            mainTab.tabs("pm").setActive();
        }
    }
JS;

echo $script;