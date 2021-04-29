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
                    <h3 class="box-title">Leave History Report</h3>
                </div>
                <!-- /.box-header -->
				<form class="form-horizontal" method="POST" action="/leave/print">
                 <input type="hidden" name="actionDate" value="{{!empty($actionDate) ? $actionDate : ''}}">
                 <input type="hidden" name="hr_person_id" value="{{!empty($hr_person_id) ? $hr_person_id : ''}}">
                 <input type="hidden" name="report" value="{{!empty($report) ? $report : ''}}">
                 <input type="hidden" name="leave_types_id" value="{{!empty($leave_types_id) ? $leave_types_id : ''}}">
					{{ csrf_field() }}
                <div class="box-body">
                    <!-- Collapsible section containing the amortization schedule -->
                    <div class="box-group" id="accordion">
                        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
						<table id="example2" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>Employee Number </th>
									<th>Names</th>
									<th>Action</th>
									<th>Action Date</th>
									<th>Leave Type</th>
									<th>Previous Balance</th>
									<th>Transaction</th>
									<th>Current Balance</th>
									<th>Performed By</th>
								</tr>
							</thead>
							<tbody>
							@if(count($historyAudit) > 0)
								@foreach($historyAudit as $audit)
									<tr>
									   <td>{{ !empty($audit->employee_number) ? $audit->employee_number : '' }}</td>
										<td>{{ !empty($audit->firstname) && !empty($audit->surname) ? $audit->firstname.' '.$audit->surname : '' }}</td>
										<td>{{ !empty($audit->action) ? $audit->action : '' }}</td>
										<td>{{ !empty($audit->action_date) ? date('Y M d : H : i : s', $audit->action_date) : '' }}</td>
										<td>{{ !empty($audit->leave_type) ? $audit->leave_type : '' }}</td>
										<td>{{ !empty($audit->previous_balance) ? number_format($audit->previous_balance / 8, 2) : '' }}</td>
										<td>{{ !empty($audit->transcation) ? number_format($audit->transcation / 8, 2) : '' }}</td>
										<td>{{ !empty($audit->current_balance) ? number_format($audit->current_balance / 8, 2): '' }}</td>
										<td>{{ !empty($audit->added_by_name) ? $audit->added_by_name : '' }}</td>
									</tr>
								@endforeach
							@endif
							</tbody>
							<tfoot>
								<tr>
									<th>Employee Number </th>
									<th>Names</th>
									<th>Action</th>
									<th>Action Date</th>
									<th>Leave Type</th>
									<th>Previous Balance</th>
									<th>Transaction</th>
									<th>Current Balance</th>
									<th>Performed By</th>
								</tr>
							</tfoot>
						</table>
						<div class="row no-print">
							<div class="col-xs-12">
							<button type="button" id="cancel" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</button>
								<button type="submit" id="print" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print report</button>
								 <!-- <button type="button" id="cancel" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Cancel</button> -->
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
						title: 'Leave History Report'
					},
					{
						extend: 'csvHtml5',
						title: 'Leave History Report'
					},
					{
						extend: 'copyHtml5',
						title: 'Leave History Report'
					}
				]
			});
		});
	</script>
@endsection