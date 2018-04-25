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
                    <h3 class="box-title"> Job Crad Approval </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <form name="leave-application-form" class="form-horizontal" method="POST"
                      action="/jobcards/appovecards" enctype="multipart/form-data">
                      
                    {{ csrf_field() }}

                    <div style="overflow-X:auto;">
                        <table id="example2" class="table table-bordered table-hover">
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
                                <th>Job Card #</th>
                                <th>Vehicle Name</th>
                                <th>Registration </th>
                                <th>Completion Date</th>
                                <th>Mechanic </th>
                                <th>Service Type</th>
                                <th style="width:3px; text-align: center;">Comments </th>
                                <th>Status</th>
                                <th style="width: 5px; text-align: center;">Accept <input type="checkbox"
                                                                                          id="checkallaccept"
                                                                                          onclick="checkAllboxAccept()"/>
                                </th>
                                <th style="width: 5px; text-align: center;">Decline</th>
                                <th style="width: 5px; text-align: center;"> ................... </th>
                            </tr>

                            @if (count($jobcardmaintanance) > 0)
                                @foreach ($jobcardmaintanance as $filling)
                                    <tr style="text-align:center">
                                        <td>
					  <a href="{{ '/jobcards/viewjobcard/' . $filling->id }}" id="edit_compan" class="btn btn-warning  btn-xs">View</a></td>
                                        <td>{{ !empty($filling->jobcard_number ) ? $filling->jobcard_number : '' }}</td>
                                       <td>{{ (!empty( $filling->fleet_number . ' ' .  $filling->vehicle_registration . ' ' . $filling->vehicle_make . ' ' . $filling->vehicle_model)) 
                                    ?  $filling->fleet_number . ' ' .  $filling->vehicle_registration . ' ' . $filling->vehicle_make . ' ' . $filling->vehicle_model : ''}} </td>
                                        <td>{{ !empty($filling->vehicle_registration ) ? $filling->vehicle_registration : '' }}</td>
                                       <td>{{ !empty($filling->completion_date ) ? date(' d M Y', $filling->completion_date) : 'Nill' }}</td>
                                        <td>{{ !empty($filling->firstname.' '. $filling->surname ) ? $filling->firstname.' '. $filling->surname : '' }}</td>
                                        <td>{{ !empty($filling->servicetype ) ? $filling->servicetype : '' }}</td>
                                        <td>{{ !empty($filling->instruction ) ? $filling->instruction : '' }}</td>
                                        <td>{{ !empty($filling->aStatus ) ? $filling->aStatus : 'Declined' }}</td>
                                      
                                        <td style='text-align:center'>
                                            <input type="hidden" class="checkbox selectall"
                                                   id="cardappprove_{{ $filling->id }}"
                                                   name="cardappprove_{{ $filling->id }}" value="0">
                                            <input type="checkbox" class="checkbox selectall"
                                                   id="cardappprove_{{ $filling->id }}"
                                                   name="cardappprove_{{ $filling->id }}"
                                                   value="1" {{$filling->status === 1 ? 'checked ="checked"' : 0 }}>
                                        </td>
                                        <td style="text-align:center"><input type="checkbox" class="checkalldeclines "id="decline_$aVehicles[id]"
                                                                             onclick="$('#comment_id_{{$filling->id}}').toggle(); uncheckCheckBoxes({{$filling->id}}, 0);">
                                        </td>
                                        <td style="width: 15px;">
                                            {{--  <input type="text" size="30" id="comment_id_{{$filling->id}}" name="declined_{{$filling->id}}" style="display:none">         --}}
                                            <textarea class="form-control" id="comment_id_{{$filling->id}}"
                                                      name="declined_{{$filling->id}}"
                                                      placeholder="Enter rejection reason ..." rows="2"
                                                      style="display:none" ></textarea>
                                        </td>
                                       
                                    </tr>
                                @endforeach
                            @else
                                <tr id="categories-list">
                                    <td colspan="9">
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                                &times;
                                            </button>
                                            No Record to display, please start by adding a new Record..
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </table>
                        <!--   </div> -->
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-warning pull-right"> Submit</button>


                        </div>
                    </div>
            </div>         
            </form>

        </div>


        @endsection

        @section('page_script')
            <script src="/custom_components/js/modal_ajax_submit.js"></script>
            <!-- Select2 -->
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <!-- iCheck -->
            <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

            <script>
                function postData(id, data) {
                    if (data == 'actdeac') location.href = "/vehice/station_act/" + id;

                }

                $('#back_button').click(function () {
                    location.href = '/vehicle_management/setup';
                });

                function toggle(source) {
                    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
                    for (var i = 0; i < checkboxes.length; i++) {
                        if (checkboxes[i] != source)
                            checkboxes[i].checked = source.checked;
                    }
                }

                //
                function checkAllboxAccept() {
                    if ($('#checkallaccept:checked').val() == 'on') {
                        $('.selectall').prop('checked', true);
                    }
                    else {
                        $('.selectall').prop('checked', false);
                    }
                }

                function checkAllboxreject() {
                    if ($('#checkallreject:checked').val() == 'on') {
                        $('.reject').prop('checked', true);
                    }
                    else {
                        $('.reject').prop('checked', false);
                    }
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

                    //Cancell booking
                    //Post module form to server using ajax (ADD)

                    //save reject reason


                    var reasonID;
                    $('#decline-vehicle-modal').on('show.bs.modal', function (e) {
                        var btnEdit = $(e.relatedTarget);
                        if (parseInt(btnEdit.data('id')) > 0) {
                            reasonID = btnEdit.data('id');
                        }
                        console.log('gets here: ' + reasonID);
                        var description = btnEdit.data('description');
                        var modal = $(this);
                        modal.find('#description').val(description);
                    });

                    $('#rejection-reason').on('click', function () {
                        var strUrl = '/vehicle_management/reject_vehicle/' + reasonID;
                        var modalID = 'decline-vehicle-modal';
                        var objData = {
                            description: $('#' + modalID).find('#description').val(),
                            _token: $('#' + modalID).find('input[name=_token]').val()
                        };
                        var submitBtnID = 'rejection-reason';
                        var redirectUrl = '/vehicle_management/vehicle_approval';
                        var successMsgTitle = 'Reason Added!';
                        var successMsg = 'The reject reason has been updated successfully.';
                        var Method = 'PATCH';
                        modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
                    });


                });


            </script>
@endsection
