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
                        <h3 class="box-title">Vehicle Incident Report </h3>
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
                                    @endforeach
                                </tr>

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
                        </table>
                        <div class="box-footer">

                            <div class="row no-print">
                                <button type="button" id="cancel" class="btn btn-default pull-left"><i
                                            class="fa fa-arrow-left"></i> Back to Search Page
                                </button>
                                <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i>
                                    Print report
                                </button>
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