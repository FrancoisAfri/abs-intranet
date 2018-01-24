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
			<table class="table table-striped">
				<tr>
					<th>Induction Title</th>
					<th>Compamy</th>
					<th>Task Description</th>
					<th>Person Responsible</th>
					<th>Status</th>
					<th>Expected Start Date</th>
					<th>Actual Start Date</th>
					<th>Due Date</th>
					<th>Date Completed</th>
					<th>Duration</th>
				</tr>
				@if(count($employeesTasks) > 0)
					@foreach($employeesTasks as $employeesTask)
						<tr>
							<td>{{ !empty($employeesTask->induction_title) ? $employeesTask->induction_title : '' }}</td>
							<td>{{ !empty($employeesTask->comp_name) ? $employeesTask->comp_name : '' }}</td>
							<td style="width:200px;">{{ !empty($employeesTask->description) ? $employeesTask->description : '' }}</td>
							<td>{{ !empty($employeesTask->firstname) && !empty($employeesTask->surname) ? $employeesTask->firstname.' '.$employeesTask->surname : '' }}</td>
							<td>{{ (!empty($employeesTask->status)) ?  $taskStatus[$employeesTask->status] : ''}} </td>
							<td>{{ !empty($employeesTask->start_date) ? date('Y M d', $employeesTask->start_date) : '' }}</td>
							<td>{{ !empty($employeesTask->date_started) ? date('Y M d', $employeesTask->date_started) : '' }}</td>
							<td>{{ !empty($employeesTask->due_date) ? date('Y M d', $employeesTask->due_date) : '' }}</td>
							<td>{{ !empty($employeesTask->date_completed) ? date('Y M d', $employeesTask->date_completed) : '' }}</td>
							<td>{{ !empty($employeesTask->duration) ? gmdate("H:i:s", $employeesTask->duration) : '' }}</td>
						</tr>
					@endforeach
				@endif
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