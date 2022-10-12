@extends('layouts.main_layout')
@section('page_dependencies')

    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap_fileinput/css/fileinput.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/AdminLTE/plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/AdminLTE/plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/AdminLTE/plugins/iCheck/square/green.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fine-uploader/fine-uploader-gallery.css') }}">
    <script src="/custom_components/js/deleteAlert.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">

    <!-- bootstrap file input -->
@stop
@section('content')
    <section class="content">

        <div class="row">
            <!-- /.col -->
            <div class=" tab-content">
                <div class="activetab-pane" id="info">
                    <section class="content">

                        <div class="row">
                            <div class="col-md-12">

                                <h1>
                                    {{--                                    View Asset - {{ $asset->name}}--}}
                                </h1>
                                <div class="nav-tabs-custom">
                                    <ul class="nav nav-tabs">
                                        <li class="" data-toggle="tooltip" title="information"><a
                                                    href="#information" data-toggle="tab">Personal Info</a></li>
                                        <li class="" data-toggle="tooltip" title="company_info"><a href="#company_info"
                                                                                                   data-toggle="tab">Company
                                                Info</a>
                                        </li>
                                        <li class="" data-toggle="tooltip" title="Tasks"><a href="#Tasks"
                                                                                            data-toggle="tab">Tasks</a>
                                        </li>
                                        <li class="" data-toggle="tooltip" title="Video"><a href="#Video"
                                                                                            data-toggle="tab">Video</a>
                                        </li>
                                        <li class="" data-toggle="tooltip" title="drive"><a href="#drive"
                                                                                            data-toggle="tab">Drive</a>
                                        </li>
                                        <li class=" pull-right">
                                            <button type="button" class="btn btn-default pull-right" id="back_button"><i
                                                        class="fa fa-arrow-left"></i> Back
                                            </button>
                                        </li>

                                    </ul>
                                    <div class="tab-content">
                                        {{--  taranferes  tab  --}}
                                        <div class="active tab-pane" id="information">
                                            @include('Employees.Tabs.information-tab')
                                        </div>
                                        <!-- /.tab-pane -->

                                        {{--  taranferes  tab  --}}
                                        <div class="tab-pane" id="company_info">
                                            @include('Employees.Tabs.company_info-tab')
                                        </div>
                                        <!-- /.tab-pane -->

                                        {{--  Components  tab  --}}
                                        <div class="tab-pane" id="Tasks">
                                            @include('Employees.Tabs.tasks-tab')
                                        </div>
                                        <!-- /.tab-pane -->

                                        {{--  Files  tab  --}}
                                        <div class="tab-pane" id="Video">
                                            @include('Employees.Tabs.videos-tab')
                                        </div>
                                        <!-- /.tab-pane -->
                                        <div class="tab-pane" id="drive">
                                            @include('Employees.Tabs.documents-tab')
                                        </div>

                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                                <!-- /.nav-tabs-custom -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
{{--                        @include('Employees.partials.edit_user')--}}
                    </section>
                </div>

            </div>
            <!-- /.tab-content -->
        </div>
        <!-- /.col -->
        <!-- /.row -->
    </section>
@stop
@section('page_script')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('custom_components/js/modal_ajax_submit.js') }}"></script>
    <script src="{{ asset('custom_components/js/deleteAlert.js') }}"></script>
    <!-- the main fileinput plugin file -->
    <script src="{{ asset('bower_components/bootstrap_fileinput/js/fileinput.min.js') }}"></script>
    <script src="{{ asset('bower_components/AdminLTE/plugins/iCheck/icheck.min.js')}}"></script>
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('plugins/fine-uploader/fine-uploader.js') }}"></script>
    <script src="/custom_components/js/deleteAlert.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

    <!-- Ajax form submit -->
    <script src="{{asset('custom_components/js/modal_ajax_submit.js')}}"></script>
    <!-- Ajax dropdown options load -->
    <script src="{{ asset('custom_components/js/load_dropdown_options.js') }}"></script>

    <script src="{{ asset('custom_components/js/dataTable.js') }}"></script>

    <script>
        $(function () {

            $(".select2").select2();

            $('[data-toggle="tooltip"]').tooltip();

            //back
            $('#user_profile').click(function () {
            
                location.href = '{{ route('user.edit',$employee->id ) }} ';

            });

            //Load divisions drop down



            $('table.files').DataTable({

                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: true,
            });


            // reposition modal
            $('.modal').on('show.bs.modal', reposition);

        })
        ;
    </script>
@stop
