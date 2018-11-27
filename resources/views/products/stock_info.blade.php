@extends('layouts.main_layout')

@section('page_dependencies')
        <!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- iCheck -->
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/green.css"> 
	    <!-- bootstrap file input -->
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css"/>
	<!--  -->
	 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-12">
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Stock Details Products ({{  $products->name}})</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i
									class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i
									class="fa fa-remove"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body" style="max-height: 190px; overflow-y: scroll;">
					<table id="example2" class="table table-bordered table-hover">
						<thead>
							<tr>
								<th></th>
								<th>Image</th>
								<th>Allow Vat</th>
								<th>Mass Net ({{ !empty($stockSettings->unit_of_measurement) ? $stockSettings->unit_of_measurement : '' }})</th>
								<th>minimum Level</th>
								<th>Maximum Level</th>
								<th>Bar Code</th>
								<th>Unit</th>
								<th>Commodity Code</th>
							</tr>
						</thead>
						<tbody>
							@if (count($products->infosProduct) > 0)
								@foreach ($products->infosProduct as $product)
									<tr>
										<td>
											<button type="button" id="edit_info" class="btn btn-primary  btn-xs"
                                                data-toggle="modal" data-target="#edit-stock-info-modal"
                                                data-id="{{ $product->id }}" data-picture="{{ $product->picture }}"
												data-location="{{ $product->location }}"
												data-allow_vat="{{ $product->allow_vat }}"
												data-mass_net="{{ $product->mass_net }}"
												data-minimum_level="{{ $product->minimum_level }}"
												data-maximum_level="{{ $product->maximum_level }}"
												data-bar_code="{{ $product->bar_code }}"
												data-unit="{{ $product->unit }}"
												data-commodity_code="{{ $product->commodity_code }}"
												data-stock_image="{{ (!empty($product->picture)) ? Storage::disk('local')->url("Producrs/images/$product->picture") : 'http://placehold.it/60x50' }}"
												data-stock_level_5="{{ $product->stock_level_5 }}" 
												data-stock_level_4="{{ $product->stock_level_4 }}"
												data-stock_level_3="{{ $product->stock_level_3 }}"
												data-stock_level_2="{{ $product->stock_level_2 }}"
												data-stock_level_1="{{ $product->stock_level_1 }}">
												<i class="fa fa-pencil-square-o"></i> Edit
											</button>
										</td>
										<td>
											<div class="product-img">
												<img alt="Product Image" class="img-responsive" width="100" height="80"
												src="{{ (!empty($product->picture)) ? Storage::disk('local')->url("Producrs/images/$product->picture") : 'http://placehold.it/60x50' }}">
											</div>
											<div class="modal fade" id="enlargeImageModal" tabindex="-1"
													 role="dialog" align="center"
													 aria-labelledby="enlargeImageModal" aria-hidden="true">
												<div class="modal-dialog modal-sm" >
													<div class="modal-body" align="center">
														<img src="" class="enlargeImageModalSource"  style="width:300;"
															height="300" >
													</div>
												</div> 
											</div>
										</td>
										<td>
										{{ (!empty($product->stockLevelFive)) ? $product->stockLevelFive->name : ''}} </br>
										{{ (!empty($product->stockLevelFour)) ? $product->stockLevelFour->name : ''}} </br> 
										{{ (!empty($product->stockLevelThree)) ? $product->stockLevelThree->name : ''}} </br> 
										{{ (!empty($product->stockLevelTwo)) ? $product->stockLevelTwo->name : ''}} </br> 
										{{ (!empty($product->stockLevelOne)) ? $product->stockLevelOne->name : ''}} 
										</td>
										<td>{{ (!empty($product->allow_vat)) ? $product->allow_vat: ''}} </td>
										<td>{{ (!empty($product->mass_net)) ? $product->mass_net:'' }} </td>
										<td>{{ (!empty($product->minimum_level)) ? $product->minimum_level : ''}} </td>
										<td>{{ (!empty($product->maximum_level)) ? $product->maximum_level : ''}} </td>
										<td>{{ (!empty($product->bar_code)) ? $product->bar_code : ''}} </td>
										<td>{{ (!empty($product->unit)) ? $product->unit : ''}} </td>
										<td>{{ (!empty($product->commodity_code)) ? $product->commodity_code : ''}} </td>
									</tr>
								@endforeach
							@endif
						</tbody>
						<tfoot>
							<tr>
								<th></th>
								<th>Image</th>
								<th>Location</th>
								<th>Allow Vat</th>
								<th>Mass Net</th>
								<th>Minimum Level</th>
								<th>Maximum Level</th>
								<th>Bar Code</th>
								<th>Unit</th>
								<th>Commodity Code</th>
							</tr>
						</tfoot>
					</table>
                </div>
				<div class="box-footer">
				@if (count($products->infosProduct) <= 0)
                    <button type="button" id="add-price_titles" class="btn btn-primary pull-right" data-toggle="modal"
                            data-target="#add-stock-info-modal">Add Details
                    </button>
				@endif
				</div>
			</div>
		</div>
    </div>
	<div class="row">
		<div class="col-sm-12">
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Preferred Suppliers</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i
									class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i
									class="fa fa-remove"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body" style="max-height: 190px; overflow-y: scroll;">
					<table id="example2" class="table table-bordered table-hover">
						<thead>
							<tr>
								<th></th>
								<th>Order No</th>
								<th>Supplier</th>
								<th>Description</th>
								<th>Inventory Code</th>
								<th>Date Last Processed</th>
							</tr>
						</thead>
						<tbody>
							@if (count($productPreferreds) > 0)
								@foreach ($productPreferreds as $productPreferred)
									<tr>
										<td>
											<button type="button" id="edit_info" class="btn btn-primary  btn-xs"
                                                data-toggle="modal" data-target="#edit-preferred-supplier-modal"
                                                data-id="{{ $productPreferred->id }}" 
												data-order_no="{{ $productPreferred->order_no }}"
												data-supplier_id="{{ $productPreferred->supplier_id }}"
												data-description="{{ $productPreferred->description }}"
												data-inventory_code="{{ $productPreferred->inventory_code }}"
												<i class="fa fa-pencil-square-o"></i> Edit
											</button>
										</td>
										<td>{{ (!empty($productPreferred->order_no)) ? $productPreferred->order_no : ''}} </td>
										<td>{{ (!empty($productPreferred->com_name)) ? $productPreferred->com_name : ''}} </td>
										<td>{{ (!empty($productPreferred->description)) ? $productPreferred->description : ''}} </td>
										<td>{{ (!empty($productPreferred->inventory_code)) ? $productPreferred->inventory_code: ''}} </td>
										<td>{{ (!empty($productPreferred->date_last_processed)) ? date(' d M Y', $productActivity->date_last_processed) : ''}} </td>
									</tr>
								@endforeach
							@endif
						</tbody>
						<tfoot>
							<tr>
								<th></th>
								<th>Order No</th>
								<th>Supplier</th>
								<th>Description</th>
								<th>Inventory Code</th>
								<th>Date Last Processed</th>
							</tr>
						</tfoot>
					</table>
                </div>
				<div class="box-footer">
                    <button type="button" id="add-price_titles" class="btn btn-primary pull-right" data-toggle="modal"
                            data-target="#add-preferred-supplier-modal">Add Supplier
                    </button>
				</div>
			</div>
		</div>
    </div>
    <div class="row">
		<div class="col-sm-12">
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Activities</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i
									class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i
									class="fa fa-remove"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body" style="max-height: 190px; overflow-y: scroll;">
					<table id="example2" class="table table-bordered table-hover">
						<thead>
						<tr>
							<th>Product name</th>
							<th>Date</th>
							<th>Action Performed</th>
							<th>Performed By</th>
							<th>Allocated to</th>
							<th style="text-align: center;">Balance Before</th>
							<th style="text-align: center;">Balance After</th>
							<th style="text-align: center;">Available Balance</th>
						</tr>
						</thead>
						<tbody>
							@if (count($productActivities) > 0)
								@foreach ($productActivities as $productActivity)
									<tr>
										<td>{{ (!empty($productActivity->product_name)) ? $productActivity->product_name : ''}} </td>
										<td>{{ (!empty($productActivity->action_date)) ? date(' d M Y', $productActivity->action_date) : ''}} </td>
										<td>{{ (!empty($productActivity->action)) ? $productActivity->action : ''}} </td>
										<td>{{ (!empty($productActivity->name)&& !empty($productActivity->surname)) ? $productActivity->name." ".$productActivity->surname: ''}}</td>
										<td>{{ (!empty($productActivity->allocated_firstname) && !empty($productActivity->allocated_surname)) ? $productActivity->allocated_firstname." ".$productActivity->allocated_surname: $productActivity->fleet_number." ".$productActivity->vehicle_registration }} </td>
										<td style="text-align: center;">{{ (!empty($productActivity->balance_before)) ? $productActivity->balance_before : 0}} </td>
										<td style="text-align: center;">{{ (!empty($productActivity->balance_after)) ? $productActivity->balance_after : 0}} </td>
										<td style="text-align: center;">{{ (!empty($productActivity->avalaible_stock)) ? $productActivity->avalaible_stock : 0}} </td>
									</tr>
								@endforeach
							@endif
						</tbody>
						<tfoot>
							<tr>
								<th>Product name</th>
								<th>Date</th>
								<th>Action Performed</th>
								<th>Performed By</th>
								<th>Allocated to</th>
								<th style="text-align: center;">Balance Before</th>
								<th style="text-align: center;">Balance After</th>
								<th style="text-align: center;">Available Balance</th>
							</tr>
						</tfoot>
					</table>
                </div>
				<div class="box-footer">
                    <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                </div>
			</div>
		</div>
    </div>
	        <!-- Include add expenditure and add income modals -->
            @include('products.partials.add_new_stock_info_modal')
			@include('products.partials.edit_stock_info_modal')
            @include('products.partials.add_prefered_suppliers_modal')
            @include('products.partials.edit_prefered_suppliers_modal')
@endsection

@section('page_script')
<!-- Select2 -->
<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>

<!-- bootstrap datepicker -->
<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

<!-- Ajax form submit -->
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<!-- iCheck -->
<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
<script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js"
		type="text/javascript"></script>
<!-- the main fileinput plugin file -->
<script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
<!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
<script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
<!-- Ajax dropdown options load -->
<script src="/custom_components/js/load_dropdown_options.js"></script>
<script type="text/javascript">

function postData(id, data)
{
	if (data == 'print_minutes')
		location.href = "/meeting/prnt_meeting/" + id;
	else if (data == 'email_minutes')
		location.href = "/meeting/email_meeting/" + id;
}
$('#back_button').click(function () {
            location.href = '/Product/Product/{{$products->category_id}}';
        });
$(function () {
	
	$('img').on('click', function () {
		$('.enlargeImageModalSource').attr('src', $(this).attr('src'));
		$('#enlargeImageModal').modal('show');
	});

	$(".select2").select2();

	$('#due_time').datetimepicker({
             format: 'HH:mm:ss'
    });
	$('#time_to').datetimepicker({
		 format: 'HH:mm:ss'
	});
	 
	 //Initialize iCheck/iRadio Elements
	$('input').iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green',
		increaseArea: '20%' // optional
	});

	$('.datepicker').datepicker({
		format: 'dd/mm/yyyy',
		autoclose: true,
		todayHighlight: true
		});
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
	//Post end task form to server using ajax (add)
	var stockID;
	var preferredID;
	// Add minute Submit
	$('#add_stock_info').on('click', function() {
		var strUrl = '/stock/stock_info/add/' + {{$products->id}};
		var formName = 'add-new-stock-info-form';
		var modalID = 'add-stock-info-modal';
		var submitBtnID = 'add_stock_info';
		var redirectUrl = '/stock/stockinfo/' + {{$products->id}};
		var successMsgTitle = 'Stock details Saved!';
		var successMsg = 'Stock details Has Been Successfully Saved!';
		modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
	});

	// Call Edit meeting modal/*data-meeting_id=""
	$('#edit-stock-info-modal').on('show.bs.modal', function (e) {
		var btnEdit = $(e.relatedTarget);
		stockID = btnEdit.data('id');
		var Description = btnEdit.data('description');
		var Location = btnEdit.data('location');
		var AllowVat = btnEdit.data('allow_vat');
		var MassNet = btnEdit.data('mass_net');
		var MinimumLevel = btnEdit.data('minimum_level');
		var MaximumLevel = btnEdit.data('maximum_level');
		var BarCode = btnEdit.data('bar_code');
		var Unit = btnEdit.data('unit');
		var CommodityCode = btnEdit.data('commodity_code');
		var stockImage = btnEdit.data('stock_image');
		var stock5 = btnEdit.data('stock_level_5');
		var stock4 = btnEdit.data('stock_level_4');
		var stock3 = btnEdit.data('stock_level_3');
		var stock2 = btnEdit.data('stock_level_2');
		var stock1 = btnEdit.data('stock_level_1');

		var modal = $(this);
		modal.find('#description').val(Description);
		modal.find('#location').val(Location);
		modal.find('select#allow_vat').val(AllowVat).trigger("change");
		modal.find('#mass_net').val(MassNet);
		modal.find('#minimum_level').val(MinimumLevel);
		modal.find('#maximum_level').val(MaximumLevel);
		modal.find('#bar_code').val(BarCode);
		modal.find('#unit').val(Unit);
		modal.find('#commodity_code').val(CommodityCode);
		modal.find('#stock_image').attr("src", stockImage);
		modal.find('#description').val(Description);
		modal.find('select#stock_level_5').val(stock5).trigger("change");
		modal.find('select#stock_level_4').val(stock4).trigger("change");
		modal.find('select#stock_level_3').val(stock3).trigger("change");
		modal.find('select#stock_level_2').val(stock2).trigger("change");
		modal.find('select#stock_level_1').val(stock1).trigger("change");
		
		//Load divisions drop down
		var parentDDID = '';
		var loadAllDivs = 1;
		var firstStockDDID = null;
		var parentContainer = $('#edit-stock-info-modal');
		@foreach($stock_levels as $stock_level)
			//Populate drop down on page load
			var ddID = '{{ 'stock_level_' . $stock_level->level }}';
			var postTo = '{!! route('stockdropdown') !!}';
			var selectedOption = '';
			var stockLevel = parseInt('{{ $stock_level->level }}');
			if (stockLevel == 5) selectedOption = stock5;
			else if(stockLevel == 4) selectedOption = stock4;
			else if(stockLevel == 3) selectedOption = stock3;
			else if(stockLevel == 2) selectedOption = stock2;
			else if(stockLevel == 1) selectedOption = stock1;
			var incInactive = -1;
			var loadAll = loadAllDivs;
			@if($loop->first)
				var selectFirstDiv = 0;
				var divHeadSpecific = 0;
				loadStockDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo, selectFirstDiv, divHeadSpecific, parentContainer);
				firstStockDDID = ddID;
			@else
				loadStockDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo, null, null, parentContainer);
			@endif
			parentDDID = ddID;
			loadAllDivs = 1;
		@endforeach
	});
	//Update meeting
	$('#update_stock_info').on('click', function () {
		var strUrl = '/stock/stock_info/update/' + stockID;
		var formName = 'edit-stock-info-form';
		var modalID = 'edit-stock-info-modal';
		var submitBtnID = 'update_stock_info';
		var successMsgTitle = 'Changes Saved!';
		var redirectUrl = '/stock/stockinfo/' + {{$products->id}};
		var successMsg = 'Stock information details has been updated successfully.';
		modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
		
	});
	// Preferred Supplier
	$('#add_pre_supplier').on('click', function () {
		var strUrl = '/stock/pre_supplier/add/' + {{$products->id}};
		var formName = 'add-new-pre-supploer-form';
		var modalID = 'add-preferred-supplier-modal';
		var submitBtnID = 'add_pre_supplier';
		var redirectUrl = '/stock/stockinfo/' + {{$products->id}};
		var successMsgTitle = 'Preferred Supplier Added!';
		var successMsg = 'Preferred Supplier has been Successfully Completed!';
		modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
	});

	// Call Edit meeting modal/*data-meeting_id="}"
	$('#edit-preferred-supplier-modal').on('show.bs.modal', function (e) {
		var btnEdit = $(e.relatedTarget);
		preferredID = btnEdit.data('id');
		var orderNo = btnEdit.data('order_no');
		var supplierID = btnEdit.data('supplier_id');
		var Description = btnEdit.data('description');
		var inventoryCode = btnEdit.data('inventory_code');
		var modal = $(this);
		modal.find('#order_no').val(orderNo);
		modal.find('#description').val(Description);
		modal.find('#inventory_code').val(inventoryCode);
		modal.find('select#supplier_id').val(supplierID).trigger('change');
	});
	$('#update_preferred_supplier').on('click', function () {
		var strUrl = '/stock/pre_supplier/update/' + preferredID;
		var formName = 'edit-preferred-supplier-form';
		var modalID = 'edit-preferred-supplier-modal';
		var submitBtnID = 'update_preferred_supplier';
		var successMsgTitle = 'Changes Saved!';
		var redirectUrl = '/stock/stockinfo/' + {{$products->id}};
		var successMsg = 'Preferred Supplier details has been updated successfully.';
		var method = 'PATCH';
		modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
	});
	
	//Load divisions drop down
	var parentDDID = '';
	var loadAllDivs = 1;
	@foreach($stock_levels as $stock_level)
		//Populate drop down on page load
		var ddID = '{{ 'stock_level_' . $stock_level->level }}';
		var postTo = '{!! route('stockdropdown') !!}';
		var selectedOption = '';
		var divLevel = parseInt('{{ $stock_level->level }}');
		var incInactive = -1;
		var loadAll = loadAllDivs;
		loadStockDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo);
		parentDDID = ddID;
		loadAllDivs = -1;
	@endforeach
});
</script>
@endsection