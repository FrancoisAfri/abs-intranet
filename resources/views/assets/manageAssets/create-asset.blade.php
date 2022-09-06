@extends('layouts.main_layout')
@section('page_dependencies')

    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">
@stop
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-barcode pull-right"></i>
                    <h3 class="box-title"> Assets</h3>
                </div>

                <div class="box-body">
                    <div class="card my-2">
                        <div class="card-body">
                            <div class="form-group">
                                <label><strong>Status :</strong></label>
                                <select id='asset_status' class="form-control" style="width: 200px">
                                    <option value="">--Select Status--</option>
                                    @foreach (\App\Models\Assets::STATUS_SELECT as $key => $status)
                                        <option value="{{$key}}"> {{ (!empty( $status)) ?  $status : ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div style="overflow-X:auto;">
                        {{--                        <table id="example2" class="table table-bordered table-hover">--}}
                        <table id="example2" class="table table-bordered data-table my-2">
                            <thead>
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
                                <th style="width: 5px; text-align: center;">Licence Type</th>
                                <th style="width: 5px; text-align: center;">price</th>
                                <th style="width: 5px; text-align: center;">Availability</th>
                                <th>Status</th>
                                <th style="width: 5px; text-align: center;">.</th>

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
                                                        data-toggle="modal" data-target="#edit-licence-modal"
                                                        data-id="{{ $assets->id }}"
                                                        data-name="{{ $assets->name }}"
                                                        data-description="{{$assets->description}}"><i
                                                            class="fa fa-pencil-square-o"></i> Edit
                                                </button>
                                            </td>

                                            <td>{{ (!empty( $assets->name)) ?  $assets->name : ''}} </td>
                                            <td>{{ (!empty( $assets->description)) ?  $assets->description : ''}} </td>
                                            <td><img src="{{ asset('storage/assets/images/'.$assets->picture) }} "
                                                     height="70px" width="70px" alt="device image"></td>
                                            <td>{{ (!empty( $assets->asset_tag)) ?  $assets->asset_tag : ''}} </td>
                                            <td>{{ (!empty( $assets->serial_number)) ?  $assets->serial_number : ''}} </td>
                                            <td>{{ (!empty( $assets->model_number)) ?  $assets->model_number : ''}} </td>
                                            <td>{{ (!empty( $assets->make_number)) ?  $assets->make_number : ''}} </td>
                                            <td>{{ (!empty( $assets->AssetType->name)) ?  $assets->AssetType->name : ''}} </td>
                                            <td>{{ (!empty( $assets->LicenseType->name)) ?  $assets->LicenseType->name : ''}} </td>
                                            <td>{{ (!empty( $assets->price)) ?  $assets->price : ''}} </td>
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
                                                @endif
                                            </td>

                                            <td>
                                                <!--   leave here  -->
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
                                <th style="width: 5px; text-align: center;">Licence Type</th>
                                <th style="width: 5px; text-align: center;">price</th>
                                <th style="width: 5px; text-align: center;">Availability</th>
                                <th>Asset Status</th>
                                <th style="width: 5px; text-align: center;">.</th>
                                {{--                                <th style="width: 5px; text-align: center;"></th>--}}
                            </tr>
                            </tfoot>
                        </table>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="button" id="cat_module" class="btn btn-default pull-right" data-toggle="modal"
                                    data-target="#add-asset-modal">Add Asset Type
                            </button>
                        </div>
                    </div>
                </div>
                @include('assets.manageAssets.partials.create')
                {{--                @include('assets.assetType.partials.edit')--}}
            </div>
            @endsection

            @section('page_script')
                <!-- DataTables -->
                <script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
                <script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
                <script src="/custom_components/js/modal_ajax_submit.js"></script>
                <script src="/custom_components/js/deleteAlert.js"></script>
{{--                <script src="/custom_components/js/dataTable.js"></script>--}}
                <!-- the main fileinput plugin file -->
                <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
                <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
                <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
                <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
                <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
                <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
                <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>

                <!-- End Bootstrap File input -->
                <script type="text/javascript">


                    function postData(id, data) {
                        if (data === 'actdeac') location.href = "{{route('assets.activate', '')}}" + "/" + id;
                    }

                    $(function () {
                        $('#example2').DataTable({
                            "paging": true,
                            "lengthChange": true,
                            "searching": true,
                            "ordering": true,
                            "info": true,
                            "autoWidth": true,
                            dom: 'Bfrtip',
                            buttons: [
                                'copy', 'csv', 'excel', 'pdf', 'print'
                            ]
                        });
                    });

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
                        $('.modal').on('show.bs.modal', reposition);

                        $('#add-asset').on('click', function () {
                            let strUrl = '{{route('store')}}';
                            let modalID = 'add-asset-modal';
                            let formName = 'add-asset-form';

                            let submitBtnID = 'add-asset';
                            let redirectUrl = '{{ route('index') }}';
                            let successMsgTitle = 'Asset Type Added!';
                            let successMsg = 'The Asset Type has been updated successfully.';
                            modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                            // modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                        });

                        // show modal info
                        let assetId;
                        $('#edit-asset-modal').on('show.bs.modal', function (e) {
                            let btnEdit = $(e.relatedTarget);
                            assetId = btnEdit.data('id');
                            let name = btnEdit.data('name');
                            let description = btnEdit.data('description');
                            let modal = $(this);
                            modal.find('#name').val(name);
                            modal.find('#description').val(description);
                        });

                        // update modal
                        $('#edit-asset').on('click', function () {

                            let strUrl = '/assets/type/' + assetId;
                            let modalID = 'edit-asset-modal';
                            let objData = {
                                name: $('#' + modalID).find('#name').val(),
                                description: $('#' + modalID).find('#description').val(),
                                _token: $('#' + modalID).find('input[name=_token]').val()
                            };
                            let submitBtnID = 'edit-asset';
                            let redirectUrl = '{{route('type.index')}}';
                            let successMsgTitle = 'Changes Saved!';
                            let successMsg = 'The Fleet Type has been updated successfully.';
                            let Method = 'PATCH';
                            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
                        });


                    });
                </script>
@stop