@extends('layouts.main_layout')
@section('page_dependencies')

    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">

@stop
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-barcode pull-right"></i>
                    <h3 class="box-title">List Assets </h3>
                </div>
                <div class="box-body">
                    <div style="overflow-X:auto;">
                        <div class="form-group">
                            <form class="form-horizontal" method="get" action="{{ route('asset.reports') }}">
                                {{ csrf_field() }}
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <div class="col-sm-5">
                                            <label>Asset Status</label>
                                            <select class="form-control select2 " style="width: 100%;"
                                                    id="status_id" name="status_id" data-select2-id="1" tabindex="-1"
                                                    aria-hidden="true">
                                                <option value="all">** Select a Status **</option>
                                                @foreach (\App\Models\Assets::STATUS_SELECT as $statusType)
                                                    <option value="{{ $statusType }}">{{ $statusType }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-sm-6">
                                            <label>Assset Types</label>
                                            <select class="form-control select2 " style="width: 100%;"
                                                    id="asset_type_id" name="asset_type_id" data-select2-id="1"
                                                    tabindex="-1" aria-hidden="true">
                                                <option value="0">** Select Asset Type **</option>
                                                @foreach( $assetTypes as $types)
                                                    <option value="{{ $types->id }}">{{ $types->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                    <div class="box-footer">
                                        <br>
                                        <button type="submit" class="btn btn-primary pull-left">Submit</button>
                                        <br>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <br>
                        <table class="table table-bordered user_datatable">
                            <thead>
                            <tr>
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

                            </tr>
                            </thead>
                            <tbody>
                            @if (count(array($assets)) > 0)
                                <ul class="products-list product-list-in-box">
                                    @foreach ($assets as  $asset)
                                        <tr id="categories-list">

                                            <td>
                                                {{ (!empty( $asset->name)) ?  $asset->name : ''}}

                                            </td>
                                            <td>
                                                {{ (!empty( $asset->description)) ?  $asset->description : ''}}
                                            </td>
                                            <td>
                                                <div class="popup-thumbnail img-responsive">
                                                    <img src="{{ asset('storage/assets/images/'.$asset->picture) }} "
                                                         height="35px" width="40px" alt="device image">
                                                </div>
                                            </td>
                                            <td>{{ (!empty( $asset->asset_tag)) ?  $asset->asset_tag : ''}} </td>
                                            <td>{{ (!empty( $asset->serial_number)) ?  $asset->serial_number : ''}} </td>
                                            <td>{{ (!empty( $asset->model_number)) ?  $asset->model_number : ''}} </td>
                                            <td>{{ (!empty( $asset->make_number)) ?  $asset->make_number : ''}} </td>
                                            <td>{{ (!empty( $asset->AssetType->name)) ?  $asset->AssetType->name : ''}} </td>
                                            <td>{{ (!empty( $asset->price)) ?  $asset->price : ''}} </td>
                                            <td>
                                                @if($asset->asset_status == 'Sold')
                                                    <span class="label label-warning">{{ (!empty( $asset->asset_status)) ?  $asset->asset_status : ''}}</span>
                                                @elseif($asset->asset_status == 'Missing')
                                                    <span class="label label-danger"> {{ (!empty( $asset->asset_status)) ?  $asset->asset_status : ''}}</span>
                                                @elseif($asset->asset_status == 'In Use')
                                                    <span class="label label-success"> {{ (!empty( $asset->asset_status)) ?  $asset->asset_status : ''}}</span>
                                                @elseif($asset->asset_status == 'Discarded')
                                                    <span class="label label-primary"> {{ (!empty( $asset->asset_status)) ?  $asset->asset_status : ''}}</span>
                                                @elseif($asset->asset_status == 'In Store')
                                                    <span class="label label-default"> {{ (!empty( $asset->asset_status)) ?  $asset->asset_status : ''}}</span>
                                                @elseif($asset->asset_status == 'Un Allocated')
                                                    <span class="label label-info"> {{ (!empty( $asset->asset_status)) ?  $asset->asset_status : ''}}</span>
                                                @endif
                                            </td>

                                        </tr>
                                @endforeach
                            @endif
                            </tbody>
                            <tfoot>
                            <tr>

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

                            </tr>
                            </tfoot>
                        </table>
                        <!-- /.box-body -->

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('page_script')
    <!-- DataTables -->
    {{--    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js"') }}"></script>--}}
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('custom_components/js/modal_ajax_submit.js') }}"></script>
    <script src="{{ asset('custom_components/js/deleteAlert.js') }}"></script>

    <script src="{{ asset('bower_components/bootstrap_fileinput/js/fileinput.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>


    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    {{--    <script src="{{ asset('custom_components/js/dataTable.js') }}"></script>--}}

    <!-- End Bootstrap File input -->
    <script type="text/javascript">


        $(function () {

            function postData(id, data) {
                if (data === 'actdeac') location.href = "{{route('assets.activate', '')}}" + "/" + id;
            }


            $('.user_datatable').DataTable({

                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: true,
                dom: 'Bfrtip',
                buttons: [
                    // 'copy', 'csv', 'excel',
                    {
                        extend: 'print',
                        title: 'Asset list Report',
                        exportOptions: {
                            stripHtml: false,
                            columns: ':visible:not(.not-export-col)'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Asset list Report',
                        //download: 'open',
                        exportOptions: {
                            stripHtml: true,
                            columns: ':visible:not(.not-export-col)'
                        },
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
                    },
                    {extend: 'copyHtml5', exportOptions: {columns: ':visible'}},
                    {extend: 'csvHtml5', title: 'CSV', exportOptions: {columns: ':visible'}},
                    // { extend: 'excelHtml5', title: 'Excel', exportOptions: { columns: ':visible' } },
                    {
                        text: 'excel',
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        }
                    },

                ]

            });

        });


    </script>
@stop

