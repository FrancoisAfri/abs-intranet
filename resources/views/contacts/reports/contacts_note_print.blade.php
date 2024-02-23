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
    <div class="row invoice-info">notes
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
						<th>Client</th>
						<th>Client Rep</th>
						<th>Employee</th>
						<th>Notes</th>
						<th>Next Action</th>
						<th>Follow Up Date</th>
					</tr>
					@if(count($notes) > 0)
						@foreach($notes as $note)
							<tr>
								<td>{{ !empty($note->companyname) ? $note->companyname : '' }}</td>
								<td>{{ !empty($note->contact_name) && !empty($note->contact_surname) ? $note->contact_name.' '.$note->contact_surname : '' }}</td>
								<td>{{ !empty($note->hr_name) && !empty($note->hr_surname) ? $note->hr_name.' '.$note->hr_surname : '' }}</td>
								<td>{{ !empty($note->notes) ? $note->notes : '' }}</td>
								<td>{{ (!empty($note->next_action)) ?  $notesStatus[$note->next_action] : ''}} </td>
								<td>{{ !empty($note->follow_date) ? date('d M Y ', $note->follow_date) : '' }}</td>
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