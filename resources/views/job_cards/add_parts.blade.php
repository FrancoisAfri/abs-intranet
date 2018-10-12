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
                    <h3 class="box-title">Add Job card Parts </h3>
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
                            <th>Category</th>
                            <th>Product Name</th>
                            <th style="text-align: center">Amount Used</th>
                        </tr>
                        @if (count($parts) > 0)
                            @foreach ($parts as $part)
                                <tr id="categories-list">
                                    <td>{{ (!empty( $part->cat_name)) ?  $part->cat_name : ''}} </td>
                                    <td>{{ (!empty( $part->name)) ?  $part->name : ''}} </td>
                                    <td style="text-align: center">{{ (!empty( $part->no_of_parts_used)) ?  $part->no_of_parts_used : 0}} </td>
                                </tr>
                            @endforeach
                        @else
                            <tr id="categories-list">
                                <td colspan="3">
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
                        <button type="button" id="add_parts_button" class="btn btn-warning pull-right" >Add New Parts </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_script')
<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
<!-- bootstrap datepicker -->
<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

<!-- InputMask -->
<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

<!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
<script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
		type="text/javascript"></script>
<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->

<!-- the main fileinput plugin file -->
<script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>

<!-- iCheck -->
<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

<!-- Ajax dropdown options load -->
<script src="/custom_components/js/load_dropdown_options.js"></script>
<!-- Ajax form submit -->
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<script>
	function postData(id, data) {
		if (data == 'actdeac') location.href = "/jobcards/service_act/" + id;

	}

	$('#back_button').click(function () {
		location.href = '/jobcards/viewcard/{{$jobcardparts->id}}';
	});$('#add_parts_button').click(function () {
		location.href = '/jobcard/addparts/{{$jobcardparts->id}}';
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

		//

		$(".js-example-basic-multiple").select2();

		//Post perk form to server using ajax (add)
		$('#add_jobparts').on('click', function () {
			var strUrl = '/jobcard/addjobparts';
			var formName = 'add-jobparts-form';
			var modalID = 'add-jobparts-modal';
			var submitBtnID = 'add_jobparts';
			var redirectUrl = '/jobcard/parts/{{ $jobcardparts->id }}';
			var successMsgTitle = 'New Record Added!';
			var successMsg = 'The Record  has been updated successfully.';
			modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
		});

		//save Fleet
		//Post module form to server using ajax (ADD)
//            $('#add_jobparts').on('click', function() {
//                //console.log('strUrl');
//                var strUrl = '/jobcard/parts/{$jobcardparts->id}';
//                var modalID = 'add-jobparts-modal';
//                var objData = {
//                    name: $('#'+modalID).find('#name').val(),
//                    description: $('#'+modalID).find('#description').val(),
//                    _token: $('#'+modalID).find('input[name=_token]').val()
//                };
//                var submitBtnID = 'add_jobparts';
//                var redirectUrl = '/jobcards/servicetype';
//                var successMsgTitle = 'Record  Added successfully!';
//                var successMsg = 'The Record Type has been updated successfully.';
//                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
//            });

		var sevicetypeID;
		$('#edit-servicetype-modal').on('show.bs.modal', function (e) {
			//console.log('kjhsjs');
			var btnEdit = $(e.relatedTarget);
			sevicetypeID = btnEdit.data('id');
			var name = btnEdit.data('name');
			var description = btnEdit.data('description');
			var modal = $(this);
			modal.find('#name').val(name);
			modal.find('#description').val(description);
		});
		$('#edit_servicetype').on('click', function () {
			var strUrl = '/jobcards/edit_servicetype/' + sevicetypeID;
			var modalID = 'edit-servicetype-modal';
			var objData = {
				name: $('#' + modalID).find('#name').val(),
				description: $('#' + modalID).find('#description').val(),
				_token: $('#' + modalID).find('input[name=_token]').val()
			};
			var submitBtnID = 'edit_servicetype';
			var redirectUrl = '/jobcards/servicetype';
			var successMsgTitle = 'Service Type Added!';
			var successMsg = 'The Service Type has been updated successfully.';
			var Method = 'PATCH';
			modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
		});

	});
</script>
@endsection
