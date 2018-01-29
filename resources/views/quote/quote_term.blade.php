@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row">
		<!-- terms And Conditions types -->
		<div class="col-md-12">
            <div class="box box-primary">
                <form class="form-horizontal" method="POST" action="">
                    {{ csrf_field() }}
                    <div class="box-header with-border">
                        <h3 class="box-title">Terms and Conditions for: {{$category->name}}</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
						<div style="overflow-X:auto;">
							<table class="table table-bordered table-striped">
								<tr>
									<th style="text-align: center; width: 5px;">#</th>
									<th>Terms</th>
									<th style="text-align: center;"></th>
								</tr>
								@if (!empty($category->terms))
									@foreach($category->terms as $termCondition)
										<tr>
											<td style="text-align: center;">
												<button type="button" class="btn btn-primary  btn-xs" onclick="postData({{$termCondition->id}}, 'edit');">
													<i class="fa fa-pencil-square-o"></i> Edit
												</button>
											</td>
											<td>{!!$termCondition->term_name!!}</td>
											<td style="text-align: center;"> <button type="button" id="act_deact" onclick="postData({{$termCondition->id}}, 'actdeac');" class="btn {{ (!empty($termCondition->status) && $termCondition->status == 1) ? "btn-danger" : "btn-success" }} btn-xs"><i class="fa {{ (!empty($termCondition->status) && $termCondition->status == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($termCondition->status) && $termCondition->status == 1) ? "De-Activate" : "Activate"}}</button></td>
										</tr>
									@endforeach
								@else
									<tr id="kpis-list">
										<td colspan="5">
											<div class="alert alert-danger alert-dismissable">
												<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
												No term & condition to display, please start by adding a new one.
											</div>
										</td>
									</tr>
								@endif
							</table>
						</div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
						<button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
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
	function postData(id, data)
		{
			if (data == 'actdeac')
				location.href = "/quote/term-actdeact/" + id;
			else if (data == 'edit')
				location.href = "/quote/term-edit/" + id;
			
		}
        $(function () {
						
			document.getElementById("back_button").onclick = function () {
			location.href = "/quote/categories-terms";	};
            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // Replace the <textarea id="send_quote_message"> with a CKEditor
            // instance, using default configuration.
			CKEDITOR.replace('term_name');
			//CKEDITOR.replace();

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
                var strUrl = '/quote/add-quote-term/{{$category->id}}';
                var formName = 'quotes-term-form';
                var modalID = 'add-quotes-terms-modal';
                var submitBtnID = 'save-quote-term';
                var redirectUrl = '/quote/term-conditions/{{$category->id}}';
                var successMsgTitle = 'Quotation term Added!';
                var successMsg = 'The quotation term has been added successfully!';
               for (instance in CKEDITOR.instances) {
					CKEDITOR.instances[instance].updateElement();
				}
				$("#term_name").serialize()
			   modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            var termConditionID;
            
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