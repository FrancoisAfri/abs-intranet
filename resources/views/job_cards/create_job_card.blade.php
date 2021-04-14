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
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/green.css">
@endsection
@section('content')
    <div class="row">
		<div class="col-md-12">
			<div class="box box-warning">
				<div class="box-header with-border">
					<h3 class="box-title"> Create Job Card </h3>
				</div>
			<div class="box-body">	
				<!-- Search JC Form -->
				<div class="col-md-12">
					<!-- Horizontal Form -->
					<div class="box box-primary">
						<div class="box-header with-border">
							<i class="fa fa-search pull-right"></i>
							<h3 class="box-title">Fill In The Form:</h3>
						</div>
						<!-- /.box-header -->
						<!-- form start -->
						<form class="form-horizontal" method="POST" action="/jobcards/addjobcard">
							<input type="hidden" name="file_index" id="file_index" value="1"/>
							<input type="hidden" name="total_files" id="total_files" value="1"/>
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
								<div class="form-group {{ $errors->has('vehicle_id') ? ' has-error' : '' }}">
									<label for="vehicle_id" class="col-sm-2 control-label">Vehicle</label>
									<div class="col-sm-10">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-truck"></i>
											</div>
											<select name="vehicle_id" id="vehicle_id" class="form-control select2" style="width: 100%;"
											 data-placeholder="*** Select a Vehicle ***">
												@foreach($vehicledetails as $details)
													<option value="{{ $details->id }}">{{ $details->fleet_number . ' ' .  $details->vehicle_registration . ' ' . $details->vehicle_make . ' ' . $details->vehicle_model }}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="form-group{{ $errors->has('card_date') ? ' has-error' : '' }}">
									<label for="card_date" class="col-sm-2 control-label">Job card Date</label>
									<div class="col-sm-10">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input type="text" class="form-control datepicker" id="card_date" name="card_date"
												value="{{ date('d/m/Y', $current_date)}}" placeholder="Select start date  ...">
										</div>
									</div>
								</div>
								<div class="form-group{{ $errors->has('schedule_date') ? ' has-error' : '' }}">
									<label for="schedule_date" class="col-sm-2 control-label">Schedule Date</label>
									<div class="col-sm-10">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
												<input type="text" class="form-control datepicker" id="schedule_date" name="schedule_date"
												value="{{ old('schedule_date')}}" placeholder="Select Schedule date  ...">
										</div>
									</div>
								</div>
								<div class="form-group {{ $errors->has('booking_type') ? ' has-error' : '' }}">
									<label for="booking_type" class="col-sm-2 control-label">Service by Agent</label>
									<div class="col-sm-10">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-check-square-o"></i>
											</div>
											<input type="checkbox" id="booking_type" value="1" name="booking_type"
												onclick="showHide();">
										</div>
									</div>
								</div>
								<div class="form-group  agent_field {{ $errors->has('booking_date') ? ' has-error' : '' }}">
									<label for="booking_date" class="col-sm-2 control-label">Booking Date</label>
									<div class="col-sm-10">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input type="text" class="form-control" id="booking_date" name="booking_date"
												value="{{ old('booking_date') }}" placeholder="Select date  ...">
										</div>
									</div>
								</div>
								<div class="form-group  agent_field {{ $errors->has('email_content') ? ' has-error' : '' }}">
									<label for="email_content" class="col-sm-2 control-label">Supplier</label>
									<div class="col-sm-10">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-check"></i>
											</div>
												<select id="supplier_id" name="supplier_id" class="form-control">
													<option value="0">*** Select a Supplier ***</option>
													@foreach($ContactCompany as $details)
														<option value="{{ $details->id }}">{{ $details->name}}</option>
													@endforeach
												</select>
										</div>
									</div>
								</div>
								<div class="form-group {{ $errors->has('service_type') ? ' has-error' : '' }}">
									<label for="service_type" class="col-sm-2 control-label">Service Type</label>
									<div class="col-sm-10">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-table"></i>
											</div>
											<select id="service_type" name="service_type" class="form-control">
												<option value=" ">*** Select a Service type ***</option>
												@foreach($servicetype as $details)
													<option value="{{ $details->id }}">{{ $details->name}}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="form-group {{ $errors->has('estimated_hours') ? ' has-error' : '' }}">
									<label for="estimated_hours" class="col-sm-2 control-label">Estimated Hours</label>
									<div class="col-sm-10">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-clock"></i>
											</div>
											<input type="number" class="form-control" id="estimated_hours" name="estimated_hours"
											   value="" placeholder="Enter Estimated Hours">
										</div>
									</div>
								</div>
								<div class="form-group {{ $errors->has('machine_hour_metre') ? ' has-error' : '' }}">
									<label for="machine_hour_metre" class="col-sm-2 control-label">Machine Hour Metre</label>
									<div class="col-sm-10">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-clock"></i>
											</div>
											<input type="number" class="form-control" id="machine_hour_metre" name="machine_hour_metre"
											   value="" placeholder="Enter Machine Hour Metre">
										</div>
									</div>
								</div>
								<div class="form-group {{ $errors->has('machine_odometer') ? ' has-error' : '' }}">
									<label for="machine_odometer" class="col-sm-2 control-label">Machine Odometer</label>
									<div class="col-sm-10">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-clock"></i>
											</div>
											<input type="number" class="form-control" id="machine_odometer" name="machine_odometer"
											   value="" placeholder="Enter Machine Odometer">
										</div>
									</div>
								</div>
								<div class="form-group {{ $errors->has('last_driver_id') ? ' has-error' : '' }}">
									<label for="last_driver_id" class="col-sm-2 control-label">Driver</label>
									<div class="col-sm-10">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-users"></i>
											</div>
											<select id="last_driver_id" name="last_driver_id" class="form-control">
												<option value="0">*** Select a driver ***</option>
												@foreach($drivers as $driver)
													<option value="{{ $driver->id }}">{{$driver->first_name . ' ' .  $driver->surname }}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="form-group mechanic_row{{ $errors->has('mechanic_id') ? ' has-error' : '' }}">
									<label for="mechanic_id" class="col-sm-2 control-label">Mechanic</label>
									<div class="col-sm-10">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-users"></i>
											</div>
											<select id="mechanic_id" name="mechanic_id" class="form-control">
												<option value="0">*** Select a mechanic ***</option>
												@foreach($mechanics as $mechanic)
													<option value="{{ $mechanic->id }}">{{ $mechanic->first_name . ' ' .  $mechanic->surname}}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="form-group mechanic_row {{ $errors->has('communication_type') ? ' has-error' : '' }}">
									<label for="communication_type" class="col-sm-2 control-label">Communication Type</label>
									<div class="col-sm-10">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-comments-o"></i>
											</div>
											<label class="radio-inline"><input type="radio" id="rdo_communication" name="communication_type" value="1">
												Email </label>
											<label class="radio-inline"><input type="radio" id="rdo_communication" name="communication_type" value="2">
												SMS</label>
											 <label class="radio-inline"><input type="radio" id="rdo_communication" name="communication_type" checked value="3">
												Both </label>
										</div>
									</div>
								</div>
								<div id="tab_10">
									<hr class="hr-text" data-content="Instructions">
									<div class="row" id="tab_tab">
										<div class="col-sm-12" style="display:none;" id="instructions_row">
											<textarea class="form-control" id="instruction" name="instruction"
												placeholder="Enter a inspection Info" rows="3" disabled="disabled"></textarea>
										</div>
										<div class="col-sm-12" id="1" name="1" style="margin-bottom: 15px;">
											<textarea class="form-control" id="instruction[1]" name="instruction[1]"
												placeholder="Enter Instruction" rows="3"></textarea>
										</div>
									</div>
									<div class="row" id="final_row">
										<div class="col-sm-12">
											<button type="button" class="btn btn-default btn-block btn-flat add_more" onclick="addFile()">
												<i class="fa fa-clone"></i> Add More
											</button>
										</div>
									</div>
								</div>
							</div>
							<!-- /.box-body -->
							<div class="box-footer">
								<button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
								<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-paper-plane-o"></i> Submit</button>
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
	<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
	
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

	<!-- InputMask -->
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
	<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <script type="text/javascript">
		$('#back_button').click(function () {
			location.href = '/jobcards/mycards';
		});
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
			// call hide agent_field
			$('.agent_field').hide();
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green',
				increaseArea: '20%' // optional
			});
			$('#external_service').on('ifChecked', function(event){
				$('.agent_field').show();
				$('#mechanic_id').val('');
				$('.mechanic_row').hide();
			});
			$('#external_service').on('ifUnchecked', function(event){
				$('.agent_field').hide();
				$('.mechanic_row').show();
			});
        });

		function clone(id, file_index, child_id) {
			var clone = document.getElementById(id).cloneNode(true);
			clone.setAttribute("id", file_index);
			clone.setAttribute("name", file_index);
			clone.style.display = "table-row";
			clone.querySelector('#' + child_id).setAttribute("name", child_id + '[' + file_index + ']');
			clone.querySelector('#' + child_id).disabled = false;
			clone.querySelector('#' + child_id).setAttribute("id", child_id + '[' + file_index + ']');
			return clone;
		}
		function addFile() {
			var table = document.getElementById("tab_tab");
			var file_index = document.getElementById("file_index");
			file_index.value = ++file_index.value;
			var instruction_clone = clone("instructions_row", file_index.value, "instruction");
			var final_row = document.getElementById("final_row").cloneNode(false);
			table.appendChild(instruction_clone);
			table.appendChild(final_row);
			var total_files = document.getElementById("total_files");
			total_files.value = ++total_files.value;
			//change the following using jquery if necessary
			var remove = document.getElementsByName("remove");
			for (var i = 0; i < remove.length; i++)
				remove[i].style.display = "inline";
		}
		
		function removeFile(row_name)
		{
			var row=row_name.parentNode.parentNode.id;
			var rows=document.getElementsByName(row);
			while(rows.length>0)
				rows[0].parentNode.removeChild(rows[0]);
			var total_files = document.getElementById("total_files");
			total_files.value=--total_files.value;
			var remove=document.getElementsByName("remove");
			if(total_files.value == 1)
				remove[1].style.display='none';
		}
		
		$(document).ready(function () {

			$('#card_date').datepicker({
				format: 'dd/mm/yyyy',
				autoclose: true,
				todayHighlight: true
			});
			$('#schedule_date').datepicker({
				format: 'dd/mm/yyyy',
				autoclose: true,
				todayHighlight: true
			});
			$('#booking_date').datepicker({
				format: 'dd/mm/yyyy',
				autoclose: true,
				todayHighlight: true
			});
		});
    </script>
@endsection