@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Latecomer Leave Deduction</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <form class="form-horizontal" method="POST" action="/hr/firstlevel">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered"> 
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
                                <th>Number of Times</th>
                                <th>Percentage (%)</th>
                                <th style="width: 5px; text-align: center;"></th>
                            </tr>
            
                            @foreach ($appraisal_setup as $type)
                                <tr>
                                    <td style=" text-align: center;" nowrap>
                                        <button type="button" id="edit_compan" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-latecomer-modal" data-id="{{ $type->id }}" data-number_of_times="{{ $type->number_of_times }}" data-percentage="{{$type->percentage}}" ><i class="fa fa-pencil-square-o"></i> Edit</button>
                                     
                                    </td>
                                    <td>{{ $type->number_of_times }}</td>
                                    <td>{{ $type->percentage }}</td>
                                    <td>
                                        
                                          <!--   <button type="button" id="view_ribbons" class="btn 11111111111111111111111{{ (!empty($type->active) && $type->active == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$type->id}}) , 'dactive';"><i class="fa {{ (!empty($type->active) && $type->active == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($type->active) && $type->active == 1) ? "De-Activate" : "Activate"}}</button> -->
                                    <button type="button" id="view_ribbons" class="btn {{ (!empty($type->active) && $type->active == 1) ? " btn-danger " : "btn-success " }}
                                      btn-xs" onclick="postData({{$type->id}}, 'dactive');"><i class="fa {{ (!empty($type->active) && $type->active == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($type->active) && $type->active == 1) ? "De-Activate" : "Activate"}}</button>
                                      
                                    </td>
                                </tr>    
                            @endforeach
                        </table>
                    </div>
         
                        <!-- /.box-body -->
                    <div class="box-footer">
                     <button type="button" id="late_modal" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-latecomer-modal">Add </button>  
                    </div>
        </div>

        <!-- Include add new prime rate modal ---->
        @include('appraisals.partials.add_latecomer_modal')
        @include('appraisals.partials.edit_latecomer_modal')
  
  
  
    </div>
@endsection

@section('page_script')
<!-- Ajax form submit -->
<script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script>
        function postData(id, data)
        {
          if (data == 'dactive') location.href = "/appraisals/latecomers/"  + id + '/activate';
             
            //location.href = "/hr/firstlevel/dactive/" + id;
             // if (data == 'ribbons') location.href = "/hr/ribbons/" + id;

    
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
              

             var latecomerID;
           $('#edit-latecomer-modal').on('show.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                latecomerID = btnEdit.data('id');
                var number_of_times = btnEdit.data('number_of_times');
                var percentage = btnEdit.data('percentage');
                //var level = btnEdit.data('level');
                var modal = $(this);
               // modal.find('#group_level_title').html('Edit Employee Group Level '+ level);
                modal.find('#number_of_times').val(number_of_times);
                modal.find('#percentage').val(percentage);
            });


        
            //Post module form to server using ajax (ADD)
            $('#save_latecomer').on('click', function() {
                var strUrl = '/appraisal/add';
                var modalID = 'add-latecomer-modal';
                var objData = {
                    number_of_times: $('#'+modalID).find('#number_of_times').val(),
                    percentage: $('#'+modalID).find('#percentage').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'save_latecomer';
                var redirectUrl = '/appraisal/setup';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The group level has been updated successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

       $('#update_latecomer').on('click', function () {
                var strUrl = '/appraisal/latecomers/'+ latecomerID;
                var modalID = 'edit-latecomer-modal';
                var objData = {
                    number_of_times: $('#'+modalID).find('#number_of_times').val(),
                    percentage: $('#'+modalID).find('#percentage').val(),
                     _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'update_latecomer';
                var redirectUrl = '/appraisal/setup';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'Company modal has been updated successfully.';
                var Method = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });
    });

 
           


    </script>
@endsection
