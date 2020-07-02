@extends('layouts.main_layout')

@section('page_dependencies')
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
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">New Client</h3>
                    <p>Enter Client details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/contacts">
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
                        <div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
                            <label for="company_id" class="col-sm-2 control-label">Company</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-building"></i>
                                    </div>
                                    <select id="company_id" name="company_id" class="form-control select2" style="width: 100%;">
                                        <option value="">*** Select a Company ***</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}" {{ (old('company_id') == $company->id || $companyID == $company->id) ? ' selected' : '' }}>{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                            <label for="first_name" class="col-sm-2 control-label">First Name</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="First Name">
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('surname') ? ' has-error' : '' }}">
                            <label for="surname" class="col-sm-2 control-label">Surname</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="surname" name="surname" value="{{ old('surname') }}" placeholder="Surname">
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('cell_number') ? ' has-error' : '' }}">
                            <label for="cell_number" class="col-sm-2 control-label">Cell Number</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="tel" class="form-control" id="cell_number" name="cell_number" value="{{ old('cell_number') }}" data-inputmask='"mask": "(999) 999-9999"' placeholder="Cell Number" data-mask>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-sm-2 control-label">Email</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Email">
                                </div>
                            </div>
                        </div>
                        <hr class="hr-text no-border" data-content="SECURITY OPTION">
                        <div class="form-group{{ $errors->has('create_login') ? ' has-error' : '' }}">
                            <label for="create_login" class="col-sm-2 control-label">Create login details</label>

                            <div class="col-sm-10">
                                <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_create_login_yes" name="create_login" value="1"{{ old('create_login') == 1 ? ' checked' : '' }}> Yes</label>
                                <label class="radio-inline"><input type="radio" id="rdo_create_login_no" name="create_login" value="0" {{ !old('create_login') ? ' checked' : '' }} {{ old('create_login') == 2 ? ' checked' : '' }}> No</label>
                            </div>
                        </div>
                        <div class="form-group security-field">
                            <label for="create_login" class="col-sm-2 control-label"></label>

                            <div class="col-sm-10">
                                <p class="text-muted well well-sm no-shadow lead"><i>This will generate login credentials with which this person will be able to login to the system. The login credentials will be sent to the email address specified above.</i></p>
                            </div>
                        </div>
                        <!--
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-sm-2 control-label">Password</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-lock"></i>
                                    </div>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                </div>
                            </div>
                        </div>
                        -->
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
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

    <script type="text/javascript">
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();

            //Phone mask
            $("[data-mask]").inputmask();

            //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });

            //Cancel button click event
            document.getElementById("cancel").onclick = function () {
                location.href = "/contacts/company/"+ {{$companyID}} + "/view";
            };

            //call hide/show fields functions on doc ready
            hideFields();

            //show/hide file upload or manual fields on radio checked
            $('#rdo_create_login_yes, #rdo_create_login_no').on('ifChecked', function(){
                hideFields();
            });
        });

        //function to hide/show security fields
        function hideFields() {
            var createLogin = $("input[name='create_login']:checked").val();
            if (createLogin == 1) { //yes
                $('.security-field').show();
            }
            else if (createLogin == 0) { //no
                $('.security-field').hide();
            }
            return createLogin;
        }
    </script>
@endsection