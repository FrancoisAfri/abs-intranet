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
                    <h3 class="box-title">Job Card Notes Report</h3>
                </div>
                <div class="box-body">
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div style="overflow-X:auto;">
                                <form class="form-horizontal" method="POST" action="/jobcards/reports/notesprint">
                                         <input type="hidden" name="action_from" value="{{!empty($actionFrom) ? $actionFrom : ''}}">
                                         <input type="hidden" name="action_to" value="{{!empty($actionTo) ? $actionTo : ''}}">
                                         <input type="hidden" name="note_details" value="{{!empty($noteDetails) ? $noteDetails : ''}}">
                                         <input type="hidden" name="user_id" value="{{!empty($userID) ? $userID : ''}}">
                                         <input type="hidden" name="user_id" value="{{!empty($userID) ? $userID : ''}}">
                                         <input type="hidden" name="vehicle_id" value="{{!empty($vehicleID) ? $vehicleID : ''}}">
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th style="width: 5px; text-align: center;"> Job Card #</th>
                                            <th>Vehicle</th>
                                            <th>Job note Date</th>
                                            <th>Note Details</th>
                                            <th>User</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if (count($notes) > 0)
                                            @foreach ($notes as $jobcard)
                                                <tr id="categories-list">
                                                    <td>{{ !empty($jobcard->jobcard_id) ? $jobcard->jobcard_id : '' }}</td>
                                                    <td>{{ !empty($jobcard->fleet_no . '' . $jobcard->vehicleregistration) ? $jobcard->fleet_no. '' . $jobcard->vehicleregistration : '' }}</td>
                                                    <td>{{ !empty($jobcard->date_default) ? date(' d M Y', $jobcard->date_default) : ''}}</td>
                                                    <td>{{ !empty($jobcard->note_details) ? $jobcard->note_details : '' }}</td>
                                                    <td>{{ !empty($jobcard->firstname . '' . $jobcard->surname) ? $jobcard->firstname. ' ' . $jobcard->surname : '' }}</td>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th style="width: 5px; text-align: center;"> Job Card #</th>
                                            <th>Vehicle</th>
                                            <th>Job note Date</th>
                                            <th>Note Details</th>
                                            <th>User</th>
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