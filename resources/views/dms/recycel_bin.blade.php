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
                    <h3 class="box-title">Recycle Bin Folder(s) </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
					<div style="max-height: 400px; overflow-y: scroll;">
						<table class="table table-bordered">
							<tr>
								<th>Name</th>
								<th>Responsible Person</th>
								<th>Visibility</th>
								<th>Division</th>
								<th>Size</th>
								<th style="width: 5px; text-align: center;">#</th>
								<th style="width: 5px; text-align: center;">#</th>
							</tr>
							@if (count($folders) > 0)
								@foreach ($folders as $folderView)
									<tr>
										<td>{{ (!empty($folderView->folder_name)) ?  $folderView->folder_name : ''}} </td>
										<td>{{ (!empty($folderView->employee->first_name)) ?  $folderView->employee->first_name." ".$folderView->employee->surname : ''}} </td>
										<td>{{ (!empty($folderView->visibility)) && $folderView->visibility == 1 ?  'Private' : 'All Employees'}} </td>
										<td>{{ (!empty($folderView->division->name)) ?  $folderView->division->name : ''}} </td>
										<td>{{ (!empty($folderView->total_size)) ?  $folderView->total_size : ''}} </td>
										<td style="text-align: center"><button type="button" id="restore_folder" class="btn btn-danger" 
												onclick="postData({{$folderView->id}}, 'restore_folder');"><i class="fa fa-check"></i> Restore Folder</button></td>
										<td style="text-align: center"><button type="button" id="delete_folder" class="btn btn-danger" 
												onclick="postData({{$folderView->id}}, 'delete_folder');"><i class="fa fa-check"></i> Delete Folder</button></td>
									</tr>
								@endforeach
							@else
								<tr>
									<td colspan="7">
										<div class="alert alert-danger alert-dismissable">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">
												&times;
											</button>
											No folder to display ...
										</div>
									</td>
								</tr>
							@endif
						</table>
					</div>
                </div>
            </div>
        </div>
		<div class="col-ms-9">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Recycle Bin File(s) </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
					<div style="max-height: 400px; overflow-y: scroll;">
						<table class="table table-bordered">
							<tr>
								<th>Name</th>
								<th>Responsible Person</th>
								<th>Current Version</th>
								<th>Date Uploaded</th>
								<th>Visibility</th>
								<th style="width: 5px; text-align: center;">#</th>
								<th style="width: 5px; text-align: center;">#</th>
								
							</tr>
							@if (count($files) > 0)
								@foreach ($files as $file)
									<tr>
										<td>{{ (!empty($file->document_name)) ?  $file->document_name : ''}} </td>
										<td>{{ (!empty($file->employee->first_name)) ?  $file->employee->first_name." ".$file->employee->surname : ''}} </td>
										<td>{{ (!empty($file->current_version)) ?  $file->current_version : ''}} </td>
										<td>{{ (!empty($file->created_at)) ?  $file->created_at : ''}} </td>
										<td>{{ (!empty($file->visibility)) && $file->visibility == 1 ?  'Private' : 'All Employees'}} </td>
										<td style="text-align: center"><button type="button" id="restore_file" class="btn btn-danger" 
												onclick="postData({{$file->id}}, 'restore_file');"><i class="fa fa-check"></i> Restore File</button></td>
										<td style="text-align: center"><button type="button" id="delete_file" class="btn btn-danger" 
												onclick="postData({{$file->id}}, 'delete_file');"><i class="fa fa-check"></i> Delete File</button></td>
									</tr>
								@endforeach
							@else
								<tr>
									<td colspan="7">
										<div class="alert alert-danger alert-dismissable">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">
												&times;
											</button>
												No file to display ...
										</div>
									</td>
								</tr>
							@endif
						</table>
					</div>
                    <!--   </div> -->
                    <!-- /.box-body -->
                </div>
            </div>
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
			if (data == 'restore_file') location.href = "/dms/file_restore/" + id;
			else if (data == 'restore_folder') location.href = "/dms/folder_restore/" + id;
			else if (data == 'delete_folder') location.href = "/dms/folder_delete/" + id;
			else if (data == 'delete_file') location.href = "/dms/file_delete/" + id;
		}
		$(function () {
			//Tooltip

			$('[data-toggle="tooltip"]').tooltip();

		});
	</script>
@endsection