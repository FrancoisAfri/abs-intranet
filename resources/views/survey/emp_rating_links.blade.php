@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-star pull-right"></i>
                    <h3 class="box-title">Rate My Service Links</h3>
                    <p>Links to employees rate my service page:</p>
                </div>
                <!-- /.box-header -->

                <!-- Form Start -->
                <form name="survey-report-form" class="form-horizontal" method="POST" action="" >
                    {{ csrf_field() }}

                    <div class="box-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <table id="emp-list-table" class="table table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Full Link</th>
                                <th>Shortened Link</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($employees as $employee)
                                <tr>
                                    <td style="vertical-align: middle;" nowrap>{{ $employee->full_name }}</td>
                                    <td>
                                        <textarea rows="2" class="form-control" style="width: 100%;" readonly>{{ $employee->encrypted_rate_my_service_link }}</textarea>
                                    </td>
                                    <td></td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Employee</th>
                                <th>Full Link</th>
                                <th>Shortened Link</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.box-body --
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Cancel</button>
                        <button type="submit" id="gen_report" class="btn btn-primary pull-right"><i class="fa fa-check"></i> Generate Report</button>
                    </div>
                    -->
                </form>
            </div>
        </div>
        <!-- Include add new modal -->
    </div>
@endsection

@section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- Date Picker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- DataTables -->
    <script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- iCheck
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <!-- Ajax form submit
    <script src="/custom_components/js/modal_ajax_submit.js"></script>-->
    <script>
        $(function () {
            /*
            //Initialize Select2 Elements
            $(".select2").select2();
            //Cancel button click event
            $('#back_button').click(function () {
                location.href = '/';
            });
            */
            //Initialize the data table
            $('#emp-list-table').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": false,
                "autoWidth": true
            });
        });
    </script>
@endsection
