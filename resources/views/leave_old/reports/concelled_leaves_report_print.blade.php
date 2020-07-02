<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Cancelled Leave Applications Report Printed By {{ $printing_person }}</title>

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

            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->


        <div class="row">
            <div class="col-sm-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Cancelled Leave Applications Report</h3>
                    </div>
                    <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Collapsible section containing the amortization schedule -->
                            <div class="box-group" id="accordion">
                                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                                <table class="table table-striped">
                                    <tr>
                                        <th class="text-center" width="5px">#</th>
                                        <th>Employee Number</th>
                                        <th>Employee Name</th>
                                        <th>Leave Type</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Cancelled By</th>
                                        <th>Cancellation Reason</th>
                                    </tr>
                                    @if(count($leaveApplications) > 0)
                                        @foreach($leaveApplications as $leaveApplication)
                                            <td class="text-center" nowrap>{{ $loop->iteration }}</td>
                                            <td nowrap>{{ ($leaveApplication->person) ? $leaveApplication->person->employee_number : '' }}</td>
                                            <td nowrap>{{ ($leaveApplication->person) ? $leaveApplication->person->full_name : '' }}</td>
                                            <td>{{ ($leaveApplication->leavetpe) ? $leaveApplication->leavetpe->name : '' }}</td>
                                            <td nowrap>{{ ($leaveApplication->start_time) ? date('d M Y H:i', $leaveApplication->start_time) : (($leaveApplication->start_date) ? date('d M Y', $leaveApplication->start_date) : '') }}</td>
                                            <td nowrap>{{ ($leaveApplication->end_time) ? date('d M Y H:i', $leaveApplication->end_time) : (($leaveApplication->end_date) ? date('d M Y', $leaveApplication->end_date) : '') }}</td>
                                            <td nowrap>{{ ($leaveApplication->canceller) ? $leaveApplication->canceller->full_name : '' }}</td>
                                            <td>{{ $leaveApplication->cancellation_reason }}</td>
                                        @endforeach
                                    @endif
                                </table>
                                <div class="row no-print">
                                    <div class="col-xs-12">
                                        <a href="/leave/reports" id="cancel" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</a>
                                        <button type="submit" id="cancel" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print</button>
                                    </div>
                                </div>
                                <!-- End amortization /table -->
                            </div>
                            <!-- /. End Collapsible section containing the amortization schedule -->
                        </div>
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