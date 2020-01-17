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
                    <h3 class="box-title">Expiring Document Report</h3>
                </div>
	<div class="box-body">
		<div class="box">
			<!-- /.box-header -->
			<div class="box-body">
				<div style="overflow-X:auto;">
				<form class="form-horizontal" method="POST" action="/reports/contact_docs_print">
					 <input type="hidden" name="doc_type" value="{{ !empty($doc_type) ? $doc_type : ''  }}">
					 <input type="hidden" name="contact_id" value="{{ !empty($contact_id) ? $contact_id : ''  }}">
                        <input type="hidden" name="company_id" value="{{ !empty($company_id) ? $company_id : ''  }}">
                        <input type="hidden" name="date_from" value="{{ !empty($Datefrom) ? $Datefrom : ''  }}">
                        <input type="hidden" name="date_to" value="{{ !empty($Dateto) ? $Dateto : ''  }}">				
					<table id="example2" class="table table-bordered table-hover">
						<thead>
						<tr>
							<th>Document Type</th>
							<th>Company Name</th>
							<th>Document Name</th>
							<th>Document Description</th>
							<th>Start Date</th>
							<th>Expiring Date</th>
						</tr>
						</thead>
						<tbody>
						@if (count($companyDocs) > 0)
							@foreach($companyDocs as $companyDoc)
							   <tr>
									<td>{{ (!empty($companyDoc->doc_name)) ?  $companyDoc->doc_name : ''}} </td>
									<td>{{ (!empty($companyDoc->companyname)) ?  $companyDoc->companyname : ''}} </td>
									<td>{{ !empty($companyDoc->name) ?  $companyDoc->name : '' }}</td>
									<td>{{ !empty($companyDoc->description) ? $companyDoc->description : '' }}</td>
									<td>{{ !empty($companyDoc->date_from) ? date('d M Y ', $companyDoc->date_from) : '' }}</td>
									<td>{{ !empty($companyDoc->expirydate) ? date('d M Y ', $companyDoc->expirydate) : '' }}</td> 
								</tr>
							@endforeach
						@endif
						</tbody>
						<tfoot>
						<tr>
							<th>Document Type</th>
							<th>Company Name</th>
							<th>Document Name</th>
							<th>Document Description</th>
							<th>Start Date</th>
							<th>Expiring Date</th>
						</tr>
						</tfoot>
					</table>
					<hr class="hr-text" data-content="Client's Documents">
					<table id="example3" class="table table-bordered table-hover">
						<thead>
							<tr>
								<th>Client Name</th>
								<th>Document Name</th>
								<th>Document Description</th>
								<th>Start Date</th>
								<th>Expiring Date</th>
							</tr>
						</thead>
						<tbody>
							@if (count($contactsDocs) > 0)
								@foreach($contactsDocs as $contactsDoc)
								   <tr>
										<td>{{ (!empty($contactsDoc->first_name)) ?  $contactsDoc->first_name." ".$contactsDoc->surname : ''}} </td>
										<td>{{ !empty($contactsDoc->document_name) ?  $contactsDoc->document_name : '' }}</td>
										<td>{{ !empty($contactsDoc->description) ? $contactsDoc->description : '' }}</td>
										<td>{{ !empty($contactsDoc->date_from) ? date('d M Y ', $contactsDoc->date_from) : '' }}</td>
										<td>{{ !empty($contactsDoc->expirydate) ? date('d M Y ', $contactsDoc->expirydate) : '' }}</td> 
									</tr>
								@endforeach
							@endif
						</tbody>
						<tfoot>
							<tr>
								<th>Client Name</th>
								<th>Document Name</th>
								<th>Document Description</th>
								<th>Start Date</th>
								<th>Expiring Date</th>
							</tr>
						</tfoot>
					</table>
			<div class="box-footer">
				<div class="row no-print">
					<button type="button" id="cancel" class="btn btn-default pull-left"><i
								class="fa fa-arrow-left"></i> Back to Search Page
					</button>
						<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print report</button>
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
	<script src="/bower_components/AdminLTE/plugins/datatables/buttons.print.min.js"></script><!-- End Bootstrap File input -->
    <script>
		document.getElementById("cancel").onclick = function () {
			location.href = '/contacts/Clients-reports';
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
					title: 'Company Documents Expiring Report'
					},
					{
						extend: 'csvHtml5',
						title: 'Company Documents Expiring Report'
					},
					{
						extend: 'copyHtml5',
						title: 'Company Documents Expiring Report'
					}
				]
				});
				
				$('#example3').DataTable({
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
					title: 'Client Documents Expiring Report'
					},
					{
						extend: 'csvHtml5',
						title: 'Client Documents Expiring Report'
					},
					{
						extend: 'copyHtml5',
						title: 'Client Documents Expiring Report'
					}
				]
				});
			});
	</script>
@endsection