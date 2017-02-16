@extends('layouts.main_layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Learners Report</h3>
                </div>
                <!-- /.box-header -->
				<form class="form-horizontal" method="POST" action="/reports/learners/print">
                 <input type="hidden" name="start_date" value="{{!empty($start_date) ? $start_date : ''}}">
                 <input type="hidden" name="school_id" value="{{!empty($school_id) ? $school_id : ''}}">
                 <input type="hidden" name="project_id" value="{{!empty($project_id) ? $project_id : ''}}">
                 <input type="hidden" name="grade" value="{{!empty($grade) ? $grade : ''}}">
					{{ csrf_field() }}
                <div class="box-body">
                    <!-- Collapsible section containing the amortization schedule -->
                    <div class="box-group" id="accordion">
                        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                        <div class="panel box box-primary">
                            <div class="box-body">
								<table class="table table-striped">
									<tr>
										<th>FirstName</th>
										<th>Surname</th>
										<th>Grade</th>
										<th>Date Started</th>
										<th>Cell Number</th>
										<th>School</th>
										<th>Project</th>
										
									</tr>
									@if(count($learners) > 0)
										@foreach($learners as $learner)
											<tr>
												<td>{{ !empty($learner->first_name) ? $learner->first_name : '' }}</td>
												<td>{{ !empty($learner->surname) ? $learner->surname : '' }}</td>
												<td>{{ !empty($learner->grade) ? $learner->grade : '' }}</td>
												<td>{{ !empty($learner->date_started_project) ? date('Y M d', $learner->date_started_project) : '' }}</td>
												<td>{{ !empty($learner->cell_number) ? $learner->cell_number : '' }}</td>
												<td>{{ !empty($learner->com_name) ? $learner->com_name : '' }}</td>
												<td>{{ !empty($project->pro_name) ? $project->pro_name : '' }}</td>
											</tr>
										@endforeach
									@endif
								</table>
								<div class="row no-print">
									<div class="col-xs-12">
										<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i>Print report</button>
									</div>
								</div>
								<!-- End amortization /table -->
							</div>
                        </div>
                    </div>
                    <!-- /. End Collapsible section containing the amortization schedule -->
                </div>
				</form>
            </div>
        </div>
    </div>
@endsection