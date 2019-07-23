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
                    <h3 class="box-title">Bookings Report</h3>
                </div>
				<!-- /.box-header -->
				<div class="box-body">
					<div style="overflow-X:auto;">
						<form class="form-horizontal" method="POST" action="/fleet/reports/booking/print">
							<input type="hidden" name="vehicle_id" value="{{!empty($vehicle_id) ? $vehicle_id : 0}}">
							<input type="hidden" name="purpose" value="{{!empty($purpose) ? $purpose : ''}}">
							<input type="hidden" name="destination" value="{{!empty($destination) ? $destination : ''}}">
							<input type="hidden" name="vehicle_type" value="{{!empty($vehicle_type) ? $vehicle_type : ''}}">
							<input type="hidden" name="driver_id" value="{{!empty($driver_id) ? $driver_id : ''}}">
							<input type="hidden" name="action_date" value="{{!empty($action_date) ? $action_date : ''}}">				
							<table id="example2" class="table table-bordered table-hover">
								<thead>
									<tr>
										<th>Vehicle</th>
										<th>Date Collected</th>
										<th>Date Returned</th>
										<th>Approved By	</th>
										<th>Driver </th>
										<th>Purpose</th>
										<th>Destination</th>
										<th>Starting Kms</th>
										<th>Ending Kms</th>
										<th>Kms Travelled</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									@if (count($vehiclebookings) > 0)
										@foreach ($vehiclebookings as $booking)
											<tr>
												<td>{{ (!empty($booking->vehicle_make) ) ? $booking->vehicle_make." ".$booking->vehicle_model." ".$booking->vehicle_type." ".$booking->v_registration : ''}}</td>
												<td>{{ (!empty($booking->collect_timestamp)) ? date('Y M d', $booking->collect_timestamp) : ''}} </td>
												<td>{{ (!empty($booking->return_timestamp)) ? date('Y M d', $booking->return_timestamp) : ''}} </td>
												<td>{{ (!empty($booking->driver_name)&& !empty($booking->driver_surname)) ? $booking->driver_name." ".$booking->driver_surname: ''}} </td>
												<td>{{ (!empty($booking->apr_firstname)&& !empty($booking->apr_surname)) ? $booking->apr_firstname." ".$booking->apr_surname: ''}} </td>
												<td>{{ (!empty($booking->purpose)) ?  $booking->purpose : ''}} </td>
												<td>{{ (!empty($booking->destination)) ?  $booking->destination : ''}} </td>
												<td style="text-align: center">{{ (!empty( $booking->start_mileage_id)) ?  $booking->start_mileage_id : ''}} </td>
												<td style="text-align: center">{{ (!empty( $booking->end_mileage_id)) ?  $booking->end_mileage_id : ''}} </td>
												<td style="text-align: center">{{ (!empty( $booking->end_mileage_id) && !empty($booking->start_mileage_id)) ?  $booking->end_mileage_id - $booking->start_mileage_id : ''}} </td>
												 <td>{{ !empty($booking->status) ? $bookingStatus[$booking->status] : ''}}</td>
											</tr>
										@endforeach
									@endif
								</tbody>
								<tfoot>
									<tr>
										<th>Vehicle</th>
										<th>Date Collected</th>
										<th>Date Returned</th>
										<th>Approved By	Driver</th>
										<th>Driver </th>
										<th>Purpose</th>
										<th>Destination</th>
										<th>Starting Kms</th>
										<th>Ending Kms</th>
										<th>Kms Travelled</th>
										<th>Status</th>
									</tr>
								</tfoot>
							</table>
							<div class="box-footer">
								<div class="row no-print">
									<button type="button" id="cancel" class="btn btn-default pull-right"><i
												class="fa fa-arrow-left"></i> Back
									</button>
									<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print Report</button>
								</div>
							</div>
						</form>
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
		document.getElementById("cancel").onclick = function () {
			location.href = "/vehicle_management/vehicle_reports";
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