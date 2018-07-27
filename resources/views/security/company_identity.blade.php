@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <form class="form-horizontal" method="POST" action="/security/setup/company_details" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="box-header with-border">
                        <h3 class="box-title">Company Identity</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
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
                        <div class="form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
                            <label for="company_name" class="col-sm-2 control-label">Company Name</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-building-o"></i>
                                    </div>
                                    <input type="text" class="form-control" id="company_name" name="company_name" value="{{ ($companyDetails) ? $companyDetails->company_name : '' }}" placeholder=Company Name">
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('full_company_name') ? ' has-error' : '' }}">
                            <label for="full_company_name" class="col-sm-2 control-label">Full Company Name</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-building-o"></i>
                                    </div>
                                    <input type="text" class="form-control" id="full_company_name" name="full_company_name" value="{{ ($companyDetails) ? $companyDetails->full_company_name : '' }}" placeholder="Full Company Name">
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('header_name_bold') || $errors->has('header_name_regular') ? ' has-error' : '' }}">
                            <label for="header_name_bold" class="col-sm-2 control-label">Name On Header (Bold) <a data-toggle="tooltip" data-placement="bottom" title="This is the name that is used by the system's layout header. The first box contains the part of the name to be displayed in bold and the second box will contain the part to be displayed in regular"><i class="fa fa-info-circle"></i></a></label>

                            <div class="col-sm-3">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-building-o"></i>
                                    </div>
                                    <input type="text" class="form-control" id="header_name_bold" name="header_name_bold" value="{{ ($companyDetails) ? $companyDetails->header_name_bold : '' }}" placeholder="Bold part of the name" onkeyup="companyNamePreview($('#header_name_bold'), $('#hnb_preview'))">
                                </div>
                            </div>

                            <label for="header_name_regular" class="col-sm-2 control-label">Name On Header (Regular)</label>

                            <div class="col-sm-3">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-building-o"></i>
                                    </div>
                                    <input type="text" class="form-control" id="header_name_regular" name="header_name_regular" value="{{ ($companyDetails) ? $companyDetails->header_name_regular : '' }}" placeholder="Regular part of the name" onkeyup="companyNamePreview($('#header_name_regular'), $('#hnr_preview'))">
                                </div>
                            </div>

                            <div class="col-sm-2 control-label">
                                <!-- Logo -->
                                <a class="lead logo">
                                    <!-- logo for regular state and mobile devices -->
                                    <span class="logo-lg"><b id="hnb_preview"></b> <span id="hnr_preview"></span></span>
                                </a>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('header_acronym_bold') || $errors->has('header_acronym_regular') ? ' has-error' : '' }}">
                            <label for="header_acronym_bold" class="col-sm-2 control-label">Acronym On Header (Bold) <a data-toggle="tooltip" data-placement="bottom" title="This is the name that is used by the system's layout header when the sidebar is collapsed. The first box contains the part of the acronym to be displayed in bold and the second box will contain the part to be displayed in regular"><i class="fa fa-info-circle"></i></a></label>

                            <div class="col-sm-3">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-building-o"></i>
                                    </div>
                                    <input type="text" class="form-control" id="header_acronym_bold" name="header_acronym_bold" value="{{ ($companyDetails) ? $companyDetails->header_acronym_bold : '' }}" placeholder="Bold part of the acronym" onkeyup="companyNamePreview($('#header_acronym_bold'), $('#hab_preview'))">
                                </div>
                            </div>

                            <label for="header_acronym_regular" class="col-sm-2 control-label"> Acronym On Header (Regular)</label>

                            <div class="col-sm-3">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-building-o"></i>
                                    </div>
                                    <input type="text" class="form-control" id="header_acronym_regular" name="header_acronym_regular" value="{{ ($companyDetails) ? $companyDetails->header_acronym_regular : '' }}" placeholder="Regular part of the acronym" onkeyup="companyNamePreview($('#header_acronym_regular'), $('#har_preview'))">
                                </div>
                            </div>

                            <div class="col-sm-2 control-label">
                                <!-- Logo -->
                                <a class="lead logo">
                                    <!-- logo for regular state and mobile devices -->
                                    <span class="logo-lg"><b id="hab_preview"></b><span id="har_preview"></span></span>
                                </a>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('company_logo') ? ' has-error' : '' }}">
                            <label for="company_logo" class="col-sm-2 control-label">Company Logo</label>

                            @if( $companyDetails && !empty($companyDetails->company_logo_url) )
                                <div class="col-sm-5" style="margin-bottom: 10px;">
                                    <img src="{{ $companyDetails->company_logo_url }}" class="img-responsive img-thumbnail" style="max-height: 200px;">
                                </div>
                            @endif
                            <div class="{{ ($companyDetails && !empty($companyDetails->company_logo_url)) ? 'col-sm-5' : 'col-sm-10' }}">
                                <input type="file" id="company_logo" name="company_logo" class="file file-loading" data-allowed-file-extensions='["jpg", "jpeg", "png"]' data-show-upload="false">
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('sys_theme_color') ? ' has-error' : '' }}">
                                <label for="sys_theme_color" class="col-sm-2 control-label">System Theme Color</label>

                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-paint-brush"></i>
                                        </div>
                                        <select id="sys_theme_color" name="sys_theme_color" class="form-control select2"  style="width: 100%;">
                                            <option selected="selected" value="">*** Select a Theme ***</option>
                                            <option value="blue" {{ ($companyDetails && 'blue' == $companyDetails->sys_theme_color) ? ' selected' : '' }}>Blue</option>
                                            <option value="black" {{ ($companyDetails && 'black' == $companyDetails->sys_theme_color) ? ' selected' : '' }}>Black</option>
                                            <option value="purple" {{ ($companyDetails && 'purple' == $companyDetails->sys_theme_color) ? ' selected' : '' }}>Purple</option>
                                            <option value="green" {{ ($companyDetails && 'green' == $companyDetails->sys_theme_color) ? ' selected' : '' }}>Green</option>
                                            <option value="red" {{ ($companyDetails && 'red' == $companyDetails->sys_theme_color) ? ' selected' : '' }}>Red</option>
                                            <option value="yellow" {{ ($companyDetails && 'yellow' == $companyDetails->sys_theme_color) ? ' selected' : '' }}>Yellow</option>
                                            <option value="blue-light" {{ ($companyDetails && 'blue-light' == $companyDetails->sys_theme_color) ? ' selected' : '' }}>Blue Light</option>
                                            <option value="black-light" {{ ($companyDetails && 'black-light' == $companyDetails->sys_theme_color) ? ' selected' : '' }}>Black Light</option>
                                            <option value="purple-light" {{ ($companyDetails && 'purple-light' == $companyDetails->sys_theme_color) ? ' selected' : '' }}>Purple Light</option>
                                            <option value="green-light" {{ ($companyDetails && 'green-light' == $companyDetails->sys_theme_color) ? ' selected' : '' }}>Green Light</option>
                                            <option value="red-light" {{ ($companyDetails && 'red-light' == $companyDetails->sys_theme_color) ? ' selected' : '' }}>Red Light</option>
                                            <option value="yellow-light" {{ ($companyDetails && 'yellow-light' == $companyDetails->sys_theme_color) ? ' selected' : '' }}>Yellow Light</option>
                                        </select>
                                    </div>
                                </div>
                        </div>
                        <div class="form-group{{ $errors->has('mailing_name') ? ' has-error' : '' }}">
                            <label for="mailing_name" class="col-sm-2 control-label">Mailing Name</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="mailing_name" name="mailing_name" value="{{ ($companyDetails) ? $companyDetails->mailing_name : '' }}" placeholder="Mailing Name">
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('mailing_address') ? ' has-error' : '' }}">
                            <label for="mailing_address" class="col-sm-2 control-label">Mailing Address</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope-o"></i>
                                    </div>
                                    <input type="email" class="form-control" id="mailing_address" name="mailing_address" value="{{ ($companyDetails) ? $companyDetails->mailing_address : '' }}" placeholder="Mailing Address">
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('support_email') ? ' has-error' : '' }}">
                            <label for="support_email" class="col-sm-2 control-label">Support Email</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope-o"></i>
                                    </div>
                                    <input type="email" class="form-control" id="support_email" name="support_email" value="{{ ($companyDetails) ? $companyDetails->support_email : '' }}" placeholder="Support Email">
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('company_website') ? ' has-error' : '' }}">
							<label for="company_website" class="col-sm-2 control-label">Compony Website</label>

							<div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-internet-explorer"></i>
									</div>
									<input type="text" class="form-control" id="company_website" name="company_website" value="{{ ($companyDetails) ? $companyDetails->company_website : '' }}" placeholder="Company Website ">
								</div>
							</div>
						</div>
						<div class="form-group{{ $errors->has('password_expiring_month') ? ' has-error' : '' }}">
							<label for="company_website" class="col-sm-2 control-label">Password Duration (Months)</label>

							<div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-building-o"></i>
									</div>
									<input type="text" class="form-control" id="password_expiring_month" name="password_expiring_month" value="{{ ($companyDetails) ? $companyDetails->password_expiring_month : '' }}" placeholder="Password Duration (Months)... ">
								</div>
							</div>
						</div>
						<div class="form-group{{ $errors->has('login_background_image') ? ' has-error' : '' }}">
                            <label for="login_background_image" class="col-sm-2 control-label">Login Background Image</label>

                            @if( $companyDetails && !empty($companyDetails->login_background_image_url) )
                                <div class="col-sm-5" style="margin-bottom: 10px;">
                                    <img src="{{ $companyDetails->login_background_image_url }}" class="img-responsive img-thumbnail" style="max-height: 200px;">
                                </div>
                            @endif
                            <div class="{{ ($companyDetails && !empty($companyDetails->login_background_image_url)) ? 'col-sm-5' : 'col-sm-10' }}">
                                <input type="file" id="login_background_image" name="login_background_image" class="file file-loading" data-allowed-file-extensions='["jpg", "jpeg", "png"]' data-show-upload="false">
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('system_background_image') ? ' has-error' : '' }}">
                            <label for="system_background_image" class="col-sm-2 control-label">System Background Image</label>

                            @if( $companyDetails && !empty($companyDetails->system_background_image_url) )
                                <div class="col-sm-5" style="margin-bottom: 10px;">
                                    <img src="{{ $companyDetails->system_background_image_url }}" class="img-responsive img-thumbnail" style="max-height: 200px;">
                                </div>
                            @endif
                            <div class="{{ ($companyDetails && !empty($companyDetails->system_background_image_url)) ? 'col-sm-5' : 'col-sm-10' }}">
                                <input type="file" id="system_background_image" name="system_background_image" class="file file-loading" data-allowed-file-extensions='["jpg", "jpeg", "png"]' data-show-upload="false">
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" id="add-new-module" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-module-modal"><i class="fa fa-floppy-o"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Include modal -->
        @if(Session('changes_saved'))
            @include('contacts.partials.success_action', ['modal_title' => "Company Identity Updated!", 'modal_content' => session('changes_saved')])
        @endif
    </div>
@endsection

@section('page_script')
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
    <!-- optionally if you need translation for your language then include locale file as mentioned below
    <script src="/bower_components/bootstrap_fileinput/js/locales/<lang>.js"></script>-->
    <!-- End Bootstrap File input -->

    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();

            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();

            //Show company name preview
            companyNamePreview($('#header_name_bold'), $('#hnb_preview'));
            companyNamePreview($('#header_name_regular'), $('#hnr_preview'));
            companyNamePreview($('#header_acronym_bold'), $('#hab_preview'));
            companyNamePreview($('#header_acronym_regular'), $('#har_preview'));

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

            //Show success action modal
            @if(Session('changes_saved'))
                $('#success-action-modal').modal('show');
            @endif
        });

        //helper function to show the company name on the preview pane
        function companyNamePreview(inputField, outputView) {
            outputView.html(inputField.val());
        }
    </script>
@endsection