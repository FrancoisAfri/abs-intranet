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
            });
        },
        error: function(xhr) {
            if(xhr.status === 422) {
                var errors = xhr.responseJSON; //get the errors response data

                myModal.find('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups

                var errorsHTML = '<button type="button" id="close-invalid-input-alert" class="close" aria-hidden="true">&times;</button><h4><i class="icon fa fa-ban"></i> Invalid Input(s)!</h4><ul>';
                $.each(errors, function (key, value) {
                    errorsHTML += '<li>' + value[0] + '</li>'; //shows only the first error.
                    myModal.find('#'+key).closest('.form-group')
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

function modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, formMethod) {
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
            });
        },
        error: function(xhr) {
            if(xhr.status === 422) {
                var errors = xhr.responseJSON; //get the errors response data

                myModal.find('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups

                var errorsHTML = '<button type="button" id="close-invalid-input-alert" class="close" aria-hidden="true">&times;</button><h4><i class="icon fa fa-ban"></i> Invalid Input(s)!</h4><ul>';
                $.each(errors, function (key, value) {
                    errorsHTML += '<li>' + value + '</li>'; //shows only the first error.
                    myModal.find('#'+key).closest('.form-group')
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
