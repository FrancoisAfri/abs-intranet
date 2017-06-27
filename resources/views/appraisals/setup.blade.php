@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Appraisal Weight Settings</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <form class="form-horizontal" method="POST" action="/appraisal/setup">
                    {{ csrf_field() }}
                    <!-- /.box-header -->
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
                            <div class="form-group{{ $errors->has('three_sixty_status') ? ' has-error' : '' }}">
                                <label for="three_sixty_status" class="col-sm-3 control-label">Enable Three-Sixty Appraisal</label>

                                <div class="col-sm-9">
                                    <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_ts_on" name="three_sixty_status" value="1"{{ (old('three_sixty_status') === 1 || ($appraisalSetup && $appraisalSetup->three_sixty_status == 1)) ? ' checked' : '' }}> Yes</label>
                                    <label class="radio-inline"><input type="radio" id="rdo_ts_off" name="three_sixty_status" value="0"{{ (old('three_sixty_status') === 0 || ($appraisalSetup && $appraisalSetup->three_sixty_status === 0)) ? ' checked' : '' }}> No</label>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('manager_appraisal_weight') ? ' has-error' : '' }}">
                                <label for="manager_appraisal_weight" class="col-sm-3 control-label">Manager Appraisal Weight</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="manager_appraisal_weight" name="manager_appraisal_weight" value="{{ (old('manager_appraisal_weight')) ? old('manager_appraisal_weight') : (($appraisalSetup) ? $appraisalSetup->manager_appraisal_weight : '') }}" placeholder="Ranking Limit">
                                        <div class="input-group-addon">
                                            <i class="fa fa-percent"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('employee_appraisal_weight') ? ' has-error' : '' }}">
                                <label for="employee_appraisal_weight" class="col-sm-3 control-label">Employee Appraisal Weight</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="employee_appraisal_weight" name="employee_appraisal_weight" value="{{ (old('employee_appraisal_weight')) ? old('employee_appraisal_weight') : (($appraisalSetup) ? $appraisalSetup->employee_appraisal_weight : '') }}" placeholder="Employee Appraisal Weight">
                                        <div class="input-group-addon">
                                            <i class="fa fa-percent"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group three-sixty-field{{ $errors->has('colleagues_appraisal_weight') ? ' has-error' : '' }}">
                                <label for="colleagues_appraisal_weight" class="col-sm-3 control-label">Colleagues Appraisal Weight</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="colleagues_appraisal_weight" name="colleagues_appraisal_weight" value="{{ (old('colleagues_appraisal_weight')) ? old('colleagues_appraisal_weight') : (($appraisalSetup) ? $appraisalSetup->colleagues_appraisal_weight : '') }}" placeholder="Colleagues Appraisal Weight">
                                        <div class="input-group-addon">
                                            <i class="fa fa-percent"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('total_appraisal_weight') ? ' has-error' : '' }}">
                                <label for="total_appraisal_weight" class="col-sm-3 control-label">Total Appraisal Weight</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="total_appraisal_weight" name="total_appraisal_weight" value="{{ (old('total_appraisal_weight')) ? old('total_appraisal_weight') : (($appraisalSetup) ? $appraisalSetup->total_appraisal_weight : '') }}" placeholder="Total Appraisal Weight" readonly>
                                        <div class="input-group-addon">
                                            <i class="fa fa-percent"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-floppy-o"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Include modal -->
        @if(Session('changes_saved'))
            @include('contacts.partials.success_action', ['modal_title' => "Company Identity Updated!", 'modal_content' => session('changes_saved')])
        @endif
    </div>
@endsection

@section('page_script')
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

    <script>
        $(function () {
            //Cancel button click event
            $('#back_button').click(function () {
                location.href = '/';
            });
            //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
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
            @if(Session('changes_saved'))
                $('#success-action-modal').modal('show');
            @endif

            //call hide/show fields functions on doc ready
            hideFields();

            //show/hide file upload or manual fields on radio checked
            $('#rdo_ts_on, #rdo_ts_off').on('ifChecked', function(){
                hideFields();
            });

            //calculate the total weight on key press
            $('#manager_appraisal_weight, #employee_appraisal_weight, #colleagues_appraisal_weight').keyup(function () {
                totalWeight();
            });
        });

        //function to hide/show report type fields
        function hideFields() {
            var threeSixtyStatus = $("input[name='three_sixty_status']:checked").val();
            //console.log(reportType);
            if (threeSixtyStatus == 1) { //Active
                $('.three-sixty-field').show();
            }
            else if (threeSixtyStatus == 0) { //Inactive
                $('.three-sixty-field').hide();
            }
            totalWeight();

            return threeSixtyStatus;
        }

        //function to calculate the total weight
        function totalWeight() {
            var threeSixtyStatus = $("input[name='three_sixty_status']:checked").val();
            var managerWeight = parseInt($("#manager_appraisal_weight").val());
            var empWeight = parseInt($("#employee_appraisal_weight").val());
            var colleaguesWeight = parseInt($("#colleagues_appraisal_weight").val());
            var totalWeight = 0;
            if (threeSixtyStatus == 1) { //Active
                totalWeight = managerWeight + empWeight + colleaguesWeight;
            }
            else if (threeSixtyStatus == 0) { //Inactive
                totalWeight = managerWeight + empWeight;
            }
            $("#total_appraisal_weight").val(totalWeight);

            return totalWeight;
        }
    </script>
@endsection