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
                    <h3 class="box-title"> My Vehicle Booking(s)</h3>
                </div>
                <div class="box-body">
                    <div style="overflow-X:auto;">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
                                <th>Vehicle</th>
                                <th>Fleet Number</th>
                                <th>Vehicle Registration</th>
                                <th>Booking Type</th>
                                <th>Required From</th>
                                <th>Return By</th>
                                <th>Capturer</th>
                                <th>Driver</th>
                                <th>Status</th>
                                <th style="width: 10px; text-align: center;"></th>
                                <th style="width: 10px; text-align: center;"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($vehiclebookings) > 0)
                            @endif
                            <ul class="products-list product-list-in-box">
                                @foreach ($vehiclebookings as $booking)
                                    <tr>
                                        {{--dnt allow editing for approved and rejected bookings--}}
                                        @if (isset($booking) && $booking->status !== 10 && $booking->status !== 14 && $booking->status !== 12 )
                                            <td nowrap>
                                                <button vehice="button" id="edit_compan" class="btn btn-warning  btn-xs"
                                                        data-toggle="modal" data-target="#edit-booking-modal"
                                                        data-id="{{ $booking->id }}"
                                                        data-vehiclemodel="{{ $booking->vehicleModel }}"
                                                        data-vehicle_reg="{{ $booking->vehicle_reg }}"
                                                        data-required_from="{{date("y F  Y, g:i a", $booking->require_datetime)}}"
                                                        data-required_to="{{date("y F  Y, g:i a", $booking->return_datetime)}}"
                                                        data-usage_type="{{$booking->usage_type}}"
                                                        data-driver="{{$booking->driver_id}}"
                                                        data-purpose="{{$booking->purpose}}"
                                                        data-destination="{{$booking->destination}}"
                                                        data-vehicle_id="{{$booking->vehicle_id}}}"
                                                ><i
                                                            class="fa fa-pencil-square-o"></i> Edit
                                                </button>
                                            </td>@else
                                            <td></td>
                                        @endif
                                       <td>{{ !empty($booking->vehicleMake . ' ' .  $booking->vehicleModel . ' ' . $booking->vehicleType . ' ' . $booking->year  ) ? $booking->vehicleMake . ' ' .  $booking->vehicleModel . ' ' . $booking->vehicleType . ' ' . $booking->year : ''}}</td>
                                        <td>{{ !empty($booking->fleet_number) ? $booking->fleet_number : ''}}</td>
                                        <td>{{ !empty($booking->vehicle_reg) ? $booking->vehicle_reg : ''}}</td>
                                        <td>{{ !empty($booking->usage_type) ? $usageType[$booking->usage_type] : ''}}</td>
                                        <td>{{ !empty($booking->require_datetime ) ?  date("F j, Y, g:i a", $booking->require_datetime)  : ''}}</td>
                                        <td>{{ !empty($booking->return_datetime ) ? date("F j, Y, g:i a", $booking->return_datetime) : ''}}</td>
                                        <td>{{ !empty($booking->capturer_id) ? $booking->capturer_id : ''}}</td>
                                        <td>{{ !empty($booking->firstname . ' ' . $booking->surname ) ? $booking->firstname . ' ' . $booking->surname : ''}}</td>
                                        <td>{{ !empty($booking->status) ? $bookingStatus[$booking->status] : ''}}</td>
                                        @if (isset($booking) && $booking->status !== 10 && $booking->status !== 14 && $booking->status !== 12 )
                                            <td nowrap>
                                                <button type="button" class="btn btn-danger btn-xs" data-toggle="modal"
                                                        data-target="#delete-contact-warning-modal"><i
                                                            class="fa fa-trash"></i> Cancel Booking
                                                </button>
                                            </td>@else
                                            <td></td>
                                        @endif

                                            @if (isset($booking) && $booking->status === 10)
                                                <td>
                                                    <a href="{{ '/vehicle_management/collect/' . $booking->id }}"
                                                       id="edit_compan"
                                                       class="btn btn-success  btn-xs" data-id="{{ $booking->id }}"><i
                                                                class="fa fa-handshake-o"></i> collect</a>
                                                </td> @else
                                                <td></td>
                                            @endif
                                    </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
                                <th>Vehicle</th>
                                <th>Fleet Number</th>
                                <th>Vehicle Registration</th>
                                <th>Booking Type</th>
                                <th>Required From</th>
                                <th>Return By</th>
                                <th>Capturer</th>
                                <th>Driver</th>
                                <th>Status</th>
                                <th style="width: 10px; text-align: center;"></th>
                            </tr>
                            </tfoot>
                        </table>
                        <!-- /.box-body -->
                        <div class="box-footer">
                           <!--  <button type="button" id="cancel" class="btn btn-default pull-right"> Create a Request </button> -->
                            
                        </div>
                    </div>
                </div>
                @include('Vehicles.sucess.cancel_booking_modal')
                @include('Vehicles.Create_request.edit_vehiclebooking_modal')
                @if (count($vehiclebookings) > 0)
                    @include('Vehicles.warnings.cancel_booking_warning_action', ['modal_title' => 'Cancel Vehicle Booking', 'modal_content' => 'Are you sure you want to Cancel this Booking ? This action cannot be undone.'])
                @endif
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
                        location.href = "/vehicle_management/vehicle_request";
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

                    function reject(id, data) {
                        alert(id)
                        if (data == 'reject_id') location.href = "/leave/reject/" + id;
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