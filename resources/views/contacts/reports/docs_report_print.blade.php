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
						<thead>
						<tr>
							<th>Document Type</th>
							<th>Company Name</th>
							<th>Document Name</th>
							<th>Document Description</th>
							<th>Start Date</th>
							<th>Expiring Date</th>
						</tr>
						</thead>
						<tbody>
						@if (count($companyDocs) > 0)
							@foreach($companyDocs as $companyDoc)
							   <tr>
									<td>{{ (!empty($companyDoc->doc_name)) ?  $companyDoc->doc_name : ''}} </td>
									<td>{{ (!empty($companyDoc->companyname)) ?  $companyDoc->companyname : ''}} </td>
									<td>{{ !empty($companyDoc->name) ?  $companyDoc->name : '' }}</td>
									<td>{{ !empty($companyDoc->description) ? $companyDoc->description : '' }}</td>
									<td>{{ !empty($companyDoc->date_from) ? date('d M Y ', $companyDoc->date_from) : '' }}</td>
									<td>{{ !empty($companyDoc->expirydate) ? date('d M Y ', $companyDoc->expirydate) : '' }}</td> 
								</tr>
							@endforeach
						@endif
						</tbody>
						<tfoot>
						<tr>
							<th>Document Type</th>
							<th>Company Name</th>
							<th>Document Name</th>
							<th>Document Description</th>
							<th>Start Date</th>
							<th>Expiring Date</th>
						</tr>
						</tfoot>
					</table>
					<hr class="hr-text" data-content="Client's Documents">
					<table class="table table-striped">
						<thead>
						<tr>
							<th>Client Name</th>
							<th>Document Name</th>
							<th>Document Description</th>
							<th>Start Date</th>
							<th>Expiring Date</th>
						</tr>
						</thead>
						<tbody>
						@if (count($contactsDocs) > 0)
							@foreach($contactsDocs as $contactsDoc)
							   <tr>
									<td>{{ (!empty($contactsDoc->first_name)) ?  $contactsDoc->first_name." ".$contactsDoc->surname : ''}} </td>
									<td>{{ !empty($contactsDoc->document_name) ?  $contactsDoc->document_name : '' }}</td>
									<td>{{ !empty($contactsDoc->description) ? $contactsDoc->description : '' }}</td>
									<td>{{ !empty($contactsDoc->date_from) ? date('d M Y ', $contactsDoc->date_from) : '' }}</td>
									<td>{{ !empty($contactsDoc->expirydate) ? date('d M Y ', $contactsDoc->expirydate) : '' }}</td> 
								</tr>
							@endforeach
						@endif
						</tbody>
						<tfoot>
						<tr>
							<th>Client Name</th>
							<th>Document Name</th>
							<th>Document Description</th>
							<th>Start Date</th>
							<th>Expiring Date</th>
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