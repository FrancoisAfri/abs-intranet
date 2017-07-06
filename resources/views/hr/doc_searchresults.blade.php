@extends('layouts.main_layout')
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<!--Time Charger-->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
@section('content')
    <div class="row">
        <div class="col-md-12">

            <!-- HR PEOPLE LIST -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Document Search Result</h3>

                    <!--
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>

                    -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    @if(!(count($document) > 0))
                        <div class="callout callout-danger">
                            <h4><i class="fa fa-database"></i> No Records found</h4>

                            <p>No user matching your search criteria in the database. Please refine your search parameters.</p>
                        </div>
                    @endif
                    <ul class="products-list product-list-in-box">
            <div class="box-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 10px"></th>
                        <th>Department</th>
                        <th>Division</th>
                        <th>Document</th>
                        <th>Type</th>
                        <th> Description</th>
                        <th>Date Uploaded</th>
                        <th>Date From</th>
                        <th>Expiry Date</th>
                        <th>Status</th>
                        <th style="width: 40px"></th>
                    </tr> 
                    
                    @foreach($document as $person)
                    <tr id="modules-list">
                        <td nowrap>
                            <a href="{{ '/hr/' . $person->id . '/edit' }}" class="product-title">{{ $person->Name . ' ' . $person->Surname }}</a>
                        </td>
                        <td><span pull-right">{{ $person->Department }}</span></td>
                         <td><span pull-right">{{ $person->Division }}</span></td>

                         <td>
                            <div class="form-group{{ $errors->has('supporting_docs') ? ' has-error' : '' }}">
                                <label for="supporting_docs" class="control-label"></label>
                                    @if(!empty($person->supporting_docs))
                                <br><a class="btn btn-default btn-flat btn-block class pull-center btn-xs" href="{{ $person->supporting_docs}}" target="_blank"><i class="fa fa-file-pdf-o"></i>  View Document</a>
                                         @else
                                <br><a class="btn btn-default pull-centre btn-xs"><i class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
                                         @endif
                                </div>
                              </td>
                              <td><span pull-right">{{ $person->DocTypeName }}</span></td>
                            <td><span pull-right">{{ $person->doc_description }}</span></td>
                            <td><span pull-right">{{ $person->created_at }}</span></td>
                            <td><span pull-right">{{ $person->date_from }}</span></td>
                            <td><span pull-right">{{ $person->expirydate }}</span></td>
                           
                           <td><span class="label {{ ($person->statas === 1) ? 'label-success' : 'label-danger' }} ">{{ $status_values[$person->statas] }}</span> </td> 
                    </tr>
                     @endforeach
                
            </table>
            </div>
                     
                </div>
                <!-- /.box-body -->
                <div class="box-footer">

                    <button id="back_to_user_search" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back to search</button>
                    <button type="button" id="add-new-doc" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-document-modal">Upload new document</button>    
                </div>
                <!-- /.box-footer -->
            </div>
            <!-- /.box -->
        </div>
          @include('hr.partials.add_document_modal')
    </div>
@endsection

@section('page_script')
<!--  -->
<script src="/custom_components/js/modal_ajax_submit.js"></script>
 <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- bootstrap datepicker -->
   <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

     <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
   
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
    <!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
    <script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
    <!-- optionally if you need translation for your language then include locale file as mentioned below

    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
<!--  -->
    <script type="text/javascript">
	//Cancel button click event
	document.getElementById("back_to_user_search").onclick = function () {
		location.href = "/hr/emp_qualification";
	};
    function reject(id, data) {
         
            if (data == 'reject_id') location.href = "/leave/reject/" + id;

       }

      //Vertically center modals on pag
           function reposition() {
            var modal = $(this)
                , dialog = modal.find('.modal-dialog');
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
          


             $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });

        //#
         $('#add_leave').on('click', function () {
            var strUrl = '/leave/type/add_leave';
            var objData = {
                name: $('#add-new-leave-modal').find('#name').val()
                , description: $('#add-new-leave-modal').find('#description').val()
                , _token: $('#add-new-leave-modal').find('input[name=_token]').val()
            };
            var modalID = 'add-new-leave-modal';
            var submitBtnID = 'add_leave';
            var redirectUrl = '/leave/types';
            var successMsgTitle = 'Changes Saved!';
            var successMsg = 'Leave has been successfully added.';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });

            //Submit programme expenditures form to server with ajax
            $('#save_doc').on('click', function() {
                var strUrl = '/hr/emp_document/upload_doc';
                var objData = {
                    name: $('#add-document-modal').find('#name').val(),
                    supporting_docs :$('#add-document-modal').find('#supporting_docs').val(),
                    date_from :$('#add-document-modal').find('#date_from').val(),
                    exp_date :$('#add-document-modal').find('#exp_date').val(),
                    description :$('#add-document-modal').find('#description').val(),
                    _token: $('#add-document-modal').find('input[name=_token]').val()
                };
                var modalID = 'add-document-modal';
                var submitBtnID = 'add-new-doc';
                var redirectUrl = '/hr/emp_doc/Search'; // live it like that
                var successMsgTitle = 'Document Updated successfully!';
                var successMsg = 'The Document has been successfully added.';
                 modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });



            // //Post module form to server using ajax (ADD)
            // $('#add-new-doc').on('click', function() {
            //     //console.log('strUrl');
            //     var strUrl = '/leave/reject/' + reject_ID; 
            //     var modalID = 'update-doc-modal';
            //     var objData = {
            //         // name: $('#'+modalID).find('#name').val(),
            //         description: $('#'+modalID).find('#description').val(),
            //         _token: $('#'+modalID).find('input[name=_token]').val()
            //     };
            //     var submitBtnID = 'reject_leave';
            //     var redirectUrl = '/leave/approval';
            //     var successMsgTitle = 'reject reason Saved!';
            //     var successMsg = 'The reject reason has been Saved successfully.';
            //     //var formMethod = 'PATCH';
            //     modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            // });

    </script>
@endsection