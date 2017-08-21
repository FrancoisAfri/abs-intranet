@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <form class="form-horizontal" method="POST" action="">
                    {{ csrf_field() }}
                    <div class="box-header with-border">
                        <h3 class="box-title">Quote Details</h3>
                    </div>
                    <!-- /.box-header -->
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

                        <div style="overflow-x:auto;">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th style="text-align: right;">Unit Price</th>
                                </tr>
                                @foreach ($quotation->products as $product)
                                    @if($loop->first || (isset($prevCategory) && $prevCategory != $product->category_id))
                                        <?php $prevCategory = 0; ?>
                                        <tr>
                                            <th class="success" colspan="4" style="text-align: center;">
                                                <i>{{ $product->ProductPackages->name }}</i>
                                            </th>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                        <td style="vertical-align: middle;">{{ $product->name }}</td>
                                        <td style="vertical-align: middle; width: 80px; text-align: center;">
                                            {{ $product->pivot->quantity }}
                                        </td>
                                        <td style="vertical-align: middle; text-align: right;">
                                            {{ $product->pivot->price ? 'R ' . number_format($product->pivot->price, 2) : '' }}
                                        </td>
                                    </tr>
                                    <?php $prevCategory = $product->category_id; ?>
                                @endforeach
                                @foreach ($quotation->packages as $package)
                                    <tr>
                                        <td class="success" style="vertical-align: middle;"><i class="fa fa-caret-down"></i></td>
                                        <th class="success" style="vertical-align: middle;">
                                            Package: {{ $package->name }}
                                        </th>
                                        <td class="success" style="vertical-align: middle; width: 80px; text-align: center;">
                                            {{ $package->pivot->quantity }}
                                        </td>
                                        <td class="success" style="vertical-align: middle; text-align: right;">
                                            {{ ($package->pivot->price) ? 'R ' . number_format($package->pivot->price, 2) : '' }}
                                        </td>
                                    </tr>
                                    @foreach($package->products_type as $product)
                                        <tr>
                                            <td style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                            <td style="vertical-align: middle;">{{ $product->name }}</td>
                                            <td style="text-align: center; vertical-align: middle; width: 80px;">
                                                &mdash;
                                            </td>
                                            <td style="vertical-align: middle; text-align: right;">
                                                &mdash;
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </table>

                            <!-- Total cost section -->
                            <div class="col-sm-4 col-sm-offset-8 no-padding">
                                <table class="table">
                                    <tr>
                                        <th style="text-align: left;">Subtotal:</th>
                                        <td style="text-align: right;" id="subtotal" nowrap>{{ 'R ' . number_format($subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: left; vertical-align: middle;">Discount{{ $discountPercent ? $discountPercent . '%' : '' }}:</th>
                                        <td style="text-align: right; vertical-align: middle;" id="discount-amount" nowrap>{{ 'R ' . number_format($discountAmount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: left; vertical-align: middle;">VAT:</th>
                                        <td style="text-align: right; vertical-align: middle;" id="vat-amount" nowrap>{{ ($vatAmount > 0) ? 'R ' . number_format($vatAmount, 2) : '&mdash;' }}</td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: left; vertical-align: middle;">Total:</th>
                                        <td style="text-align: right; vertical-align: middle;" id="total-amount" nowrap>{{ 'R ' . number_format($total, 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">

                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
        </div>

        <!-- Include modal -->
        @if(Session('changes_saved'))
            @include('contacts.partials.success_action', ['modal_title' => "Users Access Updated!", 'modal_content' => session('changes_saved')])
        @endif
    </div>
@endsection

@section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>

    <script>
        $(function () {
        });
    </script>
@endsection