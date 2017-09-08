@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
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
                        <h3 class="box-title">Account Details</h3>
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

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th rowspan="2" width="5px" style="vertical-align: middle;"><h1 class="no-margin"><i class="fa fa-id-badge"></i></h1></th>
                                    <th>Account Number</th>
                                    <th rowspan="2" width="5px" style="vertical-align: middle;"><h1 class="no-margin"><i class="fa fa-building-o"></i></h1></th>
                                    <th>Company</th>
                                    <th rowspan="2" width="5px" style="vertical-align: middle;"><h1 class="no-margin"><i class="fa fa-user"></i></h1></th>
                                    <th>Contact Person</th>
                                    <th rowspan="2" width="5px" style="vertical-align: middle;"><h1 class="no-margin"><i class="fa fa-calendar-o"></i></h1></th>
                                    <th>Date Created</th>
                                    <th rowspan="2" width="5px" style="vertical-align: middle;"><h1 class="no-margin"><i class="fa fa-info-circle"></i></h1></th>
                                    <th>Status</th>
                                </tr>
                                <tr>
                                    <td>{{ ($account->account_number) ? $account->account_number : '' }}</td>
                                    <td>{{ ($account->company) ? $account->company->name : '[individual]' }}</td>
                                    <td>{{ ($account->client) ? $account->client->full_name : '' }}</td>
                                    <td>{{ ($account->start_date) ? date('d/m/Y', $account->start_date) : '' }}</td>
                                    <td>{{ $account->str_status }}</td>
                                </tr>
                            </table>
                        </div>

                        <hr class="hr-text" data-content="PURCHASES">

                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                <tr>
                                    <td></td>
                                    <th>Quote #</th>
                                    <th>Date Ordered</th>
                                    <th>Payment Option</th>
                                    <th>Status</th>
                                    <th class="text-right">Cost</th>
                                    <th class="text-right">Balance</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($account->quotations as $quotation)
                                    <tr>
                                        <td width="5px"><i class="fa fa-caret-down"></i></td>
                                        <td>
                                            <a href="/quote/view/{{ $quotation->id }}" target="_blank">
                                                {{ ($quotation->quote_number) ? $quotation->quote_number : $quotation->id }}
                                            </a>
                                        </td>
                                        <td nowrap>{{ $quotation->created_at }}</td>
                                        <td>{{ $quotation->str_payment_option }}</td>
                                        <td><span class="label label-{{ $labelColors[$quotation->status] }}">{{ $purchaseStatus[$quotation->status] }}</span></td>
                                        <td class="text-right" nowrap>{{ ($quotation->cost) ? 'R ' . number_format($quotation->cost, 2) : '' }}</td>
                                        <td class="text-right" nowrap>{{ ($quotation->balance) ? 'R ' . number_format($quotation->balance, 2) : '' }}</td>
                                        <td class="text-right" nowrap>
                                            @if($quotation->can_capture_payment)
                                                <button type="button" class="btn btn-success btn-flat btn-xs" data-toggle="modal"
                                                        data-target="#capture-payment-modal" data-quote_id="{{ $quotation->id }}"
                                                        data-invoice_id="{{ ($quotation->invoices->first()) ? $quotation->invoices->first()->id : 0 }}"
                                                        data-balance="{{ $quotation->balance }}">
                                                    <i class="fa fa-credit-card"></i> Capture Payment
                                                </button>
                                            @endif

                                            @if($quotation->can_send_invoice)
                                                <a href="/crm/invoice/mail/{{ $quotation->id }}" class="btn btn-primary btn-flat btn-xs">
                                                    <i class="fa fa-send"></i> Send Invoice
                                                </a>
                                            @endif

                                            <a href="/crm/invoice/view/{{ $quotation->id }}/pdf" target="_blank" class="btn btn-primary btn-flat btn-xs">
                                                <i class="fa fa-print"></i> Print Invoice
                                            </a>
                                        </td>
                                    </tr>
                                    @if($quotation && (count($quotation->products) > 0 || count($quotation->packages) > 0))
                                        <tr>
                                            <td></td>
                                            <td class="warning" colspan="7">
                                                <ul class="list-inline">
                                                    @if(count($quotation->products) > 0)
                                                        @foreach($quotation->products as $product)
                                                            <li class="list-inline-item"><i class="fa fa-square-o"></i> {{ $product->name }}</li> |
                                                        @endforeach
                                                    @endif

                                                    @if(count($quotation->packages) > 0)
                                                        @foreach($quotation->packages as $package)
                                                            <li class="list-inline-item"><i class="fa fa-object-group"></i> {{ $package->name }}</li> |
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer text-center">
                        <!--
                        <a href="" class="btn btn-success"><i class="fa fa-credit-card"></i> Capture Payment</a>
                        <a href="" class="btn btn-primary"><i class="fa fa-print"></i> Send Invoice</a>
                        <a href="" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print Invoice</a>
                        -->
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
        </div>

        <!-- Include modals -->
        @include('crm.partials.capture_payment_modal')
        @if(Session('changes_saved'))
            @include('contacts.partials.success_action', ['modal_title' => "Users Access Updated!", 'modal_content' => session('changes_saved')])
        @endif
    </div>
@endsection

@section('page_script')
    <!-- Start Bootstrap File input -->
    <!-- canvas-to-blob.min.js is only needed if you wish to resize images before upload. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
    <!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
    <script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
    <!-- optionally if you need translation for your language then include locale file as mentioned below
    <script src="/bower_components/bootstrap_fileinput/js/locales/<lang>.js"></script>-->
    <!-- End Bootstrap File input -->
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- date picker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>

    <script>
        $(function () {
            //Date picker
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                //endDate: '-0d',
                autoclose: true,
                todayHighlight: true
            }).on('show.bs.modal', function(event) {
                // prevent datepicker from firing bootstrap modal "show.bs.modal"
                event.stopPropagation();
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

            //pass the statement id to the warning modal when it shows
            var quoteID = 0, invoiceID = 0;
            $('#capture-payment-modal').on('show.bs.modal', function (e) {
                var btnTrigger = $(e.relatedTarget);
                quoteID = btnTrigger.data('quote_id');
                invoiceID = btnTrigger.data('invoice_id');
                var balance = btnTrigger.data('balance');
                var modal = $(this);
                modal.find('#amount_paid').val(balance);
                console.log('gets here: q=' + quoteID + ', i=' + invoiceID);
            });

            //submit payment modal with ajax (add payment)
            $('#submit-payment').on('click', function() {
                var strUrl = '/crm/capture-payment/' + quoteID + '/' + invoiceID;
                var formName = 'capture-payment-form';
                var modalID = 'capture-payment-modal';
                //var modal = $('#'+modalID);
                var submitBtnID = 'submit-payment';
                var redirectUrl = '/crm/account/{{ $account->id }}';
                var successMsgTitle = 'Payment Captured!';
                var successMsg = 'The payment has been successfully captured.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
        });
    </script>
@endsection
