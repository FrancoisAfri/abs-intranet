@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-8 col-md-offset-2">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">New User</h3>
                    <p>Enter employee details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/hr/emp_document">
                    {{ csrf_field() }}

                    <div class="box-body">
                     <div class="form-group">
                        <label for="action" class="col-sm-3 control-label">Category</label>

                         <div class="col-sm-9">
                           <div class="input-group">
                                <div class="input-group-addon">
                                   <i class="fa fa-user"></i>
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
                        <label for="action" class="col-sm-3 control-label">Document Type</label>

                         <div class="col-sm-9">
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
                                <div class="form-group">
                            <label for="doc_description" class="col-sm-3 control-label">Document Description</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-black-tie"></i>
                                    </div>
                                    <input type="text" class="form-control" id="doc_description" name="doc_description" value="{{ old('doc_description') }}"  placeholder="Driver's licence, ID, etc..." data-mask>
                                </div>
                            </div>
                        </div>
                     
                          @foreach($division_levels as $division_level)
                            <div class="form-group manual-field{{ $errors->has('division_level_' . $division_level->level) ? ' has-error' : '' }}">
                                <label for="{{ 'division_level_' . $division_level->level }}" class="col-sm-3 control-label">{{ $division_level->name }}</label>

                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-black-tie"></i>
                                        </div>
                                         <select id="division_level_id" name="division_level_id" class="form-control select2"  style="width: 100%;" >
                                <option selected="selected" value="" >*** Select a {{ $division_level->name }} ***</option>
                                    @foreach($division as $division)
                                    <option value="{{ $division->id }}">{{ $division->name }}</option>
                                    @endforeach
                            </select>
                                    </div>
                                </div>
                            </div>
                              @endforeach 

                             <div class="form-group">
                        <label for="action" class="col-sm-3 control-label">Employee Name</label>

                         <div class="col-sm-9">
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
                            <label for="expiry_date" class="col-sm-3 control-label">Expiry Date</label>

                            <div class="col-sm-9">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
 
                                    <input type="text" class="form-control" id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}"  placeholder="Expiry Date" data-mask>
                                </div>
                            </div>
                        </div>



                               <div class="form-group">
                        <label for="doc" class="col-sm-3 control-label">Document</label>

                            <div class="col-sm-9">
                               <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-upload"></i>
                                    </div>
                                    <input type="file" id="doc" name="doc" class="file file-loading" data-allowed-file-extensions='["doc", "docx", "pdf"]' data-show-upload="false">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button id="cancel" class="btn btn-default"><i class="fa fa-arrow-left"></i> Cancel</button>
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-user-plus"></i> Create</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
    </div>
@endsection

@section('page_script')

    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <script src="./bower_components/jquery-calendar/dist/js/jquery-calendar.min.js"></script>
<script>
$(function() {
  $("#calendar").calendar();
});
</script> 

    <script type="text/javascript">
        //Cancel button click event
        document.getElementById("cancel").onclick = function () {
            location.href = "/users";

        };

        //Phone mask
        $("[data-mask]").inputmask();
    </script>
 <script type="text/javascript">


      
        
    </script>
@endsection