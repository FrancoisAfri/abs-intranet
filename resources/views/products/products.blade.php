@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Products({{$products->name}}) </h3>

                </div>
                 {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                <!-- /.box-header -->
                <div class="box-body">

				<table class="table table-bordered">
					 <tr><th style="width: 10px">#</th>
                     <th>Name</th>
                     <th>Description</th>
                     <th>Price</th>
                     <th style="width: 40px"></th>
                     </tr>
                    @if (count($products->productCategory) > 0)
						@foreach($products->productCategory as $category)
						 <tr id="categorys-list">
						   <td nowrap>
                          <button type="button" id="edit_job_title" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-product_title-modal" data-id="{{ $category->id }}" data-name="{{ $category->name }}" data-description="{{ $category->description }}"><i class="fa fa-pencil-square-o"></i> Edit</button>
                               <a href="{{ '/Product/price/' . $category->id }}" id="edit_compan" class="btn btn-primary  btn-xs"   data-id="{{ $category->id }}" data-name="{{ $category->name }}" data-description="{{$category->description}}"  ><i class="fa fa-money"></i> Prices</a></td>
						  <td>{{ (!empty($category->name)) ?  $category->name : ''}} </td>
						  <td>{{ (!empty( $category->description)) ?  $category->description : ''}} </td>
                          <td>{{ (!empty( $category->price)) ?  $category->price : ''}} </td>
						   <td>
                            <!--   leave here  -->
                            <button type="button" id="view_ribbons" class="btn {{ (!empty($category->status) && $category->status == 1) ? " btn-danger " : "btn-success " }}
                              btn-xs" onclick="postData({{$category->id}}, 'actdeac');"><i class="fa {{ (!empty($category->status) && $category->status == 1) ?
                              " fa-times " : "fa-check " }}"></i> {{(!empty($category->status) && $category->status == 1) ? "De-Activate" : "Activate"}}</button>
                            </td>
						</tr>
						@endforeach
                    @else
						<tr id="categorys-list">
						<td colspan="6">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No Products to display, please start by adding a new Products.
                        </div>
						</td>
						</tr>
                    @endif
					</table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
					<button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                    <button type="button" id="add_products_title" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-product_title-modal">Add Product</button>
                </div>
            </div>
        </div>

        <!-- Include add new prime rate modal -->
        @include('products.partials.add_position')
        @include('products.partials.edit_position')
    </div>
@endsection

@section('page_script')
<script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script>
		function postData(id , data ){   
            if(data == 'actdeac') location.href = "/Product/product_act/" + id; 
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
            $(window).on('resize', function() {
                $('.modal:visible').each(reposition);
            });

            //Show success action modal
            $('#success-action-modal').modal('show');

           

              //Post module form to server using ajax (ADD)
            $('#add-product_title').on('click', function() {
                //console.log('strUrl');
                var strUrl = 'add/{{$products->id}}';          
                var modalID = 'add-new-product_title-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    description: $('#'+modalID).find('#description').val(),
                     price: $('#'+modalID).find('#price').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'add_products_title';
                var redirectUrl = '/Product/Product/{{ $products->id }}';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The group has been updated successfully.';
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
                var price = btnEdit.data('price');
                
                //var employeeName = btnEdit.data('employeename');
                var modal = $(this);
                modal.find('#name').val(name);
                modal.find('#description').val(description);
                modal.find('#price').val(price);

             });
            $('#update-product_title').on('click', function () {
                var strUrl = '/Product/product_edit/' + Product_ID;
                // Product/category_edit/{Category}
                var modalID = 'edit-category-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    description: $('#'+modalID).find('#description').val(),
                    price: $('#'+modalID).find('#price').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'save_category';
                var redirectUrl = '/Product/Product/{{ $products->id }}';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'Category modal has been updated successfully.';
                var Method = 'PATCH';
         modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });


           
        });
    </script>
@endsection