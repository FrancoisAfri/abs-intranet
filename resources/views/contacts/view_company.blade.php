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
                    <h3 class="box-title">Company</h3>
                    <p>Company details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/contacts/company">
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
                                    <input type="text" class="form-control" id="name" name="name" value="{{ !empty($company->name) ? $company->name : '' }}" placeholder="Company Name" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('phone_number') ? ' has-error' : '' }}">
                            <label for="phone_number" class="col-sm-2 control-label">Office Number</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ !empty($company->phone_number) ? $company->phone_number : '' }}" data-inputmask='"mask": "(999) 999-9999"' placeholder="Phone Number" data-mask readonly>
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
                                    <input type="email" class="form-control" id="email" name="email" value="{{ !empty($company->email) ? $company->email : '' }}" placeholder="Email Address" readonly>
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
                                    <input type="text" class="form-control" id="phys_address" name="phys_address" value="{{ !empty($company->phys_address) ? $company->phys_address : '' }}" placeholder="Street Address" readonly>
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
                                    <input type="text" class="form-control" id="phys_city" name="phys_city" value="{{ !empty($company->phys_city) ? $company->phys_city : '' }}" placeholder="City" readonly>
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
                                    <input type="text" class="form-control" id="phys_province" name="phys_province" value="{{ !empty($provinces->name) ? $provinces->name : '' }}" placeholder="Province" readonly>
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
                                    <input type="text" class="form-control" id="phys_postal_code" name="phys_postal_code" value="{{ !empty($company->phys_postal_code) ? $company->phys_postal_code : '' }}" placeholder="Postal Code" readonly>
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
                                    <input type="text" class="form-control" id="registration_number" name="registration_number" value="{{ !empty($company->registration_number) ? $company->registration_number : '' }}" placeholder="Company Registration Number" readonly>
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
                                    <input type="text" class="form-control" id="vat_number" name="vat_number" value="{{ !empty($company->vat_number) ? $company->vat_number : '' }}" placeholder="VAT Number" readonly>
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
                                    <input type="text" class="form-control" id="tax_number" name="tax_number" value="{{ !empty($company->tax_number) ? $company->tax_number : '' }}" placeholder="Tax Number" readonly>
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('account_number') ? ' has-error' : '' }}">
                            <label for="account_number" class="col-sm-2 control-label">Account Number</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </div>
                                    <input type="text" class="form-control" id="account_number" name="account_number" value="{{ !empty($company->account_number) ? $company->account_number : '' }}" placeholder="Account Number" readonly>
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
                                    <input type="text" class="form-control" id="bee_score" name="bee_score" value="{{ !empty($company->bee_score) ? $company->bee_score : '' }}" placeholder="BEE Score" readonly>
                                </div>
                            </div>
                        </div>  

                        <div class="form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                            <label for="status" class="col-sm-2 control-label">Status</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-pinterest-p"></i>
                                    </div>
                                    <select readonly="readonly" name="status" class="form-control">
                                        <option value="">*** Select Your Priority ***</option>
                                        <option value="1" >Start</option>
                                        <option value="2" >Progress</option>
                                        <option value="3" >Assign</option>
                                    </select >
                                </div>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('estimated_spent') ? ' has-error' : '' }}">
                            <label for="estimated_spent" class="col-sm-2 control-label">Estimated Expenditure</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-credit-card-alt"></i>
                                    </div>
                                    <input type="number" class="form-control" id="estimated_spent" name="estimated_spent" value="{{ !empty($company->estimated_spent) ? $company->estimated_spent : '' }}" placeholder="Estimated Expenditure" readonly>
                                </div>
                            </div>
                        </div>
                         <div class="form-group{{ $errors->has('domain_name') ? ' has-error' : '' }}">
                            <label for="domain_name" class="col-sm-2 control-label">Domain Name</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-internet-explorer"></i>
                                    </div>
                                    <input type="text" class="form-control" id="domain_name" name="domain_name" value="{{ !empty($company->domain_name) ? $company->domain_name : '' }}" placeholder="Domain name" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('bee_certificate_doc') ? ' has-error' : '' }}">
                            <label for="bee_certificate_doc" class="col-sm-2 control-label">BEE Certificate</label>

                            <div class="col-sm-10">
                                @if(!empty($bee_certificate_doc))
                                    <a class="btn btn-default btn-flat btn-block" href="{{ $bee_certificate_doc }}" target="_blank"><i class="fa fa-file-pdf-o"></i> Click Here To View The Document</a>
                                @else
                                    <a class="btn btn-default btn-flat btn-block"><i class="fa fa-exclamation-triangle"></i> Nothing Was Uploaded</a>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('comp_reg_doc') ? ' has-error' : '' }}">
                            <label for="comp_reg_doc" class="col-sm-2 control-label">Registration Document</label>

                            <div class="col-sm-10">
                                @if(!empty($comp_reg_doc))
                                    <a class="btn btn-default btn-flat btn-block" href="{{ $comp_reg_doc }}" target="_blank"><i class="fa fa-file-pdf-o"></i> Click Here To View The Document</a>
                                @else
                                    <a class="btn btn-default btn-flat btn-block"><i class="fa fa-exclamation-triangle"></i> Nothing Was Uploaded</a>
                                @endif
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('dept_id') ? ' has-error' : '' }}">
                            <label for="dept_id" class="col-sm-2 control-label">{{$dept->name}}</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <input type="text" class="form-control" id="dept_id" name="dept_id" value="{{ !empty($deparments->name) ? $deparments->name : '' }}" placeholder="Department" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer" style="text-align: center;">
                        @if($canEdit)
                            <a href="/contacts/company/{{ $company->id }}/edit" class="btn btn-primary pull-right"><i class="fa fa-pencil-square-o"></i> Edit</a>
                            <a href="/contacts/company/{{ $company->id }}/actdeact" class="btn btn-primary pull-left  {{ (!empty($company->status) && $company->status == 1) ? " btn-danger " : " btn-success" }}"><i class="fa fa-pencil-square-o"></i> {{(!empty($company->status) && $company->status == 1) ? "Deactivate" : "Activate"}}</a>
                            <a href="{{ '/contacts/add-to-company/' . $company->id }}" class="btn btn-primary"><i class="fa fa-user-plus"></i> Add Contact Person</a>
                            <a href="/contacts/company/{{ $company->id }}/notes" class="btn btn-info "><i class="fa fa-phone-square"></i> Notes </a>  
                        @endif
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->

            <!-- Company's contacts box -->
            <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-users"></i> Contacts From The Company</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding no-margin">
                    <div id="company-contacts" style="margin-right: 10px; max-height: 250px;">
                        <!-- Include the contacts list -->
                        @include('contacts.partials.contacts_result_list', ['persons' => $company->employees])
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- End Column -->

        <!-- Confirmation Modal -->
        @if(Session('success_add'))
            @include('contacts.partials.success_action', ['modal_title' => "New Company Added!", 'modal_content' => session('success_add')])
        @endif
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
        /*document.getElementById("cancel").onclick = function () {
            location.href = "/contacts";
        };*/

        $(function () {
            //Phone mask
            $("[data-mask]").inputmask();

            //slimScroll
            $('#company-contacts').slimScroll({
                height: '',
                railVisible: true,
                alwaysVisible: true
            });

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
            $('#success-action-modal').modal('show');
        });
    </script>
@endsection