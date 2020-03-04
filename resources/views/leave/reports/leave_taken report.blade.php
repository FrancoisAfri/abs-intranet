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
                    <h3 class="box-title">Leave Taken Report</h3>
                </div>
                <!-- /.box-header -->
				<form class="form-horizontal" method="POST" action="/leave_taken/print">
                 <input type="hidden" name="action_date" value="{{!empty($actionDate) ? $actionDate : ''}}">
                 <input type="hidden" name="hr_person_id" value="{{!empty($userID) ? $userID : ''}}">
                 <input type="hidden" name="leave_types_id" value="{{!empty($LevTypID) ? $LevTypID : ''}}">
					{{ csrf_field() }}
                <div class="box-body">
                    <!-- Collapsible section containing the amortization schedule -->
                    <div class="box-group" id="accordion">
                        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                        <div class="panel box box-primary">
                            <div class="box-body">
								<table id="example2" class="table table-bordered table-hover">
									<thead>
										<tr>
											<th>Employee Number </th>
											<th>Employee Name </th>
											<th>Leave Type</th>
											<th>Date taken</th>
											<th>Day(s)</th>
										</tr>
									</thead>
									<tbody>
									@if(count($leaveTakens) > 0)
										@foreach($leaveTakens as $leaveTaken)
											<tr>
											   <td>{{ !empty($leaveTaken->employee_number) ? $leaveTaken->employee_number : '' }}</td>
												<td>{{ !empty($leaveTaken->first_name) && !empty($leaveTaken->surname) ? $leaveTaken->first_name.' '.$leaveTaken->surname : '' }}</td>
												<td>{{ !empty($leaveTaken->leave_type_name) ? $leaveTaken->leave_type_name : '' }}</td>
												<td>{{ !empty($leaveTaken->start_date) ? date('Y M d : H : i : s', $leaveTaken->start_date) : '' }}</td>
												<td>{{ !empty($leaveTaken->leave_taken) ? number_format($leaveTaken->leave_taken/8, 2) : '' }} days(s)</td>
											</tr>
										@endforeach
									@endif
									</tbody>
									<tfoot>
										<tr>
											<th>Employee Number </th>
											<th>Employee Name </th>
											<th>Leave Type</th>
											<th>Date taken</th>
											<th>Day(s)</th>
										</tr>
									</tfoot>
								</table>
								<div class="row no-print">
									<div class="col-xs-12">
									<button type="button" id="cancel" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</button>
										<button type="submit" id="print" class="btn btn-primary pull-right"><i class="fa fa-print"></i>Print report</button>
										 <!-- <button type="button" id="cancel" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Cancel</button> -->
									</div>
								</div>
								<!-- End amortization /table -->
							</div>
                        </div>
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
						title: 'Leave Taken Report'
					},
					{
						extend: 'csvHtml5',
						title: 'Leave Taken Report'
					},
					{
						extend: 'copyHtml5',
						title: 'Leave Taken Report'
					}
				]
			});
		});
	</script>
@endsection