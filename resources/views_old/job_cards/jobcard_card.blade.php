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
                    <h3 class="box-title">Job Card Report</h3>
                </div>
                <div class="box-body">
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div style="overflow-X:auto;">
                                <form class="form-horizontal" method="POST" action="/jobcards/printcards">
								 <input type="hidden" name="status" value="{{!empty( $processID) ?  $processID : ''}}">
								 <input type="hidden" name="vehicle_id" value="{{!empty($vehicleID) ? $vehicleID : ''}}">
								 <input type="hidden" name="action_from" value="{{!empty($actionFrom) ? $actionFrom : ''}}">
								 <input type="hidden" name="action_to" value="{{!empty($actionTo) ? $actionTo : ''}}">   
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
											<tr>
												<th style="width: 5px; text-align: center;"> Job Card #</th>
												<th>Vehicle</th>
												<th>Date Created</th>
												<th>Completion Date</th>
												<th>Instructions</th>
												<th>Service Type</th>
												<th>Supplier</th>
												<th>Status</th>
											</tr>
                                        </thead>
                                        <tbody>
											@if (count($jobCards) > 0)
												@foreach ($jobCards as $jobcard)
													<tr id="categories-list">
														<td>{{ !empty($jobcard->jobcard_number) ? $jobcard->jobcard_number : '' }}</td>
														<td>{{ (!empty( $jobcard->fleet_number . ' ' .  $jobcard->vehicle_registration . ' ' . $jobcard->vehicle_make . ' ' . $jobcard->vehicle_model))
												 ?  $jobcard->fleet_number . ' ' .  $jobcard->vehicle_registration . ' ' . $jobcard->vehicle_make . ' ' . $jobcard->vehicle_model : ''}} </td>
														<td>{{ !empty($jobcard->card_date) ? date(' d M Y', $jobcard->card_date) : '' }}</td>
														<td>{{ !empty($jobcard->completion_date ) ? date(' d M Y', $jobcard->completion_date) : 'Nill' }}</td>
														<td>
														@if (count($jobcard->JCinstructions) > 0)
															@foreach ($jobcard->JCinstructions as $instruction)
																{{ !empty($instruction->instruction_details) ? $instruction->instruction_details.";" : '' }}
															@endforeach
														@endif
														 </td>
														<td>{{ !empty($jobcard->servicetype) ? $jobcard->servicetype : '' }}</td>
														<td>{{ !empty($jobcard->Supplier) ? $jobcard->Supplier : '' }}</td>
														<td>{{ !empty($jobcard->aStatus) ? $jobcard->aStatus : '' }}</td>
													</tr>
												@endforeach
											@endif
                                        </tbody>
                                        <tfoot>
											<tr>
												<th style="width: 5px; text-align: center;"> Job Card #</th>
												<th>Vehicle</th>
												<th>Date Created</th>
												<th>Completion Date</th>
												<th>Instructions</th>
												<th>Service Type</th>
												<th>Supplier</th>
												<th>Status</th>
											</tr>
                                        </tfoot>
                                    </table>
                                    <div class="box-footer">
                                        <div class="row no-print">
											<button type="button" id="cancel" class="btn btn-default pull-right"><i
                                                        class="fa fa-arrow-left"></i> Back
                                            </button>
                                            <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print report</button> 
										</div>
                                    </div>
								</form>
                            </div>
                        </div>
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
		function postData(id, data) {
			if (data == 'actdeac') location.href = "/vehicle_management/vehicles_Act/" + id;
		}

		//Cancel button click event
		//Cancel button click event
		document.getElementById("cancel").onclick = function () {
			location.href = "/jobcards/reports";
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
						title: 'Bookings Report'
					},
					{
						extend: 'csvHtml5',
						title: 'Bookings Report'
					},
					{
						extend: 'copyHtml5',
						title: 'Bookings Report'
					}
				]
			});
		});
	</script>
@endsection