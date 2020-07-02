@extends('layouts.main_layout')
@section('page_dependencies')
	<!-- Include Date Range Picker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/buttons.dataTables.min.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                    <h3 class="box-title">Tank Search Resutls </h3>
                </div>
                <div class="box-body">
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div style="overflow-X:auto;">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Transaction Date</th>
                                        <th>Transaction Type</th>
                                        <th>Fleet No.</th>
                                        <th>Reg. No.</th>
                                        <th>Supplier/Employee</th>
                                        <th>Reading before filling</th>
                                        <th>Reading after filling</th>
                                        <th> Litres</th>
                                        <th>Rate Per Litre</th>
                                        <th>Cost</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($tankResults) > 0)
                                        @foreach ($tankResults as $filling)
                                            <tr id="categories-list">
                                                <td>{{ (!empty( $filling->date)) ?   date(' d M Y', $filling->date) : ''}} </td>
                                                <td>{{ (!empty( $filling->type) && $filling->type < 3) ?  $status[$filling->type] : ''}} </td>
                                                <td>{{ (!empty( $filling->fleet_number)) ?  $filling->fleet_number : ''}} </td>
                                                <td>{{ (!empty( $filling->vehicle_registration)) ?  $filling->vehicle_registration : ''}} </td>
                                                <td>{{ (!empty( $filling->Supplier)) ?  $filling->Supplier : ''}} </td>
                                                <td>{{ (!empty($filling->reading_before_filling)) ?  $filling->reading_before_filling : ''}}</td>
                                                <td>{{ (!empty($filling->current_fuel_litres)) ?  number_format($filling->current_fuel_litres, 2) : ''}}</td>
                                                <td>{{ (!empty( $filling->litres_new)) ?  number_format($filling->litres_new, 2) : ''}} </td>
                                                <td>{{ (!empty( $filling->cost_per_litre)) ? 'R'.number_format($filling->cost_per_litre, 2) : ''}} </td>
                                                <td>{{ (!empty( $filling->total_cost)) ? 'R'.number_format($filling->total_cost, 2) : ''}} </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Transaction Date</th>
                                        <th>Transaction Type</th>
                                        <th>Fleet No.</th>
                                        <th>Reg. No.</th>
                                        <th>Supplier/Employee</th>
                                        <th>Reading before filling</th>
                                        <th>Reading after filling</th>
                                        <th> Litres</th>
                                        <th>Rate Per Litre</th>
                                        <th>Cost</th>
                                    </tr>
                                    </tfoot>
                                </table>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                    <h3 class="box-title">Station Search Resutls</h3>
                </div>
                <div class="box-body">
                    <div class="box">
                            <h3 class="box-title"></h3>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div style="overflow-X:auto;">
                                <table id="example" class="table table-bordered table-hover">
                                    <thead>
										<tr>
											<th></th>
											<th style="width: 5px; text-align: center;">Date Taken</th>
											<th>Vehicle Fleet No. </th>
											<th>Vehicle Reg. No.</th>
											<th>Odometer Reading</th>
											<th>Hours Reading</th>
											<th>Captured by</th>
											<th>Service Station</th>
											<th> Litres</th>
											<th>Rate Per Litre</th>
											<th>Cost</th>
											<th style="width: 8px; text-align: center;">Status</th>
										</tr>
                                    </thead>
                                    <tbody>
                                    @if (count($stationResults) > 0)
                                        @foreach ($stationResults as $filling)
                                            <tr id="categories-list">
                                                <td nowrap>
													@if ($filling->iStatus == 4)
														</button>
														<a href="/vehicle-management/fuel-log-edit/{{$filling->fuel_id}}" id="edit_fuel"
														   class="btn btn-warning pull-right" target="_blank"><i class="fa fa-pencil-square-o"></i> Edit</a>
													@endif
                                                </td>
                                                <td>{{ (!empty( $filling->date)) ?   date(' d M Y', $filling->date) : ''}} </td>
                                                <td>{{ (!empty( $filling->fleet_number)) ?  $filling->fleet_number : ''}} </td>
                                                <td>{{ (!empty( $filling->vehicle_registration)) ?  $filling->vehicle_registration : ''}} </td>
                                                <td>{{ (!empty( $filling->odometer_reading)) ?  $filling->odometer_reading : ''}} </td>
                                                <td>{{ (!empty( $filling->Hoursreading)) ?  $filling->Hoursreading : ''}} </td>
                                                <td>{{ (!empty( $filling->firstname . ' ' . $filling->surname )) ?  $filling->firstname . ' ' . $filling->surname  : ''}} </td>
                                                <td>{{ (!empty( $filling->Staion)) ?  $filling->Staion : ''}} </td>
                                                <td>{{ (!empty( $filling->litres_new)) ?  number_format($filling->litres_new, 2) : ''}} </td>
                                                <td>{{ (!empty( $filling->cost_per_litre)) ? 'R'.number_format($filling->cost_per_litre, 2) : ''}} </td>
                                                <td>{{ (!empty( $filling->total_cost)) ? 'R'.number_format($filling->total_cost, 2) : ''}} </td>
                                                <td>{{ (!empty( $filling->iStatus)) ?  $booking[$filling->iStatus] : ''}} </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
																				<tr>
											<th></th>
											<th style="width: 5px; text-align: center;">Date Taken</th>
											<th>Vehicle Fleet No. </th>
											<th>Vehicle Reg. No.</th>
											<th>Odometer Reading</th>
											<th>Hours Reading</th>
											<th>Captured by</th>
											<th>Service Station</th>
											<th> Litres</th>
											<th>Rate Per Litre</th>
											<th>Cost</th>
											<th style="width: 8px; text-align: center;">Status</th>
										</tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<!-- /.box-body -->
				<div class="box-footer">
					<button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
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

        $(function () {
            $('#example').DataTable({
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
		$('#back_button').click(function () {
			location.href = '/vehicle_management/tank_approval';
		});
    </script>
@endsection