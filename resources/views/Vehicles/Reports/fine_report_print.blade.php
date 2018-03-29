<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Task Report Printed By {{ $user->person->first_name.' '. $user->person->surname }}</title>
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
                        <th style="width: 10px"></th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Reference</th>
                        <th>Location</th>
                        <th>Type</th>
                        <th>Driver</th>
                        <th>Amount</th>
                        <th>Amount Paid</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($vehiclefines) > 0)
                        @foreach($vehiclefines as $fine)
                            <tr>
                                <td>{{ (!empty($fine->VehicleMake) ) ? $fine->VehicleMake." ".$fine->VehicleModel." ".$fine->vehicletypes." ".$fine->vehicle_registration : ''}}</td>
                                <td>{{ !empty($fine->date_of_fine) ? date(' d M Y', $fine->date_of_fine) : '' }}</td>
                                <td>{{ !empty($fine->time_of_fine) ? date(' h:m:z', $fine->time_of_fine) : '' }}</td>
                                <td>{{ !empty($fine->fine_ref) ? $fine->fine_ref : '' }}</td>
                                <td>{{ !empty($fine->location) ?  $fine->location : '' }}</td>
                                <td>{{ !empty($fine->fine_type) ?  $fineType[$fine->fine_type] : '' }}</td>
                                <td>{{ !empty($fine->firstname . ' ' . $fine->surname ) ?  $fine->firstname . ' ' . $fine->surname : '' }}</td>
                                <td style="text-align: center">{{ !empty($fine->amount  ) ?  'R '.number_format($fine->amount, 2) :'' }} </td>
                                <td style="text-align: center">{{ !empty($fine->amount_paid  ) ?  'R '.number_format($fine->amount_paid, 2) :'' }}</td>
                                <td style="text-align: center">{{  !empty($fine->fine_status  ) ?  $status[$fine->fine_status] :'' }}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                    <tfoot>
                    <tr>
                        <th style="width: 10px"></th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Reference</th>
                        <th>Location</th>
                        <th>Type</th>
                        <th>Driver</th>
                        <th>Amount</th>
                        <th>Amount Paid</th>
                        <th>Status</th>
                    </tr>
                    </tfoot>
                    <input type="hidden" name="vehicle_id" size="10" value="$iVehicleID">
                    <class
                    ="caption">
                    <td style="text-align: right" nowrap></td>
                    <td colspan="6" style="text-align:right">Total</td>
                    <td style="text-align: right">{{number_format($total, 2) }}</td>
                    <td style="text-align: right">{{number_format($totalamount_paid, 2) }}</td>
                    <td style="text-align: right" nowrap></td>
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