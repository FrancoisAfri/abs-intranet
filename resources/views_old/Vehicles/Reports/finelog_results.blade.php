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
				<h3 class="box-title">Fines Report</h3>
			</div>
			<div class="box-body">
				<div class="box">
					<!-- /.box-header -->
					<div class="box-body">
						<div style="overflow-X:auto;">
							<form class="form-horizontal" method="POST" action="/fleet/reports/fine/print">
								<input type="hidden" name="vehicle_id" value="{{!empty($vehicle_id) ? $vehicle_id : 0}}">
								<input type="hidden" name="vehicle_type" value="{{!empty($vehicle_type) ? $vehicle_type : ''}}">
								<input type="hidden" name="driver_id" value="{{!empty($driver_id) ? $driver_id : ''}}">
								<input type="hidden" name="action_date" value="{{!empty($action_date) ? $action_date : ''}}">               
								<table id="example2" class="table table-bordered table-hover">
									<thead>
										<tr>
											<th>Vehicle</th>
											<th>Date</th>
											<th>Time</th>
											<th>Reference</th>
											<th>Location</th>
											<th>Type</th>
											<th>Driver</th>
											<th>Amount</th>
											<th>Amount Paid</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										@if (count($vehiclefines) > 0)
										@foreach($vehiclefines as $fine)
											<tr>
												<td>{{ (!empty($fine->vehicle_make) ) ? $fine->vehicle_make." ".$fine->vehicle_model." ".$fine->vehicle_types." ".$fine->vehicle_registration : ''}}</td>
												<td>{{ !empty($fine->date_of_fine) ? date('Y M d', $fine->date_of_fine) : '' }}</td>
												<td>{{ !empty($fine->time_of_fine) ? date('h:m:z', $fine->time_of_fine) : '' }}</td>
												<td>{{ !empty($fine->fine_ref) ? $fine->fine_ref : '' }}</td>
												<td>{{ !empty($fine->location) ?  $fine->location : '' }}</td>
												<td>{{ !empty($fine->fine_type) ?  $fineType[$fine->fine_type] : '' }}</td>
												<td>{{ !empty($fine->firstname . ' ' . $fine->surname ) ?  $fine->firstname . ' ' . $fine->surname : '' }}</td>
												<td style="text-align: center">{{ !empty($fine->amount  ) ?  'R '.number_format($fine->amount, 2) :'' }}</td>
												<td style="text-align: center">{{ !empty($fine->amount_paid  ) ?  'R '.number_format($fine->amount_paid, 2) :'' }}</td>
												<td>{{ !empty($fine->fine_status  ) ?  $status[$fine->fine_status] :'' }}</td>
											</tr>
										@endforeach
										@endif
									</tbody>
									<tfoot>
										<tr>
											<th>Vehicle</th>
											<th>Date</th>
											<th>Time</th>
											<th>Reference</th>
											<th>Location</th>
											<th>Type</th>
											<th>Driver</th>
											<th>Amount</th>
											<th>Amount Paid</th>
											<th>Status</th>
										</tr>
										<tr>
											<th colspan="7" style="text-align:right">Total</th>
											<td style="text-align: center">{{  'R '.number_format($total, 2) }}</td>
											<td style="text-align: center">{{  'R '.number_format($totalamount_paid, 2) }}</td>
											<td style="text-align: right" nowrap></td>
										</tr>
									</tfoot>
								</table>
								<div class="box-footer">
									<div class="row no-print">
										<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print Report</button>
										<button type="button" id="canceled" class="btn btn-default pull-right"><i
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
		document.getElementById("canceled").onclick = function () {
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
						title: 'Fines Report'
					},
					{
						extend: 'csvHtml5',
						title: 'Fines Report'
					},
					{
						extend: 'copyHtml5',
						title: 'Fines Report'
					}
				]
			});
		});
	</script>
@endsection