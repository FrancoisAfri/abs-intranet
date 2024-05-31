@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- Include Date Range Picker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">

    <link rel="stylesheet" href="../../plugins/timepicker/bootstrap-timepicker.min.css">

    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    <!--Time Charger-->
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-anchor pull-right"></i>
                    <h3 class="box-title">Loan Application</h3>
                    <p id="box-subtitle">Fill in the form</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form name="loan-application-form" class="form-horizontal" method="POST" action="/loan/add_loan" enctype="multipart/form-data">
                    <input type="hidden" name="file_index" id="file_index" value="1"/>
					<input type="hidden" name="total_files" id="total_files" value="1"/>
					{{ csrf_field() }}

                    <div class="box-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                        <label for="meeting_type" class="col-sm-2 control-label">Application Type</label>

                        <div class="col-sm-10">

                            <label class="radio-inline"><input type="radio" id="rdo_store" name="type" value="1"
                                                               > Advance </label>

                            <label class="radio-inline"><input type="radio" id="rdo_store" name="type"
                                                               value="2"> Loan </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="amount" class="col-sm-2 control-label">Amount</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="amount" name="amount" value=""
                                   placeholder="Enter Amount" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reason" class="col-sm-2 control-label">Comment</label>
                        <div class="col-sm-8">
						<textarea type="text" class="form-control" id="reason" name="reason" value=""
                                   placeholder="Enter Comment" required></textarea>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('repayment_month') ? ' has-error' : '' }}">
                        <label for="repayment_month" class="col-sm-2 control-label">Repayment Month</label>
                        <div class="col-sm-8">
                                <input type="number" class="form-control" id="repayment_month" name="repayment_month" value=""
                                   placeholder="Enter the number of month(s) Eg 6." required>
                        </div>
                    </div>
					<div id="tab_10">
						<hr class="hr-text" data-content="DOCUMENTS UPLOAD">
						<div class="row" id="tab_tab">
							<div class="col-sm-6" id="file_row" style="margin-bottom: 15px; display:none">
								<input type="file" id="document" disabled="disabled" class="form-control">
							</div>
							<div class="col-sm-6" style="margin-bottom: 15px;">
								<input type="file" id="document" name="document[1]"
								class="form-control">
							</div>
							<div class="col-sm-6" style="display:none;" id="name_row">
								<input type="text" class="form-control" id="name" name="name"
									   placeholder="File Name or description" disabled="disabled">
							</div>
							<div class="col-sm-6" id="1" name="1" style="margin-bottom: 15px;">
								<input type="text" class="form-control" id="name[1]" name="name[1]"
									   placeholder="File Name or description">
							</div>
						</div>
						<div class="row" id="final_row">
							<div class="col-sm-12">
								<button type="button" class="btn btn-default btn-block btn-flat add_more" onclick="addFile()">
									<i class="fa fa-clone"></i> Add More
								</button>
							</div>
						</div>
                    </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <input type="submit" id="load-allocation" name="load-allocation" class="btn btn-primary pull-right" value="Submit">
                        </div>
                        <!-- /.box-footer -->
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
        <!-- Confirmation Modal -->
        @if(Session('success_application'))
            @include('leave.partials.success_action', ['modal_title' => "Application Successful!", 'modal_content' => session('success_application')])
        @endif
    </div>
@endsection
@section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- bootstrap datepicker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
    <!-- Date rane picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

    <script src="../../plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>

    <script src="../../plugins/timepicker/bootstrap-timepicker.min.js"></script>
    <!-- Date picker
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script type="text/javascript">
        $(function() {

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

            //Show success action modal
            $('#success-action-modal').modal('show');
        });
       
		// clone
			function clone(id, file_index, child_id) {
				var clone = document.getElementById(id).cloneNode(true);
				clone.setAttribute("id", file_index);
				clone.setAttribute("name", file_index);
				clone.style.display = "table-row";
				clone.querySelector('#' + child_id).setAttribute("name", child_id + '[' + file_index + ']');
				clone.querySelector('#' + child_id).disabled = false;
				clone.querySelector('#' + child_id).setAttribute("id", child_id + '[' + file_index + ']');
				return clone;
			}
			function addFile() {
				var table = document.getElementById("tab_tab");
				var file_index = document.getElementById("file_index");
				file_index.value = ++file_index.value;
				var file_clone = clone("file_row", file_index.value, "document");
				var name_clone = clone("name_row", file_index.value, "name");
				var final_row = document.getElementById("final_row").cloneNode(false);
				table.appendChild(file_clone);
				table.appendChild(name_clone);
				table.appendChild(final_row);
				var total_files = document.getElementById("total_files");
				total_files.value = ++total_files.value;
				//change the following using jquery if necessary
				var remove = document.getElementsByName("remove");
				for (var i = 0; i < remove.length; i++)
					remove[i].style.display = "inline";
			}
			
			function removeFile(row_name)
			{
				var row=row_name.parentNode.parentNode.id;
				var rows=document.getElementsByName(row);
				while(rows.length>0)
					rows[0].parentNode.removeChild(rows[0]);
				var total_files = document.getElementById("total_files");
				total_files.value=--total_files.value;
				var remove=document.getElementsByName("remove");
				if(total_files.value == 1)
					remove[1].style.display='none';
			}
    </script>
@endsection