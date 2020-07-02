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
                    <h3 class="box-title"> Client Documents for - {{ !empty($person->first_name . ' ' . $person->surname ) ? $person->first_name . ' ' . $person->surname : ''}} </h3>
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
                            <th>name</th>
                            <th>Description</th>
                            <th>Date From</th>
                            <th>Expiry Date</th>
                            <th style="width: 5px; text-align: center;"></th>
                            <th style="width: 5px; text-align: center;"></th>
                        </tr>
                        @if (count($documents) > 0)
                            @foreach ($documents as $document)
                                <tr id="categories-list">
                                    <td nowrap>
                                        <button document="button" id="edit_compan" class="btn btn-warning  btn-xs"
                                                data-toggle="modal" data-target="#edit-newdoc-modal"
                                                data-id="{{ $document->id }}" data-document_name="{{ $document->document_name }}"
                                                data-description="{{ $document->description }}"
                                                data-role="{{ $document->role }}"
                                                data-date_from="{{  date(' d M Y', $document->date_from) }}"
                                                data-expirydate="{{ date(' d M Y', $document->expirydate) }}"
                                        ><i class="fa fa-pencil-square-o"></i> Edit
                                        </button>
                                    </td>

                                    <td nowrap>
                                        <div class="form-group{{ $errors->has('supporting_docs') ? ' has-error' : '' }}">
                                            <label for="document" class="control-label"></label>
                                            @if(!empty($document->supporting_docs))
                                                <a class="btn btn-default btn-flat btn-block pull-right btn-xs"
                                                   href="{{ Storage::disk('local')->url("ContactClient/client_documents/$document->supporting_docs") }}"
                                                   target="_blank"><i class="fa fa-file-pdf-o"></i> View Document</a>
                                            @else
                                                <a class="btn btn-default pull-centre btn-xs"><i
                                                            class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ !empty($document->document_name) ? $document->document_name : ''}}</td>
                                    <td>{{ !empty($document->description) ? $document->description : ''}}</td>
                                    <td>{{ !empty($document->date_from) ? date(' d M Y', $document->date_from) : '' }}</td>
                                    <td>{{ !empty($document->expirydate) ? date(' d M Y', $document->expirydate) : '' }}</td>
                                    <td>
                                        <button vehice="button" id="view_ribbons" class="btn {{ (!empty($document->status) && $document->status == 1) ? " btn-danger " : "btn-success " }}
                                                btn-xs" onclick="postData({{$document->id}}, 'actdeac');"><i class="fa {{ (!empty($document->status) && $document->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($document->status) && $document->status == 1) ? "De-Activate" : "Activate"}}</button>
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
                                        <p>No Document found. Please start by adding a document.</p>
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
                                data-target="#add-document-modal">Add Document
                        </button>
                    </div>
                </div>
            </div>
            <!-- Include add new prime rate modal -->
        @include('contacts.partials.add_document_modal')
        @include('contacts.partials.edit_clientdocument_modal')
            <!-- Include delete warning Modal form-->
            @if (count($documents) > 0)
                @include('contacts.partials.clientdoc_warning_action', ['modal_title' => 'Delete Task', 'modal_content' => 'Are you sure you want to delete this Document ? This action cannot be undone.'])
            @endif

        </div>
        @endsection

        @section('page_script')
            <script src="/custom_components/js/modal_ajax_submit.js"></script>
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
            <!-- iCheck -->
            <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
            <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js"
                    type="text/javascript"></script>
            <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
            <script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js"
                    type="text/javascript"></script>
            <!-- the main fileinput plugin file -->
            <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
            <!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
            <script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
            <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

            <!-- InputMask -->
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
            <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
            <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
            <script>
                function postData(id, data) {
                    if (data == 'actdeac') location.href = "/contacts/clientdoc_act/" + id;

                }

                $('#back_button').click(function () {
                    location.href = "/contacts/{{$person->id}}/edit";
                });

                $(function () {
                    $('#back_button').click(function () {
                        location.href = "/contacts/{{$person->id}}/edit";
                    });
                    var moduleId;
                    //Initialize Select2 Elements
                    $(".select2").select2();
                    $('.zip-field').hide();
                    $('.sex-field').hide();


                    //Tooltip

                    $('[data-toggle="tooltip"]').tooltip();

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

                    //

                    $(".js-example-basic-multiple").select2();

                    $('.datepicker').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });

                    $(function () {
                        $('img').on('click', function () {
                            $('.enlargeImageModalSource').attr('src', $(this).attr('src'));
                            $('#enlargeImageModal').modal('show');
                        });
                    });

                    //Initialize iCheck/iRadio Elements
                    $('input').iCheck({
                        checkboxClass: 'icheckbox_square-blue',
                        radioClass: 'iradio_square-blue',
                        increaseArea: '10%' // optional
                    });

                    $(document).ready(function () {

                        $('#date_from').datepicker({
                            format: 'dd/mm/yyyy',
                            autoclose: true,
                            todayHighlight: true
                        });

                    });

                    $('#exp_date').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true
                    });

                });


                //Post perk form to server using ajax (add)
                $('#add_document').on('click', function () {
                    var strUrl = '/contacts/add_document';
                    var formName = 'add-document-form';
                    var modalID = 'add-document-modal';
                    var submitBtnID = 'add_document';
                    var redirectUrl = '/contacts/{{ $person->id }}/viewdocuments';
                    var successMsgTitle = 'New Documents Details Added!';
                    var successMsg = 'Document Details has been updated successfully.';
                    modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                });

                var docID;
                $('#edit-newdoc-modal').on('show.bs.modal', function (e) {
                    var btnEdit = $(e.relatedTarget);
                    docID = btnEdit.data('id');
                    var document_name = btnEdit.data('document_name');
                    var description = btnEdit.data('description');
                    var role = btnEdit.data('start_date');
                    var date_from = btnEdit.data('date_from');
                    var expirydate = btnEdit.data('expirydate');
                    var modal = $(this);
                    modal.find('#document_name').val(document_name);
                    modal.find('#description').val(description);
                    modal.find('#role').val(role);
                    modal.find('#date_from').val(date_from);
                    modal.find('#expirydate').val(expirydate);
                });

                $('#edit_doc').on('click', function () {
                    var strUrl = '/contacts/editClientdoc/' + docID;
                    var formName = 'edit-newdoc-form';
                    var modalID = 'edit-newdoc-modal';
                    var submitBtnID = 'edit_doc';
                    var redirectUrl = '/contacts/{{ $person->id }}/viewdocuments';
                    var successMsgTitle = 'New Documents Details have been updated!';
                    var successMsg = 'The Documents Details has been updated successfully.';
                    var Method = 'PATCH';
                    modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                });

            </script>
@endsection
