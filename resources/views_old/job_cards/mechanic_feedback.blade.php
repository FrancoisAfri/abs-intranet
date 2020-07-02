@extends('layouts.main_layout')
@section('page_dependencies')
<!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
<!-- iCheck -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/green.css">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <i class="fa fa-graduation-cap pull-right"></i>
                    <h3 class="box-title"><b>Please tick instructions that have been completed and add a comment if needed.</b></h3>
                    <h5 style="color:red;">NB: Job Card will only move to the next step once all instructions have been completed.</h3>
                    <p id="box-subtitle"></p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/jobcard/mecfeedback/{{$card->id}}">
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
						<table class="table table-striped table-bordered">
							<tr>
									<td class="caption">Instructions</td>
									<td class="caption">Status</td>
									<td class="caption">Completion  Date/Time</td>
							</tr>
							@if (count($instructions) > 0)
								@foreach ($instructions as $instruction)
									<tr>
										<td>
											{{ $loop->iteration }}. {{ !empty($instruction->instruction_details) ? $instruction->instruction_details : '' }}
										</td>
										<td><input type="checkbox" name="status_{{$instruction->id}}"
                                                           value="1" {{ $instruction->status === 2 ? 'checked ="checked"' : 0 }} ></td>
									<td>{{ !empty($instruction->completion_date) ? date('d M Y ', $instruction->completion_date) : '' }} - {{ !empty($instruction->completion_time) ? $instruction->completion_time : '' }}</td>
									</tr>
								@endforeach
							@else
								<tr><td colspan="3"></td></tr>
							@endif
							<tr>
								<td class="caption">Comment</td>
								<td colspan="2"><input type="hidden" name="job_status" value="{{$card->status}}">
									<textarea class="form-control" id="mechanic_comment" name="mechanic_comment" placeholder="Enter Comment..."
                                      rows="4 ">{{ !empty($card->mechanic_comment) ? $card->mechanic_comment : ''}}</textarea></td>
							</tr>
						</table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" id="cancel" class="btn btn-default"><i class="fa fa-arrow-left"></i> Cancel</button>
                        <button type="submit" class="btn btn-success pull-right"><i class="fa fa-database"></i> Submit</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
    </div>
    @endsection

    @section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>

    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

	<!-- Ajax dropdown options load -->
	<script src="/custom_components/js/load_dropdown_options.js"></script>
	<!-- Ajax form submit -->
	<script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script type="text/javascript">
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
            //Cancel button click event
            $('#cancel').click(function () {
                location.href = '/jobcards/viewcard/{{$card->id}}';
            });
			
		});
		//Initialize iCheck/iRadio Elements
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-blue',
				radioClass: 'iradio_square-blue',
				increaseArea: '10%' // optional
			});
    </script>
@endsection