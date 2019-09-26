<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Users Access Report(Module: {{ $moduleName }}) Printed By {{ $user->person->first_name.' '. $user->person->surname }}</title>
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
					<th>Employee</th>
					<th style="vertical-align: middle; text-align: center;">None</th>
					<th style="vertical-align: middle; text-align: center;">Read</th>
					<th style="vertical-align: middle; text-align: center;">Write</th>
					<th style="vertical-align: middle; text-align: center;">Modify</th>
					<th style="vertical-align: middle; text-align: center;">Admin</th>
					<th style="vertical-align: middle; text-align: center;">Superuser</th>
				</tr>
				</thead>
				<tbody>
				@foreach($employees as $employee)
					<tr>
						<td style="vertical-align: middle;" nowrap>{{ $employee->full_name }}</td>
						<td style="vertical-align: middle; text-align: center;">
							{{ $employee->access_level == 0 ?  "Yes": '' }}
						</td>
						<td style="vertical-align: middle; text-align: center;">
							{{ $employee->access_level == 1 ?  "Yes": '' }}
						</td>
						<td style="vertical-align: middle; text-align: center;">
							{{ $employee->access_level == 2 ?  "Yes": '' }}
						</td>
						<td style="vertical-align: middle; text-align: center;">
							{{ $employee->access_level == 3 ?  "Yes": '' }}
						</td>
						<td style="vertical-align: middle; text-align: center;">
							{{ $employee->access_level == 4 ?  "Yes": '' }}
						</td>
						<td style="vertical-align: middle; text-align: center;">
							{{ $employee->access_level == 5 ?  "Yes": '' }}
						</td>
					</tr>
				@endforeach
				</tbody>
				<tfoot>
				<tr>
					<th>Employee</th>
					<th style="vertical-align: middle; text-align: center;">None</th>
					<th style="vertical-align: middle; text-align: center;">Read</th>
					<th style="vertical-align: middle; text-align: center;">Write</th>
					<th style="vertical-align: middle; text-align: center;">Modify</th>
					<th style="vertical-align: middle; text-align: center;">Admin</th>
					<th style="vertical-align: middle; text-align: center;">Superuser</th>
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