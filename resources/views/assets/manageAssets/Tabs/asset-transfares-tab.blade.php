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
                            <th>Name</th>
                            <th style="width: 5px; text-align: center;">Last User</th>
                            <th style="width: 5px; text-align: center;">Make</th>
                            <th style="width: 5px; text-align: center;">Asset Type</th>
                            <th style="width: 5px; text-align: center;">Asset Image</th>
                            <th style="width: 5px; text-align: center;">Date Created</th>
                            <th style="width: 5px; text-align: center;">Transaction Date</th>
                            <th style="width: 5px; text-align: center;">Transfer Date</th>
                            <th style="width: 5px; text-align: center;">Store</th>
                            <th>Status</th>

                        </tr>
                        </thead>
                        <tbody>
                        @if (count($Transfers) > 0)
                            <tr class="products-list product-list-in-box">
                                @foreach ($Transfers as $key => $assets)

                                        <td>{{  $assets->name ?? ''}}</td>
                                        <td>Last User</td>
                                        <td>make</td>
                                        <td>type</td>
                                        <td>images</td>
                                        <td>{{ $assets->created_at ?? '' }}</td>
                                        <td>{{ $assets->transaction_date ?? '' }}</td>
                                        <td>{{ $assets->transfer_date ?? '' }}</td>
                                        <td>{{ $assets->store_id ?? '' }}</td>
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
                            <th>Name</th>
                            <th style="width: 5px; text-align: center;">Last User</th>
                            <th style="width: 5px; text-align: center;">Make</th>
                            <th style="width: 5px; text-align: center;">Asset Type</th>
                            <th style="width: 5px; text-align: center;">Asset Image</th>
                            <th style="width: 5px; text-align: center;">Date Created</th>
                            <th style="width: 5px; text-align: center;">Transaction Date</th>
                            <th style="width: 5px; text-align: center;">Transfer Date</th>
                            <th style="width: 5px; text-align: center;">Store</th>
                            <th>Status</th>
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