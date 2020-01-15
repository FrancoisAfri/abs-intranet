@extends('layouts.main_layout')
@section('page_dependencies')
<!-- Include Date Range Picker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- iCheck -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
<!-- bootstrap file input -->
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<!--Time Charger-->
<!-- ### -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                   <h3 class="box-title">Add Note for:  {{ $company->name}}</h3>
                </div>
                 {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                <!-- /.box-header -->
                <div class="box-body">
					<table class="table table-bordered">
						 <tr>
						 <th style="width: 10px"></th>
						 <th>Notes</th>
						 <th>Date</th>
						 <th>Communication Method</th>
						 <th>Next Action </th>
						 <th>Follow up Date</th>
						 <th style="width: 40px"></th>
						 </tr>
						@if (count($contactnotes) > 0)
							@foreach($contactnotes as $notes)
							 <tr id="notess-list">
							  <td ><img src="{{ (!empty($notes->profile_pic)) ? Storage::disk('local')->url("avatars/$notes->profile_pic") : (($notes->gender === 0) ? $f_silhouette : $m_silhouette) }}" width="30" height="30" alt="" ></td>
							  <td>{{ (!empty($notes->notes)) ?  $notes->notes : ''}} </td>
							  <td>{{ !empty($notes->date) ? date('d M Y ', $notes->date) : '' }}</td>
							  <td>{{ !empty($notes->time) ? date('d M Y ', $notes->time) : '' }}</td>
							   <!-- <td>{{ (!empty($notes->next_action)) ?  $notes->next_action : ''}} </td> -->
							   <td>{{ (!empty($notes->next_action)) ?  $notesStatus[$notes->next_action] : ''}} </td>
							  <td>{{ !empty($notes->follow_date) ? date('d M Y ', $notes->follow_date) : '' }}</td>
							  <td><button type="button" id="edit_compan" class="btn btn-warning  btn-xs" data-toggle="modal" data-target="#edit-category-modal" data-id="{{ $notes->id }}" data-originator_type="{{ $notes->originator_type }}" data-company_id="{{$notes->company_id}}" data-hr_person_id="{{$notes->hr_person_id}}" data-employee_id="{{$notes->employee_id}}"
								><i class="fa fa-pencil-square-o"></i> Edit</button></td>
									</tr>
									   @endforeach
								   @else
								   <tr id="categories-list">
							<td colspan="7">
							<div class="alert alert-danger alert-dismissable">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 No Notes to display, Begin by Adding Notes.
							</div>
							</td>
							</tr>
							   @endif
					</table>
                        </div>
                                   <!-- /.box-body -->
                    <div class="box-footer">
                     <button type="button" id="new_tickets" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-note-modal">Add Note</button>
                    </div>
             </div>
        </div>
   <!-- Include add new prime rate modal -->
        @include('contacts.partials.add_note_modal')
        @include('contacts.partials.adit_note_modal')


</div>


@endsection

@section('page_script')
<!-- Ajax form submit -->
<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
     <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
    <!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
    <script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
     <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script>
        function postData(id , data ){   
            if(data == 'actdeac') location.href = "/Product/category/" + id; 
        }
        $(function () {
            var moduleId;
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
            $(window).on('resize', function() {
                $('.modal:visible').each(reposition);
            });
             $('#time').datetimepicker({
                    format: 'HH:mm:ss'
                });
                // $('#time').datetimepicker({
                //     format: 'HH:mm:ss'
                //  });
            
             $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });
             
             //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });

             //Post module form to server using ajax (ADD)
            $('#add_notes').on('click', function() {
                //console.log('strUrl');
                var strUrl = '/contacts/company/addnotes';
                var modalID = 'add-new-note-modal';
                var objData = {
                    originator_type: $('#'+modalID).find('input:checked[name = originator_type]').val(),
                    rensponse_type: $('#'+modalID).find('input:checked[name = rensponse_type]').val(),
                    company_id: $('#'+modalID).find('#company_id').val(),
                    hr_person_id: $('#'+modalID).find('#hr_person_id').val(),
                    employee_id: $('#'+modalID).find('#employee_id').val(),
                    date: $('#'+modalID).find('#date').val(),
                    time: $('#'+modalID).find('#time').val(),
                    communication_method: $('#'+modalID).find('#communication_method').val(),
                    // rensponse_type: $('#'+modalID).find('input:checked[name = rensponse_type]').val(),
                    notes: $('#'+modalID).find('#notes').val(),
                    next_action: $('#'+modalID).find('#next_action').val(),
                    follow_date: $('#'+modalID).find('#follow_date').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'cat_module';
                var redirectUrl = '/contacts/company/{{ $company->id}}/notes';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The group has been updated successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });



            //Post module form to server using ajax (ADD)
            $('#save_category').on('click', function() {
                //console.log('strUrl');
                var strUrl = '/Product/categories';
                var modalID = 'add-category-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    description: $('#'+modalID).find('#description').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'cat_module';
                var redirectUrl = '/product/Categories';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The group has been updated successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

              var doc_typeID;
            $('#edit-note-modal').on('show.bs.modal', function (e) {
                    //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                doc_typeID = btnEdit.data('id');
                var name = btnEdit.data('name');
                var description = btnEdit.data('description');
                //var employeeName = btnEdit.data('employeename');
                var modal = $(this);
                modal.find('#name').val(name);
                modal.find('#description').val(description);

             });
            $('#edit_category').on('click', function () {
                var strUrl = '/Product/category_edit/' + doc_typeID;
                // Product/category_edit/{Category}
                var modalID = 'edit-note-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    description: $('#'+modalID).find('#description').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'save_category';
                var redirectUrl = '/product/Categories';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'Category modal has been updated successfully.';
                var Method = 'PATCH';
         modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });

        });
    </script>
@endsection
