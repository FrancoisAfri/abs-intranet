@extends('layouts.printables.pdf_quote_layout')
@section('page_dependencies')
@endsection
@section('content')
    <div class="row invoice-info">
        <div class="col-md-12 invoice-col">
			<table class="table table-bordered table-hover">
				<thead>
				<tr>
					<th class="col-md-6 invoice-col">To</th>
					<th class="col-md-6 invoice-col"></th>
				</tr>
				</thead>
				<tbody>
					<tr>
						<td class="col-md-6 invoice-col">
							<address>
								<strong>{{ $quotation->company->name }}</strong><br>
								<strong>{{ $quotation->client->full_name }}</strong><br>
								{{ ($quotation->company) ? $quotation->company->phys_address : $quotation->client->res_address }}<br>
								{{ ($quotation->company) ? $quotation->company->phys_city . ', ' . $quotation->company->phys_postal_code : $quotation->client->res_city . ', ' . $quotation->client->res_postal_code }}<br>
								Phone: {{ ($quotation->company) ? $quotation->company->phone_number : $quotation->client->cell_number }}<br>
								Email: {{ ($quotation->company) ? $quotation->company->email : $quotation->client->email }}
							</address>
						</td>
						<td class="col-md-6 invoice-col">
							<address>
							   <b>Date: </b> {{ $quotation->created_at->format('d/m/Y') }}<br>
								<b>Quote #:</b> {{ $quotation->quote_number }}<br>
								<b>Valid Until:</b> {{ $quotation->created_at->addDays($quoteProfile->validity_period)->format('d/m/Y') }}
							</address>
						</td>
					</tr>
				</tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default no-padding">
				<div class="box-header with-border">
				 <h3 class="box-title"><b>Reference</b>:<b>{{!empty($quotation->quote_title) ? $quotation->quote_title: ''}} </b></h3>
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
					@if($quotation->quote_type == 1)
						<table class="table table-striped table-bordered">
							<tr>
								<th style="width: 10px;">#</th>
								<th style="width: 150px;">Product</th>
								<th style="width: 100px;">Comment</th>
								<th class="text-center; width: 30px;">Quantity</th>
								<th style="text-align: center; width: 100px;">Unit Price</th>
								<th style="text-align: center; width: 100px;">Total</th>
							</tr>
							@foreach ($quotation->products as $product)
								@if($loop->first || (isset($prevCategory) && $prevCategory != $product->category_id))
									<?php $prevCategory = 0; ?>
									<tr>
										<th class="success" colspan="6" style="text-align: left;">
											<i>{{ $product->ProductPackages->name }}</i>
										</th>
									</tr>
								@endif
								<tr>
									<td style="vertical-align: middle; width: 10px;">{{ $loop->iteration }}</td>
									<td style="vertical-align: middle; width: 90px;">{{ $product->name }}</td>
									<td style="vertical-align: middle;  width: 80px">{{ $product->pivot->comment }}</td>
									<td style="vertical-align: middle; width: 30px; text-align: center;">
										{{ $product->pivot->quantity }}
									</td>
									<td style="vertical-align: middle; text-align: center; width: 100px;">
										{{ $product->pivot->price ? 'R ' . number_format($product->pivot->price, 2) : '' }}
									</td>
									<td style="vertical-align: middle; text-align: center; width: 100px;">
										{{ $product->pivot->price ? 'R ' . number_format($product->pivot->price * $product->pivot->quantity , 2) : '' }}
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
									<td style="vertical-align: middle; text-align: right;">
										{{ $package->pivot->price ? 'R ' . number_format($package->pivot->price * $package->pivot->quantity , 2) : '' }}
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
					@elseif($quotation->quote_type == 2)
						@if($servicesSettings)
							<table class="table table-striped table-bordered">
								<thead>
									<tr>
										<th style="width: 10px" nowrap>#</th>
										<th>Description</th>
										<th style="width: 10px" nowrap class="text-center">Unit</th>
										<th class="text-center" nowrap>Quantity</th>
										<th style="width: 10px" nowrap>Total</th>
									</tr>
								</thead>
								<tbody class="input-fields-wrap">
									@foreach($quotation->services as $service)
										<tr>
											<td style="width: 10px; vertical-align: middle;" nowrap>{{ $loop->iteration }}</td>
											<td>{{ $service->description }}</td>
											<td style="width: 10px; vertical-align: middle;" nowrap class="text-center">{{ $servicesSettings->service_unit_name }}</th>
											<td style="width: 100px; vertical-align: middle;" nowrap class="text-center">
												{{ $service->quantity }}
											</td>
											<!--<td style="width: 10px; vertical-align: middle;" nowrap class="text-center"></td>-->
										</tr>
									@endforeach
								</tbody>
								<tfoot>
									<tr>
										<th colspan="3" class="text-right">Total</th>
										<th class="text-center" nowrap><span id="total_service_units"></span> <i>{{  $totalServiceQty . ' ' . $servicesSettings->service_unit_plural_name }}</i></th>
									</tr>
								</tfoot>
							</table>
						@else
							<div class="alert alert-danger alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<h4><i class="icon fa fa-database"></i> Services Settings Not Found!</h4>
								Please go to Products > Setup and enter the Services Settings.
							</div>
						@endif
					@endif
					<div class="row">
						<div class="col-xs-12"><p>&nbsp;</p></div>
					</div>
					<div class="row no-margin">
						<!-- banking details section -->
						<div class="col-xs-5 no-padding">
							<p class="no-shadow" style="margin-top: 10px;">
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
								@if($servicesSettings && $quotation->quote_type == 2)
									<tr>
										<th style="text-align: left;">Rate Per {{ ucfirst($servicesSettings->service_unit_name) }}:</th>
										<td style="text-align: right;" nowrap>
											{{ ($servicesSettings->service_rate && $servicesSettings->service_rate > 0) ? 'R ' . number_format($servicesSettings->service_rate, 2) : '' }}
										</td>
									</tr>
								@endif
								<tr>
									<th style="text-align: left;">Subtotal:</th>
									<td style="text-align: right;" id="subtotal" nowrap>{{ 'R ' . number_format($subtotal, 2) }}</td>
								</tr>
								<tr>
									<th style="text-align: left; vertical-align: middle;">Discount{{ $discountPercent ? ' (' . $discountPercent . '%)' : '' }}:</th>
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
            </div>
			<!-- Remarks box -->
            <div class="box box-default no-padding">
                <div class="box-header with-border" style="text-align: center;">
                    <p class="text-muted text-center">Comment</p>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <div class="col-xs-12 no-padding">
							<p class="no-shadow" style="margin-top: 10px;">
								{{$quotation->quote_remarks}}
							</p>
						</div>
                </div>
                <!-- /.box-body -->
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
					<br>
					<p><b>By signing this quotation you agree to all terms and conditions and the rates quoted.</b><br></br></p>
					<p><b>Name:__________________________ Signature:_____________________</b></p>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
@endsection