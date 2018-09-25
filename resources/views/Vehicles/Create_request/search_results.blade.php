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
                    <h3 class="box-title">Choose a Vehicle</h3>
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
                                    <th style="width: 5px; text-align: center;"></th>
                                    <th>Vehicle</th>
                                    <th>Type</th>
                                    <th>Fleet Number</th>
                                    <th>Registration Number</th>
                                    <th>Company</th>
                                    <th>Department</th>
                                    <th>Odometer / Hours Reading</th>
                                    <th>Notices</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($vehiclebooking) > 0)
                                    @foreach ($vehiclebooking as $booking)
                                        <tr id="categories-list">
                                            
                                            <td>
                                                <a href="{{ '/vehicle_management/bookingdetails/' . $booking->id . '/' . $vehicleDates}}"
                                                   id="edit_compan" class="btn btn-default  btn-xs"
                                                   data-id="{{ $booking->id }}">select</a>
                                            </td>
                                            <td>
                                                <div class="product-img">
                                                        <img src="{{ (!empty($booking->image)) ? Storage::disk('local')->url("Vehicle/images/$booking->image") : 'http://placehold.it/60x50' }}"
                                                             alt="Product Image" width="50" height="50">
                                                    </div>
                                            </td>
                                            <td>{{ !empty($booking->vehicle_make . ' ' . $booking->vehicle_model . ' ' . $booking->year ) ? $booking->vehicle_make . ' ' . $booking->vehicle_model  . ' ' . $booking->year: ''}}</td>
                                            <td>{{ !empty($booking->vehicletype) ? $booking->vehicletype : ''}}</td>
                                            <td>{{ !empty($booking->fleet_number) ? $booking->fleet_number : ''}}</td>
                                            <td>{{ !empty($booking->vehicle_registration) ? $booking->vehicle_registration : ''}}</td>
                                            <td>{{ !empty($booking->company) ? $booking->company : ''}}</td>
                                            <td>{{ !empty($booking->Department) ? $booking->Department : ''}}</td>
                                            <td>{{ !empty($booking->odometer_reading ) ? $booking->odometer_reading.'Kms' : ''}} <br>
                                                {{ !empty($booking->hours_reading ) ? $booking->hours_reading.'Hrs' : ''}}
                                            </td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th style="width: 5px; text-align: center;"></th>
                                    <th style="width: 5px; text-align: center;"></th>
                                    <th>Vehicle</th>
                                    <th>Type</th>
                                    <th>Fleet Number</th>
                                    <th>Registration Number</th>
                                    <th>Company</th>
                                    <th>Department</th>
                                    <th>Odometer Reading</th>
                                    <th>Notices</th>
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