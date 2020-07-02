@extends('layouts.main_layout')

@section('page_dependencies')
 <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
           <div class="col-md-5 col-md-offset-2">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                 <i class="fa fa-address-card-o pull-right">{{$name}} {{$surname}}</i>
                    <h3 class="box-title">Busines Card</h3>
                </div>
                 <form class="form-horizontal" method="POST" action="/hr/print_card" target="_blank">
           {{ csrf_field() }}
            
                <div class="box-body">
                  <div id="content">
                  @foreach($person as $person)
             <div class="row">                         
                     <div class='A' id="none" data-panel_type="none">
                       <img align="left" src="{{ (!empty($person->profile_pic)) ? Storage::disk('local')->url("avatars/$person->profile_pic") : (($person->gender === 0) ? $f_silhouette : $m_silhouette) }}"class="img-responsive" alt="Cinque Terre" width="150" "pull-right">

                    </div>
                        <div class='B' id="none" data-panel_type="none">
                        <li class="list-group-item"><b>First Name</b> <span class="pull-right">  {{ !empty($person->first_name) ? $person->first_name : '' }} </span> </li>
                                      
                        <li class="list-group-item"><b>Surname</b><span class="pull-right"> {{ !empty($person->surname) ? $person->surname : '' }} </span></li>
                                      
                        <li class="list-group-item"> <b>Email</b> <span class="pull-right"> {{ !empty($person->email) ? $person->email : '' }}  </span> </li>                   
                                  
                        <li class="list-group-item"> <b>Cell number</b> <span class="pull-right">{{ !empty($person->cell_number) ? $person->cell_number : '' }} </span> </li> </div>     
                 
                         <div class='C' id="none" data-panel_type="none"> <img src="{{ $company_logo }}" width="40%" class="img-responsive "  alt="Company Logo" > <span class="pull-left"> {{ $website }}</span></div>       
                                     
                         <div class='D' id="none" data-panel_type="none"> <img class="pull-left" src="{{ $company_logos }}" width="80%" class="img-responsive" alt="Company Logo"><span class="pull-left"><a href="https://www.afrixcel.co.za">Need a Business Card ?</a> </span></div>  
                            </div>             
                         @endforeach
                    
                   </div>
                   <div class="box-footer">
                   <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print Card</button>
                 <!--   <button id="back_to_user_search" class="btn btn-primary pull-left"><i class="fa fa-share"></i> Send to Email</button> -->
                   
                    <button type="button" id="add_new_email" class="btn btn-primary pull-left" data-toggle="modal" data-target="#add-new-email-modal"><i class="fa fa-share"></i> Send to Email</button>
                        </div>
                    </div>
                </form>
              </div>
            </div>
            <!-- /.box -->
        </div>
          @include('hr.partials.add_new_email')
     
    </div>
@endsection

@section('page_script')
<script src="/custom_components/js/modal_ajax_submit.js"></script>

    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>

    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
<!-- Select2 -->

 <!-- bootstrap datepicker -->
@section('page_script')
    <script type="text/javascript">
    //
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

              $('#enter_email').on('click', function () {
            var strUrl = '/hr/emial';
            var objData = {
                email: $('#add-new-email-modal').find('#email').val()
                , _token: $('#add-new-leave-modal').find('input[name=_token]').val()
            };
            var modalID = 'add-new-email-modal';
            var submitBtnID = 'add_new_email';
            var redirectUrl = '/leave/types';
            var successMsgTitle = 'Changes Saved!';
            var successMsg = 'Email successefuly sent.';
            modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
        });



    </script>
@endsection