//--------------------------------------
//- MONTHLY EMPLOYEE PERFORMANCE CHART -
//--------------------------------------

// Get context with jQuery - using jQuery's .get() method.
var empPerfChartCanvas = $("#empMonthlyPerformanceChart").get(0).getContext("2d");
// This will get the first returned node in the jQuery collection.
var empPerfChart = new Chart(empPerfChartCanvas);

//function to return the chart data
function perfChartData(monthlyResult, months) {
    var chartData = {
        labels: months,
        datasets: [
            {
                label: "Performance",
                fillColor: "rgba(60,141,188,0.9)",
                strokeColor: "rgba(60,141,188,0.8)",
                pointColor: "#3b8bba",
                pointStrokeColor: "rgba(60,141,188,1)",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(60,141,188,1)",
                data: monthlyResult
            }
        ]
    };
    return chartData;
}

var chartOptions = {
    //Boolean - If we should show the scale at all
    showScale: true,
    //Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines: false,
    //String - Colour of the grid lines
    scaleGridLineColor: "rgba(0,0,0,.05)",
    //Number - Width of the grid lines
    scaleGridLineWidth: 1,
    //Boolean - Whether to show horizontal lines (except X axis)
    scaleShowHorizontalLines: true,
    //Boolean - Whether to show vertical lines (except Y axis)
    scaleShowVerticalLines: true,
    //Boolean - Whether the line is curved between points
    bezierCurve: true,
    //Number - Tension of the bezier curve between points
    bezierCurveTension: 0.3,
    //Boolean - Whether to show a dot for each point
    pointDot: true,
    //Number - Radius of each point dot in pixels
    pointDotRadius: 4,
    //Number - Pixel width of point dot stroke
    pointDotStrokeWidth: 1,
    //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
    pointHitDetectionRadius: 20,
    //Boolean - Whether to show a stroke for datasets
    datasetStroke: true,
    //Number - Pixel width of dataset stroke
    datasetStrokeWidth: 2,
    //Boolean - Whether to fill the dataset with a color
    datasetFill: true,
    //String - A legend template
    legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%=datasets[i].label%></li><%}%></ul>",
    //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio: true,
    //Boolean - whether to make the chart responsive to window resizing
    responsive: true,
    //Format labels on Y axis
    //scaleLabel: function(label){return  'R ' + label.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");},
    scaleLabel: function(label){return  label.value.toString() + '%';},
    // String - Template string for single tooltips
    //tooltipTemplate: "<%if (label){%><%=label %>: <%}%><%= value + ' %' %>",
    // String - Template string for multiple tooltips
    //multiTooltipTemplate: "<%= 'R' + value %>"
    //multiTooltipTemplate: function(value){return  'R ' + value.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");}
    multiTooltipTemplate: function(value){return value.value.toString() + '%';}
};

//Get data with ajax
/*$.get("/api/emp-monthly-perf-graph-data",
    function(data) {
        var chartData = perfChartData([80, 45, 65, 0],
            [50, 60, 70, 80],
            ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']);

        //Create the line chart
        empPerfChart.Line(chartData, chartOptions);
    });*/

//------------------------------------------
//- END MONTHLY EMPLOYEE PERFORMANCE CHART -
//------------------------------------------
