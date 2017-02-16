@extends('layouts.main_layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-success">
                <div class="box-header">
                    <i class="fa fa-file pull-right"></i>
                    <h3 class="box-title">{{ $str_report_type }} Registration Report</h3>
                    <p>Report Details:</p>
                </div>
                <!-- /.box-header -->
                <form class="form-horizontal" method="POST" action="/reports/registration/print/1">
                    <input type="hidden" name="registration_type" value="{{!empty($registration_type) ? $registration_type : ''}}">
                    <input type="hidden" name="programme_id" value="{{!empty($programme_id) ? $programme_id : ''}}">
                    <input type="hidden" name="project_id" value="{{!empty($project_id) ? $project_id : ''}}">
                    <input type="hidden" name="registration_year" value="{{!empty($registration_year) ? $registration_year : ''}}">
                    <input type="hidden" name="course_type" value="{{!empty($course_type) ? $course_type : ''}}">
                    <input type="hidden" name="registration_semester" value="{{!empty($registration_semester) ? $registration_semester : ''}}">
                    {{ csrf_field() }}

                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                    <strong class="lead">Report Parameters</strong><br>
                                    <strong>Registration Type :</strong> <em>{{ $str_report_type }}</em> &nbsp; &nbsp;
                                    @if(!empty($programme))
                                        | &nbsp; &nbsp; <strong>Programme:</strong> <em>{{ $programme }}</em> &nbsp; &nbsp;
                                    @endif
                                    @if(!empty($project))
                                        | &nbsp; &nbsp; <strong>Project:</strong> <em>{{ $project }}</em> &nbsp; &nbsp;
                                    @endif
                                    @if(!empty($registration_year))
                                        | &nbsp; &nbsp; <strong>Registration Year:</strong> <em>{{ $registration_year }}</em> &nbsp; &nbsp;
                                    @endif
                                    @if(!empty($str_course_type) && $registration_type != 3)
                                        | &nbsp; &nbsp; <strong>Course Type:</strong> <em>{{ $str_course_type }}</em> &nbsp; &nbsp;
                                    @endif
                                    @if(!empty($registration_semester))
                                        | &nbsp; &nbsp; <strong>Registration Semester:</strong> <em>{{ $registration_semester }}</em> &nbsp; &nbsp;
                                    @endif
                                </p>
                            </div>
                        </div>
                        <table class="table table-striped">
                            <tr>
                                <th>Name</th>
                                <th>Programme</th>
                                <th>Project</th>
                                <th style="text-align: center;">Registration Date</th>
                                <th>Subject</th>
                                <th style="text-align: right;">Result</th>
                            </tr>
                            @if(count($registrations) > 0)
                                @foreach($registrations as $registration)
                                    <tr>
                                        <td>{{ ($registration->client) ? $registration->client->full_name : '' }}</td>
                                        <td>{{ ($registration->programme) ? $registration->programme->name : '' }}</td>
                                        <td>{{ ($registration->project) ? $registration->project->name : '' }}</td>
                                        <td style="text-align: center;">{{ !empty($registration->created_at) ? $registration->created_at->format('d/m/Y') : '' }}</td>
                                        <td>
                                            @foreach($registration->subjects as $subject)
                                                <p>{{ $subject->name }}</p>
                                            @endforeach
                                        </td>
                                        <td style="text-align: right;">
                                            @foreach($registration->subjects as $subject)
                                                <p>{{ !empty($subject->result) ? $subject->result : "&nbsp;" }}</p>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                    </div>
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back"><i class="fa fa-arrow-left"></i> Back</button>
                        <button type="submit" class="btn btn-primary pull-right" id="print_report"><i
                                    class="fa fa-print"></i> Print Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('page_script')
    <script type="text/javascript">
        $(function () {
            //Cancel button click event
            $('#back').click(function () {
                location.href = "/reports/registration";
            });
        });
    </script>
@endsection