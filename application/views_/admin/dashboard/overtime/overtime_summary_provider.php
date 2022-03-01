<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
  
  function showDashboardOvertimeSummaryProvider(){var e="dash_year_summary_provider",a="dash_month_summary_provider",t=mainTab.cells("dashboard_overtime_summary_provider_tab").attachLayout({pattern:"3T",cells:[{id:"a",header:!1},{id:"b",header:!1},{id:"c",header:!1}]});function r(){let r=$("#"+e).val(),o=$("#"+a).val();t.cells("a").progressOn(),reqJson(Dashboard("getSummaryPersonil",{month_overtime_date:o,year_overtime_date:r,equal_status:"CLOSED"}),"POST",{year:r,month:o},((e,a)=>{t.cells("a").progressOff(),Highcharts.chart("monthly_summary_provider",{chart:{type:"line"},title:{text:"Lembur Bulan "+nameOfMonth(o)},xAxis:{categories:a.categories},yAxis:{title:{text:"Tota Biaya Lembur"}},plotOptions:{line:{dataLabels:{enabled:!0},enableMouseTracking:!0}},series:a.series})}))}function o(){sumGridToElement(i,3,"total_dash_sub_provider")}mainTab.cells("dashboard_overtime_summary_provider_tab").attachMenu({icon_path:"./public/codebase/icons/",items:[{id:"month",text:genSelectMonth(e,a)},{id:"line",text:"Line Chart",img:"double_chart.png"},{id:"column",text:"Bar Chart",img:"bar_chart.png"},{id:"refresh",text:"Refresh",img:"resize.png"},{id:"data_overtime",text:"Data Lemburan",img:"app18.png"}]}).attachEvent("onClick",(function(t){switch(t){case"data_overtime":let t=$("#"+e).val(),o=$("#"+a).val();mainTab.tabs("dashboard_overtime_summary_provider_data_tab_"+nameOfMonth(o))?mainTab.tabs("dashboard_overtime_summary_provider_data_tab_"+nameOfMonth(o)).setActive():(mainTab.addTab("dashboard_overtime_summary_provider_data_tab_"+nameOfMonth(o),tabsStyle("app18.png","Data Penyelenggara Lembur "+nameOfMonth(o),"background-size: 16px 16px"),null,null,!0,!0),showSummaryOvertimeProviderData("dashboard_overtime_summary_provider_data_tab_"+nameOfMonth(o),t,o));break;case"refresh":r(),n(),s();break;case"line":"line",r();break;case"column":"column",r()}})),t.cells("a").attachHTMLString("<div class='hc_graph' id='monthly_summary_provider' style='height:100%;width:100%;'></div>"),t.cells("c").attachHTMLString("<div class='hc_graph' id='top_5_summary_provider' style='height:100%;width:100%;'></div>"),$("#"+e).on("change",(function(){r(),n(),s()})),$("#"+a).on("change",(function(){r(),n(),s()})),r();var i=t.cells("b").attachGrid();function n(){let r=$("#"+e).val(),n=$("#"+a).val();t.cells("b").progressOn(),i.clearAndLoad(Dashboard("getSubOvtGrid",{wherejoin_ovt_sub_department:"b.id",groupby_ovt_sub_department:!0,month_overtime_date:n,year_overtime_date:r,equal_status:"CLOSED"}),o)}function s(){var r=$("#"+e).val(),o=$("#"+a).val();t.cells("c").progressOn(),reqJson(Dashboard("getTop5Sub",{wherejoin_ovt_sub_department:"b.id",groupby_ovt_sub_department:!0,month_overtime_date:o,year_overtime_date:r,equal_status:"CLOSED"}),"POST",{},((e,a)=>{t.cells("c").progressOff(),Highcharts.chart("top_5_summary_provider",{chart:{plotBackgroundColor:null,plotBorderWidth:null,plotShadow:!1,type:"pie"},title:{text:"TOP 5 Bagian Penyelenggara Lembur "+nameOfMonth(o)},tooltip:{pointFormat:"{series.name}: <b>{point.percentage:.1f}%</b>"},accessibility:{point:{valueSuffix:"%"}},plotOptions:{pie:{allowPointSelect:!0,cursor:"pointer",dataLabels:{enabled:!0,format:"<b>{point.name}</b>: {point.percentage:.1f} %"}}},series:[{name:"Lembur",colorByPoint:!0,data:a.series}]})}))}i.setHeader("No,Nama Bagian Penyelenggara Lembur,Jam Lembur,Nominal"),i.setColSorting("str,str,str,str"),i.setColTypes("rotxt,rotxt,rotxt,rotxt"),i.setColAlign("center,left,left,left"),i.setInitWidthsP("5,50,20,25"),i.enableSmartRendering(!0),i.attachEvent("onXLE",(function(){t.cells("b").progressOff()})),i.attachFooter(",Total Biaya Lembur,#stat_total,<div id='total_dash_sub_provider'>0</div>"),i.init(),n(),s()}

JS;
header('Content-Type: application/javascript');
echo $script;