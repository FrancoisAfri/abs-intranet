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
                    <h3 class="box-title"> Documents </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
				<!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                <strong class="lead">Vehicle Details</strong><br>

                                @if(!empty($vehiclemaker))
                                    | &nbsp; &nbsp; <strong>Vehicle Make:</strong> <em>{{ $vehiclemaker->name }}</em> &nbsp;
                                    &nbsp;
                                @endif
                                @if(!empty($vehiclemodeler))
                                    -| &nbsp; &nbsp; <strong>Vehicle Model:</strong> <em>{{ $vehiclemodeler->name }}</em>
                                    &nbsp; &nbsp;
                                @endif
                                @if(!empty($vehicleTypes))
                                    -| &nbsp; &nbsp; <strong>Vehicle Type:</strong> <em>{{ $vehicleTypes->name }}</em> &nbsp;
                                    &nbsp;
                                @endif
                                @if(!empty($maintenance->vehicle_registration))
                                    -| &nbsp; &nbsp; <strong>Vehicle Registration:</strong>
                                    <em>{{ $maintenance->vehicle_registration }}</em> &nbsp; &nbsp;
                                @endif
                                @if(!empty($maintenance->year))
                                    -| &nbsp; &nbsp; <strong>Year:</strong> <em>{{ $maintenance->year }}</em> &nbsp;
                                    &nbsp;
                                @endif
                                @if(!empty($maintenance->vehicle_color))
                                    -| &nbsp; &nbsp; <strong>Vehicle Color:</strong>
                                    <em>{{ $maintenance->vehicle_color }}</em> &nbsp; &nbsp; -|
                                @endif

                            </p>
                        </div>
                    </div>
					<div align="center">
                        <!--  -->
                        <a href="{{ '/vehicle_management/viewdetails/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-bars"></i> General Details
                        </a>
                        <a href="{{ '/vehicle_management/bookin_log/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-book"></i> Booking Log
                        </a>

                        <a href="{{ '/vehicle_management/fuel_log/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-tint"></i> Fuel Log
                        </a>

                        <a href="{{ '/vehicle_management/oil_log/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-file-o"></i> Oil Log
                        </a>

                        <a href="{{ '/vehicle_management/incidents/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-medkit"></i> Incidents
                        </a>
                        <a href="{{ '/vehicle_management/fines/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-list-alt"></i> Fines
                        </a>
                        <a href="{{ '/vehicle_management/service_details/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-area-chart"></i> Service Details
                        </a>
                        <a href="{{ '/vehicle_management/insurance/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-car"></i>Insurance
                        </a>
                        <a href="{{ '/vehicle_management/warranties/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-snowflake-o"></i>Warranties
                        </a>
                        <a href="{{ '/vehicle_management/general_cost/' . $maintenance->id }}" class="btn btn-app">
                            <i class="fa fa-money"></i> General Cost
                        </a>
						<a href="{{ '/vehicle_management/fleet-communications/' . $maintenance->id }}"
                                   class="btn btn-app"><i class="fa fa-money"></i> Communications</a>
                        <!--  -->
                    </div>
					<div style="overflow-x: scroll;">
						<table class="table table-bordered">
							<tr>
								<th style="width: 10px; text-align: center;"></th>
								<th style="width: 5px; text-align: center;"></th>
								<th>Document Type</th>
								<th>Description</th>
								<th>Date Uploaded</th>
								<th>Date From</th>
								<th>Expiry Date</th>
								<th style="width: 5px; text-align: center;"></th>
								<th style="width: 5px; text-align: center;"></th>
							</tr>
							@if (count($vehicleDocumets) > 0)
								@foreach ($vehicleDocumets as $document)
									<tr id="categories-list">
										@if (!empty($document->exp_date) && $document->exp_date < $currentTime))
											<td bgcolor="red" nowrap>
												<button document="button" id="edit_compan" class="btn btn-warning  btn-xs"
														data-toggle="modal" data-target="#edit-newdoc-modal"
														data-id="{{ $document->id }}" data-type="{{ $document->type }}"
														data-description="{{ $document->description }}"
														data-role="{{ $document->role }}"
														data-date_from="{{  date(' d M Y', $document->date_from) }}"
														data-exp_date="{{ date(' d M Y', $document->exp_date) }}"
														data-doc_document="{{ (!empty($document->document)) ? Storage::disk('local')->url("Vehicle/documents/$document->document") : '' }}"
												><i class="fa fa-pencil-square-o"></i> Edit
												</button>
											</td>
											<td bgcolor="red" nowrap>
												<div class="form-group{{ $errors->has('document') ? ' has-error' : '' }}">
													<label for="document" class="control-label"></label>
													@if(!empty($document->document))
														<a class="btn btn-default btn-flat btn-block pull-right btn-xs"
														   href="{{ Storage::disk('local')->url("Vehicle/documents/$document->document") }}"
														   target="_blank"><i class="fa fa-file-pdf-o"></i> View Document</a>
													@else
														<a class="btn btn-default pull-centre btn-xs"><i class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
													@endif
												</div>
											</td>
											<td bgcolor="red">{{ !empty($document->documentType) ? $document->documentType->name : ''}}</td>
											<td bgcolor="red">{{ !empty($document->description) ? $document->description : ''}}</td>
											<td bgcolor="red">{{ !empty($document->upload_date) ? date(' d M Y', $document->upload_date) : '' }}</td>
											<td bgcolor="red">{{ !empty($document->date_from) ? date(' d M Y', $document->date_from) : '' }}</td>
											<td bgcolor="red">{{ !empty($document->exp_date) ? date(' d M Y', $document->exp_date) : '' }}</td>
											<td bgcolor="red">
												<button vehice="button" id="view_ribbons" class="btn {{ (!empty($document->status) && $document->status == 1) ? " btn-danger " : "btn-success " }}
												  btn-xs" onclick="postData({{$document->id}}, 'actdeac');"><i class="fa {{ (!empty($document->status) && $document->status == 1) ?
												  " fa-times " : "fa-check " }}"></i> {{(!empty($document->status) && $document->status == 1) ? "De-Activate" : "Activate"}}</button>
											</td>
											<td bgcolor="red">
												<button type="button" class="btn btn-danger btn-xs" data-toggle="modal"
														data-target="#delete-documents-warning-modal"
														data-id="{{ $document->id }}"><i class="fa fa-trash"></i>
													Delete
												</button>
											</td>
											<td bgcolor="red"> Expired </td>
										@else           
											<td nowrap>
												<button document="button" id="edit_compan" class="btn btn-warning  btn-xs"
														data-toggle="modal" data-target="#edit-newdoc-modal"
														data-id="{{ $document->id }}" data-type="{{ $document->type }}"
														data-description="{{ $document->description }}"
														data-role="{{ $document->role }}"
														data-datefrom="{{ !empty($document->date_from) ? date(' d M Y', $document->date_from) : ''}}"
														data-expdate="{{ !empty($document->exp_date) ? date(' d M Y', $document->exp_date) : ''}}"
														data-doc_document="{{ (!empty($document->document)) ? Storage::disk('local')->url("Vehicle/documents/$document->document") : '' }}"
												><i class="fa fa-pencil-square-o"></i> Edit
												</button>
											</td>
											<td nowrap>
												<div class="form-group{{ $errors->has('document') ? ' has-error' : '' }}">
													<label for="document" class="control-label"></label>
													@if(!empty($document->document))
														<a class="btn btn-default btn-flat btn-block pull-right btn-xs"
														   href="{{ Storage::disk('local')->url("Vehicle/documents/$document->document") }}"
														   target="_blank"><i class="fa fa-file-pdf-o"></i> View Document</a>
													@else
														<a class="btn btn-default pull-centre btn-xs"><i class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
													@endif
												</div>
											</td>
											<td>{{ !empty($document->documentType) ? $document->documentType->name : ''}}</td>
											<td>{{ !empty($document->description) ? $document->description : ''}}</td>
											<td>{{ !empty($document->upload_date) ? date(' d M Y', $document->upload_date) : '' }}</td>
											<td>{{ !empty($document->date_from) ? date(' d M Y', $document->date_from) : '' }}</td>
											<td>{{ !empty($document->exp_date) ? date(' d M Y', $document->exp_date) : '' }}</td>
											<td>
												<button vehice="button" id="view_ribbons" class="btn {{ (!empty($document->status) && $document->status == 1) ? " btn-danger " : "btn-success " }}
												  btn-xs" onclick="postData({{$document->id}}, 'actdeac');"><i class="fa {{ (!empty($document->status) && $document->status == 1) ?
												  " fa-times " : "fa-check " }}"></i> {{(!empty($document->status) && $document->status == 1) ? "De-Activate" : "Activate"}}</button>
											</td>
											<td>
												<button type="button" class="btn btn-danger btn-xs" data-toggle="modal"
														data-target="#delete-documents-warning-modal"
														data-id="{{ $document->id }}"><i class="fa fa-trash"></i>
													Delete
												</button>
											</td>
											<td></td>       
										@endif
									</tr>
								@endforeach
							@else
								<tr id="categories-list">
									<td colspan="9">
										<div class="alert alert-danger alert-dismissable">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">
												&times;
											</button>
											No Record for this vehicle, please start by adding Record for this
											vehicle..
										</div>
									</td>
								</tr>
							@endif
						</table>
					</div>
                    <!--   </div> -->
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                        <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal"
                                data-target="#add-document-modal">Upload New document
                        </button>
                    </div>
                </div>
            </div>
            <!-- Include add new prime rate modal -->
        @include('Vehicles.partials.upload_newdocument_modal')
        @include('Vehicles.partials.edit_newdocument_modal')
        <!-- Include delete warning Modal form-->
            @if (count($vehicleDocumets) > 0)
                @include('Vehicles.warnings.documents_warning_action', ['modal_title' => 'Delete Document', 'modal_content' => 'Are you sure you want to delete this Document? This action cannot be undone.'])
            @endif
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
			if (data == 'actdeac') location.href = "/vehicle_management/vehicledoc_act/" + id;

		}

		$('#back_button').click(function () {
			location.href = '/vehicle_management/viewdetails/{{ $maintenance->id }}';
		});

		$(document).ready(function () {
			$('#date_from').datepicker({
				format: 'dd/mm/yyyy',
				autoclose: true,
				todayHighlight: true
			});
			$('#exp_date').datepicker({
				format: 'dd/mm/yyyy',
				autoclose: true,
				todayHighlight: true
			});
			$('#date_froms').datepicker({
				format: 'dd/mm/yyyy',
				autoclose: true,
				todayHighlight: true
			});
			$('#exp_dates').datepicker({
				format: 'dd/mm/yyyy',
				autoclose: true,
				todayHighlight: true
			});
		});
		 function hideFields() {
			var allType = $("input[name='upload_type']:checked").val();
			if (allType == 1) {
				$('.zip-field').hide();
				$('.user-field').show();
			}
			else if (allType == 2) {
				$('.user-field').hide();
				$('.zip-field').show();
			}
			return allType;
		}
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
			$(".js-example-basic-multiple").select2();

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
			
			$('#rdo_single, #rdo_bulke').on('ifChecked', function () {
				var allType = hideFields();
				if (allType == 1) $('#box-subtitle').html('Site Address');
				else if (allType == 2) $('#box-subtitle').html('Temo Site Address');
			});

		//Post perk form to server using ajax (add)
		$('#add_document').on('click', function () {
			var strUrl = '/vehicle_management/add_new_document';
			var formName = 'add-document-form';
			var modalID = 'add-document-modal';
			var submitBtnID = 'add_document';
			var redirectUrl = '/vehicle_management/document/{{ $maintenance->id }}';
			var successMsgTitle = 'New Documents Details Added!';
			var successMsg = 'The Documents Details has been updated successfully.';
			modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
		});

		var docID;
		$('#edit-newdoc-modal').on('shown.bs.modal', function (e) {
			var btnEdit = $(e.relatedTarget);
			docID = btnEdit.data('id');
			var type = btnEdit.data('type');
			var description = btnEdit.data('description');
			var role = btnEdit.data('start_date');
			var date_from = btnEdit.data('datefrom');
			var exp_date = btnEdit.data('expdate');
			var docDocument = btnEdit.data('doc_document');
			var modal = $(this);
			modal.find('#type').val(type);
			modal.find('#description').val(description);
			modal.find('#role').val(role);
			modal.find('#date_froms').val(date_from);
			modal.find('#exp_dates').val(exp_date);
			if(docDocument === '') { $("a[href='http://afrixcel.co.za/']").attr('href', "http://afrixcel.co.za/");}
			else{ $("a[href='http://afrixcel.co.za/']").attr('href', docDocument);}
			//$("a").attr("href", docDocument)
		});

		$('#edit_doc').on('click', function () {
			var strUrl = '/vehicle_management/edit_vehicledoc/' + docID;
			var formName = 'edit-newdoc-form';
			var modalID = 'edit-newdoc-modal';
			var submitBtnID = 'edit_doc';
			var redirectUrl = '/vehicle_management/document/{{ $maintenance->id }}';
			var successMsgTitle = 'New Documents Details have been updated!';
			var successMsg = 'The Documents Details has been updated successfully.';
			var Method = 'PATCH';
			modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
		});
		
		$('#delete-documents-warning-modal').on('shown.bs.modal', function (e) {
			var btnDelete = $(e.relatedTarget);
			docID = btnDelete.data('id');
		});
		$('#delete_documents').on('click', function () {
			var strUrl = '/vehicle_management/document/delete/' + docID;
			var formName = 'delete-documents-warning-modal-form';
			var modalID = 'delete-documents-warning-modal';
			var submitBtnID = 'delete_documents';
			var redirectUrl = '/vehicle_management/document/{{ $maintenance->id }}';
			var successMsgTitle = 'Document Successfully Deleted!';
			var successMsg = 'Document has been deleted successfully.';
			modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
		});
	});
	</script>
@endsection
