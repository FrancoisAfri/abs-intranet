<div class="row">
    <div class="col-md-12 col-md-offset-0">
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-barcode pull-right"></i>
                <h3 class="box-title"> Asset Depreciation</h3>
            </div>
            <div class="box-body">
                <div class="card my-2">
                </div>
                <div style="overflow-X:auto;">
                    <table id=" " class="display table table-bordered data-table my-2">
                        <thead>
                        <tr>
                            <th></th>
                            <th style="width: 5px; text-align: center;">Asset Name</th>
                            <th style="width: 5px; text-align: center;">Notes</th>
                            <th style="width: 5px; text-align: center;">Depreciation Date</th>
                            <th style="width: 5px; text-align: center;">Months</th>
                            <th style="width: 5px; text-align: center;">Years</th>
                            <th style="width: 5px; text-align: center;">Initial Amount</th>
                            <th style="width: 5px; text-align: center;">Depreciated Amount</th>
                            <th style="width: 5px; text-align: center;">Balance</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($depreciations) > 0)
                            <tr class="products-list product-list-in-box">
                                @foreach ($depreciations as $key => $depreciation)
                                    <td></td>
                                    <td style="text-align: center;">
                                       {{ (!empty($depreciation->AssetsList->name)) ?  $depreciation->AssetsList->name : '' }}
                                    </td>
									<td style="text-align: center;">
										{{ (!empty($depreciation->notes)) ?  $depreciation->notes : '' }}
                                    </td>
                                    <td style="text-align: center;">
                                        {{ (!empty($depreciation->created_at->toDateString())) ?  $depreciation->created_at->toDateString() : '' }}
                                    </td>
                                    <td style="text-align: center;">
                                        {{ (!empty($depreciation->months)) ?  $depreciation->months : '' }}

                                    </td>
                                    <td style="text-align: center;">{{ $depreciation->years ?? '' }}</td>
                                    <td style="text-align: center;"> {{ (!empty($depreciation->initial_amount)) ?  'R ' .number_format($depreciation->initial_amount, 2): ' ' }}</td>
                                    <td style="text-align: center;">{{ (!empty($depreciation->amount_monthly)) ?  'R ' .number_format($depreciation->amount_monthly, 2): ' ' }}</td>
                                    <td style="text-align: center;">{{ (!empty($depreciation->balance_amount)) ?  'R ' .number_format($depreciation->balance_amount, 2): ' ' }}
                                    </td>
                            </tr>
                            @endforeach
                        @endif
                        </tbody>
                        <tfoot>
                        <tr>
                            <th style="width: 5px; text-align: center;"></th>
                            <th style="width: 5px; text-align: center;">Asset Name</th>
                            <th style="width: 5px; text-align: center;">Notes</th>
                            <th style="width: 5px; text-align: center;">Depreciation Date</th>
                            <th style="width: 5px; text-align: center;">Months</th>
                            <th style="width: 5px; text-align: center;">Years</th>
                            <th style="width: 5px; text-align: center;">Initial Amount</th>
                            <th style="width: 5px; text-align: center;">Depreciated Amount</th>
                            <th style="width: 5px; text-align: center;">Balance</th>
                        </tr>
                        </tfoot>
                    </table>
                    <!-- /.box-body -->
                    <div class="box-footer">

                            <button type="button" id="cat_module" class="btn btn-default pull-right" data-toggle="modal"
                                    data-target="#new-transfer-modal"> New Transfer
                            </button>

                    </div>
                </div>
            </div>
            @include('assets.manageAssets.partials.transfers')
        </div>
    </div>
</div>