@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
@endsection
@section('content') 
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
    <!-- Ticket Widget -->
    
     <!--  -->
     <div class="row">
        <div class="col-md-6">
          <div>
             <div class="box box-warning same-height-widget">
                <div class="box-header with-border">
                <i class="fa fa-product-hunt"></i>
                    <h3 class="box-title">view Products</h3>
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
                           <th><i class="fa fa-id-badge"></i> Account Number</th>
                           <th><i class="fa fa-building-o"></i> Company</th>
                           <th><i class="fa fa-user"></i> Contact Person</th>
                           <th><i class="fa fa-calendar-o"></i> Date Created</th>      
                        </tr>
                    </thead>

                    <tbody>
                    @if (!empty($account))
                        @foreach($account as $accounts)
                          <tr>
                         	 <td>{{ ($accounts->account_number) ? $accounts->account_number : '' }}</td>
                             <td>{{ ($accounts->company) ? $accounts->company->name : '[individual]' }}</td>
                             <td>{{ ($accounts->client) ? $accounts->client->full_name : '' }}</td>
                             <td>{{ ($accounts->start_date) ? date('d/m/Y', $accounts->start_date) : '' }}</td>
                          </tr>
                        @endforeach
                    @endif
                  </tbody>
                </table>
               
              </div>
                <!--  -->
                <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                <tr>
                                    <td></td>
                                    <th>Quote #</th>
                                    <th>Date Ordered</th>
                                    <th>Payment Option</th>
                                    <th>Status</th>
                                    <th class="text-right">Cost</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($account as $quotation)
                                    <tr>
                                        <td width="5px"><i class="fa fa-caret-down"></i></td>
                                        <td><a href="/">{{ ($quotation->quote_number) ? $quotation->quote_number : $quotation->id }}</a></td>
                                        <td>{{ $quotation->created_at }}</td>
                                        <td>{{ $quotation->str_payment_option }}</td>
                                        <!--  -->
                                        <td class="text-right"></td>
                                    </tr>
                                    @if($quotation && (count($quotation->products) > 0 || count($quotation->packages) > 0))
                                        <tr>
                                            <td></td>
                                            <td class="warning" colspan="5">
                                                <ul class="list-inline">
                                                    @if(count($quotation->products) > 0)
                                                        @foreach($quotation->products as $product)
                                                            <li class="list-inline-item"><i class="fa fa-square-o"></i> {{ $product->name }}</li> |
                                                        @endforeach
                                                    @endif

                                                    @if(count($quotation->packages) > 0)
                                                        @foreach($quotation->packages as $package)
                                                            <li class="list-inline-item"><i class="fa fa-object-group"></i> {{ $package->name }}</li> |
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
            </div>
            <div class="box-footer clearfix">
            </div>
          </div>
        </div>
     </div>
    </div>

   
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