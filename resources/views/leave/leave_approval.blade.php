@extends('layouts.main_layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Leave Approval</h3>
                </div>
                <!-- /.box-header -->
				<form class="form-horizontal" method="POST" action="/audits/print">
                 <input type="hidden" name="action_date" value="{{!empty($action_date) ? $action_date : ''}}">
                 <input type="hidden" name="user_id" value="{{!empty($user_id) ? $user_id : ''}}">
                 <input type="hidden" name="module_name" value="{{!empty($module_name) ? $module_name : ''}}">
                 <input type="hidden" name="action" value="{{!empty($action) ? $action : ''}}">
					{{ csrf_field() }}
                <div class="box-body">
                    <!-- Collapsible section containing the amortization schedule -->
                    <div class="box-group" id="accordion">
                        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                        <div class="panel box box-primary">
                            <div class="box-body">
								<table class="table table-striped">
									<tr>
										<th>Employee name</th>
                                         <th>Leave Type</th>
                                         <th>Date From</th>
                                         <th>Date TO</th>
                                         <th>Time From</th>
                                         <th>Time To</th>
                                         <th>Notes</th>
                                         <th>Supporting Documents</th>
                                         <th>Status</th>
                                         <th>Accept</th>
                                         <th>Decline</th>
									</tr>
								    
                                        <!-- loop through the leave application info   -->
                                    	@if(count($leaveApplication) > 0)
										@foreach($leaveApplication as $approval)
											<tr>
                                                
<!--												<td>{{ !empty($audit->module_name) ? $audit->module_name : '' }}</td>-->
            <td>{{ !empty($approval->firstname) && !empty($approval->surname) ? $approval->firstname.' '.$approval->surname : '' }}</td>    
<!--                <td>{{ !empty($audit->action_date) ? date('Y M d : H : i : s', $audit->action_date) : '' }}</td>                        -->
           <td>{{ !empty($approval->leavetype) ? $approval->leavetype : '' }}</td>                                    
            <td>{{ !empty($approval->start_date) ? date('d M Y ', $approval->start_date) : '' }}</td>
            <td>{{ !empty($approval->end_date) ? date(' d M Y', $approval->end_date) : '' }}</td>
            <td>{{ !empty($approval->start_time) ? $approval->start_time : '' }}</td>
            <td>{{ !empty($approval->end_time) ? date('H:i:s',$approval->end_time) : '' }}</td>
            <td>{{ !empty($approval->notes) ? $approval->notes : '' }}</td>

            <!-- <td>{{ !empty($approval->supporting_docs) ? $approval->supporting_docs : '' }}</td>  -->
               <!--  -->
                        <td>
                        <div class="form-group{{ $errors->has('supporting_doc') ? ' has-error' : '' }}">
                                    <label for="supporting_doc" class="control-label"></label>
                                    @if(!empty($approval->supporting_docs))
                                        <br><a class="btn btn-default btn-flat btn-block pull-right btn-xs" href="{{ $approval->supporting_docs }}" target="_blank"><i class="fa fa-file-pdf-o"></i> Click Here To View The Document</a>
                                    @else
                                        <br><a class="btn btn-default pull-centre btn-xs"><i class="fa fa-exclamation-triangle"></i> Nothing Was Uploaded</a>
                                    @endif
                                </div>
                                </td>
               <!--  -->

             <td>{{ !empty($approval->status) ? $approval->status : '' }}</td>                                       
                                                
<!--

-->
             <td>
                <button type="button" id="edit_leave" class="btn btn-success pull-right btn-xs"><i class="fa fa-check-circle" data-toggle="modal" data-target=" "></i> Accept</button>
            </td>
                <td>
                    <button type="button" id="reject_leave" class="btn btn-danger pull-right btn-xs"><i class="fa fa-check-circle" data-toggle="modal" data-target="#reject-leave-modal"></i> Decline</button>         
                </td>
		</tr>
										@endforeach
									@endif
                                    
                                    
								</table>
                     
								<div class="row no-print">
<!--
									<div class="col-xs-12">
										<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i>Print report</button>
									</div>
-->
								</div>
								<!-- End amortization /table -->
							</div>
                        </div>
                    </div>
                    <!-- /. End Collapsible section containing the amortization schedule -->
                </div>
				</form>
            </div>
        </div>
         @include('leave.partials.reject_leave')
    </div>
@endsection
<!-- END SECTION-->

@section('page_script')
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<script>
    function postData(id, data) {
       // if (data == 'actdeac') location.href = "/leave/types/activate" + id;
        if (data == 'ribbons') location.href = "/leave/ribbons/" + id;
        else if (data == 'edit') location.href = "/leave/reject" + id;
//        else if (data == 'actdeac') location.href = "/leave/leave_active/" + id; //leave_type_edit
//         else if (data == 'cu_actdeac') location.href = "/leave/custom/leave_type_edit/" + id;
//leave/reject
        //		 	else if (data == 'access')
        //		 		location.href = "/leave/module_access/" + id;
    }
    $(function () {
        var moduleId;
        //Tooltip
        $('[data-toggle="tooltip"]').tooltip();
        //Vertically center modals on page
        function reposition() {
            var modal = $(this)
                , dialog = modal.find('.modal-dialog');
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
        //pass module data to the leave type -edit module modal
        var reject_leaveId;
        $('#reject-leave-modal').on('show.bs.modal', function (e) {
            //console.log('kjhsjs');
            var btnEdit = $(e.relatedTarget);
            reject_leaveId = btnEdit.data('id');
            var name = btnEdit.data('reason');
            // var moduleFontAwesome = btnEdit.data('font_awesome');
            var modal = $(this);
            modal.find('#reason').val(reason);
//           
        });

        $('#rejection-reason').on('click', function () {
            var strUrl = '/leave/reject/' + reject_leaveId;
            var objData = {
                reason: $('#reject-leave-modal').find('#reason').val()
                , _token: $('#reject-leave-modal').find('input[name=_token]').val()
            };
            var modalID = 'reject-leave-modal';
            var submitBtnID = 'reject_leave';
            var redirectUrl = '/leave/approval';
            var successMsgTitle = 'Changes Saved!';
            var successMsg = 'Rejaection reason has been saved successfully.';
            var method = 'PATCH';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, method);
        });

    });
</script>
 @endsection