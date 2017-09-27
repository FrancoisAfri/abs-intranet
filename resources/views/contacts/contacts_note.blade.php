@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <form class="form-horizontal" method="POST" action="">
                    {{ csrf_field() }}
                    <div class="box-header with-border">
                        <h3 class="box-title">Notes Details</h3>
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

                        <div style="overflow-x:auto;">
                          <!--   <h4 style="text-align: center; class="box-title">Company Details</h4> -->
                                @if(!empty($notes->id))
                                    <strong>Company Name :</strong> <em>{{ $notes->id }}</em> &nbsp; &nbsp;
                                @endif
                               
                             <!--  <div style="text-align: center; vertical-align: middle;">Company Details</div> -->
                             
                              <p>
                            
                            <p>
                                <table class="table table-striped table-bordered">
                                
                            </table>
                            </p>
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th style="width: 10px"></th>
                                     <th>Client name</th>
                                    <th>Notes</th>
                                    <th>Next Action</th>
                                   <th>Follow Up Date</th>
                                </tr>
                               
                                    <tbody>
                                <!--  -->
                                <!-- loop through the leave application info   -->
                                @if(count($notes) > 0)

                                @endif
                            <ul class="products-list product-list-in-box">
                                @foreach($notes as $notereport)
                               <tr>
                                  <td></td>
                                  <td>{{ !empty($notereport->name) && !empty($notereport->surname) ? $notereport->name.' '.$notereport->surname : '' }}</td>
                                  <td>{{ !empty($notereport->notes) ? $notereport->notes : '' }}</td>
                                  <td>{{ (!empty($notereport->next_action)) ?  $notesStatus[$notereport->next_action] : ''}} </td>
                                  <td>{{ !empty($notereport->follow_date) ? date('d M Y ', $notereport->follow_date) : '' }}</td>
                                  
                                    @endforeach
                                </tbody>      
                              </table>
                            </div>
                         <!--  -->
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer text-center">
                     
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
           
        </div>

        <!-- Include modal -->
       
    </div>
@endsection

@section('page_script')
            <!-- bootstrap datepicker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>

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
    <!-- optionally if you need translation for your language then include locale file as mentioned below -->
    <script src="/bower_components/bootstrap_fileinput/js/locales/<lang>.js"></script>
    <!-- End Bootstrap File input -->
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>

    <script>
        $(function () {
            //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });

            //Initialize date picker
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
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

            //call hideFields method on when the page has loaded
            hideFields();

            //show/hide file upload or manual fields on radio checked
            $('#rdo_once_off_payment, #rdo_recurring_payment').on('ifChecked', function(){
                hideFields();
            });

         
        

      
    </script>
@endsection
