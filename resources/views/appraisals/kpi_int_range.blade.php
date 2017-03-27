@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">KPIs ({{$kpi->indicator}})</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div style="overflow-X:auto;">
                        <table class="table table-stripped">
                            <tr>
                                <th style="text-align: center; width: 10px"></th>
                                <th style="text-align: center;">Score (Integer)</th>
                                <th style="text-align: center;">Percentage</th>
                                <th style="text-align: center;"></th>
                            </tr>
                            @if (count($kpi->kpiIntScore) > 0)
                                @foreach($kpi->kpiIntScore as $intScore)
                                    <tr id="numbers-list">
                                        <td style="text-align: center;">
                                            <button type="button" id="edit-score" class="btn btn-primary  btn-xs"
                                                    data-toggle="modal"
                                                    data-target="#edit-int-score-modal"
                                                    data-id="{{ $intScore->id }}"
                                                    data-score="{{ $intScore->score }}"
                                                    data-percentage="{{ $intScore->percentage }}"><i class="fa fa-pencil-square-o"></i> Edit</button>
                                        </td>
                                        <td style="text-align: center;">{{!empty($intScore->score) ? $intScore->score : ''}}</td>
                                        <td style="text-align: center;">{{!empty($intScore->percentage) ? $intScore->percentage . ' %' : ''}}</td>
                                        <td style="text-align: center; width: 10px;">
                                            <button type="button" id="activate-kpi-score" class="btn {{ (!empty($intScore->status) && $intScore->status == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$intScore->id}});"><i class="fa {{ (!empty($intScore->status) && $intScore->status == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($intScore->status) && $intScore->status == 1) ? "De-Activate" : "Activate"}}</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr id="numbers-list">
                                    <td colspan="5">
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            No scores to display, please start by adding a new score.
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Back</button>
                    <button type="button" id="add-new-score" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-int-score-modal"><i class="fa fa-plus-circle"></i> Add Score</button>
                </div>
            </div>
        </div>

        <!-- Include add new modal -->
        @include('appraisals.partials.add_int_score')
        @include('appraisals.partials.edit_int_score')
    </div>
@endsection

@section('page_script')
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>

    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>

    <script>
        function postData(id) {
            location.href = "/appraisal/kpi_from_to/" + id + '/activate';
        }
        $(function () {
            $('#back_button').click(function () {
                location.href = '/appraisal/template/' + '{{ $kpi->kpiTemplate->id }}';
            });
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

            //Post score data to server with ajax (Add Score)
            $('#add-score').on('click', function() {
                var strUrl = '{{ '/appraisal/kpi_from_to/' . $kpi->id . '/add_int_score' }}';
                console.log('post usrl: ' + strUrl);
                var objData = {
                    score: $('#score').val(),
                    percentage: $('#percentage').val(),
                    _token: $('input[name=_token]').val()
                };
                var modalID = 'add-int-score-modal';
                var submitBtnID = 'add-score';
                var redirectUrl = '{{ '/appraisal/kpi_from_to/' . $kpi->id }}';
                var successMsgTitle = 'Score Added!';
                var successMsg = 'The new score has been added successfully.';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            //pass module data to the edit module modal
            var scoreID;
            $('#edit-int-score-modal').on('show.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                scoreID = btnEdit.data('id');
                var score = btnEdit.data('score');
                var percentage = btnEdit.data('percentage');
                var modal = $(this);
                modal.find('#score').val(score);
                modal.find('#percentage').val(percentage);
            });

            //Post score data to server with ajax (Edit Score)
            $('#update-score').on('click', function() {
                var strUrl = '/appraisal/kpi_from_to/' + scoreID;
                var modalID = 'edit-int-score-modal';
                var myModal = $('#'+modalID);
                var objData = {
                    score: myModal.find('#score').val(),
                    percentage: myModal.find('#percentage').val(),
                    _token: myModal.find('input[name=_token]').val()
                };
                var submitBtnID = 'update-score';
                var redirectUrl = '{{ '/appraisal/kpi_from_to/' . $kpi->id }}';
                var successMsgTitle = 'Score Updated!';
                var successMsg = 'The score has been updated successfully.';
                var method = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, method);
            });
        });
    </script>
@endsection