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
		<div class="col-ms-9">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{$folder->folder_name}} File(s) </h3>
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
								<th style="width: 5px; text-align: center;">#</th>
								<th>Name</th>
								<th>Responsible Person</th>
								<th>Current Version</th>
								<th>Date Uploaded</th>
								<th style="width: 5px; text-align: center;">#</th>
							</tr>
							@if (count($files) > 0)
								@foreach ($files as $file)
									<tr>
										<td style="text-align: center">
											<div class="form-group{{ $errors->has('document') ? ' has-error' : '' }}">
												<label for="document" class="control-label"></label>
												@if(!empty($file->file_name))
													<a class="btn btn-default btn-flat btn-block pull-right btn-xs"
													   href="{{ Storage::disk('local')->url("$file->path$file->file_name") }}"
													   target="_blank"><img src="{{ Storage::disk('local')->url("DMS Image/$file->file_extension.gif") }}" class="img-circle"
												 alt="Doc Image"
												 style="width: 35px; height: 35px; border-radius: 50%; margin-right: 10px; margin-top: -2px;"></a>
												@endif
											</div>
										</td>
										<td>{{ (!empty($file->document_name)) ?  $file->document_name : ''}} </td>
										<td>{{ (!empty($file->employee->first_name)) ?  $file->employee->first_name." ".$file->employee->surname : ''}} </td>
										<td>{{ (!empty($file->current_version)) ?  $file->current_version : ''}} </td>
										<td>{{ (!empty($file->created_at)) ?  $file->created_at : ''}} </td>
									</tr>
								@endforeach
							@else
								<tr>
									<td colspan="5">
										<div class="alert alert-danger alert-dismissable">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">
												&times;
											</button>
											There is no file in this directory, please contact administrator ...
										</div>
									</td>
								</tr>
							@endif
						</table>
					</div>
                    <div class="box-footer">
					<button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                    </div>
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

		$(function () {
			//Tooltip
			$('[data-toggle="tooltip"]').tooltip();
			//Cancel button click event
			document.getElementById("back_button").onclick = function () {
				location.href = "/dms/my_folders" 
			};
			$('[data-toggle="tooltip"]').tooltip();
		});
	</script>
@endsection