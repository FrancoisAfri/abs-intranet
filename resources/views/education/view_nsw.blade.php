@extends('layouts.main_layout')
@section('page_dependencies')
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <i class="fa fa-users pull-right"></i>
                    <h3 class="box-title">New Group Learner</h3>
                    <p>Enter Group Learner details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/education/group/{{ $group->id }}">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

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
                        <div class="form-group{{ $errors->has('school_id') ? ' has-error' : '' }}">
                            <label for="school_id" class="col-sm-2 control-label">School</label>
                            <div class="col-sm-10">
                                <div class="input-group">
									<div class="input-group-addon">
                              			<i class="fa fa-building"></i>
                            		</div>
									<select class="form-control select2" style="width: 100%;" id="school_id" name="school_id">
										<option value="">*** Select a School ***</option>
										@foreach($schools as $school)
											<option value="{{ $school->id }}" {{ (isset($group) && $school->id == $group->school_id) ? ' selected': '' }}>{{ $school->name }}</option>
										@endforeach
									</select>
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('date_attended') ? ' has-error' : '' }}">
                            <label for="date_attended" class="col-sm-2 control-label">Date</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control datepicker" id="date_attended" name="date_attended" placeholder="  dd/mm/yyyy" value="{{!empty($group->date_attended) ? date('d/m/Y', $group->date_attended) : ''}}">
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('educator_id_number') ? ' has-error' : '' }}">
							<label for="educator_id_number" class="col-sm-2 control-label">Educator ID Number</label>

							<div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-user"></i>
									</div>
									<input type="number" class="form-control" id="educator_id_number" name="educator_id_number" placeholder="ID Number of the Educator" value="{{ !empty($group->nswStxesEducators->educator_id_number) ? $group->nswStxesEducators->educator_id_number : '' }}">
								</div>
							</div>
						</div>
						<div class="form-group{{ $errors->has('educator_first_name') ? ' has-error' : '' }}">
							<label for="educator_first_name" class="col-sm-2 control-label">Educator First Name</label>

							<div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-user"></i>
									</div>
									<input type="text" class="form-control" id="educator_first_name" name="educator_first_name" placeholder="Educator First Name" value="{{ !empty($group->nswStxesEducators->educator_first_name) ? $group->nswStxesEducators->educator_first_name : ''}}">
								</div>
							</div>
						</div>
						<div class="form-group{{ $errors->has('educator_surname') ? ' has-error' : '' }}">
							<label for="educator_surname" class="col-sm-2 control-label">Educator Surname</label>

							<div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-user"></i>
									</div>
									<input type="text" class="form-control" id="educator_surname" name="educator_surname" placeholder="Educator Surname" value="{{ !empty($group->nswStxesEducators->educator_surname) ? $group->nswStxesEducators->educator_surname : '' }}">
								</div>
							</div>
						</div>
						<div class="form-group{{ $errors->has('educators_number') ? ' has-error' : '' }}">
							<label for="educators_number" class="col-sm-2 control-label">Educators Number</label>

							<div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="number" class="form-control" id="educators_number" name="educators_number" placeholder="Number of Educators that came with the group(s)" value="{{  !empty($group->nswStxesEducators->educators_number) ? $group->nswStxesEducators->educators_number : '' }}">
								</div>
							</div>
						</div>
                    </div>
					<div class="box-body">
					  <table id="example2" class="table table-bordered table-hover">
						<thead>
						<tr>
						  <th>&nbsp;</th>
						  <th>Grade</th>
						  <th>Learner No</th>
						  <th>Male No</th>
						  <th>Female No</th>
						  <th>African No</th>
						  <th>Asian No</th>
						  <th>Caucasian No</th>
						  <th>Coloured No</th>
						  <th>Indian No</th>
						</tr>
						</thead>
						<tbody>
							@foreach($group->nswStxesGrades as $grade)
							<tr>
								<td>
									<button type="button" class="btn btn-success pull-right" id="approve_button" name=""
                                    value="" data-toggle="modal" data-target="#edit-grade-modal"
                                    data-id="{{ $grade->id }}" 
									data-grade="{{ $grade->grade }}" 
									data-learner_number="{{ $grade->learner_number }}" 
									data-male_number="{{ $grade->male_number }}"
									data-female_number="{{ $grade->female_number }}"
									data-african_number="{{ $grade->african_number }}"
									data-asian_number="{{ $grade->asian_number }}"
									data-caucasian_number="{{ $grade->caucasian_number }}"
									data-coloured_number="{{ $grade->coloured_number }}"
									data-indian_number="{{ $grade->indian_number }}"
									data-nsw_id="{{ $grade->nsw_id }}"> Modify Grade
									</button>
								</td>
							  <td>{{ !empty($grade->grade) ? $grade->grade : '' }}</td>
							  <td>{{ !empty($grade->learner_number) ? $grade->learner_number : '' }}</td>
							  <td>{{ !empty($grade->male_number) ? $grade->male_number : '' }}</td>
							  <td>{{ !empty($grade->female_number) ? $grade->female_number : '' }}</td>
							  <td>{{ !empty($grade->african_number) ? $grade->african_number : '' }}</td>
							  <td>{{ !empty($grade->asian_number) ? $grade->asian_number : '' }}</td>
							  <td>{{ !empty($grade->caucasian_number) ? $grade->caucasian_number : '' }}</td>
							  <td>{{ !empty($grade->coloured_number) ? $grade->coloured_number : '' }}</td>
							  <td>{{ !empty($grade->indian_number) ? $grade->indian_number : '' }}</td>
							</tr>
							@endforeach
						
						</tbody>
						<tfoot>
						<tr>
						 <th>&nbsp;</th>
						  <th>Grade</th>
						  <th>Learner No</th>
						  <th>Male No</th>
						  <th>Female No</th>
						  <th>African No</th>
						  <th>Asian No</th>
						  <th>Caucasian No</th>
						  <th>Coloured No</th>
						  <th>Indian No</th>
						</tr>
						</tfoot>
					  </table>
					</div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button id="cancel" class="btn btn-default"><i class="fa fa-arrow-left"></i> Cancel</button>
                        <button type="submit" class="btn btn-success pull-right"><i class="fa fa-database"></i> Update</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
		@if(Session('success_edit'))
            @include('contacts.partials.success_action', ['modal_title' => 'Group Learner Updated!', 'modal_content' => session('success_edit')])
        @elseif(Session('success_add'))
            @include('contacts.partials.success_action', ['modal_title' => 'Group Learner Added!', 'modal_content' => session('success_add')])
        @endif
		<!-- Include update grade -->
        @include('education.partials.edit_grade')
    </div>
    @endsection

    @section('page_script')
	<!-- Datatable -->
	<script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
            <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
	<!-- Select2 -->
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
	<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <script type="text/javascript">
        //Cancel button click event
        document.getElementById("cancel").onclick = function () {
            location.href = "/contacts";
        };
		 $(function () {
		//Date picker
		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy',
			autoclose: true,
			todayHighlight: true
            });
			    //Initialize Select2 Elements
			$(".select2").select2();

        });
        //Phone mask
        $("[data-mask]").inputmask();
		$(function () {
            var gradeId;
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

            //pass grade data to the edit grade modal
            $('#edit-grade-modal').on('show.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                gradeId = btnEdit.data('id');
                var grade = btnEdit.data('grade');
                var male_number = btnEdit.data('male_number');
                var learner_number = btnEdit.data('learner_number');
                var female_number = btnEdit.data('female_number');
                var african_number = btnEdit.data('african_number');
                var asian_number = btnEdit.data('asian_number');
                var caucasian_number = btnEdit.data('caucasian_number');
                var coloured_number = btnEdit.data('coloured_number');
                var indian_number = btnEdit.data('indian_number');
                var modal = $(this);
                modal.find('#grade').val(grade);
                modal.find('#male_number').val(male_number);
                modal.find('#learner_number').val(learner_number);
                modal.find('#female_number').val(female_number);
                modal.find('#african_number').val(african_number);
                modal.find('#asian_number').val(asian_number);
                modal.find('#caucasian_number').val(caucasian_number);
                modal.find('#coloured_number').val(coloured_number);
                modal.find('#indian_number').val(indian_number);
            });

            //function to post grade form to server using ajax
            function postGradeForm(formMethod, postUrl, formName) {
                //alert('do you get here');
                $.ajax({
                    method: formMethod,
                    url: postUrl,
                    data: {
                        grade: $('form[name=' + formName + ']').find('#grade').val(),
                        male_number: $('form[name=' + formName + ']').find('#male_number').val(),
                        learner_number: $('form[name=' + formName + ']').find('#learner_number').val(),
                        female_number: $('form[name=' + formName + ']').find('#female_number').val(),
                        african_number: $('form[name=' + formName + ']').find('#african_number').val(),
                        asian_number: $('form[name=' + formName + ']').find('#asian_number').val(),
                        caucasian_number: $('form[name=' + formName + ']').find('#caucasian_number').val(),
                        coloured_number: $('form[name=' + formName + ']').find('#coloured_number').val(),
                        indian_number: $('form[name=' + formName + ']').find('#indian_number').val(),
                        _token: $('input[name=_token]').val()
                    },
                    success: function(success) {
                        location.href = "/education/nsw/" + {{$group->id}} + "/edit";
                        $('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups
                        $('form[name=' + formName + ']').trigger('reset'); //Reset the form

                        var successHTML = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-check"></i> Grade updated!</h4>';
                        successHTML += 'Grade record has been updated successfully.';
                        $('#grade-success-alert').addClass('alert alert-success alert-dismissible')
                                .fadeIn()
                                .html(successHTML);

                        //show the newly added on the setup list
                        $('#active-grade').removeClass('active');
                        var newModuleList = $('#modules-list').html();
                        newModuleList += '<li id="active-grade" class="list-group-item active"><b>' + success['new_grade'] + '</b> <font class="pull-right">' + success['new_male_number'] + ';</font></li>';

                        $('#modules-list').html(newModuleList);

                        //auto hide modal after 7 seconds
                        $("#edit-grade-modal").alert();
                        window.setTimeout(function() { $("#edit-grade-modal").modal('hide'); }, 5000);

                        //autoclose alert after 7 seconds
                        $("#grade-success-alert").alert();
                        window.setTimeout(function() { $("#grade-success-alert").fadeOut('slow'); }, 5000);
                    },
                    error: function(xhr) {
                        //if(xhr.status === 401) //redirect if not authenticated
                        //$( location ).prop( 'pathname', 'auth/login' );
                        if(xhr.status === 422) {
                            console.log(xhr);
                            var errors = xhr.responseJSON; //get the errors response data

                            $('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups

                            var errorsHTML = '<button type="button" id="close-invalid-input-alert" class="close" aria-hidden="true">&times;</button><h4><i class="icon fa fa-ban"></i> Invalid Input!</h4><ul>';
                            $.each(errors, function (key, value) {
                                errorsHTML += '<li>' + value[0] + '</li>'; //shows only the first error.
                                $('#'+key).closest('.form-group')
                                        .addClass('has-error'); //Add the has error class to form-groups with errors
                            });
                            errorsHTML += '</ul>';

                            $('#grade-invalid-input-alert').addClass('alert alert-danger alert-dismissible')
                                    .fadeIn()
                                    .html(errorsHTML);

                            //autoclose alert after 7 seconds
                            $("#grade-invalid-input-alert").alert();
                            window.setTimeout(function() { $("#grade-invalid-input-alert").fadeOut('slow'); }, 7000);

                            //Close btn click
                            $('#close-invalid-input-alert').on('click', function () {
                                $("#grade-invalid-input-alert").fadeOut('slow');
                            });
                        }
                    }
                });
            }
            $('#update-grade').on('click', function() {
                postGradeForm('PATCH', '/education/grade_edit/' + gradeId, 'edit-grade-form');
            });
        });
    </script>
@endsection