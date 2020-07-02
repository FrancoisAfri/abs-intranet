@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/select2/select2.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/green.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
    <!--  -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css"
          rel="stylesheet">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Job Card(s)</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <!-- <form class="form-horizontal" method="POST" action="/hr/document"> -->
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 10px; text-align: center;"></th>
                            <th style="width: 5px; text-align: center;">#</th>
                            <th>Vehicle Name</th>
                            <th>Registration</th>
                            <th>Job Card Date</th>
                            <th>Completion Date</th>
                            <th>Supplier</th>
                            <th>Status</th>
                            <th style="width: 5px; text-align: center;"></th>
                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        @if (count($jobcardMaintance) > 0)
                            @foreach ($jobcardMaintance as $card)
                                <tr id="categories-list">
                                    <!--  -->
                                    <td>{{ (!empty( $card->id)) ?  $card->id : ''}} </td>
                                    <td></td>
                                    <td>{{ (!empty( $card->vehicle_name)) ?  $card->vehicle_name : ''}} </td>
                                    <td>
                                        <!--   leave here  -->
                                        <button vehice="button" id="view_ribbons"
                                                class="btn {{ (!empty($card->status) && $card->status == 1) ? " btn-danger " : "btn-success " }}
                                                        btn-xs" onclick="postData({{$card->id}}, 'actdeac');"><i
                                                    class="fa {{ (!empty($card->status) && $card->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($card->status) && $vehice->status == 1) ? "De-Activate" : "Activate"}}
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-xs" data-toggle="modal"
                                                data-target="#delete-contact-warning-modal"><i class="fa fa-trash"></i>
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr id="categories-list">
                                <td colspan="5">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        No FleetType to display, please start by adding a new FleetType..
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                    <!--   </div> -->
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal"
                                data-target="#add-maintenance-modal">Add New Job Card
                        </button>
                    </div>
                </div>
            </div>
            <!-- Include add new prime rate modal -->
            @include('Vehicles.JobcardManagement.partials.add_jobcard_modal')
        </div>
        <!-- end section -->
    @endsection

    @section('page_script')
        <!-- Select2 -->
            <script src="/custom_components/js/modal_ajax_submit.js"></script>
            <!-- Select2 -->
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <!-- bootstrap datepicker -->
            <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
            <!-- iCheck -->
            <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
            <script src="/custom_components/js/modal_ajax_submit.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
            <!-- iCheck -->
            <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
            <script src="/custom_components/js/modal_ajax_submit.js"></script>
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

                    //Date picker
                    $('.datepicker').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });

                    $('#due_time').datetimepicker({
                        format: 'HH:mm:ss'
                    });
                    $('#time_to').datetimepicker({
                        format: 'HH:mm:ss'
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
                    //hideSemesterRow();
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

                //

                //function to populate the year drop down

            </script>
@endsection