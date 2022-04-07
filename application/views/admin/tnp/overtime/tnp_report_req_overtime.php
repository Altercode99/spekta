<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	function showReportReqOvt() {	
        var rekapGrid;
        var reportReqLayout =  mainTab.cells("tnp_report_req_lembur").attachLayout({
            pattern: "1C",
            cells: [
                {id: "a", text: "Rekap Request Lembur"},
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
                {id: "search", text: "<div style='width:100%'>Search: <input type='text' id='tnp_start_ovt_req_report' readonly value='"+currentDate.start+"' /> - <input type='text' id='tnp_end_ovt_req_report' readonly value='"+currentDate.end+"' /> <button id='other_btn_ftr_ovt_req_report'>Proses</button>"}
            ]
        });

        var filterCalendar = new dhtmlXCalendarObject(["tnp_start_ovt_req_report","tnp_end_ovt_req_report"]);
        $("#other_btn_ftr_ovt_req_report").on("click", function() {
            if(checkFilterDate($("#tnp_start_ovt_req_report").val(), $("#tnp_end_ovt_req_report").val())) {
                getRekapGrid();
            }
        });

        reportReqToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    getRekapGrid();
                    break;
                case "export":
                    rekapGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    break;
            }
        });

        function getRekapGrid() {
            let start = $("#tnp_start_ovt_req_report").val();
            let end = $("#tnp_end_ovt_req_report").val();
            reportReqLayout.cells("a").progressOn();
            reqJson(Overtime("getRekapColumn"), "POST", {start, end}, (err, res) => {
                rekapGrid = reportReqLayout.cells("a").attachGrid();
                rekapGrid.setImagePath("./public/codebase/imgs/");
                rekapGrid.setHeader("No,Tanggal" + res.header);
                rekapGrid.attachHeader("#rspan,#text_filter" + res.attheader);
                rekapGrid.setColSorting("int,str" + res.colsort);
                rekapGrid.setColAlign("center,left" + res.colalign);
                rekapGrid.setColTypes("rotxt,rotxt" + res.coltypes);
                rekapGrid.setInitWidthsP("5,20" + res.width);
                rekapGrid.enableSmartRendering(true);
                rekapGrid.attachEvent("onXLE", function() {
                    reportReqLayout.cells("a").progressOff();
                });
                rekapGrid.init();
                rekapGrid.parse(res.rows, "json");

            });
        }

        getRekapGrid();
    }

JS;

header('Content-Type: application/javascript');
echo $script;