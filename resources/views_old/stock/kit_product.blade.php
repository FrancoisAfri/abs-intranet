@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Products - ({{$kit->name}}) </h3>
                </div>
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr>
                            <th></th>
                            <th>Category</th>
                            <th>Name</th>
							<th>Code</th>
                            <th style="text-align: center">Avalaible Stock</th>
							<th style="text-align: center">Number Required</th>
                            <th style="width: 40px"></th>
                        </tr>
                        @if (count($products) > 0)
                            @foreach($products as $product)
                                <tr>
									<td>
                                        <button type="button" id="edit_compan" class="btn btn-primary  btn-xs"
                                                data-toggle="modal" data-target="#edit-product-modal"
                                                data-id="{{ $product->id }}" data-amount_required="{{ $product->amount_required }}"><i
                                                    class="fa fa-pencil-square-o"></i> Edit
                                        </button>
                                    </td>
                                    <td>{{ (!empty($product->cat_name)) ?  $product->cat_name : ''}} </td>
                                    <td>{{ (!empty($product->prod_name)) ?  $product->prod_name : ''}} </td>
                                    <td>{{ (!empty($product->product_code)) ?  $product->product_code : ''}} </td>
									<td style="text-align: center">{{ (!empty( $product->avalaible_stock)) ?  $product->avalaible_stock : ''}} </td>
                                    <td style="text-align: center">{{ (!empty($product->amount_required)) ?  $product->amount_required : ''}} </td>
                                    <td nowrap>
                                        <button type="button" id="activate"
                                                class="btn {{ (!empty($product->status) && $product->status == 1) ? "btn-danger" : "btn-success" }} btn-xs"
                                                onclick="postData({{$product->id}}, 'actdeac');"><i
                                                    class="fa {{ (!empty($product->status) && $product->status == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($product->status) && $product->status == 1) ? "De-Activate" : "Activate"}}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        No Products to display, please start by adding a new Product.
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                    <button type="button" id="add_products_title" class="btn btn-primary pull-right" data-toggle="modal"
                            data-target="#add-product-modal">Add Product(s)
                    </button>
                </div>
            </div>
        </div>
        <!-- Include add new prime rate modal -->
        @include('stock.partials.add_product_to_kit')
		@include('stock.partials.edit_product_to_kit')
    </div>
@endsection
@section('page_script')
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
	<script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script>
        function postData(id, data) {
            if (data == 'actdeac')
                location.href = "/stock/productkitAct/" + id;
        }

        $('#back_button').click(function () {
            location.href = '/stock/kit_management';
        });
        $(function () {
            $(".select2").select2();
            var jobId;

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
            //Post module form to server using ajax (ADD)
			$('#add-product').on('click', function () {
				var strUrl = '/stock/products_kit/add/' + {{$kit->id}};
				var formName = 'add-product-form';
				var modalID = 'add-product-modal';
				var submitBtnID = 'add-product';
				var redirectUrl = '/product/kit/' + {{$kit->id}};
				var successMsgTitle = 'Products Added to Kit!';
				var successMsg = 'Product(s) have been successfully to kit.';
				modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
			
			var proID;
            $('#edit-product-modal').on('show.bs.modal', function (e) {
                //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                proID = btnEdit.data('id');
                var amountRequired = btnEdit.data('amount_required');
                var modal = $(this);
                modal.find('#number_required').val(amountRequired);
            });
            $('#edit_pro').on('click', function () {
                
				var strUrl = '/stock/product/update/' + proID;
                var formName = 'edit-product-form';
                var modalID = 'edit-product-modal';
                var submitBtnID = 'edit_pro';
                var redirectUrl = '/product/kit/' + {{$kit->id}};
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'product informations  have been updated successfully.';
                var Method = 'PATCH';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
        });
    </script>
@endsection