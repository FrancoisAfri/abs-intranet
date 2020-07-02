<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Alerts Report Printed By {{ $user->person->first_name.' '. $user->person->surname }}</title>
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
<div class="display nowrap" style="width:100%">
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
			<table id="example1" class="table table-bordered table-hover">
				<thead>
				<tr>
					<th colspan="8" style="text-align: center"><font size="+3">Collection Overdue</font></th>
				</tr>
				<tr>
					<th>Fleet Number</th>
					<th>Make</th>
					<th>Model</th>
					<th>Registration Number</th>
					<th>Company</th>
					<th>Department</th>
					<th>Odometer/Hours Reading</th>
					<th>Collection Date</th>
				</tr>
				</thead>
				<tbody>
				@if (count($collectionOverdueAlerts) > 0)
					@foreach ($collectionOverdueAlerts as $collectionOverdueAlert)
						<tr id="categories-list">
							<td>{{ !empty($collectionOverdueAlert->fleet_number) ? $collectionOverdueAlert->fleet_number : ''}}</td>
							<td>{{ !empty($collectionOverdueAlert->vehicle_make) ? $collectionOverdueAlert->vehicle_make : ''}}</td>
							<td>{{ !empty($collectionOverdueAlert->vehicle_model) ? $collectionOverdueAlert->vehicle_model : ''}}</td>
							<td>{{ !empty($collectionOverdueAlert->vehicle_registration) ? $collectionOverdueAlert->vehicle_registration : ''}}</td>
							<td>{{ !empty($collectionOverdueAlert->company) ? $collectionOverdueAlert->company : ''}}</td>
							<td>{{ !empty($collectionOverdueAlert->Department) ? $collectionOverdueAlert->Department : ''}}</td>
							<td>{{ !empty($collectionOverdueAlert->odometer_reading ) ? $collectionOverdueAlert->odometer_reading :  $collectionOverdueAlert->hours_reading}}</td>
							<td>{{ !empty($collectionOverdueAlert->require_date) ? date("F j, Y", $collectionOverdueAlert->require_date) : ''}}</td>
						</tr>
					@endforeach
				@endif
				</tbody>
				<tfoot>
				<tr>
					<th>Fleet Number</th>
					<th>Make</th>
					<th>Model</th>
					<th>Registration Number</th>
					<th>Company</th>
					<th>Department</th>
					<th>Odometer/Hours Reading</th>
					<th>Collection Date</th>
				</tr>
				</tfoot>
			</table>
			<table id="example8" class="table table-bordered table-hover">
				<thead>
				<tr>
					<th colspan="8" style="text-align: center"><font size="+3">Return Overdue</font></th>
				</tr>
				<tr>
					<th>Fleet Number</th>
					<th>Make</th>
					<th>Model</th>
					<th>Registration Number</th>
					<th>Company</th>
					<th>Department</th>
					<th>Odometer/Hours Reading</th>
					<th>Return Date</th>
				</tr>
				</thead>
				<tbody>
				@if (count($returnOverdueAlerts) > 0)
					@foreach ($returnOverdueAlerts as $returnOverdueAlert)
						<tr id="categories-list">
							<td>{{ !empty($returnOverdueAlert->fleet_number) ? $returnOverdueAlert->fleet_number : ''}}</td>
							<td>{{ !empty($returnOverdueAlert->vehicle_make) ? $returnOverdueAlert->vehicle_make : ''}}</td>
							<td>{{ !empty($returnOverdueAlert->vehicle_model) ? $returnOverdueAlert->vehicle_model : ''}}</td>
							<td>{{ !empty($returnOverdueAlert->vehicle_registration) ? $returnOverdueAlert->vehicle_registration : ''}}</td>
							<td>{{ !empty($returnOverdueAlert->company) ? $returnOverdueAlert->company : ''}}</td>
							<td>{{ !empty($returnOverdueAlert->Department) ? $returnOverdueAlert->Department : ''}}</td>
							<td>{{ !empty($returnOverdueAlert->odometer_reading ) ? $returnOverdueAlert->odometer_reading : $returnOverdueAlert->hours_reading}}</td>
							<td>{{ !empty($returnOverdueAlert->return_date) ? date("F j, Y", $returnOverdueAlert->return_date) : ''}}</td>
						</tr>
					@endforeach
				@endif
				</tbody>
				<tfoot>
				<tr>
					<th>Fleet Number</th>
					<th>Make</th>
					<th>Model</th>
					<th>Registration Number</th>
					<th>Company</th>
					<th>Department</th>
					<th>Odometer/Hours Reading</th>
					<th>Return Date</th>
				</tr>
				</tfoot>
			</table>
			<table id="example3" class="table table-bordered table-hover">
				<thead>
				<tr>
					<th colspan="8" style="text-align: center"><font size="+3">Incidents</font></th>
				</tr>
				<tr>
					<th>Fleet Number</th>
					<th>Make</th>
					<th>Model</th>
					<th>Registration Number</th>
					<th>Company</th>
					<th>Department</th>
					<th>Odometer/Hours Reading</th>
					<th>Incident Type/Severity</th>
				</tr>
				</thead>
				<tbody>
				@if (count($incidentsAlerts) > 0)
					@foreach ($incidentsAlerts as $incidentsAlert)
						<tr id="categories-list">
							<td>{{ !empty($incidentsAlert->fleet_number) ? $incidentsAlert->fleet_number : ''}}</td>
							<td>{{ !empty($incidentsAlert->vehicle_make) ? $incidentsAlert->vehicle_make : ''}}</td>
							<td>{{ !empty($incidentsAlert->vehicle_model) ? $incidentsAlert->vehicle_model : ''}}</td>
							<td>{{ !empty($incidentsAlert->vehicle_registration) ? $incidentsAlert->vehicle_registration : ''}}</td>
							<td>{{ !empty($incidentsAlert->company) ? $incidentsAlert->company : ''}}</td>
							<td>{{ !empty($incidentsAlert->Department) ? $incidentsAlert->Department : ''}}</td>
							<td>{{ !empty($incidentsAlert->odometer_reading ) ? $incidentsAlert->odometer_reading : ''}}</td>
							<td>{{ !empty($incidentsAlert->incidents_severity) && (!empty($incidentsAlert->incident_type_name)) ? $incidentsAlert->incident_type_name."|".$severityArray[$incidentsAlert->incidents_severity] : ''}}</td>
						</tr>
					@endforeach
				@endif
				</tbody>
				<tfoot>
				<tr>
					<th>Fleet Number</th>
					<th>Make</th>
					<th>Model</th>
					<th>Registration Number</th>
					<th>Company</th>
					<th>Department</th>
					<th>Odometer/Hours Reading</th>
				</tr>
				</tfoot>
			</table>
			<table id="example4" class="table table-bordered table-hover">
				<thead>
				<tr>
					<th colspan="8" style="text-align: center"><font size="+3">Service Due</font></th>
				</tr>
				<tr>
					<th>Fleet Number</th>
					<th>Make</th>
					<th>Model</th>
					<th>Registration Number</th>
					<th>Company</th>
					<th>Department</th>
					<th>Odometer/Hours Reading</th>
					<th>Service Date</th>
				</tr>
				</thead>
				<tbody>
				@if (count($servicesAlerts) > 0)
					@foreach ($servicesAlerts as $servicesAlert)
						<tr id="categories-list">
							<td>{{ !empty($servicesAlert->fleet_number) ? $servicesAlert->fleet_number : ''}}</td>
							<td>{{ !empty($servicesAlert->vehicle_make) ? $servicesAlert->vehicle_make : ''}}</td>
							<td>{{ !empty($servicesAlert->vehicle_model) ? $servicesAlert->vehicle_model : ''}}</td>
							<td>{{ !empty($servicesAlert->vehicle_registration) ? $servicesAlert->vehicle_registration : ''}}</td>
							<td>{{ !empty($servicesAlert->company) ? $servicesAlert->company : ''}}</td>
							<td>{{ !empty($servicesAlert->Department) ? $servicesAlert->Department : ''}}</td>
							<td>{{ !empty($servicesAlert->odometer_reading ) ? $servicesAlert->odometer_reading : ''}}</td>
							<td>{{ !empty($servicesAlert->nxt_service_date ) ? date("F j, Y", $servicesAlert->nxt_service_date) : ''}}</td>
						</tr>
					@endforeach
				@endif
				</tbody>
				<tfoot>
				<tr>
					<th>Fleet Number</th>
					<th>Make</th>
					<th>Model</th>
					<th>Registration Number</th>
					<th>Company</th>
					<th>Department</th>
					<th>Odometer/Hours Reading</th>
					<th>Service Date</th>
				</tr>
				</tfoot>
			</table>
			<table id="example5" class="table table-bordered table-hover">
				<thead>
				<tr>
					<th colspan="8" style="text-align: center"><font size="+3">Warranty Expired</font></th>
				</tr>
				<tr>
					<th>Fleet Number</th>
					<th>Make</th>
					<th>Model</th>
					<th>Registration Number</th>
					<th>Company</th>
					<th>Department</th>
					<th>Odometer/Hours Reading</th>
				</tr>
				</thead>
				<tbody>
				@if (count($warantyAlerts) > 0)
					@foreach ($warantyAlerts as $warantyAlert)
						<tr id="categories-list">
							<td>{{ !empty($warantyAlert->fleet_number) ? $warantyAlert->fleet_number : ''}}</td>
							<td>{{ !empty($warantyAlert->vehicle_make) ? $warantyAlert->vehicle_make : ''}}</td>
							<td>{{ !empty($warantyAlert->vehicle_model) ? $warantyAlert->vehicle_model : ''}}</td>
							<td>{{ !empty($warantyAlert->vehicle_registration) ? $warantyAlert->vehicle_registration : ''}}</td>
							<td>{{ !empty($warantyAlert->company) ? $warantyAlert->company : ''}}</td>
							<td>{{ !empty($warantyAlert->Department) ? $warantyAlert->Department : ''}}</td>
							<td>{{ !empty($warantyAlert->odometer_reading ) ? $warantyAlert->odometer_reading : ''}}</td>
							<td>{{ !empty($warantyAlert->exp_date ) ? date("F j, Y", $warantyAlert->exp_date): ''}}</td>
						</tr>
					@endforeach
				@endif
				</tbody>
				<tfoot>
				<tr>
				   <td colspan="9">&nbsp;</td>
				</tr>
				</tfoot>
			</table>
			<table id="example6" class="table table-bordered table-hover">
				<thead>
				<tr>
					<th colspan="8" style="text-align: center"><font size="+3">Permits Expired</font></th>
				</tr>
				<tr>
					<th>Fleet Number</th>
					<th>Make</th>
					<th>Model</th>
					<th>Registration Number</th>
					<th>Company</th>
					<th>Department</th>
					<th>Permit Type</th>
					<th>Expiring Date</th>
				</tr>
				</thead>
				<tbody>
				@if (count($expiredPermitAlerts) > 0)
					@foreach ($expiredPermitAlerts as $expiredPermitAlert)
						<tr id="categories-list">
							<td>{{ !empty($expiredPermitAlert->fleet_number) ? $expiredPermitAlert->fleet_number : ''}}</td>
							<td>{{ !empty($expiredPermitAlert->vehicle_make) ? $expiredPermitAlert->vehicle_make : ''}}</td>
							<td>{{ !empty($expiredPermitAlert->vehicle_model) ? $expiredPermitAlert->vehicle_model : ''}}</td>
							<td>{{ !empty($expiredPermitAlert->vehicle_registration) ? $expiredPermitAlert->vehicle_registration : ''}}</td>
							<td>{{ !empty($expiredPermitAlert->company) ? $expiredPermitAlert->company : ''}}</td>
							<td>{{ !empty($expiredPermitAlert->Department) ? $expiredPermitAlert->Department : ''}}</td>
							<td>{{ !empty($expiredPermitAlert->license_name ) ? $expiredPermitAlert->license_name : ''}}</td>
							<td>{{ !empty($expiredPermitAlert->exp_date ) ? date("F j, Y", $expiredPermitAlert->exp_date) : ''}}</td>
						</tr>
					@endforeach
				@endif
				</tbody>
				<tfoot>
				<tr>
					<th>Fleet Number</th>
					<th>Make</th>
					<th>Model</th>
					<th>Registration Number</th>
					<th>Company</th>
					<th>Permit Type</th>
					<th>Expiring Date</th>
				</tr>
				</tfoot>
			</table>
			<table id="example7" class="table table-bordered table-hover">
				<thead>
				<tr>
					<th colspan="9" style="text-align: center"><font size="+3">Documents Expired</font</th>
				</tr>
				<tr>
					<th>Fleet Number</th>
					<th>Make</th>
					<th>Model</th>
					<th>Registration Number</th>
					<th>Company</th>
					<th>Department</th>
					<th>Document Type</th>
					<th>Expiring Date</th>
				</tr>
				</thead>
				<tbody>
				@if (count($expiredDocAlerts) > 0)
					@foreach ($expiredDocAlerts as $expiredDocAlert)
						<tr id="categories-list">
							<td>{{ !empty($expiredDocAlert->fleet_number) ? $expiredDocAlert->fleet_number : ''}}</td>
							<td>{{ !empty($expiredDocAlert->vehicle_make) ? $expiredDocAlert->vehicle_make : ''}}</td>
							<td>{{ !empty($expiredDocAlert->vehicle_model) ? $expiredDocAlert->vehicle_model : ''}}</td>
							<td>{{ !empty($expiredDocAlert->vehicle_registration) ? $expiredDocAlert->vehicle_registration : ''}}</td>
							<td>{{ !empty($expiredDocAlert->company) ? $expiredDocAlert->company : ''}}</td>
							<td>{{ !empty($expiredDocAlert->Department) ? $expiredDocAlert->Department : ''}}</td>
							<td>{{ !empty($expiredDocAlert->type_name ) ? $expiredDocAlert->type_name : ''}}</td>
							<td>{{ !empty($expiredDocAlert->exp_date ) ? date("F j, Y", $expiredDocAlert->exp_date) : ''}}</td>
						</tr>
					@endforeach
				@endif
				</tbody>
				<tfoot>
				<tr>
					<th>Fleet Number</th>
					<th>Make</th>
					<th>Model</th>
					<th>Registration Number</th>
					<th>Company</th>
					<th>Department</th>
					<th>Document Type</th>
					<th>Expiring Date</th>
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