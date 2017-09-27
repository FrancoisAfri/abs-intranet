@extends('layouts.main_layout')
@section('page_dependencies')

@endsection
@section('content')
<div class="row">
    <!-- New User Form -->
    <div class="col-md-12 col-md-12">
        <!-- Horizontal Form -->
        <form class="form-horizontal" method="get">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">Notes</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                       <!-- Contact's company details -->          
                    <div style="overflow-X:auto;">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Client name</th>
                                    <th>Notes</th>
                                    <th>Date From</th>
                                    <th>Next Action</th>
                                   <th>Follow Up Date</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <!--  -->
                                <!-- loop through the leave application info   -->
                                @if(count($notes) > 0)

                                @endif
                            <ul class="products-list product-list-in-box">
                                @foreach($notes as $approval)
                                <tr>
                                    <td>{{ !empty($approval->name) && !empty($approval->surname) ? $approval->name.' '.$approval->surname : '' }}</td>
                                    <td>{{ !empty($approval->notes) ? $approval->notes : '' }}</td>
                                    <td></td>
                                     <td></td>
                                    <td></td>
                                    @endforeach
                                    </tbody>
                               
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button id="cancel" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i> Back</button>
            </div>
            <!-- /.box-footer -->
    </div>
</form>
<!-- /.box -->
</div>
<!-- Include the reject leave modal-->
@include('leave.partials.reject_leave')
<!--  -->
@if(Session('success_application'))
@include('leave.partials.success_action', ['modal_title' => "Application Successful!", 'modal_content' => session('success_application')])
@endif
<!--  -->
</div>
@endsection

@section('page_script')
<!-- DataTables -->


<script type="text/javascript">

                                       
                                  
</script>
@endsection