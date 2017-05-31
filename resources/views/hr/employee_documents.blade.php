@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
<!-- iCheck -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
<!-- bootstrap file input -->
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row">
        <!-- User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">Employee Documents</h3>
                    <p>Employee Documents details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/hr/emp_document" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    <div class="box-body">
                       <div class="form-group">
                                <label for="leave_profile" class="col-sm-2 control-label">Category</label>

                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-black-tie"></i>
                                        </div>
                                <select id="category_id" name="category_id" class="form-control select2"  style="width: 100%;" >
                                    <option selected="selected" value="" >*** Select a Category ***</option>
                                    @foreach($category as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                    </div>
                                </div>
                            </div>
							
							 <div class="form-group">
                        <label for="action" class="col-sm-2 control-label">Employee Name</label>
                         <div class="col-sm-10">
                           <div class="input-group">
                                <div class="input-group-addon">
                                   <i class="fa fa-user"></i>
                                    </div>
                             <select id="manager_id" name="manager_id" class="form-control select2"  style="width: 100%;" >
                                <option selected="selected" value="" >*** Select a Employee ***</option>
                                    @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->surname }}</option>
                                    @endforeach
                            </select>
                                </div>
                             </div>
						</div>
						
					 <div class="form-group">
                        <label for="action" class="col-sm-2 control-label">Document Type</label>

                         <div class="col-sm-10">
                           <div class="input-group">
                                <div class="input-group-addon">
                                   <i class="fa fa-user"></i>
                                    </div>
                             <select id="category_id" name="category_id" class="form-control select2"  style="width: 100%;" >
                                <option selected="selected" value="" >*** Select a Document Type ***</option>
                                    @foreach($document as $document)
                                    <option value="{{ $document->id }}">{{ $document->name }}</option>
                                    @endforeach
                            </select>
                                </div>
                             </div>
                         </div>  
                        <!-- <div class="form-group">
                            <label for="doc_description" class="col-sm-2 control-label">Document Description</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-black-tie"></i>
                                    </div>
                                    <input type="text" class="form-control" id="doc_description" name="doc_description" value="{{ old('doc_description') }}"  placeholder="Driver's licence, ID, etc..." data-mask>
                                </div>
                            </div>
                        </div> -->
						<!-- -->
						<div class="form-group notes-field{{ $errors->has('description') ? ' has-error' : '' }}">
                           <label for="days" class="col-sm-2 control-label">Document Description</label>
                            <div class="col-sm-10">
                               <div class="input-group">
                                    <div class="input-group-addon">
                                       <i class="fa fa-sticky-note"></i>
                                    </div>
								
                                    <textarea class="form-control" id="doc_description" name="doc_description" placeholder="Driver's licence, ID, etc..." rows="4">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
						
                    <!--  -->
                          @foreach($division_levels as $division_level)
                            <div class="form-group manual-field{{ $errors->has('division_level_' . $division_level->level) ? ' has-error' : '' }}">
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
						
						  <div class="form-group day-field {{ $errors->has('leave_types_id') ? ' has-error' : '' }}">
                             <label for="days" class="col-sm-2 control-label">Expiry Date</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
<!--                                    <input type="text" class="form-control pull-right" id="reservation">-->
                                    <input type="text" class="form-control pull-left" name="expirydate" value=""  />
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
						
						
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer" style="text-align: center;">
                       <button type="button" id="cancel" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Cancel</button>
                      

                        <input type="submit" id="emp_documents" name="load-allocation" class="btn btn-primary pull-right" value="Submit">
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->

        <!-- Password Modal form-->
        @if (isset($user_profile) && $user_profile === 1)
            @include('security.partials.change_my_password')
        @elseif (isset($view_by_admin) && $view_by_admin === 1)
            @include('security.partials.change_password')
        @endif
        <!-- /.Password Modal form-->

        <!-- Confirmation Modal -->
        @if(Session('success_edit'))
            @include('contacts.partials.success_action', ['modal_title' => "User's Details Updated!", 'modal_content' => session('success_edit')])
        @endif
    </div>
@endsection

@section('page_script')
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

    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>

    <script>
        $(function () {
            //Cancel button click event
            // document.getElementById("cancel").onclick = function () {
            //     location.href ;
            // };
				 changetextbox();
            $('#cancel').click(function () {
                location.href = '/hr/emp_document';
            });
				//
				 //Initialise Date picker picker elements
            $('input[name="expirydate"]').datepicker({              
                   format: 'dd/mm/yyyy', 
				   autoclose: true        
            });
				//
           
          

            //Phone mask
            $("[data-mask]").inputmask();

            // [bootstrap file input] initialize with defaults
            $("#input-1").fileinput();
            // with plugin options
            //$("#input-id").fileinput({'showUpload':false, 'previewFileType':'any'});

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

            //Show success action modal
            $('#success-action-modal').modal('show');


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
      
        });
		
    </script>
@endsection