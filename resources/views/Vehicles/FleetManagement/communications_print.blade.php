<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Vehicle Communications Printed By {{ $user->person->first_name.' '. $user->person->surname }}</title>
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
			<p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
				<strong class="lead">Vehicle Details</strong><br>

				@if(!empty($vehiclemaker))
					| &nbsp; &nbsp; <strong>Vehicle Make:</strong> <em>{{ $vehiclemaker->name }}</em> &nbsp;
					&nbsp;
				@endif
				@if(!empty($vehiclemodeler))
					-| &nbsp; &nbsp; <strong>Vehicle Model:</strong> <em>{{ $vehiclemodeler->name }}</em>
					&nbsp; &nbsp;
				@endif
				@if(!empty($vehicleTypes))
					-| &nbsp; &nbsp; <strong>Vehicle Type:</strong> <em>{{ $vehicleTypes->name }}</em> &nbsp;
					&nbsp;
				@endif
				@if(!empty($maintenance->vehicle_registration))
					-| &nbsp; &nbsp; <strong>Vehicle Registration:</strong>
					<em>{{ $maintenance->vehicle_registration }}</em> &nbsp; &nbsp;
				@endif
				@if(!empty($maintenance->year))
					-| &nbsp; &nbsp; <strong>Year:</strong> <em>{{ $maintenance->year }}</em> &nbsp;
					&nbsp;
				@endif
				@if(!empty($maintenance->vehicle_color))
					-| &nbsp; &nbsp; <strong>Vehicle Color:</strong>
					<em>{{ $maintenance->vehicle_color }}</em> &nbsp; &nbsp; -|
				@endif
			</p>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <div class="row">
            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
            <div class="box-body">
				<table id="example2" class="table table-bordered table-hover">
						<thead>
							<tr>
								<th style="width: 150px;">Sent To</th>
								<th style="width: 150px;">Company</th>
								<th style="width: 120px;">Contact person/Employee</th>
								<th style="width: 120px;">Date</th>
								<th style="width: 70px;">Time</th>
								<th style="width: 100px;">Type</th>
								<th style="width: 300px;">Message</th>
								 <th>Sent By</th>
							</tr>						
						</thead>
						<tbody>
							@if (count($communicaions) > 0)
								@foreach($communicaions as $communicaion)
									<tr>
										<td style="width: 50px;">{{ (!empty($communicaion->send_type)) ?  $sendToStatus[$communicaion->send_type] : ''}} </td>
										<td style="width: 150px;">{{ !empty($communicaion->company->name) ?  $communicaion->company->name : '' }}</td>
										@if (!empty($communicaion->send_type) && $communicaion->send_type == 1)
											<td style="width: 120px;">{{ !empty($communicaion->contact->first_name) && !empty($communicaion->contact->surname) ? $communicaion->contact->first_name." ".$communicaion->contact->surname : ''}}</td>
										@elseif (!empty($communicaion->send_type) && $communicaion->send_type == 2)
											<td style="width: 100px;">{{ !empty($communicaion->employees->first_name) && !empty($communicaion->employees->surname) ? $communicaion->employees->first_name." ".$communicaion->employees->surname : ''}}</td>
										@else
											<td style="width: 100px;"></td>
										@endif
										<td style="width: 120px;">{{ !empty($communicaion->communication_date) ? date('d M Y ', $communicaion->communication_date) : '' }}</td>
										<td  style="width: 70px;">{{ !empty($communicaion->time_sent) ? $communicaion->time_sent : '' }}</td>
										<td style="width: 100px;">{{ (!empty($communicaion->communication_type)) ?  $communicationStatus[$communicaion->communication_type] : ''}} </td>
										<td style="width: 300px;">{{ (!empty($communicaion->message)) ?  $communicaion->message : ''}} </td> 
										<td>{{ (!empty($communicaion->sender->first_name) && !empty($communicaion->sender->surname)) ?  $communicaion->sender->first_name." ".$communicaion->sender->surname : ''}} </td> 
									</tr>
								@endforeach
							@endif
						</tbody>
						<tfoot>
							<tr>
								<th style="width: 150px;">Sent To</th>
								<th style="width: 150px;">Company</th>
								<th style="width: 120px;">Contact person/Employee</th>
								<th style="width: 120px;">Date</th>
								<th style="width: 70px;">Time</th>
								<th style="width: 100px;">Type</th>
								<th style="width: 300px;">Message</th>
								 <th>Sent By</th>
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