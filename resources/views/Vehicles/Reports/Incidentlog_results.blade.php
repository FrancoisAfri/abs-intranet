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
                    <h3 class="box-title">Vehicle Incident Report</h3>
                </div>
                <div class="box-body">
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div style="overflow-X:auto;">
                                <form class="form-horizontal" method="POST" action="/fleet/reports/incident/print">
                                    <input type="hidden" name="vehicle_id"
                                           value="{{!empty($vehicle_id) ? $vehicle_id : 0}}">
                                    <input type="hidden" name="report_type"
                                           value="{{!empty($report_type) ? $report_type : ''}}">
                                    <input type="hidden" name="vehicle_type"
                                           value="{{!empty($vehicle_type) ? $vehicle_type : ''}}">
                                    <input type="hidden" name="driver_id"
                                           value="{{!empty($driver_id) ? $driver_id : ''}}">
                                    <input type="hidden" name="action_date"
                                           value="{{!empty($action_date) ? $action_date : ''}}">
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th style="width: 10px"></th>
                                            <th>Date Reported</th>
                                            <th>Reported By</th>
                                            <th>Odometer Reading Km</th>
                                            <th>Incident Type</th>
                                            <th>Severity</th>
                                            <th>Cost</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if (count($vehicleincidents) > 0)
                                            @foreach($vehicleincidents as $details)
                                                <tr>
                                                    <td>{{ (!empty($details->VehicleMake) ) ? $details->VehicleMake." ".$details->VehicleModel." ".$details->vehicle_registration : ''}}</td>
                                                    <td>{{ !empty($details->date_of_incident) ? date(' d M Y', $details->date_of_incident) : '' }}</td>
                                                    <td>{{ !empty($details->firstname.''.$details->surname) ?  $details->firstname.''.$details->surname: '' }}</td>
                                                    <td>{{ !empty($details->odometer_reading) ?  $details->odometer_reading: '' }}</td>
                                                    <td>{{ !empty($details->IncidentType) ?  $details->IncidentType: 'Nill' }}</td>
                                                    <td>{{ (!empty($details->severity)) ?  $severity[$details->severity] : 'Nill'}}</td>
                                                    <td>{{ !empty($details->Cost) ? 'R' .number_format($details->Cost, 2) : '' }}</td>
                                                    <td>{{ !empty($details->status) ?  $status[$details->status] : ''}}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th style="width: 10px"></th>
                                            <th>Date Reported</th>
                                            <th>Reported By</th>
                                            <th>Odometer Reading Km</th>
                                            <th>Incident Type</th>
                                            <th>Severity</th>
                                            <th>Cost</th>
                                            <th>Status</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                    <div class="box-footer">

                                        <div class="row no-print">
                                            <button type="button" id="cancel" class="btn btn-default pull-left"><i
                                                        class="fa fa-arrow-left"></i> Back to Search Page
                                            </button>
                                            <button type="submit" class="btn btn-primary pull-right"><i
                                                        class="fa fa-print"></i> Print report
                                            </button>
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