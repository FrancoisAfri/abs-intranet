@extends('layouts.main_layout')
@section('page_dependencies')
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
    <!--Time Charger-->
@endsection
@section('content')

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <i class="fa fa-sliders pull-right"></i>
                    <h3 class="box-title">Vehicle Configuration</h3>
                </div>
                <form class="form-horizontal" method="POST"
                      action="/vehicle_management/configuration/{{ $configuration->id }}" enctype="multipart/form-data">
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
                        <div class="box-body" style="max-height: 1000px; overflow-y: scroll;">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <td class="caption" colspan="2">Allow Sending of Messages</td>
                                    <input type="hidden" name="allow_sending_messages" value="0">
                                    <td colspan="3"><input type="checkbox" name="allow_sending_messages"
                                                           value="1" {{ $configuration->allow_sending_messages === 1 ? 'checked ="checked"' : 0 }} >
                                    </td>
                                </tr>
                                <tr>
                                    <td class="caption" colspan="2">Use Fleet Number</td>
                                    <td colspan="3"><input type="checkbox" name="use_fleet_number"
                                                           value="1" {{ $configuration->use_fleet_number === 1 ? 'checked ="checked"' : 0 }} >
                                    </td>
                                </tr>
                                <tr>
                                    <td class="caption" colspan="2">Include Inspection Documents</td>
                                    <td colspan="3"><input type="checkbox" name="include_inspection_document"
                                                           value="1" {{ $configuration->include_inspection_document === 1 ? 'checked ="checked"' : 0 }} >
                                    </td>

                                </tr>
                                <tr>
                                    <td class="caption" colspan="2">Inforce Vehicle Documents</td>
                                    <td colspan="3"><input type="checkbox" name="inforce_vehicle_documents"
                                                           value="1" {{ $configuration->inforce_vehicle_documents === 1 ? 'checked ="checked"' : 0 }} >
                                    </td>

                                </tr>
                                <tr>
                                    <td class="caption" colspan="2">Inforce Vehicle Images</td>
                                    <td colspan="3"><input type="checkbox" name="inforce_vehicle_image"
                                                           value="1" {{ $configuration->inforce_vehicle_image === 1 ? 'checked ="checked"' : 0 }} >
                                    </td>

                                </tr>
                                <tr>
                                    <td class="caption" colspan="2">New Vehicle Approval</td>
                                    <td colspan="3"><input type="checkbox" name="new_vehicle_approval"
                                                           value="1" {{ $configuration->new_vehicle_approval === 1 ? 'checked ="checked"' : 0 }} >
                                    </td>
                                </tr>
                                <tr>
                                    <td class="caption" colspan="2">Include Division in Reports</td>
                                    <td colspan="3"><input type="checkbox" name="include_division_report"
                                                           value="1" {{ $configuration->include_division_report === 1 ? 'checked ="checked"' : 0 }} >
                                    </td>
                                </tr>
                                <tr>
                                    <td class="caption" colspan="2">Fuel Auto Approval</td>
                                    <td colspan="3"><input type="checkbox" name="fuel_auto_approval" value="1"
                                                           onclick="$('.tr_hide').toggle();" {{ $configuration->fuel_auto_approval === 1 ? 'checked ="checked"' : 0 }} >
                                    </td>
                                    </td>
                                </tr>
                                <tr class="tr_hide">
                                    <td class="caption" colspan="2">Fuel Require Tank Manager Approval</td>
                                    <td colspan="3"><input type="checkbox" name="fuel_require_tank_manager_approval"
                                                           value="1" {{ $configuration->fuel_require_tank_manager_approval === 1 ? 'checked ="checked"' : 0 }} >
                                    </td>
                                </tr>
                                <tr class="tr_hide">
                                    <td class="caption" colspan="2">Fuel Require CEO Approval</td>
                                    <td colspan="3"><input type="checkbox" name="fuel_require_ceo_approval"
                                                           value="1" {{ $configuration->fuel_require_ceo_approval === 1 ? 'checked ="checked"' : 0 }} >
                                    </td>
                                </tr>
                                <tr>
                                    <td class="caption" colspan="2">Sms Job Card to Mechanic</td>
                                    <td colspan="3"><input type="checkbox" name="mechanic_sms"
                                                           value="1"{{ $configuration->mechanic_sms === 1 ? 'checked ="checked"' : 0 }} >
                                    </td>
                                <tr>
                                    <td class="caption" colspan="2">New Permit Upload Days</td>
                                    <td colspan="6"><input type="number" name="permit_days" class="form-control"
                                                           value="{{ $configuration->permit_days }}" size="20"
                                                           maxlength="7" placeholder="Enter Permit Days" >
                                    </td>
                                </tr>

                                <tr>
                                    <td class="caption" colspan="2">Currency</td>
                                    <td colspan="3"><input type="text" name="currency" class="form-control"
                                                           value="{{ $configuration->currency }}" size="20"
                                                           maxlength="4" placeholder="Enter Currency"></td>
                                </tr>
                                <tr>
                                    <td class="caption" colspan="2">Approvals Done By</td>
                                    <td colspan="3">
                                        <label class="radio-inline" style="padding-left: 0px;"><input type="radio"
										id="rdo_cap" name="approval_manager_capturer" value="1" 
										{{ $configuration->approval_manager_capturer === 1 ? ' checked' : '' }}>  Capturer Manager  </label>
										<label class="radio-inline"><input type="radio" id="rdo_dri" name="approval_manager_capturer"
										value="2" {{ $configuration->approval_manager_capturer === 2 ? ' checked' : 0 }}> Driver Manager </label>
										<label class="radio-inline" style="padding-left: 0px;"><input type="radio"
										id="rdo_deh" name="approval_manager_capturer" value="3" 
										{{ $configuration->approval_manager_capturer === 3 ? ' checked' : 0 }}>  Department Head  </label>
										<label class="radio-inline"><input type="radio" id="rdo_adm" name="approval_manager_capturer"
										value="4" {{ $configuration->approval_manager_capturer === 4 ? ' checked' : 0 }}> Administrator </label>
									</td>
                                </tr>
                                <tr>
                                    <td class="caption" colspan="2">Return Overdue Notifications</td>
                                    <td colspan="3">
                                        <input type="checkbox" name="return_due_manager"
                                               value="1" {{ $configuration->return_due_manager === 1 ? 'checked ="checked"' : 0 }}>
                                        Driver Manager <br>
                                        <input type="checkbox" name="return_due_hod"
                                               value="1" {{ $configuration->return_due_hod === 1 ? 'checked ="checked"' : 0 }} >
                                        Department Head <br>
                                        <input type="checkbox" name="return_due_admin"
                                               value="1" {{ $configuration->return_due_admin === 1 ? 'checked ="checked"' : 0 }}>
                                        Administrators
                                    </td>
                                </tr>
                                <tr>
                                    <td class="caption" colspan="2">Fine Notifications</td>
                                    <td colspan="3">
                                        <input type="checkbox" name="fines_manager"
                                               value="1" {{ $configuration->fines_manager === 1 ? 'checked ="checked"' : 0 }}>
                                        Driver Manager <br>
                                        <input type="checkbox" name="fines_hod"
                                               value="1" {{ $configuration->fines_hod === 1 ? 'checked ="checked"' : 0 }}>
                                        Department Head <br>
                                        <input type="checkbox" name="fines_admin"
                                               value="1" {{ $configuration->fines_admin === 1 ? 'checked ="checked"' : 0 }}>
                                        Administrators
                                    </td>
                                </tr>
                                <tr class="caption">
                                    <td rowspan="4">Incidents Notifications</td>
                                    <td>&nbsp;</td>
                                    <td>Minor</td>
                                    <td>Major</td>
                                    <td>Critical</td>
                                </tr>
                                <tr>
                                    <td class="caption">Driver Manager</td>
                                    <td style="text-align:center;"><input type="checkbox" name="incident_minor_manager"
                                                                          value="1" {{ $configuration->incident_minor_manager === 1 ? 'checked ="checked"' : 0 }}>
                                    </td>
                                    <td style="text-align:center;"><input type="checkbox" name="incident_major_manager"
                                                                          value="1" {{ $configuration->incident_major_manager === 1 ? 'checked ="checked"' : 0 }} >
                                    </td>
                                    <td style="text-align:center;"><input type="checkbox"
                                                                          name="incident_critical_manager"
                                                                          value="1" {{ $configuration->incident_critical_manager === 1 ? 'checked ="checked"' : 0 }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="caption">Department Head</td>
                                    <td style="text-align:center;"><input type="checkbox" name="incident_minor_hod"
                                                                          value="1" {{ $configuration->incident_minor_hod === 1 ? 'checked ="checked"' : 0 }}>
                                    </td>
                                    <td style="text-align:center;"><input type="checkbox" name="incident_major_hod"
                                                                          value="1" {{ $configuration->incident_major_hod === 1 ? 'checked ="checked"' : 0 }}>
                                    </td>
                                    <td style="text-align:center;"><input type="checkbox" name="incident_critical_hod"
                                                                          value="1" {{ $configuration->incident_critical_hod === 1 ? 'checked ="checked"' : 0 }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="caption">Administators</td>
                                    <td style="text-align:center;"><input type="checkbox" name="incident_minor_admin"
                                                                          value="1" {{ $configuration->incident_minor_admin === 1 ? 'checked ="checked"' : 0 }}>
                                    </td>
                                    <td style="text-align:center;"><input type="checkbox" name="incident_major_admin"
                                                                          value="1" {{ $configuration->incident_major_admin === 1 ? 'checked ="checked"' : 0 }}>
                                    </td>
                                    <td style="text-align:center;"><input type="checkbox" name="incident_critical_admin"
                                                                          value="1" {{ $configuration->incident_critical_admin === 1 ? 'checked ="checked"' : 0 }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="caption" colspan="2">Notify driver of vehicle booking on their behalf
                                    </td>
                                    <td colspan="3"><input type="checkbox" name="submit_on_behalf"
                                                           value="1" {{ $configuration->submit_on_behalf === 1 ? 'checked ="checked"' : 0 }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="caption" colspan="2">Allow Past Bookings</td>
                                    <td colspan="3"><input type="checkbox" name="allow_past_bookings" 
                                                           value="1" {{ $configuration->allow_past_bookings === 1 ? 'checked ="checked"' : 0 }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="caption" colspan="2">Notification Method on approvals/rejections</td>
                                    <td colspan="3"><input type="radio" name="notification_method" value="email"{{ $configuration->notification_method === 'email' ? 'checked ="checked"' : 0 }}>Email
                                        &nbsp;
                                        <input type="radio" name="notification_method" value="sms"{{ $configuration->notification_method === 'sms' ? 'checked ="checked"' : 0 }}>SMS &nbsp;
                                        <input type="radio" name="notification_method" value="employee_based"{{ $configuration->notification_method === 'employee_based' ? 'checked ="checked"' : 0 }}>Employee
                                        Based
                                    </td>
                                </tr>
                                <tr>
                                    <td class="caption" colspan="2">Send Alert before service is due</td>
                                    <td colspan="6"><input type="number" name="service_days" class="form-control"
                                                           value="{{ $configuration->service_days }}" size="20"
                                                           maxlength="7" placeholder="Enter service days" >
                                        Days
                                        <br>
                                        <br>
                                        <input type="number" name="service_km" class="form-control"
										value="{{ $configuration->service_km }}"
                                               size="20" maxlength="7" placeholder="Enter service km" > km
                                    </td>
                                </tr>
                                <tr>
                                    <td class="caption" colspan="2">Send Recurring Notifications when service is
                                        overdue
                                    </td>
                                    <td colspan="6"><input type="number" name="service_overdue_days" class="form-control"
                                                           value="{{ $configuration->service_overdue_days }}" size="20"
                                                           maxlength="7" placeholder="Enter service overdue days"
                                                          > Days
                                        <br>
                                        <input type="number" name="service_overdue_km" name="service_overdue_days" class="form-control"
                                               value="{{ $configuration->service_overdue_km }}" size="20" maxlength="7"
                                               placeholder="Enter service overdue km " > km
                                    </td>
                                </tr>
                                <tr>
                                    <td class="caption" colspan="2">Do not allow bookings if service overdue</td>
                                    <td colspan="6"><input type="number" name="no_bookings_days" class="form-control"
                                                           value="{{ $configuration->no_bookings_days }}" size="20"
                                                           maxlength="7" placeholder="Enter no bookings days"
                                                          > Days
                                        <br>
                                        <input type="number" name="no_bookings_km" class="form-control"
                                               value="{{ $configuration->no_bookings_km }}" size="20" maxlength="7"
                                               placeholder="Enter no bookings km" > km
                                    </td>
                                </tr>
                                <tr>
                                    <td class="caption" colspan="2">Do not allow booking if incidents are unresolved
                                    </td>
                                    <td colspan="3">
                                        <input type="checkbox" name="no_bookings_minor" class="form-control"
                                               value="1" {{ $configuration->no_bookings_minor === 1 ? 'checked ="checked"' : 0 }}>
                                        Minor <br>
                                        <input type="checkbox" name="no_bookings_major" class="form-control"
                                               value="1" {{ $configuration->no_bookings_major === 1 ? 'checked ="checked"' : 0 }}>
                                        Major <br>
                                        <input type="checkbox" name="no_bookings_critical" class="form-control"
                                               value="1" {{ $configuration->no_bookings_critical === 1 ? 'checked ="checked"' : 0 }}>
                                        Critical
                                    </td>
                                </tr>
                                <tr>
                                    <td class="caption" colspan="2">Auto-Cancel if collection overdue</td>

                                    <td colspan="6"><input type="number" name="service_overdue_days" class="form-control"
                                                           value="{{ $configuration->service_overdue_days }}" size="20"
                                                           maxlength="7" placeholder="Enter Hours " > Hours
                                </tr>
								<tr>
                                    <td class="caption" colspan="2">Fleet Incidents Upload Directory</td>

                                    <td colspan="6"><input type="text" name="incidents_upload_directory" class="form-control"
                                                           value="{{ $configuration->incidents_upload_directory }}" 
                                                           placeholder="Fleet Incidents Upload Directory">
                                </tr>
								<tr>
                                    <td class="caption" colspan="2">Alerts Days</td>
                                    <td colspan="6"><input type="text" name="alert_days" class="form-control"
                                                           value="{{ !empty($configuration->alert_days) ? $configuration->alert_days : 0}}" 
                                                           placeholder="Days">
                                </tr>
								<tr>
                                    <td class="caption" colspan="2">Brake Test From Directory</td>
                                    <td colspan="6"><input type="text" name="brake_test_from" class="form-control"
                                                           value="{{ $configuration->brake_test_from }}" 
                                                           placeholder="Enter Brake Test From Directory">
                                </tr>
								<tr>
                                    <td class="caption" colspan="2">Brake Test To</td>
                                    <td colspan="6"><input type="text" name="brake_test_to" class="form-control"
                                                           value="{{ $configuration->brake_test_to }}" 
                                                           placeholder="Enter Brake Test To Directory">
                                </tr>
								<tr>
                                    <td class="caption" colspan="2">Fire Extinguisher From Directory</td>
                                    <td colspan="6"><input type="text" name="fire_extinguisher_from" class="form-control"
                                                           value="{{ $configuration->fire_extinguisher_from }}" 
                                                           placeholder="Enter Fire Extinguisher From Directory">
                                </tr>
								<tr>
                                    <td class="caption" colspan="2">Fire Extinguisher To Directory</td>
                                    <td colspan="6"><input type="text" name="fire_extinguisher_to" class="form-control"
                                                           value="{{ $configuration->fire_extinguisher_to }}" 
                                                           placeholder="Enter Fire Extinguisher To Directory">
                                </tr>
								<tr>
                                    <td class="caption" colspan="2">Fuel Transaction From Directory</td>
                                    <td colspan="6"><input type="text" name="fuel_transaction_from" class="form-control"
                                                           value="{{ $configuration->fuel_transaction_from }}" 
                                                           placeholder="Enter Fuel Transaction From Directory">
                                </tr>
								<tr>
                                    <td class="caption" colspan="2">Fuel Transaction To Directory</td>

                                    <td colspan="6"><input type="text" name="fuel_transaction_to" class="form-control"
                                                           value="{{ $configuration->fuel_transaction_to }}" 
                                                           placeholder="Enter Fuel Transaction To Directory">
                                </tr>
								<tr>
                                    <td class="caption" colspan="2">GET Fitment From Directory</td>
                                    <td colspan="6"><input type="text" name="get_fitment_from" class="form-control"
                                                           value="{{ $configuration->get_fitment_from }}" 
                                                           placeholder="Enter GET Fitment From Directory">
                                </tr>
								<tr>
                                    <td class="caption" colspan="2">GET Fitment To Directory</td>
                                    <td colspan="6"><input type="text" name="get_fitment_to" class="form-control"
                                                           value="{{ $configuration->get_fitment_to }}" 
                                                           placeholder="Enter GET Fitment To Directory">
                                </tr>
								<tr>
                                    <td class="caption" colspan="2">LDV Car Inspection From Directory</td>
                                    <td colspan="6"><input type="text" name="ldv_car_inspection_from" class="form-control"
                                                           value="{{ $configuration->ldv_car_inspection_from }}" 
                                                           placeholder="Enter LDV Car Inspection From Directory">
                                </tr>
								<tr>
                                    <td class="caption" colspan="2">LDV Car Inspection To Directory</td>

                                    <td colspan="6"><input type="text" name="ldv_car_inspection_to" class="form-control"
                                                           value="{{ $configuration->ldv_car_inspection_to }}" 
                                                           placeholder="Enter LDV Car Inspection To Directory">
                                </tr>
								<tr>
                                    <td class="caption" colspan="2">LDV Pre Use Inspections From Directory</td>
                                    <td colspan="6"><input type="text" name="ldv_pre_use_inspections_from" class="form-control"
                                                           value="{{ $configuration->ldv_pre_use_inspections_from }}" 
                                                           placeholder="Enter LDV Pre Use Inspections From Directory">
                                </tr>
								<tr>
                                    <td class="caption" colspan="2">LDV Pre Use Inspections To Directory</td>
                                    <td colspan="6"><input type="text" name="ldv_pre_use_inspections_to" class="form-control"
                                                           value="{{ $configuration->ldv_pre_use_inspections_to }}" 
                                                           placeholder="Enter LDV Pre Use Inspections From Directory">
                                </tr>
								<tr>
                                    <td class="caption" colspan="2">Mechanic Plant Inspections From Directory</td>

                                    <td colspan="6"><input type="text" name="mechanic_plant_inspections_from" class="form-control"
                                                           value="{{ $configuration->mechanic_plant_inspections_from }}" 
                                                           placeholder="Enter Mechanic Plant Inspections From Directory">
                                </tr>
								<tr>
                                    <td class="caption" colspan="2">Mechanic Plant Inspections To Directory</td>
                                    <td colspan="6"><input type="text" name="mechanic_plant_inspections_to" class="form-control"
                                                           value="{{ $configuration->mechanic_plant_inspections_to }}" 
                                                           placeholder="Enter Mechanic Plant Inspections To Directory">
                                </tr>
								<tr>
                                    <td class="caption" colspan="2">Truck Tractor Rigid Chassis From Directory</td>
                                    <td colspan="6"><input type="text" name="truck_tractor_rigid_chassis_from" class="form-control"
                                                           value="{{ $configuration->truck_tractor_rigid_chassis_from }}" 
                                                           placeholder="Enter Truck Tractor Rigid Chassis From Directory">
                                </tr>
								<tr>
                                    <td class="caption" colspan="2">Truck Tractor Rigid Chassis To Directory</td>
                                    <td colspan="6"><input type="text" name="truck_tractor_rigid_chassis_to" class="form-control"
                                                           value="{{ $configuration->truck_tractor_rigid_chassis_to }}" 
                                                           placeholder="Enter Truck Tractor Rigid Chassis To Directory">
                                </tr>
								<tr>
                                    <td class="caption" colspan="2">Tyre Survey Reports From Directory</td>
                                    <td colspan="6"><input type="text" name="tyre_survey_reports_from" class="form-control"
                                                           value="{{ $configuration->tyre_survey_reports_from }}" 
                                                           placeholder="Enter Tyre Survey Reports From Directory">
                                </tr>
								<tr>
                                    <td class="caption" colspan="2">Tyre Survey Reports To Directory</td>
                                    <td colspan="6"><input type="text" name="tyre_survey_reports_to" class="form-control"
                                                           value="{{ $configuration->tyre_survey_reports_to }}" 
                                                           placeholder="Enter Tyre Survey Reports To Directory">
                                </tr>
								<tr>
                                    <td class="caption" colspan="2">Job Card Inspection From Directory</td>
                                    <td colspan="6"><input type="text" name="job_card_inspection_from" class="form-control"
                                                           value="{{ $configuration->job_card_inspection_from }}" 
                                                           placeholder="Enter Job Card Inspection From Directory">
                                </tr><tr>
                                    <td class="caption" colspan="2">Job Card Inspection To Directory</td>
                                    <td colspan="6"><input type="text" name="job_card_inspection_to" class="form-control"
                                                           value="{{ $configuration->job_card_inspection_to }}" 
                                                           placeholder="Enter Job Card Inspection To Directory">
                                </tr>
                            </table>
                        </div>
                        <br>
                        <div class="box-footer">
						<button type="button" id="cancel" class="btn-sm btn-default btn-flat pull-left"><i
                                        class="fa fa-arrow-left"></i> Back
                        </button>
                            <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-cog"></i> Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
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
    <script type="text/javascript">
	//Cancel button click event
		document.getElementById("cancel").onclick = function () {
			location.href = '/vehicle_management/setup';
		};
        $(function () {
            $(".select2").select2();
            $('.hours-field').hide();
            $('.comp-field').hide();
            var moduleId;
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


    </script>
@endsection
