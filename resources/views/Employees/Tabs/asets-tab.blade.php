
<div class="row">
    <div class="col-md-6 col-md-offset-0">
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-barcode pull-right"></i>
                <h3 class="box-title"> My Assets </h3>
            </div>
            <div class="box-body">
                <div style="overflow-X:auto;">
                    <table id=" " class="display table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th style="width: 5px; text-align: center;">Asset Name</th>
                            <th style="width: 5px; text-align: center;">Asset Image</th>
                            <th style="width: 5px; text-align: center;">Transaction Date</th>
                            <th style="width: 5px; text-align: center;">Transfer Date</th>
                            <th style="width: 5px; text-align: center;">User Name</th>
                            <th style="width: 5px; text-align: center;">Store</th>
                            <th style="width: 5px; text-align: center;">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (!empty($assetTransfer))
                            <ul class="products-list product-list-in-box">
                                @foreach ($assetTransfer as $key => $assets)
                                    <tr id="categories-list">

                                        <td>{{ (!empty( $assets->name)) ?  $assets->name : ''}}</td>

                                        <td>
                                            <div class="popup-thumbnail img-responsive">
                                                <img src="{{ asset('storage/assets/images/'.$assets->picture) }} "
                                                     height="35px" width="40px" alt="device image">
                                            </div>
                                        </td>
                                        <td>{{ (!empty( $assets->transaction_date)) ?  $assets->transaction_date : '' }}</td>
                                        <td>{{ (!empty( $assets->transfer_date)) ?  $assets->transfer_date : $assets->created_at->toDateString() }}</td>
                                        <td> {{ (!empty( $assets->HrPeople->first_name )) ?  $assets->HrPeople->first_name. ' ' . $assets->HrPeople->surname : ' ' }}</td>
                                        <td> {{ (!empty( $assets->store->name )) ?  $assets->store->name : ' ' }}</td>
                                        <td>
                                            @if($assets->asset_status == 'Sold')
                                                <span class="label label-warning">{{ (!empty( $assets->asset_status)) ?  $assets->asset_status : ''}}</span>
                                            @elseif($assets->asset_status == 'Missing')
                                                <span class="label label-danger"> {{ (!empty( $assets->asset_status)) ?  $assets->asset_status : ''}}</span>
                                            @elseif($assets->asset_status == 'In Use')
                                                <span class="label label-success"> {{ (!empty( $assets->asset_status)) ?  $assets->asset_status : ''}}</span>
                                            @elseif($assets->asset_status == 'Discarded')
                                                <span class="label label-primary"> {{ (!empty( $assets->asset_status)) ?  $assets->asset_status : ''}}</span>
                                            @elseif($assets->asset_status == 'In Store')
                                                <span class="label label-default"> {{ (!empty( $assets->asset_status)) ?  $assets->asset_status : ''}}</span>
                                            @elseif($assets->asset_status == 'Un Allocated')
                                                <span class="label label-info"> {{ (!empty( $assets->asset_status)) ?  $assets->asset_status : ''}}</span>
                                            @endif
                                        </td>
                                    </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    <!-- /.box-body -->
                    <div class="box-footer">

                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-barcode pull-right"></i>
                    <h3 class="box-title"> License </h3>
                </div>
                <div class="box-body">
                    <div style="overflow-X:auto;">
                        <table id=" " class="display table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
                                <th>Employee Number</th>
                                <th>Employee Name</th>
                                <th>Licence Name</th>

                            </tr>
                            </thead>
                            <tbody>
                            @if (!empty($license_allocation))
                                <ul class="products-list product-list-in-box">
                                    @foreach ($license_allocation as $key => $license)
                                        <tr id="categories-list">
                                            <td></td>
                                            <td>{{ (!empty($license->Hrpersons->employee_number)) ? $license->Hrpersons->employee_number : ' ' }}</td>
                                            <td>{{ (!empty($license->Hrpersons->first_name . ' ' . $license->Hrpersons->surname )) ? $license->Hrpersons->first_name . ' ' . $license->Hrpersons->surname   : ' ' }}</td>
                                            <td>{{ (!empty($license->Licenses->name)) ? $license->Licenses->name : ' ' }}</td>

                                        </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            {{--						<button type="button" class="btn btn-default pull-left" id="back_button">Back</button>--}}
                            <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal"
                                    data-target="#add-document-modal">Add Document
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>




</div>


