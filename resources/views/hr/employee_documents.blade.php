@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-8 col-md-offset-2">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">New User</h3>
                    <p>Enter employee details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="hr/emp_document">
                    {{ csrf_field() }}

                    <div class="box-body">
                        <div class="form-group">
                            <label for="first_name" class="col-sm-3 control-label">First Name</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="First Name" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="surname" class="col-sm-3 control-label">Surname</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="surname" name="surname" value="{{ old('surname') }}" placeholder="Surname" required>
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
                                    <input type="text" class="form-control" id="cell_number" name="cell_number" value="{{ old('cell_number') }}" data-inputmask='"mask": "(999) 999-9999"' placeholder="Cell Number" data-mask>
                                </div>
                            </div>
                        </div>
                         <div class="form-group">
                            <label for="phone_number" class="col-sm-3 control-label">Phone Number</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" data-inputmask='"mask": "(999) 999-9999"' placeholder="Phone Number" data-mask>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="id_number" class="col-sm-3 control-label">ID Number</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control" id="id_number" name="id_number" value="{{ old('id_number') }}"  placeholder="ID Number" data-mask>
                                </div>
                            </div>
                        </div>
                         <div class="form-group">
                            <label for="date_of_birth" class="col-sm-3 control-label">Date of birth</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}"  placeholder="Date of birth" data-mask>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="passport_number" class="col-sm-3 control-label">Passport Number</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control" id="passport_number" name="passport_number" value="{{ old('passport_number') }}"  placeholder="Passport Number" data-mask>
                                </div>
                            </div>
                        </div>
                         <div class="form-group">
                            <label for="drivers_licence_number" class="col-sm-3 control-label">Drivers Licence Number</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control" id="drivers_licence_number" name="drivers_licence_number" value="{{ old('drivers_licence_number') }}"  placeholder="Drivers Licence Number" data-mask>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="drivers_licence_code" class="col-sm-3 control-label">Drivers Licence Code</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control" id="drivers_licence_code" name="drivers_licence_code" value="{{ old('drivers_licence_code') }}"  placeholder="Drivers Licence Code" data-mask>
                                </div>
                            </div>
                        </div>
                         <div class="form-group">
                            <label for="proof_drive_permit" class="col-sm-3 control-label">Proof Drive Permit</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control" id="proof_drive_permit" name="proof_drive_permit" value="{{ old('proof_drive_permit') }}"  placeholder="Proof Drive Permit" data-mask>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="proof_drive_permit_exp_date" class="col-sm-3 control-label">Drive Permit Expiry</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control" id="proof_drive_permit_exp_date" name="proof_drive_permit_exp_date" value="{{ old('proof_drive_permit_exp_date') }}"  placeholder="Proof Drive Permit Expiry Date" data-mask>
                                </div>
                            </div>
                        </div>
                           <div class="form-group">
                            <label for="drivers_licence_exp_date" class="col-sm-3 control-label">Licence Expiry</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <input type="text" class="form-control" id="drivers_licence_exp_date" name="drivers_licence_exp_date" value="" placeholder="Licence Expiry" required>
                                </div>
                            </div>
                        </div>
                         <div class="form-group">
                            <label for="gender" class="col-sm-3 control-label">Gender</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <input type="text" class="form-control" id="gender" name="gender" value="" placeholder="Gender" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ethnicity" class="col-sm-3 control-label">Ethnicity</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <input type="text" class="form-control" id="ethnicity" name="ethnicity" value="" placeholder="Ethnicity" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button id="cancel" class="btn btn-default"><i class="fa fa-arrow-left"></i> Cancel</button>
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-user-plus"></i> Create</button>
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

    <script type="text/javascript">
        //Cancel button click event
        document.getElementById("cancel").onclick = function () {
            location.href = "/users";
        };

        //Phone mask
        $("[data-mask]").inputmask();
    </script>
@endsection