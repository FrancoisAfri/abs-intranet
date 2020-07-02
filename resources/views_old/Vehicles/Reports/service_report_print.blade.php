<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Service Report Printed By {{ $user->person->first_name.' '. $user->person->surname }}</title>
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
                    <strong>{{ $company_name }} </strong><br>
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
							<th>Vehicle</th>
							<th>Date Serviced</th>
							<th>Garage</th>
							<th>Description</th>
							<th>Next Service Date</th>
							<th>Next Service Km</th>
							<th>Invoice Number</th>
							<th style="width: 5px; text-align: center;">Cost</th>
						</tr>
                    </thead>
                    <tbody>
                    @if (count($serviceDetails) > 0)
                        @foreach($serviceDetails as $details)
                            <tr>										
								<td>{{ (!empty($details->VehicleMake) ) ? $details->VehicleMake." ".$details->VehicleModel." ".$details->vehicle_registration : ''}}</td>
								<td>{{ !empty($details->date_serviced) ? date('Y M d', $details->date_serviced) : '' }}</td>
								<td>{{ !empty($details->garage) ? $details->garage : '' }}</td>
								<td>{{ !empty($details->description) ? $details->description : '' }}</td>
								<td style="text-align: center">{{ !empty($details->nxt_service_date) ? date('Y M d', $details->nxt_service_date) : '' }}</td>
								<td style="text-align: center">{{ !empty($details->nxt_service_km) ? $details->nxt_service_km : ''}} </td>
								<td style="text-align: center">{{ !empty($details->invoice_number) ?  $details->invoice_number : ''}} </td>
								<td style="text-align: center">{{ !empty($details->total_cost) ? 'R' .number_format($details->total_cost, 2) : ''}} </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                    <tfoot>
						<tr>
							<th>Vehicle</th>
							<th>Date Serviced</th>
							<th>Garage</th>
							<th>Description</th>
							<th>Next Service Date</th>
							<th>Next Service Km</th>
							<th>Invoice Number</th>
							<th style="width: 5px; text-align: center;">Cost</th>
						</tr>
						<tr>
							<th colspan="7" style="text-align: right">Total</th>
							<td style="text-align: right" nowrap>{{ !empty($totalCost) ? 'R' .number_format($totalCost, 2) : '' }}</td>
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