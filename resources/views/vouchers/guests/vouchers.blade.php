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
        <div class="col-sm-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <i class="fa fa-file-text pull-right"></i>
                    <h3 class="box-title">Client Voucher</h3>
                    <p>Client vouchers list.</p>
                </div>
                <!-- /.box-header -->

                <!-- Form Start -->
                <form name="service-rating-form" class="form-horizontal" method="POST" action="/get-voucher">
                    {{ csrf_field() }}

                    <div class="box-body">
                        @if($vouchers && count($vouchers) > 0)
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Reason</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>183</td>
                                    <td>John Doe</td>
                                    <td>11-7-2014</td>
                                    <td><span class="label label-success">Approved</span></td>
                                    <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                </tr>
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-info"></i> No Match Found!</h4>
                                Sorry we couldn't find any match in our database. Please refine your search parameters.
                            </div>
                        @endif
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="/get-voucher" type="button" id="back" class="btn btn-default btn-flat pull-left"><i class="fa fa-arrow-left"></i> Back</a>
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