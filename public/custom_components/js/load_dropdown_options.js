function loadDropdownOptions(ddID, ddLabel, postTo, selectedOption, parentDDID, parentDDLabel, loadAll) {
    loadAll = loadAll || -1;
    $.post(postTo, { option: parentDDID, _token: $('input[name=_token]').val(), load_all: loadAll },
        function(data) {
            var dropdown = $('#'+ddID);
            var firstDDOption = "*** Select a " + ddLabel + " ***";
            if (loadAll == 1) firstDDOption = "*** Select a " + ddLabel + " ***";
            else {
                if (parentDDID > 0) firstDDOption = "*** Select a " + ddLabel + " ***";
                else if (parentDDID == '') firstDDOption = "*** Select a " + parentDDLabel + " First ***";
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
                if (selectedOption == value) ddOption.attr("selected", "selected")
                dropdown
                    .append(ddOption);
            });
        });
}