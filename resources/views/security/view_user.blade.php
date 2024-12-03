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
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">User</h3>
                    <p>User details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/users/{{ $user->id }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <div class="box-body" id="view_users">
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
					<hr class="hr-text" data-content="Personal Information">
						<div class="form-group">
							<label for="title" class="col-sm-2 control-label">Title </label>
							<div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-user"></i>
									</div>
									<select name="title"  id="title" class="form-control">
									<option value="">*** Select Your Title ***</option>
									<option value="1" {{ ($user->person->title == 1) ? ' selected' : '' }}>Mr</option>
									<option value="2" {{ ($user->person->title == 2) ? ' selected' : '' }}>Miss</option>
									<option value="3" {{ ($user->person->title == 3) ? ' selected' : '' }}>Ms</option>
									<option value="4" {{ ($user->person->title == 4) ? ' selected' : '' }}>Dr</option>
								</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="first_name" class="col-sm-2 control-label">First Name </label>
							<div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-user"></i>
									</div>
									<input type="text" class="form-control" id="first_name" name="first_name"
										   value="{{ $user->person->first_name }}" placeholder="First Name"
										   required>
								</div>
							</div>
						</div>	
						<div class="form-group">
							<label for="surname" class="col-sm-2 control-label">Surname</label>
							<div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-user"></i>
									</div>
									<input type="text" class="form-control" id="surname" name="surname"
										   value="{{ $user->person->surname }}" placeholder="Surname" required>
								</div>
							</div>
						</div> 
						<div class="form-group">
							<label for="known_as" class="col-sm-2 control-label">Known As</label>
							<div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-user"></i>
									</div>
									<input type="text" class="form-control" id="known_as" name="known_as"
										   value="{{ $user->person->known_as }}" placeholder="Known As">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="initial" class="col-sm-2 control-label">Initial</label>
							<div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-user"></i>
									</div>
									<input type="text" class="form-control" id="initial" name="initial"
										   value="{{ $user->person->initial }}" placeholder="Initial">
								</div>
							</div>
						</div>
                        <div class="form-group">
                            <label for="cell_number" class="col-sm-2 control-label">Cell Number</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control" name="cell_number" value="{{ $user->person->cell_number }}" data-inputmask='"mask": "(999) 999-9999"' placeholder="Cell Number" data-mask>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->person->email }}" placeholder="Email" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="res_address" class="col-sm-2 control-label">Address</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <textarea name="res_address" class="form-control" placeholder="Address">{{ $user->person->res_address }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="res_suburb" class="col-sm-2 control-label">Suburb</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <input type="text" class="form-control" id="res_suburb" name="res_suburb" value="{{ $user->person->res_suburb }}" placeholder="Suburb">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="res_city" class="col-sm-2 control-label">City</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <input type="text" class="form-control" id="res_city" name="res_city" value="{{ $user->person->res_city }}" placeholder="City">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="res_postal_code" class="col-sm-2 control-label">Postal Code</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <input type="number" class="form-control" id="res_postal_code" name="res_postal_code" value="{{ $user->person->res_postal_code }}" placeholder="Postal Code">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="res_province_id" class="col-sm-2 control-label">Province</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <select name="res_province_id" class="form-control">
                                        <option value="">*** Select Your Province ***</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province->id }}" {{ ($user->person->res_province_id == $province->id) ? ' selected' : '' }}>{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="date_of_birth" class="col-sm-2 control-label">Date of Birth</label>
                            <div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="date_of_birth" placeholder="  dd/mm/yyyy" value="{{ ($user->person->date_of_birth) ? date('d/m/Y',$user->person->date_of_birth) : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gender" class="col-sm-2 control-label">Gender</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-venus-mars"></i>
                                    </div>
                                    <select name="gender" class="form-control">
                                        <option value="">*** Select Your gender ***</option>
                                        <option value="1" {{ ($user->person->gender === 1) ? ' selected' : '' }}>Male</option>
                                        <option value="2" {{ ($user->person->gender === 2) ? ' selected' : '' }}>Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="id_number" class="col-sm-2 control-label">ID Number</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="number" class="form-control" id="id_number" name="id_number"
                                           value="{{ $user->person->id_number }}" placeholder="ID Number">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="passport_number" class="col-sm-2 control-label">Passport Number</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="passport_number" name="passport_number" value="{{ $user->person->passport_number }}" placeholder="Passport Number">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="marital_status" class="col-sm-2 control-label">Marital Status</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-venus-mars"></i>
                                    </div>
                                    <select name="marital_status" class="form-control">
                                        <option value="">*** Select Your Marital Status ***</option>
                                        @foreach($marital_statuses as $marital_status)
                                            <option value="{{ $marital_status->id }}" {{ ($user->person->marital_status == $marital_status->id) ? ' selected' : '' }}>{{ $marital_status->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ethnicity" class="col-sm-2 control-label">Ethnicity</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bar-chart"></i>
                                    </div>
                                    <select name="ethnicity" class="form-control">
                                        <option value="">*** Select Your Ethnic Group ***</option>
                                        @foreach($ethnicities as $ethnicity)
                                            <option value="{{ $ethnicity->id }}" {{ ($user->person->ethnicity == $ethnicity->id) ? ' selected' : '' }}>{{ $ethnicity->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
						<hr class="hr-text" data-content="Emergency Contact Information">
						<div class="form-group">
                            <label for="next_of_kin" class="col-sm-2 control-label">Name & Surname </label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="next_of_kin" name="next_of_kin" value="{{ $user->person->next_of_kin }}" placeholder="Next of Kin">
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="next_of_kin_number" class="col-sm-2 control-label">Number </label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="next_of_kin_number" name="next_of_kin_number" value="{{ $user->person->next_of_kin_number }}" placeholder="Next of Kin Number">
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="next_of_kin_work_number" class="col-sm-2 control-label">Work Number </label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="next_of_kin_work_number" name="next_of_kin_work_number" value="{{ $user->person->next_of_kin_work_number }}" placeholder="Work Number">
                                </div>
                            </div>
                        </div>
						<hr class="hr-text" data-content="Tax Information">
						<div class="form-group">
                            <label for="income_tax_number" class="col-sm-2 control-label">Income Tax Number </label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="income_tax_number" name="income_tax_number" value="{{ $user->person->income_tax_number }}" placeholder="Income Tax Number">
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="tax_office" class="col-sm-2 control-label">Tax Office </label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="tax_office" name="tax_office" value="{{ $user->person->tax_office }}" placeholder="Tax office">
                                </div>
                            </div>
                        </div>
						<hr class="hr-text" data-content="Banking Detail">
						<div class="form-group">
                            <label for="account_type" class="col-sm-2 control-label">Account Type </label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="account_type" name="account_type" value="{{ $user->person->account_type }}" placeholder="Account Type">
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="account_holder_name" class="col-sm-2 control-label">Account Holder Name </label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="account_holder_name" name="account_holder_name" value="{{ $user->person->account_holder_name }}" placeholder="Account Holder Name">
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="bank_name" class="col-sm-2 control-label">Bank Name </label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ $user->person->bank_name }}" placeholder="Bank Name">
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="branch_name" class="col-sm-2 control-label">Branch Name </label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="branch_name" name="branch_name" value="{{ $user->person->branch_name }}" placeholder="Branch Name">
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="account_number" class="col-sm-2 control-label">Account Number </label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="account_number" name="account_number" value="{{ $user->person->account_number }}" placeholder="Account Number">
                                </div>
                            </div>
                        </div>
						<hr class="hr-text" data-content="MED AID">
						<div class="form-group">
                            <label for="med_start_date" class="col-sm-2 control-label">Start Date </label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control datepicker" id="med_start_date" name="med_start_date" value="{{ (!empty($user->person->med_start_date)) ?  date(' d M Y', $user->person->med_start_date) : ''}}" placeholder="dd/mm/yyyy">
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="med_split" class="col-sm-2 control-label">Split </label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="med_split" name="med_split" value="{{ $user->person->med_split }}" placeholder="Split">
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="med_plan_name" class="col-sm-2 control-label">Plan Name </label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="med_plan_name" name="med_plan_name" value="{{ $user->person->med_plan_name }}" placeholder="Plan Name">
                                </div>
                            </div>
                        </div><div class="form-group">
                            <label for="med_dep_spouse" class="col-sm-2 control-label">Dependants Spouse </label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="med_dep_spouse" name="med_dep_spouse" value="{{ $user->person->med_dep_spouse }}" placeholder="Spouse">
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="med_dep_adult" class="col-sm-2 control-label">Dependants Adult </label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="med_dep_adult" name="med_dep_adult" value="{{ $user->person->med_dep_adult }}" placeholder="Adult">
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="med_dep_kids" class="col-sm-2 control-label">Dependants Children </label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="med_dep_kids" name="med_dep_kids" value="{{ $user->person->med_dep_kids }}" placeholder="Children">
                                </div>
                            </div>
                        </div>
						<hr class="hr-text" data-content="Provident Fund ">
						<div class="form-group">
                            <label for="provident_start_date" class="col-sm-2 control-label">Start Date </label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control datepicker" id="provident_start_date" name="provident_start_date" value="{{ (!empty($user->person->provident_start_date)) ?  date(' d M Y', $user->person->provident_start_date) : ''}}" placeholder="dd/mm/yyyy">
                                </div>
                            </div> 
                        </div>
						<div class="form-group">
                            <label for="provident_amount" class="col-sm-2 control-label">Provident Fund </label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="provident_amount" name="provident_amount" value="{{ $user->person->provident_amount }}" placeholder="Provident Amount">
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="provident_name" class="col-sm-2 control-label">Provident Fund Name </label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="provident_name" name="provident_name" value="{{ $user->person->provident_name }}" placeholder="Provident Name">
                                </div>
                            </div>
                        </div>
						<hr class="hr-text" data-content="Work Details">
						@if (isset($view_by_admin) && $view_by_admin === 1)
							<div class="form-group">
								<label for="start_time" class="col-sm-2 control-label">Working Hours</label>
								<div class="col-sm-5">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input type="time" class="form-control" id="start_time"
											   name="start_time" value="{{ $user->person->start_time }}"
											   placeholder="Start Time Eg 8:00">
									</div>
								</div>
								<div class="col-sm-5">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input type="time" class="form-control" id="end_time"
											   name="end_time" value="{{ $user->person->end_time }}"
											   placeholder="End Time Eg 17:00">
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="employee_number" class="col-sm-2 control-label">Employee Number</label>
								<div class="col-sm-10">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-user"></i>
										</div>
										<input type="text" class="form-control" id="employee_number"
											   name="employee_number" value="{{ $user->person->employee_number }}"
											   placeholder="Employee Number">
									</div>
								</div>
							</div>
                            @foreach($division_levels as $division_level)
                                <div class="form-group manual-field{{ $errors->has('division_level_' . $division_level->level) ? ' has-error' : '' }}">
									<label for="{{ 'division_level_' . $division_level->level }}"
										   class="col-sm-2 control-label">{{ $division_level->name }}</label>
									<div class="col-sm-10">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-black-tie"></i>
											</div>
											<select id="{{ 'division_level_' . $division_level->level }}"
													name="{{ 'division_level_' . $division_level->level }}"
													class="form-control"
													onchange="divDDOnChange(this, null, 'view_users')">
											</select>
										</div>
									</div>
								</div>
                            @endforeach
							<div class="form-group">
								<label for="date_joined" class="col-sm-2 control-label">Date Joined</label>
								<div class="col-sm-10">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
									   <input type="text" class="form-control datepicker" name="date_joined" placeholder="  dd/mm/yyyy" value="{{ ($user->person->date_joined) ? date('d/m/Y',$user->person->date_joined) : '' }}">
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="date_left" class="col-sm-2 control-label">Date Left Company</label>
								<div class="col-sm-10">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
									   <input type="text" class="form-control datepicker" name="date_left" placeholder="  dd/mm/yyyy" value="{{ ($user->person->date_left) ? date('d/m/Y',$user->person->date_left) : '' }}">
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="leave_profile" class="col-sm-2 control-label">Leave Profile</label>
								<div class="col-sm-10">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-black-tie"></i>
										</div>
										<select name="leave_profile" class="form-control">
											<option value="">*** Select leave Profile ***</option>
											@foreach($leave_profile as $leave_profiles)
												<option value="{{ $leave_profiles->id }}" {{ ($user->person->leave_profile == $leave_profiles->id) ?
												' selected' : '' }}>{{ $leave_profiles->name }}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="position" class="col-sm-2 control-label">Position</label>
								<div class="col-sm-10">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-black-tie"></i>
										</div>
										<select name="position" class="form-control">
											<option value="">*** Select a Position ***</option>
											@foreach($positions as $position)
												<option value="{{ $position->id }}" {{ ($user->person->position == $position->id) ? ' selected' : '' }}>{{ $position->name }}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="action" class="col-sm-2 control-label">Reports to</label>
								<div class="col-sm-10">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-user-circle"></i>
										</div>
										<select id="manager_id" name="manager_id" class="form-control select2"  style="width: 100%;">
											<option selected="selected" value="0" >*** Select a Manager ***</option>
												@foreach($employees as $employee)
												<option value="{{ $employee->id }}" {{ ($user->person->manager_id == $employee->id) ? ' selected' : '' }}>{{ $employee->first_name . ' ' . $employee->surname }}</option>
												@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="action" class="col-sm-2 control-label">Second manager in charge </label>
								<div class="col-sm-10">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-user-circle"></i>
										</div>
										<select id="second_manager_id" name="second_manager_id" class="form-control select2"  style="width: 100%;">
											<option selected="selected" value="0" >*** Select a Manager ***</option>
											@foreach($employees as $employee)
												<option value="{{ $employee->id }}" {{ ($user->person->second_manager_id == $employee->id) ? ' selected' : '' }}>{{ $employee->first_name . ' ' . $employee->surname }}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="disabled" class="col-sm-2 control-label">Disabled</label>
								<div class="col-sm-10">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-user"></i>
										</div>
										<select name="disabled" class="form-control">
										<option value="">*** Select Disabled ***</option>
										<option value="1" {{ ($user->person->disabled === 1) ? ' selected' : '' }}>Yes</option>
										<option value="2" {{ ($user->person->disabled === 2) ? ' selected' : '' }}>No</option>
									</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="nature_of_disability" class="col-sm-2 control-label">Nature of Disability </label>
								<div class="col-sm-10">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-book"></i>
										</div>
										<input type="text" class="form-control" id="nature_of_disability" name="nature_of_disability" value="{{ $user->person->nature_of_disability }}" placeholder="Nature of Disability">
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="employment_type" class="col-sm-2 control-label">Employment Type  </label>
								<div class="col-sm-10">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-user"></i>
										</div>
										<select name="employment_type" class="form-control">
										<option value="">*** Select Employment Type ***</option>
										<option value="1" {{ ($user->person->employment_type === 1) ? ' selected' : '' }}>Permanent </option>
										<option value="2" {{ ($user->person->employment_type === 2) ? ' selected' : '' }}>Temporary </option>
									</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="occupational_level" class="col-sm-2 control-label">Occupational Level </label>
								<div class="col-sm-10">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-user"></i>
										</div>
										<select name="occupational_level" class="form-control">
										<option value="">*** Select Occupational Level ***</option>
										<option value="1" {{ ($user->person->occupational_level === 1) ? ' selected' : '' }}>Senior Management</option>
										<option value="2" {{ ($user->person->occupational_level === 2) ? ' selected' : '' }}>Middle Management</option>
										<option value="2" {{ ($user->person->occupational_level === 3) ? ' selected' : '' }}>Junior Management</option>
										<option value="2" {{ ($user->person->occupational_level === 4) ? ' selected' : '' }}>Semi Skilled </option>
										<option value="2" {{ ($user->person->occupational_level === 5) ? ' selected' : '' }}>Unskilled  </option>
									</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="job_function" class="col-sm-2 control-label">Job Function </label>
								<div class="col-sm-10">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-user"></i>
										</div>
										<select name="job_function" class="form-control">
										<option value="">*** Select Job Function ***</option>
										<option value="1" {{ ($user->person->job_function === 1) ? ' selected' : '' }}>Core/Operational  </option>
										<option value="2" {{ ($user->person->job_function === 2) ? ' selected' : '' }}>Support  </option>
									</select>
									</div>
								</div>
							</div>
						@endif
                        <div class="form-group">
                            <label for="profile_pic" class="col-sm-2 control-label">Profile Picture</label>

                            <div class="col-sm-10">
                                @if(!empty($avatar))
                                    <div style="margin-bottom: 10px;">
                                        <img src="{{ url($avatar) }}" class="img-responsive img-thumbnail" width="200" height="200">
                                    </div>
                                @endif
                                <input type="file" id="profile_pic" name="profile_pic" class="file file-loading" data-allowed-file-extensions='["jpg", "jpeg", "png"]' data-show-upload="false">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="change_password" class="col-sm-2 control-label">Password</label>

                            <div class="col-sm-10">
                                <font data-toggle="tooltip" title="Click here to change password."><button type="button" id="change_password" class="btn btn-default btn-flat btn-block" data-toggle="modal" data-target="#myPasswordModal"><i class="fa fa-lock"></i> Change Password</button></font>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer" style="text-align: center;">
                        <button type="button" id="cancel" class="btn btn-default pull-left">Profile</button>
                        <button type="submit" name="command" id="update" class="btn btn-primary pull-right">Update</button>
						@if (isset($view_by_admin) && $view_by_admin === 1)
						<button type="button" class="btn btn-primary" id="role_button" onclick="postData({{$user->id}}, 'roles');">Roles</button>
						<button type="button" class="btn btn-primary" id="access_button" onclick="postData({{$user->id}}, 'access');">Modules Access</button>
						@endif
                          
						@if (isset($view_by_admin) && $view_by_admin === 1)
						<button type="button" class="btn btn-warning" id="delete_button" name="command"
								onclick="if(confirm('Are you sure you want to delete this User ?')){ deleteRecord()} else {return false;}"
                                value="Delete"><i class="fa fa-trash"></i> Delete
                        </button>
						@endif
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->

        <!-- Password Modal form-->
        @if (isset($user_profile) && $user_profile === 1)
            @include('security.partials.change_my_password')
        @elseif (isset($view_by_admin) && $view_by_admin === 1)
            @include('security.partials.change_password')
        @endif
        <!-- /.Password Modal form-->

        <!-- Confirmation Modal -->
        @if(Session('success_edit'))
            @include('contacts.partials.success_action', ['modal_title' => "User's Details Updated!", 'modal_content' => session('success_edit')])
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
    <!-- optionally if you need translation for your language then include locale file as mentioned below
    <script src="/bower_components/bootstrap_fileinput/js/locales/<lang>.js"></script>-->
    <!-- End Bootstrap File input -->
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>

    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>

    <script>
		
        $(function () {
            //Cancel button click event
            document.getElementById("cancel").onclick = function () {
				location.href = '{{ route('employee.show',$routeUser ) }} ';
            };

            //Date picker
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                //endDate: '-1d',
                autoclose: true
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

            //Post password form to server using ajax
            $('#my-password').on('click', function() {
                var strUrl = '{{ '/users/' . $user->id . '/pw' }}';
                var objData = {
                    current_password: $('#current_password').val(),
                    new_password: $('#new_password').val(),
                    confirm_password: $('#confirm_password').val(),
                    _token: $('input[name=_token]').val()
                };
                var modalID = 'myPasswordModal';
                var submitBtnID = 'my-password';
                var redirectUrl = null;
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The password has been changed successfully.';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            //Post user password form to server using ajax
            $('#user-password').on('click', function() {
                var strUrl = '{{ '/users/' . $user->id . '/upw' }}';
                var objData = {
                    new_password: $('#new_password').val(),
                    _token: $('input[name=_token]').val()
                };
                var modalID = 'myPasswordModal';
                var submitBtnID = 'user-password';
                var redirectUrl = null;
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The password has been changed successfully.';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            //Load divisions drop down
            var parentDDID = '';
            var loadAllDivs = 1;
            @if (isset($view_by_admin) && $view_by_admin === 1)
                @foreach($division_levels as $division_level)
                    //Populate drop down on page load
                    var ddID = '{{ 'division_level_' . $division_level->level }}';
                    var postTo = '{!! route('divisionsdropdown') !!}';
                    var selectedOption = '';
                    var divLevel = parseInt('{{ $division_level->level }}');
                    if (divLevel == 5) selectedOption = '{{ $user->person->division_level_5 }}';
                    else if(divLevel == 4) selectedOption = '{{ $user->person->division_level_4 }}';
                    else if(divLevel == 3) selectedOption = '{{ $user->person->division_level_3 }}';
                    else if(divLevel == 2) selectedOption = '{{ $user->person->division_level_2 }}';
                    else if(divLevel == 1) selectedOption = '{{ $user->person->division_level_1 }}';
                    var incInactive = -1;
                    var loadAll = loadAllDivs;
                    loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo);
                    parentDDID = ddID;
                    loadAllDivs = 1;
                @endforeach
            @endif
        });

        //Load divisions drop down

		function postData(id, data)
		{
			if (data == 'access')
				location.href = "/users/module_access/" + id;
			else if (data == 'roles')
				location.href = "/hr/role_users/" + id;
		}
		function deleteRecord() {
			location.href = "/user/delete/{{ $user->id }}";
		}
    </script>
@endsection