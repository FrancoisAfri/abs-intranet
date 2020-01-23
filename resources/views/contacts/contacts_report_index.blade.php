@extends('layouts.main_layout')
<!--  -->
 <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    <!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- iCheck -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
<!--  -->
@section('page_dependencies')
<!--  -->
<!--Time Charger-->
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-files-o pull-right"></i>
                    <h3 class="box-title">CRM Reports Search criteria</h3>
                    <p>Enter search details:</p>
                </div>
                    <form name="contact-report-form" class="form-horizontal" method="POST" action=" " enctype="multipart/form-data">
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
                        <div class="form-group{{ $errors->has('report_type') ? ' has-error' : '' }}">
                                <label for="Leave_type" class="col-sm-2 control-label"> Report Type</label>
                                <div class="col-sm-9">
                                    <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_levTkn" name="report_type" value="1" checked> Client Note </label>
                                    <label class="radio-inline"><input type="radio" id="rdo_bal" name="report_type" value="2"> Meetings</label>
                                    <label class="radio-inline"><input type="radio" id="rdo_bal" name="report_type" value="3"> Communications</label>
                                    <label class="radio-inline"><input type="radio" id="rdo_bal" name="report_type" value="4"> Documents Expiring</label>   
                                </div>
                        </div>
                        <div class="form-group employee-field {{ $errors->has('hr_person_id') ? ' has-error' : '' }}">
                            <label for="hr_person_id" class="col-sm-2 control-label">Employees</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user-circle"></i>
                                    </div>
                                    <select class="form-control select2" style="width: 100%;" id="hr_person_id" name="hr_person_id" data-placeholder="**Select employee **">
                                        <option value="">*** Select an Employee ***</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                        @endforeach
                                    </select>
                                </div>
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
                        <div class="form-group all-field {{ $errors->has('contact_person_id') ? ' has-error' : '' }}">
                            <label for="{{ 'contact_person_id' }}" class="col-sm-2 control-label">Contact Person</label>

                            <div class="col-sm-10">
                                <select id="contact_person_id" name="contact_person_id" class="form-control select2" style="width: 100%;">
                                    <option value="">*** Please Select a Company First ***</option>
                                </select>
                            </div>
                        </div>
						<div class="form-group doc-field {{ $errors->has('contact_person_id') ? ' has-error' : '' }}">
                            <label for="{{ 'contact_id' }}" class="col-sm-2 control-label">Client</label>

                            <div class="col-sm-10">
                                <select id="contact_id" name="contact_id" class="form-control select2" style="width: 100%;">
                                    <option value="">*** Please Select Contact ***</option>
									@foreach($contacts as $contact)
                                        <option value="{{ $contact->id }}" {{ ($contact->id == old('contact_id')) ? 'selected' : '' }}>{{ $contact->first_name."".$contact->surname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group meetings-field">
                            <div class="col-xs-6">
                                <div class="form-group ">
                                    <label for="date_from" class="col-sm-4 control-label">From</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control" id="date_from" name="date_from"   placeholder="" data-mask>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group ">
                                    <label for="date_to" class="col-sm-3 control-label">To</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control" id="date_to" name="date_to"   placeholder="" data-mask>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="form-group doc-field">
							<label for="doc_type" class="col-sm-2 control-label">Document Type</label>
							<div class="col-sm-10">
								<div class="input-group">
									<select class="form-control select2" style="width: 100%;" id="doc_type" name="doc_type">	
										<option value="">*** Select a Document Type ***</option>
										@foreach($types as $type)
											<option value="{{ $type->id }}">{{ $type->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
                    </div> 
                     <div class="box-footer">
                        <button type="button" id="cancel" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Cancel</button>
                       <button type="submit" id="gen-report" name="gen-report" class="btn btn-primary pull-right"><i class="fa fa-check"></i> Generate Report</button>
                    </div>
                    <!-- /.box-footer -->
                 </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
        <!-- Confirmation Modal -->
        @if(Session('success_add'))
            @include('contacts.partials.success_action', ['modal_title' => "Registration Successful!", 'modal_content' => session('success_add')])
        @endif
    </div>
    @endsection

    @section('page_script')
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

    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
            <!-- Date picker -->
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
      
   <script type="text/javascript">
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
            //Cancel button click event

            $('#cancel').click(function () {
                location.href = '/leave/reports';
            });
             function postData(id, data) {
				 
         //if (data == 'approval_id') location.href = "/leave/approval/" + id;
            }
             //Phone mask
            $("[data-mask]").inputmask();
            //Date picker
			$('input[name="date_from"]').daterangepicker({
				singleDatePicker: true,
				showDropdowns: false,
			});
			$('input[name="date_to"]').daterangepicker({
				singleDatePicker: true,
				showDropdowns: false,
			});
            //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
			hideFields();
			//Date Range picker
            //show/hide fields on radio button toggles (depending on registration type)

            $('#rdo_levTkn, #rdo_bal ,#rdo_po ,#rdo_all,#rdo_levH').on('ifChecked', function(){      
                var allType = hideFields();
               
            });
            // Reposition when a modal is shown
            $('.modal').on('show.bs.modal', reposition);
            // Reposition when the window is resized
            $(window).on('resize', function() {
                $('.modal:visible').each(reposition);
            });

            //Show success action modal
            $('#success-action-modal').modal('show');
        });
        //function to hide/show fields depending on the allocation  type
        function hideFields() {
            var allType = $("input[name='report_type']:checked").val();
            if (allType == 1) { //adjsut leave
                 //$('.hours-field').hide();
                 $('.employee-field').show();
                 $('.all-field').show();
                 $('.meetings-field').hide();
                 $('.doc-field').hide();
                 $('form[name="contact-report-form"]').attr('action', '/contacts/reports/contact_note');
                 $('#gen-report').val("Submit");        
            }
            else if (allType == 2) { //resert leave
                 $('.meetings-field').show();
                 $('.all-field').show();
                 $('.employee-field').hide();
                 $('.doc-field').hide();
                 $('form[name="contact-report-form"]').attr('action', '/contacts/reports/meetings');
                 $('#gen-report').val("Submit"); 
            }
            else if(allType == 3){
                $('.meetings-field').show();
                 $('.employee-field').show();
                 $('.all-field').show();
                 $('.doc-field').hide();
                 $('form[name="contact-report-form"]').attr('action', '/contacts/reports/communication');
                 $('#gen-report').val("Submit"); 
            }
			else if(allType == 4){
                $('.meetings-field').show();
                $('.doc-field').show();
                $('.employee-field').hide();
                $('.all-field').hide();
                $('form[name="contact-report-form"]').attr('action', '/contacts/reports/documents_expired');
                $('#gen-report').val("Submit"); 
            }

            return allType;      
        }
          //Load divisions drop down 
    </script>
@endsection