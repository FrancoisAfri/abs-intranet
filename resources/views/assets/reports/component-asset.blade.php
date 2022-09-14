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
                    <h3 class="box-title"> Assets </h3>
                </div>
                <div class="box-body">
                    <div style="overflow-X:auto;">
                        <div class="form-group">
                            <label for="inputlg">Large input</label>
                            <div id="search2"></div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-3" id="search1">
                                </div>
                                <div class="col-xs-3" id="search2">
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered user_datatable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th style="width: 5px; text-align: center;">Device Image</th>
                                <th style="width: 5px; text-align: center;">size</th>
                                <th style="width: 5px; text-align: center;">Asset Name</th>
                                <th>Status</th>

                            </tr>
                            </thead>
                            <tbody>
                            @if (count($componentList) > 0)
                                <ul class="products-list product-list-in-box">
                                    @foreach ($componentList as $key => $asset)
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
                                            <td>{{ (!empty( $asset->size)) ?  $asset->size : ''}} </td>
                                            <td>{{ (!empty( $asset->AssetsList->name)) ?  $asset->AssetsList->name : ''}} </td>
                                            <td>{{ (!empty( $asset->status)) ?  $asset->status : ''}} </td>

                                        </tr>
                                @endforeach
                            @endif
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th style="width: 5px; text-align: center;">Device Image</th>
                                <th style="width: 5px; text-align: center;">size</th>
                                <th style="width: 5px; text-align: center;">Asset Name</th>
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
                initComplete: function () {
                    let counter = 0;
                    this.api().columns([7, 9]).every(function () {
                        let column = this;
                        counter++;
                        let select = $('<select><option value="Name"></option></select>')
                            .appendTo($('#search' + counter))
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>');
                        });
                    });
                }
            });

        });




    </script>
@stop

