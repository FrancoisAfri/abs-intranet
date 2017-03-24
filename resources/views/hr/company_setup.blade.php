@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Company {{$highestLvl->plural_name}}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <form class="form-horizontal" method="POST" action="/hr/firstlevel">
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
            
                            @foreach ($highestLvl->divisionLevelGroup as $type)
                                <tr>
                                    <td style=" text-align: center;" nowrap>
                                        <button type="button" id="edit_compan" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-company-modal" data-id="{{ $type->id }}" data-name="{{ $type->name }}" data-manager_id="{{$type->manager_id}}" ><i class="fa fa-pencil-square-o"></i> Edit</button>
                                        @if($highestLvl->level > $lowestactiveLvl && $type->childDiv())
                                            <a href="/hr/child_setup/{{$highestLvl->level}}/{{$type->id}}" id="edit_compan" class="btn btn-primary  btn-xs"   data-id="{{ $type->id }}" data-name="{{ $type->name }}" data-manager_id="{{$type->manager_id}}" ><i class="fa fa-eye"></i> {{$childLevelname}}</a>
                                        @endif
                                    </td>
                                    <td>{{ $type->name }}</td>
                                    <td>{{ ($type->manager) ? $type->manager->first_name." ".$type->manager->surname : ''}}</td>
                                    <td>
                                        
                                          <!--   <button type="button" id="view_ribbons" class="btn 11111111111111111111111{{ (!empty($type->active) && $type->active == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$type->id}}) , 'dactive';"><i class="fa {{ (!empty($type->active) && $type->active == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($type->active) && $type->active == 1) ? "De-Activate" : "Activate"}}</button> -->
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
                     <button type="button" id="level_module" class="btn btn-primary pull-right" data-toggle="modal" data-target="#level-module-modal">Add {{$highestLvl->name}}</button>  
                    </div>
        </div>

        <!-- Include add new prime rate modal -->
        @include('hr.partials.level_module')
        @include('hr.partials.edit_company_modal')
  
  
    </div>
@endsection

@section('page_script')
<!-- Ajax form submit -->
<script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script>
		function postData(id, data)
		{
             if (data == 'dactive') location.href = "/hr/company_edit/" + "{{ $highestLvl->id }}/" + id + '/activate';
             
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
              

            var updatecompanyID;
            $('#edit-company-modal').on('show.bs.modal', function (e) {
                    //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                updatecompanyID = btnEdit.data('id');
                var name = btnEdit.data('name');
                var manager_id = btnEdit.data('manager_id');
                var level = btnEdit.data('level');
                //var employeeName = btnEdit.data('employeename');
                var modal = $(this);
                modal.find('#name').val(name);
                modal.find('#manager_id').val(manager_id);
                
             });

            //Post module form to server using ajax (ADD)
            $('#save_firstlevel').on('click', function() {
                //console.log('strUrl');
                var strUrl = '/hr/firstleveldiv/add/'+ '{{ $highestLvl->id }}';
                var modalID = 'level-module-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    manager_id: $('#'+modalID).find('#manager_id').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'level_module';
                var redirectUrl = '/hr/company_setup';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The group level has been updated successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

       $('#update_company-modal').on('click', function () {
                var strUrl = '/hr/company_edit/{{ $highestLvl->id }}/' + updatecompanyID;
                var modalID = 'edit-company-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    manager_id: $('#'+modalID).find('#manager_id').val(),
                     _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'update_company-modal';
                var redirectUrl = '/hr/company_setup';
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


    </script>
@endsection
