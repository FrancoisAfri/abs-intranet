<div class="col-lg-6 col-sm-6 pull-left">
    <br>
    <br>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">
                Personal Information
            </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-striped table-hover">
				<hr class="hr-text" data-content="Personal Information">
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong> Title</strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{ (!empty($employee->title)) ? $titles[$employee->title] : '' }}
                        </div>

                    </td>
                </tr>
				<tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong> Name</strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{ (!empty( $employee->first_name)) ? $employee->first_name : ''}}
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Surname </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{ (!empty( $employee->surname)) ? $employee->surname : ''}}
                        </div>

                    </td>
                </tr>
				<tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Known As </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{ (!empty( $employee->known_as)) ? $employee->known_as : ''}}
                        </div>

                    </td>
                </tr>
				<tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Initial </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{ (!empty( $employee->initial)) ? $employee->initial : ''}}
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Email </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{ (!empty($employee->email)) ? $employee->email : '' }}
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Cell Number </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{ (!empty($employee->cell_number)) ? $employee->cell_number : '' }}
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Address </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{ (!empty($employee->res_address)) ? $employee->res_address : '' }}
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Suburb </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{ (!empty($employee->res_suburb)) ? $employee->res_suburb : '' }}
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>City </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{ (!empty($employee->res_city)) ? $employee->res_city : '' }}
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Postal code </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{ (!empty($employee->res_postal_code)) ? $employee->res_postal_code : '' }}
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Province </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                           {{ (!empty($employee->province->name)) ? $employee->province->name : '' }}
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Date of Birth </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{ (!empty($employee->date_of_birth)) ? date('d M Y', $employee->date_of_birth) : '' }}
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Gender </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{ !empty($employee->gender) && ($employee->gender == 1)  ? 'Male': 'Female' }}
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>ID Number / Passport Number </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{ (!empty($employee->id_number)) ? $employee->id_number : $employee->passport_number }}
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Marital Status </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{ (!empty($employee->marital_status)) ? $MaritalStatus[$employee->marital_status] : '' }}
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Ethnicity </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{ (!empty($employee->ethnicity)) ? $Ethnicity[$employee->ethnicity] : '' }}
                        </div>
                    </td>
                </tr>
            </table>
			<table class="table table-striped table-hover">
				<hr class="hr-text" data-content="Emergency Contact Information">
				<tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong> Name & Surname</strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{ (!empty($employee->next_of_kin)) ? $employee->next_of_kin : '' }}
                        </div>

                    </td>
                </tr>
				<tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong> Contact Number</strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{ (!empty( $employee->next_of_kin_number)) ? $employee->next_of_kin_number : ''}}
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Work Number </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{ (!empty( $employee->next_of_kin_work_number)) ? $employee->next_of_kin_work_number : ''}}
                        </div>

                    </td>
                </tr>
            </table>
			<table class="table table-striped table-hover">
				<hr class="hr-text" data-content="Tax Information">
				<tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Income Tax Number</strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{ (!empty($employee->income_tax_number)) ? $employee->income_tax_number : '' }}
                        </div>

                    </td>
                </tr>
				<tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Tax Office</strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{ (!empty( $employee->tax_office)) ? $employee->tax_office : ''}}
                        </div>
                    </td>
                </tr>
            </table>
			<table class="table table-striped table-hover">
				<hr class="hr-text" data-content="Provident Fund">
				<tr>
					<td>
						<div class="d-flex align-items-center">
							<strong>Start Date </strong>
						</div>
					</td>
					<td>
						<div class="col-md-6">
							{{ (!empty($employee->provident_start_date)) ? date('d M Y', $employee->provident_start_date) : '' }}
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="d-flex align-items-center">
							<strong>Provident Fund Name</strong>
						</div>
					</td>
					<td>
						<div class="col-md-6">
							{{ (!empty( $employee->provident_name)) ? $employee->provident_name : ''}}
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="d-flex align-items-center">
							<strong>Provident Fund Product</strong>
						</div>
					</td>
					<td>
						<div class="col-md-6">
							{{ (!empty( $employee->provident_fund_product)) ? $employee->provident_fund_product : ''}}
						</div>
					</td>
				</tr>
			</table>
        </div>
        <div class="box-footer">
			<button type="button" class="btn btn-default pull-right" id="user_profile"><i
                        class="fa fa-user-secret"></i> Edit My Profile
            </button>
        </div>
    </div>

</div>

<div class="col-lg-6 col-sm-6">
    <br>
    <br>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ $employee->first_name . ' ' . $employee->surname}}
            </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <img src="{{ (!empty($employee->profile_pic)) ? asset('storage/avatars/'.$employee->profile_pic)  : (($employee->gender === 0) ? $f_silhouette : $m_silhouette)}} "
                 class="card-img-top" alt="Wild Landscape"
                 style='height: 400%; width: 100%; object-fit: contain'/>
        </div>
		<table class="table table-striped table-hover">
			<hr class="hr-text" data-content="Banking Detail">
			<tr>
				<td>
					<div class="d-flex align-items-center">
						<strong>Account Type</strong>
					</div>
				</td>
				<td>
					<div class="col-md-6">
						{{ (!empty($employee->account_type)) ? $employee->account_type : '' }}
					</div>

				</td>
			</tr>
			<tr>
				<td>
					<div class="d-flex align-items-center">
						<strong>Account Holder Name</strong>
					</div>
				</td>
				<td>
					<div class="col-md-6">
						{{ (!empty( $employee->account_holder_name)) ? $employee->account_holder_name : ''}}
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="d-flex align-items-center">
						<strong>Bank Name</strong>
					</div>
				</td>
				<td>
					<div class="col-md-6">
						{{ (!empty( $employee->bank_name)) ? $employee->bank_name : ''}}
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="d-flex align-items-center">
						<strong>Branch Name</strong>
					</div>
				</td>
				<td>
					<div class="col-md-6">
						{{ (!empty( $employee->branch_name)) ? $employee->branch_name : ''}}
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="d-flex align-items-center">
						<strong>Account Number</strong>
					</div>
				</td>
				<td>
					<div class="col-md-6">
						{{ (!empty( $employee->account_number)) ? $employee->account_number : ''}}
					</div>
				</td>
			</tr>
		</table>
		<table class="table table-striped table-hover">
			<hr class="hr-text" data-content="MEDICAL AID">
			<tr>
				<td>
					<div class="d-flex align-items-center">
						<strong>Start Date </strong>
					</div>
				</td>
				<td>
					<div class="col-md-6">
						{{ (!empty($employee->med_start_date)) ? date('d M Y', $employee->med_start_date) : '' }}
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="d-flex align-items-center">
						<strong>Split</strong>
					</div>
				</td>
				<td>
					<div class="col-md-6">
						{{ (!empty( $employee->med_split)) ? $employee->med_split : ''}}
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="d-flex align-items-center">
						<strong>Plan Name</strong>
					</div>
				</td>
				<td>
					<div class="col-md-6">
						{{ (!empty( $employee->med_plan_name)) ? $employee->med_plan_name : ''}}
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="d-flex align-items-center">
						<strong>Dependants Spouse</strong>
					</div>
				</td>
				<td>
					<div class="col-md-6">
						{{ (!empty( $employee->med_dep_spouse)) ? $employee->med_dep_spouse : ''}}
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="d-flex align-items-center">
						<strong>Dependants Adult</strong>
					</div>
				</td>
				<td>
					<div class="col-md-6">
						{{ (!empty( $employee->med_dep_adult)) ? $employee->med_dep_adult : ''}}
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="d-flex align-items-center">
						<strong>Dependants Children</strong>
					</div>
				</td>
				<td>
					<div class="col-md-6">
						{{ (!empty( $employee->med_dep_kids)) ? $employee->med_dep_kids : ''}}
					</div>
				</td>
			</tr>
		</table>
    </div>
</div>
