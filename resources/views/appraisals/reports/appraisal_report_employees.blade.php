@extends('layouts.main_layout')
@section('content')
    @include('appraisals.partials.appraisal_report_emp_result')
@endsection
@section('page_script')
    <script>
        $(function () {
            //Cancel button click event
            $('#back_button').click(function () {
                location.href = '/appraisal/reports';
            });
        });
    </script>
@endsection