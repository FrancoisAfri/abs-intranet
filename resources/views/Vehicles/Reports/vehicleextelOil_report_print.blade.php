<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Expired Licences Report Printed By {{ $user->person->first_name.' '. $user->person->surname }}</title>
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
        <div class="row">
            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
            <div class="box-body">
                <table id="example2" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                      <th>Fleet Number Type</th>
                                        <th>Fuel Supplier</th>
                                        <th>km Reading</th>
                                        <th>Hour Reading</th>
                                        <th>Litres</th>
                                        <th>Avg Cons (Odo)</th>
                                        <th>Avg Cons (Hrs)</th>
                                        <th>Avg price per Litre </th>
                                        <th>Amount </th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($externalFuelLog ) > 0)
                        @foreach($externalFuelLog  as $externallog)
                            <tr>
                              <td>{{ (!empty( $externallog->fleet_number)) ?  $externallog->fleet_number : ''}} </td> 
<!--                                   <td>{{ (!empty( $externallog->Supplier)) ?  $externallog->Supplier : ''}} </td> -->
                              <td> External </td> 
                              <td>{{ (!empty( $externallog->Odometer_reading)) ?  $externallog->Odometer_reading : 0}}  Km</td> 
                              <td>{{ (!empty( $externallog->Hoursreading)) ?  $externallog->Hoursreading : 0}} Hrs</td> 
                              <td style="text-align: center">{{ !empty($externallog->litres) ? number_format($externallog->litres, 2) : 0 }}</td>
                              <td>{{ (!empty( $externallog->Odometer_reading)) ?  number_format($externallog->Odometer_reading/$externallog->litres, 2) : 0}} </td>
                              <td>{{ (!empty( $externallog->Hoursreading)) ?  number_format($externallog->Hoursreading/$externallog->litres, 2) : 0}} </td>
                              <td> R {{ (!empty( $externallog->litres)) ?  number_format($externallog->total_cost/$externallog->litres, 2) : 0}} </td>
                              <td style="text-align: center"> R {{ !empty($externallog->total_cost) ? number_format($externallog->total_cost, 2) : 0 }}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                    <tfoot>
                    <tr>
                      <th>Fleet Number Type</th>
                      <th>Fuel Supplier</th>
                      <th>km Reading</th>
                      <th>Hour Reading</th>
                      <th>Litres</th>
                      <th>Avg Cons (Odo)</th>
                      <th>Avg Cons (Hrs)</th>
                      <th>Avg price per Litre </th>
                      <th>Amount </th>
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