@extends('layouts.main_layout')
@section('page_dependencies')
<!-- Include Date Range Picker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- iCheck -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
<!-- bootstrap file input -->
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<!--Time Charger-->
<!-- ### -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-users"></i> Details</h3>
					<div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/contacts/company">
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
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-sm-2 control-label">Company Name</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-building"></i>
                                    </div>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ !empty($company->name) ? $company->name : '' }}" placeholder="Company Name" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('cp_home_number') ? ' has-error' : '' }}">
                            <label for="cp_home_number" class="col-sm-2 control-label">Office Number</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control" id="cp_home_number" name="cp_home_number" value="{{ !empty($company->cp_home_number) ? $company->cp_home_number : '' }}" data-inputmask='"mask": "(999) 999-9999"' placeholder="Office Number" data-mask readonly>
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('fax_number') ? ' has-error' : '' }}">
							<label for="fax_number" class="col-sm-2 control-label">Fax Number</label>
							<div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-fax"></i>
									</div>
									<input type="text" class="form-control" id="fax_number" name="fax_number" value="{{ !empty($company->fax_number) ? $company->fax_number : '' }}" data-inputmask='"mask": "(999) 999-9999"' placeholder="Fax Number" data-mask readonly>
								</div>
							</div>
						</div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ !empty($company->email) ? $company->email : '' }}" placeholder="Email Address" readonly>
									<a href="mailto:{{ $company->email }}">{{ $company->email }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('phys_address') ? ' has-error' : '' }}">
                            <label for="phys_address" class="col-sm-2 control-label">Street Address</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <input type="text" class="form-control" id="phys_address" name="phys_address" value="{{ !empty($company->phys_address) ? $company->phys_address : '' }}" placeholder="Street Address" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('phys_city') ? ' has-error' : '' }}">
                            <label for="phys_city" class="col-sm-2 control-label">City</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <input type="text" class="form-control" id="phys_city" name="phys_city" value="{{ !empty($company->phys_city) ? $company->phys_city : '' }}" placeholder="City" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('phys_province') ? ' has-error' : '' }}">
                            <label for="phys_province" class="col-sm-2 control-label">Province</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <input type="text" class="form-control" id="phys_province" name="phys_province" value="{{ !empty($provinces->name) ? $provinces->name : '' }}" placeholder="Province" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('phys_postal_code') ? ' has-error' : '' }}">
                            <label for="phys_postal_code" class="col-sm-2 control-label">Postal Code</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <input type="text" class="form-control" id="phys_postal_code" name="phys_postal_code" value="{{ !empty($company->phys_postal_code) ? $company->phys_postal_code : '' }}" placeholder="Postal Code" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('registration_number') ? ' has-error' : '' }}">
                            <label for="registration_number" class="col-sm-2 control-label">Registration Number</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </div>
                                    <input type="text" class="form-control" id="registration_number" name="registration_number" value="{{ !empty($company->registration_number) ? $company->registration_number : '' }}" placeholder="Company Registration Number" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('vat_number') ? ' has-error' : '' }}">
                            <label for="vat_number" class="col-sm-2 control-label">VAT Number</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </div>
                                    <input type="text" class="form-control" id="vat_number" name="vat_number" value="{{ !empty($company->vat_number) ? $company->vat_number : '' }}" placeholder="VAT Number" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('tax_number') ? ' has-error' : '' }}">
                            <label for="tax_number" class="col-sm-2 control-label">Tax Number</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </div>
                                    <input type="text" class="form-control" id="tax_number" name="tax_number" value="{{ !empty($company->tax_number) ? $company->tax_number : '' }}" placeholder="Tax Number" readonly>
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('account_number') ? ' has-error' : '' }}">
                            <label for="account_number" class="col-sm-2 control-label">Account Number</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </div>
                                    <input type="text" class="form-control" id="account_number" name="account_number" value="{{ !empty($company->account_number) ? $company->account_number : '' }}" placeholder="Account Number" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('bee_score') ? ' has-error' : '' }}">
                            <label for="bee_score" class="col-sm-2 control-label">BEE Score</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-star-half-o"></i>
                                    </div>
                                    <input type="text" class="form-control" id="bee_score" name="bee_score" value="{{ !empty($company->bee_score) ? $company->bee_score : '' }}" placeholder="BEE Score" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                            <label for="status" class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-pinterest-p"></i>
                                    </div>
                                    <select readonly="readonly" name="status" class="form-control">
                                        <option value="">*** Select Your Priority ***</option>
                                        <option value="1" >Start</option>
                                        <option value="2" >Progress</option>
                                        <option value="3" >Assign</option>
                                    </select >
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('estimated_spent') ? ' has-error' : '' }}">
                            <label for="estimated_spent" class="col-sm-2 control-label">Estimated Expenditure</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-credit-card-alt"></i>
                                    </div>
                                    <input type="number" class="form-control" id="estimated_spent" name="estimated_spent" value="{{ !empty($company->estimated_spent) ? $company->estimated_spent : '' }}" placeholder="Estimated Expenditure" readonly>
                                </div>
                            </div>
                        </div>
                         <div class="form-group{{ $errors->has('domain_name') ? ' has-error' : '' }}">
                            <label for="domain_name" class="col-sm-2 control-label">Domain Name</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-internet-explorer"></i>
                                    </div>
                                    <input type="text" class="form-control" id="domain_name" name="domain_name" value="{{ !empty($company->domain_name) ? $company->domain_name : '' }}" placeholder="Domain name" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('bee_certificate_doc') ? ' has-error' : '' }}">
                            <label for="bee_certificate_doc" class="col-sm-2 control-label">BEE Certificate</label>
                            <div class="col-sm-10">
                                @if(!empty($bee_certificate_doc))
                                    <a class="btn btn-default btn-flat btn-block" href="{{ $bee_certificate_doc }}" target="_blank"><i class="fa fa-file-pdf-o"></i> Click Here To View The Document</a>
                                @else
                                    <a class="btn btn-default btn-flat btn-block"><i class="fa fa-exclamation-triangle"></i> Nothing Was Uploaded</a>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('comp_reg_doc') ? ' has-error' : '' }}">
                            <label for="comp_reg_doc" class="col-sm-2 control-label">Registration Document</label>
                            <div class="col-sm-10">
                                @if(!empty($comp_reg_doc))
                                    <a class="btn btn-default btn-flat btn-block" href="{{ $comp_reg_doc }}" target="_blank"><i class="fa fa-file-pdf-o"></i> Click Here To View The Document</a>
                                @else
                                    <a class="btn btn-default btn-flat btn-block"><i class="fa fa-exclamation-triangle"></i> Nothing Was Uploaded</a>
                                @endif
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('dept_id') ? ' has-error' : '' }}">
                            <label for="dept_id" class="col-sm-2 control-label">{{$dept->name}}</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <input type="text" class="form-control" id="dept_id" name="dept_id" value="{{ !empty($deparments->name) ? $deparments->name : '' }}" placeholder="Department" readonly>
                                </div>
                            </div>
                        </div>
						<div class="form-group{{ $errors->has('account_owners') ? ' has-error' : '' }}">
                            <label for="account_owners" class="col-sm-2 control-label">Account Owner</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <input type="text" class="form-control" id="account_owners" name="account_owners" value="{{ !empty($company->accountManager->first_name) && !empty($company->accountManager->surname) ? $company->accountManager->first_name." ".$company->accountManager->surname : '' }}" placeholder="Account Owner" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer" style="text-align: center;">
                        @if($canEdit)
                            <a href="/contacts/company/{{ $company->id }}/edit" class="btn btn-primary pull-right"><i class="fa fa-pencil-square-o"></i> Edit</a>
                            <a href="/contacts/company/{{ $company->id }}/actdeact" class="btn btn-primary pull-left  {{ (!empty($company->status) && $company->status == 1) ? " btn-danger " : " btn-success" }}"><i class="fa fa-pencil-square-o"></i> {{(!empty($company->status) && $company->status == 1) ? "Deactivate" : "Activate"}}</a>
                            <a href="/contacts/{{ $company->id }}/viewcompanydocuments" class="btn btn-primary"  target="_blank"><i class="fa fa-clipboard"> </i> Company Document(s)</a>
                        @endif
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
            <!-- Company's contacts box -->
            <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-users"></i> Contacts</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding no-margin">
                    <div id="company-contacts" style="margin-right: 10px; max-height: 250px;">
                        <!-- Include the contacts list -->
                        @include('contacts.partials.contacts_result_list', ['persons' => $company->employees])
                    </div>
                </div>
                <!-- /.box-body -->
				<div class="box-footer" style="text-align: center;">
					@if($canEdit)
						<a href="{{ '/contacts/add-to-company/' . $company->id }}" class="btn btn-primary pull-right" target="_blank"><i class="fa fa-user-plus"></i> Add Contact Person</a>
					@endif
				</div>
            </div> 
			<!-- Company's contacts box -->
            <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-users"></i> Communications</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding no-margin">
                <div style="overflow-X:auto; margin-right: 10px; max-height: 250px;">
                   <table class="table table-striped" >
					<tr>
						<th>Company Name</th>
						<th>Contact person</th>
						<th>Communication Date</th>
						<th>Communication Time</th>
						<th>Communication Type</th>
						<th>Message</th>
						<th>Sent By</th>
					</tr>
					@if (count($contactsCommunications) > 0)
						@foreach($contactsCommunications as $contactsCommunication)
						   <tr>
								<td>{{ (!empty($contactsCommunication->companyname)) ?  $contactsCommunication->companyname : ''}} </td>
								<td>{{ !empty($contactsCommunication->first_name) && !empty($contactsCommunication->surname) ?  $contactsCommunication->first_name." ".$contactsCommunication->surname : '' }}</td>
								<td>{{ !empty($contactsCommunication->communication_date) ? date('d M Y ', $contactsCommunication->communication_date) : '' }}</td>
								<td>{{ !empty($contactsCommunication->time_sent) ? $contactsCommunication->time_sent : '' }}</td>
								<td>{{ (!empty($contactsCommunication->communication_type)) ?  $communicationStatus[$contactsCommunication->communication_type] : ''}} </td>
								<td>{{ (!empty($contactsCommunication->message)) ?  $contactsCommunication->message : ''}} </td> 
								<td>{{ (!empty($contactsCommunication->hr_firstname) && !empty($contactsCommunication->hr_surname)) ?  $contactsCommunication->hr_firstname." ".$contactsCommunication->hr_surname : ''}} </td> 
							</tr>
						@endforeach
					@endif
				</table>
                </div>
                <!-- /.box-body -->
            </div>
            </div>
			<!-- Company's contacts box -->
            <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-users"></i> Tasks</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding no-margin">
					<div style="overflow-X:auto; margin-right: 10px; max-height: 250px;">
					   <table class="table table-striped">
							<tr><th style="width: 10px"></th><th>Description</th><th>Person Responsible</th><th>Status</th><th>Notes</th><th>Document</th></tr>
								@if (!empty($tasks))
									@foreach($tasks as $task)
									<tr>
										<td>
											@if(!empty($task->administrator_id) && $task->administrator_id == $user->person->id && $task->status < 4 && $induction->status == 1)           
												<button type="button" id="end-task-button" class="btn btn-sm btn-default btn-flat pull-right" data-toggle="modal" data-target="#end-task-modal"
												data-task_id="{{ $task->task_id }}" data-employee_id="{{ $task->employee_id }}" 
												data-upload_required="{{ $task->upload_required }}" >End</button>
											@endif
										</td>
										<td>{{ (!empty($task->description)) ?  $task->description : ''}} </td>
										<td>{{ (!empty($task->hr_fist_name)) && (!empty($task->hr_surname)) ?  $task->hr_fist_name." ".$task->hr_surname : ''}} </td>
										<td>{{ (!empty($task->status)) ?  $taskStatus[$task->status] : ''}} </td>
										<td>{{ (!empty($task->status)) ?  $task->notes : ''}} </td>
										@if(!empty($task->document_on_task))
											<td><a class="btn btn-default btn-flat btn-block" href="{{ Storage::disk('local')->url("tasks/$task->document_on_task") }}" target="_blank"><i class="fa fa-file-pdf-o"></i> Click Here</a></td>
										@else
										<td><a class="btn btn-default btn-flat btn-block"><i class="fa fa-exclamation-triangle"></i>No Document Was Uploaded</a></td>
										@endif
									</tr>
									@endforeach
								@else
									<tr id="categories-list">
									<td colspan="6">
									<div class="alert alert-danger alert-dismissable">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
										No task to display, please start by adding a new task.
									</div>
									</td>
									</tr>
								@endif
						</table>
					</div>
					<!-- /.box-body -->
					<div class="box-footer">
					<button type="button" id="add-task" class="btn btn-success pull-right" data-toggle="modal"
							data-target="#add-task-modal" data-meeting_id="{{ $company->id }}">Add Task
					</button>
					</div>
				</div>
				@include('contacts.partials.add_task', ['modal_title' => 'Add Task'])
            </div>
			<!-- Company's contacts box -->
            <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-users"></i> Notes</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding no-margin">
					<div style="overflow-X:auto; margin-right: 10px; max-height: 250px;">
						<table class="table table-bordered">
							<tr>
								<th style="width: 10px"></th>
								<th>Originator</th>
								<th>Company Representative</th>
								<th>Our Representative</th>
								<th>Date</th>
								<th>Time</th>
								<th>Communication Method</th>
								<th>Follow-up Task</th>
								<th style="width: 100px">Notes</th>
								<th style="width: 40px"></th>
						    </tr>
							@if (count($contactnotes) > 0)
								@foreach($contactnotes as $notes)
									<tr id="notess-list">
										<td ><img src="{{ (!empty($notes->profile_pic)) ? Storage::disk('local')->url("avatars/$notes->profile_pic") : (($notes->gender === 0) ? $f_silhouette : $m_silhouette) }}" width="30" height="30" alt="" ></td>
										<td>{{ (!empty($notes->originator_type) && $notes->originator_type == 1) ? "From Us" : 'Client'}} </td>
										<td>{{ (!empty($notes->con_first_name) && !empty($notes->con_surname)) ? $notes->con_first_name." ".$notes->con_surname : ''}} </td>
										<td>{{ (!empty($notes->hr_first_name) && !empty($notes->hr_surname)) ? $notes->hr_first_name." ".$notes->hr_surname : ''}} </td>
										<td style="width: 100px">{{ !empty($notes->date) ? date('d M Y', $notes->date) : '' }}</td>
										<td>{{ !empty($notes->time) ? date('H:m:i', $notes->time) : '' }}</td>
										<td>{{ (!empty($notes->communication_method)) ? $communicationmethod[$notes->communication_method] : ''}} </td>
										<td>{{ (!empty($notes->next_action)) && $notes->next_action == 1 ?  "Yes" : 'No'}} </td>
										<td style="width: 100px">{{ (!empty($notes->notes)) ?  $notes->notes : ''}} </td>
										<td><button type="button" id="edit_compan" class="btn btn-warning  btn-xs" data-toggle="modal" 
											data-target="#edit-note-modal" data-id="{{ $notes->id }}" 
											data-originator_type="{{ $notes->originator_type }}" 
											data-date="{{date('d M Y', $notes->date)}}" 
											data-time="{{date('H:m:i', $notes->time)}}" 
											data-hr_person_id="{{$notes->hr_person_id}}" 
											data-employee_id="{{$notes->employee_id}}"
											data-notes="{{$notes->notes}}"
											data-next_action="{{$notes->next_action}}"
											data-communication_method="{{$notes->communication_method}}"
											data-rensponse_type="{{$notes->rensponse}}"
										><i class="fa fa-pencil-square-o"></i> Edit</button></td>
									</tr>
								@endforeach
							@else
								<tr id="categories-list">
									<td colspan="9">
										<div class="alert alert-danger alert-dismissable">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
											No Notes to display, Begin by Adding Notes.
										</div>
									</td>
								</tr>
							@endif
						</table>
					</div>
					<!-- /.box-body -->
					<div class="box-footer" style="text-align: center;">
                        @if($canEdit)
                            <button type="button" id="new_note" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-note-modal">Add Note</button>
                        @endif
                    </div>
					@include('contacts.partials.add_note_modal')
					@include('contacts.partials.edit_note_modal')
				</div>
            </div>
			<!-- Company's contacts box -->
            <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-users"></i> Induction</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding no-margin">
					<div style="overflow-X:auto; margin-right: 10px; max-height: 250px;">
						<table class="table table-striped" >
							<tr>
								<th>#</th>
								<th>Induction Name</th>
								<th>KAM </th>
								<th>Date Created</th>
								<th style="text-align: center;"><i class="fa fa-info-circle"></i> Status</th>
							</tr>
							@if (!empty($ClientInduction))
							   @foreach($ClientInduction as $induction)
									<tr>
										<td><a href="{{ '/induction/' . $induction->id . '/view' }}" class="product-title" target="_blank">View</a></td>
											
										<td>{{ (!empty($induction->induction_title)) ?  $induction->induction_title : ''}}</td>
										<td>{{ !empty($induction->firstname) && !empty($induction->surname) ? $induction->firstname.' '.$induction->surname : '' }}</td>
										<td>{{ !empty($induction->created_at) ? $induction->created_at : '' }}</td>
										<td>
											<div class="progress xs">
												<div class="progress-bar progress-bar-warning  progress-bar-striped" role="progressbar"
												aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:{{ $induction->completed_task == 0 ? 0 : ($induction->completed_task/$induction->total_task * 100)  }}%"></div></div>
												{{(round($induction->completed_task == 0 ? 0 : ($induction->completed_task/$induction->total_task * 100)))}}% 
										</td>
									</tr>
							   @endforeach
						   @endif
						</table>
					</div>
                <!-- /.box-body -->
				</div>
            </div>
            <!-- /.box -->
        </div>
        <!-- End Column -->

        <!-- Confirmation Modal -->
        @if(Session('success_add'))
            @include('contacts.partials.success_action', ['modal_title' => "New Company Added!", 'modal_content' => session('success_add')])
        @endif
    </div>
    @endsection

    @section('page_script')
	<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
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
    <!-- InputMask -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
    <script src="/custom_components/js/modal_ajax_submit.js"></script>

    <script type="text/javascript">

        $(function () {
            //Phone mask
            $("[data-mask]").inputmask();

            //slimScroll
            $('#company-contacts').slimScroll({
                height: '',
                railVisible: true,
                alwaysVisible: true
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
			$('#time').datetimepicker({
                    format: 'HH:mm:ss'
            });
			$('#due_time').datetimepicker({
				format: 'HH:mm:ss'
			});
			$('#time_update').datetimepicker({
				format: 'HH:mm:ss'
			});
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });
            //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
			// save notes
			var noteID;			
			$('#add_notes').on('click', function() {
				var strUrl = '/contacts/company/addnotes';
				var formName = 'add-note-form';
				var modalID = 'add-new-note-modal';
				var submitBtnID = 'add_notes';
				var redirectUrl = '/contacts/company/' + {{$company->id}} + '/view';
				var successMsgTitle = 'Note Saved!';
				var successMsg = 'Note Has Been Successfully Saved!';
				modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
			});
			////
            $('#edit-note-modal').on('shown.bs.modal', function (e) {
                //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                noteID = btnEdit.data('id');
                var originator_type = btnEdit.data('originator_type');
                var date = btnEdit.data('date');
                var time = btnEdit.data('time');
                var hr_person_id = btnEdit.data('hr_person_id');
                var employee_id = btnEdit.data('employee_id');
                var notes = btnEdit.data('notes');
                var next_action = btnEdit.data('next_action');
                var communication_method = btnEdit.data('communication_method');
                var rensponse_type = btnEdit.data('rensponse_type');
                var modal = $(this);
                modal.find('#originator_type_update').val(originator_type);
                modal.find('#hr_person_id_update').val(hr_person_id);
                modal.find('#employee_id_update').val(employee_id);
                modal.find('#notes_update').val(notes);
                modal.find('#next_action_update').val(next_action);
                modal.find('#communication_method_update').val(communication_method);
                modal.find('#date_update').val(date);
                modal.find('#time_update').val(time);
                modal.find('#rensponse_type_update').val(rensponse_type);
             });
			// update note
			$('#edit_note').on('click', function() {
				var strUrl = '/contacts/company/updatenotes/'+ noteID;
				var formName = 'edit-note-form';
				var modalID = 'edit-note-modal';
				var submitBtnID = 'edit_note';
				var redirectUrl = '/contacts/company/' + {{$company->id}} + '/view';
				var successMsgTitle = 'Record Updated!';
				var successMsg = 'Note have been updated successfully!';
				modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
			});
			// save  task
			$('#save-task').on('click', function() {
				$('#save-task').prop('disabled', true);
				var strUrl = '/crm/add_task/' + {{$company->id}};
				var formName = 'add-task-form';
				var modalID = 'add-task-modal';
				var submitBtnID = 'save-task';
				var redirectUrl = '/contacts/company/' + {{$company->id}} + '/view';
				var successMsgTitle = 'Task Saved!';
				var successMsg = 'Task Has Been Successfully Saved!';
				modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
			});
        });
    </script>
@endsection