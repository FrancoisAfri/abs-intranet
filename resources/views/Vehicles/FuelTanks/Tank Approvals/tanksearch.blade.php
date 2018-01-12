@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->

@endsection

@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                </div>
                <form class="form-horizontal" method="POST" action="/vehicle_management/tanksearch_approval">
                    {{ csrf_field() }}

                    <div class="box-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                                </button>
                                <h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="col-md-8 col-md-offset-2">
                            <div>
                                <div class="box-header with-border" align="center">
                                    <h3 class="box-title">Search Fuel Tank Details </h3>
                                </div>
                                <div class="box-body" id="vehicle_details">


                                    <div class="form-group">
                                        <label for="path" class="col-sm-2 control-label">Fleet Number</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-truck"></i>
                                                </div>
                                                <input type='text' class="form-control" id='fleet_no'
                                                       name="fleet_no" required=""/>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label for="path" class="col-sm-2 control-label">Vehicle</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-truck"></i>
                                                </div>
                                                <select class="form-control select2" style="width: 100%;"
                                                        id="vehicle_type" name="vehicle_type">
                                                    <option value="0">*** Select a Vehicle ***</option>
                                                    @foreach($vehiclemodel as $type)
                                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="path" class="col-sm-2 control-label">Date From </label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type='text' class="form-control" id='required_from'
                                                       name="required_from"/>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class="form-group">
                                        <label for="path" class="col-sm-2 control-label">Date To </label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type='text' class="form-control" id='required_to'
                                                       name="required_to"/>
                                            </div>
                                        </div>
                                    </div> -->

                                </div>

                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary pull-right"><i
                                                class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection

@section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- bootstrap datepicker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>


    <script type="text/javascript">
        $(function () {
            $(".select2").select2();
            var moduleId;
            //Tooltip


            $('.required_from').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });
        });


        //Initialize iCheck/iRadio Elements

        $(document).ready(function () {

            $(function () {
                $('#required_from').datepicker();
            });

            $('#required_to').datepicker({});

        });


    </script>
@endsection
