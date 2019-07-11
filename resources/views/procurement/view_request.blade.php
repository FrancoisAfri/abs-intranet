@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection
@section('content')
	<div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                    <h3 class="box-title">Request Details</h3>
                </div>
                <div style="overflow-X:auto;">
                   <table class="table table-striped table-bordered">
						<tr>
							<td><b>Date Requeted:</b></td>
							<td>{{ !empty($procurement->date_created) ? date(' d M Y', $procurement->date_created) : '' }}</b></td>
							<td><b>Title:</b></td>
							<td>{{ !empty($procurement->title_name) ? $procurement->title_name : '' }}</b></td>
						</tr>
						<tr>
							<td><b>Employee:</b></td>
							<td>{{ (!empty($procurement->employees)) ?  $procurement->employees->first_name . ' ' .  $procurement->employees->surname : ''}}</b></td>
							<td><b>On Behalf Of:</td>
							<td>{{ (!empty($procurement->employeeOnBehalf)) ?  $procurement->employeeOnBehalf->first_name . ' ' .  $procurement->employeeOnBehalf->surname : ''}}</b></td>
						</tr>
						<tr>
							<td><b>Detail of Expenditure:</b></td>
							<td>{{ (!empty($procurement->detail_of_expenditure)) ?  $procurement->detail_of_expenditure : ''}}</b></td>
							<td><b>Justification of Expenditure:</b></td>
							<td>{{ !empty($procurement->justification_of_expenditure) ? $procurement->justification_of_expenditure : '' }}</b></td>
						</tr>
						<tr>
							<td><b>Special Instructions:</b></td>
							<td>{{ (!empty($procurement->special_instructions)) ?  $procurement->special_instructions : ''}}</b></td>
							<td><b>Status:</b></td>
							<td>{{ !empty($procurement->status) ? $procurement->requestStatus->step_name : '' }}</b></td>
						</tr>
						<tr>
							<td><b>Delivery / Collection:</b></td>
							<td>{{ (!empty($procurement->delivery_type)) ?  $deleiveryType[$procurement->delivery_type] : ''}}</td>
							<td><b>PO Number:</b></td>
							<td>{{ (!empty($procurement->po_number)) ?  $procurement->po_number : ''}}</td>
						</tr>
					</table>
					<table class="table table-striped table-bordered">
						<hr class="hr-text" data-content="PROCUREMENT REQUEST ITEMS">
						<tr>
							<td>#</td>
							<td><b>Category</b></td>
							<td><b>Product</b></td>
							<td style="text-align:center"><b>Quantity</b></td>
							<td style="text-align:center"><b>Price</b></td>
							<td></td>
						</tr>
						@if (count($procurement->procurementItems) > 0)
							@if ($procurement->item_type == 1)
								@foreach ($procurement->procurementItems as $items)
									<tr>
										<td>{{ $loop->iteration }}</td>
										<td>{{ !empty($items->categories->name) ? $items->categories->name : '' }}</td>
										<td>{{ !empty($items->products->name) ? $items->products->name : '' }}</td>
										<td style="text-align:right">{{ !empty($items->quantity) ? $items->quantity : '' }}</td>
										<td style="text-align:right">{{ !empty($items->item_price) ? 'R ' .number_format($items->item_price, 2) : '' }}</td>
										<td>
											@if ($procurement->status == 1)
												<button type="button" class="btn btn-danger btn-xs" data-toggle="modal"
													data-target="#remove-items-warning-modal"
													data-id="{{ $items->id }}"><i class="fa fa-trash"></i>  Remove
												</button>
											@endif
										</td>
									</tr>
								@endforeach
							@else
								@foreach ($procurement->procurementItems as $items)
									<tr>
										<td>{{ $loop->iteration }}</td>								<td>N/A</td>
										<td>{{ !empty($items->item_name) ? $items->item_name : '' }}</td>
										<td style="text-align:center">{{ !empty($items->quantity) ? $items->quantity : '' }}</td>
										<td style="text-align:center">{{ !empty($items->item_price) ? 'R ' .number_format($items->item_price, 2) : '' }}</td>
										<td>
											@if ($procurement->status == 1)
												<button type="button" class="btn btn-danger btn-xs" data-toggle="modal"
													data-target="#remove-items-warning-modal"
													data-id="{{ $items->id }}"><i class="fa fa-trash"></i>  Remove
												</button>
											@endif
										</td>
									</tr>
								@endforeach
							@endif
						@else
							<tr><td colspan="6"></td></tr>
						@endif
						<tr>
							<th style="text-align: right;"  colspan="4">Subtotal:</th>
							<td style="text-align: center;" id="subtotal" nowrap><b>{{ 'R ' . number_format($subtotal, 2) }}</b></td>
							<td></td>
						</tr>
						<tr>
							<th style="text-align: right;"  colspan="4">VAT:</th>
							<td style="text-align: center;" id="vat-amount" nowrap><b>{{ ($vatAmount > 0) ? 'R ' . number_format($vatAmount, 2) : '&mdash;' }}</b></td>
							<td></td>
						</tr>
						<tr>
							<th style="text-align: right;"  colspan="4">Total:</th>
							<td style="text-align: center;" id="total-amount" nowrap><b>{{ 'R ' . number_format($total, 2) }}</b></td>
							<td></td>
						</tr>
					</table>
					<!-- procurement histories-->
					<hr class="hr-text" data-content="PROCUREMENT AUDIT TRAIL">
					<div class="timeline-panel" style="max-height: 300px; overflow-y: scroll; overflow-x: scroll;">
						<table class="table table-striped table-bordered">
							<tr>
								<td>#</td>
								<td><b>Date</b></td>
								<td><b>Employee</b></td>
								<td><b>Note</b></td>
								<td><b>Status</b></td>
							</tr>
							@if (count($procurement->histories) > 0)
								@foreach ($procurement->histories as $history)
									<tr>
										<td>{{ $loop->iteration }}</td>
										<td>{{ !empty($history->action_date) ? date('d M Y H i s', $history->action_date) : '' }}</td>
										<td>{{ !empty($history->historyEmployees->first_name) ? $history->historyEmployees->first_name." ".$history->historyEmployees->surname : '' }}</td>
										<td>{{ !empty($history->action) ? $history->action : '' }}</td>
										<td>{{ !empty($history->statusHistory) ? $history->statusHistory->step_name : '' }}</td>
									</tr>
								@endforeach
							@else
								<tr><td colspan="5"></td><td style="text-align:center"></td></tr>
							@endif
						</table>
					</div>
					<!-- procurement quotations-->
					<hr class="hr-text" data-content="PROCUREMENT  QUOTATIONS">
					<div class="timeline-panel" style="max-height: 300px; overflow-y: scroll; overflow-x: scroll;">
						<table class="table table-striped table-bordered">
							<tr>
								<td>#</td>
								<td><b>Date Added</b></td>
								<td><b>Supplier</b></td>
								<td><b>Contact Person</b></td>
								<td style="text-align:center"><b>Total Price</b></td>
								<td><b>Comment</b></td>
								<td><b>Attachment</b></td>
							</tr>
							@if (count($procurement->quotations) > 0)
								@foreach ($procurement->quotations as $quotation)
									<tr>
										<td>{{ $loop->iteration }}</td>
										<td>{{ !empty($quotation->date_added) ? date('d M Y H i s', $quotation->date_added) : '' }}</td>
										<td>{{ !empty($quotation->companyQuote->name) ? $quotation->companyQuote->name : '' }}</td>
										<td>{{ !empty($quotation->clientQuote->first_name) ? $quotation->clientQuote->first_name." ".$quotation->clientQuote->surname : '' }}</td>
										<td style="text-align:center">{{ !empty($quotation->total_cost) ? 'R ' .number_format($quotation->total_cost, 2) : '' }}</td>
										<td>{{ !empty($quotation->comment) ? $quotation->comment : '' }}</td>
										<td> @if(!empty($quotation->attachment))
                                                <a class="btn btn-default btn-flat btn-block pull-right btn-xs"
                                                   href="{{ Storage::disk('local')->url("Procurement Quotations/$quotation->attachment") }}"
                                                   target="_blank"><i class="fa fa-file-pdf-o"></i> View Document</a>
                                            @else
                                                <a class="btn btn-default pull-centre btn-xs"><i
                                                            class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
                                            @endif
										</td>
									</tr>
								@endforeach
							@else
								<tr><td colspan="7"></td></tr>
							@endif
							<tr><td  colspan="7"><button type="button" id="add_quotations" class="btn btn-primary pull-right" data-toggle="modal" data-target="#quotation-add-modal"><i class="fa fa-plus-circle"></i> Add Quotation</button></td></tr>
						</table>
					</div>
                    <!-- /.box-body -->
                    <div class="box-footer">
						<button type="button" id="cancel" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i> Back</button>
						@if ($procurement->status == 1)
							<button type="button" class="btn btn-sm btn-default btn-flat pull-right"  onclick="postData({{$procurement->id}}, 'edit_request');">
								<i class="fa fa-pencil-square-o"></i> Modify</button>
						@endif
						@if(!empty($procurement->status) && $flow->step_number > $procurement->status)
							<button type="button" class="btn btn-primary btn-success pull-right" 
								id="request_approved" onclick="postData({{$procurement->id}}, 'request_approval');">
								<i class="fa fa-check"></i> Approve Request</button>
							<button type="button" class="btn btn-primary btn-danger pull-right" data-toggle="modal"
                            data-target="#request-reject-modal" data-id="{{ $procurement->id }}"
							><i class="fa fa-times"></i> Reject Request
							</button>
						@endif
						<a href="/procurement/print/{{ $procurement->id }}/pdf" target="_blank" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print Request</a>
                    </div>
                </div>
            </div>
			@include('procurement.partials.edit_quotation')
			@include('procurement.partials.quotation_add_modal')
			@include('procurement.partials.request_rejection')
			@if (count($procurement) > 0)
                @include('procurement.warnings.items_warning_action', ['modal_title' => 'Remove Item', 'modal_content' => 'Are you sure you want to remove this item? This action cannot be undone.'])
            @endif
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

    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
	<script type="text/javascript">

	$(function () {
		//Tooltip
		$('[data-toggle="tooltip"]').tooltip();
		//Cancel button click event
		document.getElementById("cancel").onclick = function () {
			if ("{{$back}}" === '')
				location.href = "/procurement/create_request";
			else if ("{{$back}}" === 'procurement') location.href = "/procurement/request_approval";
			else location.href = "/procurement/seach_request";
		};
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
		$(".select2").select2();
		var stockID;
		$('#remove-items-warning-modal').on('shown.bs.modal', function (e) {
			var btnDelete = $(e.relatedTarget);
			stockID = btnDelete.data('id');
		});
		$('#remove_item').on('click', function () {
			var strUrl = '/procurement/remove/items/' + stockID;
			var formName = 'remove-item-warning-modal-form';
			var modalID = 'remove-items-warning-modal';
			var submitBtnID = 'remove_item';
			var redirectUrl = '/procurement/viewrequest/{{ $procurement->id }}';
			var successMsgTitle = 'Item Successfully Removed!';
			var successMsg = 'Item has been removed successfully.';
			modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
		});
		//Post form to server using ajax (add)
		$('#save-rejection-reason').on('click', function () {
			var strUrl = '/procurement/reject-reason/' + {{$procurement->id}};
			var formName = 'decline-procurement-request-modal';
			var modalID = 'procurement-reject-modal';
			var submitBtnID = 'save-rejection-reason';
			var redirectUrl = '/procurement/viewrequest/{{ $procurement->id }}';
			var successMsgTitle = 'Rejection Reason Saved!';
			var successMsg = 'The rejection reason has been successfully saved.';
			modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
		});
		//
		//Post perk form to server using ajax (add)
		$('#save-quote').on('click', function() {
			var strUrl = '/procuremnt/quote/new/'+ {{$procurement->id}};
			var formName = 'add-quote-form';
			var modalID = 'quotation-add-modal';
			var submitBtnID = 'save-quote';
			var redirectUrl = '/procurement/viewrequest/'+ {{$procurement->id}} +'/back';
			var successMsgTitle = 'Quotation Added!';
			var successMsg = 'The new quotation has been added successfully!';
			modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
		});

		//Post quotation form to server using ajax (edit)
		$('#update-quotation').on('click', function() {
			var strUrl = '/appraisal/perks/' + perkID;
			var formName = 'edit-perk-form';
			var modalID = 'edit-perk-modal';
			var submitBtnID = 'update-perk';
			var redirectUrl = '/appraisal/perks';
			var successMsgTitle = 'Changes Saved!';
			var successMsg = 'The perk details have been updated successfully!';
			modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
		});

		//pass perk data to the edit perk modal
		var perkID;
		$('#edit-quotation-modal').on('show.bs.modal', function (e) {
			var btnEdit = $(e.relatedTarget);
			perkID = btnEdit.data('id');
			var name = btnEdit.data('name');
			var desc = btnEdit.data('description');
			var percent = btnEdit.data('req_percent');
			var perkImg = btnEdit.data('img');
			var modal = $(this);
			modal.find('#name').val(name);
			modal.find('#description').val(desc);
			modal.find('#req_percent').val(percent);
			//show perk image if any
			var imgDiv = modal.find('#perk-img');
			imgDiv.empty();
			var htmlImg = $("<img>").attr('src', perkImg).attr('class', 'img-responsive img-thumbnail').attr('style', 'max-height: 235px;');
			imgDiv.html(htmlImg);
		});
	});
	function postData(id, data)
	{
		if (data == 'request_approval')
			location.href = "/procurement/approve-request-single/" + id;
		else if (data == 'edit_request')
			location.href = "/procuremnt/modify_request/" + id;
	}
	</script>
@endsection