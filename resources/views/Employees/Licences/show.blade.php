@extends('layouts.main_layout')
@section('page_dependencies')

    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">

    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">

    <link rel="stylesheet" href="{{ asset('bower_components/AdminLTE/plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/AdminLTE/plugins/datepicker/datepicker3.css') }}">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">
@endsection
@section('content')

    <div class="row">
        <div class="col-md-6">
            <!-- AREA CHART -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        {{ $LicenceDetails->name }} - Details
                    </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-striped table-hover">
                        <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <strong> Name</strong>
                                </div>
                            </td>
                            <td>
                                <div class="col-md-6">
                                    {{ (!empty( $LicenceDetails->name)) ? $LicenceDetails->name : ''}}
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <strong>Details </strong>
                                </div>
                            </td>
                            <td>
                                <div class="col-md-12">
                                    {{ (!empty( $LicenceDetails->details)) ? $LicenceDetails->details : ''}}
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <strong>Serial Number </strong>
                                </div>
                            </td>
                            <td>
                                <div class="col-md-6">
                                    {{ (!empty($LicenceDetails->serial)) ? $LicenceDetails->serial : '' }}
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <strong>Purchase Cost </strong>
                                </div>
                            </td>
                            <td>
                                <div class="col-md-6">
                                    {{ (!empty($LicenceDetails->purchase_cost)) ? 'R'.$LicenceDetails->purchase_cost : '' }}
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <strong>Purchase Date </strong>
                                </div>
                            </td>
                            <td>
                                <div class="col-md-6">
                                    {{ (!empty($LicenceDetails->purchase_date)) ? $LicenceDetails->purchase_date : '' }}
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <strong>Order Number </strong>
                                </div>
                            </td>
                            <td>
                                <div class="col-md-6">
                                    {{ (!empty($LicenceDetails->order_number)) ? $LicenceDetails->order_number : '' }}
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <strong>Total Available of Licences </strong>
                                </div>
                            </td>
                            <td>
                                <div class="col-md-6">
                                    {{ (!empty($LicenceDetails->total)) ? $LicenceDetails->total : '' }}
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <strong>Licence Type </strong>
                                </div>
                            </td>
                            <td>
                                <div class="col-md-6">
                                    {{ (!empty($LicenceDetails->LicensesType->name)) ? $LicenceDetails->LicensesType->name : '' }}
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <strong>Licence Expiration Date </strong>
                                </div>
                            </td>
                            <td>
                                <div class="col-md-6">
                                    {{ (!empty($LicenceDetails->expiration_date)) ? $LicenceDetails->expiration_date : '' }}
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <strong>Licence status </strong>
                                </div>
                            </td>
                            <td>
                                <div class="col-md-6">
                                    {{ !empty($LicenceDetails->status) && ($LicenceDetails->status == 1)  ? 'Active': 'De Activated' }}
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <strong>Licence Allocation Status</strong>
                                </div>
                            </td>
                            <td>
                                <div class="col-md-6">
                                    {{ (!empty($LicenceDetails->licence_status)) ? $LicenceDetails->licence_status : '' }}
                                </div>

                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    <button type="button" id="cat_module" class="btn btn-danger pull-right" data-toggle="modal"
                            data-target="#add-licence-renewal-modal"><i class="fa fa-repeat" aria-hidden="true"></i>
                        Renew
                        License
                    </button>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
        <!-- /.col (LEFT) -->
        <div class="col-md-6">
            <!-- LINE CHART -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"> Manage Licences </h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="box-header">

                    </div>
                    <div style="overflow-X:auto;">
                        <table id=" " class="asset table table-bordered data-table my-2">
                            <thead>
                            <tr>
                                <th style="width: 5px; text-align: center;">Employee Name</th>
                                <th></th>

                            </tr>
                            </thead>
                            <tbody>
                            @if (count($license_allocation) > 0)
                                <ul class="products-list product-list-in-box">
                                    @foreach ($license_allocation as $key => $allocation)
                                        <tr id="categories-list">

                                            <td style="width: 5px; text-align: center;">{{ (!empty($allocation->Hrpersons->first_name . ' ' . $allocation->Hrpersons->surname )) ? $allocation->Hrpersons->first_name . ' ' . $allocation->Hrpersons->surname   : ' ' }}</td>
                                            <td style="width: 5px; text-align: center;">

                                                <button vehice="button" id="view_ribbons" class="btn {{ (!empty($allocation->status) && $allocation->status == 1) ? " btn-danger " : "btn-success " }}
                                                  btn-xs" onclick="postData({{$allocation->id}}, 'actdeac');"><i class="fa {{ (!empty($allocation->status) && $allocation->status == 1) ?
                                                  " fa-times " : "fa-check " }}"></i> {{(!empty($allocation->status) && $allocation->status == 1) ? "De-Activate" : "Activate"}}
                                                </button>
                                            </td>
                                        </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <!-- /.box-body -->
                    </div>
                </div>
                <div class="box-footer">

                    <button type="button" id="allocate" class="btn btn-default pull-right" data-toggle="modal"
                            data-target="#add-licence-allocation"><i class="fa fa-linode" aria-hidden="true"></i>
                        Allocate License
                    </button>

                </div>
                @include('Employees.Licences.partials.renew_licence')
            </div>
            @include('Employees.Licences.partials.licence_test')
        </div>
    </div>
    <!-- /.box-body -->
    </div>
    <div class="col-md-12">
        <!-- LINE CHART -->
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"> Licence History </h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="box-header">

                </div>
                <div style="overflow-X:auto;">
                    <table id=" " class="asset table table-bordered data-table my-2">
                        <thead>
                        <tr>
                            <th style="width: 5px; text-align: center;">Purchase Date</th>
                            <th style="width: 5px; text-align: center;">Renewal Date</th>
                            <th style="width: 5px; text-align: center;">Expiration Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($licenseHistory) > 0)
                            @foreach ($licenseHistory as $key => $history)
                                <tr id="categories-list">
                                    <td style="width: 5px; text-align: center;">{{ (!empty($history->purchase_date )) ? $history->purchase_date   : ' ' }}</td>
                                    <td style="width: 5px; text-align: center;">{{ (!empty($history->renewal_date )) ? $history->renewal_date   : ' ' }}</td>
                                    <td style="width: 5px; text-align: center;">{{ (!empty($history->expiration_date )) ? $history->expiration_date   : ' ' }}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    <!-- /.box-body -->
                </div>
            </div>
            <div class="box-footer">

                <button type="button" id="cat_module" class="btn btn-default pull-right" data-toggle="modal"
                        data-target="#add-licence-allocation"><i class="fa fa-linode" aria-hidden="true"></i>
                    Allocate License
                </button>
                <button type="button" class="btn btn-default pull-left" id="back_button"><i
                            class="fa fa-arrow-left"></i> Back
                </button>
            </div>
            @include('Employees.Licences.partials.renew_licence')

        </div>

    </div>



    <!-- /.box -->

    <!-- /.col (RIGHT) -->

@stop
@section('page_script')
    <!-- DataTables -->
    {{--    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js"') }}"></script>--}}
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('custom_components/js/modal_ajax_submit.js') }}"></script>
    <script src="{{ asset('custom_components/js/deleteAlert.js') }}"></script>

    <script src="{{ asset('bower_components/bootstrap_fileinput/js/fileinput.min.js') }}"></script>

    <script src="{{ asset('plugins/axios/dist/axios.min.js') }}"></script>

    <script src="{{ asset('bower_components/AdminLTE/plugins/select2/select2.full.min.js') }}"></script>

    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
    <!-- Ajax form submit -->
    <!-- Ajax dropdown options load -->
    {{--    <script src="{{ asset('custom_components/js/load_dropdown_options.js') }}"></script>--}}
    <!-- End Bootstrap File input -->
    <script type="text/javascript">

        function postData(id, data) {
            // console.log(data)
            if (data === 'actdeac') location.href = "{{route('LicenceUser.activate', '')}}" + "/" + id;
        }

        $(function () {

            $('#back_button').click(function () {
                location.href = '{{route('licences_management.index')}}';
            });


            $('.popup-thumbnail').click(function () {
                $('.modal-body').empty();
                $($(this).parents('div').html()).appendTo('.modal-body');
                $('#modal').modal({show: true});
            });

            // Initialize date picker Elements
            $('.datepicker').datepicker({
                format: 'yyyy/mm/dd',
                autoclose: true,
                todayHighlight: true
            }).datepicker("setDate", 'now');


            //Initialize Select2 Elements
            $(".select2").select2();

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

            function reposition() {
                var modal = $(this),
                    dialog = modal.find('.modal-dialog');
                modal.css('display', 'block');

                // Dividing by two centers the modal exactly, but dividing by three
                // or four works better for larger screens.
                dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
            }

            // Reposition when a modal is shown
            $('.modal').on('show.bs.modal', reposition);
            // Reposition when the window is resized
            $(window).on('resize', function () {
                $('.modal:visible').each(reposition);
            });

            $(function () {
                $('img').on('click', function () {
                    $('.enlargeImageModalSource').attr('src', $(this).attr('src'));
                    $('#enlargeImageModal').modal('show');
                });
            });

            // allocate
            $('#add-licence-allocation-modal').on('shown.bs.modal', function (e) {

            });

            $('#add-licence_allocation').on('click', function () {
                //console.log('jfjnfjnfjfn');
                let strUrl = '{{route('licences_management.allocate')}}';
                let modalID = 'add-licence-allocation';
                let formName = 'add-licence_allocation-form';
                let files = 'file';
                let submitBtnID = 'add-licence_allocation';
                let redirectUrl = '{{ route('licences_management.show',$LicenceDetails->uuid) }}';
                let successMsgTitle = 'Allocation Successfull!';
                let successMsg = 'The Licence Allocation has been updated successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            //renewal
            $('#add-licence-allocation-modal').on('shown.bs.modal', function (e) {

            });

            $('#add-licence-renewal').on('click', function () {

                let strUrl = '{{route('licences_management.renewal')}}';
                let modalID = 'add-licence-renewal-modal';
                let formName = 'add-licence_renewal-form';
                //let files = 'file';
                let submitBtnID = 'add-licence-renewal';
                let redirectUrl = '{{ route('licences_management.show',$LicenceDetails->uuid) }}';
                let successMsgTitle = 'Renewal Successfully!';
                let successMsg = 'The Licence has been updated successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            //Load divisions drop down
            var parentDDID = '';
            var loadAllDivs = 1;
            @foreach($division_levels as $division_level)
            //Populate drop down on page load
            var ddID = '{{ 'division_level_' . $division_level->level }}';
            var postTo = '{!! route('divisionsdropdown') !!}';
            var selectedOption = '';
            var divLevel = parseInt('{{ $division_level->level }}');
            var incInactive = -1;
            var loadAll = loadAllDivs;
            loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo);
            parentDDID = ddID;
            loadAllDivs = -1;
            @endforeach
        });
    </script>
@stop