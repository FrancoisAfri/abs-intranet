<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Audit Report Printed By {{ $user->person->first_name.' '. $user->person->surname }}</title>
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
            <!--<address>
          <strong>{{ $company_name }}</strong><br>
          P O BOX 6377<br>
		  SECUNDA<br>
          2302<br>
        </address>-->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <div class="row">
            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
            <div class="panel box box-primary">
                <div class="box-body">
                    <table class="table table-striped">
                        <tr>
                            <th style="width: 5px; text-align: center;"> Job Card #</th>
                                            <th>Vehicleb Name</th>
                                            <th>Registration Number</th>
                                            <th>Job Card Date</th>
                                            <th>Completion Date</th>
                                            <th>Instruction Mechanic</th>
                                            <th>Service Type</th>
                                            <th>Supplier</th>
                                            <th>Status</th>
                        </tr>
                         @if (count($vehiclemaintenance) > 0)
                            @foreach ($vehiclemaintenance as $jobcard)
                                 <td>{{ !empty($jobcard->jobcard_number) ? $jobcard->jobcard_number : '' }}</td>
                                <td>{{ (!empty( $jobcard->fleet_number . ' ' .  $jobcard->vehicle_registration . ' ' . $jobcard->vehicle_make . ' ' . $jobcard->vehicle_model))
                                             ?  $jobcard->fleet_number . ' ' .  $jobcard->vehicle_registration . ' ' . $jobcard->vehicle_make . ' ' . $jobcard->vehicle_model : ''}} </td>
                                <td>{{ (!empty( $jobcard->vehicle_registration)) ?  $jobcard->vehicle_registration : ''}} </td>
                                <td>{{ !empty($jobcard->card_date) ? date(' d M Y', $jobcard->card_date) : '' }}</td>
                                <td>{{ !empty($jobcard->completion_date ) ? date(' d M Y', $jobcard->completion_date) : 'Nill' }}</td>
                                <td>{{ !empty($jobcard->instruction) ? $jobcard->instruction : '' }}</td>
                                 <td>{{ !empty($jobcard->servicetype) ? $jobcard->servicetype : '' }}</td>
                                <td>{{ !empty($jobcard->Supplier) ? $jobcard->Supplier : '' }}</td>
                                <td>{{ !empty($jobcard->aStatus) ? $jobcard->aStatus : '' }}</td>
                                                </tr>
                                            @endforeach
                        @endif
                    </table>
                </div>
            </div>

        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>