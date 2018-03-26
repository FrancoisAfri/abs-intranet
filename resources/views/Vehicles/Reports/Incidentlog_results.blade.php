@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
    <!-- DataTables -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <form class="form-horizontal" method="POST" action="/System/policy/update_status">
                    {{ csrf_field() }}

                    <div class="box-header with-border">
                        <h3 class="box-title">Vehicle Fuel Report </h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-remove"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                                </button>
                                <h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <table id="emp-list-table" class="table table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <th style="width: 10px"></th>
                                <th>Date Reported</th>
                                <th>Reported By	</th>
                                <th>Odometer Reading Km</th>
                                <th>Incident Type</th>
                                <th>Severity</th>
                                <th>Cost </th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($vehicleincidents as $details)
                                <tr>
                                    <td>{{ !empty($details->date) ? date(' d M Y', $details->date) : '' }}</td>

                                    {{--<td>{{ !empty($details->Hoursreading) ?  $details->Hoursreading: '' }}</td>--}}
                                    {{--<td>{{ !empty($details->litres) ?  $details->litres: '' }}</td>--}}
                                    {{--<td>{{ !empty($details->total_cost) ?  $details->total_cost: '' }}</td>--}}
                                    {{--<td>{{ !empty($details->cost_per_litre) ?  $details->cost_per_litre: '' }}</td>--}}

                                    @endforeach
                                </tr>

                            </tbody>
                            <tfoot>
                            <tr>
                                <th style="width: 10px"></th>
                                <th>Date Reported</th>
                                <th>Reported By	</th>
                                <th>Odometer Reading Km</th>
                                <th>Incident Type</th>
                                <th>Severity</th>
                                <th>Cost </th>
                                <th>Status</th>
                            </tr>
                            </tfoot>
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
                    <!-- /.box-body -->

                </form>
            </div>
        </div>

    </div>
@endsection

@section('page_script')
    <script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- End Bootstrap File input -->
    <script>
        $(function () {

            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();

            //Initialize the data table
            $('#emp-list-table').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": false,
                "autoWidth": true
            });

            //Cancel button
            $('#cancel').click(function () {
                location.href = "/vehicle_management/vehicle_reports";
            });

            //Show success action modal
            @if(Session('changes_saved'))
            $('#success-action-modal').modal('show');
            @endif
        });
    </script>
@endsection