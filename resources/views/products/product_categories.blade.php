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
                     <tr><th style="width: 10px"></th>
                     <th>Name</th>
                     <th>Description</th>
                   
                     <th style="width: 40px"></th>
                     </tr>
                    @if (count($ProductCategory) > 0)
                        @foreach($ProductCategory as $jobTitle)
                         <tr id="jobtitles-list">
                           <td nowrap>
                         <button type="button" id="edit_compan" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-category-modal" data-id="{{ $jobTitle->id }}" data-name="{{ $jobTitle->name }}" data-description="{{$jobTitle->description}}" ><i class="fa fa-pencil-square-o"></i> Edit</button>
                               <a href="{{ '/Product/Product/' . $jobTitle->id }}" id="edit_compan" class="btn btn-primary  btn-xs"   data-id="{{ $jobTitle->id }}" data-name="{{ $jobTitle->name }}" data-description="{{$jobTitle->description}}"  ><i class="fa fa-money"></i> Products</a></td>
                          <td>{{ (!empty($jobTitle->name)) ?  $jobTitle->name : ''}} </td>
                          <td>{{ (!empty( $jobTitle->description)) ?  $jobTitle->description : ''}} </td>
                          
                          <td nowrap>
                            <button type="button" id="view_ribbons" class="btn {{ (!empty($jobTitle->active) && $jobTitle->active == 1) ? " btn-danger " : "btn-success " }}
                                      btn-xs" onclick="postData({{$jobTitle->id}}, 'dactive');"><i class="fa {{ (!empty($jobTitle->active) && $jobTitle->active == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($jobTitle->active) && $jobTitle->active == 1) ? "De-Activate" : "Activate"}}</button>
                                    </td>
                                </tr>
                                   @endforeach
                               @else
                               <tr id="categories-list">
                        <td colspan="5">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No category to display, please start by adding a new category..
                        </div>
                        </td>
                        </tr>
                           @endif
                            </table>
                        </div>
                                   <!-- /.box-body -->
                    <div class="box-footer">
                     <button type="button" id="cat_module" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-category-modal">Add new Category </button>
                    </div>
             </div>
        </div>
   <!-- Include add new prime rate modal -->
        @include('hr.partials.add_category_modal')
        @include('hr.partials.edit_category_modal')


</div>


@endsection

@section('page_script')
<!-- Ajax form submit -->
<script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script>
        function postData(id , data ){
            if (data == 'qual') location.href = "/hr/addqul/" + id;
            else if (data == 'doc') location.href = "/hr/adddoc/" + id;
            else if (data == 'dactive') location.href = "/Product/category/" + id;
            else if (data == 'activateGroupLevel') location.href = '/hr/grouplevel/activate/' + id;
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
            $(window).on('resize', function() {
                $('.modal:visible').each(reposition);
            });
    
            //

            //Post module form to server using ajax (ADD)
            $('#save_category').on('click', function() {
                //console.log('strUrl');
                var strUrl = '/Product/categories';
                var modalID = 'add-category-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    description: $('#'+modalID).find('#description').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'cat_module';
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
                //var employeeName = btnEdit.data('employeename');
                var modal = $(this);
                modal.find('#name').val(name);
                modal.find('#description').val(description);

             });
            $('#edit_category').on('click', function () {
                var strUrl = '/Product/category_edit/' + doc_typeID;
                // Product/category_edit/{Category}
                var modalID = 'edit-category-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    description: $('#'+modalID).find('#description').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'save_category';
                var redirectUrl = '/product/Categories';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'Category modal has been updated successfully.';
                var Method = 'PATCH';
         modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });

        });
    </script>
@endsection
