@extends('layouts.main_layout')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">SMS SETTINGS</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
			<form class="form-horizontal" method="post" action="{{!empty($SmSConfiguration->id) ? '/contacts/update_sms/'. $SmSConfiguration->id : '/contacts/sms_settings'}}"> 
			 {{ csrf_field() }}
			 
			 @if(!empty($SmSConfiguration->id))
				{{ method_field('PATCH') }}
			 @endif
			   <table class="table table-bordered">  
				   <div class="form-group">
						<tr>
							<td>SMS Provider</td>
							<td>
								<select class="form-control select2" style="width: 100%;" id="sms_provider" name="sms_provider">
                                        <option value="">*** Select a Service Provider ***</option>
                                        <option value="1" {{ (!empty($SmSConfiguration->sms_provider) && $SmSConfiguration->sms_provider == 1) ? ' selected="selected"' : '' }}>BulkSMS</option>
                                        <option value="2" {{ (!empty($SmSConfiguration->sms_provider) && $SmSConfiguration->sms_provider == 2) ? ' selected="selected"' : '' }}>vodacomSMS</option>
                                        <option value="3" {{ (!empty($SmSConfiguration->sms_provider) && $SmSConfiguration->sms_provider == 3) ? ' selected="selected"' : '' }}>LogicSMS</option>
                                        <option value="4" {{ (!empty($SmSConfiguration->sms_provider) && $SmSConfiguration->sms_provider == 4) ? ' selected="selected"' : '' }}>HugeTelecoms</option>
                                </select>
							</td>
						</tr>
					</div>
					<div class="form-group">
						<tr>
							<td>SMS Username</td>
							<td>
								 <label for="path" class="control-label"></label>
								<input type="text" class="form-control" id="sms_username" name="sms_username" placeholder="Enter Username"required value="{{!empty($SmSConfiguration->sms_username) ? $SmSConfiguration->sms_username: ' ' }}">
							</td>
						</tr>
					</div>
					<div class="form-group">
						<tr>
						   <td>SMS Password</td>
							<td >
								 <label for="path" class="control-label"></label>
								<input type="password" class="form-control" id="sms_password" name="sms_password" placeholder="Enter Password"required value="{{!empty($SmSConfiguration->sms_password) ? $SmSConfiguration->sms_password: '' }}" >
							</td>
						</tr>
					</div>
				</table>
			<!-- /.box-body -->
			<div class="modal-footer">
			   
				<button type="submit" class="btn btn-primary"><i class="fa fa-database"></i> Submit</button> 
			</div>
			</form>
            </div>
            <!-- /.box-body -->
            <div class="modal-footer"> </div>
        </div>
    </div>
</div>
@endsection