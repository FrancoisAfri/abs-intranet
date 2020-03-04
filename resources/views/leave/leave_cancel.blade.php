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
									@if(count($applications) > 0)
										@foreach($applications as $application)
											<tr>
												<td>
													<a href="{{ '/leave/view/application/' . $application->id}}" class="product-title">View</a></td>
												<td>{{ !empty($application->employee_number) ? $application->employee_number : '' }}</td>
												<td>{{ !empty($application->first_name) && !empty($application->surname) ? $application->first_name.' '.$application->surname : '' }}</td>
												<td>{{ !empty($application->leave_type_name) ? $application->leave_type_name : '' }}</td>
												<td>{{ !empty($application->start_date) ? date('d M Y ', $application->start_date) : '' }}</td>
												<td>{{ !empty($application->end_date) ? date(' d M Y', $application->end_date) : '' }}</td>
												<td>{{ !empty($application->leave_taken) ? $application->leave_taken / 8 : '' }}</td>
												<td>{{ !empty($application->notes) ? $application->notes : '' }}</td>
												<td>{{ !empty($application->manager_first_name) && !empty($application->manager_surname) ? $application->manager_first_name.' '.$application->manager_surname : '' }}</td>
												<td>{{ (!empty($application->status)) ?  $leaveStatus[$application->status] : ''}}</td>
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
            var redirectUrl = '/leave/application';
            var successMsgTitle = 'reject reason Saved!';
            var successMsg = 'The reject reason has been Saved successfully.';
            //var formMethod = 'PATCH';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });
    </script>
@endsection