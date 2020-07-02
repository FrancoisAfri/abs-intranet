@extends('layouts.main_layout')
@section('page_dependencies')
<!-- bootstrap file input -->
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<!-- DataTables -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
<!-- iCheck -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <!-- /.box -->
            <!-- Company's contacts box -->
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-users"></i> Policy Document</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
				<form class="form-horizontal" method="POST" action="/System/policy/update_status">
					<div class="box-body no-padding no-margin">
						<div class="overflow-X:auto; overflow-Y:auto;">
							<table class="table table-striped" >
								<tr><th colspan="3">Policy</th></tr>
									<tr>
										<td colspan="3"> 
											<iframe frameborder="0" scrolling="yes" style="border:0px" 
												src="{{ $policy_documnet}}#toolbar=0&navpanes=0&scrollbar=0" width="1000" height="800">
											</iframe>
										</td>
									</tr>
									<tr>
										<th style="text-align: center;">Read and understood</th>
										<th style="text-align: center;">Read but not understood</th>
										<th style="text-align: center;">Read but not sure</th>
									</tr>
									<tr>
										<td style="vertical-align: middle; text-align: center;">
											@if(!empty($user->read_understood) ||  !empty($user->read_not_understood)  || !empty($user->read_not_sure))
													<label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="{{ $user->id . '_readunderstood' }}" name="{{ "docread[" . $user->policy_id . "]" }}" disabled value="{{ "1-$user->user_id" }}" {{ $user->read_understood == 1 ? ' checked' : '' }}></label>
											@else 
												<label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="{{ $user->id . '_readunderstood' }}" name="{{ "docread[" . $user->policy_id . "]" }}" value="{{ "1-$user->user_id" }}" {{ $user->read_understood == 1 ? ' checked' : '' }}></label>	
											@endif
										</td>
										<td style="vertical-align: middle; text-align: center;">
											@if(!empty($user->read_understood) ||  !empty($user->read_not_understood)  || !empty($user->read_not_sure))
													<label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="{{ $user->id . '_readnotunderstood' }}" name="{{ "docread[" . $user->policy_id . "]" }}" disabled value="{{ "2-$user->user_id" }}"  {{ $user->read_not_understood == 1 ? ' checked' : '' }}></label>
											@else 
													<label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="{{ $user->id . '_readnotunderstood' }}" name="{{ "docread[" . $user->policy_id . "]" }}" value="{{ "2-$user->user_id" }}"  {{ $user->read_not_understood == 1 ? ' checked' : '' }}></label>	
											@endif
										</td>
										<td style="vertical-align: middle; text-align: center;">
											@if(!empty($user->read_understood) ||  !empty($user->read_not_understood)  || !empty($user->read_not_sure))
												 <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="{{ $user->id . '_readnotsure' }}" name="{{ "docread[" . $user->policy_id . "]" }}" disabled value="{{ "3-$user->user_id" }}" {{ $user->read_not_sure == 1 ? ' checked' : '' }}></label>
											@else 
												 <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="{{ $user->id . '_readnotsure' }}" name="{{ "docread[" . $user->policy_id . "]" }}" value="{{ "3-$user->user_id" }}" {{ $user->read_not_sure == 1 ? ' checked' : '' }}></label>	
											@endif
										</td>
									</tr>
							</table>
						</div>
					</div>
					<div class="box-footer">
						<button type="button" id="cancel" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i>
                        Back
						</button>
						@if(empty($user->read_understood) &&  empty($user->read_not_understood)  && empty($user->read_not_sure))
							 <button type="submit" id="add-new-module" class="btn btn-primary pull-right"><i class="fa fa-floppy-o"></i> Save</button>
						@endif
						
					</div>
				 </form>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection
@section('page_script')
<!-- Start Bootstrap File input -->
<!-- canvas-to-blob.min.js is only needed if you wish to resize images before upload. This must be loaded before fileinput.min.js -->
<script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js" type="text/javascript"></script>
<!-- the main fileinput plugin file -->
<!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
<script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
<script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
<!-- the main fileinput plugin file -->
<script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
<!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
<script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
<!-- optionally if you need translation for your language then include locale file as mentioned below
<script src="/bower_components/bootstrap_fileinput/js/locales/<lang>.js"></script>-->
<!-- End Bootstrap File input -->

<!-- Select2 -->
<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
<!-- iCheck -->
<script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
<!-- DataTables -->
<script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- Ajax dropdown options load -->
<script src="/custom_components/js/load_dropdown_options.js"></script>

<script>
    $(function () {
		
		//Cancel button click event
        document.getElementById("cancel").onclick = function () {
            location.href = "/System/policy/view_policies";
        };
        //Initialize Select2 Elements
        $(".select2").select2();

        //Initialize iCheck/iRadio Elements
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });

        //Tooltip
        $('[data-toggle="tooltip"]').tooltip();

        //Initialize the data table

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
        @if(Session('changes_saved'))
            $('#success-action-modal').modal('show');
        @endif
    });
</script>
@endsection