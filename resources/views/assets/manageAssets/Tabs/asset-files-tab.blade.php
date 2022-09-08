<div class="row">
    <div class="col-md-12 col-md-offset-0">
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-barcode pull-right"></i>
                <h3 class="box-title"> Asset Files</h3>
            </div>
            <div class="box-body">
                <div class="card my-2">
                </div>
                <div style="overflow-X:auto;">
                    <table id=" " class="display table table-bordered data-table my-2">
                        <thead>
                        <tr>
                            <th>File Type</th>
                            <th>Images</th>
                            <th style="width: 5px; text-align: center;">File</th>
                            <th style="width: 5px; text-align: center;">  File Size</th>
                            <th style="width: 5px; text-align: center;">Notes</th>
                            <th style="width: 5px; text-align: center;">Download</th>
                            <th style="width: 5px; text-align: center;"> Created At</th>
                            <th>Status</th>
                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($assetFiles) > 0)
                            <ul class="products-list product-list-in-box">
                                @foreach ($assetFiles as $key => $assets)
                                    <td></td>
                                        <td>{{ (!empty( $assets->name)) ?  $assets->name : ''}}</td>
                                        <td>{{ (!empty( $assets->name)) ?  $assets->name : ''}}</td>
                                        <td>{{ (!empty( $assets->name)) ?  $assets->name : ''}}</td>
                                        <td>{{ (!empty( $assets->name)) ?  $assets->name : ''}}</td>
                                        <td>
                                                {{ (!empty( $assets->description)) ?  $assets->description : ''}}
                                        </td>
                                        <td>
                                            <img src="{{ asset('storage/assets/files/'.$assets->document) }} "
                                                 height="35px" width="40px" alt="device image">
                                        </td>

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
                            <th>File Type</th>
                            <th>Images</th>
                            <th style="width: 5px; text-align: center;">File</th>
                            <th style="width: 5px; text-align: center;">  File Size</th>
                            <th style="width: 5px; text-align: center;">Notes</th>
                            <th style="width: 5px; text-align: center;">Download</th>
                            <th style="width: 5px; text-align: center;"> Created At</th>
                            <th>Status</th>
                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        </tfoot>
                    </table>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" id="" class="btn btn-default pull-right" data-toggle="modal"
                                data-target="#upload-file-modal">
                            <i class="fa fa-paperclip" aria-hidden="true"></i> Upload
                        </button>

                    </div>
                </div>
            </div>
            @include('assets.manageAssets.partials.upload')
        </div>
    </div>
</div>



