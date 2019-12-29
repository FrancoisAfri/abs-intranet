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
                    <h3 class="box-title">Master Folder(s)</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
					<div style="overflow-X:auto;">
						<table class="table table-bordered">
							<tr>
								<th style="width: 5px; text-align: center;">#</th>
								<th>Name</th>
								<th>Responsible Person</th>
								<th>Visibility</th>
								<th>Division</th>
							</tr>
							@if (count($folders) > 0)
								@foreach ($folders as $folder)
									<tr>
										<td style="text-align: center"><a href="{{ '/dms/folder/view/' . $folder->id}}" class="product-title"><img src="{{ !empty($folder_image) ? $folder_image : '' }}" class="img-circle"
												 alt="Doc Image"
												 style="width: 35px; height: 35px; border-radius: 50%; margin-right: 10px; margin-top: -2px;"></a></td>
										<td>{{ (!empty( $folder->folder_name)) ?  $folder->folder_name : ''}} </td>
										<td>{{ (!empty($folder->employee->first_name)) ?  $folder->employee->first_name." ".$folder->employee->surname : ''}} </td>
										<td>{{ (!empty($folder->visibility)) && $folder->visibility == 1 ?  'Private' : 'All Employees'}} </td>
										<td>{{ (!empty($folder->division->name)) ?  $folder->division->name : ''}} </td>
									</tr>
								@endforeach
							@else
								<tr id="categories-list">
									<td colspan="6">
										<div class="alert alert-danger alert-dismissable">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">
												&times;
											</button>
											No folder to display, please start by adding a new folder ...
										</div>
									</td>
								</tr>
							@endif
						</table>
					</div>
                    <!--   </div> -->
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal"
                                data-target="#add-folder-modal">Add New Folder
                        </button>
                    </div>
                </div>
            </div>
			@include('dms.partials.add_folder_modal')
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
		$('#add_folder').on('click', function () {
			var strUrl = '/dms/add_folder';
			var formName = 'add-folder-form';
			var modalID = 'add-folder-modal';
			var submitBtnID = 'add_folder';
			var redirectUrl = '/dms/folders';
			var successMsgTitle = 'New Record Added!';
			var successMsg = 'New Folder has been Added successfully.';
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
	</script>
@endsection