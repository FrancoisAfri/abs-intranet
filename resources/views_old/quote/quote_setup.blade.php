@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary collapsed-box">
                <form class="form-horizontal" method="POST" action="">
                    {{ csrf_field() }}
                    <div class="box-header with-border">
                        <h3 class="box-title">Company (Quotation Profiles)</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="quote-profile-list" style="max-height: 250px;">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th style="text-align: center;"></th>
                                    <th style="text-align: center; width: 5px;">#</th>
                                    <th>{{ $highestLvl->name }}</th>
                                    <th>Registration Number</th>
                                    <th>VAT Number</th>
                                    <th>Bank Name</th>
                                    <th>Branch Code</th>
                                    <th>Acc. Name</th>
                                    <th>Acc. Number</th>
                                    <th style="text-align: center;">Quote Val. Period</th>
                                    <th class="text-center">Authorisation Required</th>
                                </tr>
                                @foreach($quoteProfiles as $quoteProfile)
                                    <tr>
                                        <td style="text-align: center;">
                                            <button type="button" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-profile-modal"
                                                    data-id="{{ $quoteProfile->id }}"
                                                    data-division_id="{{ $quoteProfile->division_id }}"
                                                    data-registration_number="{{ $quoteProfile->registration_number }}"
                                                    data-vat_number="{{ $quoteProfile->vat_number }}"
                                                    data-phys_address="{{ $quoteProfile->phys_address }}"
                                                    data-phys_city="{{ $quoteProfile->phys_city }}"
                                                    data-phone_number="{{ $quoteProfile->phone_number }}"
                                                    data-email="{{ $quoteProfile->email }}"
                                                    data-phys_postal_code="{{ $quoteProfile->phys_postal_code }}"
                                                    data-bank_name="{{ $quoteProfile->bank_name }}"
                                                    data-bank_branch_code="{{ $quoteProfile->bank_branch_code }}"
                                                    data-bank_account_name="{{ $quoteProfile->bank_account_name }}"
                                                    data-bank_account_number="{{ $quoteProfile->bank_account_number }}"
                                                    data-validity_period="{{ $quoteProfile->validity_period }}"
                                                    data-authorisation_required="{{ $quoteProfile->authorisation_required }}"
                                                    data-letterhead_url="{{ $quoteProfile->letterhead_url }}">
                                                <i class="fa fa-pencil-square-o"></i> Edit
                                            </button>
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $quoteProfile->divisionLevelGroup->name }}</td>
                                        <td>{{ $quoteProfile->registration_number }}</td>
                                        <td>{{ $quoteProfile->vat_number }}</td>
                                        <td>{{ $quoteProfile->bank_name }}</td>
                                        <td>{{ $quoteProfile->bank_branch_code }}</td>
                                        <td>{{ $quoteProfile->bank_account_name }}</td>
                                        <td>{{ $quoteProfile->bank_account_number }}</td>
                                        <td style="text-align: center;">{{ $quoteProfile->validity_period . ' days' }}</td>
                                        <td style="text-align: center;">{{ !empty($quoteProfile->authorisation_required) && $quoteProfile->authorisation_required == 2 ? 'Yes' : 'No'}}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" id="add-new-profile" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-profile-modal"><i class="fa fa-plus"></i> Add Profile</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>

            <!-- Include add profile modal -->
            @include('quote.partials.add_quote_profile_modal')
            <!-- Include edit profile modal -->
            @include('quote.partials.edit_quote_profile_modal')
        </div>
        <!-- Include modal -->
        @if(Session('changes_saved'))
            @include('contacts.partials.success_action', ['modal_title' => "Users Access Updated!", 'modal_content' => session('changes_saved')])
        @endif

        <div class="col-md-12">
            <div class="box box-success collapsed-box">
                <form class="form-horizontal" method="POST" action="/email-template/save">
                    {{ csrf_field() }}
                    <div class="box-header with-border">
                        <h3 class="box-title">Send Quote Email Template</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <textarea id="send_quote_message" name="template_content" rows="10" cols="80">{{ ($sendQuoteTemplate) ? $sendQuoteTemplate->template_content : '' }}</textarea>
                        <input type="hidden" name="template_key" value="send_quote">
                        <br>
                        <p style="margin-bottom: 0;">Placeholders: [client name], [employee details]</p>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-floppy-o"></i> Save</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
        </div>
        <div class="col-md-12">
            <div class="box box-success collapsed-box">
                <form class="form-horizontal" method="POST" action="/email-template/save">
                    {{ csrf_field() }}
                    <div class="box-header with-border">
                        <h3 class="box-title">Client Approved Quote Email Template</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <textarea id="approved_quote_message" name="template_content" rows="10" cols="80">{{ ($approvedQuoteTemplate) ? $approvedQuoteTemplate->template_content : '' }}</textarea>
                        <input type="hidden" name="template_key" value="approved_quote">
						<br>
                        <p style="margin-bottom: 0;">Placeholders: [client name], [employee details</p>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-floppy-o"></i> Save</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
        </div>
		<div class="col-md-12">
            <div class="box-body">
                    <div align="center">
                        <div class="box-header with-border">
                            <h3 class="box-title">Quotes Configuration</h3>
                        </div>
                        <div class="box-body">
                            <a href="/quote/configuration" class="btn btn-app">
                                <i class="fa fa-cog"></i> Settings
                            </a>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="modal-footer"></div>
                </div>
        </div>
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

    <!-- CK Editor -->
    <script src="https://cdn.ckeditor.com/4.7.1/standard/ckeditor.js"></script>

    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();

            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();

            //slimScroll
            $('#quote-profile-list').slimScroll({
                height: '',
                railVisible: true
                //alwaysVisible: true
            });

            // [bootstrap file input] initialize with defaults
            $("#letter_head").fileinput();
            // with plugin options
            //$("#input-id").fileinput({'showUpload':false, 'previewFileType':'any'});

            // Replace the <textarea id="send_quote_message"> with a CKEditor
            // instance, using default configuration.
            CKEDITOR.replace('send_quote_message');
            CKEDITOR.replace('approved_quote_message');
           // CKEDITOR.replace('term_name');

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

            //Post perk form to server using ajax (add)
            $('#save-quote-profile').on('click', function() {
                var strUrl = '/quote/setup/add-quote-profile';
                var formName = 'add-new-profile-form';
                var modalID = 'add-new-profile-modal';
                var submitBtnID = 'save-quote-profile';
                var redirectUrl = '/quote/setup';
                var successMsgTitle = 'Quotation Profile Added!';
                var successMsg = 'The quotation profile has been added successfully!';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            var quoteProfileID;
            $('#edit-profile-modal').on('show.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                quoteProfileID = btnEdit.data('id');
                var divID = btnEdit.data('division_id');
                var regNumber = btnEdit.data('registration_number');
                var vatNumber = btnEdit.data('vat_number');
                var physAddress = btnEdit.data('phys_address');
                var city = btnEdit.data('phys_city');
                var postalCode = btnEdit.data('phys_postal_code');
                var phoneNumber = btnEdit.data('phone_number');
                var emailAddress = btnEdit.data('email');
                var bankName = btnEdit.data('bank_name');
                var branchCode = btnEdit.data('bank_branch_code');
                var accName = btnEdit.data('bank_account_name');
                var accNumber = btnEdit.data('bank_account_number');
                var valPeriod = btnEdit.data('validity_period');
                var AuthorisationRequired = btnEdit.data('authorisation_required');
                var letterHead = btnEdit.data('letterhead_url');
                var modal = $(this);
                modal.find('#division_id').val(divID).trigger('change');
                modal.find('#registration_number').val(regNumber);
                modal.find('#vat_number').val(vatNumber);
                modal.find('#phys_address').val(physAddress);
                modal.find('#phys_city').val(city);
                modal.find('#phys_postal_code').val(postalCode);
                modal.find('#phone_number').val(phoneNumber);
                modal.find('#email').val(emailAddress);
                modal.find('#bank_name').val(bankName);
                modal.find('#bank_branch_code').val(branchCode);
                modal.find('#bank_account_name').val(accName);
                modal.find('#bank_account_number').val(accNumber);
                modal.find('#validity_period').val(valPeriod).trigger('change');
                modal.find('#authorisation_required').val(AuthorisationRequired).trigger('change');
                //show letter head image if any
                var imgDiv = modal.find('#letterhead-img');
                imgDiv.empty();
                var htmlImg = $("<img>").attr('src', letterHead).attr('class', 'img-responsive img-thumbnail').attr('style', 'max-height: 235px;');
                if (letterHead) imgDiv.html(htmlImg);
            });

            //Post perk form to server using ajax (add)
            $('#update-quote-profile').on('click', function() {
                var strUrl = '/quote/setup/update-quote-profile/' + quoteProfileID;
                var formName = 'edit-profile-form';
                var modalID = 'edit-profile-modal';
                var submitBtnID = 'update-quote-profile';
                var redirectUrl = '/quote/setup';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'Your changes have been successfully saved!';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
        });
    </script>
@endsection
