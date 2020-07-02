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
            <!-- /.box -->
            <!-- Company's contacts box -->
            <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-users"></i> Company Access</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding no-margin">
                    <div style="overflow-X:auto; margin-right: 10px; max-height: 250px;">
						<table class="table table-striped" >
							<tr>
								<th colspan="6" style="text-align: center;">Folders Access</th>
							</tr>
							<tr>
								<th>Division</th>
								<th>Department</th>
								<th>Section</th>
								<th>Folder</th>
								<th>Administrator</th>
								<th>Expiry Date</th>
							</tr>
							@if (count($companyAccessFolders) > 0)
								@foreach($companyAccessFolders as $companyAccessFolder)
								   <tr>
										<td>{{ (!empty($companyAccessFolder->companyname)) ?  $companyAccessFolder->companyname : ''}} </td>
										<td>{{ !empty($companyAccessFolder->first_name) && !empty($companyAccessFolder->surname) ?  $companyAccessFolder->first_name." ".$companyAccessFolder->surname : '' }}</td>
										<td>{{ !empty($companyAccessFolder->communication_date) ? date('d M Y ', $companyAccessFolder->communication_date) : '' }}</td>
										<td>{{ !empty($companyAccessFolder->time_sent) ? $companyAccessFolder->time_sent : '' }}</td>
										<td>{{ (!empty($companyAccessFolder->communication_type)) ?  $communicationStatus[$companyAccessFolder->communication_type] : ''}} </td>
										<td>{{ (!empty($companyAccessFolder->message)) ?  $companyAccessFolder->message : ''}} </td> 
									</tr>
								@endforeach
							@endif
						</table>
						<table class="table table-striped" >
							<tr>
								<th colspan="6" style="text-align: center;">Files Access</th>
							</tr>
							<tr>
								<th>Division</th>
								<th>Department</th>
								<th>Section</th>
								<th>File</th>
								<th>Administrator</th>
								<th>Expiry Date</th>
							</tr>
							@if (count($companyAccessFiles) > 0)
								@foreach($companyAccessFiles as $companyAccessFile)
								   <tr>
										<td>{{ (!empty($companyAccessFile->companyname)) ?  $companyAccessFile->companyname : ''}} </td>
										<td>{{ !empty($companyAccessFile->first_name) && !empty($companyAccessFile->surname) ?  $companyAccessFile->first_name." ".$companyAccessFile->surname : '' }}</td>
										<td>{{ !empty($companyAccessFile->communication_date) ? date('d M Y ', $companyAccessFile->communication_date) : '' }}</td>
										<td>{{ !empty($companyAccessFile->time_sent) ? $companyAccessFile->time_sent : '' }}</td>
										<td>{{ (!empty($companyAccessFile->communication_type)) ?  $communicationStatus[$companyAccessFile->communication_type] : ''}} </td>
										<td>{{ (!empty($companyAccessFile->message)) ?  $companyAccessFile->message : ''}} </td> 
										<td>{{ (!empty($companyAccessFile->hr_firstname) && !empty($companyAccessFile->hr_surname)) ?  $companyAccessFile->hr_firstname." ".$companyAccessFile->hr_surname : ''}} </td> 
									</tr>
								@endforeach
							@endif
						</table>
					</div>
					<!-- /.box-body -->
					<div class="box-footer">
						<button type="button" id="add-task" class="btn btn-success pull-right" data-toggle="modal"
								data-target="#add-company-access-modal" data-meeting_id="">Add Company Access
						</button>
					</div>
				</div>
            </div>
		</div>
		<div class="col-md-12">
			<!-- Company's contacts box -->
            <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-users"></i> Group Access</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding no-margin">
					<div style="overflow-X:auto; margin-right: 10px; max-height: 250px;">
						<table class="table table-striped" >
							<tr>
								<th colspan="4" style="text-align: center;">Folders Access</th>
							</tr>
							<tr>
								<th>Group</th>
								<th>Folder</th>
								<th>Administrator</th>
								<th>Expiry Date</th>
							</tr>
							@if (count($groupAccessFolders) > 0)
								@foreach($groupAccessFolders as $groupAccessFolder)
								   <tr>
										<td>{{ (!empty($groupAccessFolder->companyname)) ?  $groupAccessFolder->companyname : ''}} </td>
										<td>{{ !empty($groupAccessFolder->first_name) && !empty($groupAccessFolder->surname) ?  $groupAccessFolder->first_name." ".$userAccessFile->surname : '' }}</td>
										<td>{{ !empty($groupAccessFolder->communication_date) ? date('d M Y ', $groupAccessFolder->communication_date) : '' }}</td>
										<td>{{ !empty($groupAccessFolder->time_sent) ? $groupAccessFolder->time_sent : '' }}</td>
										<td>{{ (!empty($groupAccessFolder->communication_type)) ?  $communicationStatus[$groupAccessFolder->communication_type] : ''}} </td>
										<td>{{ (!empty($groupAccessFolder->message)) ?  $groupAccessFolder->message : ''}} </td> 
										<td>{{ (!empty($groupAccessFolder->hr_firstname) && !empty($groupAccessFolder->hr_surname)) ?  $groupAccessFolder->hr_firstname." ".$userAccessFile->hr_surname : ''}} </td> 
									</tr>
								@endforeach
							@endif
						</table>
						<table class="table table-striped" >
							<tr>
								<th colspan="4" style="text-align: center;">Files Access</th>
							</tr>
							<tr>
								<th>Group</th>
								<th>File</th>
								<th>Administrator</th>
								<th>Expiry Date</th>
							</tr>
							@if (count($groupAccessFiles) > 0)
								@foreach($groupAccessFiles as $groupAccessFile)
								   <tr>
										<td>{{ (!empty($groupAccessFile->companyname)) ?  $groupAccessFile->companyname : ''}} </td>
										<td>{{ !empty($groupAccessFile->first_name) && !empty($groupAccessFile->surname) ?  $groupAccessFile->first_name." ".$groupAccessFile->surname : '' }}</td>
										<td>{{ !empty($groupAccessFile->communication_date) ? date('d M Y ', $groupAccessFile->communication_date) : '' }}</td>
										<td>{{ !empty($groupAccessFile->time_sent) ? $groupAccessFile->time_sent : '' }}</td>
										<td>{{ (!empty($groupAccessFile->communication_type)) ?  $communicationStatus[$groupAccessFile->communication_type] : ''}} </td>
										<td>{{ (!empty($groupAccessFile->message)) ?  $groupAccessFile->message : ''}} </td> 
										<td>{{ (!empty($groupAccessFile->hr_firstname) && !empty($groupAccessFile->hr_surname)) ?  $groupAccessFile->hr_firstname." ".$groupAccessFile->hr_surname : ''}} </td> 
									</tr>
								@endforeach
							@endif
						</table>
					</div>
					<!-- /.box-body -->
					<div class="box-footer">
						<button type="button" id="add-task" class="btn btn-success pull-right" data-toggle="modal"
								data-target="#add-task-modal" data-meeting_id="">Add Group Access
						</button>
					</div>
				</div>
            </div>
        </div>
		<div class="col-md-12">
			<!-- Company's contacts box -->
            <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-users"></i> Users Access</h3>
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
								<th colspan="4" style="text-align: center;">Folders Access</th>
							</tr>
							<tr>
								<th>Employee</th>
								<th>Folder</th>
								<th>Administrator</th>
								<th>Expiry Date</th>
							</tr>
							@if (!empty($userAccessFolders))
								@foreach($userAccessFolders as $userAccessFolder)
									<tr>
										<td>{{ (!empty($userAccessFolder->companyname)) ?  $userAccessFolder->companyname : ''}} </td>
										<td>{{ !empty($userAccessFolder->first_name) && !empty($userAccessFolder->surname) ?  $userAccessFolder->first_name." ".$userAccessFolder->surname : '' }}</td>
										<td>{{ !empty($userAccessFolder->communication_date) ? date('d M Y ', $userAccessFolder->communication_date) : '' }}</td>
										<td>{{ !empty($userAccessFolder->time_sent) ? $userAccessFolder->time_sent : '' }}</td>
										<td>{{ (!empty($userAccessFolder->communication_type)) ?  $communicationStatus[$userAccessFolder->communication_type] : ''}} </td>
										<td>{{ (!empty($userAccessFolder->message)) ?  $userAccessFolder->message : ''}} </td> 
										<td>{{ (!empty($userAccessFolder->hr_firstname) && !empty($userAccessFolder->hr_surname)) ?  $userAccessFolder->hr_firstname." ".$userAccessFolder->hr_surname : ''}} </td> 
									</tr>
								@endforeach
							@endif
						</table>
						<table class="table table-striped" >
							<tr>
								<th colspan="4" style="text-align: center;">Files Access</th>
							</tr>
							<tr>
								<th>Employee</th>
								<th>File</th>
								<th>Administrator</th>
								<th>Expiry Date</th>
							</tr>
							@if (count($userAccessFiles) > 0)
								@foreach($userAccessFiles as $userAccessFile)
								   <tr>
										<td>{{ (!empty($userAccessFile->companyname)) ?  $userAccessFile->companyname : ''}} </td>
										<td>{{ !empty($userAccessFile->first_name) && !empty($userAccessFile->surname) ?  $userAccessFile->first_name." ".$userAccessFile->surname : '' }}</td>
										<td>{{ !empty($userAccessFile->communication_date) ? date('d M Y ', $userAccessFile->communication_date) : '' }}</td>
										<td>{{ !empty($userAccessFile->time_sent) ? $userAccessFile->time_sent : '' }}</td>
										<td>{{ (!empty($userAccessFile->communication_type)) ?  $communicationStatus[$userAccessFile->communication_type] : ''}} </td>
										<td>{{ (!empty($userAccessFile->message)) ?  $userAccessFile->message : ''}} </td> 
										<td>{{ (!empty($userAccessFile->hr_firstname) && !empty($userAccessFile->hr_surname)) ?  $userAccessFile->hr_firstname." ".$userAccessFile->hr_surname : ''}} </td> 
									</tr>
								@endforeach
							@endif
						</table>
					</div>
					<!-- /.box-body -->
					<div class="box-footer">
						<button type="button" id="add-task" class="btn btn-success pull-right" data-toggle="modal"
								data-target="#add-task-modal" data-meeting_id="">Add Users Access
						</button>
					</div>
				</div>
			</div>
		</div>
		@include('dms.partials.add_company_access_modal')
		@include('dms.partials.add_group_access_modal')
		@include('dms.partials.add_user_access_modal')
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

    <script type="text/javascript">

        $(function () {
            //Phone mask
            $("[data-mask]").inputmask();

            //slimScroll
            $('#company-contacts').slimScroll({
                height: '',
                railVisible: true,
                alwaysVisible: true
            });

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
			$('#time').datetimepicker({
                    format: 'HH:mm:ss'
            });
			$('#due_time').datetimepicker({
				format: 'HH:mm:ss'
			});
			$('#time_update').datetimepicker({
				format: 'HH:mm:ss'
			});
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });
            //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
			// save notes
			var noteID;			
			$('#add_notes').on('click', function() {
				var strUrl = '/contacts/company/addnotes';
				var formName = 'add-note-form';
				var modalID = 'add-new-note-modal';
				var submitBtnID = 'add_notes';
				var redirectUrl = '/contacts/company/' + 0 + '/view';
				var successMsgTitle = 'Note Saved!';
				var successMsg = 'Note Has Been Successfully Saved!';
				modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
			});
			////
            $('#edit-note-modal').on('shown.bs.modal', function (e) {
                //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                noteID = btnEdit.data('id');
                var originator_type = btnEdit.data('originator_type');
                var date = btnEdit.data('date');
                var time = btnEdit.data('time');
                var hr_person_id = btnEdit.data('hr_person_id');
                var employee_id = btnEdit.data('employee_id');
                var notes = btnEdit.data('notes');
                var next_action = btnEdit.data('next_action');
                var communication_method = btnEdit.data('communication_method');
                var rensponse_type = btnEdit.data('rensponse_type');
                var modal = $(this);
                modal.find('#originator_type_update').val(originator_type);
                modal.find('#hr_person_id_update').val(hr_person_id);
                modal.find('#employee_id_update').val(employee_id);
                modal.find('#notes_update').val(notes);
                modal.find('#next_action_update').val(next_action);
                modal.find('#communication_method_update').val(communication_method);
                modal.find('#date_update').val(date);
                modal.find('#time_update').val(time);
                modal.find('#rensponse_type_update').val(rensponse_type);
             });
			// update note
			$('#edit_note').on('click', function() {
				var strUrl = '/contacts/company/updatenotes/'+ noteID;
				var formName = 'edit-note-form';
				var modalID = 'edit-note-modal';
				var submitBtnID = 'edit_note';
				var redirectUrl = '/contacts/company/' + 1 + '/view';
				var successMsgTitle = 'Record Updated!';
				var successMsg = 'Note have been updated successfully!';
				modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
			});
			// save  task
			$('#save-task').on('click', function() {
				var strUrl = '/crm/add_task/' + 1;
				var formName = 'add-task-form';
				var modalID = 'add-task-modal';
				var submitBtnID = 'save-task';
				var redirectUrl = '/contacts/company/' + 1 + '/view';
				var successMsgTitle = 'Task Saved!';
				var successMsg = 'Task Has Been Successfully Saved!';
				modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
			});
        });
    </script>
@endsection