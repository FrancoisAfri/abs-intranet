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
                <h3 class="box-title">Leave Application</h3>
                <p id="box-subtitle">Details</p>
            </div>
			<div class="box-body">
				<table id="example2" class="table table-bordered table-hover">
					<tr>
						<td class="caption">Employee Number</td>
						<td>{{ !empty($leave->person->employee_number) ? $leave->person->employee_number : '' }}</td>
						<td class="caption">Employee Name</td>
						<td>{{ !empty($leave->person->first_name) && !empty($leave->person->surname) ? $leave->person->first_name.' '. $leave->person->surname : '' }}</td>
					</tr>
					<tr>
						<td class="caption">Leave Type</td>
						<td>{{ !empty($leave->leavetpe->name) ? $leave->leavetpe->name : ''}}</td>
						<td class="caption">Day(s) Requested</td>
						<td>{{ !empty($leave->leave_taken) ? $leave->leave_taken / 8 : ''}}</td>
					</tr>
					<tr>
						<td class="caption">Supporting Document</td>
						<td>@if(!empty($leave->supporting_docs))
								<a class="btn btn-default btn-flat btn-block pull-right btn-xs"
								   href="{{ Storage::disk('local')->url("Leave/LeaveDocuments/$leave->supporting_docs") }}" target="_blank"><i
											class="fa fa-file-pdf-o"></i> View Document</a>
							@else
								<a class="btn btn-default pull-centre btn-xs"><i
											class="fa fa-exclamation-triangle"></i> Nothing
									Uploaded</a>
							@endif</td>
						<td class="caption">Status</td>
						<td>{{ !empty($leave->status) ? $leaveStatus[$leave->status] : ''}}</td>
					</tr>
					<tr>
						<td class="caption">Start Date</td>
						<td>{{ !empty($leave->start_date) ? date(' d M Y', $leave->start_date) : ''}}</td>
						<td class="caption">End Date</td>
						<td>{{ !empty($leave->end_date) ? date(' d M Y', $leave->end_date) : ''}}</td>
					</tr>
					<tr>
						<td class="caption">Notes</td>
						<td>{{ !empty($leave->notes) ? $leave->notes : ''}}</td>
						<td class="caption" width="25%">Date Added</td>
						<td width="25%">{{ !empty($leave->created_at) ? $leave->created_at : ''}}</td>
					</tr>
					<tr>
						<td class="caption">Reject Reason</td>
						<td>{{ !empty($leave->reject_reason) ? $leave->reject_reason : ''}}</td>
						<td class="caption">Manager</td>
						<td>{{ (!empty($leave->manager->first_name)) ?  $leave->manager->first_name.' '.$leave->manager->surname : ''}}</td>
					</tr>
					<tr>
						<td class="caption">Canceller By</td>
						<td>{{ (!empty($leave->canceller->first_name)) ?  $leave->canceller->first_name.' '.$leave->canceller->surname : ''}}</td>
						<td class="caption">Cancellation Reason</td>
						<td>{{ !empty($leave->cancellation_reason) ? $leave->cancellation_reason : ''}}</td>
					</tr>
				</table>
				<!-- /.box-body -->
				<div class="box-footer">
					<button id="cancel" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i> Back</button>
					@if (!empty($isAdmin))
						<button id="cancel_application" data-toggle="modal" data-target="#cancel-leave-modal" class="btn btn-primary btn-warning pull-right"><i class="fa fa-trash"></i> Cancel Application</button>
					@endif
				</div>
				<!-- /.box-footer -->
			</div>
        </div>
        <!-- /.box -->
    </div>
    <!-- End new User Form-->
    <!-- Confirmation Modal -->
	@if ($isAdmin == 1)
		 @include('leave.partials.cancel_leave')
	@endif
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
            location.href = "/leave/search";
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
		
		//Post Reason  for rejectionusing ajax
		$('#cancellation-reason').on('click', function() {
			var strUrl = '/leave/cancellation/' + {{ $leave->id }};
			var modalID = 'cancel-leave-modal';
			var objData = {
				reason: $('#'+modalID).find('#reason').val(),
				_token: $('#'+modalID).find('input[name=_token]').val()
			};
			var submitBtnID = 'cancellation-reason';
			var redirectUrl = '/leave/view/application/' + {{ $leave->id }};
			//var redirectUrl = null;
			var successMsgTitle = 'Leave Application Cancelled!';
			var successMsg = 'Cancellation Reason Saved successfully.';
			modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
		});
	});
</script>
@endsection