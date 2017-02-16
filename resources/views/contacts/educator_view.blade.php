@extends('layouts.main_layout')

@section('page_dependencies')
        <!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">

<!-- bootstrap file input -->
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row">
        <!-- User Form -->
        <div class="col-md-8 col-md-offset-2">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">Client</h3>
                    <p>Client details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/educators/{{ $educator->id }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    <div class="box-body">
                        <div class="form-group">
                            <label for="appliction_type" class="col-sm-3 control-label">Appliction Type</label>
                            <div class="col-sm-9">
                                <div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-user"></i>
								</div>
								<select class="form-control" name="type" id="type" placeholder="Select Appliction Type"  onchange="changeApplicant(this.value)"  required>
									<option value="1" {{ (!empty($educator->type) && $educator->type == 1) ? ' selected': '' }}>Short term</option>
									<option value="2" {{ (!empty($educator->type) && $educator->type == 2) ? ' selected': '' }}>Long term</option>
								</select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="first_name" class="col-sm-3 control-label">First Name</label>
                            <div class="col-sm-9">
                                <div class="input-group">
								<div class="input-group-addon">
                              <i class="fa fa-user"></i>
                            </div>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $educator->first_name}}" placeholder="First Name" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="surname" class="col-sm-3 control-label">Surname</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="surname" name="surname" value="{{ $educator->surname }}" placeholder="Surname">
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="id_number" class="col-sm-3 control-label">ID Number</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="number" class="form-control" id="id_number" name="id_number" value="{{ $educator->id_number }}" placeholder="ID Number">
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="school" class="col-sm-3 control-label">School</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bar-chart"></i>
                                    </div>
                                    <select name="school_id" class="form-control">
                                        <option value="">*** Select School ***</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}" {{ (isset($educator) && $school->id == $educator->school_id) ? ' selected': '' }}>{{ $school->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="ethnicity" class="col-sm-3 control-label">Race</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bar-chart"></i>
                                    </div>
                                    <select name="ethnicity" class="form-control">
                                        <option value="">*** Select a Race ***</option>
                                        @foreach($ethnicities as $ethnicity)
                                            <option value="{{ $ethnicity->id }}" {{ (isset($educator) && $ethnicity->id == $educator->ethnicity) ? ' selected': '' }}>{{ $ethnicity->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
						 <div class="form-group">
                            <label for="gender" class="col-sm-3 control-label">Gender</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-venus-mars"></i>
                                    </div>
                                    <select name="gender" class="form-control">
                                        <option value="">*** Select gender ***</option>
                                        <option value="1" {{ (!empty($educator->gender) && $educator->gender == 1) ? ' selected': '' }}>Male</option>
                                        <option value="0" {{ (empty($educator->gender) && $educator->gender == 0) ? ' selected': '' }}>Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cell_number" class="col-sm-3 control-label">Cell Number</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="tel" class="form-control" id="cell_number" name="cell_number" value="{{ $educator->cell_number }}" data-inputmask='"mask": "(999) 999-9999"' placeholder="Cell Number" data-mask>
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="activity_id" class="col-sm-3 control-label">Activity</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bar-chart"></i>
                                    </div>
                                    <select name="activity_id" class="form-control">
                                        <option value="">*** Select Activity/Project ***</option>
                                        @foreach($activities as $activity)
                                            <option value="{{ $activity->id }}" {{ (isset($educator) && $activity->id == $educator->activity_id) ? ' selected': '' }}>{{ $activity->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="email" class="col-sm-3 control-label">Highest Academic Qualification</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-info-circle"></i>
                                    </div>
                                    <input type="text" class="form-control" id="highest_qualification" name="highest_qualification" value="{{ $educator->highest_qualification }}" placeholder="Highest Academic Qualification...">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">Highest Professional Qualification</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-info-circle"></i>
                                    </div>
                                    <input type="text" class="form-control" id="highest_professional_qualification" name="highest_professional_qualification" value="{{ $educator->highest_professional_qualification }}" placeholder="Highest Professional Qualification...">
                                </div>
                            </div>
                        </div>
						<div class="form-group shortterm">
                            <label for="comment" class="col-sm-3 control-label">Comment</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <textarea name="comment" class="form-control" placeholder="Comment">{{ $educator->Comment }}</textarea>
                                </div>
                            </div>
                        </div>
						<div class="form-group shortterm">
                            <label for="first_time" class="col-sm-3 control-label">First time attending Osizweni?</label>
                            <div class="col-sm-9">
                                <div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-user"></i>
								</div>
								<select class="form-control" name="first_time" id="first_time">
									<option value="1" {{ (!empty($educator->first_time) && $educator->first_time == 1) ? ' selected': '' }}>Yes</option>
									<option value="0" {{ (empty($educator->first_time) && $educator->first_time == 0) ? ' selected': '' }}>No</option>
								</select>
                                </div>
                            </div>
                        </div>
						<div class="form-group longterm">
                            <label for="physical_address" class="col-sm-3 control-label">Home address</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <textarea name="physical_address" class="form-control" placeholder="Home address">{{ $educator->physical_address }}</textarea>
                                </div>
                            </div>
                        </div>
						<div class="form-group longterm">
                            <label for="postal_address" class="col-sm-3 control-label">Postal Address</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <textarea name="postal_address" class="form-control" placeholder="Postal Address">{{ $educator->postal_address }}</textarea>
                                </div>
                            </div>
                        </div>
						<div class="form-group longterm">
                            <label for="nok_first_name" class="col-sm-3 control-label">Next of kin First Name</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="nok_first_name" name="nok_first_name" value="{{ $educator->nok_first_name }}" placeholder="Next of Kin First Name...">
                                </div>
                            </div>
                        </div>
                        <div class="form-group longterm">
                            <label for="nok_surname" class="col-sm-3 control-label">Next of kin Surname</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="nok_surname" name="nok_surname" value="{{ $educator->nok_surname }}" placeholder="Next of Kin Surname...">
                                </div>
                            </div>
                        </div>
                        <div class="form-group longterm">
                            <label for="nok_relationship" class="col-sm-3 control-label">Relationship (Next of Kin)</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-info-circle"></i>
                                    </div>
                                    <input type="text" class="form-control" id="nok_relationship" name="physical_address" value="{{ $educator->nok_first_name }}" placeholder="Relationship with your next of kin...">
                                </div>
                            </div>
                        </div>
                        <div class="form-group longterm">
                            <label for="nok_cell_number" class="col-sm-3 control-label">Next of kin Cell Number</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="tel" class="form-control" id="nok_cell_number" name="nok_cell_number" value="{{ $educator->nok_cell_number }}" data-inputmask='"mask": "(999) 999-9999"' placeholder="Cell Number" data-mask>
                                </div>
                            </div>
                        </div>
                        <div class="form-group longterm">
                            <label for="nok_email" class="col-sm-3 control-label">Next of kin Email</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <input type="email" class="form-control" id="nok_email" name="nok_email" value="{{ $educator->nok_email }}" data-inputmask='"mask": "(999) 999-9999"' placeholder="Cell Number" data-mask>
                                </div>
                            </div>
                        </div>
                        <div class="form-group longterm">
                            <label for="course_sponsored" class="col-sm-3 control-label">Qualification Sponsored</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-info-circle"></i>
                                    </div>
                                    <input type="text" class="form-control" id="course_sponsored" name="course_sponsored" value="{{ $educator->course_sponsored }}" placeholder="Qualification Sponsored">
                                </div>
                            </div>
                        </div>
						<div class="form-group longterm">
                            <label for="email" class="col-sm-3 control-label">Email Address</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $educator->email }}" placeholder="Email Address">
                                </div>
                            </div>
                        </div>
						<div class="form-group longterm">
                            <label for="institution" class="col-sm-3 control-label">Institution</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <input type="text" class="form-control" id="institution" name="institution" value="{{ $educator->institution }}" placeholder="How did you know about this programme?">
                                </div>
                            </div>
                        </div>
						 <div class="form-group longterm">
                            <label for="engagement_date" class="col-sm-3 control-label">Date of First Engagement</label>

                            <div class="col-sm-9">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control datepicker" id="engagement_date" name="engagement_date" placeholder="  dd/mm/yyyy" value="{{ !empty($educator->engagement_date) ? date('Y M d', $educator->engagement_date) : ''}}">
                                </div>
                            </div>
                        </div>
						<div class="form-group longterm">
                            <label for="cv_doc" class="col-sm-3 control-label">CV</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="file" id="cv_doc" name="cv_doc" class="file file-loading" data-allowed-file-extensions='["pdf", "docx"]' data-show-upload="false">
										@if(!empty($cv_doc))
											<a href="{{ $cv_doc}}" target=\"_blank\">CV Document</a>
										@endif
                                </div>
                            </div>
                        </div>
						<div class="form-group longterm">
                            <label for="contract_doc" class="col-sm-3 control-label">Contract/Agreement </label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="file" id="contract_doc" name="contract_doc" class="file file-loading" data-allowed-file-extensions='["pdf", "docx"]' data-show-upload="false">
									@if(!empty($contract_doc))
										<a href="{{ $contract_doc}}" target=\"_blank\">Contract Document</a>
									@endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="button" id="cancel" class="btn btn-default"><i class="fa fa-arrow-left"></i> Cancel</button>
                        <button type="submit" name="command" id="update" class="btn btn-primary pull-right"><i class="fa fa-upload"></i> Update</button>
				   </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
        <!-- Confirmation Modal -->
        @if(Session('success_edit'))
            @include('contacts.partials.success_action_2buttons', ['modal_title' => 'Educator Details Updated!', 'modal_content' => session('success_edit')])
        @elseif(Session('success_add'))
            @include('contacts.partials.success_action_2buttons', ['modal_title' => 'Educator Added!', 'modal_content' => session('success_add')])
        @endif
    </div>
@endsection

@section('page_script')
            <!-- bootstrap datepicker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

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
    <!-- optionally if you need translation for your language then include locale file as mentioned below -->

    <script>
        $(function () {
            //Cancel button click event
            document.getElementById("cancel").onclick = function () {
                location.href = "{{ $back }}";
            };
			//Date picker
			$('.datepicker').datepicker({
				format: 'dd/mm/yyyy',
				endDate: '-1d',
				autoclose: true,
				todayHighlight: true
				});
			if ( {{ $educator->type  }} == 1)
			{
				$('.longterm').hide();
				$('.shortterm').show();
			}
			else if ( {{ $educator->type  }} == 2)
			{
				$('.shortterm').hide();
				$('.longterm').show();
			}

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

            //add more button click
            $('#add-more-clt').click(function () {
                location.href = "{!! route('learnerregistration', ['project' => !empty($educator->project_id) ? $educator->project_id : -1, 'activity'=> !empty($educator->activity_id) ? $educator->activity_id : -1]) !!}";
            });
        });
		function changeApplicant(type)
		{
			if (type == 1)
			{
				$('.longterm').hide();
				$('.shortterm').show();
			}
			else
			{
				$('.shortterm').hide();
				$('.longterm').show();
			}

		}
    </script>
@endsection