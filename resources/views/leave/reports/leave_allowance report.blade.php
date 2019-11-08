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
                    <h3 class="box-title">Leave Allownce Report</h3>
                </div>
                <!-- /.box-header -->
				<form class="form-horizontal" method="POST" action="/leave/print-allowance">
                 <input type="hidden" name="hr_person_id" value="{{!empty($userID) ? $userID : ''}}">
                 <input type="hidden" name="leave_types_id" value="{{!empty($LevTypID) ? $LevTypID : ''}}">
					{{ csrf_field() }}
                <div class="box-body">
                    <!-- Collapsible section containing the amortization schedule -->
                    <div class="box-group" id="accordion">						
						<table id="example2" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>Employee Number </th>
									<th>Employee Name </th>
									<th>Leave Type </th>
									<th>Min Day(s)</th>
									<th>Max Day(s)</th>
								</tr>
							</thead>
							<tbody>
							 @if(count($allowances) > 0)
								@foreach($allowances as $allowance)
									<tr>
									   <td>{{ !empty($allowance->employee_number) ? $allowance->employee_number : '' }}</td>
										<td>{{ !empty($allowance->first_name) && !empty($allowance->surname) ? $allowance->first_name.' '.$allowance->surname : '' }}</td>
										<td>{{ !empty($allowance->leave_type_name) ? $allowance->leave_type_name : $allowance->leave_type_name }}</td>
										<td>{{ !empty($allowance->min) ? $allowance->min : $allowance->min }}</td>
										<td>{{ !empty($allowance->max) ? $allowance->max : $allowance->max }}</td>
									</tr>
								@endforeach
							@endif
							</tbody>
							<tfoot>
								<tr>
									<th>Employee Number </th>
									<th>Employee Name </th>
									<th>Leave Type </th>
									<th>Min Day(s)</th>
									<th>Max Day(s)</th>
								</tr>
							</tfoot>
						</table>
						<div class="row no-print">
							<div class="col-xs-12">
								<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i>Print report</button>
								<button type="button" id="cancel" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</button>
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
						title: 'Leave Allowance Report'
					},
					{
						extend: 'csvHtml5',
						title: 'Leave Allowance Report'
					},
					{
						extend: 'copyHtml5',
						title: 'Leave Allowance Report'
					}
				]
			});
		});
	</script>
@endsection