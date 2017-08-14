@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="row">
        <!-- New User Form -->
        <div class="col-md-8 col-md-offset-2">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">Search criteria</h3>
                    <p>Enter search details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" id="report_form" method="POST" action="/helpdesk/search_results">
                    {{ csrf_field() }}

                    <div class="box-body">
                        <div class="form-group">
                            <label for="Status" class="col-sm-2 control-label"> Ticket Status</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="status" name="status" placeholder="Enter Ticket status...">
                                </div>
                            </div>
                        </div>
                        <!--  -->
                        <div class="form-group">
                            <label for="ticket_no" class="col-sm-2 control-label">Ticket Number</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-ticket"></i>
                                    </div>
                                    <input type="number" class="form-control" id="ticket_no" name="ticket_no" placeholder="Enter ticket no...">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="ticket_date" class="col-sm-2 control-label">Ticket Date</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control daterangepicker" id="ticket_date" name="ticket_date" value="" placeholder="Select Ticket Date...">
                                </div>
                            </div>
                        </div>
                        <!--  -->
                         <div class="form-group {{ $errors->has('helpdesk_id') ? ' has-error' : '' }}">
                            <label for="helpdesk_id" class="col-sm-2 control-label">Help Desk</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-desktop"></i>
                                    </div>
                                    <select class="form-control select2" style="width: 100%;" id="helpdesk_id" name="helpdesk_id">
                                        <option value="">*** Select a Help Desk ***</option>
                                        @foreach($helpdesk as $help_desk)
                                            <option value="{{ $help_desk->id }}">{{ $help_desk->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                   <!--        <div class="row emp-field" style="display: block;">
                                <div class="col-xs-6">
                                    <div class="form-group from-field {{ $errors->has('date_from') ? ' has-error' : '' }}">
                                        <label for="date_from" class="col-sm-4 control-label">Date From</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control" id="date_from" name="date_from" value="{{ old('date_from') }}" placeholder="Select Month...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group to-field {{ $errors->has('date_to') ? ' has-error' : '' }}">
                                        <label for="date_to" class="col-sm-3 control-label">Date To</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control" id="date_to" name="date_to" value="{{ old('date_to') }}" placeholder="Select Month...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->


                     </div>   
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-user-plus"></i> Search</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        @if(Session('success_delete'))
            @include('contacts.partials.success_action', ['modal_title' => "Induction Deleted!", 'modal_content' => session('success_delete')])
        @endif
        <!-- End new User Form-->
    </div>
    @endsection

    @section('page_script')
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
        //Cancel button click event
        /*document.getElementById("cancel").onclick = function () {
            location.href = "/contacts";
        };*/
         $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        //Date Range picker
        $('.daterangepicker').daterangepicker({
            format: 'dd/mm/yyyy',
            endDate: '-1d',
            autoclose: true
        });

         //Date picker
            $('#date_from').datepicker({
                format: 'MM yyyy',
                autoclose: true,
                startView: "months",
                minViewMode: "months",
                todayHighlight: true
            });

            $('#date_to').datepicker({
                format: 'MM yyyy',
                autoclose: true,
                startView: "months",
                minViewMode: "months",
                todayHighlight: true
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
        //Phone mask
        $("[data-mask]").inputmask();
    </script>
@endsection