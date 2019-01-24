@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Products({{$category->name}})</h3>
                </div>
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <!-- /.box-header -->
                <div class="box-body">
				<div class="no-padding" style="overflow-x: scroll;">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Name</th>
                            <th>Code</th>
                            @if (isset($userAccess) && $userAccess->access_level > 1)
                                <th>Price</th>
                            @else
                                <th></th>
                            @endif
                            <th>Stock Type</th>
                            <th>Is Vatable</th>
                            <th style="width: 40px"></th>
                            <th style="width: 40px"></th>
                        </tr>
                        @if (count($products) > 0)
                            @foreach($products as $product)
                                <tr id="categorys-list">
                                    <td nowrap>
                                        <button type="button" id="edit_job_title" class="btn btn-primary  btn-xs"
                                                data-toggle="modal" data-target="#edit-product_title-modal"
                                                data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                                data-product_code="{{ $product->product_code }}"
                                                data-description="{{ $product->description }}"
                                                data-price="{{ $product->price }}"
                                                data-is_vatable="{{ $product->is_vatable }}"
												data-stock_type="{{ $product->stock_type }}"><i
                                                    class="fa fa-pencil-square-o"></i> Edit
                                        </button>
                                        <a href="{{ '/Product/price/' . $product->id }}" id="edit_compan"
                                           class="btn btn-primary  btn-xs" data-id="{{ $product->id }}"
                                           data-name="{{ $product->name }}"
                                           data-description="{{$product->description}}"><i class="fa fa-money"></i>
                                            Prices</a></td>
                                    <td>{{ (!empty($product->name)) ?  $product->name : ''}} </td>
                                    <td>{{ (!empty($product->product_code)) ?  $product->product_code : ''}} </td>
                                    @if (isset($userAccess) && $userAccess->access_level > 1)
                                        <td>{{ (!empty($product->current_price)) ?  'R ' .number_format($product->current_price, 2) : 0}} </td>
                                    @else
                                        <td></td>
                                    @endif
									<td>{{ (!empty($product->stock_type)) ?  $stockTypeArray[$product->stock_type] : ''}} </td>
									<td>{{ (!empty($product->is_vatable))  && $product->is_vatable == 2 ?  'No' : 'Yes'}} </td>
                                    @if ((!empty($product->stock_type))  && $product->stock_type == 1)
                                        <td><a href="/stock/stockinfo/{{$product->id}}" id="srock_info" class="btn btn-primary  btn-xs"><i class="fa fa-eye"></i> Stock Info</a> 
                                       <a href="/stock/stocklocation/{{$product->id}}" id="srock_location" class="btn btn-primary  btn-xs"><i class="fa fa-eye"></i> Stock Location</a> 
									</td>
                                    @else
                                        <td></td>
									@endif
									<td>
                                        <!--   leave here  -->
                                        <button type="button" id="view_ribbons"
                                                class="btn {{ (!empty($product->status) && $product->status == 1) ? " btn-danger " : "btn-success " }}
                                                        btn-xs" onclick="postData({{$product->id}}, 'actdeac');"><i
                                                    class="fa {{ (!empty($product->status) && $product->status == 1) ?
                              " fa-times " : "fa-check " }}"></i> {{(!empty($product->status) && $product->status == 1) ? "De-Activate" : "Activate"}}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr id="categorys-list">
                                <td colspan="9">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        No Products to display, please start by adding a new Products.
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                    <button type="button" id="add_products_title" class="btn btn-primary pull-right" data-toggle="modal"
                            data-target="#add-new-product_title-modal">Add Product
                    </button>
                </div>
            </div>
        </div>
        <!-- Include add new prime rate modal -->
        @include('products.partials.add_product')
        @include('products.partials.edit_product')
    </div>
@endsection
@section('page_script')
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script>
        function postData(id, data) {
            if (data == 'actdeac') location.href = "/Product/product_act/" + id;
        }

        $('#back_button').click(function () {
            location.href = '/product/Categories';
        });
        $(function () {
            var jobId;

            // document.getElementById("back_button").onclick = function () {
            // location.href = "/hr/job_title";
            //      }
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
            //Post module form to server using ajax (ADD)
            $('#add-product_title').on('click', function () {
                //console.log('strUrl');
                var strUrl = 'add/{{$category->id}}';
                var modalID = 'add-new-product_title-modal';
                var objData = {
                    name: $('#' + modalID).find('#name').val(),
                    description: $('#' + modalID).find('#description').val(),
                    price: $('#' + modalID).find('#price').val(),
                    product_code: $('#' + modalID).find('#product_code').val(),
                    stock_type: $('#' + modalID).find('#stock_type').val(),
                    is_vatable: $('#' + modalID).find('#is_vatable').val(),
                    _token: $('#' + modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'add-product_title';
                var redirectUrl = '/Product/Product/{{ $category->id }}';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The product has been saved successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            var Product_ID;
            $('#edit-product_title-modal').on('show.bs.modal', function (e) {
                //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                Product_ID = btnEdit.data('id');
                var name = btnEdit.data('name');
                var description = btnEdit.data('description');
                var productCode = btnEdit.data('product_code');
                var price = btnEdit.data('price');
                var stockType = btnEdit.data('stock_type');
                var isVatable = btnEdit.data('is_vatable');
console.log(isVatable);
console.log('ddddddddddd');
                //var employeeName = btnEdit.data('employeename');
                var modal = $(this);
                modal.find('#name').val(name);
                modal.find('#product_code').val(productCode);
                modal.find('#description').val(description);
                modal.find('#price').val(price);
				modal.find('select#stock_type').val(stockType);
				modal.find('select#is_vatable').val(isVatable);

            });
            $('#update-product_title').on('click', function () {
                var strUrl = '/Product/product_edit/' + Product_ID;
                // Product/category_edit/{Category}
                var modalID = 'edit-product_title-modal';
                var objData = {
                    name: $('#' + modalID).find('#name').val(),
                    description: $('#' + modalID).find('#description').val(),
                    price: $('#' + modalID).find('#price').val(),
                    product_code: $('#' + modalID).find('#product_code').val(),
                    stock_type: $('#' + modalID).find('#stock_type').val(),
                    is_vatable: $('#' + modalID).find('#is_vatable').val(),
                    _token: $('#' + modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'update-product_title';
                var redirectUrl = '/Product/Product/{{ $category->id }}';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'Category modal has been updated successfully.';
                var Method = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });
        });
    </script>
@endsection