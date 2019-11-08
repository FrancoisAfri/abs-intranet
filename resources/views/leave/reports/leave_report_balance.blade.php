@extends('layouts.main_layout')
@section('page_dependencies')
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/buttons.dataTables.min.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Leave Report  Balance</h3>
                </div>
                <!-- /.box-header -->
				<form class="form-horizontal" method="POST" action="/leave/bal">
				<input type="hidden" name="userID" value="{{!empty($userID) ? $userID : ''}}">
				<input type="hidden" name="LevTypID" value="{{!empty($LevTypID) ? $LevTypID : ''}}">
               <!-- -->
					{{ csrf_field() }}
                <div class="box-body">
                    <!-- Collapsible section containing the amortization schedule -->
                    <div class="box-group" id="accordion">
                        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                        <table id="example2" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>Employee Number </th>
									<th>Employee Name </th>
									<th>Leave Type</th>
									<th>Balance days(s)</th>
								</tr>
							</thead>
							<tbody>
							@if(count($credit) > 0)
								@foreach($credit as $audit)
									<tr>
										<td>{{ !empty($audit->employee_number) ? $audit->employee_number : '' }}</td>
										<td>{{ !empty($audit->first_name) && !empty($audit->surname) ? $audit->first_name.' '.$audit->surname : '' }}</td>
										<td>{{ !empty($audit->leaveType) ? $audit->leaveType : '' }}</td>
										<td>{{ !empty($audit->Balance) ? number_format($audit->Balance/8, 2) : '' }} days(s)</td>
									</tr>
								@endforeach
							@endif
							</tbody>
							<tfoot>
								<tr>
									<th>Employee Number </th>
									<th>Employee Name </th>
									<th>Leave Type</th>
									<th>Balance days(s)</th>
								</tr>
							</tfoot>
						</table>
						<div class="row no-print">
							<div class="col-xs-12">
								<button type="button" id="cancel" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</button>
								<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i>Print report</button>
							</div>
						</div>
								<!-- End amortization /table -->
                    </div>
                    <!-- /. End Collapsible section containing the amortization schedule -->
                </div>
				</form>
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
		$(function () {
		$('#cancel').click(function () {
			location.href = '/leave/reports';
		});
	})

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
						title: 'Leave Balance Report'
					},
					{
						extend: 'csvHtml5',
						title: 'Leave Balance Report'
					},
					{
						extend: 'copyHtml5',
						title: 'Leave Balance Report'
					}
				]
			});
		});
	</script>
@endsection