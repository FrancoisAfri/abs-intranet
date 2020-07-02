@extends('layouts.main_layout')
@section('content')
<div class="row">
    <form class="form-horizontal" method="post" action="{{!empty($dmsSetup->id) ? '/dms/setup/'.$dmsSetup->id : '/dms/setup/'  }}">
        {{ csrf_field() }}
		<div class="col-sm-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">DMS Set Up</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
				   <table class="table table-bordered">
					   <div class="form-group">
							<tr>
							<td style="width: 10px"></td>
							   <td>Root Directory</td>
								<td >
									<label for="path" class="control-label"></label>
									<input type="text" class="form-control" id="root_directory" name="root_directory" value="{{ !empty($dmsSetup->root_directory) ? $dmsSetup->root_directory : '' }}" placeholder="Enter Root Directory" >
								</td>
							</tr>
						</div>
						<div class="form-group">
							<tr>
							  <td style="width: 10px"></td>
								<td >Use Remote Server</td>
								<td style="text-align: center; vertical-align: middle;">
									<input type="checkbox" name="use_remote_server" value="1" {{ !empty($dmsSetup->use_remote_server) && $dmsSetup->use_remote_server === 1 ?  'checked ="checked"' : 0 }}>
								</td>
							</tr>
						</div>
						<div class="form-group">
							<tr>
								<td style="width: 10px"></td>
								<td>Remote FTP Url</td>
								<td>
									<label for="path" class="control-label"></label>
									<input type="text" class="form-control" id="use_remote_ftp_url" name="use_remote_ftp_url" value="{{ !empty($dmsSetup->use_remote_ftp_url) ? $dmsSetup->use_remote_ftp_url : '' }}" placeholder="Enter Remote FTP Url">
								</td>
							</tr>
						</div>
						<div class="form-group">
							<tr>
								<td style="width: 10px"></td>
								<td>Remote FTP Username</td>
								<td>
									<input type="text" class="form-control" id="use_remote_ftp_username" name="use_remote_ftp_username" value="{{ !empty($dmsSetup->use_remote_ftp_username) ? $dmsSetup->use_remote_ftp_username : '' }}" placeholder="Enter Remote FTP Username">
								</td>
							</tr>
						</div>
						<div class="form-group">
							<tr>
								<td style="width: 10px"></td>
								<td>Remote FTP Password</td>
								<td>
									<input type="password" class="form-control" id="use_remote_ftp_password" name="use_remote_ftp_password" value="{{ !empty($dmsSetup->use_remote_ftp_password) ? $dmsSetup->use_remote_ftp_password : '' }}" placeholder="Enter Remote FTP Password">
								</td>
							</tr>
						</div>
					</table>
				</div>
				<!-- /.box-body -->
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary"><i class="fa fa-database"></i> Save</button> 
				</div>
			</div>
		</div>
	</form>
</div>
@endsection
<!-- Ajax form submit -->
@section('page_script')
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<script>
    function postData(id, data) {
        //if (data == 'actdeac') location.href = "/leave/types/activate" + id;
        if (data == 'ribbons') location.href = "/leave/ribbons/" + id;
        else if (data == 'edit') location.href = "/leave/leave_edit/" + id;
        else if (data == 'actdeac') location.href = "/leave/setup/" + id; //leave_type_edit
        //  else if (data == 'cu_actdeac') location.href = "/leave/custom/leave_type_edit/" + id;
        //		 	else if (data == 'access')
        //		 		location.href = "/leave/module_access/" + id;
    }
    $(function () {
        var moduleId;
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
     
        var leavesetupId;
        $('#edit-leave_taken-modal').on('show.bs.modal', function (e) {
            //console.log('kjhsjs');
            var btnEdit = $(e.relatedTarget);
            leavesetupId = btnEdit.data('id');
            console.log('leavesetupID: ' + leavesetupId);
            var name = btnEdit.data('name');
            var day5min = btnEdit.data('day5min');
            var day5max = btnEdit.data('day5max');
            var day6min = btnEdit.data('day6min');
            var day6max = btnEdit.data('day6max');
            var shiftmin = btnEdit.data('shiftmin');
            var shiftmax = btnEdit.data('shiftmax');
    
            // var moduleFontAwesome = btnEdit.data('font_awesome');
            var modal = $(this);
            modal.find('#name').val(name);
            modal.find('#day5min').val(day5min);
            modal.find('#day5max').val(day5max);
            modal.find('#day6min').val(day6min);
            modal.find('#day6max').val(day6max);
            modal.find('#shiftmin').val(shiftmin);
            modal.find('#shiftmax').val(shiftmax);
            //if(primeRate != null && primeRate != '' && primeRate > 0) {
            //    modal.find('#prime_rate').val(primeRate.toFixed(2));
            //}
        });
        // pass module data to the custom leave  -edit module modal
        //****leave type post
        $('#update-leave_taken').on('click', function () {
            var strUrl = '/leave/setup/leave_type_edit/' + leavesetupId;
            var objData = {
                  day5min: $('#edit-leave_taken-modal').find('#day5min').val()
                , day5max: $('#edit-leave_taken-modal').find('#day5max').val()
                , day6min: $('#edit-leave_taken-modal').find('#day6min').val()
                , day6max: $('#edit-leave_taken-modal').find('#day6max').val()
                , shiftmin: $('#edit-leave_taken-modal').find('#shiftmin').val()
                , shiftmax: $('#edit-leave_taken-modal').find('#shiftmax').val()
                , _token: $('#edit-leave_taken-modal').find('input[name=_token]').val()
            };
            //console.log('gets here ' + JSON.stringify(objData));
            var modalID = 'edit-leave_taken-modal';
            var submitBtnID = 'update-leave_taken';
            var redirectUrl = '/leave/setup';
            var successMsgTitle = 'Changes Saved!';
            var successMsg = 'Leave days has been successfully added.';
             // var method = 'PATCH';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });                        // ----edit setup leave days ------
    });

//#leave cresdit settings 
 $('#save_leave_credit').on('click', function () {
            var strUrl = '/leave/custom/add_leave';
            var objData = {
                  hr_id: $('#add-custom-leave-modal').find('#hr_id').val()
                , number_of_days: $('#add-custom-leave-modal').find('#number_of_days').val()
                , _token: $('#add-custom-leave-modal').find('input[name=_token]').val()
            };
            var modalID = 'add-custom-leave-modal';
            var submitBtnID = 'add_custom_leave';
            var redirectUrl = '/leave/types';
            var successMsgTitle = 'Changes Saved!';
            var successMsg = 'Leave has been successfully added.';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });

                //UPDATE

      var updateNegativeID;
        $('#edit-annual-modal').on('show.bs.modal', function (e) {
            var btnEdit = $(e.relatedTarget);

            updateNegativeID = btnEdit.data('id');
            var number_of_days_annual = btnEdit.data('number_of_days_annual');
            //console.log(number_of_days_annual);
             var modal = $(this);
             modal.find('#number_of_days_annual').val(number_of_days_annual);

        });

         var updateSickID;
        $('#edit-sick-modal').on('show.bs.modal', function (e) {
            var btnEdit = $(e.relatedTarget);

            updateSickID = btnEdit.data('id');
            var number_of_days_sick = btnEdit.data('number_of_days_sick');
           // console.log(number_of_days_sick);
             var modal = $(this);
             modal.find('#number_of_days_sick').val(number_of_days_sick);

        });
  
            //SAVE

                 $('#update_annual').on('click', function () {
            var strUrl = '/leave/setup/' + '1';
            var objData = {
                  number_of_days_annual: $('#edit-annual-modal').find('#number_of_days_annual').val()
                , _token: $('#edit-annual-modal').find('input[name=_token]').val()
            };
            var modalID = 'edit-annual-modal';
            var submitBtnID = 'edit_annual';
            var redirectUrl = '/leave/setup';
            var successMsgTitle = 'Changes Saved!';
            var successMsg = 'Leave has been successfully added.';
            var formMethod = 'PATCH';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, formMethod);
        });

          $('#update-sick').on('click', function () {
            var strUrl = '/leave/setup/' + '1/' + 'sick';
            var objData = {
                  number_of_days_sick: $('#edit-sick-modal').find('#number_of_days_sick').val()
                , _token: $('#edit-sick-modal').find('input[name=_token]').val()
            };
            var modalID = 'edit-sick-modal';
            var submitBtnID = 'edit_sick';
            var redirectUrl = '/leave/setup';
            var successMsgTitle = 'Changes Saved!';
            var successMsg = 'Leave has been successfully added.';
            var formMethod = 'PATCH';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, formMethod);
        });

         
       

</script>

@endsection
