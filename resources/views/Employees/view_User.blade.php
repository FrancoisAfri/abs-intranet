@extends('layouts.main_layout')
@section('page_dependencies')

    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap_fileinput/css/fileinput.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/AdminLTE/plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/AdminLTE/plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/AdminLTE/plugins/iCheck/square/green.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fine-uploader/fine-uploader-gallery.css') }}">
    <script src="/custom_components/js/deleteAlert.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">

    <!-- bootstrap file input -->
@stop
@section('content')

    <section class="content">
        <div class="row">
            <div class="col-md-12">

                <h1>
                </h1>
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="" data-toggle="tooltip" title="information"><a
                                    href="#information" data-toggle="tab">Personal Info</a>
                        </li>
                        <li class="" data-toggle="tooltip" title="company_info"><a href="#company_info"
                                                                                   data-toggle="tab">Work
                                Details</a>
                        </li>
                        <li class="" data-toggle="tooltip" title="Tasks"><a href="#Tasks"
                                                                            data-toggle="tab">Tasks</a>
                        </li>
                        <li class="" data-toggle="tooltip" title="Video"><a href="#Video"
                                                                            data-toggle="tab">Video</a>
                        </li>
                        <li class="" data-toggle="tooltip" title="drive"><a href="#drive"
                                                                            data-toggle="tab">Drive</a>
                        </li>
                        <li class="" data-toggle="tooltip" title="leave"><a href="#leave"
                                                                            data-toggle="tab">Leave</a>
                        </li>
                        <li class="" data-toggle="tooltip" title="assets"><a href="#assets"
                                                                             data-toggle="tab">My assets</a>
                        </li>
{{--                        <li class="" data-toggle="tooltip" title="licence"><a href="#licence"--}}
{{--                                                                              data-toggle="tab">My Licences</a>--}}
{{--                        </li>--}}

                        <li class=" pull-right">
                            <button type="button" class="btn btn-default pull-right" id="back_button"><i
                                        class="fa fa-arrow-left"></i> Back
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="information">
                            @include('Employees.Tabs.information-tab')
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="company_info">
                            @include('Employees.Tabs.company_info-tab')
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="Tasks">
                            @include('Employees.Tabs.tasks-tab')
                        </div>
                        <div class="tab-pane" id="Video">
                            @include('Employees.Tabs.videos-tab')
                        </div>
                        <div class="tab-pane" id="leave">
                            @include('Employees.Tabs.leave-tab')
                        </div>
{{--                        <div class="tab-pane" id="licence">--}}
{{--                            @include('Employees.Tabs.licences-tab')--}}
{{--                        </div>--}}
{{--                        licences-tab.blade--}}

                        <div class="tab-pane" id="assets">
                            @include('Employees.Tabs.asets-tab')
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="drive">
                            @include('Employees.Tabs.documents-tab')
                        </div>



                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>

@stop
@section('page_script')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('custom_components/js/modal_ajax_submit.js') }}"></script>
    <script src="{{ asset('custom_components/js/deleteAlert.js') }}"></script>
    <!-- the main fileinput plugin file -->
    <script src="{{ asset('bower_components/bootstrap_fileinput/js/fileinput.min.js') }}"></script>
    <script src="{{ asset('bower_components/AdminLTE/plugins/iCheck/icheck.min.js')}}"></script>
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('plugins/fine-uploader/fine-uploader.js') }}"></script>
    <script src="/custom_components/js/deleteAlert.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <!-- Task timer -->
    <script src="/custom_components/js/tasktimer.js"></script>
    <!-- Ajax form submit -->
    <script src="{{asset('custom_components/js/modal_ajax_submit.js')}}"></script>
    <!-- Ajax dropdown options load -->
    <script src="{{ asset('custom_components/js/load_dropdown_options.js') }}"></script>

    <script src="{{ asset('custom_components/js/dataTable.js') }}"></script>

    <script>
        $(function () {

            $(".select2").select2();
            $('[data-toggle="tooltip"]').tooltip();
            $('#user_profile').click(function () {
                if ({{$modAccess}} > 3)
                    location.href = '{{ route('user.edit',$employee->user_id ) }} ';
                else location.href = '{{ route('profile') }} ';

            });
            //back
            $('#back_button').click(function () {
                location.href = '{{route('employee.index')}}';
            });
            $('#Apply').click(function () {
                location.href = '/leave/application';
            });

            //back
            /*$('#user_profile').click(function () {
                location.href = '{{ route('user.edit',$employee->id ) }} ';

            });
            $('#back_button').click(function () {
                location.href = '{{ route('employee.index') }} ';

            });*/

            //Load divisions drop down
            $('table.files').DataTable({
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: true,
            });

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });
            // date$(document).ready(function () {

            $('#date_from').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });

            //});

            $('#exp_date').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });

            document.getElementById("notes").placeholder = "Enter Task Note or Summary";
            //Post end task form to server using ajax (add)
            var taskID;
            var employeeID;
            var uploadRequired;
            $('#end-task-modal').on('show.bs.modal', function (e) {
                var btnEnd = $(e.relatedTarget);
                taskID = btnEnd.data('task_id');
                employeeID = btnEnd.data('employee_id');
                uploadRequired = btnEnd.data('upload_required');
                var modal = $(this);
                modal.find('#task_id').val(taskID);
                modal.find('#employee_id').val(employeeID);
                modal.find('#upload_required').val(uploadRequired);
            });

            $('#end-task').on('click', function () {
                endTask(taskID, "{{$routeUser}}");
                /*
                var strUrl = '/task/end';
                var formName = 'end-task-form';
                var modalID = 'end-task-modal';
                var submitBtnID = 'end-task';
                var redirectUrl = '/';
                var successMsgTitle = 'Task Ended!';
                var successMsg = 'Task has been Successfully ended!';

                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                */
            });

            $('#close-task-modal').on('show.bs.modal', function (e) {
                var btnEnd = $(e.relatedTarget);
                taskID = btnEnd.data('task_id');
                var modal = $(this);
                modal.find('#task_id').val(taskID);
            });

            $('#close-task').on('click', function () {
                var strUrl = '/task/check';
                var formName = 'close-task-form';
                var modalID = 'close-task-modal';
                var submitBtnID = 'close-task';
                var redirectUrl = '/';
                var successMsgTitle = 'Task Checked!';
                var successMsg = 'Task has been Successfully checked!';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            //Launch counter for running tasks
            @foreach($tasks as $task)
            increment({{ $task->task_id }});
            @endforeach

            //leave status (widget)
            var LeaveStatus = $('#leave-status-list');

            //leave cancellation reason form on show
            var cancelApplicationModal = $('#cancel-leave-application-modal');
            var leaveApplicationID;
            cancelApplicationModal.on('show.bs.modal', function (e) {
                //console.log('gets here');
                var btnCancel = $(e.relatedTarget);
                leaveApplicationID = btnCancel.data('leave_application_id');
                //var modal = $(this);
                //modal.find('#task_id').val(taskID);
            });

            //perform leave application cancellation
            cancelApplicationModal.find('#cancel-leave-application').on('click', function () {
                var strUrl = '/leave/application/' + leaveApplicationID + '/cancel';
                var formName = 'cancel-leave-application-form';
                var modalID = 'cancel-leave-application-modal';
                var submitBtnID = 'cancel-leave-application';
                var redirectUrl = '{{$routeUser}}';
                var successMsgTitle = 'Leave Application Cancelled!';
                var successMsg = 'Your leave application has been cancelled!';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
            //Post perk form to server using ajax (add)
            $('#add_document').on('click', function () {
                var strUrl = '/employee/add_employeedocument';
                var formName = 'add-document-form';
                var modalID = 'add-document-modal';
                var submitBtnID = 'add_document';
                var redirectUrl = '{{$routeUser}}';
                var successMsgTitle = 'New Document Added!';
                var successMsg = 'Document Details has been updated successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            var docID;
            $('#edit-newdoc-modal').on('shown.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                docID = btnEdit.data('id');
                var name = btnEdit.data('name');
                var doc_description = btnEdit.data('doc_description');
                var doc_type = btnEdit.data('doc_type_id');
                var date_from = btnEdit.data('date_from');
                var expirydate = btnEdit.data('expirydate');
                var modal = $(this);
                modal.find('#description_update').val(doc_description);
                modal.find('#doc_type_update').val(doc_type);
                modal.find('#date_from_update').val(date_from);
                modal.find('#expirydate').val(expirydate);
            });

            $('#edit_doc').on('click', function () {
                var strUrl = '/employee/edit_doc/' + docID;
                var formName = 'edit-newdoc-form';
                var modalID = 'edit-newdoc-modal';
                var submitBtnID = 'edit_doc';
                var redirectUrl = '{{$routeUser}}';
                var successMsgTitle = 'Document Details have been updated!';
                var successMsg = 'The Documents Details has been updated successfully.';
                var Method = 'PATCH';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
        });
    </script>
@stop
