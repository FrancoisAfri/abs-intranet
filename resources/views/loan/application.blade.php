@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12 ">
            <!-- Horizontal Form -->
			<div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Application Form</h3>
					<p>Enter Loan details:</p>
                </div>
				<!-- /.box-header 
				<li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li> -->
					<!-- form start <form class="form-horizontal" method="POST" action="/"  enctype="multipart/form-data">-->
				
				<form class="form-horizontal" method="POST" action="{{ (!empty($loan->id)) ? "/loan/$loan->id " : '/loan_save' }}"  enctype="multipart/form-data">
					<input type="hidden" name="file_index" id="file_index" value="1"/>
					<input type="hidden" name="total_files" id="total_files" value="1"/>
						{{ csrf_field() }}
						 @if (!empty($loan->id))
							 {{ method_field('PATCH') }}
						 @endif
				<div class="box-body">
				<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
				  <li class="active"><a href="#tab_1" data-toggle="tab">Step 1</a></li>
				  <li><a href="#tab_2" data-toggle="tab">Step 2</a></li>
				  <li><a href="#tab_3" data-toggle="tab">Step 3</a></li>
				  <li><a href="#tab_4" data-toggle="tab">Step 4</a></li>
				  <li><a href="#tab_5" data-toggle="tab">Step 5</a></li>
				  <li><a href="#tab_6" data-toggle="tab">Step 6</a></li>
				  <li><a href="#tab_7" data-toggle="tab">Step 7</a></li>
				  <li><a href="#tab_8" data-toggle="tab">Step 8</a></li>
				  <li><a href="#tab_9" data-toggle="tab">Step 9</a></li>
				  <li><a href="#tab_10" data-toggle="tab">Step 10</a></li>
				  <li><a href="#tab_11" data-toggle="tab">Step 11</a></li>
				</ul>
				<div class="tab-content">
				  <div class="tab-pane active" id="tab_1">
					<div class="form-group">
					  <label for="applicant_type" class="col-sm-3 control-label">Applicant Type</label>
					  <div class="col-sm-9">
					  <select class="form-control" name="applicant_type" id="applicant_type" placeholder="Select Applicant Type" onchange="changeApplicant(this.value)" required>
						<option value="1" {{ (!empty($loan->applicant_type) && $loan->applicant_type== 1) ? ' selected' : '' }}>Company</option>
						<option value="2"{{ (!empty($loan->applicant_type) && $loan->applicant_type == 2) ? ' selected' : '' }}>Close corporation</option>
						<option value="3"{{ (!empty($loan->applicant_type) &&  $loan->applicant_type== 3) ? ' selected' : '' }}>Sole proprietor</option>
						<option value="4"{{ (!empty($loan->applicant_type) &&  $loan->applicant_type== 4) ? ' selected' : '' }}>Individual</option>
						<option value="5"{{ (!empty($loan->applicant_type) &&  $loan->applicant_type== 5) ? ' selected' : '' }}>Surety</option>
					  </select>
					  </div>
					</div>
					<div class="form-group">
						<label for="amount_wanted" class="col-sm-3 control-label">Amount Wanted</label>
						  <div class="col-sm-9">
                            <input type="text" class="form-control" id="amount_wanted" name="amount_wanted" value="{{ !empty($loan->amount_wanted) ? $loan->amount_wanted : '' }}" placeholder="Please enter the amount you wish to loan" required>
					</div>
                 </div>
				 <div class="form-group">
					  <label for="loan_period" class="col-sm-3 control-label">Loan Repayment period</label>
					  <div class="col-sm-9">
					  <select class="form-control" name="loan_period" id="loan_period" placeholder="Loan Repayment period" onchange="" required>
						<option value="1"{{ (!empty($loan->loan_period) &&  $loan->loan_period== 1) ? ' selected' : '' }}>1 Year</option>
						<option value="2"{{ (!empty($loan->loan_period) &&  $loan->loan_period== 2) ? ' selected' : '' }}>2 Years</option>
						<option value="3"{{ (!empty($loan->loan_period) &&  $loan->loan_period== 3) ? ' selected' : '' }}>3 Years</option>
						<option value="4"{{ (!empty($loan->loan_period) &&  $loan->loan_period== 4) ? ' selected' : '' }}>4 Years</option>
						<option value="5"{{ (!empty($loan->loan_period) &&  $loan->loan_period== 5) ? ' selected' : '' }}>5 Years</option>
						<option value="6"{{ (!empty($loan->loan_period) &&  $loan->loan_period== 6) ? ' selected' : '' }}>6 Years</option>
						<option value="7"{{ (!empty($loan->loan_period) &&  $loan->loan_period== 7) ? ' selected' : '' }}>7 Years</option>
						<option value="8"{{ (!empty($loan->loan_period) &&  $loan->loan_period== 8) ? ' selected' : '' }}>8 Years</option>
						<option value="9"{{ (!empty($loan->loan_period) &&  $loan->loan_period== 9) ? ' selected' : '' }}>9 Years</option>
						<option value="10"{{ (!empty($loan->loan_period) &&  $loan->loan_period== 10) ? ' selected' : '' }}>10 Years</option>
					  </select>
					  </div>
					</div>
					<div class="form-group"><p class="lead"><b>Company Information</b></p></div>
					<div class="form-group">
						<label for="company_name" class="col-sm-3 control-label">Applicant</label>
						  <div class="col-sm-9">
                            <input type="text" class="form-control" id="company_name" name="company_name" value="{{ !empty($loan->loanCompany->company_name) ? $loan->loanCompany->company_name : '' }}" placeholder="Aplicant">
					</div>
                 </div>
				<div class="form-group">
					<label for="trading_as" class="col-sm-3 control-label">Trading as</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="trading_as" name="trading_as" value="{{ !empty($loan->loanCompany->trading_as) ? $loan->loanCompany->trading_as : '' }}" placeholder="Trading as">
					</div>
				</div>
                        <div class="form-group">
                            <label for="vat_number" class="col-sm-3 control-label">VAT Number</label>

                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="vat_number" name="vat_number" value="{{ !empty($loan->loanCompany->vat_number) ? $loan->loanCompany->vat_number : '' }}" placeholder="VAT Number">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="income_tax_number" class="col-sm-3 control-label">Income Tax Number</label>

                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="income_tax_number" name="income_tax_number" value="{{ !empty($loan->loanCompany->income_tax_number) ? $loan->loanCompany->income_tax_number : '' }}" placeholder="Income Tax Number">
                            </div>
                        </div>
						<div class="form-group">
                            <label for="contact_person" class="col-sm-3 control-label">Contact person</label>

                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="contact_person" name="contact_person" value="{{ !empty($loan->loanCompany->contact_person) ? $loan->loanCompany->contact_person : '' }}" placeholder="Contact person">
                            </div>
                        </div>
						 <div class="form-group">
                            <label for="cell_number" class="col-sm-3 control-label">Cell Number</label>

                            <div class="col-sm-9">
                                <input type="tel" class="form-control" id="cell_number" name="cell_number" value="{{ !empty($loan->loanCompany->cell_number) ? $loan->loanCompany->cell_number : '' }}" placeholder="Cell Number">
                            </div>
                        </div>
						 <div class="form-group">
                            <label for="work_number" class="col-sm-3 control-label">Work Number</label>

                            <div class="col-sm-9">
                                <input type="tel" class="form-control" id="work_number" name="work_number" value="{{ !empty($loan->loanCompany->work_number) ? $loan->loanCompany->work_number : '' }}" placeholder="Work Number">
                            </div>
                        </div>
						 <div class="form-group">
                            <label for="home_number" class="col-sm-3 control-label">Home Number</label>

                            <div class="col-sm-9">
                                <input type="tel" class="form-control" id="home_number" name="home_number" value="{{ !empty($loan->loanCompany->home_number) ? $loan->loanCompany->home_number : '' }}" placeholder="Home Number">
                            </div>
                        </div>
						 <div class="form-group">
                            <label for="fax_number" class="col-sm-3 control-label">Fax Number</label>

                            <div class="col-sm-9">
                                <input type="tel" class="form-control" id="fax_number" name="fax_number" value="{{ !empty($loan->loanCompany->fax_number) ? $loan->loanCompany->fax_number : '' }}" placeholder="Fax Number">
                            </div>
                        </div>
						<div class="form-group">
                            <label for="email" class="col-sm-3 control-label">Email</label>

                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="email" name="email" value="{{ !empty($loan->loanCompany->email) ? $loan->loanCompany->email : '' }}" placeholder="Email">
                            </div>
                        </div>
						<div class="form-group">
							<label for="physical_address" class="col-sm-3 control-label">Physical Address</label>
							<div class="col-sm-9">
							<textarea class="form-control" rows="3" id="physical_address" name="physical_address" value="{{ !empty($loan->loanCompany->physical_address) ? $loan->loanCompany->physical_address : '' }}" placeholder="Enter Physical Address"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="postal_address" class="col-sm-3 control-label">Postal Address</label>
							<div class="col-sm-9">
							<textarea class="form-control" rows="3" id="postal_address" name="postal_address" value="{{ !empty($loan->loanCompany->postal_address) ? $loan->loanCompany->postal_address : '' }}" placeholder="Enter Postal Address"></textarea>
							</div>
						</div>
						<div class="form-group"><p class="lead"><b>Accountant / auditor / accounting officer</b></p></div>
					<div class="form-group">
                    <label for="accountant_name" class="col-sm-3 control-label">Name</label>
                      <div class="col-sm-9">
                            <input type="text" class="form-control" id="accountant_name" name="accountant_name" value="{{ !empty($loan->loanCompany->accountant_name) ? $loan->loanCompany->accountant_name : '' }}" placeholder="Accountant Name">
					</div>
                 </div>
				<div class="form-group">
					<label for="account_Address" class="col-sm-3 control-label">Address</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="account_Address" name="account_Address" value="{{ !empty($loan->loanCompany->account_Address) ? $loan->loanCompany->account_Address : '' }}" placeholder="Accountant Address">
					</div>
				</div>
				<div class="form-group">
					<label for="accountant_telephone" class="col-sm-3 control-label">Telephone</label>

					<div class="col-sm-9">
						<input type="tel" class="form-control" id="accountant_telephone" name="accountant_telephone" value="{{ !empty($loan->loanCompany->accountant_telephone) ? $loan->loanCompany->accountant_telephone : '' }}" placeholder="Accountant Telephone">
					</div>
				</div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
			  <div class="form-group"><p class="lead"><b>Details of individual, surety, owner, partners, directors or members of CC</b></p></div>
                <div class="form-group">
					<label for="ind_name" class="col-sm-3 control-label">Name</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="ind_name" name="ind_name" value="{{ !empty($loan->loanContacts->ind_name) ? $loan->loanContacts->ind_name : '' }}" placeholder="Name">
					</div>
				</div>
				<div class="form-group">
					<label for="ind_id_no" class="col-sm-3 control-label">ID No</label>

					<div class="col-sm-9">
						<input type="number" class="form-control" id="ind_id_no" name="ind_id_no" value="{{ !empty($loan->loanContacts->ind_id_no) ? $loan->loanContacts->ind_id_no : '' }}" placeholder="ID No">
					</div>
				</div>
				<div class="form-group">
					<label for="marital_status" class="col-sm-3 control-label">Marital Status</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="marital_status" name="marital_status" value="{{ !empty($loan->loanContacts->marital_status) ? $loan->loanContacts->marital_status : '' }}" placeholder="Marital Status">
					</div>
				</div>
				 <div class="form-group">
					<label for="how_married" class="col-sm-3 control-label">How married</label>
					<div class="col-sm-9">
					  <select class="form-control" name="how_married" id="how_married" placeholder="Select How Married">
						<option value="1"{{ (!empty($loan->loanContacts->how_married) &&  $loan->loanContacts->how_married == 1) ? ' selected' : '' }}>ANC</option>
						<option value="2"{{ (!empty($loan->loanContacts->how_married) &&  $loan->loanContacts->how_married == 2) ? ' selected' : '' }}>COP</option>
					  </select>
					</div>
				</div>
				<div class="form-group">
					<label for="residential_address" class="col-sm-3 control-label">Residential Address</label>
					<div class="col-sm-9">
					<textarea class="form-control" rows="3" id="residential_address" name="residential_address" value="{{ !empty($loan->loanContacts->residential_address) ? $loan->loanContacts->residential_address : '' }}" placeholder="Enter Residential Address"></textarea>
					</div>
				</div>
				 <div class="form-group">
					<label for="spouse_name" class="col-sm-3 control-label">Spouse Name</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="spouse_name" name="spouse_name" value="{{ !empty($loan->loanContacts->spouse_name) ? $loan->loanContacts->spouse_name : '' }}" placeholder="Spouse Name">
					</div>
				</div>
				 <div class="form-group">
					<label for="spouse_id" class="col-sm-3 control-label">Spouse ID NO</label>

					<div class="col-sm-9">
						<input type="number" class="form-control" id="spouse_id" name="spouse_id" value="{{ !empty($loan->loanContacts->spouse_id) ? $loan->loanContacts->spouse_id : '' }}" placeholder="Spouse ID NO">
					</div>
				</div>
				 <div class="form-group">
					<label for="number_dep" class="col-sm-3 control-label">Number of Dependents</label>

					<div class="col-sm-9">
						<input type="number" class="form-control" id="number_dep" name="number_dep" value="{{ !empty($loan->loanContacts->number_dep) ? $loan->loanContacts->number_dep : '' }}" placeholder="Number of Dependents">
					</div>
				</div>
			<div class="form-group">
					<p class="lead"><b>Second</b></p>
			</div>
				<div class="form-group">
					<label for="ind_name2" class="col-sm-3 control-label">Name</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="ind_name2" name="ind_name2" value="{{ !empty($loan->loanContacts->ind_name2) ? $loan->loanContacts->ind_name2 : '' }}" placeholder="Name">
					</div>
				</div>
				<div class="form-group">
					<label for="ind_id_no2" class="col-sm-3 control-label">ID No</label>

					<div class="col-sm-9">
						<input type="number" class="form-control" id="ind_id_no2" name="ind_id_no2" value="{{ !empty($loan->loanContacts->ind_id_no2) ? $loan->loanContacts->ind_id_no2 : '' }}" placeholder="ID No">
					</div>
				</div>
				<div class="form-group">
					<label for="marital_status2" class="col-sm-3 control-label">Marital Status</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="marital_status2" name="marital_status2" value="{{ !empty($loan->loanContacts->marital_status2) ? $loan->loanContacts->marital_status2 : '' }}" placeholder="Marital Status">
					</div>
				</div>
				 <div class="form-group">
					<label for="how_married2" class="col-sm-3 control-label">How married</label>
					<div class="col-sm-9">
					  <select class="form-control" name="how_married2" id="how_married2" placeholder="Select How Married">
						<option value="1"{{ (!empty($loan->loanContacts->how_married2) &&  $loan->loanContacts->how_married2 == 2) ? ' selected' : '' }}>ANC</option>
						<option value="2"{{ (!empty($loan->loanContacts->how_married2) &&  $loan->loanContacts->how_married2 == 2) ? ' selected' : '' }}>COP</option>
					  </select>
					</div>
				</div>
				<div class="form-group">
					<label for="residential_address2" class="col-sm-3 control-label">Residential Address</label>
					<div class="col-sm-9">
					<textarea class="form-control" rows="3" id="residential_address2" name="residential_address2" value="{{ !empty($loan->loanContacts->residential_address2) ? $loan->loanContacts->residential_address2 : '' }}" placeholder="Enter Residential Address"></textarea>
					</div>
				</div>
				 <div class="form-group">
					<label for="spouse_name2" class="col-sm-3 control-label">Spouse Name</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="spouse_name2" name="spouse_name2" value="{{ !empty($loan->loanContacts->spouse_name2) ? $loan->loanContacts->spouse_name2 : '' }}" placeholder="Spouse Name">
					</div>
				</div>
				 <div class="form-group">
					<label for="spouse_id2" class="col-sm-3 control-label">Spouse ID NO</label>

					<div class="col-sm-9">
						<input type="number" class="form-control" id="spouse_id2" name="spouse_id2" value="{{ !empty($loan->loanContacts->spouse_id2) ? $loan->loanContacts->spouse_id2 : '' }}" placeholder="Spouse ID NO">
					</div>
				</div>
				 <div class="form-group">
					<label for="number_dep2" class="col-sm-3 control-label">Number of Dependents</label>

					<div class="col-sm-9">
						<input type="number" class="form-control" id="number_dep2" name="number_dep2" value="{{ !empty($loan->loanContacts->number_dep2) ? $loan->loanCompany->number_dep2 : '' }}" placeholder="Number of Dependents">
					</div>
				</div>
			<div class="form-group">
					<p class="lead"><b>Third</b></p>
				</div>
				<div class="form-group">
					<label for="ind_name3" class="col-sm-3 control-label">Name</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="ind_name3" name="ind_name3" value="{{ !empty($loan->loanContacts->ind_name3) ? $loan->loanContacts->ind_name3 : '' }}" placeholder="Name">
					</div>
				</div>
				<div class="form-group">
					<label for="ind_id_no3" class="col-sm-3 control-label">ID No</label>

					<div class="col-sm-9">
						<input type="number" class="form-control" id="ind_id_no3" name="ind_id_no3" value="{{ !empty($loan->loanContacts->ind_id_no3) ? $loan->loanContacts->ind_id_no3 : '' }}" placeholder="ID No">
					</div>
				</div>
				<div class="form-group">
					<label for="marital_status3" class="col-sm-3 control-label">Marital Status</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="marital_status3" name="marital_status3" value="{{ !empty($loan->loanContacts->marital_status3) ? $loan->loanContacts->marital_status3 : '' }}" placeholder="Marital Status">
					</div>
				</div>
				 <div class="form-group">
					<label for="how_married3" class="col-sm-3 control-label">How married</label>
					<div class="col-sm-9">
					  <select class="form-control" name="how_married3" id="how_married3" placeholder="Select How Married">
						<option value="1"{{ (!empty($loan->loanContacts->how_married3) &&  $loan->loanContacts->how_married3 == 2) ? ' selected' : '' }}>ANC</option>
						<option value="2"{{ (!empty($loan->loanContacts->how_married3) &&  $loan->loanContacts->how_married3 == 2) ? ' selected' : '' }}>COP</option>
					  </select>
					</div>
				</div>
				<div class="form-group">
					<label for="residential_address3" class="col-sm-3 control-label">Residential Address</label>
					<div class="col-sm-9">
					<textarea class="form-control" rows="3" id="residential_address3" name="residential_address3" value="{{ !empty($loan->loanContacts->residential_address3) ? $loan->loanContacts->residential_address3 : '' }}" placeholder="Enter Residential Address" ></textarea>
					</div>
				</div>
				 <div class="form-group">
					<label for="spouse_name3" class="col-sm-3 control-label">Spouse Name</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="spouse_name3" name="spouse_name3" value="{{ !empty($loan->loanContacts->spouse_name3) ? $loan->loanContacts->spouse_name3 : '' }}" placeholder="Spouse Name">
					</div>
				</div>
				 <div class="form-group">
					<label for="spouse_id3" class="col-sm-3 control-label">Spouse ID NO</label>

					<div class="col-sm-9">
						<input type="number" class="form-control" id="spouse_id3" name="spouse_id3" value="{{ !empty($loan->loanContacts->spouse_id3) ? $loan->loanContacts->spouse_id3 : '' }}" placeholder="Spouse ID NO">
					</div>
				</div>
				 <div class="form-group">
					<label for="number_dep3" class="col-sm-3 control-label">Number of Dependents</label>

					<div class="col-sm-9">
						<input type="number" class="form-control" id="number_dep3" name="number_dep3" value="{{ !empty($loan->loanContacts->number_dep3) ? $loan->loanContacts->number_dep3 : '' }}" placeholder="Number of Dependents">
					</div>
				</div>
              </div>         
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3">
			  <div class="form-group"><p class="lead"><b>Employment history (Individual application / Surety)</b></p></div>
			  <div class="form-group">
					<label for="employer" class="col-sm-3 control-label">Employer</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="employer" name="employer" value="{{ !empty($loan->loanHistory->employer) ? $loan->loanHistory->employer : '' }}" placeholder="Employer">
					</div>
				</div>
				<div class="form-group">
					<label for="emp_address" class="col-sm-3 control-label">Address</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="emp_address" name="emp_address" value="{{ !empty($loan->loanHistory->emp_address) ? $loan->loanHistory->emp_address : '' }}" placeholder="Address">
					</div>
				</div>
				<div class="form-group">
					<label for="emp_tel" class="col-sm-3 control-label">Telephone</label>
					<div class="col-sm-9">
						<input type="tel" class="form-control" id="emp_tel" name="emp_tel" value="{{ !empty($loan->loanHistory->emp_tel) ? $loan->loanHistory->emp_tel : '' }}" placeholder="Telephone">
					</div>
				</div>
				<div class="form-group">
					<label for="emp_years" class="col-sm-3 control-label">No. of years</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="emp_years" name="emp_years" value="{{ !empty($loan->loanHistory->emp_years) ? $loan->loanHistory->emp_years : '' }}" placeholder="No. of years">
					</div>
				</div>
				 <div class="form-group">
					<label for="spouse_emp" class="col-sm-3 control-label">Spouses employer</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="spouse_emp" name="spouse_emp" value="{{ !empty($loan->loanHistory->spouse_emp) ? $loan->loanHistory->spouse_emp : '' }}" placeholder="Spouses employer">
					</div>
				</div>
				 <div class="form-group">
					<label for="spouse_emp_addr" class="col-sm-3 control-label">Address</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="spouse_emp_addr" name="spouse_emp_addr" value="{{ !empty($loan->loanHistory->spouse_emp_addr) ? $loan->loanHistory->spouse_emp_addr : '' }}" placeholder="Address">
					</div>
				</div>
				<div class="form-group">
					<label for="spouse_emp_tel" class="col-sm-3 control-label">Telephone</label>
					<div class="col-sm-9">
						<input type="tel" class="form-control" id="spouse_emp_tel" name="spouse_emp_tel" value="{{ !empty($loan->loanHistory->spouse_emp_tel) ? $loan->loanHistory->spouse_emp_tel : '' }}" placeholder="Telephone">
					</div>
				</div>
				 <div class="form-group">
					<label for="spouse_emp_years" class="col-sm-3 control-label">No. of years</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="spouse_emp_years" name="spouse_emp_years" value="{{ !empty($loan->loanHistory->spouse_emp_years) ? $loan->loanHistory->spouse_emp_years : '' }}" placeholder="No. of years">
					</div>
				</div>
				<div class="form-group"><p class="lead"><b>Nearest relative in South Africa not living with you</b></p></div>
				<div class="form-group">
					<label for="rel_Full" class="col-sm-3 control-label">Full Name</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="rel_Full" name="rel_Full" value="{{ !empty($loan->loanHistory->rel_Full) ? $loan->loanHistory->rel_Full : '' }}" placeholder="Full Name">
					</div>
				</div>
				 <div class="form-group">
					<label for="rel_relation" class="col-sm-3 control-label">Relationship</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="rel_relation" name="rel_relation" value="{{ !empty($loan->loanHistory->rel_relation) ? $loan->loanHistory->rel_relation : '' }}" placeholder="Relationship">
					</div>
				</div>
				<div class="form-group">
					<label for="rel_tel" class="col-sm-3 control-label">Telephone</label>
					<div class="col-sm-9">
						<input type="tel" class="form-control" id="rel_tel" name="rel_tel" value="{{ !empty($loan->loanHistory->rel_tel) ? $loan->loanHistory->rel_tel : '' }}" placeholder="Telephone">
					</div>
				</div>
				 <div class="form-group">
					<label for="rel_address" class="col-sm-3 control-label">Address</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="rel_address" name="rel_address" value="{{ !empty($loan->loanHistory->rel_address) ? $loan->loanHistory->rel_address : '' }}" placeholder="Address">
					</div>
				</div>
				<div class="form-group"><p class="lead"><b>Landlord’s details</b> (Name & address of landlord where goods will be kept) if applicable</p></div>
				
				<div class="form-group">
					<label for="landlord_name" class="col-sm-3 control-label">Name</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="landlord_name" name="landlord_name" value="{{ !empty($loan->loanHistory->landlord_name) ? $loan->loanHistory->landlord_name : '' }}" placeholder="Name">
					</div>
				</div>
				<div class="form-group">
					<label for="landlord_tel" class="col-sm-3 control-label">Telephone</label>
					<div class="col-sm-9">
						<input type="tel" class="form-control" id="landlord_tel" name="landlord_tel" value="{{ !empty($loan->loanHistory->landlord_tel) ? $loan->loanHistory->landlord_tel : '' }}" placeholder="Telephone">
					</div>
				</div>
				 <div class="form-group">
					<label for="landlord_address" class="col-sm-3 control-label">Address</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="landlord_address" name="landlord_address" value="{{ !empty($loan->loanHistory->landlord_address) ? $loan->loanHistory->landlord_address : '' }}" placeholder="Address">
					</div>
				</div>
              </div>
			  <div class="tab-pane" id="tab_4">
               <div class="form-group"><p class="lead"><b>Home ownership</b></p></div>
				 <div class="form-group">
                  <label for="bond_type" class="col-sm-3 control-label">Do you own your property</label>
				  <div class="col-sm-9">
                  <select class="form-control" name="bond_type" id="bond_type" placeholder="Select Bond Type">
                    <option value="1"{{ (!empty($loan->bond_type) &&  $loan->bond_type == 1) ? ' selected' : '' }}>Bond free</option>
                    <option value="2"{{ (!empty($loan->bond_type) &&  $loan->bond_type == 2) ? ' selected' : '' }}>Bonded</option>
                    <option value="3"{{ (!empty($loan->bond_type) &&  $loan->bond_type == 3) ? ' selected' : '' }}>In your name</option>
                    <option value="4"{{ (!empty($loan->bond_type) &&  $loan->bond_type == 4) ? ' selected' : '' }}>In your spouse’s name</option>
                    <option value="5"{{ (!empty($loan->bond_type) &&  $loan->bond_type == 5) ? ' selected' : '' }}>Both</option>
                    <option value="6"{{ (!empty($loan->bond_type) &&  $loan->bond_type == 6) ? ' selected' : '' }}>Other</option>
                  </select>
				  </div>
                </div>
				<div class="form-group">
                  <label for="property_type" class="col-sm-3 control-label">Property Type</label>
				  <div class="col-sm-9">
                  <select class="form-control" name="property_type" id="property_type" placeholder="Select Property type">
                    <option value="1"{{ (!empty($loan->property_type) &&  $loan->property_type == 1) ? ' selected' : '' }}>House</option>
                    <option value="2"{{ (!empty($loan->property_type) &&  $loan->property_type == 2) ? ' selected' : '' }}>Town house</option>
                    <option value="3"{{ (!empty($loan->property_type) &&  $loan->property_type == 3) ? ' selected' : '' }}>Flat</option>
                  </select>
				  </div>
                </div>
				 <div class="form-group">
					<label for="property_address" class="col-sm-3 control-label">Property Address</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="property_address" name="property_address" value="{{ !empty($loan->property_address) ? $loan->property_address : '' }}" placeholder="Property Address">
					</div>
				</div>
				<div class="form-group">
					<label for="bond_month" class="col-sm-3 control-label">Bond / rental per month</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="bond_month" name="bond_month" value="{{ !empty($loan->bond_month) ? $loan->bond_month : '' }}" placeholder="Bond / rental per month R">
					</div>
				</div>
				<div class="form-group">
					<label for="bond_outs" class="col-sm-3 control-label">Bond amount outstanding</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="bond_outs" name="bond_outs" value="{{ !empty($loan->bond_outs) ? $loan->bond_outs : '' }}" placeholder="Bond amount outstanding R">
					</div>
				</div>
				<div class="form-group">
					<label for="current_value" class="col-sm-3 control-label">Current value</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="current_value" name="current_value" value="{{ !empty($loan->current_value) ? $loan->current_value : '' }}" placeholder="Current value R">
					</div>
				</div>
				<div class="form-group">
					<label for="purchase_price" class="col-sm-3 control-label">Purchase price</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="purchase_price" name="purchase_price" value="{{ !empty($loan->purchase_price) ? $loan->purchase_price : '' }}" placeholder="Purchase price R">
					</div>
				</div>
				<div class="form-group">
					<label for="bond_granted" class="col-sm-3 control-label">If access bond, total facility granted</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="bond_granted" name="bond_granted" value="{{ !empty($loan->bond_granted) ? $loan->bond_granted : '' }}" placeholder="If access bond, total facility granted R">
					</div>
				</div>
				<div class="form-group">
                  <label for="legal_atcion" class="col-sm-3 control-label">Are there any legal proceedings pending or expected against the firm,
					individual, surety, owner, partners, directors or members</label>
				  <div class="col-sm-9">
                  <select class="form-control" name="legal_atcion" id="legal_atcion" placeholder="Select Yes/No">
                    <option value="1"{{ (!empty($loan->legal_atcion) &&  $loan->legal_atcion == 1) ? ' selected' : '' }}>Yes</option>
                    <option value="2" {{ (!empty($loan->legal_atcion) &&  $loan->legal_atcion == 2) ? ' selected' : '' }}>No</option>
                  </select>
				  </div>
                </div>
				<div class="form-group">
					<label for="legal_details" class="col-sm-3 control-label">If yes provide details</label>
					<div class="col-sm-9">
						<textarea class="form-control" rows="3" id="legal_details" name="legal_details" value="{{ !empty($loan->legal_details) ? $loan->legal_details : '' }}" placeholder="Enter Details Here"></textarea>
					</div>
				</div>
				<div class="form-group">
                  <label for="judgement_atcion" class="col-sm-3 control-label">Are there any judgements against the firm, individual, surety, owner,
					partners, directors or members</label>
				  <div class="col-sm-9">
                  <select class="form-control" name="judgement_atcion" id="judgement_atcion" placeholder="Select Yes/No">
                      <option value="1"{{ (!empty($loan->judgement_atcion) &&  $loan->judgement_atcion == 1) ? ' selected' : '' }}>Yes</option>
                    <option value="2" {{ (!empty($loan->judgement_atcion) &&  $loan->judgement_atcion == 2) ? ' selected' : '' }}>No</option>
                  </select>
				  </div>
                </div>
				<div class="form-group">
					<label for="judgement_details" class="col-sm-3 control-label">If yes provide details</label>
					<div class="col-sm-9">
						<textarea class="form-control" rows="3" id="judgement_details" name="judgement_details" value="{{ !empty($loan->judgement_details) ? $loan->judgement_details : '' }}" placeholder="Enter Details Here"></textarea>
					</div>
				</div>
				<div class="form-group">
                  <label for="liquidation_atcion" class="col-sm-3 control-label">Has the firm, individual, surety owner, partners, directors or members, ever
					made a compromise with its creditors or been provisionally or finally
					liquidated or placed into judicial management.</label>
				  <div class="col-sm-9">
                  <select class="form-control" name="liquidation_atcion" id="liquidation_atcion" placeholder="Select Yes/No">
                     <option value="1"{{ (!empty($loan->liquidation_atcion) &&  $loan->liquidation_atcion == 1) ? ' selected' : '' }}>Yes</option>
                    <option value="2" {{ (!empty($loan->liquidation_atcion) &&  $loan->liquidation_atcion == 2) ? ' selected' : '' }}>No</option>
                  </select>
				  </div>
                </div>
				<div class="form-group">
					<label for="liquidation_details" class="col-sm-3 control-label">If yes provide details</label>
					<div class="col-sm-9">
						<textarea class="form-control" rows="3" id="liquidation_details" name="liquidation_details" value="{{ !empty($loan->liquidation_details) ? $loan->liquidation_details : '' }}" placeholder="Enter Details Here"></textarea>
					</div>
				</div>
				<div class="form-group">
                  <label for="debt_review" class="col-sm-3 control-label">Is the firm, individual, surety, owner, partners, directors or members,
					currently under or have applied for debt review, or have a dispute in progress
					with a credit bureau, or have any re-arrangement in place with a credit
					provider as a result of debt counselling.</label>
				  <div class="col-sm-9">
                  <select class="form-control" name="debt_review" id="debt_review" placeholder="Select Yes/No">
                    <option value="1"{{ (!empty($loan->debt_review) &&  $loan->debt_review == 1) ? ' selected' : '' }}>Yes</option>
                    <option value="2" {{ (!empty($loan->debt_review) &&  $loan->debt_review == 2) ? ' selected' : '' }}>No</option>
                  </select>
				  </div>
                </div>
				<div class="form-group">
					<label for="debt_review_details" class="col-sm-3 control-label">If yes provide details</label>
					<div class="col-sm-9">
						<textarea class="form-control" rows="3" id="debt_review_details" name="debt_review_details" value="{{ !empty($loan->debt_review_details) ? $loan->debt_review_details : '' }}"  placeholder="Enter Details Here"></textarea>
					</div>
				</div>
              </div>  
			  <div class="tab-pane" id="tab_5">
			   <div class="form-group"><p class="lead"><b>Existing and / or previous accounts with financial institutions</b></p></div>
               <div class="form-group">
					<label for="exis_inst" class="col-sm-3 control-label">Institution</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="exis_inst" name="exis_inst" value="{{ !empty($loan->exis_inst) ? $loan->exis_inst : '' }}"  placeholder="Institution">
					</div>
				</div>
				<div class="form-group">
					<label for="exis_branch" class="col-sm-3 control-label">Branch</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="exis_branch" name="exis_branch" value="{{ !empty($loan->exis_branch) ? $loan->exis_branch : '' }}"  placeholder="Branch">
					</div>
				</div>
				<div class="form-group">
					<label for="exis_acc" class="col-sm-3 control-label">Account No.</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="exis_acc" name="exis_acc" value="{{ !empty($loan->exis_acc) ? $loan->exis_acc : '' }}"  placeholder="Account No.">
					</div>
				</div>
				<div class="form-group">
					<label for="exis_amount" class="col-sm-3 control-label">Amount</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="exis_amount" name="exis_amount" value="{{ !empty($loan->exis_amount) ? $loan->exis_amount : '' }}"  placeholder="Amount">
					</div>
				</div>
				 <div class="form-group">
					<label for="exis_curr" class="col-sm-3 control-label">Current / paid up</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="exis_curr" name="exis_curr" value="{{ !empty($loan->exis_curr) ? $loan->exis_curr : '' }}"  placeholder="Current / paid up">
					</div>
				</div> 
				<div class="form-group">
					<label for="exis_inst1" class="col-sm-3 control-label">Institution</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="exis_inst1" name="exis_inst1" value="{{ !empty($loan->exis_inst1) ? $loan->exis_inst1 : '' }}"  placeholder="Institution">
					</div>
				</div>
				<div class="form-group">
					<label for="exis_branch1" class="col-sm-3 control-label">Branch</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="exis_branch1" name="exis_branch1" value="{{ !empty($loan->exis_branch1) ? $loan->exis_branch1 : '' }}"  placeholder="Branch">
					</div>
				</div>
				<div class="form-group">
					<label for="exis_acc1" class="col-sm-3 control-label">Account No.</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="exis_acc1" name="exis_acc1" value="{{ !empty($loan->exis_acc1) ? $loan->exis_acc1 : '' }}"  placeholder="Account No.">
					</div>
				</div>
				<div class="form-group">
					<label for="exis_amount1" class="col-sm-3 control-label">Amount</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="exis_amount1" name="exis_amount1" value="{{ !empty($loan->exis_amount1) ? $loan->exis_amount1 : '' }}"  placeholder="Amount">
					</div>
				</div>
				 <div class="form-group">
					<label for="exis_curr1" class="col-sm-3 control-label">Current / paid up</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="exis_curr1" name="exis_curr1" value="{{ !empty($loan->exis_curr1) ? $loan->exis_curr1 : '' }}"  placeholder="Current / paid up">
					</div>
				</div>
				<!-- Bankers -->
				 <div class="form-group"><p class="lead"><b>Bankers</b></p></div>
				 <div class="form-group">
					<label for="bank_inst" class="col-sm-3 control-label">Bank</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="bank_inst" name="bank_inst" value="{{ !empty($loan->bank_inst) ? $loan->bank_inst : '' }}"  placeholder="Bank">
					</div>
				</div>
				<div class="form-group">
					<label for="bank_branch" class="col-sm-3 control-label">Branch</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="bank_branch" name="bank_branch" value="{{ !empty($loan->bank_branch) ? $loan->bank_branch : '' }}"  placeholder="Branch">
					</div>
				</div>
				<div class="form-group">
					<label for="bank_acc_type" class="col-sm-3 control-label">Account type</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="bank_acc_type" name="bank_acc_type" value="{{ !empty($loan->bank_acc_type) ? $loan->bank_acc_type : '' }}"  placeholder="Account type">
					</div>
				</div>
				<div class="form-group">
					<label for="bank_acc" class="col-sm-3 control-label">Account No.</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="bank_acc" name="bank_acc" value="{{ !empty($loan->bank_acc) ? $loan->bank_acc : '' }}"  placeholder="Account No.">
					</div>
				</div>
				 <div class="form-group">
					<label for="bank_acc_name" class="col-sm-3 control-label">Account name</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="bank_acc_name" name="bank_acc_name" value="{{ !empty($loan->bank_acc_name) ? $loan->bank_acc_name : '' }}"  placeholder="Account name">
					</div>
				</div> 
				<div class="form-group">
					<label for="bank_inst1" class="col-sm-3 control-label">Bank</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="bank_inst1" name="bank_inst1" value="{{ !empty($loan->bank_inst1) ? $loan->bank_inst1 : '' }}"  placeholder="Bank">
					</div>
				</div>
				<div class="form-group">
					<label for="bank_branch1" class="col-sm-3 control-label">Branch</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="bank_branch1" name="bank_branch1v" value="{{ !empty($loan->bank_branch1v) ? $loan->bank_branch1v : '' }}"  placeholder="Branch">
					</div>
				</div>
				<div class="form-group">
					<label for="bank_acc_type1" class="col-sm-3 control-label">Account type</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="bank_acc_type1" name="bank_acc_type1" value="{{ !empty($loan->bank_acc_type1) ? $loan->bank_acc_type1 : '' }}"  placeholder="Account type">
					</div>
				</div>
				<div class="form-group">
					<label for="bank_acc1" class="col-sm-3 control-label">Account No.</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="bank_acc1" name="bank_acc1" value="{{ !empty($loan->bank_acc1) ? $loan->bank_acc1 : '' }}"  placeholder="Account No.">
					</div>
				</div>
				 <div class="form-group">
					<label for="bank_acc_name1" class="col-sm-3 control-label">Account name</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="bank_acc_name1" name="bank_acc_name1" value="{{ !empty($loan->bank_acc_name1) ? $loan->bank_acc_name1 : '' }}"  placeholder="Account name">
					</div>
				</div>
				<!-- Asset -->
				<div class="form-group"><p class="lead"><b>Asset</b></p></div>
				 <div class="form-group">
					<label for="asset_desc" class="col-sm-3 control-label">Description of vehicle or asset</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="asset_desc" name="asset_desc" value="{{ !empty($loan->loanAssets->asset_desc) ? $loan->loanAssets->asset_desc : '' }}" placeholder="Bank">
					</div>
				</div>
				<div class="form-group">
					<label for="asset_state" class="col-sm-3 control-label">State</label>
					 <div class="col-sm-9">
					  <select class="form-control" name="asset_state" id="asset_state" placeholder="Select">
						<option value="1" {{ (!empty($loan->loanAssets->asset_state) &&  $loan->loanAssets->asset_state == 1) ? ' selected' : '' }}>New</option>
						<option value="2" {{ (!empty($loan->loanAssets->asset_state) &&  $loan->loanAssets->asset_state == 2) ? ' selected' : '' }}>Used</option>
					  </select>
					  </div>
				</div>
				<div class="form-group">
					<label for="asset_model" class="col-sm-3 control-label">Model year</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="asset_model" name="asset_model" value="{{ !empty($loan->loanAssets->asset_model) ? $loan->loanAssets->asset_model : '' }}" placeholder="Model year">
					</div>
				</div>
				<div class="form-group">
					<label for="asset_maf_date" class="col-sm-3 control-label">Manufacture date</label>
					<div class="col-sm-9">
						<div class="input-group date">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
							<input type="text" class="form-control datepicker" name="asset_maf_date" placeholder="  dd/mm/yyyy" value="{{ !empty($loan->loanAssets->asset_maf_date) ? date('Y M d', $loan->loanAssets->asset_maf_date) : '' }}" >
						</div>
                    </div>
				</div>
				 <div class="form-group">
					<label for="asset_supplier" class="col-sm-3 control-label">Supplier</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="asset_supplier" name="asset_supplier" value="{{ !empty($loan->loanAssets->asset_supplier) ? $loan->loanAssets->asset_supplier : '' }}" placeholder="Supplier">
					</div>
				</div>  
				<div class="form-group">
					<label for="asset_supplier_contact" class="col-sm-3 control-label">Supplier contact</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="asset_supplier_contact" name="asset_supplier_contact" value="{{ !empty($loan->loanAssets->asset_supplier_contact) ? $loan->loanAssets->asset_supplier_contact : '' }}" placeholder="Supplier contact">
					</div>
				</div>  
				<div class="form-group">
					<label for="asset_supplier_tel" class="col-sm-3 control-label">Telephone No.</label>

					<div class="col-sm-9">
						<input type="tel" class="form-control" id="asset_supplier_tel" name="asset_supplier_tel" value="{{ !empty($loan->loanAssets->asset_supplier_tel) ? $loan->loanAssets->asset_supplier_tel : '' }}" placeholder="Telephone No.">
					</div>
				</div>
				<div class="form-group">
					<label for="asset_cash_price" class="col-sm-3 control-label">Cash Price</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="asset_cash_price" name="asset_cash_price" value="{{ !empty($loan->loanAssets->asset_cash_price) ? $loan->loanAssets->asset_cash_price : '' }}" placeholder="Cash Price">
					</div>
				</div> 
				<div class="form-group">
					<label for="asset_extras" class="col-sm-3 control-label">Extras</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="asset_extras" name="asset_extras" value="{{ !empty($loan->loanAssets->asset_extras) ? $loan->loanAssets->asset_extras : '' }}" placeholder="Extras">
					</div>
				</div> <div class="form-group">
					<label for="asset_extras1" class="col-sm-3 control-label">Extras</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="asset_extras1" name="asset_extras1" value="{{ !empty($loan->loanAssets->asset_extras1) ? $loan->loanAssets->asset_extras1 : '' }}" placeholder="Extras">
					</div>
				</div> <div class="form-group">
					<label for="asset_extras2" class="col-sm-3 control-label">Extras</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="asset_extras2" name="asset_extras2" value="{{ !empty($loan->loanAssets->asset_extras2) ? $loan->loanAssets->asset_extras2 : '' }}" placeholder="Extras">
					</div>
				</div> <div class="form-group">
					<label for="asset_total_extras" class="col-sm-3 control-label">Total extras</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="asset_total_extras" name="asset_total_extras" value="{{ !empty($loan->loanAssets->asset_total_extras) ? $loan->loanAssets->asset_total_extras : '' }}" placeholder="Total extras">
					</div>
				</div> <div class="form-group">
					<label for="asset_sub_total" class="col-sm-3 control-label">Sub total</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="asset_sub_total" name="asset_sub_total" value="{{ !empty($loan->loanAssets->asset_sub_total) ? $loan->loanAssets->asset_sub_total : '' }}" placeholder="Sub total">
					</div>
				</div> <div class="form-group">
					<label for="asset_deposit" class="col-sm-3 control-label">Deposit</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="asset_deposit" name="asset_deposit" value="{{ !empty($loan->loanAssets->asset_deposit) ? $loan->loanAssets->asset_deposit : '' }}" placeholder="Deposit">
					</div>
				</div> <div class="form-group">
					<label for="asset_finance" class="col-sm-3 control-label">Finance required</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="asset_finance" name="asset_finance" value="{{ !empty($loan->loanAssets->asset_finance) ? $loan->loanAssets->asset_finance : '' }}" placeholder="Finance required">
					</div>
				</div> 
				<div class="form-group">
					<label for="asset_period" class="col-sm-3 control-label">Period of contract</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="asset_period" name="asset_period" value="{{ !empty($loan->loanAssets->asset_period) ? $loan->loanAssets->asset_period : '' }}" placeholder="Period of contract">
					</div>
				</div> 
              </div>  
			  <div class="tab-pane" id="tab_6">
			  <!-- Insurance -->
				<div class="form-group"><p class="lead"><b>Insurance</b></p></div>
				<div class="form-group">
					<label for="exsiting_policy" class="col-sm-3 control-label">Existing policy</label>
					 <div class="col-sm-9">
					  <select class="form-control" name="exsiting_policy" id="exsiting_policy" placeholder="Select">
						<option value="1"{{ (!empty($loan->loanInsurance->exsiting_policy) &&  $loan->loanInsurance->exsiting_policy == 1) ? ' selected' : '' }}>Yes</option>
						<option value="2" {{ (!empty($loan->loanInsurance->exsiting_policy) &&  $loan->loanInsurance->exsiting_policy == 2) ? ' selected' : '' }}>No</option>
					  </select>
					  </div>
				</div>
				<div class="form-group">
					<label for="polocy_no" class="col-sm-3 control-label">Policy no.</label>

					<div class="col-sm-9">
						<input type="text" class="form-control" id="polocy_no" name="polocy_no" value="{{ !empty($loan->loanInsurance->polocy_no) ? $loan->loanInsurance->polocy_no : '' }}" placeholder="Policy no.">
					</div>
				</div> 
				<div class="form-group">
					<label for="renewal_date" class="col-sm-3 control-label">Renewal date</label>
					<div class="col-sm-9">
						<div class="input-group date">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
							<input type="text" class="form-control datepicker" name="renewal_date" value="{{ !empty($loan->loanInsurance->renewal_date) ? date('Y M d', $loan->loanInsurance->renewal_date) : '' }}" placeholder="  dd/mm/yyyy" value="">
						</div>
                    </div>
				</div> 
				<div class="form-group">
					<label for="annual_premium" class="col-sm-3 control-label">Annual premium</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="annual_premium" name="annual_premium" value="{{ !empty($loan->loanInsurance->annual_premium) ? $loan->loanInsurance->annual_premium : '' }}" placeholder="Annual premium">
					</div>
				</div> 
				<div class="form-group">
					<label for="monthly_premium" class="col-sm-3 control-label">Monthly premium</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="monthly_premium" name="monthly_premium" value="{{ !empty($loan->loanInsurance->monthly_premium) ? $loan->loanInsurance->monthly_premium : '' }}" placeholder="Monthly premium">
					</div>
				</div> 
				<div class="form-group"><p class="lead"><b>Additional Securities</b></p></div>
				<div class="form-group">
					<label for="type_security" class="col-sm-3 control-label">Type of security</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="type_security" name="type_security" value="{{ !empty($loan->loanInsurance->type_security) ? $loan->loanInsurance->type_security : '' }}" placeholder="Type of security">
					</div>
				</div> 
				<div class="form-group">
					<label for="value_security" class="col-sm-3 control-label">Value of security</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="value_security" name="value_security" value="{{ !empty($loan->loanInsurance->value_security) ? $loan->loanInsurance->value_security : '' }}" placeholder="Value of security">
					</div>
				</div>
              </div> 
			  <div class="tab-pane" id="tab_7">
                <div class="form-group">
					<label for="statement_liability" class="col-sm-3 control-label">Statement of Assets & Liabilities of</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="statement_liability" name="statement_liability" value="{{ !empty($loan->loanInsurance->statement_liability) ? $loan->loanInsurance->statement_liability : '' }}" placeholder="Statement of Assets & Liabilities of">
					</div>
				</div>
				<div class="form-group">
					<label for="statement_as_at" class="col-sm-3 control-label">As at</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="statement_as_at" name="statement_as_at" value="{{ !empty($loan->loanInsurance->statement_as_at) ? $loan->loanInsurance->statement_as_at : '' }}" placeholder="As at">		
					</div>
				</div>
				<div class="form-group">
					<label for="fixed_property_cost" class="col-sm-3 control-label">Fixed property – at cost plus improvements</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="fixed_property_cost" name="fixed_property_cost" value="{{ !empty($loan->loanInsurance->fixed_property_cost) ? $loan->loanInsurance->fixed_property_cost : '' }}" placeholder="Fixed property – at cost plus improvements R">		
					</div>
				</div>
				<div class="form-group">
					<label for="member_items" class="col-sm-3 control-label">Shares, Member’s Interest & Debentures – Itemise (use separate
sheet if necessary)</label>
					<div class="col-sm-9">
					<textarea class="form-control" rows="3" id="member_items" name="member_items" value="{{ !empty($loan->loanInsurance->member_items) ? $loan->loanInsurance->member_items : '' }}" placeholder="Please write all of them, write the next on the 
next line, do not forget the full stop at the end of each"></textarea>			
					</div>
				</div>
				<div class="form-group">
					<label for="motor_vehicle_owned" class="col-sm-3 control-label">Motor vehicle – only if owned – not leased</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="motor_vehicle_owned" name="motor_vehicle_owned" value="{{ !empty($loan->loanInsurance->motor_vehicle_owned) ? $loan->loanInsurance->motor_vehicle_owned : '' }}" placeholder="Motor vehicle – only if owned – not leased">		
					</div>
				</div>
				<div class="form-group">
					<label for="loans_receivable" class="col-sm-3 control-label">Loans receivable</label>
					<div class="col-sm-9">
					<textarea class="form-control" rows="3" id="loans_receivable" name="loans_receivable" value="{{ !empty($loan->loanInsurance->loans_receivable) ? $loan->loanInsurance->loans_receivable : '' }}" placeholder="Please write all of them, write the next on the 
next line, do not forget the full stop at the end of each"></textarea>			
					</div>
				</div>
				<div class="form-group">
					<label for="net_capital" class="col-sm-3 control-label">Net capital of business or profession</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="net_capital" name="net_capital" value="{{ !empty($loan->loanInsurance->net_capital) ? $loan->loanInsurance->net_capital : '' }}" placeholder="Net capital of business or profession">		
					</div>
				</div>
				<div class="form-group">
					<label for="debtors" class="col-sm-3 control-label">Debtors</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="debtors" name="debtors" value="{{ !empty($loan->loanInsurance->debtors) ? $loan->loanInsurance->debtors : '' }}" placeholder="Debtors">		
					</div>
				</div>
				<div class="form-group">
					<label for="cash_on_hand" class="col-sm-3 control-label">Cash – on hand</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="cash_on_hand" name="cash_on_hand" value="{{ !empty($loan->loanInsurance->cash_on_hand) ? $loan->loanInsurance->cash_on_hand : '' }}" placeholder="Cash – on hand">		
					</div>
				</div>
				<div class="form-group">
					<label for="cash_at_bank" class="col-sm-3 control-label">Cash - at bank</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="cash_at_bank" name="cash_at_bank" value="{{ !empty($loan->loanInsurance->cash_at_bank) ? $loan->loanInsurance->cash_at_bank : '' }}" placeholder="Cash - at bank">		
					</div>
				</div>
				<div class="form-group">
					<label for="surrender_value" class="col-sm-3 control-label">Surrender value on insurance policies – attach latest printouts</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="surrender_value" name="surrender_value" value="{{ !empty($loan->loanInsurance->surrender_value) ? $loan->loanInsurance->surrender_value : '' }}" placeholder="Surrender value on insurance policies – attach latest printouts">		
					</div>
				</div>
				<div class="form-group">
					<label for="personal_effects" class="col-sm-3 control-label">Personal effects – eg. Furniture jewellery, etc</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="personal_effects" name="personal_effects" value="{{ !empty($loan->loanInsurance->personal_effects) ? $loan->loanInsurance->personal_effects : '' }}" placeholder="Personal effects – eg. Furniture jewellery, etc">		
					</div>
				</div>
				<div class="form-group">
					<label for="other_assets" class="col-sm-3 control-label">Other assets (specify)</label>
					<div class="col-sm-9">
					<textarea class="form-control" rows="3" id="other_assets" name="other_assets" value="{{ !empty($loan->loanInsurance->other_assets) ? $loan->loanInsurance->other_assets : '' }}" placeholder="Please write all of them, write the next on the 
next line, do not forget the full stop at the end of each"></textarea>			
					</div>
				</div>
				<div class="form-group">
					<label for="total_assets" class="col-sm-3 control-label">Total assets</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="total_assets" name="total_assets" value="{{ !empty($loan->loanInsurance->total_assets) ? $loan->loanInsurance->total_assets : '' }}" placeholder="Total assets">		
					</div>
				</div>
              </div>  
			  <div class="tab-pane" id="tab_8">
				<div class="form-group"><p class="lead"><b>Liabilities</b></p></div>
              <div class="form-group">
					<label for="liabilities_bond" class="col-sm-3 control-label">Bonds</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="liabilities_bond" name="liabilities_bond" value="{{ !empty($loan->loanLiabilities->liabilities_bond) ? $loan->loanLiabilities->liabilities_bond : '' }}" placeholder="Bonds">		
					</div>
				</div>
					<div class="form-group">
						<label for="loan_payable" class="col-sm-3 control-label">Loans payable</label>
					<div class="col-sm-9">
						<textarea class="form-control" rows="3" id="loan_payable" name="loan_payable" value="{{ !empty($loan->loanLiabilities->loan_payable) ? $loan->loanLiabilities->loan_payable : '' }}" placeholder="Please write all of them, write the next on the 
next line, do not forget the full stop at the end of each R"></textarea>			
						</div>
					</div>
				<div class="form-group">
					<label for="outs_bal_mot" class="col-sm-3 control-label">Outstanding balance on motor vehicles</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="outs_bal_mot" name="outs_bal_mot" value="{{ !empty($loan->loanLiabilities->outs_bal_mot) ? $loan->loanLiabilities->outs_bal_mot : '' }}" placeholder="Outstanding balance on motor vehicles R">		
					</div>
				</div>
				<div class="form-group">
					<label for="liabilities_motor" class="col-sm-3 control-label">Liabilities on motor vehicle & equipment leases</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="liabilities_motor" name="liabilities_motor" value="{{ !empty($loan->loanLiabilities->liabilities_motor) ? $loan->loanLiabilities->liabilities_motor : '' }}" placeholder="Liabilities on motor vehicle & equipment leases R">		
					</div>
				</div>
				<div class="form-group">
					<label for="fur_outs_amt" class="col-sm-3 control-label">Furniture outstanding amounts</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="fur_outs_amt" name="fur_outs_amt" value="{{ !empty($loan->loanLiabilities->fur_outs_amt) ? $loan->loanLiabilities->fur_outs_amt : '' }}" placeholder="Furniture outstanding amounts R">		
					</div>
				</div>
				<div class="form-group">
					<label for="loan_ins_pol" class="col-sm-3 control-label">Loans on insurance policies</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="loan_ins_pol" name="loan_ins_pol" value="{{ !empty($loan->loanLiabilities->loan_ins_pol) ? $loan->loanLiabilities->loan_ins_pol : '' }}" placeholder="Loans on insurance policies R">		
					</div>
				</div>
				<div class="form-group">
					<label for="lia_inc_tax" class="col-sm-3 control-label">Liability for income tax</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="lia_inc_tax" name="lia_inc_tax" value="{{ !empty($loan->loanLiabilities->lia_inc_tax) ? $loan->loanLiabilities->lia_inc_tax : '' }}" placeholder="Liability for income tax R">		
					</div>
				</div>
				<div class="form-group">
					<label for="bank_overdraft" class="col-sm-3 control-label">Bank overdraft</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="bank_overdraft" name="bank_overdraft" value="{{ !empty($loan->loanLiabilities->bank_overdraft) ? $loan->loanLiabilities->bank_overdraft : '' }}" placeholder="Bank overdraft R">		
					</div>
				</div>
				<div class="form-group">
					<label for="crd_inst_limit" class="col-sm-3 control-label">Credit card - Institution and Card limit</label>
					<div class="col-sm-9">
					<textarea class="form-control" rows="3" id="crd_inst_limit" name="crd_inst_limit" value="{{ !empty($loan->loanLiabilities->crd_inst_limit) ? $loan->loanLiabilities->crd_inst_limit : '' }}" placeholder="Please write all of them, write the next on the 
next line, do not forget the full stop at the end of each. R"></textarea>			
						</div>
				</div>
				<div class="form-group">
					<label for="other_liabilities" class="col-sm-3 control-label">Other liabilities</label>
					<div class="col-sm-9">
					<textarea class="form-control" rows="3" id="other_liabilities" name="other_liabilities" value="{{ !empty($loan->loanLiabilities->other_liabilities) ? $loan->loanLiabilities->other_liabilities : '' }}" placeholder="Please write all of them, write the next on the 
next line, do not forget the full stop at the end of each. R"></textarea>			
						</div>
				</div>
				<div class="form-group">
					<label for="total_liability" class="col-sm-3 control-label">Total liabilities</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="total_liability" name="total_liability" value="{{ !empty($loan->loanLiabilities->total_liability) ? $loan->loanLiabilities->total_liability : '' }}"  placeholder="Total liabilities R">		
					</div>
				</div>
				<div class="form-group">
					<label for="net_asset" class="col-sm-3 control-label">Net Assets (Total assets - Total liabilities)</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="net_asset" name="net_asset" value="{{ !empty($loan->loanLiabilities->net_asset) ? $loan->loanLiabilities->net_asset : '' }}" placeholder="Total assets - Total liabilities R">		
					</div>
				</div>
				<div class="form-group"><p class="lead"><b>Contingent Liabilities</b> as Guarantor, Surety or otherwise</p></div>
				<div class="form-group">
					<label for="in_fav_of" class="col-sm-3 control-label">In favour of whom / Which institution / Amount</label>
					<div class="col-sm-9">
					<textarea class="form-control" rows="3" id="in_fav_of" name="in_fav_of" value="{{ !empty($loan->loanLiabilities->in_fav_of) ? $loan->loanLiabilities->in_fav_of : '' }}" placeholder="Please write all of them, write the next on the 
next line, do not forget the full stop at the end of each. R"></textarea>			
				</div>
				</div>
				<div class="form-group">
					<label for="total_cont_lia" class="col-sm-3 control-label">Total Contingent Liabilities</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="total_cont_lia" name="total_cont_lia" value="{{ !empty($loan->loanLiabilities->total_cont_lia) ? $loan->loanLiabilities->total_cont_lia : '' }}" placeholder="Total Contingent Liabilities R">		
					</div>
				</div>
              </div>
			  <div class="tab-pane" id="tab_9">
			  <div class="form-group"><p class="lead"><b>Schedule of Income & Expenses</b></p></div>
               <div class="form-group">
					<label for="schedule_inc_exp" class="col-sm-3 control-label">Schedule of Income & Expenses of</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="schedule_inc_exp" name="schedule_inc_exp" value="{{ !empty($loan->loanIncome->debtors) ? $loan->loanIncome->debtors : '' }}" placeholder="Schedule of Income & Expenses of">
					</div>
				</div>
				<div class="form-group">
					<label for="schedule_as_at" class="col-sm-3 control-label">As at</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="schedule_as_at" name="schedule_as_at" value="{{ !empty($loan->loanIncome->debtors) ? $loan->loanIncome->debtors : '' }}" placeholder="As at">		
					</div>
				</div>
				<!-- Income -->
				 <div class="form-group"><p class="lead"><b>Income</b></p></div>
				  <div class="form-group">
					<label for="gross_salary" class="col-sm-3 control-label">Gross salary</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="gross_salary" name="gross_salary" value="{{ !empty($loan->loanIncome->debtors) ? $loan->loanIncome->debtors : '' }}" placeholder="Gross salary. Please attach salary slip on the document session">
					</div>
				</div>
				<div class="form-group">
					<label for="deductions" class="col-sm-3 control-label">Deductions</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="deductions" name="deductions" value="{{ !empty($loan->loanIncome->debtors) ? $loan->loanIncome->debtors : '' }}" placeholder="Deductions Rand Amunt">		
					</div>
				</div>
				 <div class="form-group">
					<label for="salary_tax" class="col-sm-3 control-label">Tax</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="salary_tax" name="salary_tax" value="{{ !empty($loan->loanIncome->debtors) ? $loan->loanIncome->debtors : '' }}" placeholder="Tax Rand Amount">
					</div>
				</div>
				<div class="form-group">
					<label for="salary_medical" class="col-sm-3 control-label">Medical Aid</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="salary_medical" name="salary_medical" value="{{ !empty($loan->loanIncome->debtors) ? $loan->loanIncome->debtors : '' }}" placeholder="Medical Aid Rand Amount">		
					</div>
				</div>
				 <div class="form-group">
					<label for="salary_pension" class="col-sm-3 control-label">Pension</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="salary_pension" name="salary_pension" value="{{ !empty($loan->loanIncome->debtors) ? $loan->loanIncome->debtors : '' }}" placeholder="Pension Rand Amount">
					</div>
				</div>
				<div class="form-group">
					<label for="salary_uif" class="col-sm-3 control-label">UIF</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="salary_uif" name="salary_uif" value="{{ !empty($loan->loanIncome->debtors) ? $loan->loanIncome->debtors : '' }}" placeholder="UIF Rand Amount">		
					</div>
				</div>
				 <div class="form-group">
					<label for="salary_other" class="col-sm-3 control-label">Other</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="salary_other" name="salary_other" value="{{ !empty($loan->loanIncome->debtors) ? $loan->loanIncome->debtors : '' }}" placeholder="Other Rand Amount">
					</div>
				</div>
				<div class="form-group">
					<label for="salary_tot_ded" class="col-sm-3 control-label">Total deductions</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="salary_tot_ded" name="salary_tot_ded" value="{{ !empty($loan->loanIncome->debtors) ? $loan->loanIncome->debtors : '' }}" placeholder="Total deductions Rand Amount">		
					</div>
				</div>
				 <div class="form-group">
					<label for="salary_other_inc" class="col-sm-3 control-label">Other Income - Specify</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="salary_other_inc" name="salary_other_inc" value="{{ !empty($loan->loanIncome->debtors) ? $loan->loanIncome->debtors : '' }}" placeholder="Other Income - Specify Rand Amount">
					</div>
				</div>
				<div class="form-group">
					<label for="total_income" class="col-sm-3 control-label">Total Income</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="total_income" name="total_income" value="{{ !empty($loan->loanIncome->debtors) ? $loan->loanIncome->debtors : '' }}" placeholder="Gross Salary – Total deductions Rand Amount">		
					</div>
				</div>
				<!-- Monthly Commitments & Expenses -->
				<div class="form-group"><p class="lead"><b>Monthly Commitments & Expenses</b></p></div>
				 <div class="form-group">
					<label for="com_rent_bond" class="col-sm-3 control-label">Rent / Bond</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="com_rent_bond" name="com_rent_bond" value="{{ !empty($loan->loanIncome->com_rent_bond) ? $loan->loanIncome->com_rent_bond : '' }}" placeholder="Rent / Bond Rand Amount">
					</div>
				</div>
				<div class="form-group">
					<label for="com_hire" class="col-sm-3 control-label">Hire Purchase instalments & Lease agreements</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="com_hire" name="com_hire" value="{{ !empty($loan->loanIncome->com_hire) ? $loan->loanIncome->com_hire : '' }}" placeholder="Hire Purchase instalments & Lease agreements Rand Amunt">		
					</div>
				</div>
				 <div class="form-group">
					<label for="com_loan_repay" class="col-sm-3 control-label">Loan Repayment</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="com_loan_repay" name="com_loan_repay" value="{{ !empty($loan->loanIncome->com_loan_repay) ? $loan->loanIncome->com_loan_repay : '' }}" placeholder="Loan Repayment Rand Amount">
					</div>
				</div>
				<div class="form-group">
					<label for="com_ins_pre" class="col-sm-3 control-label">Insurance premiums</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="com_ins_pre" name="com_ins_pre" value="{{ !empty($loan->loanIncome->com_ins_pre) ? $loan->loanIncome->com_ins_pre : '' }}" placeholder="Insurance premiums Rand Amount">		
					</div>
				</div>
				 <div class="form-group">
					<label for="com_life_ins" class="col-sm-3 control-label">Life assurance premiums</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="com_life_ins" name="com_life_ins" value="{{ !empty($loan->loanIncome->com_life_ins) ? $loan->loanIncome->com_life_ins : '' }}" placeholder="Life assurance premiums Rand Amount">
					</div>
				</div>
				<div class="form-group">
					<label for="com_elec_water" class="col-sm-3 control-label">Electricity & water</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="com_elec_water" name="com_elec_water" value="{{ !empty($loan->loanIncome->com_elec_water) ? $loan->loanIncome->com_elec_water : '' }}" placeholder="Electricity & water Rand Amount">		
					</div>
				</div>
				 <div class="form-group">
					<label for="com_rates_tax" class="col-sm-3 control-label">Rates & taxes</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="com_rates_tax" name="com_rates_tax" value="{{ !empty($loan->loanIncome->com_rates_tax) ? $loan->loanIncome->com_rates_tax : '' }}" placeholder="Rates & taxes Rand Amount">
					</div>
				</div>
				<div class="form-group">
					<label for="com_tel" class="col-sm-3 control-label">Telephones including rentals & cellphones</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="com_tel" name="com_tel" value="{{ !empty($loan->loanIncome->com_tel) ? $loan->loanIncome->com_tel : '' }}" placeholder="Telephones including rentals & cellphones Rand Amount">		
					</div>
				</div>
				 <div class="form-group">
					<label for="com_maintenance" class="col-sm-3 control-label">Alimony / Maintenance</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="com_maintenance" name="com_maintenance" value="{{ !empty($loan->loanIncome->com_maintenance) ? $loan->loanIncome->com_maintenance : '' }}" placeholder="Alimony / Maintenance Rand Amount">
					</div>
				</div>
				<div class="form-group">
					<label for="com_plan_sav" class="col-sm-3 control-label">Planned savings</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="com_plan_sav" name="com_plan_sav" value="{{ !empty($loan->loanIncome->com_plan_sav) ? $loan->loanIncome->com_plan_sav : '' }}" placeholder="Planned savings Rand Amount">		
					</div>
				</div>
				 <div class="form-group">
					<label for="com_crd_amt" class="col-sm-3 control-label">Credit card accounts</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="com_crd_amt" name="com_crd_amt" value="{{ !empty($loan->loanIncome->com_crd_amt) ? $loan->loanIncome->com_crd_amt : '' }}" placeholder="Credit card accounts Rand Amount">
					</div>
				</div>
				<div class="form-group">
					<label for="com_donations" class="col-sm-3 control-label">Donations</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="com_donations" name="com_donations" value="{{ !empty($loan->loanIncome->com_donations) ? $loan->loanIncome->com_donations : '' }}" placeholder="Donations Rand Amunt">		
					</div>
				</div>
				 <div class="form-group">
					<label for="com_edu" class="col-sm-3 control-label">Education – Fees, books, etc</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="com_edu" name="com_edu" value="{{ !empty($loan->loanIncome->com_edu) ? $loan->loanIncome->com_edu : '' }}" placeholder="Education – Fees, books, etc Rand Amount">
					</div>
				</div>
				<div class="form-group">
					<label for="com_children" class="col-sm-3 control-label">Children’s clothing</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="com_children" name="com_children" value="{{ !empty($loan->loanIncome->com_children) ? $loan->loanIncome->com_children : '' }}" placeholder="Children’s clothing Rand Amount">		
					</div>
				</div>
				 <div class="form-group">
					<label for="com_groceries" class="col-sm-3 control-label">Groceries</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="com_groceries" name="com_groceries" value="{{ !empty($loan->loanIncome->com_groceries) ? $loan->loanIncome->com_groceries : '' }}" placeholder="Groceries Rand Amount">
					</div>
				</div>
				<div class="form-group">
					<label for="com_cloh_acc" class="col-sm-3 control-label">Clothing accounts</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="com_cloh_acc" name="com_cloh_acc" value="{{ !empty($loan->loanIncome->com_cloh_acc) ? $loan->loanIncome->com_cloh_acc : '' }}" placeholder="Clothing accounts Rand Amount">		
					</div>
				</div>
				 <div class="form-group">
					<label for="com_doc_den" class="col-sm-3 control-label">Doctor / Chemist</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="com_doc_den" name="com_doc_den" value="{{ !empty($loan->loanIncome->com_doc_den) ? $loan->loanIncome->com_doc_den : '' }}" placeholder="Doctor / Chemist Rand Amount">
					</div>
				</div>
				<div class="form-group">
					<label for="com_domes" class="col-sm-3 control-label">Domestic / Garden help</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="com_domes" name="com_domes" value="{{ !empty($loan->loanIncome->com_domes) ? $loan->loanIncome->com_domes : '' }}" placeholder="Domestic / Garden help Rand Amount">		
					</div>
				</div>
				 <div class="form-group">
					<label for="com_sec_sys" class="col-sm-3 control-label">Security system</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="com_sec_sys" name="com_sec_sys" value="{{ !empty($loan->loanIncome->com_sec_sys) ? $loan->loanIncome->com_sec_sys : '' }}" placeholder="Security system Rand Amount">
					</div>
				</div>
				<div class="form-group">
					<label for="com_tran" class="col-sm-3 control-label">Transport</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="com_tran" name="com_tran" value="{{ !empty($loan->loanIncome->com_tran) ? $loan->loanIncome->com_tran : '' }}" placeholder="Petrol, bus fare, parking, etc Rand Amount">		
					</div>
				</div>
				 <div class="form-group">
					<label for="com_enter" class="col-sm-3 control-label">Entertainment</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="com_enter" name="com_enter" value="{{ !empty($loan->loanIncome->com_enter) ? $loan->loanIncome->com_enter : '' }}" placeholder="Entertainment Rand Amount">
					</div>
				</div>
				<div class="form-group">
					<label for="com_dstv" class="col-sm-3 control-label">TV rental / DSTV etc</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="com_dstv" name="com_dstv" value="{{ !empty($loan->loanIncome->com_dstv) ? $loan->loanIncome->com_dstv : '' }}" placeholder="TV rental / DSTV etc Rand Amunt">		
					</div>
				</div>
				<div class="form-group">
					<label for="com_other" class="col-sm-3 control-label">Other (specify)</label>
					<div class="col-sm-9">
					<textarea class="form-control" rows="3" id="com_other" name="com_other" value="{{ !empty($loan->loanIncome->com_other) ? $loan->loanIncome->com_other : '' }}" placeholder="Please write all of them, write the next on the 
next line, do not forget the full stop at the end of each"></textarea>			
					</div>
				</div>
				<div class="form-group">
					<label for="com_total" class="col-sm-3 control-label">Total Commitments and Expenses</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="com_total" name="com_total" value="{{ !empty($loan->loanIncome->com_total) ? $loan->loanIncome->com_total : '' }}" placeholder="Total Commitments and Expenses Rand Amount">		
					</div>
				</div>
				 <div class="form-group">
					<label for="surplus_available" class="col-sm-3 control-label">Surplus available</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="surplus_available" name="surplus_available" value="{{ !empty($loan->loanIncome->surplus_available) ? $loan->loanIncome->surplus_available : '' }}" placeholder="Total income Total expenditure Rand Amount">
					</div>
				</div>
				<div class="form-group"><p class="lead"><b>Creditors</b></p></div>
			  <div class="form-group">
					<label for="debt_obl" class="col-sm-3 control-label">Debt Obligations of</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="debt_obl" name="debt_obl" value="{{ !empty($loan->loanIncome->debt_obl) ? $loan->loanIncome->debt_obl : '' }}" placeholder="Debt Obligations of">
					</div>
				</div>
				<div class="form-group">
					<label for="debt_as_at" class="col-sm-3 control-label">As at</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="debt_as_at" name="debt_as_at" value="{{ !empty($loan->loanIncome->debt_as_at) ? $loan->loanIncome->debt_as_at : '' }}" placeholder="As at">		
					</div>
				</div>
				<div class="form-group">
					<label for="name_creditors" class="col-sm-3 control-label">Name of creditor/Total amount
outstanding/Monthly commitment</label>
					<div class="col-sm-9">
					<textarea class="form-control" rows="3" id="name_creditors" name="name_creditors" value="{{ !empty($loan->loanIncome->name_creditors) ? $loan->loanIncome->name_creditors : '' }}" placeholder="Please write all of them, write the next on the 
next line, do not forget the full stop at the end of each 
Exple 
XXXX Name  120000   1000.
XXXX Name  140000   2000.
"></textarea>			
					</div>
				</div>
				<div class="form-group">
					<label for="total_cre_amount" class="col-sm-3 control-label">Total Creditors Amount</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="total_cre_amount" name="total_cre_amount" value="{{ !empty($loan->loanIncome->total_cre_amount) ? $loan->loanIncome->total_cre_amount : '' }}" placeholder="Total Creditors Amount">		
					</div>
				</div>
				<div class="form-group">
					<label for="total_cmt_amount" class="col-sm-3 control-label">Total Monthly commitment</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="total_cmt_amount" name="total_cmt_amount" value="{{ !empty($loan->loanIncome->total_cmt_amount) ? $loan->loanIncome->total_cmt_amount : '' }}" placeholder="Total Monthly commitment">		
					</div>
				</div>
              </div>
			  <div class="tab-pane" id="tab_10">
              <div class="form-group"><p class="lead"><b>Documents Upload</b></p></div>
				@if (!empty($loan->id))
				@foreach($loan->loanDoc as $doc)
						<div class="col-sm-4">
							{{ $doc->name }}
						</div>
						<div class="col-sm-8">
							<a href="{{ Storage::disk('local')->url("loanDocs/$doc->file_name") }}" target=\"_blank\">Click Here</a>
						</div>
				@endforeach
				<div class="form-group"><p class="lead"><b>New Documents Upload</b></p></div>
				@endif
			  <div id="tab_tab">
				<div class="col-sm-6"  id="file_row">
					<input type="file" class="form-control" id="loan_file" name="loan_file[1]">
				</div>
				<div class="col-sm-6"  style="display:none" id="name_row">
					<input type="text" class="form-control" id="name" name="name" placeholder="File Name or description" disabled="disabled">
				</div>
				<div class="col-sm-6"  id="1" name="1">
					<input type="text" class="form-control" id="name[1]" name="name[1]" placeholder="File Name or description">
				</div>
              </div> 
			  	<div class="form-group"  id="final_row">
				<div class="col-sm-12"><button type="button" class="btn btn-primary add_more"  onclick="addFile()">Add More</button></div>               
               </div>
              </div> 
			  <div class="tab-pane" id="tab_11">
                <div class="form-group"><p class="lead"><b>Terms and Conditions</b></p></div>
				<div><p class="lead">
				I confirm that:</br>
a) I am not a minor</br>
b) I have never been declared mentally unfit by a court</br>
c) I am not subject to an Administration Order</br>
d) I do not have any current application pending for debt restructuring or alleviation</br>
e) I do not have any current debt re-arrangement in existence</br>
f) I have not previously applied for a debt re-arrangement</br>
g) I am not under sequestration</br>
h) I do not have applications pending for credit, nor open quotations as envisaged in section 92 of the
National Credit Act</br>
If any of the above is incorrect state</br>
I understand that I will be liable for a monthly service fee.</br>
I consent to the Credit Provider reporting the conclusion of any credit agreement with me to the National Loan
Register in compliance with this Credit Provider’s obligation under the National Credit Act.</br>
I hereby declare that the information provided by me is true and correct.</br>
I consent to the Credit Provider making enquiries about my credit record with credit reference agencies for the
purposes of assessing this credit application or updating my information in future. I also consent to the Credit
Provider sharing the information with such agencies about how I manage this loan agreement, who may in turn
share the information with other credit providers.</br>
I consent to identity and fraud prevention checks and sharing information relating to this application through the
South African Fraud Prevention Service.</br>
If you are married in community of property you are required to obtain the written consent of your spouse, in
terms of the Matrimonial Property Act of 1984, before entering this agreement.</br>

I / We declare that this is a full, true and correct statement of my / our position and that</br>
my / our assets are not encumbered, other than as stated above.
I / We agree that the credit provider may verify the information contained in this</br>
statement and may make any enquiries it considers necessary.</br>
I confirm that the information provided is a true reflection of all my debt obligations.</br>
I confirm that the information provided is accurate and a true reflection of my financial position.</br>

Please note that thia action represent your signature, by clicking on this you are agreeing to out terms and conditions</p></div>
					<div class="checkbox">
					<label>
                    <input type="checkbox" name="digital_signature"  value="1"> I agree.
					</label>
					</div>
				</div> 
              <!-- /.tab-pane -->
				</div>
				<!-- /.tab-content -->
				</div>
			</div>
			<!-- /.box-body -->
			<div class="box-footer">
				<button type="submit" class="btn btn-primary pull-center" name="command" value="save">Save</button>
				<button type="submit" class="btn btn-primary pull-right" name="command" value="submit">Submit</button>
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
<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
	<script type="text/javascript">
	 $(function () {
            //Date picker
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                endDate: '-1d',
                autoclose: true
            });
        });
	function clone(id,file_index,child_id)
	{
		var clone=document.getElementById(id).cloneNode(true);
		clone.setAttribute("id",file_index);
		clone.setAttribute("name",file_index);
		clone.style.display="table-row";
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
		var file_clone=clone("file_row",file_index.value,"loan_file");
		var name_clone=clone("name_row",file_index.value,"name");
		var final_row =document.getElementById("final_row").cloneNode(false);
		table.appendChild(file_clone);
		table.appendChild(name_clone);
		table.appendChild(final_row);
		var total_files = document.getElementById("total_files");
		total_files.value=++total_files.value;
		//change the following using jquery if necessary
		var remove=document.getElementsByName("remove");
		for(var i=0; i < remove.length; i++)
		remove[i].style.display="inline";	
	}
</script>
@endsection