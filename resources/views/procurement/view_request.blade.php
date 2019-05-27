@extends('layouts.main_layout')

@section('page_dependencies')
 <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!--  -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css"
          rel="stylesheet">
    <!-- iCheck -->
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/green.css">
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
							<td><b>Rejection Reason:</b></td>
							<td></b></td>
							<td><b>PO Number:</b></td>
							<td>{{ (!empty($procurement->po_number)) ?  $procurement->po_number : ''}}</td>
						</tr>
					</table>
					<table class="table table-striped table-bordered">
						<hr class="hr-text" data-content="Procurement Request Items">
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
										<td style="text-align:right">{{ !empty($items->item_price) ? $items->item_price : '' }}</td>
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
										<td style="text-align:center">{{ !empty($items->item_price) ? $items->item_price : '' }}</td>
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
							<tr><td colspan="5"></td><td style="text-align:center">{{ !empty($items->item_price) ? $items->item_price : '' }}</td><td></td></tr>
						@endif
						<tr>
							<th style="text-align: center;"  colspan="5">Totals</th>
						</tr>
						<tr>
							<th style="text-align: right;"  colspan="4">Subtotal:</th>
							<td style="text-align: right;" id="subtotal" nowrap>{{ 'R ' . number_format($subtotal, 2) }}</td>
							<td></td>
						</tr>
						<tr>
							<th style="text-align: right; vertical-align: middle;"  colspan="4">VAT:</th>
							<td style="text-align: right; vertical-align: middle;" id="vat-amount" nowrap>{{ ($vatAmount > 0) ? 'R ' . number_format($vatAmount, 2) : '&mdash;' }}</td>
							<td></td>
						</tr>
						<tr>
							<th style="text-align: right; vertical-align: middle;"  colspan="4">Total:</th>
							<td style="text-align: right; vertical-align: middle;" id="total-amount" nowrap>{{ 'R ' . number_format($total, 2) }}</td>
							<td></td>
						</tr>
					</table>
                    <!-- /.box-body -->
                    <div class="box-footer">
						<button type="button" id="cancel" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i> Back</button>
						@if ($procurement->status == 1)
							<button type="button" class="btn btn-sm btn-default btn-flat pull-right">
								<i class="fa fa-pencil-square-o"></i> Edit</button>
						@endif
						@if(!empty($procurement->status) && $flow->step_number > $procurement->status)
							<button type="button" class="btn btn-primary btn-success pull-right" 
								id="request_approved" onclick="postData({{$procurement->id}}, 'request_approval');">
								<i class="fa fa-check"></i> Approve Request</button>
							<button type="button" class="btn btn-primary btn-danger pull-right" data-toggle="modal"
                            data-target="#procurement-reject-modal" data-id="{{ $procurement->id }}"
							><i class="fa fa-times"></i> Reject Request
							</button>
						@endif
                    </div>
                </div>
            </div>
			@include('procurement.partials.edit_request_modal')
			@include('procurement.partials.stock_request_rejection')
			@if (count($procurement) > 0)
                @include('procurement.warnings.items_warning_action', ['modal_title' => 'Remove Item', 'modal_content' => 'Are you sure you want to remove this item? This action cannot be undone.'])
            @endif

		</div>
    </div>
@endsection
@section('page_script')
<!-- DataTables -->
	<script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
	<script src="/custom_components/js/modal_ajax_submit.js"></script>
	<!-- time picker -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
	<!-- Select2 -->
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<!-- End Bootstrap File input -->
	<script src="/custom_components/js/modal_ajax_submit.js"></script>
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
	<!-- iCheck -->
	<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
	<script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
			type="text/javascript"></script>
	<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
	<script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js"
			type="text/javascript"></script>
	<!-- the main fileinput plugin file -->
	<script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
	<!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
	<script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>

	<!-- InputMask -->
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
	<script type="text/javascript">
		//Cancel button click event
	$(function () {
		$('#example2').DataTable({
			"paging": true,
			"lengthChange": true,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": true
		});
	});

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
		$(window).on('resize', function () {
			$('.modal:visible').each(reposition);
		});

		//Show success action modal
		$('#success-action-modal').modal('show');

		$(".js-example-basic-multiple").select2();

		$(".select2").select2();
		// call hide on_behalf_field
		$('.on_behalf_field').hide();
		$('input').iCheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green',
			increaseArea: '20%' // optional
		});
		$('#on_behalf').on('ifChecked', function(event){
			$('.on_behalf_field').show();
		});
		$('#on_behalf').on('ifUnchecked', function(event){
			$('.on_behalf_field').hide();
			$('#on_behalf_employee_id').val('');
		});

		//Post form to server using ajax (add)
		$('#edit-request-modal').on('shown.bs.modal', function (e) {
			var btnEdit = $(e.relatedTarget);
			JobId = btnEdit.data('id');
			var storeID = btnEdit.data('store_id');
			var employeeID = btnEdit.data('employee_id');
			var onBehalfEmployeeID = btnEdit.data('on_behalf_employee_id');
			var requestRemarks = btnEdit.data('request_remarks');
			var titleName = btnEdit.data('title_name');
			var onBehalfOf = btnEdit.data('on_behalf_of');
			
			var modal = $(this);
			modal.find('#on_behalf_of').val(onBehalfOf);
			modal.find('#request_remarks').val(requestRemarks);
			modal.find('#title_name').val(titleName);
			modal.find('select#on_behalf_employee_id').val(onBehalfEmployeeID);
			modal.find('select#employee_id').val(employeeID);
			modal.find('select#store_id').val(storeID);
			
			if (onBehalfEmployeeID > 0)
			{
				$('.on_behalf_field').show();
			}
		});
	  
		$('#update_request').on('click', function () {
			var strUrl = '/procurement/updateitems/' + {{$procurement->id}};
			console.log(strUrl);
			var formName = 'edit-request-form';
			var modalID = 'edit-request-modal';
			var submitBtnID = 'update_request';
			var redirectUrl = '/procurement/viewrequest/{{ $procurement->id }}';
			var successMsgTitle = 'Record Updated!';
			var successMsg = 'The Record  has been updated successfully.';
			var Method = 'PATCH'
			modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
		});
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
	});
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
		var file_clone = clone("product_row", file_index.value, "product_id");
		var name_clone = clone("quantity_row", file_index.value, "quantity");
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
	
	function postData(id, data)
	{
		if (data == 'request_approval')
			location.href = "/procurement/approve-request-single/" + id;
	}
	</script>
@endsection