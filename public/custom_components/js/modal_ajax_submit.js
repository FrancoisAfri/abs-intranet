function modalAjaxSubmit(strUrl, objData, modalID,submitBtnID, redirectUrl, successMsgTitle, successMsg, formMethod) {
    successMsgTitle = successMsgTitle || 'Success!';
    successMsg = successMsg || 'Action Performed Successfully.';
    redirectUrl = redirectUrl || -1;
    formMethod = formMethod || 'POST';
    var myModal = $('#'+modalID);
    $.ajax({
        method: formMethod,
        url: strUrl,
        data: objData,
        success: function(success) {
            myModal.find('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups
            //$('form[name=set-rates-form]').trigger('reset'); //Reset the form

            var successHTML = '<button type="button" id="close-invalid-input-alert" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-check"></i> ' + successMsgTitle + '</h4>';
            successHTML += successMsg;
            myModal.find('#success-alert').addClass('alert alert-success alert-dismissible')
                .fadeIn()
                .html(successHTML);

            //auto hide modal after 5 seconds
            myModal.alert();
            window.setTimeout(function() { myModal.modal('hide'); }, 5000);

            //auto close alert after 5 seconds
            myModal.find("#success-alert").alert();
            window.setTimeout(function() { myModal.find("#success-alert").fadeOut('slow'); }, 5000);

            //hide modal submit button after success action
            myModal.find("#"+submitBtnID).hide();

            //redirect after success action on modal hide(close)
            myModal.on('hidden.bs.modal', function () {
                if (redirectUrl !== -1) {
                    window.location.href = redirectUrl;
                }
            })
        },
        error: function(xhr) {
            if(xhr.status === 422) {
                var errors = xhr.responseJSON; //get the errors response data

                myModal.find('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups

                var errorsHTML = '<button type="button" id="close-invalid-input-alert" class="close" aria-hidden="true">&times;</button><h4><i class="icon fa fa-ban"></i> Invalid Input(s)!</h4><ul>';
                $.each(errors, function (key, value) {
                    errorsHTML += '<li>' + value[0] + '</li>'; //shows only the first error.
                    $('#'+key).closest('.form-group')
                        .addClass('has-error'); //Add the has error class to form-groups with errors
                });
                errorsHTML += '</ul>';

                myModal.find('#invalid-input-alert').addClass('alert alert-danger alert-dismissible')
                    .fadeIn()
                    .html(errorsHTML);

                //autoclose alert after 7 seconds
                myModal.find("#invalid-input-alert").alert();
                window.setTimeout(function() { myModal.find("#invalid-input-alert").fadeOut('slow'); }, 7000);

                //Close btn click
                myModal.find('#close-invalid-input-alert').on('click', function () {
                    myModal.find("#invalid-input-alert").fadeOut('slow');
                });
            }
        }
    });
}

function modalFormDataSubmit(strUrl, formName, modalID,submitBtnID, redirectUrl, successMsgTitle, successMsg, formMethod) {
    successMsgTitle = successMsgTitle || 'Success!';
    successMsg = successMsg || 'Action Performed Successfully.';
    redirectUrl = redirectUrl || -1;
    formMethod = formMethod || 'POST';

    var myModal = $('#'+modalID),
        csrfToken = myModal.find('input[name=_token]').val(),
        oData = new FormData(document.forms.namedItem(formName));

    /*for (var pair of oData.entries()) {
        console.log(pair[0]+ ', ' + pair[1]);
    }*/

    $.ajax({
        method: formMethod,
        url: strUrl,
        data: oData,
        dataType: 'json',
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function(success) {
            myModal.find('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups
            //$('form[name=set-rates-form]').trigger('reset'); //Reset the form

            var successHTML = '<button type="button" id="close-invalid-input-alert" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-check"></i> ' + successMsgTitle + '</h4>';
            successHTML += successMsg;
            myModal.find('#success-alert').addClass('alert alert-success alert-dismissible')
                .fadeIn()
                .html(successHTML);

            //auto hide modal after 5 seconds
            myModal.alert();
            window.setTimeout(function() { myModal.modal('hide'); }, 5000);

            //auto close alert after 5 seconds
            myModal.find("#success-alert").alert();
            window.setTimeout(function() { myModal.find("#success-alert").fadeOut('slow'); }, 5000);

            //hide modal submit button after success action
            myModal.find("#"+submitBtnID).hide();

            //redirect after success action on modal hide(close)
            myModal.on('hidden.bs.modal', function () {
                if (redirectUrl !== -1) {
                    window.location.href = redirectUrl;
                }
            })
        },
        error: function(xhr) {
            if(xhr.status === 422) {
                var errors = xhr.responseJSON; //get the errors response data

                myModal.find('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups

                var errorsHTML = '<button type="button" id="close-invalid-input-alert" class="close" aria-hidden="true">&times;</button><h4><i class="icon fa fa-ban"></i> Invalid Input(s)!</h4><ul>';
                $.each(errors, function (key, value) {
                    errorsHTML += '<li>' + value[0] + '</li>'; //shows only the first error.
                    $('#'+key).closest('.form-group')
                        .addClass('has-error'); //Add the has error class to form-groups with errors
                });
                errorsHTML += '</ul>';

                myModal.find('#invalid-input-alert').addClass('alert alert-danger alert-dismissible')
                    .fadeIn()
                    .html(errorsHTML);

                //autoclose alert after 7 seconds
                myModal.find("#invalid-input-alert").alert();
                window.setTimeout(function() { myModal.find("#invalid-input-alert").fadeOut('slow'); }, 7000);

                //Close btn click
                myModal.find('#close-invalid-input-alert').on('click', function () {
                    myModal.find("#invalid-input-alert").fadeOut('slow');
                });
            }
        }
    });
}

function deleteThis(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, formMethod) {
    formMethod = formMethod || 'POST';

    var myModal = $('#'+modalID),
        myForm = myModal.find('form[name=' + formName + ']'),
        successHTML = '<button type="button" id="close-invalid-input-alert" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-check"></i> ' + successMsgTitle + '</h4>',
        errorsHTML = '<button type="button" id="close-invalid-input-alert" class="close" aria-hidden="true">&times;</button><h4><i class="icon fa fa-ban"></i> Invalid Input(s)!</h4><ul>';
        //oData = new FormData(myForm);
        var oData = new FormData();
        oData.append("input_one", 'Value one');
        oData.append("input_two", 'Value Two');

    myForm.on('submit', function (ev) {
        ev.preventDefault();
    });

    oData.append("CustomField", "This is some extra data"); //remove this line
    console.log('Send the form using the modalFormDataSubmit method. FromDataObj: ' + oData['input_one']);
    $.each(oData, function (key, value) {
        console.log('Key: ' + key + 'Val: ' + value);
    });

    /*
    var oReq = new XMLHttpRequest();
    //_token: myModal.find('input[name=_token]').val();
    var csrfToken = myModal.find('input[name=_token]').val();
    oReq.open(formMethod, strUrl, true);
    oReq.setRequestHeader('X-CSRF-TOKEN', csrfToken);
    //oReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); //check if can be removed, and SESSION_DOMAIN in .env
    oReq.onload = function(oEvent) {
        if (oReq.status == 200) {
            //oOutput.innerHTML = "Uploaded!";
            myModal.find('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups
            //$('form[name=set-rates-form]').trigger('reset'); //Reset the form

            successHTML += successMsg;
            myModal.find('#success-alert').addClass('alert alert-success alert-dismissible')
                .fadeIn()
                .html(successHTML);

            //auto hide modal after 5 seconds
            myModal.alert();
            window.setTimeout(function() { myModal.modal('hide'); }, 5000);

            //auto close alert after 5 seconds
            myModal.find("#success-alert").alert();
            window.setTimeout(function() { myModal.find("#success-alert").fadeOut('slow'); }, 5000);

            //hide modal submit button after success action
            myModal.find("#"+submitBtnID).hide();

            //redirect after success action on modal hide(close)
            myModal.on('hidden.bs.modal', function () {
                if (redirectUrl !== -1) {
                    window.location.href = redirectUrl;
                }
            })
        }
        else if(oReq.status == 422) {
            var errors = oReq.responseJSON; //get the errors response data

            myModal.find('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups

            $.each(errors, function (key, value) {
                errorsHTML += '<li>' + value[0] + '</li>'; //shows only the first error.
                $('#'+key).closest('.form-group')
                    .addClass('has-error'); //Add the has error class to form-groups with errors
            });
            errorsHTML += '</ul>';

            myModal.find('#invalid-input-alert').addClass('alert alert-danger alert-dismissible')
                .fadeIn()
                .html(errorsHTML);

            //autoclose alert after 7 seconds
            myModal.find("#invalid-input-alert").alert();
            window.setTimeout(function() { myModal.find("#invalid-input-alert").fadeOut('slow'); }, 7000);

            //Close btn click
            myModal.find('#close-invalid-input-alert').on('click', function () {
                myModal.find("#invalid-input-alert").fadeOut('slow');
            });
        }
        else {
            errorsHTML += '<li>' + "Error " + oReq.status + " occurred when trying to upload your file.<br>" + '</li></ul>';
        }
    };

    oReq.send(oData);
    */
}