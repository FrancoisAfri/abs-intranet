<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Users List Report Printed By {{ $user->person->first_name.' '. $user->person->surname }}</title>
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
			<table id="example2" class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th>Firstname</th>
						<th>Surname</th>
						<th>Employee Number</th>
						<th>Email</th>
						<th>Position</th>
						<th>Report To</th>
						<th>Status</th>
						<th>Leave Profile</th>
					</tr>
				</thead>
				<tbody>
					@foreach($employees as $employee)
						<tr>
							<td>{{ !empty($employee->first_name) ? $employee->first_name : '' }}</td>
							<td>{{ !empty($employee->surname) ? $employee->surname : '' }}</td>
							<td>{{ !empty($employee->employee_number) ? $employee->employee_number : '' }}</td>
							<td>{{ !empty($employee->email) ? $employee->email : '' }}</td>
							<td>{{ !empty($employee->job_title) ? $employee->job_title : '' }}</td>
							<td>{{ !empty($employee->manager_first_name) && !empty($employee->manager_surname)  ? $employee->manager_first_name." ". $employee->manager_surname: '' }}</td>
							<td>{{ !empty($employee->status) && ($employee->status == 1)  ? 'Active': 'Inactive' }}</td>
							<td>{{ !empty($employee->profile_name) ? $employee->profile_name: '' }}</td>
						</tr>
					@endforeach				
				</tbody>
				<tfoot>
					<tr>
						<th>Firstname</th>
						<th>Surname</th>
						<th>Employee Number</th>
						<th>Email</th>
						<th>Position</th>
						<th>Report To</th>
						<th>Status</th>
						<th>Leave Profile</th>
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