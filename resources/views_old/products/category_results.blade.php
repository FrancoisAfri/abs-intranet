@extends('layouts.main_layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Category Search Results</h3>
                </div>
                <!-- /.box-header -->
                <!--<form class="form-horizontal" method="POST" action="/audits/print">-->
                {{ csrf_field() }}
                <div class="box-body">
                    <!-- Collapsible section containing the amortization schedule -->
                    <div class="box-group" id="accordion">
                        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                        <div class="panel box box-primary">
                            <div class="box-body">
                                <table class="table table-striped">
                                    <tr>
                                        <th></th>
                                        <th>Category Type</th>
                                        <th>Description</th>
                                    </tr>
                                    @if(count($category) > 0)
                                        @foreach($category as $category)
                                            <tr>
                                                <td></td>
                                                <td>{{ (!empty($category->name)) ?  $category->name : ''}} </td>
                                                <td>{{ (!empty($category->description)) ?  $category->description : ''}} </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <th></th>
                                            <th>Category Type</th>
                                            <th>Description</th>
                                        </tr>
                                    @endif
                                </table>
                                <div class="row no-print">
                                    <div class="col-xs-12">
                                        <button type="button" class="btn btn-default pull-left" id="back_button"><i
                                                    class="fa fa-arrow-left"></i> Back
                                        </button>
                                    </div>
                                </div>
                                <!-- End amortization /table -->
                            </div>
                        </div>
                    </div>
                    <!-- /. End Collapsible section containing the amortization schedule -->
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('page_script')
    <script>
        $(function () {
            //Cancel button click event
            $('#back_button').click(function () {
                location.href = '/product/Search';
            });
        });
    </script>
@endsection