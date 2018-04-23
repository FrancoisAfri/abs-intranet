@extends('layouts.main_layout')

@section('page_dependencies')
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- Include Date Range Picker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
    <!--Time Charger-->
    <!--  -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css"
          rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                </div>
                <form class="form-horizontal" method="POST" action="/vehicle_management/fleet_card_search">
                    {{ csrf_field() }}

                    <div class="box-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                                </button>
                                <h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="col-md-8 col-md-offset-2">
                            <div>
                                <div class="box-header with-border" align="center">
                                    <h3 class="box-title">Fleet Card Search</h3>
                                </div>
                                <div class="box-body" id="vehicle_details">

                                    <div class="form-group">
                                        <label for="path" class="col-sm-2 control-label">Card Type</label>
                                        <div class="col-sm-10">

                                            <select class="form-control select2" style="width: 100%;"
                                                    id="card_type_id" name="card_type_id">
                                                <option value="">*** Select a Card Type ***</option>
                                                @foreach($fleetcardtype as $card)
                                                    <option value="{{ $card->id }}">{{ $card->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="path" class="col-sm-2 control-label">Vehicle Fleet Number</label>
                                        <div class="col-sm-10">
                                            <select class="form-control select2" style="width: 100%;"
                                                    id="fleet_number" name="fleet_number">
                                                <option value="">*** Select a Vehicle ***</option>
                                                @foreach($vehicle_detail as $Fleet)
                                                    <option value="{{ $Fleet->fleet_number }}">{{ $Fleet->fleet_number }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="path" class="col-sm-2 control-label">Issued By</label>
                                        <div class="col-sm-10">
                                            <select class="form-control select2" style="width: 100%;"
                                                    id="company_id" name="company_id">
                                                <option value="">*** Select a Company ***</option>
                                                @foreach($contactcompanies as $Company)
                                                    <option value="{{ $Company->id }}">{{ $Company->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="path" class="col-sm-2 control-label"> Card Holder</label>
                                        <div class="col-sm-10">
                                            <select class="form-control select2" style="width: 100%;"
                                                    id="holder_id" name="holder_id">
                                                <option value="">*** Select an Employee ***</option>
                                                @foreach($hrDetails as $user)
                                                    <option value="{{ $user->id }}">{{ $user->first_name . ' ' .  $user->surname}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                                        <label for="status" class="col-sm-2 control-label"> Status
                                        </label>

                                        <div class="col-sm-10">
                                            <label class="radio-inline" style="padding-left: 0px;"><input type="radio"
                                             id="rdo_package" name="status" value="1" checked> Active
                                            </label>
                                            <label class="radio-inline"><input type="radio" id="rdo_product"
                                                                               name="status" value="2"> Inactive
                                            </label>
                                            <label class="radio-inline"><input type="radio" id="rdo_products"
                                                                               name="status" value=""> All
                                            </label>

                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary pull-left"><i
                                                    class="fa fa-search"></i> Search
                                        </button>
                                        <button type="button" id="cat_module" class="btn btn-primary pull-right"
                                                data-toggle="modal" data-target="#add-fleetcard-modal"><i
                                                    class="fa fa-plus-square-o"></i> Add Fleet Card
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                </form>
            </div>
            <!-- /.box -->
            @include('Vehicles.Fleet_cards.add_vehiclefleetcard_modal')
        </div>
    </div>
@endsection

@section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- bootstrap datepicker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
            type="text/javascript"></script>
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->

    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>

    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <!-- time picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript">
	$(function () {
		$(".select2").select2();
		//Tooltip
		$('[data-toggle="tooltip"]').tooltip();

		//Vertically center modals on page

		//Phone mask
		$("[data-mask]").inputmask();

		//Vertically center modals on page
		function reposition() {
			var modal = $(this),
				dialog = modal.find('.modal-dialog');
			modal.css('display', 'block');

			// Dividing by two centers the modal exactly, but dividing by three
			// or four works better for larger screens.
			dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
		}

		// Reposition when a modal is shown
		$('.modal').on('show.bs.modal', reposition);
		// Reposition when the window is resized
		$(window).on('resize', function () {
			$('.modal:visible').each(reposition);
		});

		//Show success action modal
		$('#success-action-modal').modal('show');
	});

	//Initialize iCheck/iRadio Elements
	$('input').iCheck({
		checkboxClass: 'icheckbox_square-blue',
		radioClass: 'iradio_square-blue',
		increaseArea: '10%' // optional
	});

	$(document).ready(function () {

		$('#issued_date').datepicker({
			format: 'dd/mm/yyyy',
			autoclose: true,
			todayHighlight: true
		});


	});

	$('#expiry_date').datepicker({
		format: 'dd/mm/yyyy',
		autoclose: true,
		todayHighlight: true
	});

	//Post module form to server using ajax (ADD)
   $('#add-fleet-card').on('click', function () {
		var strUrl = '/vehicle_management/add_vehiclefleetcard';
		var modalID = 'add-fleetcard-modal';
		var objData = {
			card_type_id: $('#'+modalID).find('#card_type_id').val(),
			fleet_number: $('#'+modalID).find('#fleet_number').val(),
			company_id: $('#'+modalID).find('#company_id').val(),
			holder_id: $('#'+modalID).find('#holder_id').val(),
			card_number: $('#'+modalID).find('#card_number').val(),
			cvs_number: $('#'+modalID).find('#cvs_number').val(),
			issued_date: $('#'+modalID).find('#issued_date').val(),
			expiry_date: $('#'+modalID).find('#expiry_date').val(),
			status: $('#'+modalID).find('input:checked[name = status]').val(),
			_token: $('#'+modalID).find('input[name=_token]').val()
		};
		var submitBtnID = 'add-fleet-card';
		var redirectUrl = '/vehicle_management/fleet_cards';
		var successMsgTitle = 'New Record Added!';
		var successMsg = 'The Record  has been updated successfully.';
		modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
	});
    </script>
@endsection
