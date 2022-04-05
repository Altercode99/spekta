<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	function showReportReqOvt() {	
        var reportReqLayout =  mainTab.cells("tnp_report_req_lembur").attachLayout({
            pattern: "1C",
            cells: [
                {id: "a", text: "Report Request Lembur"},
            ]
        });

        var reportReqToolbar = reportReqLayout.cells("a").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "refresh", text: "Refresh", type: "button", img: "refresh.png"},
                {id: "export", text: "Export To Excel", type: "button", img: "excel.png"},
            ]
        });

        let currentDate = filterForMonth(new Date());
        var reportReqMenu =  reportReqLayout.cells("a").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "search", text: "<div style='width:100%'>Search: <input type='text' id='tbp_start_ovt_req_report' readonly value='"+currentDate.start+"' /> - <input type='text' id='tbp_end_ovt_req_report' readonly value='"+currentDate.end+"' /> <button id='other_btn_ftr_ovt_req_report'>Proses</button>"}
            ]
        });

        var filterCalendar = new dhtmlXCalendarObject(["tbp_start_ovt_req_report","tbp_end_ovt_req_report"]);
        $("#other_btn_ftr_ovt_req_report").on("click", function() {
            if(checkFilterDate($("#tbp_start_ovt_req_report").val(), $("#tbp_end_ovt_req_report").val())) {
               
            }
        });
    }

JS;

header('Content-Type: application/javascript');
echo $script;