@extends('layouts.main_layout')

@section('page_dependencies')
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- iCheck -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
<!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />
<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,400i,500,700" rel="stylesheet"/>
<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
<!-- Include Required Prerequisites -->
<script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />
 
<!-- Include Date Range Picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment-with-locales.min.js"></script>

<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
<!-- -->
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-anchor pull-right"></i>
                    <h3 class="box-title">Leave Application</h3>
                    <p id="box-subtitle">leave Application</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

<!--                    <form name="leave-alloccation-form" class="form-horizontal" method="POST" action="" enctype="multipart/form-data">-->
                         <form name="leave-application-form" class="form-horizontal" method="POST" action="/leave/application" nctype="multipart/form-data">
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
                         <div class="form-group {{ $errors->has('hr_person_id') ? ' has-error' : '' }}">
                            <label for="hr_person_id" class="col-sm-2 control-label">Employees</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user-circle"></i>
                                    </div>
                                    <select class="form-control select2" style="width: 100%;" id="hr_person_id" name="hr_person_id[]">
                                        <option value="">*** Select an Employee ***</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                            <div class="form-group{{ $errors->has('application_type') ? ' has-error' : '' }}">
                                <label for="Leave_type" class="col-sm-2 control-label"> Action</label>

                                <div class="col-sm-9">
                                    <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="rdo_days" name="application_type" value="1" checked> Days </label>
                                    <label class="radio-inline"><input type="radio" id="rdo_hours" name="application_type" value="2">  Hours</label>

                                </div>
                            </div>

<!--                        <div class="form-group ">-->
                           <div class="form-group {{ $errors->has('leave_types_id') ? ' has-error' : '' }}">
                                <label for="leave_types_id" class="col-sm-2 control-label">Leave Types</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-black-tie"></i>
                                        </div>
                                        <select name="leave_type" class="form-control">
                                            <option value="">*** Select leave Type ***</option> 
                                                @foreach($leaveTypes as $leaveType)
                                                    <option value="{{ $leaveType->id }}">{{ $leaveType->name }}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>                 
                      
                        <div class="form-group {{ $errors->has('leave_types_id') ? ' has-error' : '' }} ">
                            <label for="days" class="col-sm-2 control-label">Available/Taken:</label>
                            <div class="col-sm-10">
                                <div class="input-group">
<!--
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar-plus-o"></i>
                                    </div>
-->

<!--                                    <input type="text" class="form-control" id="adjust_days" name="adjust_days" value="{{ old('updated_at') }}" placeholder="Enter number of days" >-->
                                </div>
                            </div>
                        </div>
                        
                      <div class="form-group day-field {{ $errors->has('leave_types_id') ? ' has-error' : '' }}">
                            <label for="days" class="col-sm-2 control-label">Day</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
<!--                                    <input type="text" class="form-control pull-right" id="reservation">-->
                                    <input type="text" class="form-control pull-left" name="daterange" value=" " />
                                    
                                </div>
                            </div>
                        </div>
                        <form>

                         <div class="form-group hours-field {{ $errors->has('leave_types_id') ? ' has-error' : '' }}">
                          <div class="row">
                            <label for="days" class="col-sm-2 control-label">Hours</label>
                           
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                   
<!--                                    <input type="text" class="form-control pull-right" name="daterange&time" value="01/01/2015  " />-->
                                    <input type="text" class="form-control pull-left" name="birthdate" value="10/24/2017" />
                                </div>
                            </div>
                        
                         <div class="form-group hours-field {{ $errors->has('leave_types_id') ? ' has-error' : '' }}">
                            <label for="days" class="col-sm-2 control-label">Hours</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                   
<!--                                    <input type="text" class="form-control pull-right" name="daterange&time" value="01/01/2015  " />-->
                                    <input type="text" class="form-control pull-left" name="birthdate" value="10/24/2017" />
                                </div>
                            </div>
                        </div>
                        </div>
                        </form>
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                           <label for="days" class="col-sm-2 control-label">Notes</label>
                            <div class="col-sm-10">
                               <div class="input-group">
                                    <div class="input-group-addon">
                                       <i class="fa fa-sticky-note"></i>
                                    </div>
                                    <textarea class="form-control" id="description" name="description" placeholder="Enter a Brief Description of the leave Application..." rows="4">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('supporting_doc') ? ' has-error' : '' }}">
                        <label for="days" class="col-sm-2 control-label">Supporting Document</label>
                            <div class="col-sm-10">
                               <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-upload"></i>
                                    </div>
                                    <input type="file" id="supporting_doc" name="supporting_doc" class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "doc"]' data-show-upload="false">
                                </div>
                            </div>
                        </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" id="cancel" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Cancel</button>
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
        @if(Session('success_add'))
            @include('contacts.partials.success_action', ['modal_title' => "Registration Successful!", 'modal_content' => session('success_add')])
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

    <!-- Start Bootstrap File input -->
    <!-- canvas-to-blob.min.js is only needed if you wish to resize images before upload. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
    <!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
    <script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
    <!-- optionally if you need translation for your language then include locale file as mentioned below
    <script src="/bower_components/bootstrap_fileinput/js/locales/<lang>.js"></script>-->
    <!-- End Bootstrap File input -->

    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
            <!-- Date picker -->
    <script src="/cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
        <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>


    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
        
<!--        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>-->
<!--    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->
        
    <script type="text/javascript">
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
            //Cancel button click event
            $('#cancel').click(function () {
                location.href = '/leave/application';
            });
            
             //Phone mask
            $("[data-mask]").inputmask();

            //Date picker
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
                hideFields();
            //show/hide fields on radio button toggles (depending on registration type)
            $('#rdo_days, #rdo_hours').on('ifChecked', function(){      
                var allType = hideFields();
                if (allType == 1) $('#box-subtitle').html('Days');
                else if (allType == 2) $('#box-subtitle').html('Hours');
//                else if (allType == 3) $('#box-subtitle').html('Allocate leave allocation');
            });
            
//                    $('input[name="daterange"]').daterangepicker();
            $('input[name="daterange"]').daterangepicker(
                    {
                        locale: {
                          format: 'YYYY-MM-DD'
                        },
                        startDate: moment(),
                        endDate: moment()
                    }, 
                    function(start, end, label) {
                        alert("A new leave date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                    });
                
                     $('input[name="birthdate"]').daterangepicker(
                         {
                            singleDatePicker: true,
                            showDropdowns: true
                        });

//            //repopulate projects, year dropdowns when a programme has been changed
//            $('#programme_id').change(function(){
//                var programmeID = $(this).val();
//                populateProjectDD(programmeID);
//                populateYearDD(programmeID);
//            });
//                locales('es');
                $('input[name="birthdate"]').daterangepicker({
                        singleDatePicker: true,
                        timePicker: true,
                    
                        //locale:true,
                        showDropdowns: false
                    }, 
                    function(start, end, label) {
                        var years = moment().diff(start, 'years');
                        alert("You are " + years + " years old.");
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

            //Show success action modal
            $('#success-action-modal').modal('show');
        });
        //function to hide/show fields depending on the allocation  type
        function hideFields() {
            var allType = $("input[name='application_type']:checked").val();
            if (allType == 1) { //adjsut leave
                $('.hours-field').hide();
                $('.day-field').show();
                //$('form[name="leave-alloccation-form"]').attr('action', '/leave/Allocate_leave');
                $('#load-allocation').val("Submit");       
            }
            else if (allType == 2) { //resert leave
//                
                $('.day-field').hide();
                $('.hours-field').show();
               // $('form[name="leave-alloccation-form"]').attr('action', '/leave/Allocate_leave/resert');
                $('#load-allocation').val("Submit");
            }
//            else if (allType == 3) { //allocate leave
////            
//                $('.resert-field, .adjust-field').hide();
//                $('.allocaion-field').show();
//                $('form[name="leave-alloccation-form"]').attr('action', '/leave/Allocate_leave/add');
//                $('#load-allocation').val("Submit");
//            }
            return allType;
            //hideSemesterRow();
        }
        //function to hide/show semester
      

        //Load divisions drop down
      
       
        //function to populate the year drop down
        
    </script>
@endsection