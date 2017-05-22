@extends('layouts.main_layout')

@section('page_dependencies')
        <!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">

<!-- bootstrap file input -->
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row">
        <!-- User Form -->
        <div class="col-md-8 col-md-offset-2">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">Client</h3>
                    <p>Client details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/users/{{ $user->id }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    <div class="box-body">
                        <div class="form-group">
                            <label for="first_name" class="col-sm-3 control-label">First Name</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $user->person->first_name }}" placeholder="First Name" required>
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
                                    <input type="text" class="form-control" id="surname" name="surname" value="{{ $user->person->surname }}" placeholder="Surname" required>
                                </div>
                            </div>
                        </div>
                        @if (isset($view_by_admin) && $view_by_admin === 1)
                            <div class="form-group">
                                <label for="company_id" class="col-sm-3 control-label">Company</label>

                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-building"></i>
                                        </div>
                                        <select id="company_id" name="company_id" class="form-control">
                                            <option value="">*** Select a Company ***</option>
                                            @foreach($companies as $company)
                                                <option value="{{ $company->id }}" {{ ($user->person->company_id == $company->id) ? ' selected' : '' }}>{{ $company->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="cell_number" class="col-sm-3 control-label">Cell Number</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control" name="cell_number" value="{{ $user->person->cell_number }}" data-inputmask='"mask": "(999) 999-9999"' placeholder="Cell Number" data-mask>
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
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->person->email }}" placeholder="Email" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="res_address" class="col-sm-3 control-label">Address</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <textarea name="res_address" class="form-control" placeholder="Address">{{ $user->person->res_address }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="res_suburb" class="col-sm-3 control-label">Suburb</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <input type="text" class="form-control" id="res_suburb" name="res_suburb" value="{{ $user->person->res_suburb }}" placeholder="Suburb">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="res_city" class="col-sm-3 control-label">City</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <input type="text" class="form-control" id="res_city" name="res_city" value="{{ $user->person->res_city }}" placeholder="City">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="res_postal_code" class="col-sm-3 control-label">Postal Code</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <input type="number" class="form-control" id="res_postal_code" name="res_postal_code" value="{{ $user->person->res_postal_code }}" placeholder="Postal Code">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="res_province_id" class="col-sm-3 control-label">Province</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <select name="res_province_id" class="form-control">
                                        <option value="">*** Select Your Province ***</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province->id }}" {{ ($user->person->res_province_id == $province->id) ? ' selected' : '' }}>{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="date_of_birth" class="col-sm-3 control-label">Date of Birth</label>

                            <div class="col-sm-9">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="date_of_birth" placeholder="  dd/mm/yyyy" value="{{ ($user->person->date_of_birth) ? date('d/m/Y',$user->person->date_of_birth) : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gender" class="col-sm-3 control-label">Gender</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-venus-mars"></i>
                                    </div>
                                    <select name="gender" class="form-control">
                                        <option value="">*** Select Your gender ***</option>
                                        <option value="1" {{ ($user->person->gender === 1) ? ' selected' : '' }}>Male</option>
                                        <option value="0" {{ ($user->person->gender === 0) ? ' selected' : '' }}>Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="id_number" class="col-sm-3 control-label">ID Number</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="number" class="form-control" id="id_number" name="id_number" value="{{ $user->person->id_number }}" placeholder="ID Number">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="passport_number" class="col-sm-3 control-label">Passport Number</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="passport_number" name="passport_number" value="{{ $user->person->passport_number }}" placeholder="Passport Number">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="marital_status" class="col-sm-3 control-label">Marital Status</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-venus-mars"></i>
                                    </div>
                                    <select name="marital_status" class="form-control">
                                        <option value="">*** Select Your Marital Status ***</option>
                                        @foreach($marital_statuses as $marital_status)
                                            <option value="{{ $marital_status->id }}" {{ ($user->person->marital_status == $marital_status->id) ? ' selected' : '' }}>{{ $marital_status->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ethnicity" class="col-sm-3 control-label">Ethnicity</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bar-chart"></i>
                                    </div>
                                    <select name="ethnicity" class="form-control">
                                        <option value="">*** Select Your Ethnic Group ***</option>
                                        @foreach($ethnicities as $ethnicity)
                                            <option value="{{ $ethnicity->id }}" {{ ($user->person->ethnicity == $ethnicity->id) ? ' selected' : '' }}>{{ $ethnicity->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="profile_pic" class="col-sm-3 control-label">Profile Picture</label>

                            <div class="col-sm-9">
                                @if(!empty($avatar))
                                    <div style="margin-bottom: 10px;">
                                        <img src="{{ $avatar }}" class="img-responsive img-thumbnail" width="200" height="200">
                                    </div>
                                @endif
                                <input type="file" id="profile_pic" name="profile_pic" class="file file-loading" data-allowed-file-extensions='["jpg", "jpeg", "png"]' data-show-upload="false">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="change_password" class="col-sm-3 control-label">Password</label>

                            <div class="col-sm-9">
                                <button type="button" id="change_password" class="btn btn-link" data-toggle="modal" data-target="#myPasswordModal"><font data-toggle="tooltip" title="Click here to change password.">Change Password</font></button>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="button" id="cancel" class="btn btn-default">Cancel</button>
                        <button type="submit" name="command" id="update" class="btn btn-primary pull-right">Update</button>
						<button type="button" class="btn btn-primary pull-right" id="access_button" onclick="postData({{$user->person->user_id}}, 'access');">Modules Access</button>
					</div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->

        <!-- Password Modal form-->
        @if (isset($user_profile) && $user_profile === 1)
            @include('security.partials.change_my_password')
        @elseif (isset($view_by_admin) && $view_by_admin === 1)
            @include('security.partials.change_password')
        @endif
        <!-- /.Password Modal form-->

    </div>
    @endsection

    @section('page_script')
            <!-- bootstrap datepicker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

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
    <script src="/bower_components/bootstrap_fileinput/js/locales/<lang>.js"></script>
    <!-- End Bootstrap File input -->

    <script>
        $(function () {
            //Cancel button click event
            document.getElementById("cancel").onclick = function () {
                location.href = "{{ $back }}";
            };

            //Date picker
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                endDate: '-1d',
                autoclose: true
            });

            //Phone mask
            $("[data-mask]").inputmask();

            // [bootstrap file input] initialize with defaults
            $("#input-1").fileinput();
            // with plugin options
            //$("#input-id").fileinput({'showUpload':false, 'previewFileType':'any'});

            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();

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
            $(window).on('resize', function() {
                $('.modal:visible').each(reposition);
            });

            //Post password form to server using ajax
            $('#my-password').on('click', function() {
                $.ajax({
                    method: 'POST',
                    url: '{{ '/contacts/' . $user->id . '/pw' }}',
                    data: {
                        current_password: $('#current_password').val(),
                        new_password: $('#new_password').val(),
                        confirm_password: $('#confirm_password').val(),
                        _token: $('input[name=_token]').val()
                    },
                    success: function(success) {
                        //console.log(success);
                        $('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups
                        $('form[name=password_form]').trigger('reset'); //Reset the form

                        var successHTML = '<button type="button" id="close-invalid-input-alert" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-check"></i> Changes saved!</h4>';
                        successHTML += 'The password has been changed successfully.';
                        $('#success-alert').addClass('alert alert-success alert-dismissible')
                                .fadeIn()
                                .html(successHTML);

                        //auto hide modal after 7 seconds
                        $("#myPasswordModal").alert();
                        window.setTimeout(function() { $("#myPasswordModal").modal('hide'); }, 5000);

                        //autoclose alert after 7 seconds
                        $("#success-alert").alert();
                        window.setTimeout(function() { $("#success-alert").fadeOut('slow'); }, 5000);
                    },
                    error: function(xhr) {
                        //console.log(xhr);
                        //if(xhr.status === 401) //redirect if not authenticated
                        //$( location ).prop( 'pathname', 'auth/login' );
                        if(xhr.status === 422) {
                            var errors = xhr.responseJSON; //get the errors response data
                            //console.log(errors);

                            $('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups

                            var errorsHTML = '<button type="button" id="close-invalid-input-alert" class="close" aria-hidden="true">&times;</button><h4><i class="icon fa fa-ban"></i> Invalid Input!</h4><ul>';
                            $.each(errors, function (key, value) {
                                errorsHTML += '<li>' + value[0] + '</li>'; //shows only the first error.
                                $('#'+key).closest('.form-group')
                                        .addClass('has-error'); //Add the has error class to form-groups with errors
                            });
                            errorsHTML += '</ul>';

                            $('#invalid-input-alert').addClass('alert alert-danger alert-dismissible')
                                    .fadeIn()
                                    .html(errorsHTML);

                            //autoclose alert after 7 seconds
                            $("#invalid-input-alert").alert();
                            window.setTimeout(function() { $("#invalid-input-alert").fadeOut('slow'); }, 7000);

                            //Close btn click
                            $('#close-invalid-input-alert').on('click', function () {
                                $("#invalid-input-alert").fadeOut('slow');
                            });
                        }
                    }
                });
            });

            //Post user password form to server using ajax
            $('#user-password').on('click', function() {
                $.ajax({
                    method: 'POST',
                    url: '{{ '/users/' . $user->id . '/upw' }}',
                    data: {
                        new_password: $('#new_password').val(),
                        _token: $('input[name=_token]').val()
                    },
                    success: function(success) {
                        //console.log(success);
                        $('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups
                        $('form[name=password_form]').trigger('reset'); //Reset the form

                        var successHTML = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-check"></i> Changes saved!</h4>';
                        successHTML += 'The password has been changed successfully.';
                        $('#success-alert').addClass('alert alert-success alert-dismissible')
                                .fadeIn()
                                .html(successHTML);

                        //auto hide modal after 7 seconds
                        $("#myPasswordModal").alert();
                        window.setTimeout(function() { $("#myPasswordModal").modal('hide'); }, 5000);

                        //autoclose alert after 7 seconds
                        $("#success-alert").alert();
                        window.setTimeout(function() { $("#success-alert").fadeOut('slow'); }, 5000);
                    },
                    error: function(xhr) {
                        //console.log(xhr);
                        //if(xhr.status === 401) //redirect if not authenticated
                        //$( location ).prop( 'pathname', 'auth/login' );
                        if(xhr.status === 422) {
                            var errors = xhr.responseJSON; //get the errors response data
                            //console.log(errors);

                            $('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups

                            var errorsHTML = '<button type="button" id="close-invalid-input-alert" class="close" aria-hidden="true">&times;</button><h4><i class="icon fa fa-ban"></i> Invalid Input!</h4><ul>';
                            $.each(errors, function (key, value) {
                                errorsHTML += '<li>' + value[0] + '</li>'; //shows only the first error.
                                $('#'+key).closest('.form-group')
                                        .addClass('has-error'); //Add the has error class to form-groups with errors
                            });
                            errorsHTML += '</ul>';

                            $('#invalid-input-alert').addClass('alert alert-danger alert-dismissible')
                                    .fadeIn()
                                    .html(errorsHTML);

                            //autoclose alert after 7 seconds
                            $("#invalid-input-alert").alert();
                            window.setTimeout(function() { $("#invalid-input-alert").fadeOut('slow'); }, 7000);

                            //Close btn click
                            $('#close-invalid-input-alert').on('click', function () {
                                $("#invalid-input-alert").fadeOut('slow');
                            });
                        }
                    }
                });
            });
        });
		function postData(id, data)
		{
			if (data == 'access')
				location.href = "/users/module_access/" + id;
		}
    </script>
@endsection