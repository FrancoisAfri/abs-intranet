@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-8 col-md-offset-2">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">New Public Registration</h3>
                    <p>Enter Public Registration details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/add_public_registration" enctype="multipart/form-data">
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
						<div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                            <label for="appliction_type" class="col-sm-3 control-label">Appliction Type</label>
                            <div class="col-sm-9">
                                <div class="input-group">
								<div class="input-group-addon">
                              <i class="fa fa-user"></i>
                            </div>
                                    <select class="form-control" name="type" id="type" placeholder="Select Appliction Type"  onchange="changeApplicant(this.value)"  required>
									<option value="1"{{ (1 == old('type')) ? ' selected' : '' }}>Short term</option>
									<option value="2"{{ (2 == old('type')) ? ' selected' : '' }}>Long term</option>
								  </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('names') ? ' has-error' : '' }}">
                            <label for="names" class="col-sm-3 control-label">Name & Surname</label>
                            <div class="col-sm-9">
                                <div class="input-group">
								<div class="input-group-addon">
                              <i class="fa fa-user"></i>
                            </div>
                                    <input type="text" class="form-control" id="names" name="names" value="{{ old('names') }}" placeholder="Name & Surname" >
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('id_number') ? ' has-error' : '' }}">
                            <label for="id_number" class="col-sm-3 control-label">ID Number</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="number" class="form-control" id="id_number" value="{{ old('id_number') }}" name="id_number" value="" placeholder="ID Number">
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="ethnicity" class="col-sm-3 control-label">Ethnicity</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bar-chart"></i>
                                    </div>
                                    <select name="ethnicity" class="form-control">
                                        <option value="">*** Select Ethnic Group ***</option>
                                        @foreach($ethnicities as $ethnicity)
                                            <option value="{{ $ethnicity->id }}"{{ ($ethnicity->id == old('ethnicity')) ? ' selected' : '' }}>{{ $ethnicity->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
						 <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                            <label for="gender" class="col-sm-3 control-label">Gender</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-venus-mars"></i>
                                    </div>
                                    <select name="gender" class="form-control">
                                        <option value="">*** Select gender ***</option>
                                        <option value="1"{{ (1 == old('gender')) ? ' selected' : '' }} >Male</option>
                                        <option value="0"{{ (2 == old('gender')) ? ' selected' : '' }}>Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('cell_number') ? ' has-error' : '' }}">
                            <label for="cell_number" class="col-sm-3 control-label">Cell Number</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="tel" class="form-control" id="cell_number" name="cell_number" value="" data-inputmask='"mask": "(999) 999-9999"' placeholder="Cell Number"  value="{{ old('cell_number') }}" data-mask>
                                </div>
                            </div>
                        </div>
						<div class="form-group shortterm{{ $errors->has('activity_id') ? ' has-error' : '' }}">
                            <label for="activity_id" class="col-sm-3 control-label">Activity/Project</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bar-chart"></i>
                                    </div>
                                    <select name="activity_id" class="form-control">
                                        <option value="">*** Select Activity/Project ***</option>
                                        @foreach($activities as $activity)
                                            <option value="{{ $activity->id }}">{{ $activity->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
						<div class="form-group longterm{{ $errors->has('phys_address') ? ' has-error' : '' }}">
                            <label for="phys_address" class="col-sm-3 control-label">Residential Address</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <textarea name="phys_address" class="form-control" value="{{ old('phys_address') }}" placeholder="Residential Address"></textarea>
                                </div>
                            </div>
                        </div>
						<div class="form-group longterm {{ $errors->has('postal_address') ? ' has-error' : '' }}">
                            <label for="postal_address" class="col-sm-3 control-label">Postal Address</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <textarea name="postal_address" value="{{ old('postal_address') }}" class="form-control" placeholder="Postal Address"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group longterm {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-sm-3 control-label">Email Address</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <input type="email" class="form-control" id="email" value="{{ old('email') }}" name="email" value="" placeholder="Email Address">
                                </div>
                            </div>
                        </div>
						<div class="form-group longterm{{ $errors->has('highest_qualification') ? ' has-error' : '' }}">
                            <label for="highest_qualification" class="col-sm-3 control-label">Highest qualification</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                 <input type="text" class="form-control" id="highest_qualification" value="{{ old('highest_qualification') }}" name="highest_qualification" placeholder="Highest qualification">
                                </div>
                            </div>
                        </div>
						<div class="form-group longterm{{ $errors->has('previous_computer_exp') ? ' has-error' : '' }}">
                            <label for="previous_computer_exp" class="col-sm-3 control-label">Previous computer experience</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <input type="text" class="form-control" id="previous_computer_exp" name="previous_computer_exp" value="{{ old('previous_computer_exp') }}" placeholder="Previous computer experience">
                                </div>
                            </div>
                        </div>
						<div class="form-group longterm{{ $errors->has('programme_discovery') ? ' has-error' : '' }}">
                            <label for="programme_discovery" class="col-sm-3 control-label">How did you know about this programme?</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <input type="text" class="form-control" id="programme_discovery" name="programme_discovery" value="" placeholder="How did you know about this programme?" value="{{ old('programme_discovery') }}">
                                </div>
                            </div>
                        </div>
						
                        <div class="form-group longterm{{ $errors->has('completed_certificates') ? ' has-error' : '' }}">
                            <label for="completed_certificates" class="col-sm-3 control-label">Completed Certificates</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <input type="text" class="form-control" id="completed_certificates" name="completed_certificates" value="{{ old('completed_certificates') }}" placeholder="Completed Certificates">
                                </div>
                            </div>
                        </div>
						 <div class="form-group longterm{{ $errors->has('date') ? ' has-error' : '' }}">
                            <label for="date" class="col-sm-3 control-label">Date</label>

                            <div class="col-sm-9">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="date" placeholder="  dd/mm/yyyy"  value="{{ old('date') }}">
                                </div>
                            </div>
                        </div>
						<!-- <div class="form-group longterm">
                            <label for="attendance_doc" class="col-sm-3 control-label">Attendance</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="file" id="attendance_doc" name="attendance_doc" class="file file-loading" data-allowed-file-extensions='["pdf", "docx"]' data-show-upload="false">
                                </div>
                            </div>
                        </div>-->
						<div class="form-group longterm{{ $errors->has('results') ? ' has-error' : '' }}">
                            <label for="results" class="col-sm-3 control-label">Results</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                       <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="results" name="results"  value="{{ old('results') }}" placeholder="Results">
                                </div>
                            </div>
                        </div>
						<div class="form-group longterm{{ $errors->has('registration_fee') ? ' has-error' : '' }}">
                            <label for="registration_fee" class="col-sm-3 control-label">Registration fee</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="registration_fee" name="registration_fee"  value="{{ old('registration_fee') }}"  placeholder="Registration fee">
                                </div>
                            </div>
                        </div>
						<div class="form-group longterm{{ $errors->has('employment_status') ? ' has-error' : '' }}">
                            <label for="employment_status" class="col-sm-3 control-label">Employment status</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="employment_status" name="employment_status"  value="{{ old('employment_status') }}" placeholder="Employment status">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button id="cancel" class="btn btn-default"><i class="fa fa-arrow-left"></i> Cancel</button>
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-user-plus"></i> Add</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
    </div>
    @endsection

    @section('page_script')
            <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

	<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <script type="text/javascript">
        //Cancel button click event
        document.getElementById("cancel").onclick = function () {
            location.href = "/contacts";
        };
		$(function () {
		//Date picker
		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy',
			endDate: '-1d',
			autoclose: true
            });
			$('.longterm').hide();
            var applicationType = $('#type').val();
            changeApplicant(applicationType);
        });
		function changeApplicant(type)
		{
			if (type == 1)
			{
				$('.longterm').hide();
				$('.shortterm').show();
			}
			else
			{
				$('.shortterm').hide();
				$('.longterm').show();
			}
				
		}
        //Phone mask
        $("[data-mask]").inputmask();
    </script>
@endsection