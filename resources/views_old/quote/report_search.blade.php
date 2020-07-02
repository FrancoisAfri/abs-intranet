@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    <!-- DataTables -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
    
    <!-- Include Date Range Picker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css"
          rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <form class="form-horizontal" name="quote-report-form" method="POST" action="/quote/report-history">
                    {{ csrf_field() }}
                    <div class="box-header with-border" align="center">
                        <h3 class="box-title">Search Quote</h3>
                    </div>
                    <!-- /.box-header -->
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

                        <div class="form-group{{ $errors->has('application_type') ? ' has-error' : '' }}">
                            <label for="Leave_type" class="col-sm-2 control-label"> Quote Type</label>

                            <div class="col-sm-9">
                                <!--<label class="radio-inline rdo-iCheck" style="padding-left: 0px;"><input type="radio" id="rdo_products" name="quote_type" value="1" checked> Quotation </label>
                                <label class="radio-inline rdo-iCheck"><input type="radio" id="rdo_services" name="quote_type" value="2"> Invoices </label>
                                --><label class="radio-inline rdo-iCheck"><input type="radio" id="rdo_services" name="quote_type" value="3" checked> History </label>
                            </div>
                        </div>
                        
                       <div class="form-group day-field {{ $errors->has('action_date') ? ' has-error' : '' }}">
                                        <label for="action_date" class="col-sm-2 control-label">Action Date</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control daterangepicker" id="action_date"
                                                       name="action_date" value="" placeholder="Select Action Date...">

                                            </div>
                                        </div>
                                    </div>
                        
                         <hr class="hr-text" data-content="SELECT A DIVISION ">
                         
                        <div class="form-group{{ $errors->has('division_id') ? ' has-error' : '' }}">
                            <label for="{{ 'division_id' }}" class="col-sm-2 control-label">{{ $highestLvl->name }}</label>

                            <div class="col-sm-10">
                                <select id="division_id" name="division_id" class="form-control select2" style="width: 100%;">
                                    <option value="">*** Please Select a {{ $highestLvl->name }} ***</option>
                                    @if($highestLvl->divisionLevelGroup)
                                        @foreach($highestLvl->divisionLevelGroup as $division)
                                            <option value="{{ $division->id }}" {{ ($division->id == old('division_id')) ? 'selected' : '' }}>{{ $division->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <hr class="hr-text" data-content="SELECT A CLIENT">

                        <div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
                            <label for="{{ 'company_id' }}" class="col-sm-2 control-label">Client Company</label>

                            <div class="col-sm-10">
                                <select id="company_id" name="company_id" class="form-control select2" style="width: 100%;" onchange="contactCompanyDDOnChange(this)">
                                    <option value="">*** Please Select a Company ***</option>
                                    <option value="0">[Individual Clients]</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ ($company->id == old('company_id')) ? 'selected' : '' }}>{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('contact_person_id') ? ' has-error' : '' }}">
                            <label for="{{ 'contact_person_id' }}" class="col-sm-2 control-label">Contact Person</label>

                            <div class="col-sm-10">
                                <select id="contact_person_id" name="contact_person_id" class="form-control select2" style="width: 100%;">
                                    <option value="">*** Please Select a Company First ***</option>
                                </select>
                            </div>
                        </div>

                        <hr class="hr-text -field" data-content="">

                        <!--<div class="form-group services-field{{ $errors->has('status') ? ' has-error' : '' }}">
                            <label for="{{ 'status' }}" class="col-sm-2 control-label">Quote Statuses</label>
                            <div class="col-sm-10">
                                <select id="status" name="status" class="form-control select2" style="width: 100%;">
                                  
                                    <option value="1"> *** converted into invoices  *** </option>
                                    <option value="2"> *** Not converted into invoices *** </option>
                                    
                                </select>
                            </div>
                        </div>-->
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right">Genarate Report <i class="fa fa-arrow-right"></i></button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
        </div>

        <!-- Include modal -->
        @if(Session('changes_saved'))
            @include('contacts.partials.success_action', ['modal_title' => "Users Access Updated!", 'modal_content' => session('changes_saved')])
        @endif
    </div>
@endsection

@section('page_script')
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- DataTables -->
    <script src="/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>

    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Bootstrap date picker -->
    <script src="/bower_components/AdminLTE/plugins/daterangepicker/moment.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
            $('.services-field').hide();
            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();

            //Initialize the data table
            $('#terms-conditions-table').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": false,
                "autoWidth": true
            });

            //Initialize iCheck/iRadio Elements
            $('.rdo-iCheck').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
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

            //show / hide fields
            hideFields();

            $('#rdo_products, #rdo_services').on('ifChecked', function(){
                var allType = hideFields();
            });

            //Show success action modal
            @if(Session('changes_saved'))
                $('#success-action-modal').modal('show');
            @endif
        });
        
        
        //Date Range picker
        $('.daterangepicker').daterangepicker({
            format: 'DD/MM/YYYY',
            endDate: '-1d',
            autoclose: true
        });

        //function to hide/show fields depending on the quote  type
        function hideFields() {
            var quoteType = $("input[name='quote_type']:checked").val();
            if (quoteType == 1) {
                $('.products-field').show();
                $('.packages-field').show();
                $('.services-field').hide();
				$('form[name="quote-report-form"]').attr('action', '/quote/searchreports');
                 $('#gen-report').val("Submit"); 
            }
            else if (quoteType == 2) {
                $('.products-field').hide();
                $('.packages-field').hide();
                $('.services-field').show();
				$('form[name="quote-report-form"]').attr('action', '/quote/searchreports');
                 $('#gen-report').val("Submit"); 
            }
			else if (quoteType == 3) {
                $('.products-field').show();
                $('.packages-field').show();
                $('.services-field').hide();
				$('form[name="quote-report-form"]').attr('action', '/quote/report-history');
                 $('#gen-report').val("Submit"); 
            }
            return quoteType;
        }
    </script>
@endsection
