@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-anchor pull-right"></i>
                    <h3 class="box-title">Leave Allocation</h3>
                    <p id="box-subtitle">leave allocation</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                <form name="leave-alloccation-form" class="form-horizontal" method="POST" action=""
                      enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="box-body" id="view_users">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                                </button>
                                <h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group{{ $errors->has('allocation_type') ? ' has-error' : '' }}">
                            <label for="Leave_type" class="col-sm-2 control-label"> Action</label>

                            <div class="col-sm-9">
                                <label class="radio-inline" style="padding-left: 0px;"><input type="radio"
                                                                                              id="rdo_adjust"
                                                                                              name="allocation_type"
                                                                                              value="1" checked> Adjust
                                    Leave</label>
                                <label class="radio-inline"><input type="radio" id="rdo_resert" name="allocation_type"
                                                                   value="2"> Reset Leave</label>
                                <label class="radio-inline"><input type="radio" id="rdo_allocate" name="allocation_type"
                                                                   value="3"> Allocate Leave</label>
                            </div>
                        </div>

                        <!--                        <div class="form-group ">-->
                        <div class="form-group emp-field{{ $errors->has('leave_types_id') ? ' has-error' : '' }}">
                            <label for="leave_types_id" class="col-sm-2 control-label">Leave Type(s)</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <select id="leave_types_id" name="leave_types_id" class="form-control select2"
                                             data-placeholder="**Select Leave type**">
                                        @foreach($leaveTypes as $leaveType)
                                            <option value="{{ $leaveType->id }}">{{ $leaveType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @foreach($division_levels as $division_level)
                            <div class="form-group manual-field{{ $errors->has('division_level_' . $division_level->level) ? ' has-error' : '' }}">
                                <label for="{{ 'division_level_' . $division_level->level }}"
                                       class="col-sm-2 control-label">{{ $division_level->name }}</label>

                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-black-tie"></i>
                                        </div>
                                        <select id="{{ 'division_level_' . $division_level->level }}"
                                                name="{{ 'division_level_' . $division_level->level }}"
                                                class="form-control" onchange="divDDOnChange(this, 'hr_person_id', 'view_users')">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="form-group {{ $errors->has('hr_person_id') ? ' has-error' : '' }}">
                            <label for="hr_person_id" class="col-sm-2 control-label">Employees</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user-circle"></i>
                                    </div>
                                    <select class="form-control select2" multiple="multiple" style="width: 100%;"
                                            id="hr_person_id" name="hr_person_id[]">
                                        <option value="">*** Select an Employee ***</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="form-group adjust-field{{ $errors->has('leave_credit_id') ? ' has-error' : '' }}">
                            <label for="days" class="col-sm-2 control-label">Adjust Number of Days</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar-plus-o"></i>
                                    </div>

                                    <input type="text" class="form-control" id="adjust_days" name="adjust_days"
                                           value="{{ old('adjust_days') }}" placeholder="Enter number of days">
                                </div>
                            </div>
                        </div>

                        <div class="form-group resert-field {{ $errors->has('resert_days') ? ' has-error' : '' }}">
                            <label for="days" class="col-sm-2 control-label">Reset Number of Days</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar-plus-o"></i>
                                    </div>
                                    <input type="text" class="form-control" id="resert_days" name="resert_days"
                                           value="{{ old('resert_days') }}" placeholder="Enter number of days">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" id="cancel" class="btn btn-primary"><i class="fa fa-arrow-left"></i>
                            Cancel
                        </button>
                        <input type="submit" id="load-allocation" name="load-allocation"
                               class="btn btn-primary pull-right" value="Submit">
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
        <!-- Confirmation Modal -->
        @if(Session('success_application'))
            @include('leave.partials.success_action', ['modal_title' => "Application Successful!", 'modal_content' => session('success_application')])
        @endif
    </div>
@endsection

@section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- bootstrap datepicker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <!-- Start Bootstrap File input -->
    <!-- canvas-to-blob.min.js is only needed if you wish to resize images before upload. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js"
            type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
    <!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
    <script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
    <!-- optionally if you need translation for your language then include locale file as mentioned below
    <script src="/bower_components/bootstrap_fileinput/js/locales/<lang>.js"></script>-->
    <!-- End Bootstrap File input -->

    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>

    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script type="text/javascript">
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
            //Cancel button click event
            $('#cancel').click(function () {
                location.href = '/leave/Allocate_leave_types';
            });
            //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
            hideFields();
            //show/hide fields on radio button toggles (depending on registration type)
            $('#rdo_adjust, #rdo_resert, #rdo_allocate').on('ifChecked', function () {
                var allType = hideFields();
                if (allType == 1) $('#box-subtitle').html('Adjust leave allocation');
                else if (allType == 2) $('#box-subtitle').html('Resert leave allocation');
                else if (allType == 3) $('#box-subtitle').html('Allocate leave allocation');
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
            var allType = $("input[name='allocation_type']:checked").val();
            if (allType == 1) { //adjsut leave
                $('.resert-field, .allocaion-field').hide();
                $('.adjust-field').show();
                $('form[name="leave-alloccation-form"]').attr('action', '/leave/Allocate_leave');
                $('#load-allocation').val("Submit");
            }
            else if (allType == 2) { //resert leave
//                
                $('.adjust-field, .allocate-field').hide();
                $('.resert-field').show();
                $('form[name="leave-alloccation-form"]').attr('action', '/leave/Allocate_leave/resert');
                $('#load-allocation').val("Submit");
            }
            else if (allType == 3) { //allocate leave
//            
                $('.resert-field, .adjust-field').hide();
                $('.allocaion-field').show();
                $('form[name="leave-alloccation-form"]').attr('action', '/leave/Allocate_leave/add');
                $('#load-allocation').val("Submit");
            }
            return allType;
        }

        //Load divisions drop down
        var parentDDID = '';
        var loadAllDivs = 1;
        @foreach($division_levels as $division_level)
        //Populate drop down on page load
        var ddID = '{{ 'division_level_' . $division_level->level }}';
        var postTo = '{!! route('divisionsdropdown') !!}';
        var selectedOption = '';
        var divLevel = parseInt('{{ $division_level->level }}');
        var incInactive = -1;
        var loadAll = loadAllDivs;
        loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo);
        parentDDID = ddID;
        loadAllDivs = -1;
        @endforeach
        //        });

        //function to populate the year drop down

    </script>
@endsection