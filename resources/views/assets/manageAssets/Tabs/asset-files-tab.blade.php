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

                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>File Type</th>
                            <th>Images</th>
                            <th style="width: 5px; text-align: center;">File</th>
                            <th style="width: 5px; text-align: center;">Notes</th>
                            <th style="width: 5px; text-align: center;"> Created At</th>
                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($assetFiles) > 0)
                            <tr class="products-list product-list-in-box">
                                @foreach ($assetFiles as $key => $assets)
                                    <td>
                                        @if( pathinfo($assets->document, PATHINFO_EXTENSION) == 'pdf')
                                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                        @elseif(pathinfo($assets->document, PATHINFO_EXTENSION) == 'jpg'||'jpg'||'png'||'jpeg'||'png'||'gif')
                                            <i class="fa fa-file-image-o" aria-hidden="true"></i>
                                        @elseif(pathinfo($assets->document, PATHINFO_EXTENSION) ==  'doc'||'docx')
                                            <i class="fa fa-file-word-o" aria-hidden="true"></i>
                                        @elseif(pathinfo($assets->document, PATHINFO_EXTENSION) ==  'zip'||'rar')
                                            <i class="fa fa-file-zip-o" aria-hidden="true"></i>
                                        @elseif(pathinfo($assets->document, PATHINFO_EXTENSION) ==  'xls'||'xlsx')
                                            <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                        @elseif(pathinfo($assets->document, PATHINFO_EXTENSION) ==  'xls'||'xlsx')
                                            <i class="fa fa-file-text" aria-hidden="true"></i>
                                        @elseif(pathinfo($assets->document, PATHINFO_EXTENSION) ==  'lic'||'xml')
                                            <i class="fa fa-file-code-o" aria-hidden="true"></i>
                                        @endif
                                    </td>
                                    <td>

                                        <img src="{{ asset('storage/assets/files/'.($assets->document ?? '') ) }} "
                                             height="35px" width="40px" alt=" ">
                                    </td>
                                    <td>{{ $assets->document ??  ''}}</td>
                                    <td>{{ $assets->description ??  ''}}</td>

                                    <td>{{ $assets->date_added ??  ''}}</td>

                                    <td>
                                        <form action="{{ route('file.destroy', $assets->id) }}"
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
                            <th style="width: 5px; text-align: center;">Notes</th>
                            <th style="width: 5px; text-align: center;"> Created At</th>
                            <th style="width: 5px; text-align: center;"></th>

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



