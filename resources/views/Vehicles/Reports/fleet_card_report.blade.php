@extends('layouts.main_layout')
@section('page_dependencies')
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                    <h3 class="box-title">Fleet Cards Report</h3>
                </div>
	<div class="box-body">
		<div class="box">
			<!-- /.box-header -->
			<div class="box-body">
				<div style="overflow-X:auto;">
				<form class="form-horizontal" method="POST" action="/fleet/reports/fleet_card/print">
					<input type="hidden" name="vehicle_id" value="{{!empty($vehicle_id) ? $vehicle_id : 0}}">
					<input type="hidden" name="company_id" value="{{!empty($company_id) ? $company_id : ''}}">
					<input type="hidden" name="card_type_id" value="{{!empty($card_type_id) ? $card_type_id : ''}}">
					<input type="hidden" name="vehicle_type" value="{{!empty($vehicle_type) ? $vehicle_type : ''}}">
					<input type="hidden" name="driver_id" value="{{!empty($driver_id) ? $driver_id : ''}}">
					<input type="hidden" name="action_date" value="{{!empty($action_date) ? $action_date : ''}}">				
					<table id="example2" class="table table-bordered table-hover">
						<thead>
						<tr>
							<th>Fleet Card Type</th>
							<th>Vehicle Fleet Number</th>
							<th>Card Holder</th>
							<th>Card Number</th>
							<th>CVS Number</th>
							<th>Issued By</th>
							<th>Issued Date</th>
							<th>Expiry Date</th>
							<th>Status</th>
						</tr>
						</thead>
						<tbody>
						@if (count($fleetcards) > 0)
							@foreach ($fleetcards as $fleetcard)
								<tr>
									<td>{{ !empty($fleetcard->type_name ) ? $fleetcard->type_name : '' }}</td>
									<td>{{ !empty($fleetcard->fleetnumber ) ? $fleetcard->fleetnumber : '' }}</td>
									<td>{{ !empty($fleetcard->first_name . '' . $fleetcard->surname ) ? $fleetcard->first_name . '' . $fleetcard->surname : ''}}</td>
									<td>{{ !empty($fleetcard->card_number) ? $fleetcard->card_number : ''}}</td>
									<td>{{ !empty($fleetcard->cvs_number) ? $fleetcard->cvs_number : ''}}</td>
									<td>{{ !empty($fleetcard->Vehicle_Owner) ? $fleetcard->Vehicle_Owner : ''}}</td>
									<td>{{ !empty($fleetcard->issued_date ) ? date("d/m/Y", $fleetcard->issued_date) : ''}}</td>
									<td>{{ !empty($fleetcard->expiry_date ) ? date("d/m/Y",  $fleetcard->expiry_date) : ''}}</td>
									<td>{{ !empty($fleetcard->status) ? $status[$fleetcard->status] : ''}}</td>
								</tr>
							@endforeach
						@endif
						</tbody>
						<tfoot>
						<tr>
							<th>Fleet Card Type</th>
							<th>Vehicle Fleet Number</th>
							<th>Card Holder</th>
							<th>Card Number</th>
							<th>CVS Number</th>
							<th>Issued By</th>
							<th>Issued Date</th>
							<th>Expiry Date</th>
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
	</div>
</div>
@endsection

@section('page_script')
	<!-- DataTables -->
		<script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
		<script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
		<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
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
					"searching": true,
					"ordering": true,
					"info": true,
					"autoWidth": true,
					dom: 'Bfrtip',
					buttons: [
						'copy', 'csv', 'excel'
					]
				});
			});
		</script>
@endsection