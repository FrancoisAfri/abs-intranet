@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Induction Title: <b>{{$induction->induction_title}}</b></h3></br>
                    <h3 class="box-title">Client Name: <b>{{$induction->ClientName->name}}</b></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
				<div style="overflow-X:auto;">
				<table class="table table-bordered">
					 <tr><th style="width: 10px">Order #</th><th>Description</th><th>Person Responsible</th><th>Status</th><th>Notes</th><th>Document</th></tr>
                    @if (!empty($tasks))
						@foreach($tasks as $task)
						 <tr id="categories-list">
						  <td>{{ (!empty($task->order_no)) ?  $task->order_no : ''}} </td>
						  <td>{{ (!empty($task->description)) ?  $task->description : ''}} </td>
						  <td>{{ (!empty($task->hr_fist_name)) && (!empty($task->hr_surname)) ?  $task->hr_fist_name." ".$task->hr_surname : ''}} </td>
						  <td>{{ (!empty($task->status)) ?  $taskStatus[$task->status] : ''}} </td>
						  <td>{{ (!empty($task->status)) ?  $task->notes : ''}} </td>
						  @if(!empty($task->emp_doc))
							<td><a class="btn btn-default btn-flat btn-block" href="{{ Storage::disk('local')->url("tasks/$task->emp_doc") }}" target="_blank"><i class="fa fa-file-pdf-o"></i> Click Here</a></td>
                          @else
                            <td><a class="btn btn-default btn-flat btn-block"><i class="fa fa-exclamation-triangle"></i>N/A</a></td>
                          @endif
						</tr>
						@endforeach
                    @else
						<tr id="categories-list">
						<td colspan="6">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No task to display, please start by adding a new task.
                        </div>
						</td>
						</tr>
                    @endif
					</table>
					</div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                </div>
            </div>
        </div>
    </div>
@endsection