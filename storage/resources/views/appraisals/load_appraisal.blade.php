
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
                    <h3 class="box-title">Employee Appraisals</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->

                <!-- Form Start -->
                <form id="load-kpi-form" name="load-kpi-form" class="form-horizontal" method="POST" action="" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="box-body" id="load_appraisal">
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
                        <div class="form-group{{ $errors->has('appraisal_type') ? ' has-error' : '' }}">
                            <label for="appraisal_type" class="col-sm-2 control-label">Appraisal Type</label>

                            <div class="col-sm-10">
                                <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_upload" name="appraisal_type" value="1"{{ old('appraisal_type') != 2 ? ' checked' : '' }}> File Upload</label>
                                <label class="radio-inline"><input type="radio" id="rdo_manual" name="appraisal_type" value="2" {{ old('appraisal_type') == 2 ? ' checked' : '' }}> Manual</label>
                            </div>
                        </div>
                        @foreach($division_levels as $division_level)
                            <div class="form-group manual-field{{ $errors->has('division_level_' . $division_level->level) ? ' has-error' : '' }}">
                                <label for="{{ 'division_level_' . $division_level->level }}" class="col-sm-2 control-label">{{ $division_level->name }}</label>

                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-black-tie"></i>
                                        </div>
                                        <select id="{{ 'division_level_' . $division_level->level }}" name="{{ 'division_level_' . $division_level->level }}" class="form-control select2" onchange="divDDOnChange(this, null, 'load_appraisal')" style="width: 100%;">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="form-group manual-field{{ $errors->has('hr_person_id') ? ' has-error' : '' }}">
                            <label for="hr_person_id" class="col-sm-2 control-label">Employee</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <select id="hr_person_id" name="hr_person_id" class="form-control select2" style="width: 100%;">
                                        <option value="">*** Select an Employee ***</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group file-upload-field {{ $errors->has('upload_type') ? ' has-error' : '' }}">
                            <label for="upload_type" class="col-sm-2 control-label">Upload Types</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <select id="upload_type" name="upload_type" class="form-control">
                                        <option value="">*** Select Upload Type ***</option>
                                        <option value="1">General</option>
                                        <option value="2">Clock In</option>
                                        <option value="3">Query Report </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group file-upload-field{{ $errors->has('kpi_id') ? ' has-error' : '' }}">
                            <label for="kpi_id" class="col-sm-2 control-label">KPI</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <select id="kpi_id" name="kpi_id" class="form-control select2" style="width: 100%;">
                                        <option value="">*** Select a KPI ***</option>
                                        @foreach($kpis as $kpi)
                                            <option value="{{ $kpi->id }}">{{ $kpi->indicator }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group manual-field{{ $errors->has('appraisal_month') ? ' has-error' : '' }}">
                            <label for="appraisal_month" class="col-sm-2 control-label">Appraisal Month</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" name="appraisal_month" id="appraisal_month" placeholder="Click To Select A Month" value="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group file-upload-field {{ $errors->has('date_uploaded') ? ' has-error' : '' }}">
                            <label for="date_uploaded" class="col-sm-2 control-label">Appraisal Month</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
									<input type="text" class="form-control" name="date_uploaded" id="date_uploaded" placeholder="Click To Select A Month" value="">
                                </div>
                            </div>
                        </div>
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
                        <button type="submit" id="load-kpis" class="btn btn-primary pull-right"><i class="fa fa-cloud-download"></i> Load KPIs</button>
                    </div>
                </form>
            </div>
        </div>
        @if (session('success_insert'))
        @include('appraisals.partials.success_action', ['modal_title' => 'Appraisal Successfully Uploaded!', 'modal_content' => session('success_insert')])
        @endif
        @if (session('error'))
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
    <script>
        $(function () {
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

            //Initialize Select2 Elements
            $(".select2").select2();
            //Cancel button click event
            $('#back_button').click(function () {
                location.href = '/appraisal/load_appraisals';
            });
            //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
            //Date picker
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });
            $('#appraisal_month').datepicker({
                format: 'MM yyyy',
                autoclose: true,
                startView: "months",
                minViewMode: "months",
                todayHighlight: true
            });
			$('#date_uploaded').datepicker({
                format: 'MM yyyy',
                autoclose: true,
                startView: "months",
                minViewMode: "months",
                todayHighlight: true
            });

            //call hide/show fields functions on doc ready
            hideFields();

            //show/hide file upload or manual fields on radio checked
            $('#rdo_upload, #rdo_manual').on('ifChecked', function(){
                hideFields();
            });

            //set the location of the load kpis button
            $('#hr_person_id').change(function() {
                var selectedEmp = $(this).val();
                empSelection(selectedEmp);
            });

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
        });
        //function to hide/show manual or file upload fields
        function hideFields() {
            var appraisalType = $("input[name='appraisal_type']:checked").val();
            if (appraisalType == 1) { //File upload
                $('.file-upload-field').show();
                $('.manual-field').hide();
                $('#load-kpi-form').attr('action', '/appraisal/upload_appraisals');
                $('#load-kpis').attr('type', 'submit').html("<i class='fa fa-cloud-download'></i> Upload KPIs").show();
                $('#load-kpis').attr('onclick', '');
            }
            else if (appraisalType == 2) { //Manual
                $('.manual-field').show();
                $('.file-upload-field').hide();
                $('#load-kpis').attr('type', 'button').html("<i class='fa fa-cloud-download'></i> Load KPIs");
                $('#load-kpis').attr('onclick', 'loadKPIsOnClick()');
                var hrID = $('#hr_person_id').val();
                empSelection(hrID);
            }
            return appraisalType;
        }

        //function to hide show the load kpi button if emp selected
        function empSelection(selectedEmp) {
            //console.log('selected emp: ' + selectedEmp);
            if (selectedEmp == '') {
                $('#load-kpis').hide();
            }
            else $('#load-kpis').show();
        }
        //function to set the url of the load kpi btn
        function loadKPIsOnClick() {
            var selectedEmp = $('#hr_person_id').val();
            var appraisalMonth = $.trim($('#appraisal_month').val());
            if (appraisalMonth == '') appraisalMonth = '{{ \Carbon\Carbon::now()->format('F Y') }}';
            //$('#load-kpis').click(function () {
            location.href = '/appraisal/load/result/' + selectedEmp + '/' + appraisalMonth;
            //});
        }
    </script>
@endsection
