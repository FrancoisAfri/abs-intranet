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
					 <tr><th style="width: 10px">#</th>
                     <th>Price</th>
                     <th>Start Date</th>
                     <th>End Date</th>
                     <th style="width: 40px"></th>
                     </tr>
                    @if (count($Productprice) > 0)
						@foreach($Productprice as $jobTitle)
						 <tr id="jobtitles-list">
						   <td nowrap>
                          <button type="button" id="edit_job_title" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-new-price-modal" data-id="{{ $jobTitle->id }}" data-name="{{ $jobTitle->name }}" data-description="{{ $jobTitle->description }}"><i class="fa fa-pencil-square-o"></i> Edit</button>
                              
						  <td>{{ (!empty($jobTitle->price)) ?  $jobTitle->price : ''}} </td>
                            <td>{{ !empty($jobTitle->start_date) ? date('d M Y - H:m:s', $jobTitle->start_date) : '' }}</td>
						 <!--  <td>{{ (!empty( $jobTitle->start_date)) ?  $jobTitle->start_date : ''}} </td> -->
                          <td>{{ (!empty( $jobTitle->end_date)) ?  date('d M Y - H:m:s', $jobTitle->end_date) : ''}} </td>
						  <td nowrap>
                              <button type="button" id="view_job_title" class="btn {{ (!empty($jobTitle->status) && $jobTitle->status == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$jobTitle->id}}, 'actdeac');"><i class="fa {{ (!empty($jobTitle->status) && $jobTitle->status == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($jobTitle->status) && $jobTitle->status == 1) ? "De-Activate" : "Activate"}}</button>
                          </td>
						</tr>
						@endforeach
                    @else
						<tr id="jobtitles-list">
						<td colspan="6">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
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
                    <button type="button" id="add-price_titles" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-price-modal">Add Price</button>
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
		function postData(id, data)
		{
			if (data == 'actdeac')
				location.href = "/hr/job_title_active/" + id;
		}
          $('#back').click(function () {
                location.href = '/Product/Product/{{ $products->id }}';
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
            $('#add-price_title').on('click', function() {
                //console.log('strUrl');
                var strUrl = '/Product/price/add/{{$products->id}}';          
                var modalID = 'add-new-price-modal';
                var objData = {
                    // name: $('#'+modalID).find('#name').val(),
                    // description: $('#'+modalID).find('#description').val(),
                     price: $('#'+modalID).find('#price').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'add-price_title';
                var redirectUrl = '/Product/price/add/{{ $products->id }}';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The price has been updated successfully.';
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