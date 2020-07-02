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
                    <h3 class="box-title">Job Card Notes Report</h3>
                </div>
				<!-- /.box-header -->
				<div class="box-body">
					<div style="overflow-X:auto;">
						<form class="form-horizontal" method="POST" action="/jobcards/reports/notesprint">
								 <input type="hidden" name="action_from" value="{{!empty($actionFrom) ? $actionFrom : ''}}">
								 <input type="hidden" name="action_to" value="{{!empty($actionTo) ? $actionTo : ''}}">
								 <input type="hidden" name="note_details" value="{{!empty($noteDetails) ? $noteDetails : ''}}">
								 <input type="hidden" name="user_id" value="{{!empty($userID) ? $userID : ''}}">
								 <input type="hidden" name="vehicle_id" value="{{!empty($vehicleID) ? $vehicleID : ''}}">
							<table id="example2" class="table table-bordered table-hover">
								<thead>
								<tr>
									<th>Vehicle</th>
									<th style="width: 5px; text-align: center;"> Job Card #</th>
									<th>Job note Date</th>
									<th>Note Details</th>
									<th>Employees</th>
								</tr>
								</thead>
								<tbody>
									@if (count($notes) > 0)
										@foreach ($notes as $note)
											<tr id="categories-list">
												<td>{{ !empty($note->fleet_no . '' . $note->vehicleregistration) ? $note->fleet_no. '' . $note->vehicleregistration : '' }}</td>
												<td>{{ !empty($note->jobcard_id) ? $note->jobcard_id : '' }}</td>
												<td>{{ !empty($note->date_default) ? date(' d M Y', $note->date_default) : ''}}</td>
												<td>{{ !empty($note->note_details) ? $note->note_details : '' }}</td>
												<td>{{ !empty($note->firstname . '' . $note->surname) ? $note->firstname. ' ' . $note->surname : '' }}</td>
												</td>
											</tr>
										@endforeach
									@endif
								</tbody>
								<tfoot>
									<tr>
										<th>Vehicle</th>
										<th style="width: 5px; text-align: center;"> Job Card #</th>
										<th>Job note Date</th>
										<th>Note Details</th>
										<th>Employees</th>
									</tr>
								</tfoot>
							</table>
							<div class="box-footer">

								<div class="row no-print">
									<button type="button" id="cancel" class="btn btn-default pull-right"><i
												class="fa fa-arrow-left"></i> Back
									</button>
									 <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print report</button> 
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
		//Cancel button click event
		document.getElementById("cancel").onclick = function () {
			location.href = "/jobcards/reports";
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
						title: 'Bookings Report'
					},
					{
						extend: 'csvHtml5',
						title: 'Bookings Report'
					},
					{
						extend: 'copyHtml5',
						title: 'Bookings Report'
					}
				]
			});
		});
	</script>
@endsection