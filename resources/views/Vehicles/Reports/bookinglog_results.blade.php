@extends('layouts.main_layout')
@section('page_dependencies')
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                    <h3 class="box-title">Internal Vehicle Management </h3>
                </div>
                <div class="box-body">
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div style="overflow-X:auto;">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th style="width: 5px; text-align: center;"></th>
                                        <th>Driver</th>
                                        <th>Date Collected</th>
                                        <th>Date Returned</th>
                                        <th>Approved By	</th>
                                        <th>Driver </th>
                                        <th>Purpose</th>
                                        <th>Destination</th>
                                        <th>Starting Km</th>
                                        <th>Ending Km</th>
                                        <th>Total Km Travelled</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($vehiclebookings) > 0)
                                        @foreach ($vehiclebookings as $booking)
                                            <tr id="categories-list">
                                                <td></td>
                                                <td>{{ (!empty( $booking->collect_timestamp)) ? date(' d M Y', $booking->collect_timestamp) : ''}} </td>
                                                <td>{{ (!empty( $booking->return_timestamp)) ? date(' d M Y', $booking->return_timestamp) : ''}} </td>
                                                <td>{{ (!empty( $booking->approver_booking->first_name  )) ?  $booking->approver_booking->first_name : ''}} </td>
                                                <td>{{ (!empty( $booking->driver_booking->first_name)) ?  $booking->driver_booking->first_name : ''}} </td>
                                                <td>{{ (!empty( $booking->purpose)) ?  $booking->purpose : ''}} </td>
                                                <td>{{ (!empty( $booking->destination)) ?  $booking->destination : ''}} </td>
                                                <td>{{ (!empty( $booking->start_mileage_id)) ?  $booking->start_mileage_id : ''}} </td>
                                                <td>{{ (!empty( $booking->end_mileage_id)) ?  $booking->end_mileage_id : ''}} </td>
                                                <td></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th style="width: 5px; text-align: center;"></th>
                                        <th>Driver</th>
                                        <th>Date Collected</th>
                                        <th>Date Returned</th>
                                        <th>Approved By	Driver</th>
                                        <th>Driver </th>
                                        <th>Purpose</th>
                                        <th>Destination</th>
                                        <th>Starting Km</th>
                                        <th>Ending Km</th>
                                        <th>Total Km Travelled</th>
                                    </tr>
                                    </tfoot>
                                </table>
                                <div class="box-footer">
                                    <button type="button" id="cancel" class="btn btn-default pull-left"><i
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
                        <!-- End Bootstrap File input -->
                        <script>
                            function postData(id, data) {
                                if (data == 'actdeac') location.href = "/vehicle_management/vehicles_Act/" + id;
                            }

                            //Cancel button click event
                            document.getElementById("cancel").onclick = function () {
                                location.href = "/vehicle_management/create_request";
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