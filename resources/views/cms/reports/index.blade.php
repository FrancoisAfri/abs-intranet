@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-8 col-md-offset-2">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-leanpub pull-right"></i>
                    <h3 class="box-title">Cms Reports Search</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form name="reports-form" class="form-horizontal" method="POST" action=" "
                      enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <hr class="hr-text" data-content="Report Generator">

                    <div class="box-body">
                        <div class="form-group{{ $errors->has('allocation_type') ? ' has-error' : '' }}">
                            <label for="Leave_type" class="col-sm-2 control-label"> Report Type</label>
                            <div class="col-sm-9">
                                <label class="radio-inline"><input type="radio" id="rdo_resert" name="allocation_type"
                                                                   value="1" checked> cms News Rating Report</label>
                                <label class="radio-inline"><input type="radio" id="rdo_allocate" name="allocation_type"
                                                                   value="2"> Job Card Report</label>
                            </div>
                        </div>
                    </div>

                    <hr class="hr-text" data-content="">
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <input type="submit" id="load-allocation" name="load-allocation"
                               class="btn btn-primary pull-right" value="Search">
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
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

    <!-- Start Bootstrap File input -->
    <!-- canvas-to-blob.min.js is only needed if you wish to resize images before upload. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js"
            type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
    <!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
    <script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
    <!-- optionally if you need translation for your language then include locale file as mentioned below
    <script src="/bower_components/bootstrap_fileinput/js/locales/<lang>.js"></script>-->
    <!-- End Bootstrap File input -->

    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>

    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script type="text/javascript">
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
            //Cancel button click event
            $('#cancel').click(function () {
                location.href = '/leave/Allocate_leave_types';
            });
            //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
            hideFields();
            //show/hide fields on radio button toggles (depending on registration type)
            $('#rdo_adjust, #rdo_resert, #rdo_allocate').on('ifChecked', function () {
                var allType = hideFields();
                if (allType == 1) $('#box-subtitle').html('Adjust leave allocation');
                else if (allType == 2) $('#box-subtitle').html('Resert leave allocation');
                else if (allType == 3) $('#box-subtitle').html('Allocate leave allocation');
            });

            //function to hide/show fields depending on the allocation  type
            function hideFields() {
                var allType = $("input[name='allocation_type']:checked").val();
                if (allType == 1) { //adjsut leave
                    $('.resert-field, .allocaion-field').hide();
                    $('.adjust-field').show();
                    $('form[name="reports-form"]').attr('action', '/cms/cms_news_ranking');
                    $('#load-allocation').val("Submit");
                }
                else if (allType == 2) { //resert leave
//
                    $('.adjust-field, .allocate-field').hide();
                    $('.resert-field').show();
                    $('form[name="reports-form"]').attr('action', '/vehicle_management/vehicle_reports/jobcard');
                    $('#load-allocation').val("Submit");
                }
                return allType;
            }
        });

    </script>
@endsection