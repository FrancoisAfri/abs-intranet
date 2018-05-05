@extends('layouts.main_layout')
@section('page_dependencies')
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- iCheck -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title"> Add Job Card Note </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th> Date Captured</th>
                                <th>JobCard Note</th>
                                <th>Captured By</th>
                            </tr>
                            @if (count($jobcardnote) > 0)
                              @foreach ($jobcardnote as $vehice)
                               <tr id="categories-list">
                              
                                     <td>{{ (!empty( $vehice->date_default)) ?  date(' d M Y', $vehice->date_default) : ''}} </td>
                                     <td>{{ (!empty( $vehice->note_details)) ?  $vehice->note_details : ''}} </td>
                                     <td>{{ (!empty( $vehice->firstname . ' ' . $vehice->surname)) ?   $vehice->firstname . ' ' . $vehice->surname : ''}} </td>
                                     
                                </tr>
                                   @endforeach
                               @else
                               <tr id="categories-list">
                        <td colspan="5">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No Records to display, please start by adding a new record....
                        </div>
                        </td>
                        </tr>
                           @endif
                            </table>
                      <!--   </div> -->
                                   <!-- /.box-body -->
                    <div class="box-footer">
                         <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                         <button type="button" id="safe_module" class="btn btn-warning pull-right" data-toggle="modal" 
                         data-target="#add-note-modal">Add Job Card Notes </button>
                                   
                        </button>
                    </div>
             </div>
        </div>
   <!-- Include add new prime rate modal -->
   @include('job_cards.partials.add_note_modal')
          <!-- Include delete warning Modal form-->
     
</div>


@endsection

@section('page_script')
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<!-- Select2 -->
<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
<script>
       function postData(id , data ){   
            if(data == 'actdeac') location.href = "/vehicle_management/safe_act/" + id; 
          
        }
        $('#back_button').click(function () {
                location.href = '/jobcards/viewcard/{{$card->id}}';
            });
        $(function () {
            var moduleId;
            //Initialize Select2 Elements
            $(".select2").select2();

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
    
            //

            $(".js-example-basic-multiple").select2();

            //save Fleet
            //Post module form to server using ajax (ADD)
            $('#add_notes').on('click', function () {
                    var strUrl = '/jobcards/addjobcardnotes';
                    var formName = 'add-note-form';
                    var modalID = 'add-note-modal';
                    var submitBtnID = 'add_notes';
                    var redirectUrl = '/jobcards/viewcard/{{$card->id}}';
                    var successMsgTitle = 'New Record Added!';
                    var successMsg = 'The Record  has been updated successfully.';
                    modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                });

              var safeID;
            $('#edit-package-modal').on('show.bs.modal', function (e) {
                    //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                safeID = btnEdit.data('id');
                var name = btnEdit.data('name');
                var description = btnEdit.data('description');
                var modal = $(this);
                modal.find('#name').val(name);
                modal.find('#description').val(description);
             });
            $('#edit_safe').on('click', function () {
                var strUrl = '/vehicle_management/edit_safe/' + safeID;
                var modalID = 'edit-package-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    description: $('#'+modalID).find('#description').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'edit_safe';
                var redirectUrl = '/vehicle_management/safe';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The Safe has been updated successfully.';
                var Method = 'PATCH';
         modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });

            var safeID;
                    $('#delete-safe-warning-modal').on('show.bs.modal', function (e) {
                        var btnEdit = $(e.relatedTarget);
                        safeID = btnEdit.data('id');
                        var modal = $(this);
                    });

                    $('#delete_safe').on('click', function () {
                        var strUrl = '/vehicle_management/Manage_safe/'+ safeID;
                        var modalID = 'delete-safe-warning-modal';
                        var objData = {
                            _token: $('#' + modalID).find('input[name=_token]').val()
                        };
                        var submitBtnID = 'delete_safe';
                        var redirectUrl = '/vehicle_management/safe';
                       //var Method = 'PATCH';
                        modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl);
                    });

        });
    </script>
@endsection
