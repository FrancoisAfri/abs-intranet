@extends('layouts.main_layout') @section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Leave Types Set Up</h3>
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
                        <th>Type</th>
                        <th>5-Day Employees</th>
                        <th>5-Day Employee Max</th>
                        <th>6-Day Employees</th>
                        <th>6-Day Employee Max</th>
                        <th>Shift Employees</th>
                        <th>Shift Employee Max</th>
                        <th style="width: 40px"></th>
                    </tr> 
                    @if (count($leaveTypes) > 0)
                    @foreach($leaveTypes as $leaveType)
                    <tr id="modules-list">
                        <td nowrap>
        <button type="button" id="edit_leave" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-leave_days-modal" data-id="{{ $leaveType->id }}" data-name="{{ $leaveType->name }}" data-day5min="{{ ($profile = $leaveType->leave_profle->where('id', 2)->first()) ? $profile->pivot->min : '' }}"  data-day5max="{{ ($profile = $leaveType->leave_profle->where('id', 2)->first()) ? $profile->pivot->max : '' }}" data-day6min="{{ ($profile = $leaveType->leave_profle->where('id', 3)->first()) ? $profile->pivot->min : '' }}" data-day6max="{{ ($profile = $leaveType->leave_profle->where('id', 3)->first()) ? $profile->pivot->max : '' }}" data-shiftmin="{{ ($profile = $leaveType->leave_profle->where('id', 4)->first()) ? $profile->pivot->min : '' }}" data-shiftmax="{{ ($profile = $leaveType->leave_profle->where('id', 4)->first()) ? $profile->pivot->max : '' }}"> <i class="fa fa-pencil-square-o"></i> Edit</button>
                       
                        </td>
                        <td align="center">{{ $leaveType->name}}</td>
                        <td align="center"> {{ ($profile = $leaveType->leave_profle->where('id', 2)->first()) ? $profile->pivot->min : '' }} </td>
                        <td align="center"> {{ ($profile = $leaveType->leave_profle->where('id', 2)->first()) ? $profile->pivot->max : '' }} </td>
                        <td align="center"> {{ ($profile = $leaveType->leave_profle->where('id', 3)->first()) ? $profile->pivot->min : '' }} </td>
                        <td align="center"> {{ ($profile = $leaveType->leave_profle->where('id', 3)->first()) ? $profile->pivot->max : '' }} </td>
                        <td align="center"> {{ ($profile = $leaveType->leave_profle->where('id', 4)->first()) ? $profile->pivot->min : '' }} </td>
                        <td align="center"> {{ ($profile = $leaveType->leave_profle->where('id', 4)->first()) ? $profile->pivot->max : '' }} </td>
                    </tr> 
                    @endforeach 
                    @else
                    <tr id="modules-list">
                        <td colspan="5">
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No leave types to display, please start by adding a new leave type. </div>
                        </td>
                    </tr> @endif </table>
            </div>
            <!-- /.box-body -->
            <div class="modal-footer"> </div>
        </div>
    </div>
    <!-- Include add new prime rate modal -->@include('leave.Partials.edit_leave_type_days') @include('leave.partials.edit_leavetype') </div> {{--custom leave section--}} @endsection
<!--        edit ribbon-->
<!-- Ajax form submit -->@section('page_script')
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<script>
    function postData(id, data) {
        //if (data == 'actdeac') location.href = "/leave/types/activate" + id;
        if (data == 'ribbons') location.href = "/leave/ribbons/" + id;
        else if (data == 'edit') location.href = "/leave/leave_edit/" + id;
        else if (data == 'actdeac') location.href = "/leave/setup/" + id; //leave_type_edit
        //  else if (data == 'cu_actdeac') location.href = "/leave/custom/leave_type_edit/" + id;
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
        
        // $('#edit-leave_days-modal').on('show.bs.modal', function (e) {
        //     //console.log('kjhsjs');
        //     var btnEdit = $(e.relatedTarget);
        //     leavesetupId = btnEdit.data('id');
        //     var hr_id = btnEdit.data('hr_id');
        //     var number_of_days = btnEdit.data('number_of_days');
        //     var employeeName = btnEdit.data('employeename');
        //     // var moduleFontAwesome = btnEdit.data('font_awesome');
        //     var modal = $(this);
        //     //modal.find('#hr_id').val(hr_id);
        //     modal.find('#number_of_days').val(number_of_days);
        //     modal.find('#hr_id').val(employeeName);
        //     // modal.find('#font_awesome').val(moduleFontAwesome);
        //     //if(primeRate != null && primeRate != '' && primeRate > 0) {
        //     //    modal.find('#prime_rate').val(primeRate.toFixed(2));
        //     //}
        // });
        var leavesetupId;
        $('#edit-leave_days-modal').on('show.bs.modal', function (e) {
            //console.log('kjhsjs');
            var btnEdit = $(e.relatedTarget);
            leavesetupId = btnEdit.data('id');
            console.log('leavesetupID: ' + leavesetupId);
            var name = btnEdit.data('name');
            var day5min = btnEdit.data('day5min');
            var day5max = btnEdit.data('day5max');
            var day6min = btnEdit.data('day6min');
            var day6max = btnEdit.data('day6max');
            var shiftmin = btnEdit.data('shiftmin');
            var shiftmax = btnEdit.data('shiftmax');
    
            // var moduleFontAwesome = btnEdit.data('font_awesome');
            var modal = $(this);
            modal.find('#name').val(name);
            modal.find('#day5min').val(day5min);
            modal.find('#day5max').val(day5max);
            modal.find('#day6min').val(day6min);
            modal.find('#day6max').val(day6max);
            modal.find('#shiftmin').val(shiftmin);
            modal.find('#shiftmax').val(shiftmax);
            //if(primeRate != null && primeRate != '' && primeRate > 0) {
            //    modal.find('#prime_rate').val(primeRate.toFixed(2));
            //}
        });
        // pass module data to the custom leave  -edit module modal
        //****leave type post
        $('#update-leave_days').on('click', function () {
            var strUrl = '/leave/setup/leave_type_edit/' + leavesetupId;
            var objData = {
                  day5min: $('#edit-leave_days-modal').find('#day5min').val()
                , day5max: $('#edit-leave_days-modal').find('#day5max').val()
                , day6min: $('#edit-leave_days-modal').find('#day6min').val()
                , day6max: $('#edit-leave_days-modal').find('#day6max').val()
                , shiftmin: $('#edit-leave_days-modal').find('#shiftmin').val()
                , shiftmax: $('#edit-leave_days-modal').find('#shiftmax').val()
                , _token: $('#edit-leave_days-modal').find('input[name=_token]').val()
            };
            //console.log('gets here ' + JSON.stringify(objData));
            var modalID = 'edit-leave_days-modal';
            var submitBtnID = 'update-leave_days';
            var redirectUrl = '/leave/setup';
            var successMsgTitle = 'Changes Saved!';
            var successMsg = 'Leave days has been successfully added.';
             // var method = 'PATCH';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });                        // ----edit setup leave days ------
    });
</script> 
@endsection