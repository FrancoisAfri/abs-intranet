<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Leave Taken Report Printed By {{ $user->person->first_name.' '. $user->person->surname }}</title>
 
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
				<table id="example2" class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>Employee Number </th>
							<th>Employee Name </th>
							<th>Leave Type </th>
							<th>Min Day(s)</th>
							<th>Max Day(s)</th>
						</tr>
					</thead>
					<tbody>
					 @if(count($allowances) > 0)
						@foreach($allowances as $allowance)
							<tr>
							   <td>{{ !empty($allowance->employee_number) ? $allowance->employee_number : '' }}</td>
								<td>{{ !empty($allowance->first_name) && !empty($allowance->surname) ? $allowance->first_name.' '.$allowance->surname : '' }}</td>
								<td>{{ !empty($allowance->leave_type_name) ? $allowance->leave_type_name : $allowance->leave_type_name }}</td>
								<td>{{ !empty($allowance->min) ? $allowance->min : $allowance->min }}</td>
								<td>{{ !empty($allowance->max) ? $allowance->max : $allowance->max }}</td>
							</tr>
						@endforeach
					@endif
					</tbody>
					<tfoot>
						<tr>
							<th>Employee Number </th>
							<th>Employee Name </th>
							<th>Leave Type </th>
							<th>Min Day(s)</th>
							<th>Max Day(s)</th>
						</tr>
					</tfoot>
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