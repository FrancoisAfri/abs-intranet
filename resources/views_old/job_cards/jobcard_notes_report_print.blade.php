<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Job Card Notes Report Printed By {{ $user->person->first_name.' '. $user->person->surname }}</title>
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
        @page {
            size: landscape;
        }
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
        <table class="table table-striped table-bordered">
            @foreach ($vehiclemaintenance as $vehiclemaintenance)
                <tr>
                    <td class="caption">Fleet Number</td>
                    <td>{{ !empty($vehiclemaintenance->fleet_number) ? $vehiclemaintenance->fleet_number : ''}}</td>
                    <td class="caption">Job Card Number</td>
                    <td>{{ !empty($vehiclemaintenance->jobcard_number) ? $vehiclemaintenance->jobcard_number : ''}}</td>
                </tr>
                <tr>

                    <td class="caption">vehicle registration Number</td>
                    <td>{{ !empty($vehiclemaintenance->vehicle_registration) ? $vehiclemaintenance->vehicle_registration : ''}}</td>
                    <td class="caption">Job Card Date</td>
                    <td>{{ !empty($vehiclemaintenance->card_date) ? date(' d M Y', $vehiclemaintenance->card_date) : ''}}</td>
                </tr>
                <tr>
                    <td class="caption" width="25%">Make</td>
                    <td width="25%">{{ !empty($vehiclemaintenance->vehicle_make) ? $vehiclemaintenance->vehicle_make : ''}}</td>
                    <td class="caption">Job Card Status</td>
                    <td>{{ !empty($vehiclemaintenance->aStatus) ? $vehiclemaintenance->aStatus : ''}}</td>
                </tr>
                <tr>
                    <td class="caption" width="25%">Model</td>
                    <td width="25%">{{ !empty($vehiclemaintenance->vehicle_model) ? $vehiclemaintenance->vehicle_model : ''}}</td>
                    <td class="caption" width="25%"></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="caption">Vehicle Description</td>
                    <td>{{ !empty($vehiclemaintenance->instruction) ? $vehiclemaintenance->instruction : ''}}</td>
                    <td class="caption">Driver</td>
                    <td>{{ !empty($vehiclemaintenance->last_driver_id) ? $vehiclemaintenance->last_driver_id : ''}}</td>
                </tr>
                <tr>
                    <td class="caption">Current Odometer</td>
                    <td>{{ !empty($vehiclemaintenance->odometer_reading) ? $vehiclemaintenance->odometer_reading : ''}}</td>
                    <td class="caption">Mechanic</td>
                    <td>{{ !empty($vehiclemaintenance->mechanic_id) ? $vehiclemaintenance->mechanic_id : ''}}</td>
                </tr>
                <tr>
                    <td class="caption">Current Hours</td>
                    <td>{{ !empty($vehiclemaintenance->hours_reading) ? $vehiclemaintenance->hours_reading : ''}}</td>
                    <td class="caption">Inspection List Number</td>
                    <td>{{ !empty($vehiclemaintenance->cell_number) ? $vehiclemaintenance->cell_number : ''}}</td>
                </tr>

                <tr>
                    <td class="caption">Hours Allocated</td>
                    <td>{{ !empty($vehiclemaintenance->hours_reading) ? $vehiclemaintenance->hours_reading : ''}}</td>
                    <td class="caption">Completion Date</td>
                    <td>{{ !empty($vehiclemaintenance->completion_date) ? date(' d M Y', $vehiclemaintenance->completion_date) : ''}}</td>
                </tr>

                <tr>
                    <td class="caption">Hours used</td>
                <!--                                    <td>{{ !empty($vehiclemaintenance->vehicle_type) ? $vehiclemaintenance->vehicle_type : ''}}</td>-->
                    <td></td>
                    <td class="caption">Service Time</td>
                    <td>{{ !empty($vehiclemaintenance->service_time) ? $vehiclemaintenance->service_time : ''}}</td>
                </tr>

                <tr>
                    <td class="caption">Service Type</td>
                    <td>{{ !empty($vehiclemaintenance->servicetype) ? $vehiclemaintenance->servicetype : ''}}</td>
                    <td class="caption">Servicing Agent</td>
                    <td> N/A</td>
                </tr>

                <tr>
                    <td class="caption">Purchase Order Number</td>
                <!--                                    <td>{{ !empty($vehiclemaintenance->size_of_fuel_tank) ? $vehiclemaintenance->size_of_fuel_tank : ''}}</td>-->
                    <td></td>
                    <td class="caption">Service Date</td>
                    <td></td>
                </tr>

                <br>

                <tr>
                    <td colspan="4" class="caption">Job Card Instructions</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align:left;" height="40" border="1">
                        -- {{ !empty($vehiclemaintenance->instruction) ? $vehiclemaintenance->instruction : ''}}</td>
                </tr>
                <tr>

                    <br>

                <tr>
                    <td colspan="4" class="caption"> Stock Items Despatched</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align:left;" height="40" border="1"></td>
                </tr>
                <tr>

                    <br>

                <tr>
                    <td colspan="8" class="caption"> Stock Items Despatched</td>
                </tr>
                <tr>
                    <td colspan="8" style="text-align:left;" height="120" border="1"></td>
                </tr>
                <tr>
                <tr>
                    <td colspan="2" style="text-align:left;">Completed (Y/N)</td>
                    <td colspan="2" style="text-align:left;"></td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
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
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align:left;">Notes:</td>
                </tr> <!--For new display-->
                <tr>
                    <td><b>Date
                            Captured</b>{{ !empty($vehiclemaintenance->card_date) ? date(' d M Y', $vehiclemaintenance->card_date) : '' }}
                    </td>
                    currentDate
                    <td><b>By</b></td>
                    <td colspan="2"><b>Notes</b></td>
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