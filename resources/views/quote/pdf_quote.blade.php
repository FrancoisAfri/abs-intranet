@extends('layouts.printables.pdf_quote_layout')
@section('page_dependencies')
@endsection
@section('content')
    <div class="row invoice-info">
        <div class="col-xs-4 invoice-col no-padding">
            From
            <address>
                <strong>{{ $quoteProfile->divisionLevelGroup->name }}</strong><br>
                {{ $quoteProfile->phys_address }}<br>
                {{ $quoteProfile->phys_city }}, {{ $quoteProfile->phys_postal_code }}<br>
                Phone: {{ $quoteProfile->phone_number }}<br>
                Email: {{ $quoteProfile->email }}
            </address>
        </div>
        <!-- /.col -->
        <div class="col-xs-4 invoice-col no-padding">
            To
            <address>
                <strong>{{ $quotation->client->full_name }}</strong><br>
                {{ ($quotation->company) ? $quotation->company->phys_address : $quotation->client->res_address }}<br>
                {{ ($quotation->company) ? $quotation->company->phys_city . ', ' . $quotation->company->phys_postal_code : $quotation->client->res_city . ', ' . $quotation->client->res_postal_code }}<br>
                Phone: {{ ($quotation->company) ? $quotation->company->phone_number : $quotation->client->cell_number }}<br>
                Email: {{ ($quotation->company) ? $quotation->company->email : $quotation->client->email }}
            </address>
        </div>
        <!-- /.col -->
        <div class="col-xs-4 invoice-col no-padding">
            <b>Date: </b> {{ $quotation->created_at->format('d/m/Y') }}<br>
            <b>Quote #:</b> {{ $quotation->quote_number }}<br>
            <b>Valid Until:</b> {{ $quotation->created_at->addDays($quoteProfile->validity_period)->format('d/m/Y') }}
        </div>
        <!-- /.col -->
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-default no-padding">
                <form class="form-horizontal" method="POST" action="">
                    {{ csrf_field() }}
                    <div class="box-header with-border">
                        <p class="text-muted text-center">QUOTE DESCRIPTION</p>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
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

                        <table class="table table-striped table-bordered">
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Product</th>
                                <th class="text-center">Quantity</th>
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

                        <div class="row">
                            <div class="col-xs-12"><p>&nbsp;</p></div>
                        </div>

                        <div class="row no-margin">
                            <!-- banking details section -->
                            <div class="col-xs-5 no-padding">
                                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                    <b>Banking Details</b><br><br>
                                    Bank Name: {{ $quoteProfile->bank_name }}<br>
                                    Branch Code: {{ $quoteProfile->bank_branch_code }}<br>
                                    Account Name: {{ $quoteProfile->bank_account_name }}<br>
                                    Account Number: {{ $quoteProfile->bank_account_number }}
                                </p>
                            </div>

                            <!-- Total cost section -->
                            <div class="col-xs-2 no-padding"></div>
                            <div class="col-xs-5 no-padding">
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

                        <div class="col-xs-12">&nbsp;</div>

                    </div>
                    <!-- /.box-body -->
                </form>
            </div>

            <!-- T&C's box -->
            <div class="box box-default no-padding">
                <div class="box-header with-border" style="text-align: center;">
                    <p class="text-muted text-center">TERMS AND CONDITIONS</p>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <ul>
                        @foreach($quotation->termsAndConditions as $condition)
                            <li class="text-justify">{!! $condition->term_name !!}</li>
                        @endforeach
                    </ul>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
@endsection
@section('page_script')
    <script>
        $(function () {
        });
    </script>
@endsection