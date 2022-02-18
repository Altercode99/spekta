<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
    
    function showDashboardOvertimeSummary(){var a="line",t=mainTab.cells("dashboard_overtime_summary_tab").attachLayout({pattern:"3T",cells:[{id:"a",header:!1},{id:"b",header:!1},{id:"c",header:!1}]});function e(){let e=$("#dash_year_summary").val(),r=$("#dash_month_summary").val();t.cells("a").progressOn(),reqJson(Dashboard("getSummaryPersonil",{month_overtime_date:r,year_overtime_date:e,equal_status:"CLOSED"}),"POST",{year:e,month:r},((e,s)=>{t.cells("a").progressOff(),Highcharts.chart("monthly_summary",{chart:{type:a},title:{text:"Lembur Bulan "+nameOfMonth(r)},xAxis:{categories:s.categories},yAxis:{title:{text:"Tota Biaya Lembur"}},plotOptions:{line:{dataLabels:{enabled:!0},enableMouseTracking:!0}},series:s.series})}))}function r(){sumGridToElement(s,3,"total_dash_sub")}mainTab.cells("dashboard_overtime_summary_tab").attachMenu({icon_path:"./public/codebase/icons/",items:[{id:"month",text:genSelectMonth("dash_year_summary","dash_month_summary")},{id:"line",text:"Line Chart",img:"double_chart.png"},{id:"column",text:"Bar Chart",img:"bar_chart.png"},{id:"refresh",text:"Resize",img:"resize.png"},{id:"data_overtime",text:"Data Lemburan",img:"app18.png"}]}).attachEvent("onClick",(function(t){switch(t){case"data_overtime":var r=$("#dash_year_summary").val(),s=$("#dash_month_summary").val();mainTab.tabs("dashboard_overtime_summary_data_tab_"+nameOfMonth(s))?mainTab.tabs("dashboard_overtime_summary_data_tab_"+nameOfMonth(s)).setActive():(mainTab.addTab("dashboard_overtime_summary_data_tab_"+nameOfMonth(s),tabsStyle("app18.png","Data Lembur Personil "+nameOfMonth(s)+" "+r,"background-size: 16px 16px"),null,null,!0,!0),showSummaryOvertimeData("dashboard_overtime_summary_data_tab_"+nameOfMonth(s),r,s));break;case"refresh":e(),o(),n();break;case"line":a="line",e();break;case"column":a="column",e()}})),t.cells("a").attachHTMLString("<div class='hc_graph' id='monthly_summary' style='height:100%;width:100%;'></div>"),t.cells("c").attachHTMLString("<div class='hc_graph' id='top_5_summary' style='height:100%;width:100%;'></div>"),$("#dash_year_summary").on("change",(function(){e(),o(),n()})),$("#dash_month_summary").on("change",(function(){e(),o(),n()})),e();var s=t.cells("b").attachGrid();function o(){let a=$("#dash_year_summary").val(),e=$("#dash_month_summary").val();t.cells("b").progressOn(),s.clearAndLoad(Dashboard("getSubOvtGrid",{wherejoin_sub_department_id:"b.id",groupby_sub_department_id:!0,month_overtime_date:e,year_overtime_date:a,equal_status:"CLOSED"}),r)}function n(){var a=$("#dash_year_summary").val(),e=$("#dash_month_summary").val();t.cells("c").progressOn(),reqJson(Dashboard("getTop5Sub",{wherejoin_sub_department_id:"b.id",groupby_sub_department_id:!0,month_overtime_date:e,year_overtime_date:a,equal_status:"CLOSED"}),"POST",{},((a,r)=>{t.cells("c").progressOff(),Highcharts.chart("top_5_summary",{chart:{plotBackgroundColor:null,plotBorderWidth:null,plotShadow:!1,type:"pie"},title:{text:"TOP 5 Lembur Bagian "+nameOfMonth(e)},tooltip:{pointFormat:"{series.name}: <b>{point.percentage:.1f}%</b>"},accessibility:{point:{valueSuffix:"%"}},plotOptions:{pie:{allowPointSelect:!0,cursor:"pointer",dataLabels:{enabled:!0,format:"<b>{point.name}</b>: {point.percentage:.1f} %"}}},series:[{name:"Lembur",colorByPoint:!0,data:r.series}]})}))}s.setHeader("No,Nama Bagian,Jam Lembur,Nominal"),s.setColSorting("str,str,str,str"),s.setColTypes("rotxt,rotxt,rotxt,rotxt"),s.setColAlign("center,left,left,left"),s.setInitWidthsP("5,50,20,25"),s.enableSmartRendering(!0),s.attachEvent("onXLE",(function(){t.cells("b").progressOff()})),s.attachFooter(",Total Biaya Lembur,#stat_total,<div id='total_dash_sub'>0</div>"),s.init(),o(),n()}

JS;
header('Content-Type: application/javascript');
echo $script;