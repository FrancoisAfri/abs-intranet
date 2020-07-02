@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <!-- Include Date Range Picker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title"> Company Documents For - {{ $company->name }} </h3>
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
                            <th style="width: 5px; text-align: center;"></th>
                            <th>Document Type</th>
                            <th>name</th>
                            <th>Description</th>
                            <th>Date From</th>
                            <th>Expiry Date</th>
                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        @if (count($document) > 0)
                            @foreach ($document as $documents)
                                <tr id="categories-list">
                                    <td nowrap>
                                        <button document="button" id="edit_compan" class="btn btn-warning  btn-xs"
                                                data-toggle="modal" data-target="#edit-newdoc-modal"
                                                data-id="{{ $documents->id }}" data-name="{{ $documents->name }}"
                                                data-description="{{ $documents->description }}"
                                                data-role="{{ $documents->role }}"
                                                data-doc_type="{{ $documents->doc_type }}"
                                                data-date_from="{{  date(' d M Y', $documents->date_from) }}"
                                                data-expirydate="{{ date(' d M Y', $documents->expirydate) }}"
                                        ><i class="fa fa-pencil-square-o"></i> Edit
                                        </button>
                                    </td>
                                    <td nowrap>
                                        <div class="form-group{{ $errors->has('supporting_docs') ? ' has-error' : '' }}">
                                            <label for="document" class="control-label"></label>
                                            @if(!empty($documents->supporting_docs))
                                                <a class="btn btn-default btn-flat btn-block pull-right btn-xs"
                                                   href="{{ Storage::disk('local')->url("ContactCompany/company_documents/$documents->supporting_docs") }}"
                                                   target="_blank"><i class="fa fa-file-pdf-o"></i> View Document</a>
                                            @else
                                                <a class="btn btn-default pull-centre btn-xs"><i
                                                            class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ !empty($documents->documentType->name) ? $documents->documentType->name : ''}}</td>
                                    <td>{{ !empty($documents->name) ? $documents->name : ''}}</td>
                                    <td>{{ !empty($documents->description) ? $documents->description : ''}}</td>
                                    <td>{{ !empty($documents->date_from) ? date(' d M Y', $documents->date_from) : '' }}</td>
                                    <td>{{ !empty($documents->expirydate) ? date(' d M Y', $documents->expirydate) : '' }}</td>
                                    <td>
                                        <button vehice="button" id="view_ribbons"
                                                class="btn {{ (!empty($documents->status) && $documents->status == 1) ? " btn-danger " : "btn-success " }}
                                                        btn-xs" onclick="postData({{$documents->id}}, 'actdeac');"><i
                                                    class="fa {{ (!empty($documents->status) && $documents->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($documents->status) && $documents->status == 1) ? "De-Activate" : "Activate"}}
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-xs" data-toggle="modal"
                                                data-target="#delete-contact-warning-modal"><i class="fa fa-trash"></i>
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr id="categories-list">
                                <td colspan="8">
                                    <div class="callout callout-danger">
                                        <h4><i class="fa fa-database"></i> No Records found</h4>

                                        <p>No document found in the database. Please start by adding a document.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                    <!--   </div> -->
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                        <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal"
                                data-target="#add-document-modal">Add Document
                        </button>
                    </div>
                </div>
            </div>
            <!-- Include add new prime rate modal -->
			@include('contacts.partials.add_companydocument_modal')
			@include('contacts.partials.edit_companydocument_modal')
			<!-- Include delete warning Modal form-->
            @if (count($document) > 0)
                @include('contacts.partials.companydoc_warning_action', ['modal_title' => 'Delete Task', 'modal_content' => 'Are you sure you want to delete this Document ? This action cannot be undone.'])
            @endif
        </div>
    </div>
@endsection
@section('page_script')
	<script src="/custom_components/js/modal_ajax_submit.js"></script>
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
	<!-- iCheck -->
	<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
	<script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
			type="text/javascript"></script>
	<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
	<script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js"
			type="text/javascript"></script>
	<!-- the main fileinput plugin file -->
	<script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
	<!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
	<script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
	<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

	<!-- InputMask -->
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
	<script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<script>
	function postData(id, data) {
		if (data == 'actdeac') location.href = "/contacts/companydoc_act/" + id;
	}
	$('#back_button').click(function () {
		location.href = '/contacts/company/' + {{ $company->id}} +'/view';
	});
	$(function () {
	  
		var moduleId;
		//Initialize Select2 Elements
		$(".select2").select2();
		$('.zip-field').hide();
		$('.sex-field').hide();
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

		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy',
			autoclose: true,
			todayHighlight: true
		});

		$(function () {
			$('img').on('click', function () {
				$('.enlargeImageModalSource').attr('src', $(this).attr('src'));
				$('#enlargeImageModal').modal('show');
			});
		});

		//Initialize iCheck/iRadio Elements
		$('input').iCheck({
			checkboxClass: 'icheckbox_square-blue',
			radioClass: 'iradio_square-blue',
			increaseArea: '10%' // optional
		});

		$(document).ready(function () {

			$('#date_from').datepicker({
				format: 'dd/mm/yyyy',
				autoclose: true,
				todayHighlight: true
			});

		});

		$('#exp_date').datepicker({
			format: 'dd/mm/yyyy',
			autoclose: true,
			todayHighlight: true
		});
	});
	//Post perk form to server using ajax (add)
	$('#add_document').on('click', function () {
		var strUrl = '/contacts/add_companydocument';
		var formName = 'add-document-form';
		var modalID = 'add-document-modal';
		var submitBtnID = 'add_document';
		var redirectUrl = '/contacts/{{ $company->id }}/viewcompanydocuments';
		var successMsgTitle = 'New Document Added!';
		var successMsg = 'Document Details has been updated successfully.';
		modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
	});

	var docID;
	$('#edit-newdoc-modal').on('shown.bs.modal', function (e) {
		var btnEdit = $(e.relatedTarget);
		docID = btnEdit.data('id');
		var name = btnEdit.data('name');
		var description = btnEdit.data('description');
		var doc_type = btnEdit.data('doc_type');
		var date_from = btnEdit.data('date_from');
		var expirydate = btnEdit.data('expirydate');
		var modal = $(this);
		modal.find('#name_update').val(name);
		modal.find('#description_update').val(description);
		modal.find('#doc_type_update').val(doc_type);
		modal.find('#date_from_update').val(date_from);
		modal.find('#expirydate').val(expirydate);
	});

	$('#edit_doc').on('click', function () {
		var strUrl = '/contacts/edit_companydoc/' + docID;
		var formName = 'edit-newdoc-form';
		var modalID = 'edit-newdoc-modal';
		var submitBtnID = 'edit_doc';
		var redirectUrl = '/contacts/{{ $company->id }}/viewcompanydocuments';
		var successMsgTitle = 'Document Details have been updated!';
		var successMsg = 'The Documents Details has been updated successfully.';
		var Method = 'PATCH';
		modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
	});
</script>
@endsection
