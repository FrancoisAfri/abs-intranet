@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">

            <!-- HR PEOPLE LIST -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Qualifications Search Result</h3>
                     <p>Qualifications Details:</p>
       
                </div>
                 <!-- /.box-header -->
                <form class="form-horizontal" method="POST" action=" ">
                   <!--  <input type="hidden" name="registration_type" value="{{!empty($registration_type) ? $registration_type : ''}}">
                    <input type="hidden" name="programme_id" value="{{!empty($programme_id) ? $programme_id : ''}}">
                    <input type="hidden" name="project_id" value="{{!empty($project_id) ? $project_id : ''}}">
                    <input type="hidden" name="registration_year" value="{{!empty($registration_year) ? $registration_year : ''}}">
                    <input type="hidden" name="course_type" value="{{!empty($course_type) ? $course_type : ''}}">
                    <input type="hidden" name="registration_semester" value="{{!empty($registration_semester) ? $registration_semester : ''}}"> -->
                    {{ csrf_field() }}

            <!-- well -->
            <div class="row">
                            <div class="col-sm-12">
                                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                    <strong class="lead">Registration Report Parameters</strong><br>
                                    <strong>Registration Type :</strong> <em>{{ $str_report_type }}</em> &nbsp; &nbsp;
                                    @if(!empty($programme))
                                        | &nbsp; &nbsp; <strong>Programme:</strong> <em>{{ $programme }}</em> &nbsp; &nbsp;
                                    @endif
                                    @if(!empty($project))
                                        | &nbsp; &nbsp; <strong>Project:</strong> <em>{{ $project }}</em> &nbsp; &nbsp;
                                    @endif
                                    @if(!empty($registration_year))
                                        | &nbsp; &nbsp; <strong>Registration Year:</strong> <em>{{ $registration_year }}</em> &nbsp; &nbsp;
                                    @endif
                                    @if(!empty($str_course_type) && $registration_type != 3)
                                        | &nbsp; &nbsp; <strong>Course Type:</strong> <em>{{ $str_course_type }}</em> &nbsp; &nbsp;
                                    @endif
                                    @if(!empty($registration_semester))
                                        | &nbsp; &nbsp; <strong>Registration Semester:</strong> <em>{{ $registration_semester }}</em> &nbsp; &nbsp;
                                    @endif
                                </p>
                            </div>
                        </div>
            <!-- end well -->
            @if(!(count($qualification) > 0))
                        <div class="callout callout-danger">
                            <h4><i class="fa fa-database"></i> No Records found</h4>

                            <p>No user matching your search criteria in the database. Please refine your search parameters.</p>
                        </div>
                    @endif
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 10px"></th>
                        <th>Institution</th>
                        <th>Qualification</th>
                        <th>Year obtained</th>
                        <th> Qualfification Type</th>
                        <th> Certificate</th>
                        <th>Status</th>
                        <th style="width: 40px"></th>
                    </tr> 
                    
                    @foreach($qualification as $person)
                    <tr id="modules-list">
                        <td nowrap>
                            <a href="{{ '/users/' . $person->id . '/edit' }}" class="product-title">{{ $person->Name . ' ' . $person->Surname }}</a>
                        </td>
                        <td><span pull-right">{{ $person->Institution }}</span></td>
                         <td><span pull-right">{{ $person->Qualification }}</span></td>
                         <td><span pull-right">{{ $person->yearObtained}}</span></td>
                         <td><span pull-right">{{ $person->Qualification}}</span></td>
                         <td>
                            <div class="form-group{{ $errors->has('supporting_doc') ? ' has-error' : '' }}">
                                <label for="supporting_doc" class="control-label"></label>
                                    @if(!empty($person->supporting_docs))
                                <br><a class="btn btn-default btn-flat btn-block class pull-center btn-xs" href="{{ $person->supporting_docs }}" target="_blank"><i class="fa fa-file-pdf-o"></i>  View Document</a>
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
        <!-- Include modal -->
      
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