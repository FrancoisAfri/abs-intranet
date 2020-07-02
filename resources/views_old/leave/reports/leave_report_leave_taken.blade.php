@extends('layouts.main_layout')
@section('content')
    @include('leave.partials.leave_taken_report_lk_result')
@endsection
@section('page_script')
    <script>
        $(function () {
            //Cancel button click event
            $('#back_button').click(function () {
               // location.href = '/appraisal/reports';
            });
        });
    </script>
@endsection