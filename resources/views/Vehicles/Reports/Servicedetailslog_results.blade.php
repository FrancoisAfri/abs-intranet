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
                    <h3 class="box-title">Service Report</h3>
                </div>
                <div class="box-body">
					<div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div style="overflow-X:auto;">
								<form class="form-horizontal" method="POST" action="/fleet/reports/Service/print">
									<input type="hidden" name="vehicle_id" value="{{!empty($vehicle_id) ? $vehicle_id : 0}}">
									<input type="hidden" name="vehicle_type" value="{{!empty($vehicle_type) ? $vehicle_type : ''}}">
									<input type="hidden" name="driver_id" value="{{!empty($driver_id) ? $driver_id : ''}}">
									<input type="hidden" name="action_date" value="{{!empty($action_date) ? $action_date : ''}}"> 
									<table id="example2" class="table table-bordered table-hover">
										<thead>
											<tr>
												<th style="width: 10px">Vehicle</th>
												<th>Date Serviced</th>
												<th>Garage</th>
												<th>Description</th>
												<th>Next Service Date</th>
												<th>Next Service Km</th>
												<th>Invoice Number</th>
												<th style="width: 5px; text-align: center;">Cost</th>
											</tr>
										</thead>
										<tbody>
											@if (count($serviceDetails) > 0)
											   @foreach($serviceDetails as $details)
													<tr>
														<td>{{ (!empty($details->VehicleMake) ) ? $details->VehicleMake." ".$details->VehicleModel." ".$details->vehicle_registration : ''}}</td>
														<td>{{ !empty($details->date_serviced) ? date('Y M d', $details->date_serviced) : '' }}</td>
														<td>{{ !empty($details->garage) ? $details->garage : '' }}</td>
														<td>{{ !empty($details->description) ? $details->description : '' }}</td>
														<td style="text-align: center">{{ !empty($details->nxt_service_date) ? date('Y M d', $details->nxt_service_date) : '' }}</td>
														<td style="text-align: center">{{ !empty($details->nxt_service_km) ? $details->nxt_service_km : ''}} </td>
														<td style="text-align: center">{{ !empty($details->invoice_number) ?  $details->invoice_number : ''}} </td>
														<td style="text-align: center">{{ !empty($details->total_cost) ? 'R' .number_format($details->total_cost, 2) : ''}} </td>
													</tr>
												@endforeach
											@endif
										</tbody>
										<tfoot>
											<tr>
												<th style="width: 10px">Vehicle</th>
												<th>Date</th>
												<th>Garage</th>
												<th>Description</th>
												<th>Next Service Date</th>
												<th>Next Service Km</th>
												<th>Invoice Number</th>
												<th style="width: 5px; text-align: center;">Cost</th>
											</tr>
											<tr>
												<th colspan="7" style="text-align: right">Total</th>
												<td style="text-align: right" nowrap>{{ !empty($totalCost) ? 'R' .number_format($totalCost, 2) : '' }}</td>
											</tr>
										</tfoot>
									</table>
									<div class="box-footer">
										<div class="row no-print">
											<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print Report</button>
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
			if (data == 'actdeac') location.href = "/vehicle_management/vehicle_reports";
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
						title: 'Service Report'
					},
					{
						extend: 'csvHtml5',
						title: 'Service Report'
					},
					{
						extend: 'copyHtml5',
						title: 'Service Report'
					}
				]
			});
		});
	</script>
@endsection