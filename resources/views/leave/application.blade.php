@extends('layouts.main_layout')

@section('page_dependencies')
<!-- Include Date Range Picker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- iCheck -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
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
                    <h3 class="box-title">Leave Application</h3>
                    <p id="box-subtitle">leave Application</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

<!--                    <form name="leave-alloccation-form" class="form-horizontal" method="POST" action="" enctype="multipart/form-data">-->
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
                          <div class="form-group {{ $errors->has('hr_person_id') ? ' has-error' : '' }}">
                            <label for="hr_person_id" class="col-sm-2 control-label">Employees</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user-circle"></i>
                                    </div>
                                    <select class="form-control select2" style="width: 100%;" id="hr_person_id" name="hr_person_id">
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
                                        <select id="leave_type" name="leave_type" onChange= "changetextbox();" class="form-control">
                                            <option value="leavetyes">*** Select leave Type ***</option> 
                                                @foreach($leaveTypes as $leaveType)
                                                    <option value="{{ $leaveType->id }}">{{ $leaveType->name }}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>                 
                            <!-- This is the dropbox which must receive a value from the leave dropbox via jquery -->
                      <!--   <div class="form-group  ">
                            <label for="days" class="col-sm-2 control-label">Available/Taken:</label>
                            <div class="col-sm-5">
                                    <input type="text" id ="availdays" class="form-control pull-left" name="val" value=" " disabled="true">
                            </div>
                        </div> -->
                         <div class="form-group day-field {{ $errors->has('leave_types_id') ? ' has-error' : '' }}">
                            <label for="days" class="col-sm-2 control-label">Available/Taken:</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" id ="availdays" class="form-control pull-left" name="val" value=" " disabled="true">
                                </div>
                            </div>
                        </div>

                        <!--  -->
                           <div class="row emp-field" style="display: block;">
                                <div class="col-xs-6">
                                    <div class="form-group Sick-field {{ $errors->has('date_from') ? ' has-error' : '' }}">
                                        <label for="date_from" class="col-sm-4 control-label">Available Ann Sick Days:</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" id ="negsick" class="form-control pull-left" name="val" value=" {{$negannualDays}}" disabled="true">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                          
                                 <div class="col-xs-6">
                                    <div class="form-group neg-field {{ $errors->has('date_to') ? ' has-error' : '' }}">
                                        <label for="date_to" class="col-sm-3 control-label">Available neg Sick Days:</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                               <input type="text" id ="negannual" class="form-control pull-left" name="val" value=" {{$negsickDays}}" disabled="true">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <!--  -->

                      <div class="form-group day-field {{ $errors->has('leave_types_id') ? ' has-error' : '' }}">
                            <label for="days" class="col-sm-2 control-label">Day</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
<!--                                    <input type="text" class="form-control pull-right" id="reservation">-->
                                    <input type="text" class="form-control pull-left" name="day" value=""  />
                                    
                                </div>
                            </div>
                        </div>
                        
                       
                            <div class="form-group hours-field {{ $errors->has('leave_types_id') ? ' has-error' : '' }}">
                            <label for="days" class="col-sm-2 control-label">Hours</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    
                                    <input type="text" class="form-control pull-left" name="av" value=" " />
                                    
                                </div>
                            </div>
                        </div>
              
                        <div class="form-group notes-field{{ $errors->has('description') ? ' has-error' : '' }}">
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
                        
                        <div class="form-group supDoc-field{{ $errors->has('supporting_docs') ? ' has-error' : '' }}">
                        <label for="days" class="col-sm-2 control-label">Supporting Document</label>
                            <div class="col-sm-10">
                               <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-upload"></i>
                                    </div>
                                    <input type="file" id="supporting_docs" name="supporting_docs" class="file file-loading" data-allowed-file-extensions='["pdf", "docx", "doc"]' data-show-upload="false">
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

    <!-- Date rane picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>

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
             function postData(id, data) {
        alert(id);
         //if (data == 'approval_id') location.href = "/leave/approval/" + id;
            }
             //Phone mask
            $("[data-mask]").inputmask();

            var bal = $('#availdays').val();
            var negDays = $('#negsick').val();
            var negannual = $('#negannual').val();
            var varName = bal ;

            //alert (varName)


            //Initialise date range picker elements
            $('input[name="day"]').daterangepicker({
                timePicker: false,
                //timePickerIncrement: 30,
                locale: {
                    //format: 'MM/DD/YYYY h:mm A'
                    format: 'DD/MM/YYYY'
                },
                "dateLimit": {
                    "days": 3
                },
                autoclose: true
            });
            $('input[name="datetime"]').daterangepicker({
                timePicker: true    ,
                linkedCalendars:true,
//                timePickerIncrement: 30,
                locale: {
                    format: 'DD/MM/YYYY h:mm A'
                },"dateLimit": {
                    "days": 1
                },
            });
            
            $('#hr_person_id , #leave_type').on('change', function(){
              //  console.log('test message');
                var hr_person_id = $('#hr_person_id').val();
                var leave_type = $('#leave_type').val();
                if (hr_person_id > 0 && leave_type >0) {
                    avilabledays(hr_person_id, leave_type,'availdays');
                }
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
       
//      hide notes field if leave type is maternity
            function changetextbox(){
                var levID = document.getElementById("leave_type").value;
                   // alert (levID);
                     if (levID ==  1){
                       $('.neg-field').hide();
                       $('.Sick-field').show(); 
                   }else if(levID == 2,3,4,6,7,8){
                    $('.Sick-field').hide(); 
                    $('.neg-field').hide();
                   }else if(levID ==  5){
                    $('.Sick-field').hide(); 
                    $('.neg-field').show();
                   }
            }

        //function to hide/show fields depending on the allocation  type
        function hideFields() {
            var allType = $("input[name='application_type']:checked").val();
            if (allType == 1) { //adjsut leave
                $('.hours-field').hide();
                $('.day-field').show();
                 $('form[name="leave-application-form"]').attr('action', '/leave/application/day');
                $('#load-allocation').val("Submit");        
            }
            else if (allType == 2) { //resert leave
//                
                $('.day-field').hide();
                $('.hours-field').show();
                $('form[name="leave-application-form"]').attr('action', '/leave/application/hours');
                $('#load-allocation').val("Submit");
            }else
                $('form[name="leave-application-form"]').attr('action', '/leave/application/leavDetails');
//          
            return allType;
           
        }
      // function getEmployeeBal(hr_id, levID, availdaystxt){

      // }
        
    </script>
@endsection