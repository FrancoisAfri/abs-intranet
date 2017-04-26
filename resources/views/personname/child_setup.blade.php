@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{$childLevelname}}: {{$parentDiv->name}}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <form class="form-horizontal" method="POST" action="/hr/firstchild">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered"> 
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
                                <th>Name</th>
                                <th>Manager's Name</th>
                                <th style="width: 5px; text-align: center;"></th>
                            </tr>
            
                            @foreach ($childDiv as $type)
                                <tr>
                                    <td style=" text-align: center;" nowrap>
                                        <button type="button" id="edit_compan" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-child-modal" data-id="{{ $type->id }}" data-name="{{ $type->name }}" data-manager_id="{{$type->manager_id}}" ><i class="fa fa-pencil-square-o"></i> Edit</button>
                                     
                                    </td>
                                    <td>{{ $type->name }}</td>
                                    <td>{{ ($type->manager) ? $type->manager->first_name." ".$type->manager->surname : ''}}</td>
                                    <td>
                                        
                                         <button type="button" id="view_ribbons" class="btn {{ (!empty($type->active) && $type->active == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$type->id}}, 'dactiv');"><i class="fa {{ (!empty($type->active) && $type->active == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($type->active) && $type->active == 1) ? "De-Activate" : "Activate"}}</button> 
                                     <!--   <button type="button" id="view_ribbons" class="btn {{ (!empty($type->active) && $type->active == 1) ? " btn-danger " : "btn-success " }}
                                      btn-xs" onclick="postData({{$type->id}}, 'dactiv');"><i class="fa {{ (!empty($type->active) && $type->active == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($type->active) && $type->active == 1) ? "De-Activate" : "Activate"}}</button>-->
                                      
                                    </td>
                                </tr>    
                            @endforeach
                        </table>
                    </div>
         
                        <!-- /.box-body -->
                    <div class="box-footer">
                     <button type="button" id="child_module" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-child-modal">Add {{$parentDiv->name}}</button>

                      <a type="button" href="/hr/company_setup" id="" class="btn btn-default pull-left">Back</a> 
                    </div>
        </div>


      @include('hr.partials.add_child_level')
      @include('hr.partials.edit_child_modal')
        <!-- Include add new prime rate modal 
        include('hr.partials.level_module')
        include('hr.partials.edit_company_modal')
  -->
  
    </div>
@endsection

@section('page_script')
<!-- Ajax form submit -->
<script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script>
		function postData(id, data)
		{

             if (data == 'dactiv')location.href = "/hr/firstchild/" + "{{ $parentLevel - 1 }}/" + id + '/activate';
             
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


            var updatechildID;
            $('#edit-child-modal').on('show.bs.modal', function (e) {
                   // console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                updatechildID = btnEdit.data('id');
                var name = btnEdit.data('name');
                var manager_id = btnEdit.data('manager_id');
                var level = btnEdit.data('level');
                //var employeeName = btnEdit.data('employeename');
                var modal = $(this);
                modal.find('#name').val(name);
                modal.find('#manager_id').val(manager_id);
                
             });
       

                $('#save_childlevel').on('click', function() {
                var strUrl = '/hr/firstchild/add/' + '{{ $parentLevel }}/'+ '{{ $parentDiv->id }}'  ;
                //console.log('dfgr' + strUrl);
                   //  var strUrl = '/hr/firstchild/'+ '{{ $parentDiv->id }}';
                var modalID = 'add-child-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    manager_id: $('#'+modalID).find('#manager_id').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'child_module';
                var redirectUrl = '/hr/child_setup/' + '{{ $parentLevel }}/'+ '{{ $parentDiv->id }}' ;
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The group level has been updated successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);

             });


               $('#update_child-modal').on('click', function () {
                var strUrl = '/hr/firstchild/{{ $parentLevel - 1 }}/' +  updatechildID;
                var modalID = 'edit-child-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    manager_id: $('#'+modalID).find('#manager_id').val(),
                     _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'update_child-modal';
                var redirectUrl = '/hr/child_setup/'+ '{{ $parentLevel }}/'+ '{{ $parentDiv->id }}';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'Company modal has been updated successfully.';
                var Method = 'PATCH';
               modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });

                 });
        
           

               
          /*  $('#update-module').on('click', function() {
                postModuleForm('PATCH', '/users/module_edit/' + moduleId, 'edit-module-form');
            });
            */

   /*   updatechildID
                 $('#add-child-modal').on('show.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                companyID = btnEdit.data('id');
                var companyIDName = btnEdit.data('name');
                var companyIDEmployers = btnEdit.data('manager_id');
                var level = btnEdit.data('level');
                var modal = $(this);
                modal.find('#group_level_title').html('Edit Employee Group Level '+ level);
                modal.find('#name').val(companyIDName);
                modal.find('#manager_id').val(companyIDEmployers);
                modal.find('#division_level_id').val(level);
                if(primeRate != null && primeRate != '' && primeRate > 0) {
                   modal.find('#prime_rate').val(primeRate.toFixed(2));
                }
    });*/



    </script>
@endsection

