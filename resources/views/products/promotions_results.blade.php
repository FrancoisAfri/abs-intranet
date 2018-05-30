@extends('layouts.main_layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Product Search Results</h3>
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

                                        <th>Promotion Type</th>
                                        <th>Product Type</th>
                                        <th>Promotion start date</th>
                                        <th>Promotion End date</th>
                                        <th>Discount</th>
                                        <th>price</th>


                                    </tr>
                                    @if(count($Promotions) > 0)
                                        @foreach($Promotions as $Promotion)
                                            <tr>
                                                <td>{{ (!empty($Promotion->name)) ?  $Promotion->name : ''}} </td>
                                                <td>{{ (!empty($Promotion->product_name)) ?  $Promotion->product_name : ''}} </td>
                                                <td>{{ !empty($Promotion->start_date) ? date('d M Y ', $Promotion->start_date) : '' }}</td>
                                                <td>{{ !empty($Promotion->end_date) ? date('d M Y ', $Promotion->end_date) : '' }}</td>
                                                <td>
                                                    <span class="label label-primary ">{{ (!empty($Promotion->discount)) ?  $Promotion->discount : ''}}
                                                </td>
                                                <td>
                                                    R {{ (!empty($Promotion->price)) ?   'R' .number_format($Promotion->price, 2) : ''}} </td>
                                            </tr>
                                        @endforeach
                                        <tr>

                                            <th>Promotion Type</th>
                                            <th>Product Type</th>
                                            <th>Promotion start date</th>
                                            <th>Promotion End date</th>
                                            <th>Discount</th>
                                            <th>price</th>
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