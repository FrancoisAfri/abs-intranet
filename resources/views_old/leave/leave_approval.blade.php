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
                        <h3 class="box-title">Leave Approvals</h3>
                    </div>
                    <div class="box-body">
                        <div style="overflow-X:auto;">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
									<tr>
										<th>Employee name</th>
										<th>Leave Type</th>
										<th>Date From</th>
										<th>Date To</th>
										<th>Date Applied</th>
										<th>Day(s) Requested</th>
										<th>Notes</th>
										<th>Supporting Documents</th>
										<th>Status</th>
										<th>Reports To</th>
										<th>Action</th>
										<th></th>
									</tr>
                                </thead>
                                <tbody>
									<!-- loop through the leave applications   -->
									@if(count($leaveApplications) > 0)
										@foreach($leaveApplications as $approval)
											<tr>
												<td>{{ !empty($approval->firstname) && !empty($approval->surname) ? $approval->firstname.' '.$approval->surname : '' }}</td>
												<td>{{ !empty($approval->leavetype) ? $approval->leavetype : '' }}</td>
												<td>{{ !empty($approval->start_date) ? date('d M Y ', $approval->start_date) : '' }}</td>
												<td>{{ !empty($approval->end_date) ? date(' d M Y', $approval->end_date) : '' }}</td>
												<td>{{ !empty($approval->created_at) ? $approval->created_at : '' }}</td>
												<td>{{ !empty($approval->leave_taken) ? $approval->leave_taken / 8 : '' }}</td>
												<td>{{ !empty($approval->notes) ? $approval->notes : '' }}</td>
												<td>
													<div class="form-group{{ $errors->has('supporting_doc') ? ' has-error' : '' }}">
														<label for="supporting_doc" class="control-label"></label>
														@if(!empty($approval->supporting_docs))
															<a class="btn btn-default btn-flat btn-block pull-right btn-xs"
															   href="{{ Storage::disk('local')->url("Leave/LeaveDocuments/$approval->supporting_docs") }}" target="_blank"><i
																		class="fa fa-file-pdf-o"></i> View Document</a>
														@else
															<a class="btn btn-default pull-centre btn-xs"><i
																		class="fa fa-exclamation-triangle"></i> Nothing
																Uploaded</a>
														@endif
													</div>
												</td>
												<td>{{ (!empty($approval->status)) ?  $leaveStatus[$approval->status] : ''}}</td>
												<td>{{ !empty($approval->mg_firstname) && !empty($approval->mg_surname) ? $approval->mg_firstname.' '.$approval->mg_surname : '' }}</td>
												<td>
													<button type="button" id="Accept"
															class="btn btn-success btn-xs btn-detail open-modal"
															value="{{$approval->id}}"
															onclick="postData({{$approval->id}}, 'approval_id')">Accept
													</button>

												</td>
												<td>
													<button type="button" id="reject-reason" class="btn btn-danger btn-xs"
														data-toggle="modal" data-target="#reject-leave-modal"
														data-id="{{ $approval->id }}">Decline</button></td>
											</tr>
										@endforeach
									@endif
                                </tbody>
                                <tfoot>
									<tr>
										<th>Employee name</th>
										<th>Leave Type</th>
										<th>Date From</th>
										<th>Date To</th>
										<th>Date Applied</th>
										<th>Day(s) Requested</th>
										<th>Notes</th>
										<th>Supporting Documents</th>
										<th>Status</th>
										<th>Reports To</th>
										<th>Action</th>
										<th></th>
									</tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
				
                <!-- Subdinate leave -->
				<div class="box box-primary">
                    <div class="box-header with-border">
                        <i class="fa fa-user pull-right"></i>
                        <h3 class="box-title">Subordinates Leave Approvals</h3>
                    </div>
                    <div class="box-body">
                        <div style="overflow-X:auto;">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
									<tr>
										<th>Employee name</th>
										<th>Leave Type</th>
										<th>Date From</th>
										<th>Date To</th>
										<th>Date Applied</th>
										<th>Day(s) Requested</th>
										<th>Notes</th>
										<th>Supporting Documents</th>
										<th>Status</th>
										<th>Reports To</th>
										<th>Action</th>
										<th></th>
									</tr>
                                </thead>
                                <tbody>
									<!-- loop through the leave applications   -->
									@if(count($subLeaveApplications) > 0)
										@foreach($subLeaveApplications as $approval)
											<tr>
												<td>{{ !empty($approval->firstname) && !empty($approval->surname) ? $approval->firstname.' '.$approval->surname : '' }}</td>
												<td>{{ !empty($approval->leavetype) ? $approval->leavetype : '' }}</td>
												<td>{{ !empty($approval->start_date) ? date('d M Y ', $approval->start_date) : '' }}</td>
												<td>{{ !empty($approval->end_date) ? date(' d M Y', $approval->end_date) : '' }}</td>
												<td>{{ !empty($approval->created_at) ? $approval->created_at : '' }}</td>
												<td>{{ !empty($approval->leave_taken) ? $approval->leave_taken / 8 : '' }}</td>
												<td>{{ !empty($approval->notes) ? $approval->notes : '' }}</td>
												<td>
													<div class="form-group{{ $errors->has('supporting_doc') ? ' has-error' : '' }}">
														<label for="supporting_doc" class="control-label"></label>
														@if(!empty($approval->supporting_docs))
															<a class="btn btn-default btn-flat btn-block pull-right btn-xs"
															   href="{{ Storage::disk('local')->url("Leave/LeaveDocuments/$approval->supporting_docs") }}" target="_blank"><i
																		class="fa fa-file-pdf-o"></i> View Document</a>
														@else
															<a class="btn btn-default pull-centre btn-xs"><i
																		class="fa fa-exclamation-triangle"></i> Nothing
																Uploaded</a>
														@endif
													</div>
												</td>
												<td>{{ (!empty($approval->status)) ?  $leaveStatus[$approval->status] : ''}}</td>
												<td>{{ !empty($approval->mg_firstname) && !empty($approval->mg_surname) ? $approval->mg_firstname.' '.$approval->mg_surname : '' }}</td>
												<td>
													<button type="button" id="Accept"
															class="btn btn-success btn-xs btn-detail open-modal"
															value="{{$approval->id}}"
															onclick="postData({{$approval->id}}, 'approval_id')">Accept
													</button>

												</td>
												<td>
													<button type="button" id="reject-reason" class="btn btn-danger btn-xs"
														data-toggle="modal" data-target="#reject-leave-modal"
														data-id="{{ $approval->id }}">Decline</button></td>
											</tr>
										@endforeach
									@endif
                                </tbody>
                                <tfoot>
									<tr>
										<th>Employee name</th>
										<th>Leave Type</th>
										<th>Date From</th>
										<th>Date To</th>
										<th>Date Applied</th>
										<th>Day(s) Requested</th>
										<th>Notes</th>
										<th>Supporting Documents</th>
										<th>Status</th>
										<th>Reports To</th>
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
                    <button id="cancel" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i> Back</button>
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
            if (data == 'approval_id') location.href = "/leave/approval/" + id;
        }

        function reject(id, data) {
            alert(id)
            if (data == 'reject_id') location.href = "/leave/reject/" + id;
        }

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
            var redirectUrl = '/leave/approval';
            var successMsgTitle = 'reject reason Saved!';
            var successMsg = 'The reject reason has been Saved successfully.';
            //var formMethod = 'PATCH';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });
    </script>
@endsection