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
<!-- ### -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
		<div class="col-md-12">
			<!-- Company's contacts box -->
            <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-users"></i> Users Access Request</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding no-margin">
					<div style="overflow-X:auto; margin-right: 10px; max-height: 250px;">
					   <table class="table table-striped">
							<tr>
								<th colspan="5" style="text-align: center;">Folders Access</th>
							</tr>
							<tr>
								<th>Requested By</th>
								<th>Date Requested</th>
								<th>Folder</th>
								<th>Expiry Date</th>
								<th>#</th>
								<th>#</th>
							</tr>
							@if (!empty($userAccessFolders))
								@foreach($userAccessFolders as $userAccessFolder)
									<tr>
										<td>{{ !empty($userAccessFolder->employee->first_name) && !empty($userAccessFolder->employee->surname) ?  $userAccessFolder->employee->first_name." ".$userAccessFolder->employee->surname : '' }}</td>
										<td>{{ !empty($userAccessFolder->date_requested) ? date('d M Y ', $userAccessFolder->date_requested) : '' }}</td>
										<td>{{ !empty($userAccessFolder->userFolder->folder_name) ? $userAccessFolder->userFolder->folder_name : '' }}</td>
										<td>{{ !empty($userAccessFolder->expiry_date) ? date('d M Y ', $userAccessFolder->expiry_date) : '' }}</td>
										<td>
											<button type="button" id="Accept"
													class="btn btn-success btn-xs btn-detail open-modal"
													value="{{$userAccessFile->id}}"
													onclick="postData({{$userAccessFile->id}}, 'approval_id')">Accept
											</button>

										</td>
										<td>
											<button type="button" id="reject-reason" class="btn btn-danger btn-xs"
												data-toggle="modal" data-target="#reject-leave-modal"
												data-id="{{ $userAccessFile->id }}">Decline</button>
										</td>
									</tr>
								@endforeach
							@endif
						</table>
						<table class="table table-striped" >
							<tr>
								<th colspan="5" style="text-align: center;">Files Access</th>
							</tr>
							<tr>
								<th>Requested By</th>
								<th>Date Requested</th>
								<th>File</th>
								<th>Expiry Date</th>
								<th>#</th>
								<th>#</th>
							</tr>
							@if (count($userAccessFiles) > 0)
								@foreach($userAccessFiles as $userAccessFile)
								   <tr>
										<td>{{ !empty($userAccessFile->employee->first_name) && !empty($userAccessFile->employee->surname) ?  $userAccessFile->employee->first_name." ".$userAccessFile->employee->surname : '' }}</td>
										<td>{{ !empty($userAccessFile->date_requested) ? date('d M Y ', $userAccessFile->date_requested) : '' }}</td>
										<td>{{ !empty($userAccessFile->userFile->document_name) ? $userAccessFile->userFile->document_name : '' }}</td>
										<td>{{ !empty($userAccessFile->expiry_date) ? date('d M Y ', $userAccessFile->expiry_date) : '' }}</td>
										<td>
											<button type="button" id="Accept"
													class="btn btn-success btn-xs btn-detail open-modal"
													value="{{$userAccessFile->id}}"
													onclick="postData({{$userAccessFile->id}}, 'approval_id')">Accept
											</button>

										</td>
										<td>
											<button type="button" id="reject-reason" class="btn btn-danger btn-xs"
												data-toggle="modal" data-target="#reject-request-modal"
												data-id="{{ $userAccessFile->id }}">Decline</button>
										</td>
 									</tr>
								@endforeach
							@endif
						</table>
					</div>
					<!-- /.box-body -->
					<div class="box-footer">
					</div>
					@include('dms.partials.reject_request')
					<!--  -->
					@if(Session('success_application'))
						@include('leave.partials.success_action', ['modal_title' => "Application Successful!", 'modal_content' => session('success_application')])
					@endif
				</div>
			</div>
		</div>
    </div>
@endsection
@section('page_script')
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
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
    <!-- InputMask -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
		<!-- Ajax dropdown options load -->
	<script src="/custom_components/js/load_dropdown_options.js"></script>

    <script type="text/javascript">
		function postData(id, data) {
				if (data == 'approval_id') location.href = "/dms/approve-request/" + id;
			}
			
        $(function () {
			
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

            //Show success action modal
            $('#success-action-modal').modal('show');
			var reject_ID;
			$('#reject-request-modal').on('show.bs.modal', function (e) {
				var btnEdit = $(e.relatedTarget);
				reject_ID = btnEdit.data('id');
				var modal = $(this);
			});
			//Post module form to server using ajax (ADD)
			$('#reject_request').on('click', function () {
				//console.log('strUrl');
				var strUrl = '/dms/reject-request/' + reject_ID;
				var modalID = 'reject-request-modal';
				var objData = {
					description: $('#' + modalID).find('#description').val(),
					_token: $('#' + modalID).find('input[name=_token]').val()
				};
				var submitBtnID = 'reject_request';
				var redirectUrl = '/dms/request';
				var successMsgTitle = 'Rejection Reason Send!';
				var successMsg = 'The rejection reason has been Saved successfully.';
				//var formMethod = 'PATCH';
				modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
			});
        });
    </script>
@endsection