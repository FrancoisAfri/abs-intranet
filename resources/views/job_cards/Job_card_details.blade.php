@extends('layouts.main_layout')
@section('page_dependencies')
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- year picker -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css"
          rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> </head>
@endsection
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h4 class="box-title"></h4>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <div align="center" class="box box-default">
                    
                    <div class="box-body">
                        <table class="table table-striped table-bordered">
                            @foreach ($jobcards as $jobcard)
                             <!--  -->
                                <tr>
                                    <td class="caption">Fleet Number</td>
                                    <td>{{ !empty($jobcard->fleet_number) ? $jobcard->fleet_number : ''}}</td>
                                    <td class="caption">Job Card Number</td>
                                    <td>{{ !empty($jobcard->jobcard_number) ? $jobcard->jobcard_number : ''}}</td>
                                </tr>
                                <tr>

                                    <td class="caption">vehicle registration Number</td>
                                    <td>{{ !empty($jobcard->vehicle_registration) ? $jobcard->vehicle_registration : ''}}</td>
                                    <td class="caption">Job Card Date</td>
                                    <td>{{ !empty($jobcard->card_date) ? date(' d M Y', $jobcard->card_date) : ''}}</td>
                                </tr>
                                <tr>
                                    <td class="caption" width="25%">Make</td>
                                    <td width="25%">{{ !empty($jobcard->vehicle_make) ? $jobcard->vehicle_make : ''}}</td>
                                    <td class="caption">Job Card Status</td>
                                    <td>{{ !empty($jobcard->vehicle_type) ? $jobcard->vehicle_type : ''}}</td>
                                </tr>
                                <tr>
                                    <td class="caption" width="25%">Model</td>
                                    <td width="25%">{{ !empty($jobcard->vehicle_model) ? $jobcard->vehicle_model : ''}}</td>
                                    <td class="caption">Service File Attachment</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="caption">Vehicle Description </td>
                                    <td>{{ !empty($jobcard->instruction) ? $jobcard->instruction : ''}}</td>
                                    <td class="caption">Driver</td>
                                    <td>{{ !empty($jobcard->last_driver_id) ? $jobcard->last_driver_id : ''}}</td>
                                </tr>
                                <tr>
                                    <td class="caption">Current Odometer </td>
                                    <td>{{ !empty($jobcard->odometer_reading) ? $jobcard->odometer_reading : ''}}</td>
                                    <td class="caption">Mechanic</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="caption">Current Hours </td>
                                    <td>{{ !empty($jobcard->odometer_reading) ? $jobcard->odometer_reading : ''}}</td>
                                    <td class="caption">Inspection List Number</td>
                                    <td>{{ !empty($jobcard->cell_number) ? $jobcard->cell_number : ''}}</td>
                                </tr>
                               
                                <tr>
                                    <td class="caption">Hours Allocated</td>
                                    <td>{{ !empty($jobcard->hours_reading) ? $jobcard->hours_reading : ''}}</td>
                                    <td class="caption">Completion Date</td>
                                    <td>{{ !empty($jobcard->cell_number) ? $jobcard->cell_number : ''}}</td>
                                </tr>
                             
                                <tr>
                                    
                                    <td class="caption">Service Time</td>
                                    <td> </td>
                                    <td class="caption"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="caption">Service Type</td>
                                    <td></td>
                                    <td class="caption">Servicing Agent</td>
                                    <td></td>
                                </tr>
                                 <tr>
                                    <td class="caption">Purchase Order Number</td>
                                    <td>{{ !empty($jobcard->size_of_fuel_tank) ? $jobcard->size_of_fuel_tank : ''}}</td>
                                    <td class="caption">Service Date</td>
                                    <td></td>
                                </tr>
                                <tr><td colspan="4" class="caption">Job Card Instructions</td></tr>
                                     <tr>
				         <td colspan="4" style="text-align:left;" height="40" border="1"></td>
				     </tr>
                                <tr>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-body" align="center">
                <button vehice="button" id="edit_compan" class="btn btn-sm btn-default btn-flat" data-toggle="modal"
                            data-target="#edit-jobcard-modal" data-id="{{ $jobcard->id }}"
                            data-status="{{$jobcard->status}}"
                            data-division_level_5="{{$jobcard->division_level_5 }}"
                            data-division_level_4="{{ $jobcard->division_level_4 }}"
                            data-division_level_3="{{ $jobcard->division_level_3 }}"
                            data-division_level_2="{{ $jobcard->division_level_2 }}"
                            data-division_level_1="{{ $jobcard->division_level_1 }}"
                            data-responsible_for_maintenance="{{ $jobcard->responsible_for_maintenance}}"
                            data-vehicle_make="{{ $jobcard->vehicle_make}}"
                            data-responsible_for_maintenance="{{$jobcard->responsible_for_maintenance}}"
                            data-vehicle_model="{{$jobcard->vehicle_model}}"
                            data-vehicle_type="{{$jobcard->vehicle_type}}"
                            data-year="{{$jobcard->year}}"
                            data-vehicle_registration="{{$jobcard->vehicle_registration}}"
                            data-chassis_number="{{$jobcard->chassis_number}}"
                            data-engine_number="{{$jobcard->engine_number}}"
                            data-vehicle_color="{{$jobcard->vehicle_color}}"
                            data-metre_reading_type="{{$jobcard->metre_reading_type}}"
                            data-odometer_reading="{{$jobcard->odometer_reading}}"
                            data-hours_reading="{{$jobcard->hours_reading}}"
                            data-fuel_type="{{$jobcard->fuel_type}}"
                            data-size_of_fuel_tank="{{$jobcard->size_of_fuel_tank}}"
                            data-fleet_number="{{$jobcard->fleet_number}}"
                            data-cell_number="{{$jobcard->cell_number}}"
                            data-tracking_umber="{{$jobcard->tracking_umber}}"
                            data-vehicle_owner="{{$jobcard->vehicle_owner}}"
                            data-title_type="{{$jobcard->title_type}}"
                            data-financial_institution="{{$jobcard->financial_institution}}"
                            data-extras="{{ $jobcard->extras }}"
                            data-property_type="{{ $jobcard->property_type }}"
                            data-company="{{ $jobcard->company }}"

                    ><i class="fa fa-pencil-square-o"></i> Edit
                    </button>

                    <a href="{{ '/vehicle_management/viewImage/' . $jobcard->vehicle_id }}"
                       id="edit_compan" class="btn btn-sm btn-default btn-flat"
                       target="_blank">Images</a>

                       <a href="{{ '/jobcards/jobcardnotes/' . $card->id }}"
                       id="edit_compan" class="btn btn-sm btn-default btn-flat"
                       target="_blank">Job Card Notes</a>
                   
                   <a href="{{ '/jobcard/parts/' . $card->id }}"
                       id="edit_compan" class="btn btn-sm btn-default btn-flat"
                       data-id="{{ $jobcard->id }}">Parts</a>

                    <a href="{{ '/jobcards/parts/' . $jobcard->id }}"
                       id="edit_compan" class="btn btn-sm btn-default btn-flat"
                       data-id="{{ $jobcard->id }}">Print</a>
					   <a href="{{ '/vehicle_management/notes/' . $jobcard->id }}"
                       id="edit_compan" class="btn btn-sm btn-default btn-flat"
                       data-id="{{ $jobcard->id }}">Conclude Jobcard</a>
                    <!--
                    <a href="{{ '/jobcard/cancellation/' . $card->id }}"
                       id="edit_compan" class="btn btn-sm btn-default btn-flat"
                       data-id="{{ $jobcard->id }}">Request Cancellation</a>

                    <a href="{{ '/vehicle_management/reminders/' . $jobcard->id }}"
                       id="edit_compan" class="btn btn-sm btn-default btn-flat"
                       data-id="{{ $jobcard->id }}">Create Request</a>
                       -->
                    <button type="button" id="cancel" class="btn-sm btn-default btn-flat pull-left"><i
                                class="fa fa-arrow-left"></i> Back
                    </button>
                </div>
                @endforeach
            </div>
        </div>
		@include('job_cards.partials.edit_jobcard_modal ')
    </div>
@endsection
@section('page_script')
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

<script src="/custom_components/js/modal_ajax_submit.js"></script>

<!-- Ajax dropdown options load -->
<script src="/custom_components/js/load_dropdown_options.js"></script>

<script>
	$('#cancel').click(function () {
		location.href = '/jobcards/search';
	});

	$(function () {
	
	
//        if($maintenance->metre_reading_type == 1)
//            $('.odometer-field').show();
//        }else  $('.odometer-field').show();
		$(".select2").select2();
		$('.hours-field').hide();
		$('.comp-field').hide();
		var moduleId;
		//Tooltip
		$('[data-toggle="tooltip"]').tooltip();
		

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

		//
		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy',
			autoclose: true,
			todayHighlight: true
		});

		//Initialize iCheck/iRadio Elements
		$('input').iCheck({
			checkboxClass: 'icheckbox_square-blue',
			radioClass: 'iradio_square-blue',
			increaseArea: '10%' // optional
		});

		$(document).ready(function () {

			$('#year').datepicker({
				minViewMode: 'years',
				autoclose: true,
				format: 'yyyy'
			});

		});

		$('#rdo_package, #rdo_product').on('ifChecked', function () {
			var allType = hideFields();
			if (allType == 1) $('#box-subtitle').html('Site Address');
			else if (allType == 2) $('#box-subtitle').html('Temo Site Address');
		});

		//

		$('#rdo_fin, #rdo_comp').on('ifChecked', function () {
			var allType = hidenFields();
			if (allType == 1) $('#box-subtitle').html('Site Address');
			else if (allType == 2) $('#box-subtitle').html('Temo Site Address');
		});


		function hideFields() {
			var allType = $("input[name='promotion_type']:checked").val();
			if (allType == 1) {
				$('.hours-field').hide();
				$('.odometer-field').show();
			}
			else if (allType == 2) {
				$('.odometer-field').hide();
				$('.hours-field').show();
			}
			return allType;
		}

		//
		function hidenFields() {
			var allType = $("input[name='title_type']:checked").val();
			if (allType == 1) {
				$('.comp-field').hide();
				$('.fin-field').show();
			}
			else if (allType == 2) {
				$('.fin-field').hide();
				$('.comp-field').show();
			}
			return allType;
		}

		$('#add_notes').on('click', function () {
				var strUrl = '/jobcards/addjobcardnotes';
				var formName = 'add-note-form';
				var modalID = 'add-note-modal';
				var submitBtnID = 'add_notes';
				var redirectUrl = '/jobcards/viewcard/{{$card->id}}';
				var successMsgTitle = 'New Record Added!';
				var successMsg = 'The Record  has been updated successfully.';
				modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
			});

	});
</script>
@endsection