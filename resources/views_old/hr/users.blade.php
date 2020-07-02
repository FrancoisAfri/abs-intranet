@extends('layouts.main_layout')

@section('page_dependencies')
 <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/select2/select2.min.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    @endsection
@section('content')
    <div class="row">
        <div class="col-md-12">

            <!-- HR PEOPLE LIST -->
           <div class="col-md-7 col-md-offset-2">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                 <i class="fa fa-search pull-right"></i>
                    <h3 class="box-title">User</h3>
                    <!--
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                    -->
                </div>
                <!-- /.box-header -->
                <form class="form-horizontal" method="POST" action="/hr/card_active">
                
            {{ csrf_field() }}
                <div class="box-body">
                    <ul class="products-list product-list-in-box">
                        <div align="right">
                           <input type="checkbox" id="checkallaccept" onclick="checkAllboxAccept()"/> All<br />
                         
                           </div>
                        @foreach($persons as $person)
                            <li class="item">
                                <div class="product-img">
                                    <img src="{{ (!empty($person->profile_pic)) ? Storage::disk('local')->url("avatars/$person->profile_pic") : (($person->gender === 0) ? $f_silhouette : $m_silhouette) }}" alt="Profile Picture">
                                </div>
                                <div class="product-info">
                                    <a href="{{ '/users/' . $person->user_id . '/edit' }}" class="product-title">{{ $person->first_name . ' ' . $person->surname }}</a>
                                    <!-- <span class="label {{ ($person->status === 1) ? 'label-success' : 'label-danger' }} pull-right">{{ $status_values[$person->status] }}</span> -->

                                     <span class="chkCheckbox pull-right ">
                                        <input type="hidden" class="checkbox selectall" id="selected_{{ $person->id }}_{{ $person->user_id }}" name="selected_{{ $person->id }}_{{ $person->user_id }}" value="0">

                                        <input type="checkbox" class="checkbox selectall" id="selected_{{ $person->id }}_{{ $person->user_id }}" name="selected_{{ $person->id }}_{{ $person->user_id }}"  value="1"  {{ $person->card_status === 1 ? 'checked ="checked"' : 0 }}> 
                                    </span> 

                            <span class="product-description">
                                @if(!empty($person->email))
                                    <i class="fa fa-envelope-o"></i> {{ $person->email }}
                                @endif
                                @if(!empty($person->position) && count($positions) > 0)
                                    &nbsp; {{ ' | ' }} &nbsp; <i class="fa fa-user-circle"></i> {{ $positions[$person->position] }}
                                @endif

                            </span>
                    </div>
    
 
                            </li>
                        @endforeach
                        <!-- /.item -->
                    </ul>
                </div>
               
                <!-- /.box-body -->
                <div class="box-footer">
                    <button id="back_to_user_search" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back to search</button>
                    <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-check"></i> Submit</button>
                </div>
                <!-- /.box-footer -->
           
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection

@section('page_script')
<script src="/custom_components/js/modal_ajax_submit.js"></script>
<!-- Select2 -->
<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
 <!-- bootstrap datepicker -->
<script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
@section('page_script')
    <script type="text/javascript">
    //
     function postData(id, data) {
      
         if (data == 'actdeac') 
            location.href = "/hr/card_active/" + id; 
    }
   
	//Cancel button click event
	document.getElementById("back_to_user_search").onclick = function () {
		location.href = "/hr/search";
	};


    function toggle(source) {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != source)
            checkboxes[i].checked = source.checked;
    }
}
// 
function checkAllboxAccept()
    {
        if($('#checkallaccept:checked').val() == 'on')
        {
            $('.selectall').prop('checked',true);
        }
        else
        {
            $('.selectall').prop('checked',false);
        }
    }
    </script>
@endsection