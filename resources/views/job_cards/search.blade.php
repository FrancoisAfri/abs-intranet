@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- Include Date Range Picker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
    <!--Time Charger-->
   <!-- Include Date Range Picker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
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
                <form class="form-horizontal" method="POST" action="/vehicle_management/vehiclesearch">
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
                                    <h3 class="box-title">Job Cards Search</h3>
                                </div>
                                <div class="box-body" id="vehicle_details">

                                    <div class="form-group">
                                        <label for="path" class="col-sm-2 control-label">Job Card # </label>
                                        <div class="col-sm-8">
                                                
                                                <input type='text' class="form-control" id='jobcard_id'
                                                       name="jobcard_id"/>
                                            
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="path" class="col-sm-2 control-label"> Date </label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control pull-left" name="date" value=""  />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="path" class="col-sm-2 control-label">Fleet Number </label>
                                        <div class="col-sm-8">
                                            
                                                <input type='text' class="form-control" id='fleet_number'
                                                       name="fleet_number"/>
                                            
                                        </div>
                                    </div>

                                     <div class="form-group">
                                        <label for="path" class="col-sm-2 control-label">Registration Number </label>
                                        <div class="col-sm-8">
                                            
                                                <input type='text' class="form-control" id='registration_no'
                                                       name="registration_no"/>
                                            
                                        </div>
                                    </div>

                                    <div class="form-group">
                                            <label for="kpa_id" class="col-sm-2 control-label"> Status</label>
                                           <div class="col-sm-8">
                                                <select id="status" name="status" class="form-control select2" style="width: 100%;" required>
                                             <option value="0">*** Select Job Title ***</option>
                                              
                                                </select>
                                            </div>
                                    </div>
                                    
                                    <div class="form-group">
                                            <label for="kpa_id" class="col-sm-2 control-label"> Service Type</label>
                                           <div class="col-sm-8">
                                                <select id="service_type_id" name="service_type_id" class="form-control select2" style="width: 100%;" required>
                                             <option value="0">*** Select Job Title ***</option>
                                              
                                                </select>
                                            </div>
                                    </div>

                                    <div class="form-group">
                                            <label for="kpa_id" class="col-sm-2 control-label"> Mechanic</label>
                                           <div class="col-sm-8">
                                                <select id="mechanic_id" name="mechanic_id" class="form-control select2" style="width: 100%;" required>
                                             <option value="0">*** Select Job Title ***</option>
                                              
                                                </select>
                                            </div>
                                    </div>
                                   

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

<script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js" type="text/javascript"></script>
<!-- the main fileinput plugin file -->
<!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
<script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
<script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
<!-- the main fileinput plugin file -->
<script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>


<!-- Date rane picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>

<!-- iCheck -->
<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

<!-- Ajax dropdown options load -->
<script src="/custom_components/js/load_dropdown_options.js"></script>
<!-- Date picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

<!-- Ajax form submit -->
<script src="/custom_components/js/modal_ajax_submit.js"></script>

<!--        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>-->
<!--    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->
    <script type="text/javascript">
        $(function () {
            $(".select2").select2();
            $('.hours-field').hide();
            $('.comp-field').hide();
            var moduleId;
            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();

            //Vertically center modals on page

            //Phone mask
            $("[data-mask]").inputmask();

            //Vertically center modals on page
            function reposition() {
                var modal = $(this),
                    dialog = modal.find('.modal-dialog');
                modal.css('display', 'block');

                // Dividing by two centers the modal exactly, but dividing by three
                // or four works better for larger screens.
                dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
            }

            // Reposition when a modal is shown
            $('.modal').on('show.bs.modal', reposition);
            // Reposition when the window is resized
            $(window).on('resize', function () {
                $('.modal:visible').each(reposition);
            });

            //Show success action modal
            $('#success-action-modal').modal('show');
        });

        $('input[name="date"]').daterangepicker({
                timePicker: false,
                //timePickerIncrement: 30,
                locale: {
                    //format: 'MM/DD/YYYY h:mm A'
                    format: 'DD/MM/YYYY'
                }
            });

        $('.required_from').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true
        });

        //Initialize iCheck/iRadio Elements
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '10%' // optional
        });

       
        $(document).ready(function () {

            $(function () {
                $('#required_from').datetimepicker();
            });

            $('#required_to').datetimepicker({});

        });

        

    </script>
@endsection
