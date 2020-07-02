@extends('layouts.main_layout')
@section('page_dependencies')
	<!-- iCheck -->
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/green.css">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-10 col-md-offset-1">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">Answer Questions</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/visitors/screening">
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
							<div class="form-group">
								<label for="question_1" class="col-sm-4 control-label">Does anyone in your household work in a facility where COVID-19 patients are being treated?</label>
								<div class="col-sm-8">
									<label class="radio-inline rdo-iCheck" style="padding-left: 0px;"><input type="radio" id="rdo_yes" name="question_1" value="1"> Yes</label>
									<label class="radio-inline rdo-iCheck"><input type="radio" id="rdo_no" name="question_1" value="2" checked>  No</label>
								</div>
							</div>
							<div class="form-group">
								<label for="question_2" class="col-sm-4 control-label">In the past 14 days have you come into contact with a COVID-19 positive or suspected positive person?</label>
								<div class="col-sm-8">
									<label class="radio-inline rdo-iCheck" style="padding-left: 0px;"><input type="radio" id="rdo_yes2" name="question_2" value="1"> Yes</label>
									<label class="radio-inline rdo-iCheck"><input type="radio" id="rdo_no2" name="question_2" value="2" checked>  No</label>
								</div>
							</div>
							<div class="form-group">
								<label for="question_4" class="col-sm-4 control-label">In the past 14 days, have you travelled or been in contact with someone who has travelled to an area with local transmission of COVID-19?</label>
								<div class="col-sm-8">
									<label class="radio-inline rdo-iCheck" style="padding-left: 0px;"><input type="radio" id="rdo_yes3" name="question_3" value="1"> Yes</label>
									<label class="radio-inline rdo-iCheck"><input type="radio" id="rdo_no3" name="question_3" value="2" checked>  No</label>
								</div>
							</div>
							<div class="form-group">
								<label for="question_4" class="col-sm-4 control-label">Has you or anyone in your household been tested for COVID-19 in the past 3 days?</label>
								<div class="col-sm-8">
									<label class="radio-inline rdo-iCheck" style="padding-left: 0px;"><input type="radio" id="rdo_yes4" name="question_4" value="1"> Yes</label>
									<label class="radio-inline rdo-iCheck"><input type="radio" id="rdo_no4" name="question_4" value="2" checked>  No</label>
								</div>
							</div>
							<div class="form-group">
								<label for="question_5" class="col-sm-4 control-label">Do you have any illness in the past 14 days?</label>
								<div class="col-sm-8">
									<label class="radio-inline rdo-iCheck" style="padding-left: 0px;"><input type="radio" id="rdo_yes5" name="question_5" value="1"> Yes</label>
									<label class="radio-inline rdo-iCheck"><input type="radio" id="rdo_no5" name="question_5" value="2" checked>  No</label>
								</div>
							</div>
							<div class="form-group">
								<label for="question_6" class="col-sm-4 control-label">Did you have any of these in the last 14 days:
								*	Cough
								*	Sore throat
								*	Fever
								*	Chills
								*	Headache
								*	Shortness of breath
								*	Muscle or joint pain
								*	Sinusitis
								*	Diarrhoea
								</label>
								<div class="col-sm-8">
									<label class="radio-inline rdo-iCheck" style="padding-left: 0px;"><input type="radio" id="rdo_yes6" name="question_6" value="1"> Yes</label>
									<label class="radio-inline rdo-iCheck"><input type="radio" id="rdo_no6" name="question_6" value="2" checked>  No</label>
								</div>
							</div>
							<div class="form-group">
								<label for="question_7" class="col-sm-4 control-label">Have you lost your sense of smell and or appetite</label>
								<div class="col-sm-8">
									<label class="radio-inline rdo-iCheck" style="padding-left: 0px;"><input type="radio" id="rdo_yes7" name="question_7" value="1"> Yes</label>
									<label class="radio-inline rdo-iCheck"><input type="radio" id="rdo_no7" name="question_7" value="2" checked>  NO</label>
								</div>
							</div>
							<div class="form-group">
								<label for="question_8" class="col-sm-4 control-label">Have you lost your sense of smell and or appetite</label>
								<div class="col-sm-8">
									<label class="radio-inline rdo-iCheck" style="padding-left: 0px;"><input type="radio" id="rdo_yes8" name="question_8" value="1"> Yes</label>
									<label class="radio-inline rdo-iCheck"><input type="radio" id="rdo_no8" name="question_8" value="2" checked>  No</label>
								</div>
							</div>
							<div class="form-group">
								<label for="division_level_5" class="col-sm-4 control-label">{{$levelsFive->plural_name}}</label>
								<div class="col-sm-8">
									<div class="input-group">
										<select class="form-control select2" style="width: 100%;" id="division_level_5" name="division_level_5" required>
											<option selected="selected" value="">*** Select a {{$levelsFive->name}} ***</option>
												@foreach($companyFive as $five)
													<option value="{{ $five->id }}">{{ $five->name }}</option>
												@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="division_level_4" class="col-sm-4 control-label">{{$levelsFour->plural_name}}</label>
								<div class="col-sm-8">
									<div class="input-group">
										<select class="form-control select2" style="width: 100%;" id="division_level_4" name="division_level_4" required>
											<option selected="selected" value="">*** Select a {{$levelsFour->name}} ***</option>
												@foreach($companyFour as $four)
													<option value="{{ $four->id }}">{{ $four->name }}</option>
												@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="temperature" class="col-sm-4 control-label">Temperature</label>
								<div class="col-sm-8">
									<div class="input-group">
										<input type="text" class="form-control" id="temperature" name="temperature" value="{{ old('temperature') }}" placeholder="Enter Temperature" required>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="client_name" class="col-sm-4 control-label">Client Name</label>
								<div class="col-sm-8">
									<div class="input-group">
										<input type="text" class="form-control" id="client_name" name="client_name" value="{{ old('client_name') }}" placeholder="Enter Client Name" required>
									</div>
								</div>
							</div><div class="form-group">
								<label for="new_id_number" class="col-sm-4 control-label">ID Number</label>
								<div class="col-sm-8">
									<div class="input-group">
										<input type="number" class="form-control" id="new_id_number" name="new_id_number" value="{{ old('new_id_number') }}" placeholder="Enter ID Number" required>
									</div>
								</div>
							</div><div class="form-group">
								<label for="cell_number" class="col-sm-4 control-label">Cell Number</label>
								<div class="col-sm-8">
									<div class="input-group">
										<input type="text" class="form-control" id="cell_number" name="cell_number" data-inputmask='"mask": "(999) 999-9999"'  value="{{ old('cell_number') }}" placeholder="Enter Cell Number"  data-mask required>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="comment" class="col-sm-4 control-label">Comment</label>
								<div class="col-sm-8">
									<div class="input-group">
										<textarea name="comment" id="comment" class="form-control" placeholder="Enter Comment" >{{ old('comment') }}</textarea>
									</div>
								</div>
							</div>
							<input type="hidden" id="screening_done" name="screening_done" value=""> 
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
		@if(Session('success_add'))
			@include('contacts.partials.success_action', ['modal_title' => "Record Added!", 'modal_content' => session('success_add')])
		@endif
    </div>
@endsection

@section('page_script')
   <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
	<!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
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

    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>

    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>

    <script>
        $(function () {
           //Initialize iCheck/iRadio Elements
            $('.rdo-iCheck').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green',
				increaseArea: '20%' // optional
			});
            //Phone mask
            $("[data-mask]").inputmask();

            // [bootstrap file input] initialize with defaults
            $("#input-1").fileinput();
            // with plugin options
            //$("#input-id").fileinput({'showUpload':false, 'previewFileType':'any'});

            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();

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