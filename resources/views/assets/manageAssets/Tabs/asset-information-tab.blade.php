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
                            {{$asset->asset_status}}
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
                </tbody>
            </table>
        </div>
    </div>
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

            <img src="{{ asset('storage/assets/images/'.$asset->picture) }} "
                 class="card-img-top" alt="Wild Landscape"
                 style='height: 500%; width: 100%; object-fit: contain'/>

            <div class="card-body">
                <h2 class="card-title">Checked Out To</h2>
                <h4 class="card-title">Nkosana</h4>
                <p class="card-text">
                    Additional Info
                </p>
                <p class="card-text">
                    <small class="text-muted">Last updated 3 mins
                        ago</small>
                </p>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
</div>
