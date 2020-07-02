@extends('layouts.main_layout')

@section('page_dependencies')
 <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

@endsection
@section('content')
<body onload="window.print();">
<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          <img width="196" height="60" src="{{ $company_logo }}" alt="logo">
       <!--  -->
        </h2>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      <div class="col-sm-8 invoice-col">
       
      </div>
      <!-- /.col -->
    </div>
    <div class="row">
        <div class="col-md-12">
           <div class="col-md-5 col-md-offset-2">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                 <i class="fa fa-address-card-o pull-right">{{$name}} {{$surname}}</i>
                    <h3 class="box-title">Busines Card</h3>
                </div>
                <div class="box-body">
                  <div id="content">
                  @foreach($person as $person)
             <div class="row">                         
                     <div class='A' id="none" data-panel_type="none">
                       <img src="{{ (!empty($person->profile_pic)) ? Storage::disk('local')->url("avatars/$person->profile_pic") : (($person->gender === 0) ? $f_silhouette : $m_silhouette) }}"class="img-responsive" alt="Cinque Terre" width="150" height="100" "pull-right">
                    </div>
                        <div class='B' id="none" data-panel_type="none">
                        <li class="list-group-item"><b>First Name</b> <span class="pull-right">  {{ !empty($person->first_name) ? $person->first_name : '' }} </span> </li>
                                      
                        <li class="list-group-item"><b>Surname</b><span class="pull-right"> {{ !empty($person->surname) ? $person->surname : '' }} </span></li>
                                      
                        <li class="list-group-item"> <b>Email</b> <span class="pull-right"> {{ !empty($person->email) ? $person->email : '' }}  </span> </li>                   
                                  
                        <li class="list-group-item"> <b>Cell number</b> <span class="pull-right">{{ !empty($person->cell_number) ? $person->cell_number : '' }} </span> </li> </div>     
                 
                         <div class='C' id="none" data-panel_type="none"> <img src="{{ $company_logo }}" width="40%" class="img-responsive" alt="Company Logo"> <span class="pull-left"> {{ $website }}</span></div>       
                                     
                         <div class='D' id="none" data-panel_type="none"> <img class="pull-left" src="{{ $company_logos }}" width="80%" class="img-responsive" alt="Company Logo"><span class="pull-left"><a href="https://www.afrixcel.co.za">Need a Business Card ?</a> </span></div>  
                                       
                         @endforeach
                                 </div>
                               </div>
                            </div>
                      </div>
                    </div>
                </div>
            </div>
         </section>
        </div>
    </body>
    
@endsection

@section('page_script')
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<!-- Select2 -->

 <!-- bootstrap datepicker -->
@section('page_script')
    <script type="text/javascript">
    //

    </script>
@endsection