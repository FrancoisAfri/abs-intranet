<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Fine Report Printed By {{ $user->person->first_name.' '. $user->person->surname }}</title>
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
                       <th>Product name</th>
			<th>Date </th>
			<th>Action Performed</th>
			<th>Performed By</th>
			<th>Available balance Before </th>
			<th>Available balance After </th>

                    </tr>
                    </thead>
                    <tbody>
                    @if (count($stock) > 0)
                        @foreach($stock as $fine)
                            <tr>
                                <td>{{ (!empty($booking->product_name)) ? $booking->product_name : ''}} </td>
			        <td>{{ (!empty($booking->action_date)) ? date(' d M Y', $booking->action_date) : ''}} </td>
				<td>{{ (!empty($booking->action)) ? $booking->action : ''}} </td>
				<td>{{ (!empty($booking->name)&& !empty($booking->surname)) ? $booking->name." ".$booking->surname: ''}} </td>
				<td>{{ (!empty($booking->apr_firstname)&& !empty($booking->apr_surname)) ? $booking->apr_firstname." ".$booking->apr_surname: ''}} </td>
				<td style="text-align: center">{{ (!empty( $booking->start_mileage_id)) ?  $booking->start_mileage_id : ''}} </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                    <tfoot>
                    <tr>
                       <th>Product name</th>
			<th>Date </th>
			<th>Action Performed</th>
			<th>Performed By</th>
			<th>Available balance Before </th>
			<th>Available balance After </th>
                    </tr>
                    </tfoot>
                    <input type="hidden" name="vehicle_id" size="10" value="$iVehicleID">
                    <class
                
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