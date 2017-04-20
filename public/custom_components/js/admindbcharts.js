//function to return the chart data
function perfChartData(graphData, labels) {
    var chartData = {
        labels: labels,
        datasets: [
            {
                label: "Performance",
                fillColor: "rgba(60,141,188,0.9)",
                strokeColor: "rgba(60,141,188,0.8)",
                pointColor: "#3b8bba",
                pointStrokeColor: "rgba(60,141,188,1)",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(60,141,188,1)",
                data: graphData
            }
        ]
    };
    return chartData;
}
//--------------------------------------
//- MONTHLY EMPLOYEE PERFORMANCE CHART -
//--------------------------------------
//chart options
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
//function to draw the chart on the canvas
function loadEmpMonthlyPerformance(chartCanvas, empID) {
    // Get context with jQuery - using jQuery's .get() method.
    var empPerfChartCanvas = chartCanvas.get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var empPerfChart = new Chart(empPerfChartCanvas);
    //char labels (months)
    var monthLabels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    //get chart data with ajax
    $.get("/api/emp/" + empID + "/monthly-performance",
        function(data) {
            var chartData = perfChartData(data, monthLabels);

            //Create the bar chart
            empPerfChart.Bar(chartData, chartOptions);
        });
}

//------------------------------------------
//- END MONTHLY EMPLOYEE PERFORMANCE CHART -
//------------------------------------------


//-------------------------------
//- DIVISIONS PERFORMANCE CHART -
//-------------------------------
//Chart options
var divChartOptions = {
    showScale: true,
    scaleShowGridLines: false,
    scaleGridLineColor: "rgba(0,0,0,.05)",
    scaleGridLineWidth: 1,
    scaleShowHorizontalLines: true,
    scaleShowVerticalLines: true,
    bezierCurve: true,
    bezierCurveTension: 0.3,
    pointDot: true,
    pointDotRadius: 4,
    pointDotStrokeWidth: 1,
    pointHitDetectionRadius: 20,
    datasetStroke: true,
    datasetStrokeWidth: 2,
    datasetFill: true,
    //String - A legend template
    legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%=datasets[i].label%></li><%}%></ul>",
    maintainAspectRatio: true,
    responsive: true,
    //Format labels on Y axis
    scaleLabel: function(label){return  label.value.toString() + '%';},
    // String - Template string for single tooltips
    //tooltipTemplate: "<%if (label){%><%=label %>: <%}%><%= value + ' %' %>",
    // String - Template string for multiple tooltips
    multiTooltipTemplate: function(value){return value.value.toString() + '%';}
};

//function to draw the chart on the canvas and show rankings
function loadDivPerformance(chartCanvas, rankingList, divLevel, parentDivID) {
    parentDivID = parentDivID || 0;
    // Get context with jQuery - using jQuery's .get() method.
    var divPerfChartCanvas = chartCanvas.get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var divPerfChart = new Chart(divPerfChartCanvas);
    
    //Get chart data using ajax
    var getURL = "/api/divlevel/" + divLevel + "/group-performance"; //all divs of a level
    if (parentDivID > 0) getURL = 'api/divlevel/' + divLevel + '/parentdiv/' + parentDivID + '/group-performance'; //child divs of a specific parent div
    $.get(getURL,
        function(data) {
            var divResults = [], divLabels = [];
            $.each(data, function(key, value) {
                divResults.push(value['div_result']);
                divLabels.push(value['div_name']);
            });
            var chartData = perfChartData(divResults, divLabels);

            //Create the bar chart
            divPerfChart.Bar(chartData, divChartOptions);

            //sort the data by performance
            var sortedData = data.sort(function(a, b){return a['div_result']-b['div_result']});

            //Load ranking
            rankingList.empty();
            $.each(sortedData, function(key, value) {
                var divID = value['div_id'],
                    divName = value['div_name'],
                    divResult = value['div_result'],
                    divLevel = value['div_level'],
                    isChildLevelActive = value['is_child_level_active'],
                    childLevel = value['child_level'],
                    childName = value['child_level_name'],
                    childPluralName = value['child_level_plural_name'],
                    resultColor = '';
                if (divResult < 50) resultColor = 'red';
                else if (divResult >= 50 && divResult < 60) resultColor = 'yellow';
                else if (divResult > 60) resultColor = 'blue';
                var divNameSpan = $("<span class='progress-text'></span>").html(divName);
                var divResultSpan = $("<span class='progress-number'></span>").html(divResult + "%").addClass("text-" + resultColor); //<i class='fa fa-angle-down'></i>
                var progBar = $("<div class='progress-bar'></div>").attr("style", "width: " + divResult + "%").addClass("progress-bar-" + resultColor);
                var progBarDiv  = $("<div class='progress xs'></div>").css("margin-bottom", "5px").append(progBar);
                var progressGroup  = $("<div class='progress-group'></div>")
                    .append(divNameSpan)
                    .append(divResultSpan)
                    .append(progBarDiv);
                var childModalID = 'emp-list-performance-modal';
                if (isChildLevelActive) childModalID = 'sub-division-performance-modal-' + childLevel;
                var listLink = $("<a href='#'></a>")
                    .attr('data-toggle', 'modal')
                    .attr('data-target', '#' + childModalID)
                    .attr('data-division_id', divID)
                    .attr('data-division_name', divName)
                    .attr('data-division_level', divLevel)
                    .attr('data-is_child_level_active', isChildLevelActive)
                    .attr('data-child_level', childLevel)
                    //.attr('data-child_level_name', childName)
                    .attr('data-child_level_plural_name', childPluralName)
                    .append(progressGroup);
                var listItem = $("<li></li>")
                    .append(listLink);
                rankingList.append(listItem);
            });
        });
}
//function to draw chart on modal canvas (modal show)
function subDivOnShow(objTrigger, modalWin) {
    var divID = objTrigger.data('division_id');
    var divName = objTrigger.data('division_name');
    var isChildLevelActive = objTrigger.data('is_child_level_active');
    var childLevel = objTrigger.data('child_level');
    var childLevelPluralName = objTrigger.data('child_level_plural_name');
    modalWin.find('#sud-division-modal-title-' + childLevel).html(divName + ' Performance');
    if (isChildLevelActive) {
        var rankingList = modalWin.find('#sub-div-ranking-list-' + childLevel);
        var divChartCanvas = modalWin.find('#subDivisionsPerformanceChart' + childLevel);
        modalWin.find('#sub-division-chart-title-' + childLevel).html(childLevelPluralName + ' Performance For ' + new Date().getFullYear());
        loadDivPerformance(divChartCanvas, rankingList, childLevel, divID);
    }
}
//-----------------------------------
//- END DIVISIONS PERFORMANCE CHART -
//-----------------------------------


//-----------------------------
//- EMPLOYEE LIST PERFORMANCE -
//-----------------------------
//function to show employees performance ranking
function loadEmpListPerformance(rankingList, divLevel, divID) {
    //Get employees performance data using ajax
    var getURL = "/api/divlevel/" + divLevel + "/div/" + divID + "/emps-performance"; //all employees from a specific divisionspecific parent div
    $.get(getURL,
        function(data) {
            //console.log(JSON.stringify(data));
            //sort the data by performance
            var sortedData = data.sort(function(a, b){return a['emp_result']-b['emp_result']});

            //Load ranking
            rankingList.empty();
            $.each(sortedData, function(key, value) {
                var empID = value['emp_id'],
                    empFullName = value['emp_full_name'],
                    empEmail = value['emp_email'],
                    empJobTitle = value['emp_job_title'],
                    empProfilePic = value['emp_profile_pic'],
                    empResult = value['emp_result'],
                    resultColor = '';
                if (empResult < 50) resultColor = 'red';
                else if (empResult >= 50 && empResult < 60) resultColor = 'yellow';
                else if (empResult > 60) resultColor = 'blue';
                var profilePicImg = $("<img>")
                    .attr('src', empProfilePic)
                    .attr('alt', 'Profile Picture');
                var productImgDiv = $("<div class='product-img'></div>").append(profilePicImg);
                var prodTitleSpan = $("<span class='product-title text-blue'></span>").html(empFullName);
                var prodDescHTML = '';
                if (empEmail != '') prodDescHTML += '<i class="fa fa-envelope-o"></i> ' + empEmail;
                if (empJobTitle != '') prodDescHTML += ' &nbsp; | &nbsp; <i class="fa fa-user-circle"></i> ' + empJobTitle;
                var productDescSpan = $("<span class='product-description'></span>").html(prodDescHTML);
                var productInfoDiv = $("<div class='product-info'></div>")
                    .append(prodTitleSpan)
                    .append(productDescSpan);
                var divNameSpan = $("<span class='progress-text'></span>").html('&nbsp;');
                var divResultSpan = $("<span class='progress-number'></span>").html(empResult + "%").addClass("text-" + resultColor); //<i class='fa fa-angle-down'></i>
                var progBar = $("<div class='progress-bar'></div>").attr("style", "width: " + empResult + "%").addClass("progress-bar-" + resultColor);
                var progBarDiv  = $("<div class='progress xs'></div>").css("margin-bottom", "5px").append(progBar);
                var progressGroup  = $("<div class='progress-group'></div>")
                    .append(divNameSpan)
                    .append(divResultSpan)
                    .append(progBarDiv);
                var listLink = $("<a href='#'></a>")
                    .attr('data-toggle', 'modal')
                    .attr('data-target', '#emp-year-performance-modal')
                    .attr('data-emp_id', empID)
                    .attr('data-emp_name', empFullName)
                    .append(productImgDiv)
                    .append(productInfoDiv)
                    .append(progressGroup);
                var listItem = $("<li class=item'></li>")
                    .append(listLink);
                rankingList.append(listItem);
            });
        });
}
//function to load employees performance on modal canvas (modal show)
function empPerOnShow(objTrigger, modalWin) {
    var divID = objTrigger.data('division_id');
    var divLevel = objTrigger.data('division_level');
    var divName = objTrigger.data('division_name');
    modalWin.find('#emp-list-modal-title').html(divName + ' Performance');
    var rankingList = modalWin.find('#emp-ranking-list');
    loadEmpListPerformance(rankingList, divLevel, divID);
}
//---------------------------------------
//- END EMPLOYEE LIST PERFORMANCE CHART -
//---------------------------------------