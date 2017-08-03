//function to draw the chart on the canvas
function startTask(taslIDppraisedMonthList) {
    loadingWheel = loadingWheel || null;
    empAppraisedMonthList = empAppraisedMonthList || null;

    // Get context with jQuery - using jQuery's .get() method.
    var empPerfChartCanvas = chartCanvas.get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var empPerfChart = new Chart(empPerfChartCanvas);
    //char labels (months)
    var monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
    //get chart data with ajax
    $.get("/api/emp/" + empID + "/monthly-performance",
	function(data) {
		var chartData = perfChartData(data, monthLabels);

		//hide loading wheel
		if (loadingWheel != null) loadingWheel.hide();

		//Create the bar chart
		empPerfChart.Bar(chartData, chartOptions);

		//load appraised month list
		if (empAppraisedMonthList != null) {
			empAppraisedMonthList.empty();
			var monthsArray = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
			$.each(data, function(key, value) {
				var monthName = monthsArray[key],
					monthResult = value,
					resultColor = '';
				if (monthResult < 50) resultColor = 'red';
				else if (monthResult >= 50 && monthResult < 60) resultColor = 'yellow';
				else if (monthResult > 60) resultColor = 'blue';
				var divNameSpan = $("<span class='progress-text'></span>").html(monthName);
				var divResultSpan = $("<span class='progress-number'></span>").html(monthResult + "%").addClass("text-" + resultColor); //<i class='fa fa-angle-down'></i>
				var progBar = $("<div class='progress-bar'></div>").attr("style", "width: " + monthResult + "%").addClass("progress-bar-" + resultColor);
				var progBarDiv  = $("<div class='progress xs'></div>").css("margin-bottom", "5px").append(progBar);
				var progressGroup  = $("<div class='progress-group'></div>")
					.append(divNameSpan)
					.append(divResultSpan)
					.append(progBarDiv);
				var childModalID = 'emp-performance-per-kpa-modal';
				var listLink = $("<a></a>")
					.attr('href', '/appraisal/search_results/' + empID + '/' + monthName)
					//.attr('data-toggle', 'modal')
					//.attr('data-target', '#' + childModalID)
					.attr('data-hr_id', empID)
					.attr('data-appraisal_month', monthName)
					.append(progressGroup);
				var listItem = $("<li></li>")
					.append(listLink);
				empAppraisedMonthList.append(listItem);
			});
		}
	});
}

/* function to load child [Division] and employees Tasks */
function divDDEmpTasksOnChange(dropDownObj, meetingList, inductionList, prtContainer, loadingWheel) {
    
    loadingWheel = loadingWheel || null;
	var postTo = ''; var selectedOption = '';
    var ddID = dropDownObj.id;
    var parentDDVal = dropDownObj.value;
    var incInactive = -1; var loadAll = -1;
    var childDDID = ''; var childDDLabel = '';
	prtContainer = prtContainer || $(document);
	//show loading wheel
    if (loadingWheel != null) loadingWheel.show();
	
    if (parentDDVal > 0) {
        switch(ddID) {
            case 'division_level_5':
                childDDID = 'division_level_4';
                childDDLabel = $('label[for="' + childDDID + '"]').html();
                loadDivDDOptions(childDDID, selectedOption, ddID, incInactive, loadAll, postTo, null, null, prtContainer);
                loadEmpListTasks(meetingList, 5, parentDDVal, true);
                loadEmpListTasks(inductionList, 5, parentDDVal, false, true, loadingWheel);
                break;
            case 'division_level_4':
                childDDID = 'division_level_3';
                childDDLabel = $('label[for="' + childDDID + '"]').html();
                loadDivDDOptions(childDDID, selectedOption, ddID, incInactive, loadAll, postTo, null, null, prtContainer);
                loadEmpListTasks(meetingList, 4, parentDDVal, true);
                loadEmpListTasks(inductionList, 4, parentDDVal, false, true, loadingWheel);
                break;
            case 'division_level_3':
                childDDID = 'division_level_2';
                childDDLabel = $('label[for="' + childDDID + '"]').html();
                loadDivDDOptions(childDDID, selectedOption, ddID, incInactive, loadAll, postTo, null, null, prtContainer);
                loadEmpListTasks(meetingList, 3, parentDDVal, true);
                loadEmpListTasks(inductionList, 3, parentDDVal, false, true, loadingWheel);
                break;
            case 'division_level_2':
                childDDID = 'division_level_1';
                childDDLabel = $('label[for="' + childDDID + '"]').html();
                loadDivDDOptions(childDDID, selectedOption, ddID, incInactive, loadAll, postTo, null, null, prtContainer);
                loadEmpListTasks(meetingList, 2, parentDDVal, true);
                loadEmpListTasks(inductionList, 2, parentDDVal, false, true, loadingWheel);
                break;
            case 'division_level_1':
                loadEmpListTasks(meetingList, 1, parentDDVal, true);
                loadEmpListTasks(inductionList, 1, parentDDVal, false, true, loadingWheel);
                break;
            default:
                return null;
                break;
        }
    } else {
        meetingList.empty();
        inductionList.empty();
		//hide loading wheel
        if (loadingWheel != null) loadingWheel.hide();
    }
}
