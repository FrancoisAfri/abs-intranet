@extends('layouts.main_layout')
@section('page_dependencies')

    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!--  -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css"
          rel="stylesheet">

@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                    <h3 class="box-title"> Vehicle Booking Log </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                <strong class="lead">Vehicle Details</strong><br>
                                @if(!empty($vehiclemaker))
                                    | &nbsp; &nbsp; <strong>Vehicle Make:</strong> <em>{{ $vehiclemaker->name }}</em>
                                    &nbsp;
                                    &nbsp;
                                @endif
                                @if(!empty($vehiclemodeler))
                                    -| &nbsp; &nbsp; <strong>Vehicle Model:</strong>
                                    <em>{{ $vehiclemodeler->name }}</em>
                                    &nbsp; &nbsp;
                                @endif
                                @if(!empty($vehicleTypes))
                                    -| &nbsp; &nbsp; <strong>Vehicle Type:</strong> <em>{{ $vehicleTypes->name }}</em>
                                    &nbsp;
                                    &nbsp;
                                @endif
                                @if(!empty($maintenance->vehicle_registration))
                                    -| &nbsp; &nbsp; <strong>Vehicle Registration:</strong>
                                    <em>{{ $maintenance->vehicle_registration }}</em> &nbsp; &nbsp;
                                @endif
                                @if(!empty($maintenance->year))
                                    -| &nbsp; &nbsp; <strong>Year:</strong> <em>{{ $maintenance->year }}</em> &nbsp;
                                    &nbsp;
                                @endif
                                @if(!empty($maintenance->vehicle_color))
                                    -| &nbsp; &nbsp; <strong>Vehicle Color:</strong>
                                    <em>{{ $maintenance->vehicle_color }}</em> &nbsp; &nbsp; -|
                                @endif

                            </p>
                        </div>
                    </div>
                    <div align="center">
                        <!--  -->
                        <a href="{{ '/vehicle_management/viewdetails/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-bars"></i> General Details
                        </a>
                        <a href="{{ '/vehicle_management/bookin_log/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-book"></i> Booking Log
                        </a>

                        <a href="{{ '/vehicle_management/fuel_log/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-tint"></i> Fuel Log
                        </a>

                        <a href="{{ '/vehicle_management/oil_log/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-file-o"></i> Oil Log
                        </a>

                        <a href="{{ '/vehicle_management/incidents/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-medkit"></i> Incidents
                        </a>
                        <a href="{{ '/vehicle_management/fines/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-list-alt"></i> Fines
                        </a>
                        <a href="{{ '/vehicle_management/service_details/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-area-chart"></i> Service Details
                        </a>
                        <a href="{{ '/vehicle_management/insurance/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-car"></i>Insurance
                        </a>
                        <a href="{{ '/vehicle_management/warranties/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-snowflake-o"></i>Warranties
                        </a>
                        <a href="{{ '/vehicle_management/general_cost/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-money"></i> General Cost
                        </a>
						<a href="{{ '/vehicle_management/fleet-communications/' . $maintenance->id }}"
                                   class="btn btn-app"><i class="fa fa-money"></i> Communications</a>
                        <!--  -->
                    </div>
                    <div style="overflow-X:auto;">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
                                <th>Vehicle</th>
                                <th>Requested From</th>
                                <th>Return By</th>
                                <th>Capturer</th>
                                <th>Driver</th>
                                <th>Status</th>
                                <th>km Out</th>
                                <th>km In</th>
                                <th>Total km Travelled</th>
                                <th style="width: 10px; text-align: center;">Action</th>
                                <th style="width: 10px; text-align: center;">Inspection</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($vehiclebooking) > 0)
                                <ul class="products-list product-list-in-box">
                                    @foreach ($vehiclebooking as $booking)
                                        <tr>
                                            <td nowrap>
                                                <a href="{{ '/vehicle_management/view_booking/' . $booking->id }}" 
                                                       class="btn btn-success  btn-xs" target="_blank"><i class="fa fa-handshake-o"></i> View</a>
                                            </td>
                                            <td>{{ !empty($booking->vehicleMake . ' ' .  $booking->vehicleModel . ' ' . $booking->vehicleType . ' ' . $booking->year  ) ? $booking->vehicleMake . ' ' .  $booking->vehicleModel . ' ' . $booking->vehicleType . ' ' . $booking->year : ''}}</td>
                                            <td>{{ !empty($booking->require_datetime ) ?  date("F j, Y, g:i a", $booking->require_datetime)  : ''}}</td>
                                            <td>{{ !empty($booking->return_datetime ) ?  date("F j, Y, g:i a", $booking->return_datetime)  : ''}}</td>
                                            <td>{{ !empty($booking->capturer_id) ? $booking->capturer_id : ''}}</td>
                                            <td>{{ !empty($booking->firstname . ' ' . $booking->surname ) ? $booking->firstname . ' ' . $booking->surname : ''}}</td>
                                            <td>{{ !empty($booking->status) ? $bookingStatus[$booking->status] : ''}}</td>
                                            <td>{{ !empty($booking->start_mileage_id) ? $booking->start_mileage_id : ''}}</td>
                                            <td>{{ !empty($booking->end_mileage_id) ? $booking->end_mileage_id : ''}}</td>
                                            <td>{{ !empty($booking->end_mileage_id && !empty($booking->start_mileage_id) ) ? $booking->end_mileage_id - $booking->start_mileage_id  : ''}}</td>
                                            @if (isset($booking) && $booking->status === 10)
                                                <td>
                                                    <a href="{{ '/vehicle_management/collect/' . $booking->id }}" 
                                                       id="collect"
                                                       class="btn btn-success  btn-xs" target="_blank"><i
                                                                class="fa fa-handshake-o"></i> collect</a>
                                                </td>@elseif (isset($booking) && $booking->status == 11)
                                                <td>
                                                    <a href="{{ '/vehicle_management/return_vehicle/' . $booking->id }}"
                                                       id="return"
                                                       class="btn btn-info  btn-xs" target="_blank"><i
                                                                class="fa fa-reply-all"></i> return Vehicle </a>
                                                </td>
                                            @else
                                                <td></td>
                                            @endif
                                            @if (isset($booking) && $booking->status > 11)
                                                <td>
                                                    <a href="{{ '/vehicle_management/vehicle_ispection/' . $booking->id }}"
                                                       id="collect"
                                                       class="btn btn-primary  btn-xs" target="_blank"><i
                                                                class="fa fa-hand-lizard-o"></i> Inspection</a>
                                                </td>
											@else 
												<td></td>
                                        </tr>
                            @endif
                            @endforeach
                            @endif
                            </tbody>
                            <tfoot>
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
                                <th>Vehicle</th>
                                <th>Requested From</th>
                                <th>Return By</th>
                                <th>Capturer</th>
                                <th>Driver</th>
                                <th>Status</th>
                                <th>km Out</th>
                                <th>km In</th>
                                <th>Total km Travelled</th>
                                <th style="width: 10px; text-align: center;">Action</th>
                                <th style="width: 10px; text-align: center;">Inspection</th>
                            </tr>
                            </tfoot>
                        </table>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="button" id="cancel" class="btn-sm btn-default btn-flat pull-left"><i
                                        class="fa fa-arrow-left"></i> Back
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endsection

        @section('page_script')
            <!-- DataTables -->
                <script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
                <script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
                <script src="/custom_components/js/modal_ajax_submit.js"></script>
                <!-- time picker -->
                <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
                <!-- Select2 -->
                <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
                <!-- End Bootstrap File input -->
                <script>
                    //Cancel button click event
                    document.getElementById("cancel").onclick = function () {
                        location.href = '/vehicle_management/viewdetails/{{ $maintenance->id }}';
                    };
                    $(function () {
                        $('#example2').DataTable({
                            "paging": true,
                            "lengthChange": true,
                            "searching": true,
                            "ordering": true,
                            "info": true,
                            "autoWidth": true
                        });
                    });

                    $(document).ready(function () {

                        $(function () {
                            $('#required_from').datetimepicker();
                        });

                        $('#required_to').datetimepicker({});

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