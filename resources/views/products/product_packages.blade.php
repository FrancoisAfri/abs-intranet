@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"> Product Packages</h3>
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
                            <th style="width: 10px; text-align: center;"></th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Discount</th>
                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        @if (count($packages) > 0)
                            @foreach ($packages as $type)
                                <tr id="categories-list">
                                    <td nowrap>
                                        <button type="button" id="edit_compan" class="btn btn-primary  btn-xs"
                                                data-toggle="modal" data-target="#edit-package-modal"
                                                data-id="{{ $type->id }}" data-name="{{ $type->name }}"
                                                data-description="{{$type->description}}"><i
                                                    class="fa fa-pencil-square-o"></i> Edit
                                        </button>
                                        <a href="{{ '/Product/packages/' . $type->id }}" id="edit_compan"
                                           class="btn btn-primary  btn-xs" data-id="{{ $type->id }}"
                                           data-name="{{ $type->name }}" data-description="{{$type->description}}"><i
                                                    class="fa fa-eye"></i> Product(s)</a>
                                    </td>
                                    <td>{{ !empty($type->name) ? $type->name : ''}} </td>
                                    <td>{{ !empty($type->description) ? $type->description : ''}} </td>
                                    <td>{{ !empty($type->discount) ? '%' .number_format($type->discount, 2) : ''}} </td>
                                    <td>
                                        <!--   leave here  -->
                                        <button type="button" id="view_ribbons"
                                                class="btn {{ (!empty($type->status) && $type->status == 1) ? " btn-danger " : "btn-success " }}
                                                        btn-xs" onclick="postData({{$type->id}}, 'actdeac');"><i
                                                    class="fa {{ (!empty($type->status) && $type->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($type->status) && $type->status == 1) ? "De-Activate" : "Activate"}}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr id="categories-list">
                                <td colspan="5">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        No Packages to display, please start by adding a new Packages..
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" id="cat_module" class="btn btn-primary pull-right" data-toggle="modal"
                            data-target="#add-package-modal">Add new Packages
                    </button>
                </div>
            </div>
        </div>
        <!-- Include add new prime rate modal -->
        @include('products.partials.add_package_modal')
        @include('products.partials.edit_package_modal')


    </div>


@endsection

@section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>

    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script>
        function postData(id, data) {
            if (data == 'actdeac') location.href = "/Product/productPack_act/" + id;
        }

        $(function () {
            var moduleId;
            //Initialize Select2 Elements
            $(".select2").select2();

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

            //Show success action modal
            $('#success-action-modal').modal('show');

            //

            $(".js-example-basic-multiple").select2();

            //save the package
            //Post module form to server using ajax (ADD)
            $('#add-package').on('click', function () {
                //console.log('strUrl');
                var strUrl = '/Product/packages/add';
                var modalID = 'add-package-modal';
                var objData = {
                    name: $('#' + modalID).find('#name').val(),
                    description: $('#' + modalID).find('#description').val(),
                    product_id: $('#' + modalID).find('#product_id').val(),
                    discount: $('#' + modalID).find('#discount').val(),
                    _token: $('#' + modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'add-package';
                var redirectUrl = '/product/Packages';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The package has been updated successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            var doc_typeID;
            $('#edit-package-modal').on('show.bs.modal', function (e) {
                //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                doc_typeID = btnEdit.data('id');
                var name = btnEdit.data('name');
                var description = btnEdit.data('description');
                var product_id = btnEdit.data('product_id');
                var discount = btnEdit.data('discount');
                var modal = $(this);
                modal.find('#name').val(name);
                modal.find('#description').val(description);
                modal.find('#product_id').val(product_id);
                modal.find('#discount').val(discount);

            });
            $('#edit_package').on('click', function () {
                var strUrl = '/Product/packages_edit/' + doc_typeID;
                // Product/category_edit/{Category}
                var modalID = 'edit-package-modal';
                var objData = {
                    name: $('#' + modalID).find('#name').val(),
                    description: $('#' + modalID).find('#description').val(),
                    product_id: $('#' + modalID).find('#product_id').val(),
                    discount: $('#' + modalID).find('#discount').val(),
                    _token: $('#' + modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'edit_package';
                var redirectUrl = '/product/Packages';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'Package modal has been updated successfully.';
                var Method = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });

        });
    </script>
@endsection
