@extends('layouts.main_layout')
@section('page_dependencies')
<!-- bootstrap file input -->
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-files-o pull-right"></i>
                    <h3 class="box-title">New Company</h3>
                    <p>Enter company details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/contacts/company" enctype="multipart/form-data">
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
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-sm-2 control-label">Company Name</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-building"></i>
                                    </div>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Company Name" required>
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('trading_as') ? ' has-error' : '' }}">
							<label for="name" class="col-sm-2 control-label">Trading As</label>
							<div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-building"></i>
									</div>
									<input type="text" class="form-control" id="trading_as" name="trading_as" value="{{ old('trading_as') }}" placeholder="Trading As">
								</div>
							</div>
						</div>
                        <div class="form-group{{ $errors->has('cp_home_number') ? ' has-error' : '' }}">
                            <label for="cp_home_number" class="col-sm-2 control-label">Office Number</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control" id="cp_home_number" name="cp_home_number" value="{{ old('cp_home_number') }}" data-inputmask='"mask": "(999) 999-9999"' placeholder="Office Number" data-mask>
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('fax_number') ? ' has-error' : '' }}">
							<label for="fax_number" class="col-sm-2 control-label">Fax Number</label>
							<div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-fax"></i>
									</div>
									<input type="text" class="form-control" id="fax_number" name="fax_number" value="{{ old('fax_number') }}" data-inputmask='"mask": "(999) 999-9999"' placeholder="Fax Number" data-mask>
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
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Email Address">
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('phys_address') ? ' has-error' : '' }}">
                            <label for="phys_address" class="col-sm-2 control-label">Street Address</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <input type="text" class="form-control" id="phys_address" name="phys_address" value="{{ old('phys_address') }}" placeholder="Street Address">
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('phys_city') ? ' has-error' : '' }}">
                            <label for="phys_city" class="col-sm-2 control-label">City</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <input type="text" class="form-control" id="phys_city" name="phys_city" value="{{ old('phys_address') }}" placeholder="City">
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('phys_province') ? ' has-error' : '' }}">
                            <label for="phys_province" class="col-sm-2 control-label">Province</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <select id="phys_province" name="phys_province" class="form-control">
                                        <option value="">*** Select a Province ***</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province->id }}" {{ (old('phys_province') == $province->id) ? ' selected' : '' }}>{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('phys_postal_code') ? ' has-error' : '' }}">
                            <label for="phys_postal_code" class="col-sm-2 control-label">Postal Code</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <input type="text" class="form-control" id="phys_postal_code" name="phys_postal_code" value="{{ old('phys_postal_code') }}" placeholder="Postal Code">
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('postal_address') ? ' has-error' : '' }}">
							<label for="phys_address" class="col-sm-2 control-label">Postal Address</label>
							<div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-envelope-square"></i>
									</div>
									<textarea class="form-control" id="postal_address" name="postal_address" placeholder="Postal Address" rows="3">{{ old('postal_address') }}</textarea>
								</div>
							</div>
						</div>
                        <div class="form-group{{ $errors->has('registration_number') ? ' has-error' : '' }}">
                            <label for="registration_number" class="col-sm-2 control-label">Registration Number</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </div>
                                    <input type="text" class="form-control" id="registration_number" name="registration_number" value="{{ old('registration_number') }}" placeholder="Company Registration Number">
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('vat_number') ? ' has-error' : '' }}">
                            <label for="vat_number" class="col-sm-2 control-label">VAT Number</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </div>
                                    <input type="text" class="form-control" id="vat_number" name="vat_number" value="{{ old('vat_number') }}" placeholder="VAT Number">
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('tax_number') ? ' has-error' : '' }}">
                            <label for="tax_number" class="col-sm-2 control-label">Tax Number</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </div>
                                    <input type="text" class="form-control" id="tax_number" name="tax_number" value="{{ old('tax_number') }}" placeholder="Tax Number">
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('bee_score') ? ' has-error' : '' }}">
                            <label for="bee_score" class="col-sm-2 control-label">BEE Score</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-star-half-o"></i>
                                    </div>
                                    <input type="text" class="form-control" id="bee_score" name="bee_score" value="{{ old('bee_score') }}" placeholder="BEE Score">
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('bee_certificate_doc') ? ' has-error' : '' }}">
                            <label for="bee_certificate_doc" class="col-sm-2 control-label">BEE Certificate</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </div>
                                    <input type="file" id="bee_certificate_doc" name="bee_certificate_doc" class="file file-loading" data-allowed-file-extensions='["pdf"]' data-show-upload="false">
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('comp_reg_doc') ? ' has-error' : '' }}">
                            <label for="comp_reg_doc" class="col-sm-2 control-label">Registration Document</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </div>
                                    <input type="file" id="comp_reg_doc" name="comp_reg_doc" class="file file-loading" data-allowed-file-extensions='["pdf"]' data-show-upload="false">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group{{ $errors->has('do') ? ' has-error' : '' }}">
                            <label for="domain_name" class="col-sm-2 control-label">Domain Name</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-internet-explorer"></i>
                                    </div>
                                    <input type="text" class="form-control" id="domain_name" name="domain_name" value="{{ old('domain_name') }}" placeholder="Domain name">
                                </div>
                            </div>
                        </div>
						<div class="form-group">
							<label for="name" class="col-sm-2 control-label">Choose {{$dept->name}}</label>
							<div class="col-sm-10">
								<div class="input-group">
									<select class="form-control select2" id="dept_id" name="dept_id">
										<option selected="selected" value="0">*** Select {{$dept->name}} ***</option>
										@foreach($deparments as $deparment)
											<option value="{{ $deparment->id }}">{{ $deparment->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="form-group{{ $errors->has('account_owners') ? ' has-error' : '' }}">
                            <label for="account_owners" class="col-sm-2 control-label">Account Owner</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-users"></i>
                                    </div>
                                    <select id="account_owners" name="account_owners" class="form-control select2">
                                        <option value="">*** Select an Employee ***</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" {{ (old('account_owners') == $employee->id) ? ' selected' : '' }}>{{ $employee->first_name." ".$employee->surname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                         <!--
                        <div class="form-group{{ $errors->has('cp_cell_number') ? ' has-error' : '' }}">
                            <label for="phone_number" class="col-sm-2 control-label">Cell Number</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-mobile"></i>
                                    </div>
                                    <input type="text" class="form-control" id="cp_cell_number" name="cp_cell_number" value="{{ old('cp_cell_number') }}" data-inputmask='"mask": "(999) 999-9999"' placeholder="Cell Number" data-mask>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('cp_home_number') ? ' has-error' : '' }}">
                            <label for="phone_number" class="col-sm-2 control-label">Home Number</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control" id="cp_home_number" name="cp_home_number" value="{{ old('cp_home_number') }}" data-inputmask='"mask": "(999) 999-9999"' placeholder="Home Number" data-mask>
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('account_number') ? ' has-error' : '' }}">
                            <label for="phone_number" class="col-sm-2 control-label">Account Number</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </div>
                                    <input type="text" class="form-control" id="account_number" name="account_number" value="{{ old('account_number') }}" placeholder="Account Number">
                                </div>
                            </div>
                        </div>
                        -->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button id="cancel" class="btn btn-default"><i class="fa fa-arrow-left"></i> Cancel</button>
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-database"></i> Add</button>
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
    <!-- End Bootstrap File input -->

    <script type="text/javascript">
        //Cancel button click event
        document.getElementById("cancel").onclick = function () {
            location.href = "/contacts";
        };

        $(function () {
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
            $(window).on('resize', function() {
                $('.modal:visible').each(reposition);
            });
        });
    </script>
@endsection