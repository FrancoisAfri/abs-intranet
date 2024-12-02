<div class="col-lg-12 ">
    <br>
    <br>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">
                Work Information
            </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-striped table-hover">
                <tbody>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Employee Number </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{ (!empty($employee->employee_number)) ? $employee->employee_number : '' }}
                        </div>

                    </td>
                </tr>
				<tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>
                                Date Joined
                            </strong>
                        </div>
                    </td>
                    <td>
                        <span class="label label-success  col-md-6">
                             {{ (!empty( $employee->date_joined)) ?  date(' d M Y', $employee->date_joined) : ''}}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>
                                Date Left
                            </strong>
                        </div>
                    </td>
                    <td>
                        {{ (!empty( $employee->date_left)) ?  date(' d M Y', $employee->date_left) : ''}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong> Leave Profile </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{ (!empty($employee->leave_profile)) ? $leaveProfiles[$employee->leave_profile] : '' }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Position </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{ (!empty($employee->jobTitle->name)) ? $employee->jobTitle->name : '' }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Division </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
							{{ (!empty($employee->division->name)) ? $employee->division->name : '' }}
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Department </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
							{{ (!empty($employee->department->name)) ? $employee->department->name : '' }}
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Report to </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
							@if (!empty($managerDetails))
								{{ (!empty($managerDetails->first_name . ' ' . $managerDetails->surname)) ? $managerDetails->first_name . ' ' . $managerDetails->surname : '' }}
							@endif
					   </div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Second manager in charge </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
							@if (!empty($secondmanagerDetails))
								{{ (!empty($secondmanagerDetails->first_name . ''. $secondmanagerDetails->surname)) ? $secondmanagerDetails->first_name . ''. $secondmanagerDetails->surname : '' }}
							@endif
						</div>

                    </td>
                </tr>
				<tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Disabled </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
							 {{ (!empty($employee->disabled)) ? $disabilities[$employee->disabled] : '' }}
						</div>

                    </td>
                </tr>
				<tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Nature of Disability  </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
							{{ (!empty($employee->nature_of_disability)) ? $employee->nature_of_disability : '' }}
						</div>

                    </td>
                </tr>
				<tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Employment Type</strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
							{{ (!empty($employee->employment_type)) ? $employmentTypes[$employee->employment_type] : '' }}
						</div>
                    </td>
                </tr>
				<tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Occupational Level </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
							{{ (!empty($employee->occupational_level)) ? $occupationalLevels[$employee->occupational_level] : '' }}
						</div>
                    </td>
                </tr>
				<tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Job Function </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
							{{ (!empty($employee->job_function)) ? $jobFunctions[$employee->job_function] : '' }}
						</div>

                    </td>
                </tr>
				<tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Working Hours</strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
							{{ (!empty($employee->start_time)) ? $employee->start_time : '' }} - {{ (!empty($employee->end_time)) ? $employee->end_time : '' }}
						</div>

                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>



