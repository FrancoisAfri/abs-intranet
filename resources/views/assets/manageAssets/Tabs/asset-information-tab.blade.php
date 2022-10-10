<div class="col-lg-6 col-sm-6 pull-left">
    <br>
    <br>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $asset->name}}</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-striped table-hover">
                <tbody>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Asset Name</strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{$asset->name}}
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Asset Description</strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{$asset->description}}
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Asset Type</strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{$asset->AssetType->name}}
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>
                                Status
                            </strong>
                        </div>
                    </td>
                    <td>
                        <span class="label label-success">{{$asset->asset_status}}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong> Serial Number</strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{$asset->serial_number}}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Model Number</strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{$asset->model_number}}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Make Number</strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{$asset->make_number}}
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Price</strong>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-6">
                            {{$asset->price}}
                        </div>
                    </td>

                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <strong>Update Status</strong>
                        </div>
                    </td>
                    @if (isset($view_by_admin) && $view_by_admin === 1)
                    <td>
                        <div class="col-md-6">
                            <button type="button" id="efit_status" class="btn btn-secondary pull-right btn-sm"
                                    data-toggle="modal" data-target="#change-asset_status-modal"
                                    data-id="{{ $asset->id }}"
                                    data-name="{{ $asset->asset_status}}">Change Status
                            </button>
                        </div>
                    </td>
                    @endif

                </tr>

                </tbody>
            </table>
        </div>
    </div>
    @include('assets.manageAssets.partials.asset_status')
</div>

<div class="col-lg-6 col-sm-6">
    <br>
    <br>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $asset->name}}</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">

            <img src="{{ (!empty($asset->picture)) ? Storage::disk('local')->url("assets/images/$asset->picture") : 'http://placehold.it/60x50' }}"
                 class="card-img-top" alt="Wild Landscape"
                 style='height: 500%; width: 100%; object-fit: contain'/>

        </div>
        <!-- /.box-body -->

    </div>
</div>
