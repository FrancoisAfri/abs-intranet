<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Fleet Cards Report Printed By {{ $user->person->first_name.' '. $user->person->surname }}</title>
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
							<th>Fleet Number</th>
							<th>Card Type</th>
							<th>Card Holder</th>
							<th>Card Number</th>
							<th>CVS Number</th>
							<th>Issued By</th>
							<th>Issued Date</th>
							<th>Expiry Date</th>
							<th>Status</th>
						</tr>
						</thead>
						<tbody>
						@if (count($fleetcards) > 0)
							@foreach ($fleetcards as $fleetcard)
								<tr>
									<td style="width:50px;">{{ !empty($fleetcard->fleetnumber ) ? $fleetcard->fleetnumber : '' }}</td>
									<td>{{ !empty($fleetcard->type_name ) ? $fleetcard->type_name : '' }}</td>
									<td>{{ !empty($fleetcard->first_name . '' . $fleetcard->surname ) ? $fleetcard->first_name . '' . $fleetcard->surname : ''}}</td>
									<td>{{ !empty($fleetcard->card_number) ? $fleetcard->card_number : ''}}</td>
									<td>{{ !empty($fleetcard->cvs_number) ? $fleetcard->cvs_number : ''}}</td>
									<td style="width:50px;">{{ !empty($fleetcard->Vehicle_Owner) ? $fleetcard->Vehicle_Owner : ''}}</td>
									<td>{{ !empty($fleetcard->issued_date ) ? date("Y M d", $fleetcard->issued_date) : ''}}</td>
									<td>{{ !empty($fleetcard->expiry_date ) ? date("Y M d",  $fleetcard->expiry_date) : ''}}</td>
									<td>{{ !empty($fleetcard->status) ? $status[$fleetcard->status] : ''}}</td>
								</tr>
							@endforeach
						@endif
						</tbody>
						<tfoot>
						<tr>
							<th>Fleet Number</th>
							<th>Card Type</th>
							<th>Card Holder</th>
							<th>Card Number</th>
							<th>CVS Number</th>
							<th>Issued By</th>
							<th>Issued Date</th>
							<th>Expiry Date</th>
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