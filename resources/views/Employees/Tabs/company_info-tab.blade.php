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
                            {{ (!empty($employee->division)) ? $employee->division : '' }}
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
                            {{ (!empty($employee->department)) ? $employee->department : '' }}
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
                            {{ (!empty($employee->manager_first_name . ' ' . $employee->manager_surname)) ? $employee->manager_first_name . ' ' . $employee->manager_surname : '' }}
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
                            {{ (!empty($employee->second_manager_first_name . ''. $employee->second_manager_surname)) ? $employee->second_manager_first_name . ''. $employee->second_manager_surname : '' }}
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
                        <span class="label label-success">
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

                </tbody>
            </table>
        </div>
    </div>
</div>



