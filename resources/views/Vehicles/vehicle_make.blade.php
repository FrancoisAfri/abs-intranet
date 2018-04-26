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
                    <h3 class="box-title">Add vehicle make</h3>
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
                            <th style="width: 10px; text-align: center;"></th>
                            <th>Name</th>
                            <th>Description</th>
                            <th style="width: 5px; text-align: center;"></th>
                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        @if (count($vehiclemake) > 0)
                            @foreach ($vehiclemake as $vehice)
                                <tr id="categories-list">
                                    <td nowrap>
                                        <button vehice="button" id="edit_compan" class="btn btn-warning  btn-xs"
                                                data-toggle="modal" data-target="#edit-package-modal"
                                                data-id="{{ $vehice->id }}" data-name="{{ $vehice->name }}"
                                                data-description="{{$vehice->description}}"><i
                                                    class="fa fa-pencil-square-o"></i> Edit
                                        </button>
                                    </td>
                                    <td>
                                      <a href="{{ '/vehicle_management/vehice_model/' . $vehice->id }}" id="edit_compan" 
                                      class="btn btn-primary  btn-xs"   data-id="{{ $vehice->id }}" data-name="{{ $vehice->name }}"
                                       data-description="{{$vehice->description}}"  ><i class="fa fa-bullseye"></i> Vehicle Model</a></td></td>
                                    <td>{{ (!empty( $vehice->name)) ?  $vehice->name : ''}} </td>
                                    <td>{{ (!empty( $vehice->description)) ?  $vehice->description : ''}} </td>
                                    <td>
                                        <!--   leave here  -->
                                        <button vehice="button" id="view_ribbons"
                                                class="btn {{ (!empty($vehice->status) && $vehice->status == 1) ? " btn-danger " : "btn-success " }}
                                                        btn-xs" onclick="postData({{$vehice->id}}, 'actdeac');"><i
                                                    class="fa {{ (!empty($vehice->status) && $vehice->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($vehice->status) && $vehice->status == 1) ? "De-Activate" : "Activate"}}
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-xs" data-toggle="modal"
                                                data-target="#delete-contact-warning-modal"  data-id="{{ $vehice->id }}"><i class="fa fa-trash"></i>
                                            Delete
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
                                        No Vehicle Make to display, please start by adding a new Vehicle Make ..
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                    <!--   </div> -->
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                        <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal"
                                data-target="#add-vehicle_make-modal">Add new Vehicle Make
                        </button>
                    </div>
                </div>
            </div>
            <!-- Include add new prime rate modal -->
            @include('Vehicles.partials.add_vehiclemake_modal')
            @include('Vehicles.partials.edit_vehiclemake_modal')
			<!-- Include delete warning Modal form-->
            @if (count($vehiclemake) > 0)
                @include('Vehicles.warnings.vehiclemake_warning_action', ['modal_title' => 'Delete Make', 'modal_content' => 'Are you sure you want to delete this Fleet Make? This action cannot be undone.'])
            @endif
        </div>


        @endsection

        @section('page_script')
            <script src="/custom_components/js/modal_ajax_submit.js"></script>
            <!-- Select2 -->
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <script>
                function postData(id, data) {
                    if (data == 'actdeac') location.href = "/vehice/vehiclemake_act/" + id;

                }

                $('#back_button').click(function () {
                    location.href = '/vehicle_management/setup';
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
                    $('#add-vehicle_make').on('click', function () {
                        //console.log('strUrl');
                        var strUrl = '/vehice/addvehicle_make';
                        var modalID = 'add-vehicle_make-modal';
                        var objData = {
                            name: $('#' + modalID).find('#name').val(),
                            description: $('#' + modalID).find('#description').val(),
                            _token: $('#' + modalID).find('input[name=_token]').val()
                        };
                        var submitBtnID = 'add-vehicle_make';
                        var redirectUrl = '/vehicle_management/vehice_make';
                        var successMsgTitle = 'Vehicles Make Added!';
                        var successMsg = 'The vehice Make has been updated successfully.';
                        //var formMethod = 'PATCH';
                        modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                    });

                    var fleetID;
                    $('#edit-package-modal').on('show.bs.modal', function (e) {
                        //console.log('kjhsjs');
                        var btnEdit = $(e.relatedTarget);
                        fleetID = btnEdit.data('id');
                        var name = btnEdit.data('name');
                        var description = btnEdit.data('description');
                        var modal = $(this);
                        modal.find('#name').val(name);
                        modal.find('#description').val(description);
                    });
                    

                    var makeID;
                    $('#delete-contact-warning-modal').on('show.bs.modal', function (e) {
                        var btnEdit = $(e.relatedTarget);
                        makeID = btnEdit.data('id');
                        var modal = $(this);
                    });

                    $('#delete_contact').on('click', function () {
                        var strUrl = '/vehice/vehiclemake/'+ makeID;
                        var modalID = 'delete-contact-warning-modal';
                        var objData = {
                            _token: $('#' + modalID).find('input[name=_token]').val()
                        };
                        var submitBtnID = 'delete_contact';
                        var redirectUrl = '/vehicle_management/vehice_make';
                       //var Method = 'PATCH';
                        modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl);
                    });


                    // var makeID;
					// $('#delete-contact-warning-modal').on('show.bs.modal', function (e) {
                    //     var btnEdit = $(e.relatedTarget);
                    //     makeID = btnEdit.data('id'); 
                    //     var btnEdit = $(e.relatedTarget);  
                    //     var makeID = btnEdit.data('make_id');
                    //     var modal = $(this);
                    //     modal.find('#make_id').val(MakeID);
                    // });

                    $('#edit_vehiclemake').on('click', function () {
                        var strUrl = '/vehice/edit_vehicle_make/' + fleetID;
                        var modalID = 'edit-package-modal';
                        var objData = {
                            name: $('#' + modalID).find('#name').val(),
                            description: $('#' + modalID).find('#description').val(),
                            _token: $('#' + modalID).find('input[name=_token]').val()
                        };
                        var submitBtnID = 'edit_vehiclemake';
                        var redirectUrl = '/vehicle_management/vehice_make';
                        var successMsgTitle = 'Changes Saved!';
                        var successMsg = 'The vehice make has been updated successfully.';
                        var Method = 'PATCH';
                        modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
                    });

                });
            </script>
@endsection
