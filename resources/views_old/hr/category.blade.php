@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">List of Document types </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <form class="form-horizontal" method="POST" action="/hr/category">
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
                             @if (count($doc_type) > 0)
                              @foreach ($doc_type as $type)
                               <tr>
                                <tr id="doc_type-list">
                                    <td nowrap>
                                        <button type="button" id="edit_compan" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-document-modal" data-id="{{ $type->id }}" data-name="{{ $type->name }}" data-description="{{$type->description}}" ><i class="fa fa-pencil-square-o"></i> Edit</button>
                                           
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
                                       @else
                               <tr id="doc_type-list">
                        <td colspan="5">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No Document Type to display, please start by adding a new Document Type.
                        </div>
                        </td>
                        </tr>
                           @endif 
                            </table>
                        </div>
                                   <!-- /.box-body -->
                    <div class="box-footer">
                     <button type="button" id="doc_module" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-document-modal">Add Document</button>  

                      <!--  <a type="button" href="/hr/document" id="" class="btn btn-default pull-left">Back</a>  -->
                    </div>
        </div>
            </div>
   <!-- Include add new prime rate modal -->
        @include('hr.partials.add_document_modal')
        @include('hr.partials.edit_document_modal')
     
  
              </div>
@endsection

@section('page_script')
<!-- Ajax form submit -->
<script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script>
        function postData(id, data)
        {
             if (data == 'dactive') location.href = "/hr/category/" + id + '/activate';
             
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
              
        var edit_DocID;
        $('#edit-document-modal').on('show.bs.modal', function (e) {
            //console.log('kjhsjs');
            var btnEdit = $(e.relatedTarget);
            edit_DocID = btnEdit.data('id');
            var name = btnEdit.data('name');
            var description = btnEdit.data('description');
            var modal = $(this);
            modal.find('#name').val(name);
            modal.find('#description').val(description);
           
        });

            //  var docID;
            // $('#add-document-modal').on('show.bs.modal', function (e) {
            //     var btnEdit = $(e.relatedTarget);
            //     docID = btnEdit.data('id');
            //     var name = btnEdit.data('name');
            //     var description = btnEdit.data('description');
            //     var modal = $(this);
            //     modal.find('#name').val(name);
            //     modal.find('#description').val(description);  
            //  });

             //Post module form to server using ajax (ADD)
            $('#save_doc').on('click', function() {
                var strUrl = '/hr/addDoctype/{{ $category }}'; 
                var modalID = 'add-document-modal';
                var objData = {
                     name: $('#'+modalID).find('#name').val()
                    ,description: $('#'+modalID).find('#description').val()
                   , _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'add-document';
                var redirectUrl = '/hr/category/{{ $category }}';
                var successMsgTitle = 'Document Type Saved!';
                var successMsg = 'The Document Type has been Saved successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            // 
             var edit_DocID;
            $('#edit-document-modal').on('show.bs.modal', function (e) {
                    //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                edit_DocID = btnEdit.data('id');
                var name = btnEdit.data('name');
                var description = btnEdit.data('description');
                //var employeeName = btnEdit.data('employeename');
                var modal = $(this);
                modal.find('#name').val(name);
                modal.find('#description').val(description);
                
             });

              $('#edit_document').on('click', function () {
            var strUrl = '/hr/Doc_type_edit/' + edit_DocID;
            var objData = {
                 name: $('#edit-document-modal').find('#name').val()
                ,description: $('#edit-document-modal').find('#description').val()
                , _token: $('#edit-document-modal').find('input[name=_token]').val()
            };
            var modalID = 'edit-document-modal';
            var submitBtnID = 'edit_document';
            var redirectUrl = '/hr/setup';
            var successMsgTitle = 'Changes Saved!';
            var successMsg = 'The Document Type has been changed successfully.';
            // var method = 'PATCH';
           modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });
            // 


    </script>
@endsection
