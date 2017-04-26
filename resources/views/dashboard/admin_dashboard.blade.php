@extends('layouts.main_layout')

@section('content')
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
                        <div class="col-md-12">
                            <p class="text-center">
                                <strong>My Performance For {{ date('Y') }}</strong>
                            </p>

                            <div class="chart">
                                <!-- Sales Chart Canvas-->
                                <canvas id="empMonthlyPerformanceChart" style="height: 220px;"></canvas>
                            </div>
                            <!-- /.chart-responsive -->
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
            </div>
            <!-- /.box Employee Monthly performance Widget -->
        </div>
        <!-- /.col -->
    </div>
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
    <div class="row">
        <div class="col-md-4">
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
        <div class="col-md-4">
            <!-- Top Ten Employees Performance Ranking Widget -->
            <div class="box box-success same-height-widget">
                <div class="box-header with-border">
                    <h3 class="box-title">Employees Ranking</h3>

                    <div class="box-tools pull-right">
                        <span class="label label-success"><i class="fa fa-level-up"></i> Top 10 Employees</span>
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <div class="no-padding" style="max-height: 274px; overflow-y: scroll;">
                        <ul class="nav nav-pills nav-stacked products-list product-list-in-box" id="emp-top-ten-list">
                        </ul>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.Top Ten Employees Performance Ranking Widgets -->
        </div>
        <div class="col-md-4">
            <!-- Bottom Ten Employees Performance Ranking Widgets -->
            <div class="box box-danger same-height-widget">
                <div class="box-header with-border">
                    <h3 class="box-title">Employees Ranking</h3>

                    <div class="box-tools pull-right">
                        <span class="label label-danger"><i class="fa fa-level-down"></i> Bottom 10 Employees</span>
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <div class="no-padding" style="max-height: 274px; overflow-y: scroll;">
                        <ul class="nav nav-pills nav-stacked products-list product-list-in-box"
                            id="emp-bottom-ten-list">
                        </ul>
                    </div>
                    <!-- /.users-list -->
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.Bottom Ten Employees Performance Ranking Widgets -->
        </div>
    </div>
    @endsection

    @section('page_script')
            <!-- ChartJS 1.0.1 -->
    <script src="/bower_components/AdminLTE/plugins/chartjs/Chart.min.js"></script>
    <!-- Admin dashboard charts ChartsJS -->
    <script src="/custom_components/js/admindbcharts.js"></script>
    <!-- matchHeight.js
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.0/jquery.matchHeight-min.js"></script>-->

    <script>
        $(function () {
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

            //Draw employee performance graph
            var empID = parseInt('{{ $user->person->id }}');
            var empChartCanvas = $('#empMonthlyPerformanceChart');
            loadEmpMonthlyPerformance(empChartCanvas, empID);

            //Company appraisal
            var isSuperuser = parseInt({{ (int) $isSuperuser }}),
                    isDivHead = parseInt({{ (int) $isDivHead }}),
                    isSupervisor = parseInt({{ (int) $isSupervisor }}),
                    canViewCPWidget = parseInt({{ (int) $canViewCPWidget }});
            if (canViewCPWidget == 1) {
                //Draw divisions performance graph [Comp Appraisal Widget]
                var rankingList = $('#ranking-list');
                var divChartCanvas = $('#divisionsPerformanceChart');
                var managerID = parseInt({{ $user->person->id }});
                if (isSuperuser == 1) {
                    var divLevel = parseInt('{{ $topGroupLvl->id }}');
                    loadDivPerformance(divChartCanvas, rankingList, divLevel);
                }
                else if (isDivHead == 1) {
                    var divLevel = parseInt({{ $managedDivsLevel->level }});
                    loadDivPerformance(divChartCanvas, rankingList, divLevel, null, managerID);
                }
                else if (isSupervisor) {
                    $('#topLvlDivGraphAndRankingRow').hide();
                    var staffPerfRow = $('#myStaffPerformanceRankingRow');
                    staffPerfRow.show();
                    rankingList = staffPerfRow.find('#my-staff-ranking-list');
                    loadEmpListPerformance(rankingList, 0, 0, false, false, null, managerID);
                }

                //show performance of sub division levels on modals (modal show) [Comp Appraisal Widget]
                var i = 1;
                for (i; i <= 4; i++) {
                    $('#sub-division-performance-modal-' + i).on('show.bs.modal', function (e) {
                        var linkDiv = $(e.relatedTarget);
                        var modalWin = $(this);
                        subDivOnShow(linkDiv, modalWin);
                    });
                }

                //show performance of employees on modals [Comp Appraisal Widget]
                $('#emp-list-performance-modal').on('show.bs.modal', function (e) {
                    var linkDiv = $(e.relatedTarget);
                    var modalWin = $(this);
                    empPerOnShow(linkDiv, modalWin);
                });

                //show employee monthly performance on modal [Comp Appraisal Widget]
                $('#emp-year-performance-modal').on('show.bs.modal', function (e) {
                    var linkDiv = $(e.relatedTarget);
                    var empID = parseInt(linkDiv.data('emp_id'));
                    var empName = linkDiv.data('emp_name');
                    var empChartCanvas = $('#empMonthlyPerformanceModalChart');
                    var modalWin = $(this);
                    modalWin.find('#emp-year-modal-title').html(empName + '  - Appraisal');
                    loadEmpMonthlyPerformance(empChartCanvas, empID);
                });
            }

            //Show available perks on the perks widget
            var perksWidgetList = $('#perks-widget-list');
            loadAvailablePerks(perksWidgetList);

            //Load top ten performing employees (widget)
            var topTenList = $('#emp-top-ten-list');
            loadEmpListPerformance(topTenList, 0, 0, true);

            //Load Bottom ten performing employees (widget)
            var bottomTenList = $('#emp-bottom-ten-list');
            var totnumEmp = parseInt('{{ $totNumEmp }}');
            loadEmpListPerformance(bottomTenList, 0, 0, false, true, totnumEmp);

            //Show perk details
            $('#edit-perk-modal').on('show.bs.modal', function (e) {
                var perkLink = $(e.relatedTarget);
                var modal = $(this);
                perkDetailsOnShow(perkLink, modal);
            });
        });
    </script>
@endsection
