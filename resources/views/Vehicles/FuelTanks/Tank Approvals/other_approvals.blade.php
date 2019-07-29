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
                    <h3 class="box-title">Other Fuel Approval(s)</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <form name="leave-application-form" class="form-horizontal" method="POST"
                      action="/vehicle_management/otherApproval"
                      enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th> Date Taken</th>
                                <th>Vehicle Fleet No.</th>
                                <th>Vehicle Reg. No.</th>
                                <th>Odometer Reading</th>
                                <th>Hours Reading</th>
                                <th>Service Station</th>
                                <th>Litres</th>
                                <th>Rate Per Litre</th>
                                <th>Cost</th>
                                <th>Status</th>
                                <th style="width: 5px; text-align: center;">Accept <input type="checkbox"
                                                                                          id="checkallaccept"
                                                                                          onclick="checkAllboxAccept()"/>
                                </th>
                                <th style="width: 5px; text-align: center;">Decline</th>
                                <td></td>
                            </tr>

                            @if (count($Approvals) > 0)
                                @foreach ($Approvals as $filling)
                                    <tr style="text-align:center">
                                        <td>{{ (!empty( $filling->date)) ?   date('Y M d', $filling->date) : ''}} </td>
                                        <td>{{ (!empty( $filling->fleet_number)) ?  $filling->fleet_number : ''}} </td>
                                        <td>{{ (!empty( $filling->vehicle_registration)) ?  $filling->vehicle_registration : ''}} </td>
                                        <td>{{ (!empty( $filling->Odometer_reading)) ?  $filling->Odometer_reading : ''}} </td>
                                        <td>{{ (!empty( $filling->Hoursreading)) ?  $filling->Hoursreading : ''}} </td>
                                        <td>{{ (!empty( $filling->Staion)) ?  $filling->Staion : ''}} </td>
                                        <td>{{ (!empty( $filling->litres_new)) ?  number_format($filling->litres_new, 2) : ''}} </td>
                                        <td>{{ (!empty( $filling->cost_per_litre)) ?   'R '.number_format($filling->cost_per_litre, 2) : ''}} </td>
                                        <td>{{ (!empty( $filling->total_cost)) ? 'R '.number_format($filling->total_cost, 2) : ''}} </td>
                                        <td>{{ !empty($filling->fuel_status) ?  $bookingStatus[$filling->fuel_status] : ''}}</td>
                                        <td style='text-align:center'>
                                            <input type="hidden" class="checkbox selectall"
                                                   id="vehicleappprove_{{ $filling->fuelLogID }}"
                                                   name="vehicleappprove_{{ $filling->fuelLogID }}" value="0">
                                            <input type="checkbox" class="checkbox selectall"
                                                   id="vehicleappprove_{{ $filling->fuelLogID }}"
                                                   name="vehicleappprove_{{ $filling->fuelLogID }}"
                                                   value="1" {{$filling->status === 1 ? 'checked ="checked"' : 0 }}>
                                        </td>
                                        <td style="text-align:center"><input type="checkbox" class="checkalldeclines "
                                                                             id="decline_$aVehicles[id]"
                                                                             onclick="$('#comment_id_{{$filling->fuelLogID}}').toggle(); uncheckCheckBoxes({{$filling->fuelLogID}}, 0);">
                                        </td>
                                        <td>
                                            {{--  <input type="text" size="30" id="comment_id_{{$filling->id}}" name="declined_{{$filling->id}}" style="display:none">         --}}
                                            <textarea class="form-control" id="comment_id_{{$filling->fuelLogID}}"
                                                      name="declined_{{$filling->fuelLogID}}"
                                                      placeholder="Enter rejection reason ..." rows="2"
                                                      style="display:none"></textarea>
                                        </td>


                                    </tr>
                                @endforeach
                            @else
                                <tr id="categories-list">
                                    <td colspan="16">
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                                &times;
                                            </button>
                                            No Fuel Tank to display, please start by adding a new Fuel Tank..
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </table>
                        <!--   </div> -->
                        <!-- /.box-body -->
                        <div class="box-footer">
							<button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
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
                    location.href = '/vehicle_management/tank_approval';
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


                });


            </script>
@endsection
