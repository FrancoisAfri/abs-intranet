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
                    <h3 class="box-title"> Vehicle Approval </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <form name="leave-application-form" class="form-horizontal" method="POST"
                      action="/vehicle_management/vehicleApproval"
                      enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{--  {{ method_field('PATCH') }}  --}}

                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
                                <th>Vehicle Model/Year</th>
                                <th>Fleet Number</th>
                                <th>Vehicle Registration</th>
                                <th>Odometer</th>
                                <th>Company</th>
                                <th>Department</th>
                                <th style="width: 5px; text-align: center;">Accept <input type="checkbox"
                                                    id="checkallaccept" onclick="checkAllboxAccept()"/>

                                        <br/></th>
                                    <th style="width: 5px; text-align: center;">Decline <input type="checkbox"
                                                    id="checkallreject" onclick="checkAllboxreject()"/><br/>

                                    </th>
                            </tr>

                            @if (count($Vehiclemanagemnt) > 0)
                                @foreach ($Vehiclemanagemnt as $filling)
                                    <tr style="text-align:center">

                                        <td nowrap>
                                            <div class="product-img">
                                                <img src="{{ (!empty($filling->image)) ? Storage::disk('local')->url("image/$filling->image") : 'http://placehold.it/60x50' }}"
                                                     alt="Product Image" width="75" height="50">
                                            </div>
                                        </td>
                                        <td>{{ (!empty( $filling->vehiclemodel . ' ' . $filling->year )) ?   $filling->vehiclemodel . ' ' . $filling->year : ''}} </td>
                                        <td>{{ (!empty( $filling->fleet_number)) ?  $filling->fleet_number : ''}} </td>
                                        <td>{{ (!empty( $filling->vehicle_registration)) ?  $filling->vehicle_registration : ''}} </td>
                                        <td>{{ (!empty( $filling->odometer_reading)) ?  $filling->odometer_reading : ''}} </td>
                                        <td>{{ (!empty( $filling->Department)) ?  $filling->Department : ''}} </td>
                                        <td>{{ (!empty( $filling->company)) ?  $filling->company : ''}} </td>
                                        <td style='text-align:center'>
                                        <input type="hidden" class="checkbox selectall" id="vehicleappprove_{{ $filling->id }}" name="vehicleappprove_{{ $filling->id }}" value="0">
                                        <input type="checkbox" class="checkbox selectall" id="vehicleappprove_{{ $filling->id }}" name="vehicleappprove_{{ $filling->id }}" value="1"  {{$filling->status === 1 ? 'checked ="checked"' : 0 }}>
                                        </td>

                                        <td>
                                        <input type="hidden" class="checkbox reject" id="vehiclereject_{{ $filling->id }}" name="vehiclereject_{{ $filling->id }}" value="0">
                                        <input type="checkbox" class="checkbox reject" id="vehiclereject_{{ $filling->id }}" name="vehiclereject_{{ $filling->id }}" value="1"  {{$filling->status === 1 ? 'checked ="checked"' : 0 }}>
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
                                            No vehicles to display, please start by adding a new vehicles..
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </table>
                        <!--   </div> -->
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary pull-right"> Submit</button>


                        </div>
                    </div>
            </div>
            <!-- Include add new prime rate modal -->
            {{--  @include('Vehicles.Vehicle Approvals.decline_vehicle_modal')  --}}
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


                    //edit booking
                    var bookingID;
                    $('#edit-booking-modal').on('show.bs.modal', function (e) {
                        //console.log('kjhsjs');
                        var btnEdit = $(e.relatedTarget);
                        if (parseInt(btnEdit.data('id')) > 0) {
                            bookingID = btnEdit.data('id');

                        }
                        var vehiclemodel = btnEdit.data('vehiclemodel');
                        var vehicle_reg = btnEdit.data('vehicle_reg');
                        var required_from = btnEdit.data('required_from');
                        var required_to = btnEdit.data('required_to');
                        var usage_type = btnEdit.data('usage_type');
                        var driver = btnEdit.data('driver');
                        var purpose = btnEdit.data('purpose');
                        var destination = btnEdit.data('destination');
                        var vehicle_id = btnEdit.data('vehicle_id');
                        var modal = $(this);
                        modal.find('#vehiclemodel').val(vehiclemodel);
                        modal.find('#vehicle_reg').val(vehicle_reg);
                        modal.find('#required_from').val(required_from);
                        modal.find('#required_to').val(required_to);
                        modal.find('#usage_type').val(usage_type);
                        modal.find('#driver').val(driver);
                        modal.find('#purpose').val(purpose);
                        modal.find('#destination').val(destination);
                        modal.find('#vehicle_id').val(vehicle_id);
                    });

                    //Post perk form to server using ajax (edit)
                    $('#edit_booking').on('click', function () {
                        var strUrl = '/vehicle_management/edit_booking/' + bookingID;
                        var formName = 'edit-booking-form';
                        var modalID = 'edit-booking-modal';
                        var submitBtnID = 'edit_booking';
                        var redirectUrl = '/vehicle_management/vehiclebooking_results';
                        var successMsgTitle = 'Changes Saved!';
                        var successMsg = 'The  details have been updated successfully!';
                        var Method = 'PATCH';
                        modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                    });


                });


            </script>
@endsection
