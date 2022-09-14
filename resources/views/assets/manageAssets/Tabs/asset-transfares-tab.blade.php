<div class="row">
    <div class="col-md-12 col-md-offset-0">
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-barcode pull-right"></i>
                <h3 class="box-title"> Asset Transfers</h3>
            </div>
            <div class="box-body">
                <div class="card my-2">
                </div>
                <div style="overflow-X:auto;">
                    <table id=" " class="display table table-bordered data-table my-2">
                        <thead>
                        <tr>
                            <th></th>
                            <th style="width: 5px; text-align: center;">Name</th>
                            <th style="width: 5px; text-align: center;">Asset Image</th>
                            <th style="width: 5px; text-align: center;">Date Created</th>
                            <th style="width: 5px; text-align: center;">Transaction Date</th>
                            <th style="width: 5px; text-align: center;">Transfer Date</th>
                            <th style="width: 5px; text-align: center;">User Name</th>
                            <th style="width: 5px; text-align: center;">Store</th>
                            <th style="width: 5px; text-align: center;">Status</th>

                        </tr>
                        </thead>
                        <tbody>
                        @if (count($Transfers) > 0)
                            <tr class="products-list product-list-in-box">
                                @foreach ($Transfers as $key => $assets)
                                    <td></td>
                                    <td>
                                        {{ (!empty( $assets->name)) ?  $assets->name : $assets->AssetTransfers->name }}
                                    </td>
                                    <td>
                                        <img src="{{ asset('storage/files/images/'.($assets->document ?? '') ) }} "
                                             height="35px" width="40px" alt=" ">
                                    </td>
                                    <td>
                                        {{ (!empty( $assets->created_at->toDateString())) ?  $assets->created_at->toDateString() : $assets->AssetTransfers->created_at->toDateString() }}
                                    </td>
                                    <td>
                                        {{ (!empty( $assets->transaction_date)) ?  $assets->transaction_date : $assets->AssetTransfers->created_at->toDateString() }}

                                    </td>
                                    <td>{{ $assets->transfer_date ?? '' }}</td>
                                    <td> {{ (!empty( $assets->HrPeople->first_name )) ?  $assets->HrPeople->first_name. ' ' . $assets->HrPeople->surname : ' ' }}</td>
                                    <td>{{ $assets->store->name ?? '' }}</td>
                                    <td>
                                        @if($assets->asset_status == 'Sold')
                                            <span class="label label-danger">{{ (!empty( $assets->asset_status)) ?  $assets->asset_status : ''}}</span>
                                        @elseif($assets->asset_status == 'Missing')
                                            <span class="label label-warning"> {{ (!empty( $assets->asset_status)) ?  $assets->asset_status : ''}}</span>
                                        @elseif($assets->asset_status == 'In Use')
                                            <span class="label label-default"> {{ (!empty( $assets->asset_status)) ?  $assets->asset_status : ''}}</span>
                                        @elseif($assets->asset_status == 'Discarded')
                                            <span class="label label-primary"> {{ (!empty( $assets->asset_status)) ?  $assets->asset_status : ''}}</span>
                                        @elseif($assets->asset_status == 'In Store')
                                            <span class="label label-success"> {{ (!empty( $assets->asset_status)) ?  $assets->asset_status : ''}}</span>
                                        @elseif($assets->asset_status == 'Un Allocated')
                                            <span class="label label-info"> {{ (!empty( $assets->asset_status)) ?  $assets->asset_status : ''}}</span>
                                        @endif
                                    </td>

                            </tr>
                            @endforeach
                        @endif
                        </tbody>
                        <tfoot>
                        <tr>
                            <th style="width: 5px; text-align: center;"></th>
                            <th style="width: 5px; text-align: center;">Name</th>
                            <th style="width: 5px; text-align: center;">Asset Image</th>
                            <th style="width: 5px; text-align: center;">Date Created</th>
                            <th style="width: 5px; text-align: center;">Transaction Date</th>
                            <th style="width: 5px; text-align: center;">Transfer Date</th>
                            <th style="width: 5px; text-align: center;">User Name</th>
                            <th style="width: 5px; text-align: center;">Store</th>
                            <th style="width: 5px; text-align: center;">Status</th>
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