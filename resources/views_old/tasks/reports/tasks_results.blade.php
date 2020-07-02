@extends('layouts.main_layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Tasks Report</h3>
                </div>
                <!-- /.box-header -->
				<form class="form-horizontal" method="POST" action="/task/meeting/print">
                 <input type="hidden" name="meeting_name" value="{{!empty($meeting_name) ? $meeting_name : ''}}">
                 <input type="hidden" name="completion_date" value="{{!empty($completion_date) ? $completion_date : ''}}">
                 <input type="hidden" name="creation_date" value="{{!empty($creation_date) ? $creation_date : ''}}">
                 <input type="hidden" name="employee_id" value="{{!empty($employee_id) ? $employee_id : ''}}">
                 <input type="hidden" name="status" value="{{!empty($status) ? $status : ''}}">
					{{ csrf_field() }}
                <div class="box-body">
					<div class="box-body">
						<table class="table table-striped">
							<tr>
								<th>Meeting Title</th>
								<th>Task Description</th>
								<th>Person Responsible</th>
								<th>Status</th>
								<th>Expected Start Date</th>
								<th>Actual Start Date</th>
								<th>Due Date</th>
								<th>Date Completed</th>
								<th>Duration</th>
							</tr>
							@if(count($employeesTasks) > 0)
								@foreach($employeesTasks as $employeesTask)
									<tr>
										<td>{{ !empty($employeesTask->meeting_name) ? $employeesTask->meeting_name : '' }}</td>
										<td style="width:200px;">{{ !empty($employeesTask->description) ? $employeesTask->description : '' }}</td>
										<td>{{ !empty($employeesTask->firstname) && !empty($employeesTask->surname) ? $employeesTask->firstname.' '.$employeesTask->surname : '' }}</td>
										<td>{{ (!empty($employeesTask->status)) ?  $taskStatus[$employeesTask->status] : ''}} </td>
										<td>{{ !empty($employeesTask->start_date) ? date('Y M d', $employeesTask->start_date) : '' }}</td>
										<td>{{ !empty($employeesTask->date_started) ? date('Y M d', $employeesTask->date_started) : '' }}</td>
										<td>{{ !empty($employeesTask->due_date) ? date('Y M d', $employeesTask->due_date) : '' }}</td>
										<td>{{ !empty($employeesTask->date_completed) ? date('Y M d', $employeesTask->date_completed) : '' }}</td>
										<td>{{ !empty($employeesTask->duration) ? gmdate("H:i:s", $employeesTask->duration) : '' }}</td>
									</tr>
								@endforeach
							@endif
							<tr>
								<th>Meeting Title</th>
								<th>Task Description</th>
								<th>Person Responsible</th>
								<th>Status</th>
								<th>Expected Start Date</th>
								<th>Actual Start Date</th>
								<th>Due Date</th>
								<th>Date Completed</th>
								<th>Duration</th>
							</tr>
						</table>
						<div class="row no-print">
							<div class="col-xs-12">
								<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print report</button>
							</div>
						</div>
						<!-- End amortization /table -->
					</div>
				</div>
				</form>
            </div>
        </div>
    </div>
@endsection