@extends('layouts.guest_main_layout')

@section('page_dependencies')
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">

    <!-- Star Ratting Plugin -->
    <!-- default styles -->
    <link href="/bower_components/kartik-v-bootstrap-star-rating-3642656/css/star-rating.css" media="all" rel="stylesheet" type="text/css" />
    <!-- optionally if you need to use a theme, then include the theme CSS file as mentioned below -->
    <link href="/bower_components/kartik-v-bootstrap-star-rating-3642656/themes/krajee-svg/theme.css" media="all" rel="stylesheet" type="text/css" />
    <!-- /Star Ratting Plugin -->
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <i class="fa fa-file-text pull-right"></i>
                    <h3 class="box-title">Client Voucher</h3>
                    <p>Client vouchers list.</p>
                </div>
                <!-- /.box-header -->

                <!-- Form Start -->
                <form name="service-rating-form" class="form-horizontal" method="POST" action="/get-voucher">
                    {{ csrf_field() }}

                    <div class="box-body">
                        @if($vouchers && count($vouchers) > 0)
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Voucher #</th>
                                    <th>Reservation No</th>
                                    <th>Client Name</th>
                                    <th>Collect Address</th>
                                    <th>Return Address</th>
                                    <th>Issued By</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($vouchers as $voucher)
                                    <tr>
                                        <td style="vertical-align: middle;">{{ $voucher->c_sup_vouch_no }}</td>
                                        <td style="vertical-align: middle;">{{ $voucher->c_reservation_no }}</td>
                                        <td style="vertical-align: middle;">{!! html_entity_decode($voucher->c_pax_name) !!}</td>
                                        <td style="vertical-align: middle;">{{ $voucher->c_rental_addr1}}</td>
                                        <td style="vertical-align: middle;">{{ $voucher->c_return_addr1 }}</td>
                                        <td style="vertical-align: middle;">{{ $voucher->c_issued_by }}</td>
                                        <td style="vertical-align: middle; width: 70px;" class="text-center" nowrap>
                                            <a href="/vouchers/view-car/{{ $voucher->id }}" class="btn btn-xs btn-link" title="View/Print" target="_blank"><i class="fa fa-print"></i></a>
                                            <button type="button" data-toggle="modal" data-target="#email-voucher-modal" data-id="{{ $voucher->id }}" class="btn btn-xs btn-link" title="Send"><i class="fa fa-send"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-info"></i> No Match Found!</h4>
                                Sorry we couldn't find any match in our database. Please refine your search parameters.
                            </div>
                        @endif
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="/vouchers/get-voucher" type="button" id="back" class="btn btn-default btn-flat pull-left"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                </form>
            </div>
        </div>
        <!-- Include add new modal -->
        @include('vouchers.guests.partials.email_voucher')
    </div>
@endsection

@section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

    <!-- Star Ratting Plugin -->
    <!-- default styles -->
    <script src="/bower_components/kartik-v-bootstrap-star-rating-3642656/js/star-rating.js" type="text/javascript"></script>
    <!-- optionally if you need to use a theme, then include the theme JS file as mentioned below -->
    <script src="/bower_components/kartik-v-bootstrap-star-rating-3642656/themes/krajee-svg/theme.js"></script>
    <!-- optionally if you need translation for your language then include locale file as mentioned below -->
    <!-- <script src="path/to/js/locales/<lang>.js"></script> -->
    <!-- /Star Ratting Plugin -->

    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>

    <script>
        $(function () {
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

            //Initialize Select2 Elements
            $(".select2").select2();

            //email voucher modal on show
            var voucherID;
            $('#email-voucher-modal').on('show.bs.modal', function (e) {
                var btnEmail = $(e.relatedTarget);
                voucherID = btnEmail.data('id');
                //var modal = $(this);
                //modal.find('#division_id').val(divID).trigger('change');
                //show letter head image if any
            });

            //Post email voucher form to server using ajax (add)
            $('#email-voucher-btn').on('click', function() {
                var strUrl = '/vouchers/email-car/' + voucherID;
                var formName = 'email-voucher-form';
                var modalID = 'email-voucher-modal';
                var submitBtnID = 'email-voucher-btn';
                var redirectUrl = '';
                var successMsgTitle = 'Voucher Sent!';
                var successMsg = 'The voucher has been successfully emailed to the recipient!';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
        });
    </script>
@endsection