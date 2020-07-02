@extends('layouts.guest_main_layout')

@section('page_dependencies')
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">

    <!-- Star Ratting Plugin -->
    <!-- default styles -->
    <link href="/bower_components/kartik-v-bootstrap-star-rating-3642656/css/star-rating.css" media="all" rel="stylesheet" type="text/css" />
    <!-- optionally if you need to use a theme, then include the theme CSS file as mentioned below -->
    <link href="/bower_components/kartik-v-bootstrap-star-rating-3642656/themes/krajee-svg/theme.css" media="all" rel="stylesheet" type="text/css" />
    <!-- /Star Ratting Plugin -->
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
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
                        @if($isEmpFound)
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
                                                <option value="{{ $employee->id }}"{{ ($employee->id == $consultantID) ? ' selected' : '' }}>{{ $employee->full_name }}</option>
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

                            <div class="row">
                                @foreach($surveyQuestions as $surveyQuestion)
                                    <div class="col-sm-6">
                                        <div class="form-group {{ $errors->has("questions[$surveyQuestion->id]") ? ' has-error' : '' }}">
                                            <label for="{{ 'question_id_' . $surveyQuestion->id }}" class="col-sm-4 control-label">{{ $surveyQuestion->description }}</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control rating rating-loading" id="{{ 'question_id_' . $surveyQuestion->id }}" name="{{ "questions[$surveyQuestion->id]" }}" value="{{ old("questions[$surveyQuestion->id]") }}" data-min="0" data-max="5" data-step="1" data-show-clear="false" data-size='xs'>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="form-group {{ $errors->has('additional_comments') ? ' has-error' : '' }}">
                                <label for="additional_comments" class="col-sm-2 control-label">Additional Comments</label>
                                <div class="col-sm-10">
                                    <textarea name="additional_comments" id="additional_comments" class="form-control" rows="4">{{ old('additional_comments') }}</textarea>
                                </div>
                            </div>
                        @else
                            <div class="callout callout-danger">
                                <h4><i class="icon fa fa-ban"></i> Consultant Not Found!</h4>
                                <p>There is no consultant associated with this url. Please make sure you have got the correct url or contact the systems administrator for more details.</p>
                            </div>
                        @endif
                    </div>
                    <!-- /.box-body -->
                    @if($isEmpFound)
                        <div class="box-footer">
                            <input type="submit" id="submit-review" name="submit-review" class="btn btn-primary btn-flat pull-right" value="Submit Feedback">
                        </div>
                    @endif
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

    <!-- Star Ratting Plugin -->
    <!-- default styles -->
    <script src="/bower_components/kartik-v-bootstrap-star-rating-3642656/js/star-rating.js" type="text/javascript"></script>
    <!-- optionally if you need to use a theme, then include the theme JS file as mentioned below -->
    <script src="/bower_components/kartik-v-bootstrap-star-rating-3642656/themes/krajee-svg/theme.js"></script>
    <!-- optionally if you need translation for your language then include locale file as mentioned below -->
    <!-- <script src="path/to/js/locales/<lang>.js"></script> -->
    <!-- /Star Ratting Plugin -->

    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });
    </script>
@endsection