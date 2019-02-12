@extends('layouts.main_layout')

@section('page_dependencies')
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
@endsection
@section('content')
	<div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                    <h3 class="box-title"> View Request </h3>
                </div>
                <div style="overflow-X:auto;">
                   <table class="table table-striped table-bordered">
						<tr>
							<td><b>Date Requeted:</b></td>
							<td>{{ !empty($stock->date_created) ? date(' d M Y', $stock->date_created) : '' }}</b></td>
							<td><b>Title:</b></td>
							<td>{{ !empty($stock->title_name) ? $stock->title_name : '' }}</b></td>
						</tr>
						<tr>
							<td><b>Employee:</b></td>
							<td>{{ (!empty($stock->employees)) ?  $stock->employees->first_name . ' ' .  $stock->employees->surname : ''}}</b></td>
							<td><b>On Behalf Of:</td>
							<td>{{ (!empty($stock->employeeOnBehalf)) ?  $stock->employeeOnBehalf->first_name . ' ' .  $stock->employeeOnBehalf->surname : ''}}</b></td>
						</tr>
						<tr>
							<td><b>Remarks:</b></td>
							<td>{{ (!empty($stock->request_remarks)) ?  $stock->request_remarks : ''}}</b></td>
							<td><b>Status:</b></td>
							<td>{{ !empty($stock->status) ? $stock->requestStatus->step_name : 'Rejected' }}</b></td>
						</tr>
						<tr>
							<td><b>Rejection Reason:</b></td>
							<td>{{ (!empty($stock->rejection_reason)) ?  $stock->rejection_reason : ''}}</b></td>
							<td><b>Rejected By:</b></td>
							<td>{{ !empty($stock->rejectedPerson) ? $stock->rejectedPerson->first_name . ' ' .  $stock->rejectedPerson->surname : '' }}</b></td>
						</tr>
						<tr>
							<td><b>Date Rejected:</b></td>
							<td>{{ (!empty($stock->rejection_date)) ? date(' d M Y', $stock->rejection_date) : ''}}</b></td>
							<td></td>
							<td></td>
						</tr>
					</table>
					<table class="table table-striped table-bordered">
						<hr class="hr-text" data-content="Stock Items">
						<tr>
							<td>#</td>
							<td><b>Category</b></td>
							<td><b>Product</b></td>
							<td style="text-align:center"><b>Quantity</b></td>
							<td></td>
						</tr>
						@if (count($stock->stockItems) > 0)
							@foreach ($stock->stockItems as $items)
								<tr>
									<td>{{ $loop->iteration }}</td>
									<td>{{ !empty($items->categories->name) ? $items->categories->name : '' }}</td>
									<td>{{ !empty($items->products->name) ? $items->products->name : '' }}</td>
									<td style="text-align:center">{{ !empty($items->quantity) ? $items->quantity : '' }}</td>
									<td>
										@if ($stock->status == 1)
											<button type="button" class="btn btn-danger btn-xs" data-toggle="modal"
												data-target="#remove-items-warning-modal"
												data-id="{{ $items->id }}"><i class="fa fa-trash"></i>  Remove
											</button>
										@endif
									</td>
								</tr>
							@endforeach
						@else
							<tr><td colspan="4"></td></tr>
						@endif
					</table>
                    <!-- /.box-body -->
                    <div class="box-footer">
						<button type="button" id="cancel" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i> Back</button>
						@if(!empty($stock->status) && $flow->step_number == $stock->status)
							<button type="button" class="btn btn-primary btn-danger pull-right" data-toggle="modal"
                            data-target="#stock-collection-modal" data-id="{{ $stock->id }}"
							><i class="fa fa-times"></i> Close Request
							</button>
						@endif
						<a href="{{ '/stock/print_delivery_note/' . $stock->id }}"
						   class="btn btn-sm btn-default btn-flat  pull-right" target=”_blank”">Print Delivery Note</a>
                    </div>
                </div>
            </div>
			@include('stock.partials.stock_request_close')
		</div>
    </div>
@endsection
@section('page_script')
<!-- DataTables -->
	<script src="/custom_components/js/modal_ajax_submit.js"></script>
	<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
	<script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js"
			type="text/javascript"></script>
	<!-- the main fileinput plugin file -->
	<script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
	<script type="text/javascript">
		//Cancel button click event
	$(function () {
		//Tooltip
		$('[data-toggle="tooltip"]').tooltip();
		//Cancel button click event
            document.getElementById("cancel").onclick = function () {
				location.href = "/stock/request_collection";
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
		$(window).on('resize', function () {
			$('.modal:visible').each(reposition);
		});

		//Show success action modal
		$('#success-action-modal').modal('show');

		//Post form to server using ajax (add)
		$('#close-request').on('click', function () {
			var strUrl = '/stock/close-request/' + {{$stock->id}};
			var formName = 'stock-collection-modal';
			var modalID = 'stock-collection-modal';
			var submitBtnID = 'close-request';
			var redirectUrl = '/stock/request_collection';
			var successMsgTitle = 'Rejection Reason Saved!';
			var successMsg = 'The rejection reason has been successfully saved.';
			modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
		});
	});
	</script>
@endsection