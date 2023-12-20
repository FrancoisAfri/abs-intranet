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
                        <h3 class="box-title">Search Results</h3>
                    </div>
                    <div class="box-body">
                        <div style="overflow-X:auto;">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
									<tr>
										<th></th>
										<th>Employee</th>
										<th>Type</th>
										<th>Date Applied</th>
										<th>Amount</th>
										<th>Repayment Month(s)</th>
										<th nowrap>Notes</th>
										<th>Status</th>
									</tr>
                                </thead>
                                <tbody>
									@if(count($loans) > 0)
										@foreach($loans as $loan)
											<tr>
												<td><a href="{{ '/loan/view-ind/' . $loan->id}}">View</a></td>
												<td>{{ !empty($loan->users->first_name) && !empty($loan->users->surname) ? $loan->users->first_name.' '.$loan->users->surname : '' }}</td>
												<td>{{ ((!empty($loan->type)) && $loan->type == 1)  ?  'Advance' : 'Loan'}} </td>
												<td>{{ !empty($loan->created_at) ? $loan->created_at : '' }}</td>
												<td>{{ !empty($loan->amount) ? 'R ' .number_format($loan->amount, 2) : '' }}</td>
												<td style="width: 10px; text-align: center;">{{ (!empty( $loan->repayment_month)) ?  $loan->repayment_month : ''}} </td>
												<td nowrap>{{ !empty($loan->reason) ? $loan->reason : '' }}</td>
												<td>{{ (!empty( $loan->status)) ?  $statuses[$loan->status] : ''}} </td>
											</tr>
										@endforeach
									@endif
                                </tbody>
                                <tfoot>
									<tr>
										<th></th>
										<th>Employee</th>
										<th>Type</th>
										<th>Date Applied</th>
										<th>Amount</th>
										<th>Repayment Month(s)</th>
										<th nowrap>Notes</th>
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
            location.href = "/loan/search";
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