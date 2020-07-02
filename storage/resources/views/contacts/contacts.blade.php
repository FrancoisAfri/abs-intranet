@extends('layouts.main_layout')

@section('content')
    @include('contacts.partials.contact_search_result')
@endsection

@section('page_script')
    <script type="text/javascript">
        //Cancel button click event
        document.getElementById("back_to_contact_search").onclick = function () {
            location.href = "/contacts";
        };
    </script>
@endsection