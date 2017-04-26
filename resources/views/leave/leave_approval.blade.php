@extends('layouts.main_layout')
@section('page_dependencies')
   
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12 ">
            <!-- Horizontal Form -->
            <form class="form-horizontal">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user"></i>
                    <h3 class="box-title">Leave Approval</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                <div class="box-body">
                <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
            <div style="overflow-x:auto;">
              <table style="width:100%"  id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                        <!-- <th style="width:36%">Month</th> -->
                        <th style="width:36%">Employee name</th>
                        <th style="width:36%">Leave Type</th>
                        <th style="width:36%">Date From</th>
                        <th style="width:36%">Date TO</th>
                        <th style="width:36%">Time From</th>
                        <th style="width:36%">Time To</th>
                        <th style="width:70%">Notes</th>
                        <th style="width:36%">Supporting Documents</th>
                        <th style="width:36%">Status</th>
                        <th style="width:50%">Leave Balance</th>
                        <th style="width:50%">Accept</th>
                        <th style="width:50%">Decline</th>
                  </tr>
                </thead>
                <tbody>
                <!-- loop through the leave application info   -->
                          @if(count($leaveApplication) > 0)
                    @foreach($leaveApplication as $approval)
                      <tr>
                                                
<!--        <td>{{ !empty($audit->module_name) ? $audit->module_name : '' }}</td>-->
      <td>{{ !empty($approval->firstname) && !empty($approval->surname) ? $approval->firstname.' '.$approval->surname : '' }}</td>      <td>{{ !empty($approval->leavetype) ? $approval->leavetype : '' }}</td>                                    
            <td>{{ !empty($approval->start_date) ? date('d M Y ', $approval->start_date) : '' }}</td>
            <td>{{ !empty($approval->end_date) ? date(' d M Y', $approval->end_date) : '' }}</td>
            <td>{{ !empty($approval->start_time) ? $approval->start_time : '' }}</td>
            <td>{{ !empty($approval->end_time) ? date('H:i:s',$approval->end_time) : '' }}</td>
            <td>{{ !empty($approval->notes) ? $approval->notes : '' }}</td>

            <td>
                        <div class="form-group{{ $errors->has('supporting_doc') ? ' has-error' : '' }}">
                                    <label for="supporting_doc" class="control-label"></label>
                                    @if(!empty($approval->supporting_docs))
                                        <br><a class="btn btn-default btn-flat btn-block pull-right btn-xs" href="{{ $approval->supporting_docs }}" target="_blank"><i class="fa fa-file-pdf-o"></i>  View The Document</a>
                                    @else
                                        <br><a class="btn btn-default pull-centre btn-xs"><i class="fa fa-exclamation-triangle"></i> Nothing Was Uploaded</a>
                                    @endif
                                </div>
            </td>
               <!--  -->

             <td>{{ !empty($approval->status) ? $approval->status : '' }}</td>    
              <td>{{ !empty($approval->status) ? $approval->status : '' }}</td>                                     
                       <!-- Accept -->
             <td>
                <button type="button" id="edit_leave" class="btn btn-success pull-right btn-xs"><i class="fa fa-check-circle" data-toggle="modal" data-target=" "></i> Accept</button>
            </td>
            <!-- reject button -->
                <td>
                    <button type="button" id="reject_leave" class="btn btn-danger pull-right btn-xs"><i class="fa fa-check-circle" data-toggle="modal" data-target="#reject-leave-modal" data-id="{{$approval->id}}" data-description="{{$approval->application}}"></i> Decline</button>         
                </td>
    </tr>                          
                      </tr>
                    @endforeach
                @endif
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
                    </div>
                    <!-- /.box-footer -->
            </div>
            </form>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
    </div>
    @endsection

    @section('page_script')
    <!-- DataTables -->
<script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- End Bootstrap File input -->

    <script type="text/javascript">
    //Cancel button click event
    document.getElementById("cancel").onclick = function () {
        location.href = "/contacts/general_search";
    };
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
        
    </script>
@endsection