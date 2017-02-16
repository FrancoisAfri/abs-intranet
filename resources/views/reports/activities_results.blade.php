@extends('layouts.main_layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Actvity Report</h3>
                </div>
                <!-- /.box-header -->
				<form class="form-horizontal" method="POST" action="/reports/programme/print">
                 <input type="hidden" name="start_date" value="{{!empty($start_date) ? $start_date : ''}}">
                 <input type="hidden" name="end_date" value="{{!empty($end_date) ? $end_date : ''}}">
                 <input type="hidden" name="service_provider_id" value="{{!empty($service_provider_id) ? $service_provider_id : ''}}">
                 <input type="hidden" name="act_facilitator_id" value="{{!empty($act_facilitator_id) ? $act_facilitator_id : ''}}">
                 <input type="hidden" name="project_id" value="{{!empty($project_id) ? $project_id : ''}}">
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
										<th>Project Name</th>
										<th>Project Manager</th>
									</tr>
									@if(count($activities) > 0)
										@foreach($activities as $activity)
											<tr>
												<td>{{ !empty($activity->name) ? $activity->name : '' }}</td>
												<td>{{ !empty($activity->code) ? $activity->code : '' }}</td>
												<td>{{ !empty($activity->start_date) ? date('Y M d', $activity->start_date) : '' }}</td>
												<td>{{ !empty($activity->end_date) ? date('Y M d', $activity->end_date) : '' }}</td>
												<td>{{ !empty($activity->description) ? $activity->description : '' }}</td>
												<td>{{ !empty($activity->com_name) ? $activity->com_name : '' }}</td>
												<td>{{ !empty($activity->proj_name) ? $activity->proj_name : '' }}</td>
												<td>{{ !empty($activity->hr_firstname) && !empty($activity->hr_surname) ? $activity->hr_firstname.' '.$activity->hr_surname : '' }}</td>
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