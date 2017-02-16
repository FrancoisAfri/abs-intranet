function modalAjaxSubmit(strUrl, objData, modalID,submitBtnID, redirectUrl, successMsgTitle, successMsg) {
    successMsgTitle = successMsgTitle || 'Success!';
    successMsg = successMsg || 'Action Performed Successfully.';
    redirectUrl = redirectUrl || -1;
    $.ajax({
        method: 'POST',
        url: strUrl,
        data: objData,
        success: function(success) {
            $('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups
            //$('form[name=set-rates-form]').trigger('reset'); //Reset the form

            var successHTML = '<button type="button" id="close-invalid-input-alert" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-check"></i> ' + successMsgTitle + '</h4>';
            successHTML += successMsg;
            $('#success-alert').addClass('alert alert-success alert-dismissible')
                .fadeIn()
                .html(successHTML);

            //auto hide modal after 5 seconds
            $("#"+modalID).alert();
            window.setTimeout(function() { $("#"+modalID).modal('hide'); }, 5000);

            //auto close alert after 5 seconds
            $("#success-alert").alert();
            window.setTimeout(function() { $("#success-alert").fadeOut('slow'); }, 5000);

            //hide modal submit button after success action
            $("#"+submitBtnID).hide();

            //redirect after success action on modal hide(close)
            $('#'+modalID).on('hidden.bs.modal', function () {
                if (redirectUrl !== -1) {
                    window.location.href = redirectUrl;
                }
            })
        },
        error: function(xhr) {
            if(xhr.status === 422) {
                var errors = xhr.responseJSON; //get the errors response data

                $('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups

                var errorsHTML = '<button type="button" id="close-invalid-input-alert" class="close" aria-hidden="true">&times;</button><h4><i class="icon fa fa-ban"></i> Invalid Input(s)!</h4><ul>';
                $.each(errors, function (key, value) {
                    errorsHTML += '<li>' + value[0] + '</li>'; //shows only the first error.
                    $('#'+key).closest('.form-group')
                        .addClass('has-error'); //Add the has error class to form-groups with errors
                });
                errorsHTML += '</ul>';

                $('#invalid-input-alert').addClass('alert alert-danger alert-dismissible')
                    .fadeIn()
                    .html(errorsHTML);

                //autoclose alert after 7 seconds
                $("#invalid-input-alert").alert();
                window.setTimeout(function() { $("#invalid-input-alert").fadeOut('slow'); }, 7000);

                //Close btn click
                $('#close-invalid-input-alert').on('click', function () {
                    $("#invalid-input-alert").fadeOut('slow');
                });
            }
        }
    });
}