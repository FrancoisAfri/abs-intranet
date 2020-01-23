@extends('layouts.main_layout')

@section('page_dependencies')
        <!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- iCheck -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Employees Upload</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <!-- Form Start -->
                <form name="upload employee" class="form-horizontal" method="POST" action="/hr/employees_upload" enctype="multipart/form-data" required>
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="form-group file-upload-field {{ $errors->has('file_input') ? ' has-error' : '' }}">
                            <label for="file_input" class="col-sm-2 control-label">File input</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="file" class="form-control " id="file_input" name="input_file">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Back</button>
                        <button type="submit" id="upload-employee" class="btn btn-primary pull-right"><i class="fa fa-cloud-download"></i> Submit</button>
                    </div>
                </form>
            </div>
        </div>
        @if (session('success_add'))
        @include('contacts.partials.success_action', ['modal_title' => "Employees Details Inserted!", 'modal_content' => session('success')])
        @endif
        @if (session('error_add'))
        @include('appraisals.partials.success_action', ['modal_title' => 'An Error Occurred!', 'modal_content' => session('error')])
        @endif
        <!-- Include add new modal -->
    </div>
@endsection

@section('page_script')
            <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- Date Picker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
@endsection
