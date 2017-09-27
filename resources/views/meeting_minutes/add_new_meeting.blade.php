@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
	<!-- iCheck -->
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/green.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="row">
        <!-- New User Form -->
        <div class="col-md-8 col-md-offset-2">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">New Meeting</h3>
                    <p>Enter Meeting details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST"  action="/meeting_minutes/add_meeting">
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
						<div class="form-group{{ $errors->has('meeting_type') ? ' has-error' : '' }}">
                                <label for="meeting_type" class="col-sm-2 control-label">Type</label>

                                <div class="col-sm-10">
                                    <label class="radio-inline"><input type="radio" id="rdo_new" name="meeting_type" value="1" checked> New Meeting</label>
                                    <label class="radio-inline"><input type="radio" id="rdo_recurring" name="meeting_type" value="2"> Recurring Meeting</label>
                                </div>
                        </div>
						<div class="form-group recurring-field {{ $errors->has('recurring_id') ? ' has-error' : '' }}">
							<label for="recurring_id" class="col-sm-2 control-label">Recurring Meeting</label>
							<div class="col-sm-10">
								<select id="recurring_id" name="recurring_id" class="form-control select2" style="width: 100%;">
									<option value="0">*** Select a Meeting ***</option>
									@foreach($questions as $question)
										<option value="{{ $question->id }}">{{ $question->meeting_title }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group new-field {{ $errors->has('company_id') ? ' has-error' : '' }}">
							<label for="company_id" class="col-sm-2 control-label">Client</label>
							<div class="col-sm-10">
								<select id="company_id" name="company_id" class="form-control select2" style="width: 100%;">
									<option value="">*** Select a Client Company ***</option>
									@foreach($companies as $company)
										<option value="{{ $company->id }}">{{ $company->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
                        <div class="form-group new-field {{ $errors->has('meeting_name') ? ' has-error' : '' }}">
							<label for="Meeting Name" class="col-sm-2 control-label">Title</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="meeting_name" name="meeting_name" value="{{ old('meeting_name') }}" placeholder="Enter Name">
							</div>
						</div>
                        <div class="form-group new-field {{ $errors->has('meeting_date') ? ' has-error' : '' }}">
                        <label for="Meeting Date" class="col-sm-2 control-label">Date</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control datepicker" name="meeting_date" placeholder="  dd/mm/yyyy" value="{{ old('meeting_date') }}">
                        </div>
						</div>
						<div class="form-group new-field {{ $errors->has('meeting_location') ? ' has-error' : '' }}">
							<label for="Meeting Location" class="col-sm-2 control-label">Location</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="meeting_location" name="meeting_location" value="{{ old('meeting_location') }}" placeholder="Enter Location">
							</div>
						</div>
						<div class="form-group new-field {{ $errors->has('meeting_agenda') ? ' has-error' : '' }}">
							<label for="Meeting Agenda" class="col-sm-2 control-label">Agenda</label>
							<div class="col-sm-10">
								<textarea rows="4" cols="50" class="form-control" id="meeting_agenda" name="meeting_agenda" placeholder="Enter Agenda">{{ old('meeting_agenda') }}</textarea>
							</div>
						</div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-user-plus"></i> Submit</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new Form-->
</div>
@endsection
<!-- Ajax form submit -->

@section('page_script')
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<!-- Select2 -->
<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
 <!-- bootstrap datepicker -->
<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- iCheck -->
<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
<script>
    $(function () {
		//Initialize Select2 Elements
            $(".select2").select2();
		//Date picker
		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy',
			autoclose: true,
			todayHighlight: true
		});
		 //Initialize iCheck/iRadio Elements
		$('input').iCheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green',
			increaseArea: '20%' // optional
		});
		//call hide/show fields functions
        hideFields();
		$('#rdo_recurring, #rdo_new').on('ifChecked', function(){
           hideFields();
       });
    });
	//function to hide/show fields depending on the registration type
	function hideFields() {
		var meetingType = $("input[name='meeting_type']:checked").val();
		if (meetingType == 1) { //New Meeting
			$('.recurring-field').hide();
			$('.new-field ').show();
		}
		else if (meetingType == 2) { //Recurring Meeting
			$('.new-field ').hide();
			$('.recurring-field').show();
		}
	}
</script>
@endsection