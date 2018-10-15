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
                    <h3 class="box-title">Process flow</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 10px; text-align: center;"></th>
                            <th style="text-align: center">Step number</th>
                            <th>Step name</th>
                            <th>Role</th>
                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        @if (count($processflows) > 0)
                            @foreach ($processflows as $processflow)
                                <tr id="categories-list">
                                    <td nowrap>
                                        <button vehice="button" id="edit_compan" class="btn btn-warning  btn-xs"
                                                data-toggle="modal" data-target="#edit-step-modal"
                                                data-id="{{ $processflow->id }}"
                                                data-step_number="{{ $processflow->step_number }}"
                                                data-step_name="{{$processflow->step_name}}"
                                                data-job_title="{{$processflow->job_title}}">
                                            <i class="fa fa-pencil-square-o"></i> Edit
                                        </button>
                                    </td>
                                    <td style="text-align: center">{{ (!empty( $processflow->step_number)) ?  $processflow->step_number : ''}} </td>
                                    <td>{{ (!empty( $processflow->step_name)) ?  $processflow->step_name : ''}} </td>
                                    <td>{{ (!empty( $processflow->jobtitle_name)) ?  $processflow->jobtitle_name : ''}} </td>
                                    <td>
                                        <button vehice="button" id="view_ribbons"
                                                class="btn {{ (!empty($processflow->status) && $processflow->status == 1) ? " btn-danger " : "btn-success " }}
                                                        btn-xs" onclick="postData({{$processflow->id}}, 'actdeac');"><i
                                                    class="fa {{ (!empty($processflow->status) && $processflow->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($processflow->status) && $processflow->status == 1) ? "De-Activate" : "Activate"}}
                                        </button>
                                    </td>
                                    <!--                                 <td><button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#delete-contact-warning-modal"><i class="fa fa-trash"></i> Delete</button></td>-->
                                </tr>
                            @endforeach
                        @else
                            <tr id="categories-list">
                                <td colspan="5">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        No Record to display, please start by adding a new Record....
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                        <button type="button" id="safe_module" class="btn btn-warning pull-right" data-toggle="modal"
                                data-target="#add-steps-modal">Add new Process flow
                        </button>
                    </div>
                </div>
            </div>
            <!-- Include add  modal -->
        @include('job_cards.partials.add_steps_modal')
        @include('job_cards.partials.edit_steps_modal')
        </div>
    </div>
@endsection
@section('page_script')
	<script src="/custom_components/js/modal_ajax_submit.js"></script>
	<!-- Select2 -->
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<script>
		function postData(id, data) {
			if (data == 'actdeac') location.href = "/jobcards/process_act/" + id;
		}

		$('#back_button').click(function () {
			location.href = '/jobcards/set_up';
		});
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

			//save Fleet
			//Post module form to server using ajax (ADD)
			$('#add-steps').on('click', function () {
				//console.log('strUrl');
				var strUrl = '/jobcards/add_processflow';
				var modalID = 'add-steps-modal';
				var objData = {
					step_number: $('#' + modalID).find('#step_number').val(),
					step_name: $('#' + modalID).find('#step_name').val(),
					job_title: $('#' + modalID).find('#job_title').val(),
					_token: $('#' + modalID).find('input[name=_token]').val()
				};
				var submitBtnID = 'add-steps';
				var redirectUrl = '/jobcards/approval_process';
				var successMsgTitle = 'New Recoed Added!';
				var successMsg = 'The Recoed has been updated successfully.';
				modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
			});

			var sevicetypeID;
			$('#edit-step-modal').on('show.bs.modal', function (e) {
				//console.log('kjhsjs');
				var btnEdit = $(e.relatedTarget);
				sevicetypeID = btnEdit.data('id');
				var step_number = btnEdit.data('step_number');
				var step_name = btnEdit.data('step_name');
				var jobTitle = btnEdit.data('job_title');
				var modal = $(this);
				modal.find('#step_number').val(step_number);
				modal.find('#step_name').val(step_name);
				modal.find('select#job_title').val(jobTitle).trigger("change");
				
			});

			$('#edit_step').on('click', function () {
				var strUrl = '/jobcards/edit_step/' + sevicetypeID;
				var modalID = 'edit-step-modal';
				var objData = {
					step_number: $('#' + modalID).find('#step_number').val(),
					step_name: $('#' + modalID).find('#step_name').val(),
					job_title: $('#' + modalID).find('#job_title').val(),
					_token: $('#' + modalID).find('input[name=_token]').val()
				};
				var submitBtnID = 'edit_step';
				var redirectUrl = '/jobcards/approval_process';
				var successMsgTitle = 'New Recoed Added!';
				var successMsg = 'The Recoed has been updated successfully.';
				var Method = 'PATCH';
				modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
			});
		});
	</script>
@endsection
