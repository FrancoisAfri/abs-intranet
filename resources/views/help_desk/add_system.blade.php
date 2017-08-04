@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Add new Help Desk</h3>

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
                    @if (count($systems) > 0)
                        @foreach($systems as $jobTitle)
                         <tr id="jobtitles-list">
                           <td nowrap>
                          <button type="button" id="edit_job_title" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-service-modal" data-id="{{ $jobTitle->id }}" data-name="{{ $jobTitle->name }}" data-description="{{ $jobTitle->description }}"><i class="fa fa-pencil-square-o"></i> Edit</button>

                               <a href="{{ '/help_desk/service/' . $jobTitle->id }}" id="edit_compan" class="btn btn-primary  btn-xs"   data-id="{{ $jobTitle->id }}" data-name="{{ $jobTitle->name }}" data-description="{{$jobTitle->description}}"  ><i class="fa fa-tasks"></i> view help desk</a></td>

                          <td>{{ (!empty($jobTitle->name)) ?  $jobTitle->name : ''}} </td>
                          <td>{{ (!empty( $jobTitle->description)) ?  $jobTitle->description : ''}} </td>
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
                            No systems to display, please start by adding a new systems.
                        </div>
                        </td>
                        </tr>
                    @endif
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" id="add_products_title" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-service_title-modal">Add Help Desk</button>
                </div>
            </div>
        </div>

        <!-- Include add new prime rate modal -->
        @include('help_desk.partials.add_position')
        @include('help_desk.partials.edit_position')
    </div>
@endsection

@section('page_script')
<script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script>
        function postData(id, data)
        {
            if (data == 'actdeac')
                location.href = "/hr/job_title_active/" + id;
            //product/Categories
        }
         $('#back_button').click(function () {
                location.href = '/product/Categories';
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
            $('#add_service').on('click', function() {
                //console.log('strUrl');
                var strUrl = '/help_desk/system/add';
                var modalID = 'add-new-service_title-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    description: $('#'+modalID).find('#description').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'cat_module';
                var redirectUrl = '/helpdesk/setup';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The service has been Added successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });


              var serviceID;
            $('#edit-service-modal').on('show.bs.modal', function (e) {
                    //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                serviceID = btnEdit.data('id');
                var name = btnEdit.data('name');
                var description = btnEdit.data('description');
                //var employeeName = btnEdit.data('employeename');
                var modal = $(this);
                modal.find('#name').val(name);
                modal.find('#description').val(description);

             });
            $('#update-service').on('click', function () {
                var strUrl = '/help_desk/system/adit/' + serviceID;
                var modalID = 'edit-service-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    description: $('#'+modalID).find('#description').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'edit_job_title';
                 var redirectUrl = '/helpdesk/setup';
                var successMsgTitle = 'Changes Saved!';
                 var successMsg = 'The service has been updated successfully.';
                var Method = 'PATCH';
         modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });


           
        });
    </script>
@endsection