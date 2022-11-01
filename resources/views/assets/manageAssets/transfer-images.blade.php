@extends('layouts.main_layout')

@section('content')
    <br><br><br><br>
    <div class="album py-5 bg-light">
        <div class="container">
            <h1> Images for Asset - {{ $name }}</h1>
            <br>
            <div class="row">

                @foreach ($image as $key => $images)
                    <div class="col-md-4">
                        <div class="card mb-4 shadow-sm">
                            <img class="bd-placeholder-img card-img-top" width="80%" height="200"
                                 src="{{ asset('storage/assets/images/'.$images->picture) }} " alt="Photo">
                            <div class="card-body">
                                <p class="card-text"></p>
                                <div class="d-flex justify-content-between align-items-center"></div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
            <div class="box-footer">
                <button type="button" class="btn btn-default pull-right" id="back_button"><i
                            class="fa fa-arrow-left"></i> Back
                </button>
            </div>
        </div>
    </div>

@stop
@section('page_script')
    <script>
        //back
        $('#back_button').click(function () {
            location.href = '{{ route('assets.show', [$transfer]) }}';
        });
    </script>

@stop
