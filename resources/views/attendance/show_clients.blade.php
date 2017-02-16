@extends('layouts.main_layout')
@section('page_dependencies')

    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Mark Attendance</h3>
                </div>
                <!-- /.box-header -->
				<form class="form-horizontal" method="POST" action="/education/mark_attendance">
				<input type="hidden" name="programme_id" value="{{!empty($programme_id) ? $programme_id : ''}}">
				<input type="hidden" name="registration_type" value="{{!empty($registration_type) ? $registration_type : ''}}">
                 <input type="hidden" name="project_id" value="{{!empty($project_id) ? $project_id : ''}}">
                 <input type="hidden" name="registration_year" value="{{!empty($registration_year) ? $registration_year : ''}}">
                 <input type="hidden" name="course_type" value="{{!empty($course_type) ? $course_type : ''}}">
                 <input type="hidden" name="registration_semester" value="{{!empty($regSemester) ? $regSemester : ''}}">
                 <input type="hidden" name="learnerID" value="{{!empty($learnerID) ? $learnerID : ''}}">
					{{ csrf_field() }}
                <div class="box-body">
                    <!-- Collapsible section containing the amortization schedule -->
                    <div class="box-group" id="accordion">
                        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it 
						
						<input type="submit" name="command" value="Previous Month" style="float: left;" onclick="this.form.action='/education/get_attendance';">-->
                        <div class="panel box box-primary">
                            <div class="box-body">
							<!--<table style="text-align: center;" border="2" cellpadding="0" cellspacing="0" width="200"> 
							<tr><th>Legend</th></tr>
							<tr style="text-align: center">
								<td align="center">
									<table style="text-align: center; vertical-align: text-top;"  border="0" cellpadding="0" cellspacing="0">
										<tr>
											<td><div style="height: 19px; width: 20px;">P:</div></td>
											<td><span style="font-size:15px; font-family:Verdana, Geneva, sans-serif;">Present</span></td>
											<td><div style="height:50px; width: 20px; line-height: 50px;"></div>A:</td>
											<td><span style="font-size:15px; font-family:Verdana, Geneva, sans-serif;">Absent</span></td>
										
										</tr>
									</table>
								</td>
							</tr>
							</table>
							</br>-->
								<table class="table table-striped">
								<tr><th colspan="{{$span}}"  style="text-align: center;">Class Attendance ({{$sweekStarted}}- {{$sweekEnds}} )</th></tr>
									<tr>
										<th>Name</th>
										@for ($i = $weekStarted ; $i <=$weekEnds; $i += 86400)
											<th>{{  date('D d M', $i) }}</th>
										@endfor
									</tr>
									@if(count($registrations) > 0)
										@foreach($registrations as $registration)
											<tr>
												<td>{{ !empty($registration->client->full_name) ? $registration->client->full_name : '' }}</td>
											@for ($i = $weekStarted ; $i <=$weekEnds; $i += 86400)
												<td><label class="radio-inline"><input type="radio" id="rdo_attendance" name="attendance_{{$registration->$learnerID}}_{{$i}}" value="1"> A</label>
												<label class="radio-inline"><input type="radio" id="rdo_attendance" name="attendance_{{$registration->$learnerID}}_{{$i}}" value="2">P</label></td>
											@endfor
											</tr>
										@endforeach
									@endif
								</table>
								<div class="row no-print">
									<div class="col-xs-12">
										<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-adn"></i>  Submit</button>
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