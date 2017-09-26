@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
@endsection

@section('content')
    <div class="row">
         <div class="col-md-10 col-md-offset-1">
            <div class="box box-primary">
                <div class="box-header with-border">
                <i class="fa fa-ticket pull-right"></i>
                <p>
                    <h3 class="box-title">Assign Ticket(s) - {{ $Names }}</h3>
                  <!--  -->
                </div>
              <!--  -->
                     {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <!-- /.box-header -->
                    <div class="box-body">

                <table class="table table-bordered">
                     <tr><th style="width: 10px"></th>
                     <th>Name</th>
                     <th>email</th>
                     <th>Subject</th>
                      <th>Ticket Date</th>
                      <th>Operator</th>
                   <!--   <th>Assign Operator</th> -->
                     <th style="width: 40px"></th>
                     </tr>
                    @if (count($tickets) > 0)
                        @foreach($tickets as $ticket)
                         <tr id="jobtitles-list">
                           <td nowrap>
                        <td>{{ (!empty($ticket->name)) ?  $ticket->name : ''}} </td> 
                        <td>{{ (!empty($ticket->email)) ?  $ticket->email : ''}} </td>
                        <td>{{ (!empty($ticket->subject)) ?  $ticket->subject : ''}} </td>
                        <td>{{ !empty($ticket->ticket_date) ? date('d M Y ', $ticket->ticket_date) : '' }}</td>
                     <td><button type="button" id="add_operators" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#assign-operators-modal" data-id="{{ $ticket->id }}"
                    > <i class="fa fa-ticket"></i> Assign Ticket</button></td>
                        </tr>
                        @endforeach
                    @else
                        <tr id="jobtitles-list">
                        <td colspan="6">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            No Tickets to display.
                        </div>
                        </td>
                        </tr>
                    @endif
                    </table>
                </div>
               <!--  </form> -->
            </div>
        </div>
        
     @include('help_desk.partials.assign_operators')    

@endsection

@section('page_script')
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script>
             function assign(id, data) {
          alert(id)
         // if (data == 'reject_id') location.href = "/leave/reject/" + id;
       }

        $(function () {
            var jobId;

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


                 var serviceID;
            $('#assign-operators-modal').on('show.bs.modal', function (e) {
                    //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                serviceID = btnEdit.data('id');
                // var name = btnEdit.data('name');
                // var description = btnEdit.data('description');
                //var employeeName = btnEdit.data('employeename');
                var modal = $(this);
                // modal.find('#name').val(name);
                // modal.find('#description').val(description);

             });

                  //Post module form to server using ajax (ADD)
            $('#add_operator').on('click', function() {
                //console.log('strUrl');
                var strUrl = '/help_desk/operator/assign/' + serviceID ;
                var modalID = 'assign-operators-modal';
                var objData = {
                    operator_id: $('#'+modalID).find('#operator_id').val(),
                    // description: $('#'+modalID).find('#description').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'add-operators';
                var redirectUrl = '/help_desk/assign_ticket/{{ $ID }}';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The Operator has been Added successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            // 

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