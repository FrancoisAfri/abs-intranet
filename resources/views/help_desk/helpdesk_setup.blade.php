@extends('layouts.main_layout')

@section('page_dependencies') 
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-anchor pull-right"></i>
                     <h3 class="box-title">Help Desk Set up </h3>
                </div>
                <!-- <form name="leave-application-form" class="form-horizontal" method="POST" action=" " enctype="multipart/form-data"> -->
                  <form class="form-horizontal" id="report_form" method="POST" action="/help_desk/setup">
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
                       
                        <div class="box-body">
                          <div class="form-group {{ $errors->has('system_name') ? ' has-error' : '' }}">
                            <label for="system_name" class="col-sm-2 control-label">System</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-assistive-listening-systems"></i>
                                    </div>
                                <select class="form-control select2" style="width: 100%;" id="system_name" name="system_name"  disabled="false">
                                            
                                            <option value="{{ $serviceName }}" >{{ $serviceName }}</option>
                                          
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--  -->
                       
                        <!--  -->
                          <div class="form-group">
                            <label for="employee_number" class="col-sm-2 control-label">System Description</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                       <i class="fa fa-sticky-note"></i>
                                    </div>
                                    <input type="text" class="form-control" id="description" name="description"
                                           value="{{ $description }}" readonly>
                                </div>
                            </div>
                        </div>
                        <!--  -->

                    <div class="form-group neg-field {{ $errors->has('date_to') ? ' has-error' : '' }}">
                                        <label for="date_to" class="col-sm-2 control-label">Ticket Name (i.e: Ticket, Task, Fault)</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-ticket"></i>
                                                </div>
                                               <input type="text" id ="negannual" class="form-control pull-left" name="val"  value="TICKET# _{{ $serviceID}}" disabled="false">
                                            </div>
                                     </div>
                             </div>

                        <div class="form-group {{ $errors->has('hr_person_id') ? ' has-error' : '' }}">
                            <label for="hr_person_id" class="col-sm-2 control-label">Maximum Priority & Operator Level</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-pinterest-p"></i>
                                    </div>
                                    <select name="maximum_priority" class="form-control">
                                        <option value="">*** Select Your Priority ***</option>
                                        <option value="1" >Low</option>
                                        <option value="2" >Medium</option>
                                        <option value="3" >High</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                        
                       
                    <!-- /.box-body -->
                    <div class="box-footer">
                <input type="submit" id="load-allocation" name="load-allocation" class="btn btn-primary pull-right" value="Submit">
                    </div>
                    <!-- /.box-footer -->
                 </div>
                </form>
            </div>
        </div>
<!--  -->
    <div class="col-md-6">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-anchor pull-right"></i>
                  <h3 class="box-title">Notifications Settings</h3>
                </div>
                    <form class="form-horizontal" id="report_form" method="POST" action="/help_desk/notify_managers">
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

                        <div class="row emp-field" style="display: block;">
                                <div class="col-xs-6">
                                    <div class="form-group from-field {{ $errors->has('time_from') ? ' has-error' : '' }}">
                                        <label for="time_from" class="col-sm-4 control-label">Time From</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                                <input type="text" class="form-control" id="time_from" name="time_from" value="{{ old('time_from') }}" placeholder="Select Start time...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group to-field {{ $errors->has('time_to') ? ' has-error' : '' }}">
                                        <label for="time_to" class="col-sm-3 control-label"> To</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                                <input type="text" class="form-control" id="time_to" name="time_to" value="{{ old('time_to') }}" placeholder="Select End time...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           
                    
                            <table class="table table-bordered">
                             <div class="form-group">
                            <tr>
                                <td>Notify HR with Application</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <td>
                                        <!-- <div><input type="checkbox" name="notify_hr_email">Email</div> -->
                                        <input type="hidden" class="checkbox selectall"  name="notify_hr_email" value="0">
                                        <div class="sms"><input type="checkbox" name="notify_hr_email" value="1"> Email</div>

                                         <input type="hidden" class="checkbox selectall"  name="notify_hr_sms_sms" value="0">
                                        <div class="sms"><input type="checkbox" name="notify_hr_sms_sms" value="1"> SMS</div>

                                    
                                    <!-- <div class="sms"><input type="checkbox" class="checkbox selectall"  name="notify_hr_sms_sms" value="1" >SMS</div> -->
                                    </td> 
                                </td>
                              </tr>
                             </div>
                             <!--  -->
                             <div class="form-group">
                              <tr>
                                <td>Notify Managers of New Tickets (After Hours)</td>
                                  <td>
                                    <input type="hidden" class="checkbox selectall"  name="notify_manager_email" value="0">
                                        <div class="sms"><input type="checkbox" name="notify_manager_email" value="1"> Email</div>

                                   <!--  <div class="sms"><input type="checkbox" name="notify_manager_sms_sms"> SMS</div> -->
                                     <input type="hidden" class="checkbox selectall"  name="notify_manager_sms" value="0">
                                        <div class="sms"><input type="checkbox" name="notify_manager_sms" value="1"> SMS</div>

                                  </td>
                               </tr>
                             </div>
                        </table>                       
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <input type="submit" id="" name="" class="btn btn-primary pull-right" value="Submit">
                    </div>
                    <!-- /.box-footer -->
                 </div>
                </form>
            </div>
        </div>
    </div>
    <!--  -->
    <!--  -->
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-anchor pull-right"></i>
                    <h3 class="box-title">Auto-Escalations Settings</h3>
                </div>
                    <form class="form-horizontal" id="report_form" method="POST" action="/help_desk/auto_escalations">
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
                          <table class="table table-bordered">
                    <tr>
                        <th style="width: 10px"></th>
                        <th>Hours</th>
                        <th>Office Hours Only</th>
                         <th>Notify from Level</th>
                         <th>Office Hours</th>
                           <th>After Hours </th>
                        <th style="width: 40px"></th>
                    </tr> 
                   
                    <tr id="modules-list">
                      
                        <td>Low </td>
                        <td><input type="text" size="2" name="auto_low" value=""></td>
                        <!--  -->
                         <!-- <td style="text-align:center;"><input type="checkbox" name="office_hrs_low"></td> -->
                        <input type="hidden" class="checkbox selectall"  name="office_hrs_low" value="0">
                        <td style="text-align:center;"><div class="sms"><input type="checkbox" name="office_hrs_low" value="1"></div></td>
                         <!--  -->
                        <td style="text-align:center;"><select name="notify_level_low"><option selected="selected" value="1">low</option><option value="2">medium</option><option value="3">high</option></select></td>
                                 <td>
                                 <!--  -->
                                 <input type="hidden" class="checkbox selectall"  name="office_hrs_low_email" value="0">
                                 <div class="sms"><input type="checkbox" name="office_hrs_low_email" value="1"> Email</div>

                                 <input type="hidden" class="checkbox selectall"  name="office_hrs_low_sms" value="0">
                                 <div class="sms"><input type="checkbox" name="office_hrs_low_sms" value="1"> SMS</div>
                                 <!--  -->
                                    <!-- <div ><input type="checkbox" name="office_hrs_low_email">Email </div> 
                                    <div class="sms"><input type="checkbox" name="office_hrs_low_sms"> SMS</div> -->
                                  </td>
                                  <td>
                                  <!--  -->
                                  <input type="hidden" class="checkbox selectall"  name="aftoffice_hrs_low_email" value="0">
                                 <div class="sms"><input type="checkbox" name="aftoffice_hrs_low_email" value="1"> Email</div>

                                 <input type="hidden" class="checkbox selectall"  name="aftoffice_hrs_low_sms" value="0">
                                 <div class="sms"><input type="checkbox" name="aftoffice_hrs_low_sms" value="1"> SMS</div>
                                  <!--  -->
                                   <!--  <div ><input type="checkbox" name="aftoffice_hrs_low_email">Email </div> 
                                    <div class="sms"><input type="checkbox" name="aftoffice_hrs_low_sms"> SMS</div> -->
                                  </td>
                      
                        </tr>
                    <tr id="modules-list">
                      
                        <td>Normal </td>
                        <td><input type="text" size="2" name="auto_mormal" value=""></td>
                        <!--  -->
                         <input type="hidden" class="checkbox selectall"  name="office_hrs_normal" value="0">
                        <td style="text-align:center;"><div class="sms"><input type="checkbox" name="office_hrs_normal" value="1"></div></td>
                        <td style="text-align:center;"><select name="notify_level_normal"><option selected="selected" value="1">low</option><option value="2">medium</option><option value="3">high</option></select></td>
                         <td>
                                <input type="hidden" class="checkbox selectall"  name="office_hrs_normal_email" value="0">
                                 <div class="sms"><input type="checkbox" name="office_hrs_normal_email" value="1"> Email</div>

                                  <input type="hidden" class="checkbox selectall"  name="office_hrs_normal_sms" value="0">
                                 <div class="sms"><input type="checkbox" name="office_hrs_normal_sms" value="1"> SMS</div>
<!-- 
                                    <div ><input type="checkbox" name="office_hrs_normal_email">Email </div> 
                                    <div class="sms"><input type="checkbox" name="office_hrs_normal_sms"> SMS</div> -->
                                  </td>
                                  <td>

                                  <input type="hidden" class="checkbox selectall"  name="aftoffice_hrs_normal_email" value="0">
                                 <div class="sms"><input type="checkbox" name="aftoffice_hrs_normal_email" value="1"> Email</div>

                                  <input type="hidden" class="checkbox selectall"  name="aftoffice_hrs_normal_sms" value="0">
                                  <div class="sms"><input type="checkbox" name="aftoffice_hrs_normal_sms" value="1"> SMS</div>
<!-- 

                                    <div ><input type="checkbox" name="aftoffice_hrs_normal_email">Email </div> 
                                    <div class="sms"><input type="checkbox" name="aftoffice_hrs_normal_sms"> SMS</div> -->
                                  </td>
                    </tr>

                     <tr id="modules-list">
                      
                        <td>High </td>
                        <td><input type="text" size="2" name="auto_high" value=""></td>
                         <!-- <td style="text-align:center;"><input type="checkbox" name="office_hrs_hihg"></td> -->
                         <!--  -->
                         <input type="hidden" class="checkbox selectall"  name="office_hrs_hihg" value="0">
                        <td style="text-align:center;"><div class="sms"><input type="checkbox" name="office_hrs_hihg" value="1"></div></td>  
                        <td style="text-align:center;"><select name="notify_level_high"><option selected="selected" value="1">low</option><option value="2">medium</option><option value="3">high</option></select></td>
                          <td>
                                <input type="hidden" class="checkbox selectall"  name="office_hrs_high_email" value="0">
                                 <div class="sms"><input type="checkbox" name="office_hrs_high_email" value="1"> Email</div>

                                  <input type="hidden" class="checkbox selectall"  name="office_hrs_high_sms" value="0">
                                  <div class="sms"><input type="checkbox" name="office_hrs_high_sms" value="1"> SMS</div>

                                 <!--    <div ><input type="checkbox" name="office_hrs_high_email">Email </div> 
                                    <div class="sms"><input type="checkbox" name="office_hrs_high_sms"> SMS</div> -->
                                  </td>
                                  <td>
                                    <!-- <div ><input type="checkbox" name="aftoffice_hrs_high_sms">Email </div> 
                                    <div class="sms"><input type="checkbox" name="aftoffice_hrs_high_sms"> SMS</div> -->
                                    <input type="hidden" class="checkbox selectall"  name="aftoffice_hrs_high_email" value="0">
                                 <div class="sms"><input type="checkbox" name="aftoffice_hrs_high_email" value="1"> Email</div>

                                  <input type="hidden" class="checkbox selectall"  name="aftoffice_hrs_high_sms" value="0">
                                  <div class="sms"><input type="checkbox" name="aftoffice_hrs_high_sms" value="1"> SMS</div>
                                  </td>
                    </tr>

                     <tr id="modules-list">
                      
                        <td>Critical </td>
                        <td><input type="text" size="2" name="auto_critical" value=""></td>
                        <!--  <td style="text-align:center;"><input type="checkbox" name="office_hrs_critical"></td> -->
                         <input type="hidden" class="checkbox selectall"  name="office_hrs_critical" value="0">
                        <td style="text-align:center;"><div class="sms"><input type="checkbox" name="office_hrs_critical" value="1"></div></td> 
                        <td style="text-align:center;"><select name="notify_level_critical"><option selected="selected" value="1">low</option><option value="2">medium</option><option value="3">high</option></select></td>
                         <td>   
                                    <input type="hidden" class="checkbox selectall"  name="office_hrs_critical_email" value="0">
                                 <div class="sms"><input type="checkbox" name="office_hrs_critical_email" value="1"> Email</div>

                                  <input type="hidden" class="checkbox selectall"  name="office_hrs_critical_sms" value="0">
                                  <div class="sms"><input type="checkbox" name="office_hrs_critical_sms" value="1"> SMS</div>

                                   <!--  <div ><input type="checkbox" name="office_hrs_critical_email">Email </div> 
                                    <div class="sms"><input type="checkbox" name="office_hrs_critical_sms"> SMS</div> -->
                                  </td>
                                  <td>
                                  <input type="hidden" class="checkbox selectall"  name="aftoffice_hrs_critical_email" value="0">
                                 <div class="sms"><input type="checkbox" name="aftoffice_hrs_critical_email" value="1"> Email</div>

                                  <input type="hidden" class="checkbox selectall"  name="aftoffice_hrs_critical_sms" value="0">
                                  <div class="sms"><input type="checkbox" name="aftoffice_hrs_critical_sms" value="1"> SMS</div>

                                  <!--   <div ><input type="checkbox" name="aftoffice_hrs_critical_email">Email </div> 
                                    <div class="sms"><input type="checkbox" name="aftoffice_hrs_critical_sms"> SMS</div> -->
                                  </td>
                    </tr>
                    
            </table>
   
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <input type="submit" id="load-allocation" name="load-allocation" class="btn btn-primary pull-right" value="Submit">
                    </div>
                    <!-- /.box-footer -->
                 </div>
                </form>
            </div>
        </div>
<!--  -->
    <div class="col-md-6">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-anchor pull-right"></i>
                       <h3 class="box-title">Notify Managers on unresolved tickets</h3>
                </div>
                    <form class="form-horizontal" id="report_form" method="POST" action="/help_desk/unresolved_tickets">
                    {{ csrf_field() }}

                    <div class="box-body">
                      <!--   @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif -->
                        <table class="table table-bordered">
                    <tr>
                        <th style="width: 10px"></th>
                        <th>Hours</th>
                        <th>Office Hours Only</th>
                         <th>Office Hours</th>
                           <th>After Hours </th>
                        <th style="width: 40px"></th>
                    </tr> 
                   
                    <tr id="modules-list">
                      
                        <td>Low </td>
                        <td><input type="text" size="2" name="tickets_low" value=""></td>
                        <!--  <td style="text-align:center;"><input type="checkbox" name="low_ah"></td> -->

                         <input type="hidden" class="checkbox selectall"  name="low_ah" value="0">
                        <td style="text-align:center;"><div class="sms"><input type="checkbox" name="low_ah" value="1"></div></td>
                    
                                 <td>
                                  <input type="hidden" class="checkbox selectall"  name="esc_low_email" value="0">
                                 <div class="sms"><input type="checkbox" name="esc_low_email" value="1"> Email</div>

                                  <input type="hidden" class="checkbox selectall"  name="esc_low_sms" value="0">
                                  <div class="sms"><input type="checkbox" name="esc_low_sms" value="1"> SMS</div>
                                    <!--  -->
                                    <!-- <div ><input type="checkbox" name="esc_low_email">Email </div> 
                                    <div class="sms"><input type="checkbox" name="esc_low_sms"> SMS</div> -->
                                  </td>
                                  <td>
                                 <input type="hidden" class="checkbox selectall"  name="aftoffice_hrs_low_email" value="0">
                                 <div class="sms"><input type="checkbox" name="aftoffice_hrs_low_email" value="1"> Email</div>

                                  <input type="hidden" class="checkbox selectall"  name="aftoffice_hrs_low_sms" value="0">
                                  <div class="sms"><input type="checkbox" name="aftoffice_hrs_low_sms" value="1"> SMS</div>
                                 <!--  -->
                                    <!-- <div ><input type="checkbox" name="aftoffice_hrs_low_email">Email </div> 
                                    <div class="sms"><input type="checkbox" name="aftoffice_hrs_low_sms"> SMS</div> -->
                                  </td>
                      
                        </tr>
                    <tr id="modules-list">
                      
                        <td>Normal </td>
                        <td><input type="text" size="2" name="tickets_normal" value=""></td>
                         <!-- <td style="text-align:center;"><input type="checkbox" name="normal_oficehrs"></td> -->

                          <input type="hidden" class="checkbox selectall"  name="normal_oficehrs" value="0">
                        <td style="text-align:center;"><div class="sms"><input type="checkbox" name="normal_oficehrs" value="1"></div></td>
                       
                         <td>       
                                 <input type="hidden" class="checkbox selectall"  name="office_hrs_normal_email" value="0">
                                 <div class="sms"><input type="checkbox" name="office_hrs_normal_email" value="1"> Email</div>

                                  <input type="hidden" class="checkbox selectall"  name="office_hrs_normal_sms" value="0">
                                  <div class="sms"><input type="checkbox" name="office_hrs_normal_sms" value="1"> SMS</div>

                                    <!--  -->
                                    <!-- <div ><input type="checkbox" name="esc_low_email">Email </div> 
                                    <div class="sms"><input type="checkbox" name="esc_normal_email"> SMS</div> -->
                                  </td>
                                  <td>
                                    <!-- <div ><input type="checkbox" name="esc_low_email">Email </div> 
                                    <div class="sms"><input type="checkbox" name="esc_normal_email"> SMS</div> -->
                                    <!--  -->
                                 <input type="hidden" class="checkbox selectall"  name="aftoffice_hrs_nomal_email" value="0">
                                 <div class="sms"><input type="checkbox" name="aftoffice_hrs_nomal_email" value="1"> Email</div>

                                  <input type="hidden" class="checkbox selectall"  name="aftoffice_hrs_nomal_sms" value="0">
                                  <div class="sms"><input type="checkbox" name="aftoffice_hrs_nomal_sms" value="1"> SMS</div>
                                  </td>
                    </tr>

                     <tr id="modules-list">
                      
                        <td>High </td>
                        <td><input type="text" size="2" name="tickets_high" value=""></td>
                        <!--  -->
                         <input type="hidden" class="checkbox selectall"  name="high_oficehrs" value="0">
                        <td style="text-align:center;"><div class="sms"><input type="checkbox" name="high_oficehrs" value="1"></div></td>

                         <!-- <td style="text-align:center;"><input type="checkbox" name="low_ah"></td> -->
                      
                          <td>
                                   <!--  <div ><input type="checkbox" name="esc_high_email">Email </div> 
                                    <div class="sms"><input type="checkbox" name="esc_high_email"> SMS</div> -->
                                    <!--  -->
                                     <input type="hidden" class="checkbox selectall"  name="office_hrs_high_email" value="0">
                                     <div class="sms"><input type="checkbox" name="office_hrs_high_email" value="1"> Email</div>

                                  <input type="hidden" class="checkbox selectall"  name="office_hrs_high_sms" value="0">
                                  <div class="sms"><input type="checkbox" name="office_hrs_high_sms" value="1"> SMS</div>

                                  </td>
                                  <td>
                                  <input type="hidden" class="checkbox selectall"  name="aftoffice_hrs_high_email" value="0">
                                 <div class="sms"><input type="checkbox" name="aftoffice_hrs_high_email" value="1"> Email</div>

                                  <input type="hidden" class="checkbox selectall"  name="aftoffice_hrs_high_sms" value="0">
                                  <div class="sms"><input type="checkbox" name="aftoffice_hrs_high_sms" value="1"> SMS</div>
                                    <!--  -->
                                   <!--  <div ><input type="checkbox" name="esc_low_email">Email </div> 
                                    <div class="sms"><input type="checkbox" name="esc_high_email"> SMS</div> -->
                                  </td>
                    </tr>

                     <tr id="modules-list">
                      
                        <td>Critical </td>
                        <td><input type="text" size="2" name="tickets_critical" value=""></td>
                        <!--  <input type="hidden" class="checkbox selectall"  name="critical_oficehrs" value="0"> -->

                          <input type="hidden" class="checkbox selectall"  name="critical_oficehrs" value="0">
                        <td style="text-align:center;"><div class="sms"><input type="checkbox" name="critical_oficehrs" value="1"></div></td>
                        <!--  <td style="text-align:center;"><input type="checkbox" name="low_ah"></td> -->
                        
                         <td>
                                    <!-- <div ><input type="checkbox" name="esc_low_email">Email </div> 
                                    <div class="sms"><input type="checkbox" name="esc_critical_email"> SMS</div> -->
                                    <!--  -->
                                     <input type="hidden" class="checkbox selectall"  name="office_hrs_critical_email" value="0">
                                     <div class="sms"><input type="checkbox" name="office_hrs_critical_email" value="1"> Email</div>

                                      <input type="hidden" class="checkbox selectall"  name="office_hrs_critical_sms" value="0">
                                     <div class="sms"><input type="checkbox" name="office_hrs_critical_sms" value="1"> SMS</div>

                                  </td>
                                  <td>
                                    <!-- <div ><input type="checkbox" name="esc_low_email">Email </div> 
                                    <div class="sms"><input type="checkbox" name="esc_critical_email"> SMS</div> -->
                                    <!--  -->
                                    <input type="hidden" class="checkbox selectall"  name="aftoffice_hrs_critical_email" value="0">
                                 <div class="sms"><input type="checkbox" name="aftoffice_hrs_critical_email" value="1"> Email</div>

                                  <input type="hidden" class="checkbox selectall"  name="aftoffice_hrs_critical_sms" value="0">
                                  <div class="sms"><input type="checkbox" name="aftoffice_hrs_critical_sms" value="1"> SMS</div>

                                  </td>
                    </tr>
                    
            </table>
                         
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <input type="submit" id="load-allocation" name="load-allocation" class="btn btn-primary pull-right" value="Submit">
                    </div>
                    <!-- /.box-footer -->
                 </div>
                </form>
            </div>
        </div>
    </div>
    <!--  -->
    <!--  -->
     <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-anchor pull-right"></i>
                    <h3 class="box-title">Auto-responder messages</h3>
                </div>
                  <form class="form-horizontal" id="report_form" method="POST" action="/help_desk/auto_responder_messages">
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
                       
                        
                        <div class="form-group notes-field{{ $errors->has('responder_messages') ? ' has-error' : '' }}">
                           <label for="days" class="col-sm-2 control-label">Auto-responder messages</label>
                            <div class="col-sm-10">
                               <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="fa fa-ticket"></i>
                                    </div>
                                    <textarea class="form-control" id="responder_messages" name="responder_messages" placeholder="Auto-responder message when receiving incoming emails:..." rows="4">{{ old('responder_messages') }}</textarea>
                                </div>
                            </div>
                        </div>
                        
                           <div class="form-group notes-field{{ $errors->has('response_emails') ? ' has-error' : '' }}">
                           <label for="days" class="col-sm-2 control-label">Header for response emails sent from the helpdesk:</label>
                            <div class="col-sm-10">
                               <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="fa fa-ticket"></i>
                                    </div>
                                    <textarea class="form-control" id="response_emails" name="response_emails" placeholder="Header for response emails sent from the helpdesk:..." rows="4">{{ old('response_emails') }}</textarea>
                                </div>
                            </div>
                        </div>

                           <div class="form-group notes-field{{ $errors->has('ticket_completion_req') ? ' has-error' : '' }}">
                           <label for="days" class="col-sm-2 control-label">Message sent when ticket completion has been requested:</label>
                            <div class="col-sm-10">
                               <div class="input-group">
                                    <div class="input-group-addon">
                                       <i class="fa fa-ticket"></i>
                                    </div>
                                    <textarea class="form-control" id="ticket_completion_req" name="ticket_completion_req" placeholder="Message sent when ticket completion has been requested:..." rows="4">{{ old('ticket_completion_req') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group notes-field{{ $errors->has('ticket_completed') ? ' has-error' : '' }}">
                           <label for="days" class="col-sm-2 control-label">Message sent when ticket has been completed:</label>
                            <div class="col-sm-10">
                               <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="fa fa-ticket"></i>
                                    </div>
                                    <textarea class="form-control" id="ticket_completed" name="ticket_completed" placeholder="Message sent when ticket has been completed:..." rows="4">{{ old('ticket_completed') }}</textarea>
                                </div>
                            </div>
                        </div>
                       
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <input type="submit" id="load-allocation" name="load-allocation" class="btn btn-primary pull-right" value="Submit">
                    </div>
                    <!-- /.box-footer -->
                 </div>
                </form>
            </div>
        </div>
<!--  -->
    <div class="col-md-6">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-anchor pull-right"></i>
                    <h3 class="box-title"> System Email Setup</h3>
                </div>
            <form class="form-horizontal" id="report_form" method="POST" action="/help_desk/email_setup">
                    {{ csrf_field() }}

                    <div class="box-body">
                       
                         <table class="table table-bordered">
                             
                            <tr>
                                <td>Auto-process Emails:</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <td>
                                        <!-- <div><input type="checkbox" name="esc_low_email"></div> -->

                                        <input type="hidden" class="checkbox selectall"  name="auto_processemails" value="0">
                                     <div class="sms"><input type="checkbox" name="auto_processemails" value="1"> </div>

                                      
                                    </td> 
                                </td>
                              </tr>
                            
                             <!--  -->
                        
                            <tr>
                                <td>Only process replies:</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <td>
                                         <input type="hidden" class="checkbox selectall"  name="anly_processreplies" value="0">
                                     <div class="sms"><input type="checkbox" name="anly_processreplies" value="1"> </div>

                                    </td> 
                                </td>
                              </tr>
                            

                            <tr>
                                <td>Email address:</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <td>
                                       <input type="email" id ="email_address" class="form-control pull-left" name="email_address:" value=" " >
                                    </td> 
                                </td>
                              </tr>
                         

                              <div class="form-group">
                            <tr>
                                <td>Server Name:</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <td>
                                       <input type="text" id ="server_name" class="form-control pull-left" name="server_name" value=" " >
                                    </td> 
                                </td>
                              </tr>
                           


                              <tr>
                                <td>Server Type:</td>
                                <td>
                                         <div class="radio">
                                            <label><input type="radio" name="preferred_communication_method" id="IMAP" value="1" checked>IMAP/Exchange</label>
                                            <br>
                                            <label><input type="radio" name="preferred_communication_method" id="POP3" value="2" checked>POP3</label>
                                            </div>
                                        </td>
                             </tr>
                          

                            <tr>
                                <td>Server Port:</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <td>
                                       <input type="text" id ="server_port" class="form-control pull-left" name="server_port" value=" " placeholder="Default - POP3: 110; IMAP/Exchange: 143">
                                    </td> 
                                </td>
                              </tr>
                            

                           
                            <tr>
                                <td>Username:</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <td>
                                       <input type="text" id ="username" class="form-control pull-left" name="username" value=" " placeholder="Default - POP3: 110; IMAP/Exchange: 143">
                                    </td> 
                                </td>
                              </tr>
                           

                             
                            <tr>
                                <td>Password:</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <td>
                                       <input type="text" id ="password" class="form-control pull-left" name="password" value=" " placeholder="Default - POP3: 110; IMAP/Exchange: 143">
                                    </td> 
                                </td>
                              </tr>
                          
                               <tr>
                                <td>Signature Start String:</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <td>
                                       <input type="text" id ="Signature_start" class="form-control pull-left" name="Signature_start" value=" " placeholder="Everything below & including this string will be removed.">
                                    </td> 
                                </td>
                              </tr>

                        </table>                         
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <input type="submit" id="load-allocation" name="load-allocation" class="btn btn-primary pull-right" value="Submit" >
                    </div>
                    <!-- /.box-footer -->
                 </div>
                </form>
            </div>
        </div>
    </div>

    <!--  -->
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
<<<<<<< HEAD
                    <h3 class="box-title"> Help Desk Operators</h3>
=======
                    <h3 class="box-title"> Operators for {{ $serviceName }}</h3>
>>>>>>> cd6523785203f9fb4cbdb4fc6e946351c31e51fd

                </div>
                 {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                <!-- /.box-header -->
                <div class="box-body">

                <table class="table table-bordered">
                     <tr><th style="width: 10px">#</th>
                     <th>Helpdesk</th>
                     <th>User</th>
                     <th></th>
                     <th style="width: 40px"></th>
                     </tr>
                    @if (count($operators) > 0)
                        @foreach($operators as $jobTitle)
                         <tr id="jobtitles-list">
                           <td nowrap>
                          <button type="button" id="edit_job_title" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-service-modal" data-id="{{ $jobTitle->id }}" data-name="{{ $jobTitle->firstname }}" data-description="{{ $jobTitle->surname }}"><i class="fa fa-pencil-square-o"></i> Edit</button>

                          <td>{{ $serviceName }} </td>
                          <td>{{ ( $jobTitle->firstname . ' ' . $jobTitle->surname) }} </td>
                          <td nowrap>
                              <button type="button" id="view_job_title" class="btn {{ (!empty($jobTitle->status) && $jobTitle->status == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$jobTitle->id}}, 'actdeac');"><i class="fa {{ (!empty($jobTitle->status) && $jobTitle->status == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($jobTitle->status) && $jobTitle->status == 1) ? "De-Activate" : "Activate"}}</button>
                          </td>
                          <!-- <td>
                           <button type="button" id="add_products_title" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-operator-modal">Add Operator</button>
                          </td> -->
                        </tr>
                        @endforeach
                    @else
                        <tr id="jobtitles-list">
                        <td colspan="6">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No operator to display, please start by adding a new operator.
                        </div>
                        </td>
                        </tr>
                    @endif
                    </table>
                </div>
               
                <div class="box-footer">
                    <button type="button" id="add-operators" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-operators-modal">Add Operator</button>
                </div>
            </div>
             <!-- Include add new prime rate modal -->
        @include('help_desk.partials.add_operators')
        @include('help_desk.partials.edit_position')
        </div>

    
       <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"> Add Help Desk Admin</h3>

                </div>
                 {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                <!-- /.box-header -->
                <div class="box-body">

                <table class="table table-bordered">
                     <tr><th style="width: 10px">#</th>
                     <th>Helpdesk</th>
                     <th>User</th>
                     <th></th>
                     <th style="width: 40px"></th>
                     </tr>
                    @if (count($HelpdeskAdmin) > 0)
                        @foreach($HelpdeskAdmin as $jobTitle)
                         <tr id="jobtitles-list">
                           <td nowrap>
                          <button type="button" id="edit_job_title" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-service-modal" data-id="{{ $jobTitle->id }}" data-name="{{ $jobTitle->firstname }}" data-description="{{ $jobTitle->surname }}"><i class="fa fa-pencil-square-o"></i> Edit</button>

                          
                          <td>{{ $serviceName }} </td>
                          <td>{{ ( $jobTitle->firstname . ' ' . $jobTitle->surname) }} </td>
                          <td nowrap>
                              <button type="button" id="view_job_title" class="btn {{ (!empty($jobTitle->status) && $jobTitle->status == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$jobTitle->id}}, 'actdeac');"><i class="fa {{ (!empty($jobTitle->status) && $jobTitle->status == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($jobTitle->status) && $jobTitle->status == 1) ? "De-Activate" : "Activate"}}</button>
                          </td>
                         
                        </tr>
                        @endforeach
                    @else
                        <tr id="jobtitles-list">
                        <td colspan="6">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No operator to display, please start by adding a new operator.
                        </div>
                        </td>
                        </tr>
                    @endif
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                     <button type="button" id="adminas" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-administrator-modal">Add Administrator</button>
                </div>
            </div>
        @include('help_desk.partials.add_admin')
        @include('help_desk.partials.edit_position')
        </div>
    </div>
    @endsection

    @section('page_script')
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script> 
    <script>

        $(function () {
            var jobId;

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
            $(window).on('resize', function() {
                $('.modal:visible').each(reposition);
            });

            //Show success action modal
            $('#success-action-modal').modal('show');


                $('#time_from').datetimepicker({
                    format: 'HH:mm:ss'
                });
                $('#time_to').datetimepicker({
                    format: 'HH:mm:ss'
                 });

                  //Post module form to server using ajax (ADD)
            $('#add_operator').on('click', function() {
                //console.log('strUrl');
                var strUrl = '/help_desk/operator/add/{{ $serviceID }}';
                var modalID = 'add-operators-modal';
                var objData = {
                    operator_id: $('#'+modalID).find('#operator_id').val(),
                    // description: $('#'+modalID).find('#description').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'add-operators';
                var redirectUrl = '/help_desk/service/{{ $serviceID }}';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The Operator has been Added successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });


                  //Post module form to server using ajax (ADD)
            $('#add_admin').on('click', function() {
                //console.log('strUrl');
                 var strUrl = '/help_desk/admin/add/{{ $serviceID }}';
                var modalID = 'add-administrator-modal';
                var objData = {
                    admin_id: $('#'+modalID).find('#admin_id').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'adminas';
               var redirectUrl = '/help_desk/service/{{ $serviceID }}';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The service has been Added successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });


              var serviceID;
            $('#edit-service-modal').on('show.bs.modal', function (e) {
                    //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                serviceID = btnEdit.data('id');
                var name = btnEdit.data('name');
                var description = btnEdit.data('description');
                //var employeeName = btnEdit.data('employeename');
                var modal = $(this);
                modal.find('#name').val(name);
                modal.find('#description').val(description);

             });
            $('#update-service').on('click', function () {
                var strUrl = '/help_desk/system/adit/' + serviceID;
                var modalID = 'edit-service-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    description: $('#'+modalID).find('#description').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'edit_job_title';
                 var redirectUrl = '/helpdesk/setup';
                var successMsgTitle = 'Changes Saved!';
                 var successMsg = 'The service has been updated successfully.';
                var Method = 'PATCH';
         modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });
        });
    </script>
@endsection