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
                    <h3 class="box-title">Job Card Search</h3>
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
                                        <th style="width: 5px; text-align: center;"> Job Card #</th>
                                        <th>Vehicleb Name</th>
                                        <th>Registration Number</th>
                                        <th>Job Card Date</th>
                                        <th>Completion Date</th>
                                        <th>Instruction Mechanic</th>
                                        <th>Service Type</th>
                                        <th>Supplier</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($jobcardmaintanance) > 0)
                                        @foreach ($jobcardmaintanance as $jobcard)
                                            <tr id="categories-list">
                                                <td>
                                                    <a href="{{ '/jobcards/viewcard/' . $jobcard->id }}"
                                                       id="edit_compan" class="btn btn-warning  btn-xs"><i
                                                                class="fa fa-money"></i> View</a></td>
                                                </td>
                                                <td>{{ !empty($jobcard->jobcard_number) ? $jobcard->jobcard_number : '' }}</td>
                                                <td>{{ (!empty( $jobcard->fleet_number . ' ' .  $jobcard->vehicle_registration . ' ' . $jobcard->vehicle_make . ' ' . $jobcard->vehicle_model))
                                             ?  $jobcard->fleet_number . ' ' .  $jobcard->vehicle_registration . ' ' . $jobcard->vehicle_make . ' ' . $jobcard->vehicle_model : ''}} </td>
                                                <td>{{ (!empty( $jobcard->vehicle_registration)) ?  $jobcard->vehicle_registration : ''}} </td>
                                                <td>{{ !empty($jobcard->card_date) ? date(' d M Y', $jobcard->card_date) : '' }}</td>
                                                <td>{{ !empty($jobcard->completion_date ) ? date(' d M Y', $jobcard->completion_date) : '' }}</td>
                                                <td>{{ !empty($jobcard->instruction) ? $jobcard->instruction : '' }}</td>
                                                <td>{{ !empty($jobcard->servicetype) ? $jobcard->servicetype : '' }}</td>
                                                <td>{{ !empty($jobcard->Supplier) ? $jobcard->Supplier : '' }}</td>
                                                <td>{{ !empty($jobcard->aStatus) ? $jobcard->aStatus : '' }}</td>
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
                                location.href = "/jobcards/search";
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