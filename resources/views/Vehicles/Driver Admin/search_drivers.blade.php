@extends('layouts.main_layout')

@section('page_dependencies')
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- Include Date Range Picker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
@endsection

@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                </div>
                <form class="form-horizontal" method="POST" action="/vehicle_management/driver_search">
                    {{ csrf_field() }}

                    <div class="box-body">
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
                        <div class="col-md-8 col-md-offset-2">
                            <div>
                                <div class="box-header with-border" align="center">
                                    <h3 class="box-title">Search for an Employee</h3>
                                </div>
                                <div class="box-body" id="view_users">

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
                                                            class="form-control"
                                                            onchange="divDDOnChange(this, null, 'view_users')">
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="form-group">
                                        <label for="employee" class="col-sm-2 control-label">Employee</label>

                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <input type="text" class="form-control" id="employee" name="employee"
                                                       value="" placeholder="employee" required>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                                        <label for="status" class="col-sm-2 control-label"> Status
                                        </label>

                                        <div class="col-sm-10">
                                            <label class="radio-inline" style="padding-left: 0px;"><input type="radio"
                                                                                                          id="rdo_package"
                                                                                                          name="status"
                                                                                                          value="1"
                                                                                                          checked>
                                                Active
                                            </label>
                                            <label class="radio-inline"><input type="radio" id="rdo_product"
                                                                               name="status" value="2"> Inactive
                                            </label>
                                            <label class="radio-inline"><input type="radio" id="rdo_products"
                                                                               name="status" value=""> All
                                            </label>

                                        </div>
                                    </div>


                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary pull-right"><i
                                                    class="fa fa-search"></i> Search
                                        </button>
                                        <!-- <button type="button" id="cat_module" class="btn btn-primary pull-right"
                                                data-toggle="modal" data-target="#add-fleetcard-modal"><i
                                                    class="fa fa-plus-square-o"></i> Add Fleet Card
                                        </button> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                </form>
            </div>
            <!-- /.box -->
            @include('Vehicles.Fleet_cards.add_vehiclefleetcard_modal')
        </div>
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

    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
            type="text/javascript"></script>
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->

    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>

    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <!-- time picker -->

    <script type="text/javascript">
        $(function () {
            $(".select2").select2();
            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();

            //Vertically center modals on page

            //Phone mask
            $("[data-mask]").inputmask();

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

        //Initialize iCheck/iRadio Elements
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '10%' // optional
        });

        $(document).ready(function () {

            $('#issued_date').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });


        });

        $('#expiry_date').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true
        });

        //Load divisions drop down
        var parentDDID = '';
        var loadAllDivs = 1;
        @if (isset($view_by_admin) && $view_by_admin === 1)
        @foreach($division_levels as $division_level)
        //Populate drop down on page load
        var ddID = '{{ 'division_level_' . $division_level->level }}';
        var postTo = '{!! route('divisionsdropdown') !!}';
        var selectedOption = '';
        var divLevel = parseInt('{{ $division_level->level }}');
        if (divLevel == 5) selectedOption = '{{ $user->person->division_level_5 }}';
        else if (divLevel == 4) selectedOption = '{{ $user->person->division_level_4 }}';
        else if (divLevel == 3) selectedOption = '{{ $user->person->division_level_3 }}';
        else if (divLevel == 2) selectedOption = '{{ $user->person->division_level_2 }}';
        else if (divLevel == 1) selectedOption = '{{ $user->person->division_level_1 }}';
        var incInactive = -1;
        var loadAll = loadAllDivs;
        loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo);
        parentDDID = ddID;
        loadAllDivs = -1;
        @endforeach
        @endif


    </script>
@endsection
