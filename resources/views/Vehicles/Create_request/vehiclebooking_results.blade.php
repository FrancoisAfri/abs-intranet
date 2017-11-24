@extends('layouts.main_layout')
@section('page_dependencies')

    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">

@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12 col-md-offset-0">
            <!-- Horizontal Form -->
            <!-- <form class="form-horizontal" method="get" action="/leave/approval"> -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                    <h3 class="box-title">Internal Vehicle Management </h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                <div class="box-body">
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
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
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($vehiclebookings) > 0)
                                    @foreach ($vehiclebookings as $booking)
                                        <tr id="categories-list">
                                            <td nowrap>
                                                <button vehice="button" id="edit_compan" class="btn btn-warning  btn-xs" data-toggle="modal" data-target="#edit-package-modal" data-id="{{ $booking->id }}"  ><i class="fa fa-pencil-square-o"></i> Edit</button>
                                            </td>
                                            <td>{{ !empty($booking->vehicleMake . ' ' .  $booking->vehicleModel . ' ' . $booking->vehicleType . ' ' . $booking->year  ) ? $booking->vehicleMake . ' ' .  $booking->vehicleModel . ' ' . $booking->vehicleType . ' ' . $booking->year : ''}}</td>
                                            <td>{{ !empty($booking->fleet_number) ? $booking->fleet_number : ''}}</td>
                                            <td>{{ !empty($booking->vehicle_reg) ? $booking->vehicle_reg : ''}}</td>
                                            <td>{{ !empty($booking->usage_type) ? $usageType[$booking->usage_type] : ''}}</td>
                                            <td>{{ !empty($booking->require_datetime . ' ' . $booking->required_time) ? $booking->require_datetime . ' ' . $booking->required_time : ''}}</td>
                                            <td>{{ !empty($booking->return_datetime . ' ' . $booking->return_time) ? $booking->return_datetime . ' ' . $booking->return_time : ''}}</td>
                                            <td>{{ !empty($booking->capturer_id) ? $booking->capturer_id : ''}}</td>
                                            <td>{{ !empty($booking->firstname . ' ' . $booking->surname ) ? $booking->firstname . ' ' . $booking->surname : ''}}</td>
                                            <td>{{ !empty($booking->status) ? $bookingStatus[$booking->status] : ''}}</td>
                                            <td><button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#delete-contact-warning-modal"><i class="fa fa-trash"></i> Cancel Booking</button></td>
                                        </tr>
                                    @endforeach
                                @endif
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
                                <button type="button" id="cancel" class="btn btn-default pull-left"><i
                                            class="fa fa-arrow-left"></i> Back
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- End new User Form-->
                </div>
            @endsection

            @section('page_script')
                <!-- DataTables -->
                    <script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
                    <script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
                    <!-- End Bootstrap File input -->

                    <script>
                        //Cancel button click event
                        document.getElementById("cancel").onclick = function () {
                            location.href = "/vehicle_management/vehiclesearch";
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

                    </script>
@endsection