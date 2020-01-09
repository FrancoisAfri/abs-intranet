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
                                    <td>{{ (!empty($folder->folder_name)) ?  $folder->folder_name : ''}}</td>
                                    <td class="caption"><b>Responsible Person</b></td>
                                    <td>{{ (!empty($folder->employee->first_name)) ?  $folder->employee->first_name." ".$folder->employee->surname : ''}}</td>
                                </tr>
								<tr>
                                    <td class="caption"><b>Visibility</b></td>
                                    <td>{{ (!empty($folder->visibility)) && $folder->visibility == 1 ?  'Private' : 'All Employees'}}</td>
                                    <td class="caption"><b></b></td>
                                    <td></td>
                                </tr>
								<tr>
                                    <td class="caption">Status</td>
                                    <td>{{ (!empty($folder->status)) && $folder->status == 1 ?  'Active' : 'Inactive'}}</td>
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
                                    <td class="caption"><b>Max Size</b></td>
                                    <td>{{ (!empty($folder->size)) ?  $folder->size : ''}} MB</td>
                                    <td class="caption"><b>Current Size</b></td>
                                    <td>{{ (!empty($folder->total_size)) ?  $folder->total_size : ''}}</td>
                                </tr>
                        </table>
					</div>
                    <!--   </div> -->
                    <!-- /.box-body -->
                    <div class="box-footer">
						 <button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Back</button>
                        <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal"
                                data-target="#edit-folder-modal"data-id="{{ $folder->id }}"
                            data-division_level_5="{{$folder->division_5 }}"
                            data-division_level_4="{{ $folder->division_4 }}"
                            data-division_level_3="{{ $folder->division_3 }}"
                            data-division_level_2="{{ $folder->division_2 }}"
                            data-division_level_1="{{ $folder->division_1 }}"
                            data-size="{{ $folder->size }}"
                            data-visibility="{{ $folder->visibility }}"
                            data-folder_name="{{ $folder->folder_name }}"
                            data-responsable_person="{{ $folder->responsable_person}}">Edit Details
                        </button>
						&nbsp; 
						<button type="button" id="cat_module" class="btn btn-primary pull-left" data-toggle="modal"
                                onclick="postData({{$folder->id}}, 'group_access');">Group Access
                        </button>&nbsp;  
						<button type="button" id="cat_module" class="btn btn-primary pull-left" data-toggle="modal"
                                onclick="postData({{$folder->id}}, 'company_access');">Company Access
                        </button>&nbsp; 
						<button type="button" id="cat_module" class="btn btn-primary pull-left" data-toggle="modal"
                                onclick="postData({{$folder->id}}, 'user_access');">User Access
                        </button>&nbsp;
						<button type="button" id="cat_module" class="btn btn-danger pull-right" data-toggle="modal"
                                data-target="#delete-folder-warning-modal">Delete
                        </button>
                    </div>
                </div>
            </div>
			@include('dms.partials.edit_folder_modal')
			@if (!empty($folder))
                @include('dms.warnings.delete_folder_action', ['modal_title' => 'Delete Folder', 'modal_content' => 'Are you sure you want to delete this folder ? This action cannot be undone.'])
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
				location.href = "/dms/folder/view/{{$folder->parent_id}}" 
			};
			//Tooltip
			$('[data-toggle="tooltip"]').tooltip();
			//Cancel button click event
			document.getElementById("back_button").onclick = function () {
				if ("{{$folder->parent_id}}" === "")
					location.href = "/dms/folders";
				else if ("{{$folder->parent_id}}" !== "") location.href = "/dms/folder/view/{{$folder->parent_id}}" 
			};
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
            $('#edit-folder-modal').on('show.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                if (parseInt(btnEdit.data('id')) > 0) {
                    folderID = btnEdit.data('id');
                }
                var division_level_5 = btnEdit.data('division_level_5');
				//console.log('gets here: ' + division_level_5);
                var division_level_4 = btnEdit.data('division_level_4');
                var division_level_3 = btnEdit.data('division_level_3');
                var division_level_2 = btnEdit.data('division_level_2');
                var division_level_1 = btnEdit.data('division_level_1');
                var visibility = btnEdit.data('visibility');
                var responsable_person = btnEdit.data('responsable_person');
                var folder_name = btnEdit.data('folder_name');
                var size = btnEdit.data('size');

                var modal = $(this);
                modal.find('#status').val(status);
                modal.find('#division_level_5').val(division_level_5);
                modal.find('#division_level_4').val(division_level_4);
                modal.find('#division_level_3').val(division_level_3);
                modal.find('#division_level_2').val(division_level_2);
                modal.find('#division_level_1').val(division_level_1);
                modal.find('#responsable_person').val(responsable_person);
                modal.find('#visibility').val(visibility);
                modal.find('#folder_name').val(folder_name);
                modal.find('#size').val(size);
            });

            $('#edit_folder').on('click', function () {
                var strUrl = '/dms/edit_folder_details/' + folderID;
                var formName = 'edit-folder-form';
                var modalID = 'edit-folder-modal';
                var submitBtnID = 'edit_folder';
                var redirectUrl = '/dms/folder_management/{{ $folder->id }}';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The Folder details has been updated successfully.';
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