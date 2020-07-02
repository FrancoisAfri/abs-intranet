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
                    <h3 class="box-title"> <b>Company Folder(s)</b></h3>
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
								<th colspan="5" style="text-align: center;">Folders</th>
							</tr>
							<tr>
								<th></th>
								<th>Folder</th>
								<th>Division</th>
								<th>Administrator</th>
								<th>Expiry Date</th>
							</tr>
							@if (count($companyAccessFolders) > 0)
								@foreach($companyAccessFolders as $companyAccessFolder)
								   <tr>
										<td style="text-align: center"><a href="{{ '/dms/folder/view/' . $companyAccessFolder->id}}" class="product-title"><img src="{{ !empty($folder_image) ? $folder_image : '' }}" class="img-circle"
												 alt="Doc Image"
												 style="width: 35px; height: 35px; border-radius: 50%; margin-right: 10px; margin-top: -2px;"></a></td>
										<td>{{ (!empty($companyAccessFolder->companyFolder->folder_name)) ?  $companyAccessFolder->companyFolder->folder_name : ''}} </td>
										<td>{{ (!empty($companyAccessFolder->division->name)) ?  $companyAccessFolder->division->name : ''}} </td>
										<td>{{ !empty($companyAccessFolder->companyAdmin->first_name) && !empty($companyAccessFolder->companyAdmin->surname) ?  $companyAccessFolder->companyAdmin->first_name." ".$companyAccessFolder->companyAdmin->surname : '' }}</td>
										<td>{{ !empty($companyAccessFolder->expiry_date) ? date('d M Y ', $companyAccessFolder->expiry_date) : '' }}</td>
									</tr>
								@endforeach
							@endif
						</table>
						<table class="table table-striped" >
							<tr>
								<th colspan="5" style="text-align: center;">Files</th>
							</tr>
							<tr>
								<th></th>
								<th>File</th>
								<th>Division</th>
								<th>Administrator</th>
								<th>Expiry Date</th>
							</tr>
							@if (count($companyAccessFiles) > 0)
								@foreach($companyAccessFiles as $companyAccessFile)
									<tr>
										<td>{{ (!empty($companyAccessFile->division->name)) ?  $companyAccessFile->division->name : ''}} </td>
										<td>{{ (!empty($companyAccessFile->department->name)) ?  $companyAccessFile->department->name : ''}} </td>
										<td>{{ (!empty($companyAccessFile->section->name)) ?  $companyAccessFile->section->name : ''}} </td>
										<td>{{ (!empty($companyAccessFile->companyFile->document_name)) ?  $companyAccessFile->companyFile->document_name : ''}} </td>
										<td>{{ !empty($companyAccessFile->companyAdmin->first_name) && !empty($companyAccessFile->companyAdmin->surname) ?  $companyAccessFile->companyAdmin->first_name." ".$companyAccessFile->companyAdmin->surname : '' }}</td>
										<td>{{ !empty($companyAccessFile->expiry_date) ? date('d M Y ', $companyAccessFile->expiry_date) : '' }}</td>
										<td nowrap>
											<button type="button" id="view_users" class="btn {{ (!empty($companyAccessFile->status) && $companyAccessFile->status == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$companyAccessFile->id}}, 'actdeac');"><i class="fa {{ (!empty($companyAccessFile->status) && $companyAccessFile->status == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($companyAccessFile->status) && $companyAccessFile->status == 1) ? "Revoke Access" : ""}}</button>
										</td>
									</tr>
								@endforeach
							@endif
						</table>
					</div>
					<!-- /.box-body -->
					<div class="box-footer">
					
					</div>
				</div>
            </div>
		</div>
		<div class="col-md-12">
			<!-- Company's contacts box -->
            <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title"> <b>Group Folder(s)</b></h3>
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
								<th colspan="4" style="text-align: center;">Folders</th>
							</tr>
							<tr>
								<th></th>
								<th>Folder</th>
								<th>Administrator</th>
								<th>Expiry Date</th>
							</tr>
							@if (count($groupAccessFolders) > 0)
								@foreach($groupAccessFolders as $groupAccessFolder)
								   <tr>
										<td style="text-align: center"><a href="{{ '/dms/folder/view/' . $groupAccessFolder->id}}" class="product-title"><img src="{{ !empty($folder_image) ? $folder_image : '' }}" class="img-circle"
												 alt="Doc Image"
												 style="width: 35px; height: 35px; border-radius: 50%; margin-right: 10px; margin-top: -2px;"></a></td>
										<td>{{ (!empty($groupAccessFolder->groupFolder->folder_name)) ?  $groupAccessFolder->groupFolder->folder_name : ''}} </td>
										<td>{{ !empty($groupAccessFolder->groupAdmin->first_name) && !empty($groupAccessFolder->groupAdmin->surname) ? $groupAccessFolder->groupAdmin->first_name." ".$groupAccessFolder->groupAdmin->surname : '' }}</td>
										<td>{{ !empty($groupAccessFolder->expiry_date) ? date('d M Y ', $groupAccessFolder->expiry_date) : '' }}</td>
										
									</tr>
								@endforeach
							@endif
						</table>
						<table class="table table-striped" >
							<tr>
								<th colspan="4" style="text-align: center;">Files</th>
							</tr>
							<tr>
								<th></th>
								<th>File</th>
								<th>Administrator</th>
								<th>Expiry Date</th>
							</tr>
							@if (count($groupAccessFiles) > 0)
								@foreach($groupAccessFiles as $groupAccessFile)
									<tr>
										<td>{{ (!empty($groupAccessFile->groupName->group_name)) ?  $groupAccessFile->groupName->group_name : ''}} </td>
										<td>{{ (!empty($groupAccessFile->groupFile->document_name)) ?  $groupAccessFile->groupFile->document_name : ''}} </td>
										<td>{{ !empty($groupAccessFile->groupAdmin->first_name) && !empty($groupAccessFile->groupAdmin->surname) ? $groupAccessFolder->groupAdmin->first_name." ".$groupAccessFolder->groupAdmin->surname : '' }}</td>
										<td>{{ !empty($groupAccessFile->expiry_date) ? date('d M Y ', $groupAccessFile->expiry_date) : '' }}</td>
										<td nowrap>
											<button type="button" id="view_users" class="btn {{ (!empty($groupAccessFile->status) && $groupAccessFile->status == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$groupAccessFile->id}}, 'actdeac');"><i class="fa {{ (!empty($groupAccessFile->status) && $groupAccessFile->status == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($groupAccessFile->status) && $groupAccessFile->status == 1) ? "Revoke Access" : ""}}</button>
										</td>
									</tr>
								@endforeach
							@endif
						</table>
					</div>
					<!-- /.box-body -->
					<div class="box-footer">
						
					</div>
				</div>
            </div>
        </div>
		<div class="col-md-12">
			<!-- Company's contacts box -->
            <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title"> <b>Users Folder(s)</b></h3>
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
								<th colspan="4" style="text-align: center;">Folders</th>
							</tr>
							<tr>
								<th></th>
								<th>Folder</th>
								<th>Administrator</th>
								<th>Expiry Date</th>
							</tr>
							@if (!empty($userAccessFolders))
								@foreach($userAccessFolders as $userAccessFolder)
									<tr>
										<td style="text-align: center"><a href="{{ '/dms/folder/view/' . $userAccessFolder->id}}" class="product-title"><img src="{{ !empty($folder_image) ? $folder_image : '' }}" class="img-circle"
												 alt="Doc Image"
												 style="width: 35px; height: 35px; border-radius: 50%; margin-right: 10px; margin-top: -2px;"></a></td>
										<td>{{ !empty($userAccessFolder->userFolder->folder_name) ? $userAccessFolder->userFolder->folder_name : '' }}</td>
										<td>{{ !empty($userAccessFolder->expiry_date) ? date('d M Y ', $userAccessFolder->expiry_date) : '' }}</td>
										<td>{{ (!empty($userAccessFolder->userAdmin->first_name) && !empty($userAccessFolder->userAdmin->surname)) ?  $userAccessFolder->userAdmin->first_name." ".$userAccessFolder->userAdmin->surname : ''}} </td>
									</tr>
								@endforeach
							@endif
						</table>
						<table class="table table-striped" >
							<tr>
								<th colspan="4" style="text-align: center;">Files</th>
							</tr>
							<tr>
								<th></th>
								<th>File</th>
								<th>Administrator</th>
								<th>Expiry Date</th>
							</tr>
							@if (count($userAccessFiles) > 0)
								@foreach($userAccessFiles as $userAccessFile)
								   <tr>
										<td>{{ !empty($userAccessFile->employee->first_name) && !empty($userAccessFile->employee->surname) ?  $userAccessFile->employee->first_name." ".$userAccessFile->employee->surname : '' }}</td>
										<td>{{ !empty($userAccessFile->userFile->document_name) ? $userAccessFile->userFile->document_name : '' }}</td>
										<td>{{ !empty($userAccessFile->expiry_date) ? date('d M Y ', $userAccessFile->expiry_date) : '' }}</td>
										<td>{{ (!empty($userAccessFile->userAdmin->first_name) && !empty($userAccessFile->userAdmin->surname)) ?  $userAccessFile->userAdmin->first_name." ".$userAccessFile->userAdmin->surname : ''}} </td>
										<td nowrap>
											<button type="button" id="view_users" class="btn {{ (!empty($userAccessFile->status) && $userAccessFile->status == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$userAccessFile->id}}, 'actdeac');"><i class="fa {{ (!empty($userAccessFile->status) && $userAccessFile->status == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($userAccessFile->status) && $userAccessFile->status == 1) ? "Revoke Access" : ""}}</button>
										</td>
									</tr>
								@endforeach
							@endif
						</table>
					</div>
					<!-- /.box-body -->
					<div class="box-footer">
						<button type="button" id="add-task" class="btn btn-success pull-right" data-toggle="modal"
								data-target="#add-user-access-modal" data-meeting_id="">Request Access
						</button>
					</div>
				</div>
			</div>
		</div>
		@include('dms.partials.request_user_access_modal')
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
		function postData(id, data)
		{
			if (data == 'actdeac')
				location.href = "/dms/group/"  + id + "/actdect";
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

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });
			$(document).ready(function () {
                $('#expiry_date').datepicker({
					format: 'dd/mm/yyyy',
					autoclose: true,
					todayHighlight: true
                });
            });
			$(document).ready(function () {
                $('#expiry_gr_date').datepicker({
					format: 'dd/mm/yyyy',
					autoclose: true,
					todayHighlight: true
                });
            });
			$(document).ready(function () {
                $('#expiry_usr_date').datepicker({
					format: 'dd/mm/yyyy',
					autoclose: true,
					todayHighlight: true
                });
            });
            //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
			hideFields();
			$('#rdo_fol_com, #rdo_fil_com, #rdo_fol_gr, #rdo_fil_gr, #rdo_fol_usr, #rdo_fil_usr').on('ifChecked', function(){
				   hideFields();
			});

			// save company access
			var noteID;			
			$('#add_company_access').on('click', function() {
				var strUrl = '/dms/company/access_save';
				var formName = 'add-company-access-form';
				var modalID = 'add-company-access-modal';
				var submitBtnID = 'add_company_access';
				var redirectUrl = '/dms/grant_access';
				var successMsgTitle = 'Access Saved!';
				var successMsg = 'Company Access Has Been Successfully Saved!';
				modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
			});
			// save group access
			$('#add_group_access').on('click', function() {
				var strUrl = '/dms/group/access_save';
				var formName = 'add-group-access-form';
				var modalID = 'add-group-access-modal';
				var submitBtnID = 'add_group_access';
				var redirectUrl = '/dms/grant_access';
				var successMsgTitle = 'Access Saved!';
				var successMsg = 'Group Access Has Been Successfully Saved!';
				modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
			});
			// save user access
			$('#add_user_access').on('click', function() {
				var strUrl = '/dms/user/access_save';
				var formName = 'add-user-access-form';
				var modalID = 'add-user-access-modal';
				var submitBtnID = 'add_user_access';
				var redirectUrl = '/dms/grant_access';
				var successMsgTitle = 'Access Saved!';
				var successMsg = 'User Access Has Been Successfully Saved!';
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
			
			//Load divisions drop down
			var parentDDID = '';
			var loadAllDivs = 1;
			@foreach($division_levels as $division_level)
			//Populate drop down on page load
			var ddID = '{{ 'division_level_' . $division_level->level }}';
			var postTo = '{!! route('divisionsdropdown') !!}';
			var selectedOption = '';
			var divLevel = parseInt('{{ $division_level->level }}');
			var incInactive = -1;
			var loadAll = loadAllDivs;
			loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo);
			parentDDID = ddID;
			loadAllDivs = -1;
			@endforeach
        });
		//function to hide/show fields depending on the registration type
		function hideFields() {
			var accessComType = $("input[name='access_com_type']:checked").val();
			if (accessComType == 1) { //folder
				$('.folder-field').show();
				$('.file-field ').hide();
			}
			else if (accessComType == 2) { //file
				$('.file-field').show();
				$('.folder-field').hide();
			}
			//dd
			var accessGrType = $("input[name='access_gr_type']:checked").val();
			if (accessGrType == 1) { //folder
				$('.folder-field').show();
				$('.file-field ').hide();
			}
			else if (accessGrType == 2) { //file
				$('.file-field').show();
				$('.folder-field').hide();
			}
			//dd
			var accessUsrType = $("input[name='access_usr_type']:checked").val();
			if (accessUsrType == 1) { //folder
				$('.folder-field').show();
				$('.file-field ').hide();
			}
			else if (accessUsrType == 2) { //file
				$('.file-field').show();
				$('.folder-field').hide();
			}
		}
    </script>
@endsection