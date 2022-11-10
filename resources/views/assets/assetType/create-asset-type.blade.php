@extends('layouts.main_layout')
@section('page_dependencies')

    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/select2/select2.min.css">

@stop
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-barcode pull-right"></i>
                    <h3 class="box-title"> Assets Types</h3>
                </div>
                <div class="box-body">
                    <div style="overflow-X:auto;">
                            <table id=" " class="display table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th style="width: 10px; text-align: center;">#</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th style="width: 5px; text-align: center;">.</th>
                                <th style="width: 5px; text-align: center;">.</th>
                                {{--                                <th style="width: 5px; text-align: center;">.</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($assetType) > 0)
                                <ul class="products-list product-list-in-box">
                                    @foreach ($assetType as $key => $assetTypes)
                                        <tr id="categories-list">
                                            <td nowrap>
                                                <button vehice="button" id="edit_asset" class="btn btn-warning  btn-xs"
                                                        data-toggle="modal" data-target="#edit-asset-modal"
                                                        data-id="{{ $assetTypes->id }}"
                                                        data-name="{{ $assetTypes->name }}"
                                                        data-description="{{$assetTypes->description}}"><i
                                                            class="fa fa-pencil-square-o"></i> Edit
                                                </button>
                                            </td>
                                            <td>{{ $assetTypes->name ?? ''}} </td>
                                            <td>{{ $assetTypes->description ?? ''}} </td>
                                            <td>
                                                <!--   leave here  -->
                                                <button vehice="button" id="view_ribbons" class="btn {{ (!empty($assetTypes->status) && $assetTypes->status == 1) ? " btn-danger " : "btn-success " }}
                                      btn-xs" onclick="postData({{$assetTypes->id}}, 'actdeac');"><i class="fa {{ (!empty($assetTypes->status) && $assetTypes->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($assetTypes->status) && $assetTypes->status == 1) ? "De-Activate" : "Activate"}}
                                                </button>
                                            </td>
                                            <td>
                                                <form action="{{ route('type.destroy', $assetTypes->id) }}"
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
                                <th style="width: 5px; text-align: center;"></th>
                                <th style="width: 5px; text-align: center;"></th>
                                {{--                                <th style="width: 5px; text-align: center;"></th>--}}
                            </tr>
                            </tfoot>
                        </table>

                        <div class="box-footer">
                            <button type="button" id="cat_module" class="btn btn-default pull-right" data-toggle="modal" data-target="#add-asset-modal">Add Asset Type</button>
                            <button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>

                    </div>
                </div>
                @include('assets.assetType.partials.create')
                @include('assets.assetType.partials.edit')
            </div>
            @endsection

            @section('page_script')
                <!-- DataTables -->
                <script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
                <script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
                <script src="/custom_components/js/modal_ajax_submit.js"></script>
                <script src="/custom_components/js/deleteAlert.js"></script>
                <script src="/custom_components/js/dataTable.js"></script>
                <script src="{{ asset('custom_components/js/deleteModal.js') }}"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
                {{--      data tables add ons         --}}


                <script type="text/javascript">

                    function postData(id , data ){
                        if(data === 'actdeac') location.href = "{{route('type.activate', '')}}"+"/"+id;
                    }

                    $('#back_button').click(function () {
                        location.href = '{{route('assets.settings')}}';
                    });


                </script>

                <script>

                    $(function () {
                        //Initialize Select2 Elements

                         $('#add-asset').on('click', function() {

                            let strUrl = '/assets/type';
                            let modalID = 'add-asset-modal';
                            let objData = {
                                name: $('#'+modalID).find('#name').val(),
                                description: $('#'+modalID).find('#description').val(),
                                _token: $('#'+modalID).find('input[name=_token]').val()
                            };

                            console.log(objData)

                            let submitBtnID = 'add-asset';
                            let redirectUrl = '{{ route('type.index') }}';
                            let successMsgTitle = 'Asset Type Added!';
                            let successMsg = 'The Asset Type has been updated successfully.';
                            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
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
                                name: $('#'+modalID).find('#name').val(),
                                description: $('#'+modalID).find('#description').val(),
                                _token: $('#'+modalID).find('input[name=_token]').val()
                            };
                            let submitBtnID = 'edit-asset';
                            let redirectUrl = '{{route('type.index')}}';
                            let successMsgTitle = 'Changes Saved!';
                            let successMsg = 'Record has been updated successfully.';
                            let Method = 'PATCH';
                            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
                        });

                    });
                </script>
@endsection