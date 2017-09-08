@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
@endsection
@section('content')

 @foreach($Ribbon_module as $modules)
     @if (($modules->id === 9) && $modules->active === 1)              
    <div class="row">
        <div class="col-md-12">
            <!-- Employee Monthly performance Widget-->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Employee Monthly Appraisal</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="text-center">
                                <strong>My Performance For {{ date('Y') }}</strong>
                            </p>

                            <div class="chart">
                                <!-- Sales Chart Canvas-->
                                <canvas id="empMonthlyPerformanceChart" style="height: 220px;"></canvas>
                            </div>
                            <!-- /.chart-responsive -->
                        </div>
                        <!-- Appraised months list col -->
                        <div class="col-md-4">
                            <p class="text-center">
                                <strong>Appraised Months List</strong>
                            </p>
                            <div class="no-padding" style="max-height: 220px; overflow-y: scroll;">
                                <ul class="nav nav-pills nav-stacked" id="emp-appraised-month-list"></ul>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <!-- Loading wheel overlay -->
                <div class="overlay" id="loading_overlay_emp_monthly_appraisal">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
            </div>
            <!-- /.box Employee Monthly performance Widget -->
        </div>
        <!-- /.col -->
    </div>
    @endif
    @endforeach
    <!--  -->
    @foreach($Ribbon_module as $modules)
     @if (($modules->id === 9) && $modules->active === 1)    
    <div class="row">
        <div class="col-md-12">
            @if($canViewCPWidget)
            <!-- company performance Widget -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Company Appraisal</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row" id="myStaffPerformanceRankingRow" hidden>
                        <div class="col-md-12">
                            <p class="text-center"><strong>My Staff Performance Ranking For {{ date('Y') }}</strong></p>
                            <div class="no-padding" style="max-height: 420px; overflow-y: scroll;">
                                <ul class="nav nav-pills nav-stacked products-list product-list-in-box" id="my-staff-ranking-list">
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="topLvlDivGraphAndRankingRow">
                        <!-- Chart col -->
                        <div class="col-md-8">
                            <p class="text-center">
                                <strong>
                                    @if($isSuperuser)
                                        {{ $topGroupLvl->plural_name }}
                                    @elseif($isDivHead)
                                        {{ $managedDivsLevel->plural_name }}
                                    @endif
                                    Performance For {{ date('Y') }}
                                </strong>
                            </p>

                            <div class="chart">
                                <!-- Sales Chart Canvas-->
                                <canvas id="divisionsPerformanceChart" style="height: 220px;"></canvas>
                            </div>
                            <!-- /.chart-responsive -->
                        </div>
                        <!-- Ranking col -->
                        <div class="col-md-4">
                            <p class="text-center">
                                <strong>Ranking</strong>
                            </p>
                            <div class="no-padding" style="max-height: 220px; overflow-y: scroll;">
                                <ul class="nav nav-pills nav-stacked" id="ranking-list">
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <!-- Loading wheel overlay -->
                <div class="overlay" id="lo_company_appraisal">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
                <!-- Include division performance modal -->
                @include('dashboard.partials.division_4_performance_modal')
                @include('dashboard.partials.division_3_performance_modal')
                @include('dashboard.partials.division_2_performance_modal')
                @include('dashboard.partials.division_1_performance_modal')
                <!-- Include emp list performance modal -->
                @include('dashboard.partials.emp_list_performance_modal')
                <!-- Include emp year performance modal -->
                @include('dashboard.partials.emp_year_performance_modal')
            </div>
            <!-- /.box company performance Widget -->
            @endif
        </div>
        <!-- /.col -->
    </div>
    @endif
    @endforeach
    <!--  -->
@foreach($Ribbon_module as $modules)
     @if (($modules->id === 4) && $modules->active === 1)    
@if($canViewEmpRankWidget)
    <div class="row">
        <div class="col-md-12">
            <!-- Employees Performance Ranking Widget -->
            <div class="box box-success same-height-widget" id="empPerformanceRankingWidgetBox">
                <div class="box-header with-border">
                    <h3 class="box-title">Employees Ranking</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <!-- Emp Group Filters (divisions) -->
                    <div class="col-sm-4 border-right">
                        <p class="text-center">
                            <strong>Filters</strong>
                        </p>
                        <form>
                            @foreach($divisionLevels as $divisionLevel)
                                <div class="form-group">
                                    <label for="{{ 'division_level_' . $divisionLevel->level }}" class="control-label">{{ $divisionLevel->name }}</label>

                                    <select id="{{ 'division_level_' . $divisionLevel->level }}" name="{{ 'division_level_' . $divisionLevel->level }}" class="form-control input-sm select2" onchange="divDDEmpPWOnChange(this, $('#emp-top-ten-list'), $('#emp-bottom-ten-list'), parseInt('{{ $totNumEmp }}'), $('#loading_overlay_emp_performance_ranking'))" style="width: 100%;">
                                    </select>
                                </div>
                            @endforeach
                        </form>
                    </div>
                    <!-- /.Emp Group Filters (divisions) -->
                    <!-- Top ten -->
                    <div class="col-sm-4 border-right">
                        <p class="text-center">
                            <strong class="label label-success"><i class="fa fa-level-up"></i> Top 10 Employees</strong>
                        </p>
                        <div class="no-padding" style="max-height: 274px; overflow-y: scroll;">
                            <ul class="nav nav-pills nav-stacked products-list product-list-in-box" id="emp-top-ten-list">
                            </ul>
                        </div>
                    </div>
                    <!-- Bottom ten -->
                    <div class="col-sm-4">
                        <p class="text-center">
                            <strong class="label label-danger"><i class="fa fa-level-down"></i> Bottom 10 Employees</strong>
                        </p>
                        <div class="no-padding" style="max-height: 274px; overflow-y: scroll;">
                            <ul class="nav nav-pills nav-stacked products-list product-list-in-box"
                                id="emp-bottom-ten-list">
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <!-- Loading wheel overlay -->
                <div class="overlay" id="loading_overlay_emp_performance_ranking">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
            </div>
            <!-- /.Employees Performance Ranking Widget -->
        </div>
    </div>
@endif
  @endif
    @endforeach

<!--  -->
@if($canViewTaskWidget)
    @foreach($Ribbon_module as $modules)
     @if (($modules->id === 13 || $modules->id === 10 ) && $modules->active === 1)   
    <div class="row">
        <div class="col-md-12">
            <!-- Employees Performance Ranking Widget -->
            <div class="box box-success same-height-widget" id="emptasksWidgetBox">
                <div class="box-header with-border">
                    <h3 class="box-title">Company Tasks</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <!-- Emp Group Filters (divisions) -->
                    <div class="col-sm-4 border-right">
                        <p class="text-center">
                            <strong>Filters</strong>
                        </p>
                        <form>
                            @foreach($divisionLevels as $divisionLevel)
                                <div class="form-group">
                                    <label for="{{ 'division_level_' . $divisionLevel->level }}" class="control-label">{{ $divisionLevel->name }}</label>

                                    <select id="{{ 'division_level_' . $divisionLevel->level }}" name="{{ 'division_level_' . $divisionLevel->level }}" class="form-control input-sm select2" onchange="divDDEmpTasksOnChange(this, $('#emp-meeting-tasks-list'), $('#emp-induction-tasks-list'),$('#emptasksWidgetBox'), $('#loading_overlay_emp_tasks'))" style="width: 100%;">
                                    </select>
                                </div>
                            @endforeach
                        </form>
                    </div>
                    <!-- /.Emp Group Filters (divisions) -->

                    <!-- Top ten -->
                    <div class="col-sm-4 border-right">
                        <p class="text-center">
                            <strong class="label label-success"> Meeting Tasks</strong>
                        </p>
                        <div class="no-padding" style="max-height: 274px; overflow-y: scroll;">
                            <ul class="nav nav-pills nav-stacked products-list product-list-in-box" id="emp-meeting-tasks-list">
                            </ul>
                        </div>
                    </div>
                    <!-- Bottom ten -->
                    <div class="col-sm-4">
                        <p class="text-center">
                            <strong class="label label-success">Induction Tasks</strong>
                        </p>
                        <div class="no-padding" style="max-height: 274px; overflow-y: scroll;">
                            <ul class="nav nav-pills nav-stacked products-list product-list-in-box"
                                id="emp-induction-tasks-list">
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                 <!-- Loading wheel overlay -->
                <div class="overlay" id="loading_overlay_emp_tasks">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
            </div>
            <!-- /.Employees Performance Ranking Widget -->
        </div>
    </div>
@endif 
    @endforeach
         @endif
<!--  -->

<!-- /Check if induction is active before showing this  And Meeting-->
  @foreach($Ribbon_module as $modules)
     @if (($modules->id === 13) && $modules->active === 1) 
    <div class="row">
        <div class="col-md-7">
            <!-- Include tasks widget -->
            @include('dashboard.partials.widgets.tasks_widget')
        </div>
        <div class="col-md-5">
            <!-- Include tasks to check widget -->
            @include('dashboard.partials.widgets.tasks_to_check_widget')
        </div>
    </div>
      @endif
    @endforeach
    <!--  -->
     @foreach($Ribbon_module as $modules)
     @if (($modules->id === 9) && $modules->active === 1)  
    <div class="row">
        <div class="col-md-6">
            <!-- Available Perks Widgets -->
            <div class="box box-warning same-height-widget">
                <div class="box-header with-border">
                    <h3 class="box-title">Available Perks</h3>

                    <div class="box-tools pull-right">
                        <!-- <span class="label label-warning">8 New Members</span> -->
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <ul class="users-list clearfix" id="perks-widget-list">
                    </ul>
                    <!-- /.users-list -->
                </div>
                <!-- /.box-body -->
                <!-- include perk details modal -->
                @include('appraisals.partials.edit_perk', ['isReaOnly' => true])
            </div>
            <!-- /.Available Perks Widgets -->
        </div>
    </div>
     @endif
    @endforeach
    
    <!--  -->
     @foreach($Ribbon_module as $modules)
     @if (($modules->id === 5) && $modules->active === 1)    
     <div class="row">
        <div class="col-md-6">
         <!-- /Tasks List -->
          <div class="box box-info">
            <div class="box-header with-border">
             <i class="fa fa-hourglass"></i>
              <h3 class="box-title">Leave Balance</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="max-height: 274px; overflow-y: scroll;">
              <div class="table-responsive">
                <table class="table no-margin">
                    <thead>
                        <tr>
                            <th>Leave Type</th>
                            <th style="text-align: right;"><i class="material-icons">account_balance_wallet</i>Leave Balance</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                    @if (!empty($balance))
                        @foreach($balance as $task)
                          <tr>
                        <td>{{ (!empty($task->leavetype)) ?  $task->leavetype : ''}}</td>
            <!-- <td style="text-align: right;"><span class="label {{ $statusLabels[$task->leave_balance] }} pull-right"> -->
                <td style="text-align: right;">{{ (!empty($task->leave_balance)) ?  $task->leave_balance : ''}}</td>
                            
                          
                          </tr>
                        @endforeach
                    @endif
                  </tbody>
                </table>
                <div class="box-footer">
                   <!--  <button id="back_to_user_search" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back to search</button> -->
                     <button id="Apply"class="btn btn-primary pull-right"><i class="fa fa-cloud-download"></i> Apply For Leave</button>
                </div>
              </div>
                @if(Session('error_starting'))
                    @include('tasks.partials.error_tasks', ['modal_title' => "Task Error!", 'modal_content' => session('error_starting')])
                @endif
                @include('tasks.partials.end_task')
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /Tasks List End -->
        </div>

        <div class="col-md-6">
         <!-- /Tasks List -->
          <div class="box box-info">
            <div class="box-header with-border">
             <i class="fa fa-hourglass"></i>
              <h3 class="box-title">Leave Applied For Status</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="max-height: 274px; overflow-y: scroll;">
              <div class="table-responsive">
                <table class="table no-margin">
                    
                    <thead>
                        <tr>
                            <th><i class="material-icons">shop_two</i>Leave Type</th>
                              <th><i class="fa fa-calendar-o"></i>Date From</th>
                             <th><i class="fa fa-calendar-o"></i>Date To</th>
                            <th style="text-align: right;"><i class="fa fa-info-circle"></i> Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if (!empty($application))
                        @foreach($application as $checkTask)
                          <tr>
                            <td>{{ (!empty($checkTask->leavetype)) ?  $checkTask->leavetype : ''}}</td>
                           <!--  <td>{{ (!empty($checkTask->start_date)) ?  $checkTask->start_date : ''}}</td> -->
                             <td>{{ !empty($checkTask->start_date) ? date('d M Y ', $checkTask->start_date) : '' }}</td>
                             <td>{{ !empty($checkTask->end_date) ? date('d M Y ', $checkTask->end_date) : '' }}</td>
                           <td style="text-align: right;">{{ (!empty($checkTask->leaveStatus)) ?  $checkTask->leaveStatus : ''}}</td>
                            <!-- <td>{{ (!empty($checkTask->status)) ?  $taskStatus[$checkTask->status] : ''}}</td> -->
                            <td>
                           
                            </td>
                          </tr>
                        @endforeach
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
     </div>
     @endif
    @endforeach
     <div class="box-body" style="max-height: 274px; overflow-y: scroll;">
              <div class="table-responsive">
              <table class="table table-striped table-bordered">
                                <tr>
                         @if (count($helpdeskTickets) > 0)
                        @foreach($helpdeskTickets as $helpdeskTicket)
                          <tr>
                             
                             <th rowspan="2" width="3px" style="vertical-align: middle;"><h1 class="no-margin"><i class="fa fa-user-o"></i></h1></th>
                                    <th>Your Ticket(s)  {{ $names." ".$surname }}</th>
                                     <th class="btn btn-primary ">  {{ $helpdeskTicket->ticket->count() }}</th>
                                    <!-- <td ><span class="label label-primary ">{{ $helpdeskTicket->ticket->count() }}</span></td> -->

                          </tr>
                        @endforeach
                    @endif
                                </tr>
                            </table>
                <table class="table no-margin">
                    <thead>
                        <tr>                     
                            <th><i class="fa fa-id-badge"></i> Ticket Number</th>
                            <th><i class="fa fa-envelope"></i> Subject</th>
                            <th><i class="fa fa-calendar-o"></i> Ticket Date</th>        
                            <th style="text-align: right;"><i class="fa fa-info-circle"></i> Status</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                    @if (!empty($tickets))
                        @foreach($tickets as $ticket)
                          <tr>
                            <td>TICK{{ (!empty($ticket->id)) ?  $ticket->id : ''}}</td>
                            <td>{{ (!empty($ticket->subject)) ?  $ticket->subject : ''}}</td> 
                             <td>{{ !empty($ticket->ticket_date) ? date('d M Y ', $ticket->ticket_date) : '' }}</td>
                             <td style="text-align: right;">{{ (!empty($ticket->status)) ?  $ticketStatus[$ticket->status] : ''}} </td>
                          </tr>
                        @endforeach
                    @endif
                  </tbody>
                </table>
                <div class="box-footer">
                      <button type="button" id="new_tickets" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-ticket-modal">Add Ticket</button>
                </div>
              </div>  
            </div>
            <div class="box-footer clearfix">
            </div>
          </div>
        </div>
     </div>
   

    
     
    

  <!--     @foreach($Ribbon_module as $modules)
     @if (($modules->id === 6) && $modules->active === 1) 
    <div class="row">
        <div class="col-md-6">
          <div>
             <div class="box box-danger same-height-widget">
                <div class="box-header with-border">
                <i class="fa fa-product-hunt"></i>
                    <h3 class="box-title">Product Category</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body" style="max-height: 274px; overflow-y: scroll;">
              <div class="table-responsive">
                <table class="table no-margin">
                    <thead>
                        <tr>
                            <th>Category #</th>
                            <th>Product</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                    @if (!empty($ProductCategory))
                        @foreach($ProductCategory as $category)
                          <tr>
                        <td>{{ (!empty($category->name)) ?  $category->name : ''}}</td>
                        </tr>
                        <tr>
                        <td>
                       @foreach($category->productCategory as $productCategory )
                         <td>{{ (!empty($productCategory->name)) ?  $productCategory->name : ''}}</td>
                          @endforeach
                        @endforeach
                        </tr>
                    @endif
                  </tbody>
                </table>
              </div>  
            </div>
            <div class="box-footer clearfix">
            </div>
          </div>
        </div>
     </div> -->
     <!-- Products -->
       
     <!--    <div class="row">
        <div class="col-md-6">
          <div>
             <div class="box box-danger same-height-widget">
                <div class="box-header with-border">
                <i class="fa fa-product-hunt"></i>
                    <h3 class="box-title">Package Product</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body" style="max-height: 274px; overflow-y: scroll;">
              <div class="table-responsive">
                <table class="table no-margin">
                    <thead>
                        <tr>
                            <th>Package #</th>
                            <th>Product</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                    @if (!empty($packages))
                        @foreach($packages as $package)
                          <tr>
                        <td>{{ (!empty($package->name)) ?  $package->name : ''}}</td>
                        </tr>
                        <tr>
                       @foreach($package->products_type as $productstype )
                            <tr>
                         <td>{{ (!empty($productstype->name)) ?  $productstype->name : ''}}</td>
                         </tr>
                          @endforeach
                        @endforeach
                        </tr>
                    @endif
                  </tbody>
                </table>
                <div class="box-footer">
                      <button type="button" id="new_tickets" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-ticket-modal">Add Ticket</button>
                </div>
              </div>  
            </div>
            <div class="box-footer clearfix">
            </div>
          </div>
        </div>
     </div>
    </div>
    </div>
    @endif
    @endforeach --> -->
    <!--  -->
@endsection
  @include('dashboard.partials.add_ticket')

@section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- ChartJS 1.0.1 -->
    <script src="/bower_components/AdminLTE/plugins/chartjs/Chart.min.js"></script>
    <!-- Admin dashboard charts ChartsJS -->
    <script src="/custom_components/js/admindbcharts.js"></script>
    <!-- matchHeight.js
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.0/jquery.matchHeight-min.js"></script>-->
    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
    <!-- Task timer -->
    <script src="/custom_components/js/tasktimer.js"></script>

    <script>
        function postData(id, data)
        {
            if (data == 'start')
                location.href = "/task/start/" + id;
            else if (data == 'pause')
                location.href = "/task/pause/" + id;
            else if (data == 'end')
                location.href = "/task/end/" + id;
        }
        ///delete this
        /*var time = 0;
        var running = 0;

        function startPause() {
            if (running == 0)
            {
                running = 1;
                increment();
                document.getElementById("startPause").innerHTML = "<i class='glyphicon glyphicon-pause'></i> Pause";
                $("#end-button").show();
            }
            else
            {
                running = 0;
                document.getElementById("startPause").innerHTML = "<i class='glyphicon glyphicon-repeat'></i> Resume";
                $("#end-button").show();
            }
        }

        function increment() {
            if (running == 1) {
                setTimeout(function() {
                    time++;
                    var mins = Math.floor(time / 10 / 60) % 60;
                    var secs = Math.floor(time / 10) % 60;
                    var tenths = time % 10;

                    if (mins < 10) {
                        mins = "0" + mins;
                    }
                    if (secs < 10) {
                        secs = "0" + secs;
                    }
                    document.getElementById("stopWatchDisplay").innerHTML = mins + ":" + secs + ":" + "0" + tenths;
                    increment();
                }, 100);
            }
        }
        */
          //Post module form to server using ajax (ADD)
            $('#add_tiket').on('click', function() {
                //console.log('strUrl');
                var strUrl = '/help_desk/ticket/add';
                var modalID = 'add-new-ticket-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    email: $('#'+modalID).find('#email').val(),
                    helpdesk_id: $('#'+modalID).find('#helpdesk_id').val(),
                    subject: $('#'+modalID).find('#subject').val(),
                    message: $('#'+modalID).find('#message').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val(),
                };
                var submitBtnID = 'new_tickets';
                var redirectUrl = '/';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The ticket has been Added successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });


        $(function () {
            // hide end button when page load
            //$("#end-button").show();
            //Initialize Select2 Elements
            $(".select2").select2();

            $('#Apply').click(function () {
                location.href = '/leave/application';
            });

             $('#ticket').click(function () {
                location.href = '/helpdesk/ticket';
            });

            
            //initialise matchHeight on widgets
            //$('.same-height-widget').matchHeight();

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

            //widgets permissions
            var isSuperuser = parseInt({{ (int) $isSuperuser }}),
                isDivHead = parseInt({{ (int) $isDivHead }}),
                isSupervisor = parseInt({{ (int) $isSupervisor }}),
                canViewCPWidget = parseInt({{ (int) $canViewCPWidget }}),
                canViewTaskWidget = parseInt({{ (int) $canViewTaskWidget }}),
                canViewEmpRankWidget = parseInt({{ (int) $canViewEmpRankWidget }});

            //Employees ranking widget
            if (canViewEmpRankWidget == 1) {
                //Load divisions drop down
                var parentDDID = '';
                var loadAllDivs = 1;
                var firstDivDDID = null;
                var parentContainer = $('#empPerformanceRankingWidgetBox');
                @foreach($divisionLevels as $divisionLevel)
                    //Populate drop down on page load
                    var ddID = '{{ 'division_level_' . $divisionLevel->level }}';
                    var postTo = '{!! route('divisionsdropdown') !!}';
                    var selectedOption = '';
                    //var divLevel = parseInt('{{ $divisionLevel->level }}');
                    var incInactive = -1;
                    var loadAll = loadAllDivs;
                    @if($loop->first)
                        var selectFirstDiv = 1;
                        var divHeadSpecific;
                        if (isSuperuser) divHeadSpecific = 0;
                        else if (isDivHead) divHeadSpecific = 1;
                        loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo, selectFirstDiv, divHeadSpecific, parentContainer);
                        //firstDivDDID = ddID;
                    @else
                        loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo, null, null, parentContainer);
                    @endif
                    //parentDDID
                    parentDDID = ddID;
                    loadAllDivs = -1;
                @endforeach

                //Load top ten performing employees (widget)
                //var topTenList = $('#emp-top-ten-list');
                //loadEmpListPerformance(topTenList, 0, 0, true);

                //Load Bottom ten performing employees (widget)
                //var bottomTenList = $('#emp-bottom-ten-list');
                //var totNumEmp = parseInt('{{ $totNumEmp }}');
                //loadEmpListPerformance(bottomTenList, 0, 0, false, true, totNumEmp);
            }
            if (canViewTaskWidget == 1)
            {
                //Load divisions drop down
                var parentDDID = '';
                var loadAllDivs = 1;
                var firstDivDDID = null;
                var parentContainer = $('#emptasksWidgetBox');
                @foreach($divisionLevels as $divisionLevel)
                    //Populate drop down on page load
                    var ddID = '{{ 'division_level_' . $divisionLevel->level }}';
                    var postTo = '{!! route('divisionsdropdown') !!}';
                    var selectedOption = '';
                    //var divLevel = parseInt('{{ $divisionLevel->level }}');
                    var incInactive = -1;
                    var loadAll = loadAllDivs;
                    @if($loop->first)
                        var selectFirstDiv = 1;
                        var divHeadSpecific = 1;
                        if (isSuperuser) divHeadSpecific = 0;
                        else if (isDivHead) divHeadSpecific = 1;
                        loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo, selectFirstDiv, divHeadSpecific, parentContainer);
                        firstDivDDID = ddID;
                    @else
                        loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo, null, null, parentContainer);
                    @endif
                    //parentDDID
                    parentDDID = ddID;
                    loadAllDivs = -1;
                @endforeach
            }

            //Draw employee performance graph
            var empID = parseInt('{{ $user->person->id }}');
            var empChartCanvas = $('#empMonthlyPerformanceChart');
            var loadingWheel = $('#loading_overlay_emp_monthly_appraisal');
            var empAppraisedMonthList = $('#emp-appraised-month-list');
            loadEmpMonthlyPerformance(empChartCanvas, empID, loadingWheel, empAppraisedMonthList);

            //Company appraisal
            if (canViewCPWidget == 1) {
                //Draw divisions performance graph [Comp Appraisal Widget]
                var rankingList = $('#ranking-list');
                var divChartCanvas = $('#divisionsPerformanceChart');
                var loadingWheelCompApr = $('#lo_company_appraisal');
                var managerID = parseInt({{ $user->person->id }});
                if (isSuperuser == 1) {
                    var divLevel = parseInt('{{ $topGroupLvl->id }}');
                    loadDivPerformance(divChartCanvas, rankingList, divLevel, null, null, loadingWheelCompApr);
                }
                else if (isDivHead == 1) {
                    var divLevel = parseInt({{ $managedDivsLevel->level }});
                    loadDivPerformance(divChartCanvas, rankingList, divLevel, null, managerID, loadingWheelCompApr);
                }
                else if (isSupervisor) {
                    $('#topLvlDivGraphAndRankingRow').hide();
                    var staffPerfRow = $('#myStaffPerformanceRankingRow');
                    staffPerfRow.show();
                    rankingList = staffPerfRow.find('#my-staff-ranking-list');
                    loadEmpListPerformance(rankingList, 0, 0, false, false, null, managerID, loadingWheelCompApr);
                }

                //show performance of sub division levels on modals (modal show) [Comp Appraisal Widget]
                var i = 1;
                for (i; i <= 4; i++) {
                    $('#sub-division-performance-modal-' + i).on('show.bs.modal', function (e) {
                        var linkDiv = $(e.relatedTarget);
                        var modalWin = $(this);
                        subDivOnShow(linkDiv, modalWin);
                    });
                    $('#sub-division-performance-modal-' + i).on('hidden.bs.modal', function (e) {
                        $('#lo-sub-division-performance-modal-' + i).show();
                    });
                }

                //show performance of employees on modals [Comp Appraisal Widget]
                $('#emp-list-performance-modal').on('show.bs.modal', function (e) {
                    var linkDiv = $(e.relatedTarget);
                    var modalWin = $(this);
                    var loadingWheelEmpList = $('#lo-emp-list-performance-modal');
                    empPerOnShow(linkDiv, modalWin);
                });
                $('#emp-list-performance-modal').on('hidden.bs.modal', function (e) {
                    $('#lo-emp-list-performance-modal').show();
                });

                //show employee monthly performance on modal [Comp Appraisal Widget]
                $('#emp-year-performance-modal').on('show.bs.modal', function (e) {
                    var linkDiv = $(e.relatedTarget);
                    var empID = parseInt(linkDiv.data('emp_id'));
                    var empName = linkDiv.data('emp_name');
                    var empChartCanvas = $('#empMonthlyPerformanceModalChart');
                    var loadingWheel = $('#lo-emp-year-performance-modal');
                    var empAppraisedMonthList = $('#emp-appraised-month-modal-list');
                    var modalWin = $(this);
                    modalWin.find('#emp-year-modal-title').html(empName + '  - Appraisal');
                    loadEmpMonthlyPerformance(empChartCanvas, empID, loadingWheel, empAppraisedMonthList);
                });
                $('#emp-year-performance-modal').on('hidden.bs.modal', function (e) {
                    $('#lo-emp-year-performance-modal').show();
                });
            }

            //Show available perks on the perks widget
            var perksWidgetList = $('#perks-widget-list');
            loadAvailablePerks(perksWidgetList);

            //leave status (widget)
            var LeaveStatus = $('#leave-status-list');
            //loadLeaveStatus();

            //Show perk details
            $('#edit-perk-modal').on('show.bs.modal', function (e) {
                var perkLink = $(e.relatedTarget);
                var modal = $(this);
                perkDetailsOnShow(perkLink, modal);
            });
            //Show success action modal
            $('#success-action-modal').modal('show');
            document.getElementById("notes").placeholder = "Enter Task Note or Summary";
            //Post end task form to server using ajax (add)
            var taskID;
            var employeeID;
            var uploadRequired;
            
             $('#end-task-modal').on('show.bs.modal', function (e) {
                var btnEnd = $(e.relatedTarget);
                taskID = btnEnd.data('task_id');
                employeeID = btnEnd.data('employee_id');
                uploadRequired = btnEnd.data('upload_required');
                var modal = $(this);
                modal.find('#task_id').val(taskID);
                modal.find('#employee_id').val(employeeID);
                modal.find('#upload_required').val(uploadRequired);
            });
            
            $('#end-task').on('click', function() {
                endTask(taskID);
                /*
                var strUrl = '/task/end';
                var formName = 'end-task-form';
                var modalID = 'end-task-modal';
                var submitBtnID = 'end-task';
                var redirectUrl = '/';
                var successMsgTitle = 'Task Ended!';
                var successMsg = 'Task has been Successfully ended!';

                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                */
            });
            $('#close-task-modal').on('show.bs.modal', function (e) {
                var btnEnd = $(e.relatedTarget);
                taskID = btnEnd.data('task_id');
                var modal = $(this);
                modal.find('#task_id').val(taskID);
            });
            
            $('#close-task').on('click', function() {
                var strUrl = '/task/check';
                var formName = 'close-task-form';
                var modalID = 'close-task-modal';
                var submitBtnID = 'close-task';
                var redirectUrl = '/';
                var successMsgTitle = 'Task Checked!';
                var successMsg = 'Task has been Successfully checked!';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            //Launch counter for running tasks
            @foreach($tasks as $task)
                increment({{ $task->task_id }});
            @endforeach
        });
    </script>
@endsection