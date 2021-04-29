@extends('layouts.main_layout')

@section('page_dependencies')
        <!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- iCheck -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Leave Upload</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <!-- Form Start -->
                <form name="upload employee" class="form-horizontal" method="POST" action="" enctype="multipart/form-data" required>
                    {{ csrf_field() }}
					<div class="form-group{{ $errors->has('upload_type') ? ' has-error' : '' }}">
						<label for="upload_type" class="col-sm-2 control-label"> Report Type</label>
						<div class="col-sm-9">
							<label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_bal" name="upload_type" value="1" > Upload Leave Balance </label>
							<label class="radio-inline"><input type="radio" id="rdo_app" name="upload_type" value="2"> Upload Leave Applications</label>
							<label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_paid" name="upload_type" value="3" > Upload Leave Paid Out </label>
							<label class="radio-inline"><input type="radio" id="rdo_react" name="upload_type" value="4"> Upload Leave Days Reactivation</label>
						</div>
					</div>
                    <div class="box-body">
                        <div class="form-group file-upload-field {{ $errors->has('file_input') ? ' has-error' : '' }}">
                            <label for="file_input" class="col-sm-2 control-label">File input</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="file" class="form-control " id="file_input" name="input_file">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Back</button>
                        <button type="submit" id="upload-employee" class="btn btn-primary pull-right"><i class="fa fa-cloud-download"></i> Submit</button>
                    </div>
                </form>
            </div>
        </div>
        @if (session('success_add'))
        @include('contacts.partials.success_action', ['modal_title' => "Leave Transactions Completed!", 'modal_content' => session('success')])
        @endif
        @if (session('error_add'))
        @include('appraisals.partials.success_action', ['modal_title' => 'An Error Occurred!', 'modal_content' => session('error')])
        @endif
        <!-- Include add new modal -->
    </div>
@endsection

@section('page_script')
            <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- Date Picker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
	<script type="text/javascript">
	$(function () {
		//Initialize Select2 Elements
		$(".select2").select2();
		//Cancel button click event

		$('#cancel').click(function () {
			location.href = '/leave/reports';
		});
		//Initialize iCheck/iRadio Elements
		$('input').iCheck({
			checkboxClass: 'icheckbox_square-blue',
			radioClass: 'iradio_square-blue',
			increaseArea: '20%' // optional
		});
			hideFields();

		//show/hide fields on radio button toggles (depending on registration type)

		$('#rdo_app, #rdo_bal, #rdo_react, #rdo_paid').on('ifChecked', function(){
			var allType = hideFields();
			if (allType == 1) $('#box-subtitle').html('Leave Balance');
			else if (allType == 2) $('#box-subtitle').html('Leave Applications');
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
		$(window).on('resize', function () {
			$('.modal:visible').each(reposition);
		});

		//Show success action modal
		$('#success-action-modal').modal('show');
	});      
    
	//function to hide/show fields depending on the allocation  type
	function hideFields() {
		var allType = $("input[name='upload_type']:checked").val();
		if (allType == 1) { //adjsut leave
			 $('form[name="upload employee"]').attr('action', '/leave/leave_upload');
			 $('#gen-report').val("Submit");        
		}
		else if (allType == 2) { //reset leave
			 $('form[name="upload employee"]').attr('action', '/leave/upload/app');
			 $('#gen-report').val("Submit"); 
		}
		else if (allType == 3) {
			 $('form[name="upload employee"]').attr('action', '/leave/upload/paid');
			 $('#gen-report').val("Submit"); 
		}
		else if (allType == 4) { //resert leave
			 $('form[name="upload employee"]').attr('action', '/leave/upload/reactivation');
			 $('#gen-report').val("Submit");
		}
		return allType;      
		}
    </script>
@endsection