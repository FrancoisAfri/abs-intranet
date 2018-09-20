@extends('layouts.main_layout')
	<!-- iCheck -->
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/green.css">
@section('content')
    <div class="row">
        <!-- Search User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-search pull-right"></i>
                    <h3 class="box-title">Send Message</h3>
                    <p>Select clients to send the message to:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/contacts/send-message">
                    {{ csrf_field() }}

                    <div class="box-body">
                        <div class="form-group{{ $errors->has('clients') ? ' has-error' : '' }}">
                            <label for="clients" class="col-sm-2 control-label">Client(s)</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-users"></i>
                                    </div>
                                    <select name="clients[]" id="clients" class="form-control select2" multiple data-placeholder="*** Select a Client ***">
                                        @foreach($contactPersons as $contactPerson)
                                            <option value="{{ $contactPerson->id}}">{{$contactPerson->comp_name."|**|". $contactPerson->first_name." ".$contactPerson->surname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('message_type') ? ' has-error' : '' }}">
							<label for="message_type" class="col-sm-2 control-label">Type</label>
							<div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-check-square-o"></i>
									</div>
									<label class="radio-inline"><input type="radio" id="rdo_email" name="message_type" value="1" checked> Email</label>
									<label class="radio-inline"><input type="radio" id="rdo_sms" name="message_type" value="2"> SMS</label>
								</div>
                            </div>
                        </div>
                        <div class="form-group sms-field {{ $errors->has('sms_content') ? ' has-error' : '' }}">
                            <label for="sms_content" class="col-sm-2 control-label">SMS</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-comments-o"></i>
                                    </div>
                                    <textarea name="sms_content" id="sms_content" class="form-control" placeholder="Message" rows="3" maxlength="180">{{ old('sms_content') }}</textarea>
                                </div>
                            </div>
                        </div>
						<div class="form-group email-field {{ $errors->has('email_content') ? ' has-error' : '' }}">
                            <label for="email_content" class="col-sm-2 control-label">Email</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope-o"></i>
                                    </div>
                                    <textarea name="email_content" id="email_content" class="form-control" placeholder="Message" rows="6" maxlength="1500">{{ old('email_content') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-paper-plane-o"></i> Send</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
                <!-- End Form-->
            </div>
			@if (session('success_sent'))
			@include('contacts.partials.success_action', ['modal_title' => 'Communication Sent!', 'modal_content' => session('success_sent')])
			@endif
            <!-- /.box -->
        </div>
        <!-- /.col-md-12 -->
    </div>
@endsection

@section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<!-- iCheck -->
	<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <script type="text/javascript">
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
			 //Initialize iCheck/iRadio Elements
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green',
				increaseArea: '20%' // optional
			});
			//call hide/show fields functions
			hideFields();
				$('#rdo_email, #rdo_sms').on('ifChecked', function(){
				   hideFields();
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
		   //Reposition when a modal is shown
			$('.modal').on('show.bs.modal', reposition);
			// Reposition when the window is resized
			$(window).on('resize', function () {
				$('.modal:visible').each(reposition);
			});
		   //Show success action modal
           $('#success-action-modal').modal('show');
        });
		//function to hide/show fields depending on the registration type
		function hideFields() {
			var messageType = $("input[name='message_type']:checked").val();
			if (messageType == 1) { //email
				$('.sms-field').hide();
				$('.email-field ').show();
			}
			else if (messageType == 2) { //sms
				$('.email-field').hide();
				$('.sms-field').show();
			}
		}
    </script>
@endsection