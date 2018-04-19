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
                    <h3 class="box-title"> My Job Cards</h3>
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
                                    <th>Job Card #</th>
                                    <th>Vehicle Name</th>
                                    <th>Registration</th>
                                    <th>Job Card Date </th>
                                    <th>Completion Date</th>
                                    <th>Instruction</th>
                                    <th>Mechanic</th>
                                    <th>Service Type</th>
                                    <th>Supplier</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($configuration) > 0)
                                    @foreach ($configuration as $booking)
                                        <tr id="categories-list">
                                            
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th style="width: 5px; text-align: center;"></th>
                                    <th>Job Card #</th>
                                    <th>Vehicle Name</th>
                                    <th>Registration</th>
                                    <th>Job Card Date </th>
                                    <th>Completion Date</th>
                                    <th>Instruction</th>
                                    <th>Mechanic</th>
                                    <th>Service Type</th>
                                    <th>Supplier</th>
                                    <th>Status</th>
                                </tr>
                                </tfoot>
                            </table>
                            <div class="box-footer">
                                <button type="button" id="cancel" class="btn btn-default pull-left"><iclass="fa fa-arrow-left"></i> Back
                                <button type="button" class="btn btn-default pull-right" id="back_button">Back</button>
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