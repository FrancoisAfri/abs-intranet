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
                    <h3 class="box-title">Cancelled Leave Applications Report</h3>
                </div>
                <!-- /.box-header -->
                <form class="form-horizontal" method="POST" action="/leave/reports/cancelled-leaves/print" target="_blank">
                    <input type="hidden" name="hr_person_id" value="{{ $employeeID }}">
                    <input type="hidden" name="leave_types_id" value="{{ $leaveTypeID }}">
                    <input type="hidden" name="action_date" value="{{ $action_date }}">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <!-- Collapsible section containing the amortization schedule -->
                        <div class="box-group" id="accordion">
                            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
									<tr>
										<th class="text-center" width="5px">#</th>
										<th>Employee Number</th>
										<th>Employee Name</th>
										<th>Leave Type</th>
										<th>Start Date</th>
										<th>End Date</th>
										<th>Cancelled By</th>
										<th>Cancellation Reason</th>
									</tr>
								</thead>
								<tbody>
                                @if(count($leaveApplications) > 0)
                                    @foreach($leaveApplications as $leaveApplication)
                                        <td class="text-center" nowrap>{{ $loop->iteration }}</td>
                                        <td nowrap>{{ ($leaveApplication->person) ? $leaveApplication->person->employee_number : '' }}</td>
                                        <td nowrap>{{ ($leaveApplication->person) ? $leaveApplication->person->full_name : '' }}</td>
                                        <td>{{ ($leaveApplication->leavetpe) ? $leaveApplication->leavetpe->name : '' }}</td>
                                        <td nowrap>{{ ($leaveApplication->start_time) ? date('d M Y H:i', $leaveApplication->start_time) : (($leaveApplication->start_date) ? date('d M Y', $leaveApplication->start_date) : '') }}</td>
                                        <td nowrap>{{ ($leaveApplication->end_time) ? date('d M Y H:i', $leaveApplication->end_time) : (($leaveApplication->end_date) ? date('d M Y', $leaveApplication->end_date) : '') }}</td>
                                        <td nowrap>{{ ($leaveApplication->canceller) ? $leaveApplication->canceller->full_name : '' }}</td>
                                        <td>{{ $leaveApplication->cancellation_reason }}</td>
                                    @endforeach
                                @endif
								</tbody>
								<tfoot>
									<tr>
										<th class="text-center" width="5px">#</th>
										<th>Employee Number</th>
										<th>Employee Name</th>
										<th>Leave Type</th>
										<th>Start Date</th>
										<th>End Date</th>
										<th>Cancelled By</th>
										<th>Cancellation Reason</th>
									</tr>
								</tfoot>
                            </table>
                            <div class="row no-print">
                                <div class="col-xs-12">
                                    <a href="/leave/reports" id="cancel" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</a>
                                    <button type="submit" id="cancel" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print</button>
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
						title: 'Leave Cancelled Report'
					},
					{
						extend: 'csvHtml5',
						title: 'Leave Cancelled Report'
					},
					{
						extend: 'copyHtml5',
						title: 'Leave Cancelled Report'
					}
				]
			});
		});
	</script>
@endsection