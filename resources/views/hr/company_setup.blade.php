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
                <form class="form-horizontal" method="POST" action="/hr/grouplevel">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered"> 
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
                                <th>Name</th>
                                <th>Manager's Name</th>
                                <th>Level</th>
                                <th style="width: 5px; text-align: center;"></th>
                            </tr>
                 
                            @foreach ($types as $type)
                                <tr>
                                     <td nowrap>
                                     <button type="button" id="view_ribbons" class="btn btn-primary  btn-xs" onclick="postData({{$type->id}}, 'ribbons');><i class="fa fa-eye" data-id="{{ $type->id }}"></i> Ribbons</button>
                                    <td style="width: 5px; text-align: center;"><button type="button" id="level-module-modal" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#level-module-modal" data-id="{{ $type->id }}" data-name="{{ $type->name }}" data-manager_id="{{ $type->manager_id }}" data-active="{{ $type->active }}" ><i class="fa fa-pencil-square-o"></i> Edit</button></td>
                                    <td>{{ $type->name }}</td>
                                    <td>{{ $type->manager_id }}</td>
                                    <td>{{ $type->highestLvl }}</td>
                                    <td style="width: 5px; text-align: center;">
                                        @if ($type->name!='')
                                            <button type="button" id="view_ribbons" class="btn {{ (!empty($type->active) && $type->active == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$type->id}});"><i class="fa {{ (!empty($type->active) && $type->active == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($type->active) && $type->active == 1) ? "De-Activate" : "Activate"}}</button>
                                        @endif
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
  
  
    </div>
@endsection

@section('page_script')
<!-- Ajax form submit -->
<script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script>
		function postData(id)
		{
			location.href = "/hr/firstLevel/activate/" + id;
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

          

                var companyID;
           $('#level-module-modal').on('show.bs.modal', function (e) {
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
               /* if(primeRate != null && primeRate != '' && primeRate > 0) {
                   modal.find('#prime_rate').val(primeRate.toFixed(2));
                }*/
            });

       

            //Post module form to server using ajax (ADD)
            $('#save_firstlevel').on('click', function() {
                var strUrl = '/hr/firstLevel';
                var modalID = 'level-module-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    manager_id: $('#'+modalID).find('#manager_id').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'save_firstlevel';
                var redirectUrl = '/hr/company_setup';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The group level has been updated successfully.';
                var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, formMethod);
            });


          /*  $('#update-module').on('click', function() {
                postModuleForm('PATCH', '/users/module_edit/' + moduleId, 'edit-module-form');
            });
            */

   });
    </script>
@endsection
