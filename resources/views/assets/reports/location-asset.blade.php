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
                    <h3 class="box-title"> Assets Location</h3>
                </div>
                <div class="box-body">
                    <div style="overflow-X:auto;">
                        <div class="form-group">
                            <form class="form-horizontal" method="get" action="{{ route('location.reports') }}">
                                {{ csrf_field() }}
                                <div class="col-md-10">
                                    <div class="form-group">

                                        <div class="col-sm-4">
                                            <label>Assset Type</label>
                                            <select class="form-control select2 " style="width: 100%;"
                                                    id="asset_id" name="asset_id" data-select2-id="1"
                                                    tabindex="-1" aria-hidden="true">
                                                <option value="0">** Select Asset Type **</option>
                                                @foreach( $assets as $types)
                                                    <option value="{{ $types->id }}">{{ $types->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-sm-4">
                                            <label> User</label>
                                            <select class="form-control select2 " style="width: 100%;"
                                                    id="user_id" name="user_id" data-select2-id="1" tabindex="-1"
                                                    aria-hidden="true">
                                                <option value="0">** Select User **</option>
                                                @foreach( $users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->first_name . ' ' .   $user->surname}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-sm-4">
                                            <label>Assset Location</label>
                                            <select class="form-control select2 " style="width: 100%;"
                                                    id="store_id" name="store_id" data-select2-id="1"
                                                    tabindex="-1" aria-hidden="true">
                                                <option value="0">** Select Location **</option>
                                                @foreach( $stores as $store)
                                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
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
                                <th>Asset Name</th>
                                <th>User</th>
                                <th style="width: 5px; text-align: center;"> Location</th>
                                <th>Status</th>

                            </tr>
                            </thead>
                            <tbody>
                            @if (count(array($assetTransfer)) > 0)
                                <ul class="products-list product-list-in-box">
                                    @foreach ($assetTransfer as  $asset)
                                        <tr id="categories-list">

                                            <td>
                                                {{ (!empty( $asset->AssetTransfers->name)) ?  $asset->AssetTransfers->name : ''}}
                                            </td>
                                            <td>
                                                {{ (!empty( $asset->HrPeople->first_name)) ?  $asset->HrPeople->first_name . ' ' .  $asset->HrPeople->first_name: ''}}
                                            </td>

                                            <td>{{ (!empty( $asset->store->name)) ?  $asset->store->name : ''}} </td>

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
                                <th>Asset Name</th>
                                <th>User</th>
                                <th style="width: 5px; text-align: center;"> Location</th>
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
                        title: 'Asset Location Report',
                        exportOptions: {
                            stripHtml: false,
                            columns: ':visible:not(.not-export-col)'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Asset Location Report',
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

