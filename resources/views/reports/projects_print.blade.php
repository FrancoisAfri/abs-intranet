<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Project Report Printed By {{ $user->person->first_name.' '. $user->person->surname }}</title>
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
          P O BOX 6377<br>
		  SECUNDA<br>
          2302<br>
        </address>
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
						<th>Name</th>
						<th>Code</th>
						<th>Start Date</th>
						<th>End Date</th>
						<th>Brief Description</th>
						<th>Service Provider</th>
						<th>Manager</th>
						<th>Programme</th>
					</tr>
					@if(count($projects) > 0)
						@foreach($projects as $project)
							<tr>
								<td>{{ !empty($project->name) ? $project->name : '' }}</td>
								<td>{{ !empty($project->code) ? $project->code : '' }}</td>
								<td>{{ !empty($project->start_date) ? date('Y M d', $project->start_date) : '' }}</td>
								<td>{{ !empty($project->end_date) ? date('Y M d', $project->end_date) : '' }}</td>
								<td>{{ !empty($project->description) ? $project->description : '' }}</td>
								<td>{{ !empty($project->com_name) ? $project->com_name : '' }}</td>
								<td>{{ !empty($project->hr_firstname) && !empty($project->hr_surname) ? $project->hr_firstname.' '.$project->hr_surname : '' }}</td>
								<td>{{ !empty($project->prog_name) && !empty($project->hr_surname) ? $project->prog_name : '' }}</td>
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