@extends('layouts.main_layout')
@section('page_dependencies')

    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!--  -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css"
          rel="stylesheet">

@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                    <h3 class="box-title">Booking(s) Search Results</h3>
                </div>
					<div class="box-body">
						<div style="overflow-X:auto;">
							<table id="example2" class="table table-bordered table-hover">
								<thead>
								<tr>
									<th style="width: 10px; text-align: center;"></th>
									<th>Vehicle</th>
									<th>Fleet Number</th>
									<th>Vehicle Registration</th>
									<th>Booking Type</th>
									<th>Booking Date</th>
									<th>Required From</th>
									<th>Return By</th>
									<th>Capturer</th>
									<th>Driver</th>
									<th>Status</th>
								</tr>
								</thead>
								<tbody>
								@if (count($bookings) > 0)
								@endif
								<ul class="products-list product-list-in-box">
									@foreach ($bookings as $booking)
										<tr>
											<td nowrap>
                                                <a href="{{ '/vehicle_management/view_booking/' . $booking->id }}" 
                                                       class="btn btn-success  btn-xs" target="_blank"><i class="fa fa-handshake-o"></i> View</a>
                                            </td>
											<td>{{ !empty($booking->vehicleMake . ' ' .  $booking->vehicleModel . ' ' . $booking->vehicleType . ' ' . $booking->year  ) ? $booking->vehicleMake . ' ' .  $booking->vehicleModel . ' ' . $booking->vehicleType . ' ' . $booking->year : ''}}</td>
											<td>{{ !empty($booking->fleet_number) ? $booking->fleet_number : ''}}</td>
											<td>{{ !empty($booking->vehicle_reg) ? $booking->vehicle_reg : ''}}</td>
											<td>{{ !empty($booking->usage_type) ? $usageTypes[$booking->usage_type] : ''}}</td>
											<td>{{ !empty($booking->booking_date) ?  date("F j, Y, g:i a", $booking->booking_date)  : ''}}</td>
											<td>{{ !empty($booking->require_datetime ) ?  date("F j, Y, g:i a", $booking->require_datetime)  : ''}}</td>
											<td>{{ !empty($booking->return_datetime ) ? date("F j, Y, g:i a", $booking->return_datetime) : ''}}</td>
											<td>{{ !empty($booking->capturer_id) ? $booking->capturer_id : ''}}</td>
											<td>{{ !empty($booking->firstname . ' ' . $booking->surname ) ? $booking->firstname . ' ' . $booking->surname : ''}}</td>
											<td>{{ !empty($booking->status) ? $bookingStatuses[$booking->status] : ''}}</td>
										</tr>
							@endforeach
							</tbody>
							<tfoot>
							<tr>
								<th style="width: 10px; text-align: center;"></th>
								<th>Vehicle</th>
								<th>Fleet Number</th>
								<th>Vehicle Registration</th>
								<th>Booking Type</th>
								<th>Booking Date</th>
								<th>Required From</th>
								<th>Return By</th>
								<th>Capturer</th>
								<th>Driver</th>
								<th>Status</th>
							</tr>
							</tfoot>
						</table>
						<!-- /.box-body -->
						<div class="box-footer">
						   <!--  <button type="button" id="cancel" class="btn btn-default pull-right"> Create a Request </button> -->
						  <button type="button" id="cancel" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i> Back  </button>
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
	<script src="/custom_components/js/modal_ajax_submit.js"></script>
	<!-- time picker -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
	<!-- Select2 -->
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<!-- End Bootstrap File input -->

	<script>
		//Cancel button click event
		document.getElementById("cancel").onclick = function () {
			location.href = "/vehicle_management/bookings_search";
		};
		$(function () {
			$('#example2').DataTable({
				"paging": true,
				"lengthChange": true,
				"searching": true,
				"ordering": true,
				"info": true,
				"autoWidth": true
			});
		});
	</script>
@endsection