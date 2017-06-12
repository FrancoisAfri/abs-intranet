@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Employees Group Levels</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <form class="form-horizontal" method="POST" action="/hr/grouplevel">
                  <!--   {{ csrf_field() }}
                    {{ method_field('PATCH') }} -->
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered"> 
                            <tr>
                                <th style="width: 10x; text-align: center;"></th>
                                <th>Level</th>
                                <th>Name</th>
                                <th>Plural Name</th>
                                <th style="width: 40px; text-align: center;"></th>
                            </tr>
                 
                            @foreach ($division_types as $division_type)
                                <tr>
                                    <td style="width: 5px; text-align: center;"><button type="button" id="edit_grouplevel" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-grouplevel-modal" data-id="{{ $division_type->id }}" data-name="{{ $division_type->name }}" data-plural_name="{{ $division_type->plural_name }}" data-level="{{ $division_type->level }}"><i class="fa fa-pencil-square-o"></i> Edit</button></td>
                                    <td>Employee Group Level {{ $division_type->level }}</td>
                                    <td>{{ $division_type->name }}</td>
                                    <td>{{ $division_type->plural_name }}</td>
                                    <td style="width: 5px; text-align: center;">
                                        @if ($division_type->name!='')
                                            <button type="button" id="view_ribbons" class="btn {{ (!empty($division_type->active) && $division_type->active == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$division_type->id}});"><i class="fa {{ (!empty($division_type->active) && $division_type->active == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($division_type->active) && $division_type->active == 1) ? "De-Activate" : "Activate"}}</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                
                        <!-- /.box-body -->
              
                </form>
        </div>
        <!-- Include add new prime rate modal -->
          @include('hr.partials.edit_group_level')
    </div>
    </div>
    <!--  -->
    <div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Qualification Types</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <!-- /.box-header -->

            <div class="box-body"> <!-- {{-- start custom leave--}} -->
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 10px; text-align: center;"></th>
                        <th>Qualification Type Name</th>
                        <th>Description</th>
                        <th style="width: 40px; text-align: center;"></th>
                    </tr> @if (count($Qualif_type) > 0)
                    @foreach($Qualif_type as $leavecustom)
                    <tr id="modules-list">
                        <td nowrap>
                            <button type="button" id="edit_leave" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-qualificationType-modal" data-id="{{ $leavecustom->id }}" data-name="{{$leavecustom->name}}" data-description="{{ $leavecustom->description }}"> <i class="fa fa-pencil-square-o">
                                </i> Edit</button>
                        </td>
                        <td>{{ $leavecustom->name }} </td>
                        <td>{{ $leavecustom->description }} </td>
                        <td>
                            <!--   leave here  -->
                            <button type="button" id="view_ribbons" class="btn {{ (!empty($leavecustom->status) && $leavecustom->status == 1) ? " btn-danger " : "btn-success " }}
                                        btn-xs" onclick="postData({{$leavecustom->id}}, 'qual');"><i class="fa {{ (!empty($leavecustom->status) && $leavecustom->status == 1) ?
                              " fa-times " : "fa-check " }}"></i> {{(!empty($leavecustom->status) && $leavecustom->status == 1) ? "De-Activate" : "Activate"}}</button>
                        </td>
                    </tr> 
                    @endforeach @else
                    <tr id="modules-list">
                        <td colspan="5">
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No Custom leaves to display, please start by adding a new Custom leave . </div>
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
            <!-- /.box-body -->
             <div class="box-footer">
                    <button type="button" id="add-new-doc" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-qualificationType-modal">Add Qualification Type</button>
            </div>
          @include('hr.partials.add_qualificationType_modal')
          @include('hr.partials.edit_qualificationType_modal')
        </div>
   <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">List Catagories</h3>
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
                            @if (count($doc_type) > 0)
                              @foreach ($doc_type as $type)
                               <tr id="categories-list">
                               <td nowrap>
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
                               @else
                               <tr id="categories-list">
                        <td colspan="5">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No category to display, please start by adding a new category.
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
              else if (data == 'dactive') location.href = "/hr/document/" + id + '/activate';
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

            //Add Qualification Type 
             //pass module data to the edit module modal
             var qualificationID;
            $('#add-qualificationType-modal').on('show.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                qualificationID = btnEdit.data('id');
                var name = btnEdit.data('name');
                var description = btnEdit.data('description');
                var modal = $(this);
                modal.find('#name').val(name);
                modal.find('#description').val(description);  
             });

             //Post module form to server using ajax (ADD)
            $('#add_qualification').on('click', function() {
                var strUrl = '/hr/addqultype/' ; 
                var modalID = 'add-qualificationType-modal';
                var objData = {
                     name: $('#'+modalID).find('#name').val(),
                    description: $('#'+modalID).find('#description').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'add-document';
                var redirectUrl = '/hr/setup';
                var successMsgTitle = 'Qualification Type Saved!';
                var successMsg = 'The Qualification Type has been Saved successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
            
                //Document
                 //Add Qualification Type 
             //pass module data to the edit module modal
             var docID;
            $('#add-document-modal').on('show.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                docID = btnEdit.data('id');
                var name = btnEdit.data('name');
                var description = btnEdit.data('description');
                var modal = $(this);
                modal.find('#name').val(name);
                modal.find('#description').val(description);  
             });

             //Post module form to server using ajax (ADD)
            $('#save_doc').on('click', function() {
                var strUrl = '/hr/addDoctype/' ; 
                var modalID = 'add-document-modal';
                var objData = {
                     name: $('#'+modalID).find('#name').val(),
                    description: $('#'+modalID).find('#description').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'add-document';
                var redirectUrl = '/hr/setup';
                var successMsgTitle = 'Document Type Saved!';
                var successMsg = 'The Document Type has been Saved successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
                //
                        // Edit
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
        //modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, method);
        });
                // 

            // Edit
              var edit_QualID;
        $('#edit-qualificationType-modal').on('show.bs.modal', function (e) {
            //console.log('kjhsjs');
            var btnEdit = $(e.relatedTarget);
            edit_QualID = btnEdit.data('id');
            var name = btnEdit.data('name');
            var description = btnEdit.data('description');
            // var moduleFontAwesome = btnEdit.data('font_awesome');
            var modal = $(this);
            modal.find('#name').val(name);
            modal.find('#description').val(description);
           
        });

         $('#edit_qualification').on('click', function () {
            var strUrl = '/hr/qul_type_edit/' + edit_QualID;
            var objData = {
                 name: $('#edit-qualificationType-modal').find('#name').val()
                ,description: $('#edit-qualificationType-modal').find('#description').val()
                , _token: $('#edit-qualificationType-modal').find('input[name=_token]').val()
            };
            var modalID = 'edit-qualificationType-modal';
            var submitBtnID = 'edit_qualification';
            var redirectUrl = '/hr/setup';
            var successMsgTitle = 'Changes Saved!';
            var successMsg = 'The Qualification Type has been changed successfully.';
            // var method = 'PATCH';
           modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        //modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, method);
        });

            //pass module data to the edit module modal
            var grouplevelID;
            $('#edit-grouplevel-modal').on('show.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                grouplevelID = btnEdit.data('id');
                var grouplevelname = btnEdit.data('name');
                var grouplevelnamepluralname = btnEdit.data('plural_name');
                var level = btnEdit.data('level');
                var modal = $(this);
                modal.find('#group_level_title').html('Edit Employee Group Level '+ level);
                modal.find('#name').val(grouplevelname);
                modal.find('#plural_name').val(grouplevelnamepluralname);//
                //if(primeRate != null && primeRate != '' && primeRate > 0) {
                //    modal.find('#prime_rate').val(primeRate.toFixed(2));
                //}
            });


            //Post module form to server using ajax (ADD)
            $('#save_grouplevel').on('click', function() {
                var strUrl = '/hr/grouplevel/'+grouplevelID;
                var modalID = 'edit-grouplevel-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    plural_name: $('#'+modalID).find('#plural_name').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'save_grouplevel';
                var redirectUrl = '/hr/setup';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The group level has been updated successfully.';
                var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, formMethod);
            });
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
/*
            $('#update-module').on('click', function() {
                postModuleForm('PATCH', '/users/module_edit/' + moduleId, 'edit-module-form');
            });
            */
        });
    </script>
@endsection
