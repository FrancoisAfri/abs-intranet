<div class="row">
    <div class="col-md-12 col-md-offset-0">
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-barcode pull-right"></i>
                <h3 class="box-title"> Asset Tranfares</h3>
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
                            <th>Description</th>
                            <th style="width: 5px; text-align: center;">Device Image</th>
                            <th style="width: 5px; text-align: center;">Asset Tag</th>
                            <th style="width: 5px; text-align: center;">Serial</th>
                            <th style="width: 5px; text-align: center;">Model</th>
                            <th style="width: 5px; text-align: center;">Make</th>
                            <th style="width: 5px; text-align: center;">Asset Type</th>
                            <th style="width: 5px; text-align: center;">price</th>
                            <th>Status</th>
                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($assetTransfare) > 0)
                            <ul class="products-list product-list-in-box">
                                @foreach ($assetTransfare as $key => $assets)
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

                                        <td>
                                            <a data-toggle="tooltip" title="Click to View Asset"
                                               href="{{ route('assets.show',  $assets->uuid) }}">
                                                {{ (!empty( $assets->name)) ?  $assets->name : ''}}
                                            </a>
                                        </td>
                                        <td>
                                            <a data-toggle="tooltip" title="Click to View Asset"
                                               href="{{ route('assets.show',  $assets->uuid) }}">
                                                {{ (!empty( $assets->description)) ?  $assets->description : ''}}
                                            </a>
                                        </td>
                                        <td>
                                            <img src="{{ asset('storage/assets/images/'.$assets->picture) }} "
                                                 height="35px" width="40px" alt="device image">
                                        </td>
                                        <td>{{ (!empty( $assets->asset_tag)) ?  $assets->asset_tag : ''}} </td>
                                        <td>{{ (!empty( $assets->serial_number)) ?  $assets->serial_number : ''}} </td>
                                        <td>{{ (!empty( $assets->model_number)) ?  $assets->model_number : ''}} </td>
                                        <td>{{ (!empty( $assets->make_number)) ?  $assets->make_number : ''}} </td>
                                        <td>{{ (!empty( $assets->AssetType->name)) ?  $assets->AssetType->name : ''}} </td>
                                        <td>{{ (!empty( $assets->price)) ?  $assets->price : ''}} </td>
                                        <td>
                                            <button vehice="button" id="view_ribbons" class="btn {{ (!empty($assets->status) && $assets->status == 1) ? " btn-danger " : "btn-success " }}
                                      btn-xs" onclick="postData({{$assets->id}}, 'actdeac');"><i class="fa {{ (!empty($assets->status) && $assets->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($assets->status) && $assets->status == 1) ? "De-Activate" : "Activate"}}
                                            </button>
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
                </div>
            </div>

        </div>
    </div>
</div>