@extends('layouts.main_layout')
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
@section('content')
    <div class="row">
        <div class="col-md-12">

            <!-- HR PEOPLE LIST -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Qualifications Search Result</h3>

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
                    @if(!(count($qualification) > 0))
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
                        <th> Description</th>
                        <th>Status</th>
                        <th style="width: 40px"></th>
                    </tr> 
                    
                    @foreach($qualification as $person)
                    <tr id="modules-list">
                        <td nowrap>
                            <a href="{{ '/users/' . $person->id . '/edit' }}" class="product-title">{{ $person->Name . ' ' . $person->Surname }}</a>
                        </td>
                        <td><span pull-right">{{ $person->Department }}</span></td>
                         <td><span pull-right">{{ $person->Division }}</span></td>

                         <td>
                            <div class="form-group{{ $errors->has('supporting_doc') ? ' has-error' : '' }}">
                                <label for="supporting_doc" class="control-label"></label>
                                    @if(!empty($person->supporting_docs))
                                <br><a class="btn btn-default btn-flat btn-block class pull-center btn-xs" href="{{ $person->Document }}" target="_blank"><i class="fa fa-file-pdf-o"></i>  View Document</a>
                                         @else
                                <br><a class="btn btn-default pull-centre btn-xs"><i class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
                                         @endif
                                </div>
                              </td>
                              
                         
                           
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
    </div>
@endsection

@section('page_script')
<!--  -->
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<!--  -->
    <script type="text/javascript">
	//Cancel button click event
	document.getElementById("back_to_user_search").onclick = function () {
		location.href = "/users";
	};
    function reject(id, data) {
         
            if (data == 'reject_id') location.href = "/leave/reject/" + id;

       }

      //Vertically center modals on pag
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
            $(window).on('resize', function() {
                $('.modal:visible').each(reposition);
            });

             var reject_ID;
            $('#add-document-modal').on('show.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                reject_ID = btnEdit.data('id');
                // var name = btnEdit.data('name');
                var description = btnEdit.data('description');
                var modal = $(this);
                // modal.find('#name').val(name);
                modal.find('#description').val(description);  
             });

            //Post module form to server using ajax (ADD)
            $('#add-new-doc').on('click', function() {
                //console.log('strUrl');
                //var strUrl = '/leave/reject/' + reject_ID; 
                var modalID = 'update-doc-modal';
                var objData = {
                    // name: $('#'+modalID).find('#name').val(),
                    description: $('#'+modalID).find('#description').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'reject_leave';
                var redirectUrl = '/leave/approval';
                var successMsgTitle = 'reject reason Saved!';
                var successMsg = 'The reject reason has been Saved successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

    </script>
@endsection