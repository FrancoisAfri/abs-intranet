<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Fuel Report Printed By {{ $user->person->first_name.' '. $user->person->surname }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="/bower_components/AdminLTE/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
 -->
  <link rel="stylesheet" href="/bower_components/AdminLTE/dist/css/AdminLTE.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <style type="text/css" media="print">
  @page { size: landscape; }
</style>
</head>
<body onload="window.print();">
<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          <img width="196" height="60" src="{{ $company_logo }}" alt="logo">
          <small class="pull-right">Date: {{$date}}</small>
        </h2>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      <div class="col-sm-8 invoice-col">
        <address>
          <strong>{{ $company_name }}</strong><br>
        </address>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
    <div class="row">
		<!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
		<div class="box-body">
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
		</div>
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>