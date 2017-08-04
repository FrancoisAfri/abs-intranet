@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <!-- Search User Form -->
        <div class="col-md-8 col-md-offset-2">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-search pull-right"></i>
                    <h3 class="box-title">Search Client</h3>
                    <p>Enter client details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/contacts/search">
                    {{ csrf_field() }}

                    <div class="box-body">
                        <div class="form-group">
                            <label for="person_name" class="col-sm-2 control-label">Name</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="person_name" name="person_name" value="{{ old('person_name') }}" placeholder="Search by name...">
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
                                    <input type="number" class="form-control" id="id_number" name="id_number" value="{{ old('id_number') }}" placeholder="Search by ID number...">
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
                                    <input type="text" class="form-control" id="passport_number" name="passport_number" value="{{ old('passport_number') }}" placeholder="Search by passport number...">
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
                                    <select name="res_province_id" id="res_province_id" class="form-control select2">
                                        <option value="">*** Select Your Province ***</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province->id }}" {{ (old('res_province_id') == $province->id) ? ' selected' : '' }}>{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" value="" name="res_province_name" id="res_province_name">
                        <div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
                            <label for="company_id" class="col-sm-2 control-label">Search by Company</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-building"></i>
                                    </div>
                                    <select id="company_id" name="company_id" class="form-control select2" style="width: 100%;">
                                        <option value="">*** Select a Company ***</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}" {{ (old('company_id') == $company->id) ? ' selected' : '' }}>{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" value="" name="company_name" id="company_name">
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Search</button>
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
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>

    <script type="text/javascript">
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();

            //set the company name hidden input when the name has been selected from the drop-down
            $('#company_id').change(function (e) {
                var selectedValue = $('#company_id').val();
                if (selectedValue > 0) $('#company_name').val($('#company_id option:selected').text());
                else $('#company_name').val('');
            });

            //set the company name hidden input when the name has been selected from the drop-down
            $('#res_province_id').change(function (e) {
                var selectedValue = $('#res_province_id').val();
                if (selectedValue > 0) $('#res_province_name').val($('#res_province_id option:selected').text());
                else $('#res_province_name').val('');
            });
        });
    </script>
@endsection