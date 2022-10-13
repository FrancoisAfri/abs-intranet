@extends('layouts.main_layout')
@section('page_dependencies')
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"') }}">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
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
							<th>Date Joined</th>
							<th>Date Left Company</th>
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
								<td>{{ ($employee->date_joined) ? date('d/m/Y',$employee->date_joined) : '' }}</td>
								<td>{{ ($employee->date_left) ? date('d/m/Y',$employee->date_left) : '' }}</td>
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
						<th>Date Joined</th>
						<th>Date Left Company</th>
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
				<button type="button" id="cancel" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i> Back</button>
			</div>
        </div>
    </div>
</div>
@endsection
@section('page_script')
	<!-- DataTables -->
	<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('custom_components/js/modal_ajax_submit.js') }}"></script>
    <script src="{{ asset('custom_components/js/deleteAlert.js') }}"></script>

    <script src="{{ asset('bower_components/bootstrap_fileinput/js/fileinput.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>


    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
		<!-- End Bootstrap File input -->
	<script>
		//Cancel button click event
		document.getElementById("cancel").onclick = function () {
			location.href = "/users/reports";
		};
		$(function () {
			$('#example2').DataTable({
                paging: true,
                lengthChange: true,
				lengthMenu: [ 50, 75, 100, 150, 200, 250 ],
				pageLength: 50,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
		});
	</script>
@endsection