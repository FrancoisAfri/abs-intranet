@extends('layouts.main_layout')
<!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
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
                        <th></th>
                        <th>Picture</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th style="width: 40px"></th>
                        </tr>
                        @if (count($products->infosProduct) > 0)
                            @foreach($products->infosProduct as $product)
                                <tr>
                                    <td><button type="button" id="edit_info" class="btn btn-primary  btn-xs"
                                                data-toggle="modal" data-target="#edit-stock-info-modal"
                                                data-id="{{ $product->id }}" data-picture="{{ $product->picture }}"
                                                data-description="{{ $product->description }}"
												data-location="{{ $product->location }}"><i
                                                    class="fa fa-pencil-square-o"></i> Edit
                                        </button></td>
                                    <td>
									 <img src="{{ (!empty($product->picture)) ? Storage::disk('local')->url("Products/images/$product->picture") : 'http://placehold.it/60x50' }}"
                                                     alt="Product Image" width="50" height="50"></td>
                                    <td>{{ !empty($product->location) ? $product->location : '' }}</td>
                                    <td>{{ (!empty( $product->description)) ?  $product->description : ''}} </td>
                                    <td nowrap>
                                        
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
                            data-target="#add-stock-info-modal">Add Info
                    </button>
                </div>
            </div>
        </div>

        <!-- Include add new prime rate modal -->
        @include('products.partials.add_new_stock_info_modal')
        @include('products.partials.edit_stock_info_modal')
    </div>
@endsection

@section('page_script')
	 <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
                        type="text/javascript"></script>
	<!-- the main fileinput plugin file -->
	<script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
	<!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
	<script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
	<script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script>
        function postData(id, data) {
            if (data == 'actdeac')
                location.href = "/hr/job_title_active/" + id;
        }

        $('#back').click(function () {
            location.href = '/stock/stockinfo/{{$products->id}}';
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
            
			$('#add_stock_info').on('click', function () {
				var strUrl = '/stock/stock_info/add/{{$products->id}}';
				var formName = 'add-new-stock-info-form';
				var modalID = 'add-stock-info-modal';
				//var modal = $('#'+modalID);
				var submitBtnID = 'add_stock_info';
				var redirectUrl = '/stock/stockinfo/{{$products->id}}';
				var successMsgTitle = 'Stock Info Added!';
				var successMsg = 'The stock info  has been updated successfully.';
				modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
			});
			
            var StockID;
			$('#edit-stock-info-modal').on('show.bs.modal', function (e) {
				//console.log('kjhsjs');
				var btnEdit = $(e.relatedTarget);
				  if (parseInt(btnEdit.data('id')) > 0) {
				  StockID = btnEdit.data('id');
				 }
				var Location = btnEdit.data('location');
				var Description = btnEdit.data('description');
				var images = btnEdit.data('images');
				var modal = $(this);
				modal.find('#location').val(Location);
				modal.find('#description').val(Description);
			});
			
			$('#update_stock_info').on('click', function () {
				var strUrl = '/stock/stock_info/edit/' + ImageID;
				var formName = 'edit-stock-info-form';
				var modalID = 'edit-stock-info-modal';
				var submitBtnID = 'update_stock_info';
				var redirectUrl = '/stock/stockinfo/{{$products->id}}';
				var successMsgTitle = 'Stock Info Modified!';
				var successMsg = 'Stock Info has been updated successfully.';
				var Method = 'PATCH'
				modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
			});
        });
    </script>
@endsection