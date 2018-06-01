@extends('layouts.main_layout')
@section('page_dependencies')
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                    <h3 class="box-title">Job Card Report</h3>
                </div>
                <div class="box-body">
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div style="overflow-X:auto;">
                                <form class="form-horizontal" method="POST" action="/jobcards/printcards">
                                         <input type="hidden" name="applicationType" value="{{!empty($applicationType) ? $applicationType : ''}}">
                                         <input type="hidden" name=" process_id" value="{{!empty( $processID) ?  $processID : ''}}">
                                         <input type="hidden" name="vehicle_id" value="{{!empty($vehicleID) ? $vehicleID : ''}}">
                                         <input type="hidden" name="application_type" value="{{!empty($application_type) ? $application_type : ''}}">
                                         <input type="hidden" name="action_from" value="{{!empty($actionFrom) ? $actionFrom : ''}}">
                                         <input type="hidden" name="action_to" value="{{!empty($actionTo) ? $actionTo : ''}}">
                                         
                                         
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th style="width: 5px; text-align: center;"> Job Card #</th>
                                            <th>Vehicleb Name</th>
                                            <th>Registration Number</th>
                                            <th>Job Card Date</th>
                                            <th>Completion Date</th>
                                            <th>Instruction Mechanic</th>
                                            <th>Service Type</th>
                                            <th>Supplier</th>
                                            <th>Status</th>
<!--                                            <th style="width: 5px; text-align: center;"></th>-->
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if (count($vehiclemaintenance) > 0)
                                            @foreach ($vehiclemaintenance as $jobcard)
                                                <tr id="categories-list">
                                                    <td>{{ !empty($jobcard->jobcard_number) ? $jobcard->jobcard_number : '' }}</td>
                                                    <td>{{ (!empty( $jobcard->fleet_number . ' ' .  $jobcard->vehicle_registration . ' ' . $jobcard->vehicle_make . ' ' . $jobcard->vehicle_model))
                                             ?  $jobcard->fleet_number . ' ' .  $jobcard->vehicle_registration . ' ' . $jobcard->vehicle_make . ' ' . $jobcard->vehicle_model : ''}} </td>
                                                    <td>{{ (!empty( $jobcard->vehicle_registration)) ?  $jobcard->vehicle_registration : ''}} </td>
                                                    <td>{{ !empty($jobcard->card_date) ? date(' d M Y', $jobcard->card_date) : '' }}</td>
                                                    <td>{{ !empty($jobcard->completion_date ) ? date(' d M Y', $jobcard->completion_date) : 'Nill' }}</td>
                                                    <td>{{ !empty($jobcard->instruction) ? $jobcard->instruction : '' }}</td>
                                                    <td>{{ !empty($jobcard->servicetype) ? $jobcard->servicetype : '' }}</td>
                                                    <td>{{ !empty($jobcard->Supplier) ? $jobcard->Supplier : '' }}</td>
                                                    <td>{{ !empty($jobcard->aStatus) ? $jobcard->aStatus : '' }}</td>
<!--                                                    <td><a href="{{ '/jobcards/printcards/' . $jobcard->id }}"
                                                           id="edit_compan" class="btn btn-default  btn-xs"><i
                                                                    class="fa fa-print"></i> Print</a></td>-->
                                                    </td>
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

                                        <div class="row no-print">
                                            <button type="button" id="cancel" class="btn btn-default pull-left"><i
                                                        class="fa fa-arrow-left"></i> Back
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
                        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
                        <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
                        <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
                        <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
                        <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
                        <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
                        <!-- End Bootstrap File input -->
                        <script>
                            function postData(id, data) {
                                if (data == 'actdeac') location.href = "/vehicle_management/vehicles_Act/" + id;
                            }

                            //Cancel button click event
                            //Cancel button click event
                            document.getElementById("cancel").onclick = function () {
                                location.href = "/jobcards/reports";
                            };

                            $(function () {
                                $('#example2').DataTable({
                                    "paging": true,
                                    "lengthChange": true,
                                    "searching": true,
                                    "ordering": true,
                                    "info": true,
                                    "autoWidth": true,
                                    dom: 'Bfrtip',
                                    buttons: [
                                        'copy', 'csv', 'excel'
                                    ]
                                });
                            });
                        </script>
@endsection