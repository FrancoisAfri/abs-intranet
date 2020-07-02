@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <!-- Include Date Range Picker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Booking Details</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
				<div align="center" class="box box-default">
                    <div class="box-body">
                        <table class="table table-striped table-bordered">	
							<tr>
								<td class="caption">Vehicle Type</td>
								<td>{{ (!empty($vehicleTypes->name)) ?  $vehicleTypes->name : ''}}</td>
								<td class="caption">Vehicle Model</td>
								<td>{{ (!empty($vehiclemodeler->name)) ?  $vehiclemodeler->name : ''}}</td>
							</tr>
							<tr>
								<td class="caption">Make</td>
								<td>{{ !empty($vehiclemaintenance->vehiclemake) ? $vehiclemaintenance->vehiclemake : ''}}</td>
								<td class="caption">Vehicle Reg. No</td>
								<td>{{ (!empty($vehicle->vehicle_registration)) ?  $vehicle->vehicle_registration : ''}}</td>
							</tr>
							<tr>
								<td class="caption">Year</td>
								<td>{{ (!empty($maintenance->year)) ?  $maintenance->year : ''}}</td>
								<td class="caption">Color</td>
								<td>{{ (!empty($maintenance->vehicle_color)) ?  $maintenance->vehicle_color : ''}}</td>
							</tr>
							<tr>
								<td class="caption">Fleet Number</td>
								<td>{{ !empty($booking->fleet_number)?  $booking->fleet_number: '' }}</td>
								<td class="caption">Color</td>
								<td>{{ !empty($booking->usage_type) ?  $usageTypes[$booking->usage_type] : '' }}</td>
							</tr>
							<tr>
								<td class="caption">Required From</td>
								<td>{{ !empty($booking->require_datetime) ?  date("j F, Y, g:i a", $booking->require_datetime) : '' }}</td>
								<td class="caption">Required To</td>
								<td>{{ !empty($booking->return_datetime) ?  date("j F, Y, g:i a", $booking->return_datetime) : '' }}</td>
							</tr>
							<tr>
								<td class="caption">Driver</td>
								<td>{{ !empty($employee->first_name) && !empty($employee->surname) ?  $employee->first_name. ' ' .$employee->surname : '' }}</td>
								<td class="caption">Capturer</td>
								<td>{{ !empty($booking->capturer_id) ?  $booking->capturer_id : '' }}</td>
							</tr>
							<tr>
								<td class="caption">Purpose</td>
								<td>{{ !empty($booking->purpose)?  $booking->purpose: '' }}</td>
								<td class="caption">Destination</td>
								<td>{{ !empty($booking->destination)?  $booking->destination: '' }}</td>
							</tr>
							<tr>
								@if (isset($vehicle) && $vehicle->metre_reading_type === 1)
									<td class="caption">Odometer Reading</td>
									<td>{{ (!empty($vehicle->odometer_reading)) ? $vehicle->odometer_reading : ''}} kms</td>
								@else
									<td class="caption">Hours Reading</td>
									<td>{{ (!empty($vehicle->hours_reading)) ? $vehicle->hours_reading : ''}} Hrs</td>
								@endif	
								<td class="caption">Booking Status</td>
								<td>{{ !empty($booking->status) ? $bookingStatuses[$booking->status] : ''}}</td>
							</tr>
							<tr>
								<td class="caption">km Out</td>
								<td>{{ !empty($booking->start_mileage_id) ? $booking->start_mileage_id : ''}}</td>
								<td class="caption">km In</td>
								<td>{{ !empty($booking->end_mileage_id) ? $booking->end_mileage_id : ''}}</td>
							</tr>
							<tr>
								<td class="caption">Total km Travelled</td>
								<td>{{ !empty($booking->end_mileage_id && !empty($booking->start_mileage_id) ) ? $booking->end_mileage_id - $booking->start_mileage_id  : ''}}</td>
								<td class="caption">Inspection</td>
								<td><a href="{{ '/vehicle_management/vehicle_ispection/' . $booking->id }}" 
                                    class="btn btn-primary  btn-xs" target="_blank"><i class="fa fa-hand-lizard-o"></i> Docs/Images</a></td>
							</tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page_script')
<!-- the main fileinput plugin file -->
<!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
<script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
<script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js" type="text/javascript"></script>

@endsection