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
					<h3 class="box-title">Stock Location ({{  $products->name}})</h3>
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
								@if (count($stock_levels) > 0)
									@foreach ($stock_levels as $level)
										<th>{{$level->name}}</th>
									@endforeach
								@endif
							</tr>
						</thead>
						<tbody>
							@if (count($products->productLocation) > 0)
								@foreach ($products->productLocation as $product)
									<tr>
										<td>
											<button type="button" id="edit_info" class="btn btn-primary  btn-xs"
                                                data-toggle="modal" data-target="#edit-stock-location-modal"
                                                data-id="{{ $product->id }}"
												data-stock_level_5="{{ $product->stock_level_5 }}" 
												data-stock_level_4="{{ $product->stock_level_4 }}"
												data-stock_level_3="{{ $product->stock_level_3 }}"
												data-stock_level_2="{{ $product->stock_level_2 }}"
												data-stock_level_1="{{ $product->stock_level_1 }}">
												<i class="fa fa-pencil-square-o"></i> Edit
											</button>
										</td>
										<td>{{ (!empty($product->stockLevelFive)) ? $product->stockLevelFive->name : ''}}</td>
										<td>{{ (!empty($product->stockLevelFour)) ? $product->stockLevelFour->name : ''}}</td>
										<td>{{ (!empty($product->stockLevelThree)) ? $product->stockLevelThree->name : ''}}</td>
										<td>{{ (!empty($product->stockLevelTwo)) ? $product->stockLevelTwo->name : ''}}</td> 
										<td>{{ (!empty($product->stockLevelOne)) ? $product->stockLevelOne->name : ''}}</td>
									</tr>
								@endforeach
							@endif
						</tbody>
						<tfoot>
							<tr>
								<th></th>
								@if (count($stock_levels) > 0)
									@foreach ($stock_levels as $level)
										<th>{{$level->name}}</th>
									@endforeach
								@endif
							</tr>
						</tfoot>
					</table>
                </div>
				<div class="box-footer">
					<button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                    <button type="button" id="add-price_titles" class="btn btn-primary pull-right" data-toggle="modal"
                            data-target="#add-stock-location-modal">Add New Location
                    </button>
				</div>
			</div>
		</div>
    </div>
	<!-- Include add expenditure and add income modals -->
	@include('products.partials.add_product_location_modal')
	@include('products.partials.edit_stock_location_modal')
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

$('#back_button').click(function () {
    location.href = '/Product/Product/{{$products->category_id}}';
});
$(function () {
	
	$(".select2").select2();

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
	// Add minute Submit
	$('#add_stock_location').on('click', function() {
		var strUrl = '/stock/stock_loc/add/' + {{$products->id}};
		var formName = 'add-new-stock-location-form';
		var modalID = 'add-stock-location-modal';
		var submitBtnID = 'add_stock_location';
		var redirectUrl = '/stock/stocklocation/' + {{$products->id}};
		var successMsgTitle = 'Stock details Saved!';
		var successMsg = 'Stock details Has Been Successfully Saved!';
		modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
	});

	// Call Edit meeting modal/*data-meeting_id=""
	$('#edit-stock-location-modal').on('show.bs.modal', function (e) {
		var btnEdit = $(e.relatedTarget);
		stockID = btnEdit.data('id');
		var stock5 = btnEdit.data('stock_level_5');
		var stock4 = btnEdit.data('stock_level_4');
		var stock3 = btnEdit.data('stock_level_3');
		var stock2 = btnEdit.data('stock_level_2');
		var stock1 = btnEdit.data('stock_level_1');

		var modal = $(this);
		modal.find('select#stock_level_5').val(stock5).trigger("change");
		modal.find('select#stock_level_4').val(stock4).trigger("change");
		modal.find('select#stock_level_3').val(stock3).trigger("change");
		modal.find('select#stock_level_2').val(stock2).trigger("change");
		modal.find('select#stock_level_1').val(stock1).trigger("change");
		
		//Load divisions drop down
		var parentDDID = '';
		var loadAllDivs = 1;
		var firstStockDDID = null;
		var parentContainer = $('#edit-stock-location-modal');
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
	$('#update_stock_location').on('click', function () {
		var strUrl = '/stock/stock_loc/update/' + stockID;
		var formName = 'edit-stock-location-form';
		var modalID = 'edit-stock-location-modal';
		var submitBtnID = 'update_stock_location';
		var successMsgTitle = 'Changes Saved!';
		var redirectUrl = '/stock/stocklocation/' + {{$products->id}};
		var successMsg = 'Stock Location details has been updated successfully.';
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