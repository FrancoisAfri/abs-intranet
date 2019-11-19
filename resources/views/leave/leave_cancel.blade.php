@extends('layouts.main_layout')
@section('page_dependencies')

    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12 col-md-12">
            <!-- Horizontal Form -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <i class="fa fa-user pull-right"></i>
                        <h3 class="box-title">Leave Approvals</h3>
                    </div>
                    <div class="box-body">
                        <div style="overflow-X:auto;">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
									<tr>
										<th></th>
										<th>Employee Number</th>
										<th>Employee name</th>
										<th>Leave Type</th>
										<th>Date From</th>
										<th>Date To</th>
										<th>Day(s)</th>
										<th>Notes</th>
										<th>Report To</th>
										<th>Status</th>
									</tr>
                                </thead>
                                <tbody>
									<!-- loop through the leave applications   -->
									@if(count($applicatiions) > 0)
										@foreach($applicatiions as $applicatiion)
											<tr>
												<td>
													<a href="{{ '/leave/view/applicatiion/' . $applicatiion->id}}" class="product-title">View</a></td>
												<td>{{ !empty($applicatiion->employee_number) ? $applicatiion->employee_number : '' }}</td>
												<td>{{ !empty($applicatiion->first_name) && !empty($applicatiion->surname) ? $applicatiion->first_name.' '.$applicatiion->surname : '' }}</td>
												<td>{{ !empty($applicatiion->leave_type_name) ? $applicatiion->leave_type_name : '' }}</td>
												<td>{{ !empty($applicatiion->start_date) ? date('d M Y ', $applicatiion->start_date) : '' }}</td>
												<td>{{ !empty($applicatiion->end_date) ? date(' d M Y', $applicatiion->end_date) : '' }}</td>
												<td>{{ !empty($applicatiion->leave_days) ? $applicatiion->leave_days / 8 : '' }}</td>
												<td>{{ !empty($applicatiion->notes) ? $applicatiion->notes : '' }}</td>
												<td>{{ !empty($applicatiion->manager_first_name) && !empty($applicatiion->manager_surname) ? $applicatiion->manager_first_name.' '.$applicatiion->manager_surname : '' }}</td>
												<td>{{ (!empty($applicatiion->status)) ?  $leaveStatus[$applicatiion->status] : ''}}</td>
											</tr>
										@endforeach
									@endif
                                </tbody>
                                <tfoot>
									<tr>
										<th></th>
										<th>Employee Number</th>
										<th>Employee name</th>
										<th>Leave Type</th>
										<th>Date From</th>
										<th>Date To</th>
										<th>Day(s)</th>
										<th>Notes</th>
										<th>Report To</th>
										<th>Status</th>
									</tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button id="cancel" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i> Back</button>
                </div>
                <!-- /.box-footer -->
        </div>
        <!-- /.box -->
    </div>
    <!-- Include the reject leave modal-->
    @include('leave.partials.cancel_leave')
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
		document.getElementById("cancel").onclick = function () {
            location.href = "/leave/search";
        };
        // $('#Accept').click(function () {
        //         $('form[name="leave-application-form"]').attr('action', '/leave/application/AcceptLeave');
        //   });

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
            var redirectUrl = '/leave/applicatiion';
            var successMsgTitle = 'reject reason Saved!';
            var successMsg = 'The reject reason has been Saved successfully.';
            //var formMethod = 'PATCH';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });
    </script>
@endsection