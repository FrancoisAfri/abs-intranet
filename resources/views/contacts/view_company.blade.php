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
        <!-- New Company Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <i class="fa fa-building pull-right"></i>
                    <h3 id="box-title" class="box-title">View {{ $str_company_type }}</h3>
                    <p id="box-subtitle">{{ $str_company_type }} details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start enctype="multipart/form-data" -->
                <form class="form-horizontal" method="POST" action="/contacts/company/{{ $company->id }}/approve">
                    {{ csrf_field() }}

                    <div class="box-body">
                        @if($company->company_type !== 2 && $company->status !== 3)
                            <div class="callout {{ $status_labels[$company->status] }}">
                                <h4><i class="fa fa-info-circle"></i> Approval Status</h4>

                                <p>{{ $status_strings[$company->status] }}{{ (in_array($company->status, [-1, -2])) ? ' for the following reason: ' . (($company->status === -1) ? $company->first_rejection_reason : $company->second_rejection_reason) : '.' }}</p>
                            </div>
                        @endif
                        <div class="form-group{{ $errors->has('company_type') ? ' has-error' : '' }}">
                            <label for="name" class="col-sm-2 control-label">Partner Type</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-filter"></i>
                                    </div>
                                    <input type="text" class="form-control" name="company_type" value="{{ $str_company_type }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label id="name-lbl" for="name" class="col-sm-2 control-label">{{ ($company->company_type === 2) ? 'School' : 'Company' }} Name</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-building-o"></i>
                                    </div>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $company->name }}" placeholder="Enter The Name Of The {{ ($company->company_type === 2) ? 'School' : 'Company' }}..." readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group provider-field{{ $errors->has('registration_number') ? ' has-error' : '' }}">
                            <label for="registration_number" class="col-sm-2 control-label">Registration Number</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-building-o"></i>
                                    </div>
                                    <input type="text" class="form-control" id="registration_number" name="registration_number" value="{{ $company->registration_number }}" placeholder="Enter Company Registration Number..." readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group provider-field{{ $errors->has('vat_number') ? ' has-error' : '' }}">
                            <label for="vat_number" class="col-sm-2 control-label">VAT Number</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-certificate"></i>
                                    </div>
                                    <input type="text" class="form-control" id="vat_number" name="vat_number" value="{{ $company->vat_number }}" placeholder="Enter VAT Number..." readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group provider-field{{ $errors->has('tax_number') ? ' has-error' : '' }}">
                            <label for="tax_number" class="col-sm-2 control-label">Tax Number</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-certificate"></i>
                                    </div>
                                    <input type="text" class="form-control" id="tax_number" name="tax_number" value="{{ $company->tax_number }}" placeholder="Enter Tax Number..." readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group provider-field{{ $errors->has('bee_score') ? ' has-error' : '' }}">
                            <label for="bee_score" class="col-sm-2 control-label">BEE Score</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-star-half-o"></i>
                                    </div>
                                    <input type="text" class="form-control" id="bee_score" name="bee_score" value="{{ $company->bee_score }}" placeholder="Enter BEE Score..." readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group provider-field{{ $errors->has('bee_certificate_doc') ? ' has-error' : '' }}">
                            <label for="bee_certificate_doc" class="col-sm-2 control-label">BEE Certificate</label>
                            <div class="col-sm-10">
                                @if(!empty($bee_certificate_doc))
                                    <a class="btn btn-default btn-flat btn-block" href="{{ $bee_certificate_doc }}" target="_blank"><i class="fa fa-file-pdf-o"></i> Click Here To View The Document</a>
                                @else
                                    <a class="btn btn-default btn-flat btn-block"><i class="fa fa-exclamation-triangle"></i> Nothing Was Uploaded</a>
                                @endif
                            </div>
                        </div>
                        <div class="form-group provider-field{{ $errors->has('comp_reg_doc') ? ' has-error' : '' }}">
                            <label for="comp_reg_doc" class="col-sm-2 control-label">Company Registration Document</label>
                            <div class="col-sm-10">
                                @if(!empty($comp_reg_doc))
                                    <a class="btn btn-default btn-flat btn-block" href="{{ $comp_reg_doc }}" target="_blank"><i class="fa fa-file-pdf-o"></i> Click Here To View The Document</a>
                                @else
                                    <a class="btn btn-default btn-flat btn-block"><i class="fa fa-exclamation-triangle"></i> Nothing Was Uploaded</a>
                                @endif
                            </div>
                        </div>
                        <div class="form-group provider-field{{ $errors->has('supporting_doc') ? ' has-error' : '' }}">
                            <label for="supporting_doc" class="col-sm-2 control-label">Other Supporting Document</label>
                                <div class="col-sm-10">
                                    @if(!empty($supporting_doc))
                                        <a class="btn btn-default btn-flat btn-block" href="{{ $supporting_doc }}" target="_blank"><i class="fa fa-file-pdf-o"></i> Click Here To View The Document</a>
                                    @else
                                        <a class="btn btn-default btn-flat btn-block"><i class="fa fa-exclamation-triangle"></i> Nothing Was Uploaded</a>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group provider-field{{ $errors->has('sector') ? ' has-error' : '' }}">
                            <label for="sector" class="col-sm-2 control-label">Area of Specialisation</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </div>
                                    <input type="text" class="form-control" id="sector" name="sector" value="{{ $company->sector }}" placeholder="Area of Specialisation..." readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group school_field{{ $errors->has('phone_number') ? ' has-error' : '' }}">
                            <label for="phone_number" class="col-sm-2 control-label">Telephone</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $company->phone_number }}" data-inputmask='"mask": "(999) 999-9999"' placeholder="Telephone Number..." data-mask readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group school_field{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-sm-2 control-label">Email</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-at"></i>
                                    </div>
                                    <input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}" value="{{ $company->email }}" placeholder="Email" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group school_field{{ $errors->has('phys_address') ? ' has-error' : '' }}">
                            <label for="phys_address" class="col-sm-2 control-label">Physical Address</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <textarea id="phys_address" name="phys_address" value="{{ old('phys_address') }}" class="form-control" placeholder="Physical Address..." readonly>{{ $company->phys_address }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group school_field{{ $errors->has('postal_address') ? ' has-error' : '' }}">
                            <label for="postal_address" class="col-sm-2 control-label">Postal Address</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope-o"></i>
                                    </div>
                                    <textarea id="postal_address" value="{{ old('postal_address') }}" name="postal_address" class="form-control" placeholder="Postal Address..." readonly>{{ $company->postal_address }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group school_field{{ $errors->has('phys_circuit') ? ' has-error' : '' }}">
                            <label for="phys_circuit" class="col-sm-2 control-label">Circuit</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <input type="text" class="form-control" value="{{ old('phys_circuit') }}" id="phys_circuit" name="phys_circuit" value="{{ $company->phys_circuit }}" placeholder="Circuit..." readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group school_field{{ $errors->has('phys_region') ? ' has-error' : '' }}">
                            <label for="phys_region" class="col-sm-2 control-label">Region</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <input type="text" class="form-control" id="phys_region" value="{{ old('phys_region') }}" name="phys_region" value="{{ $company->phys_region }}" placeholder="Region..." readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group school_field{{ $errors->has('phys_district') ? ' has-error' : '' }}">
                            <label for="phys_district" class="col-sm-2 control-label">District</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <input type="text" class="form-control" id="phys_district" name="phys_district" value="{{ $company->phys_district }}" placeholder="District..." readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group school_field{{ $errors->has('phys_province_id') ? ' has-error' : '' }}">
                            <label for="phys_province_id" class="col-sm-2 control-label">Province</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <select name="phys_province_id" class="form-control select2" disabled>
                                        <option value="">*** Select a Province ***</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province->id }}" {{ ($company->phys_province_id == $province->id) ? ' selected' : '' }}>{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer" style="text-align: center;">
                        @if($show_edit)
                            <a href="/contacts/company/{{ $company->id }}/edit" class="btn btn-primary"><i class="fa fa-pencil-square-o"></i> Edit</a>
                        @endif
                        @if($show_elm_approve)
                            <button type="button" id="elm_reject" class="btn btn-danger pull-left" data-toggle="modal" data-target="#rejection-reason-modal"><i class="fa fa-times"></i> Reject</button>
                            <button type="submit" id="elm_approve" class="btn btn-success pull-right"><i class="fa fa-check"></i> Approve</button>
                        @endif
                        @if($show_gm_approve)
                            <button type="button" id="gm_reject" class="btn btn-danger pull-left" data-toggle="modal" data-target="#rejection-reason-modal"><i class="fa fa-times"></i> Reject</button>
                            <button type="submit" id="gm_approve" class="btn btn-success pull-right"><i class="fa fa-check"></i> Approve</button>
                        @endif
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new Company Form-->
        @if($show_elm_approve || $show_gm_approve)
            @include('education.partials.programme_rejection_reason', ['modal_title' => "Why Are You Rejecting This $str_company_type?"])
        @endif
        <!-- Confirmation Modal -->
        @if(Session('success_edit'))
            @include('contacts.partials.success_action', ['modal_title' => "$str_company_type Details Updated!", 'modal_content' => session('success_edit')])
        @elseif(Session('success_add'))
            @include('contacts.partials.success_action', ['modal_title' => "New $str_company_type Added!", 'modal_content' => session('success_add')])
        @elseif(Session('success_approve'))
            @include('contacts.partials.success_action', ['modal_title' => "$str_company_type Approved!", 'modal_content' => session('success_approve')])
        @endif
    </div>
@endsection

@section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>

    <!-- bootstrap datepicker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

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
    <!--<script src="/bower_components/bootstrap_fileinput/js/locales/<lang>.js"></script>-->
    <!-- End Bootstrap File input -->

    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>

    <script type="text/javascript">
        //function to hide/show fields depending on the company type
        function hideFields(companyType) {
            if (companyType == 1 || companyType == 3) {
                //console.log('company type: ' + companyType + '. Gets here');
                $('.school_field').hide();
                $('.provider-field').show();
            }
            else if (companyType == 2) {
                $('.provider-field').hide();
                $('.school_field').show();
            }
            return companyType;
        }

        $(function () {
            //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%' // optional
            });

            //Initialize Select2 Elements
            $(".select2").select2();

            //Phone mask
            $("[data-mask]").inputmask();

            //Date picker
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true
            });

            // [bootstrap file input] initialize with defaults
            $("#input-1").fileinput();
            // with plugin options
            //$("#input-id").fileinput({'showUpload':false, 'previewFileType':'any'});

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

            //Hide/show fields
            hideFields({{ $company->company_type }});

            //Submit rejection form to serve with ajax
            $('#submit-rejection-reason').on('click', function() {
                var strUrl = '{{ '/contacts/company/' . $company->id . '/reject' }}';
                var objData = {
                    rejection_reason: $('#rejection_reason').val(),
                    _token: $('input[name=_token]').val()
                };
                var modalID = 'rejection-reason-modal';
                var submitBtnID = 'submit-rejection-reason';
                var redirectUrl = '{{ '/contacts/company/' . $company->id . '/view' }}';
                var successMsgTitle = '{{ $str_company_type }} Rejected!';
                var successMsg = 'The {{ $str_company_type }} has been rejected. An email stating the rejection reason(s) has been sent to the person who loaded this partner.';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
        });
    </script>
@endsection