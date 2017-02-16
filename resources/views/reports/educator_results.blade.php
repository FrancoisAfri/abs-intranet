@extends('layouts.main_layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Educators Report</h3>
                </div>
                <!-- /.box-header -->
				<form class="form-horizontal" method="POST" action="/reports/educators/print">
                 <input type="hidden" name="start_date" value="{{!empty($start_date) ? $start_date : ''}}">
                 <input type="hidden" name="school_id" value="{{!empty($school_id) ? $school_id : ''}}">
                 <input type="hidden" name="project_id" value="{{!empty($project_id) ? $project_id : ''}}">
                 <input type="hidden" name="highest_qualification" value="{{!empty($highest_qualification) ? $highest_qualification : ''}}">
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
										<th>Qualification</th>
										<th>Engagement Date</th>
										<th>Cell Number</th>
										<th>School</th>
										<th>Project</th>
									</tr>
									@if(count($educators) > 0)
										@foreach($educators as $educator)
											<tr>
												<td>{{ !empty($educator->first_name) ? $educator->first_name : '' }}</td>
												<td>{{ !empty($educator->surname) ? $educator->surname : '' }}</td>
												<td>{{ !empty($educator->highest_qualification) ? $educator->highest_qualification : '' }}</td>
												<td>{{ !empty($educator->engagement_date) ? date('Y M d', $educator->engagement_date) : '' }}</td>
												<td>{{ !empty($educator->cell_number) ? $educator->cell_number : '' }}</td>
												<td>{{ !empty($educator->com_name) ? $educator->com_name : '' }}</td>
												<td>{{ !empty($educator->pro_name) ? $educator->pro_name : '' }}</td>
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