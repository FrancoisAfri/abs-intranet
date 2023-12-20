@extends('layouts.main_layout')
@section('page_dependencies')
<!-- Include Date Range Picker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- iCheck -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
<!-- bootstrap file input -->
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<!--Time Charger-->
@endsection
@section('content')
<div class="row">
    <!-- New User Form -->
    <div class="col-md-12">
        <!-- Horizontal Form -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-anchor pull-right"></i>
                <h3 class="box-title">Applications</h3>
                <p id="box-subtitle">Details</p>
            </div>
			<div class="box-body">
				<table id="example2" class="table table-bordered table-hover">
					<tr>
						<td class="caption">Employee Number</td>
						<td>{{ !empty($loan->users->employee_number) ? $loan->users->employee_number : '' }}</td>
						<td class="caption">Employee Name</td>
						<td>{{ !empty($loan->users->first_name) && !empty($loan->users->surname) ? $loan->users->first_name.' '. $loan->users->surname : '' }}</td>
					</tr>
					<tr>
						<td class="caption">loan Type</td>
						<td>{{ ((!empty($loan->type)) && $loan->type == 1)  ?  'Advance' : 'Loan'}}</td>
						<td class="caption">Amount</td>
						<td>{{ !empty($loan->amount) ? 'R ' .number_format($loan->amount, 2) : '' }}</td>
					</tr>
					<tr>
						<td class="caption">Repayment Month(s)</td>
						<td>{{ (!empty( $loan->repayment_month)) ?  $loan->repayment_month : ''}}</td>
						<td class="caption">Status</td>
						<td>{{ (!empty( $loan->status)) ?  $statuses[$loan->status] : ''}} </td>
					</tr>
					<tr>
						<td class="caption">First Approval</td>
						<td>{{!empty($loan->firstUsers->first_name) && !empty($loan->firstUsers->surname) ? $loan->firstUsers->first_name.' '.$loan->firstUsers->surname : '' }}</td>
						<td class="caption">First Approval Date</td>
						<td>{{ !empty($loan->first_approval_date) ? date('d M Y ', $loan->first_approval_date) : '' }}</td>
					</tr>
					<tr>
						<td class="caption">Second Approval</td>
						<td>{{!empty($loan->secondUsers->first_name) && !empty($loan->secondUsers->surname) ? $loan->secondUsers->first_name.' '.$loan->secondUsers->surname : '' }}</td>
						<td class="caption" width="25%">Second Approval Date</td>
						<td width="25%">{{ !empty($loan->second_approval_date) ? date('d M Y ', $loan->second_approval_date) : '' }}</td>
					</tr>
					<tr>
						<td class="caption">Rejection Reason</td>
						<td>{{!empty($loan->rejection_reason) ? $loan->rejection_reason : '' }}</td>
						<td class="caption">Rejected BY</td>
						<td>{{!empty($loan->rejectedUsers->first_name) && !empty($loan->rejectedUsers->surname) ? $loan->rejectedUsers->first_name.' '.$loan->rejectedUsers->surname : '' }}</td>
					</tr>
					<tr>
						<td class="caption">Rejection Date</td>
						<td>{{ !empty($loan->rejected_date) ? date('d M Y ', $loan->rejected_date) : '' }}</td>
						<td class="caption">Date Applied</td>
						<td>{{ !empty($loan->created_at) ? $loan->created_at : '' }}</td>
					</tr>
					<tr>
						<td class="caption">Notes</td>
						<td>{{ !empty($loan->reason) ? $loan->reason : '' }}</td>
						<td class="caption"></td>
						<td></td>
					</tr>
					
					
					
					</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>
											
											
				</table>
				<!-- /.box-body -->
				<div class="box-footer">
					<button id="cancel" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i> Back</button>
				</div>
				<!-- /.box-footer -->
			</div>
        </div>
        <!-- /.box -->
    </div>
    <!-- End new User Form-->
</div>
@endsection
@section('page_script')
<!-- Select2 -->
<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
<!-- bootstrap datepicker -->
<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- InputMask -->
<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js" type="text/javascript"></script>
<!-- the main fileinput plugin file -->
<!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
<script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
<script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
<!-- the main fileinput plugin file -->
<script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
<!-- Date rane picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>
<!-- iCheck -->
<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
<!-- Ajax dropdown options load -->
<script src="/custom_components/js/load_dropdown_options.js"></script>
<!-- Date picker
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
<!-- Ajax form submit -->
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<script type="text/javascript">
	$(function() {
		document.getElementById("cancel").onclick = function () {
            location.href = "/loan/search";
        };
		//Initialize Select2 Elements
		$(".select2").select2();
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