@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- Include Date Range Picker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
@endsection

@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                </div>
                <form class="form-horizontal" method="POST" action="/vehicle_management/bookings_search_results">
                    {{ csrf_field() }}
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
                        <div class="col-md-8 col-md-offset-2">
                            <div>
                                <div class="box-header with-border" align="center">
                                    <h3 class="box-title">Search Criteria</h3>
                                </div>
                                <div class="box-body" id="vehicle_details">
                                    <div class="form-group">
                                        <label for="vehicle_type" class="col-sm-2 control-label">Vehicle Type</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <select class="form-control select2" style="width: 100%;"
                                                        id="vehicle_type" name="vehicle_type">
                                                    <option value="">*** Select a Vehicle Type ***</option>
                                                    @foreach($Vehicle_types as $Vehicle)
                                                        <option value="{{ $Vehicle->id }}">{{ $Vehicle->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div><div class="form-group">
                                        <label for="make_id" class="col-sm-2 control-label">Vehicle Make</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <select class="form-control select2" style="width: 100%;"
                                                        id="make_id" name="make_id">
                                                    <option value="">*** Select a Vehicle Make ***</option>
                                                    @foreach($vehiclemake as $make)
                                                        <option value="{{ $make->id }}">{{ $make->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div><div class="form-group">
                                        <label for="model_id" class="col-sm-2 control-label">Vehicle Model</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <select class="form-control select2" style="width: 100%;"
                                                        id="model_id" name="model_id">
                                                    <option value="">*** Select a Vehicle Type ***</option>
                                                    @foreach($vehiclemodel as $model)
                                                        <option value="{{ $model->id }}">{{ $model->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label for="driver_id" class="col-sm-2 control-label">Driver</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <select class="form-control select2" style="width: 100%;"
                                                        id="driver_id" name="driver_id">
                                                    <option value="">*** Select Driver ***</option>
                                                    @foreach($employees as $employee)
                                                        <option value="{{ $employee->id }}">{{ $employee->first_name." ".$employee->surname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
										<label for="action_date" class="col-sm-2 control-label">Booking Date</label>
										<div class="col-sm-10">
											<div class="input-group">
											<input type="text" class="form-control daterangepicker" id="booking_date" name="booking_date" value="" placeholder="Select Booking Date...">
											</div>
										</div>
									</div> 
									<div class="form-group">
										<label for="action_date" class="col-sm-2 control-label">Fleet Number</label>
										<div class="col-sm-10">
											<div class="input-group">
											<input type="text" class="form-control" id="fleet_number" name="fleet_number" value="" placeholder="Enter Fleet Number">
											</div>
										</div>
									</div>
									<div class="form-group">
                                        <label for="path" class="col-sm-2 control-label">Booking Status</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <select class="form-control select2" style="width: 100%;"
                                                        id="booking_status" name="booking_status">
                                                    <option value="">*** Select a Status ***</option>
                                                    <option value="1">Pending Driver Manager Approval</option>
                                                    <option value="2">Pending Capturer Manager Approval</option>
                                                    <option value="3">Pending HOD Approval</option>
                                                    <option value="4">Pending Admin Approval</option>
                                                    <option value="10">Approved</option>
                                                    <option value="11">Collected</option>
                                                    <option value="12">Returned</option>
                                                    <option value="13">Cancelled</option>
                                                    <option value="14">Rejected</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label for="path" class="col-sm-2 control-label">Usage Type</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <select class="form-control select2" style="width: 100%;"
                                                        id="usage_type" name="usage_type">
                                                    <option value="">*** Select Usage Type ***</option>
                                                    <option value="1">Usage</option>
                                                    <option value="2">Service</option>
                                                    <option value="3">Maintenance</option>
                                                    <option value="4">Repair</option>                                                    
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Search </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection

@section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
<!-- Bootstrap date picker -->
	<script src="/bower_components/AdminLTE/plugins/daterangepicker/moment.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>

	<script type="text/javascript">
		$('#back_button').click(function () {
                    location.href = '/vehicle_management/create_request';
                });

        $(function () {
            $(".select2").select2();
            $('.daterangepicker').daterangepicker({
				format: 'dd/mm/yyyy',
				endDate: '-1d',
				autoclose: true
			});
        });
    </script>
@endsection
