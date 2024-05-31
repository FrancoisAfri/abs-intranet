@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Loan Application</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
               
                <div class="box-body">
					<div style="overflow-X:auto;">
						<table class="table table-bordered">
							<tr>
								<th style="width: 10px; text-align: center;"></th>
								<th>Type</th>
								<th>Amount</th>
								<th>Notes</th>
								<th>Repayment Month</th>
								<th>Status</th>
								<th>Rejection Reason</th>
								<th>Supporting Documents</th>
							</tr>
							@if (count($loans) > 0)
								@foreach ($loans as $loan)
									<tr>
										<td nowrap>
											@if ($loan->status == 1)
												<button type="button" id="edit_compan" class="btn btn-warning  btn-xs"
														data-toggle="modal" data-target="#edit-loan-modal"
														data-id="{{ $loan->id }}" 
														data-type="{{ $loan->type}}"
														data-amount="{{ $loan->amount}}"
														data-reason="{{$loan->reason}}"
														data-repayment_month="{{$loan->repayment_month}}">
														<i class="fa fa-pencil-square-o"></i> Edit
												</button>
											@endif
										</td>
										<td>{{ ((!empty($loan->type)) && $loan->type == 1)  ?  'Advance' : 'Loan'}} </td>
										<td>{{ (!empty( $loan->amount)) ?  'R ' .number_format($loan->amount, 2) : ''}} </td>
										<td>{{ (!empty( $loan->reason)) ?  $loan->reason : ''}} </td>
										<td style="width: 10px; text-align: center;">{{ (!empty( $loan->repayment_month)) ?  $loan->repayment_month : ''}} </td>
										<td>{{ (!empty( $loan->status)) ?  $statuses[$loan->status] : ''}} </td>
										<td>{{ (!empty( $loan->rejection_reason)) ?  $loan->rejection_reason : ''}} </td>
										<td nowrap>
											<div class="form-group{{ $errors->has('document') ? ' has-error' : '' }}">
												<label for="document" class="control-label"></label>
												@if(!empty($loan->loanDocs))
													@foreach ($loan->loanDocs as $doc)
													<a class="btn btn-default btn-flat btn-block pull-right btn-xs"
													   href="{{ Storage::disk('local')->url("loan/$doc->supporting_docs") }}"
													   target="_blank"><i class="fa fa-file-pdf-o"></i> {{$doc->doc_name}}</a>
													@endforeach
												@else
													<a class="btn btn-default pull-centre btn-xs"><i class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
												@endif
											</div>	 
										</td>
									</tr>
								@endforeach
							@else
								<tr id="categories-list">
									<td colspan="7">
										<div class="alert alert-danger alert-dismissable">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">
												&times;
											</button>
											No loan application to display, please start by adding a new loan ...
										</div>
									</td>
								</tr>
							@endif
						</table>
					</div>
                    <!--   </div> -->
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                        <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal"
                                data-target="#add-loan-modal">Add loan Application
                        </button>
                    </div>
                </div>
            </div>
            <!-- Include add new prime rate modal -->
        @include('loan.partials.add_loan_modal')
        @include('loan.partials.edit_loan_modal')
        </div>
    @endsection

@section('page_script')
	<script src="/custom_components/js/modal_ajax_submit.js"></script>
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
	<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
	<script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js"
			type="text/javascript"></script>
	<!-- the main fileinput plugin file -->
	<script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
	<!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
	<script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
	<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
	<!-- InputMask -->
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<!-- Ajax dropdown options load -->
	<script src="/custom_components/js/load_dropdown_options.js"></script>
	<!-- Ajax form submit -->
	<script src="/custom_components/js/modal_ajax_submit.js"></script>
	<script>
		function postData(id, data) {
			if (data == 'actdeac') location.href = "/System/policy_act/" + id;

		}

		$('#back_button').click(function () {
			location.href = '/System/loan/create';
		});

		$(function () {
			var moduleId;
			//Initialize Select2 Elements
			$(".select2").select2();
			$('.zip-field').hide();


			//Tooltip

			//Phone mask
			$("[data-mask]").inputmask();

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
			$(window).on('resize', function () {
				$('.modal:visible').each(reposition);
			});

			//Show success action modal
			$('#success-action-modal').modal('show');

			//

			$(".js-example-basic-multiple").select2();

			//Initialize iCheck/iRadio Elements
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-blue',
				radioClass: 'iradio_square-blue',
				increaseArea: '10%' // optional
			});


			$(document).ready(function () {

				$('input[name="date"]').datepicker({
					format: 'dd/mm/yyyy',
					autoclose: true,
					todayHighlight: true
				});
			});
			//save Fleet
			$('#add-loan').on('click', function () {
				
				var strUrl = '/loan/add_loan';
				var formName = 'add-loan-form';
				var modalID = 'add-loan-modal';
				var submitBtnID = 'add-loan';
				var submitButton = $('#' + submitBtnID);
				submitButton.prop('disabled', true); // Disable the submit button to prevent multiple submissions
				var redirectUrl = '/loan/view';
				var successMsgTitle = 'New Application  Added!';
				var successMsg = 'The Application has been added successfully.';
				modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
			});


			var appID;
			$('#edit-loan-modal').on('shown.bs.modal', function (e) {
				var btnEdit = $(e.relatedTarget);
				if (parseInt(btnEdit.data('id')) > 0) {
					appID = btnEdit.data('id');
				}
				var type = btnEdit.data('type');
				var amount = btnEdit.data('amount');
				var reason = btnEdit.data('reason');
				var repayment_month = btnEdit.data('repayment_month');

				var modal = $(this);
				modal.find('#reason').val(reason);
				modal.find('#type').val(type);
				modal.find('#amount').val(amount);
				modal.find('#repayment_month').val(repayment_month);
			});

			//Post perk form to server using ajax (edit)
			$('#edit_loan').on('click', function () {
				var strUrl = '/loan/edit_loan/' + appID;
				var formName = 'edit-loan-form';
				var modalID = 'edit-loan-modal';
				var submitBtnID = 'edit_loan';
				var redirectUrl = '/loan/view';
				var successMsgTitle = 'Changes Saved!';
				var successMsg = 'Application details been updated successfully!';
				modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
			});
		});
		// clone
			function clone(id, file_index, child_id) {
				var clone = document.getElementById(id).cloneNode(true);
				clone.setAttribute("id", file_index);
				clone.setAttribute("name", file_index);
				clone.style.display = "table-row";
				clone.querySelector('#' + child_id).setAttribute("name", child_id + '[' + file_index + ']');
				clone.querySelector('#' + child_id).disabled = false;
				clone.querySelector('#' + child_id).setAttribute("id", child_id + '[' + file_index + ']');
				return clone;
			}
			function addFile() {
				var table = document.getElementById("tab_tab");
				var file_index = document.getElementById("file_index");
				file_index.value = ++file_index.value;
				var file_clone = clone("file_row", file_index.value, "document");
				var name_clone = clone("name_row", file_index.value, "name");
				var final_row = document.getElementById("final_row").cloneNode(false);
				table.appendChild(file_clone);
				table.appendChild(name_clone);
				table.appendChild(final_row);
				var total_files = document.getElementById("total_files");
				total_files.value = ++total_files.value;
				//change the following using jquery if necessary
				var remove = document.getElementsByName("remove");
				for (var i = 0; i < remove.length; i++)
					remove[i].style.display = "inline";
			}
			
			function removeFile(row_name)
			{
				var row=row_name.parentNode.parentNode.id;
				var rows=document.getElementsByName(row);
				while(rows.length>0)
					rows[0].parentNode.removeChild(rows[0]);
				var total_files = document.getElementById("total_files");
				total_files.value=--total_files.value;
				var remove=document.getElementsByName("remove");
				if(total_files.value == 1)
					remove[1].style.display='none';
			}
	</script>
@endsection
