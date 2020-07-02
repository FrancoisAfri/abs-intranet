@extends('layouts.main_layout')
@section('page_dependencies')
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/buttons.dataTables.min.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                    <h3 class="box-title">Details Report</h3>
                </div>
                <div class="box-body">
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div style="overflow-X:auto;">
								<form class="form-horizontal" method="POST" action="/fleet/reports/details/print">
									<input type="hidden" name="vehicle_id" value="{{!empty($vehicle_id) ? $vehicle_id : 0}}">
									<input type="hidden" name="vehicle_type" value="{{!empty($vehicle_type) ? $vehicle_type : ''}}">
									<input type="hidden" name="vehicle_make" value="{{!empty($vehicle_make) ? $vehicle_make : ''}}">
									<input type="hidden" name="division_level_5" value="{{!empty($division_level_5) ? $division_level_5 : ''}}">
									<input type="hidden" name="division_level_4" value="{{!empty($division_level_4) ? $division_level_4 : ''}}">
									<input type="hidden" name="division_level_3" value="{{!empty($division_level_3) ? $division_level_3 : ''}}">
									<input type="hidden" name="division_level_2" value="{{!empty($division_level_2) ? $division_level_2 : ''}}">
									<input type="hidden" name="division_level_1" value="{{!empty($division_level_1) ? $division_level_1 : ''}}">
									<table id="example2" class="table table-bordered table-hover">
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
											<th>Kms/Hrs Reading</th>
											<th>Division</th>
											<th>Department</th>
										</tr>
										</thead>
										<tbody>
										@if (count($vehicledetails) > 0)
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
													<td>{{ !empty($details->department) ? $details->department : ''}}</td>
												</tr>
											@endforeach
										@endif
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
											<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print report</button>
											<button type="button" id="cancel" class="btn btn-default pull-right"><i
														class="fa fa-arrow-left"></i> Back
											</button>
										</div>
									</div>
								</form>
                            </div>
                        </div>
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
	<script src="/bower_components/AdminLTE/plugins/datatables/dataTables.buttons.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/buttons.flash.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/jszip.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/pdfmake.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/vfs_fonts.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/buttons.html5.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/buttons.print.min.js"></script>
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
			"lengthMenu": [ 50, 75, 100, 150, 200, 250 ],
			"pageLength": 50,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": true,
			dom: 'lfrtipB',
			buttons: [
				{
					extend: 'excelHtml5',
					title: 'Details Report'
				},
				{
					extend: 'csvHtml5',
					title: 'Details Report'
				},
				{
					extend: 'copyHtml5',
					title: 'Details Report'
				}
			]
			});
		});

	</script>
@endsection