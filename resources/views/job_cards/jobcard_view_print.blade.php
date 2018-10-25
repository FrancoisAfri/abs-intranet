<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Job Card Report Printed By {{ $user->person->first_name.' '. $user->person->surname }}</title>
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

        <table class="table table-striped table-bordered">
            <!--                              <h5 class="box-title"> Job Card </h3>-->
        @foreach ($jobcard as $jobcard)
            <!--  -->
                <tr>
                    <td class="caption">Fleet Number</td>
                    <td>{{ !empty($jobcard->fleet_number) ? $jobcard->fleet_number : ''}}</td>
                    <td class="caption">Job Card Number</td>
                    <td>{{ !empty($jobcard->jobcard_number) ? $jobcard->jobcard_number : ''}}</td>
                </tr>
                <tr>
                    <td class="caption">vehicle registration Number</td>
                    <td>{{ !empty($jobcard->vehicle_registration) ? $jobcard->vehicle_registration : ''}}</td>
                    <td class="caption">Job Card Date</td>
                    <td>{{ !empty($jobcard->card_date) ? date(' d M Y', $jobcard->card_date) : ''}}</td>
                </tr>
                <tr>
                    <td class="caption">Driver</td>
                    <td>{{ !empty($jobcard->dr_firstname)  && !empty($jobcard->dr_surname) ? $jobcard->dr_firstname." ".$jobcard->dr_surname : ''}}</td>
					<td class="caption">Mechanic</td>
                    <td>{{ !empty($jobcard->me_firstname)  && !empty($jobcard->me_surname) ? $jobcard->me_firstname." ".$jobcard->me_surname : ''}}</td>
                </tr>
                <tr>
                    <td class="caption">Current Odometer</td>
                    <td>{{ !empty($jobcard->odometer_reading) ? $jobcard->odometer_reading : ''}}</td>
					<td class="caption">Job Card Status</td>
                    <td>{{ !empty($jobcard->aStatus) ? $jobcard->aStatus : ''}}</td>
                </tr>
                <tr>
                    <td class="caption">Current Hours</td>
                    <td>{{ !empty($jobcard->hours_reading) ? $jobcard->hours_reading : ''}}</td>
                    <td class="caption">Inspection List Number</td>
                    <td>{{ !empty($jobcard->cell_number) ? $jobcard->cell_number : ''}}</td>
                </tr>
                <tr>
                    <td class="caption">Hours Allocated</td>
                    <td>{{ !empty($jobcard->hours_reading) ? $jobcard->hours_reading : ''}}</td>
                    <td class="caption">Completion Date</td>
                    <td>{{ !empty($jobcard->completion_date) ? date('d M Y', $jobcard->completion_date) : ''}}</td>
                </tr>
                <tr>
                    <td class="caption">Hours used</td>
                    <td>{{ !empty($jobcard->machine_hour_metre) ? $jobcard->machine_hour_metre : ''}}</td>-->
                    <td></td>
                    <td class="caption">Service Time</td>
                    <td>{{ !empty($jobcard->service_time) ? $jobcard->service_time : ''}}</td>
                </tr>
                <tr>
                    <td class="caption">Service Type</td>
                    <td>{{ !empty($jobcard->servicetype) ? $jobcard->servicetype : ''}}</td>
                    <td class="caption">Servicing Agent</td>
                    <td> N/A</td>
                </tr>
                <tr>
                    <td class="caption">Purchase Order Number</td>
                    <td></td>
                    <td class="caption">Service Date</td>
                    <td></td>
                </tr>
				<tr>
					<td class="caption">Mechanic Comment</td>
					<td>{{ !empty($jobcard->mechanic_comment) ? $jobcard->mechanic_comment : ''}}</td>
					<td class="caption">Completion Comment</td>
					<td>{{ !empty($jobcard->completion_comment) ? $jobcard->completion_comment : ''}}</td>
				</tr>
                <br>
                <tr>
                    <th colspan="4" class="caption">Job Card Instructions</th>
                </tr>
				<tr><td colspan="2">Instructions</td><td>Status</td><td>Completion  Date/Time</td></tr>
				@if (count($instructions) > 0)
					@foreach ($instructions as $instruction)
						<tr>
							<td colspan="2">
								{{ $loop->iteration }}. {{ !empty($instruction->instruction_details) ? $instruction->instruction_details : '' }}
							</td>
							<td>{{ !empty($instruction->status) && $instruction->status == 2 ? 'Completed' : 'Incomplete' }}</td>
							<td>{{ !empty($instruction->completion_date) ? date('d M Y ', $instruction->completion_date) : '' }} - {{ !empty($instruction->completion_time) ? $instruction->completion_time : '' }}</td>
						</tr>
					@endforeach
				@else
					<tr>
						<td colspan="4" class="caption"></td>
					</tr>
				@endif
                <br>
                <tr>
                    <td colspan="2" style="text-align:left;">Completed (Y/N)</td>
                    <td colspan="2" style="text-align:left;"></td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align:left;">Workshop Comments:</td>
                </tr>
                <tr border="1">
                    <td colspan="4" style="text-align:left;" height="170" border="1">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr height="50">
                    <td width="160">Signature - Worshop</td>
                    <td>____________________</td>
                    <td width="160">Signature - Fleet Manager</td>
                    <td>_____________________</td>
                </tr>

        </table>
    @endforeach
</div>
</div>
<!-- /.row -->
</section>
<!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>