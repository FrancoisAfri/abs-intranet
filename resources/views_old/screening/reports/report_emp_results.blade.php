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
                    <h3 class="box-title"><b>{{$companyFive->name}}</b> Report</h3>
                </div>
				<!-- /.box-header -->
				<div class="box-body">
					<div style="overflow-X:auto;">
						<form class="form-horizontal" method="POST" action="/fleet/reports/booking/print">
							<table id="example2" class="table table-bordered table-hover">
								<thead>
									<tr>
										<th>{{$levelsFour->name}}</th>
										<th>Emp No</th>
										<th>Names</th>
										<th>Date</th>
										<th>Admin</th>
										<th>Temp</th>
										<th>Comment</th>
										<th>Screening Status</th>
										<th>Clock In </th>
									</tr>
								</thead>
								<tbody>
									@if(!empty($screenings))
										@foreach($screenings as $screening)
											<tr bgcolor="{{!empty($screening->background_temp) ? $screening->background_temp : ''}}">
												<td>{{ !empty($screening->site->name) ? $screening->site->name : '' }}</td>
												<td>{{ !empty($screening->employee_number) ? $screening->employee_number : '' }}</td>
												<td>{{ !empty($screening->employee->first_name) && !empty($screening->employee->surname) ? $screening->employee->first_name.' '.$screening->employee->surname : '' }}</td>
												<td>{{ !empty($screening->date_captured) ? date('d M Y', $screening->date_captured) : '' }}</td>
												<td>{{ !empty($screening->administrtor->first_name) && !empty($screening->administrtor->surname) ? $screening->administrtor->first_name.' '.$screening->administrtor->surname : '' }}</td>
												<td>{{ !empty($screening->temperature) ? $screening->temperature : '' }}</td>
												<td>{{ !empty($screening->comment) ? $screening->comment : '' }}</td>
												<td>{{ empty($screening->background) ? "Good" : "Not Good" }}</td>
												<td>{{ !empty($screening->clockin_time) ? $screening->clockin_time : '' }}</td>
												
											</tr>
										@endforeach
									@endif
								</tbody>
								<tfoot>
									<tr>
										<th>{{$levelsFour->name}}</th>
										<th>Emp No</th>
										<th>Names</th>
										<th>Date</th>
										<th>Admin</th>
										<th>Temp</th>
										<th>Comment</th>
										<th>Screening Status</th>
										<th>Clock In </th>
									</tr>
								</tfoot>
							</table>
							<div class="box-footer">
								<div class="row no-print">
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
		//Cancel button click event
		document.getElementById("cancel").onclick = function () {
			location.href = "/screening/report";
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
						title: 'Employees Screening Report'
					},
					{
						extend: 'csvHtml5',
						title: 'Employees Screening Report'
					},
					{
						extend: 'copyHtml5',
						title: 'Employees Screening Report'
					}
				]
			});
		});
	</script>
@endsection