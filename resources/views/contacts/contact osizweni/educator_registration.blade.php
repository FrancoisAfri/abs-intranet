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
                    <h3 class="box-title">New Educator Registration</h3>
                    <p>Enter Educator details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/add_educator" enctype="multipart/form-data">
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
                         <div class="form-group{{ $errors->has('project_id') ? ' has-error' : '' }}">
                            <label for="project_id" class="col-sm-3 control-label">Project</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bar-chart"></i>
                                    </div>
                                    <select id="project_id" name="project_id" class="form-control">
                                        <option value="">*** Select Project ***</option>
                                        @foreach($projects as $projects)
                                            <option value="{{ $projects->id }}"{{ ($project_id != -1) ? ' selected' : '' }}>{{ $projects->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('activity_id') ? ' has-error' : '' }}">
                            <label for="activity_id" class="col-sm-3 control-label">Activity</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bar-chart"></i>
                                    </div>
                                    <select id="activity_id" name="activity_id" class="form-control">
                                        <option value="">*** Select a Project First ***</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}"">
                            <label for="appliction_type" class="col-sm-3 control-label">Appliction Type</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <select class="form-control" name="type" id="type"
                                            placeholder="Select Appliction Type" onchange="changeApplicant(this.value)"
                                            required>
                                        <option value="1"{{ (1 == old('type')) ? ' selected' : '' }}>Short term</option>
                                        <option value="2"{{ (2 == old('type')) ? ' selected' : '' }}>Long term</option>
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
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                           value="{{ old('first_name') }}" placeholder="First Name" required>
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
                                    <input type="text" class="form-control" id="surname" name="surname"
                                           value="{{ old('surname') }}" placeholder="Surname">
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
                                    <input type="number" class="form-control" id="id_number" name="id_number" value=""
                                           placeholder="ID Number">
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
                                            <option value="{{ $school->id }}">{{ $school->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ethnicity" class="col-sm-3 control-label">Race</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bar-chart"></i>
                                    </div>
                                    <select name="ethnicity" class="form-control">
                                        <option value="">*** Select a Race ***</option>
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
                                        <option value="1"{{ (1 == old('gender')) ? ' selected' : '' }}>Male</option>
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
                                    <input type="tel" class="form-control" id="cell_number" name="cell_number" value=""
                                           data-inputmask='"mask": "(999) 999-9999"' placeholder="Cell Number"
                                           data-mask value="{{ old('cell_number') }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">Highest Academic Qualification</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-info-circle"></i>
                                    </div>
                                    <input type="text" class="form-control" id="highest_qualification"
                                           name="highest_qualification" value="{{ old('highest_qualification') }}"
                                           placeholder="Highest Academic Qualification...">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">Highest Professional Qualification</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-info-circle"></i>
                                    </div>
                                    <input type="text" class="form-control" id="highest_professional_qualification"
                                           name="highest_professional_qualification"
                                           value="{{ old('highest_professional_qualification') }}"
                                           placeholder="Highest Professional Qualification...">
                                </div>
                            </div>
                        </div>
                        <div class="form-group shortterm">
                            <label for="comment" class="col-sm-3 control-label">Comment</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <textarea name="comment" class="form-control" placeholder="Comment"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group shortterm">
                            <label for="first_time" class="col-sm-3 control-label">First time attending
                                Osizweni?</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <select class="form-control" name="first_time" id="first_time">
                                        <option value="1"{{ (1 == old('first_time')) ? ' selected' : '' }}>Yes</option>
                                        <option value="0"{{ (2 == old('first_time')) ? ' selected' : '' }}>No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group longterm{{ $errors->has('physical_address') ? ' has-error' : '' }} longterm">
                            <label for="physical_address" class="col-sm-3 control-label">Home address</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <textarea name="physical_address" class="form-control"
                                              placeholder="Home address">{{ old('physical_address') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group longterm">
                            <label for="postal_address" class="col-sm-3 control-label">Postal Address</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <textarea name="postal_address" class="form-control"
                                              placeholder="Postal Address"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group longterm">
                            <label for="nok_first_name" class="col-sm-3 control-label">Next of kin First Name</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="nok_first_name" name="nok_first_name"
                                           value="{{ old('nok_first_name') }}" placeholder="Next of Kin First Name...">
                                </div>
                            </div>
                        </div>
                        <div class="form-group longterm">
                            <label for="nok_surname" class="col-sm-3 control-label">Next of kin Surname</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="nok_surname" name="nok_surname"
                                           value="{{ old('nok_first_name') }}" placeholder="Next of Kin Surname...">
                                </div>
                            </div>
                        </div>
                        <div class="form-group longterm">
                            <label for="nok_relationship" class="col-sm-3 control-label">Relationship (Next of
                                Kin)</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-info-circle"></i>
                                    </div>
                                    <input type="text" class="form-control" id="nok_relationship"
                                           name="nok_relationship" value="{{ old('nok_first_name') }}"
                                           placeholder="Relationship with your next of kin...">
                                </div>
                            </div>
                        </div>
                        <div class="form-group longterm">
                            <label for="nok_cell_number" class="col-sm-3 control-label">Next of kin Cell Number</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="tel" class="form-control" id="nok_cell_number" name="nok_cell_number"
                                           value="{{ old('nok_cell_number') }}"
                                           data-inputmask='"mask": "(999) 999-9999"' placeholder="Cell Number"
                                           data-mask>
                                </div>
                            </div>
                        </div>
                        <div class="form-group longterm">
                            <label for="nok_email" class="col-sm-3 control-label">Next of kin Email</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <input type="email" class="form-control" id="nok_email" name="nok_email"
                                           value="{{ old('nok_email') }}" data-inputmask='"mask": "(999) 999-9999"'
                                           placeholder="Cell Number" data-mask>
                                </div>
                            </div>
                        </div>
                        <div class="form-group longterm">
                            <label for="course_sponsored" class="col-sm-3 control-label">Qualification Sponsored</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-info-circle"></i>
                                    </div>
                                    <input type="text" class="form-control" id="course_sponsored"
                                           name="course_sponsored" value="{{ old('course_sponsored') }}"
                                           placeholder="Qualification Sponsored">
                                </div>
                            </div>
                        </div>
                        <div class="form-group longterm{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-sm-3 control-label">Email Address</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <input type="email" class="form-control" id="email" name="email" value=""
                                           placeholder="Email Address">
                                </div>
                            </div>
                        </div>
                        <div class="form-group longterm">
                            <label for="institution" class="col-sm-3 control-label">Institution</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <input type="text" class="form-control" id="institution" name="institution"
                                           value="{{ old('institution') }}"
                                           placeholder="How did you know about this programme?">
                                </div>
                            </div>
                        </div>
                        <div class="form-group longterm">
                            <label for="engagement_date" class="col-sm-3 control-label">Date of First Engagement</label>

                            <div class="col-sm-9">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control datepicker" id="engagement_date"
                                           name="engagement_date" placeholder="  dd/mm/yyyy"
                                           value="{{ old('engagement_date') }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group longterm">
                            <label for="cv_doc" class="col-sm-3 control-label">CV</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="file" id="cv_doc" name="cv_doc" class="file file-loading"
                                           data-allowed-file-extensions='["pdf", "docx"]' data-show-upload="false">
                                </div>
                            </div>
                        </div>
                        <div class="form-group longterm">
                            <label for="contract_doc" class="col-sm-3 control-label">Contract/Agreement </label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="file" id="contract_doc" name="contract_doc" class="file file-loading"
                                           data-allowed-file-extensions='["pdf", "docx"]' data-show-upload="false">
                                </div>
                            </div>
                        </div>
                        <!--<div class="form-group longterm">
                            <label for="result" class="col-sm-3 control-label">Results</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                       <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="result" name="result" value="{{ old('result') }}" placeholder="Results">
                                </div>
                            </div>
                        </div>
						<div class="form-group longterm">
                            <label for="result_doc" class="col-sm-3 control-label">Results Attachment</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="file" id="result_doc" name="result_doc" class="file file-loading" data-allowed-file-extensions='["pdf", "docx"]' data-show-upload="false">
                                </div>
                            </div>
                        </div>
						<div id="tab_tab">
							<div class="form-group longterm" id="module_regis">
								<label for="module_registered" class="col-sm-3 control-label">Module registered</label>
								<div class="col-sm-9">
									<div class="input-group">
										<div class="input-group-addon">
										   <i class="fa fa-book"></i>
										</div>
										<input type="text" class="form-control" id="module_registered" name="module_registered[1]" value="" placeholder="Module registered">
									</div>
								</div>
							</div>
							<div class="form-group longterm" id="module_dat">
								<label for="module_date" class="col-sm-3 control-label">Date of module registration</label>
								<div class="col-sm-9">
									<div class="input-group date">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input type="text" class="form-control datepicker" id="module_date" name="module_date[1]" placeholder="  dd/mm/yyyy" value="">
									</div>
								</div>
							</div>
						</div>
						<div class="form-group longterm"  id="final_row">
							<div class="col-sm-12"><button type="button" class="btn btn-primary add_more"  onclick="addFile()">Add More Module</button></div>               
						</div>-->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button id="cancel" class="btn btn-default"><i class="fa fa-arrow-left"></i> Cancel</button>
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-user-plus"></i> Add
                        </button>
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
                autoclose: true,
                todayHighlight: true
            });
            //Phone mask
            $("[data-mask]").inputmask();

            //load activities on page load if project passed by default url
            var projID = $('#project_id').val();
            if (projID > 0) populateActivityDD(projID);

            //load activities on project change
            $('#project_id').change(function () {
                var projectID = $(this).val()
                populateActivityDD(projectID);
            });
        });
        $('.longterm').hide();
        function changeApplicant(type) {
            if (type == 1) {
                $('.longterm').hide();
                $('.shortterm').show();
            }
            else {
                $('.shortterm').hide();
                $('.longterm').show();
            }

        }
        function clone(id, file_index, child_id) {
            var clone = document.getElementById(id).cloneNode(true);
            clone.setAttribute("id", file_index);
            clone.setAttribute("name", file_index);
            clone.style.display = "block";
            clone.querySelector('#' + child_id).setAttribute("name", child_id + '[' + file_index + ']');
            clone.querySelector('#' + child_id).disabled = false;
            clone.querySelector('#' + child_id).setAttribute("id", child_id + '[' + file_index + ']');
            return clone;
        }
        function addFile() {
            var table = document.getElementById("tab_tab");
            var file_index = document.getElementById("file_index");
            file_index.value = ++file_index.value;
            var file_clone = clone("module_regis", file_index.value, "module_registered");
            var name_clone = clone("module_dat", file_index.value, "module_date");
            var final_row = document.getElementById("final_row").cloneNode(false);
            table.appendChild(file_clone);
            table.appendChild(name_clone);
            table.appendChild(final_row);
            var total_files = document.getElementById("total_files");
            total_files.value = ++total_files.value;
            //change the following using jquery if necessary
            var remove = document.getElementsByName("remove");
            for (var i = 0; i < remove.length; i++)
                remove[i].style.display = "inline";
        }
        //select the project sent from url
        var passedActivityID = parseInt("{{ $activity_id }}");
        //function to populate the projects drop down
        function populateActivityDD(project_id, loadAll) {
            loadAll = loadAll || -1;
            $.post("{!! route('activitydropdown') !!}", {
                        option: project_id,
                        _token: $('input[name=_token]').val(),
                        load_all: loadAll
                    },
                    function (data) {
                        var activities = $('#activity_id');
                        var firstDDOption = "*** Select an Activity ***";
                        if (activity_id > 0) firstDDOption = "*** Select an Activity ***";
                        else if (activity_id == '') firstDDOption = "*** Select a Project First ***";
                        activities.empty();
                        activities
                                .append($("<option></option>")
                                        .attr("value", '')
                                        .text(firstDDOption));
                        $.each(data, function (key, value) {
                            var option = $("<option></option>")
                                    .attr("value", value)
                                    .text(key);
                            if (passedActivityID == value) option.attr("selected", "selected");
                            activities.append(option);
                            ;
                        });
                    });
        }
    </script>
@endsection