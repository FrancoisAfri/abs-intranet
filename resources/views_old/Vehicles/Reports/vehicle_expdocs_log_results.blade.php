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
                    <h3 class="box-title">Vehicle Expired Docs Report</h3>
                </div>
                <div class="box-body">
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div style="overflow-X:auto;">
                            <form class="form-horizontal" method="POST" action="/fleet/reports/details/print">
                                <input type="hidden" name="vehicle_id" value="{{!empty($vehicle_id) ? $vehicle_id : 0}}">
                                <input type="hidden" name="report_type" value="{{!empty($report_type) ? $report_type : ''}}">
                                <input type="hidden" name="vehicle_type" value="{{!empty($vehicle_type) ? $vehicle_type : ''}}">
                                <input type="hidden" name="driver_id" value="{{!empty($driver_id) ? $driver_id : ''}}">
                                <input type="hidden" name="action_date" value="{{!empty($action_date) ? $action_date : ''}}">  
                                <input type="hidden" name="report_id" value="{{!empty($report_id) ? $report_id : ''}}">
                                <input type="hidden" name="licence_type" value="{{!empty($licence_type) ? $licence_type : ''}}">
                                <input type="hidden" name="driver_id" value="{{!empty($driver_id) ? $driver_id : ''}}">
                                <input type="hidden" name="destination" value="{{!empty($destination) ? $destination : ''}}">
                                <input type="hidden" name="purpose" value="{{!empty($purpose) ? $purpose : ''}}">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th style="width: 10px"></th>
                                        <th>Vehicle type</th>
                                        <th>Make</th>
                                        <th>Model</th>
                                        <th>Year</th>
                                        <th>Color</th>
                                        <th>Chassis Number</th>
                                        <th>VIN Number</th>
                                        <th>Fuel Type</th>
                                        <th>Tank Size</th>
                                        <th>Kilometer/Hours Reading</th>
                                        <th>Division</th>
                                        <th>Department</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($vehicledetails) > 0)
                                       @foreach($vehicledetails as $details)
                                            <tr>
                                                <td>
                                                <div class="product-img">
                                                    <img src="{{ (!empty($details->image)) ? Storage::disk('local')->url("Vehicle/images/$details->image") : 'http://placehold.it/60x50' }}"
                                                         alt="Product Image" width="50" height="50">
                                                </div>
                                                    </td>
                                                <td>{{ !empty($details->vehicle_type) ?  $details->vehicle_type: '' }}</td>
                                                <td>{{ !empty($details->vehicle_make) ?  $details->vehicle_make: '' }}</td>
                                                <td>{{ !empty($details->vehicle_model) ?  $details->vehicle_model: '' }}</td>
                                                <td>{{ !empty($details->year) ?  $details->year: '' }}</td>
                                                <td>{{ !empty($details->vehicle_color) ?  $details->vehicle_color: '' }}</td>
                                                <td>{{ !empty($details->chassis_number) ?  $details->chassis_number: '' }}</td>
                                                <td>{{ !empty($details->engine_number) ?  $details->engine_number: '' }}</td>
                                                <td>{{ !empty($details->fuel_type) ?  $status[$details->fuel_type] : ''}}</td>
                                                <td>{{ !empty($details->size_of_fuel_tank) ?  $details->size_of_fuel_tank : ''}}</td>
                                                @if (isset($details) && $details->hours_reading === 0)
                                                    <td>{{ !empty($details->hours_reading) ? $details->hours_reading : ''}}</td>
                                                @else
                                                    <td>{{ !empty($details->odometer_reading) ? $details->odometer_reading : ''}}</td>
                                                @endif
                                                <td>{{ !empty($details->company) ? $details->company : ''}}</td>
                                                <td>{{ !empty($details->Department) ? $details->Department : ''}}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th style="width: 10px"></th>
                                        <th>Vehicle type</th>
                                        <th>Make</th>
                                        <th>Model</th>
                                        <th>Year</th>
                                        <th>Color</th>
                                        <th>Chassis Number</th>
                                        <th>VIN Number</th>
                                        <th>Fuel Type</th>
                                        <th>Tank Size</th>
                                        <th>Kilometer/Hours Reading</th>
                                        <th>Division</th>
                                        <th>Department</th>
                                    </tr>
                                    </tfoot>
                                    <input type="hidden" name="vehicle_id" size="10" value="$iVehicleID">
                                    <class
                                    ="caption">
                                    <td></td>
                                    <td colspan="6" style="text-align:right">Total</td>
                                    <td style="text-align: right" nowrap>{{ !empty($totalamount_paid) ? 'R' .number_format($totalamount_paid, 2) : '' }}</td>
                                </table>
                                <div class="box-footer">
                                    
                                    <div class="row no-print">
                                        <button type="button" id="cancel" class="btn btn-default pull-left"><i
                                                    class="fa fa-arrow-left"></i> Back to Search Page
                                        </button>
                                            <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print report</button>
                                    </div>
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
                                location.href = "/vehicle_management/vehicle_reports";
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