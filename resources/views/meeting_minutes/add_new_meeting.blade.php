@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/select2/select2.min.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Create Meeting Minutes</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
			<form method="POST" action="/induction/client_add">
            {{ csrf_field() }}
            <!-- /.box-header -->
            <div class="box-body">
			
			
            <form class="form-horizontal" method="POST" name="add_new_leavetype-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Meeting</h4>
                </div>
                <div class="modal-body">
                    <div id="task-invalid-input-alert"></div>
                    <div id="task-success-alert"></div>
                    <div class="form-group">
                        <label for="Meeting Name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <input type="text" class="form-control" id="meeting_name" name="meeting_name" value="" placeholder="Enter Name">
                            </div>
                        </div>
                    </div>
					<div class="form-group">
                        <label for="Meeting Date" class="col-sm-2 control-label">Date</label>
                        <div class="col-sm-10">
							 <div class="input-group">
                            <input type="text" class="form-control datepicker" name="meeting_date" placeholder="  dd/mm/yyyy" value="">
							</div>
                        </div>
                    </div>
					<div class="form-group">
                        <label for="Meeting Location" class="col-sm-2 control-label">Location</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <input type="text" class="form-control" id="meeting_location" name="meeting_location" value="" placeholder="Enter Location">
                            </div>
                        </div>
                    </div>
					<div class="form-group">
                        <label for="Meeting Agenda" class="col-sm-2 control-label">Agenda</label>
                        <div class="col-sm-10">
                            <div class="input-group">
							<textarea rows="4" cols="50" class="form-control" id="meeting_agenda" name="meeting_agenda" placeholder="Enter Agenda"></textarea>
							</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add_library_task" class="btn btn-primary">Add Task</button>
                </div>
            </form>
         <!-- /.box-body -->
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-adn"></i>  Submit</button>
            </div>
        </div>
		</form>
        </div>
    </div>
</div>

@endsection
<!-- Ajax form submit -->

@section('page_script')
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<!-- Select2 -->
<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
 <!-- bootstrap datepicker -->
<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
    $(function () {
		  //Date picker
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });
    });
</script>
 @endsection