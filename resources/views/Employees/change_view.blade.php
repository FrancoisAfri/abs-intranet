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
                        <h3 class="box-title">Differences Between Records</h3>
                    </div>
                    <div class="box-body">
                        <div style="overflow-X:auto;">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Field</th>
                                        <th>Original Value</th>
                                        <th>Changed Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <!-- loop through the leave applications   -->
                                @if(count($differences) > 0)
                                    @foreach($differences as $difference)
                                        <tr>
                                            <td>{{ $difference['field'] }}</td>
                                            <td>{{ $difference['original'] }}</td>
                                            <td>{{ $difference['changed'] }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Field</th>
                                        <th>Original Value</th>
                                        <th>Changed Value</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer d-flex justify-content-between">
                    <button type="button" id="Accept"
                            class="btn btn-success btn-detail open-modal"
                            value="{{$change}}"
                            onclick="postData({{$change}}, 'approval_id')">
                        Approve
                    </button>
                    <button type="button" id="reject-reason" class="btn btn-danger"
                            data-toggle="modal" data-target="#reject-leave-modal"
                            data-id="{{ $change }}">
                        Reject
                    </button>
                </div>
                <!-- /.box-footer -->
            </form>
        </div>
        <!-- /.box -->
    </div>
    <!-- Include the reject leave modal-->
    @include('Employees.partials.reject_changes')
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
            var strUrl = '/employee/reject-changes/' + reject_ID;
            var modalID = 'reject-leave-modal';
            var objData = {
                // name: $('#'+modalID).find('#name').val(),
                description: $('#' + modalID).find('#description').val(),
                _token: $('#' + modalID).find('input[name=_token]').val()
            };
            var submitBtnID = 'reject_leave';
            var redirectUrl = '/employee/change_approval';
            var successMsgTitle = 'reject reason Saved!';
            var successMsg = 'The reject reason has been sent successfully.';
            //var formMethod = 'PATCH';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });
    </script>
@endsection