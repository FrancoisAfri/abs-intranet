@extends('layouts.main_layout')
@section('page_dependencies')
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/buttons.dataTables.min.css">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12 col-md-offset-0">
            <!-- Horizontal Form -->
			<form class="form-horizontal">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">Report Search Results</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                <div class="box-body">
				<div class="box">
            <!-- /.box-header -->
            <div class="box-body">
				<div style="overflow-X:auto;">
					<table id="example2" class="table table-bordered table-hover">
					<thead>
					<tr>
					  <th>Date</th>
					  <th>Office</th>
					  <th>Company</th>
					  <th>Traveller</th>
					  <th>Supplier</th>
					  <th>Type</th>
					  <th>Employee</th>
					  <th>Summary</th>
					  <th>Pending Reason</th>
					  <th>Corrective Measure</th>
					  <th>Type Of Complaint/Compliment</th>
					  <th>Error Type</th>
					  <th>Responsible Party</th>
					  <th>Status</th>
					</tr>
					</thead>
					<tbody>
					@if (count($complaints) > 0)
						@foreach($complaints as $complaint)
						<tr>
						  <td>{{ !empty($complaint->date_complaint_compliment) ? date('d M Y ', $complaint->date_complaint_compliment) : '' }}</td>
						  <td>{{ !empty($complaint->office) ? $complaint->office : '' }}</td>
						  <td>{{ !empty($complaint->company) ? $complaint->company->name : '' }}</td>
						  <td>{{!empty($complaint->client->first_name) && !empty($complaint->client->surname) ? $complaint->client->first_name." ". $complaint->client->surname : ''}}</td>
						  <td>{{!empty($complaint->supplier) ? $complaint->supplier : ''}}</td>
						  <td>{{ ($complaint->type == 1) ? 'Complaint' : 'Compliment' }}</td>
						  <td>{{$complaint->employees->first_name." ".$complaint->employees->surname}}</td>
						  <td>{{ !empty($complaint->summary_complaint_compliment) ? $complaint->summary_complaint_compliment : '' }}</td>
						  <td>{{ !empty($complaint->pending_reason) ? $complaint->pending_reason : '' }}</td>
						  <td>{{ !empty($complaint->summary_corrective_measure) ? $complaint->summary_corrective_measure : '' }}</td>
						  <td>{{ !empty($complaint->type_complaint_compliment) ? $typeComplaints[$complaint->type_complaint_compliment] : '' }}</td>
						  <td>{{ !empty($complaint->error_type) ? $complaint->error_type : '' }}</td>
						  <td>{{ !empty($complaint->type_complaint_compliment) ? $reponsible[$complaint->type_complaint_compliment] : '' }}</td>
						  <td>{{ !empty($complaint->status) ? $statuses[$complaint->status] : '' }}</td>
						  </tr>
						@endforeach
					@endif
					</tbody>
					<tfoot>
					<tr>
					  <th>Date</th>
					  <th>Office</th>
					  <th>Company</th>
					  <th>Traveller</th>
					  <th>Supplier</th>
					  <th>Type</th>
					  <th>Employee</th>
					  <th>Summary</th>
					  <th>Pending Reason</th>
					  <th>Corrective Measure</th>
					  <th>Type Of Complaint/Compliment</th>
					  <th>Error Type</th>
					  <th>Responsible Party</th>
					  <th>Status</th>
					</tr>
					</tfoot>
				  </table>
				</div>
            </div>
            <!-- /.box-body -->
          </div>
                     </div>   
                    <!-- /.box-body -->
                    <div class="box-footer">
						<button type="button" id="cancel" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i> Cancel</button>
                    </div>
                    <!-- /.box-footer -->
            </div>
			</form>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
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

    <script type="text/javascript">
	//Cancel button click event
	document.getElementById("cancel").onclick = function () {
		location.href = "/complaints/reports";
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
					title: 'Complaints & Compliments Report'
				},
				{
					extend: 'csvHtml5',
					title: 'Complaints & Compliments Report'
				},
				{
					extend: 'copyHtml5',
					title: 'Complaints & Compliments Report'
				}/*,
				{
					extend: 'pdfHtml5',
					title: 'Complaints & Compliments Report'
				}*/
			]
		});
	});
    </script>
@endsection