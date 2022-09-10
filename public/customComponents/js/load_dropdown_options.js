/* function to calculate day requested from dates*/
function numberdays(datesend, availdaystxt, availdaystxts) {
	var mystr = datesend;
	var myarr = mystr.split("-");
	var dateFrom = myarr[0] ;
	var dateTo = myarr[1];
	var dateFrom = dateFrom.replace("/", "-");
	var dateFrom = dateFrom.replace("/", "-");
	var dateTo = dateTo.replace("/", "-");
	var dateTo = dateTo.replace("/", "-");

    postTo = '/api/leave/calleavedays/'+ dateFrom + '/' + dateTo;
		$.get(postTo, { datesend: datesend},
        function(data) {
            var txtBalance = $('#'+availdaystxt);
            var txtBalances = $('#'+availdaystxts);
            txtBalance.val(data);
            txtBalances.val(data);
        });
}
/* function to get leave balance */
function avilabledays(hr_id, levID, availdaystxt) {
     postTo = '/api/leave/availableBalance/'+ hr_id + '/' + levID;
    $.get(postTo, { hr_id: hr_id, levID: levID },
        function(data) {
            var txtBalance = $('#'+availdaystxt);
            txtBalance.val(data);
        });
}
/* function to get negative leave days */
function negativedays(hr_id, levID, negDAYS) {
     postTo = '/api/leave/negativeDays/'+ hr_id + '/' + levID;
    $.get(postTo, { hr_id: hr_id, levID: levID },
        function(data) {
            var txtBalance = $('#'+negDAYS);
            txtBalance.val(data);
        });
}
/* function to load [Divisions] drop down options */
function loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo, selectFirstDiv, divHeadSpecific, parentContainer) {
    parentDDID = parentDDID || '';
    loadAll = loadAll || -1;
    incInactive = incInactive || -1;
    postTo = postTo || '/api/divisionsdropdown';
    selectFirstDiv = selectFirstDiv || 0;
    divHeadSpecific = divHeadSpecific || 0;
    parentContainer = parentContainer || $(document);

    var parentDDVal = 0;
    if (parentDDID != '') parentDDVal = parentContainer.find('#' + parentDDID).val();
    //console.log('divHeadSpecific = ' + divHeadSpecific);
    var parentDDLabel = parentContainer.find('label[for="' + parentDDID + '"]').html();
    var ddLabel = parentContainer.find('label[for="' + ddID + '"]').html();
    //console.log('calls the function with: ' + ddID + ', ' + ddLabel + ', ' + postTo + ', ' + selectedOption + ', ' + parentDDVal + ', ' + parentDDLabel + ', ' + incInactive + ', ' + loadAll + ', ');
    var divLvl = parseInt(ddID.substr(ddID.lastIndexOf("_") + 1));
    //console.log('division level: ' + divLvl);
    $.post(postTo, { div_level: divLvl, parent_id: parentDDVal, div_head_specific: divHeadSpecific, _token: $('input[name=_token]').val(), load_all: loadAll, inc_inactive: incInactive },
        function(data) {
            var dropdown = parentContainer.find('#'+ddID);
            var firstDDOption = "*** Select a " + ddLabel + " ***";
            if (loadAll == 1) {
                //console.log('load all is: ' + loadAll);
                firstDDOption = "*** Select a " + ddLabel + " ***";
            }
            else if (loadAll == -1) {
                //console.log('load all is: ' + loadAll);
                if (parentDDVal > 0) firstDDOption = "*** Select a " + ddLabel + " ***";
                else firstDDOption = "*** Select a " + parentDDLabel + " First ***"; //if (parentDDVal == '')
            }
            dropdown.empty();
            dropdown
                .append($("<option></option>")
                    .attr("value",'')
                    .text(firstDDOption));
            $.each(data, function(key, value) {
                var ddOption = $("<option></option>")
                    .attr("value",value)
                    .text(key);
                if (selectedOption == value) ddOption.attr("selected", "selected");
                dropdown
                    .append(ddOption);
            });
            if (selectFirstDiv == 1) { //select the first division
                dropdown.val(parentContainer.find('#' + ddID + ' option:nth-child(2)').val());
                dropdown.trigger('change');
            }
        });
}

/* function to load child [Division] and employee (HR Person) drop down options */
function divDDOnChange(dropDownObj, hrPeopleDDID, parentContainer) {
    hrPeopleDDID = hrPeopleDDID || 'hr_person_id';
    parentContainer = $('#'+parentContainer) || $(document);

    var postTo = ''; var selectedOption = '';
    var ddID = dropDownObj.id;
    var parentDDVal = dropDownObj.value;
    var incInactive = -1; var loadAll = -1;
    var childDDID = ''; var childDDLabel = '';
    var hrPeopleDDLabel = $('label[for="' + hrPeopleDDID + '"]').html();
    //console.log("function called by dd changed event: " + "ddID = " + ddID + ", parentDDVal = " + parentDDVal + ", parentDDLabel = " + parentDDLabel);

    switch(ddID) {
        case 'division_level_5':
            childDDID = 'division_level_4';
            childDDLabel = $('label[for="' + childDDID + '"]').html();
            loadDivDDOptions(childDDID, selectedOption, ddID, incInactive, loadAll, postTo, null, null, parentContainer);
            loadHRPeopleOptions(hrPeopleDDID, selectedOption, ddID, incInactive, loadAll, postTo);
            break;
        case 'division_level_4':
            childDDID = 'division_level_3';
            childDDLabel = $('label[for="' + childDDID + '"]').html();
            loadDivDDOptions(childDDID, selectedOption, ddID, incInactive, loadAll, postTo, null, null, parentContainer);
            loadHRPeopleOptions(hrPeopleDDID, selectedOption, ddID, incInactive, loadAll, postTo);
            break;
        case 'division_level_3':
            childDDID = 'division_level_2';
            childDDLabel = $('label[for="' + childDDID + '"]').html();
            loadDivDDOptions(childDDID, selectedOption, ddID, incInactive, loadAll, postTo, null, null, parentContainer);
            loadHRPeopleOptions(hrPeopleDDID, selectedOption, ddID, incInactive, loadAll, postTo);
            break;
        case 'division_level_2':
            //console.log("level two div changed");
            childDDID = 'division_level_1';
            childDDLabel = $('label[for="' + childDDID + '"]').html();
            loadDivDDOptions(childDDID, selectedOption, ddID, incInactive, loadAll, postTo, null, null, parentContainer);
            loadHRPeopleOptions(hrPeopleDDID, selectedOption, ddID, incInactive, loadAll, postTo);
            break;
        case 'division_level_1':
            loadHRPeopleOptions(hrPeopleDDID, selectedOption, ddID, incInactive, loadAll, postTo);
            break;
        default:
            return null;
            break;
    }
}

/* function to load child [Division] and employees performance ranking */
function divDDEmpPWOnChange(dropDownObj, topTenList, bottomTenList, totNumEmp, loadingWheel) {
    //hrPeopleDDID = hrPeopleDDID || 'hr_person_id';
    totNumEmp = totNumEmp || 0;
    loadingWheel = loadingWheel || null;

    var postTo = ''; var selectedOption = '';
    var ddID = dropDownObj.id;
    var parentDDVal = dropDownObj.value;
    var incInactive = -1; var loadAll = -1;
    var childDDID = ''; var childDDLabel = '';
    //var hrPeopleDDLabel = $('label[for="' + hrPeopleDDID + '"]').html();
    //console.log("function called by dd changed event: " + "ddID = " + ddID + ", parentDDVal = " + parentDDVal + ", topTenList = " + topTenList + ", totNumEmp = " + totNumEmp);

    //show loading wheel
    if (loadingWheel != null) loadingWheel.show();

    //load the employee performance ranking list
    if (parentDDVal > 0) {
        switch(ddID) {
            case 'division_level_5':
                childDDID = 'division_level_4';
                childDDLabel = $('label[for="' + childDDID + '"]').html();
                loadDivDDOptions(childDDID, selectedOption, ddID, incInactive, loadAll, postTo);
                //loadHRPeopleOptions(hrPeopleDDID, selectedOption, ddID, incInactive, loadAll, postTo);
                loadEmpListPerformance(topTenList, 5, parentDDVal, true);
                loadEmpListPerformance(bottomTenList, 5, parentDDVal, false, true, totNumEmp, null, loadingWheel);
                break;
            case 'division_level_4':
                childDDID = 'division_level_3';
                childDDLabel = $('label[for="' + childDDID + '"]').html();
                loadDivDDOptions(childDDID, selectedOption, ddID, incInactive, loadAll, postTo);
                //loadHRPeopleOptions(hrPeopleDDID, selectedOption, ddID, incInactive, loadAll, postTo);
                loadEmpListPerformance(topTenList, 4, parentDDVal, true);
                loadEmpListPerformance(bottomTenList, 4, parentDDVal, false, true, totNumEmp, null, loadingWheel);
                break;
            case 'division_level_3':
                childDDID = 'division_level_2';
                childDDLabel = $('label[for="' + childDDID + '"]').html();
                loadDivDDOptions(childDDID, selectedOption, ddID, incInactive, loadAll, postTo);
                //loadHRPeopleOptions(hrPeopleDDID, selectedOption, ddID, incInactive, loadAll, postTo);
                loadEmpListPerformance(topTenList, 3, parentDDVal, true);
                loadEmpListPerformance(bottomTenList, 3, parentDDVal, false, true, totNumEmp, null, loadingWheel);
                break;
            case 'division_level_2':
                //console.log("level two div changed. parentDDVal = " + parentDDVal);
                childDDID = 'division_level_1';
                childDDLabel = $('label[for="' + childDDID + '"]').html();
                loadDivDDOptions(childDDID, selectedOption, ddID, incInactive, loadAll, postTo);
                //loadHRPeopleOptions(hrPeopleDDID, selectedOption, ddID, incInactive, loadAll, postTo);
                loadEmpListPerformance(topTenList, 2, parentDDVal, true);
                loadEmpListPerformance(bottomTenList, 2, parentDDVal, false, true, totNumEmp, null, loadingWheel);
                break;
            case 'division_level_1':
                //loadHRPeopleOptions(hrPeopleDDID, selectedOption, ddID, incInactive, loadAll, postTo);
                loadEmpListPerformance(topTenList, 1, parentDDVal, true);
                loadEmpListPerformance(bottomTenList, 1, parentDDVal, false, true, totNumEmp, null, loadingWheel);
                break;
            default:
                return null;
                break;
        }
    } else {
        topTenList.empty();
        bottomTenList.empty();

        //hide loading wheel
        if (loadingWheel != null) loadingWheel.hide();
    }
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

/* function to load HR People drop down options */
function loadHRPeopleOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo) {
    loadAll = loadAll || -1;
    incInactive = incInactive || -1;
    postTo = postTo || '/api/hrpeopledropdown';

    var parentDDVal = $('#'+parentDDID).val();
    var ddLabel = $('label[for="' + ddID + '"]').html();
    var divLvl = parseInt(parentDDID.substr(parentDDID.lastIndexOf("_") + 1));
    $.post(postTo, { div_level: divLvl, div_val: parentDDVal, _token: $('input[name=_token]').val(), load_all: loadAll, inc_inactive: incInactive },
        function(data) {
            var dropdown = $('#'+ddID);
            var firstDDOption = "*** Select an " + ddLabel + " ***";
            dropdown.empty();
            dropdown
                .append($("<option></option>")
                    .attr("value",'')
                    .text(firstDDOption));
            $.each(data, function(key, value) {
                var ddOption = $("<option></option>")
                    .attr("value",value)
                    .text(key);
                if (selectedOption == value) ddOption.attr("selected", "selected");
                dropdown
                    .append(ddOption);
            });
        });
}
/* function to load kpa drop down options */
function loadkpaOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo) {
    loadAll = loadAll || -1;
    incInactive = incInactive || -1;
    postTo = postTo || '/api/kpadropdown';

    var parentDDVal = $('#'+parentDDID).val();
    var ddLabel = $('label[for="' + ddID + '"]').html();
    var divLvl = parseInt(parentDDID.substr(parentDDID.lastIndexOf("_") + 1));
    $.post(postTo, { div_level: divLvl, div_val: parentDDVal, _token: $('input[name=_token]').val(), load_all: loadAll, inc_inactive: incInactive },
        function(data) {
            var dropdown = $('#'+ddID);
            var firstDDOption = "*** Select a " + ddLabel + " ***";
            dropdown.empty();
            dropdown
                .append($("<option></option>")
                    .attr("value",'')
                    .text(firstDDOption));
            $.each(data, function(key, value) {
                var ddOption = $("<option></option>")
                    .attr("value",value)
                    .text(key);
                if (selectedOption == value) ddOption.attr("selected", "selected");
                dropdown
                    .append(ddOption);
            });
        });
}

/* function to load child [Division] and employee (HR Person) drop down options */
function categoryOnChange(dropDownObj, hrPeopleDDID) {
    var postTo = ''; var selectedOption = '';
    var ddID = dropDownObj.id;
    var parentDDVal = dropDownObj.value;
    var incInactive = -1; var loadAll = -1;
    var childDDID = ''; var childDDLabel = '';
    //console.log("function called by dd changed event: " + "ddID = " + ddID + ", parentDDVal = " + parentDDVal + ", parentDDLabel = " + parentDDLabel);
	childDDID = 'kpa_id';
    childDDLabel = $('label[for="' + childDDID + '"]').html();
	loadkpaOptions(ddID,selectedOption, incInactive, loadAll, postTo);
}

/* function to load contact people drop down options */
function contactCompanyDDOnChange(dropDownObj, contactPeopleDDID, selectedOption) {
    //console.log('gets here');
    contactPeopleDDID = contactPeopleDDID || 'contact_person_id';
    selectedOption = selectedOption || '';

    var postTo = '';
    //var ddID = dropDownObj.id;
    var companyID = dropDownObj.value;
    var incInactive = -1;
    var loadAll = -1;
    //var childDDID = '';
    //var childDDLabel = '';
    //var hrPeopleDDLabel = $('label[for="' + hrPeopleDDID + '"]').html();
    //console.log("function called by dd changed event: " + "ddID = " + ddID + ", parentDDVal = " + parentDDVal + ", parentDDLabel = " + parentDDLabel);

    loadContactPeopleOptions(contactPeopleDDID, selectedOption, companyID);
}

/* function to load Contact People drop down options */
function loadContactPeopleOptions(ddID, selectedOption, companyID, incInactive, loadAll, postTo) {
    loadAll = loadAll || -1;
    incInactive = incInactive || -1;
    postTo = postTo || '/api/contact-people-dropdown';

    //var parentDDVal = $('#'+parentDDID).val();
    //var ddLabel = 'a Company';
    //var divLvl = parseInt(parentDDID.substr(parentDDID.lastIndexOf("_") + 1));
    $.post(postTo, { company_id: companyID, _token: $('input[name=_token]').val(), load_all: loadAll, inc_inactive: incInactive },
        function(data) {
            var dropdown = $('#'+ddID);
            var firstDDOption = "*** Select a Person ***";
            if (companyID == '') firstDDOption = "*** Select a Company First ***";
            dropdown.empty();
            dropdown
                .append($("<option></option>")
                    .attr("value",'')
                    .text(firstDDOption));
            if (companyID != '') {
                $.each(data, function(key, value) {
                    var ddOption = $("<option></option>")
                        .attr("value",value)
                        .text(key);
                    if (selectedOption == value) ddOption.attr("selected", "selected");
                    dropdown
                        .append(ddOption);
                });
            }
        });
}
// **********************************************
/* function to load vehicle make  drop down options */
    function vehiclemakeDDOnChange(dropDownObj, vehiclemomdeDDID, selectedOption) {
       // console.log('gets here');
        vehiclemomdeDDID = vehiclemomdeDDID || 'vehiclemodel_id';
        selectedOption = selectedOption || '';

        var postTo = '';
        //var ddID = dropDownObj.id;
        var makeID = dropDownObj.value;
        var incInactive = -1;
        var loadAll = -1;
        loadVehicleModelOptions(vehiclemomdeDDID, selectedOption, makeID);
    }

/* function to load Contact People drop down options */
function loadVehicleModelOptions(ddID, selectedOption, makeID, incInactive, loadAll, postTo) {
    loadAll = loadAll || -1;
    incInactive = incInactive || -1;
    postTo = postTo || '/api/vehiclemodeldropdown';

    //var parentDDVal = $('#'+parentDDID).val();
    //var ddLabel = 'a Company';
    //var divLvl = parseInt(parentDDID.substr(parentDDID.lastIndexOf("_") + 1));
    $.post(postTo, { vehiclemake_id: makeID, _token: $('input[name=_token]').val(), load_all: loadAll, inc_inactive: incInactive },
        function(data) {
            var dropdown = $('#'+ddID);
            var firstDDOption = "*** Select a Vehicle Model ***";
            if (makeID == '') firstDDOption = "*** Select a make First ***";
            dropdown.empty();
            dropdown
                .append($("<option></option>")
                    .attr("value",'')
                    .text(firstDDOption));
            if (makeID != '') {
                $.each(data, function(key, value) {
                    var ddOption = $("<option></option>")
                        .attr("value",value)
                        .text(key);
                    if (selectedOption == value) ddOption.attr("selected", "selected");
                    dropdown
                        .append(ddOption);
                });
            }
        });
}

// // **********************************************
    /* function to load job card categories drop down options */
    function productcategoryDDOnChange(dropDownObj, productCategoryDDID, selectedOption) {
        productCategoryDDID = productCategoryDDID || 'product_id';
        selectedOption = selectedOption || '';

        var postTo = '';
        //var ddID = dropDownObj.id;
        var procategoryID = dropDownObj.value;
        var incInactive = -1;
        var loadAll = -1;
        loadproductOptions(productCategoryDDID, selectedOption, procategoryID);
    }

    /* function to load Contact People drop down options */
    function loadproductOptions(ddID, selectedOption, procategoryID, incInactive, loadAll, postTo) {
        loadAll = loadAll || -1;
        incInactive = incInactive || -1;
        postTo = postTo || '/api/productCategorydropdown';
        $.post(postTo, { category_id: procategoryID, _token: $('input[name=_token]').val(), load_all: loadAll, inc_inactive: incInactive },
            function(data) {
                var dropdown = $('#'+ddID);
                var firstDDOption = "*** Select a Product ***";
                if (procategoryID == '') firstDDOption = "*** Select a Catergory First ***";
                dropdown.empty();
                dropdown
                    .append($("<option></option>")
                        .attr("value",'')
                        .text(firstDDOption));
                if (procategoryID != '') {
                    $.each(data, function(key, value) {
                        var ddOption = $("<option></option>")
                            .attr("value",value)
                            .text(key);
                        if (selectedOption == value) ddOption.attr("selected", "selected");
                        dropdown
                            .append(ddOption);
                    });
                }
            });
    }
	
/* function to load child [stock] drop down options */
function stockDDOnChange(dropDownObj, parentContainer) {
    parentContainer = $('#'+parentContainer) || $(document);

    var postTo = ''; var selectedOption = '';
    var ddID = dropDownObj.id;
    var parentDDVal = dropDownObj.value;
    var incInactive = -1; var loadAll = -1;
    var childDDID = ''; var childDDLabel = '';
	//console.log(ddID);
    switch(ddID) {
        case 'stock_level_5':
            childDDID = 'stock_level_4';
            childDDLabel = $('label[for="' + childDDID + '"]').html();
            loadStockDDOptions(childDDID, selectedOption, ddID, incInactive, loadAll, postTo, null, null, parentContainer);
            break;
        case 'stock_level_4':
            childDDID = 'stock_level_3';
            childDDLabel = $('label[for="' + childDDID + '"]').html();
            loadStockDDOptions(childDDID, selectedOption, ddID, incInactive, loadAll, postTo, null, null, parentContainer);
            break;
        case 'stock_level_3':
            childDDID = 'stock_level_2';
            childDDLabel = $('label[for="' + childDDID + '"]').html();
            loadStockDDOptions(childDDID, selectedOption, ddID, incInactive, loadAll, postTo, null, null, parentContainer);
            break;
        case 'stock_level_2':
            //console.log("level two div changed");
            childDDID = 'stock_level_1';
            childDDLabel = $('label[for="' + childDDID + '"]').html();
            loadStockDDOptions(childDDID, selectedOption, ddID, incInactive, loadAll, postTo, null, null, parentContainer);
            break;
        default:
            return null;
            break;
    }
}
/* function to load [Stock] drop down options */
function loadStockDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo, selectFirstDiv, divHeadSpecific, parentContainer) {
    parentDDID = parentDDID || '';
    loadAll = loadAll || -1;
    incInactive = incInactive || -1;
    postTo = postTo || '/api/stockdropdown';
    selectFirstDiv = selectFirstDiv || 0;
    divHeadSpecific = divHeadSpecific || 0;
    parentContainer = parentContainer || $(document);

    var parentDDVal = 0;
    if (parentDDID != '') parentDDVal = parentContainer.find('#' + parentDDID).val();
    //console.log('divHeadSpecific = ' + divHeadSpecific);
    var parentDDLabel = parentContainer.find('label[for="' + parentDDID + '"]').html();
    var ddLabel = parentContainer.find('label[for="' + ddID + '"]').html();
    //console.log('calls the function with: ' + ddID + ', ' + ddLabel + ', ' + postTo + ', ' + selectedOption + ', ' + parentDDVal + ', ' + parentDDLabel + ', ' + incInactive + ', ' + loadAll + ', ');
    var divLvl = parseInt(ddID.substr(ddID.lastIndexOf("_") + 1));
    //console.log('division level: ' + divLvl);
    $.post(postTo, { div_level: divLvl, parent_id: parentDDVal, div_head_specific: divHeadSpecific, _token: $('input[name=_token]').val(), load_all: loadAll, inc_inactive: incInactive },
        function(data) {
            var dropdown = parentContainer.find('#'+ddID);
            var firstDDOption = "*** Select a " + ddLabel + " ***";
            if (loadAll == 1) {
                //console.log('load all is: ' + loadAll);
                firstDDOption = "*** Select a " + ddLabel + " ***";
            }
            else if (loadAll == -1) {
                //console.log('load all is: ' + loadAll);
                if (parentDDVal > 0) firstDDOption = "*** Select a " + ddLabel + " ***";
                else firstDDOption = "*** Select a " + parentDDLabel + " First ***"; //if (parentDDVal == '')
            }
            dropdown.empty();
            dropdown
                .append($("<option></option>")
                    .attr("value",'')
                    .text(firstDDOption));
            $.each(data, function(key, value) {
                var ddOption = $("<option></option>")
                    .attr("value",value)
                    .text(key);
                if (selectedOption == value) ddOption.attr("selected", "selected");
                dropdown
                    .append(ddOption);
            });
            if (selectFirstDiv == 1) { //select the first division
                dropdown.val(parentContainer.find('#' + ddID + ' option:nth-child(2)').val());
                dropdown.trigger('change');
            }
        });
}