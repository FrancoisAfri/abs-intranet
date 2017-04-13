@extends('layouts.main_layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Audit Report</h3>
                </div>
                <!-- /.box-header -->
				<form class="form-horizontal" method="POST" action="/audits/print">
                 <input type="hidden" name="action_date" value="{{!empty($action_date) ? $action_date : ''}}">
                 <input type="hidden" name="user_id" value="{{!empty($user_id) ? $user_id : ''}}">
                 <input type="hidden" name="module_name" value="{{!empty($module_name) ? $module_name : ''}}">
                 <input type="hidden" name="action" value="{{!empty($action) ? $action : ''}}">
					{{ csrf_field() }}
                <div class="box-body">
                    <!-- Collapsible section containing the amortization schedule -->
                    <div class="box-group" id="accordion">
                        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                        <div class="panel box box-primary">
                            <div class="box-body">
								<table class="table table-striped">
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
                                         <th>Accept</th>
                                         <th>Decline</th>
									</tr>
								    
                                        <!-- loop through the leave application info   -->
                                    	@if(count($leaveApplication) > 0)
										@foreach($leaveApplication as $approval)
				<tr>
                    <td>{{ !empty($approval->notes) ? $approval->notes : '' }}</td>
                    <td>{{ !empty($approval->supporting_docs) ? $approval->supporting_docs : '' }}</td>
                                            <!--   -->
                    <td>{{ !empty($approval->supporting_docs) ? $approval->supporting_docs : '' }}</td>
                    <td>{{ !empty($approval->supporting_docs) ? $approval->supporting_docs : '' }}</td>
                    <td>{{ !empty($approval->notes) ? $approval->notes : '' }}</td>
                    <td>{{ !empty($approval->notes) ? $approval->notes : '' }}</td>
                    <td>{{ !empty($approval->notes) ? $approval->notes : '' }}</td>
                    <td>{{ !empty($approval->notes) ? $approval->notes : '' }}</td>
                    <td>{{ !empty($approval->supporting_docs) ? $approval->supporting_docs : '' }}</td>

                    <td>
                    <button type="button" id="edit_leave" class="btn btn-success pull-right btn-xs"><i class="fa fa-check-circle" data-toggle="modal" data-target=" "></i>Accept</button></td>
                    <td>
                    <button type="button" id="edit_leave" class="btn btn-danger pull-right btn-xs"><i class="fa fa-check-circle" data-toggle="modal" data-target=" "></i>decline</button></td>	
                </tr>
										@endforeach
									@endif
                                    
                                    
								</table>
                     
								<div class="row no-print">
<!--
									<div class="col-xs-12">
										<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i>Print report</button>
									</div>
-->
								</div>
								<!-- End amortization /table -->
							</div>
                        </div>
                    </div>
                    <!-- /. End Collapsible section containing the amortization schedule -->
                </div>
				</form>
            </div>
        </div>
    </div>
@endsection