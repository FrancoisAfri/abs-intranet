@extends('layouts.main_layout')
@section('page_dependencies')

    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12 col-md-12">
            <!-- Horizontal Form -->
            <form class="form-horizontal" method="get" action="/loan/status-changed">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <i class="fa fa-user pull-right"></i>
                        <h3 class="box-title">Loan Approvals</h3>
                    </div>
                    <div class="box-body">
                        <div style="overflow-X:auto;">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
									<tr>
										<th>Employee</th>
										<th>Type</th>
										<th>Date Applied</th>
										<th>Amount</th>
										<th>Repayment Month(s)</th>
										<th nowrap>Notes</th>
										<th nowrap>Supporting Documents</th>
										<th>Status</th>
										<th>Approve</th>
										<th>Reject</th>
									</tr>
                                </thead>
                                <tbody>
									<!-- loop through the leave applications   -->
									@if(count($loans) > 0)
										@foreach($loans as $loan)
											<tr>
												<td>{{ !empty($loan->users->first_name) && !empty($loan->users->surname) ? $loan->users->first_name.' '.$loan->users->surname : '' }}</td>
												<td>{{ ((!empty($loan->type)) && $loan->type == 1)  ?  'Advance' : 'Loan'}} </td>
												<td>{{ !empty($loan->created_at) ? $loan->created_at : '' }}</td>
												<td>{{ !empty($loan->amount) ? 'R ' .number_format($loan->amount, 2) : '' }}</td>
												<td style="width: 10px; text-align: center;">{{ (!empty( $loan->repayment_month)) ?  $loan->repayment_month : ''}} </td>
												<td nowrap>{{ !empty($loan->reason) ? $loan->reason : '' }}</td>
												<td nowrap>
													<div class="form-group{{ $errors->has('document') ? ' has-error' : '' }}">
														<label for="document" class="control-label"></label>
														@if(!empty($loan->loanDocs))
															@foreach ($loan->loanDocs as $doc)
															<a class="btn btn-default btn-flat btn-block pull-right btn-xs"
															   href="{{ Storage::disk('local')->url("$directory/$doc->supporting_docs") }}"
															   target="_blank"><i class="fa fa-file-pdf-o"></i> {{$doc->doc_name}}</a>
															@endforeach
														@else
															<a class="btn btn-default pull-centre btn-xs"><i class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
														@endif
													</div>	 
												</td>
												<td>{{ (!empty( $loan->status)) ?  $statuses[$loan->status] : ''}} </td>
												<td>
													<button type="button" id="Accept"
															class="btn btn-success btn-xs btn-detail open-modal"
															value="{{$loan->id}}"
															onclick="postData({{$loan->id}}, 'approval_id')">Approve
													</button>

												</td>
												<td>
													<button type="button" id="reject-reason" class="btn btn-danger btn-xs"
														data-toggle="modal" data-target="#reject-loan-modal"
														data-id="{{ $loan->id }}">Reject</button></td>
											</tr>
										@endforeach
									@endif
                                </tbody>
                                <tfoot>
									<tr>
										<th>Employee</th>
										<th>Type</th>
										<th>Date Applied</th>
										<th>Amount</th>
										<th>Repayment Month(s)</th>
										<th>Notes</th>
										<th>Status</th>
										<th>Approve</th>
										<th>Reject</th>
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
    @include('loan.partials.reject_loan')
    <!--  -->
    @if(Session('success_application'))
        @include('loan.partials.success_action', ['modal_title' => "Application Successful!", 'modal_content' => session('success_application')])
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

        // post data
        function postData(id, data) {
            if (data == 'approval_id') location.href = "/loan/accepted/" + id;
        }

        function reject(id, data) {
            if (data == 'reject_id') location.href = "/loan/reject/" + id;
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
        $('#reject-loan-modal').on('show.bs.modal', function (e) {
            var btnEdit = $(e.relatedTarget);
            reject_ID = btnEdit.data('id');
            var reason = btnEdit.data('reason');
            var modal = $(this);
            // modal.find('#name').val(name);
            modal.find('#reason').val(reason);
        });
        //Post module form to server using ajax (ADD)
        $('#rejection-reason').on('click', function () {
            //console.log('strUrl');
            var strUrl = '/loan/reject/' + reject_ID;
            var modalID = 'reject-loan-modal';
            var objData = {
                reason: $('#' + modalID).find('#reason').val(),
                _token: $('#' + modalID).find('input[name=_token]').val()
            };
            var submitBtnID = 'rejection-reason';
            var redirectUrl = '/loan/approval';
            var successMsgTitle = 'rejection reason Saved!';
            var successMsg = 'The rejection reason has been Saved successfully.';
            //var formMethod = 'PATCH';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });
    </script>
@endsection