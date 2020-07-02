@extends('layouts.main_layout')

    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
<!--  -->
@section('page_dependencies')
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-anchor pull-right"></i>
                    <h3 class="box-title"> Documents Search criteria</h3>
                    <p>Enter search Criteria:</p>
                </div>
                         <form name="leave-application-form" class="form-horizontal" method="POST" action=" " enctype="multipart/form-data">
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
                          <div class="form-group{{ $errors->has('application_type') ? ' has-error' : '' }}">
                                <label for="Leave_type" class="col-sm-2 control-label"> Report Type</label>

                                <div class="col-sm-9">
                                    <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_levTkn" name="application_type" value="1" checked> Employee Document </label>
                                    <label class="radio-inline"><input type="radio" id="rdo_bal" name="application_type" value="2"> Employee Qualification</label>
                                     <label class="radio-inline"><input type="radio" id="rdo_po" name="application_type" value="3">  Employee Search</label>
                                </div>
                            </div>


                           <div class="box-body">
                       <div class="form-group">
                        @foreach($division_levels as $division_level)
                            <div class="form-group {{ $errors->has('division_level_' . $division_level->level) ? ' has-error' : '' }}">
                                <label for="{{ 'division_level_' . $division_level->level }}" class="col-sm-2 control-label">{{ $division_level->name }}</label>

                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-black-tie"></i>
                                        </div>
                                        <select id="{{ 'division_level_' . $division_level->level }}" name="{{ 'division_level_' . $division_level->level }}" class="form-control" onchange="divDDOnChange(this)">
                                        </select>
                                    </div>
                                </div>
                            </div>
                          @endforeach  
                           
                          <div class="form-group user-field{{ $errors->has('hr_person_id') ? ' has-error' : '' }}">
                            <label for="hr_person_id" class="col-sm-2 control-label">Employees</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user-circle"></i>
                                    </div>
                                    <select class="form-control select2" style="width: 100%;" id="employe_name" name="employe_name">
                                        <option value="">*** Select an Employee ***</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--  -->
               
                        <!--  -->
                        <div class="form-group emp-field">
                            <label for="person_name" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="user_name" name="user_name" value="{{ old('user_name') }}" placeholder="Search by name...">
                                </div>
                            </div>
                        </div>
                        <div class="form-group emp-field">
                            <label for="id_number" class="col-sm-2 control-label">ID Number</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="number" class="form-control" id="id_number" name="id_number" value="{{ old('id_number') }}" placeholder="Search by ID number...">
                                </div>
                            </div>
                        </div>
                        <div class="form-group emp-field">
                            <label for="passport_number" class="col-sm-2 control-label">Passport Number</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="passport_number" name="passport_number" value="{{ old('passport_number') }}" placeholder="Search by passport number...">
                                </div>
                            </div>
                        </div>
                          <div class="form-group emp-field">
                            <label for="passport_number" class="col-sm-2 control-label">Employee Number</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user-circle-o"></i>
                                    </div>
                                    <input type="text" class="form-control" id="employee_number" name="employee_number" value="{{ old('employee_number') }}" placeholder="Search by employee number...">
                                </div>
                            </div>
                        </div>
                        <!--  -->
                         <div class="form-group qul-field {{ $errors->has('qualification_id') ? ' has-error' : '' }}">
                            <label for="qualification_id" class="col-sm-2 control-label">Qualification Type</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user-circle"></i>
                                    </div>
                                    <select class="form-control select2" style="width: 100%;" id="qualification_id" name="qualification_type">
                                        <option value="">*** Select a Qualification Type ***</option>
                                        @foreach($QulificationType as $qualification)
                                            <option value="{{ $qualification->id }}">{{ $qualification->name  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                          <div class="form-group doc-field {{ $errors->has('document_id') ? ' has-error' : '' }}">
                            <label for="document_id" class="col-sm-2 control-label">Document Type</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user-circle"></i>
                                    </div>
                                    <select class="form-control select2" style="width: 100%;" id="document_id" name="document_type">
                                        <option value="">*** Select a Document Type ***</option>
                                        @foreach($DocType as $documentType)
                                            <option value="{{ $documentType->id }}">{{ $documentType->name  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                      
                        
                   <!--  -->
                   
                     </div>
                   <!--  -->
                     <div class="box-footer">
                       <!--  <button type="button" id="cancel" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Cancel</button> -->
                       <button type="submit" id="gen-report" name="gen-report" class="btn btn-primary pull-right"><i class="fa fa-check"></i>Search</button>
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
        
<!--        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>-->
<!--    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->
        
   <script type="text/javascript">
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
            //Cancel button click event

            // $('#cancel').click(function () {
            //     location.href = '/leave/reports';
            // });
             function postData(id, data) {
        alert(id);
         //if (data == 'approval_id') location.href = "/leave/approval/" + id;
            }
             //Phone mask
            $("[data-mask]").inputmask();

            //Date picker
          
        
            //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
                hideFields();
                //Date Range picker
        $('.daterangepicker').daterangepicker({
            format: 'DD/MM/YYYY',
            endDate: '-1d',
            autoclose: true
        });
            //show/hide fields on radio button toggles (depending on registration type)

            $('#rdo_levTkn, #rdo_bal ,#rdo_po').on('ifChecked', function(){      
                var allType = hideFields();
                if (allType == 1) $('#box-subtitle').html('Employee Document ');
                else if (allType == 2) $('#box-subtitle').html('Employee Qualification ');
                else if (allType == 3) $('#box-subtitle').html('Employee Search');
               
            });
         
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

            //Show success action modal
            $('#success-action-modal').modal('show');
        });      
    
        //function to hide/show fields depending on the allocation  type
        function hideFields() {
            var allType = $("input[name='application_type']:checked").val();
            if (allType == 1) { //adjsut leave
                 //$('.hours-field').hide();
                 $('.doc-field').show();
                 $('.qul-field').hide();
                 $('.emp-field').hide();     
                 $('form[name="leave-application-form"]').attr('action', '/hr/emp_doc/Search');
                 $('#gen-report').val("Submit");        
            }
            else if (allType == 2) { //resert leave
                 $('.doc-field').hide();
                 $('.qul-field').show();
                 $('.emp-field').hide();
                 $('form[name="leave-application-form"]').attr('action', '/hr/emp_qual/Search');
                 //$('form[name="leave-application-form"]').attr('action', '/leave/print/bal');
                 $('#gen-report').val("Submit"); 
            }
             else if(allType == 3){
                  $('.doc-field').hide();
                  $('.qul-field').hide();
                  $('.emp-field').show();
                  $('.user-field').hide();                
                  $('form[name="leave-application-form"]').attr('action', '/hr/emp_search/Search');
                   $('#gen-report').val("Submit"); 
            }

            return allType;      
            }
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
      
        
    </script>
@endsection