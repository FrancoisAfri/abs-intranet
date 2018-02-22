@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    rel="stylesheet">
@endsection
@section('content')
    {{----}}
    {{----}}
    <div class="row">
        <div class="col-md-6">
            <div class="box box-muted same-height-widget">
                <div class="box-header with-border">
                    <i class="fa fa-comments-o"></i>
                    <h3 class="box-title"> News</h3>

                    <div class="box-tools pull-right" data-toggle="tooltip" title="" data-original-title="Status">
                        <div class="btn-group" data-toggle="btn-toggle">
                            <button type="button" class="btn btn-default btn-sm active"><i
                                        class="fa fa-square text-green"></i>
                            </button>
                            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-square text-red"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                

                <div id="myCarousel" class="carousel slide"> <!-- slider -->
                    <div class="carousel-inner">
                        <div class="active item"> <!-- item 1 -->
                            <img src="{{ $avatar }}" class="img-responsive img-thumbnail"  width="520" height="300">
                        </div> <!-- end item -->
                        <div class="item"> <!-- item 2 -->
                            <img src="{{ $avatar }}" class="img-responsive img-thumbnail"  width="520" height="300">
                        </div> <!-- end item -->
                        <div class="item"> <!-- item 3 -->
                            <img src="{{ $avatar }}" class="img-responsive img-thumbnail"  width="520" height="300">
                        </div> <!-- end item -->
                    </div> <!-- end carousel inner -->
                    <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
                    <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
                </div> <!-- end slider -->



            </div>
        </div>


        <div class="col-md-6">
            <div class="box box-muted same-height-widget">
                <div class="box-header with-border">
                    <i class="fa fa-comments-o"></i>
                    <h3 class="box-title">Campony News</h3>

                    <div class="box-tools pull-right" data-toggle="tooltip" title="" data-original-title="Status">
                        <div class="btn-group" data-toggle="btn-toggle">
                            <button type="button" class="btn btn-default btn-sm active"><i
                                        class="fa fa-square text-green"></i>
                            </button>
                            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-square text-red"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body" style="height: 274px; overflow-y: scroll;">
                    <table class="table table-striped table-hover">


                        @if (!empty($news))
                            @foreach($news as $Cmsnews)
                                <tr>
                                    <div id="categories-list">
                                        <p class="filename">
                                            <td>
                                                <div class="slimScrollDiv"
                                                     style="position: relative; width: auto; height: 150px;">
                                                    <div class="box-body chat" id="chat-box"
                                                         style="overflow: hidden; width: auto; height:auto; ">
                                                        <h4>{{ $Cmsnews->name }}:</h4>
                                                        <small class="text-muted pull-right"><i
                                                                    class="fa fa-clock-o"></i> {{ $Cmsnews->created_at }}
                                                        </small>
                                        <td>{!!$Cmsnews->summary!!}</td>
                                    </div>
                                    </p>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>
                <!-- </div> -->
                <div class="box-footer">
                </div>
            </div>
        </div>
    </div>
    {{----}}

    {{----}}

    @if($activeModules->where('code_name', 'appraisal')->first())
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
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i>
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

    @if($activeModules->where('code_name', 'appraisal')->first())
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
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row" id="myStaffPerformanceRankingRow" hidden>
                                <div class="col-md-12">
                                    <p class="text-center"><strong>My Staff Performance Ranking
                                            For {{ date('Y') }}</strong></p>
                                    <div class="no-padding" style="max-height: 420px; overflow-y: scroll;">
                                        <ul class="nav nav-pills nav-stacked products-list product-list-in-box"
                                            id="my-staff-ranking-list">
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

    @if($activeModules->where('code_name', 'appraisal')->first())
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
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i>
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
                                            <label for="{{ 'division_level_' . $divisionLevel->level }}"
                                                   class="control-label">{{ $divisionLevel->name }}</label>

                                            <select id="{{ 'division_level_' . $divisionLevel->level }}"
                                                    name="{{ 'division_level_' . $divisionLevel->level }}"
                                                    class="form-control input-sm select2"
                                                    onchange="divDDEmpPWOnChange(this, $('#emp-top-ten-list'), $('#emp-bottom-ten-list'), parseInt('{{ $totNumEmp }}'), $('#loading_overlay_emp_performance_ranking'))"
                                                    style="width: 100%;">
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
                                    <ul class="nav nav-pills nav-stacked products-list product-list-in-box"
                                        id="emp-top-ten-list">
                                    </ul>
                                </div>
                            </div>
                            <!-- Bottom ten -->
                            <div class="col-sm-4">
                                <p class="text-center">
                                    <strong class="label label-danger"><i class="fa fa-level-down"></i> Bottom 10
                                        Employees</strong>
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

    @if($activeModules->whereIn('code_name', ['induction', 'tasks', 'meeting'])->first())
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

    @if($activeModules->where('code_name', 'appraisal')->first())
        <div class="row">
            <div class="col-md-12">
                <!-- Available Perks Widgets -->
                <div class="box box-warning same-height-widget">
                    <div class="box-header with-border">
                        <h3 class="box-title">Available Perks</h3>

                        <div class="box-tools pull-right">
                            <!-- <span class="label label-warning">8 New Members</span> -->
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i>
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
    @if($activeModules->where('code_name', 'induction')->first())
        <div class="row">
            <div class="col-md-12">
                <div class="box box-muted same-height-widget">
                    <div class="box-header with-border">
                        <i class="material-icons">school</i>
                        <h3 class="box-title">Induction</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body" style="max-height: 274px; overflow-y: scroll;">
                        <table class="table table-striped table-bordered">

                            <tr>
                                <!--  <th style="width: 10px">#</th> -->
                                <th>Induction Name</th>
                                <th>KAM</th>
                                <th>Client</th>
                                <th style="text-align: center;"><i class="fa fa-info-circle"></i> Status</th>
                            </tr>

                            @if (!empty($ClientInduction))
                                @foreach($ClientInduction as $Induction)
                                    <tr>
                                    <!--  <td>{{ $Induction->completed_task }}</td> -->
                                        <td>{{ (!empty($Induction->induction_title)) ?  $Induction->induction_title : ''}}</td>
                                        <td>{{ !empty($Induction->firstname) && !empty($Induction->surname) ? $Induction->firstname.' '.$Induction->surname : '' }}</td>
                                    <!-- <td>{{ (!empty($Induction->create_by)) ?  $Induction->create_by : ''}}</td> -->
                                        <td>{{ (!empty($Induction->company_name)) ?  $Induction->company_name : ''}}</td>
                                        <td>
                                            <div class="progress xs">
                                                <div class="progress-bar progress-bar-warning  progress-bar-striped"
                                                     role="progressbar"
                                                     aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                                     style="width:{{ $Induction->completed_task == 0 ? 0 : ($Induction->completed_task/$Induction->total_task * 100)  }}%"> {{  (round($Induction->completed_task == 0 ? 0 : ($Induction->completed_task/$Induction->total_task * 100)))}}
                                                    %
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                    </div>
                    <!-- </div> -->
                </div>
            </div>
        </div>
    @endif
    <!--  -->
    @if($activeModules->where('code_name', 'leave')->first())
        <div class="row">
            <div class="col-md-6">
                <!-- /Tasks List -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="fa fa-hourglass"></i>
                        <h3 class="box-title">Leave Balance</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body" style="max-height: 274px; overflow-y: scroll;">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                <tr>
                                    <th>Leave Type</th>
                                    <th style="text-align: right;"><i class="material-icons">account_balance_wallet</i>Leave
                                        Balance
                                    </th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                @if (!empty($balances))
                                    @foreach($balances as $balance)
                                        <tr>
                                            <td>{{ (!empty($balance->leavetype)) ?  $balance->leavetype : ''}}</td>
                                            <td style="text-align: right;">{{ (!empty($balance->leave_balance)) ?  $balance->leave_balance / 8: 0}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            <div class="box-footer">
                                <!--  <button id="back_to_user_search" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back to search</button> -->
                                <button id="Apply" class="btn btn-primary pull-right"><i
                                            class="fa fa-cloud-download"></i> Apply For Leave
                                </button>
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
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body" style="max-height: 274px; overflow-y: scroll;">
                        <div class="table-responsive">
                            <table class="table no-margin">

                                <thead>
                                <tr>
                                    <th><i class="material-icons">shop_two</i> Leave Type</th>
                                    <th><i class="fa fa-calendar-o"></i> Date From</th>
                                    <th><i class="fa fa-calendar-o"></i> Date To</th>
                                    <th style="text-align: right;"><i class="fa fa-info-circle"></i> Status</th>
                                    <th style="text-align: right;"><i class="fa fa-info-circle"></i> Rejection Reason
                                    </th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (!empty($application))
                                    @foreach($application as $app)
                                        <tr>
                                            <td style="vertical-align: middle;">{{ (!empty($app->leavetype)) ?  $app->leavetype : ''}}</td>
                                            <td style="vertical-align: middle;">
                                                {{ !empty($app->start_date) ? date('d M Y ', $app->start_date) : '' }}
                                            </td>
                                            <td style="vertical-align: middle;">{{ !empty($app->end_date) ? date('d M Y ', $app->end_date) : '' }}</td>
                                            <td style="text-align: right; vertical-align: middle;">
                                                {{ (!empty($app->status) && $app->status > 0) ? $leaveStatusNames[$app->status]." ".$app->reject_reason  : ''}}
                                            </td>
                                            <td style="text-align: right; vertical-align: middle;">
                                                {{ !empty($app->reject_reason) ? $app->reject_reason  : 'N/A'}}
                                            </td>
                                            <td class="text-right" style="vertical-align: middle;">
                                                @if(in_array($app->status, [2, 3, 4, 5]))
                                                    <button class="btn btn-xs btn-warning"
                                                            title="Cancel Leave Application" data-toggle="modal"
                                                            data-target="#cancel-leave-application-modal"
                                                            data-leave_application_id="{{ $app->id }}"><i
                                                                class="fa fa-times"></i></button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Include cancellation reason modal -->
                    @include('dashboard.partials.cancel_leave_application_modal')
                </div>
            </div>

            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="ion ion-ios-people-outline"></i>
                        <h3 class="box-title">People On Leave This Month</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding" style="max-height: 180px; overflow-y: scroll;">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Employee</th>
                                <th class="text-center">From</th>
                                <th class="text-center">To</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($onLeaveThisMonth as $employee)
                                <tr>
                                    <td style="vertical-align: middle;"
                                        class="{{ ($employee->is_on_leave_today) ? 'bg-primary' : '' }}"
                                        nowrap>{{ $loop->iteration }}.
                                    </td>
                                    <td style="vertical-align: middle;"
                                        class="{{ ($employee->is_on_leave_today) ? 'bg-primary' : '' }}">
                                        <img src="{{ $employee->profile_pic_url }}" class="img-circle"
                                             alt="Employee's Photo"
                                             style="width: 25px; height: 25px; border-radius: 50%; margin-right: 10px; margin-top: -2px;">
                                        <span>{{ $employee->full_name }}</span>
                                    </td>
                                    <td style="vertical-align: middle;"
                                        class="text-center {{ ($employee->is_on_leave_today) ? 'bg-primary' : '' }}">{{ ($employee->start_time) ? date('d M Y H:i', $employee->start_time) : (($employee->start_date) ? date('d M Y', $employee->start_date) : '') }}</td>
                                    <td style="vertical-align: middle;"
                                        class="text-center {{ ($employee->is_on_leave_today) ? 'bg-primary' : '' }}">{{ ($employee->end_time) ? date('d M Y H:i', $employee->end_time) : (($employee->end_date) ? date('d M Y', $employee->end_date) : '') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    @endif
@endsection

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
        function postData(id, data) {
            if (data == 'start')
                location.href = "/task/start/" + id;
            else if (data == 'pause')
                location.href = "/task/pause/" + id;
            else if (data == 'end')
                location.href = "/task/end/" + id;
        }

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

            @if($activeModules->where('code_name', 'appraisal')->first())
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

            if (canViewTaskWidget == 1) {
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

            //Show perk details
            $('#edit-perk-modal').on('show.bs.modal', function (e) {
                var perkLink = $(e.relatedTarget);
                var modal = $(this);
                perkDetailsOnShow(perkLink, modal);
            });
            @endif

            @if($activeModules->where('code_name', 'leave')->first())
            //leave status (widget)
            var LeaveStatus = $('#leave-status-list');
            //loadLeaveStatus();

            //leave cancellation reason form on show
            var cancelApplicationModal = $('#cancel-leave-application-modal');
            var leaveApplicationID;
            cancelApplicationModal.on('show.bs.modal', function (e) {
                //console.log('gets here');
                var btnCancel = $(e.relatedTarget);
                leaveApplicationID = btnCancel.data('leave_application_id');
                //var modal = $(this);
                //modal.find('#task_id').val(taskID);
            });

            //perform leave application cancellation
            cancelApplicationModal.find('#cancel-leave-application').on('click', function () {
                var strUrl = '/leave/application/' + leaveApplicationID + '/cancel';
                var formName = 'cancel-leave-application-form';
                var modalID = 'cancel-leave-application-modal';
                var submitBtnID = 'cancel-leave-application';
                var redirectUrl = '/';
                var successMsgTitle = 'Leave Application Cancelled!';
                var successMsg = 'Your leave application has been cancelled!';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
            @endif

            @if($activeModules->whereIn('code_name', ['induction', 'tasks', 'meeting'])->first())
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

            $('#end-task').on('click', function () {
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

            $('#close-task').on('click', function () {
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
            @endif

            //Show success action modal
            //$('#success-action-modal').modal('show');
        });
    </script>
@endsection