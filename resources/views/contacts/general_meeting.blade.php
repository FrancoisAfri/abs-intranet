@extends('layouts.main_layout')

@section('page_dependencies')
        <!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- Select2
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/select2/select2.min.css">-->
<!-- bootstrap file input -->
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<!-- iCheck -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/green.css">
@endsection

@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-8 col-md-offset-2">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">New Client</h3>
                    <p>Enter client details:</p>
                </div>
                <!-- /.box-header <label class="radio-inline"><input type="radio" id="rdo_agm" name="contact_type" value="2"{{ ($contact_type === 2) ? ' checked' : '' }}> AGM</label>-->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/contacts/agm/store">
                    {{ csrf_field() }}

                    <div class="box-body">
                        <div class="form-group{{ $errors->has('contact_type') ? ' has-error' : '' }}">
                            <label for="contact_type" class="col-sm-3 control-label">Contact Type</label>

                            <div class="col-sm-9">
                                <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_contact" name="contact_type" value="1"{{ ($contact_type === 1) ? ' checked' : '' }}> Contact</label>
                                
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="names" class="col-sm-3 control-label">Name & Surname</label>
                            <div class="col-sm-9">
                                <div class="input-group">
								<div class="input-group-addon">
                              <i class="fa fa-user"></i>
                            </div>
                                    <input type="text" class="form-control" id="names" name="names" value="{{ old('names') }}" placeholder="Name & Surname" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="representative" class="col-sm-3 control-label">Type of Contact</label>
                            <div class="col-sm-9">
                                <div class="input-group">
								<div class="input-group-addon">
                              <i class="fa fa-user"></i>
                            </div>
                                    <select class="form-control" name="representative" id="representative" placeholder="Select Representative">
									<option value="">*** Select a Type of Contact ***</option>
									<option value="1">Company Rep</option>
									<option value="2">Student</option>
									<option value="3">Learner</option>
									<option value="4">Official</option>
									<option value="5">Educator</option>
									<option value="6">Osizweni Employee</option>
									<option value="7">Osizweni Board Member</option>
									<option value="8">Other</option>
								  </select>
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="type_attendees" class="col-sm-3 control-label">Organization Type</label>
                            <div class="col-sm-9">
                                <div class="input-group">
								<div class="input-group-addon">
                              <i class="fa fa-user"></i>
                            </div>
                                    <select class="form-control" name="type_attendees" id="type_attendees" placeholder="Select Attendees" required>
									<option value="1">Private Company</option>
									<option value="1">Parastatal</option>
									<option value="2">School</option>
									<option value="3">Government</option>
									<option value="4">Other</option>
								  </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">Email</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <input type="email" class="form-control" id="email" name="email" value="" placeholder="Email" required>
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="office_number" class="col-sm-3 control-label">Office number</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="tel" class="form-control" id="office_number" name="office_number" value="" data-inputmask='"mask": "(999) 999-9999"' placeholder="Office number" data-mask>
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="cell_number" class="col-sm-3 control-label">Cell Number</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="tel" class="form-control" id="cell_number" name="cell_number" value="" data-inputmask='"mask": "(999) 999-9999"' placeholder="Cell Number" data-mask>
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="position" class="col-sm-3 control-label">Position</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-black-tie"></i>
                                    </div>
                                    <input type="text" class="form-control" id="position" name="position" value="{{ old('position') }}" placeholder="Position">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button id="cancel" class="btn btn-default"><i class="fa fa-arrow-left"></i> Cancel</button>
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-user-plus"></i> Add</button>
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
            <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

    <script type="text/javascript">
        //Cancel button click event
        document.getElementById("cancel").onclick = function () {
            location.href = "/contacts";
        };

        $(function () {
            //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%' // optional
            });

            //Phone mask
            $("[data-mask]").inputmask();
        });
    </script>
@endsection