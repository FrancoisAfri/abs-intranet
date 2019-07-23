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
                    <h3 class="box-title">Fuel Report</h3>
                </div>
                <div class="box-body">
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div style="overflow-X:auto;">
                            <form class="form-horizontal" method="POST" action="/fleet/reports/fuel/print">
                                <input type="hidden" name="vehicle_id" value="{{!empty($vehicle_id) ? $vehicle_id : 0}}">
                                <input type="hidden" name="vehicle_type" value="{{!empty($vehicle_type) ? $vehicle_type : ''}}">
                                <input type="hidden" name="driver_id" value="{{!empty($driver_id) ? $driver_id : ''}}">
                                <input type="hidden" name="action_date" value="{{!empty($action_date) ? $action_date : ''}}">               
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
										<tr>
											<th>Fleet No.</th>
											<th>Date</th>
											<th>Driver</th>
											<th>Description</th>
											<th>Station</th>
											<th>Travelled km</th>
											<th>Travelled Hr</th>
											<th>Litres</th>
											<th>Cost</th>
											<th>Rate per Litre</th>
										</tr>
									</thead>
									<tbody>
									<?php $prevVehicleID = 0; ?>
									@foreach($fuelLog as $details)
										@if(($prevVehicleID > 0) && $prevVehicleID != $details->vehicleID)
                                            <tr>
                                                <td class="success"></td>
												<td class="success"></td>
												<td class="success"></td>
												<td class="success"></td>
												<td class="success" style="text-align: right;">
                                                    <i>SubTotals</i>
                                                </td>
												<td class="success" style="text-align: center;"> <i>{{ !empty($details->total_kms) ? number_format($details->total_kms, 2): 0 }} kms</i></td> 
												<td class="success" style="text-align: center;"> <i>{{ !empty($details->total_hours) ? number_format($details->total_hours, 2): 0 }} hrs</i></td> 
												<td class="success" style="text-align: center;"> <i>{{ !empty($details->total_litres) ? number_format($details->total_litres, 2): 0 }} l</i></td>  
												<td class="success" style="text-align: center;">R <i>{{ !empty($details->total_costs) ? number_format($details->total_costs, 2) : 0 }}</i></td>
												<td class="success" style="text-align: center;"><i></i></td>
                                            </tr>
                                        @endif
										<tr>
											<td>{{ !empty($details->fleet_number) ?  $details->fleet_number: '' }}</td>
											<td>{{ !empty($details->date) ? date('Y M d', $details->date) : '' }}</td>
											<td>{{ !empty($details->firstname.''.$details->surname) ? $details->firstname.''.$details->surname: '' }}</td>
											<td>{{ !empty($details->description) ?  $details->description: '' }}</td>
											<td>{{ !empty($details->station) ?  $details->station : '' }}</td>
											<td>{{ !empty($details->km_travelled) ?  $details->km_travelled: '' }}</td>
											<td>{{ !empty($details->hr_travelled) ?  $details->hr_travelled: '' }}</td>
											<td>{{ !empty($details->litres_new) ?  $details->litres_new: '' }}</td>
											<td>{{ !empty($details->total_cost) ?  $details->total_cost: '' }}</td>
											<td>{{ !empty($details->cost_per_litre) ?  $details->cost_per_litre: '' }}</td>
										</tr>
										@if($loop->last)
											<tr>
                                                <td class="success"></td>
												<td class="success"></td>
												<td class="success"></td>
												<td class="success"></td>
												<td class="success" style="text-align: right;">
                                                    <i>SubTotals</i>
                                                </td>
												<td class="success" style="text-align: center;"> <i>{{ !empty($details->total_kms) ? number_format($details->total_kms, 2): 0 }} kms</i></td> 
												<td class="success" style="text-align: center;"> <i>{{ !empty($details->total_hours) ? number_format($details->total_hours, 2): 0 }} hrs</i></td> 
												<td class="success" style="text-align: center;"> <i>{{ !empty($details->total_litres) ? number_format($details->total_litres, 2): 0 }} l</i></td>  
												<td class="success" style="text-align: center;">R <i>{{ !empty($details->total_costs) ? number_format($details->total_costs, 2) : 0 }}</i></td>
												<td class="success" style="text-align: center;"><i></i></td>
                                            </tr>
										@endif
										<?php $prevVehicleID = $details->vehicleID; ?>
									@endforeach
									</tbody>
									<tfoot>
										<tr>
											<th>Fleet No.</th>
											<th>Date</th>
											<th>Driver</th>
											<th>Description</th>
											<th>Station</th>
											<th>Travelled km</th>
											<th>Travelled Hr</th>
											<th>Litres</th>
											<th>Cost</th>
											<th>Rate per Litre</th>
										</tr>
										<tr class="caption">
											<th colspan="5" style="text-align:right;">Grand Totals</th> 
											<th>Travelled kms</th>  
											<th>Travelled Hrs</th>  
											<th>Litres</th>
											<th>Cost</th>
											<th></th>
										</tr>
										<tr>
											<td colspan="5" style="text-align:right;"></td> 
											<td>{{ !empty($totalKms) ? number_format($totalKms, 2) : 0 }} kms</td> 
											<td>{{ !empty($totalHours) ? number_format($totalHours, 2) : 0 }} hrs</td> 
											<td>{{ !empty($totalLitres) ? number_format($totalLitres, 2) : 0 }} l</td>  
											<td>R {{ !empty($totalCost) ? number_format($totalCost, 2) : 0 }}</td>
										</tr>
									</tfoot>
                                </table>
                                <div class="box-footer">
                                    <div class="row no-print">
                                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print Report</button>
                                        <button type="button" id="cancel" class="btn btn-default pull-right"><i
                                                    class="fa fa-arrow-left"></i> Back
                                        </button>
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
						title: 'Fuel Report'
					},
					{
						extend: 'csvHtml5',
						title: 'Fuel Report'
					},
					{
						extend: 'copyHtml5',
						title: 'Fuel Report'
					}
					
				]
			});
		});
	</script>
@endsection