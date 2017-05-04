@extends('layouts.main_layout')
@section('page_dependencies')
   
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12 col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">leave Approvals</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
            <!-- /.box-header -->
                  <div class="box-body">
                    <div style="overflow-X:auto;">
                      <table id="example2" class="table table-bordered table-hover">
                      <thead>
                      <tr>
                        <!-- <th style="width:36%">Month</th> -->
                        <th>Employee name</th>
                        <th>Leave Type</th>
                        <th>Date From</th>
                        <th>Date TO</th>
                        <th>Time From</th>
                        <th>Time To</th>
                        <th>Notes</th>
                        <th>Supporting Documents</th>
                        <th>Status</th>
                        <th>Leave Balance</th>
                        <th>Action</th>
                        <!-- th>Action</th> -->
                       <!--  <th style="width:50%">Decline</th> -->
                  </tr>
                </thead>
                <tbody>
                <!-- loop through the leave application info   -->
                  @if(count($leaveApplication) > 0)
                       <!--  <div class="callout callout-danger">
                            <h4><i class="fa fa-database"></i> No Records found</h4>

                            <p>No Recods matching your search criteria in the database. Please refine your search parameters.</p>
                        </div> -->
                    @endif
                    <ul class="products-list product-list-in-box">
                    @foreach($leaveApplication as $approval)
                      <tr>
                                                
<!--        <td>{{ !empty($audit->module_name) ? $audit->module_name : '' }}</td>-->
                                  <td>{{ !empty($approval->firstname) && !empty($approval->surname) ? $approval->firstname.' '.$approval->surname : '' }}</td>     <td>{{ !empty($approval->leavetype) ? $approval->leavetype : '' }}</td>                                    
                              <td>{{ !empty($approval->start_date) ? date('d M Y ', $approval->start_date) : '' }}</td>
                              <td>{{ !empty($approval->end_date) ? date(' d M Y', $approval->end_date) : '' }}</td>
                              <td>{{ !empty($approval->start_time) ? date('H:i:s',$approval->start_time) : '' }}</td>
                              <td>{{ !empty($approval->end_time) ? date('H:i:s',$approval->end_time) : '' }}</td>
                              <td>{{ !empty($approval->notes) ? $approval->notes : '' }}</td>

                              <td>
                                          <div class="form-group{{ $errors->has('supporting_doc') ? ' has-error' : '' }}">
                                                      <label for="supporting_doc" class="control-label"></label>
                                                      @if(!empty($approval->supporting_docs))
                                                          <br><a class="btn btn-default btn-flat btn-block pull-right btn-xs" href="{{ $approval->supporting_docs }}" target="_blank"><i class="fa fa-file-pdf-o"></i>  View The Document</a>
                                                      @else
                                                          <br><a class="btn btn-default pull-centre btn-xs"><i class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
                                                      @endif
                                                  </div>
                              </td>
                                 <!--  -->

                               <td>{{ !empty($approval->status) ? $approval->status : '' }}</td>    
                                <td>{{ !empty($approval->leave_Days) ? $approval->leave_Days : '' }}</td>                                     
                       <!-- Accept -->
                                <td>
                                 <button type= "button" id="Accept" class="btn btn-success btn-xs btn-detail open-modal" value="{{$approval->id}}" onclick="postData()">Accept</button>


                               <!--  <button type="submit" id="edit_leave" class="btn btn-success pull-right btn-xs"><i class="fa fa-check-circle"></i> Accept</button> -->
                            <!-- reject button -->
                            <!-- <input class="btn" type="submit" class="btn btn-success pull-right btn-xs" value="Save"> -->
                               </td>
                               <td>
                <button type="button" id="reject_leave" class="btn btn-danger btn-xs"><i class="fa fa-check-circle" data-toggle="modal" data-target="#reject-leave-modal" data-id="{{$approval->id}}" data-description="{{$approval->application}}"></i> Decline</button>         
                                </td>
                      </tr>                          
                      </tr>
                    @endforeach
               
                </tbody>
                                <tfoot>
                                <tr>
                                  <th>Employee name</th>
                                        <th>Leave Type</th>
                                        <th>Date From</th>
                                        <th>Date TO</th>
                                        <th>Time From</th>
                                        <th>Time To</th>
                                        <th>Notes</th>
                                        <th>Supporting Documents</th>
                                        <th>Status</th>
                                        <!-- <th>Accept</th>
                                        <th>Decline</th> -->
                                </tr>
                                </tfoot>
              </table>
            </div>
            </div>
            <!-- /.box-body -->
          </div>
                     </div>   
                    <!-- /.box-body -->
                    <div class="box-footer">
                    <button type="button" id="cancel" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i> Cancel</button>

                    <!--  <button type="button" id="reject_leave" class="btn btn-primary pull-right" data-toggle="modal" data-target="#reject-leave-modal">Add </button> -->
                    </div>
                    <!-- /.box-footer -->
            </div>
            </form>
            <!-- /.box -->
        </div>
        <!-- include reject leave modal -->
       @include('leave.partials.reject_leave')
    </div>
    @endsection

    @section('page_script')
    <!-- DataTables -->

<script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<meta name="_token" content="{!! csrf_token() !!}" />
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="{{asset('js/ajax-crud.js')}}"></script> -->
    <!-- End Bootstrap File input -->

    <script type="text/javascript">
    //Cancel button click event
    document.getElementById("cancel").onclick = function () {
        location.href = "/leave/Allocate_leave_types";
    };
    // function to search through view details 
     $(function () {
         $('#example2').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": true
    });
        });
        
        // post data
        function postData(id, data)
        {
             // if (data == 'dactive') location.href = "/hr/document/" + id + '/activate';  
          location.href = "/leave/Allocate_leave_types" + id;
             // if (data == 'ribbons') location.href = "/hr/ribbons/" + id;
        } 

          $('#Accept').click(function () {
                  $('form[name="leave-application-form"]').attr('action', '/leave/application/AcceptLeaave');
            });

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

             var reject_ID;
            $('#Reject-leave-modal').on('show.bs.modal', function (e) {
                    //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                reject_ID = btnEdit.data('id');
                // var name = btnEdit.data('name');
                var description = btnEdit.data('description');
                //var employeeName = btnEdit.data('employeename');
                var modal = $(this);
                // modal.find('#name').val(name);
                modal.find('#description').val(description);   
             });
            //Post module form to server using ajax (ADD)
            $('#reject-reason').on('click', function() {
                //console.log('strUrl');
                var strUrl = '/leave/approval/reject' +  'reject_ID'; 
                var modalID = 'Reject-leave-modal';
                var objData = {
                    // name: $('#'+modalID).find('#name').val(),
                    description: $('#'+modalID).find('#description').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'reject_leave';
                var redirectUrl = '/leave/approval';
                var successMsgTitle = 'reject reason Saved!';
                var successMsg = 'The reject reason has been Saved successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });






    </script>
@endsection