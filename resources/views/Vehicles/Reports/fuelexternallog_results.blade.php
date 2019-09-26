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
                    <h3 class="box-title">External Fuel Report</h3>
                </div>
				<div class="box-body">
					<div class="box">
						<!-- /.box-header -->
						<div class="box-body">
							<div style="overflow-X:auto;">
								<form class="form-horizontal" method="POST" action="/fleet/reports/extOil/print">
									<input type="hidden" name="vehicle_id" value="{{!empty($vehicle_id) ? $vehicle_id : 0}}">
									<input type="hidden" name="vehicle_type" value="{{!empty($vehicle_type) ? $vehicle_type : ''}}">
									<input type="hidden" name="driver_id" value="{{!empty($driver_id) ? $driver_id : ''}}">
									<input type="hidden" name="action_date" value="{{!empty($action_date) ? $action_date : ''}}">               
									<table id="example2" class="table table-bordered table-hover">
										<thead>
											<tr>
												<th>Fleet Number</th>
												<th>Supplier</th>
												<th>Travelled km</th>
												<th>Travelled Hr</th>
												<th>Litres</th>
												<th>Avg Cons (Odo)</th>
												<th>Avg Cons (Hrs)</th>
												<th>Avg price per Litre </th>
												<th>Amount </th>
											</tr>
										</thead>
										<tbody>
											<?php $prevVehicleID = 0; ?>
											@if (count($externalFuelLog) > 0)
												@foreach ($externalFuelLog as $externallog)
													@if(($prevVehicleID > 0) && $prevVehicleID != $externallog->vehicleID)
														<tr>
															<td class="success" style="text-align: right;">
																<i>&nbsp;</i>
															</td>
															<td class="success" style="text-align: right;">
																<i>SubTotals</i>
															</td>
															<td class="success" style="text-align: center;"> <i>{{ !empty($externallog->total_kms) ? number_format($externallog->total_kms, 2): 0 }} kms</i></td> 
															<td class="success" style="text-align: center;"> <i>{{ !empty($externallog->total_hours) ? number_format($externallog->total_hours, 2): 0 }} hrs</i></td> 
															<td class="success" style="text-align: center;"> <i>{{ !empty($externallog->total_litres) ? number_format($externallog->total_litres, 2): 0 }} l</i></td>
															<td class="success" style="text-align: center;"> <i>{{ !empty($externallog->total_kms) && (!empty( $externallog->total_litres)) ? number_format($externallog->total_kms/$externallog->total_litres, 2): 0 }} kms</i></td> 
															<td class="success" style="text-align: center;"> <i>{{ !empty($externallog->total_hours) && (!empty( $externallog->total_litres)) ? number_format($externallog->total_hours/$externallog->total_litres, 2): 0 }} hrs</i></td> 
															<td class="success" style="text-align: center;"> <i>{{ !empty($externallog->total_costs) && (!empty( $externallog->total_litres)) ? number_format($externallog->total_costs/$externallog->total_litres, 2): 0 }} l</i></td>  
															<td class="success" style="text-align: center;">R <i>{{ !empty($externallog->total_costs) ? number_format($externallog->total_costs, 2) : 0 }}</i></td>
														</tr>
													@endif
													<tr>
														<td>{{ (!empty( $externallog->fleet_number)) ?  $externallog->fleet_number : ''}} </td>                                    
														<td>{{ (!empty( $externallog->supplier)) ?  $externallog->supplier : ''}} </td> 
														<td>{{ (!empty( $externallog->km_travelled)) ?  number_format($externallog->km_travelled, 2): 0}}  Km</td> 
														<td>{{ (!empty( $externallog->hr_travelled)) ?  number_format($externallog->hr_travelled, 2): 0}} Hrs</td> 
														<td style="text-align: center">{{ !empty($externallog->litres_new) ? number_format($externallog->litres_new, 2) : 0 }} l</td>
														<td>{{ (!empty($externallog->km_travelled) && (!empty( $externallog->litres_new))) ?  number_format($externallog->km_travelled/$externallog->litres_new, 2) : 0}} </td>
														<td>{{ (!empty($externallog->hr_travelled) && (!empty( $externallog->litres_new))) ?  number_format($externallog->hr_travelled/$externallog->litres_new, 2) : 0}} </td>
														<td> R {{ (!empty( $externallog->litres_new) && !empty( $externallog->total_cost)) ?  number_format($externallog->total_cost/$externallog->litres_new, 2) : 0}} </td>
														<td style="text-align: center"> R {{ !empty($externallog->total_cost) ? number_format($externallog->total_cost, 2) : 0 }}</td>
													</tr>
													@if($loop->last)
														<tr>
															<td class="success" style="text-align: right;">
																<i>&nbsp;</i>
															</td>
															<td class="success" style="text-align: right;">
																<i>SubTotals</i>
															</td>
															<td class="success" style="text-align: center;"> <i>{{ !empty($externallog->total_kms) ? number_format($externallog->total_kms, 2): 0 }} kms</i></td> 
															<td class="success" style="text-align: center;"> <i>{{ !empty($externallog->total_hours) ? number_format($externallog->total_hours, 2): 0 }} hrs</i></td> 
															<td class="success" style="text-align: center;"> <i>{{ !empty($externallog->total_litres) ? number_format($externallog->total_litres, 2): 0 }} l</i></td>
															<td class="success" style="text-align: center;"> <i>{{ !empty($externallog->total_kms) && (!empty( $externallog->total_litres)) ? number_format($externallog->total_kms/$externallog->total_litres, 2): 0 }} kms</i></td> 
															<td class="success" style="text-align: center;"> <i>{{ !empty($externallog->total_hours) && (!empty( $externallog->total_litres)) ? number_format($externallog->total_hours/$externallog->total_litres, 2): 0 }} hrs</i></td> 
															<td class="success" style="text-align: center;"> <i>{{ !empty($externallog->total_costs) && (!empty( $externallog->total_litres)) ? number_format($externallog->total_costs/$externallog->total_litres, 2): 0 }} l</i></td>  
															<td class="success" style="text-align: center;">R <i>{{ !empty($externallog->total_costs) ? number_format($externallog->total_costs, 2) : 0 }}</i></td>
														</tr>
													@endif
													<?php $prevVehicleID = $externallog->vehicleID; ?>
												@endforeach
											@endif
										</tbody>
										<tfoot>
											<tr>
												<th>Fleet Number</th>
												<th>Fleet Number</th>
												<th>Supplier</th>
												<th>Travelled km</th>
												<th>Travelled Hr</th>
												<th>Litres</th>
												<th>Avg Cons (Odo)</th>
												<th>Avg Cons (Hrs)</th>
												<th>Avg price per Litre </th>
												<th>Amount </th>
											</tr>
											<tr>
												<th colspan="2" style="text-align:right;"> Totals</th> 
												<th> kms </th>  
												<th> Hrs </th>  
												<th> Litres </th>
												<th> Avg Km/l</th>
												<th> Avg hr/l</th>
												<th> Avg Price</th>
												<th> Amount </th>
											</tr>
											<tr>
												<td colspan="2" style="text-align:right;"></td> 
													<td>{{ !empty($totalKms) ? number_format($totalKms, 2) : 0 }} kms</td> 
													<td>{{ !empty($totalHours) ? number_format($totalHours, 2) : 0 }} hrs</td> 
													<td>{{ !empty($totalLitres) ? number_format($totalLitres, 2) : 0 }} l</td> 
													<td>{{ !empty($totalAvgKms) ? number_format($totalAvgKms, 2) : 0 }} Km/l</td> 
													<td>{{ !empty($totalAvgHrs) ? number_format($totalAvgHrs, 2) : 0 }} hr/l</td> 
													<td>R {{ !empty($totalAvgCost) ? number_format($totalAvgCost, 2) : 0 }}</td> 
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
						title: 'External Fuel Report'
					},
					{
						extend: 'csvHtml5',
						title: 'External Fuel Report'
					},
					{
						extend: 'copyHtml5',
						title: 'External Fuel Report'
					}
				]
			});
		});
	</script>
@endsection