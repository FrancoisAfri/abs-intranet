@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- Star Ratting Plugin -->
    <!-- default styles -->
    <link href="/bower_components/kartik-v-bootstrap-star-rating-3642656/css/star-rating.css" media="all" rel="stylesheet" type="text/css" />
    <!-- optionally if you need to use a theme, then include the theme CSS file as mentioned below -->
    <link href="/bower_components/kartik-v-bootstrap-star-rating-3642656/themes/krajee-svg/theme.css" media="all" rel="stylesheet" type="text/css" />
    <!-- /Star Ratting Plugin -->
@endsection
@section('content')
    @include('survey.partials.survey_report_result')
@endsection
@section('page_script')
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
        	//set star rating widgets to displayonly
        	$('.rating-loading').rating({displayOnly: true, step: 1});
            //Cancel button click event
            $('#back_button').click(function () {
                location.href = '/survey/reports';
            });
        });
    </script>
@endsection