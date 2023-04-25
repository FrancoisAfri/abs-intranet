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
        <div class="col-ms-9">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{$folder->folder_name}} Folder(s) </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
					<div style="max-height: 400px; overflow-y: scroll;">
						<table class="table table-striped table-bordered">
							<tr>
								<td class="caption"><b>Name</b></td>
								<td>{{ (!empty($file->document_name)) ?  $file->document_name : ''}}</td>
								<td class="caption"><b>Responsible Person</b></td>
								<td>{{ (!empty($file->employee->first_name)) ?  $file->employee->first_name." ".$file->employee->surname : ''}}</td>
							</tr>
							<tr>
								<td class="caption"><b>Visibility</b></td>
								<td>{{ (!empty($file->visibility)) && $file->visibility == 1 ?  'Private' : 'All Employees'}}</td>
								<td class="caption"><b>Description</b></td>
								<td>{{ (!empty($file->description)) ?  $file->description : ''}}</td>
							</tr>
							<tr>
								<td class="caption"><b>Status</b></td>
								<td>{{ (!empty($file->status)) && $file->status == 1 ?  'Active' : 'Inactive'}}</td>
								<td class="caption"><b>Division</b></td>
								<td>{{ (!empty($folder->division->name)) ?  $folder->division->name : ''}}</td>
							</tr>
							<tr>
								<td class="caption"><b>Department</b></td>
								<td>{{ (!empty($folder->department->name)) ?  $folder->department->name : ''}}</td>
								<td class="caption"><b>Section</b></td>
								<td>{{ (!empty($folder->section->name)) ?  $folder->section->name : ''}}</td>
							</tr>
							<tr>
								<td class="caption"><b>Version</b></td>
								<td>{{ (!empty($file->current_version)) ?  $file->current_version : ''}} MB</td>
								<td class="caption"><b>Deleted</b></td>
								<td>{{ (!empty($file->deleted)) ?  'Yes' : 'No'}}</td>
							</tr>
                        </table>
					</div>
                    <!--   </div> -->
                    <!-- /.box-body -->
                    <div class="box-footer">
						 <button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Back</button>
                        <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal"
                                data-target="#edit-file-modal"data-id="{{ $file->id }}"
                            data-document_name="{{ $file->document_name }}"
                            data-visibility="{{ $file->visibility }}"
                            data-description="{{ $file->description }}"
                            data-current_version="{{ $file->current_version}}">Edit Details
                        </button>
						&nbsp; 
						<!-- <button type="button" id="cat_module" class="btn btn-primary pull-left" data-toggle="modal"
                                onclick="postData({{$folder->id}}, 'group_access');">Group Access
                        </button>&nbsp;  
						<button type="button" id="cat_module" class="btn btn-primary pull-left" data-toggle="modal"
                                onclick="postData({{$folder->id}}, 'company_access');">Company Access
                        </button>&nbsp; 
						<button type="button" id="cat_module" class="btn btn-primary pull-left" data-toggle="modal"
                                onclick="postData({{$folder->id}}, 'user_access');">User Access
                        </button>&nbsp; -->
						@if (empty($file->deleted)) 
							<button type="button" id="cat_module" class="btn btn-danger pull-right" data-toggle="modal"
									data-target="#delete-folder-warning-modal">Delete
							</button>
						@endif
                    </div>
                </div>
            </div>
			@include('dms.partials.edit_file_modal')
			@if (empty($file->deleted))
                @include('dms.warnings.delete_file_action', ['modal_title' => 'Delete File', 'modal_content' => 'Are you sure you want to delete this file ? The file will be moved to the recycle bin.'])
            @endif
        </div>
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
			if (data == 'group_access') location.href = "/dms/folder/group_access/" + id;
			else if (data == 'company_access') location.href = "/dms/folder/company_access/" + id;
			else if (data == 'user_access') location.href = "/dms/folder/user_access/" + id;
		}
		$(function () {
			//Cancel button click event
			document.getElementById("back_button").onclick = function () {
				location.href = "/dms/folder/view/{{$file->folder_id}}" 
			};
			//Tooltip
			$('[data-toggle="tooltip"]').tooltip();
			
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
			 //Show success action modal
			@if(Session('changes_saved'))
			$('#success-action-modal').modal('show');
			@endif
			//Post perk form to server using ajax (add)
			
			var folderID;
            $('#edit-file-modal').on('show.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                if (parseInt(btnEdit.data('id')) > 0) {
                    fileID = btnEdit.data('id');
                }
                var visibility = btnEdit.data('visibility');
                var document_name = btnEdit.data('document_name');
                var description = btnEdit.data('description');
                var current_version = btnEdit.data('current_version');
				console.log(description);
                var modal = $(this);
                modal.find('#document_name').val(document_name);
                modal.find('#description').val(description);
                modal.find('#current_version').val(current_version);
                modal.find('#visibility').val(visibility);
            });

            $('#edit_file').on('click', function () {
                var strUrl = '/dms/edit_file_details/' + fileID;
                var formName = 'edit-file-form';
                var modalID = 'edit-file-modal';
                var submitBtnID = 'edit_folder';
                var redirectUrl = '/dms/file_management/{{ $file->id }}';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'File details has been updated successfully.';
                var Method = 'PATCH';
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
			loadAllDivs = 1;
        @endforeach
		});
	</script>
@endsection