@extends('layouts.main_layout')
@section('page_dependencies')
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/buttons.dataTables.min.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <form class="form-horizontal" method="POST" action="/reports/contact_note/client_report">
					<input type="hidden" name="hr_person_id" value="{{ !empty($personID) ? $personID : ''  }}">
					<input type="hidden" name="company_id" value="{{ !empty($companyID) ? $companyID : ''  }}">
					<input type="hidden" name="user_id" value="{{ !empty($userID) ? $userID : ''  }}">
					{{ csrf_field() }}
					<div class="box-header with-border">
						 <i class="fa fa-file-text-o pull-right"></i>
						<h3 class="box-title">Notes Report</h3>
					</div>
						<!-- /.box-header -->
					<div class="box-body">
							<div style="overflow-x:auto;">
								<table  id="example2" class="table table-striped table-bordered">
									<thead>
										<tr>
											<th>Client Name</th>
											<th>Notes</th>
											<th>Next Action</th>
											<th>Follow Up Date</th>
										</tr>
									</thead>
									<tbody>
										<!-- loop through the leave application info   -->
										@if(count($notes) > 0)
											@foreach($notes as $notereport)
												<tr>
													<td>{{ !empty($notereport->name) && !empty($notereport->surname) ? $notereport->name.' '.$notereport->surname : '' }}</td>
													<td>{{ !empty($notereport->notes) ? $notereport->notes : '' }}</td>
													<td>{{ (!empty($notereport->next_action)) ?  $notesStatus[$notereport->next_action] : ''}} </td>
													<td>{{ !empty($notereport->follow_date) ? date('d M Y ', $notereport->follow_date) : '' }}</td>
												</tr>
											@endforeach
										@endif  
									</tbody>      
								</table>
							</div>
						<div class="box-footer no-print">
							<button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Back</button>
							<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print Report</button>
						</div>
					</div>
                </form>
			</div>
		</div>
    </div>
@endsection

@section('page_script')
	<script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/dataTables.buttons.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/buttons.flash.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/jszip.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/pdfmake.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/vfs_fonts.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/buttons.html5.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/buttons.print.min.js"></script>
    <script>
       $(function () {
            //Cancel button click event
            $('#back_button').click(function () {
                location.href = '/contacts/Clients-reports';
            });
        });
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
						title: 'Notes Report'
					},
					{
						extend: 'csvHtml5',
						title: 'Notes Report'
					},
					{
						extend: 'copyHtml5',
						title: 'Notes Report'
					}
				]
			});
		});
    </script>
@endsection