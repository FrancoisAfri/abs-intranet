@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/select2/select2.min.css">
	<!-- iCheck -->
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/green.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    <!--  -->
     <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="row">
        <!-- New User Form -->
        <div class="col-md-8 col-md-offset-2">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">New Task</h3>
                    <p>Enter Task details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST"  action="/tasks/add_new_task">
                    {{ csrf_field() }}
                    <div class="box-body">
					    @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
						<div class="form-group {{ $errors->has('description') ? ' has-error' : '' }}">
							<label for="description" class="col-sm-3 control-label">Description</label>
							<div class="col-sm-9">
								<div class="input-group">
								<textarea rows="4" cols="50" class="form-control" id="description" name="description" placeholder="Enter Description">{{ old('description') }}</textarea>
								</div>
							</div>
						</div>
                        <div class="form-group {{ $errors->has('start_date') ? ' has-error' : '' }}">
							<label for="start_date" class="col-sm-3 control-label">Start Date</label>
							<div class="col-sm-9">
								 <div class="input-group">
								<input type="text" class="form-control datepicker" name="start_date" placeholder="Click to Select a Start Date..." value="{{ old('start_date') }}">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="due_date" class="col-sm-3 control-label">Due Date</label>

							<div class="col-sm-9">
								<div class="input-group">
									<input type="text" class="form-control datepicker" id="due_date" name="due_date" value="{{ old('due_date') }}" placeholder="Click to Select a Due Date...">
								</div>
							</div>
						</div>
						<div class="form-group " style="display: block;">
                          <label for="time" class="col-sm-3 control-label">Due Time</label>  

                       		<div class="col-sm-9">
                            	<div class="input-group">
                                  	
                             <input type="text" class="form-control" id="due_time" name="due_time" value="{{ old('due_time') }}" placeholder="Select Start time...">
                            </div>
                          </div>
                    	</div>

						<div class="form-group">
							<label for="manager_duration" class="col-sm-3 control-label">Duration</label>

							<div class="col-sm-9">
								<div class="input-group">
									<input type="number" class="form-control" id="manager_duration" name="manager_duration" value="{{ old('manager_duration') }}" placeholder="Enter Duration in Minutes...">
								</div>
							</div>
						</div>
						<div class="form-group {{ $errors->has('employee_id') ? ' has-error' : '' }}">
							<label for="employee_id" class="col-sm-3 control-label">Employee Person</label>
							<div class="col-sm-9">
								<div class="input-group">
									<select class="form-control select2" multiple="multiple" style="width:170px;" id="employee_id" name="employee_id[]">
										
									<option value="0">*** Select a Employee ***</option>
									@foreach($users as $user)
										<option value="{{ $user->id }}" {{ ($user->id === old('employee_id')) ? ' selected="selected" ' : '' }}>{{ $user->first_name.' '.$user->surname}}</option>
									@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="employee_id" class="col-sm-3 control-label">Client</label>
							<div class="col-sm-9">
								<div class="input-group">
									<select class="form-control select2" style="width:300px;" id="company_id" name="company_id">
										<option value="0">*** Select a Client ***</option>
										@foreach($companies as $company)
											<option value="{{ $company->id }}" {{ ($company->id === old('company_id')) ? ' selected="selected"' : '' }}>{{ $company->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-user-plus"></i> Submit</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new Form-->
	@if(Session('success_add'))
            @include('contacts.partials.success_action', ['modal_title' => "New Task Added!", 'modal_content' => session('success_add')])
    @endif
</div>
@endsection
<!-- Ajax form submit -->

@section('page_script')
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<!-- Select2 -->
<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
 <!-- bootstrap datepicker -->
<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- iCheck -->
<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
<script>
    $(function () {
		//Initialize Select2 Elements
            $(".select2").select2();
		//Date picker
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });

		$('#due_time').datetimepicker({
             format: 'HH:mm:ss'
        });
        $('#time_to').datetimepicker({
             format: 'HH:mm:ss'
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

		//Show success action modal
		$('#success-action-modal').modal('show');
    });
</script>
 @endsection