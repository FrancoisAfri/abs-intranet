@extends('layouts.main_layout')
@section('page_dependencies')

    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">

@stop
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-barcode pull-right"></i>
                    <h3 class="box-title"> Store</h3>
                </div>
                <div class="box-body">
                    <div style="overflow-X:auto;">
                        <table id="example2" class="table table-bordered table-hover">
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
                            @if (count($store) > 0)
                                <ul class="products-list product-list-in-box">
                                    @foreach ($store as $key => $stores)
                                        <tr id="categories-list">
                                            <td nowrap>
                                                <button vehice="button" id="edit_storeroom" class="btn btn-warning  btn-xs"
                                                        data-toggle="modal" data-target="#edit-storeroom-modal"
                                                        data-id="{{ $stores->id }}"
                                                        data-name="{{ $stores->name }}"
                                                        data-description="{{$stores->description}}"><i
                                                            class="fa fa-pencil-square-o"></i> Edit
                                                </button>
                                            </td>
                                            <td>{{ (!empty( $stores->name)) ?  $stores->name : ''}} </td>
                                            <td>{{ (!empty( $stores->description)) ?  $stores->description : ''}} </td>
                                            <td>
                                                <!--   leave here  -->
                                                <button vehice="button" id="view_ribbons" class="btn {{ (!empty($stores->status) && $stores->status == 1) ? " btn-danger " : "btn-success " }}
                                      btn-xs" onclick="postData({{$stores->id}}, 'actdeac');"><i class="fa {{ (!empty($stores->status) && $stores->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($stores->status) && $stores->status == 1) ? "De-Activate" : "Activate"}}
                                                </button>
                                            </td>
                                            <td>
                                                <form action="{{ route('store-room.destroy', $stores->id) }}"
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
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="button" id="cat_module" class="btn btn-default pull-right" data-toggle="modal" data-target="#add-storeroom-modal">Add Store</button>
                            <button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                    </div>
                </div>
                @include('assets.store.partials.create')
                @include('assets.store.partials.edit')

            </div>
            @endsection

            @section('page_script')
                <!-- DataTables -->
                <script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
                <script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
                <script src="/custom_components/js/modal_ajax_submit.js"></script>
                <script src="/custom_components/js/deleteAlert.js"></script>
                <script src="/custom_components/js/dataTable.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
                <!-- End Bootstrap File input -->
                <script type="text/javascript">

                    function postData(id , data ){
                        if(data === 'actdeac') location.href = "{{route('store.activate', '')}}"+"/"+id;
                    }

                    $('#back_button').click(function () {
                        location.href = '{{route('assets.settings')}}';
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

                </script>

                <script>

                    $(function () {
                        $('.modal').on('show.bs.modal', reposition);

                        $('#add-storeroom').on('click', function() {
                            //console.log('strUrl');
                            let strUrl = '/assets/store-room';
                            let modalID = 'add-storeroom-modal';
                            let id = 1;
                            let objData = {
                                name: $('#'+modalID).find('#name').val(),
                                description: $('#'+modalID).find('#description').val(),
                                _token: $('#'+modalID).find('input[name=_token]').val()
                            };
                            let submitBtnID = 'add-storeroom';
                            let redirectUrl = '{{ route('store-room.index') }}';
                            let successMsgTitle = 'Storeroom Added!';
                            let successMsg = 'The Store room Type has been updated successfully.';
                            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                        });

                        // show modal info
                        let storeId;
                        $('#edit-storeroom-modal').on('show.bs.modal', function (e) {
                            let btnEdit = $(e.relatedTarget);
                            storeId = btnEdit.data('id');
                            let name = btnEdit.data('name');
                            let description = btnEdit.data('description');
                            let modal = $(this);
                            modal.find('#name').val(name);
                            modal.find('#description').val(description);
                        });

                        // update modal
                        $('#edit-storeroom').on('click', function () {
                            // console.log(licenceId)
                            let strUrl = '/assets/store-room/' + storeId;
                            let modalID = 'edit-storeroom-modal';
                            let objData = {
                                name: $('#'+modalID).find('#name').val(),
                                description: $('#'+modalID).find('#description').val(),
                                _token: $('#'+modalID).find('input[name=_token]').val()
                            };
                            let submitBtnID = 'edit-storeroom';
                            let redirectUrl = '{{route('store-room.index')}}';
                            let successMsgTitle = 'Changes Saved!';
                            let successMsg = 'The Record has been updated successfully.';
                            let Method = 'PATCH';
                            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
                        });



                    });
                </script>
@endsection