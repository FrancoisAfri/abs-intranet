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
                            <th style="width: 10px; text-align: center;"></th>
                            <th>Name</th>
                            <th style="width: 5px; text-align: center;">Asset Image</th>
                            <th style="width: 5px; text-align: center;">Date Created</th>
                            <th style="width: 5px; text-align: center;">Store</th>
                            <th style="width: 5px; text-align: center;">User</th>
                            <th style="width: 5px; text-align: center;">Transaction Date</th>
                            <th style="width: 5px; text-align: center;">Make</th>
                            <th style="width: 5px; text-align: center;">Asset Type</th>
                            <th style="width: 5px; text-align: center;">price</th>
                            <th>Status</th>
                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($Transfers) > 0)
                            <ul class="products-list product-list-in-box">
                                @foreach ($Transfers as $key => $assets)
                                    <tr id="categories-list">
                                        <td nowrap>
                                            <button vehice="button" id="edit_licence"
                                                    class="btn btn-warning  btn-xs"
                                                    data-toggle="modal" data-target="#edit-licence-modal"
                                                    data-id="{{ $assets->id }}"
                                                    data-name="{{ $assets->name }}"
                                                    data-description="{{$assets->description}}"><i
                                                        class="fa fa-pencil-square-o"></i> Edit
                                            </button>
                                        </td>

                                        <td>{{  $assets->name ?? ''}}</td>
                                        <td>{{ $assets->description ??  ''}}</td>
                                        <td>{{ $assets->created_at ?? '' }}</td>
                                        <td>
                                            <img src="{{ asset('storage/assets/images/'.$assets->picture) }} "
                                                 height="35px" width="40px" alt="device image">
                                        </td>
                                        <td>{{ (!empty( $assets->asset_tag)) ?  $assets->asset_tag : ''}} </td>
                                        <td>{{ (!empty( $assets->serial_number)) ?  $assets->serial_number : ''}} </td>
                                        <td>{{ (!empty( $assets->model_number)) ?  $assets->model_number : ''}} </td>
                                        <td>{{ (!empty( $assets->make_number)) ?  $assets->make_number : ''}} </td>
                                        <td> {{ $assets->asset_status ?? '' }}</td>
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
                                        <td>
                                            <form action="{{ route('assets.destroy', $assets->id) }}"
                                                  method="POST"
                                                  style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                                <button type="submit"
                                                        class="btn btn-xs btn-danger btn-flat delete_confirm"
                                                        data-toggle="tooltip" title='Delete'>
                                                    <i class="fa fa-trash"> Delete </i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                            @endforeach
                        @endif
                        </tbody>
                        <tfoot>
                        <tr>
                            <th style="width: 10px; text-align: center;">#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th style="width: 5px; text-align: center;">Device Image</th>
                            <th style="width: 5px; text-align: center;">Asset Tag</th>
                            <th style="width: 5px; text-align: center;">Serial</th>
                            <th style="width: 5px; text-align: center;">Model</th>
                            <th style="width: 5px; text-align: center;">Make</th>
                            <th style="width: 5px; text-align: center;">Asset Type</th>
                            <th style="width: 5px; text-align: center;">price</th>
                            <th>Asset Status</th>
                            <th style="width: 5px; text-align: center;">.</th>
                        </tr>
                        </tfoot>
                    </table>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" id="cat_module" class="btn btn-default pull-right" data-toggle="modal"
                                data-target="#new-transfer-modal">Transfer
                        </button>
                        <button type="button" class="btn btn-default pull-left" id="back_button"><i
                                    class="fa fa-arrow-left"></i> Back
                        </button>
                    </div>
                </div>
            </div>
            @include('assets.manageAssets.partials.transfers')
        </div>
    </div>
</div>