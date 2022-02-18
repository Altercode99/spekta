<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
    
    function showSummaryOvertimeProviderData(tabs, year, month) {	
        var legend = legendGrid();
        var unique = year+""+month;
        var total_sum_overtime = "dash_total_sum_overtime_provider_" + unique; 
        var grand_sum_overtime = "dash_grand_sum_overtime_provider_" + unique; 
        var total_meal_sum_overtime = "dash_total_meal_sum_overtime_provider_" + unique; 
        var grand_meal_sum_overtime = "dash_grand_meal_sum_overtime_provider_" + unique; 
        var sum_grand_total = "dash_sum_grand_total_provider_" + unique; 
        
        var sumOvtProTabs =  mainTab.cells(tabs).attachTabbar({
            tabs: [
                {id: "a", text: "Report Lembur Semua Bagian", active: true},
                {id: "b", text: "Report Lembur Bagian"},
                {id: "c", text: "Report Lembur Sub Bagian"},
                {id: "d", text: "Report Lembur Karyawan"},
            ]
        });

        var sumOvtProMenu = sumOvtProTabs.cells("a").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "export", text: "Export To Excel", img: "excel.png"},
            ]
        });

        sumOvtProMenu.attachEvent("onClick", function(id) {
            switch (id) {
                case "export":
                    sumOvtProGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    sAlert("Export Data Dimulai");
                    break;
            }
        });

        var sumOvtProSubMenu = sumOvtProTabs.cells("b").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "export", text: "Export To Excel", img: "excel.png"},
            ]
        });

        sumOvtProSubMenu.attachEvent("onClick", function(id) {
            switch (id) {
                case "export":
                    sumOvtProSubGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    sAlert("Export Data Dimulai");
                    break;
            }
        });

        var sumOvtDivMenu = sumOvtProTabs.cells("c").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "export", text: "Export To Excel", img: "excel.png"},
            ]
        });

        sumOvtDivMenu.attachEvent("onClick", function(id) {
            switch (id) {
                case "export":
                    sumOvtProDivGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    sAlert("Export Data Dimulai");
                    break;
            }
        });

        var sumOvtProEmpMenu = sumOvtProTabs.cells("d").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "export", text: "Export To Excel", img: "excel.png"},
            ]
        });

        sumOvtProEmpMenu.attachEvent("onClick", function(id) {
            switch (id) {
                case "export":
                    sumOvtProEmpGrid.toExcel("./public/codebase/grid-to-excel-php/generate.php");
                    sAlert("Export Data Dimulai");
                    break;
            }
        });

        let sumOvtStatusBar = sumOvtProTabs.cells("a").attachStatusBar();
        function sumOvtProGridCount() {
            var sumOvtProGridRows = sumOvtProGrid.getRowsNum();
            sumOvtStatusBar.setText("Total baris: " + sumOvtProGridRows + " (" + legend.input_overtime + ")");
            let totalOvertime = sumGridToElement(sumOvtProGrid, 20, total_sum_overtime, grand_sum_overtime);
            let totalMeal = sumGridToElement(sumOvtProGrid, 22, total_meal_sum_overtime, grand_meal_sum_overtime);
            $("#"+sum_grand_total).html("Rp. " + numberFormat(totalOvertime+totalMeal));
        }

        var sumOvtProGrid = sumOvtProTabs.cells("a").attachGrid();
        sumOvtProGrid.setImagePath("./public/codebase/imgs/");
        sumOvtProGrid.setHeader("No,Task ID,No. Memo Lembur,Nama Karyawan,Bagian,Sub Bagian,Bagian Penyelenggara,Sub Bagian Penyelenggara,Nama Mesin #1,Nama Mesin #2,Pelayanan,Tanggal Overtime,Waktu Mulai,Waktu Selesai,Status Hari,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Premi,Nominal Overtime,Makan,Biaya Makan,Status Overtime,Created At");
        sumOvtProGrid.attachHeader("#rspan,#select_filter,#select_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#rspan,#text_filter")
        sumOvtProGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        sumOvtProGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        sumOvtProGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        sumOvtProGrid.setInitWidthsP("5,20,20,20,0,0,20,20,15,15,15,15,15,15,10,10,10,10,10,10,15,5,15,10,25");
        sumOvtProGrid.attachFooter(",Total Summary,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,,<div id='"+total_sum_overtime+"'>0</div>,,<div id='"+total_meal_sum_overtime+"'>0</div>,,");
        sumOvtProGrid.attachFooter(",Total Biaya Lembur,<div id='"+grand_sum_overtime+"'>0</div>");
        sumOvtProGrid.attachFooter(",Total Biaya Makan,<div id='"+grand_meal_sum_overtime+"'>0</div>");
        sumOvtProGrid.attachFooter(",Grand Total,<div id='"+sum_grand_total+"'>0</div>");
        sumOvtProGrid.enableSmartRendering(true);
        sumOvtProGrid.attachEvent("onXLE", function() {
            sumOvtProTabs.cells("a").progressOff();
        });
        sumOvtProGrid.init();

        function rSumOvtProGrid() {
            sumOvtProTabs.cells("a").progressOn();
            let params = {
                equal_status: "CLOSED",
                year_overtime_date: year,
                month_overtime_date: month,
            };
            sumOvtProGrid.clearAndLoad(Dashboard("getSumOvtGrid", params), sumOvtProGridCount);
        }

        rSumOvtProGrid();

        var total_sum_overtime_provider_sub = "total_sum_overtime_provider_sub_" + unique; 
        var grand_sum_overtime_provider_sub = "grand_sum_overtime_provider_sub_" + unique; 
        var total_meal_sum_overtime_provider_sub = "total_meal_sum_overtime_provider_sub_" + unique; 
        var grand_meal_sum_overtime_provider_sub = "grand_meal_sum_overtime_provider_sub_" + unique; 
        var sum_grand_total_provider_sub = "sum_grand_total_provider_sub_" + unique; 

        let sumtOvtSubStatusBar = sumOvtProTabs.cells("b").attachStatusBar();
        function sumOvtProSubGridCount() {
            var sumOvtProSubGridRows = sumOvtProSubGrid.getRowsNum();
            sumtOvtSubStatusBar.setText("Total baris: " + sumOvtProSubGridRows);
            let totalOvertimeSub = sumGridToElement(sumOvtProSubGrid, 7, total_sum_overtime_provider_sub, grand_sum_overtime_provider_sub);
            let totalMealSub = sumGridToElement(sumOvtProSubGrid, 8, total_meal_sum_overtime_provider_sub, grand_meal_sum_overtime_provider_sub);
            $("#"+sum_grand_total_provider_sub).html("Rp. " + numberFormat(totalOvertimeSub+totalMealSub));
        }

        var sumOvtProSubGrid = sumOvtProTabs.cells("b").attachGrid();
        sumOvtProSubGrid.setImagePath("./public/codebase/imgs/");
        sumOvtProSubGrid.setHeader("No,Bagian,Sub Unit,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Nominal Overtime,Biaya Makan");
        sumOvtProSubGrid.attachHeader("#rspan,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        sumOvtProSubGrid.setColSorting("int,str,str,str,str,str,str,str,str");
        sumOvtProSubGrid.setColAlign("center,left,left,left,left,left,left,left,left");
        sumOvtProSubGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        sumOvtProSubGrid.setInitWidthsP("5,20,20,10,10,10,10,13,13");
        sumOvtProSubGrid.attachFooter(",Total Summary,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,<div id='"+total_sum_overtime_provider_sub+"'>0</div>,<div id='"+total_meal_sum_overtime_provider_sub+"'>0</div>");
        sumOvtProSubGrid.attachFooter(",Total Biaya Lembur,<div id='"+grand_sum_overtime_provider_sub+"'>0</div>");
        sumOvtProSubGrid.attachFooter(",Total Biaya Makan,<div id='"+grand_meal_sum_overtime_provider_sub+"'>0</div>");
        sumOvtProSubGrid.attachFooter(",Grand Total,<div id='"+sum_grand_total_provider_sub+"'>0</div>");
        sumOvtProSubGrid.enableSmartRendering(true);
        sumOvtProSubGrid.attachEvent("onXLE", function() {
            sumOvtProTabs.cells("b").progressOff();
        });
        sumOvtProSubGrid.init();
        
        function rSumOvtProDeptGrid() {
            sumOvtProTabs.cells("b").progressOn();
            let params = {
                equal_status: "CLOSED",
                year_overtime_date: year,
                month_overtime_date: month,
                groupby_sub_department_id: true
            };
            sumOvtProSubGrid.clearAndLoad(Dashboard("getSumOvtSubGrid", params), sumOvtProSubGridCount);
        }

        rSumOvtProDeptGrid();

        var total_sum_overtime_provider_div = "total_sum_overtime_provider_div_" + unique; 
        var grand_sum_overtime_provider_div = "grand_sum_overtime_provider_div_" + unique; 
        var total_meal_sum_overtime_provider_div = "total_meal_sum_overtime_provider_div_" + unique; 
        var grand_meal_sum_overtime_provider_div = "grand_meal_sum_overtime_provider_div_" + unique; 
        var sum_grand_total_provider_div = "sum_grand_total_provider_div_" + unique; 

        let sumOvtDivStatusBar = sumOvtProTabs.cells("c").attachStatusBar();
        function sumOvtProDivGridCount() {
            var sumOvtProDivGridRows = sumOvtProDivGrid.getRowsNum();
            sumOvtDivStatusBar.setText("Total baris: " + sumOvtProDivGridRows);
            let totalOvertimeDiv = sumGridToElement(sumOvtProDivGrid, 7, total_sum_overtime_provider_div, grand_sum_overtime_provider_div);
            let totalMealDiv = sumGridToElement(sumOvtProDivGrid, 8, total_meal_sum_overtime_provider_div, grand_meal_sum_overtime_provider_div);
            $("#"+sum_grand_total_provider_div).html("Rp. " + numberFormat(totalOvertimeDiv+totalMealDiv));
        }

        var sumOvtProDivGrid = sumOvtProTabs.cells("c").attachGrid();
        sumOvtProDivGrid.setImagePath("./public/codebase/imgs/");
        sumOvtProDivGrid.setHeader("No,Sub Bagian,Bagian,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Nominal Overtime,Biaya Makan");
        sumOvtProDivGrid.attachHeader("#rspan,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        sumOvtProDivGrid.setColSorting("int,str,str,str,str,str,str,str,str");
        sumOvtProDivGrid.setColAlign("center,left,left,left,left,left,left,left,left");
        sumOvtProDivGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        sumOvtProDivGrid.setInitWidthsP("5,20,20,10,10,10,10,15,15");
        sumOvtProDivGrid.attachFooter(",Total Summary,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,<div id='"+total_sum_overtime_provider_div+"'>0</div>,<div id='"+total_meal_sum_overtime_provider_div+"'>0</div>");
        sumOvtProDivGrid.attachFooter(",Total Biaya Lembur,<div id='"+grand_sum_overtime_provider_div+"'>0</div>");
        sumOvtProDivGrid.attachFooter(",Total Biaya Makan,<div id='"+grand_meal_sum_overtime_provider_div+"'>0</div>");
        sumOvtProDivGrid.attachFooter(",Grand Total,<div id='"+sum_grand_total_provider_div+"'>0</div>");
        sumOvtProDivGrid.enableSmartRendering(true);
        sumOvtProDivGrid.attachEvent("onXLE", function() {
            sumOvtProTabs.cells("c").progressOff();
        });
        sumOvtProDivGrid.init();
        
        function rSumOvtProDivGrid() {
            sumOvtProTabs.cells("c").progressOn();
            let params = {
                equal_status: "CLOSED",
                year_overtime_date: year,
                month_overtime_date: month,
                groupby_division_id: true
            };
            sumOvtProDivGrid.clearAndLoad(Dashboard("getSumDivGrid", params), sumOvtProDivGridCount);
        }

        rSumOvtProDivGrid();

        var total_sum_overtime_provider_emp = "total_sum_overtime_provider_emp_" + unique; 
        var grand_sum_overtime_provider_emp = "grand_sum_overtime_provider_emp_" + unique; 
        var total_meal_sum_overtime_provider_emp = "total_meal_sum_overtime_provider_emp_" + unique; 
        var grand_meal_sum_overtime_provider_emp = "grand_meal_sum_overtime_provider_emp_" + unique; 
        var sum_grand_total_provider_emp = "sum_grand_total_provider_emp_" + unique; 

        let sumOvtEmpStatusBar = sumOvtProTabs.cells("d").attachStatusBar();
        function sumOvtProEmpGridCount() {
            var sumOvtProDivGridRows = sumOvtProEmpGrid.getRowsNum();
            sumOvtEmpStatusBar.setText("Total baris: " + sumOvtProDivGridRows);
            let totalOvertimeEmp = sumGridToElement(sumOvtProEmpGrid, 9, total_sum_overtime_provider_emp, grand_sum_overtime_provider_emp);
            let totalMealEmp = sumGridToElement(sumOvtProEmpGrid, 10, total_meal_sum_overtime_provider_emp, grand_meal_sum_overtime_provider_emp);
            $("#"+sum_grand_total_provider_emp).html("Rp. " + numberFormat(totalOvertimeEmp+totalMealEmp));
        }

        var sumOvtProEmpGrid = sumOvtProTabs.cells("d").attachGrid();
        sumOvtProEmpGrid.setImagePath("./public/codebase/imgs/");
        sumOvtProEmpGrid.setHeader("No,Nama Karyawan,Sub Bagian,Bagian,Sub Unit,Jam Efektif,Jam Istirahat,Jam Ril,Jam Lembur,Nominal Overtime,Biaya Makan");
        sumOvtProEmpGrid.attachHeader("#rspan,#text_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter")
        sumOvtProEmpGrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str");
        sumOvtProEmpGrid.setColAlign("center,left,left,left,left,left,left,left,left,left,left");
        sumOvtProEmpGrid.setColTypes("rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt,rotxt");
        sumOvtProEmpGrid.setInitWidthsP("5,20,20,20,20,10,10,10,10,15,15");
        sumOvtProEmpGrid.attachFooter(",Total Summary,#cspan,#cspan,#cspan,#stat_total,#stat_total,#stat_total,#stat_total,<div id='"+total_sum_overtime_provider_emp+"'>0</div>,<div id='"+total_meal_sum_overtime_provider_emp+"'>0</div>");
        sumOvtProEmpGrid.attachFooter(",Total Biaya Lembur,<div id='"+grand_sum_overtime_provider_emp+"'>0</div>");
        sumOvtProEmpGrid.attachFooter(",Total Biaya Makan,<div id='"+grand_meal_sum_overtime_provider_emp+"'>0</div>");
        sumOvtProEmpGrid.attachFooter(",Grand Total,<div id='"+sum_grand_total_provider_emp+"'>0</div>");
        sumOvtProEmpGrid.enableSmartRendering(true);
        sumOvtProEmpGrid.attachEvent("onXLE", function() {
            sumOvtProTabs.cells("d").progressOff();
        });
        sumOvtProEmpGrid.init();
        
        function rSumOvtProEmpGrid() {
            sumOvtProTabs.cells("d").progressOn();
            let params = {
                equal_status: "CLOSED",
                year_overtime_date: year,
                month_overtime_date: month,
                groupby_emp_id: true
            };
            sumOvtProEmpGrid.clearAndLoad(Dashboard("getSumOvtEmpGrid", params), sumOvtProEmpGridCount);
        }

        rSumOvtProEmpGrid();
    }

JS;
header('Content-Type: application/javascript');
echo $script;