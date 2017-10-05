@extends('layouts.main_layout')
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
                        <!-- <div class="box-body" style="max-height: 274px;""> -->
                            <div class="box-body" style="max-height: 600px; overflow-y: scroll;">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <!-- <th style="width: 10px">#</th> -->
                                    <th></th>
                                    <th></th>
                                    <th style="text-align: right;"> </th>
                                    <th></th>
                                </tr>
                                    <td style="vertical-align: middle;"></td>
                                      
                                    <tr><td class="caption" colspan="2">Allow Sending of Messages</td>
                                        <input type="hidden" name="allow_sending_messages" value="0">
                                        <td colspan="3"><input type="checkbox" name="allow_sending_messages" value="1" ></td>
                                    </tr>
                                    <tr><td class="caption" colspan="2">Use Fleet Number</td>
                                          <input type="hidden" name="use_fleet_number" value="0">
                                        <td colspan="3"><input type="checkbox" name="use_fleet_number" value="1" ></td>

                                    </tr>
                                    <tr><td class="caption" colspan="2">Include Inspection Documents</td>
                                         <input type="hidden" name="include_inspection_document" value="0">
                                        <td colspan="3"><input type="checkbox" name="include_inspection_document" value="1"></td>
                                    </tr>
                                    <tr><td class="caption" colspan="2">New Vehicle Approval</td>
                                        <input type="hidden" name="new_vehicle_approval" value="0">
                                        <td colspan="3"><input type="checkbox" name="new_vehicle_approval" value="1"></td>
                                    </tr>
                                    <tr><td class="caption" colspan="2">Include Division in Reports</td>
                                        <input type="hidden" name="include_division_report" value="0">
                                        <td colspan="3"><input type="checkbox" name="include_division_report" value="1" ></td>
                                    </tr>
                                    <tr><td class="caption" colspan="2">Fuel Auto Approval</td>
                                        <input type="hidden" name="fuel_auto_approval" value="0">
                                        <td colspan="3"><input type="checkbox" name="fuel_auto_approval" value="1"  onclick="$('.tr_hide').toggle();"></td>
                                    </tr>
                                    <tr class="tr_hide"><td class="caption" colspan="2">Fuel Require Tank Manager Approval</td>
                                         <input type="hidden" name="fuel_require_tank_manager_approval" value="0">
                                        <td colspan="3"><input type="checkbox" name="fuel_require_tank_manager_approval" value="1"></td>
                                    </tr>
                                    <tr class="tr_hide"><td class="caption" colspan="2">Fuel Require CEO Approval</td>
                                        <input type="hidden" name="fuel_require_ceo_approval" value="0">
                                        <td colspan="3"><input type="checkbox" name="fuel_require_ceo_approval" value="1" ></td>
                                    </tr>
                                    <tr><td class="caption" colspan="2">Sms Job Card to Mechanic</td>
                                        <input type="hidden" name="mechanic_sms" value="0">
                                        <td colspan="3"><input type="checkbox" name="mechanic_sms" value="1"></td>

                                    <tr><td class="caption" colspan="2">New Permit Upload Days</td>
                                        <input type="hidden" name="permit_days" value="0">
                                        <td colspan="6"><input type="number" name="permit_days" size="20" maxlength="7"  placeholder="Enter Permit Days" ></td>
                                    </tr>

                                    <tr><td class="caption" colspan="2">Currency</td>
                                        <td colspan="3"><input type="text" name="currency" size="20" maxlength="4" placeholder="Enter Currency" ></td>
                                    </tr>
                                    <tr><td class="caption" colspan="2">Approvals Done By</td>
                                        <td colspan="3">
                                            <input type="hidden" name="approval_manager_capturer" value="0">
                                            <input type="checkbox" name="approval_manager_capturer" value="1"> Capturer Manager<br>

                                            <input type="hidden" name="approval_manager_driver" value="0">
                                            <input type="checkbox" name="approval_manager_driver" value="1"> Driver Manager<br>

                                            <input type="hidden" name="approval_hod" value="0">
                                            <input type="checkbox" name="approval_hod" value="1"> Department Head<br>

                                            <input type="hidden" name="approval_admin" value="0">
                                            <input type="checkbox" name="approval_admin" value="1"> Administrator
                                        </td>
                                    </tr>
                                    <tr><td class="caption" colspan="2">Return Overdue Notifications</td>
                                        <td colspan="3">
                                            <input type="hidden" name="return_due_manager" value="0">
                                            <input type="checkbox" name="return_due_manager" value="1"> Driver Manager <br>

                                            <input type="hidden" name="return_due_hod" value="0">
                                            <input type="checkbox" name="return_due_hod" value="1"> Department Head <br>

                                            <input type="hidden" name="return_due_admin" value="0">
                                            <input type="checkbox" name="return_due_admin" value="1"> Administrators
                                        </td>
                                    </tr>
                                    <tr><td class="caption" colspan="2">Fine Notifications</td>
                                        <td colspan="3">
                                            <input type="hidden" name="fines_manager" value="0">
                                            <input type="checkbox" name="fines_manager" value="1"> Driver Manager <br>

                                            <input type="hidden" name="fines_hod" value="0">
                                            <input type="checkbox" name="fines_hod" value="1"> Department Head <br>

                                            <input type="hidden" name="fines_admin" value="0">
                                            <input type="checkbox" name="fines_admin" value="1"> Administrators
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
                                        <td style="text-align:center;"><input type="checkbox" name="incident_minor_manager" value="1"></td>

                                        <input type="hidden" name="incident_major_manager" value="0">
                                        <td style="text-align:center;"><input type="checkbox" name="incident_major_manager" value="1" ></td>

                                        <input type="hidden" name="incident_critical_manager" value="0">
                                        <td style="text-align:center;"><input type="checkbox" name="incident_critical_manager" value="1"></td>
                                    </tr>
                                    <tr>
                                        <td class="caption">Department Head</td>
                                        <input type="hidden" name="incident_minor_hod" value="0">
                                        <td style="text-align:center;"><input type="checkbox" name="incident_minor_hod" value="1"></td>

                                        <input type="hidden" name="incident_major_hod" value="0">
                                        <td style="text-align:center;"><input type="checkbox" name="incident_major_hod" value="1"></td>

                                        <input type="hidden" name="incident_critical_hod" value="0">
                                        <td style="text-align:center;"><input type="checkbox" name="incident_critical_hod" value="1"></td>
                                    </tr>
                                    <tr>
                                        <td class="caption">Administators</td>
                                        <input type="hidden" name="incident_minor_admin" value="0">
                                        <td style="text-align:center;"><input type="checkbox" name="incident_minor_admin" value="1"></td>

                                        <input type="hidden" name="incident_major_admin" value="0">
                                        <td style="text-align:center;"><input type="checkbox" name="incident_major_admin" value="1"></td>

                                        <input type="hidden" name="incident_critical_admin" value="0">
                                        <td style="text-align:center;"><input type="checkbox" name="incident_critical_admin" value="1"></td>
                                    </tr>
                                    <tr>
                                        <td class="caption" colspan="2">Notify driver of vehicle booking on their behalf</td>
                                        <input type="hidden" name="submit_on_behalf" value="0">
                                        <td colspan="3"><input type="checkbox" name="submit_on_behalf" value="1"></td>
                                    </tr>
                                    <tr>
                                        <td class="caption" colspan="2">Allow Past Bookings</td>
                                         <input type="hidden" name="allow_past_bookings" value="0">
                                        <td colspan="3"><input type="checkbox" name="allow_past_bookings" value="1"></td>
                                    </tr>
                                    <tr>
                                        <td class="caption" colspan="2">Notification Method on approvals/rejections</td>
                                        <td colspan="3"><input type="radio" name="notification_method" value="email" >Email &nbsp;
                                            <input type="radio" name="notification_method" value="sms" >SMS &nbsp;
                                        <input type="radio" name="notification_method" value="employee_based">" checked="checked">Employee Based
                                        </td>
                                    </tr>
                                    <!--  -->
                                    <tr><td class="caption" colspan="2">Send Alert before service is due</td>
                                        
                                        <td colspan="6"><input type="number" name="service_days" size="20" maxlength="7"  placeholder="Enter service days" > Days
                                        <br>
                                        <br>
                                        <input type="number" name="service_km" size="20" maxlength="7"  placeholder="Enter service km" > km</td>
                                    </tr>
                                    <!--  -->
                                    <tr><td class="caption" colspan="2">Send Recurring Notifications when service is overdue</td>
                                        
                                        <td colspan="6"><input type="number" name="service_overdue_days" size="20" maxlength="7"  placeholder="Enter service overdue days" > Days
                                        <br>
                                        <br>
                                        <input type="number" name="service_overdue_km" size="20" maxlength="7"  placeholder="Enter service overdue km " > km</td>
                                    </tr>
                                    <!--  -->
                                    <tr><td class="caption" colspan="2">Do not allow bookings if service overdue </td>
                                        
                                        <td colspan="6"><input type="number" name="no_bookings_days" size="20" maxlength="7"  placeholder="Enter no bookings days" > Days
                                        <br>
                                        <br>
                                        <input type="number" name="no_bookings_km" size="20" maxlength="7"  placeholder="Enter no bookings km" > km</td>
                                    </tr>

                                    <tr>
                                        <td class="caption" colspan="2">Do not allow booking if incidents are unresolved</td>
                                        <td colspan="3">
                                            <input type="hidden" name="no_bookings_minor" value="0">
                                            <input type="checkbox" name="no_bookings_minor" value="1" > Minor <br>

                                            <input type="hidden" name="no_bookings_major" value="0">
                                            <input type="checkbox" name="no_bookings_major" value="1" > Major <br>

                                            <input type="hidden" name="no_bookings_critical" value="0">
                                            <input type="checkbox" name="no_bookings_critical" value="1"> Critical
                                        </td>
                                    </tr>

                                    <tr><td class="caption" colspan="2">Auto-Cancel if collection overdue</td>
                                        
                                        <td colspan="6"><input type="text" name="service_overdue_days" size="20" maxlength="7"  placeholder="Enter Hours " > Hours
                                    </tr>
                            </table>
                            <br>
                              <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-cog"></i> Savve </button>
                    </div>
                 </div>   
                </form>
            </div>
            </div>
        </div>
       </div>
     @endsection
<!-- Ajax form submit -->
@section('page_script')
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<script>
    
 
</script>

@endsection
