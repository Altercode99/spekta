<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	function showOvertimeCathering() {
        let currentDate = getCurrentDate(new Date());

        var ovtCathLayout = mainTab.cells("ga_lembur_katering").attachLayout({
           pattern: "1C",
           cells: [
               {id: "a", text: "Kebutuhan Katering Lembur"}
           ]
        });

        var ovtGridMenu =  ovtCathLayout.cells("a").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "search", text: "<div style='width:100%'>Search: <input type='text' id='ga_ovtcat_start' readonly value='"+currentDate+"' /> - <input type='text' id='ga_ovtcat_end' readonly value='"+currentDate+"' /> <button id='ga_ovtcat_process'>Proses</button>"}
            ]
        });

        var filterCalendar = new dhtmlXCalendarObject(["ga_ovtcat_start","ga_ovtcat_end"]);

        $("#ga_ovtcat_process").on("click", function() {
            if(checkFilterDate($("#ga_ovtcat_start").val(), $("#ga_ovtcat_end").val())) {
                rOvtGrid();
            }
        });

        let ovtGridBar = ovtCathLayout.cells("a").attachStatusBar();
        function ovtGridCount() {
            var ovtGridRows = ovtGrid.getRowsNum();
            ovtGridBar.setText("Total baris: " + ovtGridRows);
            sumGridToElement(ovtGrid, 11, "ga_total_meal");
        }

        var ovtGrid = ovtCathLayout.cells("a").attachGrid();
        ovtGrid.setImagePath("./public/codebase/imgs/");
        ovtGrid.setHeader("No,Task ID,Nama Karyawan,Sub Unit,Bagian,Sub Bagian,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Status Hari,Makan,,Tugas,Status Overtime,Status Terakhir,Created By,Updated By,Created At");
        ovtGrid.attachHeader("#rspan,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        ovtGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        ovtGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        ovtGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        ovtGrid.setInitWidthsP("5,20,20,20,20,20,15,15,15,10,5,0,25,10,30,15,15,22");
        ovtGrid.attachFooter(",Total Makan,<span id='ga_total_meal'>0</span> BOX");
        ovtGrid.enableSmartRendering(true);
        ovtGrid.attachEvent("onXLE", function() {
            ovtCathLayout.cells("a").progressOff();
        });
        ovtGrid.init();

        function rOvtGrid() {
            ovtCathLayout.cells("a").progressOn();
            let start = $("#ga_ovtcat_start").val();
            let end = $("#ga_ovtcat_end").val();
            let params = {
                notin_status: "CANCELED,REJECTED,ADD",
                betweendate_overtime_date: start+","+end
            };
            ovtGrid.clearAndLoad(GAOther("getCatheringOvertime", params), ovtGridCount);
        }

        rOvtGrid();
    }

JS;

header('Content-Type: application/javascript');
echo $script;
