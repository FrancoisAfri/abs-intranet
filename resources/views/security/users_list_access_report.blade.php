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
				<h3 class="box-title">Users Access Report(Module: {{ $moduleName }})</h3>
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
						<th>Employee</th>
						<th style="vertical-align: middle; text-align: center;">None</th>
						<th style="vertical-align: middle; text-align: center;">Read</th>
						<th style="vertical-align: middle; text-align: center;">Write</th>
						<th style="vertical-align: middle; text-align: center;">Modify</th>
						<th style="vertical-align: middle; text-align: center;">Admin</th>
						<th style="vertical-align: middle; text-align: center;">Superuser</th>
					</tr>
					</thead>
					<tbody>
					@foreach($employees as $employee)
						<tr>
							<td style="vertical-align: middle;" nowrap>{{ $employee->full_name }}</td>
							<td style="vertical-align: middle; text-align: center;">
								{{ $employee->access_level == 0 ?  "Yes": '' }}
							</td>
							<td style="vertical-align: middle; text-align: center;">
								{{ $employee->access_level == 1 ?  "Yes": '' }}
							</td>
							<td style="vertical-align: middle; text-align: center;">
								{{ $employee->access_level == 2 ?  "Yes": '' }}
							</td>
							<td style="vertical-align: middle; text-align: center;">
								{{ $employee->access_level == 3 ?  "Yes": '' }}
							</td>
							<td style="vertical-align: middle; text-align: center;">
								{{ $employee->access_level == 4 ?  "Yes": '' }}
							</td>
							<td style="vertical-align: middle; text-align: center;">
								{{ $employee->access_level == 5 ?  "Yes": '' }}
							</td>
						</tr>
					@endforeach
					</tbody>
					<tfoot>
					<tr>
						<th>Employee</th>
						<th style="vertical-align: middle; text-align: center;">None</th>
						<th style="vertical-align: middle; text-align: center;">Read</th>
						<th style="vertical-align: middle; text-align: center;">Write</th>
						<th style="vertical-align: middle; text-align: center;">Modify</th>
						<th style="vertical-align: middle; text-align: center;">Admin</th>
						<th style="vertical-align: middle; text-align: center;">Superuser</th>
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

    <!-- Include modal -->
    @if(Session('changes_saved'))
    @include('contacts.partials.success_action', ['modal_title' => "Company Identity Updated!", 'modal_content' => session('changes_saved')])
    @endif
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