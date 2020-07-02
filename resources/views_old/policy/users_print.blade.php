<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Policy User Details </title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/bootstrap/css/bootstrap.min.css">
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
            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
            <div class="panel box box-primary">
                <div class="box-body">
                    <table class="table table-striped">
                        <tr>
                            <th> Employee Name</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th> Date Added</th>
                            <th> Date Read</th>
                            <th>Understood</th>
                            <th>Not Understood</th>
                            <th>Read but not sure</th>
                        </tr>
                        @if(count($Policies) > 0)
                            @foreach($Policies as $policy)
                                <tr>

                                    <td style="vertical-align: middle;"
                                        nowrap>{{ (!empty( $policy->firstname . ' ' . $policy->surname)) ? $policy->firstname . ' ' . $policy->surname : ''}}</td>

                                    <td style="vertical-align: middle;"
                                        nowrap>{{ (!empty( $policy->company )) ? $policy->company : ''}}</td>

                                    <td style="vertical-align: middle;"
                                        nowrap>{{ (!empty( $policy->Department )) ? $policy->Department : ''}}</td>
                                    <td style="vertical-align: middle;"
                                        nowrap>{{ (!empty( $policy->date_added )) ? date('d M Y', $policy->date_added) : ''}}</td>

                                    <td style="vertical-align: middle;"
                                        nowrap>{{ (!empty( $policy->date_read )) ? date('d M Y', $policy->date_read) : '' }}</td>

                                    <td style="vertical-align: middle;"
                                        nowrap>{{ (!empty( $policy->read_understood )) ? 'Yes' : 'N/A'}}
                                    </td>
                                    <td style="vertical-align: middle;" nowrap>
                                        {{ (!empty( $policy->read_not_understood )) ? 'Yes' : 'N/A'}}
                                    </td>
                                    <td style="vertical-align: middle;" nowrap>
                                        {{ (!empty( $policy->read_not_sure )) ? 'Yes' : 'N/A'}}</td>
                                    @endforeach
                                </tr>

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