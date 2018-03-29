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
                    <h3 class="box-title">Fleet Service Details Report</h3>
                </div>
                <div class="box-body">
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div style="overflow-X:auto;">
                            <form class="form-horizontal" method="POST" action="/fleet/reports/Service/print">
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
                                        <th>Date</th>
                                        <th>Garage</th>
                                        <th>Next Service Date</th>
                                        <th>Next Service Km</th>
                                        <th>Licence Renewal Date</th>
                                        <th>Invoice Number</th>
                                        <th style="width: 5px; text-align: center;">Total Cost</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($serviceDetails) > 0)
                                       @foreach($serviceDetails as $details)
                                            <tr>
                                                <td>{{ (!empty($details->VehicleMake) ) ? $details->VehicleMake." ".$details->VehicleModel." ".$details->vehicle_registration : ''}}</td>
                                                <td>{{ !empty($details->date_serviced) ? date(' d M Y', $details->date_serviced) : '' }}</td>
                                                <td>{{ !empty($details->garage) ? $details->garage : '' }}</td>
                                            
                                                <td style="text-align: center">{{ !empty($details->nxt_service_date) ? date(' d M Y', $details->nxt_service_date) : '' }}</td>
                                                <td style="text-align: center">{{ !empty($details->nxt_service_km) ? date(' d M Y', $details->nxt_service_km) : ''}} </td>
                                                 <td></td>
                                                <td style="text-align: center">{{ !empty($details->invoice_number) ?  $details->invoice_number : ''}} </td>
                                                <td style="text-align: center">{{ !empty($details->total_cost) ? 'R' .number_format($details->total_cost, 2) : ''}} </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                       <th style="width: 10px"></th>
                                        <th>Date</th>
                                        <th>Garage</th>
                                        <th>Next Service Date</th>
                                        <th>Next Service Km</th>
                                        <th>Licence Renewal Date</th>
                                        <th>Invoice Number</th>
                                        <th style="width: 5px; text-align: center;">Total Cost</th>
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