<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
    
    function showDashOvertimeCompare() {
        var polar = true;
        var type = "line";
        var yearOne = "dash_year_comp_1";	
        var yearTwo = "dash_year_comp_2";	

        var compareLayout = mainTab.cells("dashboard_overtime_comparasion_tab").attachLayout({
            pattern: "1C",
            cells: [
                {id: "a", header: null}
            ]
        });

        compareLayout.cells("a").attachHTMLString("<div class='hc_graph' id='overtime_comparasion' style='height:100%;width:100%;'></div>");

        var compareMenu =  mainTab.cells("dashboard_overtime_comparasion_tab").attachMenu({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "year", text: genCompareYear(yearOne, yearTwo)},
                {id: "refresh", text: "Refresh", img: "resize.png"},
            ]
        });

        
        compareMenu.attachEvent("onClick", function(id) {
            switch (id) {
                case "refresh":
                    loadComparation(polar, type);
                    break;
            }
        });

        var compareToolbar = mainTab.cells("dashboard_overtime_comparasion_tab").attachToolbar({
            icon_path: "./public/codebase/icons/",
            items: [
                {id: "polar", text: "Polar", type: "button", img: "polar.png"},
                {id: "line", text: "Line", type: "button", img: "double_chart.png"},
                {id: "bar", text: "Bar Chart", type: "button", img: "bar_chart.png"},
            ]
        });

        compareToolbar.attachEvent("onClick", function(id) {
            switch (id) {
                case "polar":
                    polar = true;
                    type = "line";
                    loadComparation(true, "line");
                    break;
                case "line":
                    polar = false;
                    type = "line";
                    loadComparation(false, 'line');
                    break;
                case "bar":
                    polar = false;
                    type = "column";
                    loadComparation(false, 'column');
                    break;
            }
        });

        $("#"+yearOne).on("change", function() {
            loadComparation(polar, type);
        });

        $("#"+yearTwo).on("change", function() {
            loadComparation(polar, type);
        });

        function loadComparation(polar, type) {
            let firstYear = $("#"+yearOne).val();
            let lastYear = $("#"+yearTwo).val();
            compareLayout.cells("a").progressOn();
            reqJson(Dashboard("getOvtCompare"), "POST", {yearOne: firstYear, yearTwo: lastYear}, (err, res) => {
                if(res.status === "success") {
                    compareLayout.cells("a").progressOff();
                    Highcharts.chart('overtime_comparasion', {
                        chart: {
                            polar: polar,
                            type: type
                        },

                        title: {
                            text: res.title
                        },

                        pane: {
                            size: '80%'
                        },

                        xAxis: {
                            categories: res.categories,
                            tickmarkPlacement: 'on',
                            lineWidth: 0
                        },

                        yAxis: {
                            gridLineInterpolation: 'polygon',
                            lineWidth: 0,
                            min: 0,
                            title: {
                                text: polar ? null : "Tota Biaya Lembur"
                            }
                        },

                        tooltip: {
                            shared: true
                        },

                        series: res.series,

                        responsive: {
                            rules: [{
                                condition: {
                                    maxWidth: 500
                                },
                                chartOptions: {
                                    legend: {
                                        align: 'center',
                                        verticalAlign: 'bottom',
                                        layout: 'horizontal'
                                    },
                                    pane: {
                                        size: '70%'
                                    }
                                }
                            }]
                        },
                    });
                }
            });
        }

        loadComparation(polar, type);
    }

JS;
header('Content-Type: application/javascript');
echo $script;