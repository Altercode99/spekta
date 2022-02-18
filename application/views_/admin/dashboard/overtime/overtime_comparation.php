<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
    
    function showDashOvertimeCompare(){var e=!0,a="line",t="dash_year_comp_1",i="dash_year_comp_2",n=mainTab.cells("dashboard_overtime_comparasion_tab").attachLayout({pattern:"1C",cells:[{id:"a",header:null}]});function o(e,a){let o=$("#"+t).val(),r=$("#"+i).val();n.cells("a").progressOn(),reqJson(Dashboard("getOvtCompare"),"POST",{yearOne:o,yearTwo:r},((t,i)=>{"success"===i.status&&(n.cells("a").progressOff(),Highcharts.chart("overtime_comparasion",{chart:{polar:e,type:a},title:{text:i.title},pane:{size:"80%"},xAxis:{categories:i.categories,tickmarkPlacement:"on",lineWidth:0},yAxis:{gridLineInterpolation:"polygon",lineWidth:0,min:0,title:{text:e?null:"Tota Biaya Lembur"}},tooltip:{shared:!0},series:i.series,responsive:{rules:[{condition:{maxWidth:500},chartOptions:{legend:{align:"center",verticalAlign:"bottom",layout:"horizontal"},pane:{size:"70%"}}}]}}))}))}n.cells("a").attachHTMLString("<div class='hc_graph' id='overtime_comparasion' style='height:100%;width:100%;'></div>"),mainTab.cells("dashboard_overtime_comparasion_tab").attachMenu({icon_path:"./public/codebase/icons/",items:[{id:"year",text:genCompareYear(t,i)},{id:"refresh",text:"Refresh",img:"resize.png"}]}).attachEvent("onClick",(function(t){switch(t){case"refresh":o(e,a)}})),mainTab.cells("dashboard_overtime_comparasion_tab").attachToolbar({icon_path:"./public/codebase/icons/",items:[{id:"polar",text:"Polar",type:"button",img:"polar.png"},{id:"line",text:"Line",type:"button",img:"double_chart.png"},{id:"bar",text:"Bar Chart",type:"button",img:"bar_chart.png"}]}).attachEvent("onClick",(function(t){switch(t){case"polar":e=!0,a="line",o(!0,"line");break;case"line":e=!1,a="line",o(!1,"line");break;case"bar":e=!1,a="column",o(!1,"column")}})),$("#"+t).on("change",(function(){o(e,a)})),$("#"+i).on("change",(function(){o(e,a)})),o(e,a)}

JS;
header('Content-Type: application/javascript');
echo $script;