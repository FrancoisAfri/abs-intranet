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
                    <h3 class="box-title"> Request Stock Approval </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <form name="leave-application-form" class="form-horizontal" method="POST"
                      action="/stock/appoverequest" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div style="overflow-X:auto;">
                        <table id="example2" class="table table-bordered table-hover">
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
                                <th>Store</th>
                                <th>Date Requested</th>
                                <th>Title</th>
                                <th>Created By</th>
                                <th>On behalf Of</th>
                                <th style="width: 150px;">Remarks</th>
                                <th>Status</th>
                                <th style="width: 5px; text-align: center;">Accept <input type="checkbox"
                                                                                          id="checkallaccept"
                                                                                          onclick="checkAllboxAccept()"/>
                                </th>
                                <th style="width: 5px; text-align: center;">Decline</th>
                                <th style="width: 5px; text-align: center;"> ...................</th>
                            </tr>

                            @if (count($stocks) > 0)
                                @foreach ($stocks as $stock)
                                    <tr style="text-align:center">
                                        <td>
                                            <a href="{{ '/stock/viewrequest/' . $stock->id.'/back/app'}}" id="view_request"
                                               class="btn btn-warning  btn-xs">View</a></td>
										<td>{{ !empty($stock->store_name) ? $stock->store_name : '' }}</td>
                                        <td>{{ !empty($stock->date_created) ? date(' d M Y', $stock->date_created) : '' }}</td>
                                        <td style="width: 100px; text-align: left;">{{ !empty($stock->title_name) ? $stock->title_name : '' }}</td>
                                        <td>{{ !empty($stock->firstname.' '. $stock->surname) ? $stock->firstname.' '. $stock->surname : '' }}</td>
                                        <td>{{ !empty($stock->hp_firstname.' '. $stock->hp_surname) ? $stock->hp_firstname.' '. $stock->hp_surname : '' }}</td>
                                        <td style="width: 150px; text-align: left;">{{ (!empty($stock->request_remarks)) ?  $stock->request_remarks : ''}}</td>
                                        <td>{{ !empty($stock->step_name ) ? $stock->step_name : 'Declined' }}</td>
                                        @if (isset($stock) && $stock->status === 0 || $stock->status === $stepnumber)
                                            <td></td>
                                        @else
                                            <td style='text-align:center'>
                                                <input type="checkbox" class="checkbox selectall"
                                                       id="stockappprove_{{ $stock->id }}"
                                                       name="stockappprove_{{ $stock->id }}"
                                                       value="{{$stock->status}}">
                                            </td>
                                        @endif

                                        @if (isset($stock) && $stock->status === 0 || $stock->status === $stepnumber)
                                            <td></td>
                                            <td></td>
                                        @else
                                            <td style="text-align:center"><input type="checkbox" class="checkalldeclines" id="decline_{{$stock->id}}"
                                                   onclick="$('#comment_id_{{$stock->id}}').toggle(); uncheckCheckBoxes({{$stock->id}}, 0);">
                                            </td>
                                            <td style="width: 15px;">
                                                <textarea class="form-control" id="comment_id_{{$stock->id}}"
                                                          name="declined_{{$stock->id}}"
                                                          placeholder="Enter rejection reason ..." rows="2"
                                                          style="display:none"></textarea>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr id="categories-list">
                                    <td colspan="12">
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                                &times;
                                            </button>
                                            No Record to display, please start by creating a request..
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