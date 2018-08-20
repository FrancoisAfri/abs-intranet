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
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">Client</h3>
                    <p>Client details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/contacts/{{ $contactPerson->id }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    <div class="box-body">

                        <!-- Contact's company details -->
                        @if($contactPerson->company)
                            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                <strong class="lead">Client's Company Details</strong>
                                <span class="pull-right">
                                    <a href="{{ "/contacts/company/$contactPerson->company_id/view" }}" class="btn btn-sm btn-primary no-print"><i class="fa fa-eye"></i> View Company</a>
                                </span><br>
                                @if(!empty($contactPerson->company->name))
                                    <strong>Company Name:</strong> <em>{{ $contactPerson->company->name }}</em> &nbsp; &nbsp;
                                @endif
                                @if(!empty($contactPerson->company->registration_number))
                                    | &nbsp; &nbsp; <strong>Registration Number:</strong> <em>{{ $contactPerson->company->registration_number }}</em> &nbsp; &nbsp;
                                @endif
                                @if(!empty($contactPerson->company->vat_number))
                                    | &nbsp; &nbsp; <strong>VAT Number:</strong> <em>{{ $contactPerson->company->vat_number }}</em> &nbsp; &nbsp;
                                @endif
                                @if(!empty($contactPerson->company->phone_number))
                                    | &nbsp; &nbsp; <strong>Telephone:</strong> <em>{{ $contactPerson->company->phone_number }}</em> &nbsp; &nbsp;
                                @endif
                                @if(!empty($contactPerson->company->email))
                                    | &nbsp; &nbsp; <strong>Email:</strong> <em> <a href="mailto:{{ $contactPerson->company->email }}">{{ $contactPerson->company->email }}</a></em> &nbsp; &nbsp;
                                @endif
                                @if(!empty($contactPerson->company->full_phys_address))
                                    | &nbsp; &nbsp; <strong>Physical Address:</strong> <em>{{ $contactPerson->company->full_phys_address }}</em> &nbsp; &nbsp;
                                @endif
                            </p>
                        @endif
                        <!-- /. Contact's company details -->

                        <!-- Contact status -->
                        <div class="callout {{ ($contactPerson->status == 1) ? 'callout-success' : 'callout-danger' }}">
                            <h4>Client Status</h4>

                            <p>This profile is {{ ($contactPerson->status == 1) ? 'active' : 'deactivated' }}.</p>
                        </div>
                        <!-- /. Contact status -->

                        <div class="form-group">
                            <label for="first_name" class="col-sm-2 control-label">First Name</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $contactPerson->first_name }}" placeholder="First Name" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="surname" class="col-sm-2 control-label">Surname</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="surname" name="surname" value="{{ $contactPerson->surname }}" placeholder="Surname" required>
                                </div>
                            </div>
                        </div>
                        @if (isset($view_by_admin) && $view_by_admin === 1)
                            <div class="form-group">
                                <label for="company_id" class="col-sm-2 control-label">Company</label>

                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-building"></i>
                                        </div>
                                        <select id="company_id" name="company_id" class="form-control select2">
                                            <option value="">*** Select a Company ***</option>
                                            @foreach($companies as $company)
                                                <option value="{{ $company->id }}" {{ ($contactPerson->company_id == $company->id) ? ' selected' : '' }}>{{ $company->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="cell_number" class="col-sm-2 control-label">Cell Number</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control" name="cell_number" value="{{ $contactPerson->cell_number }}" data-inputmask='"mask": "(999) 999-9999"' placeholder="Cell Number" data-mask>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">Email</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <a href="mailto:{{ $contactPerson->email }}">Send </br><i class="fa fa-envelope"></i></a>
                                    </div>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $contactPerson->email }}" placeholder="Email" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="res_address" class="col-sm-2 control-label">Address</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <textarea name="res_address" class="form-control" placeholder="Address">{{ $contactPerson->res_address }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="res_suburb" class="col-sm-2 control-label">Suburb</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <input type="text" class="form-control" id="res_suburb" name="res_suburb" value="{{ $contactPerson->res_suburb }}" placeholder="Suburb">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="res_city" class="col-sm-2 control-label">City</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <input type="text" class="form-control" id="res_city" name="res_city" value="{{ $contactPerson->res_city }}" placeholder="City">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="res_postal_code" class="col-sm-2 control-label">Postal Code</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <input type="number" class="form-control" id="res_postal_code" name="res_postal_code" value="{{ $contactPerson->res_postal_code }}" placeholder="Postal Code">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="res_province_id" class="col-sm-2 control-label">Province</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <select name="res_province_id" class="form-control select2">
                                        <option value="">*** Select Your Province ***</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province->id }}" {{ ($contactPerson->res_province_id == $province->id) ? ' selected' : '' }}>{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="date_of_birth" class="col-sm-2 control-label">Date of Birth</label>

                            <div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="date_of_birth" placeholder="  dd/mm/yyyy" value="{{ ($contactPerson->date_of_birth) ? date('d/m/Y',$contactPerson->date_of_birth) : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gender" class="col-sm-2 control-label">Gender</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-venus-mars"></i>
                                    </div>
                                    <select name="gender" class="form-control select2">
                                        <option value="">*** Select Your gender ***</option>
                                        <option value="1" {{ ($contactPerson->gender === 1) ? ' selected' : '' }}>Male</option>
                                        <option value="0" {{ ($contactPerson->gender === 0) ? ' selected' : '' }}>Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="id_number" class="col-sm-2 control-label">ID Number</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="number" class="form-control" id="id_number" name="id_number" value="{{ $contactPerson->id_number }}" placeholder="ID Number">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="passport_number" class="col-sm-2 control-label">Passport Number</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="passport_number" name="passport_number" value="{{ $contactPerson->passport_number }}" placeholder="Passport Number">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="marital_status" class="col-sm-2 control-label">Marital Status</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-venus-mars"></i>
                                    </div>
                                    <select name="marital_status" class="form-control select2">
                                        <option value="">*** Select Your Marital Status ***</option>
                                        @foreach($marital_statuses as $marital_status)
                                            <option value="{{ $marital_status->id }}" {{ ($contactPerson->marital_status == $marital_status->id) ? ' selected' : '' }}>{{ $marital_status->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ethnicity" class="col-sm-2 control-label">Ethnicity</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bar-chart"></i>
                                    </div>
                                    <select name="ethnicity" class="form-control select2">
                                        <option value="">*** Select Your Ethnic Group ***</option>
                                        @foreach($ethnicities as $ethnicity)
                                            <option value="{{ $ethnicity->id }}" {{ ($contactPerson->ethnicity == $ethnicity->id) ? ' selected' : '' }}>{{ $ethnicity->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="profile_pic" class="col-sm-2 control-label">Profile Picture</label>

                            <div class="col-sm-10">
                                @if(!empty($avatar))
                                    <div style="margin-bottom: 10px;">
                                        <img src="{{ $avatar }}" class="img-responsive img-thumbnail" width="200" height="200">
                                    </div>
                                @endif
                                <input type="file" id="profile_pic" name="profile_pic" class="file file-loading" data-allowed-file-extensions='["jpg", "jpeg", "png"]' data-show-upload="false">
                            </div>
                        </div>
                        <hr class="hr-text no-border" data-content="SECURITY OPTION">
                        @if($contactPerson->user)
                            {{--reset password--}}
                            <div class="form-group">
                                <label for="change_password" class="col-sm-2 control-label">Password</label>

                                <div class="col-sm-10">
                                    <button type="button" id="change_password" class="btn btn-default btn-block btn-flat" data-toggle="modal" data-target="#myPasswordModal"><font data-toggle="tooltip" title="Click here to change password."><i class="fa fa-unlock-alt"></i> Change Password</font></button>
                                </div>
                            </div>
                        @else
                            {{--create login details with the person's email--}}
                            <div class="callout callout-info">
                                <h4><i class="fa fa-info-circle"></i> Login details</h4>

                                @if(!empty($contactPerson->email))
                                    <a href="/contacts/{{ $contactPerson->id }}/create-login" class="btn btn-outline btn-block btn-flat"><i class="fa fa-unlock"></i> Create Login Details</a><br>
                                    <p>This will generate login credentials with which this person will be able to login to the system. The login credentials will be sent to the email address specified above.</p>
                                @else
                                    <p>To create login details for this person, you need to start by updating their email address first.</p>
                                @endif
                            </div>
                        @endif
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer" style="text-align: center;">
                        <button type="button" id="cancel" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i> Cancel</button>
                        @if($canDeleteAndActivate)
                            <a href="/contacts/{{ $contactPerson->id }}/activate" class="btn  {{ (!empty($contactPerson->status) && $contactPerson->status == 1) ? " btn-danger " : " btn-success" }}"><i class="fa fa-pencil-square-o"></i> {{(!empty($contactPerson->status) && $contactPerson->status == 1) ? "Deactivate" : "Activate"}}</a>
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#delete-contact-warning-modal"><i class="fa fa-trash"></i> Delete Client</button>
                        @endif
                        <button type="submit" name="command" id="update" class="btn btn-primary pull-right"><i class="fa fa-floppy-o"></i> Update</button>
                        <a href="/contacts/{{ $contactPerson->id }}/viewdocuments" class="btn btn-primary " ><i class="fa fa-clipboard"> </i> Client Document(s)</a>
						@if($contactPerson->user)
						<button type="button" class="btn btn-primary pull-right" id="access_button" onclick="postData({{$contactPerson->user_id}}, 'access');">Modules Access</button>
						@endif
                    </div>
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

        <!-- Include delete warning Modal form-->
        @include('contacts.partials.warning_action', ['modal_title' => 'Delete Client', 'modal_content' => 'Are you sure you want to delete this contact? This action cannot be undone.'])
    </div>
@endsection

@section('page_script')
            <!-- bootstrap datepicker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>

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
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();

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
                    url: '{{ '/contacts/' . $contactPerson->user_id . '/pw' }}',
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
                    url: '{{ '/users/' . $contactPerson->user_id . '/upw' }}',
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

        //       //Load divisions drop down
        var parentDDID = '';
            var loadAllDivs = 1;
            @if (isset($view_by_admin) && $view_by_admin === 1)
                @foreach($division_levels as $division_level)
                    //Populate drop down on page load
                    var ddID = '{{ 'division_level_' . $division_level->level }}';
                    var postTo = '{!! route('divisionsdropdown') !!}';
                    var selectedOption = '';
                    var divLevel = parseInt('{{ $division_level->level }}');
                    if (divLevel == 5) selectedOption = '{{ $contactPerson->division_level_5 }}';
                    else if(divLevel == 4) selectedOption = '{{ $contactPerson->division_level_4 }}';
                    else if(divLevel == 3) selectedOption = '{{ $contactPerson->division_level_3 }}';
                    else if(divLevel == 2) selectedOption = '{{ $contactPerson->division_level_2 }}';
                    else if(divLevel == 1) selectedOption = '{{ $contactPerson->division_level_1 }}';
                    var incInactive = -1;
                    var loadAll = loadAllDivs;
                    loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo);
                    parentDDID = ddID;
                    loadAllDivs = -1;
                @endforeach
            @endif
    </script>
@endsection