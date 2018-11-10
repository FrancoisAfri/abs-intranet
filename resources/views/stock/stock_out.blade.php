@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- bootstrap file input -->
   <!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
<!-- bootstrap file input -->
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css"/>

<!-- Include Date Range Picker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- iCheck -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
<!-- Select 2-->
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <form class="form-horizontal" method="POST" action="/stock/takestock">
                    {{ csrf_field() }}
                    <div class="box-header with-border">
                        <h3 class="box-title">Products Stock Out</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-remove"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                                </button>
                                <h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <table id="emp-list-table" class="table table-bordered table-striped table-hover">
                            <thead>
								<tr>
									<th>#</th>
									<th>Product Name</th>
									<th style="vertical-align: middle; text-align: center;">Employee</th>
									<th style="vertical-align: middle; text-align: center;">Vehicle</th>
									<th style="vertical-align: middle; text-align: center;">Stock Available</th>
									<th style="vertical-align: middle; text-align: center;">Enter Number</th>       
								</tr>
                            </thead>
                            <tbody>
                            @foreach($stocks as $stock)
                                <tr>
									<td style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                    <td style="vertical-align: center; width=70px;"
                                        >{{ (!empty( $stock->name)) ?  $stock->name : ''}}</td>
                                    <td style="vertical-align: middle; text-align: center;"
                                        >
                                        <div class="form-group">
                                            <label for="path" class="col-sm-3 control-label"> </label>
                                            <div class="col-sm-18">
                                                <select class="form-control select2" style="width: 100%;"
                                                        id="userid_{{ $stock->id }}" name="userid_{{$stock->id}}">
                                                    <option value="0">*** Select User ***</option>
                                                    @foreach($user as $employee)
                                                        <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="vertical-align: middle; text-align: center;">
                                        <div class="form-group">
                                            <label for="path" class="col-sm-3 control-label"> </label>
                                            <div class="col-sm-15">
                                                <select class="form-control select2" style="width: 100%;"
                                                        id="vehicle_{{ $stock->id }}" name="vehicle_{{$stock->id}}">
                                                    <option value="0">*** Select Vehicle ***</option>
                                                    @foreach($vehicle as $vehicles)
                                                        <option value="{{ $vehicles->id }}">{{ $vehicles->fleet_number . ' ' . $vehicles->vehicle_registration}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="vertical-align: middle; text-align: center;"
                                        nowrap>{{ (!empty( $stock->avalaible_stock)) ?  $stock->avalaible_stock : 0}}</td>
                                    <td style="vertical-align: middle; text-align: center;"
                                        nowrap>
                                        <input type="number" min="0" class="form-control"
                                               id="stock_{{ $stock->id }}_{{$stock->category_id}}}"
                                               name="stock_{{$stock->id}}_{{$stock->category_id}}" value=""
                                               placeholder="Enter Items Number"></td>
                                </tr>
							@endforeach
                            </tbody>
                            <tfoot>
                            <tr>
								<th>#</th>
                                <th>Product Name</th>
                                <th style="vertical-align: middle; text-align: center;">Employee</th>
                                <th style="vertical-align: middle; text-align: center;">Vehicle</th>
                                <th style="vertical-align: middle; text-align: center;">Available Number</th>
                                <th style="vertical-align: middle; text-align: center;">Enter number</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" id="cancel" class="btn btn-default pull-left"><i
                                    class="fa fa-arrow-left"></i>
                            Back
                        </button>
                        <button type="submit" class="btn btn-warning pull-right"> Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page_script')
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <!-- DataTables -->
    <script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>

    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
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
    
      <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <!-- Date rane picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Date Picker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <!--  -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <!-- Bootstrap date picker -->
    <script src="/bower_components/AdminLTE/plugins/daterangepicker/moment.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Start Bootstrap File input -->
    <!-- canvas-to-blob.min.js is only needed if you wish to resize images before upload. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js"
            type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
    <!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
    <script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
    <!--Time Charger-->

    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
    <!-- Date picker -->
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>

    <!--        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>-->
    <!--    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <script>
        function postData(id, data) {
            if (data == 'actdeac') location.href = "/System/add_user_act/" + id;

        }
        $('#cancel').click(function () {
            location.href = '/stock/stock_allocation';
        });
        $(function () {
            var moduleId;
            //Initialize Select2 Elements
            $(".select2").select2();
            $('.vehicle-field').hide();
            //Phone mask
            $("[data-mask]").inputmask();
            $('[data-toggle="tooltip"]').tooltip();
            //Initialize the data table
            $('#emp-list-table').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": false,
                "autoWidth": true
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
            $(window).on('resize', function () {
                $('.modal:visible').each(reposition);
            });

            //Show success action modal
            $('#success-action-modal').modal('show');

            $(".js-example-basic-multiple").select2();

           // Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-yellow',
                radioClass: 'iradio_square-blue',
                increaseArea: '10%' // optional
            });

            $('#rdo_levTkn, #rdo_bal ,#rdo_po ,#rdo_all,#rdo_levH, #rdo_cancelled_leaves').on('ifChecked', function () {
                var allType = hideFields();           
            });
                      
			function hideFields() {
				var allType = $("input[name='application_type']:checked").val();
				if (allType == 1) { //adjsut leave
					$('.vehicle-field').hide();
					$('.user-field').show();
				}
				else if (allType == 2) { //resert leave
					$('.vehicle-field').show();
					$('.user-field').hide();
				}
				return allType;
			}
            $('#add-user').on('click', function () {
                var strUrl = '/System/policy/add_policyUsers';
                var formName = 'add-user-form';
                var modalID = 'add-user-modal';
                var submitBtnID = 'add-user';
                var redirectUrl = '/system/policy/viewUsers';
                var successMsgTitle = 'New Users  Added!';
                var successMsg = 'The policy Users has been updated successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
        });
    </script>
@endsection