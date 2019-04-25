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
                                <form class="form-horizontal" method="POST" action="/jobcards/reports/printparts">    
                                         <input type="hidden" name="vehicle_id" value="{{!empty($vehicleID) ? $vehicleID : ''}}">
                                         <input type="hidden" name="action_from" value="{{!empty($actionFrom) ? $actionFrom : ''}}">
                                         <input type="hidden" name="action_to" value="{{!empty($actionTo) ? $actionTo : ''}}">
                                         <input type="hidden" name="category_id" value="{{!empty($categoryID) ? $categoryID : ''}}">
                                         <input type="hidden" name="product_id" value="{{!empty($productID) ? $productID : ''}}">
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
											<tr>
												<th>Vehicle</th>
												<th style="text-align: center;"> Job Card #</th>
												<th>Job Card Date</th>
												<th>Service Type</th>
												<th>Part</th>
												<th style="text-align: center;">Number Used</th>
											</tr>
                                        </thead>
                                        <tbody>
											@if (count($parts) > 0)
												@foreach ($parts as $part)
													<tr>
														<td>{{ !empty($part->fleet_no . '' . $part->vehicleregistration) ? $part->fleet_no. '-' . $part->vehicleregistration : '' }}</td>
														<td style="text-align: center;">{{ !empty($part->jobcard_number) ? $part->jobcard_number : '' }}</td>
														<td>{{ !empty($part->date_created) ? date(' d M Y', $part->date_created) : '' }}</td>
														<td>{{ !empty($part->servicetype) ? $part->servicetype : '' }}</td>
														<td>{{ !empty($part->product_name) ? $part->product_name : '' }}</td>
														<td style="text-align: center;">{{ !empty($part->no_of_parts_used) ? $part->no_of_parts_used : 0 }}</td>
													</tr>
												@endforeach
											@endif
                                        </tbody>
                                        <tfoot>
											<tr>
												<th style="text-align: center;"> Job Card #</th>
												<th>Vehicle</th>
												<th>Job Card Date</th>
												<th>Service Type</th>
												<th>Part</th>
												<th>Number Used</th>
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