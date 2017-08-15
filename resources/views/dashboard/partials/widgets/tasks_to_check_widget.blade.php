<!-- /Tasks List -->
<div class="box box-info">
    <div class="box-header with-border">
        <i class="ion ion-clipboard"></i>
        <h3 class="box-title">Tasks To Check</h3>

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
                    <th>Employee</th>
                    <th>Description</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @if (!empty($checkTasks))
                    @foreach($checkTasks as $checkTask)
                        <tr>
                            <td>{{ (!empty($checkTask->description)) ?  $checkTask->firstname." ".$checkTask->surname : ''}}</td>
                            <td>{{ (!empty($checkTask->description)) ?  $checkTask->description : ''}}</td>
                            <td>{{ (!empty($checkTask->status)) ?  $taskStatus[$checkTask->status] : ''}}</td>
                            <td>
                                @if(!empty($checkTask->status) && ($checkTask->status == 1 || $checkTask->status == 3))
                                    <button type="button" id="close-task-button" class="btn btn-sm btn-default btn-flat pull-right" data-toggle="modal" data-target="#close-task-modal"
                                            data-task_id="{{ $checkTask->task_id }}"">Close</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
        @if(Session('error_starting'))
            @include('tasks.partials.error_tasks', ['modal_title' => "Task Error!", 'modal_content' => session('error_starting')])
        @endif
        @include('tasks.partials.end_task')
        @include('tasks.partials.check_task')
    </div>
    <!-- /.box-body -->
    <div class="box-footer clearfix">
    </div>
    <!-- /.box-footer -->
</div>
<!-- /Tasks List End -->