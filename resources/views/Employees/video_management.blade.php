@extends('layouts.main_layout')
@section('page_dependencies')

    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">
    <link href="https://unpkg.com/cloudinary-video-player@1.9.0/dist/cld-video-player.min.css" rel="stylesheet">


@stop
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user-times pull-right"></i>
                    <h3 class="box-title"> Manage Videos </h3>
                </div>
                <div class="box-body">
                    <div class="box-header">
                        <br>
                        <button type="button" id="cat_module" class="btn btn-default pull-right" data-toggle="modal"
                                data-target="#add-video-modal">Add Video
                        </button>
                    </div>
                    <div style="overflow-X:auto;">
                        <table id=" " class="asset table table-bordered data-table my-2">
                            <thead>
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
                                <th style="width: 5px; text-align: center;">Name</th>
                                <th style="width: 5px; text-align: center;">Description</th>
                                <th style="width: 5px; text-align: center;">Type</th>
                                <th style="width: 5px; text-align: center;"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($videos) > 0)
                                <ul class="products-list product-list-in-box">
                                    @foreach ($videos as $key => $video)
                                        <tr id="categories-list">
                                            <td>
                                                <video  height="60" width="150" controls>
													<source src="{{URL::asset("storage/public/videos/$video->path")}}" type="video/mp4">
													Your browser does not support the video tag.
												</video>
                                            </td>
                                            <td style="text-align:center;">
                                                <a data-toggle="tooltip" title="Click to View Asset"
                                                   href="{{ route('videos.show',['videos' => $video->uuid]) }}">
                                                    {{ (!empty( $video->name)) ?  $video->name : ''}}
                                                </a>
                                            </td>
                                            <td style="text-align:center;">
                                                <span data-toggle="tooltip" title="description"
                                                      href="{{ route('videos.show',['videos' => $video->uuid]) }} ">
                                                    {{ (!empty($video->description)) ? $video->description : '' }}</span>
                                            </td>
                                            <td>
                                                {{ (!empty($video->video_type)) ?  $videoType[$video->video_type] : ' ' }}
                                            </td>

                                            <td>
                                                <button vehice="button" id="view_ribbons" class="btn {{ (!empty($video->status) && $video->status == 1) ? " btn-danger " : "btn-success " }}
                                                      btn-xs" onclick="postData({{$video->id}}, 'actdeac');"><i class="fa {{ (!empty($video->status) && $video->status == 1) ?
                                                      " fa-times " : "fa-check " }}"></i> {{(!empty($video->status) && $video->status == 1) ? "De-Activate" : "Activate"}}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endif
                                </ul></tbody>
                        </table>
                        <!-- /.box-body -->
                    </div>
                </div>
                @include('Employees.partials.create_video')
            </div>
        </div>
    </div>
	<br></br>
	<div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user-times pull-right"></i>
                    <h3 class="box-title"> Training Documents </h3>
                </div>
                <div class="box-body">
                    <div class="box-header">
                        <br>
                        <button type="button" id="cat_module" class="btn btn-default pull-right" data-toggle="modal"
                                data-target="#add-training-docs-modal">Add New Document
                        </button>
                    </div>
                    <div style="overflow-X:auto;">
                        <table id=" " class="document table table-bordered data-table my-2">
                            <thead>
                            <tr>
                                <th style="width: 5px; text-align: center;">Document</th>
                                <th style="width: 5px; text-align: center;">Name</th>
                                <th style="width: 5px; text-align: center;">Description</th>
                                <th style="width: 5px; text-align: center;">Division</th>
                                <th style="width: 5px; text-align: center;">Department</th>
                                <th style="width: 5px; text-align: center;">Section</th>
                                <th style="width: 5px; text-align: center;"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($documents) > 0)
                                <ul class="products-list product-list-in-box">
                                    @foreach ($documents as $key => $document)
                                        <tr>
                                            <td>
												<div class="form-group{{ $errors->has('document') ? ' has-error' : '' }}">
													<label for="document" class="control-label"></label>
													@if(!empty($document->document))
														<a class="btn btn-default btn-flat btn-block pull-right btn-xs"
														   href="{{ Storage::disk('local')->url("Employee/training/$document->document") }}"
														   target="_blank"><i class="fa fa-file-pdf-o"></i> View Document</a>
													@else
														<a class="btn btn-default pull-centre btn-xs"><i
																	class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
													@endif
												</div>
                                            </td>
                                            <td style="text-align:center;">
                                                    {{ (!empty( $document->name)) ?  $document->name : ''}}
                                            </td>
                                            <td style="text-align:center;">
                                                    {{ (!empty($document->description)) ? $document->description : '' }}
                                            </td>
                                            <td>
                                                {{ (!empty($document->division->name)) ?  $document->division->name : ' ' }}
                                            </td>
											<td>
                                                {{ (!empty($document->department->name)) ?  $document->department->name : ' ' }}
                                            </td>
											<td>
                                                {{ (!empty($document->section->name)) ?  $document->section->name : ' ' }}
                                            </td>

                                            <td>
                                                <button vehice="button" id="view_ribbons" class="btn {{ (!empty($document->status) && $document->status == 1) ? " btn-danger " : "btn-success " }}
                                                      btn-xs" onclick="postData({{$document->id}}, 'actdeacdoc');"><i class="fa {{ (!empty($document->status) && $document->status == 1) ?
                                                      " fa-times " : "fa-check " }}"></i> {{(!empty($document->status) && $document->status == 1) ? "De-Activate" : "Activate"}}
                                                </button>
                                            </td>

                                        </tr>
                                    @endforeach
                                    @endif
                                </ul></tbody>
                        </table>
                        <!-- /.box-body -->
                    </div>
                </div>
                @include('Employees.partials.add_training_document_modal')
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

    <script src="{{ asset('plugins/axios/dist/axios.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>

    <script src="https://unpkg.com/cloudinary-video-player@1.9.0/dist/cld-video-player.min.js"
            type="text/javascript"></script>

    {{--    <script src="{{ asset('custom_components/js/dataTable.js') }}"></script>--}}
    <!-- Ajax form submit -->
    <script src="{{asset('custom_components/js/modal_ajax_submit.js')}}"></script>
    <!-- Ajax dropdown options load -->
    <script src="{{ asset('custom_components/js/load_dropdown_options.js') }}"></script>

    <!-- End Bootstrap File input -->
    <script type="text/javascript">



        // var demoplayer = cloudinary.videoPlayer('doc-player', { cloud_name: 'demo' });
        // demoplayer.source('race_road_car')


        function sendStatus() {

            let select = document.getElementById("status_id");
            console.log(select)

        }


        function postData(id, data) {
            console.log(id)
            if (data === 'actdeac') location.href = "{{ route('video.activate', '')}}" + "/" + id;
            if (data === 'actdeacdoc') location.href = "{{ route('docs-training.activate', '')}}" + "/" + id;
        }

        $('.popup-thumbnail').click(function () {
            $('.modal-body').empty();
            $($(this).parents('div').html()).appendTo('.modal-body');
            $('#modal').modal({show: true});
        });


        //TODO WILL CREATE A SIGLE GLOBAL FILE

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
			$('table.document').DataTable({

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

            $('#add-video').on('click', function () {
                let strUrl = '{{route('employee.videos')}}';
                let modalID = 'add-video-modal';
                let formName = 'add-videos-form';

                //console.log(formName)
                let submitBtnID = 'add-video';
                let redirectUrl = '{{ route('video.index') }}';
                let successMsgTitle = 'Video Added!';
                let successMsg = 'Record has been updated successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
			//add doc-player
			$('#add-training-doc').on('click', function () {
				var strUrl = '/employee/add-training-docs';
				var formName = 'add-training-docs-form';
				var modalID = 'add-training-docs-modal';
				var submitBtnID = 'add-training-doc';
				var redirectUrl = '/employee/video_management';
				var successMsgTitle = 'New Document  Added!';
				var successMsg = 'The document has been updated successfully.';
				modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
			});
        });
    </script>
@stop
