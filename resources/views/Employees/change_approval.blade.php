@extends('layouts.main_layout')
@section('page_dependencies')

    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12 col-md-12">
            <!-- Horizontal Form -->
            <form class="form-horizontal" method="get" action="/leave/approval">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <i class="fa fa-user pull-right"></i>
                        <h3 class="box-title">Changes</h3>
                    </div>
                    <div class="box-body">
                        <div style="overflow-X:auto;">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>First Name</th>
                                    <th>Surname</th>
                                    <th>Employee Number</th>
                                    <th>Email</th>
                                    <th>Cell Phone</th>
                                </tr>
                                </thead>
                                <tbody>
                                <!-- loop through the leave applications   -->
                                @if(count($changes) > 0)
                                    @foreach($changes as $change)
                                        <tr>
                                            <td><button type="button" id="view"
                                                         class="btn btn-success btn-xs btn-detail open-modal"
                                                         value="{{$change->id}}"
                                                         onclick="postData({{$change->id}}, 'view_id')">View
                                                </button></td>
                                            <td>{{ !empty($change->first_name) ? $change->first_name : '' }}</td>
                                            <td>{{ !empty($change->surname) ? $change->surname : '' }}</td>
                                            <td>{{ !empty($change->employee_number) ? $change->employee_number : '' }}</td>
                                            <td>{{ !empty($change->email) ? $change->email : '' }}</td>
                                            <td>{{ !empty($change->cell_number) ? $change->cell_number : '' }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>First Name</th>
                                    <th>Surname</th>
                                    <th>Employee Number</th>
                                    <th>Email</th>
                                    <th>Cell Phone</th>
                                    <th>Action</th>
                                    <th></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                </div>
                <!-- /.box-footer -->
            </form>
        </div>
        <!-- /.box -->
    </div>
    <!-- Include the reject leave modal-->
    @include('leave.partials.reject_leave')
    <!--  -->
    @if(Session('success_application'))
        @include('leave.partials.success_action', ['modal_title' => "Application Successful!", 'modal_content' => session('success_application')])
    @endif
    <!--  -->
    </div>
@endsection
@section('page_script')
    <!-- DataTables -->
    <script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <!-- End Bootstrap File input -->
    <script type="text/javascript">
        $(function () {
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true
            });
        });
        //       document.getElementById("postData").onclick = function () {
        //     // location.href = "leave/application/AcceptLeave";
        //     alert("I am an alert box!");
        // };
        // post data
        function postData(id, data) {
            if (data == 'approval_id') location.href = "/employee/approval_changes/" + id;
            else if (data == 'view_id') location.href = "/employee/view_changes/" + id;
        }

        function reject(id, data) {
            if (data == 'reject_id') location.href = "/employee/reject_changes/" + id;
        }

        //Vertically center modals on pag
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
        var reject_ID;
        $('#reject-leave-modal').on('show.bs.modal', function (e) {
            var btnEdit = $(e.relatedTarget);
            reject_ID = btnEdit.data('id');
            // var name = btnEdit.data('name');
            var description = btnEdit.data('description');
            var modal = $(this);
            // modal.find('#name').val(name);
            modal.find('#description').val(description);
        });
        //Post module form to server using ajax (ADD)
        $('#rejection-reason').on('click', function () {
            //console.log('strUrl');
            var strUrl = '/leave/reject/' + reject_ID;
            var modalID = 'reject-leave-modal';
            var objData = {
                // name: $('#'+modalID).find('#name').val(),
                description: $('#' + modalID).find('#description').val(),
                _token: $('#' + modalID).find('input[name=_token]').val()
            };
            var submitBtnID = 'reject_leave';
            var redirectUrl = '/leave/approval';
            var successMsgTitle = 'reject reason Saved!';
            var successMsg = 'The reject reason has been Saved successfully.';
            //var formMethod = 'PATCH';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });
    </script>
@endsection