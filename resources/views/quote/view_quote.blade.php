@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <form class="form-horizontal" method="POST" action="">
                    {{ csrf_field() }}
                    <div class="box-header with-border">
                        <h3 class="box-title">Quote Details</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div style="overflow-x:auto;">
							<table class="table table-striped table-bordered">
                                <tr>
                                    <th>Company</th>
                                    <th>Contact Person</th>
                                    <th>Creator</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr> 
								<tr>
                                    <td>{{ ($quotation->company) ? $quotation->company->name : '[Individual]' }}</td>
                                    <td>{{$quotation->client->first_name." ".$quotation->client->surname}}</td>
                                    <td>{{$quotation->person->first_name." ".$quotation->person->surname}}</td>
									<td>{{$quotation->created_at}}</td>
									<td>{{$quotation->quote_status}}</td>
                                </tr>
                            </table>
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th style="text-align: right;">Unit Price</th>
                                </tr>
                                @foreach ($quotation->products as $product)
                                    @if($loop->first || (isset($prevCategory) && $prevCategory != $product->category_id))
                                        <?php $prevCategory = 0; ?>
                                        <tr>
                                            <th class="success" colspan="4" style="text-align: center;">
                                                <i>{{ $product->ProductPackages->name }}</i>
                                            </th>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                        <td style="vertical-align: middle;">{{ $product->name }}</td>
                                        <td style="vertical-align: middle; width: 80px; text-align: center;">
                                            {{ $product->pivot->quantity }}
                                        </td>
                                        <td style="vertical-align: middle; text-align: right;">
                                            {{ $product->pivot->price ? 'R ' . number_format($product->pivot->price, 2) : '' }}
                                        </td>
                                    </tr>
                                    <?php $prevCategory = $product->category_id; ?>
                                @endforeach
                                @foreach ($quotation->packages as $package)
                                    <tr>
                                        <td class="success" style="vertical-align: middle;"><i class="fa fa-caret-down"></i></td>
                                        <th class="success" style="vertical-align: middle;">
                                            Package: {{ $package->name }}
                                        </th>
                                        <td class="success" style="vertical-align: middle; width: 80px; text-align: center;">
                                            {{ $package->pivot->quantity }}
                                        </td>
                                        <td class="success" style="vertical-align: middle; text-align: right;">
                                            {{ ($package->pivot->price) ? 'R ' . number_format($package->pivot->price, 2) : '' }}
                                        </td>
                                    </tr>
                                    @foreach($package->products_type as $product)
                                        <tr>
                                            <td style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                            <td style="vertical-align: middle;">{{ $product->name }}</td>
                                            <td style="text-align: center; vertical-align: middle; width: 80px;">
                                                &mdash;
                                            </td>
                                            <td style="vertical-align: middle; text-align: right;">
                                                &mdash;
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </table>

                            <!-- Total cost section -->
                            <div class="col-sm-4 col-sm-offset-8 no-padding">
                                <table class="table">
                                    <tr>
                                        <th style="text-align: left;">Subtotal:</th>
                                        <td style="text-align: right;" id="subtotal" nowrap>{{ 'R ' . number_format($subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: left; vertical-align: middle;">Discount{{ $discountPercent ? ' (' . $discountPercent . '%)' : '' }}:</th>
                                        <td style="text-align: right; vertical-align: middle;" id="discount-amount" nowrap>{{ 'R ' . number_format($discountAmount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: left; vertical-align: middle;">VAT:</th>
                                        <td style="text-align: right; vertical-align: middle;" id="vat-amount" nowrap>{{ ($vatAmount > 0) ? 'R ' . number_format($vatAmount, 2) : '&mdash;' }}</td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: left; vertical-align: middle;">Total:</th>
                                        <td style="text-align: right; vertical-align: middle;" id="total-amount" nowrap>{{ 'R ' . number_format($total, 2) }}</td>
                                    </tr>
                                </table>
                            </div>
							<div>
                                <table class="table">
                                    <tr>
                                        <th style="text-align: center;" colspan="4">Quote History</th>
									</tr>
									<tr>
                                        <td style="text-align: right;" id="subtotal" nowrap>{{ 'R ' . number_format($subtotal, 2) }}</td>
                                        <td style="text-align: right;" id="subtotal" nowrap>{{ 'R ' . number_format($subtotal, 2) }}</td>
                                        <td style="text-align: right;" id="subtotal" nowrap>{{ 'R ' . number_format($subtotal, 2) }}</td>
                                        <td style="text-align: right;" id="subtotal" nowrap>{{ 'R ' . number_format($subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: left; vertical-align: middle;">Discount{{ $discountPercent ? $discountPercent . '%' : '' }}:</th>
                                        <td style="text-align: right; vertical-align: middle;" id="discount-amount" nowrap>{{ 'R ' . number_format($discountAmount, 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer text-center">
                        @if($quotation->status == 1)
                            <button type="button" class="btn btn-primary btn-success" id="approve_quote" onclick="postData({{$quotation->id}}, 'approve_quote');"><i class="fa fa-check"></i> Approve Quote</button>
                            <button type="button" class="btn btn-primary btn-danger" id="decline_quote" onclick="postData({{$quotation->id}}, 'decline_quote');"><i class="fa fa-times"></i> Decline Quote</button>
                            <button type="button" class="btn btn-primary btn-warning" id="cancel_quote" onclick="postData({{$quotation->id}}, 'cancel_quote');"><i class="fa fa-trash"></i> Cancel Quote</button>
                        @endif
                        @if($quotation->status == 2)
                            <!--<button type="button" class="btn btn-primary btn-success" id="client_approve" onclick="postData({{$quotation->id}}, 'client_approve');"><i class="fa fa-check"></i> Client Approved</button>-->
                            <button type="button" class="btn btn-primary btn-success" id="client_approve" data-toggle="modal" data-target="#purchase-type-modal"><i class="fa fa-check"></i> Client Approved</button>
                            <button type="button" class="btn btn-primary btn-danger" id="client_declined" onclick="postData({{$quotation->id}}, 'client_declined');"><i class="fa fa-times"></i> Client Rejected</button>
                        @endif
                        @if($quotation->status < 2)
                            <button type="button" class="btn btn-primary btn-warning" id="modify_quote" onclick="postData({{$quotation->id}}, 'modify_quote');"><i class="fa fa-pencil-square-o"></i> Modify Quote</button>
                        @endif
                        <a href="/quote/email_quote/{{ $quotation->id }}" class="btn btn-primary"><i class="fa fa-send"></i> Email Quote</a>
                        <a href="/quote/view/{{ $quotation->id }}/pdf" target="_blank" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print Quote</a>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
			@if ($quotation->status == 2)
				 @include('quote.partials.get_client_response')
				 @include('quote.partials.purchase_type_modal')
			@endif
        </div>

        <!-- Include modal -->
        @if(Session('changes_saved'))
            @include('contacts.partials.success_action', ['modal_title' => "Users Access Updated!", 'modal_content' => session('changes_saved')])
        @endif
    </div>
@endsection

@section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- date picker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>

    <script src="/custom_components/js/modal_ajax_submit.js"></script>

    <script>
        $(function () {
            //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });

            //Initialize date picker
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
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

            //call hideFields method on when the page has loaded
            hideFields();

            //show/hide file upload or manual fields on radio checked
            $('#rdo_once_off_payment, #rdo_recurring_payment').on('ifChecked', function(){
                hideFields();
            });

            //Post purchase type form to server using ajax
            $('#save-purchase-type').on('click', function() {
                var strUrl = '/quote/client-approve/' + {{ $quotation->id }};
                var modalID = 'purchase-type-modal';
                var objData = {
                    payment_option: $('#'+modalID).find("input[name='payment_option']:checked").val(),
                    payment_term: $('#'+modalID).find('#payment_term').val(),
                    first_payment_date: $('#'+modalID).find('#first_payment_date').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'save-purchase-type';
                var redirectUrl = '/crm/account/quote/' + {{ $quotation->id }};
                //var redirectUrl = null;
                var successMsgTitle = 'Quote Approved!';
                var successMsg = 'The quotation has been successfully approved.';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
        });
        function postData(id, data)
        {
            if (data == 'approve_quote')
                location.href = "/quote/approve_quote/" + id;
            else if(data == 'decline_quote')
                location.href = "/quote/decline_quote/" + id;
            else if (data == 'client_approve')
                location.href = "/quote/approve_quote/" + id;
            else if(data == 'client_declined')
                location.href = "/quote/decline_quote/" + id;
            else if(data == 'cancel_quote')
                location.href = "/quote/cancel_quote/" + id;
            else if(data == 'modify_quote')
                location.href = "/quote/modify_quote/" + id;
            else if(data == 'print_quote')
                location.href = "/quote/print_quote/" + id;
            else if(data == 'email_quote')
                location.href = "/quote/email_quote/" + id;
        }

        //function to show/hide payment fields
        function hideFields() {
            var paymentOption = $("input[name='payment_option']:checked").val();
            if (paymentOption == 1) { //yes
                $('.recurring-payment-field').hide();
            }
            else if (paymentOption == 2) { //no
                $('.recurring-payment-field').show();
            }
            return paymentOption;
        }
    </script>
@endsection