@extends('layouts.main_layout')

@section('page_dependencies')
<!-- dependencies -->

@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Leave Types</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 10px"></th>
                        <th>Name</th>
                        <th>Description</th>
                        <th style="width: 40px"></th>
                    </tr> 
                    @if (count($leaveTypes) > 0)
                    @foreach($leaveTypes as $leaveType)
                    <tr id="modules-list">
                        <td nowrap>
                            <button type="button" id="edit_leave" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-leave-modal" data-id="{{ $leaveType->id }}" data-name="{{ $leaveType->name }}" data-description="{{ $leaveType->description }}"> <i class="fa fa-pencil-square-o">
                                </i> Edit</button>
                        </td>
                        <td>{{ $leaveType->name }} </td>
                        <td>{{ $leaveType->description }} </td>
                        <td>
                            <!--   leave here  -->
                            <button type="button" id="view_ribbons" class="btn {{ (!empty($leaveType->status) && $leaveType->status == 1) ? " btn-danger " : "btn-success " }}
							  btn-xs" onclick="postData({{$leaveType->id}}, 'actdeac');"><i class="fa {{ (!empty($leaveType->status) && $leaveType->status == 1) ?
							  " fa-times " : "fa-check " }}"></i> {{(!empty($leaveType->status) && $leaveType->status == 1) ? "De-Activate" : "Activate"}}</button>
                        </td>
                    </tr>
                     @endforeach
                 @else
                    <tr id="modules-list">
                        <td colspan="5">
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No leave types to display, please start by adding a new leave type. </div>
                        </td>
                    </tr> 
                @endif
            </table>
            </div>
            <!-- /.box-body -->
            <div class="modal-footer">
                <button type="button" id="add-new-leave" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-leave-modal">Add Leave Type</button>
            </div>
        </div>
    </div>
    <!-- Include add new prime rate modal -->
    @include('leave.partials.add_new_leavetype') 
    @include('leave.partials.edit_leavetype')
</div>

 {{--custom leave section--}}

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Custom Leave Types</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body"> {{-- start custom leave--}}
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 10px"></th>
                        <th>Employee Name</th>
                        <th>Annual Days</th>
                        <th style="width: 40px"></th>
                    </tr> @if (count($leave_customs) > 0)
                    @foreach($leave_customs as $leavecustom)
                    <tr id="modules-list">
                        <td nowrap>
                            <button type="button" id="edit_leave" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-customleave-modal" data-id="{{ $leavecustom->id }}" data-employeename="{{($leavecustom->userCustom) ? $leavecustom->userCustom->first_name." ".$leavecustom->userCustom->surname : ''}}"      data-number_of_days="{{ $leavecustom->number_of_days }}"> <i class="fa fa-pencil-square-o">
                                </i> Edit</button>
                        </td>
                        <td>{{ ($leavecustom->userCustom) ? $leavecustom->userCustom->first_name." ".$leavecustom->userCustom->surname : ''}} </td>
                        <td>{{ $leavecustom->number_of_days }} </td>
                        <td>
                            <!--   leave here  -->
                            <button type="button" id="view_ribbons" class="btn {{ (!empty($leavecustom->status) && $leavecustom->status == 1) ? " btn-danger " : "btn-success " }}
                                        btn-xs" onclick="postData({{$leavecustom->id}}, 'cu_actdeac');"><i class="fa {{ (!empty($leavecustom->status) && $leavecustom->status == 1) ?
							  " fa-times " : "fa-check " }}"></i> {{(!empty($leavecustom->status) && $leavecustom->status == 1) ? "De-Activate" : "Activate"}}</button>
                        </td>
                    </tr> 
                    @endforeach @else
                    <tr id="modules-list">
                        <td colspan="5">
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No Custom leaves to display, please start by adding a new Custom leave . </div>
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
            <!-- /.box-body -->
            <div class="modal-footer">
                <button type="button" id="add_custom_leave" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-custom-leave-modal">Add Custom Leave</button> {{--hr leave approval--}} </div>
        </div>
    </div>
    @include('leave.partials.add_custom_leave')
    @include('leave.partials.edit_customleave')
</div>
@endsection
<!--        edit ribbon-->
           
    </div>
<!-- Ajax form submit -->

@section('page_script')
<script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
<script>
    function postData(id, data) {
       // if (data == 'actdeac') location.href = "/leave/types/activate" + id;
        if (data == 'ribbons') location.href = "/leave/ribbons/" + id;
        else if (data == 'edit') location.href = "/leave/leave_edit/" + id;
        else if (data == 'actdeac') location.href = "/leave/leave_active/" + id; //leave_type_edit
         else if (data == 'cu_actdeac') location.href = "/leave/custom/leave_type_edit/" + id;
    }
    $(function () {
        var moduleId;
		 //Initialize Select2 Elements
            $(".select2").select2();
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
        var leaveTypeId;
        $('#edit-leave-modal').on('show.bs.modal', function (e) {
            //console.log('kjhsjs');
            var btnEdit = $(e.relatedTarget);
            leaveTypeId = btnEdit.data('id');
            var name = btnEdit.data('name');
            var description = btnEdit.data('description');
            // var moduleFontAwesome = btnEdit.data('font_awesome');
            var modal = $(this);
            modal.find('#name').val(name);
            modal.find('#description').val(description);
            // modal.find('#font_awesome').val(moduleFontAwesome);
            //if(primeRate != null && primeRate != '' && primeRate > 0) {
            //    modal.find('#prime_rate').val(primeRate.toFixed(2));
            //}
        });
        // pass module data to the custom leave  -edit module modal
        var customleaveId;
        $('#edit-customleave-modal').on('show.bs.modal', function (e) {
            //console.log('kjhsjs');
            var btnEdit = $(e.relatedTarget);
            customleaveId = btnEdit.data('id');
            var hr_id = btnEdit.data('hr_id');
            var number_of_days = btnEdit.data('number_of_days');
            var employeeName = btnEdit.data('employeename');
            // var moduleFontAwesome = btnEdit.data('font_awesome');
            var modal = $(this);
            //modal.find('#hr_id').val(hr_id);
            modal.find('#number_of_days').val(number_of_days);
            modal.find('#hr_id').val(employeeName);
        });
        ///leave/type/add_leave
        //****leave type post
        $('#add_leave').on('click', function () {
            var strUrl = '/leave/type/add_leave';
            var objData = {
                name: $('#add-new-leave-modal').find('#name').val()
                , description: $('#add-new-leave-modal').find('#description').val()
                , _token: $('#add-new-leave-modal').find('input[name=_token]').val()
            };
            var modalID = 'add-new-leave-modal';
            var submitBtnID = 'add_leave';
            var redirectUrl = '/leave/types';
            var successMsgTitle = 'Changes Saved!';
            var successMsg = 'Leave has been successfully added.';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });
        $('#update-leave_type').on('click', function () {
            var strUrl = '/leave/leave_type_edit/' + leaveTypeId;
            var objData = {
                name: $('#edit-leave-modal').find('#name').val()
                , description: $('#edit-leave-modal').find('#description').val()
                , _token: $('#edit-leave-modal').find('input[name=_token]').val()
            };
            var modalID = 'edit-leave-modal';
            var submitBtnID = 'update-leave_type';
            var redirectUrl = '/leave/types';
            var successMsgTitle = 'Changes Saved!';
            var successMsg = 'Leave type has been changed successfully.';
            var method = 'PATCH';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, method);
        });
        //Post password form to server using ajax
        // **** custom leave post to server using ajax *****************
        $('#save_custom_leave').on('click', function () {
            var strUrl = '/leave/custom/add_leave';
            var objData = {
                hr_id: $('#add-custom-leave-modal').find('#hr_id').val()
                , number_of_days: $('#add-custom-leave-modal').find('#number_of_days').val()
                , _token: $('#add-custom-leave-modal').find('input[name=_token]').val()
            };
            var modalID = 'add-custom-leave-modal';
            var submitBtnID = 'add_custom_leave';
            var redirectUrl = '/leave/types';
            var successMsgTitle = 'Changes Saved!';
            var successMsg = 'Leave has been successfully added.';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });
        //
        //..............edit custom leave
        $('#update-custom-leave').on('click', function () {
            var strUrl = '/leave/custom/leave_type_edit/' + customleaveId;
            var objData = {
                // hr_id: $('#edit-customleave-modal').find('#hr_id').val()
                number_of_days: $('#edit-customleave-modal').find('#number_of_days').val()
                , _token: $('#edit-customleave-modal').find('input[name=_token]').val()
            };
            var modalID = 'edit-customleave-modal';
            var submitBtnID = 'update-leave_type';
            var redirectUrl = '/leave/types';
            var successMsgTitle = 'Changes Saved!';
            var successMsg = 'Anual dayshas been changed successfully.';
            // var method = 'PATCH';
           modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });
    });
</script>
 @endsection
