@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
	<!-- iCheck -->
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
@endsection
@section('content')
    <div class="row">
		<div class="col-md-12">
			<div class="box box-warning">
				<div class="box-header with-border">
					<h3 class="box-title"> Upload Image(s)</h3>
				</div>
			<div class="box-body">	
				<!-- Search Image Form -->
				<div class="col-md-12">
					<!-- Horizontal Form -->
					<div class="box box-primary">
						<div class="box-header with-border">
							<i class="fa fa-search pull-right"></i>
							<h3 class="box-title">Fill In The Form:</h3>
						</div>
						<!-- /.box-header -->
						<!-- form start -->
						<form class="form-horizontal" method="POST" action="/vehicle_management/add_images/{{$maintenance->id}}" enctype="multipart/form-data">
							<input type="hidden" name="file_index" id="file_index" value="1"/>
							<input type="hidden" name="total_files" id="total_files" value="1"/>
							{{ csrf_field() }}
							<div class="box-body">
								@if (count($errors) > 0)
									<div class="alert alert-danger alert-dismissible fade in">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
										<h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
										<ul>
											@foreach ($errors->all() as $error)
												<li>{{ $error }}</li>
											@endforeach
										</ul>
									</div>
								@endif
								<div class="form-group {{ $errors->has('machine_hour_metre') ? ' has-error' : '' }}">
									<label for="image" class="col-sm-3 control-label">Upload</label>
									<div class="col-sm-8">
										<input type="file" id="image" name="images[]" multiple class="file file-loading"
											   data-allowed-file-extensions='["jpg", "jpeg", "png"]' data-show-upload="false">
									</div>
								</div>
								<div class="form-group {{ $errors->has('machine_hour_metre') ? ' has-error' : '' }}">
									<label for="days" class="col-sm-3 control-label">Note</label>
									<div class="col-sm-8">

										<textarea class="form-control" id="note"
												  placeholder="For multiple  pictures upload Click ctrl A to select all pictures in the directory. Or choose the one you want to upload ..."
												  rows="3" readonly="">{{ old('note') }}</textarea>
									</div>
								</div>
							</div>
							<!-- /.box-body -->
							<div class="box-footer">
								<button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
								<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-paper-plane-o"></i> Submit</button>
							</div>
							<!-- /.box-footer -->
						</form>
						<!-- End Form-->
					</div>
				</div>
				<!-- /.box -->
			</div>
        <!-- /.col-md-12 -->
		</div>
		</div>
    </div>
@endsection

@section('page_script')
    <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
			type="text/javascript"></script>
	<!-- the main fileinput plugin file -->
	<script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
	<!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
	<script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
	<script src="/custom_components/js/modal_ajax_submit.js"></script>
	<!-- iCheck -->
	<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
	<script type="text/javascript">
		$('#back_button').click(function () {
			location.href = '/vehicle_management/viewImage/{{$maintenance->id}}';
		});
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
			// call hide agent_field
			$('.agent_field').hide();
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green',
				increaseArea: '20%' // optional
			});
        });

    </script>
@endsection