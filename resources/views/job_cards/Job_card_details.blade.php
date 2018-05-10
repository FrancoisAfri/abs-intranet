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
                             @foreach ($vehiclemaintenance as $vehiclemaintenance)
                             <!--  -->
                                <tr>
                                    <td class="caption">Fleet Number</td>
                                    <td>{{ !empty($vehiclemaintenance->fleet_number) ? $vehiclemaintenance->fleet_number : ''}}</td>
                                    <td class="caption">Job Card Number</td>
                                    <td>{{ !empty($vehiclemaintenance->jobcard_number) ? $vehiclemaintenance->jobcard_number : ''}}</td>
                                </tr>
                                <tr>

                                    <td class="caption">vehicle registration Number</td>
                                    <td>{{ !empty($vehiclemaintenance->vehicle_registration) ? $vehiclemaintenance->vehicle_registration : ''}}</td>
                                    <td class="caption">Job Card Date</td>
                                    <td>{{ !empty($vehiclemaintenance->card_date) ? date(' d M Y', $vehiclemaintenance->card_date) : ''}}</td>
                                </tr>
                                <tr>
                                    <td class="caption" width="25%">Make</td>
                                    <td width="25%">{{ !empty($vehiclemaintenance->vehicle_make) ? $vehiclemaintenance->vehicle_make : ''}}</td>
                                    <td class="caption">Job Card Status</td>
                                    <td>{{ !empty($vehiclemaintenance->vehicle_type) ? $vehiclemaintenance->vehicle_type : ''}}</td>
                                </tr>
                                <tr>
                                    <td class="caption" width="25%">Model</td>
                                    <td width="25%">{{ !empty($vehiclemaintenance->vehicle_model) ? $vehiclemaintenance->vehicle_model : ''}}</td>
                                    <td class="caption">Service File Attachment</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="caption">Vehicle Description </td>
                                    <td>{{ !empty($vehiclemaintenance->instruction) ? $vehiclemaintenance->instruction : ''}}</td>
                                    <td class="caption">Driver</td>
                                    <td>{{ !empty($vehiclemaintenance->last_driver_id) ? $vehiclemaintenance->last_driver_id : ''}}</td>
                                </tr>
                                <tr>
                                    <td class="caption">Current Odometer </td>
                                    <td>{{ !empty($vehiclemaintenance->odometer_reading) ? $vehiclemaintenance->odometer_reading : ''}}</td>
                                    <td class="caption">Mechanic</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="caption">Current Hours </td>
                                    <td>{{ !empty($vehiclemaintenance->odometer_reading) ? $vehiclemaintenance->odometer_reading : ''}}</td>
                                    <td class="caption">Inspection List Number</td>
                                    <td>{{ !empty($vehiclemaintenance->cell_number) ? $vehiclemaintenance->cell_number : ''}}</td>
                                </tr>
                               
                                <tr>
                                    <td class="caption">Hours Allocated</td>
                                    <td>{{ !empty($vehiclemaintenance->hours_reading) ? $vehiclemaintenance->hours_reading : ''}}</td>
                                    <td class="caption">Completion Date</td>
                                    <td>{{ !empty($vehiclemaintenance->cell_number) ? $vehiclemaintenance->cell_number : ''}}</td>
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
                                    <td>{{ !empty($vehiclemaintenance->size_of_fuel_tank) ? $vehiclemaintenance->size_of_fuel_tank : ''}}</td>
                                    <td class="caption">Service Date</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="caption">Job Card Instructions</td></tr>
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
                            data-target="#edit-vehicledetails-modal" data-id="{{ $vehiclemaintenance->id }}"
                            data-status="{{$vehiclemaintenance->status}}"
                            data-division_level_5="{{$vehiclemaintenance->division_level_5 }}"
                            data-division_level_4="{{ $vehiclemaintenance->division_level_4 }}"
                            data-division_level_3="{{ $vehiclemaintenance->division_level_3 }}"
                            data-division_level_2="{{ $vehiclemaintenance->division_level_2 }}"
                            data-division_level_1="{{ $vehiclemaintenance->division_level_1 }}"
                            data-responsible_for_maintenance="{{ $vehiclemaintenance->responsible_for_maintenance}}"
                            data-vehicle_make="{{ $vehiclemaintenance->vehicle_make}}"
                            data-responsible_for_maintenance="{{$vehiclemaintenance->responsible_for_maintenance}}"
                            data-vehicle_model="{{$vehiclemaintenance->vehicle_model}}"
                            data-vehicle_type="{{$vehiclemaintenance->vehicle_type}}"
                            data-year="{{$vehiclemaintenance->year}}"
                            data-vehicle_registration="{{$vehiclemaintenance->vehicle_registration}}"
                            data-chassis_number="{{$vehiclemaintenance->chassis_number}}"
                            data-engine_number="{{$vehiclemaintenance->engine_number}}"
                            data-vehicle_color="{{$vehiclemaintenance->vehicle_color}}"
                            data-metre_reading_type="{{$vehiclemaintenance->metre_reading_type}}"
                            data-odometer_reading="{{$vehiclemaintenance->odometer_reading}}"
                            data-hours_reading="{{$vehiclemaintenance->hours_reading}}"
                            data-fuel_type="{{$vehiclemaintenance->fuel_type}}"
                            data-size_of_fuel_tank="{{$vehiclemaintenance->size_of_fuel_tank}}"
                            data-fleet_number="{{$vehiclemaintenance->fleet_number}}"
                            data-cell_number="{{$vehiclemaintenance->cell_number}}"
                            data-tracking_umber="{{$vehiclemaintenance->tracking_umber}}"
                            data-vehicle_owner="{{$vehiclemaintenance->vehicle_owner}}"
                            data-title_type="{{$vehiclemaintenance->title_type}}"
                            data-financial_institution="{{$vehiclemaintenance->financial_institution}}"
                            data-extras="{{ $vehiclemaintenance->extras }}"
                            data-property_type="{{ $vehiclemaintenance->property_type }}"
                            data-company="{{ $vehiclemaintenance->company }}"

                    ><i class="fa fa-pencil-square-o"></i> Edit
                    </button>

                    <a href="{{ '/vehicle_management/viewImage/' . $vehiclemaintenance->vehicle_id }}"
                       id="edit_compan" class="btn btn-sm btn-default btn-flat"
                       data-id="{{ $vehiclemaintenance->id }}">Images</a>

                       <a href="{{ '/jobcards/jobcardnotes/' . $card->id }}"
                       id="edit_compan" class="btn btn-sm btn-default btn-flat"
                       data-id="{{ $vehiclemaintenance->id }}">Job Card Notes</a>
                   
                   <a href="{{ '/jobcard/parts/' . $card->id }}"
                       id="edit_compan" class="btn btn-sm btn-default btn-flat"
                       data-id="{{ $vehiclemaintenance->id }}">Parts</a>
                    
                 
                    
<!--                 <button vehice="button" id="edit_compan" class="btn btn-sm btn-default btn-flat" data-toggle="modal"data-target="#add-safe-modal"> Print  </button>-->
                    
                    <a href="{{ '/jobcards/parts/' . $card->id }}"
                       id="edit_compan" class="btn btn-sm btn-default btn-flat"
                       data-id="{{ $card->id }}">Print</a>
		    <a href="{{ '/vehicle_management/notes/' . $card->id }}"
                       id="edit_compan" class="btn btn-sm btn-default btn-flat"
                       data-id="{{ $card->id }}">Conclude Jobcard</a>
                   
                   <button class="btn btn-sm btn-default btn-flat"  id="print" name="print" onclick="myFunction()">Print </button>  
                     
                  <div id="myDIV">
                      <br>
                     <form class="form-horizontal" method="get" action="/jobcards/print/{{$card->id}}">
                      
<!--                           <td style="vertical-align: middle; text-align: center;">
                                    <label class="radio-inline" style="padding-left: 0px;"> Job Cards <input type="checkbox"
                                                                                                  id="{{ $card->id . '_rdo_none' }}"
                                                                                                  name=""
                                                                                                  value="print_jobcard"></label>
                                </td>
                                
                      <td style="vertical-align: middle; text-align: center;">
                                    <label class="radio-inline" style="padding-left: 0px;"> Job Cards + notes <input type="checkbox"
                                                                                                  id="{{ $card->id . '_rdo_none' }}"
                                                                                                  name=""
                                                                                                  value="print_jobcard_notes"></label>
                                </td>-->
                                
                     
                                
                       <td style="vertical-align: middle; text-align: center;"> Job Cards <input type="checkbox" class="checkbox selectall"
                                                   id="jobcards{{ $card->id }}" name="cards_2" value="1" > </td>
                       <td style="vertical-align: middle; text-align: center;"> Job Cards + notes <input type="checkbox" class="checkbox selectall"
                                                   id="jobcards_notes{{ $card->id }}" name="cards_3" value="1" > </td>
                       <td style="vertical-align: middle; text-align: center;"> Audit <input type="checkbox" class="checkbox selectall"
                                                   id="audit{{ $card->id }}" name="cards_4" value="1" > </td>
                       
                                                                      
                                
                      <input type="submit" id="load-allocation" name="load-allocation" class="btn btn-sm btn-default btn-flat" value="Submit">           
                      
                  </form>
                  </div>

                    <button type="button" id="cancel" class="btn-sm btn-default btn-flat pull-left"><i
                                class="fa fa-arrow-left"></i> Back
                    </button>

                </div>
                @endforeach
               
            </div>
        </div>
        @include('job_cards.partials.print_modal')	
       
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
        
         $('.print').hide();

         function myFunction() {
                   
             
                    var x = document.getElementById("myDIV");
                    if (x.style.display === "none") {
                        x.style.display = "block";
                    } else {
                        x.style.display = "none";
                    }
                }
    
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
