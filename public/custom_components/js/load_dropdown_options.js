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
function avilabledays(hr_id, levID, negDAYS) {
     postTo = '/api/leave/negativeDays/'+ hr_id + '/' + levID;
    $.get(postTo, { hr_id: hr_id, levID: levID },
        function(data) {
            var txtBalance = $('#'+negDAYS);
            txtBalance.val(data);
        });
}


/* function to load [Divisions] drop down options */
function loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo, selectFirstDiv, divHeadSpecific) {
    parentDDID = parentDDID || '';
    loadAll = loadAll || -1;
    incInactive = incInactive || -1;
    postTo = postTo || '/api/divisionsdropdown';
    selectFirstDiv = selectFirstDiv || 0;
    divHeadSpecific = divHeadSpecific || 0;

    var parentDDVal = $('#'+parentDDID).val();
    //console.log('divHeadSpecific = ' + divHeadSpecific);
    var parentDDLabel = $('label[for="' + parentDDID + '"]').html();
    var ddLabel = $('label[for="' + ddID + '"]').html();
    //console.log('calls the function with: ' + ddID + ', ' + ddLabel + ', ' + postTo + ', ' + selectedOption + ', ' + parentDDVal + ', ' + parentDDLabel + ', ' + incInactive + ', ' + loadAll + ', ');
    var divLvl = parseInt(ddID.substr(ddID.lastIndexOf("_") + 1));
    //console.log('division level: ' + divLvl);
    $.post(postTo, { div_level: divLvl, parent_id: parentDDVal, div_head_specific: divHeadSpecific, _token: $('input[name=_token]').val(), load_all: loadAll, inc_inactive: incInactive },
        function(data) {
            var dropdown = $('#'+ddID);
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
                dropdown.val($('#' + ddID + ' option:nth-child(2)').val());
                dropdown.trigger('change');
            }
        });
}

/* function to load child [Division] and employee (HR Person) drop down options */
function divDDOnChange(dropDownObj, hrPeopleDDID) {
    hrPeopleDDID = hrPeopleDDID || 'hr_person_id';

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
            loadDivDDOptions(childDDID, selectedOption, ddID, incInactive, loadAll, postTo);
            loadHRPeopleOptions(hrPeopleDDID, selectedOption, ddID, incInactive, loadAll, postTo);
            break;
        case 'division_level_4':
            childDDID = 'division_level_3';
            childDDLabel = $('label[for="' + childDDID + '"]').html();
            loadDivDDOptions(childDDID, selectedOption, ddID, incInactive, loadAll, postTo);
            loadHRPeopleOptions(hrPeopleDDID, selectedOption, ddID, incInactive, loadAll, postTo);
            break;
        case 'division_level_3':
            childDDID = 'division_level_2';
            childDDLabel = $('label[for="' + childDDID + '"]').html();
            loadDivDDOptions(childDDID, selectedOption, ddID, incInactive, loadAll, postTo);
            loadHRPeopleOptions(hrPeopleDDID, selectedOption, ddID, incInactive, loadAll, postTo);
            break;
        case 'division_level_2':
            //console.log("level two div changed");
            childDDID = 'division_level_1';
            childDDLabel = $('label[for="' + childDDID + '"]').html();
            loadDivDDOptions(childDDID, selectedOption, ddID, incInactive, loadAll, postTo);
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
function divDDEmpPWOnChange(dropDownObj, topTenList, bottomTenList, totNumEmp) {
    //hrPeopleDDID = hrPeopleDDID || 'hr_person_id';
    totNumEmp = totNumEmp || 0;

    var postTo = ''; var selectedOption = '';
    var ddID = dropDownObj.id;
    var parentDDVal = dropDownObj.value;
    var incInactive = -1; var loadAll = -1;
    var childDDID = ''; var childDDLabel = '';
    //var hrPeopleDDLabel = $('label[for="' + hrPeopleDDID + '"]').html();
    //console.log("function called by dd changed event: " + "ddID = " + ddID + ", parentDDVal = " + parentDDVal + ", topTenList = " + topTenList + ", totNumEmp = " + totNumEmp);

    if (parentDDVal > 0) {
        switch(ddID) {
            case 'division_level_5':
                childDDID = 'division_level_4';
                childDDLabel = $('label[for="' + childDDID + '"]').html();
                loadDivDDOptions(childDDID, selectedOption, ddID, incInactive, loadAll, postTo);
                //loadHRPeopleOptions(hrPeopleDDID, selectedOption, ddID, incInactive, loadAll, postTo);
                loadEmpListPerformance(topTenList, 5, parentDDVal, true);
                loadEmpListPerformance(bottomTenList, 5, parentDDVal, false, true, totNumEmp);
                break;
            case 'division_level_4':
                childDDID = 'division_level_3';
                childDDLabel = $('label[for="' + childDDID + '"]').html();
                loadDivDDOptions(childDDID, selectedOption, ddID, incInactive, loadAll, postTo);
                //loadHRPeopleOptions(hrPeopleDDID, selectedOption, ddID, incInactive, loadAll, postTo);
                loadEmpListPerformance(topTenList, 4, parentDDVal, true);
                loadEmpListPerformance(bottomTenList, 4, parentDDVal, false, true, totNumEmp);
                break;
            case 'division_level_3':
                childDDID = 'division_level_2';
                childDDLabel = $('label[for="' + childDDID + '"]').html();
                loadDivDDOptions(childDDID, selectedOption, ddID, incInactive, loadAll, postTo);
                //loadHRPeopleOptions(hrPeopleDDID, selectedOption, ddID, incInactive, loadAll, postTo);
                loadEmpListPerformance(topTenList, 3, parentDDVal, true);
                loadEmpListPerformance(bottomTenList, 3, parentDDVal, false, true, totNumEmp);
                break;
            case 'division_level_2':
                //console.log("level two div changed. parentDDVal = " + parentDDVal);
                childDDID = 'division_level_1';
                childDDLabel = $('label[for="' + childDDID + '"]').html();
                loadDivDDOptions(childDDID, selectedOption, ddID, incInactive, loadAll, postTo);
                //loadHRPeopleOptions(hrPeopleDDID, selectedOption, ddID, incInactive, loadAll, postTo);
                loadEmpListPerformance(topTenList, 2, parentDDVal, true);
                loadEmpListPerformance(bottomTenList, 2, parentDDVal, false, true, totNumEmp);
                break;
            case 'division_level_1':
                //loadHRPeopleOptions(hrPeopleDDID, selectedOption, ddID, incInactive, loadAll, postTo);
                loadEmpListPerformance(topTenList, 1, parentDDVal, true);
                loadEmpListPerformance(bottomTenList, 1, parentDDVal, false, true, totNumEmp);
                break;
            default:
                return null;
                break;
        }
    } else {
        topTenList.empty();
        bottomTenList.empty();
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