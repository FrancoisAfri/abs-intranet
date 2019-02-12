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
                    <h3 class="box-title"> Product(s) Kit</h3>
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
                            <th>Date Added</th>
                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        @if (count($kitProducts) > 0)
                            @foreach ($kitProducts as $kitProduct)
                                <tr>
                                    <td nowrap>
                                        <button type="button" id="edit_compan" class="btn btn-primary  btn-xs"
                                                data-toggle="modal" data-target="#edit-kit-modal"
                                                data-id="{{ $kitProduct->id }}" data-name="{{ $kitProduct->name }}"><i
                                                    class="fa fa-pencil-square-o"></i> Edit
                                        </button>
                                        <a href="{{ '/product/kit/' . $kitProduct->id }}" id="view_products"
                                           class="btn btn-primary  btn-xs"><i
                                                    class="fa fa-eye"></i> Product(s)</a>
                                    </td>
                                    <td>{{ !empty($kitProduct->name) ? $kitProduct->name : ''}} </td>
                                    <td>{{ !empty($kitProduct->date_added) ? date('l d M Y', $kitProduct->date_added) : ''}} </td>
                                    <td>
                                        <!--   leave here  -->
                                        <button type="button" id="view_ribbons"
                                                class="btn {{ (!empty($kitProduct->status) && $kitProduct->status == 1) ? " btn-danger " : "btn-success " }}
                                                        btn-xs" onclick="postData({{$kitProduct->id}}, 'actdeac');"><i
                                                    class="fa {{ (!empty($kitProduct->status) && $kitProduct->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($kitProduct->status) && $kitProduct->status == 1) ? "De-Activate" : "Activate"}}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        No kit to display, please start by adding a new kit..
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" id="cat_module" class="btn btn-primary pull-right" data-toggle="modal"
                            data-target="#add-kit-modal">Create Kit
                    </button>
                </div>
            </div>
        </div>
        @include('stock.partials.add_kit_modal')
        @include('stock.partials.edit_kit_modal')
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
            if (data == 'actdeac') location.href = "/stock/kit_act/" + id;
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

            $(".js-example-basic-multiple").select2();

            //save the package
            //Post module form to server using ajax (ADD)
            $('#add-kit').on('click', function () {

				var strUrl = '/stock/kit/add';
				var formName = 'add-kit-form';
				var modalID = 'add-kit-modal';
				var submitBtnID = 'add-kit';
				var redirectUrl = '/stock/kit_management';
				var successMsgTitle = 'New Kit Added!';
				var successMsg = 'Product(s) have been successfully to kit.';
				modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            var kitID;
            $('#edit-kit-modal').on('show.bs.modal', function (e) {
                //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                kitID = btnEdit.data('id');
                var name = btnEdit.data('name');
                var modal = $(this);
                modal.find('#name').val(name);
            });
            $('#edit_kit').on('click', function () {
                
				var strUrl = '/stock/kit/update/' + kitID;
                var formName = 'edit-kit-form';
                var modalID = 'edit-kit-modal';
                var submitBtnID = 'edit_kit';
                var redirectUrl = '/stock/kit_management';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'Kit informations  have been updated successfully.';
                var Method = 'PATCH';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

        });
    </script>
@endsection
