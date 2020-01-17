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
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">SMS SETTINGS</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
			<form class="form-horizontal" method="post" action="{{!empty($SmSConfiguration->id) ? '/contacts/update_sms/'. $SmSConfiguration->id : '/contacts/sms_settings'}}"> 
			 {{ csrf_field() }}
			 
			 @if(!empty($SmSConfiguration->id))
				{{ method_field('PATCH') }}
			 @endif
			   <table class="table table-bordered">  
				   <div class="form-group">
						<tr>
							<td>SMS Provider</td>
							<td>
								<select class="form-control select2" style="width: 100%;" id="sms_provider" name="sms_provider">
                                        <option value="">*** Select a Service Provider ***</option>
                                        <option value="1" {{ (!empty($SmSConfiguration->sms_provider) && $SmSConfiguration->sms_provider == 1) ? ' selected="selected"' : '' }}>BulkSMS</option>
                                        <option value="2" {{ (!empty($SmSConfiguration->sms_provider) && $SmSConfiguration->sms_provider == 2) ? ' selected="selected"' : '' }}>vodacomSMS</option>
                                        <option value="3" {{ (!empty($SmSConfiguration->sms_provider) && $SmSConfiguration->sms_provider == 3) ? ' selected="selected"' : '' }}>LogicSMS</option>
                                        <option value="4" {{ (!empty($SmSConfiguration->sms_provider) && $SmSConfiguration->sms_provider == 4) ? ' selected="selected"' : '' }}>HugeTelecoms</option>
                                </select>
							</td>
						</tr>
					</div>
					<div class="form-group">
						<tr>
							<td>SMS Username</td>
							<td>
								 <label for="path" class="control-label"></label>
								<input type="text" class="form-control" id="sms_username" name="sms_username" placeholder="Enter Username"required value="{{!empty($SmSConfiguration->sms_username) ? $SmSConfiguration->sms_username: ' ' }}">
							</td>
						</tr>
					</div>
					<div class="form-group">
						<tr>
						   <td>SMS Password</td>
							<td >
								 <label for="path" class="control-label"></label>
								<input type="password" class="form-control" id="sms_password" name="sms_password" placeholder="Enter Password"required value="{{!empty($SmSConfiguration->sms_password) ? $SmSConfiguration->sms_password: '' }}" >
							</td>
						</tr>
					</div>
				</table>
			<!-- /.box-body -->
			<div class="modal-footer">
			   
				<button type="submit" class="btn btn-primary"><i class="fa fa-database"></i> Submit</button> 
			</div>
			</form>
            </div>
            <!-- /.box-body -->
            <div class="modal-footer"> </div>
        </div>
    </div>
	<!-- /.document type -->
	<div class="col-md-12">
        <div class="box box-warning">
			<div class="box-header with-border">
				<h3 class="box-title">Document Type</h3>
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
				</div>
			</div>
			<div class="box-body">
				<table class="table table-bordered">
					<tr>
						<th style="width: 10px; text-align: center;"></th>
						<th>Name</th>
						<th>Description</th>
						<th style="width: 5px; text-align: center;"></th>
					</tr>
					@if (count($crmDocumentTypes) > 0)
						@foreach ($crmDocumentTypes as $document)
							<tr id="categories-list">
								<td nowrap>
									<button vehice="button" id="edit_compan" class="btn btn-warning  btn-xs" data-toggle="modal" data-target="#edit-document-type-modal" data-id="{{ $document->id }}" data-name="{{ $document->name }}" data-description="{{$document->description}}" ><i class="fa fa-pencil-square-o"></i> Edit</button>
								</td>
								<td>{{ (!empty( $document->name)) ?  $document->name : ''}} </td>
								<td>{{ (!empty( $document->description)) ?  $document->description : ''}} </td>
								<td>
									<button vehice="button" id="view_ribbons" class="btn {{ (!empty($document->status) && $document->status == 1) ? " btn-danger " : "btn-success " }}
									btn-xs" onclick="postData({{$document->id}}, 'actdeac');"><i class="fa {{ (!empty($document->status) && $document->status == 1) ?
									"fa-times " : "fa-check " }}"></i> {{(!empty($document->status) && $document->status == 1) ? "De-Activate" : "Activate"}}</button>
								</td>
							</tr>
						@endforeach
					@else
						<tr id="categories-list">
							<td colspan="5">
								<div class="alert alert-danger alert-dismissable">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									No Document Type to display, please start by adding a new Fleet Document Type..
								</div>
							</td>
						</tr>
					@endif
				</table>
				<!-- /.box-body -->
			</div>
			<div class="box-footer">
				 <button type="button" id="document_type" class="btn btn-warning pull-right" data-toggle="modal" data-target="#add-document-modal">Add Document Type </button>
			</div>
        </div>
        @include('contacts.partials.add_document_type_modal')
        @include('contacts.partials.edit_document_type_modal')
	</div>
</div>
@endsection

@section('page_script')
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<!-- Select2 -->
<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
<script>
       function postData(id , data ){   
            if(data == 'actdeac') location.href = "/crm/document_act/" + id;
        }
         $('#back_button').click(function () {
                location.href = '/vehicle_management/setup';
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
            $(window).on('resize', function() {
                $('.modal:visible').each(reposition);
            });

            //Show success action modal
            $('#success-action-modal').modal('show');
            //
            $(".js-example-basic-multiple").select2();

            //save Fleet
            //Post module form to server using ajax (ADD)
			var typeID;			
			$('#add-document-type').on('click', function() {
				var strUrl = '/crm/add_document_type';
				var formName = 'add-document-form';
				var modalID = 'add-document-modal';
				var submitBtnID = 'add-document-type';
				var redirectUrl = '/contacts/setup';
				var successMsgTitle = 'Document Type Added!';
				var successMsg = 'The Document Type has been updated successfully!';
				modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
			});

            $('#edit-document-type-modal').on('show.bs.modal', function (e) {
                //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                typeID = btnEdit.data('id');
                var Name = btnEdit.data('name');
                var Description = btnEdit.data('description');
                var modal = $(this);
                modal.find('#name').val(Name);
                modal.find('#description').val(Description);
             });
			// update note
			$('#update_document').on('click', function() {
				var strUrl = '/crm/document_type/update/'+ typeID;
				var formName = 'edit-document-type-form';
				var modalID = 'edit-document-type-modal';
				var submitBtnID = 'update_document';
				var redirectUrl = '/contacts/setup';
				var successMsgTitle = 'Record Updated!';
				var successMsg = 'Document Type have been updated successfully!';
				modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
			});
        });
    </script>
@endsection