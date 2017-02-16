@extends('layouts.main_layout')
@section('page_dependencies')
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- iCheck -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/green.css">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <i class="fa fa-graduation-cap pull-right"></i>
                    <h3 class="box-title">Enrolment</h3>
                    <p id="box-subtitle">Enrol a learner and his/her subjects</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/education/registration">
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
                            <div class="form-group{{ $errors->has('registration_type') ? ' has-error' : '' }}">
                                <label for="registration_type" class="col-sm-2 control-label">Registration Type</label>

                                <div class="col-sm-10">
                                    <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_learner" name="registration_type" value="1" checked> Learner</label>
                                    <label class="radio-inline"><input type="radio" id="rdo_educator" name="registration_type" value="2"> Educator</label>
                                    <label class="radio-inline"><input type="radio" id="rdo_gen_pub" name="registration_type" value="3"> General Public</label>
                                </div>
                            </div>
                        <div class="form-group{{ $errors->has('programme_id') ? ' has-error' : '' }}">
                            <label for="programme_id" class="col-sm-2 control-label">Programme</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-graduation-cap"></i>
                                    </div>
                                    <select class="form-control select2" style="width: 100%;" id="programme_id" name="programme_id">
                                        <option value="">*** Select a Programme ***</option>
                                        @foreach($programmes as $programme)
                                            <option value="{{ $programme->id }}"{{ ($programme->id === old('programme_id')) ? ' selected="selected"' : '' }}>{{ $programme->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                            <div class="form-group{{ $errors->has('project_id') ? ' has-error' : '' }}">
                                <label for="project_id" class="col-sm-2 control-label">Project</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-graduation-cap"></i>
                                        </div>
                                        <select class="form-control select2" style="width: 100%;" id="project_id" name="project_id">
                                            <option value="">*** Select a Programme First ***</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group learner-field{{ $errors->has('learner_id') ? ' has-error' : '' }}">
                                <label for="learner_id" class="col-sm-2 control-label">Learner</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <select class="form-control select2" style="width: 100%;" id="learner_id" name="learner_id">
                                            <option value="">*** Select a Learner ***</option>
                                            @foreach($learners as $learner)
                                                <option value="{{ $learner->id }}"{{ ($learner->id === old('learner_id')) ? ' selected="selected"' : '' }}>{{ $learner->first_name . ' '. $learner->surname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group educator-field{{ $errors->has('educator_id') ? ' has-error' : '' }}">
                                <label for="educator_id" class="col-sm-2 control-label">Educator</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <select class="form-control select2" style="width: 100%;" id="educator_id" name="educator_id">
                                            <option value="">*** Select an Educator ***</option>
                                            @foreach($educators as $educator)
                                                <option value="{{ $educator->id }}"{{ ($educator->id === old('educator_id')) ? ' selected="selected"' : '' }}>{{ $educator->first_name . ' '. $educator->surname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group gen-pub-field{{ $errors->has('gen_public_id') ? ' has-error' : '' }}">
                                <label for="gen_public_id" class="col-sm-2 control-label">Member of General Public</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <select class="form-control select2" style="width: 100%;" id="gen_public_id" name="gen_public_id">
                                            <option value="">*** Select a Person ***</option>
                                            @foreach($gen_pubs as $gen_pub)
                                                <option value="{{ $gen_pub->id }}"{{ ($gen_pub->id === old('gen_public_id')) ? ' selected="selected"' : '' }}>{{ $gen_pub->names }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('registration_year') ? ' has-error' : '' }}">
                                <label for="registration_year" class="col-sm-2 control-label">Year</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-o"></i>
                                        </div>
                                        <select class="form-control select2" style="width: 100%;" id="registration_year" name="registration_year">
                                            <option value="">*** Select a Programme First ***</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group learner-field educator-field{{ $errors->has('course_type') ? ' has-error' : '' }}">
                                <label for="course_type" class="col-sm-2 control-label">Course Type</label>

                                <div class="col-sm-10">
                                    <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_year_course" name="course_type" value="1" checked> Year Course</label>
                                    <label class="radio-inline"><input type="radio" id="rdo_sem_course" name="course_type" value="2"> Semester Course</label>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('registration_semester') ? ' has-error' : '' }}" id="semester-row"><!-- learner-field educator-field -->
                                <label for="registration_semester" class="col-sm-2 control-label">Semester</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-o"></i>
                                        </div>
                                        <select class="form-control select2" style="width: 100%;" id="registration_semester" name="registration_semester">
                                            <option value="">*** Select the Semester ***</option>
                                            <option value="1">Semester One</option>
                                            <option value="2">Semester Two</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group learner-field{{ $errors->has('subject_id') ? ' has-error' : '' }}">
                                <label for="subject_id[]" class="col-sm-2 control-label">Subjects</label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" multiple="multiple" data-placeholder="Select a Subject" style="width: 100%;" id="subject_id" name="subject_id[]">
                                        @foreach($subjects as $index => $value)
                                            <option value="{{ $index }}"{{ ($index === old('subject_id[]')) ? ' selected="selected"' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        <div class="input-fields-wrap">
                            <div class="row educator-field">
                                <div class="col-xs-6">
                                    <div class="form-group{{ $errors->has('module_name') ? ' has-error' : '' }}">
                                        <label for="module_name[]" class="col-sm-4 control-label">Module</label>

                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-book"></i>
                                                </div>
                                                <input type="text" class="form-control" id="module_name" name="module_name[]" placeholder="Module" value="{{ old('module_name[]') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group{{ $errors->has('module_fee') ? ' has-error' : '' }}">
                                        <label for="module_fee[]" class="col-sm-3 control-label">Module Fee</label>

                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    R
                                                </div>
                                                <input type="number" class="form-control" id="module_fee" name="module_fee[]" placeholder="Module Fee" value="{{ old('module_fee[]') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="form-group educator-field">
                                <div class="col-sm-10 col-sm-offset-2">
                                    <button type="button" class="btn btn-default btn-block btn-flat" id="add_module"><i class="fa fa-clone"></i> Add More Modules</button>
                                </div>
                            </div>
                            <div class="form-group gen-pub-field{{ $errors->has('area_id') ? ' has-error' : '' }}">
                                <label for="area_id[]" class="col-sm-2 control-label">Areas</label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" multiple="multiple" data-placeholder="Select an Area" style="width: 100%;" id="area_id" name="area_id[]">
                                        @foreach($areas as $index => $value)
                                            <option value="{{ $index }}"{{ ($index === old('area_id[]')) ? ' selected="selected"' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group gen-pub-field{{ $errors->has('gen_pub_reg_fee') ? ' has-error' : '' }}">
                                <label for="gen_pub_reg_fee" class="col-sm-2 control-label">Registration Fee</label>

                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            R
                                        </div>
                                        <input type="number" class="form-control" id="gen_pub_reg_fee" name="gen_pub_reg_fee" placeholder="Registration Fee" value="{{ old('gen_pub_reg_fee') }}">
                                    </div>
                                </div>
                            </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" id="cancel" class="btn btn-default"><i class="fa fa-arrow-left"></i> Cancel</button>
                        <button type="submit" class="btn btn-success pull-right"><i class="fa fa-database"></i> Register</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
        <!-- Confirmation Modal -->
        @if(Session('success_add'))
            @include('contacts.partials.success_action', ['modal_title' => "Registration Successful!", 'modal_content' => session('success_add')])
        @endif
    </div>
    @endsection

    @section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>

    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <script type="text/javascript">
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
            //Cancel button click event
            $('#cancel').click(function () {
                location.href = '/';
            });
            //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%' // optional
            });
            //Date picker
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });
            //show/hide fields on radio button toggles (depending on registration type)
            $('#rdo_learner, #rdo_educator, #rdo_gen_pub').on('ifChecked', function(){
                var regType = hideFields();
                if (regType == 1) $('#box-subtitle').html('Enrol a learner and his/her subjects');
                else if (regType == 2) $('#box-subtitle').html('Enrol an educator and his/her modules');
                else if (regType == 3) $('#box-subtitle').html('Enrol a member of the general public');
            });
            //show/hide semester row
            $('#rdo_year_course, #rdo_sem_course').on('ifChecked', function(){
                hideSemesterRow();
            });

            //Add more modules
            var max_fields      = 10; //maximum input boxes allowed
            var wrapper         = $(".input-fields-wrap"); //Fields wrapper
            var add_button      = $("#add_module"); //Add button ID
            var x = 1; //initial text box count
            $(add_button).click(function(e){ //on add input button click
                e.preventDefault();
                if(x < max_fields){ //max input box allowed
                    x++; //text box increment
                    $(wrapper).append('<div class="row educator-field"><div class="col-xs-6"><div class="form-group{{ $errors->has('module_name[]') ? ' has-error' : '' }}"><label for="module_name" class="col-sm-4 control-label">Module</label><div class="col-sm-8"><div class="input-group"><div class="input-group-addon"><i class="fa fa-book"></i></div><input type="text" class="form-control" id="module_name" name="module_name[]" placeholder="Module" value="{{ old('module_name[]') }}"></div></div></div></div><div class="col-xs-6"><div class="form-group{{ $errors->has('module_fee[]') ? ' has-error' : '' }}"><label for="module_fee" class="col-sm-3 control-label">Module Fee</label><div class="col-sm-8"><div class="input-group"><div class="input-group-addon">R</div><input type="number" class="form-control" id="module_fee" name="module_fee[]" placeholder="Module Fee" value="{{ old('module_fee[]') }}"></div></div><div class="col-sm-1"><a href="#" class="remove_field"><i class="fa fa-times"></i></a></div></div></div></div>'); //add input box
                }
            });

            $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
                e.preventDefault(); $(this).parent('div').parent('div').parent('div').parent('div').remove(); x--;
            });

            //call hide/show fields functions
            hideFields();
            hideSemesterRow();

            //repopulate projects, year dropdowns when a programme has been changed
            $('#programme_id').change(function(){
                var programmeID = $(this).val()
                populateProjectDD(programmeID);
                populateYearDD(programmeID);
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
            $(window).on('resize', function() {
                $('.modal:visible').each(reposition);
            });

            //Show success action modal
            $('#success-action-modal').modal('show');
        });
        //function to hide/show fields depending on the registration type
        function hideFields() {
            var regType = $("input[name='registration_type']:checked").val();
            if (regType == 1) { //Learner
                $('select#educator_id, select#gen_public_id').val('');
                $('.educator-field, .gen-pub-field').hide();
                $('.learner-field').show();
            }
            else if (regType == 2) { //Educator
                $('select#learner_id, select#gen_public_id').val('');
                $('.learner-field, .gen-pub-field').hide();
                $('.educator-field').show();
            }
            else if (regType == 3) { //General Public
                $('select#learner_id, select#educator_id, select#registration_semester').val('');
                //$("input[name='course_type']:checked").val(1);
                $("#rdo_year_course").iCheck('check');
                $('.learner-field, .educator-field').hide();
                $('.gen-pub-field').show();
            }
            return regType;
            hideSemesterRow();
        }
        //function to hide/show semester
        function hideSemesterRow() {
            var courseType = $("input[name='course_type']:checked").val();
            if (courseType == 1) { //Year Course
                $('#registration_semester').select2().val('').trigger("change");
                $('#semester-row').hide();
            }
            else if (courseType == 2) { //Semester course
                $('#semester-row').show();
            }
            //return courseType;
        }
        //function to populate the projects drop down
        function populateProjectDD(programme_id, loadAll) {
            loadAll = loadAll || -1;
            $.post("{!! route('projectsdropdown') !!}", { option: programme_id, _token: $('input[name=_token]').val(), load_all: loadAll },
                    function(data) {
                        var projects = $('#project_id');
                        var firstDDOption = "*** Select a Project ***";
                        if (programme_id > 0) firstDDOption = "*** Select a Project ***";
                        else if (programme_id == '') firstDDOption = "*** Select a Programme First ***";
                        projects.empty();
                        projects
                                .append($("<option></option>")
                                        .attr("value",'')
                                        .text(firstDDOption));
                        /*var userID = (isset($loan)) ? $loan->user_id : ''*/
                        $.each(data, function(key, value) {
                            projects
                                    .append($("<option></option>")
                                            .attr("value",value)
                                            .text(key));
                        });
                    });
        }
        //function to populate the year drop down
        function populateYearDD(programme_id, loadAll) {
            loadAll = loadAll || -1;
            $.post("{!! route('regyeardropdown') !!}", { option: programme_id, _token: $('input[name=_token]').val(), load_all: loadAll },
                    function(data) {
                        var years = $('#registration_year');
                        var firstDDOption = "*** Select a Programme First ***";
                        if (programme_id > 0) firstDDOption = "*** Select the Year ***";
                        else if (programme_id == '') firstDDOption = "*** Select a Programme First ***";
                        years.empty();
                        years
                                .append($("<option></option>")
                                        .attr("value",'')
                                        .text(firstDDOption));
                        /*var userID = (isset($loan)) ? $loan->user_id : ''*/
                        $.each(data, function(key, value) {
                            years
                                    .append($("<option></option>")
                                            .attr("value",value)
                                            .text(key));
                        });
                    });
        }
    </script>
@endsection