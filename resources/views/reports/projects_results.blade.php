@extends('layouts.main_layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Programme Report</h3>
                </div>
                <!-- /.box-header -->
				<form class="form-horizontal" method="POST" action="/reports/projects/print">
                 <input type="hidden" name="start_date" value="{{!empty($start_date) ? $start_date : ''}}">
                 <input type="hidden" name="end_date" value="{{!empty($end_date) ? $end_date : ''}}">
                 <input type="hidden" name="service_provider_id" value="{{!empty($service_provider_id) ? $service_provider_id : ''}}">
                 <input type="hidden" name="facilitator_id" value="{{!empty($facilitator_id) ? $facilitator_id : ''}}">
                 <input type="hidden" name="programme_id" value="{{!empty($programme_id) ? $programme_id : ''}}">
                 <input type="hidden" name="manager_id" value="{{!empty($manager_id) ? $manager_id : ''}}">
					{{ csrf_field() }}
                <div class="box-body">
                    <!-- Collapsible section containing the amortization schedule -->
                    <div class="box-group" id="accordion">
                        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                        <div class="panel box box-primary">
                            <div class="box-body">
								<table class="table table-striped">
									<tr>
										<th>Name</th>
										<th>Code</th>
										<th>Start Date</th>
										<th>End Date</th>
										<th>Brief Description</th>
										<th>Service Provider</th>
										<th>Manager</th>
										<th>Programme</th>
									</tr>
									@if(count($projects) > 0)
										@foreach($projects as $project)
											<tr>
												<td>{{ !empty($project->name) ? $project->name : '' }}</td>
												<td>{{ !empty($project->code) ? $project->code : '' }}</td>
												<td>{{ !empty($project->start_date) ? date('Y M d', $project->start_date) : '' }}</td>
												<td>{{ !empty($project->end_date) ? date('Y M d', $project->end_date) : '' }}</td>
												<td>{{ !empty($project->description) ? $project->description : '' }}</td>
												<td>{{ !empty($project->com_name) ? $project->com_name : '' }}</td>
												<td>{{ !empty($project->hr_firstname) && !empty($project->hr_surname) ? $project->hr_firstname.' '.$project->hr_surname : '' }}</td>
												<td>{{ !empty($project->prog_name) && !empty($project->hr_surname) ? $project->prog_name : '' }}</td>
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