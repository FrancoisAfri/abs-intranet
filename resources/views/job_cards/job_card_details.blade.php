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
                            @foreach ($jobcard as $jobcard)
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
                                    <td class="caption">Sechedule Date</td>
                                    <td>{{ !empty($jobcard->schedule_date) ? date(' d M Y', $jobcard->schedule_date) : ''}}</td>
                                    <td class="caption">Booking Date</td>
                                    <td>{{ !empty($jobcard->booking_date) ? date(' d M Y', $jobcard->booking_date) : ''}}</td>
                                </tr>
                                <tr>
                                    <td class="caption">Completion Date</td>
                                    <td>{{ !empty($jobcard->completion_date) ? date(' d M Y', $jobcard->completion_date) : ''}}</td>
                                    <td class="caption">Job Card Status</td>
                                    <td>{{ !empty($jobcard->aStatus) ? $jobcard->aStatus : ''}}</td>
                                </tr>
                                <tr>
                                    <td class="caption" width="25%">Make</td>
                                    <td width="25%">{{ !empty($jobcard->vehicle_make) ? $jobcard->vehicle_make : ''}}</td>
                                    <td class="caption" width="25%">Model</td>
                                    <td width="25%">{{ !empty($jobcard->vehicle_model) ? $jobcard->vehicle_model : ''}}</td>
                                </tr>
                                <tr>
                                    <td class="caption">Current Odometer</td>
                                    <td>{{ !empty($jobcard->odometer_reading) ? $jobcard->odometer_reading : ''}}</td>
                                    <td class="caption">Current Hours</td>
                                    <td>{{ !empty($jobcard->hours_reading) ? $jobcard->hours_reading : ''}}</td>
                                </tr>
                                <tr>
                                    <td class="caption">Inspection Number</td>
                                    <td>{{ !empty($jobcard->cell_number) ? $jobcard->cell_number : ''}}</td>
                                    <td class="caption">Service Time</td>
                                    <td>{{ !empty($jobcard->service_time) ? $jobcard->service_time : ''}}</td>
                                </tr>
                                <tr>
                                    <td class="caption">Service Type</td>
                                    <td>{{ !empty($jobcard->servicetype) ? $jobcard->servicetype : ''}}</td>
                                    <td class="caption">Servicing Agent</td>
                                    <td>{{ !empty($jobcard->supplier) ? $jobcard->supplier : ''}}</td>
                                </tr>
                                <tr>
                                    <td class="caption">Mechanic</td>
                                    <td>{{ !empty($jobcard->me_firstname)  && !empty($jobcard->me_surname) ? $jobcard->me_firstname." ".$jobcard->me_surname : 'N/A'}}</td>
                                    <td class="caption">Driver</td>
                                    <td>{{ !empty($jobcard->dr_firstname)  && !empty($jobcard->dr_surname) ? $jobcard->dr_firstname." ".$jobcard->dr_surname : 'N/A'}}</td>
                                </tr>
                                <tr>
                                    <td class="caption">Service File Attachment</td>
                                    <td>
                                        <div class="form-group{{ $errors->has('inspection_file_upload') ? ' has-error' : '' }}">
                                            <label for="document" class="control-label"></label>
                                            @if(!empty($jobcard->service_file_upload))
                                                <a class="btn btn-default btn-flat btn-block pull-right btn-xs"
                                                   href="{{ Storage::disk('local')->url("Jobcard/servicefileupload/$jobcard->service_file_upload") }}"
                                                   target="_blank"><i class="fa fa-file-pdf-o"></i> View Document</a>
                                            @else
                                                <a class="btn btn-default pull-centre btn-xs"><i class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
								<tr>
                                    <td class="caption">Mechanic Comment</td>
                                    <td>{{ !empty($jobcard->mechanic_comment) ? $jobcard->mechanic_comment : ''}}</td>
                                    <td class="caption">Completion Comment</td>
                                    <td>{{ !empty($jobcard->completion_comment) ? $jobcard->completion_comment : ''}}</td>
                                </tr>
                        </table>
						<table class="table table-striped table-bordered">
							<hr class="hr-text" data-content="Instructions">
							<tr>
									<td>#</td>
									<td>Instructions</td>
									<td>Status</td>
									<td>Completion  Date/Time</td>
							</tr>
							@if (count($instructions) > 0)
								@foreach ($instructions as $instruction)
									<tr>
										<td>
										@if ($card->status <= 1)
											<a href="{{ '/jobcards/edit_instructions/' . $instruction->id }}"
											id="edit_instructions" class="btn btn-sm btn-default btn-flat">Modify</a>
										@endif
										</td>
										<td>
											{{ $loop->iteration }}. {{ !empty($instruction->instruction_details) ? $instruction->instruction_details : '' }}
										</td>
										<td>{{ !empty($instruction->status) && $instruction->status == 2 ? 'Completed' : 'Incomplete' }}</td>
									<td>{{ !empty($instruction->completion_date) ? date('d M Y ', $instruction->completion_date) : '' }} - {{ !empty($instruction->completion_time) ? $instruction->completion_time : '' }}</td>
									</tr>
								@endforeach
							@else
								<tr><td colspan="4"></td></tr>
							@endif
						</table>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-body" align="center">
                    <button vehice="button" class="btn btn-sm btn-default btn-flat" data-toggle="modal"
                            data-target="#edit-jobcard-modal" data-id="{{ $card->id }}"
                            data-card_date="{{ !empty($jobcard->card_date) ? date(' d M Y', $jobcard->card_date) : ''}}"
                            data-schedule_date="{{ !empty($jobcard->schedule_date) ? date(' d M Y', $jobcard->schedule_date) : ''}}"
                            data-booking_date="{{ !empty($jobcard->booking_date) ? date(' d M Y', $jobcard->booking_date) : ''}}"
                            data-supplier_id="{{$jobcard->supplier_id}}"
                            data-service_type="{{$jobcard->service_type}}"
                            data-estimated_hours="{{$jobcard->estimated_hours}}"
                            data-service_time="{{$jobcard->service_time}}"
                            data-machine_hour_metre="{{$jobcard->machine_hour_metre}}"
                            data-machine_odometer="{{$jobcard->machine_odometer}}"
                            data-last_driver_id="{{$jobcard->last_driver_id}}"
                            data-inspection_info="{{$jobcard->inspection_info}}"
                            data-mechanic_id="{{$jobcard->mechanic_id}}"
                            data-vehicle_id="{{$jobcard->vehicle_id}}"
                            data-instruction="{{$jobcard->instruction}}"><i class="fa fa-pencil-square-o"></i> Edit
                    </button>
					@if (!empty($roles->role_id) && $roles->role_id == $jobcard->job_title)
					<a href="{{ '/jobcards/mechanic-feedback/' . $card->id }}"
                       id="edit_compan" class="btn btn-sm btn-default btn-flat">Mechanic Feedback</a>
					@endif
                    <a href="{{ '/jobcards/jobcardimages/' . $card->id }}"
                       id="edit_compan" class="btn btn-sm btn-default btn-flat">Images</a>
                    <a href="{{ '/jobcards/jobcardnotes/' . $card->id }}"
                       id="edit_compan" class="btn btn-sm btn-default btn-flat">Notes({{$card->jcNotes->count()}})</a>
                    <a href="{{ '/jobcard/parts/' . $card->id }}"
                       id="edit_compan" class="btn btn-sm btn-default btn-flat">Parts</a>
					@if ($card->status == 1)
						<a href="{{ '/jobcard/next-step/' . $card->id }}"
                       id="edit_compan" class="btn btn-sm btn-default btn-flat">Next Step</a>
					@endif
					@if (($flow->step_number - 2) == $card->status)
						<button vehice="button" class="btn btn-sm btn-default btn-flat" data-toggle="modal"
						data-target="#document-jobcard-modal" data-id="{{ $card->id }}"
						data-completion_date="{{ !empty($current_date) ? date('d/m/Y', $current_date) : ''}}"
						><i class="fa fa-lock"></i> Process Documents
						</button>
					@endif
					@if (($flow->step_number - 1) == $card->status)
						<button vehice="button" class="btn btn-sm btn-default btn-flat" data-toggle="modal"
						data-target="#close-jobcard-modal" data-id="{{ $card->id }}"
						data-completion_date="{{ !empty($current_date) ? date('d/m/Y', $current_date) : ''}}"
						><i class="fa fa-lock"></i> Conclude Jobcard
						</button>
					@endif
					@if (!empty($userAccess))
						<a href="{{ '/jobcard/jobcard_history/' . $card->id }}"
						   class="btn btn-sm btn-default btn-flat" target=”_blank”">History</a>
					@endif
                    <button class="btn btn-sm btn-default btn-flat" id="print" name="print" onclick="myFunction()">
                        Print
                    </button>
					@if (!empty($userAccess))
						<button type="button" class="btn btn-danger btn-xs" data-toggle="modal"
								data-target="#delete-jobcard-warning-modal"
								data-id="{{ $card->id }}"><i class="fa fa-trash"></i>
							Delete
						</button>
					@endif
                    <div id="myDIV">
                        <br>
                        <form class="form-horizontal" method="get" action="/jobcards/print/{{$card->id}}">
                        {{ csrf_field() }}
							<td style="vertical-align: middle; text-align: center;"> Job Cards 
								<input type="checkbox" class="checkbox" id="jobcards" name="jobcards" value="1">
							</td>
                            <td style="vertical-align: middle; text-align: center;">+ Notes 
								<input type="checkbox" class="checkbox" id="jobcards_notes" name="jobcards_notes" value="1">
							</td>
							<td style="vertical-align: middle; text-align: center;">+ Parts 
								<input type="checkbox" class="checkbox" id="jobcards_parts" name="jobcards_parts" value="1">
							</td>
							<td style="vertical-align: middle; text-align: center;">All 
								<input type="checkbox" class="checkbox" id="jobcards_all" name="jobcards_all" value="1">
							</td>
                            <input type="submit" id="load-allocation" name="load-allocation"
                                   class="btn btn-sm btn-default btn-flat" value="Submit">
                        </form>
                    </div>
                    <button type="button" id="cancel" class="btn-sm btn-default btn-flat pull-left"><i
                                class="fa fa-arrow-left"></i> Back
                    </button>
                </div>
                @endforeach
            </div>
        </div>
        @include('job_cards.partials.edit_jobcard_modal')
        @include('job_cards.partials.document_jobcard_modal')
        @include('job_cards.partials.conclude_jobcard_modal')
		@if (!empty($userAccess))
			@include('job_cards.partials.delete_jobcard_warning_action', ['modal_title' => 'Delete Job Card', 'modal_content' => 'Are you sure you want to delete this job card? This action cannot be undone.'])
		@endif
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
        $('#myDIV').hide();
        function myFunction() {
            var x = document.getElementById("myDIV");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }

        $(function () {
            $(".select2").select2();
            $('.hours-field').hide();
            $('.comp-field').hide();
            var JobId;
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
			$('#document-jobcard-modal').on('shown.bs.modal', function (e) {
				var btnEdit = $(e.relatedTarget);
				var completionDate = btnEdit.data('completion_date');
				var modal = $(this);
				modal.find('#completion_date').val(completionDate);
			});
			$('#document_jobcard').on('click', function () {
                var strUrl = '/jobcards/documentjobcard/' + {{$card->id}};
                var formName = 'document-jobcard-form';
                var modalID = 'document-jobcard-modal';
                var submitBtnID = 'document_jobcard';
                var redirectUrl = '/jobcards/viewcard/' + {{$card->id}};
                var successMsgTitle = 'Job Card Closed!';
                var successMsg = 'The Job Card  has been updated successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
			$('#close_jobcard').on('click', function () {
                var strUrl = '/jobcards/closejobcard/' + {{$card->id}};
                var formName = 'close-jobcard-form';
                var modalID = 'close-jobcard-modal';
                var submitBtnID = 'close_jobcard';
                var redirectUrl = '/jobcards/viewcard/' + {{$card->id}};
                var successMsgTitle = 'Job Card Closed!';
                var successMsg = 'The Job Card  has been updated successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
            //pass category data to the edit category modal
            $('#edit-jobcard-modal').on('shown.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                JobId = btnEdit.data('id');
                var cardDate = btnEdit.data('card_date');
                var scheduleDate = btnEdit.data('schedule_date');
                var bookingDate = btnEdit.data('booking_date');
                var supplierID = btnEdit.data('supplier_id');
                var serviceType = btnEdit.data('service_type');
                var estimatedHours = btnEdit.data('estimated_hours');
                var serviceTime = btnEdit.data('service_time');
                var machineHourMetre = btnEdit.data('machine_hour_metre');
                var machineOdometer = btnEdit.data('machine_odometer');
                var lastDriverID = btnEdit.data('last_driver_id');
                var inspectionInfo = btnEdit.data('inspection_info');
                var mechanicID = btnEdit.data('mechanic_id');
                var vehicleID = btnEdit.data('vehicle_id');
                var modal = $(this);
                modal.find('#card_date').val(cardDate);
                modal.find('#schedule_date').val(scheduleDate);
                modal.find('#booking_date').val(bookingDate);
                modal.find('#estimated_hours').val(estimatedHours);
                modal.find('#service_time').val(serviceTime);
                modal.find('#machine_hour_metre').val(machineHourMetre);
                modal.find('#machine_odometer').val(machineOdometer);
                modal.find('#inspection_info').val(inspectionInfo);
                modal.find('select#service_type').val(serviceType);
                modal.find('select#last_driver_id').val(lastDriverID);
                modal.find('select#mechanic_id').val(mechanicID);
                modal.find('select#supplier_id').val(supplierID);
                modal.find('select#vehicle_id').val(vehicleID);
            });
          
            $('#update_jobcard').on('click', function () {
                var strUrl = '/jobcards/updatejobcard/' + {{$card->id}};
                var formName = 'edit-jobcard-form';
                var modalID = 'edit-jobcard-modal';
                var submitBtnID = 'edit_warrantie';
                var redirectUrl = '/jobcards/viewcard/' + {{ $card->id }};
                var successMsgTitle = 'Record Updated!';
                var successMsg = 'The Record  has been updated successfully.';
                var Method = 'PATCH'
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
			
			var jobID;
			$('#delete-jobcard-warning-modal').on('shown.bs.modal', function (e) {
				var btnDelete = $(e.relatedTarget);
				jobID = btnDelete.data('id');
			});
			$('#delete_jobcard').on('click', function () {
				var strUrl = '/jobcard/' + jobID + '/delete';
				var formName = 'delete-jobcard-warning-modal-form';
				var modalID = 'delete-jobcard-warning-modal';
				var submitBtnID = 'delete_jobcard';
				var redirectUrl = '/jobcards/mycards';
				var successMsgTitle = 'Job Card Successfully Deleted!';
				var successMsg = 'Job Card has been deleted successfully.';
				modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
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
    </script>
@endsection
