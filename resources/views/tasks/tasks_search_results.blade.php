@extends('layouts.main_layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Search Results</h3>
                </div>
                <!-- /.box-header -->
				<form class="form-horizontal" method="POST">
                 	{{ csrf_field() }}
                <div class="box-body">
                    <!-- Collapsible section containing the amortization schedule -->
                    <div class="box-group" id="accordion">
                        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                        <div class="box-body">
								<table class="table table-striped">
									<tr>
										<th>Task Description</th>
										<th>Person Responsible</th>
										<th>Status</th>
										<th>Expected Start Date</th>
										<th>Actual Start Date</th>
										<th>Due Date</th>
										<th>Date Completed</th>
									</tr>
									@if(count($employeesTasks) > 0)
										@foreach($employeesTasks as $employeesTask)
											<tr>
												<td style="width:200px;">{{ !empty($employeesTask->description) ? $employeesTask->description : '' }}</td>
												<td>{{ !empty($employeesTask->firstname) && !empty($employeesTask->surname) ? $employeesTask->firstname.' '.$employeesTask->surname : '' }}</td>
												<td>{{ (!empty($employeesTask->status)) ?  $taskStatus[$employeesTask->status] : ''}} </td>
												<td>{{ !empty($employeesTask->start_date) ? date('Y M d', $employeesTask->start_date) : '' }}</td>
												<td>{{ !empty($employeesTask->date_started) ? date('Y M d', $employeesTask->date_started) : '' }}</td>
												<td>{{ !empty($employeesTask->due_date) ? date('Y M d', $employeesTask->due_date) : '' }}</td>
												<td>{{ !empty($employeesTask->date_completed) ? date('Y M d', $employeesTask->date_completed) : '' }}</td>
												
											</tr>
										@endforeach
									@endif
								</table>
								<div class="row no-print">
									<div class="col-xs-12">
									<button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Back</button>
									</div>
								</div>
								<!-- End amortization /table -->
							</div>
                    </div>
                    <!-- /. End Collapsible section containing the amortization schedule -->
                </div>
				</form>
            </div>
        </div>
    </div>
@endsection
@section('page_script')
    <script>
        $(function () {
            //Cancel button click event
            $('#back_button').click(function () {
                location.href = '/tasks/search_task';
            });
        });
    </script>
@endsection