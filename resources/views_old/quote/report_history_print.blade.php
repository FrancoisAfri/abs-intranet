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
				<table class="table table-bordered table-hover">
						<thead>
						<tr>
							<th>Quote Number</th>
                            <th>Quote title</th>
                            <th>Company</th>
                            <th>Contact person</th>
                            <th>Perform By</th>
                            <th>Date Perfomed</th>
                            <th>Comment</th>
                            <th>Status</th>
						</tr>
						</thead>
						<tbody>
						@if (count($quotationsAudits) > 0)
							@foreach($quotationsAudits as $quotationsAudit)
							   <tr>
                                    <td>{{!empty($quotationsAudit->quote_number  ) ? $quotationsAudit->quote_number  : ''}}</td>
                                    <td>{{!empty($quotationsAudit->quote_title  ) ? $quotationsAudit->quote_title  : ''}}</td>
                                    <td>{{!empty($quotationsAudit->companyname  ) ? $quotationsAudit->companyname  : ''}}</td>
                                    <td>{{ !empty($quotationsAudit->approverfirstname . ' ' . $quotationsAudit->approversurname ) ? $quotationsAudit->approverfirstname . ' ' . $quotationsAudit->approversurname : ''}}</td>
                                    <td>{{ !empty($quotationsAudit->approvaldate ) ? date("d/m/Y",  $quotationsAudit->approvaldate) : ''}}</td>
                                    <td>{{!empty($quotationsAudit->Comment) ? $quotationsAudit->Comment  : ''}}</td>
                                    <td>{{!empty($quotationsAudit->quoteStatus) ? $quoteStatuses[$quotationsAudit->quoteStatus] : ''}} </td>
                                </tr>
							@endforeach
						@endif
						</tbody>
						<tfoot>
						<tr>
							<th>Quote Number</th>
                            <th>Quote title</th>
                            <th>Company</th>
                            <th>Contact person</th>
                            <th>Perform By</th>
                            <th>Date Perfomed</th>
                            <th>Comment</th>
                            <th>Status</th>
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