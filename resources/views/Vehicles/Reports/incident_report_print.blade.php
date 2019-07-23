<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Incidents Report Printed By {{ $user->person->first_name.' '. $user->person->surname }}</title>
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
                        <th>Date Reported</th>
                        <th>Reported By</th>
                        <th>Odometer Reading Km</th>
                        <th>Incident Type</th>
                        <th>Severity</th>
                        <th>Cost</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($vehicleincidents) > 0)
                        @foreach($vehicleincidents as $details)
                            <tr>
                                <td>{{ (!empty($details->VehicleMake) ) ? $details->VehicleMake." ".$details->VehicleModel." ".$details->vehicle_registration : ''}}</td>
                                <td>{{ !empty($details->date_of_incident) ? date('Y M d', $details->date_of_incident) : '' }}</td>
                                <td>{{ !empty($details->firstname.''.$details->surname) ?  $details->firstname.''.$details->surname: '' }}</td>
                                <td>{{ !empty($details->odometer_reading) ?  $details->odometer_reading: '' }}</td>
                                <td>{{ !empty($details->IncidentType) ?  $details->IncidentType: 'Nill' }}</td>
                                <td>{{ (!empty($details->severity)) ?  $severity[$details->severity] : 'Nill'}}</td>
                                <td>{{ !empty($details->Cost) ? 'R' .number_format($details->Cost, 2) : '' }}</td>
                                <td>{{ !empty($details->status) ?  $status[$details->status] : ''}}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Vehicle</th>
                        <th>Date Reported</th>
                        <th>Reported By</th>
                        <th>Odometer Reading Km</th>
                        <th>Incident Type</th>
                        <th>Severity</th>
                        <th>Cost</th>
                        <th>Status</th>
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