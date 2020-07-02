<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>External Fuel Report Printed By {{ $user->person->first_name.' '. $user->person->surname }}</title>
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
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>