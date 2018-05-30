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
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Job Card Parts</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 10px; text-align: center;"></th>
                            <th>Name</th>
                            <th>Description</th>
                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        @if (count($parts) > 0)
                            @foreach ($parts as $service_type)
                                <tr id="categories-list">
                                    <td nowrap>
                                        <button vehice="button" id="edit_compan" class="btn btn-warning  btn-xs"
                                                data-toggle="modal" data-target="#edit-servicetype-modal"
                                                data-id="{{ $service_type->id }}" data-name="{{ $service_type->name }}"
                                                data-description="{{$service_type->description}}">
                                            <i class="fa fa-pencil-square-o"></i> Edit
                                        </button>
                                    </td>
                                    <td>{{ (!empty( $service_type->name)) ?  $service_type->name : ''}} </td>
                                    <td>{{ (!empty( $service_type->description)) ?  $service_type->description : ''}} </td>
                                    <td>
                                        <!--   leave here  -->
                                        <button vehice="button" id="view_ribbons"
                                                class="btn {{ (!empty($service_type->status) && $service_type->status == 1) ? " btn-danger " : "btn-success " }}
                                                        btn-xs" onclick="postData({{$service_type->id}}, 'actdeac');"><i
                                                    class="fa {{ (!empty($service_type->status) && $service_type->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($service_type->status) && $service_type->status == 1) ? "De-Activate" : "Activate"}}
                                        </button>
                                    </td>
                                    <!--                                 <td><button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#delete-contact-warning-modal"><i class="fa fa-trash"></i> Delete</button></td>-->
                                </tr>
                            @endforeach
                        @else
                            <tr id="categories-list">
                                <td colspan="5">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        No Record to display, please start by adding a new Record....
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                        <button type="button" id="safe_module" class="btn btn-warning pull-right" data-toggle="modal"
                                data-target="#add-servicetype-modal">Add jodcard Part
                        </button>
                    </div>
                </div>
            </div>
            <!-- Include add new prime rate modal -->
        @include('job_cards.partials.add_parts_modal')
        @include('job_cards.partials.edit_servicetype_modal')
        <!-- Include delete warning Modal form-->
            @if (count($parts) > 0)
                @include('job_cards.partials.servicetype_warning_action', ['modal_title' => 'Delete Task', 'modal_content' => 'Are you sure you want to delete this Service Type? This action cannot be undone.'])
            @endif

        </div>


        @endsection

        @section('page_script')
            <script src="/custom_components/js/modal_ajax_submit.js"></script>
            <!-- Select2 -->
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <!-- bootstrap datepicker -->
            <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
            <!-- InputMask -->
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

            <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
            <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
                    type="text/javascript"></script>
            <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
            <!-- the main fileinput plugin file -->
            <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
            <!-- iCheck -->
            <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
            <!-- Ajax dropdown options load -->
            <script src="/custom_components/js/load_dropdown_options.js"></script>
            <!-- Ajax form submit -->
            <script src="/custom_components/js/modal_ajax_submit.js"></script>
            <script>
                function postData(id, data) {
                    if (data == 'actdeac') location.href = "/jobcards/service_act/" + id;

                }

                $('#back_button').click(function () {
                    location.href = '/jobcards/set_up';
                });
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

                    //save Fleet
                    //Post module form to server using ajax (ADD)
                    $('#add-servicetype').on('click', function () {
                        //console.log('strUrl');
                        var strUrl = '/jobcards/addservicetype';
                        var modalID = 'add-servicetype-modal';
                        var objData = {
                            name: $('#' + modalID).find('#name').val(),
                            description: $('#' + modalID).find('#description').val(),
                            _token: $('#' + modalID).find('input[name=_token]').val()
                        };
                        var submitBtnID = 'add-servicetype';
                        var redirectUrl = '/jobcards/servicetype';
                        var successMsgTitle = 'Service Type Added!';
                        var successMsg = 'The Service Type has been updated successfully.';
                        modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                    });

                    var sevicetypeID;
                    $('#edit-servicetype-modal').on('show.bs.modal', function (e) {
                        //console.log('kjhsjs');
                        var btnEdit = $(e.relatedTarget);
                        sevicetypeID = btnEdit.data('id');
                        var name = btnEdit.data('name');
                        var description = btnEdit.data('description');
                        var modal = $(this);
                        modal.find('#name').val(name);
                        modal.find('#description').val(description);
                    });
                    $('#edit_servicetype').on('click', function () {
                        var strUrl = '/jobcards/edit_servicetype/' + sevicetypeID;
                        var modalID = 'edit-servicetype-modal';
                        var objData = {
                            name: $('#' + modalID).find('#name').val(),
                            description: $('#' + modalID).find('#description').val(),
                            _token: $('#' + modalID).find('input[name=_token]').val()
                        };
                        var submitBtnID = 'edit_servicetype';
                        var redirectUrl = '/jobcards/servicetype';
                        var successMsgTitle = 'Service Type Added!';
                        var successMsg = 'The Service Type has been updated successfully.';
                        var Method = 'PATCH';
                        modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
                    });

                });
            </script>
@endsection
