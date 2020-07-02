@extends('layouts.main_layout')

@section('page_dependencies')
	<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/select2/select2.min.css">
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Questions Library</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="overflow-y: scroll;">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 10px"></th>
						<th>{{$LevelFive->name}}</th>
						<th>{{$LevelFour->name}}</th>
						<th>{{$LevelThree->name}}</th>
						<th>{{$LevelTwo->name}}</th>
						<th>{{$LevelOne->name}}</th>
                        <th>Description</th>
                        <th style="width: 40px"></th>
                    </tr>
                    @if (!empty($questions))
                    @foreach($questions as $question)
                    <tr id="modules-list">
                        <td nowrap>
                            <button type="button" id="edit_question" class="btn btn-primary  btn-xs" data-toggle="modal" 
							data-target="#edit-question-modal" data-id="{{ $question->id }}" 
							data-description="{{ $question->description }}" 
							data-division_level_5="{{ $question->division_level_5 }}" 
							data-division_level_4="{{ $question->division_level_4 }}"
							data-division_level_3="{{ $question->division_level_3 }}"
							data-division_level_2="{{ $question->division_level_2 }}"
							data-division_level_1="{{ $question->division_level_1 }}"
							> <i class="fa fa-pencil-square-o">
                                </i> Edit</button>
                        </td>
                        <td>{{ $question->level5name }} </td>
                        <td>{{ $question->level4name }} </td>
                        <td>{{ $question->level3name }} </td>
                        <td>{{ $question->level2name }} </td>
                        <td>{{ $question->level1name }} </td>
                        <td>{{ $question->description }} </td>
                        <td>
                            <!--   leave here  -->
                            <button type="button" id="view_ribbons" class="btn {{ (!empty($question->status) && $question->status == 1) ? " btn-danger " : "btn-success " }}
							  btn-xs" onclick="postData({{$question->id}}, 'actdeac');"><i class="fa {{ (!empty($question->status) && $question->status == 1) ?
							  " fa-times " : "fa-check " }}"></i> {{(!empty($question->status) && $question->status == 1) ? "De-Activate" : "Activate"}}</button>
                        </td>
                    </tr>
                     @endforeach
                 @else
                    <tr id="modules-list">
                        <td colspan="5">
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No task to display, please start by adding a new task to the question. </div>
                        </td>
                    </tr> 
                @endif
            </table>
            </div>
            <!-- /.box-body -->
            <div class="modal-footer">
                <button type="button" id="add-new-question" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-question-modal">Add Question</button>
            </div>
        </div>
    </div>
    <!-- Include add new prime rate modal -->
	@include('survey.partials.add_new_question')
    @include('survey.partials.edit_question')
</div>

  @endsection
<!-- Ajax form submit -->

@section('page_script')
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<!-- Select2 -->
<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
<!-- Ajax dropdown options load -->
<script src="/custom_components/js/load_dropdown_options.js"></script>
<script>
    function postData(id, data) {
       if (data == 'actdeac') location.href = "/survey/question_activate/" + id;
    }
    $(function () {
        //Tooltip
        $('[data-toggle="tooltip"]').tooltip();
        //Vertically center modals on page
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
        //pass module data to the leave type -edit module modal
        var questionId;
        $('#edit-question-modal').on('show.bs.modal', function (e) {
            var btnEdit = $(e.relatedTarget);
            questionId = btnEdit.data('id');
            var dept5 = btnEdit.data('division_level_5');
            var dept4 = btnEdit.data('division_level_4');
            var dept3 = btnEdit.data('division_level_3');
            var dept2 = btnEdit.data('division_level_2');
            var dept1 = btnEdit.data('division_level_1');
            var Description = btnEdit.data('description');
            var modal = $(this);
			modal.find('#description').val(Description);
			modal.find('select#division_level_5').val(dept5).trigger("change");
			modal.find('select#division_level_4').val(dept4).trigger("change");
			modal.find('select#division_level_3').val(dept3).trigger("change");
			modal.find('select#division_level_2').val(dept2).trigger("change");
			modal.find('select#division_level_1').val(dept1).trigger("change");
			//Load divisions drop down
			var parentDDID = '';
			var loadAllDivs = 1;
			var firstDivDDID = null;
			var parentContainer = $('#edit-question-modal');
			@foreach($division_levels as $divisionLevel)
				//Populate drop down on page load
				var ddID = '{{ 'division_level_' . $divisionLevel->level }}';
				var postTo = '{!! route('divisionsdropdown') !!}';
				var selectedOption = '';
				//var divLevel = parseInt('{{ $divisionLevel->level }}');
				var incInactive = -1;
				var loadAll = loadAllDivs;
				@if($loop->first)
					var selectFirstDiv = 1;
					var divHeadSpecific = 1;
					loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo, selectFirstDiv, divHeadSpecific, parentContainer);
					firstDivDDID = ddID;
				@else
					loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo, null, null, parentContainer);
				@endif
				//parentDDID
				parentDDID = ddID;
				loadAllDivs = -1;
			@endforeach
        });
        
        $('#add_questions').on('click', function () {
            var strUrl = '/survey/add_question';
            var objData = {
                division_level_5: $('#add-new-question-modal').find('#division_level_5').val()
                ,division_level_4: $('#add-new-question-modal').find('#division_level_4').val()
                ,division_level_3: $('#add-new-question-modal').find('#division_level_3').val()
                ,division_level_2: $('#add-new-question-modal').find('#division_level_2').val()
                ,division_level_1: $('#add-new-question-modal').find('#division_level_1').val()
                , description: $('#add-new-question-modal').find('#description').val()
                , _token: $('#add-new-question-modal').find('input[name=_token]').val()
            };
            var modalID = 'add-new-question-modal';
            var submitBtnID = 'add_questions';
            var redirectUrl = '/survey/questions';
            var successMsgTitle = 'Changes Saved!';
            var successMsg = 'Question successfully Added.';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });
        $('#update-question').on('click', function () {
			var strUrl = '/survey/question_update/' + questionId;
			var formName = 'edit-question-form';
			var modalID = 'edit-question-modal';
			var submitBtnID = 'update-question';
			var successMsgTitle = 'Changes Saved!';
			var redirectUrl = '/survey/questions';
			var successMsg = 'Question details has been updated successfully.';
			var method = 'PATCH';
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
 @endsection