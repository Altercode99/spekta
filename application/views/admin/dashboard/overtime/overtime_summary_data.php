<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
    
    function showSummaryOvertimeData(tabs, year, month) {	
        var legend = legendGrid();
        var unique = year+""+month;
        var total_sum_overtime = "dash_total_sum_overtime_" + unique; 
        var grand_sum_overtime = "dash_grand_sum_overtime_" + unique; 
        var total_meal_sum_overtime = "dash_total_meal_sum_overtime_" + unique; 
        var grand_meal_sum_overtime = "dash_grand_meal_sum_overtime_" + unique; 
        var sum_grand_total = "dash_sum_grand_total_" + unique; 
        
        var sumOvtTabs =  mainTab.cells(tabs).attachTabbar({
            tabs: [
                {id: "a", text: "Report Lembur Semua Bagian", active: true},
                {id: "b", text: "Report Lembur Bagian"},
                {id: "c", text: "Report Lembur Sub Bagian"},
                {id: "d", text: "Report Lembur Karyawan"},
            ]
        });

        var sumOvtMenu = sumOvtTabs.cells("a").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "export", text: "Export To Excel", img: "excel.png"},
            ]
        });

        sumOvtMenu.attachEvent("onClick", function(id) {
            switch (id) {
                case "export":
                    sumOvtGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    sAlert("Export Data Dimulai");
                    break;
            }
        });

        var sumOvtSubMenu = sumOvtTabs.cells("b").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "export", text: "Export To Excel", img: "excel.png"},
            ]
        });

        sumOvtSubMenu.attachEvent("onClick", function(id) {
            switch (id) {
                case "export":
                    sumOvtSubGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    sAlert("Export Data Dimulai");
                    break;
            }
        });

        var sumOvtDivMenu = sumOvtTabs.cells("c").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "export", text: "Export To Excel", img: "excel.png"},
            ]
        });

        sumOvtDivMenu.attachEvent("onClick", function(id) {
            switch (id) {
                case "export":
                    sumOvtDivGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    sAlert("Export Data Dimulai");
                    break;
            }
        });

        var sumOvtEmpMenu = sumOvtTabs.cells("d").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "export", text: "Export To Excel", img: "excel.png"},
            ]
        });

        sumOvtEmpMenu.attachEvent("onClick", function(id) {
            switch (id) {
                case "export":
                    sumOvtEmpGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    sAlert("Export Data Dimulai");
                    break;
            }
        });

        let sumOvtStatusBar = sumOvtTabs.cells("a").attachStatusBar();
        function sumOvtGridCount() {
            var sumOvtGridRows = sumOvtGrid.getRowsNum();
            sumOvtStatusBar.setText("Total baris: " + sumOvtGridRows + " (" + legend.input_overtime + ")");
            let totalOvertime = sumGridToElement(sumOvtGrid, 20, total_sum_overtime, grand_sum_overtime);
            let totalMeal = sumGridToElement(sumOvtGrid, 22, total_meal_sum_overtime, grand_meal_sum_overtime);
            $("#"+sum_grand_total).html("Rp. " + numberFormat(totalOvertime+totalMeal));
        }

        var sumOvtGrid = sumOvtTabs.cells("a").attachGrid();
        sumOvtGrid.setImagePath("./public/codebase/imgs/");
        sumOvtGrid.setHeader("No,Task ID,No. Memo Lembur,Nama Karyawan,Bagian,Sub Bagian,Bagian Penyelenggara,Sub Bagian Penyelenggara,Nama Mesin #1,Nama Mesin #2,Pelayanan,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Status Hari,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Premi,Nominal Overtime,Makan,Biaya Makan,Status Overtime,Created At");
        sumOvtGrid.attachHeader("#rspan,#select_filter,#select_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#rspan,#text_filter")
        sumOvtGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        sumOvtGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        sumOvtGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        sumOvtGrid.setInitWidthsP("5,20,20,20,20,20,0,0,15,15,15,15,15,15,10,10,10,10,10,10,15,5,15,10,25");
        sumOvtGrid.attachFooter(",Total Summary,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,,<div id='"+total_sum_overtime+"'>0</div>,,<div id='"+total_meal_sum_overtime+"'>0</div>,,");
        sumOvtGrid.attachFooter(",Total Biaya Lembur,<div id='"+grand_sum_overtime+"'>0</div>");
        sumOvtGrid.attachFooter(",Total Biaya Makan,<div id='"+grand_meal_sum_overtime+"'>0</div>");
        sumOvtGrid.attachFooter(",Grand Total,<div id='"+sum_grand_total+"'>0</div>");
        sumOvtGrid.enableSmartRendering(true);
        sumOvtGrid.attachEvent("onXLE", function() {
            sumOvtTabs.cells("a").progressOff();
        });
        sumOvtGrid.init();

        function rSumOvtGrid() {
            sumOvtTabs.cells("a").progressOn();
            let params = {
                equal_status: "CLOSED",
                year_overtime_date: year,
                month_overtime_date: month,
            };
            sumOvtGrid.clearAndLoad(Dashboard("getSumOvtGrid", params), sumOvtGridCount);
        }

        rSumOvtGrid();

        var total_sum_overtime_sub = "total_sum_overtime_sub_" + unique; 
        var grand_sum_overtime_sub = "grand_sum_overtime_sub_" + unique; 
        var total_meal_sum_overtime_sub = "total_meal_sum_overtime_sub_" + unique; 
        var grand_meal_sum_overtime_sub = "grand_meal_sum_overtime_sub_" + unique; 
        var sum_grand_total_sub = "sum_grand_total_sub_" + unique; 

        let sumtOvtSubStatusBar = sumOvtTabs.cells("b").attachStatusBar();
        function sumOvtSubGridCount() {
            var sumOvtSubGridRows = sumOvtSubGrid.getRowsNum();
            sumtOvtSubStatusBar.setText("Total baris: " + sumOvtSubGridRows);
            let totalOvertimeSub = sumGridToElement(sumOvtSubGrid, 7, total_sum_overtime_sub, grand_sum_overtime_sub);
            let totalMealSub = sumGridToElement(sumOvtSubGrid, 8, total_meal_sum_overtime_sub, grand_meal_sum_overtime_sub);
            $("#"+sum_grand_total_sub).html("Rp. " + numberFormat(totalOvertimeSub+totalMealSub));
        }

        var sumOvtSubGrid = sumOvtTabs.cells("b").attachGrid();
        sumOvtSubGrid.setImagePath("./public/codebase/imgs/");
        sumOvtSubGrid.setHeader("No,Bagian,Sub Unit,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Nominal Overtime,Biaya Makan");
        sumOvtSubGrid.attachHeader("#rspan,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        sumOvtSubGrid.setColSorting("int,str,str,str,str,str,str,str,str");
        sumOvtSubGrid.setColAlign("center,left,left,left,left,left,left,left,left");
        sumOvtSubGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        sumOvtSubGrid.setInitWidthsP("5,20,20,10,10,10,10,13,13");
        sumOvtSubGrid.attachFooter(",Total Summary,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,<div id='"+total_sum_overtime_sub+"'>0</div>,<div id='"+total_meal_sum_overtime_sub+"'>0</div>");
        sumOvtSubGrid.attachFooter(",Total Biaya Lembur,<div id='"+grand_sum_overtime_sub+"'>0</div>");
        sumOvtSubGrid.attachFooter(",Total Biaya Makan,<div id='"+grand_meal_sum_overtime_sub+"'>0</div>");
        sumOvtSubGrid.attachFooter(",Grand Total,<div id='"+sum_grand_total_sub+"'>0</div>");
        sumOvtSubGrid.enableSmartRendering(true);
        sumOvtSubGrid.attachEvent("onXLE", function() {
            sumOvtTabs.cells("b").progressOff();
        });
        sumOvtSubGrid.init();
        
        function rSumOvtDeptGrid() {
            sumOvtTabs.cells("b").progressOn();
            let params = {
                equal_status: "CLOSED",
                year_overtime_date: year,
                month_overtime_date: month,
                groupby_sub_department_id: true
            };
            sumOvtSubGrid.clearAndLoad(Dashboard("getSumOvtSubGrid", params), sumOvtSubGridCount);
        }

        rSumOvtDeptGrid();

        var total_sum_overtime_div = "total_sum_overtime_div_" + unique; 
        var grand_sum_overtime_div = "grand_sum_overtime_div_" + unique; 
        var total_meal_sum_overtime_div = "total_meal_sum_overtime_div_" + unique; 
        var grand_meal_sum_overtime_div = "grand_meal_sum_overtime_div_" + unique; 
        var sum_grand_total_div = "sum_grand_total_div_" + unique; 

        let sumOvtDivStatusBar = sumOvtTabs.cells("c").attachStatusBar();
        function sumOvtDivGridCount() {
            var sumOvtDivGridRows = sumOvtDivGrid.getRowsNum();
            sumOvtDivStatusBar.setText("Total baris: " + sumOvtDivGridRows);
            let totalOvertimeDiv = sumGridToElement(sumOvtDivGrid, 7, total_sum_overtime_div, grand_sum_overtime_div);
            let totalMealDiv = sumGridToElement(sumOvtDivGrid, 8, total_meal_sum_overtime_div, grand_meal_sum_overtime_div);
            $("#"+sum_grand_total_div).html("Rp. " + numberFormat(totalOvertimeDiv+totalMealDiv));
        }

        var sumOvtDivGrid = sumOvtTabs.cells("c").attachGrid();
        sumOvtDivGrid.setImagePath("./public/codebase/imgs/");
        sumOvtDivGrid.setHeader("No,Sub Bagian,Bagian,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Nominal Overtime,Biaya Makan");
        sumOvtDivGrid.attachHeader("#rspan,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        sumOvtDivGrid.setColSorting("int,str,str,str,str,str,str,str,str");
        sumOvtDivGrid.setColAlign("center,left,left,left,left,left,left,left,left");
        sumOvtDivGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        sumOvtDivGrid.setInitWidthsP("5,20,20,10,10,10,10,15,15");
        sumOvtDivGrid.attachFooter(",Total Summary,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,<div id='"+total_sum_overtime_div+"'>0</div>,<div id='"+total_meal_sum_overtime_div+"'>0</div>");
        sumOvtDivGrid.attachFooter(",Total Biaya Lembur,<div id='"+grand_sum_overtime_div+"'>0</div>");
        sumOvtDivGrid.attachFooter(",Total Biaya Makan,<div id='"+grand_meal_sum_overtime_div+"'>0</div>");
        sumOvtDivGrid.attachFooter(",Grand Total,<div id='"+sum_grand_total_div+"'>0</div>");
        sumOvtDivGrid.enableSmartRendering(true);
        sumOvtDivGrid.attachEvent("onXLE", function() {
            sumOvtTabs.cells("c").progressOff();
        });
        sumOvtDivGrid.init();
        
        function rSumOvtDivGrid() {
            sumOvtTabs.cells("c").progressOn();
            let params = {
                equal_status: "CLOSED",
                year_overtime_date: year,
                month_overtime_date: month,
                groupby_division_id: true
            };
            sumOvtDivGrid.clearAndLoad(Dashboard("getSumDivGrid", params), sumOvtDivGridCount);
        }

        rSumOvtDivGrid();

        var total_sum_overtime_emp = "total_sum_overtime_emp_" + unique; 
        var grand_sum_overtime_emp = "grand_sum_overtime_emp_" + unique; 
        var total_meal_sum_overtime_emp = "total_meal_sum_overtime_emp_" + unique; 
        var grand_meal_sum_overtime_emp = "grand_meal_sum_overtime_emp_" + unique; 
        var sum_grand_total_emp = "sum_grand_total_emp_" + unique; 

        let sumOvtEmpStatusBar = sumOvtTabs.cells("d").attachStatusBar();
        function sumOvtEmpGridCount() {
            var sumOvtDivGridRows = sumOvtEmpGrid.getRowsNum();
            sumOvtEmpStatusBar.setText("Total baris: " + sumOvtDivGridRows);
            let totalOvertimeEmp = sumGridToElement(sumOvtEmpGrid, 9, total_sum_overtime_emp, grand_sum_overtime_emp);
            let totalMealEmp = sumGridToElement(sumOvtEmpGrid, 10, total_meal_sum_overtime_emp, grand_meal_sum_overtime_emp);
            $("#"+sum_grand_total_emp).html("Rp. " + numberFormat(totalOvertimeEmp+totalMealEmp));
        }

        var sumOvtEmpGrid = sumOvtTabs.cells("d").attachGrid();
        sumOvtEmpGrid.setImagePath("./public/codebase/imgs/");
        sumOvtEmpGrid.setHeader("No,Nama Karyawan,Sub Bagian,Bagian,Sub Unit,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Nominal Overtime,Biaya Makan");
        sumOvtEmpGrid.attachHeader("#rspan,#text_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        sumOvtEmpGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str");
        sumOvtEmpGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left");
        sumOvtEmpGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        sumOvtEmpGrid.setInitWidthsP("5,20,20,20,20,10,10,10,10,15,15");
        sumOvtEmpGrid.attachFooter(",Total Summary,#cspan,#cspan,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,<div id='"+total_sum_overtime_emp+"'>0</div>,<div id='"+total_meal_sum_overtime_emp+"'>0</div>");
        sumOvtEmpGrid.attachFooter(",Total Biaya Lembur,<div id='"+grand_sum_overtime_emp+"'>0</div>");
        sumOvtEmpGrid.attachFooter(",Total Biaya Makan,<div id='"+grand_meal_sum_overtime_emp+"'>0</div>");
        sumOvtEmpGrid.attachFooter(",Grand Total,<div id='"+sum_grand_total_emp+"'>0</div>");
        sumOvtEmpGrid.enableSmartRendering(true);
        sumOvtEmpGrid.attachEvent("onXLE", function() {
            sumOvtTabs.cells("d").progressOff();
        });
        sumOvtEmpGrid.init();
        
        function rSumOvtEmpGrid() {
            sumOvtTabs.cells("d").progressOn();
            let params = {
                equal_status: "CLOSED",
                year_overtime_date: year,
                month_overtime_date: month,
                groupby_emp_id: true
            };
            sumOvtEmpGrid.clearAndLoad(Dashboard("getSumOvtEmpGrid", params), sumOvtEmpGridCount);
        }

        rSumOvtEmpGrid();

    }

JS;
header('Content-Type: application/javascript');
echo $script;