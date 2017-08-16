@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row">
		<!-- terms And Conditions types -->
		<div class="col-md-12">
            <div class="box box-primary collapsed-box">
                <form class="form-horizontal" method="POST" action="">
                    {{ csrf_field() }}
                    <div class="box-header with-border">
                        <h3 class="box-title">Terms and Conditions</h3>
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
									<th style="text-align: center; width: 5px;">#</th>
									<th>Terms</th>
									<th style="text-align: center;"></th>
								</tr>
								@foreach($termConditions as $termCondition)
									<tr>
										<td style="text-align: center;">
											<button type="button" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-quotes-term-modal"
													data-id="{{ $termCondition->id }}"
													data-term_name="{{ $termCondition->term_name }}">
												<i class="fa fa-pencil-square-o"></i> Edit
											</button>
										</td>
										<td>{{ $termCondition->term_name }}</td>
										<td style="text-align: center;"> <button type="button" id="view_kpi" class="btn {{ (!empty($termCondition->status) && $termCondition->status == 1) ? "btn-danger" : "btn-success" }} btn-xs"><i class="fa {{ (!empty($termCondition->status) && $termCondition->status == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($termCondition->status) && $termCondition->status == 1) ? "De-Activate" : "Activate"}}</button></td>
									</tr>
								@endforeach
							</table>
						</div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" id="add-new-term-type" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-quotes-terms-modal">Add New Term & Conditions</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
			@include('quote.partials.add_quote_terms_modal')
			@include('quote.partials.edit_quote_terms_modal')
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
            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // Replace the <textarea id="send_quote_message"> with a CKEditor
            // instance, using default configuration.
			CKEDITOR.replace('term_name');

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
            $('#success-action-modal').modal('show');
			//Post term & conditions form to server using ajax (add)
            $('#save-quote-term').on('click', function() {
                var strUrl = '/quote/add-quote-term';
                var formName = 'add-new-term-form';
                var modalID = 'add-new-term-modal';
                var submitBtnID = 'save-quote-term';
                var redirectUrl = '/quote/term-conditions';
                var successMsgTitle = 'Quotation term Added!';
                var successMsg = 'The quotation term has been added successfully!';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            var termConditionID;
            $('#edit-quotes-term-modal').on('show.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                termConditionID = btnEdit.data('id');
                var divID = btnEdit.data('division_id');
                var modal = $(this);
                modal.find('#division_id').val(divID).trigger('change');
                modal.find('#registration_number').val(regNumber);
            });

            //Post perk form to server using ajax (add)
            $('#update-quote-profile').on('click', function() {
                var strUrl = '/quote/setup/update-quote-profile/' + termConditionID;
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