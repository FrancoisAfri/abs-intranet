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
                    <h3 class="box-title">Communication Report</h3>
                </div>
	<div class="box-body">
		<div class="box">
			<!-- /.box-header -->
			<div class="box-body">
				<div style="overflow-X:auto;">
				<form class="form-horizontal" method="POST" action="/reports/contact_com_print">
					 <input type="hidden" name="contact_id" value="{{ !empty($personID) ? $personID : ''  }}">
                        <input type="hidden" name="company_id" value="{{ !empty($companyID) ? $companyID : ''  }}">
                        <input type="hidden" name="date_from" value="{{ !empty($Datefrom) ? $Datefrom : ''  }}">
                        <input type="hidden" name="date_to" value="{{ !empty($Dateto) ? $Dateto : ''  }}">				
					<table id="example2" class="table table-bordered table-hover">
						<thead>
							<tr>
								<th>Company Name</th>
								<th>Contact person</th>
								<th>Communication Date</th>
								<th>Communication Time</th>
								<th>Communication Type</th>
								<th>Message</th>
								<th>Sent By</th>
							</tr>
						</thead>
						<tbody>
						@if (count($contactsCommunications) > 0)
							@foreach($contactsCommunications as $contactsCommunication)
							   <tr>
									<td>{{ (!empty($contactsCommunication->companyname)) ?  $contactsCommunication->companyname : ''}} </td>
									<td>{{ !empty($contactsCommunication->first_name) && !empty($contactsCommunication->surname) ?  $contactsCommunication->first_name." ".$contactsCommunication->surname : '' }}</td>
									<td>{{ !empty($contactsCommunication->communication_date) ? date('d M Y ', $contactsCommunication->communication_date) : '' }}</td>
									<td>{{ !empty($contactsCommunication->time_sent) ? $contactsCommunication->time_sent : '' }}</td>
									<td>{{ (!empty($contactsCommunication->communication_type)) ?  $communicationStatus[$contactsCommunication->communication_type] : ''}} </td>
									<td>{{ (!empty($contactsCommunication->message)) ?  $contactsCommunication->message : ''}} </td> 
									<td>{{ (!empty($contactsCommunication->hr_firstname) && !empty($contactsCommunication->hr_surname)) ?  $contactsCommunication->hr_firstname." ".$contactsCommunication->hr_surname : ''}} </td> 
								</tr>
							@endforeach
						@endif
						</tbody>
						<tfoot>
						<tr>
							 <th>Company Name</th>
							<th>Contact person</th>
							<th>Communication Date</th>
							<th>Communication Time</th>
							<th>Communication Type</th>
							<th>Message</th>
							<th>Sent By</th>
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
	<script src="/bower_components/AdminLTE/plugins/datatables/buttons.print.min.js"></script>
		<!-- End Bootstrap File input -->
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
						title: 'Communication Report'
					},
					{
						extend: 'csvHtml5',
						title: 'Communication Report'
					},
					{
						extend: 'copyHtml5',
						title: 'Communication Report'
					}
				]
			});
		});
		</script>
@endsection