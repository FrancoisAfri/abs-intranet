@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">

    <!-- Makes the date picker work with modals -->
    <style>
        .datepicker{z-index:1151 !important;}
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Loan Statement</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-xs-6">
                                    <h5>Loan Amount:</h5>
                                    <h5>Current Prime Rate:</h5>
                                    <h5>Interest Rate:</h5>
                                    <h5>Term:</h5>
                                </div>
                                <div class="col-xs-6">
                                    <h5><b>{{ !empty($loan->amount_wanted) ? 'R ' . number_format($loan->amount_wanted, 2) : '&nbsp;' }}</b></h5>
                                    <h5>{{ !empty($prime_rate->prime_rate) ? number_format($prime_rate->prime_rate, 2) . ' %' : '&nbsp;' }}</h5>
                                    <h5>{{ $interest_rate }}</h5>
                                    <h5>{{ $term . ' Year(s)' }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-xs-6">
                                    <h5>Loan Start Date:</h5>
                                    <h5>Monthly Instalment:</h5>
                                    <h5>Number of Payments:</h5>
                                    <h5>Current Balance:</h5>
                                </div>
                                <div class="col-xs-6">
                                    <h5>{{ isset($approval_date) ? $approval_date->format('d/m/Y') : '&nbsp;' }}</h5>
                                    <h5>{{ isset($month_instalment) ? 'R ' . number_format($month_instalment, 2) : '&nbsp;' }}</h5>
                                    <h5>{{ isset($num_payments) ? $num_payments : '&nbsp;' }}{{ isset($num_rem_payments) ? " (". $num_rem_payments . " remaining)" : '' }}</h5>
                                    <h5><b>{{ isset($tot_loan_cost) ? 'R ' . number_format($tot_loan_cost, 2) : '&nbsp;' }}</b></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <table class="table table-striped">
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Date</th>
                            <th>Activity</th>
                            <th style="text-align: right;">Amount</th>
                            <th style="text-align: right;">Balance</th>
                        </tr>
                        @if(count($loan_statement) > 0)
                            @foreach($loan_statement as $statement)
                                <tr>
                                    <td>{{ $statement->index }}</td>
                                    <td nowrap>{{ (!empty($statement->date)) ? $statement->date->format('d/m/Y') : '' }}</td>
                                    <td>{{ $statement->activity }}</td>
                                    <td style="text-align: right;" nowrap>{{ ($statement->isPayment) ? '-' : '' }}R {{ number_format($statement->amount, 2) }}</td>
                                    <td style="text-align: right;" nowrap>R {{ number_format($statement->balance, 2) }}</td>
                                </tr>
                            @endforeach
                        @endif
                        <tr class="bg-orange">
                            <td><i class="fa fa-info-circle" data-toggle="tooltip" title="This payment must be made before this date: {{ $next_pmt_date->format('d/m/Y') }}" data-placement="right"></i></td>
                            <td nowrap>{{ $next_pmt_date->format('d/m/Y') }} *</td>
                            <td>Next Scheduled Payment</td>
                            <td style="text-align: right;" nowrap>{{ isset($next_pmt_amt) ? 'R ' . number_format($next_pmt_amt, 2) : '&nbsp;' }}</td>
                            <td style="text-align: right;" nowrap></td>
                        </tr>
                    </table>
                    <!-- End statement /table -->
                    <hr>
					@if (($user->type != 2))
                    <!-- Collapsible section containing the amortization schedule -->
                    <div class="box-group" id="accordion">
                        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                        <div class="panel box box-primary">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="collapsed"><i class="fa fa-caret-square-o-down"></i> View Amortization Schedule
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                <div class="box-body">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>Date</th>
                                            <th style="text-align: right;">Beginning Balance</th>
                                            <th style="text-align: right;">Total Payment</th>
                                            <th style="text-align: right;">Principal</th>
                                            <th style="text-align: right;">Interest</th>
                                            <th style="text-align: right;">Ending Balance</th>
                                        </tr>
                                        @if(count($loan_amortization) > 0)
                                            @foreach($loan_amortization as $amor)
                                                <tr>
                                                    <td nowrap>{{ $amor['amr_date']->format('d/m/Y') }}</td>
                                                    <td style="text-align: right;" nowrap>R {{ number_format($amor['beginning_bal'], 2) }}</td>
                                                    <td style="text-align: right;" nowrap>R {{ number_format($amor['total_pmt'], 2) }}</td>
                                                    <td style="text-align: right;" nowrap>R {{ number_format($amor['capital'], 2) }}</td>
                                                    <td style="text-align: right;" nowrap>R {{ number_format($amor['interest'], 2) }}</td>
                                                    <td style="text-align: right;" nowrap>R {{ number_format(round($amor['ending_bal'], 2), 2) }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </table>
                                    <!-- End amortization /table -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /. End Collapsible section containing the amortization schedule -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" id="capture_payment" class="btn btn-success pull-right" data-toggle="modal" data-target="#capture-payment-modal"><i class="fa fa-credit-card"></i> Capture Payment</button>
                </div>
                <!-- Include the modal to capture a payment -->
					@include('loan.partials.capture_payment')
				@endif
				 <div class="row no-print">
					<div class="col-xs-12">
					  <a href="/statement/{{$loan->id}}" target="_blank" class="btn btn-default  pull-right"><i class="fa fa-print"></i>Print Statement</a>
					</div>
				 </div>
            </div>
        </div>
    </div>
@endsection

@section('page_script')
    <!-- bootstrap datepicker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

    <script>
        $(function () {
            //Date picker
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                endDate: '-0d',
                autoclose: true
            });

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

            //Submit payment capture form to server with ajax
            $('#submit-payment').on('click', function() {
                $.ajax({
                    method: 'POST',
                    url: '{{ '/loan/' . $loan->id . '/capture_payment' }}',
                    data: {
                        date_added: $('#date_added').val(),
                        amount_paid: $('#amount_paid').val(),
                        _token: $('input[name=_token]').val()
                    },
                    success: function(success) {
                        //console.log(success);
                        $('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups
                        //$('form[name=set-rates-form]').trigger('reset'); //Reset the form

                        var successHTML = '<button type="button" id="close-invalid-input-alert" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-check"></i>Payment Approved!</h4>';
                        successHTML += 'The payment has been successfully captured.';
                        $('#success-alert').addClass('alert alert-success alert-dismissible')
                                .fadeIn()
                                .html(successHTML);

                        //auto hide modal after 7 seconds
                        $("#capture-payment-modal").alert();
                        window.setTimeout(function() { $("#capture-payment-modal").modal('hide'); }, 5000);

                        //autoclose alert after 7 seconds
                        $("#success-alert").alert();
                        window.setTimeout(function() { $("#success-alert").fadeOut('slow'); }, 5000);

                        //reload page on modal close
                        $('#capture-payment-modal').on('hidden.bs.modal', function () {
                            window.location = '/loan/{{ $loan->id }}/summary';
                        })
                    },
                    error: function(xhr) {
                        //console.log(xhr);
                        //if(xhr.status === 401) //redirect if not authenticated
                        //$( location ).prop( 'pathname', 'auth/login' );
                        if(xhr.status === 422) {
                            var errors = xhr.responseJSON; //get the errors response data
                            //console.log(errors);

                            $('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups

                            var errorsHTML = '<button type="button" id="close-invalid-input-alert" class="close" aria-hidden="true">&times;</button><h4><i class="icon fa fa-ban"></i> Invalid Input!</h4><ul>';
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
                                $("#prime-rate-invalid-input-alert").fadeOut('slow');
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection