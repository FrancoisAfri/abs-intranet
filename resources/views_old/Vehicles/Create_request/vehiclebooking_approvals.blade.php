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
                    <h3 class="box-title">Vehicle Booking(s) Approval</h3>
                </div>
                <div class="box-body">
                    <div style="overflow-X:auto;">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
                                <th>Fleet Number</th>
                                <th>Vehicle Make</th>
                                <th>Vehicle Model</th>
                                <th>Vehicle Registration</th>
                                <th>Required From</th>
                                <th>Required To</th>
                                <th>Booking Type</th>
                                <th>Purpose </th>
                                <th>Destination </th>
                                <th>Capturer</th>
                                <th>Driver</th>
                                <th>Status</th>
                                <th style="width: 10px; text-align: center;">Action</th>
                                <th style="width: 10px; text-align: center;"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($vehicleapprovals) > 0)
                            @endif
                            <ul class="products-list product-list-in-box">
                                @foreach ($vehicleapprovals as $booking)
                                    <tr>
                                        <td></td>
                                        <td>{{ !empty($booking->fleet_number) ? $booking->fleet_number : ''}}</td>
                                        <td>{{ !empty($booking->vehicleMake) ? $booking->vehicleMake : ''}}</td>
                                        <td>{{ !empty($booking->vehicleModel) ? $booking->vehicleModel : ''}}</td>
                                        <td>{{ !empty($booking->vehicle_reg) ? $booking->vehicle_reg : ''}}</td>
                                        <td>{{ !empty($booking->require_datetime ) ?  date("y F  Y, g:i a", $booking->require_datetime)  : ''}}</td>
                                        <td>{{ !empty($booking->return_datetime ) ? date("y F  Y, g:i a", $booking->return_datetime) : ''}}</td>
                                        <td>{{ !empty($booking->usage_type) ? $usageType[$booking->usage_type]  : ''}}</td>
                                        <td>{{ !empty($booking->purpose) ? $booking->purpose : ''}}</td>
                                        <td>{{ !empty($booking->destination) ? $booking->destination : ''}}</td>
                                        <td>{{ !empty($booking->capturer_id) ? $booking->capturer_id : ''}}</td>
                                        <td>{{ !empty($booking->driver_firstname . ' ' . $booking->driver_surname ) ? $booking->driver_firstname . ' ' . $booking->driver_surname : ''}}</td>
                                        <td>{{ !empty($booking->status) ? $bookingStatus[$booking->status] : ''}}</td>
                                         <td nowrap>
                                        <button type= "button" id="Accept" class="btn btn-success btn-xs btn-detail open-modal" value="{{$booking->id}}" onclick="postData({{$booking->id}}, 'approval')">Approve</button>
                                        </td>
                                         <td nowrap>
                                            <button type="button" id="reject-reason" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#decline-booking-modal" data-id="{{ $booking->id }}" data-description ="{{ $booking->reject_reason }}">Reject</button></td>
                                    </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
                                <th>Fleet Number</th>
                                <th>Vehicle Make</th>
                                <th>Vehicle Model</th>
                                <th>Vehicle Registration</th>
                                <th>Required From</th>
                                <th>Required To</th>
                                <th>Booking Type</th>
                                <th>Purpose </th>
                                <th>Destination </th>
                                <th>Capturer</th>
                                <th>Driver</th>
                                <th>Status</th>
                                <th style="width: 10px; text-align: center;">Action</th>
                                <th style="width: 10px; text-align: center;"></th>
                            </tr>
                            </tfoot>
                        </table>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="button" id="cancel" class="btn btn-default pull-left"><i
                                        class="fa fa-arrow-left"></i> Back
                            </button>
                        </div>
                    </div>
                </div>
                @include('Vehicles.Create_request.decline_booking')
                @if(Session('success_application'))ic
                @include('Vehicles.sucess.success_action', ['modal_title' => "Vehicle booking approval was Successful!", 'modal_content' => session('success_application')])
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
                        location.href = "/vehicle_management/vehiclesearch";
                    };

                    function postData(id, data) {
                        if (data == 'approval') location.href = "/vehicle_management/approval/" + id;
                    }
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

                        //

                        $(".js-example-basic-multiple").select2();

                        //Cancell booking
                        //Post module form to server using ajax (ADD)

                        var reject_ID;
                        $('#decline-booking-modal').on('show.bs.modal', function (e) {
                            var btnEdit = $(e.relatedTarget);
                            reject_ID = btnEdit.data('id');
                            var description = btnEdit.data('description');
                            var modal = $(this);
                            modal.find('#description').val(description);
                        });
                        //Post module form to server using ajax (ADD)
                        $('#rejection-reason').on('click', function() {
                            var strUrl = '/vehicle_management/decline_booking/' + reject_ID;
                            var modalID = 'decline-booking-modal';
                            var objData = {
                                description: $('#' + modalID).find('#description').val(),
                                _token: $('#' + modalID).find('input[name=_token]').val()
                            };
                            var submitBtnID = 'rejection-reason';
                            var redirectUrl = '/vehicle_management/approval';
                            var successMsgTitle = 'reject reason Saved!';
                            var successMsg = 'The reject reason has been Saved successfully.';
                            var Method = 'PATCH';
                            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
                        });

                    });
                </script>

@endsection