@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <!-- Include Date Range Picker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css"/>
	<!-- iCheck -->
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/green.css">
@endsection
@section('content')
    <div class="row">
		<div class="col-md-12">
			<div class="box box-warning">
				<div class="box-header with-border">
					<h3 class="box-title"> Send Message </h3>
				</div>
			<div class="box-body">
				<div class="row">
					<div class="col-sm-12">
						<p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
							<strong class="lead">Vehicle Details</strong><br>

							@if(!empty($vehiclemaker))
								| &nbsp; &nbsp; <strong>Vehicle Make:</strong> <em>{{ $vehiclemaker->name }}</em> &nbsp;
								&nbsp;
							@endif
							@if(!empty($vehiclemodeler))
								-| &nbsp; &nbsp; <strong>Vehicle Model:</strong> <em>{{ $vehiclemodeler->name }}</em>
								&nbsp; &nbsp;
							@endif
							@if(!empty($vehicleTypes))
								-| &nbsp; &nbsp; <strong>Vehicle Type:</strong> <em>{{ $vehicleTypes->name }}</em> &nbsp;
								&nbsp;
							@endif
							@if(!empty($maintenance->vehicle_registration))
								-| &nbsp; &nbsp; <strong>Vehicle Registration:</strong>
								<em>{{ $maintenance->vehicle_registration }}</em> &nbsp; &nbsp;
							@endif
							@if(!empty($maintenance->year))
								-| &nbsp; &nbsp; <strong>Year:</strong> <em>{{ $maintenance->year }}</em> &nbsp;
								&nbsp;
							@endif
							@if(!empty($maintenance->vehicle_color))
								-| &nbsp; &nbsp; <strong>Vehicle Color:</strong>
								<em>{{ $maintenance->vehicle_color }}</em> &nbsp; &nbsp; -|
							@endif
						</p>
					</div>
					<div align="center">
						<!--  -->
						<a href="{{ '/vehicle_management/viewdetails/' . $maintenance->id }}" class="btn btn-app">
							<i class="fa fa-bars"></i> General Details
						</a>
						<a href="{{ '/vehicle_management/bookin_log/' . $maintenance->id }}" class="btn btn-app">
							<i class="fa fa-book"></i> Booking Log
						</a>
						<a href="{{ '/vehicle_management/fuel_log/' . $maintenance->id }}" class="btn btn-app">
							<i class="fa fa-tint"></i> Fuel Log
						</a>
						<a href="{{ '/vehicle_management/incidents/' . $maintenance->id }}" class="btn btn-app">
							<i class="fa fa-medkit"></i> Incidents
						</a>
						<a href="{{ '/vehicle_management/fines/' . $maintenance->id }}" class="btn btn-app">
							<i class="fa fa-list-alt"></i> Fines
						</a>
						<a href="{{ '/vehicle_management/service_details/' . $maintenance->id }}" class="btn btn-app">
							<i class="fa fa-area-chart"></i> Service Details
						</a>
						<a href="{{ '/vehicle_management/insurance/' . $maintenance->id }}" class="btn btn-app">
							<i class="fa fa-car"></i>Insurance
						</a>
						<a href="{{ '/vehicle_management/warranties/' . $maintenance->id }}" class="btn btn-app">
							<i class="fa fa-snowflake-o"></i>Warranties
						</a>
						<a href="{{ '/vehicle_management/general_cost/' . $maintenance->id }}" class="btn btn-app">
							<i class="fa fa-money"></i> General Cost
						</a>
						<a href="{{ '/vehicle_management/fleet-communications/' . $maintenance->id }}"
								   class="btn btn-app"><i class="fa fa-money"></i> Communications</a>
						<!--  -->
					</div>
				</div>
						
				<!-- Search User Form -->
				<div class="col-md-12">
					<!-- Horizontal Form -->
					<div class="box box-primary">
						<div class="box-header with-border">
							<i class="fa fa-search pull-right"></i>
							<h3 class="box-title">Select Client(s) To Send message To:</h3>
						</div>
						<!-- /.box-header -->
						<!-- form start -->
						<form class="form-horizontal" method="POST" action="/vehicle_management/send-communicaions/{{ $maintenance->id }}">
							{{ csrf_field() }}
							<div class="box-body">
								@if (count($errors) > 0)
									<div class="alert alert-danger alert-dismissible fade in">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
										<h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
										<ul>
											@foreach ($errors->all() as $error)
												<li>{{ $error }}</li>
											@endforeach
										</ul>
									</div>
								@endif
								<div class="form-group{{ $errors->has('send_type') ? ' has-error' : '' }}">
									<label for="send_type" class="col-sm-2 control-label">Send To</label>
									<div class="col-sm-10">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-check-square-o"></i>
											</div>
											<label class="radio-inline"><input type="radio" id="rdo_clients" name="send_type" value="1" checked> Client(s</label>
											<label class="radio-inline"><input type="radio" id="rdo_employees" name="send_type" value="2"> Employees</label>
										</div>
									</div>
								</div>
								<div class="form-group send-clients {{ $errors->has('clients') ? ' has-error' : '' }}">
									<label for="clients" class="col-sm-2 control-label">Client(s)</label>
									<div class="col-sm-10">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-users"></i>
											</div>
											<select name="clients[]" id="clients" class="form-control select2" multiple  style="width: 100%;"
											 data-placeholder="*** Select a Client ***">
												@foreach($contactPersons as $contactPerson)
													<option value="{{ $contactPerson->id}}">{{$contactPerson->comp_name."|**|". $contactPerson->first_name." ".$contactPerson->surname }}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="form-group send-employee {{ $errors->has('employees') ? ' has-error' : '' }}">
									<label for="employees" class="col-sm-2 control-label">Employee(s)</label>
									<div class="col-sm-10">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-user"></i>
											</div>
											<select class="form-control select2" multiple style="width: 100%;" id="employees" name="employees[]"
											 data-placeholder="*** Select an Employee ***">
												@foreach($employees as $employee)
													<option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
												@endforeach

											</select>
										</div>
									</div>
								</div>
								<div class="form-group{{ $errors->has('message_type') ? ' has-error' : '' }}">
									<label for="message_type" class="col-sm-2 control-label">Type</label>
									<div class="col-sm-10">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-check-square-o"></i>
											</div>
											<label class="radio-inline"><input type="radio" id="rdo_email" name="message_type" value="1" checked> Email</label>
											<label class="radio-inline"><input type="radio" id="rdo_sms" name="message_type" value="2"> SMS</label>
										</div>
									</div>
								</div>
								<div class="form-group sms-field {{ $errors->has('sms_content') ? ' has-error' : '' }}">
									<label for="sms_content" class="col-sm-2 control-label">SMS</label>

									<div class="col-sm-10">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-comments-o"></i>
											</div>
											<textarea name="sms_content" id="sms_content" class="form-control" placeholder="Message" rows="3" maxlength="180">{{ old('sms_content') }}</textarea>
										</div>
									</div>
								</div>
								<div class="form-group email-field {{ $errors->has('email_content') ? ' has-error' : '' }}">
									<label for="email_content" class="col-sm-2 control-label">Email</label>

									<div class="col-sm-10">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-envelope-o"></i>
											</div>
											<textarea name="email_content" id="email_content" class="form-control" placeholder="Message" rows="6" maxlength="1500">{{ old('email_content') }}</textarea>
										</div>
									</div>
								</div>
								<div class="form-group {{ $errors->has('email_content') ? ' has-error' : '' }}">
									<label for="email_content" class="col-sm-2 control-label">Send Vehicle Deatails</label>

									<div class="col-sm-10">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-check"></i>
											</div>
												<input class="rdo-iCheck" type="checkbox" id="send_fleet_details" name="send_fleet_details" value="1">
										</div>
									</div>
								</div>
							</div>
							<!-- /.box-body -->
							<div class="box-footer">
								<button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
								<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-paper-plane-o"></i> Send</button>
							</div>
							<!-- /.box-footer -->
						</form>
						<!-- End Form-->
					</div>
				</div>
				<!-- /.box -->
			</div>
        <!-- /.col-md-12 -->
		</div>
		</div>
    </div>
@endsection

@section('page_script')
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
	<!-- iCheck -->
	<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
	<script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
			type="text/javascript"></script>
	<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
	<script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js"
			type="text/javascript"></script>
	<!-- the main fileinput plugin file -->
	<script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
	<!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
	<script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
	<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

	<!-- InputMask -->
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <script type="text/javascript">
		$('#back_button').click(function () {
			location.href = '/vehicle_management/fleet-communications/{{ $maintenance->id }}';
		});
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
			 //Initialize iCheck/iRadio Elements
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green',
				increaseArea: '20%' // optional
			});
			//call hide/show fields functions
			hideFields();
			$('#rdo_email, #rdo_sms').on('ifChecked', function(){
				hideFields();
			});
			$('#rdo_clients, #rdo_employees').on('ifChecked', function(){
				hideFields();
			});
        });
		//function to hide/show fields depending on the registration type
	function hideFields() {
		var messageType = $("input[name='message_type']:checked").val();
		if (messageType == 1) { //email
			$('.sms-field').hide();
			$('.email-field').show();
		}
		else if (messageType == 2) { //sms
			$('.email-field').hide();
			$('.sms-field').show();
		}
		
		var sendType = $("input[name='send_type']:checked").val();
		if (sendType == 1) { //clients
			$('.send-employee').hide();
			$('.send-clients').show();
		}
		else if (sendType == 2) { //employee
			$('.send-clients').hide();
			$('.send-employee').show();
		}
	}
	
    </script>
@endsection