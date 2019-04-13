<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Fleet Details Report Printed By {{ $user->person->first_name.' '. $user->person->surname }}</title>
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
                        <th style="width: 10px"></th>
                        <th>Vehicle type</th>
                        <th>Make</th>
                        <th>Model</th>
                        <th>Year</th>
                        <th>Color</th>
                        <th>Chassis Number</th>
                        <th>VIN Number</th>
                        <th>Fuel Type</th>
                        <th>Tank Size</th>
                        <th>Kilometer/Hours Reading</th>
                        <th>Division</th>
                        <th>Department</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($vehicledetails) > 0)
                        @foreach($vehicledetails as $details)
                            <tr>
                                <td>
                                    <div class="product-img">
                                        <img src="{{ (!empty($details->image)) ? Storage::disk('local')->url("Vehicle/images/$details->image") : 'http://placehold.it/60x50' }}"
                                             alt="Product Image" width="50" height="50">
                                    </div>
                                </td>
                                <td>{{ !empty($details->vehicle_type) ?  $details->vehicle_type: '' }}</td>
                                <td>{{ !empty($details->vehicle_make) ?  $details->vehicle_make: '' }}</td>
                                <td>{{ !empty($details->vehicle_model) ?  $details->vehicle_model: '' }}</td>
                                <td>{{ !empty($details->year) ?  $details->year: '' }}</td>
                                <td>{{ !empty($details->vehicle_color) ?  $details->vehicle_color: '' }}</td>
                                <td>{{ !empty($details->chassis_number) ?  $details->chassis_number: '' }}</td>
                                <td>{{ !empty($details->engine_number) ?  $details->engine_number: '' }}</td>
                                <td>{{ !empty($details->fuel_type) ?  $status[$details->fuel_type] : ''}}</td>
                                <td>{{ !empty($details->size_of_fuel_tank) ?  $details->size_of_fuel_tank : ''}}</td>
                                @if (isset($details) && $details->hours_reading === 0)
                                    <td>{{ !empty($details->hours_reading) ? $details->hours_reading : ''}}</td>
                                @else
                                    <td>{{ !empty($details->odometer_reading) ? $details->odometer_reading : ''}}</td>
                                @endif
                                <td>{{ !empty($details->company) ? $details->company : ''}}</td>
                                <td>{{ !empty($details->department) ? $details->department : ''}}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                    <tfoot>
                    <tr>
                        <th style="width: 10px"></th>
                        <th>Vehicle type</th>
                        <th>Make</th>
                        <th>Model</th>
                        <th>Year</th>
                        <th>Color</th>
                        <th>Chassis Number</th>
                        <th>VIN Number</th>
                        <th>Fuel Type</th>
                        <th>Tank Size</th>
                        <th>Kilometer/Hours Reading</th>
                        <th>Division</th>
                        <th>Department</th>
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