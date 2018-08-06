<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
 
 
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="/bower_components/AdminLTE/bootstrap/css/bootstrap.min.css">
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
						<th>Company Name</th>
						<th>Contact person</th>
						<th>Communication Date</th>
						<th>Communication Time</th>
						<th>Communication Type</th>
						<th>Message</th>
						<th>Sent By</th>
					</tr>
					@if (count($contactsCommunications) > 0)
						@foreach($contactsCommunications as $contactsCommunication)
						   <tr>
								<td>{{ (!empty($contactsCommunication->companyname)) ?  $contactsCommunication->companyname : ''}} </td>
								<td>{{ !empty($contactsCommunication->first_name) && !empty($contactsCommunication->surname) ?  $contactsCommunication->first_name." ".$contactsCommunication->surname : '' }}</td>
								<td>{{ !empty($contactsCommunication->communication_date) ? date('d M Y ', $contactsCommunication->communication_date) : '' }}</td>
								<td>{{ !empty($contactsCommunication->time_sent) ? $contactsCommunication->time_sent : '' }}</td>
								<td>{{ (!empty($contactsCommunication->communication_type)) ?  $communicationStatus[$contactsCommunication->communication_type] : ''}} </td>
								<td>{{ (!empty($contactsCommunication->message)) ?  $contactsCommunication->message : ''}} </td> 
								<td>{{ (!empty($contactsCommunication->hr_firstname) && !empty($contactsCommunication->hr_surname)) ?  $contactsCommunication->hr_firstname." ".$contactsCommunication->hr_surname : ''}} </td> 
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