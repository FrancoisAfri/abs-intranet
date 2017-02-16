@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
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
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">New Learner Registration</h3>
                    <p>Enter Learner details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/add_learner"  enctype="multipart/form-data">
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
                        <div class="form-group{{ $errors->has('project_id') ? ' has-error' : '' }}">
                            <label for="project_id" class="col-sm-3 control-label">Project</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bar-chart"></i>
                                    </div>
                                    <select id = "project_id" name="project_id" class="form-control">
                                        <option value="">*** Select Project ***</option>

                                        @foreach($projects as $projects)
                                            <option value="{{ $projects->id }}"{{ ($project_id != -1) ? ' selected' : '' }}>{{ $projects->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('activity_id') ? ' has-error' : '' }}">
                            <label for="project_id" class="col-sm-3 control-label">Activity</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bar-chart"></i>
                                    </div>
                                    <select  id = "activity_id" name="activity_id" class="form-control">
                                        <option value="">*** Select a project first ***</option>

                                    </select>
                                </div>
                            </div>
                        </div>

						<div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                            <label for="type" class="col-sm-3 control-label">Appliction Type</label>
                            <div class="col-sm-9">
                                <div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-user"></i>
								</div>
								<select class="form-control" name="type" id="type" placeholder="Select Appliction Type"  onchange="changeApplicant(this.value)"  required>
									<option value="1">Short term</option>
									<option value="2">Long term</option>
								</select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                            <label for="first_name" class="col-sm-3 control-label">First Name</label>
                            <div class="col-sm-9">
                                <div class="input-group">
								<div class="input-group-addon">
                              <i class="fa fa-user"></i>
                            </div>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="First Surname" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('surname') ? ' has-error' : '' }}">
                            <label for="surname" class="col-sm-3 control-label">Surname</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="surname" name="surname" value="{{ old('surname') }}" placeholder="Surname" required>
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('grade') ? ' has-error' : '' }} ">
								<label for="module_registered" class="col-sm-3 control-label">Grade</label>
								<div class="col-sm-9">
									<div class="input-group">
										<div class="input-group-addon">
										   <i class="fa fa-book"></i>
										</div>
										<input type="number" class="form-control" id="grade" name="grade" value="{{ old('grade') }}" placeholder="Grade">
									</div>
								</div>
							</div>
						<div class="form-group{{ $errors->has('id_number') ? ' has-error' : '' }} longterm">
                            <label for="id_number" class="col-sm-3 control-label">ID Number</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="number" class="form-control" id="id_number" name="id_number" value="{{ old('id_number') }}" placeholder="ID Number">
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('date_of_birth') ? ' has-error' : '' }} longterm">
                            <label for="date_of_birth" class="col-sm-3 control-label">Date of Birth</label>

                            <div class="col-sm-9">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="date_of_birth" placeholder="  dd/mm/yyyy" value="{{ old('date_of_birth') }}">
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('school_id') ? ' has-error' : '' }}">
                            <label for="school" class="col-sm-3 control-label">School</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bar-chart"></i>
                                    </div>
                                    <select name="school_id" class="form-control">
                                        <option value="">*** Select School ***</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}"{{ ($school->id == old('school_id')) ? ' selected' : '' }}>{{ $school->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('ethnicity') ? ' has-error' : '' }}">
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
                                        <option value="1"{{ (1 == old('ethnicity')) ? ' selected' : '' }}>Male</option>
                                        <option value="0"{{ (2 == old('ethnicity')) ? ' selected' : '' }}>Female</option>
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
                                    <input type="tel" class="form-control" id="cell_number" name="cell_number" value="{{ old('cell_number') }}" data-inputmask='"mask": "(999) 999-9999"' placeholder="Cell Number" data-mask>
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('field_of_choice') ? ' has-error' : '' }}">
                            <label for="field_of_choice" class="col-sm-3 control-label">Field of choice after grade 12</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <textarea name="field_of_choice" class="form-control" placeholder="Field of choice after grade 12">{{ old('field_of_choice') }}</textarea>
                                </div>
                            </div>
                        </div>

						<div class="form-group{{ $errors->has('first_time') ? ' has-error' : '' }} shortterm">
                            <label for="first_time" class="col-sm-3 control-label">Is the first time visiting the centre?</label>
                            <div class="col-sm-9">
                                <div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-user"></i>
								</div>
								<select class="form-control" name="first_time" id="first_time">
									<option value="1"{{ (1 == old('first_time')) ? ' selected' : '' }}>Yes</option>
									<option value="2"{{ (2 == old('first_time')) ? ' selected' : '' }}>No</option>
								</select>
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('physical_address') ? ' has-error' : '' }} longterm">
                            <label for="physical_address" class="col-sm-3 control-label">Home address</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <textarea name="physical_address" class="form-control" placeholder="Home address">{{ old('physical_address') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('educator_id') ? ' has-error' : '' }} shortterm">
                            <label for="educator_id" class="col-sm-3 control-label">Educator</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
									<select name="educator_id" class="form-control" id="educator_id" >
                                        <option value="">*** Select Educator ***</option>
                                        @foreach($educators as $educator)
                                            <option value="{{ $educator->id }}"{{ ($educator->id == old('educator_id')) ? ' selected' : '' }}>{{ $educator->first_name . ' ' . $educator->surname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('toy_library') ? ' has-error' : '' }} shortterm">
                            <label for="toy_library" class="col-sm-3 control-label">Toy library</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
									 <textarea name="toy_library" class="form-control" placeholder="Toy library (Parent name & surname, email ,telephone, cell phone, Age group)">{{ old('toy_library') }}</textarea>
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('learning_condition') ? ' has-error' : '' }} longterm">
                            <label for="learning_condition" class="col-sm-3 control-label">Learning condition</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                       <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="learning_condition" name="learning_condition" value="{{ old('learning_condition') }}" placeholder="Learning condition">
                                </div>
                            </div>
                        </div><div class="form-group{{ $errors->has('physical_disability') ? ' has-error' : '' }} longterm">
                            <label for="physical_disability" class="col-sm-3 control-label">Physical Disability</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                       <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="physical_disability" name="physical_disability" value="{{ old('physical_disability') }}" placeholder="Physical Disability">
                                </div>
                            </div>
                        </div><div class="form-group{{ $errors->has('medical_condition') ? ' has-error' : '' }} longterm">
                            <label for="medical_condition" class="col-sm-3 control-label">Existing medical condition</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                       <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="medical_condition" name="medical_condition" value="{{ old('medical_condition') }}" placeholder="Existing medical condition">
                                </div>
                            </div>
                        </div><div class="form-group{{ $errors->has('parent_name') ? ' has-error' : '' }} longterm">
                            <label for="parent_name" class="col-sm-3 control-label">Name & Surname of Parent</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                       <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="parent_name" name="parent_name" value="{{ old('parent_name') }}" placeholder="Name & Surname of Parent">
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('parent_number') ? ' has-error' : '' }} longterm">
                            <label for="res_address" class="col-sm-3 control-label">Parent/Guardian contact number(Cellphone)</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
									<input type="tel" class="form-control" id="parent_number" name="parent_number" value="{{ old('parent_number') }}" data-inputmask='"mask": "(999) 999-9999"' placeholder="Parent/Guardian contact number(Cellphone)" data-mask>
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('attendance_reg_doc') ? ' has-error' : '' }}">
                            <label for="attendance_reg_doc" class="col-sm-3 control-label">Attendance register</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="file" id="attendance_reg_doc" name="attendance_reg_doc" class="file file-loading" data-allowed-file-extensions='["pdf", "docx"]' data-show-upload="false">
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('result_doc') ? ' has-error' : '' }} longterm">
                            <label for="result_doc" class="col-sm-3 control-label">Results</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="file" id="result_doc" name="result_doc" class="file file-loading" data-allowed-file-extensions='["pdf", "docx"]' data-show-upload="false">
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('date_started_project') ? ' has-error' : '' }}">
							<label for="date_started_project" class="col-sm-3 control-label">Date started in the project</label>
							<div class="col-sm-9">
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control datepicker" name="date_started_project" placeholder="  dd/mm/yyyy" value="{{ old('date_started_project') }}">
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

    <!-- Bootstrap date picker -->
	<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

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
    <!-- optionally if you need translation for your language then include locale file as mentioned below -->
    <!--<script src="/bower_components/bootstrap_fileinput/js/locales/<lang>.js"></script>-->
    <!-- End Bootstrap File input -->

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
            //Phone mask
            $("[data-mask]").inputmask();

            //load activities on page load if project passed by default url
            var projID = $('#project_id').val();
            if (projID > 0) populateActivityList(projID);

            //load activities on load
            $('#project_id').change(function(){
                var projectID = $(this).val();
                populateActivityList(projectID);
            });

        });
        //select the project sent from url
        var passedActivityID = parseInt("{{ $activity_id }}");
        //function to populate the activity drop down
        function populateActivityList(project_id, loadAll) {
            loadAll = loadAll || -1;
            $.post("{!! route('activitydropdown') !!}", { option: project_id, _token: $('input[name=_token]').val(), load_all: loadAll },
                    function(data) {
                        var activity = $('#activity_id');
                        var firstDDOption = "*** Select an Activity ***";
                        if (project_id > 0) firstDDOption = "*** Select an Activity ***";
                        else if (project_id == '') firstDDOption = "*** Select a Project First ***";
                        activity.empty();
                        activity
                                .append($("<option></option>")
                                        .attr("value",'')
                                        .text(firstDDOption));
                        $.each(data, function(key, value) {
                            var option = $("<option></option>")
                                    .attr("value",value)
                                    .text(key);
                            if(passedActivityID == value) option.attr("selected","selected");
                            activity.append(option);
                        });
                    });
        }
        //function to change applicant type
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
    </script>
@endsection