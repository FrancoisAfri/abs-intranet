@extends('layouts.main_layout')
@section('page_dependencies')
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
                <form class="form-horizontal" method="POST" action="/education/nsw_save">
					<input type="hidden" name="file_index" id="file_index" value="1"/>
					<input type="hidden" name="total_files" id="total_files" value="1"/>
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
											<option value="{{ $school->id }}"{{ ($school->id === old('school_id')) ? ' selected="selected"' : '' }}>{{ $school->name }}</option>
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
                                    <input type="text" class="form-control datepicker" id="date_attended" name="date_attended" placeholder="  dd/mm/yyyy" value="{{ old('date_attended') }}">
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
									<input type="number" class="form-control" id="educator_id_number" name="educator_id_number" placeholder="ID Number of the Educator" value="{{ old('educator_id_number') }}">
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
									<input type="text" class="form-control" id="educator_first_name" name="educator_first_name" placeholder="Educator First Name" value="{{ old('educator_first_name') }}">
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
									<input type="text" class="form-control" id="educator_surname" name="educator_surname" placeholder="Educator Surname" value="{{ old('educator_surname') }}">
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
									<input type="number" class="form-control" id="educators_number" name="educators_number" placeholder="Number of Educators that came with the group(s)" value="{{ old('educators_number') }}">
								</div>
							</div>
						</div>
						<div id="tab_tab">
							<div class="form-group{{ $errors->has('grade') ? ' has-error' : '' }}" id="grade_name">
								<label for="grade" class="col-sm-2 control-label">Grade</label>
								<div class="col-sm-10">
									<div class="input-group">
										<div class="input-group-addon">
										   <i class="fa fa-book"></i>
										</div>
										<input type="number" class="form-control" id="grade" name="grade" value="{{ old('grade') }}" placeholder="Grade">
									</div>
								</div>
							</div>
							<div class="form-group{{ $errors->has('learner_number') ? ' has-error' : '' }}" id="num_learn">
								<label for="learner_number"  class="col-sm-2 control-label">Number of Learners</label>
								<div class="col-sm-10">
									<div class="input-group">
										<div class="input-group-addon">
										   <i class="fa fa-users"></i>
										</div>
										<input type="number" class="form-control" id="learner_number" name="learner_number" value="{{ old('learner_number') }}" placeholder="Total number of learners in this grade">
									</div>
								</div>
							</div>
							<div class="form-group{{ $errors->has('male_number') ? ' has-error' : '' }}" id="num_male">
								<label for="male_number" class="col-sm-2 control-label">Male Learners</label>
								<div class="col-sm-10">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-male"></i>
										</div>
										<input type="number" name="male_number" id="male_number" value="{{ old('male_number') }}" class="form-control" placeholder="Number of male learners in this grade">
									</div>
								</div>
							</div>
							<div class="form-group{{ $errors->has('female_number') ? ' has-error' : '' }}" id="num_female">
								<label for="female_number" class="col-sm-2 control-label">Female Learners</label>
								<div class="col-sm-10">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-female"></i>
										</div>
										<input type="number" name="female_number" id="female_number" value="{{ old('female_number') }}" class="form-control" placeholder="Number of female learners in this grade">
									</div>
								</div>
							</div>
							<div class="form-group{{ $errors->has('african_number') ? ' has-error' : '' }}" id="num_black">
								<label for="african_number" class="col-sm-2 control-label">Black Learners</label>
								<div class="col-sm-10">
									<div class="input-group date">
										<div class="input-group-addon">
											<i class="fa fa-bar-chart"></i>
										</div>
										<input type="number" name="african_number" id="african_number" value="{{ old('african_number') }}" class="form-control" placeholder="Number of black learners in this grade">
									</div>
								</div>
							</div>
							<div class="form-group{{ $errors->has('caucasian_number') ? ' has-error' : '' }}" id="num_white">
								<label for="caucasian_number" class="col-sm-2 control-label">White Learners</label>
								<div class="col-sm-10">
									<div class="input-group date">
										<div class="input-group-addon">
											<i class="fa fa-bar-chart"></i>
										</div>
										<input type="number" name="caucasian_number" id="caucasian_number" value="{{ old('caucasian_number') }}" class="form-control" placeholder="Number of white learners in this grade">
									</div>
								</div>
							</div>
							<div class="form-group{{ $errors->has('coloured_number') ? ' has-error' : '' }}" id="num_coloured">
								<label for="coloured_number" class="col-sm-2 control-label">Coloured Learners</label>
								<div class="col-sm-10">
									<div class="input-group date">
										<div class="input-group-addon">
											<i class="fa fa-bar-chart"></i>
										</div>
										<input type="number" name="coloured_number" value="{{ old('coloured_number') }}" id="coloured_number" class="form-control" placeholder="Number of coloured learners in this grade">
									</div>
								</div>
							</div>
							<div class="form-group{{ $errors->has('indian_number') ? ' has-error' : '' }}" id="num_indian">
								<label for="indian_number" class="col-sm-2 control-label">Indian Learners</label>
								<div class="col-sm-10">
									<div class="input-group date">
										<div class="input-group-addon">
											<i class="fa fa-bar-chart"></i>
										</div>
										<input type="number" name="indian_number" value="{{ old('indian_number') }}" id="indian_number" class="form-control" placeholder="Number of indian learners in this grade">
									</div>
								</div>
							</div>
							<div class="form-group{{ $errors->has('asian_number') ? ' has-error' : '' }}" id="num_asian">
								<label for="asian_number" class="col-sm-2 control-label">Asian Learners</label>
								<div class="col-sm-10">
									<div class="input-group date">
										<div class="input-group-addon">
											<i class="fa fa-bar-chart"></i>
										</div>
										<input type="number" name="asian_number" value="{{ old('asian_number') }}" id="asian_number" class="form-control" placeholder="Number of asian learners in this grade">
									</div>
								</div>
							</div>
						</div>
						<div class="form-group"  id="final_row">
							<div class="col-sm-10 col-sm-offset-2">
								<button type="button" class="btn btn-primary btn-block btn-flat add_more"  onclick="addFile()"><i class="fa fa-clone"></i> Add More Grades</button>
							</div>
						</div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button id="cancel" class="btn btn-default"><i class="fa fa-arrow-left"></i> Cancel</button>
                        <button type="submit" class="btn btn-success pull-right"><i class="fa fa-database"></i> Add</button>
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
		function clone(id,file_index,child_id)
		{
			var clone=document.getElementById(id).cloneNode(true);
			clone.setAttribute("id",file_index);
			clone.setAttribute("name",file_index);
			clone.style.display="block";
			clone.querySelector('#'+child_id).setAttribute("name",child_id+'['+file_index+']');
			clone.querySelector('#'+child_id).disabled=false;
			clone.querySelector('#'+child_id).setAttribute("id",child_id+'['+file_index+']');
			return clone;
		}
		function addFile()
		{
			var table = document.getElementById("tab_tab"); 
			var file_index = document.getElementById("file_index");
			file_index.value=++file_index.value;
			var asian_clone=clone("num_asian",file_index.value,"asian_number");
			var indian_clone=clone("num_indian",file_index.value,"indian_number");
			var coloured_clone=clone("num_coloured",file_index.value,"coloured_number");
			var white_clone=clone("num_white",file_index.value,"caucasian_number");
			var black_clone=clone("num_black",file_index.value,"african_number");
			var female_clone=clone("num_female",file_index.value,"female_number");
			var male_clone=clone("num_male",file_index.value,"male_number");
			var learn_clone=clone("num_learn",file_index.value,"learner_number");
			var grade_clone=clone("grade_name",file_index.value,"grade");
			var final_row =document.getElementById("final_row").cloneNode(false);
			table.appendChild(grade_clone);
			table.appendChild(learn_clone);
			table.appendChild(male_clone);
			table.appendChild(female_clone);
			table.appendChild(black_clone);
			table.appendChild(white_clone);
			table.appendChild(coloured_clone);
			table.appendChild(indian_clone);
			table.appendChild(asian_clone);
			table.appendChild(final_row);
			var total_files = document.getElementById("total_files");
			total_files.value=++total_files.value;
			//change the following using jquery if necessary
			var remove=document.getElementsByName("remove");
			for(var i=0; i < remove.length; i++)
			remove[i].style.display="inline";	
		}
        //Phone mask
        $("[data-mask]").inputmask();
    </script>
@endsection