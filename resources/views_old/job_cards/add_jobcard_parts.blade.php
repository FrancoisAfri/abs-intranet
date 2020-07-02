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
                    <h3 class="box-title"> Job Card - Parts for {{$parts->name}}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 10px; text-align: center;"></th>
                            <th>Name</th>
                            <th>Description</th>
                            <th style="width: 5px; text-align: center;">Number Available</th>
                            <th style="width: 5px; text-align: center;"></th>
                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        @if (count($jobcartparts) > 0)
                            @foreach ($jobcartparts as $jobparts)
                                <tr id="categories-list">
                                    <td nowrap>
                                        <button vehice="button" id="edit_compan" class="btn btn-warning  btn-xs"
                                                data-toggle="modal" data-target="#edit-jobcardparts-modal"
                                                data-id="{{ $jobparts->id }}" data-name="{{ $jobparts->name }}"
                                                data-description="{{$jobparts->description}}"
                                                data-no_of_parts_available="{{$jobparts->no_of_parts_available}}"><i
                                                    class="fa fa-pencil-square-o"></i> Edit
                                        </button>
                                    </td>

                                    <td>{{ (!empty( $jobparts->name)) ?  $jobparts->name : ''}} </td>
                                    <td>{{ (!empty( $jobparts->description)) ?  $jobparts->description : ''}} </td>
                                    <td>{{ (!empty( $jobparts->no_of_parts_available)) ?  $jobparts->no_of_parts_available : ''}} </td>
                                    <td>
                                        <!--   leave here  -->
                                        <button vehice="button" id="view_ribbons"
                                                class="btn {{ (!empty($jobparts->status) && $jobparts->status == 1) ? " btn-danger " : "btn-success " }}
                                                        btn-xs" onclick="postData({{$jobparts->id}}, 'actdeac');"><i
                                                    class="fa {{ (!empty($jobparts->status) && $jobparts->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($jobparts->status) && $jobparts->status == 1) ? "De-Activate" : "Activate"}}
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-xs" data-toggle="modal"
                                                data-target="#delete-parts-warning-modal" data-id="{{ $jobparts->id }}">
                                            <i class="fa fa-trash"></i> Delete
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
                                        No Records to display, please start by adding a new Record ....
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                    <!--   </div> -->
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                        <button type="button" id="safe_module" class="btn btn-warning pull-right" data-toggle="modal"
                                data-target="#add-jobcardparts-modal">Add new parts
                        </button>
                    </div>
                </div>
            </div>
            <!-- Include add new prime rate modal -->
        @include('job_cards.partials.add_jobcard_parts_modal')
        @include('job_cards.partials.edit_jobcard_parts_modal')

        <!-- Include delete warning Modal form-->
            @if (count($jobcartparts) > 0)
                @include('job_cards.partials.delete_jobcard_parts_warning_action', ['modal_title' => 'Delete  jobcard part', 'modal_content' => 'Are you sure you want to delete this jobcard part ? This action cannot be undone.'])
            @endif
        </div>


        @endsection

        @section('page_script')
            <script src="/custom_components/js/modal_ajax_submit.js"></script>
            <!-- Select2 -->
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <script>
                function postData(id, data) {
                    if (data == 'actdeac') location.href = "/jobcards/parts_act/" + id;

                }

                $('#back_button').click(function () {
                    location.href = '/jobcards/parts';
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


                    //Post perk form to server using ajax (add)
                    $('#add-jobcardparts').on('click', function () {
                        var strUrl = '/jobcards/addjobcardparts';
                        var formName = 'add-jobcardparts-form';
                        var modalID = 'add-jobcardparts-modal';
                        var submitBtnID = 'add-jobcardparts';
                        var redirectUrl = '/jobcards/addparts/{{ $parts->id }}';
                        var successMsgTitle = 'Record  Added!';
                        var successMsg = 'The new record has been updated successfully.';
                        modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                    });


                    var jobcardpartsID;
                    $('#edit-jobcardparts-modal').on('show.bs.modal', function (e) {
                        //console.log('kjhsjs');
                        var btnEdit = $(e.relatedTarget);
                        jobcardpartsID = btnEdit.data('id');
                        var name = btnEdit.data('name');
                        var description = btnEdit.data('description');
                        var no_of_parts_available = btnEdit.data('no_of_parts_available');
                        var modal = $(this);
                        modal.find('#name').val(name);
                        modal.find('#description').val(description);
                        modal.find('#no_of_parts_available').val(no_of_parts_available);
                    });

                    $('#edit_jobcardpartse').on('click', function () {
                        var strUrl = '/jobcards/edit_cardparts/' + jobcardpartsID;
                        var modalID = 'edit-jobcardparts-modal';
                        var objData = {
                            name: $('#' + modalID).find('#name').val(),
                            description: $('#' + modalID).find('#description').val(),
                            no_of_parts_available: $('#' + modalID).find('#no_of_parts_available').val(),
                            _token: $('#' + modalID).find('input[name=_token]').val()
                        };
                        var submitBtnID = 'edit_jobcardpartse';
                        var redirectUrl = '/jobcards/addparts/{{ $parts->id }}';
                        var successMsgTitle = 'Record  Added!';
                        var successMsg = 'The new record has been updated successfully.';
                        var Method = 'PATCH';
                        modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
                    });

                    var jobcardspartsID;
                    $('#delete-parts-warning-modal').on('show.bs.modal', function (e) {
                        var btnEdit = $(e.relatedTarget);
                        jobcardspartsID = btnEdit.data('id');
                        var modal = $(this);
                    });

                    $('#delete_parts').on('click', function () {
                        var strUrl = '/jobcards/delete_jobcards/' + jobcardspartsID;
                        var modalID = 'delete-parts-warning-modal';
                        var objData = {
                            _token: $('#' + modalID).find('input[name=_token]').val()
                        };
                        var submitBtnID = 'delete_parts';
                        var redirectUrl = '/jobcards/addparts/{{ $parts->id }}';
                        modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl);
                    });

                });
            </script>
@endsection
