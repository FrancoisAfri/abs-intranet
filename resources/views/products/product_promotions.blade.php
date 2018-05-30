@extends('layouts.main_layout')
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css"/>
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css"/>
<!-- table -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
<!-- iCheck -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Promotions</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <!-- <form class="form-horizontal" method="POST" action="/hr/document"> -->
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr>
                            <!--<th style="width: 10px; text-align: center;"></th>-->
                            <th>Name</th>
                            <th>Description</th>
                            <th>Product</th>
                            <th>Package</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th class="text-center">Discount</th>
                            <!--  <th>Price</th> -->

                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        @if (count($productsPromotions) > 0)
                            @foreach ($productsPromotions as $type)
                                <tr id="categories-list">


                                    <td>{{ ($type->name) ? $type->product->name : '' }}</td>
                                    <td>{{ ($type->description) ? $type->product->description : '' }}</td>
                                    <td>{{ ($type->product) ? $type->product->product : '' }}</td>
                                    <td>{{ ($type->package) ? $type->package->package : '' }}</td>
                                    <td nowrap>{{ !empty($type->start_date) ? date('d M Y ', $type->start_date) : '' }}</td>
                                    <td nowrap>{{ !empty($type->end_date) ? date(' d M Y', $type->end_date) : '' }}</td>
                                    <td style="text-align: center;"
                                        nowrap>{{ !empty($type->discount) ?  number_format($type->discount, 2): '' }} %
                                    </td>

                                    <td style="width: 10px; text-align: center;">
                                        <button class="btn btn-danger btn-xs" data-toggle="modal"
                                                data-target="#end-promotion-warning-modal"
                                                data-promo_id="{{ $type->id }}" data-promo_name="{{ $type->name }}">
                                            <i class="fa fa-times"></i> End Promotion
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr id="categories-list">
                                <td colspan="7">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        No Promotions to display, please start by adding a new Promotions..
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" id="cat_module" class="btn btn-primary pull-right" data-toggle="modal"
                            data-target="#add-promotion-modal">Add New Promotion
                    </button>
                </div>
            </div>
        </div>
        <!-- Include add new prime rate modal -->
        @include('products.partials.add_promotion_modal')
        @include('hr.partials.edit_category_modal')
        @include('products.partials.end_promotion_modal')


    </div>

    <!-- end section -->

@endsection

@section('page_script')
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
    <!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
    <script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <script src="/custom_components/js/modal_ajax_submit.js"></script>


    <script>
        function postData(id, data) {
            if (data == 'qual') location.href = "/hr/addqul/" + id;

        }

        $(function () {
            $(".select2").select2();
            $('.temp-field').hide();
            var moduleId;
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
            $(window).on('resize', function () {
                $('.modal:visible').each(reposition);
            });

            //
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });

            //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });

            $('#rdo_package, #rdo_product').on('ifChecked', function () {
                var allType = hideFields();
                if (allType == 1) $('#box-subtitle').html('Site Address');
                else if (allType == 2) $('#box-subtitle').html('Temo Site Address');
            });

            function hideFields() {
                var allType = $("input[name='promotion_type']:checked").val();
                if (allType == 1) { //adjsut leave
                    $('.temp-field').hide();
                    $('.site-field').show();
                }
                else if (allType == 2) { //resert leave
//                
                    $('.site-field').hide();
                    $('.temp-field').show();
                }

//          
                return allType;

            }


            //Post module form to server using ajax (ADD)
            $('#add_promotion').on('click', function () {
                //console.log('strUrl');
                var strUrl = '/Product/promotions/add';
                var modalID = 'add-promotion-modal';
                var objData = {
                    name: $('#' + modalID).find('#name').val(),
                    description: $('#' + modalID).find('#description').val(),
                    start_date: $('#' + modalID).find('#start_date').val(),
                    end_date: $('#' + modalID).find('#end_date').val(),
                    discount: $('#' + modalID).find('#discount').val(),
                    package: $('#' + modalID).find('#package').val(),
                    product: $('#' + modalID).find('#product').val(),
                    price: $('#' + modalID).find('#price').val(),
                    promotion_type: $('#' + modalID).find('input:checked[name = promotion_type]').val(),
                    _token: $('#' + modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'add_promotion';
                var redirectUrl = '/product/Promotions';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The group has been updated successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            var doc_typeID;
            $('#edit-category-modal').on('show.bs.modal', function (e) {
                //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                doc_typeID = btnEdit.data('id');
                var name = btnEdit.data('name');
                var description = btnEdit.data('description');
                //var employeeName = btnEdit.data('employeename');
                var modal = $(this);
                modal.find('#name').val(name);
                modal.find('#description').val(description);

            });
            $('#edit_category').on('click', function () {
                var strUrl = '/Product/category_edit/' + doc_typeID;
                // Product/category_edit/{Category}
                var modalID = 'edit-category-modal';
                var objData = {
                    name: $('#' + modalID).find('#name').val(),
                    description: $('#' + modalID).find('#description').val(),
                    _token: $('#' + modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'edit_category';
                var redirectUrl = '/product/Categories';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'Category modal has been updated successfully.';
                var Method = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });

            $('#end-promotion-warning-modal').on('show.bs.modal', function (e) {
                console.log('gets here');
                var btnEdit = $(e.relatedTarget);
                var promotionID = btnEdit.data('promo_id');
                var promotionName = btnEdit.data('promo_name');
                var modal = $(this);
                modal.find('#promotion_name').html('<i class="fa fa-warning"></i> ' + promotionName);
                modal.find('#end_promotion').attr('href', '/product/promotion/end/' + promotionID);

            });
        });
    </script>
@endsection
