@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">Prime Interest Rate</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    @if (count($prime_rates) > 0)
                        <ul id="prime-rates-list" class="list-group list-group-unbordered">
                            @foreach($prime_rates as $prime_rate)
                                @if($prime_rate->current !== 1)
                                    <li class="list-group-item">
                                        <b>{{ date('d-m-Y', $prime_rate->date_added) }}</b> <font class="pull-right">{{ $prime_rate->prime_rate }}&percnt;</font>
                                    </li>
                                @else
                                    <li id="active-prime-rate" class="list-group-item active">
                                        <b>{{ date('d-m-Y', $prime_rate->date_added) }}</b> <font class="pull-right">{{ $prime_rate->prime_rate }}&percnt;</font>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No rates to display, please start by adding a rate.
                        </div>
                    @endif

                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" id="add-new-prime-rate" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-prime-rate-modal">Add New Prime Rate</button>
                </div>
            </div>
        </div>

        <!-- Include add new prime rate modal -->
        @include('loan.partials.add_prime_rate')
    </div>
@endsection

@section('page_script')
    <script>
        $(function () {
            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();

            //Vertically center modals on page
            function reposition() {
                var modal = $(this),
                        dialog = modal.find('.modal-dialog');
                modal.css('display', 'block');

                // Dividing by two centers the modal exactly, but dividing by three
                // or four works better for larger screens.
                dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
            }
            // Reposition when a modal is shown
            $('.modal').on('show.bs.modal', reposition);
            // Reposition when the window is resized
            $(window).on('resize', function() {
                $('.modal:visible').each(reposition);
            });

            //Post password form to server using ajax
            $('#add-prime-rate').on('click', function() {
                $.ajax({
                    method: 'POST',
                    url: '/loan/setup/primerate',
                    data: {
                        prime_rate: $('#prime_rate').val(),
                        _token: $('input[name=_token]').val()
                    },
                    success: function(success) {
                        $('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups
                        $('form[name=add-prime-rate-form]').trigger('reset'); //Reset the form

                        var successHTML = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-check"></i> Prime rate added!</h4>';
                        successHTML += 'The new prime rate has been added successfully.';
                        $('#prime-rate-success-alert').addClass('alert alert-success alert-dismissible')
                                .fadeIn()
                                .html(successHTML);

                        //show the newly added prime rate on the setup list
                        $('#active-prime-rate').removeClass('active');
                        var newPrimeRateList = $('#prime-rates-list').html();
                        newPrimeRateList += '<li id="active-prime-rate" class="list-group-item active"><b>' + success['date_added'] + '</b> <font class="pull-right">' + success['new_prime_rate'] + '&percnt;</font></li>';
                        $('#prime-rates-list').html(newPrimeRateList);

                        //auto hide modal after 7 seconds
                        $("#add-new-prime-rate-modal").alert();
                        window.setTimeout(function() { $("#add-new-prime-rate-modal").modal('hide'); }, 5000);

                        //autoclose alert after 7 seconds
                        $("#prime-rate-success-alert").alert();
                        window.setTimeout(function() { $("#prime-rate-success-alert").fadeOut('slow'); }, 5000);
                    },
                    error: function(xhr) {
                        //if(xhr.status === 401) //redirect if not authenticated
                        //$( location ).prop( 'pathname', 'auth/login' );
                        if(xhr.status === 422) {
                            console.log(xhr);
                            var errors = xhr.responseJSON; //get the errors response data

                            $('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups

                            var errorsHTML = '<button type="button" id="close-invalid-input-alert" class="close" aria-hidden="true">&times;</button><h4><i class="icon fa fa-ban"></i> Invalid Input!</h4><ul>';
                            $.each(errors, function (key, value) {
                                errorsHTML += '<li>' + value[0] + '</li>'; //shows only the first error.
                                $('#'+key).closest('.form-group')
                                        .addClass('has-error'); //Add the has error class to form-groups with errors
                            });
                            errorsHTML += '</ul>';

                            $('#prime-rate-invalid-input-alert').addClass('alert alert-danger alert-dismissible')
                                    .fadeIn()
                                    .html(errorsHTML);

                            //autoclose alert after 7 seconds
                            $("#prime-rate-invalid-input-alert").alert();
                            window.setTimeout(function() { $("#prime-rate-invalid-input-alert").fadeOut('slow'); }, 7000);

                            //Close btn click
                            $('#close-invalid-input-alert').on('click', function () {
                                $("#prime-rate-invalid-input-alert").fadeOut('slow');
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection