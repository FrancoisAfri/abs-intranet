@extends('layouts.main_layout')
@section('page_dependencies')

    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
@stop
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-barcode pull-right"></i>
                    <h3 class="box-title"> Licence Types</h3>
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
                            @if (count($licenceType) > 0)
                                <ul class="products-list product-list-in-box">
                                    @foreach ($licenceType as $key => $licenceTypes)
                                        <tr id="categories-list">
                                            <td nowrap>
                                                <button vehice="button" id="edit_licence" class="btn btn-warning  btn-xs"
                                                        data-toggle="modal" data-target="#edit-licence-modal"
                                                        data-id="{{ $licenceTypes->id }}"
                                                        data-name="{{ $licenceTypes->name }}"
                                                        data-description="{{$licenceTypes->description}}"><i
                                                            class="fa fa-pencil-square-o"></i> Edit
                                                </button>
                                            </td>

                                            <td>{{ (!empty( $licenceTypes->name)) ?  $licenceTypes->name : ''}} </td>
                                            <td>{{ (!empty( $licenceTypes->description)) ?  $licenceTypes->description : ''}} </td>
                                            <td>
                                                <!--   leave here  -->
                                                <button vehice="button" id="view_ribbons" class="btn {{ (!empty($licenceTypes->status) && $licenceTypes->status == 1) ? " btn-danger " : "btn-success " }}
                                      btn-xs" onclick="postData({{$licenceTypes->id}}, 'actdeac');"><i class="fa {{ (!empty($licenceTypes->status) && $licenceTypes->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($licenceTypes->status) && $licenceTypes->status == 1) ? "De-Activate" : "Activate"}}
                                                </button>
                                            </td>
                                            <td>
                                                <form action="{{ route('licence.destroy', $licenceTypes->id ) }}" method="POST"
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
                            <button type="button" id="cat_module" class="btn btn-default pull-right" data-toggle="modal" data-target="#add-licence-modal">Add Licence Type</button>
                            <button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                    </div>
                </div>
                @include('assets.licenseType.partials.create')
                @include('assets.licenseType.partials.edit')
            </div>
        </div>
    </div>
            @endsection

            @section('page_script')
                <!-- DataTables -->
                <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
                <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
                <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
                <script src="{{ asset('custom_components/js/modal_ajax_submit.js') }}"></script>
                <script src="{{ asset('custom_components/js/deleteAlert.js') }}"></script>
                <script src="{{ asset('custom_components/js/dataTable.js') }}"></script>

                <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
                <!-- End Bootstrap File input -->
                <script type="text/javascript">

                    function postData(id , data ){
                        if(data === 'actdeac') location.href = "{{route('licence.activate', '')}}"+"/"+id;
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

                        $('#add-licence').on('click', function() {
                            //console.log('strUrl');
                            let strUrl = '/assets/licence';
                            let modalID = 'add-licence-modal';
                            let id = 1;
                            let objData = {
                                name: $('#'+modalID).find('#name').val(),
                                description: $('#'+modalID).find('#description').val(),
                                _token: $('#'+modalID).find('input[name=_token]').val()
                            };
                            let submitBtnID = 'add-licence';
                            let redirectUrl = '{{ route('licence.index') }}';
                            let successMsgTitle = 'licence Type Added!';
                            let successMsg = 'The licence Type has been updated successfully.';
                            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                        });

                        // show modal info
                        let licenceId;
                        $('#edit-licence-modal').on('show.bs.modal', function (e) {
                            let btnEdit = $(e.relatedTarget);
                            licenceId = btnEdit.data('id');
                            let name = btnEdit.data('name');
                            let description = btnEdit.data('description');
                            let modal = $(this);
                            modal.find('#name').val(name);
                            modal.find('#description').val(description);
                        });

                        // update modal
                        $('#edit-licence').on('click', function () {

                            let strUrl = '/assets/licence/' + licenceId;
                            let modalID = 'edit-licence-modal';
                            let objData = {
                                name: $('#'+modalID).find('#name').val(),
                                description: $('#'+modalID).find('#description').val(),
                                _token: $('#'+modalID).find('input[name=_token]').val()
                            };

                            let submitBtnID = 'edit-licence';
                            let redirectUrl = '{{route('licence.index')}}';
                            let successMsgTitle = 'Changes Saved!';
                            let successMsg = 'The Record has been updated successfully.';
                            let Method = 'PATCH';
                            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
                        });

                    });
                </script>
@endsection