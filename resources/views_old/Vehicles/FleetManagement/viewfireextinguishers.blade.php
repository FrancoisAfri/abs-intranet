@extends('layouts.main_layout')
@section('page_dependencies')

    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!--  -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css"
          rel="stylesheet">

@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-truck pull-right"></i>
                    <h3 class="box-title"> Vehicle Fire Extinguishers </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                <strong class="lead">Vehicle Details</strong><br>

                                @if(!empty($vehiclemaker))
                                    | &nbsp; &nbsp; <strong>Vehicle Make:</strong> <em>{{ $vehiclemaker->name }}</em>
                                    &nbsp;
                                    &nbsp;
                                @endif
                                @if(!empty($vehiclemodeler))
                                    -| &nbsp; &nbsp; <strong>Vehicle Model:</strong>
                                    <em>{{ $vehiclemodeler->name }}</em>
                                    &nbsp; &nbsp;
                                @endif
                                @if(!empty($vehicleTypes))
                                    -| &nbsp; &nbsp; <strong>Vehicle Type:</strong> <em>{{ $vehicleTypes->name }}</em>
                                    &nbsp;
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
                    <div style="overflow-X:auto;">
                        <table id="example2" class="table table-bordered table-hover">
                            <tr>
								<th style="width: 10px; text-align: center;"></th>
								<th style="width: 10px; text-align: center;">Images</th>
								<th style="width: 10px; text-align: center;">Documents</th>
								<th>Barcode</th>
								<th>Item</th>
								<th>Service Provier</th>
								<th>Serial Number</th>
								<th>Weight</th>
								<th>Invoice Number</th>
								<th>PO Number</th>
								<th>Date Purchased</th>
								<th>Cost</th>
								<th>Capturer By</th>
								<th>Status</th>
								<th style="width: 5px; text-align: center;"></th>
                            </tr>
                            @if (count($fireextinguishers) > 0)
                                <ul class="products-list product-list-in-box">
                                    @foreach ($fireextinguishers as $extinguishers)
                                        <tr>
                                            <td>
                                        <button vehice="button" id="edit_compan" class="btn btn-warning  btn-xs"
                                                data-toggle="modal" data-target="#edit-fire-extinghuisher-modal"
                                                data-id="{{ $extinguishers->id }}" data-bar_code="{{$extinguishers->bar_code}}"
                                                data-item_no="{{ $extinguishers->item_no }}" data-description="{{$extinguishers->Description}}"
                                                data-weight="{{ $extinguishers->Weight }}" data-serial_number="{{$extinguishers->Serial_number}}"
                                                data-purchase_order="{{ $extinguishers->purchase_order }}" data-invoice_number="{{$extinguishers->invoice_number}}"
                                                data-supplier_id="{{ $extinguishers->supplier_id }}" 
												data-date_purchased="{{ !empty($extinguishers->date_purchased) ? date(' d M Y', $extinguishers->date_purchased) : ''}}"
                                                data-cost="{{ $extinguishers->Cost }}"
                                                data-fire_image="{{ (!empty($extinguishers->image)) ? Storage::disk('local')->url("Vehicle/fireextinguishers/images/$extinguishers->image") : 'http://placehold.it/60x50' }}"
												data-fire_document="{{ (!empty($extinguishers->attachement)) ? Storage::disk('local')->url("Vehicle/fireextinguishers/document/$extinguishers->attachement") : '' }}"	
												>
												<i class="fa fa-pencil-square-o"></i> Edit
                                        </button>
                                    </td>
                                    <td>
                                        <div class="product-img">
                                        <img src="{{ (!empty($extinguishers->image)) ? Storage::disk('local')->url("Vehicle/fireextinguishers/images/$extinguishers->image") : 'http://placehold.it/60x50' }}"
                                                             alt="Product Image" width="100" height="75">
                                       </div> 
                                    </td>                
                                    <td>
										@if(!empty($extinguishers->attachement))
											<a class="btn btn-default btn-flat btn-block btn-xs"
											   href="{{ Storage::disk('local')->url("Vehicle/fireextinguishers/document/$extinguishers->attachement")}}"
											   target="_blank"><i class="fa fa-file-pdf-o"></i> View Document</a>
										@else
											<a class="btn btn-default btn-flat btn-block"><i class="fa fa-exclamation-triangle"></i> Nothing Was Uploaded</a>
										@endif
									</td>
                                    <td>{{ (!empty( $extinguishers->bar_code)) ?  $extinguishers->bar_code : ''}} </td>
                                    <td>{{ (!empty( $extinguishers->item_no)) ?  $extinguishers->item_no : ''}} </td>
                                    <td>{{ (!empty( $extinguishers->comp_name)) ?  $extinguishers->comp_name : ''}} </td>
                                    <td>{{ (!empty( $extinguishers->Serial_number)) ?  $extinguishers->Serial_number : ''}} </td>
                                    <td>{{ (!empty( $extinguishers->Weight)) ?  $extinguishers->Weight : ''}} </td>
                                    <td>{{ (!empty( $extinguishers->invoice_number)) ?  $extinguishers->invoice_number : ''}} </td>
                                    <td>{{ (!empty( $extinguishers->purchase_order)) ?  $extinguishers->purchase_order : ''}} </td>
                                    <td>{{ !empty($extinguishers->date_purchased) ? date(' d M Y', $extinguishers->date_purchased) : '' }} </td>
                                    <td>{{ !empty($extinguishers->Cost) ?  'R' .number_format($extinguishers->Cost, 2): '' }}</td>
                                    <td>{{ !empty($extinguishers->firstname) ?  $extinguishers->firstname." ".$extinguishers->surname: '' }}</td>
                                    <td>{{ (!empty( $extinguishers->Status)) ?  $statusArray[$extinguishers->Status] : ''}} </td>
                                    <td nowrap>
                                        <button details="button" id="edit_compan" class="btn btn-warning  btn-xs"
                                                data-toggle="modal" data-target="#change-fire-status-modal"
                                                data-id="{{ $extinguishers->id }}" data-status="{{ $extinguishers->Status }}"
                                                ><i class="fa fa-stack-overflow"></i>
                                            Change Status
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            @endif
                            </tbody>
                            <tfoot>
                            <tr>
								<th style="width: 10px; text-align: center;"></th>
								<th style="width: 10px; text-align: center;">Images</th>
								<th style="width: 10px; text-align: center;">Documents</th>
								<th>Barcode</th>
								<th>Item</th>
								<th>Service Provier</th>
								<th>Serial Number</th>
								<th>Weight</th>
								<th>Invoice Number</th>
								<th>PO Number</th>
								<th>Date Purchased</th>
								<th>Cost</th>
								<th>Capturer By</th>
								<th>Status</th>
								<th style="width: 5px; text-align: center;"></th>
                            </tr>
                            </tfoot>
                        </table>
                        <!-- /.box-body -->
						<div class="box-footer">
							<button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
							<button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal"
									data-target="#add_fireextinguishers-modal">Add Fire Extinguishers
							</button>
						</div>                        
                    </div>
                </div>
            </div>
        <!-- Include Modal form-->
          @include('Vehicles.partials.add_fire_extinguishers_modal')
          
          @include('Vehicles.partials.edit_fireextinguisher_modal')
          @include('Vehicles.partials.change_extinguisherstatus_modal')
        </div>
        @endsection

@section('page_script')
            <!-- DataTables -->
	<script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
	<script src="/custom_components/js/modal_ajax_submit.js"></script>
	<!-- time picker -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
	<!-- Select2 -->
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
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

	  <!-- Ajax dropdown options load -->
	<script src="/custom_components/js/load_dropdown_options.js"></script>
	<script>

		$(document).ready(function () {

			$('#date_purchased').datepicker({
				format: 'dd/mm/yyyy',
				autoclose: true,
				todayHighlight: true
			});
			$('#date_purchaseds').datepicker({
				format: 'dd/mm/yyyy',
				autoclose: true,
				todayHighlight: true
			});
	
		});

		$(function () {
			var fireID;
			//Tooltip
			$('[data-toggle="tooltip"]').tooltip();

			//Initialize Select2 Elements
			$(".select2").select2();
			
			document.getElementById("back_button").onclick = function () {
			location.href = '/vehicle_management/viewdetails/{{ $maintenance->id }}';	};
			
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
			
			 //Post form to server using ajax (add)
			$('#add-fire-extinguishers').on('click', function () {
				var strUrl = '/vehicle_management/addfireextinguishers';
				var formName = 'add-fireextinguishers-form';
				var modalID = 'add_fireextinguishers-modal';
				var submitBtnID = 'add-fire-extinguishers';
				var redirectUrl = '/vehicle_management/fire_extinguishers/{{ $maintenance->id }}';
				var successMsgTitle = 'New Record Added!';
				var successMsg = 'New Record Successfully Added!';
				modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
			});
			
			$('#edit-fire-extinghuisher-modal').on('shown.bs.modal', function (e) {
				//console.log('kjhsjs');
				var btnEdit = $(e.relatedTarget);
				fireID = btnEdit.data('id');
				var barCode = btnEdit.data('bar_code');
				var itemNo = btnEdit.data('item_no');
				var desc = btnEdit.data('description');
				var weights = btnEdit.data('weight');
				var serialNumber = btnEdit.data('serial_number');
				var purchaseOrder = btnEdit.data('purchase_order');
				var invoiceNumber = btnEdit.data('invoice_number');
				var supplierID = btnEdit.data('supplier_id');
				var datePurchased = btnEdit.data('date_purchased');
				var amount = btnEdit.data('cost');
				var fireImage = btnEdit.data('fire_image');
				var fireDocument = btnEdit.data('fire_document');
				var modal = $(this);
				modal.find('#bar_code').val(barCode);
				modal.find('#item_no').val(itemNo);
				modal.find('#Description').val(desc);
				modal.find('#Weight').val(weights);
				modal.find('#Serial_number').val(serialNumber);
				modal.find('#purchase_order').val(purchaseOrder);
				modal.find('#invoice_number').val(invoiceNumber);
				modal.find('select#supplier_id').val(supplierID).trigger("change");
				modal.find('#date_purchaseds').val(datePurchased);
				modal.find('#Cost').val(amount);
				modal.find('#fire_image').attr("src", fireImage);
				if(fireDocument === '') { $("a[href='http://afrixcel.co.za/']").attr('href', "http://afrixcel.co.za/");}
				else{ $("a[href='http://afrixcel.co.za/']").attr('href', fireDocument);}
				//$("a").attr("href", fireDocument)
			});
			
			$('#update-fire-extinguishers').on('click', function() {
				var strUrl = '/vehicle_management/editfireexting/' + fireID;
				var formName = 'edit-fireextinguishers-form';
				var modalID = 'edit-fire-extinghuisher-modal';
				var submitBtnID = 'update-fire-extinguishers';
				var redirectUrl = '/vehicle_management/fire_extinguishers/{{ $maintenance->id }}';
				var successMsgTitle = 'The Record  has been updated successfully';
				var successMsg = 'The Record  has been updated successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
			
			$('#change-fire-status-modal').on('show.bs.modal', function (e) {
				var btnChange = $(e.relatedTarget);
				fireID = btnChange.data('id');
				var change = btnChange.data('status');
				var modal = $(this);
				
				modal.find('select#Status').val(change).trigger("change");
				modal.find('#fire_id').val(fireID);
            });
			$('#change-fire-status').on('click', function() {
				
				var strUrl = '/vehicle_management/changestatus/' + fireID;
				var formName = 'change-fire-status-form';
				var modalID = 'change-fire-status-modal';
				var submitBtnID = 'update-fire-extinguishers';
				var redirectUrl = '/vehicle_management/fire_extinguishers/{{ $maintenance->id }}';
				var successMsgTitle = 'The Record  has been updated successfully';
				var successMsg = 'The Record  has been updated successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
        });
    </script>
@endsection