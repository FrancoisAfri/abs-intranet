<!-- /Tasks List -->
	<div class="box-header">
		<h3 class="box-title"><i class="fa fa-hourglass"></i> Tasks List</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
			<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
		</div>
	</div>
    <!-- /.box-header -->
    <div class="box-body" style="max-height: 274px; overflow-y: scroll;">
        <div class="table-responsive">
            <table class="table no-margin">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Description</th>
                    <th>Duration</th>
                    <th>Due Date</th>
                    <th>Client Name</th>
                    <th>Document</th>
                    <th></th>
                    <!--<th></th>-->
                </tr>
                </thead>
                <tbody>
                @if (!empty($tasks))
                    <script>
                        var time = [];
                        var running = [];
                    </script>
                    @foreach($tasks as $task)
                        <script>
                            running[{{ $task->task_id }}] = {{ ($task->status == 2) ? 1 : 0 }};
                            time[{{ $task->task_id }}] = {{ ($task->status == 2) ? (($task->date_paused) ? (time() - $task->date_paused) * 10 : (time() - $task->date_started) * 10) : $task->duration * 10 }};
                            console.log('Assigned time: ' + 'taskID = ' + {{$task->task_id}} + ', running = ' + running[{{ $task->task_id }}] + ', time = ' + time[{{ $task->task_id }}]);
                        </script>
                        <tr>
                            <td>{{ (!empty($task->order_no)) ?  $task->order_no : ''}}</td>
                            <td>{{ (!empty($task->description)) ?  $task->description : ''}}</td>
                            <td>{{ (!empty($task->manager_duration)) ?  $task->manager_duration : ''}}</td>
                            <td>{{ (!empty($task->due_date)) ?  date('Y-m-d',$task->due_date) : ''}}</td>
                            <td>{{ (!empty($task->client_name)) ?  $task->client_name : ''}}</td>
                            <td>@if(!empty($task->document_on_task))
									<a class="btn btn-default btn-flat btn-block" href="{{ Storage::disk('local')->url("tasks/$task->document_on_task") }}" target="_blank"><i class="fa fa-file-pdf-o"></i> Click Here</a>
								@else
									<a class="btn btn-default btn-flat btn-block"><i class="fa fa-exclamation-triangle"></i>No Document Was Uploaded</a>
								@endif
							</td>
                            <td>
                                <div id="controls">
                                    <input type="text" id="{{ $task->task_id . 'stopWatchDisplay' }}" style="font-weight:bold; font-family:cursive; width: 80px; height: 23px;" value="{{ $task->human_duration }}" class="input-sm" disabled>
                                    <button id="{{ $task->task_id . 'startPause' }}" onClick="startPause({{ $task->task_id }})"
                                            class="btn btn-xs btn-success btn-flat">
                                        @if($task->status == 1)
                                            <i class="fa fa-play"></i> Start
                                        @elseif($task->status == 2)
                                            <i class="fa fa-pause"></i> Pause
                                        @elseif($task->status == 3)
                                            <i class="fa fa-repeat"></i> Resume
                                        @endif
                                    </button>
                                    <button id="end-button" class="btn btn-xs btn-warning btn-flat{{ ($task->status == 1) ? ' hidden' : '' }}"
                                            data-toggle="modal" data-target="#end-task-modal"
                                            data-task_id="{{ $task->task_id }}" data-employee_id="{{ $task->employee_id }}"
                                            data-upload_required="{{ $task->upload_required }}"><i class="fa fa-stop"></i> End</button>
                                    @if($task->status != 1)
                                    @endif
                                </div>
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
    </div>
    <!-- /.box-body -->
    <div class="box-footer clearfix">
    </div>
    <!-- /.box-footer -->
<!-- /Tasks List End -->