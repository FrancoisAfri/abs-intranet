@extends('layouts.main_layout')
@section('page_dependencies')

    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12 col-md-12">
            <!-- Horizontal Form -->
            <form class="form-horizontal" method="get" action="">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <i class="fa fa-user pull-right"></i>
                        <h3 class="box-title">
							<span style="color:red;" size="40"> Please Note that complaints must be closed within 72 Hrs.</span></h3>
                    </div>
                    <div class="box-body">
                        <div style="overflow-X:auto;">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
									<tr>
										<th>Date Added</th>
										<th>Date</th>
										<th>Office</th>
										<th>Company</th>
										<th>Traveller</th>
										<th>Supplier</th>
										<th>Type</th>
										<th>Employee</th>
										<th>Summary</th>
										<th>Status</th>
										<th>Report To</th>
										<th></th>
									</tr>
                                </thead>
                                <tbody>
									<!-- loop through the leave applications   -->
									@if(count($complaints) > 0)
										@foreach($complaints as $complaint)
											<tr>
												<td>{{ !empty($complaint->created_at) ? $complaint->created_at : '' }}</td>
												<td>{{ !empty($complaint->date_complaint_compliment) ? date(' d M Y', $complaint->date_complaint_compliment) : '' }}</td>
												<td>{{ !empty($complaint->office) ? $complaint->office : '' }}</td>
												<td>{{ !empty($complaint->com_name) ? $complaint->com_name : '' }}</td>
												<td>{{ !empty($complaint->con_name) && !empty($complaint->con_surname) ? $complaint->con_name.' '.$complaint->con_surname : '' }}</td>
												<td>{{ !empty($complaint->supplier) ? $complaint->supplier : '' }}</td>
												<td>{{ !empty($complaint->type) && ($complaint->type == 1) ? 'Complaints': 'Compliments' }}</td>
												<td>{{ !empty($complaint->firstname) && !empty($complaint->surname) ? $complaint->firstname.' '.$complaint->surname : '' }}</td>
												<td>{{ !empty($complaint->summary_complaint_compliment) ? $complaint->summary_complaint_compliment : '' }}</td>
												<td>{{ (!empty($complaint->status)) ?  'Open' : ''}}</td>
												<td>{{ !empty($complaint->mg_firstname) && !empty($complaint->mg_surname) ? $complaint->mg_firstname.' '.$complaint->mg_surname : '' }}</td>
												<td>
													@if ($complaint->type == 1)
														<button type="button" id="close-complaint" class="btn btn-danger btn-xs"
														data-toggle="modal" data-target="#close-complaints-modal"
														data-id="{{ $complaint->id }}">Close</button></td>
													@endif
											</tr>
										@endforeach
									@endif
                                </tbody>
                                <tfoot>
									<tr>
										<th>Date Added</th>
										<th>Date</th>
										<th>Office</th>
										<th>Company</th>
										<th>Traveller</th>
										<th>Supplier</th>
										<th>Type</th>
										<th>Employee</th>
										<th>Summary</th>
										<th>Status</th>
										<th>Report To</th>
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
                        <h3 class="box-title">Subordinates Complaints / Compliments</h3>
                    </div>
                    <div class="box-body">
                        <div style="overflow-X:auto;">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
									<tr>
										<th>Date Added</th>
										<th>Date</th>
										<th>Office</th>
										<th>Company</th>
										<th>Traveller</th>
										<th>Supplier</th>
										<th>Type</th>
										<th>Employee</th>
										<th>Summary</th>
										<th>Status</th>
										<th>Report To</th>
										<th></th>
									</tr>
                                </thead>
                                <tbody>
									<!-- loop through the leave applications   -->
									@if(count($subComplaints) > 0)
										@foreach($subComplaints as $subComplaint)
											<tr>
												<td>{{ !empty($subComplaint->created_at) ? $subComplaint->created_at : '' }}</td>
												<td>{{ !empty($subComplaint->date_complaint_compliment) ? date(' d M Y', $subComplaint->date_complaint_compliment) : '' }}</td>
												<td>{{ !empty($subComplaint->office) ? $subComplaint->office : '' }}</td>
												<td>{{ !empty($subComplaint->com_name) ? $subComplaint->com_name : '' }}</td>
												<td>{{ !empty($subComplaint->con_name) && !empty($subComplaint->con_surname) ? $subComplaint->con_name.' '.$subComplaint->con_surname : '' }}</td>
												<td>{{ !empty($subComplaint->supplier) ? $subComplaint->supplier : '' }}</td>
												<td>{{ !empty($subComplaint->type) && ($subComplaint->type == 1) ? 'Complaints': 'Compliments' }}</td>
												<td>{{ !empty($subComplaint->firstname) && !empty($subComplaint->surname) ? $subComplaint->firstname.' '.$subComplaint->surname : '' }}</td>
												<td>{{ !empty($subComplaint->summary_complaint_compliment) ? $subComplaint->summary_complaint_compliment : '' }}</td>
												<td>{{ (!empty($subComplaint->status)) ?  'Open' : ''}}</td>
												<td>{{ !empty($subComplaint->mg_firstname) && !empty($subComplaint->mg_surname) ? $subComplaint->mg_firstname.' '.$subComplaint->mg_surname : '' }}</td>
												<td>
													@if ($complaint->type == 1)
													<button type="button" id="close-complaint" class="btn btn-danger btn-xs"
														data-toggle="modal" data-target="#close-complaints-modal"
														data-id="{{ $subComplaint->id }}">Close</button></td>
													@endif
											</tr>
										@endforeach
									@endif
                                </tbody>
                                <tfoot>
									<tr>
										<th>Date Added</th>
										<th>Date</th>
										<th>Office</th>
										<th>Company</th>
										<th>Traveller</th>
										<th>Supplier</th>
										<th>Type</th>
										<th>Employee</th>
										<th>Summary</th>
										<th>Status</th>
										<th>Report To</th>
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
    @include('complaints.partials.close_complaints')
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
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
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
            if (data == 'approval_id') location.href = "/leave/subComplaint/" + id;
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
        var close_ID;
        $('#close-complaints-modal').on('show.bs.modal', function (e) {
            var btnEdit = $(e.relatedTarget);
            close_ID = btnEdit.data('id');
            var modal = $(this);
        });
        //Post module form to server using ajax (ADD)
		$('#close_complaint').on('click', function () {
			var strUrl = '/complaint/close/' + close_ID;
			var formName = 'close-complaints-form';
			var modalID = 'close-complaints-modal';
			var submitBtnID = 'close_complaint';
			var redirectUrl = '/complaints/queue';
			var successMsgTitle = 'Complaint Closed!';
			var successMsg = 'The complaint have been successfully closed.';
			modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
		});
    </script>
@endsection