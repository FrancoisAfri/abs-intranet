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
					<h3 class="box-title"> Edit Fuel Record </h3>
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
						<form class="form-horizontal" method="POST" action="/vehicle_management/update_fuel_record/{{$fuel->id}}">
							{{ csrf_field() }}
							{{ method_field('PATCH') }}
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
								<div class="form-group {{ $errors->has('driver') ? ' has-error' : '' }}">
									<label for="driver" class="col-sm-2 control-label">Driver</label>
									<div class="col-sm-10">
										<div class="input-group">
											<select name="driver" id="driver" class="form-control select2" style="width: 100%;"
											 data-placeholder="*** Select a Driver ***">
												@foreach($employees as $driver)
													<option value="{{ $driver->id }}" {{ ($fuel->driver == $driver->id) ? ' selected' : '' }}>{{ $driver->first_name . ' ' . $driver->surname }}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="form-group{{ $errors->has('document_number') ? ' has-error' : '' }}">
									<label for="document_number" class="col-sm-2 control-label">Document Number</label>
									<div class="col-sm-10">
										<div class="input-group">
											<input type="text" class="form-control" id="document_number" name="document_number"
												value="{{$fuel->document_number }}" placeholder="Enter Document Number...">
										</div>
									</div>
								</div>
								<div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
									<label for="date" class="col-sm-2 control-label">Date</label>
									<div class="col-sm-10">
										<div class="input-group">
												<input type="text" class="form-control datepicker" id="date" name="date"
												value="{{ date(' d M Y', $fuel->date) }}" placeholder="Select Date  ...">
										</div>
									</div>
								</div>
								<div class="form-group{{ $errors->has('tank_and_other') ? ' has-error' : '' }}">
									<label for="tank_and_other" class="col-sm-2 control-label"> Tanks and Other </label>
									<div class="col-sm-8">
										<label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_transaction"
												name="tank_and_other" value="1" {{ ($fuel->tank_and_other == 1) ? ' checked' : '' }} >Tank
									 </label>
										 <label class="radio-inline"><input type="radio" id="rdo_Other" name="tank_and_other" value="2" {{ ($fuel->tank_and_other == 2) ? ' checked' : '' }}>
												Other
										</label>
									</div>
								</div>
								<div class="form-group transaction-field{{ $errors->has('transaction_type') ? ' has-error' : '' }}">
									<label for="transaction_type" class="col-sm-2 control-label"> Transaction Type </label>
									<div class="col-sm-8">
										<label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_fulltank"
																									  name="transaction_type" value="1" {{ ($fuel->transaction_type == 1) ? ' checked' : '' }}>Full Tank
										</label>
										<label class="radio-inline"><input type="radio" id="rdo_topup" name="transaction_type" value="2" {{ ($fuel->transaction_type == 2) ? ' checked' : '' }}>
											Top Up
										</label>
									</div>
								</div>
								<div class="form-group Tanks-field">
									<label for="tank_name" class="col-sm-2 control-label">Tanks </label>
									<div class="col-sm-8">
										<select class="form-control select2" style="width: 100%;"
												id="tank_name" name="tank_name">
											<option value="0">*** Select tank  ***</option>
											@foreach($fueltank as $tank)
											<option value="{{ $tank->id }}"  {{ ($fuel->tank_name == $tank->id) ? ' selected' : '' }}>{{ $tank->name }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="form-group  transaction-field">
									<label for="service_station" class="col-sm-2 control-label">Service Station </label>
									<div class="col-sm-8">
										<select class="form-control select2" style="width: 100%;"
												id="service_station" name="service_station">
											<option value="0">*** Service Station  ***</option>
											@foreach($servicestation as $station)
												<option value="{{ $station->id }}" {{ ($fuel->service_station == $station->id) ? ' selected' : '' }}>{{ $station->name }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="form-group">
									<label for="litres_new" class="col-sm-2 control-label">Litres </label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="litres_new" name="litres_new" value="{{$fuel->litres_new }}"
											   placeholder="Enter Litres">
									</div>
								</div>
								<div class="form-group  transaction-field">
									<label for="cost_per_litre" class="col-sm-2 control-label">Cost per Litre </label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="cost_per_litre" name="cost_per_litre" value="{{$fuel->cost_per_litre }}"
											   placeholder="Enter Litres">
									</div>
								</div>
								<div class="form-group  transaction-field">
									<label for="total_cost" class="col-sm-2 control-label">Total Cost</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="total_cost" name="total_cost" value="{{$fuel->total_cost }}"
											   placeholder="Enter Litres">
									</div>
								</div>
								@if (isset($metreType) && $metreType== 1)
									<div class="form-group">
										<label for="Odometer_reading" class="col-sm-2 control-label">Km Reading </label>
										<div class="col-sm-8">
											<input type="text" class="form-control" id="Odometer_reading" name="Odometer_reading" value="{{$fuel->Odometer_reading }}"
												   placeholder="Enter Odometer reading Reading">
										</div>
									</div>
								@else
									<div class="form-group">
										<label for="hours_reading" class="col-sm-2 control-label">Hour Reading</label>
										<div class="col-sm-8">
											<input type="text" class="form-control" id="hours_reading" name="hours_reading" value="{{$fuel->hours_reading }}"
												   placeholder="Enter Hours Reading">
										</div>
									</div>
								@endif
								<div class="form-group ">
									<label for="description" class="col-sm-2 control-label">Description</label>
									<div class="col-sm-8">

										<textarea class="form-control" id="description" name="description"
												  placeholder="Enter Description..." rows="3">{{$fuel->description }}</textarea>
									</div>
								</div>	
							</div>
							<!-- /.box-body -->
							<div class="box-footer">
								<button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
								<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-paper-plane-o"></i> Update</button>
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
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <script type="text/javascript">
		$('#back_button').click(function () {
			location.href = '/vehicle_management/fuel_log/{{ $fuel->vehicleID }}';
		});
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
			if ({{$fuel->tank_and_other}} == 1)
			{
				$('.Tanks-field').show();
				$('.transaction-field').hide();
			}
			else
			{
				$('.Tanks-field').hide();
				$('.transaction-field').show();
			}
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-blue',
				radioClass: 'iradio_square-blue',
				increaseArea: '20%' // optional
			});
			
        });
		$('#rdo_transaction, #rdo_Other').on('ifChecked', function () {
			var allType = hideFields();
		});
		function hideFields() {
			var allType = $("input[name='tank_and_other']:checked").val();
			if (allType == 1) {
				$('.transaction-field').hide();
				$('.Tanks-field').show();
			}
			else if (allType == 2) {
				$('.transaction-field').show();
				$('.Tanks-field').hide();
			}
			return allType;
		}
		$(document).ready(function () {
			$('#date').datepicker({
				format: 'dd/mm/yyyy',
				autoclose: true,
				todayHighlight: true
			});
		});
		$(document).ready(function () {
			$('#litres_new').change(function () {
				var litres_new = $('#litres_new').val();
				var total_cost = $('#total_cost').val();
				var litre_cost = $('#cost_per_litre').val();

				if (litre_cost > 0 && litres_new > 0) {
					var total_cost = (litres_new * litre_cost).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
					document.getElementById('total_cost').value = total_cost;
				}
				else if (litres_new > 0 && total_cost > 0) {
					var litre_cost = (total_cost / litres_new).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
					document.getElementById('cost_per_litre').value = litre_cost;
				}
			});
			$('#cost_per_litre').change(function () {
				var litres_new = $('#litres_new').val();
				var total_cost = $('#total_cost').val();
				var litre_cost = $('#cost_per_litre').val();
				if (litre_cost > 0 && litres_new > 0) {
					var total_cost = (litres_new * litre_cost).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
					document.getElementById('total_cost').value = total_cost;
				}
				else if (litre_cost > 0 && total_cost > 0) {
					var litres_new = (total_cost / litre_cost).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
					document.getElementById('litres_new').value = litres_new;
				}
			});
			$('#total_cost').change(function () {
				var litres_new = $('#litres_new').val();
				var total_cost = $('#total_cost').val();
				var litre_cost = $('#cost_per_litre').val();
				if (litre_cost > 0 && total_cost) {
					var litres_new = (total_cost / litre_cost).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
					document.getElementById('litres_new').value = litres_new;
				}
				else if (litres_new > 0 && total_cost) {
					var litre_cost = (total_cost / litres_new).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
					document.getElementById('cost_per_litre').value = litre_cost;
				}
			});
		});
    </script>
@endsection