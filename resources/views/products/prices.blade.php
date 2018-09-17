@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Products({{  $products->name}}) </h3>
                </div>
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <!-- /.box-header -->
                <div class="box-body">

                    <table class="table table-bordered">
                        <th>Price</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th style="width: 40px"></th>
                        </tr>
						<tr id="jobtitles-list">
							<td>{{ (!empty($products->price)) ?  'R' .' '.number_format($products->price, 2) : ''}} </td>
							<td></td>
							<td></td>
							<td nowrap>
							</td>
						</tr>
                        @if (count($Productprice) > 0)
                            @foreach($Productprice as $product)
                                <tr id="jobtitles-list">
                                    <td>{{ (!empty($product->price)) ?  'R' .number_format($product->price, 2) : ''}} </td>
                                    <td>{{ !empty($product->start_date) ? date('d M Y - H:m:s', $product->start_date) : '' }}</td>
                                    <td>{{ (!empty( $product->end_date)) ?  date('d M Y - H:m:s', $product->end_date) : ''}} </td>
                                    <td nowrap>
                                        <button type="button" id="view_job_title"
                                                class="btn {{ (!empty($product->status) && $product->status == 1) ? "btn-danger" : "btn-success" }} btn-xs"
                                                onclick="postData({{$product->id}}, 'actdeac');"><i
                                                    class="fa {{ (!empty($product->status) && $product->status == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($product->status) && $product->status == 1) ? "De-Activate" : "Activate"}}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr id="jobtitles-list">
                                <td colspan="6">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        No Prices to display, please start by adding a new Prices.
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" class="btn btn-default pull-left" id="back">Back</button>
                    <button type="button" id="add-price_titles" class="btn btn-primary pull-right" data-toggle="modal"
                            data-target="#add-new-price-modal">Add Price
                    </button>
                </div>
            </div>
        </div>

        <!-- Include add new prime rate modal -->
        @include('products.partials.add_price_modal')
        @include('products.partials.edit_new_price-modal')
    </div>
@endsection

@section('page_script')
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script>
        function postData(id, data) {
            if (data == 'actdeac')
                location.href = "/hr/job_title_active/" + id;
        }

        $('#back').click(function () {
            location.href = '/Product/Product/{{ $products->category_id }}';
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
            $('#add-price_title').on('click', function () {
                //console.log('strUrl');
                var strUrl = '/Product/price/add/{{$products->id}}';
                var modalID = 'add-new-price-modal';
                var objData = {
                    // name: $('#'+modalID).find('#name').val(),
                    // description: $('#'+modalID).find('#description').val(),
                    price: $('#' + modalID).find('#price').val(),
                    _token: $('#' + modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'add-price_title';
                var redirectUrl = '/Product/price/{{ $products->id }}';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The price has been updated successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            var Price_ID;
            $('#edit-product_title-modal').on('show.bs.modal', function (e) {
                //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                Price_ID = btnEdit.data('id');
                var price = btnEdit.data('price');

                //var employeeName = btnEdit.data('employeename');
                var modal = $(this);
                modal.find('#price').val(price);

            });
            $('#update-product_title').on('click', function () {
                var strUrl = '/Product/product_edit/' + Product_ID;
                // Product/category_edit/{Category}
                var modalID = 'edit-category-modal';
                var objData = {
                    name: $('#' + modalID).find('#name').val(),
                    description: $('#' + modalID).find('#description').val(),
                    price: $('#' + modalID).find('#price').val(),
                    _token: $('#' + modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'update-product_title';
                var redirectUrl = '/Product/Product/{{ $products->id }}';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'Category modal has been updated successfully.';
                var Method = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });


        });
    </script>
@endsection