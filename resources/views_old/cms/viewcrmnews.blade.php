@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <!-- Include Date Range Picker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title"> Company News </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">

                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 10px; text-align: center;"></th>
                            <th style="width: 20px; text-align: center;"></th>
                            <th>Date</th>
                            <th>name</th>
                            <th>Description</th>
                            <th style="width: 5px; text-align: center;"></th>
                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        @if (count($Cmsnews) > 0)
                            @foreach ($Cmsnews as $news)
                                <tr id="categories-list">
                                    <td>
                                        <a href="{{ '/cms/viewnews/' . $news->id }}" id="edit_compan"
                                           class="btn btn-primary  btn-xs" data-id="{{ $news->id }}"><i
                                                    class="fa fa-pencil-square-o"></i> Edit</a>
                                    </td>
                                    <td nowrap>
                                        <a href="{{ '/view/' . $news->id }}" id="edit_compan"
                                           class="btn btn-warning  btn-xs" target="_blank"><i class=""></i> View News
                                        </a>
                                    </td>
                                    <td>{{!empty($news->expirydate) ? date(' d M Y', $news->expirydate) : ''}}</td>
                                    <td>{{ !empty($news->name) ? $news->name : ''}}</td>
                                    <td>{{ !empty($news->description) ? $news->description : ''}}</td>
                                    <td>
                                        <button vehice="button" id="view_ribbons"
                                                class="btn {{ (!empty($news->status) && $news->status == 1) ? " btn-danger " : "btn-success " }}
                                                        btn-xs" onclick="postData({{$news->id}}, 'actdeac');"><i
                                                    class="fa {{ (!empty($news->status) && $news->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($news->status) && $news->status == 1) ? "De-Activate" : "Activate"}}
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-xs" data-toggle="modal"
                                                data-target="#delete-contact-warning-modal"><i class="fa fa-trash"></i>
                                            Delete
                                        </button>
                                    </td>

                                </tr>
                            @endforeach
                        @else
                            <tr id="categories-list">
                                <td colspan="7">
                                    <div class="callout callout-danger">
                                        <h4><i class="fa fa-database"></i> No Records found</h4>
                                        <p>No Records found. Please start by adding a Record.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                    <!--   </div> -->
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back_button">Back</button>
                        <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal"
                                data-target="#add-news-modal">Add news
                        </button>
                    </div>
                </div>
            </div>
            <!-- Include add new prime rate modal -->

        @include('cms.partials.add_news_modal')
        <!-- Include delete warning Modal form-->
            @if (count($Cmsnews) > 0)
                @include('cms.partials.news_warning_action', ['modal_title' => 'Delete Record', 'modal_content' => 'Are you sure you want to delete this Record? This action cannot be undone.'])
            @endif
        </div>
        @endsection

        @section('page_script')
            <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
            <!-- iCheck -->
            <!-- Start Bootstrap File input -->
            <!-- canvas-to-blob.min.js is only needed if you wish to resize images before upload. This must be loaded before fileinput.min.js -->
            <script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js"
                    type="text/javascript"></script>
            <!-- the main fileinput plugin file -->
            <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
            <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
                    type="text/javascript"></script>
            <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
            <script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js"
                    type="text/javascript"></script>
            <!-- the main fileinput plugin file -->
            <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
            <!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
            <script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
            <!-- optionally if you need translation for your language then include locale file as mentioned below
            <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
            <!-- Select2 -->
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <!-- Ajax dropdown options load -->
            <script src="/custom_components/js/load_dropdown_options.js"></script>
            <!-- CK Editor -->
            <script src="https://cdn.ckeditor.com/4.7.1/standard/ckeditor.js"></script>
            <!-- Ajax form submit -->
            <script src="/custom_components/js/modal_ajax_submit.js"></script>
            <script>
			//Cancel button click event
			document.getElementById("back_button").onclick = function () {
				location.href = "/";
			};
                function postData(id, data) {
                    if (data == 'actdeac') location.href = "/cms/cmsnews_act/" + id;

                }

                $('[data-toggle="tooltip"]').tooltip();

                //slimScroll
                $('#quote-profile-list').slimScroll({
                    height: '',
                    railVisible: true
                    //alwaysVisible: true
                });
                // Replace the <textarea id="send_quote_message"> with a CKEditor
                // instance, using default configuration.
                CKEDITOR.replace('term_name');

                //Vertically center modals on page
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

                //Show success action modal
                $('#success-action-modal').modal('show');


                $('.datepicker').datepicker({
                    format: 'dd/mm/yyyy',
                    autoclose: true,
                    todayHighlight: true
                });

                //Show success action modal
                @if(Session('changes_saved'))
                $('#success-action-modal').modal('show');
                @endif

                $(".js-example-basic-multiple").select2();

                //Post perk form to server using ajax (add)
                $('#add_news').on('click', function () {
                    var strUrl = '/cms/crm_news';
                    var formName = 'add-news-form';
                    var modalID = 'add-news-modal';
                    var submitBtnID = 'add_news';
                    var redirectUrl = '/cms/viewnews';
                    var successMsgTitle = 'New Record Details Added!';
                    var successMsg = 'New Crm News has been Added successfully.';
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].updateElement();
                    }
                    $("#term_name").serialize()
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
                //
            </script>
@endsection
