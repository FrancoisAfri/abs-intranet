@extends('layouts.main_layout')

@section('page_dependencies')
        <!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">

<!-- bootstrap file input -->
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row">
        <!-- User Form -->
        <div class="col-md-8 col-md-offset-2">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">Learner</h3>
                    <p>Learner details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/learners/{{ $learner->id }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    <div class="box-body">
						<div class="form-group">
                            <label for="type" class="col-sm-3 control-label">Appliction Type</label>
                            <div class="col-sm-9">
                                <div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-user"></i>
								</div>
								<select class="form-control" name="type" id="type" placeholder="Select Appliction Type"  onchange="changeApplicant(this.value)"  required>
									<option value="1" {{ (!empty($learner->type) && $learner->type == 1) ? ' selected': '' }}>Short term</option>
									<option value="2" {{ (!empty($learner->type) && $learner->type == 2) ? ' selected': '' }}>Long term</option>
								</select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="first_name" class="col-sm-3 control-label">First Name</label>
                            <div class="col-sm-9">
                                <div class="input-group">
								<div class="input-group-addon">
                              <i class="fa fa-user"></i>
                            </div>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $learner->first_name}}" placeholder="First Surname" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="surname" class="col-sm-3 control-label">Surname</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="surname" name="surname" value="{{ $learner->surname}}" placeholder="Surname" required>
                                </div>
                            </div>
                        </div>
						<div class="form-group ">
								<label for="module_registered" class="col-sm-3 control-label">Grade</label>
								<div class="col-sm-9">
									<div class="input-group">
										<div class="input-group-addon">
										   <i class="fa fa-book"></i>
										</div>
										<input type="number" class="form-control" id="grade" name="grade" value="{{ $learner->grade}}" placeholder="Grade">
									</div>
								</div>
							</div>
						<div class="form-group longterm">
                            <label for="id_number" class="col-sm-3 control-label">ID Number</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="number" class="form-control" id="id_number" name="id_number" value="{{ $learner->id_number}}" placeholder="ID Number">
                                </div>
                            </div>
                        </div>
						<div class="form-group longterm">
                            <label for="date_of_birth" class="col-sm-3 control-label">Date of Birth</label>

                            <div class="col-sm-9">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="date_of_birth" placeholder="  dd/mm/yyyy" value="{{!empty($learner->date_of_birth) ? date('d/m/Y', $learner->date_of_birth) : ''}}">
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="school" class="col-sm-3 control-label">School</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bar-chart"></i>
                                    </div>
                                    <select name="school_id" class="form-control">
                                        <option value="">*** Select School ***</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}" {{ (isset($learner) && $school->id == $learner->school_id) ? ' selected': '' }}>{{ $school->name }}</option>
                                        @endforeach
                                    </select>
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
                                            <option value="{{ $ethnicity->id }}"{{ (isset($learner) && $ethnicity->id == $learner->ethnicity) ? ' selected': '' }}>{{ $ethnicity->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
						 <div class="form-group">
                            <label for="gender" class="col-sm-3 control-label">Gender</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-venus-mars"></i>
                                    </div>
                                    <select name="gender" class="form-control">
                                        <option value="">*** Select gender ***</option>
                                        <option value="1" {{ (!empty($learner->gender) && $learner->gender == 1) ? ' selected': '' }}>Male</option>
                                        <option value="0" {{ (empty($learner->gender) && $learner->gender == 0) ? ' selected': '' }}>Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cell_number" class="col-sm-3 control-label">Cell Number</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="tel" class="form-control" id="cell_number" name="cell_number" value="{{ $learner->cell_number }}" data-inputmask='"mask": "(999) 999-9999"' placeholder="Cell Number" data-mask>
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="field_of_choice" class="col-sm-3 control-label">Field of choice after grade 12</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <textarea name="field_of_choice" class="form-control" placeholder="Field of choice after grade 12">{{ $learner->field_of_choice }}</textarea>
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="project_id" class="col-sm-3 control-label">Project/Activity</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bar-chart"></i>
                                    </div>
                                    <select name="activity_id" class="form-control">
                                        <option value="">*** Select Activity/Project ***</option>
                                        @foreach($activities as $activity)
                                            <option value="{{ $activity->id }}" {{ (isset($learner) && $activity->id == $learner->activity_id) ? ' selected': '' }}>{{ $activity->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
						<div class="form-group shortterm">
                            <label for="first_time" class="col-sm-3 control-label">Is the first time visiting the centre?</label>
                            <div class="col-sm-9">
                                <div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-user"></i>
								</div>
								<select class="form-control" name="first_time" id="first_time">
									<option value="1" {{ (empty($learner->first_time) && $learner->first_time == 1) ? ' selected': '' }}>Yes</option>
									<option value="2" {{ (empty($learner->first_time) && $learner->first_time == 2) ? ' selected': '' }}>No</option>
								</select>
                                </div>
                            </div>
                        </div>
						<div class="form-group longterm">
                            <label for="physical_address" class="col-sm-3 control-label">Home address</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <textarea name="physical_address" class="form-control" placeholder="Home address">{{ $learner->physical_address }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group shortterm">
                            <label for="educator_id" class="col-sm-3 control-label">Educator</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
									<select name="educator_id" class="form-control" id="educator_id" >
                                        <option value="">*** Select Educator ***</option>
                                        @foreach($educators as $educator)
                                            <option value="{{ $educator->id }}" {{ (isset($learner) && $educator->id == $learner->educator_id) ? ' selected': '' }}>{{ $educator->first_name . ' ' . $educator->surname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
						<div class="form-group shortterm">
                            <label for="toy_library" class="col-sm-3 control-label">Toy library</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
									 <textarea name="toy_library" class="form-control" placeholder="Toy library">{{ $learner->toy_library }}</textarea>
                                </div>
                            </div>
                        </div>
						<div class="form-group longterm">
                            <label for="learning_condition" class="col-sm-3 control-label">Learning condition</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                       <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="learning_condition" name="learning_condition" value="{{ $learner->learning_condition }}" placeholder="Learning condition">
                                </div>
                            </div>
                        </div><div class="form-group longterm">
                            <label for="physical_disability" class="col-sm-3 control-label">Physical Disability</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                       <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="physical_disability" name="physical_disability" value="{{ $learner->physical_disability }}" placeholder="Physical Disability">
                                </div>
                            </div>
                        </div><div class="form-group longterm">
                            <label for="medical_condition" class="col-sm-3 control-label">Existing medical condition</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                       <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="medical_condition" name="medical_condition" value="{{ $learner->medical_condition }}" placeholder="Existing medical condition">
                                </div>
                            </div>
                        </div><div class="form-group longterm">
                            <label for="parent_name" class="col-sm-3 control-label">Name & Surname of Parent</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                       <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="parent_name" name="parent_name" value="{{ $learner->parent_name }}" placeholder="Name & Surname of Parent">
                                </div>
                            </div>
                        </div>
						<div class="form-group longterm">
                            <label for="res_address" class="col-sm-3 control-label">Parent/Guardian contact number(Cellphone)</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
									<input type="tel" class="form-control" id="parent_number" name="parent_number" value="{{ $learner->parent_number }}" data-inputmask='"mask": "(999) 999-9999"' placeholder="Parent/Guardian contact number(Cellphone)" data-mask>
                                </div>
                            </div>
                        </div>
						<!--<div class="form-group">
                            <label for="attendance_reg_doc" class="col-sm-3 control-label">Attendance register</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="file" id="attendance_reg_doc" name="attendance_reg_doc" class="file file-loading" data-allowed-file-extensions='["pdf", "docx"]' data-show-upload="false">
                                </div>
                            </div>
                        </div>-->
						<div class="form-group longterm">
                            <label for="result_doc" class="col-sm-3 control-label">Results</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="file" id="result_doc" name="result_doc" class="file file-loading" data-allowed-file-extensions='["pdf", "docx"]' data-show-upload="false">
									@if(!empty($result_doc))
											<a href="{{ $result_doc}}" target=\"_blank\">Results</a>
										@endif
							   </div>
                            </div>
                        </div>
						<div class="form-group">
							<label for="date_started_project" class="col-sm-3 control-label">Date started in the project</label>
							<div class="col-sm-9">
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control datepicker" name="date_started_project" placeholder="  dd/mm/yyyy" value="{{ !empty($learner->date_started_project) ? date('d/m/Y', $learner->date_started_project) : ''}}">
								</div>
							</div>
						</div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="button" id="cancel" class="btn btn-default"><i class="fa fa-arrow-left"></i> Cancel</button>
                        <button type="submit" name="command" id="update" class="btn btn-primary pull-right"><i class="fa fa-upload"></i> Update</button>
				   </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
        <!-- Confirmation Modal -->
        @if(Session('success_edit'))
            @include('contacts.partials.success_action_2buttons', ['modal_title' => 'Learner Details Updated!', 'modal_content' => session('success_edit')])
        @elseif(Session('success_add'))
            @include('contacts.partials.success_action_2buttons', ['modal_title' => 'Learner Added!', 'modal_content' => session('success_add')])
        @endif
    </div>
@endsection

@section('page_script')
            <!-- bootstrap datepicker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

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

    <script>
        $(function () {
            //Cancel button click event
            document.getElementById("cancel").onclick = function () {
                location.href = "{{ $back }}";
            };
			//Date picker
			$('.datepicker').datepicker({
				format: 'dd/mm/yyyy',
				endDate: '-1d',
				autoclose: true,
				todayHighlight: true
				});
			if ( {{ $learner->type  }} == 1)
			{
				$('.longterm').hide();
				$('.shortterm').show();
			}
			else if ( {{ $learner->type  }} == 2)
			{
				$('.shortterm').hide();
				$('.longterm').show();
			}

            //Phone mask
            $("[data-mask]").inputmask();

            // [bootstrap file input] initialize with defaults
            $("#input-1").fileinput();
            // with plugin options
            //$("#input-id").fileinput({'showUpload':false, 'previewFileType':'any'});

            //Tooltip
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
            $(window).on('resize', function() {
                $('.modal:visible').each(reposition);
            });

            //Show success action modal
            $('#success-action-modal').modal('show');

            //add more button click
            $('#add-more-clt').click(function () {
                location.href = "{!! route('learnerregistration', ['project' => !empty($learner->project_id) ? $learner->project_id : -1, 'activity'=> !empty($learner->activity_id) ? $learner->activity_id : '']) !!}";
            });
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
    </script>
@endsection