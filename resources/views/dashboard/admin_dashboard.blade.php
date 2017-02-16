@extends('layouts.main_layout')

@section('content')
	<div class="row">
		<div class="col-lg-4 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-green">
				<div class="inner">
					<h3>{{ ($newprogrammes) ? $newprogrammes : '0' }}  <sup style="font-size: 20px"></sup></h3>

					<p>New Programmes (git test)</p>
				</div>
				<div class="icon">
					<i class="ion ion-stats-bars"></i>
				</div>

				@if($canView)
				<a href="/programme/search/1" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				@else
					<a class="small-box-footer">&nbsp;</a>
				@endif

				
			</div>
		</div>

		<div class="col-lg-4 col-xs-6">
			<!-- small box -->

			<div class="small-box bg-aqua">
				<div class="inner">
					
					<h3>{{ ($newprojects) ? $newprojects : '0' }}</h3>

					<p>New Projects</p>
				</div>
				<div class="icon">
					<i class="ion ion-stats-bars"></i>
				</div>

				@if($canViewProject)
				<a href="/project/search/1" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				@else
					<a class="small-box-footer">&nbsp;</a>
				@endif
				
				
				
			</div>
		</div>
		<!-- ./col -->
		<div class="col-lg-4 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-yellow">
				<div class="inner">
					<h3>{{ ($newactivities) ? $newactivities : '0' }}</h3>

					<p>New Activities</p>
				</div>
				<div class="icon">
					<i class="ion ion-stats-bars"></i>
				</div>
				@if($canViewProject)
				<a href="/activity/search/1" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				@else
					<a class="small-box-footer">&nbsp;</a>
				@endif
				
			</div>
		</div>
		<!-- ./col -->
		
		<!-- ./col -->
		<!-- <div class="col-lg-3 col-xs-6">
			<!-- small box 
			<div class="small-box bg-red">
				<div class="inner">
					<h3>65</h3>

					<p>New Facilitators</p>
				</div>
				<div class="icon">
					<i class="ion ion-person-add"></i>
				</div>
				<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div> -->
		<!-- ./col -->
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Latest Projects</h3>

					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
					</div>
					<!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					
					 <div class="box-body">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <th>Project Name</th>
                                <th>Project Code</th>
                                <th> Start Date</th>
                                <th >End Date</th>
                                <th style="text-align: right;">Status</th>
                             </tr>
                        		@foreach($projects as $project)
                                <tr>
                                	<td>
                                		@if($canViewProject || $user->id == $project->user_id)
                                			<a href="{{ '/project/view/' . $project->id }}">{{ ($project->name) ? $project->name : '' }}</a>
                                		@else
                                			{{ ($project->name) ? $project->name : '' }}
                                		@endif
                                	</td>
                                	<td>{{ ($project->code) ? $project->code : '' }}</td>
                                    <td>{{ ($project->start_date) ? date('d/m/Y', $project->start_date) : '' }}</td>
                                    <td>{{ ($project->end_date) ? date('d/m/Y', $project->end_date) : '' }}</td>
                                    <td style="text-align: right;"><span class="label {{ $statusLabels[$project->status] }} pull-right">{{ ($project->status) ? $status_array[$project->status] : '' }}</span></td>
                                    <!-- <span class="label label-danger pull-right">{{ ($project->status) ? $status_array[$project->status] : '' }}</span> -->
									
									

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->
		</div>
		<div class="col-md-6">
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Latest Activies</h3>

					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
					</div>
					<!-- /.box-tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					
					 <div class="box-body">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <th>Activity Name</th>
                                <th>Activity Code</th>
                                <th> Start Date</th>
                                <th >End Date</th>
                                <th style="text-align: right;">Status</th>
                             </tr>
                        		@foreach($activity as $activitities)
                                <tr>
                                	<td>
                                		@if($canViewProject || ($activitities->projec && $user->id == $activitities->project->user_id))
                                			<a href="{{ '/education/activity/' . $activitities->id . '/view'}}">{{ ($activitities->name) ? $activitities->name : '' }}</a>
                                		@else
                                			{{ ($activitities->name) ? $activitities->name : '' }}
                                		@endif
                                	</td>
                                	<td>{{ ($activitities->code) ? $activitities->code : '' }}</td>
                                    <td>{{ ($activitities->start_date) ? date('d/m/Y', $activitities->start_date) : '' }}</td>
                                    <td>{{ ($activitities->end_date) ? date('d/m/Y', $activitities->end_date) : '' }}</td>
                                    <td style="text-align: right;"><span class="label {{ $statusLabels[$activitities->status] }} pull-right">{{ ($activitities->status) ? $status_array[$activitities->status] : '' }}</span></td>
                                    <!-- <span class="label label-danger pull-right">{{ ($project->status) ? $status_array[$project->status] : '' }}</span> -->
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->
		</div>
	</div>
@endsection

@section('page_script')
@endsection

						