@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Product Catagories </h3>

                </div>
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 10px"></th>
                            <th>Name</th>
                            <th>Description</th>
							<th>Stock Type</th>
                            <th style="width: 40px"></th>
                        </tr>
                        @if (count($ProductCategory) > 0)
                            @foreach($ProductCategory as $category)
                                <tr id="categorys-list">
                                    <td nowrap>
                                        <button type="button" id="edit_compan" class="btn btn-primary  btn-xs"
                                                data-toggle="modal" data-target="#edit-category-modal"
                                                data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                                data-description="{{$category->description}}"
                                                data-stock_type="{{$category->stock_type}}"
												><i
                                                    class="fa fa-pencil-square-o"></i> Edit
                                        </button>
                                        <a href="{{ '/Product/Product/' . $category->id }}" id="edit_compan"
                                           class="btn btn-primary  btn-xs" data-id="{{ $category->id }}"
                                           data-name="{{ $category->name }}"
                                           data-description="{{$category->description}}"><i class="fa fa-money"></i>
                                            Products</a></td>
                                    <td>{{ (!empty($category->name)) ?  $category->name : ''}} </td>
                                    <td>{{ (!empty( $category->description)) ?  $category->description : ''}} </td>
									<td>{{ (!empty($category->stock_type)) ?  $stockTypeArray[$category->stock_type] : ''}} </td>
                                    <td>
                                        <!--   leave here  -->
                                        <button type="button" id="view_ribbons"
                                                class="btn {{ (!empty($category->status) && $category->status == 1) ? " btn-danger " : "btn-success " }}
                                                        btn-xs" onclick="postData({{$category->id}}, 'actdeac');"><i
                                                    class="fa {{ (!empty($category->status) && $category->status == 1) ?
							  " fa-times " : "fa-check " }}"></i> {{(!empty($category->status) && $category->status == 1) ? "De-Activate" : "Activate"}}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr id="categories-list">
                                <td colspan="6">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        No category to display, please start by adding a new category..
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" id="cat_module" class="btn btn-primary pull-right" data-toggle="modal"
                            data-target="#add-category-modal">Add New Category
                    </button>
                </div>
            </div>
        </div>
        <!-- Include add new prime rate modal -->
        @include('products.partials.add_new_productCategory')
        @include('products.partials.edit_category_modal')
    </div>
@endsection

@section('page_script')
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script>
        function postData(id, data) {
            if (data == 'actdeac') location.href = "/Product/category/" + id;
        }

        $(function () {
            var moduleId;
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
            //Post module form to server using ajax (ADD)
            $('#save_category').on('click', function () {
                //console.log('strUrl');
                var strUrl = '/Product/categories';
                var modalID = 'add-category-modal';
                var objData = {
                    name: $('#' + modalID).find('#name').val(),
                    description: $('#' + modalID).find('#description').val(),
                    stock_type: $('#' + modalID).find('#stock_type').val(),
                    _token: $('#' + modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'save_category';
                var redirectUrl = '/product/Categories';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The group has been updated successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            var doc_typeID;
            $('#edit-category-modal').on('show.bs.modal', function (e) {
                //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                doc_typeID = btnEdit.data('id');
                var name = btnEdit.data('name');
                var description = btnEdit.data('description');
				var stockType = btnEdit.data('stock_type');
                //var employeeName = btnEdit.data('employeename');
				console.log(stockType);
                var modal = $(this);
                modal.find('#name').val(name);
                modal.find('#description').val(description);
				modal.find('select#stock_type').val(stockType);

            });
            $('#edit_category').on('click', function () {
                var strUrl = '/Product/category_edit/' + doc_typeID;
                // Product/category_edit/{Category}
                var modalID = 'edit-category-modal';
                var objData = {
                    name: $('#' + modalID).find('#name').val(),
                    description: $('#' + modalID).find('#description').val(),
                    stock_type: $('#' + modalID).find('#stock_type').val(),
                    _token: $('#' + modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'edit_category';
                var redirectUrl = '/product/Categories';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'Category modal has been updated successfully.';
                var Method = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });

        });
    </script>
@endsection
