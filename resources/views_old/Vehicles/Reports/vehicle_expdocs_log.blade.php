@extends('layouts.main_layout')
@section('page_dependencies')
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/buttons.dataTables.min.css">
	<!--Time Charger-->
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                    <h3 class="box-title">Expired Documents & Licence Report</h3>
                </div>
                <div class="box-body">
                    <div class="box">
                        <h3 class="box-title">Expired Documents </h3>
                        <!-- /.box-header -->
                        <form class="form-horizontal" method="POST" action="/fleet/reports/expdocs/print">
							<div class="box-body">
								<div style="overflow-X:auto;">
									<input type="hidden" name="vehicle_id" value="{{!empty($vehicle_id) ? $vehicle_id : 0}}">
									<input type="hidden" name="vehicle_type" value="{{!empty($vehicle_type) ? $vehicle_type : ''}}">
									<input type="hidden" name="action_date" value="{{!empty($action_date) ? $action_date : ''}}">
									<table id="example2" class="table table-bordered table-hover">
										<thead>
											<tr>
												<th>Fleet Number</th>
												<th>Make</th>
												<th>Model</th>
												<th>Registration</th>
												<th>Division</th>
												<th>Department</th>
												<th>Expired Date</th>
												<th>Days Remaining </th>
											</tr>
										</thead>
										<tbody>
											@if (count($vehicleDocumets) > 0)
												@foreach ($vehicleDocumets as $filling)
													<tr id="categories-list">

													   <!--  <td>{{ (!empty( $filling->date)) ?   date(' d M Y', $filling->date) : ''}} </td> -->
														<td>{{ (!empty( $filling->fleet_number)) ?  $filling->fleet_number : ''}} </td>
														<td>{{ (!empty( $filling->VehicleMake)) ?  $filling->VehicleMake : ''}} </td>
														<td>{{ (!empty( $filling->VehicleModel)) ?  $filling->VehicleModel : ''}} </td>
														<td>{{ (!empty( $filling->vehicle_registration)) ?  $filling->vehicle_registration : ''}} </td>
														<td>{{ (!empty( $filling->company)) ?  $filling->company : ''}} </td>
														<td>{{ (!empty( $filling->Department)) ?  $filling->Department : ''}} </td>
														<td>{{ (!empty( $filling->exp_date)) ?   date('Y M d', $filling->exp_date) : ''}} </td>
														<td bgcolor="red"> Expired </td>

													</tr>
												@endforeach
											@endif
										</tbody>
										<tfoot>
										<tr>
											<th>Fleet Number</th>
											<th>Make</th>
											<th>Model</th>
											<th>Registration</th>
											<th>Division</th>
											<th>Department</th>
											<th>Expired Date</th>
											<th>Days Remaining </th>
										</tr>
										</tfoot>
									</table>
									<div class="box-footer">
										<div class="row no-print">
											<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print report</button>
										</div>
									</div>
								</div>
							</div>
                        </form>
                    </div>
                </div>
				<div class="box-body">
					<div class="box">
						<h3 class="box-title">Expired Licences</h3>
						<!-- /.box-header -->
						<form class="form-horizontal" method="POST" action="/fleet/reports/expLic/print">
							<div class="box-body">
								<div style="overflow-X:auto;">
									<table id="example" class="table table-bordered table-hover">
											<input type="hidden" name="vehicle_id" value="{{!empty($vehicle_id) ? $vehicle_id : 0}}">
											<input type="hidden" name="vehicle_type" value="{{!empty($vehicle_type) ? $vehicle_type : ''}}">
											<input type="hidden" name="action_date" value="{{!empty($action_date) ? $action_date : ''}}">
										<thead>
										<tr>
											<th>Fleet Number</th>
											<th>Make</th>
											<th>Model</th>
											<th>Registration</th>
											<th>Division</th>
											<th>Department</th>
											<th>Supplier</th>
											<th>Captured By</th>
											<th>Date Expired</th>
											<th style="width: 8px; text-align: center;">Days Remaining</th>
										</tr>
										</thead>
										<tbody>
										@if (count($VehicleLicences) > 0)
											@foreach ($VehicleLicences as $filling)
												<tr id="categories-list">
													<td>{{ (!empty( $filling->fleet_number)) ?  $filling->fleet_number : ''}} </td>
													<td>{{ (!empty( $filling->VehicleMake)) ?  $filling->VehicleMake : ''}} </td>
													<td>{{ (!empty( $filling->VehicleModel)) ?  $filling->VehicleModel : ''}} </td>
													<td>{{ (!empty( $filling->vehicle_registration)) ?  $filling->vehicle_registration : ''}} </td>
													<td>{{ (!empty( $filling->company)) ?  $filling->company : ''}} </td>
													<td>{{ (!empty( $filling->Department)) ?  $filling->Department : ''}} </td>
													<td>{{ (!empty( $filling->supplier)) ?  $filling->supplier : ''}}</td>
													<td>{{ (!empty( $filling->captured_by)) ?  $filling->captured_by : ''}} </td>
													<td>{{ (!empty( $filling->exp_date)) ?   date(' d M Y', $filling->exp_date) : ''}} </td>
													<td bgcolor="red"> Expired </td>
												</tr>
											@endforeach
										@endif
										</tbody>
										<tfoot>
										<tr>
											<th>Fleet Number</th>
											<th>Make</th>
											<th>Model</th>
											<th>Registration</th>
											<th>Division</th>
											<th>Department</th>
											<th>Supplier</th>
											<th>Captured By</th>
											<th>Date Expired</th>
											<th style="width: 8px; text-align: center;">Days Remaining</th>
										</tr>
										</tfoot>
									</table>
									<div class="box-footer">
										<div class="row no-print">
											<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print Report</button>
											
											<button type="button" id="canceled" class="btn btn-default pull-right"><i class="fa fa-arrow-left"></i> Back</button>
										</div>
									</div>
								</div>
							</div>
						</form>
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
    <script>
	//Cancel button click event
	document.getElementById("canceled").onclick = function () {
		location.href = "/vehicle_management/vehicle_reports";
	};
			
	$(function ()  {
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
					title: 'Expired Documents Report'
				},
				{
					extend: 'csvHtml5',
					title: 'Expired Documents Report'
				},
				{
					extend: 'copyHtml5',
					title: 'Expired Documents Report'
				}
			]
		});
	});

	$(function () {
		$('#example').DataTable({
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
					title: 'Expired Licences Report'
				},
				{
					extend: 'csvHtml5',
					title: 'Expired Licences Report'
				},
				{
					extend: 'copyHtml5',
					title: 'Expired Licences Report'
				}
			]
		});
	});
    </script>
@endsection