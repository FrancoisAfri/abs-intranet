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
                    <h3 class="box-title">Application Details</h3>
					<p>View Loan Application details:</p>
                </div>
				<!-- /.box-header 
				<li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li> -->
					<!-- form start <form class="form-horizontal" method="POST" action="/"  enctype="multipart/form-data">-->

				<form class="form-horizontal" method="POST" action="/loan/{{ $loan->id }}/approve"  enctype="multipart/form-data">
					<input type="hidden" name="file_index" id="file_index" value="1"/>
					<input type="hidden" name="total_files" id="total_files" value="1"/>
						{{ csrf_field() }}
						
				<div class="box-body">
					@if($showbtton === 1 && $approval_lvl === 2 && $dir_Approval_count === 0)
						<div class="callout callout-info">
							<h4>Two director approvals needed!</h4>

							<p>This loan application needs to be approved by two(2) directors, you can perform the first approval by clicking on the "Approve Application" button bellow.</p>
						</div>
					@elseif($showbtton === 1 && $approval_lvl === 2 && $dir_Approval_count > 0)
						<div class="callout callout-info">
							<h4>One more director approval needed!</h4>

							@if($can_do_sec_approval === 1)
								<p>This loan application needs to be approved by one(1) more director, you can perform the second approval by clicking on the "Approve Application" button bellow.</p>
							@else
								<p>This loan application needs to be approved by another director.</p>
							@endif
						</div>
					@endif
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
				</ul>
				@if ($loan->id > 0)
				<div class="tab-content">
				  <div class="tab-pane active" id="tab_1">
					<div class="form-group">
					  <label for="applicant_type" class="col-sm-3 control-label">Applicant Type</label>
					  <div class="col-sm-9">
					  {{ !empty($loan->applicant_type) ? $applicationtype[$loan->applicant_type]  : '' }}
					  </div>
					</div>
					<div class="form-group">
						<label for="amount_wanted" class="col-sm-3 control-label">Amount Wanted</label>
						R {{ !empty($loan->amount_wanted) ? number_format($loan->amount_wanted, 2) : '' }}
                 </div>
				 <div class="form-group">
					  <label for="loan_period" class="col-sm-3 control-label">Loan Repayment period</label>
					  <div class="col-sm-9">
					 {{ !empty($loan->loan_period) ? $period[$loan->loan_period]  : '' }}
					  </div>
					</div>
					<div class="form-group"><p class="lead"><b>Company Information</b></p></div>
					<div class="form-group">
						<label for="company_name" class="col-sm-3 control-label">Applicant</label>
						  <div class="col-sm-9">
						   {{ !empty($loan->loanCompany->company_name) ? $loan->loanCompany->company_name : '' }}
					</div>
                 </div>
				<div class="form-group">
					<label for="trading_as" class="col-sm-3 control-label">Trading as</label>
					<div class="col-sm-9">
						 {{ !empty($loan->loanCompany->trading_as) ? $loan->loanCompany->trading_as : '' }}
					</div>
				</div>
                        <div class="form-group">
                            <label for="vat_number" class="col-sm-3 control-label">VAT Number</label>
                            <div class="col-sm-9">
								{{ !empty($loan->loanCompany->vat_number) ? $loan->loanCompany->vat_number : '' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="income_tax_number" class="col-sm-3 control-label">Income Tax Number</label>
                            <div class="col-sm-9">
								{{ !empty($loan->income_tax_number) ? $loan->income_tax_number : '' }}
                            </div>
                        </div>
						<div class="form-group">
                            <label for="contact_person" class="col-sm-3 control-label">Contact person</label>
                            <div class="col-sm-9">
								{{ !empty($loan->loanCompany->contact_person) ? $loan->loanCompany->contact_person : '' }}
                            </div>
                        </div>
						 <div class="form-group">
                            <label for="cell_number" class="col-sm-3 control-label">Cell Number</label>
                            <div class="col-sm-9">
							{{ !empty($loan->loanCompany->cell_number) ? $loan->loanCompany->cell_number : '' }}
                            </div>
                        </div>
						 <div class="form-group">
                            <label for="work_number" class="col-sm-3 control-label">Work Number</label>
                            <div class="col-sm-9">
							{{ !empty($loan->loanCompany->work_number) ? $loan->loanCompany->work_number : '' }}
                            </div>
                        </div>
						 <div class="form-group">
                            <label for="home_number" class="col-sm-3 control-label">Home Number</label>
                            <div class="col-sm-9">
							{{ !empty($loan->home_number) ? $loan->home_number : '' }}
                            </div>
                        </div>
						 <div class="form-group">
                            <label for="fax_number" class="col-sm-3 control-label">Fax Number</label>
                            <div class="col-sm-9">
							{{ !empty($loan->loanCompany->fax_number) ? $loan->loanCompany->fax_number : '' }}
                            </div>
                        </div>
						<div class="form-group">
                            <label for="email" class="col-sm-3 control-label">Email</label>
                            <div class="col-sm-9">
							{{ !empty($loan->loanCompany->email) ? $loan->loanCompany->email : '' }}
                            </div>
                        </div>
						<div class="form-group">
							<label for="physical_address" class="col-sm-3 control-label">Physical Address</label>
							<div class="col-sm-9">
							{{ !empty($loan->loanCompany->physical_address) ? $loan->loanCompany->physical_address : '' }}
							</div>
						</div>
						<div class="form-group">
							<label for="postal_address" class="col-sm-3 control-label">Postal Address</label>
							<div class="col-sm-9">
							{{ !empty($loan->loanCompany->postal_address) ? $loan->loanCompany->postal_address : '' }}
							</div>
						</div>
						<div class="form-group"><p class="lead"><b>Accountant / auditor / accounting officer</b></p></div>
					<div class="form-group">
                    <label for="accountant_name" class="col-sm-3 control-label">Name</label>
                      <div class="col-sm-9">
					  {{ !empty($loan->loanCompany->accountant_name) ? $loan->loanCompany->accountant_name : '' }}
					</div>
                 </div>
				<div class="form-group">
					<label for="account_Address" class="col-sm-3 control-label">Address</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanCompany->account_Address) ? $loan->loanCompany->account_Address : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="accountant_telephone" class="col-sm-3 control-label">Telephone</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanCompany->accountant_telephone) ? $loan->loanCompany->accountant_telephone : '' }}
					</div>
				</div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
			  <div class="form-group"><p class="lead"><b>Details of individual, surety, owner, partners, directors or members of CC</b></p></div>
                <div class="form-group">
					<label for="ind_name" class="col-sm-3 control-label">Name</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanContacts->ind_name) ? $loan->loanContacts->ind_name : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="ind_id_no" class="col-sm-3 control-label">ID No</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanContacts->ind_id_no) ? $loan->loanContacts->ind_id_no : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="marital_status" class="col-sm-3 control-label">Marital Status</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanContacts->marital_status) ? $loan->loanContacts->marital_status : '' }}
					</div>
				</div>
				 <div class="form-group">
					<label for="how_married" class="col-sm-3 control-label">How married</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanContacts->how_married) ? $howmarried[$loan->loanContacts->how_married] : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="residential_address" class="col-sm-3 control-label">Residential Address</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanContacts->residential_address) ? $loan->loanContacts->residential_address : '' }}
					</div>
				</div>
				 <div class="form-group">
					<label for="spouse_name" class="col-sm-3 control-label">Spouse Name</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanContacts->spouse_name) ? $loan->loanContacts->spouse_name : '' }}
					</div>
				</div>
				 <div class="form-group">
					<label for="spouse_id" class="col-sm-3 control-label">Spouse ID NO</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanContacts->spouse_id) ? $loan->loanContacts->spouse_id : '' }}
					</div>
				</div>
				 <div class="form-group">
					<label for="number_dep" class="col-sm-3 control-label">Number of Dependents</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanContacts->number_dep) ? $loan->loanContacts->number_dep : '' }}
					</div>
				</div>
			<div class="form-group">
					<p class="lead"><b>Second</b></p>
			</div>
				<div class="form-group">
					<label for="ind_name2" class="col-sm-3 control-label">Name</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanContacts->ind_name2) ? $loan->loanContacts->ind_name2 : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="ind_id_no2" class="col-sm-3 control-label">ID No</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanContacts->ind_id_no2) ? $loan->loanContacts->ind_id_no2 : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="marital_status2" class="col-sm-3 control-label">Marital Status</label>

					<div class="col-sm-9">
					{{ !empty($loan->loanContacts->marital_status2) ? $loan->loanContacts->marital_status2 : '' }}
					</div>
				</div>
				 <div class="form-group">
					<label for="how_married2" class="col-sm-3 control-label">How married</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanContacts->how_married2) ? $howmarried[$loan->loanContacts->how_married2] : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="residential_address2" class="col-sm-3 control-label">Residential Address</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanContacts->residential_address2) ? $loan->loanContacts->residential_address2 : '' }}
					</div>
				</div>
				 <div class="form-group">
					<label for="spouse_name2" class="col-sm-3 control-label">Spouse Name</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanContacts->spouse_name2) ? $loan->loanContacts->spouse_name2 : '' }}
					</div>
				</div>
				 <div class="form-group">
					<label for="spouse_id2" class="col-sm-3 control-label">Spouse ID NO</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanContacts->spouse_id2) ? $loan->loanContacts->spouse_id2 : '' }}
					</div>
				</div>
				 <div class="form-group">
					<label for="number_dep2" class="col-sm-3 control-label">Number of Dependents</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanContacts->number_dep2) ? $loan->loanContacts->number_dep2 : '' }}
					</div>
				</div>
			<div class="form-group">
					<p class="lead"><b>Third</b></p>
				</div>
				<div class="form-group">
					<label for="ind_name3" class="col-sm-3 control-label">Name</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanContacts->ind_name3) ? $loan->loanContacts->ind_name3 : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="ind_id_no3" class="col-sm-3 control-label">ID No</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanContacts->ind_id_no3) ? $loan->loanContacts->ind_id_no3 : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="marital_status3" class="col-sm-3 control-label">Marital Status</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanContacts->marital_status3) ? $loan->loanContacts->marital_status3 : '' }}
					</div>
				</div>
				 <div class="form-group">
					<label for="how_married3" class="col-sm-3 control-label">How married</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanContacts->how_married3) ? $howmarried[$loan->loanContacts->how_married3] : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="residential_address3" class="col-sm-3 control-label">Residential Address</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanContacts->residential_address3) ? $loan->loanContacts->residential_address3 : '' }}
					</div>
				</div>
				 <div class="form-group">
					<label for="spouse_name3" class="col-sm-3 control-label">Spouse Name</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanContacts->spouse_name3) ? $loan->loanContacts->spouse_name3 : '' }}
					</div>
				</div>
				 <div class="form-group">
					<label for="spouse_id3" class="col-sm-3 control-label">Spouse ID NO</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanContacts->spouse_id3) ? $loan->loanContacts->spouse_id3 : '' }}
					</div>
				</div>
				 <div class="form-group">
					<label for="number_dep3" class="col-sm-3 control-label">Number of Dependents</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanContacts->number_dep3) ? $loan->loanContacts->number_dep3 : '' }}
					</div>
				</div>
              </div>         
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3">
			  <div class="form-group"><p class="lead"><b>Employment history (Individual application / Surety)</b></p></div>
			  <div class="form-group">
					<label for="employer" class="col-sm-3 control-label">Employer</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanHistory->employer) ? $loan->loanHistory->employer : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="emp_address" class="col-sm-3 control-label">Address</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanHistory->emp_address) ? $loan->loanHistory->emp_address : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="emp_tel" class="col-sm-3 control-label">Telephone</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanHistory->emp_tel) ? $loan->loanHistory->emp_tel : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="emp_years" class="col-sm-3 control-label">No. of years</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanHistory->emp_years) ? $loan->loanHistory->emp_years : '' }}
					</div>
				</div>
				 <div class="form-group">
					<label for="spouse_emp" class="col-sm-3 control-label">Spouses employer</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanHistory->spouse_emp) ? $loan->loanHistory->spouse_emp : '' }}
					</div>
				</div>
				 <div class="form-group">
					<label for="spouse_emp_addr" class="col-sm-3 control-label">Address</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanHistory->spouse_emp_addr) ? $loan->loanHistory->spouse_emp_addr : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="spouse_emp_tel" class="col-sm-3 control-label">Telephone</label>
					<div class="col-sm-9">
					 {{ !empty($loan->loanHistory->spouse_emp_tel) ? $loan->loanHistory->spouse_emp_tel : '' }}
					</div>
				</div>
				 <div class="form-group">
					<label for="spouse_emp_years" class="col-sm-3 control-label">No. of years</label>
					<div class="col-sm-9">
					 {{ !empty($loan->loanHistory->spouse_emp_years) ? $loan->loanHistory->spouse_emp_years : '' }}
					</div>
				</div>
				<div class="form-group"><p class="lead"><b>Nearest relative in South Africa not living with you</b></p></div>
				<div class="form-group">
					<label for="rel_Full" class="col-sm-3 control-label">Full Name</label>
					<div class="col-sm-9">
					 {{ !empty($loan->loanHistory->rel_Full) ? $loan->loanHistory->rel_Full : '' }}
					</div>
				</div>
				 <div class="form-group">
					<label for="rel_relation" class="col-sm-3 control-label">Relationship</label>
					<div class="col-sm-9">
					 {{ !empty($loan->loanHistory->rel_relation) ? $loan->loanHistory->rel_relation : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="rel_tel" class="col-sm-3 control-label">Telephone</label>
					<div class="col-sm-9">
					 {{ !empty($loan->loanHistory->rel_tel) ? $loan->loanHistory->rel_tel : '' }}
					</div>
				</div>
				 <div class="form-group">
					<label for="rel_address" class="col-sm-3 control-label">Address</label>
					<div class="col-sm-9">
					 {{ !empty($loan->loanHistory->rel_address) ? $loan->loanHistory->rel_address : '' }}
					</div>
				</div>
				<div class="form-group"><p class="lead"><b>Landlord’s details</b> (Name & address of landlord where goods will be kept) if applicable</p></div>
				
				<div class="form-group">
					<label for="landlord_name" class="col-sm-3 control-label">Name</label>
					<div class="col-sm-9">
					 {{ !empty($loan->loanHistory->landlord_name) ? $loan->loanHistory->landlord_name : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="landlord_tel" class="col-sm-3 control-label">Telephone</label>
					<div class="col-sm-9">
					 {{ !empty($loan->loanHistory->landlord_tel) ? $loan->loanHistory->landlord_tel : '' }}
					</div>
				</div>
				 <div class="form-group">
					<label for="landlord_address" class="col-sm-3 control-label">Address</label>
					<div class="col-sm-9">
					 {{ !empty($loan->loanHistory->landlord_address) ? $loan->loanHistory->landlord_address : '' }}
					</div>
				</div>
              </div>
			  <div class="tab-pane" id="tab_4">
               <div class="form-group"><p class="lead"><b>Home ownership</b></p></div>
				 <div class="form-group">
                  <label for="bond_type" class="col-sm-3 control-label">Do you own your property</label>
				  <div class="col-sm-9">
				  {{ !empty($loan->bond_type) ? $bondtype[$loan->bond_type] : '' }}
				  </div>
                </div>
				<div class="form-group">
                  <label for="property_type" class="col-sm-3 control-label">Property Type</label>
				  <div class="col-sm-9">
				  {{ !empty($loan->property_type) ? $propertytype[$loan->property_type] : '' }}
				  </div>
                </div>
				 <div class="form-group">
					<label for="property_address" class="col-sm-3 control-label">Property Address</label>
					<div class="col-sm-9">
					{{ !empty($loan->property_address) ? $loan->property_address : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="bond_month" class="col-sm-3 control-label">Bond / rental per month</label>
					<div class="col-sm-9">
					{{ !empty($loan->bond_month ) ? $loan->bond_month  : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="bond_outs" class="col-sm-3 control-label">Bond amount outstanding</label>
					<div class="col-sm-9">
					{{ !empty($loan->bond_outs) ? $loan->bond_outs : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="current_value" class="col-sm-3 control-label">Current value</label>
					<div class="col-sm-9">
					{{ !empty($loan->current_value) ? $loan->current_value : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="purchase_price" class="col-sm-3 control-label">Purchase price</label>
					<div class="col-sm-9">
						{{ !empty($loan->purchase_price) ? $loan->purchase_price : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="bond_granted" class="col-sm-3 control-label">If access bond, total facility granted</label>
					<div class="col-sm-9">
						{{ !empty($loan->bond_granted) ? $loan->bond_granted : '' }}
					</div>
				</div>
				<div class="form-group">
                  <label for="legal_atcion" class="col-sm-3 control-label">Are there any legal proceedings pending or expected against the firm,
					individual, surety, owner, partners, directors or members</label>
				  <div class="col-sm-9">
					{{ !empty($loan->legal_atcion) ? $yesno[$loan->legal_atcion] : '' }}
				  </div>
                </div>
				<div class="form-group">
					<label for="legal_details" class="col-sm-3 control-label">If yes provide details</label>
					<div class="col-sm-9">
						{{ !empty($loan->legal_details) ? $loan->legal_details : '' }}
					</div>
				</div>
				<div class="form-group">
                  <label for="judgement_atcion" class="col-sm-3 control-label">Are there any judgements against the firm, individual, surety, owner,
					partners, directors or members</label>
				  <div class="col-sm-9">
					{{ !empty($loan->judgement_atcion) ? $yesno[$loan->judgement_atcion] : '' }}
				  </div>
                </div>
				<div class="form-group">
					<label for="judgement_details" class="col-sm-3 control-label">If yes provide details</label>
					<div class="col-sm-9">
						{{ !empty($loan->judgement_details) ? $loan->judgement_details : '' }}
					</div>
				</div>
				<div class="form-group">
                  <label for="liquidation_atcion" class="col-sm-3 control-label">Has the firm, individual, surety owner, partners, directors or members, ever
					made a compromise with its creditors or been provisionally or finally
					liquidated or placed into judicial management.</label>
				  <div class="col-sm-9">
                  {{ !empty($loan->liquidation_atcion) ? $yesno[$loan->liquidation_atcion] : '' }}
				  </div>
                </div>
				<div class="form-group">
					<label for="liquidation_details" class="col-sm-3 control-label">If yes provide details</label>
					<div class="col-sm-9">
						{{ !empty($loan->liquidation_details) ? $loan->liquidation_details : '' }}
					</div>
				</div>
				<div class="form-group">
                  <label for="debt_review" class="col-sm-3 control-label">Is the firm, individual, surety, owner, partners, directors or members,
					currently under or have applied for debt review, or have a dispute in progress
					with a credit bureau, or have any re-arrangement in place with a credit
					provider as a result of debt counselling.</label>
				  <div class="col-sm-9">
                  {{ !empty($loan->debt_review) ? $yesno[$loan->debt_review] : '' }}
				  </div>
                </div>
				<div class="form-group">
					<label for="debt_review_details" class="col-sm-3 control-label">If yes provide details</label>
					<div class="col-sm-9">
						{{ !empty($loan->debt_review_details) ? $loan->debt_review_details : '' }}
					</div>
				</div>
              </div>  
			  <div class="tab-pane" id="tab_5">
			   <div class="form-group"><p class="lead"><b>Existing and / or previous accounts with financial institutions</b></p></div>
               <div class="form-group">
					<label for="exis_inst" class="col-sm-3 control-label">Institution</label>

					<div class="col-sm-9">
						{{ !empty($loan->exis_inst) ? $loan->exis_inst : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="exis_branch" class="col-sm-3 control-label">Branch</label>

					<div class="col-sm-9">
						{{ !empty($loan->exis_branch) ? $loan->exis_branch : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="exis_acc" class="col-sm-3 control-label">Account No.</label>

					<div class="col-sm-9">
						{{ !empty($loan->exis_acc) ? $loan->exis_acc : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="exis_amount" class="col-sm-3 control-label">Amount</label>
					<div class="col-sm-9">
					{{ !empty($loan->exis_amount) ? $loan->exis_amount : '' }}
					</div>
				</div>
				 <div class="form-group">
					<label for="exis_curr" class="col-sm-3 control-label">Current / paid up</label>

					<div class="col-sm-9">
						{{ !empty($loan->exis_curr) ? $loan->exis_curr : '' }}
					</div>
				</div> 
				<div class="form-group">
					<label for="exis_inst1" class="col-sm-3 control-label">Institution</label>

					<div class="col-sm-9">
						{{ !empty($loan->exis_inst1) ? $loan->exis_inst1 : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="exis_branch1" class="col-sm-3 control-label">Branch</label>

					<div class="col-sm-9">
						{{ !empty($loan->exis_branch1) ? $loan->exis_branch1 : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="exis_acc1" class="col-sm-3 control-label">Account No.</label>

					<div class="col-sm-9">
						{{ !empty($loan->exis_acc1) ? $loan->exis_acc1 : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="exis_amount1" class="col-sm-3 control-label">Amount</label>
					<div class="col-sm-9">
					{{ !empty($loan->exis_amount1) ? $loan->exis_amount1 : '' }}
					</div>
				</div>
				 <div class="form-group">
					<label for="exis_curr1" class="col-sm-3 control-label">Current / paid up</label>

					<div class="col-sm-9">
						{{ !empty($loan->exis_curr1) ? $loan->exis_curr1 : '' }}
					</div>
				</div>
				<!-- Bankers -->
				 <div class="form-group"><p class="lead"><b>Bankers</b></p></div>
				 <div class="form-group">
					<label for="bank_inst" class="col-sm-3 control-label">Bank</label>

					<div class="col-sm-9">
						{{ !empty($loan->bank_inst) ? $loan->bank_inst : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="bank_branch" class="col-sm-3 control-label">Branch</label>

					<div class="col-sm-9">
						{{ !empty($loan->bank_branch) ? $loan->bank_branch : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="bank_acc_type" class="col-sm-3 control-label">Account type</label>
					<div class="col-sm-9">
					{{ !empty($loan->bank_acc_type) ? $loan->bank_acc_type : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="bank_acc" class="col-sm-3 control-label">Account No.</label>

					<div class="col-sm-9">
						{{ !empty($loan->bank_acc) ? $loan->bank_acc : '' }}
					</div>
				</div>
				 <div class="form-group">
					<label for="bank_acc_name" class="col-sm-3 control-label">Account name</label>

					<div class="col-sm-9">
						{{ !empty($loan->bank_acc_name) ? $loan->bank_acc_name : '' }}
					</div>
				</div> 
				<div class="form-group">
					<label for="bank_inst1" class="col-sm-3 control-label">Bank</label>

					<div class="col-sm-9">
						{{ !empty($loan->bank_inst1) ? $loan->bank_inst1 : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="bank_branch1" class="col-sm-3 control-label">Branch</label>

					<div class="col-sm-9">
						{{ !empty($loan->bank_branch1) ? $loan->bank_branch1 : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="bank_acc_type1" class="col-sm-3 control-label">Account type</label>
					<div class="col-sm-9">
					{{ !empty($loan->bank_acc_type1) ? $loan->bank_acc_type1 : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="bank_acc1" class="col-sm-3 control-label">Account No.</label>

					<div class="col-sm-9">
						{{ !empty($loan->bank_acc1) ? $loan->bank_acc1 : '' }}
					</div>
				</div>
				 <div class="form-group">
					<label for="bank_acc_name1" class="col-sm-3 control-label">Account name</label>

					<div class="col-sm-9">
						{{ !empty($loan->bank_acc_name1) ? $loan->bank_acc_name1 : '' }}
					</div>
				</div>
				<!-- Asset -->
				<div class="form-group"><p class="lead"><b>Asset</b></p></div>
				 <div class="form-group">
					<label for="asset_desc" class="col-sm-3 control-label">Description of vehicle or asset</label>

					<div class="col-sm-9">
						{{ !empty($loan->loanAssets->asset_desc) ? $loan->loanAssets->asset_desc : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="asset_state" class="col-sm-3 control-label">State</label>
					 <div class="col-sm-9">
					  {{ !empty($loan->loanAssets->asset_state) ? $assetstate[$loan->loanAssets->asset_state] : '' }}
					  </div>
				</div>
				<div class="form-group">
					<label for="asset_model" class="col-sm-3 control-label">Model year</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanAssets->asset_model) ? $loan->loanAssets->asset_model : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="asset_maf_date" class="col-sm-3 control-label">Manufacture date</label>
					<div class="col-sm-9">
						<div>
							{{ !empty($loan->loanAssets->asset_maf_date) ? date('Y M d', $loan->loanAssets->asset_maf_date) : '' }}
						</div>
                    </div>
				</div>
				 <div class="form-group">
					<label for="asset_supplier" class="col-sm-3 control-label">Supplier</label>

					<div class="col-sm-9">
						{{ !empty($loan->loanAssets->asset_supplier) ? $loan->loanAssets->asset_supplier : '' }}
					</div>
				</div>  
				<div class="form-group">
					<label for="asset_supplier_contact" class="col-sm-3 control-label">Supplier contact</label>

					<div class="col-sm-9">
						{{ !empty($loan->loanAssets->asset_supplier_contact) ? $loan->loanAssets->asset_supplier_contact : '' }}
					</div>
				</div>  
				<div class="form-group">
					<label for="asset_supplier_tel" class="col-sm-3 control-label">Telephone No.</label>

					<div class="col-sm-9">
						{{ !empty($loan->loanAssets->asset_supplier_tel) ? $loan->loanAssets->asset_supplier_tel : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="asset_cash_price" class="col-sm-3 control-label">Cash Price</label>

					<div class="col-sm-9">
						{{ !empty($loan->loanAssets->asset_cash_price) ? $loan->loanAssets->asset_cash_price : '' }}
					</div>
				</div> 
				<div class="form-group">
					<label for="asset_extras" class="col-sm-3 control-label">Extras</label>

					<div class="col-sm-9">
						{{ !empty($loan->loanAssets->asset_extras) ? $loan->loanAssets->asset_extras : '' }}
					</div>
				</div> <div class="form-group">
					<label for="asset_extras1" class="col-sm-3 control-label">Extras</label>

					<div class="col-sm-9">
						{{ !empty($loan->loanAssets->asset_extras1) ? $loan->loanAssets->asset_extras1 : '' }}
					</div>
				</div> <div class="form-group">
					<label for="asset_extras2" class="col-sm-3 control-label">Extras</label>

					<div class="col-sm-9">
						{{ !empty($loan->loanAssets->asset_extras2) ? $loan->loanAssets->asset_extras2 : '' }}
					</div>
				</div> <div class="form-group">
					<label for="asset_total_extras" class="col-sm-3 control-label">Total extras</label>

					<div class="col-sm-9">
						{{ !empty($loan->loanAssets->asset_total_extras) ? $loan->loanAssets->asset_total_extras : '' }}
					</div>
				</div> <div class="form-group">
					<label for="asset_sub_total" class="col-sm-3 control-label">Sub total</label>

					<div class="col-sm-9">
						{{ !empty($loan->loanAssets->asset_sub_total) ? $loan->loanAssets->asset_sub_total : '' }}
					</div>
				</div> <div class="form-group">
					<label for="asset_deposit" class="col-sm-3 control-label">Deposit</label>

					<div class="col-sm-9">
						{{ !empty($loan->loanAssets->asset_deposit) ? $loan->loanAssets->asset_deposit : '' }}
					</div>
				</div> <div class="form-group">
					<label for="asset_finance" class="col-sm-3 control-label">Finance required</label>

					<div class="col-sm-9">
						{{ !empty($loan->loanAssets->asset_finance) ? $loan->loanAssets->asset_finance : '' }}
					</div>
				</div> 
				<div class="form-group">
					<label for="asset_period" class="col-sm-3 control-label">Period of contract</label>

					<div class="col-sm-9">
						{{ !empty($loan->loanAssets->asset_period) ? $loan->loanAssets->asset_period : '' }}
					</div>
				</div> 
              </div>  
			  <div class="tab-pane" id="tab_6">
			  <!-- Insurance -->
				<div class="form-group"><p class="lead"><b>Insurance</b></p></div>
				<div class="form-group">
					<label for="exsiting_policy" class="col-sm-3 control-label">Existing policy</label>
					 <div class="col-sm-9">
					  {{ !empty($loan->loanInsurance->exsiting_policy) ? $yesno[$loan->loanInsurance->exsiting_policy] : '' }}
					  </div>
				</div>
				<div class="form-group">
					<label for="polocy_no" class="col-sm-3 control-label">Policy no.</label>

					<div class="col-sm-9">
						{{ !empty($loan->loanInsurance->polocy_no) ? $loan->loanInsurance->polocy_no : '' }}
					</div>
				</div> 
				<div class="form-group">
					<label for="renewal_date" class="col-sm-3 control-label">Renewal date</label>
					<div class="col-sm-9">
						<div>
							{{ !empty($loan->loanInsurance->renewal_date) ? date('Y M d', $loan->loanInsurance->renewal_date) : '' }}
						</div>
                    </div>
				</div> 
				<div class="form-group">
					<label for="annual_premium" class="col-sm-3 control-label">Annual premium</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanInsurance->annual_premium) ? $loan->loanInsurance->annual_premium : '' }}
					</div>
				</div> 
				<div class="form-group">
					<label for="monthly_premium" class="col-sm-3 control-label">Monthly premium</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanInsurance->monthly_premium) ? $loan->loanInsurance->monthly_premium : '' }}
					</div>
				</div> 
				<div class="form-group"><p class="lead"><b>Additional Securities</b></p></div>
				<div class="form-group">
					<label for="type_security" class="col-sm-3 control-label">Type of security</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanInsurance->type_security) ? $loan->loanInsurance->type_security : '' }}
					</div>
				</div> 
				<div class="form-group">
					<label for="value_security" class="col-sm-3 control-label">Value of security</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanInsurance->value_security) ? $loan->loanInsurance->value_security : '' }}
					</div>
				</div>
              </div> 
			  <div class="tab-pane" id="tab_7">
                <div class="form-group">
					<label for="statement_liability" class="col-sm-3 control-label">Statement of Assets & Liabilities of</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanInsurance->statement_liability) ? $loan->loanInsurance->statement_liability : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="statement_as_at" class="col-sm-3 control-label">As at</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanInsurance->statement_as_at) ? $loan->loanInsurance->statement_as_at : '' }}		
					</div>
				</div>
				<div class="form-group">
					<label for="fixed_property_cost" class="col-sm-3 control-label">Fixed property – at cost plus improvements</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanInsurance->fixed_property_cost) ? $loan->loanInsurance->fixed_property_cost : '' }}	
					</div>
				</div>
				<div class="form-group">
					<label for="member_items" class="col-sm-3 control-label">Shares, Member’s Interest & Debentures – Itemise (use separate
sheet if necessary)</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanInsurance->member_items) ? $loan->loanInsurance->member_items : '' }}		
					</div>
				</div>
				<div class="form-group">
					<label for="motor_vehicle_owned" class="col-sm-3 control-label">Motor vehicle – only if owned – not leased</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanInsurance->motor_vehicle_owned) ? $loan->loanInsurance->motor_vehicle_owned : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="loans_receivable" class="col-sm-3 control-label">Loans receivable</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanInsurance->loans_receivable) ? $loan->loanInsurance->loans_receivable : '' }}		
					</div>
				</div>
				<div class="form-group">
					<label for="net_capital" class="col-sm-3 control-label">Net capital of business or profession</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanInsurance->net_capital) ? $loan->loanInsurance->net_capital : '' }}		
					</div>
				</div>
				<div class="form-group">
					<label for="debtors" class="col-sm-3 control-label">Debtors</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanInsurance->debtors) ? $loan->loanInsurance->debtors : '' }}	
					</div>
				</div>
				<div class="form-group">
					<label for="cash_on_hand" class="col-sm-3 control-label">Cash – on hand</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanInsurance->cash_on_hand) ? $loan->loanInsurance->cash_on_hand : '' }}	
					</div>
				</div>
				<div class="form-group">
					<label for="cash_at_bank" class="col-sm-3 control-label">Cash - at bank</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanInsurance->cash_at_bank) ? $loan->loanInsurance->cash_at_bank : '' }}	
					</div>
				</div>
				<div class="form-group">
					<label for="surrender_value" class="col-sm-3 control-label">Surrender value on insurance policies – attach latest printouts</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanInsurance->surrender_value) ? $loan->loanInsurance->surrender_value : '' }}		
					</div>
				</div>
				<div class="form-group">
					<label for="personal_effects" class="col-sm-3 control-label">Personal effects – eg. Furniture jewellery, etc</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanInsurance->personal_effects) ? $loan->loanInsurance->personal_effects : '' }}		
					</div>
				</div>
				<div class="form-group">
					<label for="other_assets" class="col-sm-3 control-label">Other assets (specify)</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanInsurance->other_assets) ? $loan->loanInsurance->other_assets : '' }}		
					</div>
				</div>
				<div class="form-group">
					<label for="total_assets" class="col-sm-3 control-label">Total assets</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanInsurance->total_assets) ? $loan->loanInsurance->total_assets : '' }}		
					</div>
				</div>
              </div>  
			  <div class="tab-pane" id="tab_8">
				<div class="form-group"><p class="lead"><b>Liabilities</b></p></div>
              <div class="form-group">
					<label for="liabilities_bond" class="col-sm-3 control-label">Bonds</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanLiabilities->liabilities_bond) ? $loan->loanLiabilities->liabilities_bond : '' }}		
					</div>
				</div>
					<div class="form-group">
						<label for="loan_payable" class="col-sm-3 control-label">Loans payable</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanLiabilities->loan_payable) ? $loan->loanLiabilities->loan_payable : '' }}			
						</div>
					</div>
				<div class="form-group">
					<label for="outs_bal_mot" class="col-sm-3 control-label">Outstanding balance on motor vehicles</label>
					<div class="col-sm-9">
						{{ !empty($loan->outs_bal_mot) ? $loan->loanLiabilities->outs_bal_mot : '' }}		
					</div>
				</div>
				<div class="form-group">
					<label for="liabilities_motor" class="col-sm-3 control-label">Liabilities on motor vehicle & equipment leases</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanLiabilities->liabilities_motor) ? $loan->loanLiabilities->liabilities_motor : '' }}		
					</div>
				</div>
				<div class="form-group">
					<label for="fur_outs_amt" class="col-sm-3 control-label">Furniture outstanding amounts</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanLiabilities->fur_outs_amt) ? $loan->loanLiabilities->fur_outs_amt : '' }}		
					</div>
				</div>
				<div class="form-group">
					<label for="loan_ins_pol" class="col-sm-3 control-label">Loans on insurance policies</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanLiabilities->loan_ins_pol) ? $loan->loanLiabilities->loan_ins_pol : '' }}		
					</div>
				</div>
				<div class="form-group">
					<label for="lia_inc_tax" class="col-sm-3 control-label">Liability for income tax</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanLiabilities->lia_inc_tax) ? $loan->loanLiabilities->lia_inc_tax : '' }}		
					</div>
				</div>
				<div class="form-group">
					<label for="bank_overdraft" class="col-sm-3 control-label">Bank overdraft</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanLiabilities->bank_overdraft) ? $loan->loanLiabilities->bank_overdraft : '' }}		
					</div>
				</div>
				<div class="form-group">
					<label for="crd_inst_limit" class="col-sm-3 control-label">Credit card - Institution and Card limit</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanLiabilities->crd_inst_limit) ? $loan->loanLiabilities->crd_inst_limit : '' }}			
						</div>
				</div>
				<div class="form-group">
					<label for="other_liabilities" class="col-sm-3 control-label">Other liabilities</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanLiabilities->other_liabilities) ? $loan->loanLiabilities->other_liabilities : '' }}			
						</div>
				</div>
				<div class="form-group">
					<label for="total_liability" class="col-sm-3 control-label">Total liabilities</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanLiabilities->total_liability) ? $loan->loanLiabilities->total_liability : '' }}		
					</div>
				</div>
				<div class="form-group">
					<label for="net_asset" class="col-sm-3 control-label">Net Assets (Total assets - Total liabilities)</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanLiabilities->net_asset) ? $loan->loanLiabilities->net_asset : '' }}		
					</div>
				</div>
				<div class="form-group"><p class="lead"><b>Contingent Liabilities</b> as Guarantor, Surety or otherwise</p></div>
				<div class="form-group">
					<label for="in_fav_of" class="col-sm-3 control-label">In favour of whom / Which institution / Amount</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanLiabilities->in_fav_of) ? $loan->loanLiabilities->in_fav_of : '' }}		
				</div>
				</div>
				<div class="form-group">
					<label for="total_cont_lia" class="col-sm-3 control-label">Total Contingent Liabilities</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanLiabilities->total_cont_lia) ? $loan->loanLiabilities->total_cont_lia : '' }}		
					</div>
				</div>
              </div>
			  <div class="tab-pane" id="tab_9">
			  <div class="form-group"><p class="lead"><b>Schedule of Income & Expenses</b></p></div>
               <div class="form-group">
					<label for="schedule_inc_exp" class="col-sm-3 control-label">Schedule of Income & Expenses of</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanIncome->schedule_inc_exp) ? $loan->loanIncome->schedule_inc_exp : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="schedule_as_at" class="col-sm-3 control-label">As at</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanIncome->schedule_as_at) ? $loan->loanIncome->schedule_as_at : '' }}		
					</div>
				</div>
				<!-- Income -->
				 <div class="form-group"><p class="lead"><b>Income</b></p></div>
				  <div class="form-group">
					<label for="gross_salary" class="col-sm-3 control-label">Gross salary</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanIncome->gross_salary) ? $loan->loanIncome->gross_salary : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="deductions" class="col-sm-3 control-label">Deductions</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanIncome->deductions) ? $loan->loanIncome->deductions : '' }}		
					</div>
				</div>
				 <div class="form-group">
					<label for="salary_tax" class="col-sm-3 control-label">Tax</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanIncome->salary_tax) ? $loan->loanIncome->salary_tax : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="salary_medical" class="col-sm-3 control-label">Medical Aid</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanIncome->salary_medical) ? $loan->loanIncome->salary_medical : '' }}		
					</div>
				</div>
				 <div class="form-group">
					<label for="salary_pension" class="col-sm-3 control-label">Pension</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanIncome->salary_pension) ? $loan->loanIncome->salary_pension : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="salary_uif" class="col-sm-3 control-label">UIF</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanIncome->salary_uif) ? $loan->loanIncome->salary_uif : '' }}		
					</div>
				</div>
				 <div class="form-group">
					<label for="salary_other" class="col-sm-3 control-label">Other</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanIncome->salary_other) ? $loan->loanIncome->salary_other : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="salary_tot_ded" class="col-sm-3 control-label">Total deductions</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanIncome->salary_tot_ded) ? $loan->loanIncome->salary_tot_ded : '' }}		
					</div>
				</div>
				 <div class="form-group">
					<label for="salary_other_inc" class="col-sm-3 control-label">Other Income - Specify</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanIncome->salary_other_inc) ? $loan->loanIncome->salary_other_inc : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="total_income" class="col-sm-3 control-label">Total Income</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanIncome->total_income) ? $loan->loanIncome->total_income : '' }}		
					</div>
				</div>
				<!-- Monthly Commitments & Expenses -->
				<div class="form-group"><p class="lead"><b>Monthly Commitments & Expenses</b></p></div>
				 <div class="form-group">
					<label for="com_rent_bond" class="col-sm-3 control-label">Rent / Bond</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanIncome->com_rent_bond) ? $loan->loanIncome->com_rent_bond : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="com_hire" class="col-sm-3 control-label">Hire Purchase instalments & Lease agreements</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanIncome->com_hire) ? $loan->loanIncome->com_hire : '' }}	
					</div>
				</div>
				 <div class="form-group">
					<label for="com_loan_repay" class="col-sm-3 control-label">Loan Repayment</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanIncome->com_loan_repay) ? $loan->loanIncome->com_loan_repay : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="com_ins_pre" class="col-sm-3 control-label">Insurance premiums</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanIncome->com_ins_pre) ? $loan->loanIncome->com_ins_pre : '' }}		
					</div>
				</div>
				 <div class="form-group">
					<label for="com_life_ins" class="col-sm-3 control-label">Life assurance premiums</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanIncome->com_life_ins) ? $loan->loanIncome->com_life_ins : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="com_elec_water" class="col-sm-3 control-label">Electricity & water</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanIncome->com_elec_water) ? $loan->loanIncome->com_elec_water : '' }}		
					</div>
				</div>
				 <div class="form-group">
					<label for="com_rates_tax" class="col-sm-3 control-label">Rates & taxes</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanIncome->com_rates_tax) ? $loan->loanIncome->com_rates_tax : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="com_tel" class="col-sm-3 control-label">Telephones including rentals & cellphones</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanIncome->com_tel) ? $loan->loanIncome->com_tel : '' }}		
					</div>
				</div>
				 <div class="form-group">
					<label for="com_maintenance" class="col-sm-3 control-label">Alimony / Maintenance</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanIncome->com_maintenance) ? $loan->loanIncome->com_maintenance : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="com_plan_sav" class="col-sm-3 control-label">Planned savings</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanIncome->com_plan_sav) ? $loan->loanIncome->com_plan_sav : '' }}	
					</div>
				</div>
				 <div class="form-group">
					<label for="com_crd_amt" class="col-sm-3 control-label">Credit card accounts</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanIncome->com_crd_amt) ? $loan->loanIncome->com_crd_amt : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="com_donations" class="col-sm-3 control-label">Donations</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanIncome->com_donations) ? $loan->loanIncome->com_donations : '' }}		
					</div>
				</div>
				 <div class="form-group">
					<label for="com_edu" class="col-sm-3 control-label">Education – Fees, books, etc</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanIncome->com_edu) ? $loan->loanIncome->com_edu : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="com_children" class="col-sm-3 control-label">Children’s clothing</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanIncome->com_children) ? $loan->loanIncome->com_children : '' }}		
					</div>
				</div>
				 <div class="form-group">
					<label for="com_groceries" class="col-sm-3 control-label">Groceries</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanIncome->com_groceries) ? $loan->loanIncome->com_groceries : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="com_cloh_acc" class="col-sm-3 control-label">Clothing accounts</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanIncome->com_cloh_acc) ? $loan->loanIncome->com_cloh_acc : '' }}		
					</div>
				</div>
				 <div class="form-group">
					<label for="com_doc_den" class="col-sm-3 control-label">Doctor / Chemist</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanIncome->com_doc_den) ? $loan->loanIncome->com_doc_den : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="com_domes" class="col-sm-3 control-label">Domestic / Garden help</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanIncome->com_domes) ? $loan->loanIncome->com_domes : '' }}		
					</div>
				</div>
				 <div class="form-group">
					<label for="com_sec_sys" class="col-sm-3 control-label">Security system</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanIncome->com_sec_sys) ? $loan->loanIncome->com_sec_sys : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="com_tran" class="col-sm-3 control-label">Transport</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanIncome->com_tran) ? $loan->loanIncome->com_tran : '' }}		
					</div>
				</div>
				 <div class="form-group">
					<label for="com_enter" class="col-sm-3 control-label">Entertainment</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanIncome->com_enter) ? $loan->loanIncome->com_enter : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="com_dstv" class="col-sm-3 control-label">TV rental / DSTV etc</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanIncome->com_dstv) ? $loan->loanIncome->com_dstv : '' }}		
					</div>
				</div>
				<div class="form-group">
					<label for="com_other" class="col-sm-3 control-label">Other (specify)</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanIncome->com_other) ? $loan->loanIncome->com_other : '' }}			
					</div>
				</div>
				<div class="form-group">
					<label for="com_total" class="col-sm-3 control-label">Total Commitments and Expenses</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanIncome->com_total) ? $loan->loanIncome->com_total : '' }}		
					</div>
				</div>
				 <div class="form-group">
					<label for="surplus_available" class="col-sm-3 control-label">Surplus available</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanIncome->surplus_available) ? number_format($loan->loanIncome->surplus_available, 2) : '' }}
					</div>
				</div>
				<div class="form-group"><p class="lead"><b>Creditors</b></p></div>
			  <div class="form-group">
					<label for="debt_obl" class="col-sm-3 control-label">Debt Obligations of</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanIncome->debt_obl) ? $loan->loanIncome->debt_obl : '' }}
					</div>
				</div>
				<div class="form-group">
					<label for="debt_as_at" class="col-sm-3 control-label">As at</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanIncome->debt_as_at) ? $loan->loanIncome->debt_as_at : '' }}	
					</div>
					</div>
					<div class="form-group">
					<label for="name_creditors" class="col-sm-3 control-label">Name of creditor/Total amount
outstanding/Monthly commitment</label>
					<div class="col-sm-9">
					{{ !empty($loan->loanIncome->name_creditors) ? number_format($loan->loanIncome->name_creditors, 2)  : '' }}		
					</div>
					</div>
					<div class="form-group">
					<label for="total_cre_amount" class="col-sm-3 control-label">Total Creditors Amount</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanIncome->total_cre_amount) ? number_format($loan->loanIncome->total_cre_amount, 2) : '' }}	
					</div>
					</div>
					<div class="form-group">
					<label for="total_cmt_amount" class="col-sm-3 control-label">Total Monthly commitment</label>
					<div class="col-sm-9">
						{{ !empty($loan->loanIncome->total_cmt_amount) ? number_format($loan->loanIncome->total_cmt_amount, 2) : '' }}		
					</div>
					</div>
				  </div>
				  <div class="tab-pane" id="tab_10">
					<div class="form-group"><p class="lead"><b>Documents Uploaded</b></p></div>		  
					  @foreach($loan->loanDoc as $doc)
						<div class="col-sm-4">
							{{ $doc->name }}
						</div>
						<div class="col-sm-8">
							<a href="{{ Storage::disk('local')->url("loanDocs/$doc->file_name") }}" target=\"_blank\">Click Here</a>
						</div>
					  @endforeach
				  </div> 
              <!-- /.tab-pane -->
				</div>
				@endif
				<!-- /.tab-content -->
				</div>
			</div>
			<!-- /.box-body -->
			@if ($showbtton == 1)
				<div class="box-footer">
					<button type="button" class="btn btn-primary pull-left" id="reject_button" name="command" value="Reject" data-toggle="modal" data-target="#rejection-reason-modal">Reject Application</button>
					@if($approval_lvl === 1)
						<button type="submit" class="btn btn-primary pull-right" id="approve_button" name="command" value="Approve">Approve Application</button>
					@elseif($approval_lvl === 2)
						@if($dir_Approval_count === 0)
							<button type="button" class="btn btn-primary pull-right" id="approve_button" name="command" value="Approve" data-toggle="modal" data-target="#set-rates-modal" data-prime_rate="{{ (count($prime_rate) > 0 ) ? $prime_rate->prime_rate : '' }}">Approve Application</button>
						@elseif($dir_Approval_count > 0 && $can_do_sec_approval === 1)
							<button type="submit" class="btn btn-primary pull-right" id="approve_button" name="command" value="Approve">Approve Application</button>
						@endif
					@endif
					<button type="button" class="btn btn-primary center-block" id="edit_button" name="command" value="Edit">Edit Application</button>
				</div>
			@else
				<div class="box-footer">
				</div>
			@endif
				<!-- /.box-footer -->
			</form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
		<!-- Modals -->
		@if ($showbtton == 1)
			@include('loan.partials.rejection_reason')
			@include('loan.partials.set_rates')
		@endif
		@if (session('success_modal'))
			@include('loan.partials.success_action', ['modal_title' => 'Admin Approval - Loan Application Approved!', 'modal_content' => session('success_modal')])
		@elseif(session('success_modal_dir2'))
			@include('loan.partials.success_action', ['modal_title' => 'Loan Application Approved!', 'modal_content' => session('success_modal_dir2')])
		@endif
    </div>
@endsection

@section('page_script')
<script type="text/javascript">
	$(function () {
		$('#edit_button').on('click', function () {
			location.href = "/loan/edit/{{ $loan->id }}";
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

		//pass prime rate to the set rates modal
		$('#set-rates-modal').on('show.bs.modal', function (e) {
			var btnAccept = $(e.relatedTarget);
			var primeRate = btnAccept.data('prime_rate');
			var modal = $(this);
			if(primeRate != null && primeRate != '' && primeRate > 0) {
				modal.find('#prime_rate').val(primeRate.toFixed(2));
				claculateIntRate();
			}
		});

		//function to calculate the interest rate.
		function claculateIntRate() {
			var primeRate = $('#prime_rate').val();
			var plusMinus = $('#plus_minus').val();
			var variableRate = $('#variable_rate').val();
			var intRate = 0;

			if (primeRate == '') primeRate = 0;
			else primeRate = parseFloat(primeRate);

			if (variableRate == '') variableRate = 0;
			else variableRate = parseFloat(variableRate);

			if (plusMinus == '+') intRate = primeRate + variableRate;
			else intRate = primeRate - variableRate;

			$('#loan_interest_rate').val(intRate.toFixed(2));
		}

		//Plus Minus on change event (shows the plus/minus sign)
		$('#plus_minus').change(function () {
			$('#plus_minus_group').find('i').toggleClass('fa-plus fa-minus');
			claculateIntRate();
		});

		//Variable rate on change event (calculates the interest rate)
		$('#variable_rate').on('input', function () {
			claculateIntRate();
		});
		$('#variable_rate').on('change', function () {
			$(this).val(parseFloat($(this).val()).toFixed(2));
		});

		//Submit rates form to serve with ajax
		$('#submit-rates').on('click', function() {
			$.ajax({
				method: 'POST',
				url: '{{ '/loan/' . $loan->id . '/approve' }}',
				data: {
					prime_rate: $('#prime_rate').val(),
					plus_minus: $('#plus_minus').val(),
					variable_rate: $('#variable_rate').val(),
					loan_interest_rate: $('#loan_interest_rate').val(),
					_token: $('input[name=_token]').val()
				},
				success: function(success) {
					//console.log(success);
					$('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups
					//$('form[name=set-rates-form]').trigger('reset'); //Reset the form

					var successHTML = '<button type="button" id="close-invalid-input-alert" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-check"></i> Director Approval - Loan Approved!</h4>';
					successHTML += 'You have successfully approved the loan application. This loan application needs to be approved by one more director.';
					$('#prime-rate-success-alert').addClass('alert alert-success alert-dismissible')
							.fadeIn()
							.html(successHTML);

					//auto hide modal after 7 seconds
					$("#set-rates-modal").alert();
					window.setTimeout(function() { $("#set-rates-modal").modal('hide'); }, 5000);

					//autoclose alert after 7 seconds
					$("#prime-rate-success-alert").alert();
					window.setTimeout(function() { $("#prime-rate-success-alert").fadeOut('slow'); }, 5000);

					//reload page on modal close
					$('#set-rates-modal').on('hidden.bs.modal', function () {
						window.location = '/loan/view/{{ $loan->id }}';
					})
				},
				error: function(xhr) {
					//console.log(xhr);
					//if(xhr.status === 401) //redirect if not authenticated
					//$( location ).prop( 'pathname', 'auth/login' );
					if(xhr.status === 422) {
						var errors = xhr.responseJSON; //get the errors response data
						//console.log(errors);

						$('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups

						var errorsHTML = '<button type="button" id="close-invalid-input-alert" class="close" aria-hidden="true">&times;</button><h4><i class="icon fa fa-ban"></i> Invalid Input!</h4><ul>';
						$.each(errors, function (key, value) {
							errorsHTML += '<li>' + value[0] + '</li>'; //shows only the first error.
							$('#'+key).closest('.form-group')
									.addClass('has-error'); //Add the has error class to form-groups with errors
						});
						errorsHTML += '</ul>';

						$('#prime-rate-invalid-input-alert').addClass('alert alert-danger alert-dismissible')
								.fadeIn()
								.html(errorsHTML);

						//autoclose alert after 7 seconds
						$("#prime-rate-invalid-input-alert").alert();
						window.setTimeout(function() { $("#prime-rate-invalid-input-alert").fadeOut('slow'); }, 7000);

						//Close btn click
						$('#close-invalid-input-alert').on('click', function () {
							$("#prime-rate-invalid-input-alert").fadeOut('slow');
						});
					}
				}
			});
		});

		//Submit rejection form to serve with ajax
		$('#submit-rejection-reason').on('click', function() {
			$.ajax({
				method: 'POST',
				url: '{{ '/loan/' . $loan->id . '/reject' }}',
				data: {
					rejection_reason: $('#rejection_reason').val(),
					_token: $('input[name=_token]').val()
				},
				success: function(success) {
					//console.log(success);
					$('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups
					//$('form[name=set-rates-form]').trigger('reset'); //Reset the form

					var successHTML = '<button type="button" id="close-invalid-input-alert" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-check"></i> Loan Rejected!</h4>';
					successHTML += 'The loan application has been rejected. An email stating the rejection reason(s) has been sent to the applicant.';
					$('#success-alert').addClass('alert alert-success alert-dismissible')
							.fadeIn()
							.html(successHTML);

					//auto hide modal after 7 seconds
					$("#rejection-reason-modal").alert();
					window.setTimeout(function() { $("#rejection-reason-modal").modal('hide'); }, 5000);

					//autoclose alert after 7 seconds
					$("#success-alert").alert();
					window.setTimeout(function() { $("#success-alert").fadeOut('slow'); }, 5000);
				},
				error: function(xhr) {
					//console.log(xhr);
					//if(xhr.status === 401) //redirect if not authenticated
					//$( location ).prop( 'pathname', 'auth/login' );
					if(xhr.status === 422) {
						var errors = xhr.responseJSON; //get the errors response data
						//console.log(errors);

						$('.form-group').removeClass('has-error'); //Remove the has error class to all form-groups

						var errorsHTML = '<button type="button" id="close-invalid-input-alert" class="close" aria-hidden="true">&times;</button><h4><i class="icon fa fa-ban"></i> Invalid Input!</h4><ul>';
						$.each(errors, function (key, value) {
							errorsHTML += '<li>' + value[0] + '</li>'; //shows only the first error.
							$('#'+key).closest('.form-group')
									.addClass('has-error'); //Add the has error class to form-groups with errors
						});
						errorsHTML += '</ul>';

						$('#invalid-input-alert').addClass('alert alert-danger alert-dismissible')
								.fadeIn()
								.html(errorsHTML);

						//autoclose alert after 7 seconds
						$("#invalid-input-alert").alert();
						window.setTimeout(function() { $("#invalid-input-alert").fadeOut('slow'); }, 7000);

						//Close btn click
						$('#close-invalid-input-alert').on('click', function () {
							$("#invalid-input-alert").fadeOut('slow');
						});
					}
				}
			});
		});
	})
</script>
@endsection