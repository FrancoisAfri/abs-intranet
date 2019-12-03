@extends('layouts.main_layout')
@section('page_dependencies')
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/buttons.dataTables.min.css">
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <form class="form-horizontal" method="POST" action="/users/get_users_print">
				<input type="hidden" name="division_level_5" value="{{!empty($division_level_5) ? $division_level_5 : 0}}">
				<input type="hidden" name="division_level_4" value="{{!empty($division_level_4) ? $division_level_4 : 0}}">
				<input type="hidden" name="division_level_3" value="{{!empty($division_level_3) ? $division_level_3 : 0}}">
				<input type="hidden" name="division_level_2" value="{{!empty($division_level_2) ? $division_level_2 : ''}}">
				<input type="hidden" name="division_level_1" value="{{!empty($division_level_1) ? $division_level_1 : ''}}">
				<input type="hidden" name="hr_person_id" value="{{!empty($hr_person_id) ? $hr_person_id : ''}}">
                <div class="box-header with-border">
                    <h3 class="box-title">Users List Report</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    @if (count($errors) > 0)
                    <div class="alert alert-danger alert-dismissible fade in">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <table id="example2" class="table table-bordered table-striped table-hover">
                        <thead>
							<tr>
								<th>Firstname</th>
								<th>Surname</th>
								<th>Employee Number</th>
								<th>Email</th>
								<th>Position</th>
								<th>Report To</th>
								<th>Status</th>
								<th>Leave Profile</th>
							</tr>
                        </thead>
                        <tbody>
							@foreach($employees as $employee)
								<tr>
									<td>{{ !empty($employee->first_name) ? $employee->first_name : '' }}</td>
									<td>{{ !empty($employee->surname) ? $employee->surname : '' }}</td>
									<td>{{ !empty($employee->employee_number) ? $employee->employee_number : '' }}</td>
									<td>{{ !empty($employee->email) ? $employee->email : '' }}</td>
									<td>{{ !empty($employee->job_title) ? $employee->job_title : '' }}</td>
									<td>{{ !empty($employee->manager_first_name) && !empty($employee->manager_surname)  ? $employee->manager_first_name." ". $employee->manager_surname: '' }}</td>
									<td>{{ !empty($employee->status) && ($employee->status == 1)  ? 'Active': 'Inactive' }}</td>
									<td>{{ !empty($employee->profile_name) ? $employee->profile_name: '' }}</td>
								</tr>
							@endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Firstname</th>
							<th>Surname</th>
							<th>Employee Number</th>
							<th>Email</th>
							<th>Position</th>
							<th>Report To</th>
							<th>Status</th>
							<th>Leave Profile</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" id="cancel" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i> Cancel</button>
                    <button type="submit" id="print-report" class="btn btn-primary pull-right"><i class="fa fa-floppy-o"></i> Print</button>
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
		//Cancel button click event
		document.getElementById("cancel").onclick = function () {
			location.href = "/users/reports";
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
						title: 'Employee Report'
					},
					{
						extend: 'csvHtml5',
						title: 'Employee Report'
					},
					{
						extend: 'copyHtml5',
						title: 'Employee Report'
					}
				]
			});
		});
	</script>
@endsection