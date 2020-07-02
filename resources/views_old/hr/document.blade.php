@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">List Categories </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <form class="form-horizontal" method="POST" action="/hr/document">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered"> 
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
                                <th>Name</th>
                                <th>Description</th>
                                <th style="width: 5px; text-align: center;"></th>
                            </tr>
                              @foreach ($doc_type as $type)
                               <tr>
                                    <td style=" text-align: center;" nowrap>
                                        <button type="button" id="edit_compan" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-category-modal" data-id="{{ $type->id }}" data-name="{{ $type->name }}" data-description="{{$type->description}}" ><i class="fa fa-pencil-square-o"></i> Edit</button>
                                            <a href="/hr/category" id="edit_compan" class="btn btn-primary  btn-xs"   data-id="{{ $type->id }}" data-name="{{ $type->name }}" data-description="{{$type->description}}" ><i class="fa fa-eye"></i> Document Type</a>
                                    </td>
                                    <td>{{ $type->name }}</td>
                                    <td>{{ $type->description }}</td>
                                    <td>      
                                    <button type="button" id="view_ribbons" class="btn {{ (!empty($type->active) && $type->active == 1) ? " btn-danger " : "btn-success " }}
                                      btn-xs" onclick="postData({{$type->id}}, 'dactive');"><i class="fa {{ (!empty($type->active) && $type->active == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($type->active) && $type->active == 1) ? "De-Activate" : "Activate"}}</button>  
                                    </td>
                                </tr>  
                                   @endforeach  
                            </table>
                        </div>
                                   <!-- /.box-body -->
                    <div class="box-footer">
                     <button type="button" id="cat_module" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-category-modal">Add Category</button>  
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
        function postData(id, data)
        {
             if (data == 'dactive') location.href = "/hr/document/" + id + '/activate';
             
            //location.href = "/hr/firstlevel/dactive/" + id;
             // if (data == 'ribbons') location.href = "/hr/ribbons/" + id;

    
        }
        $(function () {
            /*
            var moduleId;
            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();
*/
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
              

        /*    var updatecategoryID;
            $('#add-category-modal').on('show.bs.modal', function (e) {
                    //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                updatecategoryID = btnEdit.data('id');
                var name = btnEdit.data('name');
                var description = btnEdit.data('description');
                //var employeeName = btnEdit.data('employeename');
                var modal = $(this);
                modal.find('#name').val(name);
                modal.find('#description').val(description);
                
             });*/

            // 
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

            // 

            //Post module form to server using ajax (ADD)
            $('#save_category').on('click', function() {
                //console.log('strUrl');
                var strUrl = '/hr/document/add/' +  'doc_type'; 
                var modalID = 'add-category-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    description: $('#'+modalID).find('#description').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'cat_module';
                var redirectUrl = '/hr/document';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The group has been updated successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

    /**/   $('#edit_category').on('click', function () {
                var strUrl = '/hr/document/' + doc_typeID;
                var modalID = 'edit-category-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    description: $('#'+modalID).find('#description').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'edit_category';
                var redirectUrl = '/hr/document';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'Category modal has been updated successfully.';
                var Method = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });
    });

    </script>
@endsection
