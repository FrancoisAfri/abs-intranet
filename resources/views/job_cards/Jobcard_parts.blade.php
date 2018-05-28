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
                                    
                                    <th style="width: 5px; text-align: center;"> Job Card #</th>
                                    <th>Vehicle</th>
                                    <th>Job Card Date</th>
                                    <th>Instruction	Mechanic</th>
                                    <th>Service Type</th>
                                    <th>Part</th>
                                    <th>Transaction</th>
                                    
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($parts) > 0)
                                    @foreach ($parts as $jobcard)
                                        <tr id="categories-list">
                                         <td>{{ !empty($jobcard->jobcard_number) ? $jobcard->jobcard_number : '' }}</td>
                                         <td>{{ !empty($jobcard->fleet_no . '' . $jobcard->vehicleregistration) ? $jobcard->fleet_no. '' . $jobcard->vehicleregistration : '' }}</td>
                                         <td>{{ !empty($jobcard->date_created) ? date(' d M Y', $jobcard->date_created) : '' }}</td>
                                         <td>{{ !empty($jobcard->instruction) ? $jobcard->instruction : '' }}</td>
                                         <td>{{ !empty($jobcard->servicetype) ? $jobcard->servicetype : '' }}</td>
                                         <td>{{ !empty($jobcard->product_name) ? $jobcard->product_name : '' }}</td>	
                                         
                                         </td> 
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th style="width: 5px; text-align: center;"> Job Card #</th>
                                    <th>Vehicle</th>
                                    <th>Job Card Date</th>
                                    <th>Instruction Mechanic</th>
                                    <th>Service Type</th>
                                    <th>Part</th>
                                    <th>Transaction</th>
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
                            if (data == 'actdeac') location.href = "/jobcards/reports";
                        }

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
                                "autoWidth": true
                            });
                        });

                    </script>
@endsection