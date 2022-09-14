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
                    <h3 class="box-title"> Create and Manage Assets </h3>
                </div>
                <div class="box-body">
                    <div class="box-header">

                        <div class="form-group container-sm">
                            <form class="form-horizontal" method="get" action="{{ route('index') }}">
                                {{ csrf_field() }}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-sm-4">
                                            <label>Asset Status</label>
                                            <select class="form-control select2 " style="width: 100%;"
                                                   id="status_id" name="status_id" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                                @foreach (\App\Models\Assets::STATUS_SELECT as $values)
                                                    <option value="{{ $values }}">{{ $values }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-sm-4">
                                            <label>Assset Types</label>
                                            <select class="form-control select2 " style="width: 100%;"
                                                 id="asset_type_id" name="asset_type_id"   data-select2-id="1" tabindex="-1" aria-hidden="true">
                                                @foreach($assetType as $types)
                                                    <option value="{{ $types->id }}">{{ $types->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary pull-left">Submit</button><br>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <br>

{{--                        @foreach($assetType as $assets)--}}
{{--                            <option value="{{ $assets->id }}">{{ $assets->name }}</option>--}}
{{--                        @endforeach--}}

                        <button type="button" id="cat_module" class="btn btn-default pull-right" data-toggle="modal"
                                data-target="#add-asset-modal">Add Asset
                        </button>
                    </div>
                    <div style="overflow-X:auto;">
                        <table id=" " class="asset table table-bordered data-table my-2">
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
                            @if (count($asserts) > 0)
                                <ul class="products-list product-list-in-box">
                                    @foreach ($asserts as $key => $assets)
                                        <tr id="categories-list">
                                            <td nowrap>
                                                <button vehice="button" id="edit_licence"
                                                        class="btn btn-warning  btn-xs"
                                                        data-toggle="modal" data-target="#edit-asset-modal"
                                                        data-id="{{ $assets->id }}"
                                                        data-name="{{ $assets->name }}"
                                                        data-description="{{$assets->description}}"
                                                        data-serial_number="{{$assets->serial_number}}"
                                                        data-asset_tag="{{$assets->asset_tag}}"
                                                        data-model_number="{{$assets->model_number}}"
                                                        data-make_number="{{$assets->make_number}}"
                                                        data-price="{{$assets->price}}"
                                                        data-asset_type_id="{{$assets->asset_type_id}}"
                                                        data-picture="{{$assets->picture}}"><i
                                                            class="fa fa-pencil-square-o"></i> Edit
                                                </button>
                                            </td>
                                            <td>
                                                <a data-toggle="tooltip" title="Click to View Asset"
                                                   href="{{ route('assets.show',['assets' => $assets->uuid, 'tab' => $info])}}">
                                                    {{ (!empty( $assets->name)) ?  $assets->name : ''}}
                                                </a>
                                            </td>
                                            <td>
                                                <a data-toggle="tooltip" title="Click to View Asset"
                                                   href="{{ route('assets.show',['assets' => $assets->uuid, 'tab' => $info]) }}">
                                                    {{ (!empty( $assets->description)) ?  $assets->description : ''}}
                                                </a>
                                            </td>
                                            <td>
                                                <div class="popup-thumbnail img-responsive">
                                                    <img src="{{ asset('storage/assets/images/'.$assets->picture) }} "
                                                         height="35px" width="40px" alt="device image">
                                                </div>
                                            </td>
                                            <td>{{ (!empty( $assets->asset_tag)) ?  $assets->asset_tag : ''}} </td>
                                            <td>{{ (!empty( $assets->serial_number)) ?  $assets->serial_number : ''}} </td>
                                            <td>{{ (!empty( $assets->model_number)) ?  $assets->model_number : ''}} </td>
                                            <td>{{ (!empty( $assets->make_number)) ?  $assets->make_number : ''}} </td>
                                            <td>{{ (!empty( $assets->AssetType->name)) ?  $assets->AssetType->name : ''}} </td>
                                            <td>{{ (!empty( $assets->price)) ?  $assets->price : ''}} </td>
                                            <td>
                                                <span class="label label-info">{{ (!empty( $assets->asset_status)) ?  $assets->asset_status : ''}}</span>
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
                            </tfoot>
                        </table>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="button" id="cat_module" class="btn btn-default pull-right" data-toggle="modal"
                                    data-target="#add-asset-modal">Add Asset
                            </button>
                        </div>
                    </div>
                </div>
                @include('assets.manageAssets.partials.create')
                @include('assets.manageAssets.partials.edit')
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


        function postData(id, data) {
            if (data === 'actdeac') location.href = "{{route('assets.activate', '')}}" + "/" + id;
        }

        $('.popup-thumbnail').click(function(){
            $('.modal-body').empty();
            $($(this).parents('div').html()).appendTo('.modal-body');
            $('#modal').modal({show:true});
        });




        //TODO WILL CREATE A SIGLE GLOBAL FILE
        $('.delete_confirm').click(function (event) {

            var form = $(this).closest("form");

            var name = $(this).data("name");

            event.preventDefault();

            swal({

                title: `Are you sure you want to delete this record?`,
                text: "If you delete this, it will be gone forever.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                        swal("Poof! Your Record has been deleted!", {
                            icon: "success",
                        });
                    }

                });

        });

        $(function () {

            $('table.asset').DataTable({

                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });

            $('.modal').on('show.bs.modal', reposition);

            $('#add-asset').on('click', function () {
                let strUrl = '{{route('store')}}';
                let modalID = 'add-asset-modal';
                let formName = 'add-asset-form';

                console.log(formName)
                let submitBtnID = 'add-asset';
                let redirectUrl = '{{ route('index') }}';
                let successMsgTitle = 'Asset Added!';
                let successMsg = 'Record has been updated successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            // show modal info
            let assetId;
            $('#edit-asset-modal').on('show.bs.modal', function (e) {
                let btnEdit = $(e.relatedTarget);
                assetId = btnEdit.data('id');
                let name = btnEdit.data('name');
                let description = btnEdit.data('description');
                let serial_number = btnEdit.data('serial_number');
                let asset_tag = btnEdit.data('asset_tag');
                let model_number = btnEdit.data('model_number');
                let make_number = btnEdit.data('make_number');
                let asset_status = btnEdit.data('asset_status');
                let price = btnEdit.data('price');
                let asset_type_id = btnEdit.data('asset_type_id');
                let modal = $(this);
                modal.find('#name').val(name);
                modal.find('#description').val(description);
                modal.find('#serial_number').val(serial_number);
                modal.find('#asset_tag').val(asset_tag);
                modal.find('#model_number').val(model_number);
                modal.find('#make_number').val(make_number);
                modal.find('#price').val(price);
                modal.find('#asset_type_id').val(asset_type_id);
            });

            // update modal
            $('#edit-asset').on('click', function () {

                let strUrl = '/assets/update/' + assetId;
                let modalID = 'edit-asset-modal';
                let objData = {
                    name: $('#'+modalID).find('#name').val(),
                    description: $('#'+modalID).find('#description').val(),
                    serial_number: $('#'+modalID).find('#serial_number').val(),
                    asset_tag: $('#'+modalID).find('#asset_tag').val(),
                    model_number: $('#'+modalID).find('#model_number').val(),
                    make_number: $('#'+modalID).find('#make_number').val(),
                    asset_type_id: $('#'+modalID).find('#asset_type_id').val(),
                    price: $('#'+modalID).find('#price').val(),
                    asset_status: $('#'+modalID).find('#asset_status').val(),
                    picture: $('#'+modalID).find('#picture').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };

                let submitBtnID = 'edit-asset';
                let redirectUrl = '{{ route('index') }}';
                let successMsgTitle = 'Changes Saved!';
                let successMsg = 'Record has been updated successfully.';
                let Method = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });

        });
    </script>
@stop