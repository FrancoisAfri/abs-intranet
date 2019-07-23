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
                    <h3 class="box-title">Fire Extinguisher Report</h3>
                </div>
				<div class="box-body">
					<div class="box">
						<!-- /.box-header -->
						<div class="box-body">
							<div style="overflow-X:auto;">
								<form class="form-horizontal" method="POST" action="/fleet/reports/fireExtinguisher/print">
									<input type="hidden" name="vehicle_id" value="{{!empty($vehicle_id) ? $vehicle_id : ''}}">
									<input type="hidden" name="action_date" value="{{!empty($action_date) ? $action_date : ''}}">				
									<table id="example2" class="table table-bordered table-hover">
										<thead>
											<tr>
												<th>Vehicle</th>
												<th>Date Purchased</th>
												<th>Barcode</th>
												<th>Item</th>
												<th>Service Provider</th>
												<th>Serial Number</th>
												<th>Weight</th>
												<th>Cost</th>
												<th>PO Number</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody>
											@if (count($fireExtinguishers) > 0)
												@foreach ($fireExtinguishers as $fireExtinguisher)
													<tr>
														<td>{{ !empty($fireExtinguisher->fleet_number) ? $fireExtinguisher->fleet_number : ''}}</td>
														<td>{{ !empty($fireExtinguisher->date_purchased) ? date('Y M d', $fireExtinguisher->date_purchased) : ''}} </td>
														<td>{{ !empty($fireExtinguisher->bar_code) ? $fireExtinguisher->bar_code : ''}} </td>
														<td>{{ !empty($fireExtinguisher->item_no) ? $fireExtinguisher->item_no : ''}} </td>
														<td>{{ !empty($fireExtinguisher->com_name) ? $fireExtinguisher->com_name : ''}} </td>
														<td>{{ !empty($fireExtinguisher->Serial_number) ?  $fireExtinguisher->Serial_number : ''}} </td>
														<td>{{ !empty($fireExtinguisher->Weight) ?  $fireExtinguisher->Weight : ''}} </td>
														<td>{{ !empty($fireExtinguisher->Cost) ?  'R ' .number_format($fireExtinguisher->Cost, 2) : ''}} </td>
														<td>{{ !empty($fireExtinguisher->purchase_order) ?  $fireExtinguisher->purchase_order : ''}} </td>
														<td>{{ !empty($fireExtinguisher->Status) ?  $statusArray[$fireExtinguisher->Status] : ''}} </td>
													</tr>
												@endforeach
											@endif
										</tbody>
										<tfoot>
											<tr>
												<th>Vehicle</th>
												<th>Date Purchased</th>
												<th>Barcode</th>
												<th>Item</th>
												<th>Service Provier</th>
												<th>Serial Number</th>
												<th>Weight</th>
												<th>Cost</th>
												<th>PO Number</th>
												<th>Status</th>
											</tr>
										</tfoot>
									</table>
									<div class="box-footer">
										<div class="row no-print">
											<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print Report</button>
											<button type="button" id="cancel" class="btn btn-default pull-right"><i
														class="fa fa-arrow-left"></i> Back</button>
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
						title: 'Fire Extinguisher Report'
					},
					{
						extend: 'csvHtml5',
						title: 'Fire Extinguisher Report'
					},
					{
						extend: 'copyHtml5',
						title: 'Fire Extinguisher Report'
					}
				]
			});
		});
	</script>
@endsection