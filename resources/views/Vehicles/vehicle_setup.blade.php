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
@endsection
@section('content')

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="box box-warning">
            <div class="box-header with-border">
                <i class="fa fa-sliders pull-right"></i>
                <h3 class="box-title">Vehicle Configuration</h3>
                <!-- <p>Enter company details:</p> -->
            </div>
            <form class="form-horizontal" method="POST" action="/vehicle_management/configuration/{{ $configuration->id }}" enctype="multipart/form-data">
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

                    <div class="box-body" style="max-height: 600px; overflow-y: scroll;">
                        <table class="table table-striped table-bordered">

                            {{--<td style="vertical-align: middle;"></td>--}}

                            <tr><td class="caption" colspan="2">Allow Sending of Messages</td>
                            <input type="hidden" name="allow_sending_messages" value="0">
                            <td colspan="3"><input type="checkbox" name="allow_sending_messages" value="1" {{ $configuration->allow_sending_messages === 1 ? 'checked ="checked"' : 0 }} ></td>
                            </tr>
                            <tr><td class="caption" colspan="2">Use Fleet Number</td>
                            <input type="hidden" name="use_fleet_number" value="0">
                            <td colspan="3"><input type="checkbox" name="use_fleet_number" value="1" {{ $configuration->use_fleet_number === 1 ? 'checked ="checked"' : 0 }} ></td>

                            </tr>
                            <tr><td class="caption" colspan="2">Include Inspection Documents</td>
                            <input type="hidden" name="include_inspection_document" value="0">
                            <td colspan="3"><input type="checkbox" name="include_inspection_document" value="1" {{ $configuration->include_inspection_document === 1 ? 'checked ="checked"' : 0 }} ></td>

                            </tr>
                            <tr><td class="caption" colspan="2">New Vehicle Approval</td>
                            <input type="hidden" name="new_vehicle_approval" value="0">
                            <td colspan="3"><input type="checkbox" name="new_vehicle_approval" value="1" {{ $configuration->new_vehicle_approval === 1 ? 'checked ="checked"' : 0 }} ></td>
                            </tr>
                            <tr><td class="caption" colspan="2">Include Division in Reports</td>
                            <input type="hidden" name="include_division_report" value="0">
                            <td colspan="3"><input type="checkbox" name="include_division_report" value="1" {{ $configuration->include_division_report === 1 ? 'checked ="checked"' : 0 }} ></td>
                            </tr>
                            <tr><td class="caption" colspan="2">Fuel Auto Approval</td>
                            <input type="hidden" name="fuel_auto_approval" value="0">
                            <td colspan="3"><input type="checkbox" name="fuel_auto_approval" value="1"  onclick="$('.tr_hide').toggle();" {{ $configuration->fuel_auto_approval === 1 ? 'checked ="checked"' : 0 }} ></td>></td>
                            </tr>
                            <tr class="tr_hide"><td class="caption" colspan="2">Fuel Require Tank Manager Approval</td>
                            <input type="hidden" name="fuel_require_tank_manager_approval" value="0">
                            <td colspan="3"><input type="checkbox" name="fuel_require_tank_manager_approval" value="1" {{ $configuration->fuel_require_tank_manager_approval === 1 ? 'checked ="checked"' : 0 }} ></td>
                            </tr>
                            <tr class="tr_hide"><td class="caption" colspan="2">Fuel Require CEO Approval</td>
                            <input type="hidden" name="fuel_require_ceo_approval" value="0">
                            <td colspan="3"><input type="checkbox" name="fuel_require_ceo_approval" value="1" {{ $configuration->fuel_require_ceo_approval === 1 ? 'checked ="checked"' : 0 }} ></td>
                            </tr>
                            <tr><td class="caption" colspan="2">Sms Job Card to Mechanic</td>
                            <input type="hidden" name="mechanic_sms" value="0">
                            <td colspan="3"><input type="checkbox" name="mechanic_sms" value="1"{{ $configuration->mechanic_sms === 1 ? 'checked ="checked"' : 0 }} ></td>

                            <tr><td class="caption" colspan="2">New Permit Upload Days</td>
                            <input type="hidden" name="permit_days" value="0">

                            <td colspan="6"><input type="number" name="permit_days" value="{{ $configuration->permit_days }}" size="20" maxlength="7"  placeholder="Enter Permit Days" required="" ></td>
                            </tr>

                            <tr><td class="caption" colspan="2">Currency</td>
                                <td colspan="3"><input type="text" name="currency" value="{{ $configuration->currency }}"size="20" maxlength="4" placeholder="Enter Currency" required=""></td>
                            </tr>
                            <tr><td class="caption" colspan="2">Approvals Done By</td>
                                <td colspan="3">
                                    <input type="hidden" name="approval_manager_capturer" value="0">
                                    <input type="checkbox" name="approval_manager_capturer" value="1" {{ $configuration->approval_manager_capturer === 1 ? 'checked ="checked"' : 0 }} > Capturer Manager<br>

                                    <input type="hidden" name="approval_manager_driver" value="0">
                                    <input type="checkbox" name="approval_manager_driver" value="1" {{ $configuration->approval_manager_driver === 1 ? 'checked ="checked"' : 0 }}> Driver Manager<br>

                                    <input type="hidden" name="approval_hod" value="0">
                                    <input type="checkbox" name="approval_hod" value="1" {{ $configuration->approval_hod === 1 ? 'checked ="checked"' : 0 }}> Department Head<br>

                                    <input type="hidden" name="approval_admin" value="0">
                                    <input type="checkbox" name="approval_admin" value="1" {{ $configuration->approval_admin === 1 ? 'checked ="checked"' : 0 }}> Administrator
                                </td>
                            </tr>
                            <tr><td class="caption" colspan="2">Return Overdue Notifications</td>
                                <td colspan="3">
                                    <input type="hidden" name="return_due_manager" value="0">
                                    <input type="checkbox" name="return_due_manager" value="1" {{ $configuration->return_due_manager === 1 ? 'checked ="checked"' : 0 }}> Driver Manager <br>

                                    <input type="hidden" name="return_due_hod" value="0">
                                    <input type="checkbox" name="return_due_hod" value="1" {{ $configuration->return_due_hod === 1 ? 'checked ="checked"' : 0 }} > Department Head <br>

                                    <input type="hidden" name="return_due_admin" value="0">
                                    <input type="checkbox" name="return_due_admin" value="1" {{ $configuration->return_due_admin === 1 ? 'checked ="checked"' : 0 }}> Administrators
                                </td>
                            </tr>
                            <tr><td class="caption" colspan="2">Fine Notifications</td>
                                <td colspan="3">
                                    <input type="hidden" name="fines_manager" value="0">
                                    <input type="checkbox" name="fines_manager" value="1" {{ $configuration->fines_manager === 1 ? 'checked ="checked"' : 0 }}> Driver Manager <br>

                                    <input type="hidden" name="fines_hod" value="0">
                                    <input type="checkbox" name="fines_hod" value="1" {{ $configuration->fines_hod === 1 ? 'checked ="checked"' : 0 }}> Department Head <br>

                                    <input type="hidden" name="fines_admin" value="0">
                                    <input type="checkbox" name="fines_admin" value="1" {{ $configuration->fines_admin === 1 ? 'checked ="checked"' : 0 }}> Administrators
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
                            <input type="hidden" name="incident_minor_manager" value="0">
                            <td style="text-align:center;"><input type="checkbox" name="incident_minor_manager" value="1" {{ $configuration->incident_minor_manager === 1 ? 'checked ="checked"' : 0 }}></td>

                            <input type="hidden" name="incident_major_manager" value="0">
                            <td style="text-align:center;"><input type="checkbox" name="incident_major_manager" value="1" {{ $configuration->incident_major_manager === 1 ? 'checked ="checked"' : 0 }} ></td>

                            <input type="hidden" name="incident_critical_manager" value="0">
                            <td style="text-align:center;"><input type="checkbox" name="incident_critical_manager" value="1" {{ $configuration->incident_critical_manager === 1 ? 'checked ="checked"' : 0 }}></td>
                            </tr>
                            <tr>
                                <td class="caption">Department Head</td>
                            <input type="hidden" name="incident_minor_hod" value="0">
                            <td style="text-align:center;"><input type="checkbox" name="incident_minor_hod" value="1" {{ $configuration->incident_minor_hod === 1 ? 'checked ="checked"' : 0 }}></td>

                            <input type="hidden" name="incident_major_hod" value="0">
                            <td style="text-align:center;"><input type="checkbox" name="incident_major_hod" value="1" {{ $configuration->incident_major_hod === 1 ? 'checked ="checked"' : 0 }}></td>

                            <input type="hidden" name="incident_critical_hod" value="0">
                            <td style="text-align:center;"><input type="checkbox" name="incident_critical_hod" value="1" {{ $configuration->incident_critical_hod === 1 ? 'checked ="checked"' : 0 }}></td>
                            </tr>
                            <tr>
                                <td class="caption">Administators</td>
                            <input type="hidden" name="incident_minor_admin" value="0">
                            <td style="text-align:center;"><input type="checkbox" name="incident_minor_admin" value="1" {{ $configuration->incident_minor_admin === 1 ? 'checked ="checked"' : 0 }}></td>

                            <input type="hidden" name="incident_major_admin" value="0">
                            <td style="text-align:center;"><input type="checkbox" name="incident_major_admin" value="1" {{ $configuration->incident_major_admin === 1 ? 'checked ="checked"' : 0 }}></td>

                            <input type="hidden" name="incident_critical_admin" value="0">
                            <td style="text-align:center;"><input type="checkbox" name="incident_critical_admin" value="1"  {{ $configuration->incident_critical_admin === 1 ? 'checked ="checked"' : 0 }}></td>
                            </tr>
                            <tr>
                                <td class="caption" colspan="2">Notify driver of vehicle booking on their behalf</td>
                            <input type="hidden" name="submit_on_behalf" value="0">
                            <td colspan="3"><input type="checkbox" name="submit_on_behalf" value="1" {{ $configuration->submit_on_behalf === 1 ? 'checked ="checked"' : 0 }}></td>
                            </tr>
                            <tr>
                                <td class="caption" colspan="2">Allow Past Bookings</td>
                            <input type="hidden" name="allow_past_bookings" value="0">
                            <td colspan="3"><input type="checkbox" name="allow_past_bookings" value="1" {{ $configuration->allow_past_bookings === 1 ? 'checked ="checked"' : 0 }}></td>
                            </tr>
                            <tr>
                                <td class="caption" colspan="2">Notification Method on approvals/rejections</td>
                                <td colspan="3"><input type="radio" name="notification_method" value="email" >Email &nbsp;
                                    <input type="radio" name="notification_method" value="sms" >SMS &nbsp;
                                    <input type="radio" name="notification_method" value="employee_based">Employee Based
                                </td>
                            </tr>

                            <tr><td class="caption" colspan="2">Send Alert before service is due</td>

                                <td colspan="6"><input type="number" name="service_days" value="{{ $configuration->service_days }}" size="20" maxlength="7"  placeholder="Enter service days" required="" > Days
                                    <br>
                                    <br>
                                    <input type="number" name="service_km" value="{{ $configuration->service_km }}"size="20" maxlength="7"  placeholder="Enter service km" required=""> km</td>
                            </tr>

                            <tr><td class="caption" colspan="2">Send Recurring Notifications when service is overdue</td>

                                <td colspan="6"><input type="number" name="service_overdue_days" value="{{ $configuration->service_overdue_days }}" size="20" maxlength="7"  placeholder="Enter service overdue days" required=""> Days
                                    <br>
                                    <br>
                                    <input type="number" name="service_overdue_km" name="service_overdue_days" value="{{ $configuration->service_overdue_km }}" size="20" maxlength="7"  placeholder="Enter service overdue km " required=""> km</td>
                            </tr>

                            <tr><td class="caption" colspan="2">Do not allow bookings if service overdue </td>

                                <td colspan="6"><input type="number" name="no_bookings_days"  value="{{ $configuration->no_bookings_days }}"size="20" maxlength="7"  placeholder="Enter no bookings days" required="" > Days
                                    <br>
                                    <br>
                                    <input type="number" name="no_bookings_km"  value="{{ $configuration->no_bookings_km }}" size="20" maxlength="7"  placeholder="Enter no bookings km" required=""> km</td>
                            </tr>

                            <tr>
                                <td class="caption" colspan="2">Do not allow booking if incidents are unresolved</td>
                                <td colspan="3">
                                    <input type="hidden" name="no_bookings_minor" value="0">
                                    <input type="checkbox" name="no_bookings_minor" value="1" {{ $configuration->no_bookings_minor === 1 ? 'checked ="checked"' : 0 }}> Minor <br>

                                    <input type="hidden" name="no_bookings_major" value="0">
                                    <input type="checkbox" name="no_bookings_major" value="1"  {{ $configuration->no_bookings_major === 1 ? 'checked ="checked"' : 0 }}> Major <br>

                                    <input type="hidden" name="no_bookings_critical" value="0">
                                    <input type="checkbox" name="no_bookings_critical" value="1" {{ $configuration->no_bookings_critical === 1 ? 'checked ="checked"' : 0 }}> Critical
                                </td>
                            </tr>

                            <tr><td class="caption" colspan="2">Auto-Cancel if collection overdue</td>

                                <td colspan="6"><input type="number" name="service_overdue_days" value="{{ $configuration->service_overdue_days }}"size="20" maxlength="7"  placeholder="Enter Hours " required=""> Hours
                            </tr>
                        </table>
                    </div>
                        <br>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-cog"></i> Save </button>
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
