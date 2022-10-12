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
                <tbody>
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
                        <div class="col-md-12">
                            {{ (!empty( $employee->surname)) ? $employee->surname : ''}}
                        </div>

                    </td>
                </tr>
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
                            {{ (!empty($employee->province)) ? $employee->province : '' }}
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
                            <strong>Province </strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{ (!empty($employee->province)) ? $employee->province : '' }}
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

                </tbody>
            </table>
        </div>
        <div class="box-footer">
{{--            <button type="button" id="cat_module" class="btn btn-default pull-right" href="">Edit My Details</button>--}}
            <button type="button" class="btn btn-default pull-right" id="user_profile"><i
                        class="fa fa-user-secret"></i> Edit My Profile
            </button>
{{--            {{ route('users/profile') }}--}}
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
        <!-- /.box-body -->

    </div>
</div>
