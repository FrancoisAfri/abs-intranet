@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
        <div class="box box-warning">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                   <!--  <h3 class="box-title">Search for a Vehicle</h3> -->
                </div>
                <!-- /.box-header -->
                <!-- form start -->
             <!--    <form class="form-horizontal"  id="search_form" method="POST"> -->
                <form class="form-horizontal" method="POST" action="/vehicle_management/vehicle/Search">
                    {{ csrf_field() }}

                    <div class="box-body">

                        <!--  -->
                    <div class="col-md-8 col-md-offset-2">
                        <div >
                            <div class="box-header with-border">
                              <h3 class="box-title">Search for a Vehicle</h3>
                            </div>
                            <div class="box-body">
                            
                             <div class="form-group">
                            <label for="company_id" class="col-sm-3 control-label">Company</label>
                            <div class="col-sm-7">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <select class="form-control select2" id="company_id" name="company_id">
                                        <option selected="selected" value="0">*** Select a Company ***</option>
                                       
                                    </select> 
                                </div>
                            </div>
                        </div>

                         <div class="form-group">
                            <label for="department_id" class="col-sm-3 control-label">Department</label>
                            <div class="col-sm-7">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <select class="form-control select2" id="department_id" name="department_id">
                                        <option selected="selected" value="0">*** Select a Department ***</option>
                                       
                                    </select> 
                                </div>
                            </div>
                        </div>

                         <div class="form-group{{ $errors->has('property_type') ? ' has-error' : '' }}">
                                <label for="property_type" class="col-sm-3 control-label"> Property Type </label>

                                <div class="col-sm-9">
                                    <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_package" name="property_type" value="1" checked> All    </label>
                                    <label class="radio-inline"><input type="radio" id="rdo_product" name="property_type" value="2">  Internal   </label>
                                     <label class="radio-inline"><input type="radio" id="rdo_products" name="property_type" value="3">  External   </label>

                                </div>
                         </div> 

                         <div class="form-group">
                            <label for="vehicle_id" class="col-sm-3 control-label">Vehicle Type</label>
                            <div class="col-sm-7">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <select class="form-control select2" id="vehicle_id" name="vehicle_id">
                                        <option selected="selected" value="0">*** Select a Vehicle Type ***</option>
                                       
                                    </select> 
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fleet_number" class="col-sm-3 control-label">Fleet Number </label>
                            <div class="col-sm-7">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="fleet_number" name="fleet_number" placeholder="Enter Fleet Number...">
                                </div>
                            </div>
                        </div>

                         <div class="form-group">
                            <label for="registration_number" class="col-sm-3 control-label">Registration Number </label>
                            <div class="col-sm-7">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="registration_number" name="registration_number" placeholder="Enter Registration Number...">
                                </div>
                            </div>
                        </div>

                         <div class="form-group{{ $errors->has('promotion_type') ? ' has-error' : '' }}">
                                <label for="property_type" class="col-xs-3 control-label"> Property Type </label>

                                <div class="col-sm-9">
                                    <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_package" name="promotion_type" value="1" checked> Inactive </label>
                                    <label class="radio-inline"><input type="radio" id="rdo_product" name="promotion_type" value="2">  Active </label>
                                    <label class="radio-inline"><input type="radio" id="rdo_products" name="promotion_type" value="3">  Require Approval </label>
                                    <label class="radio-inline"><input type="radio" id="rdo_products" name="promotion_type" value="4"> Rejected  </label>
                                    <label class="radio-inline"><input type="radio" id="rdo_products" name="promotion_type" value="5"> All </label>

                                </div>
                         </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary pull-left"><i class="fa fa-search"></i> Search</button>
                        <button type="button" class="btn btn-primary pull-right" id="add_vehicle" ><i class="fa fa-plus-square-o"></i> Add Vehicle</button>
                        
                     </div>
                    </div>
                   </div>
                  </div>   
                    <!-- /.box-body -->
                    
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
    </div>
    @endsection

    @section('page_script')
    <!-- Select 2-->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <!-- Bootstrap date picker -->
    <script src="/bower_components/AdminLTE/plugins/daterangepicker/moment.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Start Bootstrap File input -->
    <!-- canvas-to-blob.min.js is only needed if you wish to resize images before upload. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
    <!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
    <script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
    <!-- optionally if you need translation for your language then include locale file as mentioned below -->
    <!--<script src="/bower_components/bootstrap_fileinput/js/locales/<lang>.js"></script>-->
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script> 
    <script type="text/javascript">
        //Cancel button click event
        /*document.getElementById("cancel").onclick = function () {
            location.href = "/contacts";
        };*/

        $('#add_vehicle').click(function () {
                location.href = '/vehicle_management/add_vehicle';
            });

         $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
            //Date Range picker
            $('.daterangepicker').daterangepicker({
                format: 'dd/mm/yyyy',
                endDate: '-1d',
                autoclose: true
            });
            //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
            $('#search_form').attr('action', '/task/search_results');
            $('.meetingTasks').hide();
            $('.heldeskTasks').hide();
        });
        //Phone mask
        $("[data-mask]").inputmask();
        function changetype(type)
        {
            if (type == 1)
            {
                $('.inductionTasks').show();
                $('.meetingTasks').hide();
                $('.heldeskTasks').hide();
            }
            else if (type == 2)
            {
                $('.inductionTasks').hide();
                $('.meetingTasks').show();
                $('.heldeskTasks').hide();
            }
            else if (type == 3)
            {
                $('.inductionTasks').hide();
                $('.meetingTasks').hide();
                $('.heldeskTasks').hide();
            }
            else if (type == 4)
            {
                $('.inductionTasks').hide();
                $('.meetingTasks').hide();
                $('.heldeskTasks').show();
            }
                
        }
    </script>
@endsection