<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Registration Report Printed By {{ $user->person->first_name.' '. $user->person->surname }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
   -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/dist/css/AdminLTE.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body onload="window.print();">
<div class="wrapper">
    <!-- Main content -->
    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <img width="196" height="60" src="{{ $company_logo }}" alt="logo">
                    <small class="pull-right">Date: {{$date}}</small>
                </h2>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-8 invoice-col">
                <address>
                    <strong>{{ $company_name }}</strong><br>
                    P O BOX 6377<br>
                    SECUNDA<br>
                    2302<br>
                </address>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <div class="row">
            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
            <div class="panel box box-success">
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
            </div>

        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>