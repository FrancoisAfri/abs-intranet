@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Procurement Request Approval </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <form name="procurement-request-form" class="form-horizontal" method="POST"
                      action="/procurement/appoverequests" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div style="overflow-X:auto;">
                        <table id="example2" class="table table-bordered table-hover">
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
                                <th>Date Requested</th>
                                <th>Title</th>
                                <th>Created By</th>
                                <th>On behalf Of</th>
                                <th style="width: 150px;">Detail of Expenditure</th>
                                <th>Status</th>
                                <th style="width: 5px; text-align: center;">Accept <input type="checkbox"
                                                                                          id="checkallaccept"
                                                                                          onclick="checkAllboxAccept()"/>
                                </th>
                                <th style="width: 5px; text-align: center;">Decline</th>
                                <th style="width: 5px; text-align: center;"> ...................</th>
                            </tr>
                            @if (count($procurements) > 0)
                                @foreach ($procurements as $procurement)
                                    <tr style="text-align:center">
                                        <td>
                                            <a href="{{ '/procurement/viewrequest/' . $procurement->id.'/back/app'}}" id="view_request"
                                               class="btn btn-warning  btn-xs">View</a></td>
                                        <td>{{ !empty($procurement->date_created) ? date(' d M Y', $procurement->date_created) : '' }}</td>
                                        <td style="width: 100px; text-align: left;">{{ !empty($procurement->title_name) ? $procurement->title_name : '' }}</td>
                                        <td>{{ !empty($procurement->firstname.' '. $procurement->surname) ? $procurement->firstname.' '. $procurement->surname : '' }}</td>
                                        <td>{{ !empty($procurement->hp_firstname.' '. $procurement->hp_surname) ? $procurement->hp_firstname.' '. $procurement->hp_surname : '' }}</td>
                                        <td style="width: 150px; text-align: left;">{{ (!empty($procurement->detail_of_expenditure)) ?  $procurement->detail_of_expenditure : ''}}</td>
                                        <td>{{ !empty($procurement->step_name ) ? $procurement->step_name : 'Declined' }}</td>
                                        @if (isset($procurement) && $procurement->status === 0 || $procurement->status === $stepnumber)
                                            <td></td>
                                        @else
                                            <td style='text-align:center'>
                                                <input type="checkbox" class="checkbox selectall"
                                                       id="procurementappprove_{{ $procurement->id }}"
                                                       name="procurementappprove_{{ $procurement->id }}"
                                                       value="{{$procurement->status}}">
                                            </td>
                                        @endif
                                        @if (isset($procurement) && $procurement->status === 0 || $procurement->status === $stepnumber)
                                            <td></td>
                                            <td></td>
                                        @else
                                            <td style="text-align:center"><input type="checkbox" class="checkalldeclines" id="decline_{{$procurement->id}}"
                                                   onclick="$('#comment_id_{{$procurement->id}}').toggle(); uncheckCheckBoxes({{$procurement->id}}, 0);">
                                            </td>
                                            <td style="width: 15px;">
                                                <textarea class="form-control" id="comment_id_{{$procurement->id}}"
                                                          name="declined_{{$procurement->id}}"
                                                          placeholder="Enter rejection reason ..." rows="2"
                                                          style="display:none"></textarea>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr id="categories-list">
                                    <td colspan="12">
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                                &times;
                                            </button>
                                            No Record to display, please start by creating a request..
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </table>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-warning pull-right"> Submit</button>
                        </div>
                    </div>
				</form>
            </div>
		</div>
    </div>
@endsection
@section('page_script')
	<script src="/custom_components/js/modal_ajax_submit.js"></script>
	<!-- Select2 -->
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<!-- iCheck -->
	<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
	<script>
		function toggle(source) {
			var checkboxes = document.querySelectorAll('input[type="checkbox"]');
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i] != source)
					checkboxes[i].checked = source.checked;
			}
		}
		//
		function checkAllboxAccept() {
			if ($('#checkallaccept:checked').val() == 'on') {
				$('.selectall').prop('checked', true);
			}
			else {
				$('.selectall').prop('checked', false);
			}
		}

		function checkAllboxreject() {
			if ($('#checkallreject:checked').val() == 'on') {
				$('.reject').prop('checked', true);
			}
			else {
				$('.reject').prop('checked', false);
			}
		}

		$(function () {
			var moduleId;
			//Initialize Select2 Elements
			$(".select2").select2();
			//Tooltip
			$('[data-toggle="tooltip"]').tooltip();
			//Vertically center modals on page
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
			//Show success action modal
			$('#success-action-modal').modal('show');
			$(".js-example-basic-multiple").select2();
			//Post module form to server using ajax (ADD)
			//save reject reason
			var reasonID;
			$('#decline-vehicle-modal').on('show.bs.modal', function (e) {
				var btnEdit = $(e.relatedTarget);
				if (parseInt(btnEdit.data('id')) > 0) {
					reasonID = btnEdit.data('id');
				}
				var description = btnEdit.data('description');
				var modal = $(this);
				modal.find('#description').val(description);
			});

			$('#rejection-reason').on('click', function () {
				var strUrl = '/vehicle_management/reject_vehicle/' + reasonID;
				var modalID = 'decline-vehicle-modal';
				var objData = {
					description: $('#' + modalID).find('#description').val(),
					_token: $('#' + modalID).find('input[name=_token]').val()
				};
				var submitBtnID = 'rejection-reason';
				var redirectUrl = '/vehicle_management/vehicle_approval';
				var successMsgTitle = 'Reason Added!';
				var successMsg = 'The reject reason has been updated successfully.';
				var Method = 'PATCH';
				modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
			});
		});
	</script>
@endsection