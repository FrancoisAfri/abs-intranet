@extends('layouts.guest_main_layout')

@section('page_dependencies')
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">

    <!-- Start Ratting Plugin -->
    <!-- default styles -->
    <link href="/bower_components/kartik-v-bootstrap-star-rating-3642656/css/star-rating.css" media="all" rel="stylesheet" type="text/css" />
    <!-- optionally if you need to use a theme, then include the theme CSS file as mentioned below -->
    <link href="/bower_components/kartik-v-bootstrap-star-rating-3642656/themes/krajee-svg/theme.css" media="all" rel="stylesheet" type="text/css" />
    <!-- /Start Ratting Plugin -->
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-comments-o pull-right"></i>
                    <h3 class="box-title">Customer Feedback</h3>
                    <p>We value your feedback and appreciate your comments.</p>
                </div>
                <!-- /.box-header -->

                <!-- Form Start -->
                <form name="service-rating-form" class="form-horizontal" method="POST" action="/rate-our-services">
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

                        @if (session('success_add'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-check"></i> Thanks for your feedback!</h4>
                                {{ session('success_add') }}
                            </div>
                        @endif
                        
                        <div class="form-group {{ $errors->has('hr_person_id') ? ' has-error' : '' }}">
                            <label for="hr_person_id" class="col-sm-2 control-label">Consultant Name <i class="fa fa-asterisk"></i></label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <select id="hr_person_id" name="hr_person_id" class="form-control select2" style="width: 100%;">
                                        <option value="">*** Select a Consultant ***</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}"{{ ($employee->id == old('hr_person_id')) ? ' selected' : '' }}>{{ $employee->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('client_name') ? ' has-error' : '' }}">
                            <label for="client_name" class="col-sm-2 control-label">Your Name <i class="fa fa-asterisk"></i></label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input type="text" class="form-control" id="client_name" name="client_name" value="{{ old('client_name') }}" placeholder="enter your full name">
                                </div>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('booking_number') ? ' has-error' : '' }}">
                            <label for="booking_number" class="col-sm-2 control-label">Quote / Booking No.</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-info"></i>
                                </div>
                                <input type="text" class="form-control" id="booking_number" name="booking_number" value="{{ old('booking_number') }}" placeholder="enter booking number">
                                </div>
                            </div>
                        </div>

                        <hr class="hr-text" data-content="TELL US ABOUT YOUR EXPERIENCE">

                        <div class="form-group {{ $errors->has('attitude_enthusiasm') ? ' has-error' : '' }}">
                            <label for="booking_number" class="col-sm-2 control-label">Attitude / Enthusiasm</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control rating rating-loading" id="attitude_enthusiasm" name="attitude_enthusiasm" value="{{ old('attitude_enthusiasm') }}" data-min="0" data-max="5" data-step="1" data-show-clear="false">
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('expertise') ? ' has-error' : '' }}">
                            <label for="expertise" class="col-sm-2 control-label">Expertise</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control rating rating-loading" id="expertise" name="expertise" value="{{ old('expertise') }}" data-min="0" data-max="5" data-step="1" data-show-clear="false">
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('efficiency') ? ' has-error' : '' }}">
                            <label for="efficiency" class="col-sm-2 control-label">Turnaround Time</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control rating rating-loading" id="efficiency" name="efficiency" value="{{ old('efficiency') }}" data-min="0" data-max="5" data-step="1" data-show-clear="false">
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('attentive_listening') ? ' has-error' : '' }}">
                            <label for="attentive_listening" class="col-sm-2 control-label">Attentive Listening</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control rating rating-loading" id="attentive_listening" name="attentive_listening" value="{{ old('attentive_listening') }}" data-min="0" data-max="5" data-step="1" data-show-clear="false">
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('general_overall_assistance') ? ' has-error' : '' }}">
                            <label for="general_overall_assistance" class="col-sm-2 control-label">Overall Experience</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control rating rating-loading" id="general_overall_assistance" name="general_overall_assistance" value="{{ old('general_overall_assistance') }}" data-min="0" data-max="5" data-step="1" data-show-clear="false">
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('additional_comments') ? ' has-error' : '' }}">
                            <label for="additional_comments" class="col-sm-2 control-label">Additional Comments</label>
                            <div class="col-sm-10">
                                <textarea name="additional_comments" id="additional_comments" class="form-control" rows="4">{{ old('additional_comments') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <input type="submit" id="submit-review" name="submit-review" class="btn btn-primary btn-flat pull-right" value="Submit Feedback">
                    </div>
                </form>
            </div>
        </div>
        <!-- Include add new modal -->
    </div>
@endsection

@section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

    <!-- Start Ratting Plugin -->
    <!-- default styles -->
    <script src="/bower_components/kartik-v-bootstrap-star-rating-3642656/js/star-rating.js" type="text/javascript"></script>
    <!-- optionally if you need to use a theme, then include the theme JS file as mentioned below -->
    <script src="/bower_components/kartik-v-bootstrap-star-rating-3642656/themes/krajee-svg/theme.js"></script>
    <!-- optionally if you need translation for your language then include locale file as mentioned below -->
    <!-- <script src="path/to/js/locales/<lang>.js"></script> -->
    <!-- /Start Ratting Plugin -->

    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });
    </script>
@endsection